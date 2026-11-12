<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Support\MediaHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $tab = $request->string('tab', 'all')->toString();

        $query = Project::orderBy('tab')->orderBy('sort_order')->orderBy('id');
        if (in_array($tab, ['website', 'aplikasi', 'desain'], true)) {
            $query->where('tab', $tab);
        }

        $projects = $query->get();

        return view('admin.projects.index', compact('projects', 'tab'));
    }

    public function create(): View
    {
        return view('admin.projects.form', [
            'project' => new Project([
                'tab' => 'website',
                'img_object_position' => '50% 50%',
                'featured' => true,
                'sort_order' => (Project::max('sort_order') ?? 0) + 1,
                'tags' => [],
            ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['slug'] = $this->makeUniqueSlug($data['tab'], $data['title']);

        if ($request->hasFile('image')) {
            $data['img'] = MediaHelper::storePublicImage($request->file('image'), 'projects');
        } else {
            $data['img'] = $data['img_url'] ?: '/projects/ssb-garuda-muda-web.jpg';
        }
        unset($data['img_url'], $data['image']);

        Project::create($data);

        return redirect()->route('admin.projects.index')->with('success', 'Proyek berhasil ditambahkan.');
    }

    public function edit(Project $project): View
    {
        return view('admin.projects.form', compact('project'));
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $data = $this->validatedData($request, isUpdate: true);

        if ($request->hasFile('image')) {
            MediaHelper::deleteStoredPublicImage($project->img);
            $data['img'] = MediaHelper::storePublicImage($request->file('image'), 'projects');
        } elseif (! empty($data['img_url'])) {
            $data['img'] = $data['img_url'];
        } else {
            $data['img'] = $project->img;
        }
        unset($data['img_url'], $data['image']);

        $project->update($data);

        return redirect()->route('admin.projects.index')->with('success', 'Proyek berhasil diperbarui.');
    }

    public function toggleFeatured(Project $project): RedirectResponse
    {
        $project->update(['featured' => ! $project->featured]);

        return back()->with('success', $project->featured ? 'Proyek ditandai Featured.' : 'Status Featured dilepas.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        MediaHelper::deleteStoredPublicImage($project->img);
        $project->delete();

        return back()->with('success', 'Proyek berhasil dihapus.');
    }

    private function validatedData(Request $request, bool $isUpdate = false): array
    {
        $validated = $request->validate([
            'tab' => ['required', Rule::in(['website', 'aplikasi', 'desain'])],
            'title' => ['required', 'string', 'max:160'],
            'category' => ['required', 'string', 'max:120'],
            'description' => ['required', 'string', 'max:1000'],
            'long_description' => ['nullable', 'string', 'max:3000'],
            'tags_input' => ['nullable', 'string', 'max:500'],
            'link' => ['nullable', 'string', 'max:500'],
            'img_url' => ['nullable', 'string', 'max:500'],
            'image' => [$isUpdate ? 'nullable' : 'required_without:img_url', 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'img_object_position' => ['nullable', 'string', 'max:40'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
        ], [
            'image.required_without' => 'Upload file gambar atau isi path/URL gambar.',
        ]);

        $tags = collect(explode(',', (string) ($validated['tags_input'] ?? '')))
            ->map(fn (string $tag) => trim($tag))
            ->filter()
            ->values()
            ->all();

        return [
            'tab' => $validated['tab'],
            'title' => trim($validated['title']),
            'category' => trim($validated['category']),
            'description' => trim($validated['description']),
            'long_description' => trim((string) ($validated['long_description'] ?? '')) ?: null,
            'tags' => $tags,
            'link' => trim((string) ($validated['link'] ?? '')) ?: '',
            'img_url' => trim((string) ($validated['img_url'] ?? '')),
            'img_object_position' => trim((string) ($validated['img_object_position'] ?? '')) ?: '50% 50%',
            'featured' => $request->boolean('featured'),
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
        ];
    }

    private function makeUniqueSlug(string $tab, string $title): string
    {
        $prefix = match ($tab) {
            'aplikasi' => 'app',
            'desain' => 'des',
            default => 'web',
        };
        $base = $prefix.'-'.Str::slug($title, '-');
        $slug = Str::limit($base, 65, '');
        $counter = 2;

        while (Project::where('slug', $slug)->exists()) {
            $slug = Str::limit($base, 60, '').'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
