<?php

namespace App\Http\Controllers;

use App\Models\SpektrumFrekuensi;
use App\Models\WebsiteStyle;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('public.home', [
            'services' => SpektrumFrekuensi::serviceCatalog(),
            'cities' => SpektrumFrekuensi::cityCatalog(),
            'websiteStyle' => WebsiteStyle::singleton(),
        ]);
    }
}
