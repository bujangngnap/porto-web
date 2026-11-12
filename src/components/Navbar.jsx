import React, { useState, useEffect, useRef } from 'react';
import { motion, AnimatePresence } from 'framer-motion';

const navItems = [
  { name: "Home", url: "#Home" },
  { name: "About", url: "#About" },
  { name: "Portfolio", url: "#Portofolio" },
  { name: "Contact", url: "#Contact" },
];

export default function Navbar({ activeSection = "Home" }) {
  const [mounted, setMounted] = useState(false);
  const [hoveredTab, setHoveredTab] = useState(null);
  const [activeTab, setActiveTab] = useState(activeSection || "Home");
  const [isLightMode, setIsLightMode] = useState(false);
  const isClickingRef = useRef(false);

  useEffect(() => {
    setMounted(true);
  }, []);

  // Update activeTab when activeSection prop changes (from external scroll watcher)
  useEffect(() => {
    if (!isClickingRef.current) {
      const match = navItems.find(
        (item) =>
          item.name.toLowerCase() === activeSection.toLowerCase() ||
          (item.name === "Portfolio" && activeSection.toLowerCase() === "portofolio")
      );
      if (match) {
        setActiveTab(match.name);
      }
    }
  }, [activeSection]);

  // Track scroll position to adapt light/dark mode and active section
  useEffect(() => {
    let ticking = false;

    const handleScroll = () => {
      ticking = false;

      // Check if inside light section (Portofolio Gallery, PhotoGallery, Contact, or Footer)
      const gallery = document.getElementById("Portofolio-Gallery");
      const portal = document.getElementById("Portofolio");
      const photoGallery = document.getElementById("Gallery");
      const contact = document.getElementById("Contact");
      const footer = document.getElementById("Footer");
      let light = false;
      if (gallery) {
        const rect = gallery.getBoundingClientRect();
        const progress = portal ? parseFloat(portal.dataset.gpProgress || "0") : 0;
        if (progress >= 0.72 || (rect.top <= 120 && rect.bottom >= 120)) {
          light = true;
        }
      }
      if (photoGallery) {
        const pRect = photoGallery.getBoundingClientRect();
        if (pRect.top <= 120 && pRect.bottom >= 120) {
          light = true;
        }
      }
      if (contact) {
        const cRect = contact.getBoundingClientRect();
        if (cRect.top <= 120 && cRect.bottom >= 120) {
          light = true;
        }
      }
      if (footer) {
        const fRect = footer.getBoundingClientRect();
        if (fRect.top <= 120 && fRect.bottom >= 120) {
          light = true;
        }
      }
      setIsLightMode(light);

      if (isClickingRef.current) return;

      const triggerY = window.innerHeight * 0.38;
      let current = navItems[0].name;

      for (const item of navItems) {
        const el = document.querySelector(item.url);
        if (el && el.getBoundingClientRect().top <= triggerY) {
          current = item.name;
        }
      }

      setActiveTab((prev) => (prev !== current ? current : prev));
    };

    const onScroll = () => {
      if (!ticking) {
        requestAnimationFrame(handleScroll);
        ticking = true;
      }
    };

    window.addEventListener("scroll", onScroll, { passive: true });
    window.addEventListener("resize", onScroll, { passive: true });
    handleScroll();

    return () => {
      window.removeEventListener("scroll", onScroll);
      window.removeEventListener("resize", onScroll);
    };
  }, []);

  const handleClick = (e, url, name) => {
    e.preventDefault();
    const target = document.querySelector(url);
    if (target) {
      isClickingRef.current = true;
      setActiveTab(name);

      const offset = url === "#Portofolio" || url === "#Home" ? 0 : 60;
      const top = Math.max(0, target.getBoundingClientRect().top + window.scrollY - offset);

      if (window.lenis) {
        window.lenis.scrollTo(top, { duration: 0.9 });
      } else {
        window.scrollTo({ top, behavior: "smooth" });
      }

      setTimeout(() => {
        isClickingRef.current = false;
      }, 1000);
    }
  };

  if (!mounted) return null;

  return (
    <div className="fixed top-3 md:top-5 left-0 right-0 z-[9999] pointer-events-none">
      <div className="flex justify-center">
        <motion.nav
          initial={{ y: -20, opacity: 0 }}
          animate={{ y: 0, opacity: 1 }}
          transition={{ duration: 0.35, ease: [0.22, 1, 0.36, 1] }}
          className={`pointer-events-auto flex items-center gap-1 sm:gap-2 py-1.5 px-2 rounded-full relative backdrop-blur-md transition-[background-color,box-shadow,border-color] duration-300 border ${
            isLightMode
              ? "bg-white/75 border-zinc-200/80 shadow-[inset_0_1px_0_rgba(255,255,255,0.95),0_12px_28px_rgba(15,23,42,0.10),0_2px_8px_rgba(15,23,42,0.05)]"
              : "bg-slate-950/30 border-white/10 shadow-[inset_0_1px_0_rgba(255,255,255,0.20),0_8px_24px_rgba(0,0,0,0.25)]"
          }`}
        >
          {navItems.map((item) => {
            const isActive = activeTab === item.name;
            const isHovered = hoveredTab === item.name;

            return (
              <a
                key={item.name}
                href={item.url}
                onClick={(e) => handleClick(e, item.url, item.name)}
                onMouseEnter={() => setHoveredTab(item.name)}
                onMouseLeave={() => setHoveredTab(null)}
                className={`relative cursor-pointer text-xs md:text-sm font-bold px-4 py-2 md:px-6 md:py-2.5 rounded-full transition-colors duration-200 select-none ${
                  isActive
                    ? "text-white"
                    : isLightMode
                    ? "text-zinc-800 hover:text-zinc-950"
                    : "text-white/95 hover:text-white drop-shadow-[0_1px_2px_rgba(0,0,0,0.45)]"
                }`}
              >
                {/* Active Solid Blue Pill */}
                {isActive && (
                  <motion.div
                    layoutId="active-nav-pill"
                    className="absolute inset-0 rounded-full -z-10 bg-[#2c67ed] shadow-[0_6px_16px_rgba(44,103,237,0.35)]"
                    transition={{ type: "spring", stiffness: 320, damping: 32 }}
                  />
                )}

                {/* Text Label */}
                <span className="relative z-10">{item.name}</span>

                {/* Hover Soft Backdrop */}
                <AnimatePresence>
                  {isHovered && !isActive && (
                    <motion.div
                      initial={{ opacity: 0, scale: 0.95 }}
                      animate={{ opacity: 1, scale: 1 }}
                      exit={{ opacity: 0, scale: 0.95 }}
                      transition={{ duration: 0.15 }}
                      className={`absolute inset-0 rounded-full -z-10 ${
                        isLightMode ? "bg-zinc-900/[0.06]" : "bg-white/15"
                      }`}
                    />
                  )}
                </AnimatePresence>
              </a>
            );
          })}
        </motion.nav>
      </div>
    </div>
  );
}
