<?php
 
namespace App\Http\Controllers;
 
use App\Models\Supplier;
use Illuminate\Http\Request;
 
class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::paginate(8);
        return view('Supplier.Index', compact('suppliers'));
    }
 
    public function create()
    {
        return view('Supplier.Create');
    }
 
    public function store(Request $request)
    {
        $request->validate([
            'id_supplier'      => 'required|string|max:10|unique:supplier,id_supplier',
            'nama_supplier'    => 'required|string|max:100',
            'alamat_supplier'  => 'nullable|string',
            'no_telp_supplier' => 'nullable|string|max:20',
        ]);
 
        Supplier::create($request->all());
        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil ditambahkan!');
    }
 
    public function show(Supplier $supplier)
    {
        $supplier->load('faktur');
        return view('Supplier.Show', compact('supplier'));
    }
 
    public function edit(Supplier $supplier)
    {
        return view('Supplier.Edit', compact('supplier'));
    }
 
    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'nama_supplier'    => 'required|string|max:100',
            'alamat_supplier'  => 'nullable|string',
            'no_telp_supplier' => 'nullable|string|max:20',
        ]);
 
        $supplier->update($request->except('id_supplier'));
        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil diperbarui!');
    }
 
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil dihapus!');
    }
}