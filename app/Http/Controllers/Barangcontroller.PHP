<?php
 
namespace App\Http\Controllers;
 
use App\Models\Barang;
use Illuminate\Http\Request;
 
class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::paginate(15);
        return view('Barang.Index', compact('barangs'));
    }
 
    public function create()
    {
        return view('Barang.Create');
    }
 
    public function store(Request $request)
    {
        $request->validate([
            'id_barang'   => 'required|string|max:10|unique:barang,id_barang',
            'nama_barang' => 'required|string|max:100',
            'harga'       => 'required|numeric|min:0',
            'disc'        => 'nullable|numeric|min:0|max:100',
            'satuan'      => 'nullable|string|max:20',
        ]);
 
        Barang::create($request->all());
 
        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }
 
    public function show(Barang $barang)
    {
        return view('Barang.Show', compact('barang'));
    }
 
    public function edit(Barang $barang)
    {
        return view('Barang.Edit', compact('barang'));
    }
 
    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:100',
            'harga'       => 'required|numeric|min:0',
            'disc'        => 'nullable|numeric|min:0|max:100',
            'satuan'      => 'nullable|string|max:20',
        ]);
 
        $barang->update($request->all());
 
        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui.');
    }
 
    public function destroy(Barang $barang)
    {
        $barang->delete();
        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus.');
    }
}