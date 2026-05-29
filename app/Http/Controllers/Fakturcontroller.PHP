<?php
 
namespace App\Http\Controllers;
 
use App\Models\Faktur;
use App\Models\DetailFaktur;
use App\Models\Supplier;
use App\Models\Pegawai;
use App\Models\Barang;
use Illuminate\Http\Request;
 
class FakturController extends Controller
{
    public function index()
    {
        $fakturs = Faktur::with('supplier', 'pegawai')->latest('tanggal_faktur')->paginate(10);
        return view('faktur.index', compact('fakturs'));
    }
 
    public function create()
    {
        $suppliers = Supplier::all();
        $pegawais  = Pegawai::all();
        $barangs   = Barang::all();
        return view('faktur.create', compact('suppliers', 'pegawais', 'barangs'));
    }
 
    public function store(Request $request)
    {
        $request->validate([
            'id_faktur'      => 'required|string|max:10|unique:faktur,id_faktur',
            'no_faktur'      => 'required|string|max:50',
            'tanggal_faktur' => 'required|date',
            'id_supplier'    => 'nullable|exists:supplier,id_supplier',
            'id_pegawai'     => 'nullable|exists:pegawai,id_pegawai',
            'items'          => 'required|array|min:1',
            'items.*.id_barang'       => 'required|exists:barang,id_barang',
            'items.*.quantity'        => 'required|integer|min:1',
            'items.*.subtotal_faktur' => 'required|numeric|min:0',
        ]);
 
        $total = collect($request->items)->sum('subtotal_faktur');
 
        $faktur = Faktur::create([
            'id_faktur'            => $request->id_faktur,
            'no_faktur'            => $request->no_faktur,
            'tanggal_faktur'       => $request->tanggal_faktur,
            'id_supplier'          => $request->id_supplier,
            'id_pegawai'           => $request->id_pegawai,
            'total_jumlah_faktur'  => $total,
        ]);
 
        foreach ($request->items as $item) {
            DetailFaktur::create([
                'id_faktur'       => $faktur->id_faktur,
                'id_barang'       => $item['id_barang'],
                'quantity'        => $item['quantity'],
                'subtotal_faktur' => $item['subtotal_faktur'],
            ]);
        }
 
        return redirect()->route('faktur.index')->with('success', 'Faktur berhasil ditambahkan!');
    }
 
    public function show(Faktur $faktur)
    {
        $faktur->load('supplier', 'pegawai', 'detailFaktur.barang');
        return view('faktur.show', compact('faktur'));
    }
 
    public function edit(Faktur $faktur)
    {
        $faktur->load('detailFaktur.barang');
        $suppliers = Supplier::all();
        $pegawais  = Pegawai::all();
        $barangs   = Barang::all();
        return view('faktur.edit', compact('faktur', 'suppliers', 'pegawais', 'barangs'));
    }
 
    public function update(Request $request, Faktur $faktur)
    {
        $request->validate([
            'no_faktur'      => 'required|string|max:50',
            'tanggal_faktur' => 'required|date',
            'id_supplier'    => 'nullable|exists:supplier,id_supplier',
            'id_pegawai'     => 'nullable|exists:pegawai,id_pegawai',
            'items'          => 'required|array|min:1',
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
        $faktur->detailFaktur()->delete();
        $faktur->delete();
        return redirect()->route('faktur.index')->with('success', 'Faktur berhasil dihapus!');
    }

     public function print(Faktur $faktur)
    {
        $faktur->load('supplier', 'pegawai', 'detailFaktur.barang');
        return view('faktur.print', compact('faktur'));
    }
}