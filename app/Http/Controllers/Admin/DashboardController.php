<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ImportHistory;
use App\Models\SpektrumFrekuensi;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $cityOptions = SpektrumFrekuensi::cityFilterOptions();
        $selectedCity = trim($request->string('city')->toString());
        $selectedCity = $selectedCity !== '' && array_key_exists($selectedCity, $cityOptions)
            ? $selectedCity
            : null;

        return view('admin.dashboard', [
            'stats' => SpektrumFrekuensi::dashboardSummary($selectedCity),
            'cityOptions' => $cityOptions,
            'selectedCity' => $selectedCity,
            'selectedCityLabel' => $selectedCity ? $cityOptions[$selectedCity] : 'Semua Kota',
        ]);
    }

    public function dataManagement(): View
    {
        return view('admin.data-management', [
            'importHistory' => ImportHistory::query()
                ->latest('import_date')
                ->limit(10)
                ->get(),
        ]);
    }
}
