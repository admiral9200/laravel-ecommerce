<?php

namespace App\Http\Controllers;

use AvoRed\Framework\Models\Database\Configuration;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $dbConnectError = false;
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            $dbConnectError = true;
        }
        
        if (false === $dbConnectError) {
            $themeViewPath = Configuration::getConfiguration('active_theme_path');
            $fileViewFinder = View::getFinder();
            $fileViewFinder->prependLocation(base_path($themeViewPath));
        }
    }
}
