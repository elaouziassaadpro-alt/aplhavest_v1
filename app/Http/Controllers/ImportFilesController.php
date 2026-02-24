<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CNASNU;
use App\Models\ANRF;

class ImportFilesController extends Controller
{
    /**
     * Display the files list (CNASNUS + ANRF).
     */
    public function filesList(Request $request)
    {
        $cnasnusSearch = $request->input('cnasnus_search');
        $anrfSearch    = $request->input('anrf_search');

        $cnasnusQuery = CNASNU::query();
        if ($cnasnusSearch) {
            $cnasnusQuery->where(function ($q) use ($cnasnusSearch) {
                $q->where('firstName',  'like', "%{$cnasnusSearch}%")
                  ->orWhere('secondName',    'like', "%{$cnasnusSearch}%")
                  ->orWhere('originalName',  'like', "%{$cnasnusSearch}%")
                  ->orWhere('dataID',        'like', "%{$cnasnusSearch}%")
                  ->orWhere('nationality',   'like', "%{$cnasnusSearch}%")
                  ->orWhere('country',       'like', "%{$cnasnusSearch}%");
            });
        }
        $cnasnusRecords = $cnasnusQuery->orderByDesc('created_at')->paginate(15, ['*'], 'cnasnus_page');
        $cnasnusTotalFiltered = $cnasnusQuery->toBase()->count();

        $anrfQuery = ANRF::query();
        if ($anrfSearch) {
            $anrfQuery->where(function ($q) use ($anrfSearch) {
                $q->where('nom',         'like', "%{$anrfSearch}%")
                  ->orWhere('identifiant','like', "%{$anrfSearch}%")
                  ->orWhere('pays',       'like', "%{$anrfSearch}%")
                  ->orWhere('activite',   'like', "%{$anrfSearch}%");
            });
        }
        $anrfRecords = $anrfQuery->orderByDesc('created_at')->paginate(15, ['*'], 'anrf_page');
        $anrfTotalFiltered = $anrfQuery->toBase()->count();

        return view('files_list.index', [
            'cnasnusRecords' => $cnasnusRecords,
            'anrfRecords'    => $anrfRecords,
            'cnasnusCount'   => CNASNU::count(),
            'anrfCount'      => ANRF::count(),
            'cnasnusFilteredCount' => $cnasnusTotalFiltered,
            'anrfFilteredCount'    => $anrfTotalFiltered,
        ]);
    }

    /**
     * Display the import files page.
     */
    public function index()
    {
        return view('files_list.import_files');
    }
}
