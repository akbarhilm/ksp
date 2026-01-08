<?php

namespace App\Console\Commands;
use App\Services\TutupBukuTahunanService;
use Illuminate\Console\Command;

class TutupBukuTahunan extends Command
{
    protected $signature = 'tutupbuku:tahunan';
    protected $description = 'Proses tutup buku bulanan otomatis';

    public function handle()
    {
        $tanggal = now()->subMonth()->endOfMonth();

        TutupBukuTahunanService::proses($tanggal, 0);

        $this->info('Tutup buku '.$tanggal->format('Y-m').' selesai');
    }
}

