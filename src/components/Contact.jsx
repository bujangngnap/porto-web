import React, { useState, useRef, useEffect } from 'react';
import { motion } from 'framer-motion';
import {
  Send,
  User,
  Mail,
  MessageSquare,
  MessageCircle,
  ImagePlus,
  CheckCircle,
  X
} from 'lucide-react';
import { SocialBrandIcon } from './Icons';
import confetti from 'canvas-confetti';
import FluxLines, { FLUX_LIGHT_PRESET } from './FluxLines';
import { resolveApiUrl, resolveMediaUrl } from '../lib/api';

export default function Contact({ personal, socialLinks, hideBackground = false }) {
  // Left column: Direct "Hubungi" form state
  const [contactForm, setContactForm] = useState({ name: '', email: '', message: '' });
  const [contactSubmitting, setContactSubmitting] = useState(false);
  const [contactSuccess, setContactSuccess] = useState(false);
  const [contactError, setContactError] = useState('');
  const [copiedEmail, setCopiedEmail] = useState(false);

  // Right column: "Tulis Komentar" form state
  const [commentForm, setCommentForm] = useState({ name: '', message: '', avatar: '', avatarFile: null });
  const [commentSubmitting, setCommentSubmitting] = useState(false);
  const [commentSuccess, setCommentSuccess] = useState(false);
  const [commentError, setCommentError] = useState('');
  const [avatarError, setAvatarError] = useState('');
  const [dbConnected, setDbConnected] = useState(false);
  const [commentsLoading, setCommentsLoading] = useState(true);
  const fileInputRef = useRef(null);

  // Live comments fetched directly from Laravel SQLite API (syncs on mount and tab focus)
  const [comments, setComments] = useState([]);

  useEffect(() => {
    let active = true;

    const loadComments = async () => {
      try {
        const res = await fetch(resolveApiUrl('/api/comments'), {
          headers: { Accept: 'application/json' },
          cache: 'no-store',
        });
        const data = res.ok ? await res.json().catch(() => null) : null;
        if (!active || !data || !Array.isArray(data.comments)) return;
        setComments(data.comments);
        setDbConnected(true);
      } catch {
        if (active) setDbConnected(false);
      } finally {
        if (active) setCommentsLoading(false);
      }
    };

    loadComments();

    const handleRefresh = () => {
      if (document.visibilityState !== 'hidden') {
        loadComments();
      }
    };

    window.addEventListener('focus', handleRefresh);
    document.addEventListener('visibilitychange', handleRefresh);

    return () => {
      active = false;
      window.removeEventListener('focus', handleRefresh);
      document.removeEventListener('visibilitychange', handleRefresh);
    };
  }, []);

  const handleCopyEmail = () => {
    navigator.clipboard.writeText(personal.email);
    setCopiedEmail(true);
    setTimeout(() => setCopiedEmail(false), 2500);
  };

  const handleContactSubmit = async (e) => {
    e.preventDefault();
    if (!contactForm.name.trim() || !contactForm.email.trim() || !contactForm.message.trim()) return;

    setContactSubmitting(true);
    setContactError('');
    setContactSuccess(false);

    try {
      const response = await fetch(resolveApiUrl('/api/contact'), {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Accept: 'application/json',
        },
        body: JSON.stringify({
          name: contactForm.name.trim(),
          email: contactForm.email.trim(),
          message: contactForm.message.trim(),
        }),
      });

      const payload = await response.json().catch(() => ({}));
      if (!response.ok) {
        const firstErr = payload?.errors ? Object.values(payload.errors).flat()[0] : null;
        throw new Error(firstErr || payload?.message || 'Gagal mengirim pesan.');
      }

      setContactSuccess(true);
      setContactForm({ name: '', email: '', message: '' });

      confetti({
        particleCount: 70,
        spread: 65,
        origin: { y: 0.7 }
      });

      setTimeout(() => setContactSuccess(false), 4000);
    } catch (err) {
      setContactError(err.message || 'Server Laravel belum aktif. Coba lagi sebentar.');
    } finally {
      setContactSubmitting(false);
    }
  };

  const handleFileChange = (e) => {
    const file = e.target.files?.[0];
    setAvatarError('');
    if (!file) return;

    if (file.size > 5 * 1024 * 1024) {
      setAvatarError('Ukuran file maksimal 5MB.');
      return;
    }

    const reader = new FileReader();
    reader.onload = () => {
      if (typeof reader.result === 'string') {
        setCommentForm((prev) => ({ ...prev, avatar: reader.result, avatarFile: file }));
      }
    };
    reader.readAsDataURL(file);
  };

  const handleCommentSubmit = async (e) => {
    e.preventDefault();
    if (!commentForm.name.trim() || !commentForm.message.trim()) return;

    setCommentSubmitting(true);
    setCommentError('');
    setCommentSuccess(false);

    try {
      const formData = new FormData();
      formData.append('name', commentForm.name.trim());
      formData.append('message', commentForm.message.trim());
      if (commentForm.avatarFile) {
        formData.append('avatar', commentForm.avatarFile);
      }

      const response = await fetch(resolveApiUrl('/api/comments'), {
        method: 'POST',
        headers: {
          Accept: 'application/json',
        },
        body: formData,
      });

      const payload = await response.json().catch(() => ({}));
      if (!response.ok || !payload.comment) {
        const firstErr = payload?.errors ? Object.values(payload.errors).flat()[0] : null;
        throw new Error(firstErr || payload?.message || 'Gagal menyimpan komentar.');
      }

      setComments((prev) => [payload.comment, ...prev]);
      setDbConnected(true);
      setCommentSuccess(true);
      setCommentForm({ name: '', message: '', avatar: '', avatarFile: null });
      if (fileInputRef.current) fileInputRef.current.value = '';

      confetti({
        particleCount: 80,
        spread: 70,
        origin: { y: 0.7 }
      });

      setTimeout(() => setCommentSuccess(false), 4000);
    } catch (err) {
      setCommentError(err.message || 'Server Laravel belum aktif. Coba lagi sebentar.');
    } finally {
      setCommentSubmitting(false);
    }
  };

  return (
    <section
      id="Contact"
      className={`relative z-10 w-full py-24 sm:py-28 text-zinc-900 overflow-hidden ${
        hideBackground ? 'bg-transparent' : 'bg-[#fafafa]'
      }`}
    >
      {/* Interactive FluxLines fluid backdrop matching light sections */}
      {!hideBackground && (
        <div className="absolute inset-0 pointer-events-none">
          <FluxLines {...FLUX_LIGHT_PRESET} />
        </div>
      )}

      <div className="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Section Title */}
        <div className="text-center mb-12 sm:mb-14">
          <motion.h2
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            className="text-4xl sm:text-5xl font-black text-zinc-900 tracking-tight"
            style={{ fontFamily: 'var(--font-display)' }}
          >
            Hubungi Saya
          </motion.h2>
        </div>

        {/* Main 2-Column Layout matching reference */}
        <div className="grid grid-cols-1 lg:grid-cols-[380px_1fr] gap-6 lg:gap-8 items-start">
          {/* LEFT COLUMN */}
          <div className="space-y-6">
            {/* Card 1: "Hubungi" Direct Message Box */}
            <div className="bg-white rounded-3xl p-6 sm:p-7 shadow-[0_24px_60px_-14px_rgba(15,23,42,0.16),0_12px_28px_-8px_rgba(15,23,42,0.10),0_3px_10px_rgba(15,23,42,0.06)] border border-zinc-200/80">
              <h3
                className="text-2xl font-bold text-[#2c67ed] mb-1.5"
                style={{ fontFamily: 'var(--font-display)' }}
              >
                Hubungi
              </h3>
              <p className="text-sm text-zinc-500 leading-relaxed mb-6">
                Ada yang mau didiskusikan? Langsung kirim pesan aja ya..!
              </p>

              <form onSubmit={handleContactSubmit} className="space-y-3.5">
                {/* Nama Anda */}
                <div className="relative flex items-center">
                  <User className="w-4 h-4 text-zinc-400 absolute left-4 pointer-events-none" />
                  <input
                    type="text"
                    required
                    value={contactForm.name}
                    onChange={(e) => setContactForm({ ...contactForm, name: e.target.value })}
                    placeholder="Nama Anda"
                    className="w-full pl-11 pr-4 py-3 rounded-xl bg-[#f4f5f7] border border-zinc-200/50 focus:border-[#2c67ed] focus:bg-white focus:outline-none text-sm text-zinc-900 placeholder:text-zinc-400 shadow-[0_2px_8px_rgba(15,23,42,0.04)] transition-all"
                  />
                </div>

                {/* Email Anda */}
                <div className="relative flex items-center">
                  <Mail className="w-4 h-4 text-zinc-400 absolute left-4 pointer-events-none" />
                  <input
                    type="email"
                    required
                    value={contactForm.email}
                    onChange={(e) => setContactForm({ ...contactForm, email: e.target.value })}
                    placeholder="Email Anda"
                    className="w-full pl-11 pr-4 py-3 rounded-xl bg-[#f4f5f7] border border-zinc-200/50 focus:border-[#2c67ed] focus:bg-white focus:outline-none text-sm text-zinc-900 placeholder:text-zinc-400 shadow-[0_2px_8px_rgba(15,23,42,0.04)] transition-all"
                  />
                </div>

                {/* Pesan Anda */}
                <div className="relative">
                  <MessageSquare className="w-4 h-4 text-zinc-400 absolute left-4 top-3.5 pointer-events-none" />
                  <textarea
                    required
                    rows={4}
                    value={contactForm.message}
                    onChange={(e) => setContactForm({ ...contactForm, message: e.target.value })}
                    placeholder="Pesan Anda"
                    className="w-full pl-11 pr-4 py-3 rounded-xl bg-[#f4f5f7] border border-zinc-200/50 focus:border-[#2c67ed] focus:bg-white focus:outline-none text-sm text-zinc-900 placeholder:text-zinc-400 shadow-[0_2px_8px_rgba(15,23,42,0.04)] transition-all resize-none"
                  />
                </div>

                <button
                  type="submit"
                  disabled={contactSubmitting}
                  className="w-full py-3.5 px-5 rounded-xl bg-[#2c67ed] hover:bg-[#1d4ed8] text-white font-semibold text-sm flex items-center justify-center gap-2 shadow-[0_6px_18px_rgba(44,103,237,0.28)] transition-all hover:scale-[1.01] active:scale-[0.99] disabled:opacity-50 cursor-pointer"
                >
                  <Send className="w-4 h-4" />
                  <span>{contactSubmitting ? "Mengirim..." : "Kirim Pesan"}</span>
                </button>

                {contactSuccess && (
                  <motion.div
                    initial={{ opacity: 0, y: 6 }}
                    animate={{ opacity: 1, y: 0 }}
                    className="flex items-center justify-center gap-1.5 text-xs font-semibold text-emerald-600 pt-1"
                  >
                    <CheckCircle className="w-4 h-4" />
                    <span>Pesan terkirim! Terima kasih.</span>
                  </motion.div>
                )}

                {contactError && (
                  <p className="text-xs font-medium text-rose-500 text-center pt-1">{contactError}</p>
                )}
              </form>
            </div>

            {/* Card 2: Social Links & Direct Info */}
            <div className="bg-white rounded-3xl p-6 shadow-[0_24px_60px_-14px_rgba(15,23,42,0.16),0_12px_28px_-8px_rgba(15,23,42,0.10),0_3px_10px_rgba(15,23,42,0.06)] border border-zinc-200/80 space-y-4">
              <div className="flex items-center gap-2.5 pb-3 border-b border-zinc-100">
                <div className="w-1 h-4 rounded-full bg-[#2c67ed]" />
                <h4 className="text-sm font-bold text-zinc-900" style={{ fontFamily: 'var(--font-display)' }}>
                  Terhubung Dengan Saya
                </h4>
              </div>

              <div className="space-y-2.5">
                {socialLinks.map((link) => (
                  <a
                    key={link.name}
                    href={link.url}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="group flex items-center justify-between p-3 rounded-xl bg-[#f8f9fb] hover:bg-blue-50/70 border border-zinc-200/70 hover:border-blue-200 shadow-[0_4px_12px_-2px_rgba(15,23,42,0.06),0_1px_4px_rgba(15,23,42,0.04)] hover:shadow-[0_10px_22px_-6px_rgba(44,103,237,0.16),0_4px_10px_rgba(15,23,42,0.06)] transition-all duration-200"
                  >
                    <div className="flex items-center gap-3">
                      <SocialBrandIcon
                        type={link.type}
                        className="w-9 h-9 flex-shrink-0 rounded-lg shadow-sm transition-transform duration-200 group-hover:scale-105"
                      />
                      <div>
                        <div className="text-xs font-bold text-zinc-900 group-hover:text-[#2c67ed] transition-colors">
                          {link.displayName}
                        </div>
                        <div className="text-[11px] text-zinc-500">{link.subText}</div>
                      </div>
                    </div>
                    <span className="text-[11px] font-semibold text-zinc-400 group-hover:text-[#2c67ed] transition-colors">
                      Kunjungi →
                    </span>
                  </a>
                ))}
              </div>

              {/* Direct Email Copy */}
              <div className="p-3 rounded-xl bg-[#f8f9fb] border border-zinc-200/70 shadow-[0_4px_12px_-2px_rgba(15,23,42,0.06),0_1px_4px_rgba(15,23,42,0.04)] flex items-center justify-between gap-2">
                <div className="flex items-center gap-2.5 min-w-0">
                  <div className="w-8 h-8 rounded-lg bg-white border border-zinc-200/80 shadow-sm flex items-center justify-center text-[#2c67ed] flex-shrink-0">
                    <Mail className="w-3.5 h-3.5" />
                  </div>
                  <div className="min-w-0">
                    <div className="text-[10px] text-zinc-400 font-medium">Email Langsung</div>
                    <div className="text-xs font-semibold text-zinc-800 truncate">{personal.email}</div>
                  </div>
                </div>
                <button
                  type="button"
                  onClick={handleCopyEmail}
                  className="px-2.5 py-1.5 rounded-lg text-[11px] font-semibold bg-white hover:bg-[#2c67ed] text-zinc-700 hover:text-white border border-zinc-200 shadow-sm transition-colors cursor-pointer flex-shrink-0"
                >
                  {copiedEmail ? "Tersalin ✓" : "Salin"}
                </button>
              </div>

              {/* Portrait Snapshot */}
              <div className="overflow-hidden rounded-2xl border border-zinc-200/80 shadow-[0_10px_24px_-8px_rgba(15,23,42,0.16)] bg-zinc-100 aspect-[10/9]">
                <img
                  src={resolveMediaUrl(personal.contactPhoto || '/profile.jpg')}
                  alt={personal.name || "Profile photo"}
                  loading="eager"
                  className="w-full h-full object-cover object-[50%_35%]"
                />
              </div>
            </div>
          </div>

          {/* RIGHT COLUMN: "Tulis Komentar" Card */}
          <div className="bg-white rounded-3xl p-6 sm:p-8 shadow-[0_24px_60px_-14px_rgba(15,23,42,0.16),0_12px_28px_-8px_rgba(15,23,42,0.10),0_3px_10px_rgba(15,23,42,0.06)] border border-zinc-200/80">
            {/* Header Row with Divider */}
            <div className="flex items-center gap-3 pb-4 mb-6 border-b border-zinc-100">
              <div className="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center text-[#2c67ed]">
                <MessageCircle className="w-4 h-4" />
              </div>
              <h3
                className="text-lg font-bold text-zinc-900"
                style={{ fontFamily: 'var(--font-display)' }}
              >
                Tulis Komentar
              </h3>
            </div>

            <form onSubmit={handleCommentSubmit} className="space-y-4">
              {/* Nama */}
              <div>
                <label className="block text-xs font-bold text-zinc-800 mb-1.5">
                  Nama
                </label>
                <input
                  type="text"
                  required
                  value={commentForm.name}
                  onChange={(e) => setCommentForm({ ...commentForm, name: e.target.value })}
                  placeholder="Masukkan nama kamu"
                  className="w-full px-4 py-3 rounded-xl bg-[#f4f5f7] border border-zinc-200/50 focus:border-[#2c67ed] focus:bg-white focus:outline-none text-sm text-zinc-900 placeholder:text-zinc-400 shadow-[0_2px_8px_rgba(15,23,42,0.04)] transition-all"
                />
              </div>

              {/* Komentar */}
              <div>
                <label className="block text-xs font-bold text-zinc-800 mb-1.5">
                  Komentar
                </label>
                <textarea
                  required
                  rows={4}
                  value={commentForm.message}
                  onChange={(e) => setCommentForm({ ...commentForm, message: e.target.value })}
                  placeholder="Tulis komentar kamu di sini..."
                  className="w-full px-4 py-3 rounded-xl bg-[#f4f5f7] border border-zinc-200/50 focus:border-[#2c67ed] focus:bg-white focus:outline-none text-sm text-zinc-900 placeholder:text-zinc-400 shadow-[0_2px_8px_rgba(15,23,42,0.04)] transition-all resize-none"
                />
              </div>

              {/* Foto Profil (opsional) */}
              <div>
                <label className="block text-xs font-bold text-zinc-800 mb-1.5">
                  Foto Profil <span className="font-normal text-zinc-400">(opsional)</span>
                </label>

                <input
                  ref={fileInputRef}
                  type="file"
                  accept="image/*"
                  onChange={handleFileChange}
                  className="hidden"
                />

                {!commentForm.avatar ? (
                  <button
                    type="button"
                    onClick={() => fileInputRef.current?.click()}
                    className="w-full py-2.5 px-4 rounded-xl bg-[#f8f9fb] hover:bg-[#f0f2f5] border border-zinc-200 shadow-[0_2px_8px_rgba(15,23,42,0.05)] flex items-center justify-center gap-2 text-sm font-medium text-zinc-700 transition-colors cursor-pointer"
                  >
                    <ImagePlus className="w-4 h-4 text-zinc-600" />
                    <span>Choose Profile Photo</span>
                  </button>
                ) : (
                  <div className="flex items-center justify-between px-4 py-2 rounded-xl bg-blue-50/70 border border-blue-200">
                    <div className="flex items-center gap-3">
                      <img
                        src={commentForm.avatar}
                        alt="Avatar preview"
                        className="w-8 h-8 rounded-full object-cover border border-white shadow-sm"
                      />
                      <span className="text-xs font-semibold text-zinc-800">Foto profil siap digunakan</span>
                    </div>
                    <button
                      type="button"
                      onClick={() => {
                        setCommentForm((prev) => ({ ...prev, avatar: '', avatarFile: null }));
                        if (fileInputRef.current) fileInputRef.current.value = '';
                      }}
                      className="p-1 rounded-full hover:bg-white text-zinc-500 hover:text-rose-500 transition-colors cursor-pointer"
                      title="Hapus foto"
                    >
                      <X className="w-4 h-4" />
                    </button>
                  </div>
                )}

                {avatarError ? (
                  <p className="text-[11px] text-rose-500 text-center mt-1.5">{avatarError}</p>
                ) : (
                  <p className="text-[11px] text-zinc-400 text-center mt-1.5">Max file size: 5MB</p>
                )}
              </div>

              {/* Submit Button */}
              <button
                type="submit"
                disabled={commentSubmitting}
                className="w-full py-3.5 px-5 rounded-xl bg-[#2c67ed] hover:bg-[#1d4ed8] text-white font-semibold text-sm flex items-center justify-center gap-2 shadow-[0_6px_18px_rgba(44,103,237,0.28)] transition-all hover:scale-[1.01] active:scale-[0.99] disabled:opacity-50 cursor-pointer"
              >
                <Send className="w-4 h-4" />
                <span>{commentSubmitting ? "Mengirim..." : "Kirim Komentar"}</span>
              </button>

              {commentSuccess && (
                <motion.div
                  initial={{ opacity: 0, y: 6 }}
                  animate={{ opacity: 1, y: 0 }}
                  className="flex items-center justify-center gap-1.5 text-xs font-semibold text-emerald-600"
                >
                  <CheckCircle className="w-4 h-4" />
                  <span>Komentar berhasil ditambahkan!</span>
                </motion.div>
              )}

              {commentError && (
                <p className="text-xs font-medium text-rose-500 text-center">{commentError}</p>
              )}
            </form>

            {/* Recent Comments List inside card */}
            <div className="mt-8 pt-6 border-t border-zinc-100">
              <div className="flex items-center justify-between mb-4">
                <span className="text-xs font-bold uppercase tracking-wider text-zinc-400">
                  Komentar Pengunjung ({comments.length})
                </span>
                {dbConnected ? (
                  <span className="inline-flex items-center gap-1.5 text-[11px] font-semibold text-emerald-600">
                    <span className="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse" />
                    Live
                  </span>
                ) : commentsLoading ? (
                  <span className="text-[11px] font-medium text-zinc-400">Memuat...</span>
                ) : (
                  <span className="text-[11px] font-medium text-zinc-400">Offline</span>
                )}
              </div>

              <div className="space-y-3 max-h-64 overflow-y-auto pr-1">
                {comments.length === 0 ? (
                  <div className="p-4 rounded-2xl bg-[#f8f9fb] border border-dashed border-zinc-200 text-center text-xs text-zinc-500">
                    {commentsLoading
                      ? 'Memuat komentar pengunjung...'
                      : dbConnected
                        ? 'Belum ada komentar pengunjung. Jadilah yang pertama menulis komentar!'
                        : 'Komentar belum tersedia karena server backend belum terhubung.'}
                  </div>
                ) : (
                  comments.map((item) => (
                    <div
                      key={item.id}
                      className="p-3.5 rounded-2xl bg-[#f8f9fb] border border-zinc-200/70 shadow-[0_4px_12px_-2px_rgba(15,23,42,0.06),0_1px_4px_rgba(15,23,42,0.04)] flex items-start gap-3"
                    >
                      {item.avatar ? (
                        <img
                          src={resolveMediaUrl(item.avatar)}
                          alt={item.name}
                          className="w-9 h-9 rounded-full object-cover flex-shrink-0 border border-white shadow-sm"
                        />
                      ) : (
                        <div className="w-9 h-9 rounded-full bg-blue-100 text-[#2c67ed] font-bold text-xs flex items-center justify-center flex-shrink-0">
                          {item.name.charAt(0).toUpperCase()}
                        </div>
                      )}
                      <div className="flex-1 min-w-0">
                        <div className="flex items-center justify-between gap-2">
                          <span className="text-xs font-bold text-zinc-900 truncate">{item.name}</span>
                          <span className="text-[10px] text-zinc-400 flex-shrink-0">{item.date}</span>
                        </div>
                        <p className="mt-1 text-xs text-zinc-600 leading-relaxed">{item.message}</p>
                      </div>
                    </div>
                  ))
                )}
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
