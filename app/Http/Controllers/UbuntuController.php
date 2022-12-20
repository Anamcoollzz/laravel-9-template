<?php

namespace App\Http\Controllers;

use App\Jobs\EditFileJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class UbuntuController extends Controller
{
    public function index()
    {
        $files  = File::allFiles('/etc/nginx/sites-available');

        $i = 0;
        foreach ($files as $file) {
            File::exists('/etc/nginx/sites-enabled/' . $file->getFilename()) ? $files[$i]->enabled = true : $files[$i]->enabled = false;
            $i++;
        }

        return view('stisla.ubuntu.index', [
            'files' => $files,
        ]);
    }

    public function edit($pathname)
    {
        $pathnameD = decrypt($pathname);
        $file = file_get_contents($pathnameD);

        return view('stisla.ubuntu.form', [
            'file'       => $file,
            'title'      => __('Ubuntu'),
            'fullTitle'  => __('Ubah File'),
            'routeIndex' => route('ubuntu.index'),
            'action'     => route('ubuntu.update', [$pathname]),
            'pathname'   => $pathnameD,
        ]);
    }

    public function update($pathname, Request $request)
    {
        $pathnameD = decrypt($pathname);
        EditFileJob::dispatch($pathnameD, $request->filename);

        return redirect()->back()->with('successMessage', 'Berhasil memperbarui file');
    }
}
