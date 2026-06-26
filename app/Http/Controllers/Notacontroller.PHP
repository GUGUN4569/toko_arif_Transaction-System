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
            'tanggal_nota' => 'required|date|before_or_equal:today',
            'id_customer'  => 'nullable|exists:customer,id_customer',
            'id_pegawai'   => 'nullable|exists:pegawai,id_pegawai',
            'tanda_terima' => 'nullable|string|max:100',
            'items'        => 'required|array|min:1',
            'items.*.id_barang'   => 'required|exists:barang,id_barang',
            'items.*.banyaknya'   => 'required|integer|min:1',
            'items.*.subtotal_nota' => 'required|numeric|min:0',
        ], [
            'tanggal_nota.before_or_equal' => 'Tanggal nota tidak boleh lebih dari hari ini.',
        ]);
 
        $total = collect($request->items)->sum('subtotal_nota');
 
        $nota = Nota::create([
            'id_nota'           => $this->generateNotaId(),
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
 
        return redirect()->route('nota.show', $nota)->with('success', 'Nota berhasil ditambahkan!');
    }
 
    public function show(Nota $nota)
    {
        $nota->load('customer', 'pegawai', 'detailNota.barang');
        // dd($nota->detailNota);
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
            'tanggal_nota' => 'required|date|before_or_equal:today',
            'id_customer'  => 'nullable|exists:customer,id_customer',
            'id_pegawai'   => 'nullable|exists:pegawai,id_pegawai',
            'tanda_terima' => 'nullable|string|max:100',
            'items'        => 'required|array|min:1',
        ], [
            'tanggal_nota.before_or_equal' => 'Tanggal nota tidak boleh lebih dari hari ini.',
        ]);

 
        $total = collect($request->items)->sum('subtotal_nota');
 
        $nota->update([
            'tanggal_nota'      => $request->tanggal_nota,
            'id_customer'       => $request->id_customer,
            'id_pegawai'        => $request->id_pegawai,
            'tanda_terima'      => $request->tanda_terima,
            'banyaknya'         => $request->banyaknya,
            'total_jumlah_nota' => $total,
        ]);
 
 
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

    // ── Auto-generate ID Nota (format: NTA001, NTA002, dst) ──
    private function generateNotaId(): string
    {
        $last = Nota::orderByDesc('id_nota')->first();

        $nextNumber = 1;
        if ($last && preg_match('/(\d+)$/', $last->id_nota, $match)) {
            $nextNumber = ((int) $match[1]) + 1;
        }

        return 'NTA' . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
    }
}