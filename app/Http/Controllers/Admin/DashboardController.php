<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * GET /admin — ringkasan konten.
     * Controller "invokable" (hanya punya __invoke) karena halaman ini cuma satu aksi.
     */
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'totalProjects' => Project::count(),
            'latestProjects' => Project::latest()->take(5)->get(),
        ]);
    }
}
