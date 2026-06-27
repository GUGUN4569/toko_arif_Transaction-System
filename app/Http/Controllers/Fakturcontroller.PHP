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

                // 2. Iterasi / Perulangan item yang diinput mandiri
                foreach ($validated['items'] as $item) {
                    // Cari barang berdasarkan nama (mengabaikan huruf besar/kecil)
                    $barang = Barang::where('nama_barang', $item['nama_barang'])->first();

                    if (!$barang) {
                        // HITUNG HARGA SATUAN AWAL (Subtotal / Quantity)
                        $hargaSatuan = $item['quantity'] > 0 ? ($item['subtotal_faktur'] / $item['quantity']) : 0;

                        // JIKA BARANG BARU -> Generate ID & Buat ke master barang
                        $barang = Barang::create([
                            'id_barang'   => $this->generateBarangId(),
                            'nama_barang' => $item['nama_barang'],
                            'satuan'      => $item['satuan'] ?? null,
                            'harga'       => $hargaSatuan, // Menyimpan taksiran harga modal beli awal
                            'disc'        => 0.00,
                        ]);
                    } else {
                        // JIKA BARANG SUDAH ADA -> Perbarui satuannya jika admin menggantinya di form
                        if (!empty($item['satuan'])) {
                            $barang->update(['satuan' => $item['satuan']]);
                        }
                    }

                    // 3. Simpan ke detail faktur menggunakan id_barang hasil pencarian/pembuatan di atas
                    DetailFaktur::create([
                        'id_faktur'       => $faktur->id_faktur,
                        'id_barang'       => $barang->id_barang,
                        'quantity'        => $item['quantity'],
                        'subtotal_faktur' => $item['subtotal_faktur'],
                    ]);
                }

                return $faktur;
            });
        } catch (Throwable $e) {
            report($e);
            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan faktur. ('.$e->getMessage().')');
        }

        return redirect()
            ->route('faktur.show', $faktur)
            ->with('success', 'Faktur berhasil ditambahkan dan produk otomatis terdaftar!');
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
            'items.*.nama_barang' => 'required|string|max:100',
            'items.*.satuan'      => 'required|string|max:20',
            'items.*.quantity'    => 'required|integer|min:1',
            'items.*.subtotal_faktur' => 'required|numeric|min:0',
        ], [
            'tanggal_faktur.before_or_equal' => 'Tanggal faktur tidak boleh lebih dari hari ini.',
        ]);
 
        $total = collect($request->items)->sum('subtotal_faktur');
 
        try {
            DB::transaction(function () use ($request, $faktur, $total) {
                // 1. Update data header faktur
                $faktur->update([
                    'no_faktur'           => $request->no_faktur,
                    'tanggal_faktur'      => $request->tanggal_faktur,
                    'id_supplier'         => $request->id_supplier,
                    'id_pegawai'          => $request->id_pegawai,
                    'total_jumlah_faktur' => $total,
                ]);
         
                // 2. Hapus detail lama agar digantikan yang baru dari form edit
                $faktur->detailFaktur()->delete();
                
                // 3. Simpan item-item baru hasil editan kasir
                foreach ($request->items as $item) {
                    $barang = Barang::where('nama_barang', $item['nama_barang'])->first();

                    if (!$barang) {
                    $barang = Barang::create([
                        'id_barang'   => $this->generateBarangId(),
                        'nama_barang' => $item['nama_barang'],
                        'satuan'      => $item['satuan'] ?? null,
                        'harga'       => $item['harga_satuan'], // <-- Pastikan ini 'harga_satuan' bukan 'harga_beli'
                        'disc'        => 0.00,
                        'stok'        => $item['quantity'], // Opsional: jika ingin stok di halaman barang langsung terisi
                    ]);
                    } else {
                        $barang->update(['satuan' => $item['satuan']]);
                    }

                    DetailFaktur::create([
                        'id_faktur'       => $faktur->id_faktur,
                        'id_barang'       => $barang->id_barang,
                        'quantity'        => $item['quantity'],
                        'subtotal_faktur' => $item['subtotal_faktur'],
                    ]);
                }
            });
        } catch (Throwable $e) {
            report($e);
            return back()->with('error', 'Gagal memperbarui faktur. ('.$e->getMessage().')');
        }
 
        return redirect()->route('faktur.index')->with('success', 'Faktur berhasil diperbarui!');
    }
 
    public function destroy(Faktur $faktur)
    {
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
 
    public function deleteFile(Faktur $faktur)
    {
        if ($faktur->file_faktur) {
            Storage::disk('public')->delete($faktur->file_faktur);
            $faktur->update(['file_faktur' => null]);
        }
 
        return redirect()->route('faktur.show', $faktur)
            ->with('success', 'File faktur berhasil dihapus.');
    }
 
    public function upload(Request $request, Faktur $faktur)
    {
        $request->validate([
            'file_faktur' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);
 
        if ($faktur->file_faktur) {
            Storage::disk('public')->delete($faktur->file_faktur);
        }
 
        $path = $request->file('file_faktur')->store('faktur', 'public');
        $faktur->update(['file_faktur' => $path]);
 
        return redirect()->route('faktur.show', $faktur)
            ->with('success', 'File faktur berhasil diupload!');
    }

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

        while (Faktur::where('id_faktur', $newId)->exists()) {
            $nextNumber++;
            $newId = 'FKT' . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
        }

        return $newId;
    }

    // ── Tambahan Otomatis Generator ID Barang (Format: BRG001, BRG002, dst) ──
    private function generateBarangId(): string
    {
        $last = Barang::where('id_barang', 'like', 'BRG%')
            ->orderByRaw('CAST(SUBSTRING(id_barang, 4) AS UNSIGNED) DESC')
            ->first();

        $nextNumber = 1;
        if ($last && preg_match('/(\d+)$/', $last->id_barang, $match)) {
            $nextNumber = ((int) $match[1]) + 1;
        }

        $newId = 'BRG' . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);

        while (Barang::where('id_barang', $newId)->exists()) {
            $nextNumber++;
            $newId = 'BRG' . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
        }

        return $newId;
    }
}