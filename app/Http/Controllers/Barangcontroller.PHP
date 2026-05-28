<?php
 
namespace App\Http\Controllers;
 
use App\Models\Barang;
use Illuminate\Http\Request;
 
class BarangController extends Controller
{
    public function index()
    {
        $barang = Barang::paginate(10);
        return view('barang.index', compact('barang'));
    }
 
    public function create()
    {
        return view('barang.create');
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
        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan!');
    }
 
    public function show(Barang $barang)
    {
        return view('barang.show', compact('barang'));
    }
 
    public function edit(Barang $barang)
    {
        return view('barang.edit', compact('barang'));
    }
 
    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:100',
            'harga'       => 'required|numeric|min:0',
            'disc'        => 'nullable|numeric|min:0|max:100',
            'satuan'      => 'nullable|string|max:20',
        ]);
 
        $barang->update($request->except('id_barang'));
        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui!');
    }
 
    public function destroy(Barang $barang)
    {
        $barang->delete();
        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus!');
    }
}