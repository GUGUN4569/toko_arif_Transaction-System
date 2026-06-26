<?php
 
namespace App\Http\Controllers;
 
use App\Models\DetailFaktur;
use App\Models\Faktur;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
 
class DetailFakturController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $details = DetailFaktur::with('faktur', 'barang')->paginate(10);
        return view('detail-faktur.index', compact('details'));
    }
 
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $fakturs = Faktur::all();
        $barangs = Barang::all();
        return view('detail-faktur.create', compact('fakturs', 'barangs'));
    }
 
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_faktur' => 'required|exists:faktur,id_faktur',
            'id_barang' => 'required|exists:barang,id_barang',
            'quantity' => 'required|integer|min:1',
            'subtotal_faktur' => 'required|numeric|min:0',
        ]);
 
        $faktur = DB::transaction(function () use ($request) {
            DetailFaktur::create($request->all());
 
            // Update total in faktur table
            $faktur = Faktur::find($request->id_faktur);
            $faktur->total_jumlah_faktur = $faktur->detailFaktur()->sum('subtotal_faktur');
            $faktur->save();
 
            return $faktur;
        });
 
        return redirect()->route('faktur.show', $faktur)
                         ->with('success', 'Item berhasil ditambahkan.');
    }
 
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DetailFaktur $detailFaktur)
    {
        $fakturs = Faktur::all();
        $barangs = Barang::all();
        return view('detail-faktur.edit', compact('detailFaktur', 'fakturs', 'barangs'));
    }
 
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DetailFaktur $detailFaktur)
    {
        $request->validate([
            'id_faktur' => 'required|exists:faktur,id_faktur',
            'id_barang' => 'required|exists:barang,id_barang',
            'quantity' => 'required|integer|min:1',
            'subtotal_faktur' => 'required|numeric|min:0',
        ]);
 
        $faktur = DB::transaction(function () use ($request, $detailFaktur) {
            $detailFaktur->update($request->all());
 
            // Update total in faktur table
            $faktur = Faktur::find($request->id_faktur);
            $faktur->total_jumlah_faktur = $faktur->detailFaktur()->sum('subtotal_faktur');
            $faktur->save();
 
            return $faktur;
        });
 
        return redirect()->route('faktur.show', $faktur)
                         ->with('success', 'Detail Faktur updated successfully.');
    }
 
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DetailFaktur $detailFaktur)
    {
        $id_faktur = $detailFaktur->id_faktur;
 
        DB::transaction(function () use ($detailFaktur, $id_faktur) {
            $detailFaktur->delete();
 
            // Update total in faktur table
            $faktur = Faktur::find($id_faktur);
            if ($faktur) {
                $faktur->total_jumlah_faktur = $faktur->detailFaktur()->sum('subtotal_faktur');
                $faktur->save();
            }
        });
 
        $faktur = Faktur::find($id_faktur);
 
        return redirect()->route($faktur ? 'faktur.show' : 'faktur.index', $faktur)
                         ->with('success', 'Item berhasil dihapus.');
    }
}