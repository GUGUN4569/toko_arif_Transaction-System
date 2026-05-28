<?php
 
namespace App\Http\Controllers;
 
use App\Models\Pegawai;
use Illuminate\Http\Request;
 
class PegawaiController extends Controller
{
    public function index()
    {
        $pegawais = Pegawai::paginate(10);
        return view('pegawai.index', compact('pegawais'));
    }
 
    public function create()
    {
        return view('pegawai.create');
    }
 
    public function store(Request $request)
    {
        $request->validate([
            'nama_pegawai' => 'required|string|max:100',
        ]);
 
        Pegawai::create($request->all());
        return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil ditambahkan!');
    }
 
    public function show(Pegawai $pegawai)
    {
        return view('pegawai.show', compact('pegawai'));
    }
 
    public function edit(Pegawai $pegawai)
    {
        return view('pegawai.edit', compact('pegawai'));
    }
 
    public function update(Request $request, Pegawai $pegawai)
    {
        $request->validate([
            'nama_pegawai' => 'required|string|max:100',
        ]);
 
        $pegawai->update($request->all());
        return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil diperbarui!');
    }
 
    public function destroy(Pegawai $pegawai)
    {
        $pegawai->delete();
        return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil dihapus!');
    }
}