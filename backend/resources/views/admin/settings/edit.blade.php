@extends('admin.layouts.app')

@section('title', 'Pengaturan Akun')
@section('page_title', 'Pengaturan Akun Admin')
@section('page_subtitle', 'Ubah nama profil, email login, atau password dashboard.')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-[#18181b] rounded-3xl p-6 sm:p-7 border border-white/[0.07] space-y-5">
            <h2 class="font-display font-bold text-base text-zinc-100">Profil Admin</h2>

            <form method="POST" action="{{ route('admin.settings.profile') }}" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Nama Lengkap</label>
                    <input
                        type="text"
                        name="name"
                        required
                        value="{{ old('name', $user->name) }}"
                        class="w-full px-4 py-3 rounded-xl bg-[#202025] border border-white/[0.08] text-sm text-zinc-100 focus:border-zinc-400 focus:outline-none"
                    >
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Email Login</label>
                    <input
                        type="email"
                        name="email"
                        required
                        value="{{ old('email', $user->email) }}"
                        class="w-full px-4 py-3 rounded-xl bg-[#202025] border border-white/[0.08] text-sm text-zinc-100 focus:border-zinc-400 focus:outline-none"
                    >
                </div>

                <button type="submit" class="px-5 py-2.5 rounded-xl bg-zinc-100 hover:bg-white text-zinc-950 text-xs font-semibold cursor-pointer">
                    Simpan Profil
                </button>
            </form>
        </div>

        <div class="bg-[#18181b] rounded-3xl p-6 sm:p-7 border border-white/[0.07] space-y-5">
            <h2 class="font-display font-bold text-base text-zinc-100">Ganti Password</h2>

            <form method="POST" action="{{ route('admin.settings.password') }}" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Password Saat Ini</label>
                    <input
                        type="password"
                        name="current_password"
                        required
                        class="w-full px-4 py-3 rounded-xl bg-[#202025] border border-white/[0.08] text-sm text-zinc-100 focus:border-zinc-400 focus:outline-none"
                    >
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Password Baru</label>
                    <input
                        type="password"
                        name="password"
                        required
                        minlength="6"
                        class="w-full px-4 py-3 rounded-xl bg-[#202025] border border-white/[0.08] text-sm text-zinc-100 focus:border-zinc-400 focus:outline-none"
                    >
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-300 mb-1.5">Konfirmasi Password Baru</label>
                    <input
                        type="password"
                        name="password_confirmation"
                        required
                        minlength="6"
                        class="w-full px-4 py-3 rounded-xl bg-[#202025] border border-white/[0.08] text-sm text-zinc-100 focus:border-zinc-400 focus:outline-none"
                    >
                </div>

                <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#26262b] hover:bg-[#323238] border border-white/10 text-zinc-100 text-xs font-semibold cursor-pointer">
                    Perbarui Password
                </button>
            </form>
        </div>
    </div>
@endsection
