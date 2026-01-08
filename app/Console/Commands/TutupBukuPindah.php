<?php

namespace App\Console\Commands;
use App\Services\TutupBukuPindahService;
use Illuminate\Console\Command;

class TutupBukuPindah extends Command
{
    protected $signature = 'tutupbuku:pindah';
    protected $description = 'Proses tutup buku bulanan otomatis';

    public function handle()
    {
        $tanggal = now()->subMonth()->endOfMonth();

        TutupBukuPindahService::proses($tanggal, 0);

        $this->info('Tutup buku '.$tanggal->format('Y-m').' selesai');
    }
}

