<?php
 
namespace App\Http\Controllers;
 
use App\Models\Faktur;
use App\Models\DetailFaktur;
use App\Models\Supplier;
use App\Models\Pegawai;
use App\Models\Barang;
use App\Http\Requests\StoreFakturRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Throwable;
 
class FakturController extends Controller
{
    public function index()
    {
        $fakturs = Faktur::with('supplier', 'pegawai')->latest('tanggal_faktur')->paginate(10);
        return view('Faktur.Index', compact('fakturs'));
    }
 
    public function create()
    {
        $suppliers = Supplier::all();
        $pegawais  = Pegawai::all();
        $barangs   = Barang::all();
        return view('Faktur.Create', compact('suppliers', 'pegawais', 'barangs'));
    }
 
    public function store(StoreFakturRequest $request)
    {
        // $request sudah otomatis divalidasi oleh StoreFakturRequest
        // sebelum sampai ke method ini (header faktur + items barang).
        $validated = $request->validated();
        $total     = collect($validated['items'])->sum('subtotal_faktur');

        try {
            $faktur = DB::transaction(function () use ($validated, $total) {

                // 1. Simpan header faktur
                $faktur = Faktur::create([
                    'id_faktur'           => $this->generateFakturId(),
                    'no_faktur'           => $validated['no_faktur'],
                    'tanggal_faktur'      => $validated['tanggal_faktur'],
                    'id_supplier'         => $validated['id_supplier'] ?? null,
                    'id_pegawai'          => $validated['id_pegawai'] ?? null,
                    'total_jumlah_faktur' => $total,
                ]);

                // 2. Siapkan payload detail (tanpa id_faktur,
                //    karena createMany() otomatis mengisi FK dari relasi)
                $itemsPayload = array_map(static function (array $item) {
                    return [
                        'id_barang'       => $item['id_barang'],
                        'quantity'        => $item['quantity'],
                        'subtotal_faktur' => $item['subtotal_faktur'],
                    ];
                }, $validated['items']);

                // 3. Simpan semua detail barang sekaligus lewat relasi hasMany
                $faktur->detailFaktur()->createMany($itemsPayload);

                return $faktur;
            });
        } catch (Throwable $e) {
            // Jika ada error di tengah jalan (mis. constraint FK gagal),
            // seluruh insert (header + detail) otomatis di-rollback oleh DB::transaction
            report($e); // catat ke log Laravel

            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan faktur. Silakan coba lagi. ('.$e->getMessage().')');
        }

        return redirect()
            ->route('faktur.show', $faktur)
            ->with('success', 'Faktur berhasil ditambahkan!');
    }
 
    public function show(Faktur $faktur)
    {
        $faktur->load('supplier', 'pegawai', 'detailFaktur.barang');
        $barangs = Barang::all();
        return view('Faktur.Show', compact('faktur', 'barangs'));
    }
 
    public function edit(Faktur $faktur)
    {
        $faktur->load('detailFaktur.barang');
        $suppliers = Supplier::all();
        $pegawais  = Pegawai::all();
        $barangs   = Barang::all();
        return view('Faktur.Edit', compact('faktur', 'suppliers', 'pegawais', 'barangs'));
    }
 
    public function update(Request $request, Faktur $faktur)
    {
        $request->validate([
            'no_faktur'      => 'required|string|max:50',
            'tanggal_faktur' => 'required|date|before_or_equal:today',
            'id_supplier'    => 'nullable|exists:supplier,id_supplier',
            'id_pegawai'     => 'nullable|exists:pegawai,id_pegawai',
            'items'          => 'required|array|min:1',
        ], [
            'tanggal_faktur.before_or_equal' => 'Tanggal faktur tidak boleh lebih dari hari ini.',
        ]);
 
        $total = collect($request->items)->sum('subtotal_faktur');
 
        $faktur->update([
            'no_faktur'           => $request->no_faktur,
            'tanggal_faktur'      => $request->tanggal_faktur,
            'id_supplier'         => $request->id_supplier,
            'id_pegawai'          => $request->id_pegawai,
            'total_jumlah_faktur' => $total,
        ]);
 
        $faktur->detailFaktur()->delete();
        foreach ($request->items as $item) {
            DetailFaktur::create([
                'id_faktur'       => $faktur->id_faktur,
                'id_barang'       => $item['id_barang'],
                'quantity'        => $item['quantity'],
                'subtotal_faktur' => $item['subtotal_faktur'],
            ]);
        }
 
        return redirect()->route('faktur.index')->with('success', 'Faktur berhasil diperbarui!');
    }
 
    public function destroy(Faktur $faktur)
    {
        // Hapus file jika ada
        if ($faktur->file_faktur) {
            Storage::disk('public')->delete($faktur->file_faktur);
        }
        $faktur->detailFaktur()->delete();
        $faktur->delete();
        return redirect()->route('faktur.index')->with('success', 'Faktur berhasil dihapus!');
    }
 
    public function print(Faktur $faktur)
    {
        $faktur->load('supplier', 'pegawai', 'detailFaktur.barang');
        return view('Faktur.print', compact('faktur'));
    }
 
    // ── Hapus file faktur ───────────────────────────────
    public function deleteFile(Faktur $faktur)
    {
        if ($faktur->file_faktur) {
            Storage::disk('public')->delete($faktur->file_faktur);
            $faktur->update(['file_faktur' => null]);
        }
 
        return redirect()->route('faktur.show', $faktur)
            ->with('success', 'File faktur berhasil dihapus.');
    }
 
    // ── Upload file faktur ──────────────────────────────
    public function upload(Request $request, Faktur $faktur)
    {
        $request->validate([
            'file_faktur' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);
 
        // Hapus file lama jika ada
        if ($faktur->file_faktur) {
            Storage::disk('public')->delete($faktur->file_faktur);
        }
 
        $path = $request->file('file_faktur')->store('faktur', 'public');
 
        $faktur->update(['file_faktur' => $path]);
 
        return redirect()->route('faktur.show', $faktur)
            ->with('success', 'File faktur berhasil diupload!');
    }

    // ── Auto-generate ID Faktur (format: FKT001, FKT002, dst) ──
    private function generateFakturId(): string
    {
        $last = Faktur::where('id_faktur', 'like', 'FKT%')
            ->orderByRaw('CAST(SUBSTRING(id_faktur, 4) AS UNSIGNED) DESC')
            ->first();

        $nextNumber = 1;
        if ($last && preg_match('/(\d+)$/', $last->id_faktur, $match)) {
            $nextNumber = ((int) $match[1]) + 1;
        }

        $newId = 'FKT' . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);

        // Pengaman ekstra: kalau ternyata masih bentrok, naikkan terus sampai unik
        while (Faktur::where('id_faktur', $newId)->exists()) {
            $nextNumber++;
            $newId = 'FKT' . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
        }

        return $newId;
    }
}