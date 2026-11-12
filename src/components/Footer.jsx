import React from 'react';
import { ArrowUp, Heart } from 'lucide-react';

export default function Footer({ name = "Muhammad Raihan Al Ashr" }) {
  const scrollToTop = () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  return (
    <footer className="relative w-full py-12 bg-[#02050e] border-t border-white/10 text-slate-400 select-none">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-6">
        <div className="flex items-center gap-3">
          <div className="w-8 h-8 rounded-full bg-blue-600/20 border border-blue-500/30 flex items-center justify-center text-blue-400 font-black text-sm">
            {name ? name.charAt(0) : "R"}
          </div>
          <div>
            <div className="text-sm font-bold text-white tracking-tight">
              {name}
            </div>
            <p className="text-xs text-slate-500">
              Web Developer &amp; Tech Enthusiast
            </p>
          </div>
        </div>

        <div className="text-xs text-slate-500 text-center flex items-center gap-1">
          <span>© {new Date().getFullYear()} {name}. Built with</span>
          <Heart className="w-3.5 h-3.5 text-rose-500 fill-rose-500 inline" />
          <span>and React.</span>
        </div>

        <button
          onClick={scrollToTop}
          className="p-2.5 rounded-full bg-white/[0.05] hover:bg-white/15 text-slate-300 hover:text-white border border-white/10 transition-all duration-200 hover:scale-105"
          aria-label="Back to top"
        >
          <ArrowUp className="w-4 h-4" />
        </button>
      </div>
    </footer>
  );
}
