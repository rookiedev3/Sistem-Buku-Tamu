<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Storage;

class LaporanController extends Controller
{
    public function downloadFile(string $filename)
{
    $path = 'exports/' . $filename;

    if (!Storage::disk('public')->exists($path)) {
        abort(404, 'File tidak ditemukan');
    }

    return Storage::disk('public')->download($path, $filename, [
        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
    ]);
}
}
