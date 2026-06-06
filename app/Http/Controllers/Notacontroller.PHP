<?php
 
namespace App\Http\Controllers;
 
use App\Models\Nota;
use App\Models\DetailNota;
use App\Models\Customer;
use App\Models\Pegawai;
use App\Models\Barang;
use Illuminate\Http\Request;
 
class NotaController extends Controller
{
    public function index()
    {
        $notas = Nota::with('customer', 'pegawai')->latest('tanggal_nota')->paginate(10);
        return view('nota.index', compact('notas'));
    }
 
    public function create()
    {
        $customers = Customer::all();
        $pegawais  = Pegawai::all();
        $barangs   = Barang::all();
        return view('nota.create', compact('customers', 'pegawais', 'barangs'));
    }
 
    public function store(Request $request)
    {
        $request->validate([
            'id_nota'      => 'required|string|max:10|unique:nota,id_nota',
            'tanggal_nota' => 'required|date',
            'id_customer'  => 'nullable|exists:customer,id_customer',
            'id_pegawai'   => 'nullable|exists:pegawai,id_pegawai',
            'tanda_terima' => 'nullable|string|max:100',
            'items'        => 'required|array|min:1',
            'items.*.id_barang'   => 'required|exists:barang,id_barang',
            'items.*.banyaknya'   => 'required|integer|min:1',
            'items.*.subtotal_nota' => 'required|numeric|min:0',
        ]);
 
        $total = collect($request->items)->sum('subtotal_nota');
 
        $nota = Nota::create([
            'id_nota'           => $request->id_nota,
            'tanggal_nota'      => $request->tanggal_nota,
            'id_customer'       => $request->id_customer,
            'id_pegawai'        => $request->id_pegawai,
            'tanda_terima'      => $request->tanda_terima,
            'total_jumlah_nota' => $total,
        ]);
 
        foreach ($request->items as $item) {
            DetailNota::create([
                'id_nota'       => $nota->id_nota,
                'id_barang'     => $item['id_barang'],
                'banyaknya'     => $item['banyaknya'],
                'subtotal_nota' => $item['subtotal_nota'],
            ]);
        }
 
        return redirect()->route('nota.index')->with('success', 'Nota berhasil ditambahkan!');
    }
 
    public function show(Nota $nota)
    {
        $nota->load('customer', 'pegawai', 'detailNota.barang');
        $barangs = Barang::all();
        return view('Nota.Show', compact('nota', 'barangs'));
    }
 
    public function edit(Nota $nota)
    {
        $nota->load('detailNota.barang');
        $customers = Customer::all();
        $pegawais  = Pegawai::all();
        $barangs   = Barang::all();
        return view('nota.edit', compact('nota', 'customers', 'pegawais', 'barangs'));
    }
 
    public function update(Request $request, Nota $nota)
    {
        $request->validate([
            'tanggal_nota' => 'required|date',
            'id_customer'  => 'nullable|exists:customer,id_customer',
            'id_pegawai'   => 'nullable|exists:pegawai,id_pegawai',
            'tanda_terima' => 'nullable|string|max:100',
            'items'        => 'required|array|min:1',
        ]);
 
        $total = collect($request->items)->sum('subtotal_nota');
 
        $nota->update([
            'tanggal_nota'      => $request->tanggal_nota,
            'id_customer'       => $request->id_customer,
            'id_pegawai'        => $request->id_pegawai,
            'tanda_terima'      => $request->tanda_terima,
            'total_jumlah_nota' => $total,
        ]);
 
        $nota->detailNota()->delete();
        foreach ($request->items as $item) {
            DetailNota::create([
                'id_nota'       => $nota->id_nota,
                'id_barang'     => $item['id_barang'],
                'banyaknya'     => $item['banyaknya'],
                'subtotal_nota' => $item['subtotal_nota'],
            ]);
        }
 
        return redirect()->route('nota.index')->with('success', 'Nota berhasil diperbarui!');
    }
 
    public function destroy(Nota $nota)
    {
        $nota->detailNota()->delete();
        $nota->delete();
        return redirect()->route('nota.index')->with('success', 'Nota berhasil dihapus!');
    }
 
 
    public function print(Nota $nota)
    {
        $nota->load('customer', 'pegawai', 'detailNota.barang');
        return view('nota.print', compact('nota'));
    }
}