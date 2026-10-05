<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\Project;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    /**
     * GET / — beranda: Hero, About Me, My Journey, Selected Works, Contact.
     */
    public function index(): View
    {
        return view('public.home', [
            'profile' => Profile::current(), // null -> section About disembunyikan
            'projects' => Project::latest()->get(),
        ]);
    }

    /**
     * GET /projects/{project:slug} — cerita lengkap satu proyek.
     * Slug yang tidak ada otomatis menghasilkan 404 (route model binding).
     */
    public function show(Project $project): View
    {
        // "Proyek berikutnya" = proyek yang lebih lama; bila sudah paling akhir, kembali ke yang terbaru.
        $nextProject = Project::where('id', '<', $project->id)->latest('id')->first()
            ?? Project::whereKeyNot($project->id)->latest('id')->first();

        return view('public.projects.show', [
            'project' => $project,
            'nextProject' => $nextProject,
        ]);
    }
}
