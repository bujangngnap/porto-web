export const portfolioData = {
  personal: {
    name: "Muhammad Raihan Al Ashr",
    firstName: "Muhammad Raihan",
    lastName: "Al Ashr",
    nickname: "Raihan",
    greeting: "Hi, I'm",
    role: "Web Developer & Tech Enthusiast",
    tagline: "Mahasiswa Teknik Informatika · Universitas Muhammadiyah Magelang | Web Developer & Tech Enthusiast",
    bio: "Sebagai mahasiswa Teknik Informatika Universitas Muhammadiyah Magelang, Aku berfokus pada pengembangan teknologi yang tidak hanya fungsional, tetapi juga menghadirkan pengalaman digital yang menarik dan berdampak.",
    campus: "Universitas Muhammadiyah Magelang",
    degree: "S1 Teknik Informatika",
    avatar: "/profile.png", // Foto profil jas hitam resmi
    avatarReveal: "/PhotoBlackPanther.png", // Easter-egg flashlight reveal: Black Panther
    resumeUrl: "#",
    email: "banggob09@gmail.com",
    location: "Magelang, Jawa Tengah",
    status: "Mahasiswa Aktif · Siap untuk Proyek & Kolaborasi",
  },

  academicStats: [
    { number: "S1", label: "Jenjang", desc: "Sarjana" },
    { number: "TI", label: "Prodi", desc: "Teknik Informatika" },
    { number: "UMM", label: "Kampus", desc: "Univ. Muhammadiyah Magelang" }
  ],

  stats: {
    totalProjects: 6,
    totalCertificates: 8,
    totalAwards: 3,
    yearsExperience: "3+",
  },

  education: [
    {
      id: "edu-1",
      school: "Universitas Muhammadiyah Magelang",
      degree: "S1 Teknik Informatika",
      faculty: "Fakultas Teknik",
      subInfo: "Undergraduate • Mahasiswa Aktif",
      year: "2025 – Sekarang",
      tag: "Mahasiswa Aktif",
      logo: "/unimma_logo.png",
      description: "Fokus pada Software Engineering, Web Development, Algoritma Pemrograman, dan Penerapan Solusi Teknologi Informasi."
    },
    {
      id: "edu-2",
      school: "SMK Muhammadiyah 1 Temanggung",
      degree: "Desain Komunikasi Visual (DKV)",
      faculty: "Vocational High School",
      subInfo: "DKV • Alumni",
      year: "2022 – 2025",
      tag: "Alumni",
      logo: "/smk_logo.png",
      description: "Mempelajari prinsip desain grafis, ilustrasi digital, tipografi, visual branding, fotografi, dan dasar animasi multimedia."
    }
  ],

  experience: [
    {
      id: "exp-1",
      company: "Tumbuh Kembang",
      role: "Desainer & Web Developer",
      year: "2026",
      subtitle: "Creative Agency & Web Development",
      tag: "Design & Dev",
      logo: "/tumbuh_kembang_logo.png",
      description: "Merancang visual identity, UI/UX antarmuka digital, serta membangun dan mengoptimalkan arsitektur website yang responsif, interaktif, dan modern."
    },
    {
      id: "exp-2",
      company: "Kenangita",
      role: "Editor & Fotografer",
      year: "2026",
      subtitle: "Creative Photography & Production",
      tag: "Photo & Video",
      logo: "/kenangita_logo.png",
      description: "Memimpin produksi fotografi, color grading, retouching visual, serta editing konten video komersial dan dokumentasi kreatif."
    },
    {
      id: "exp-3",
      company: "Akademi Kesehatan Muhammadiyah Temanggung",
      role: "Desainer & Creative Social Media",
      year: "2024",
      subtitle: "Creative Media & Visual Design",
      tag: "Design & Social Media",
      logo: "/akmt_logo.png",
      description: "Merancang desain grafis visual, materi publikasi kampus, serta memproduksi konten kreatif media sosial untuk memperkuat branding dan komunikasi digital."
    }
  ],

  skillCategories: [
    {
      category: "Mobile & Frontend",
      skills: ["Flutter", "Dart", "React.js", "Vite", "Tailwind CSS", "JavaScript (ES6+)", "HTML5 / CSS3"]
    },
    {
      category: "Backend & Database",
      skills: ["Supabase", "Firebase", "PostgreSQL", "RESTful APIs", "Node.js", "Authentication & RLS"]
    },
    {
      category: "Design & Creative",
      skills: ["Figma", "UI/UX Design", "Motion Graphics", "Hyperframes", "Blender 3D", "Adobe Illustrator"]
    },
    {
      category: "DevOps & Tools",
      skills: ["Git & GitHub", "Android Studio", "VS Code", "Vercel", "Postman", "Linux"]
    }
  ],

  projectsWebsite: [
    {
      id: "web-1",
      title: "SSB Garuda Muda Website",
      category: "Sports Academy Web",
      description: "Website profil Sekolah Sepak Bola SSB Garuda Muda berbasis Laravel dengan informasi visi dan misi, profil pelatih, fasilitas latihan, pilihan paket pembinaan, serta pendaftaran anggota.",
      longDescription: "Platform web yang dibangun menggunakan Laravel dan Blade untuk memperkenalkan ekosistem pembinaan atlet sepak bola usia dini di SSB Garuda Muda, lengkap dengan profil tim pelatih, rincian fasilitas lapangan, katalog paket latihan, serta alur pendaftaran siswa baru.",
      tags: ["Laravel", "PHP", "Blade", "MySQL", "Tailwind CSS"],
      link: "",
      github: "",
      img: "/projects/ssb-garuda-muda-web.jpg",
      imgObjectPosition: "50% 42%",
      featured: true,
    },
    {
      id: "web-2",
      title: "Tumbuh Kembang Group Website",
      category: "Corporate & Agency Web",
      description: "Website company profile Tumbuh Kembang Group bertema gelap modern dengan dukungan dwibahasa (ID/EN), serta showcase layanan branding, media kreatif, dan inovasi digital.",
      longDescription: "Platform web interaktif yang dirancang untuk merepresentasikan ekosistem layanan Tumbuh Kembang Group, mulai dari strategi branding, produksi videografi sinematik, pengembangan digital & web, hingga solusi mobilitas dan konsultasi bisnis.",
      tags: ["React.js", "Tailwind CSS", "JavaScript", "UI/UX Design", "Bilingual ID/EN"],
      link: "https://www.tumbuhkembanggroup.com/",
      github: "",
      img: "/projects/tumbuh-kembang-web.jpg",
      imgObjectPosition: "50% 54%",
      featured: true,
    },
    {
      id: "web-4",
      title: "Kenangita Photobooth Website",
      category: "Event & Creative Web",
      description: "Website layanan photobooth Kenangita bernuansa editorial hangat dengan galeri interaktif, katalog paket acara, testimoni klien, pemesanan online, mode gelap, dan dukungan dwibahasa.",
      longDescription: "Platform web untuk layanan photobooth gathering, ulang tahun, pernikahan, wisuda, dan acara korporat yang dilengkapi etalase paket interaktif, galeri dokumentasi acara, fitur kemitraan, serta alur reservasi yang cepat dan responsif.",
      tags: ["React.js", "Tailwind CSS", "JavaScript", "UI/UX Design", "Dark Mode"],
      link: "https://kenangitaphotobooth.id/",
      github: "",
      img: "/projects/kenangita-web.jpg",
      imgObjectPosition: "50% 50%",
      featured: true,
    }
  ],

  projectsAplikasi: [
    {
      id: "app-1",
      title: "EduLink SMP Founder Edition v2.0",
      category: "Mobile App & EdTech",
      description: "Aplikasi mobile terpadu berbasis Flutter untuk SMP yang menggabungkan QR Attendance Anti Curang (Dynamic TOTP 15s + Geofencing GPS), monitoring ortu realtime, dan gamifikasi.",
      longDescription: "Solusi digital komprehensif untuk ekosistem sekolah modern. Menghubungkan guru, siswa, dan orang tua dalam satu platform dengan push notification instan saat absensi, feed kelas privat aman, pengumpulan tugas foto/PDF, serta sistem XP & level cendekia.",
      tags: ["Flutter", "Dart", "Supabase", "GPS Geofencing", "TOTP QR", "State Management"],
      link: "",
      github: "https://github.com",
      img: "/projects/edulink-smp.jpg",
      featured: true,
    },
    {
      id: "app-2",
      title: "Kasir Warung POS Pintar",
      category: "Point of Sale & UMKM",
      description: "Aplikasi kasir mobile responsif untuk warung dan UMKM dengan pencatatan transaksi cepat, antarmuka Liquid Glass, integrasi pembayaran digital, dan sinkronisasi realtime.",
      longDescription: "Dirancang untuk mempercepat alur transaksi toko offline. Dilengkapi fitur kalkulasi kembalian otomatis, katalog produk berbasis kategori, struk digital, manajemen pesanan makan di tempat (Meja), serta rekap laporan keuangan harian dan bulanan.",
      tags: ["Flutter", "Supabase", "Dart", "Local Storage", "POS Engine"],
      link: "",
      github: "https://github.com",
      img: "/projects/kasir-warung-mockup.jpg",
      featured: true,
    },
    {
      id: "app-3",
      title: "Media Pembelajaran Interaktif TK",
      category: "Interactive EdTech App",
      description: "Aplikasi mobile edukasi interaktif anak prasekolah dan TK dengan fitur pengenalan anggota tubuh 3D, kartu belajar fisik QR, modul menulis, dan audio ceria.",
      longDescription: "Membantu siswa usia prasekolah dan TK belajar membaca huruf alfabet, mengenal angka, mengenali anggota tubuh dengan model interaktif 3D, serta aktivitas fisik menggunakan QR card.",
      tags: ["Flutter", "Interactive 3D", "Audio Synthesis", "Gamification"],
      link: "",
      github: "https://github.com",
      img: "https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=1200&q=80",
      featured: false,
    }
  ],

  projectsDesain: [
    {
      id: "des-1",
      title: "Why Choose Tumbuh Kembang",
      category: "Creative Campaign",
      description: "Poster kampanye digital Tumbuh Kembang Group dengan konsep visual kontras tajam dan hierarki tipografi modern.",
      longDescription: "Dirancang dalam format 4:5 siap publikasi media sosial untuk menonjolkan proposisi nilai utama Tumbuh Kembang Group secara ringkas dan kuat.",
      tags: ["Figma", "Social Media Design", "Visual Branding"],
      link: "",
      github: "",
      img: "/projects/tumbuh-kembang-why-choose.jpg",
      imgObjectPosition: "50% 42%",
      featured: true,
    },
    {
      id: "des-2",
      title: "Our Strategic Approach",
      category: "Business Visual",
      description: "Desain infografis pendekatan strategis bisnis Tumbuh Kembang Group yang merangkum 5 pilar utama ke dalam visual kunci ikonik.",
      longDescription: "Menyajikan struktur Business Positioning, Market Analysis, Growth Planning, Operational Improvement, dan Strategic Decision Making secara visual.",
      tags: ["Figma", "Infographic", "Visual Design"],
      link: "",
      github: "",
      img: "/projects/tumbuh-kembang-strategic-approach.jpg",
      imgObjectPosition: "50% 46%",
      featured: true,
    },
    {
      id: "des-3",
      title: "Business Strategy Feed",
      category: "Editorial Design",
      description: "Desain editorial strategi bisnis bernuansa monokrom dengan aksen fokus oranye untuk komunikasi branding profesional.",
      longDescription: "Menggabungkan komposisi fotografi 3D catur dengan tipografi display tegas untuk konten edukasi strategi di media sosial.",
      tags: ["Figma", "Editorial Design", "Creative Direction"],
      link: "",
      github: "",
      img: "/projects/tumbuh-kembang-business-strategy.jpg",
      imgObjectPosition: "50% 34%",
      featured: true,
    },
    {
      id: "des-4",
      title: "Maskot Koki Ceria",
      category: "Vector Illustration",
      description: "Desain maskot karakter vektor koki ceria dan aset ilustrasi digital komersial untuk kebutuhan merchandise, kemasan kuliner, dan materi promosi brand.",
      longDescription: "Eksplorasi karakter kartun yang ramah dan ekspresif dalam format vektor presisi, siap diaplikasikan pada skala cetak besar maupun antarmuka aplikasi digital.",
      tags: ["Adobe Illustrator", "Vector Art", "Character Design", "Branding"],
      link: "",
      github: "",
      img: "/projects/maskot-koki-4x5.png",
      imgObjectPosition: "50% 46%",
      featured: true,
    },
    {
      id: "des-5",
      title: "Tumbuh Kembang Our Services",
      category: "Brand Showcase",
      description: "Poster katalog layanan Tumbuh Kembang Group dengan metafora tab folder taktil berwarna oranye, hitam, dan abu cerah.",
      longDescription: "Menampilkan lini Creative, Consultant, Media, Transportation, dan Legal Tech dalam layout modern yang bersih dan mudah dipindai.",
      tags: ["Figma", "Brand Design", "Social Media Design"],
      link: "",
      github: "",
      img: "/projects/tumbuh-kembang-services.jpg",
      imgObjectPosition: "50% 32%",
      featured: false,
    },
    {
      id: "des-6",
      title: "Kasir Warung POS UI",
      category: "Mobile UI Design",
      description: "Koleksi antarmuka aplikasi mobile Kasir Warung POS Pintar meliputi layar transaksi cepat, dashboard analitik, dan rekap belanja.",
      longDescription: "Sistem desain antarmuka seluler dengan kartu bersih, hierarki angka keuangan yang jelas, dan alur kasir responsif untuk UMKM.",
      tags: ["Figma", "UI/UX Design", "Mobile Interface"],
      link: "",
      github: "",
      img: "/projects/kasir-warung-ui-4x5.png",
      imgObjectPosition: "50% 50%",
      featured: false,
    }
  ],

  designSlides: [
    {
      image: { src: "/projects/tumbuh-kembang-why-choose.jpg", alt: "Why Businesses Should Choose Tumbuh Kembang Group" },
      title: "Why Choose\nTumbuh Kembang",
    },
    {
      image: { src: "/projects/tumbuh-kembang-strategic-approach.jpg", alt: "Tumbuh Kembang Our Strategic Approach" },
      title: "Our Strategic\nApproach",
    },
    {
      image: { src: "/projects/tumbuh-kembang-business-strategy.jpg", alt: "Tumbuh Kembang Business Strategy" },
      title: "Business\nStrategy",
    },
    {
      image: { src: "/projects/maskot-koki-4x5.png", alt: "Maskot Koki Ceria Vector Illustration" },
      title: "Maskot Koki Ceria\nVector Art",
    },
    {
      image: { src: "/projects/tumbuh-kembang-services.jpg", alt: "Tumbuh Kembang Our Services" },
      title: "Tumbuh Kembang\nOur Services",
    },
    {
      image: { src: "/projects/kasir-warung-ui-4x5.png", alt: "Kasir Warung POS Mobile UI Design Showcase" },
      title: "Kasir Warung POS\nUI Design",
    },
  ],

  projects: [
    {
      id: 1,
      title: "EduLink SMP Founder Edition v2.0",
      category: "Mobile App & EdTech Ecosystem",
      description: "Aplikasi mobile terpadu berbasis Flutter untuk SMP yang menggabungkan QR Attendance Anti Curang (Dynamic TOTP 15s + Geofencing GPS), monitoring ortu realtime, dan gamifikasi.",
      longDescription: "Solusi digital komprehensif untuk ekosistem sekolah modern. Menghubungkan guru, siswa, dan orang tua dalam satu platform dengan push notification instan saat absensi, feed kelas privat aman, pengumpulan tugas foto/PDF, serta sistem XP & level cendekia.",
      tags: ["Flutter", "Dart", "Supabase", "GPS Geofencing", "TOTP QR", "State Management"],
      link: "",
      github: "https://github.com",
      img: "/projects/edulink-smp.jpg",
      featured: true,
    },
    {
      id: 2,
      title: "Kasir Warung POS Pintar",
      category: "Point of Sale & Business System",
      description: "Aplikasi kasir mobile responsif untuk warung dan UMKM dengan pencatatan transaksi cepat, manajemen stok otomatis, dan sinkronisasi cloud realtime.",
      longDescription: "Dirancang untuk mempercepat alur transaksi toko offline. Dilengkapi fitur kalkulasi kembalian otomatis, katalog produk berbasis kategori, struk digital, serta rekap laporan keuangan harian dan bulanan.",
      tags: ["Flutter", "Supabase", "Dart", "Local Storage", "POS Engine"],
      link: "",
      github: "https://github.com",
      img: "/projects/kasir-warung-mockup.jpg",
      featured: true,
    },
    {
      id: 3,
      title: "SSB Garuda Muda Website",
      category: "Sports Academy Web",
      description: "Website profil Sekolah Sepak Bola SSB Garuda Muda berbasis Laravel dengan informasi visi dan misi, profil pelatih, fasilitas latihan, pilihan paket pembinaan, serta pendaftaran anggota.",
      longDescription: "Platform web yang dibangun menggunakan Laravel dan Blade untuk memperkenalkan ekosistem pembinaan atlet sepak bola usia dini di SSB Garuda Muda, lengkap dengan profil tim pelatih, rincian fasilitas lapangan, katalog paket latihan, serta alur pendaftaran siswa baru.",
      tags: ["Laravel", "PHP", "Blade", "MySQL", "Tailwind CSS"],
      link: "",
      github: "",
      img: "/projects/ssb-garuda-muda-web.jpg",
      imgObjectPosition: "50% 42%",
      featured: true,
    },
    {
      id: 4,
      title: "Our Strategic Approach",
      category: "Business Visual",
      description: "Desain infografis pendekatan strategis bisnis Tumbuh Kembang Group yang merangkum 5 pilar utama ke dalam visual kunci ikonik.",
      longDescription: "Menyajikan struktur Business Positioning, Market Analysis, Growth Planning, Operational Improvement, dan Strategic Decision Making secara visual.",
      tags: ["Figma", "Infographic", "Visual Design"],
      link: "",
      github: "",
      img: "/projects/tumbuh-kembang-strategic-approach.jpg",
      imgObjectPosition: "50% 46%",
      featured: true,
    },
    {
      id: 5,
      title: "Tumbuh Kembang Group Website",
      category: "Corporate & Agency Web",
      description: "Website company profile Tumbuh Kembang Group bertema gelap modern dengan dukungan dwibahasa (ID/EN), serta showcase layanan branding, media kreatif, dan inovasi digital.",
      longDescription: "Platform web interaktif yang dirancang untuk merepresentasikan ekosistem layanan Tumbuh Kembang Group, mulai dari strategi branding, produksi videografi sinematik, pengembangan digital & web, hingga solusi mobilitas dan konsultasi bisnis.",
      tags: ["React.js", "Tailwind CSS", "JavaScript", "UI/UX Design", "Bilingual ID/EN"],
      link: "https://www.tumbuhkembanggroup.com/",
      github: "",
      img: "/projects/tumbuh-kembang-web.jpg",
      imgObjectPosition: "50% 54%",
      featured: true,
    },
    {
      id: 6,
      title: "Why Choose Tumbuh Kembang",
      category: "Creative Campaign",
      description: "Poster kampanye digital Tumbuh Kembang Group dengan konsep visual kontras tajam dan hierarki tipografi modern.",
      longDescription: "Dirancang dalam format 4:5 siap publikasi media sosial untuk menonjolkan proposisi nilai utama Tumbuh Kembang Group secara ringkas dan kuat.",
      tags: ["Figma", "Social Media Design", "Visual Branding"],
      link: "",
      github: "",
      img: "/projects/tumbuh-kembang-why-choose.jpg",
      imgObjectPosition: "50% 42%",
      featured: false,
    },
    {
      id: 7,
      title: "Kenangita Photobooth Website",
      category: "Event & Creative Web",
      description: "Website layanan photobooth Kenangita bernuansa editorial hangat dengan galeri interaktif, katalog paket acara, testimoni klien, pemesanan online, mode gelap, dan dukungan dwibahasa.",
      longDescription: "Platform web untuk layanan photobooth gathering, ulang tahun, pernikahan, wisuda, dan acara korporat yang dilengkapi etalase paket interaktif, galeri dokumentasi acara, fitur kemitraan, serta alur reservasi yang cepat dan responsif.",
      tags: ["React.js", "Tailwind CSS", "JavaScript", "UI/UX Design", "Dark Mode"],
      link: "https://kenangitaphotobooth.id/",
      github: "",
      img: "/projects/kenangita-web.jpg",
      imgObjectPosition: "50% 50%",
      featured: true,
    }
  ],

  certificatesSkills: [
    {
      id: "cert-s-1",
      title: "Flutter & Dart Mobile App Development",
      issuer: "Professional Certificate",
      year: "2026",
      img: "https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=800&q=80",
      badge: "Mobile Dev"
    },
    {
      id: "cert-s-2",
      title: "Modern Frontend Web Development",
      issuer: "Professional Certificate",
      year: "2025",
      img: "https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=800&q=80",
      badge: "Web Frontend"
    },
    {
      id: "cert-s-3",
      title: "UI/UX Design & Prototyping Mastery",
      issuer: "Design Academy",
      year: "2025",
      img: "https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?auto=format&fit=crop&w=800&q=80",
      badge: "UI/UX Design"
    },
    {
      id: "cert-s-4",
      title: "Cloud Database & Backend with Supabase",
      issuer: "Developer Certification",
      year: "2025",
      img: "https://images.unsplash.com/photo-1627398242454-45a1465c2479?auto=format&fit=crop&w=800&q=80",
      badge: "Backend & DB"
    }
  ],

  certificatesAwards: [
    {
      id: "cert-a-1",
      title: "Best Mobile Innovation & UX Concept",
      issuer: "National Tech Summit",
      year: "2026",
      img: "https://images.unsplash.com/photo-1567427017947-545c5f8d16ad?auto=format&fit=crop&w=800&q=80",
      badge: "1st Place"
    },
    {
      id: "cert-a-2",
      title: "Finalist Digital Product Competition",
      issuer: "Creative App Hackathon",
      year: "2025",
      img: "https://images.unsplash.com/photo-1531403009284-440f080d1e12?auto=format&fit=crop&w=800&q=80",
      badge: "Finalist"
    },
    {
      id: "cert-a-3",
      title: "Outstanding Motion Graphics Project Award",
      issuer: "Digital Media Exhibition",
      year: "2025",
      img: "https://images.unsplash.com/photo-1579389083078-4e7018379f7e?auto=format&fit=crop&w=800&q=80",
      badge: "Best Visual"
    }
  ],

  gallery: [
    {
      id: 1,
      title: "Ekspedisi Puncak & Panorama Pegunungan",
      category: "Adventure & Outdoor",
      src: "/gallery/mountain-summit.jpg"
    },
    {
      id: 2,
      title: "Momen Spesial & Syukuran Karya",
      category: "Milestone",
      src: "/gallery/celebration-cake.jpg"
    },
    {
      id: 3,
      title: "Jelajah Pesisir & Refleksi Ide Kreatif",
      category: "Travel & Exploration",
      src: "/gallery/beach-trip.jpg"
    },
    {
      id: 4,
      title: "Trekking Jalur Kabut Dataran Tinggi",
      category: "Hiking & Life",
      src: "/gallery/misty-trekking.jpg"
    },
    {
      id: 5,
      title: "Peace Sign & Kebersamaan Hangat",
      category: "Moments & Memories",
      src: "/gallery/peace-selfie.jpg"
    },
    {
      id: 6,
      title: "Senyum & Inspirasi Santai",
      category: "Personal Life",
      src: "/gallery/cheerful-smile.jpg"
    },
    {
      id: 7,
      title: "Langkah Santai Menjelajah Hari",
      category: "Candid Snapshot",
      src: "/gallery/candid-walk.jpg"
    },
    {
      id: 8,
      title: "Kebersamaan Almamater UNIMMA",
      category: "Campus & Friendship",
      src: "/gallery/campus-almamater.jpg",
      objectPosition: "50% 85%"
    },
    {
      id: 9,
      title: "Kebersamaan Tim di BTM AMMAN",
      category: "Teamwork & Moments",
      src: "/gallery/btm-amman.jpg",
      aspectClass: "aspect-[4/3]",
      widthScale: 1.28,
      objectPosition: "48% 32%"
    }
  ],

  socialLinks: [
    {
      name: "GitHub",
      displayName: "GitHub",
      subText: "Jelajahi Source Code",
      url: "https://github.com/bujangngnap",
      color: "from-zinc-700 to-zinc-900",
      type: "github"
    },
    {
      name: "LinkedIn",
      displayName: "LinkedIn",
      subText: "Koneksi Profesional",
      url: "https://www.linkedin.com/in/muhammad-al-ashr-a371a540b/",
      color: "from-blue-600 to-cyan-600",
      type: "linkedin"
    },
    {
      name: "Instagram",
      displayName: "Instagram",
      subText: "Karya Desain & Portofolio",
      url: "https://www.instagram.com/tasr_png?obrf=MXUyZXIycnBrZjU5MA==",
      color: "from-pink-500 via-purple-500 to-amber-500",
      type: "instagram"
    },
    {
      name: "TikTok",
      displayName: "TikTok",
      subText: "Konten Tech & Kreatif",
      url: "https://tiktok.com",
      color: "from-slate-900 via-black to-zinc-900",
      type: "tiktok"
    }
  ]
};
