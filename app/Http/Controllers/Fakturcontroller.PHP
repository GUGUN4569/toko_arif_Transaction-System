<?php
 
namespace App\Http\Controllers;
 
use App\Models\Faktur;
use App\Models\DetailFaktur;
use App\Models\Supplier;
use App\Models\Pegawai;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
 
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
 
    public function store(Request $request)
    {
        $request->validate([
            'no_faktur'      => 'required|string|max:50',
            'tanggal_faktur' => 'required|date|before_or_equal:today',
            'id_supplier'    => 'nullable|exists:supplier,id_supplier',
            'id_pegawai'     => 'nullable|exists:pegawai,id_pegawai',
            'items'          => 'required|array|min:1',
            'items.*.id_barang'       => 'required|exists:barang,id_barang',
            'items.*.quantity'        => 'required|integer|min:1',
            'items.*.subtotal_faktur' => 'required|numeric|min:0',
        ], [
            'tanggal_faktur.before_or_equal' => 'Tanggal faktur tidak boleh lebih dari hari ini.',
        ]);
 
        $total = collect($request->items)->sum('subtotal_faktur');
 
        $faktur = Faktur::create([
            'id_faktur'           => $this->generateFakturId(),
            'no_faktur'           => $request->no_faktur,
            'tanggal_faktur'      => $request->tanggal_faktur,
            'id_supplier'         => $request->id_supplier,
            'id_pegawai'          => $request->id_pegawai,
            'total_jumlah_faktur' => $total,
        ]);
 
        foreach ($request->items as $item) {
            DetailFaktur::create([
                'id_faktur'       => $faktur->id_faktur,
                'id_barang'       => $item['id_barang'],
                'quantity'        => $item['quantity'],
                'subtotal_faktur' => $item['subtotal_faktur'],
            ]);
        }
 
        return redirect()->route('faktur.show', $faktur)->with('success', 'Faktur berhasil ditambahkan!');
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
        $last = Faktur::orderByDesc('id_faktur')->first();

        $nextNumber = 1;
        if ($last && preg_match('/(\d+)$/', $last->id_faktur, $match)) {
            $nextNumber = ((int) $match[1]) + 1;
        }

        return 'FKT' . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
    }
}