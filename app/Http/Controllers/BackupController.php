<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
   public function index()
    {
        $files = collect(Storage::files('backup'))
            ->sortDesc()
            ->map(function ($file) {
                return [
                    'name' => basename($file),
                    'size' => round(Storage::size($file) / 1024 / 1024, 2),
                    'date' => date('Y-m-d H:i:s', Storage::lastModified($file))
                ];
            });

        return view('backup.index', compact('files'));
    }

    public function run()
    {
        Artisan::call('backup:mysql');

        return redirect()->route('backup.index')
            ->with('success', 'Backup berhasil dibuat!');
    }

    public function download($file)
{
    $path = "backup/$file";

    if (!Storage::exists($path)) {
        abort(404);
    }

    return Storage::download($path);
}


    public function delete($file)
    {
        Storage::delete("backup/$file");

        return back()->with('success', 'File berhasil dihapus');
    }
}
