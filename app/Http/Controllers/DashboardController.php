<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function DashboardPage():View {
        return view('pages.dashboard.dashboard');
    }
}
