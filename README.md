# Portofolio Interaktif (Inspirasi Eka Wahyu)

Website portofolio modern & interaktif yang terinspirasi langsung dari [ekawahyu-portofolio.vercel.app](https://ekawahyu-portofolio.vercel.app/), dibangun menggunakan **React + Vite + Tailwind CSS + Framer Motion**.

---

## 🌟 Fitur Utama

1. **Interactive Welcome Screen**:
   - Canvas particle text animation (*"WELCOME TO MY PORTOFOLIO WEBSITE"*).
   - Loading bar realtime (0% hingga 100%) dengan tombol *Skip Intro*.
   - Transisi exit halus dengan blur & zoom effect.

2. **Cinematic Multi-Layer Parallax Hero**:
   - Parallax scrolling multi-layer menggunakan aset SVG asli (Sky & Moon, Mountain Ridge, Ground Silhouette, dan Forest Canopy).
   - Tipografi besar & dinamis dengan scaling responsif dan transisi halus ke section berikutnya.

3. **Floating Glassmorphic Navbar & Dock**:
   - Floating pill island di bagian atas dengan efek backdrop blur.
   - Active section indicator dengan spring physics animation.
   - Menu responsive untuk tampilan mobile.

4. **About Me with Flashlight Spotlight Easter Egg**:
   - Layout 3-kolom modern dengan ringkasan filosofi, profil, dan tombol *View CV* & *Projects*.
   - **Spotlight Reveal Photo Card**: Arahkan kursor ke foto profil untuk memunculkan efek lampu senter / spotlight dinamis yang mengungkap persona rahasia (Spider-Man suit easter egg!).
   - **Dual Interactive Timelines**: Education & Experience dengan interactive highlight dot dan tahun.
   - **Skill Dossier & Tech Stack**: Tab filter kategori skill (Frontend, Backend & Cloud, UI/UX, Tools).

5. **Showcase Portofolio (Horizontal Carousel & Tabs)**:
   - Tab switcher: **Projects**, **Sertifikat Keahlian**, dan **Sertifikat Prestasi**.
   - Carousel horizontal geser mulus dengan tombol navigasi panah kiri & kanan.
   - Modal detail interaktif untuk setiap proyek dengan deskripsi lengkap, tech tags, dan link live demo / GitHub.
   - Modal Lightbox untuk melihat sertifikat dalam resolusi penuh.

6. **3D Perspective Photo Gallery**:
   - Grid galeri foto dokumentasi aktivitas dan kompetisi dengan efek 3D tilt yang merespons pergerakan kursor mouse.
   - Modal zoom foto saat diklik.

7. **Contact & Interactive Guestbook**:
   - Kartu profil media sosial (LinkedIn, GitHub, Instagram, TikTok).
   - Fitur cepat salin alamat email.
   - Form kirim pesan interaktif dengan efek perayaan konfeti (*canvas-confetti*) dan penyimpanan pesan realtime di browser (*LocalStorage*).

---

## 🚀 Cara Menjalankan Project

### 1. Install Dependencies
```bash
npm install
```

### 2. Jalankan Server Development
```bash
npm run dev
```
Buka browser di URL yang muncul di terminal (biasanya `http://localhost:5173`).

### 3. Build untuk Production
```bash
npm run build
```
File hasil build akan berada di folder `dist/` dan siap di-deploy ke Vercel atau Netlify.

---

## 🛠️ Cara Mengubah Data Portofolio (Customization)

Cukup edit satu file:  
👉 **`src/data/portfolioData.js`**

Di dalam file tersebut kamu bisa mengganti:
- Nama, sapaan, role, bio, dan link CV
- Foto profil (`avatar` dan `avatarReveal`)
- Angka statistik (jumlah proyek, sertifikat, award)
- Riwayat pendidikan dan pengalaman kerja/organisasi
- Daftar proyek, deskripsi, screenshot, dan link live/github
- Sertifikat keahlian & penghargaan prestasi
- Galeri foto kegiatan
- Akun sosial media (LinkedIn, GitHub, Instagram, TikTok)
