<?php

namespace App\Console\Commands;
use App\Services\TutupBukuService;
use Illuminate\Console\Command;

class TutupBukuBulanan extends Command
{
    protected $signature = 'tutupbuku:bulanan';
    protected $description = 'Proses tutup buku bulanan otomatis';

    public function handle()
    {
        $tanggal = now()->subMonth()->endOfMonth();

        TutupBukuService::proses($tanggal, 0);

        $this->info('Tutup buku '.$tanggal->format('Y-m').' selesai');
    }
}

