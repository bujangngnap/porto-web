<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Education;
use App\Models\Experience;
use App\Models\PortfolioProfile;
use App\Models\SocialLink;
use App\Support\MediaHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PortfolioContentController extends Controller
{
    public function index(Request $request): View
    {
        $section = $request->string('section', 'profile')->toString();
        if (! in_array($section, ['profile', 'education', 'experience', 'social'], true)) {
            $section = 'profile';
        }

        $profile = PortfolioProfile::first() ?? new PortfolioProfile([
            'hero_top_text' => 'WELCOME TO MY',
            'hero_bottom_text' => 'PORTOFOLIO',
            'greeting' => "Hi, I'm",
            'first_name' => 'Muhammad Raihan',
            'last_name' => 'Al Ashr',
            'full_name' => 'Muhammad Raihan Al Ashr',
            'email' => 'banggob09@gmail.com',
            'location' => 'Magelang, Jawa Tengah',
            'avatar' => '/profile.png',
            'avatar_reveal' => '/PhotoBlackPanther.png',
            'contact_photo' => '/profile.jpg',
        ]);

        $educations = Education::orderBy('sort_order')->orderBy('id')->get();
        $experiences = Experience::orderBy('sort_order')->orderBy('id')->get();
        $socialLinks = SocialLink::orderBy('sort_order')->orderBy('id')->get();

        $editingEducation = $request->filled('edit_edu')
            ? Education::find($request->integer('edit_edu'))
            : null;
        $editingExperience = $request->filled('edit_exp')
            ? Experience::find($request->integer('edit_exp'))
            : null;
        $editingSocial = $request->filled('edit_social')
            ? SocialLink::find($request->integer('edit_social'))
            : null;

        return view('admin.portfolio.index', compact(
            'section',
            'profile',
            'educations',
            'experiences',
            'socialLinks',
            'editingEducation',
            'editingExperience',
            'editingSocial'
        ));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'hero_top_text' => ['required', 'string', 'max:100'],
            'hero_bottom_text' => ['required', 'string', 'max:100'],
            'greeting' => ['required', 'string', 'max:80'],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'full_name' => ['required', 'string', 'max:180'],
            'bio' => ['required', 'string', 'max:2000'],
            'resume_url' => ['nullable', 'string', 'max:500'],
            'resume_file' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'email' => ['required', 'email', 'max:150'],
            'location' => ['required', 'string', 'max:150'],
            'avatar_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'avatar_reveal_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'contact_photo_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $profile = PortfolioProfile::first() ?? new PortfolioProfile();

        $data = [
            'hero_top_text' => trim($validated['hero_top_text']),
            'hero_bottom_text' => trim($validated['hero_bottom_text']),
            'greeting' => trim($validated['greeting']),
            'first_name' => trim($validated['first_name']),
            'last_name' => trim($validated['last_name']),
            'full_name' => trim($validated['full_name']),
            'bio' => trim($validated['bio']),
            'resume_url' => trim((string) ($validated['resume_url'] ?? '')),
            'email' => trim($validated['email']),
            'location' => trim($validated['location']),
            'avatar' => $profile->avatar ?: '/profile.png',
            'avatar_reveal' => $profile->avatar_reveal ?: '/PhotoBlackPanther.png',
            'contact_photo' => $profile->contact_photo ?: '/profile.jpg',
        ];

        if ($request->hasFile('resume_file')) {
            MediaHelper::deleteStoredPublicImage($profile->resume_url);
            $data['resume_url'] = MediaHelper::storePublicFile($request->file('resume_file'), 'cv');
        }

        if ($request->hasFile('avatar_file')) {
            MediaHelper::deleteStoredPublicImage($profile->avatar);
            $data['avatar'] = MediaHelper::storePublicImage($request->file('avatar_file'), 'profile');
        }

        if ($request->hasFile('avatar_reveal_file')) {
            MediaHelper::deleteStoredPublicImage($profile->avatar_reveal);
            $data['avatar_reveal'] = MediaHelper::storePublicImage($request->file('avatar_reveal_file'), 'profile');
        }

        if ($request->hasFile('contact_photo_file')) {
            MediaHelper::deleteStoredPublicImage($profile->contact_photo);
            $data['contact_photo'] = MediaHelper::storePublicImage($request->file('contact_photo_file'), 'profile');
        }

        $profile->fill($data)->save();

        return redirect()
            ->route('admin.portfolio.index', ['section' => 'profile'])
            ->with('success', 'Profil & konten portofolio berhasil diperbarui.');
    }

    public function storeEducation(Request $request): RedirectResponse
    {
        $data = $this->validateEducation($request);
        if ($request->hasFile('logo_file')) {
            $data['logo'] = MediaHelper::storePublicImage($request->file('logo_file'), 'logos');
        } else {
            $data['logo'] = $data['logo_url'] ?? '';
        }
        unset($data['logo_url']);

        Education::create($data);

        return redirect()
            ->route('admin.portfolio.index', ['section' => 'education'])
            ->with('success', 'Riwayat pendidikan berhasil ditambahkan.');
    }

    public function updateEducation(Request $request, Education $education): RedirectResponse
    {
        $data = $this->validateEducation($request);
        if ($request->hasFile('logo_file')) {
            MediaHelper::deleteStoredPublicImage($education->logo);
            $data['logo'] = MediaHelper::storePublicImage($request->file('logo_file'), 'logos');
        } elseif (! empty($data['logo_url'])) {
            $data['logo'] = $data['logo_url'];
        }
        unset($data['logo_url']);

        $education->update($data);

        return redirect()
            ->route('admin.portfolio.index', ['section' => 'education'])
            ->with('success', 'Riwayat pendidikan berhasil diperbarui.');
    }

    public function destroyEducation(Education $education): RedirectResponse
    {
        MediaHelper::deleteStoredPublicImage($education->logo);
        $education->delete();

        return redirect()
            ->route('admin.portfolio.index', ['section' => 'education'])
            ->with('success', 'Riwayat pendidikan berhasil dihapus.');
    }

    public function storeExperience(Request $request): RedirectResponse
    {
        $data = $this->validateExperience($request);
        if ($request->hasFile('logo_file')) {
            $data['logo'] = MediaHelper::storePublicImage($request->file('logo_file'), 'logos');
        } else {
            $data['logo'] = $data['logo_url'] ?? '';
        }
        unset($data['logo_url']);

        Experience::create($data);

        return redirect()
            ->route('admin.portfolio.index', ['section' => 'experience'])
            ->with('success', 'Pengalaman berhasil ditambahkan.');
    }

    public function updateExperience(Request $request, Experience $experience): RedirectResponse
    {
        $data = $this->validateExperience($request);
        if ($request->hasFile('logo_file')) {
            MediaHelper::deleteStoredPublicImage($experience->logo);
            $data['logo'] = MediaHelper::storePublicImage($request->file('logo_file'), 'logos');
        } elseif (! empty($data['logo_url'])) {
            $data['logo'] = $data['logo_url'];
        }
        unset($data['logo_url']);

        $experience->update($data);

        return redirect()
            ->route('admin.portfolio.index', ['section' => 'experience'])
            ->with('success', 'Pengalaman berhasil diperbarui.');
    }

    public function destroyExperience(Experience $experience): RedirectResponse
    {
        MediaHelper::deleteStoredPublicImage($experience->logo);
        $experience->delete();

        return redirect()
            ->route('admin.portfolio.index', ['section' => 'experience'])
            ->with('success', 'Pengalaman berhasil dihapus.');
    }

    public function storeSocial(Request $request): RedirectResponse
    {
        $data = $this->validateSocial($request);
        SocialLink::create($data);

        return redirect()
            ->route('admin.portfolio.index', ['section' => 'social'])
            ->with('success', 'Tautan media sosial berhasil ditambahkan.');
    }

    public function updateSocial(Request $request, SocialLink $social): RedirectResponse
    {
        $data = $this->validateSocial($request);
        $social->update($data);

        return redirect()
            ->route('admin.portfolio.index', ['section' => 'social'])
            ->with('success', 'Tautan media sosial berhasil diperbarui.');
    }

    public function destroySocial(SocialLink $social): RedirectResponse
    {
        $social->delete();

        return redirect()
            ->route('admin.portfolio.index', ['section' => 'social'])
            ->with('success', 'Tautan media sosial berhasil dihapus.');
    }

    private function validateEducation(Request $request): array
    {
        $v = $request->validate([
            'school' => ['required', 'string', 'max:160'],
            'degree' => ['required', 'string', 'max:160'],
            'faculty' => ['nullable', 'string', 'max:160'],
            'year' => ['required', 'string', 'max:80'],
            'tag' => ['nullable', 'string', 'max:80'],
            'description' => ['nullable', 'string', 'max:1000'],
            'logo_url' => ['nullable', 'string', 'max:500'],
            'logo_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:5120'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
        ]);

        return [
            'school' => trim($v['school']),
            'degree' => trim($v['degree']),
            'faculty' => trim((string) ($v['faculty'] ?? '')),
            'year' => trim($v['year']),
            'tag' => trim((string) ($v['tag'] ?? '')),
            'description' => trim((string) ($v['description'] ?? '')),
            'logo_url' => trim((string) ($v['logo_url'] ?? '')),
            'sort_order' => (int) ($v['sort_order'] ?? 0),
        ];
    }

    private function validateExperience(Request $request): array
    {
        $v = $request->validate([
            'company' => ['required', 'string', 'max:160'],
            'role' => ['required', 'string', 'max:160'],
            'year' => ['required', 'string', 'max:80'],
            'subtitle' => ['nullable', 'string', 'max:160'],
            'tag' => ['nullable', 'string', 'max:80'],
            'description' => ['nullable', 'string', 'max:1000'],
            'logo_url' => ['nullable', 'string', 'max:500'],
            'logo_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:5120'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
        ]);

        return [
            'company' => trim($v['company']),
            'role' => trim($v['role']),
            'year' => trim($v['year']),
            'subtitle' => trim((string) ($v['subtitle'] ?? '')),
            'tag' => trim((string) ($v['tag'] ?? '')),
            'description' => trim((string) ($v['description'] ?? '')),
            'logo_url' => trim((string) ($v['logo_url'] ?? '')),
            'sort_order' => (int) ($v['sort_order'] ?? 0),
        ];
    }

    private function validateSocial(Request $request): array
    {
        $v = $request->validate([
            'display_name' => ['required', 'string', 'max:100'],
            'sub_text' => ['nullable', 'string', 'max:150'],
            'url' => ['required', 'url', 'max:500'],
            'type' => ['required', Rule::in(['github', 'linkedin', 'instagram', 'youtube', 'tiktok'])],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
        ]);

        return [
            'display_name' => trim($v['display_name']),
            'sub_text' => trim((string) ($v['sub_text'] ?? '')),
            'url' => trim($v['url']),
            'type' => $v['type'],
            'sort_order' => (int) ($v['sort_order'] ?? 0),
        ];
    }
}
