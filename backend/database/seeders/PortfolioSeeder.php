<?php

namespace Database\Seeders;

use App\Models\ContactMessage;
use App\Models\Education;
use App\Models\Experience;
use App\Models\GalleryPhoto;
use App\Models\PortfolioProfile;
use App\Models\Project;
use App\Models\SocialLink;
use App\Models\User;
use App\Models\VisitorComment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user account for /admin dashboard
        User::updateOrCreate(
            ['email' => 'banggob09@gmail.com'],
            [
                'name' => 'Muhammad Raihan Al Ashr',
                'password' => Hash::make('admin1234'),
                'email_verified_at' => now(),
            ]
        );

        // Initial visitor comments
        if (VisitorComment::count() === 0) {
            VisitorComment::create([
                'name' => 'Dev Enthusiast',
                'message' => 'Keren banget parallax websitenya! Desainnya rapi dan responsif.',
                'is_approved' => true,
                'created_at' => now()->subMinutes(5),
                'updated_at' => now()->subMinutes(5),
            ]);

            VisitorComment::create([
                'name' => 'UI/UX Reviewer',
                'message' => 'Transisi scroll-driven portofolionya halus banget, sukses selalu bro!',
                'is_approved' => true,
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ]);
        }

        // Seed initial sample inbox message if empty
        if (ContactMessage::count() === 0) {
            ContactMessage::create([
                'name' => 'Tim Rekrutmen Digital',
                'email' => 'hr@contoh-klien.id',
                'message' => 'Halo Raihan, kami tertarik berdiskusi tentang kolaborasi pembuatan website company profile.',
                'is_read' => false,
                'created_at' => now()->subHours(3),
                'updated_at' => now()->subHours(3),
            ]);
        }

        // Projects across Website, Aplikasi, and Desain tabs
        $projects = [
            // Website
            [
                'slug' => 'web-1',
                'tab' => 'website',
                'title' => 'SSB Garuda Muda Website',
                'category' => 'Sports Academy Web',
                'description' => 'Website profil Sekolah Sepak Bola SSB Garuda Muda berbasis Laravel dengan informasi visi dan misi, profil pelatih, fasilitas latihan, pilihan paket pembinaan, serta pendaftaran anggota.',
                'long_description' => 'Platform web yang dibangun menggunakan Laravel dan Blade untuk memperkenalkan ekosistem pembinaan atlet sepak bola usia dini di SSB Garuda Muda, lengkap dengan profil tim pelatih, rincian fasilitas lapangan, katalog paket latihan, serta alur pendaftaran siswa baru.',
                'tags' => ['Laravel', 'PHP', 'Blade', 'MySQL', 'Tailwind CSS'],
                'link' => '',
                'img' => '/projects/ssb-garuda-muda-web.jpg',
                'img_object_position' => '50% 42%',
                'featured' => true,
                'sort_order' => 1,
            ],
            [
                'slug' => 'web-2',
                'tab' => 'website',
                'title' => 'Tumbuh Kembang Group Website',
                'category' => 'Corporate & Agency Web',
                'description' => 'Website company profile Tumbuh Kembang Group bertema gelap modern dengan dukungan dwibahasa (ID/EN), serta showcase layanan branding, media kreatif, dan inovasi digital.',
                'long_description' => 'Platform web interaktif yang dirancang untuk merepresentasikan ekosistem layanan Tumbuh Kembang Group, mulai dari strategi branding, produksi videografi sinematik, pengembangan digital & web, hingga solusi mobilitas dan konsultasi bisnis.',
                'tags' => ['React.js', 'Tailwind CSS', 'JavaScript', 'UI/UX Design', 'Bilingual ID/EN'],
                'link' => 'https://www.tumbuhkembanggroup.com/',
                'img' => '/projects/tumbuh-kembang-web.jpg',
                'img_object_position' => '50% 54%',
                'featured' => true,
                'sort_order' => 2,
            ],
            [
                'slug' => 'web-4',
                'tab' => 'website',
                'title' => 'Kenangita Photobooth Website',
                'category' => 'Event & Creative Web',
                'description' => 'Website layanan photobooth Kenangita bernuansa editorial hangat dengan galeri interaktif, katalog paket acara, testimoni klien, pemesanan online, mode gelap, dan dukungan dwibahasa.',
                'long_description' => 'Platform web untuk layanan photobooth gathering, ulang tahun, pernikahan, wisuda, dan acara korporat yang dilengkapi etalase paket interaktif, galeri dokumentasi acara, fitur kemitraan, serta alur reservasi yang cepat dan responsif.',
                'tags' => ['React.js', 'Tailwind CSS', 'JavaScript', 'UI/UX Design', 'Dark Mode'],
                'link' => 'https://kenangitaphotobooth.id/',
                'img' => '/projects/kenangita-web.jpg',
                'img_object_position' => '50% 50%',
                'featured' => true,
                'sort_order' => 3,
            ],

            // Aplikasi
            [
                'slug' => 'app-1',
                'tab' => 'aplikasi',
                'title' => 'EduLink SMP Founder Edition v2.0',
                'category' => 'Mobile App & EdTech',
                'description' => 'Aplikasi mobile terpadu berbasis Flutter untuk SMP yang menggabungkan QR Attendance Anti Curang (Dynamic TOTP 15s + Geofencing GPS), monitoring ortu realtime, dan gamifikasi.',
                'long_description' => 'Solusi digital komprehensif untuk ekosistem sekolah modern. Menghubungkan guru, siswa, dan orang tua dalam satu platform dengan push notification instan saat absensi, feed kelas privat aman, pengumpulan tugas foto/PDF, serta sistem XP & level cendekia.',
                'tags' => ['Flutter', 'Dart', 'Supabase', 'GPS Geofencing', 'TOTP QR', 'State Management'],
                'link' => '',
                'img' => '/projects/edulink-smp.jpg',
                'img_object_position' => '50% 50%',
                'featured' => true,
                'sort_order' => 1,
            ],
            [
                'slug' => 'app-2',
                'tab' => 'aplikasi',
                'title' => 'Kasir Warung POS Pintar',
                'category' => 'Point of Sale & UMKM',
                'description' => 'Aplikasi kasir mobile responsif untuk warung dan UMKM dengan pencatatan transaksi cepat, antarmuka Liquid Glass, integrasi pembayaran digital, dan sinkronisasi realtime.',
                'long_description' => 'Dirancang untuk mempercepat alur transaksi toko offline. Dilengkapi fitur kalkulasi kembalian otomatis, katalog produk berbasis kategori, struk digital, manajemen pesanan makan di tempat (Meja), serta rekap laporan keuangan harian dan bulanan.',
                'tags' => ['Flutter', 'Supabase', 'Dart', 'Local Storage', 'POS Engine'],
                'link' => '',
                'img' => '/projects/kasir-warung-mockup.jpg',
                'img_object_position' => '50% 50%',
                'featured' => true,
                'sort_order' => 2,
            ],
            [
                'slug' => 'app-3',
                'tab' => 'aplikasi',
                'title' => 'Media Pembelajaran Interaktif TK',
                'category' => 'Interactive EdTech App',
                'description' => 'Aplikasi mobile edukasi interaktif anak prasekolah dan TK dengan fitur pengenalan anggota tubuh 3D, kartu belajar fisik QR, modul menulis, dan audio ceria.',
                'long_description' => 'Membantu siswa usia prasekolah dan TK belajar membaca huruf alfabet, mengenal angka, mengenali anggota tubuh dengan model interaktif 3D, serta aktivitas fisik menggunakan QR card.',
                'tags' => ['Flutter', 'Interactive 3D', 'Audio Synthesis', 'Gamification'],
                'link' => '',
                'img' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=1200&q=80',
                'img_object_position' => '50% 50%',
                'featured' => false,
                'sort_order' => 3,
            ],

            // Desain
            [
                'slug' => 'des-1',
                'tab' => 'desain',
                'title' => 'Why Choose Tumbuh Kembang',
                'category' => 'Creative Campaign',
                'description' => 'Poster kampanye digital Tumbuh Kembang Group dengan konsep visual kontras tajam dan hierarki tipografi modern.',
                'long_description' => 'Dirancang dalam format 4:5 siap publikasi media sosial untuk menonjolkan proposisi nilai utama Tumbuh Kembang Group secara ringkas dan kuat.',
                'tags' => ['Figma', 'Social Media Design', 'Visual Branding'],
                'link' => '',
                'img' => '/projects/tumbuh-kembang-why-choose.jpg',
                'img_object_position' => '50% 42%',
                'featured' => true,
                'sort_order' => 1,
            ],
            [
                'slug' => 'des-2',
                'tab' => 'desain',
                'title' => 'Our Strategic Approach',
                'category' => 'Business Visual',
                'description' => 'Desain infografis pendekatan strategis bisnis Tumbuh Kembang Group yang merangkum 5 pilar utama ke dalam visual kunci ikonik.',
                'long_description' => 'Menyajikan struktur Business Positioning, Market Analysis, Growth Planning, Operational Improvement, dan Strategic Decision Making secara visual.',
                'tags' => ['Figma', 'Infographic', 'Visual Design'],
                'link' => '',
                'img' => '/projects/tumbuh-kembang-strategic-approach.jpg',
                'img_object_position' => '50% 46%',
                'featured' => true,
                'sort_order' => 2,
            ],
            [
                'slug' => 'des-3',
                'tab' => 'desain',
                'title' => 'Business Strategy Feed',
                'category' => 'Editorial Design',
                'description' => 'Desain editorial strategi bisnis bernuansa monokrom dengan aksen fokus oranye untuk komunikasi branding profesional.',
                'long_description' => 'Menggabungkan komposisi fotografi 3D catur dengan tipografi display tegas untuk konten edukasi strategi di media sosial.',
                'tags' => ['Figma', 'Editorial Design', 'Creative Direction'],
                'link' => '',
                'img' => '/projects/tumbuh-kembang-business-strategy.jpg',
                'img_object_position' => '50% 34%',
                'featured' => true,
                'sort_order' => 3,
            ],
            [
                'slug' => 'des-4',
                'tab' => 'desain',
                'title' => 'Maskot Koki Ceria',
                'category' => 'Vector Illustration',
                'description' => 'Desain maskot karakter vektor koki ceria dan aset ilustrasi digital komersial untuk kebutuhan merchandise, kemasan kuliner, dan materi promosi brand.',
                'long_description' => 'Eksplorasi karakter kartun yang ramah dan ekspresif dalam format vektor presisi, siap diaplikasikan pada skala cetak besar maupun antarmuka aplikasi digital.',
                'tags' => ['Adobe Illustrator', 'Vector Art', 'Character Design', 'Branding'],
                'link' => '',
                'img' => '/projects/maskot-koki-4x5.png',
                'img_object_position' => '50% 46%',
                'featured' => true,
                'sort_order' => 4,
            ],
            [
                'slug' => 'des-5',
                'tab' => 'desain',
                'title' => 'Tumbuh Kembang Our Services',
                'category' => 'Brand Showcase',
                'description' => 'Poster katalog layanan Tumbuh Kembang Group dengan metafora tab folder taktil berwarna oranye, hitam, dan abu cerah.',
                'long_description' => 'Menampilkan lini Creative, Consultant, Media, Transportation, dan Legal Tech dalam layout modern yang bersih dan mudah dipindai.',
                'tags' => ['Figma', 'Brand Design', 'Social Media Design'],
                'link' => '',
                'img' => '/projects/tumbuh-kembang-services.jpg',
                'img_object_position' => '50% 32%',
                'featured' => false,
                'sort_order' => 5,
            ],
            [
                'slug' => 'des-6',
                'tab' => 'desain',
                'title' => 'Kasir Warung POS UI',
                'category' => 'Mobile UI Design',
                'description' => 'Koleksi antarmuka aplikasi mobile Kasir Warung POS Pintar meliputi layar transaksi cepat, dashboard analitik, dan rekap belanja.',
                'long_description' => 'Sistem desain antarmuka seluler dengan kartu bersih, hierarki angka keuangan yang jelas, dan alur kasir responsif untuk UMKM.',
                'tags' => ['Figma', 'UI/UX Design', 'Mobile Interface'],
                'link' => '',
                'img' => '/projects/kasir-warung-ui-4x5.png',
                'img_object_position' => '50% 50%',
                'featured' => false,
                'sort_order' => 6,
            ],
        ];

        foreach ($projects as $project) {
            Project::updateOrCreate(['slug' => $project['slug']], $project);
        }

        // Gallery Photos
        if (GalleryPhoto::count() === 0) {
            $gallery = [
                [
                    'title' => 'Ekspedisi Puncak & Panorama Pegunungan',
                    'category' => 'Adventure & Outdoor',
                    'src' => '/gallery/mountain-summit.jpg',
                    'aspect_class' => 'aspect-[4/5]',
                    'width_scale' => 1,
                    'object_position' => '50% 50%',
                    'sort_order' => 1,
                ],
                [
                    'title' => 'Momen Spesial & Syukuran Karya',
                    'category' => 'Milestone',
                    'src' => '/gallery/celebration-cake.jpg',
                    'aspect_class' => 'aspect-[4/5]',
                    'width_scale' => 1,
                    'object_position' => '50% 50%',
                    'sort_order' => 2,
                ],
                [
                    'title' => 'Jelajah Pesisir & Refleksi Ide Kreatif',
                    'category' => 'Travel & Exploration',
                    'src' => '/gallery/beach-trip.jpg',
                    'aspect_class' => 'aspect-[4/5]',
                    'width_scale' => 1,
                    'object_position' => '50% 50%',
                    'sort_order' => 3,
                ],
                [
                    'title' => 'Trekking Jalur Kabut Dataran Tinggi',
                    'category' => 'Hiking & Life',
                    'src' => '/gallery/misty-trekking.jpg',
                    'aspect_class' => 'aspect-[4/5]',
                    'width_scale' => 1,
                    'object_position' => '50% 50%',
                    'sort_order' => 4,
                ],
                [
                    'title' => 'Peace Sign & Kebersamaan Hangat',
                    'category' => 'Moments & Memories',
                    'src' => '/gallery/peace-selfie.jpg',
                    'aspect_class' => 'aspect-[4/5]',
                    'width_scale' => 1,
                    'object_position' => '50% 50%',
                    'sort_order' => 5,
                ],
                [
                    'title' => 'Senyum & Inspirasi Santai',
                    'category' => 'Personal Life',
                    'src' => '/gallery/cheerful-smile.jpg',
                    'aspect_class' => 'aspect-[4/5]',
                    'width_scale' => 1,
                    'object_position' => '50% 50%',
                    'sort_order' => 6,
                ],
                [
                    'title' => 'Langkah Santai Menjelajah Hari',
                    'category' => 'Candid Snapshot',
                    'src' => '/gallery/candid-walk.jpg',
                    'aspect_class' => 'aspect-[4/5]',
                    'width_scale' => 1,
                    'object_position' => '50% 50%',
                    'sort_order' => 7,
                ],
                [
                    'title' => 'Kebersamaan Almamater UNIMMA',
                    'category' => 'Campus & Friendship',
                    'src' => '/gallery/campus-almamater.jpg',
                    'aspect_class' => 'aspect-[4/5]',
                    'width_scale' => 1,
                    'object_position' => '50% 85%',
                    'sort_order' => 8,
                ],
                [
                    'title' => 'Kebersamaan Tim di BTM AMMAN',
                    'category' => 'Teamwork & Moments',
                    'src' => '/gallery/btm-amman.jpg',
                    'aspect_class' => 'aspect-[4/3]',
                    'width_scale' => 1.28,
                    'object_position' => '48% 32%',
                    'sort_order' => 9,
                ],
            ];

            foreach ($gallery as $photo) {
                GalleryPhoto::create($photo);
            }
        }

        // Portfolio Profile & Hero Info
        if (PortfolioProfile::count() === 0) {
            PortfolioProfile::create([
                'hero_top_text' => 'WELCOME TO MY',
                'hero_bottom_text' => 'PORTOFOLIO',
                'greeting' => "Hi, I'm",
                'first_name' => 'Muhammad Raihan',
                'last_name' => 'Al Ashr',
                'full_name' => 'Muhammad Raihan Al Ashr',
                'bio' => 'Sebagai mahasiswa Teknik Informatika Universitas Muhammadiyah Magelang, Aku berfokus pada pengembangan teknologi yang tidak hanya fungsional, tetapi juga menghadirkan pengalaman digital yang menarik dan berdampak.',
                'resume_url' => '',
                'email' => 'banggob09@gmail.com',
                'location' => 'Magelang, Jawa Tengah',
                'avatar' => '/profile.png',
                'avatar_reveal' => '/PhotoBlackPanther.png',
                'contact_photo' => '/profile.jpg',
            ]);
        }

        // Education Timeline
        if (Education::count() === 0) {
            Education::create([
                'school' => 'Universitas Muhammadiyah Magelang',
                'degree' => 'S1 Teknik Informatika',
                'faculty' => 'Fakultas Teknik',
                'year' => '2025 – Sekarang',
                'tag' => 'Mahasiswa Aktif',
                'logo' => '/unimma_logo.png',
                'description' => 'Fokus pada Software Engineering, Web Development, Algoritma Pemrograman, dan Penerapan Solusi Teknologi Informasi.',
                'sort_order' => 1,
            ]);

            Education::create([
                'school' => 'SMK Muhammadiyah 1 Temanggung',
                'degree' => 'Desain Komunikasi Visual (DKV)',
                'faculty' => 'Vocational High School',
                'year' => '2022 – 2025',
                'tag' => 'Alumni',
                'logo' => '/smk_logo.png',
                'description' => 'Mempelajari prinsip desain grafis, ilustrasi digital, tipografi, visual branding, fotografi, dan dasar animasi multimedia.',
                'sort_order' => 2,
            ]);
        }

        // Experience Timeline
        if (Experience::count() === 0) {
            Experience::create([
                'company' => 'Tumbuh Kembang',
                'role' => 'Desainer & Web Developer',
                'year' => '2026',
                'subtitle' => 'Creative Agency & Web Development',
                'tag' => 'Design & Dev',
                'logo' => '/tumbuh_kembang_logo.png',
                'description' => 'Merancang visual identity, UI/UX antarmuka digital, serta membangun dan mengoptimalkan arsitektur website yang responsif, interaktif, dan modern.',
                'sort_order' => 1,
            ]);

            Experience::create([
                'company' => 'Kenangita',
                'role' => 'Editor & Fotografer',
                'year' => '2026',
                'subtitle' => 'Creative Photography & Production',
                'tag' => 'Photo & Video',
                'logo' => '/kenangita_logo.png',
                'description' => 'Memimpin produksi fotografi, color grading, retouching visual, serta editing konten video komersial dan dokumentasi kreatif.',
                'sort_order' => 2,
            ]);

            Experience::create([
                'company' => 'Akademi Kesehatan Muhammadiyah Temanggung',
                'role' => 'Desainer & Creative Social Media',
                'year' => '2024',
                'subtitle' => 'Creative Media & Visual Design',
                'tag' => 'Design & Social Media',
                'logo' => '/akmt_logo.png',
                'description' => 'Merancang desain grafis visual, materi publikasi kampus, serta memproduksi konten kreatif media sosial untuk memperkuat branding dan komunikasi digital.',
                'sort_order' => 3,
            ]);
        }

        // Social Links
        if (SocialLink::count() === 0) {
            SocialLink::create([
                'display_name' => 'GitHub',
                'sub_text' => 'Jelajahi Source Code',
                'url' => 'https://github.com/bujangngnap',
                'type' => 'github',
                'sort_order' => 1,
            ]);

            SocialLink::create([
                'display_name' => 'LinkedIn',
                'sub_text' => 'Koneksi Profesional',
                'url' => 'https://www.linkedin.com/in/muhammad-al-ashr-a371a540b/',
                'type' => 'linkedin',
                'sort_order' => 2,
            ]);

            SocialLink::create([
                'display_name' => 'Instagram',
                'sub_text' => 'Karya Desain & Portofolio',
                'url' => 'https://instagram.com',
                'type' => 'instagram',
                'sort_order' => 3,
            ]);

            SocialLink::create([
                'display_name' => 'TikTok',
                'sub_text' => 'Konten Tech & Kreatif',
                'url' => 'https://tiktok.com',
                'type' => 'tiktok',
                'sort_order' => 4,
            ]);
        }
    }
}
