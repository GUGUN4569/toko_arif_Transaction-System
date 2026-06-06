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
            'subtotal_nota' => 'required|numeric|min:0',
        ]);
 
        DB::transaction(function () use ($request) {
            DetailNota::create($request->all());
 
            // Update total in nota table
            $nota = Nota::find($request->id_nota);
            $nota->total_jumlah_nota = $nota->detailNota()->sum('subtotal_nota');
            $nota->save();
        });
 
        return redirect()->route('nota.show', $request->id_nota)
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
        $request->validate([
            'id_nota' => 'required|exists:nota,id_nota',
            'id_barang' => 'required|exists:barang,id_barang',
            'banyaknya' => 'required|integer|min:1',
            'subtotal_nota' => 'required|numeric|min:0',
        ]);
 
        DB::transaction(function () use ($request, $detailNota) {
            $detailNota->update($request->all());
 
            // Update total in nota table
            $nota = Nota::find($request->id_nota);
            $nota->total_jumlah_nota = $nota->detailNota()->sum('subtotal_nota');
            $nota->save();
        });
 
        return redirect()->route('detail-nota.index')
                         ->with('success', 'Detail Nota updated successfully.');
    }
 
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DetailNota $detailNota)
    {
        DB::transaction(function () use ($detailNota) {
            $id_nota = $detailNota->id_nota;
            $detailNota->delete();
 
            // Update total in nota table
            $nota = Nota::find($id_nota);
            if ($nota) {
                $nota->total_jumlah_nota = $nota->detailNota()->sum('subtotal_nota');
                $nota->save();
            }
        });
 
        return redirect()->route('nota.show', $detailNota->id_nota)
                         ->with('success', 'Item berhasil dihapus.');
    }
}