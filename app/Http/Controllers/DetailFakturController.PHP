<?php
 
namespace App\Http\Controllers;
 
use App\Models\DetailFaktur;
use App\Models\Faktur;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;
 
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
            'id_faktur'       => 'required|exists:faktur,id_faktur',
            'nama_barang'     => 'required|string|max:100',
            'satuan'          => 'required|string|max:20',
            'harga_satuan'    => 'required|numeric|min:0',
            'quantity'        => 'required|integer|min:1',
            'subtotal_faktur' => 'required|numeric|min:0',
        ]);
 
        try {
            $faktur = DB::transaction(function () use ($request) {
                // 1. Cari apakah nama barang sudah terdaftar
                $barang = Barang::where('nama_barang', 'LIKE', $request->nama_barang)->first();
    
                if (!$barang) {
                    // Jika barang baru -> Simpan ke Master Barang
                    $barang = Barang::create([
                        'id_barang'   => $this->generateBarangId(),
                        'nama_barang' => $request->nama_barang,
                        'satuan'      => $request->satuan,
                        'harga'       => $request->harga_satuan,
                        'disc'        => 0.00,
                    ]);
                } else {
                    $barang->update(['satuan' => $request->satuan]);
                }
    
                // 2. Simpan item ke detail_faktur
                DetailFaktur::create([
                    'id_faktur'       => $request->id_faktur,
                    'id_barang'       => $barang->id_barang,
                    'quantity'        => $request->quantity,
                    'subtotal_faktur' => $request->subtotal_faktur,
                ]);
     
                // 3. Update total di tabel faktur
                $faktur = Faktur::find($request->id_faktur);
                $faktur->total_jumlah_faktur = $faktur->detailFaktur()->sum('subtotal_faktur');
                $faktur->save();
     
                return $faktur;
            });
 
            return redirect()->route('faktur.show', $faktur)
                             ->with('success', 'Item berhasil ditambahkan.');
        } catch (Throwable $e) {
            return back()->with('error', 'Gagal menambahkan item: ' . $e->getMessage());
        }
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
            'id_faktur'       => 'required|exists:faktur,id_faktur',
            'nama_barang'     => 'required|string|max:100',
            'satuan'          => 'required|string|max:20',
            'harga_satuan'    => 'required|numeric|min:0',
            'quantity'        => 'required|integer|min:1',
            'subtotal_faktur' => 'required|numeric|min:0',
        ]);
 
        try {
            $faktur = DB::transaction(function () use ($request, $detailFaktur) {
                // Ambil ID barang lama sebelum data diperbarui
                $idBarangLama = $detailFaktur->id_barang;

                // 1. Cari atau buat barang baru berdasarkan input teks nama yang baru
                $barangBaru = Barang::where('nama_barang', 'LIKE', $request->nama_barang)->first();
    
                if (!$barangBaru) {
                    $barangBaru = Barang::create([
                        'id_barang'   => $this->generateBarangId(),
                        'nama_barang' => $request->nama_barang,
                        'satuan'      => $request->satuan,
                        'harga'       => $request->harga_satuan,
                        'disc'        => 0.00,
                    ]);
                } else {
                    $barangBaru->update(['satuan' => $request->satuan]);
                }
    
                // 2. Perbarui data pada baris detail faktur target ke barang baru
                $detailFaktur->update([
                    'id_barang'       => $barangBaru->id_barang,
                    'quantity'        => $request->quantity,
                    'subtotal_faktur' => $request->subtotal_faktur,
                ]);
     
                // 3. PENGAMANAN: Jika ID barang berubah (artinya nama diganti saat edit), 
                // cek apakah barang lama masih digunakan di detail faktur / detail nota lainnya.
                if ($idBarangLama && $idBarangLama !== $barangBaru->id_barang) {
                    $masihDigunakan = DetailFaktur::where('id_barang', $idBarangLama)->exists() 
                                   || \App\Models\DetailNota::where('id_barang', $idBarangLama)->exists();
                    
                    // Jika sudah tidak ada yang memakai barang lama ini, hapus dari Master Barang
                    if (!$masihDigunakan) {
                        Barang::where('id_barang', $idBarangLama)->delete();
                    }
                }

                // 4. Hitung ulang total jumlah faktur
                $faktur = Faktur::find($request->id_faktur);
                $faktur->total_jumlah_faktur = $faktur->detailFaktur()->sum('subtotal_faktur');
                $faktur->save();
     
                return $faktur;
            });
 
            return redirect()->route('faktur.show', $faktur)
                             ->with('success', 'Detail Faktur updated successfully.');
        } catch (Throwable $e) {
            return back()->with('error', 'Gagal memperbarui item: ' . $e->getMessage());
        }
    }
 
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DetailFaktur $detailFaktur)
    {
        $id_faktur = $detailFaktur->id_faktur;
        $id_barang = $detailFaktur->id_barang; // Ambil ID barang yang akan dihapus
 
        DB::transaction(function () use ($detailFaktur, $id_faktur, $id_barang) {
            // 1. Hapus item detail faktur terlebih dahulu
            $detailFaktur->delete();
 
            // 2. CEK PENGGUNAAN BARANG: Apakah barang ini masih dipakai di detail faktur lain atau detail nota?
            if ($id_barang) {
                $masihDigunakan = DetailFaktur::where('id_barang', $id_barang)->exists() 
                               || \App\Models\DetailNota::where('id_barang', $id_barang)->exists();
                
                // Jika sudah benar-benar tidak digunakan di transaksi mana pun, hapus dari Master Barang
                if (!$masihDigunakan) {
                    Barang::where('id_barang', $id_barang)->delete();
                }
            }

            // 3. Update total di tabel faktur induk
            $faktur = Faktur::find($id_faktur);
            if ($faktur) {
                $faktur->total_jumlah_faktur = $faktur->detailFaktur()->sum('subtotal_faktur');
                $faktur->save();
            }
        });
 
        $faktur = Faktur::find($id_faktur);
 
        return redirect()->route($faktur ? 'faktur.show' : 'faktur.index', $faktur)
                         ->with('success', 'Item berhasil dihapus dan dibersihkan dari Master Barang.');
    }

    /**
     * Generator ID Barang Otomatis (BRG001, BRG002, dst)
     */
    private function generateBarangId(): string
    {
        $last = Barang::where('id_barang', 'like', 'BRG%')
            ->orderByRaw('CAST(SUBSTRING(id_barang, 4) AS UNSIGNED) DESC')
            ->first();

        $nextNumber = 1;
        if ($last && preg_match('/(\d+)$/', $last->id_barang, $match)) {
            $nextNumber = ((int) $match[1]) + 1;
        }

        $newId = 'BRG' . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);

        while (Barang::where('id_barang', $newId)->exists()) {
            $nextNumber++;
            $newId = 'BRG' . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
        }

        return $newId;
    }
}