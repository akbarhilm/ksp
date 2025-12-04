<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;

class BackupDatabase extends Command
{
    protected $signature = 'backup:mysql';
    protected $description = 'Backup database MySQL ke storage Laravel';

    public function handle()
    {
        $db = config('database.connections.mysql');

        $filename = 'backup-' . date('Y-m-d_His') . '.sql';
        $path = storage_path('app/backup');
        $file = $path . '/' . $filename;

        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
        // Build command
        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s %s > %s',
            escapeshellarg($db['username']),
            escapeshellarg($db['password']),
            escapeshellarg($db['host']),
            escapeshellarg($db['database']),
            escapeshellarg($file)
        );

        exec($command . ' 2>&1', $output, $returnVar);


        if ($returnVar !== 0) {
            $this->error("Backup gagal!");
            dump($output);
            return 1;
        }
$gzFile = $file . '.gz';
$fp = fopen($file, 'r');
$zp = gzopen($gzFile, 'w9');

while (!feof($fp)) {
    gzwrite($zp, fread($fp, 1024 * 512));
}

fclose($fp);
gzclose($zp);

// Hapus file SQL asli (opsional)
unlink($file);
       $this->info("Backup berhasil (gzip): " . basename($gzFile));
$this->info("Lokasi: storage/app/backup/");
    }
}

