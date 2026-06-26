<?php
 
namespace App\Http\Controllers;
 
use App\Models\DetailNota;
use App\Models\Nota;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
 
class DetailNotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $details = DetailNota::with('nota', 'barang')->paginate(10);
        return view('detail-nota.index', compact('details'));
    }
 
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $notas = Nota::all();
        $barangs = Barang::all();
        return view('detail-nota.create', compact('notas', 'barangs'));
    }
 
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_nota' => 'required|exists:nota,id_nota',
            'id_barang' => 'required|exists:barang,id_barang',
            'banyaknya' => 'required|integer|min:1',
            'diskon' => 'nullable|numeric|min:0|max:100',
            'subtotal_nota' => 'required|numeric|min:0',
        ]);

        $nota = DB::transaction(function () use ($request) {
            DetailNota::create([
                'id_nota'       => $request->id_nota,
                'id_barang'     => $request->id_barang,
                'banyaknya'     => $request->banyaknya,
                'diskon'        => $request->diskon ?? 0,
                'subtotal_nota' => $request->subtotal_nota,
            ]);

            // Update total in nota table
            $nota = Nota::find($request->id_nota);
            $nota->total_jumlah_nota = $nota->detailNota()->sum('subtotal_nota');
            $nota->save();

            return $nota;
        });

        return redirect()->route('nota.show', $nota)
                         ->with('success', 'Item berhasil ditambahkan.');
    }
 
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DetailNota $detailNota)
    {
        $notas = Nota::all();
        $barangs = Barang::all();
        return view('detail-nota.edit', compact('detailNota', 'notas', 'barangs'));
    }
 
    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, DetailNota $detailNota)
{
    // 1. Validasi input dari form edit item
    $request->validate([
        'id_barang' => 'required',
        'banyaknya' => 'required|integer|min:1',
        'diskon'    => 'nullable|numeric|min:0|max:100',
    ]);

    // 2. Hitung subtotal di sisi server (Backend) agar lebih aman
    $barang = \App\Models\Barang::find($request->id_barang);
    $harga  = $barang->harga ?? 0;
    $diskon = $request->diskon ?? 0;

    // Rumus: (Harga x Qty) dikurangi diskon manual (per item)
    $subtotal = ($harga * $request->banyaknya) * (1 - $diskon / 100);

    // 3. Update data pada baris DetailNota ini saja
    $detailNota->update([
        'id_barang'     => $request->id_barang,
        'banyaknya'     => $request->banyaknya,
        'diskon'        => $diskon,
        'subtotal_nota' => $subtotal,
    ]);

    // 4. Update `total_jumlah_nota` pada tabel induk (Nota)
    $nota = \App\Models\Nota::find($detailNota->id_nota);


    if ($nota) {
        $totalKeseluruhan = $nota->detailNota()->sum('subtotal_nota');

        $nota->update([
            'total_jumlah_nota' => $totalKeseluruhan
        ]);
    }

    // 5. Redirect kembali ke halaman detail nota
    return redirect()->route($nota ? 'nota.show' : 'nota.index', $nota)
                     ->with('success', 'Item nota berhasil diperbarui.');
}
 
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DetailNota $detailNota)
    {
        $id_nota = $detailNota->id_nota;
 
        DB::transaction(function () use ($detailNota, $id_nota) {
            $detailNota->delete();
 
            // Update total in nota table
            $nota = Nota::find($id_nota);
            if ($nota) {
                $nota->total_jumlah_nota = $nota->detailNota()->sum('subtotal_nota');
                $nota->save();
            }
        });
 
        $nota = Nota::find($id_nota);
 
        return redirect()->route($nota ? 'nota.show' : 'nota.index', $nota)
                         ->with('success', 'Item berhasil dihapus.');
    }
}