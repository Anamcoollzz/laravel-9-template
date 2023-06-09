<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use App\Services\DatabaseService;
use Illuminate\Support\Str;

class BackupDatabaseController extends Controller
{
    private $databaseService;

    /**
     * constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->databaseService = new DatabaseService;
        $this->middleware('can:Backup Database');
    }

    /**
     * showing backup database page
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $filter_month = $request->query('filter_month') ?? (int) date('m');
        $filter_year = $request->query('filter_year') ?? (int) date('Y');

        $isShowFilter = false;
        $data = $this->databaseService->getAllBackupMysql();
        if (count($data) > 100) {
            $data = $data->filter(function ($item) use ($filter_month, $filter_year) {
                return Str::contains($item['name'], $filter_year . '-' . $filter_month);
            });
            $isShowFilter = true;
        }

        return view('stisla.backup-databases.index', [
            'active'       => 'databases.index',
            'title'        => 'Backup Database',
            'data'         => $data,
            'month'        => $filter_month,
            'year'         => $filter_year,
            'array_bulan'  => array_bulan(),
            'array_year'   => array_year(2019, date('Y')),
            'isShowFilter' => $isShowFilter,
        ]);
    }

    public function create()
    {
        $this->databaseService->backupMysql();
        return redirect()->route('backup-databases.index')->with('successMessage', 'Database berhasil dibackup');
    }

    public function update($filename)
    {
        $this->databaseService->restoreMysql($filename);
        return redirect()->route('backup-databases.index')->with('successMessage', 'Database berhasil direstore menggunakan ' . $filename);
    }

    public function destroy($fileName)
    {
        $this->databaseService->deleteMysql($fileName);
        return redirect()->back()->with('successMessage', 'Database berhasil dihapus');
    }
}
