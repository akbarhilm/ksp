<?php

namespace App\Helpers;
use App\Models\Akun;
use App\Models\Jurnal;
use Carbon\Carbon;
use DB;

class JurnalHelper
{
    public static function noJurnal()
    {
       $today = Carbon::now()->format('ymd'); // YYMMDD

        // Cari nomor jurnal terakhir hari ini
        $last = DB::table('tmjurnal')
            ->where('no_jurnal', 'like', $today.'%')
            ->orderBy('no_jurnal', 'desc')
            ->value('no_jurnal');

        if ($last) {
            // Ambil nomor urut terakhir (6 digit di belakang)
            $lastNumber = (int) substr($last, 6);
            $next = $lastNumber + 1;
        } else {
            // Jika belum ada transaksi hari ini
            $next = 1;
        }

        // Pad jadi 6 digit
        $sequence = str_pad($next, 6, '0', STR_PAD_LEFT);

        // Gabungkan
        return $today . $sequence;
    
       
}

}
