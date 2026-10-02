<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProjectRequest;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /**
     * Folder cover di dalam disk media (Project::mediaDisk()):
     * - lokal  : storage/app/public/projects -> /storage/projects/... (via `php artisan storage:link`)
     * - Vercel : bucket R2/S3 -> projects/...
     */
    private const COVER_DIR = 'projects';

    /**
     * GET /admin/projects
     */
    public function index(): View
    {
        return view('admin.projects.index', [
            'projects' => Project::latest()->paginate(10),
        ]);
    }

    /**
     * GET /admin/projects/create
     */
    public function create(): View
    {
        return view('admin.projects.create', [
            'project' => new Project,
        ]);
    }

    /**
     * POST /admin/projects
     */
    public function store(ProjectRequest $request): RedirectResponse
    {
        $data = $request->projectData();

        // store() memberi nama file acak & unik, contoh: projects/Xk3...9a.jpg
        $data['cover_image'] = $request->file('cover_image')->store(self::COVER_DIR, Project::mediaDisk());

        $project = Project::create($data);

        return redirect()
            ->route('admin.projects.index')
            ->with('success', "Proyek \"{$project->title}\" berhasil ditambahkan.");
    }

    /**
     * GET /admin/projects/{project}/edit
     */
    public function edit(Project $project): View
    {
        return view('admin.projects.edit', [
            'project' => $project,
        ]);
    }

    /**
     * PUT /admin/projects/{project}
     */
    public function update(ProjectRequest $request, Project $project): RedirectResponse
    {
        $data = $request->projectData();
        $oldCover = null;

        if ($request->hasFile('cover_image')) {
            $oldCover = $project->cover_image;
            $data['cover_image'] = $request->file('cover_image')->store(self::COVER_DIR, Project::mediaDisk());
        }

        $project->update($data);

        // Hapus gambar lama SETELAH data tersimpan, supaya tidak ada proyek tanpa gambar bila update gagal.
        if ($oldCover) {
            Storage::disk(Project::mediaDisk())->delete($oldCover);
        }

        return redirect()
            ->route('admin.projects.index')
            ->with('success', "Proyek \"{$project->title}\" berhasil diperbarui.");
    }

    /**
     * DELETE /admin/projects/{project}
     * File cover ikut terhapus otomatis lewat event "deleted" di model Project.
     */
    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();

        return redirect()
            ->route('admin.projects.index')
            ->with('success', "Proyek \"{$project->title}\" telah dihapus.");
    }
}
