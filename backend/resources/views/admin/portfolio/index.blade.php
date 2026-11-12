@extends('admin.layouts.app')

@section('title', 'Edit Portofolio')
@section('page_title', 'Edit Portofolio')
@section('page_subtitle', 'Ubah teks Hero, profil About Me, foto, CV, pendidikan, pengalaman, dan media sosial.')

@section('header_actions')
    <div class="hidden sm:flex items-center gap-2">
        <a href="{{ route('admin.projects.index') }}" class="px-3.5 py-2 rounded-xl border border-white/[0.08] bg-[#18181b] hover:bg-[#26262b] text-xs font-medium text-zinc-200 transition-colors">
            Kelola Proyek →
        </a>
        <a href="{{ route('admin.gallery.index') }}" class="px-3.5 py-2 rounded-xl border border-white/[0.08] bg-[#18181b] hover:bg-[#26262b] text-xs font-medium text-zinc-200 transition-colors">
            Kelola Galeri →
        </a>
    </div>
@endsection

@section('content')
    @php
        $inputClass = 'w-full px-4 py-2.5 rounded-xl bg-[#202025] border border-white/[0.08] text-sm text-zinc-100 placeholder:text-zinc-500 focus:border-zinc-400 focus:bg-[#24242a] focus:outline-none transition-colors';
        $compactInputClass = 'w-full px-3.5 py-2.5 rounded-xl bg-[#202025] border border-white/[0.08] text-sm text-zinc-100 placeholder:text-zinc-500 focus:border-zinc-400 focus:bg-[#24242a] focus:outline-none transition-colors';
    @endphp

    <!-- Section Tabs -->
    <div class="flex flex-wrap items-center gap-2">
        @foreach ([
            'profile' => 'Profil, Hero & Kontak',
            'education' => 'Pendidikan (' . $educations->count() . ')',
            'experience' => 'Pengalaman (' . $experiences->count() . ')',
            'social' => 'Media Sosial (' . $socialLinks->count() . ')',
        ] as $key => $label)
            <a
                href="{{ route('admin.portfolio.index', ['section' => $key]) }}"
                class="px-4 py-2 rounded-xl text-xs font-medium transition-all {{ $section === $key ? 'bg-[#26262b] text-white border border-white/10 shadow-sm' : 'bg-[#18181b] text-zinc-400 border border-white/[0.07] hover:text-zinc-200' }}"
            >
                {{ $label }}
            </a>
        @endforeach
    </div>

    @if ($section === 'profile')
        <form
            method="POST"
            action="{{ route('admin.portfolio.profile.update') }}"
            enctype="multipart/form-data"
            class="space-y-6"
        >
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Identity & Hero Card -->
                <div class="bg-[#18181b] rounded-3xl p-6 sm:p-7 border border-white/[0.07] space-y-4">
                    <h2 class="font-display font-bold text-base text-zinc-100 pb-3 border-b border-white/[0.06]">Hero &amp; Identitas Utama</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Teks Hero Baris 1</label>
                            <input type="text" name="hero_top_text" required value="{{ old('hero_top_text', $profile->hero_top_text) }}" class="{{ $inputClass }}">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Teks Hero Baris 2</label>
                            <input type="text" name="hero_bottom_text" required value="{{ old('hero_bottom_text', $profile->hero_bottom_text) }}" class="{{ $inputClass }}">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Sapaan</label>
                            <input type="text" name="greeting" required value="{{ old('greeting', $profile->greeting) }}" class="{{ $inputClass }}">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Nama Depan (Baris 1)</label>
                            <input type="text" name="first_name" required value="{{ old('first_name', $profile->first_name) }}" class="{{ $inputClass }}">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Nama Belakang (Baris 2)</label>
                            <input type="text" name="last_name" required value="{{ old('last_name', $profile->last_name) }}" class="{{ $inputClass }}">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Nama Lengkap (Mobile &amp; Alt Foto)</label>
                        <input type="text" name="full_name" required value="{{ old('full_name', $profile->full_name) }}" class="{{ $inputClass }}">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Deskripsi Bio (About Me)</label>
                        <textarea name="bio" rows="4" required class="{{ $inputClass }} leading-relaxed">{{ old('bio', $profile->bio) }}</textarea>
                    </div>
                </div>

                <!-- Contact, CV & Visual Assets -->
                <div class="bg-[#18181b] rounded-3xl p-6 sm:p-7 border border-white/[0.07] space-y-4">
                    <h2 class="font-display font-bold text-base text-zinc-100 pb-3 border-b border-white/[0.06]">Kontak, CV &amp; Foto Profil</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Email Langsung (Kartu Kontak)</label>
                            <input type="email" name="email" required value="{{ old('email', $profile->email) }}" class="{{ $inputClass }}">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Lokasi</label>
                            <input type="text" name="location" required value="{{ old('location', $profile->location) }}" class="{{ $inputClass }}">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Link CV (Google Drive / URL)</label>
                            <input type="text" name="resume_url" value="{{ old('resume_url', $profile->resume_url) }}" placeholder="https://... atau upload PDF →" class="{{ $inputClass }}">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Atau Upload File CV (PDF, Max 10MB)</label>
                            <input
                                type="file"
                                name="resume_file"
                                accept="application/pdf"
                                class="w-full px-3 py-2 rounded-xl bg-[#202025] border border-white/[0.08] text-xs text-zinc-300 file:mr-2.5 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:bg-zinc-100 file:text-zinc-950 file:font-semibold cursor-pointer"
                            >
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                        <div class="p-3 rounded-2xl bg-[#121215] border border-white/[0.07] space-y-2">
                            <div class="text-[11px] font-semibold text-zinc-300">Foto Utama (About)</div>
                            <img src="{{ \App\Support\MediaHelper::adminPreviewUrl($profile->avatar) }}" alt="Avatar" class="w-full h-24 object-contain bg-[#09090b] rounded-xl p-1 border border-white/[0.05]">
                            <input type="file" name="avatar_file" accept="image/*" class="w-full text-[11px] text-zinc-400 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border file:border-white/10 file:bg-[#202025] file:text-zinc-200 hover:file:bg-[#2a2a30] cursor-pointer">
                        </div>

                        <div class="p-3 rounded-2xl bg-[#121215] border border-white/[0.07] space-y-2">
                            <div class="text-[11px] font-semibold text-zinc-300">Foto Reveal (Flashlight)</div>
                            <img src="{{ \App\Support\MediaHelper::adminPreviewUrl($profile->avatar_reveal) }}" alt="Reveal" class="w-full h-24 object-contain bg-[#09090b] rounded-xl p-1 border border-white/[0.05]">
                            <input type="file" name="avatar_reveal_file" accept="image/*" class="w-full text-[11px] text-zinc-400 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border file:border-white/10 file:bg-[#202025] file:text-zinc-200 hover:file:bg-[#2a2a30] cursor-pointer">
                        </div>

                        <div class="p-3 rounded-2xl bg-[#121215] border border-white/[0.07] space-y-2">
                            <div class="text-[11px] font-semibold text-zinc-300">Foto Kartu Kontak</div>
                            <img src="{{ \App\Support\MediaHelper::adminPreviewUrl($profile->contact_photo) }}" alt="Contact Photo" class="w-full h-24 object-cover rounded-xl border border-white/[0.05]">
                            <input type="file" name="contact_photo_file" accept="image/*" class="w-full text-[11px] text-zinc-400 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border file:border-white/10 file:bg-[#202025] file:text-zinc-200 hover:file:bg-[#2a2a30] cursor-pointer">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end">
                <button type="submit" class="px-6 py-3 rounded-xl bg-zinc-100 hover:bg-white text-zinc-950 text-sm font-semibold transition-colors cursor-pointer">
                    Simpan Perubahan Profil Portofolio
                </button>
            </div>
        </form>

    @elseif ($section === 'education')
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            <div class="lg:col-span-5 bg-[#18181b] rounded-3xl p-6 border border-white/[0.07] space-y-4">
                <h2 class="font-display font-bold text-base text-zinc-100">
                    {{ $editingEducation ? 'Edit Pendidikan' : 'Tambah Riwayat Pendidikan' }}
                </h2>

                <form
                    method="POST"
                    action="{{ $editingEducation ? route('admin.portfolio.education.update', $editingEducation) : route('admin.portfolio.education.store') }}"
                    enctype="multipart/form-data"
                    class="space-y-3.5"
                >
                    @csrf
                    @if ($editingEducation) @method('PUT') @endif

                    <div>
                        <label class="block text-xs font-semibold text-zinc-300 mb-1">Nama Kampus / Sekolah</label>
                        <input type="text" name="school" required value="{{ old('school', $editingEducation?->school) }}" placeholder="Universitas Muhammadiyah Magelang" class="{{ $compactInputClass }}">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-300 mb-1">Jurusan / Gelar</label>
                        <input type="text" name="degree" required value="{{ old('degree', $editingEducation?->degree) }}" placeholder="S1 Teknik Informatika" class="{{ $compactInputClass }}">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-zinc-300 mb-1">Fakultas / Subjudul</label>
                            <input type="text" name="faculty" value="{{ old('faculty', $editingEducation?->faculty) }}" placeholder="Fakultas Teknik" class="{{ $compactInputClass }}">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-zinc-300 mb-1">Tahun</label>
                            <input type="text" name="year" required value="{{ old('year', $editingEducation?->year) }}" placeholder="2025 – Sekarang" class="{{ $compactInputClass }}">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-zinc-300 mb-1">Badge Status</label>
                            <input type="text" name="tag" value="{{ old('tag', $editingEducation?->tag) }}" placeholder="Mahasiswa Aktif" class="{{ $compactInputClass }}">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-zinc-300 mb-1">Urutan</label>
                            <input type="number" name="sort_order" min="0" value="{{ old('sort_order', $editingEducation?->sort_order ?? ($educations->count() + 1)) }}" class="{{ $compactInputClass }}">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-300 mb-1">Deskripsi Singkat</label>
                        <textarea name="description" rows="3" class="{{ $compactInputClass }}">{{ old('description', $editingEducation?->description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-zinc-300 mb-1">Upload Logo</label>
                            <input type="file" name="logo_file" accept="image/*" class="w-full text-xs text-zinc-400">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-zinc-300 mb-1">Atau Path Logo</label>
                            <input type="text" name="logo_url" value="{{ old('logo_url', $editingEducation?->logo) }}" placeholder="/unimma_logo.png" class="w-full px-3 py-2 rounded-xl bg-[#202025] border border-white/[0.08] text-xs text-zinc-100 placeholder:text-zinc-500 focus:border-zinc-400 focus:outline-none">
                        </div>
                    </div>

                    <div class="pt-2 flex items-center gap-2">
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-zinc-100 hover:bg-white text-zinc-950 text-xs font-semibold cursor-pointer">
                            {{ $editingEducation ? 'Simpan Perubahan' : 'Tambah Pendidikan' }}
                        </button>
                        @if ($editingEducation)
                            <a href="{{ route('admin.portfolio.index', ['section' => 'education']) }}" class="px-4 py-2.5 rounded-xl border border-white/[0.08] text-xs font-medium text-zinc-400 hover:text-zinc-200">Batal</a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="lg:col-span-7 space-y-3.5">
                @forelse ($educations as $edu)
                    <div class="bg-[#18181b] rounded-2xl p-5 border border-white/[0.07] flex items-start justify-between gap-4">
                        <div class="flex items-start gap-3.5 min-w-0">
                            @if ($edu->logo)
                                <img src="{{ \App\Support\MediaHelper::adminPreviewUrl($edu->logo) }}" alt="{{ $edu->school }}" class="w-11 h-11 rounded-full object-contain bg-white p-1 flex-shrink-0">
                            @endif
                            <div class="min-w-0 space-y-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="font-display font-bold text-sm text-zinc-100">{{ $edu->school }}</span>
                                    <span class="px-2 py-0.5 text-[11px] font-medium rounded-full bg-[#202025] border border-white/[0.06] text-zinc-300">{{ $edu->year }}</span>
                                    @if ($edu->tag)
                                        <span class="px-2 py-0.5 text-[11px] font-semibold rounded-full bg-white/10 text-zinc-200">{{ $edu->tag }}</span>
                                    @endif
                                </div>
                                <div class="text-xs font-medium text-zinc-300">{{ $edu->degree }} {{ $edu->faculty ? '· ' . $edu->faculty : '' }}</div>
                                @if ($edu->description)
                                    <p class="text-xs text-zinc-400 leading-relaxed">{{ $edu->description }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-2 flex-shrink-0">
                            <a href="{{ route('admin.portfolio.index', ['section' => 'education', 'edit_edu' => $edu->id]) }}" class="px-3 py-1.5 rounded-lg text-[11px] font-semibold bg-zinc-100 hover:bg-white text-zinc-950">Edit</a>
                            <form method="POST" action="{{ route('admin.portfolio.education.destroy', $edu) }}" onsubmit="return confirm('Hapus riwayat pendidikan ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 rounded-lg text-[11px] font-medium text-rose-400 hover:bg-rose-500/10 border border-rose-500/25 cursor-pointer">Hapus</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="bg-[#18181b] rounded-3xl p-10 border border-white/[0.07] text-center text-sm text-zinc-500">Belum ada data pendidikan.</div>
                @endforelse
            </div>
        </div>

    @elseif ($section === 'experience')
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            <div class="lg:col-span-5 bg-[#18181b] rounded-3xl p-6 border border-white/[0.07] space-y-4">
                <h2 class="font-display font-bold text-base text-zinc-100">
                    {{ $editingExperience ? 'Edit Pengalaman' : 'Tambah Pengalaman Baru' }}
                </h2>

                <form
                    method="POST"
                    action="{{ $editingExperience ? route('admin.portfolio.experience.update', $editingExperience) : route('admin.portfolio.experience.store') }}"
                    enctype="multipart/form-data"
                    class="space-y-3.5"
                >
                    @csrf
                    @if ($editingExperience) @method('PUT') @endif

                    <div>
                        <label class="block text-xs font-semibold text-zinc-300 mb-1">Perusahaan / Instansi</label>
                        <input type="text" name="company" required value="{{ old('company', $editingExperience?->company) }}" placeholder="Tumbuh Kembang" class="{{ $compactInputClass }}">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-300 mb-1">Peran / Jabatan</label>
                        <input type="text" name="role" required value="{{ old('role', $editingExperience?->role) }}" placeholder="Desainer & Web Developer" class="{{ $compactInputClass }}">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-zinc-300 mb-1">Bidang / Subjudul</label>
                            <input type="text" name="subtitle" value="{{ old('subtitle', $editingExperience?->subtitle) }}" placeholder="Creative Agency & Web" class="{{ $compactInputClass }}">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-zinc-300 mb-1">Tahun</label>
                            <input type="text" name="year" required value="{{ old('year', $editingExperience?->year) }}" placeholder="2026" class="{{ $compactInputClass }}">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-zinc-300 mb-1">Tag Kategori</label>
                            <input type="text" name="tag" value="{{ old('tag', $editingExperience?->tag) }}" placeholder="Design & Dev" class="{{ $compactInputClass }}">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-zinc-300 mb-1">Urutan</label>
                            <input type="number" name="sort_order" min="0" value="{{ old('sort_order', $editingExperience?->sort_order ?? ($experiences->count() + 1)) }}" class="{{ $compactInputClass }}">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-300 mb-1">Deskripsi Kontribusi</label>
                        <textarea name="description" rows="3" class="{{ $compactInputClass }}">{{ old('description', $editingExperience?->description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-zinc-300 mb-1">Upload Logo</label>
                            <input type="file" name="logo_file" accept="image/*" class="w-full text-xs text-zinc-400">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-zinc-300 mb-1">Atau Path Logo</label>
                            <input type="text" name="logo_url" value="{{ old('logo_url', $editingExperience?->logo) }}" placeholder="/tumbuh_kembang_logo.png" class="w-full px-3 py-2 rounded-xl bg-[#202025] border border-white/[0.08] text-xs text-zinc-100 placeholder:text-zinc-500 focus:border-zinc-400 focus:outline-none">
                        </div>
                    </div>

                    <div class="pt-2 flex items-center gap-2">
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-zinc-100 hover:bg-white text-zinc-950 text-xs font-semibold cursor-pointer">
                            {{ $editingExperience ? 'Simpan Perubahan' : 'Tambah Pengalaman' }}
                        </button>
                        @if ($editingExperience)
                            <a href="{{ route('admin.portfolio.index', ['section' => 'experience']) }}" class="px-4 py-2.5 rounded-xl border border-white/[0.08] text-xs font-medium text-zinc-400 hover:text-zinc-200">Batal</a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="lg:col-span-7 space-y-3.5">
                @forelse ($experiences as $exp)
                    <div class="bg-[#18181b] rounded-2xl p-5 border border-white/[0.07] flex items-start justify-between gap-4">
                        <div class="flex items-start gap-3.5 min-w-0">
                            @if ($exp->logo)
                                <img src="{{ \App\Support\MediaHelper::adminPreviewUrl($exp->logo) }}" alt="{{ $exp->company }}" class="w-11 h-11 rounded-full object-contain bg-white p-1 flex-shrink-0">
                            @endif
                            <div class="min-w-0 space-y-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="font-display font-bold text-sm text-zinc-100">{{ $exp->company }}</span>
                                    <span class="px-2 py-0.5 text-[11px] font-medium rounded-full bg-[#202025] border border-white/[0.06] text-zinc-300">{{ $exp->year }}</span>
                                    @if ($exp->tag)
                                        <span class="px-2 py-0.5 text-[11px] font-semibold rounded-full bg-white/10 text-zinc-200">{{ $exp->tag }}</span>
                                    @endif
                                </div>
                                <div class="text-xs font-medium text-zinc-300">{{ $exp->role }} {{ $exp->subtitle ? '· ' . $exp->subtitle : '' }}</div>
                                @if ($exp->description)
                                    <p class="text-xs text-zinc-400 leading-relaxed">{{ $exp->description }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-2 flex-shrink-0">
                            <a href="{{ route('admin.portfolio.index', ['section' => 'experience', 'edit_exp' => $exp->id]) }}" class="px-3 py-1.5 rounded-lg text-[11px] font-semibold bg-zinc-100 hover:bg-white text-zinc-950">Edit</a>
                            <form method="POST" action="{{ route('admin.portfolio.experience.destroy', $exp) }}" onsubmit="return confirm('Hapus pengalaman ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 rounded-lg text-[11px] font-medium text-rose-400 hover:bg-rose-500/10 border border-rose-500/25 cursor-pointer">Hapus</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="bg-[#18181b] rounded-3xl p-10 border border-white/[0.07] text-center text-sm text-zinc-500">Belum ada data pengalaman.</div>
                @endforelse
            </div>
        </div>

    @elseif ($section === 'social')
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            <div class="lg:col-span-5 bg-[#18181b] rounded-3xl p-6 border border-white/[0.07] space-y-4">
                <h2 class="font-display font-bold text-base text-zinc-100">
                    {{ $editingSocial ? 'Edit Media Sosial' : 'Tambah Link Media Sosial' }}
                </h2>

                <form
                    method="POST"
                    action="{{ $editingSocial ? route('admin.portfolio.social.update', $editingSocial) : route('admin.portfolio.social.store') }}"
                    class="space-y-3.5"
                >
                    @csrf
                    @if ($editingSocial) @method('PUT') @endif

                    <div>
                        <label class="block text-xs font-semibold text-zinc-300 mb-1">Platform</label>
                        <select name="type" class="{{ $compactInputClass }}">
                            @foreach (['github' => 'GitHub', 'linkedin' => 'LinkedIn', 'instagram' => 'Instagram', 'tiktok' => 'TikTok'] as $val => $label)
                                <option value="{{ $val }}" @selected(old('type', $editingSocial?->type) === $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-300 mb-1">Nama Tampil</label>
                        <input type="text" name="display_name" required value="{{ old('display_name', $editingSocial?->display_name) }}" placeholder="Instagram" class="{{ $compactInputClass }}">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-300 mb-1">Subteks</label>
                        <input type="text" name="sub_text" value="{{ old('sub_text', $editingSocial?->sub_text) }}" placeholder="Karya Desain & Portofolio" class="{{ $compactInputClass }}">
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <div class="col-span-2">
                            <label class="block text-xs font-semibold text-zinc-300 mb-1">URL Profil</label>
                            <input type="url" name="url" required value="{{ old('url', $editingSocial?->url) }}" placeholder="https://instagram.com/..." class="{{ $compactInputClass }}">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-zinc-300 mb-1">Urutan</label>
                            <input type="number" name="sort_order" min="0" value="{{ old('sort_order', $editingSocial?->sort_order ?? ($socialLinks->count() + 1)) }}" class="{{ $compactInputClass }}">
                        </div>
                    </div>

                    <div class="pt-2 flex items-center gap-2">
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-zinc-100 hover:bg-white text-zinc-950 text-xs font-semibold cursor-pointer">
                            {{ $editingSocial ? 'Simpan Perubahan' : 'Tambah Link' }}
                        </button>
                        @if ($editingSocial)
                            <a href="{{ route('admin.portfolio.index', ['section' => 'social']) }}" class="px-4 py-2.5 rounded-xl border border-white/[0.08] text-xs font-medium text-zinc-400 hover:text-zinc-200">Batal</a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="lg:col-span-7 space-y-3">
                @forelse ($socialLinks as $link)
                    <div class="bg-[#18181b] rounded-2xl p-4 sm:p-5 border border-white/[0.07] flex items-center justify-between gap-4">
                        <div class="min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="font-display font-bold text-sm text-zinc-100">{{ $link->display_name }}</span>
                                <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded-full bg-[#202025] border border-white/[0.06] text-zinc-400">{{ $link->type }}</span>
                            </div>
                            <div class="text-xs text-zinc-400">{{ $link->sub_text }}</div>
                            <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer" class="text-xs text-zinc-300 hover:text-white hover:underline truncate block mt-0.5">{{ $link->url }}</a>
                        </div>

                        <div class="flex items-center gap-2 flex-shrink-0">
                            <a href="{{ route('admin.portfolio.index', ['section' => 'social', 'edit_social' => $link->id]) }}" class="px-3 py-1.5 rounded-lg text-[11px] font-semibold bg-zinc-100 hover:bg-white text-zinc-950">Edit</a>
                            <form method="POST" action="{{ route('admin.portfolio.social.destroy', $link) }}" onsubmit="return confirm('Hapus link medsos ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 rounded-lg text-[11px] font-medium text-rose-400 hover:bg-rose-500/10 border border-rose-500/25 cursor-pointer">Hapus</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="bg-[#18181b] rounded-3xl p-10 border border-white/[0.07] text-center text-sm text-zinc-500">Belum ada tautan media sosial.</div>
                @endforelse
            </div>
        </div>
    @endif
@endsection
