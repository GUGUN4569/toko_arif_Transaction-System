<?php
 
namespace App\Http\Controllers;
 
use App\Models\Customer;
use Illuminate\Http\Request;
 
class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::paginate(10);
        return view('customer.index', compact('customers'));
    }
 
    public function create()
    {
        return view('customer.create');
    }
 
    public function store(Request $request)
    {
        $request->validate([
            'id_customer'   => 'required|string|max:10|unique:customer,id_customer',
            'nama_customer' => 'required|string|max:100',
            'kontak'        => 'nullable|string|max:50',
        ]);
 
        Customer::create($request->all());
        return redirect()->route('customer.index')->with('success', 'Customer berhasil ditambahkan!');
    }
 
    public function show(Customer $customer)
    {
        $customer->load('nota');
        return view('customer.show', compact('customer'));
    }
 
    public function edit(Customer $customer)
    {
        return view('customer.edit', compact('customer'));
    }
 
    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'nama_customer' => 'required|string|max:100',
            'kontak'        => 'nullable|string|max:50',
        ]);
 
        $customer->update($request->except('id_customer'));
        return redirect()->route('customer.index')->with('success', 'Customer berhasil diperbarui!');
    }
 
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customer.index')->with('success', 'Customer berhasil dihapus!');
    }
}