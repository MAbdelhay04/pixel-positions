<?php

namespace App\Http\Controllers;

use App\Services\Dashboard\DashboardFactory;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $dashboard = DashboardFactory::make(Auth::user());

        return $dashboard->render(Auth::user());
    }
}
