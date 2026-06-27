<?php

namespace App\Http\Middleware;

use App\Models\DetailFaktur;
use App\Models\DetailNota;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LockExpiredTransaction
{
    /**
     * Blokir akses edit/update/destroy/tambah-item ke Nota & Faktur
     * yang sudah berumur lebih dari 14 hari. Nota/Faktur lama hanya
     * boleh dilihat (show), tidak boleh diubah lagi.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $nota   = $request->route('nota');
        $faktur = $request->route('faktur');

        // Untuk route detail-nota / detail-faktur, ambil nota/faktur induknya
        $detailNota = $request->route('detail_nota') ?? $request->route('detail-nota');
        if ($detailNota instanceof DetailNota) {
            $nota = $detailNota->nota;
        }

        $detailFaktur = $request->route('detail_faktur') ?? $request->route('detail-faktur');
        if ($detailFaktur instanceof DetailFaktur) {
            $faktur = $detailFaktur->faktur;
        }

        // Untuk store (tambah item baru), id_nota/id_faktur dikirim lewat request body
        if (!$nota && $request->filled('id_nota')) {
            $nota = \App\Models\Nota::find($request->input('id_nota'));
        }
        if (!$faktur && $request->filled('id_faktur')) {
            $faktur = \App\Models\Faktur::find($request->input('id_faktur'));
        }

        if ($nota && method_exists($nota, 'isLocked') && $nota->isLocked()) {
            return back()->with('error', 'Nota ini sudah lebih dari 14 hari dan tidak bisa diubah lagi. Hanya dapat dilihat.');
        }

        if ($faktur && method_exists($faktur, 'isLocked') && $faktur->isLocked()) {
            return back()->with('error', 'Faktur ini sudah lebih dari 14 hari dan tidak bisa diubah lagi. Hanya dapat dilihat.');
        }

        return $next($request);
    }
}