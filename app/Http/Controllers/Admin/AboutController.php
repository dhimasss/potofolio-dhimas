<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AboutRequest;
use App\Models\Profile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * Biodata "About" — resource singleton (hanya satu biodata):
 * GET /admin/about, /admin/about/create, /admin/about/edit, POST/PUT/DELETE /admin/about
 */
class AboutController extends Controller
{
    private const PHOTO_DIR = 'profile';

    /**
     * GET /admin/about — tampilkan biodata, atau ajakan membuatnya bila belum ada.
     */
    public function show(): View
    {
        return view('admin.about.show', [
            'profile' => Profile::current(),
        ]);
    }

    /**
     * GET /admin/about/create
     */
    public function create(): View|RedirectResponse
    {
        if (Profile::current()) {
            return redirect()->route('admin.about.edit');
        }

        return view('admin.about.create', [
            'profile' => new Profile,
        ]);
    }

    /**
     * POST /admin/about
     */
    public function store(AboutRequest $request): RedirectResponse
    {
        // Jaga agar tetap satu biodata, meskipun form create dikirim dua kali.
        if (Profile::current()) {
            return redirect()->route('admin.about.edit');
        }

        $data = $request->profileData();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store(self::PHOTO_DIR, Profile::mediaDisk());
        }

        Profile::create($data);

        return redirect()->route('admin.about.show')->with('success', 'Biodata berhasil dibuat.');
    }

    /**
     * GET /admin/about/edit
     */
    public function edit(): View|RedirectResponse
    {
        $profile = Profile::current();

        if (! $profile) {
            return redirect()->route('admin.about.create');
        }

        return view('admin.about.edit', [
            'profile' => $profile,
        ]);
    }

    /**
     * PUT /admin/about
     */
    public function update(AboutRequest $request): RedirectResponse
    {
        $profile = Profile::current();

        if (! $profile) {
            return redirect()->route('admin.about.create');
        }

        $data = $request->profileData();
        $oldPhoto = null;

        if ($request->hasFile('photo')) {
            $oldPhoto = $profile->photo;
            $data['photo'] = $request->file('photo')->store(self::PHOTO_DIR, Profile::mediaDisk());
        } elseif ($request->boolean('remove_photo')) {
            $oldPhoto = $profile->photo;
            $data['photo'] = null;
        }

        $profile->update($data);

        // Hapus foto lama setelah data tersimpan (sama seperti cover proyek).
        if ($oldPhoto) {
            Storage::disk(Profile::mediaDisk())->delete($oldPhoto);
        }

        return redirect()->route('admin.about.show')->with('success', 'Biodata berhasil diperbarui.');
    }

    /**
     * DELETE /admin/about — foto ikut terhapus lewat event "deleted" di model.
     */
    public function destroy(): RedirectResponse
    {
        Profile::current()?->delete();

        return redirect()->route('admin.about.show')->with('success', 'Biodata telah dihapus. Section About disembunyikan dari situs.');
    }
}
