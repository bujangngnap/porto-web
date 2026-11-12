import React, { useEffect, useState } from 'react';
import { motion } from 'framer-motion';
import {
  ArrowLeft,
  ArrowRight,
  ChevronDown,
  ChevronUp,
  ChevronRight,
  Code2,
  ExternalLink,
  Layers,
  Smartphone,
  Database,
  Palette,
  Globe,
  Cpu,
} from 'lucide-react';

function getTechIcon(tag) {
  const lower = String(tag).toLowerCase();
  if (/figma|illustrator|design|branding|ui\/ux|motion|vector|character|3d|webgl|three/.test(lower)) {
    return Palette;
  }
  if (/flutter|dart|mobile|pos|gps/.test(lower)) {
    return Smartphone;
  }
  if (/mysql|supabase|database|storage/.test(lower)) {
    return Database;
  }
  if (/flask|python|laravel|php|api|server/.test(lower)) {
    return Cpu;
  }
  if (/react|tailwind|blade|html|css|javascript|web/.test(lower)) {
    return Code2;
  }
  if (/chart|totp|hyperframes|video|sound|editing/.test(lower)) {
    return Layers;
  }
  return Globe;
}

/**
 * Full-page project showcase replacing the modal popup.
 * Presents project details, expandable overview, technologies used, and preview media.
 */
export default function ProjectDetailPage({
  project,
  categoryLabel = 'Projects',
  siblingProjects = [],
  onSelectProject,
  onBack,
}) {
  const [expanded, setExpanded] = useState(false);

  // Allow Escape key to return to the portfolio list
  useEffect(() => {
    const handleKeyDown = (e) => {
      if (e.key === 'Escape') {
        onBack?.();
      }
    };
    window.addEventListener('keydown', handleKeyDown);
    return () => window.removeEventListener('keydown', handleKeyDown);
  }, [onBack]);

  if (!project) return null;

  const hasLongDescription =
    Boolean(project.longDescription) && project.longDescription.trim() !== project.description?.trim();

  const currentIdx = siblingProjects.findIndex((item) => item.id === project.id);
  const prevProject = currentIdx > 0 ? siblingProjects[currentIdx - 1] : null;
  const nextProject =
    currentIdx >= 0 && currentIdx < siblingProjects.length - 1 ? siblingProjects[currentIdx + 1] : null;

  return (
    <motion.div
      key={project.id}
      initial={{ opacity: 0, y: 12 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ duration: 0.28, ease: [0.16, 1, 0.3, 1] }}
      className="relative min-h-screen w-full bg-[#030712] text-white overflow-x-clip"
    >
      {/* Subtle atmospheric glow */}
      <div
        className="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,rgba(44,103,237,0.14),transparent_55%),radial-gradient(ellipse_at_bottom_left,rgba(30,58,138,0.1),transparent_50%)]"
        aria-hidden="true"
      />

      <div className="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-7 sm:pt-12 pb-16 sm:pb-20">
        {/* Top Back Button & Breadcrumb */}
        <div className="flex flex-wrap items-center gap-3 sm:gap-4 mb-8 sm:mb-12">
          <button
            type="button"
            onClick={onBack}
            className="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/[0.05] hover:bg-white/[0.1] border border-white/10 hover:border-white/20 text-zinc-200 text-xs sm:text-sm font-medium transition-colors cursor-pointer"
          >
            <ArrowLeft className="w-4 h-4" />
            <span>Back</span>
          </button>

          <nav aria-label="Breadcrumb" className="flex items-center gap-1.5 sm:gap-2 text-xs sm:text-sm min-w-0">
            <button
              type="button"
              onClick={onBack}
              className="text-zinc-500 hover:text-zinc-300 transition-colors cursor-pointer flex-shrink-0"
            >
              {categoryLabel}
            </button>
            <ChevronRight className="w-3.5 h-3.5 text-zinc-600 flex-shrink-0" />
            <span className="text-zinc-200 font-medium truncate">{project.title}</span>
          </nav>
        </div>

        {/* Main 2-Column Content */}
        <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 sm:gap-10 lg:gap-14 items-start">
          {/* Left Column: Title, Description, Technologies, Actions */}
          <div className="lg:col-span-6 space-y-7">
            <div>
              {project.category && (
                <span className="inline-block mb-3 px-3 py-1 rounded-full text-[11px] font-semibold bg-[#2c67ed]/15 text-blue-300 border border-[#2c67ed]/30">
                  {project.category}
                </span>
              )}

              <h1
                className="text-3xl sm:text-4xl lg:text-[2.75rem] font-extrabold text-white tracking-tight leading-[1.12]"
                style={{ fontFamily: 'var(--font-display)' }}
              >
                {project.title}
              </h1>

              <div className="mt-4 sm:mt-5 space-y-3 text-sm sm:text-base text-slate-300 leading-relaxed font-normal">
                <p>{project.description}</p>

                {hasLongDescription && (
                  <div>
                    {expanded ? (
                      <p className="text-slate-300/90">{project.longDescription}</p>
                    ) : (
                      <p className="text-slate-400 line-clamp-1">{project.longDescription}</p>
                    )}

                    <button
                      type="button"
                      onClick={() => setExpanded((prev) => !prev)}
                      className="mt-2 inline-flex items-center gap-1.5 text-sm font-medium text-[#5c90ff] hover:text-[#86adff] transition-colors cursor-pointer"
                    >
                      <span>{expanded ? 'Lihat lebih sedikit' : 'Lihat selengkapnya'}</span>
                      {expanded ? <ChevronUp className="w-4 h-4" /> : <ChevronDown className="w-4 h-4" />}
                    </button>
                  </div>
                )}
              </div>
            </div>

            {/* Technologies Used */}
            {project.tags?.length > 0 && (
              <div className="pt-1">
                <div className="flex items-center gap-2 text-sm sm:text-base font-bold text-white mb-3.5">
                  <Code2 className="w-4 h-4 text-zinc-400" />
                  <h2 style={{ fontFamily: 'var(--font-display)' }}>Technologies Used</h2>
                </div>

                <div className="flex flex-wrap gap-2.5">
                  {project.tags.map((tag) => {
                    const TechIcon = getTechIcon(tag);
                    return (
                      <span
                        key={tag}
                        className="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white/[0.04] border border-white/10 hover:border-white/20 text-zinc-200 text-xs sm:text-sm font-medium transition-colors"
                      >
                        <TechIcon className="w-3.5 h-3.5 text-zinc-400" />
                        <span>{tag}</span>
                      </span>
                    );
                  })}
                </div>
              </div>
            )}

            {/* Action Links (shown when available) */}
            {project.link && (
              <div className="flex flex-wrap items-center gap-3 pt-2">
                <a
                  href={project.link}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold bg-[#2c67ed] hover:bg-[#1d4ed8] text-white shadow-[0_8px_20px_rgba(44,103,237,0.3)] transition-colors"
                >
                  <ExternalLink className="w-4 h-4" />
                  <span>Live Website</span>
                </a>
              </div>
            )}
          </div>

          {/* Right Column: Showcase Media Frame */}
          <div className="lg:col-span-6">
            <div className="w-fit max-w-full mx-auto rounded-2xl sm:rounded-3xl bg-white/[0.04] border border-white/10 p-3 sm:p-4 shadow-[0_24px_70px_-15px_rgba(0,0,0,0.8)]">
              <div className="rounded-xl sm:rounded-2xl overflow-hidden bg-[#0a0f1f] border border-white/5">
                <img
                  src={project.img}
                  alt={project.title}
                  className="max-w-full w-auto h-auto max-h-[540px] object-contain mx-auto block"
                />
              </div>
            </div>
          </div>
        </div>

        {/* Bottom Prev / Next Project Switcher */}
        {(prevProject || nextProject) && onSelectProject && (
          <div className="mt-14 pt-7 border-t border-white/10 flex flex-wrap items-center justify-between gap-4">
            {prevProject ? (
              <button
                type="button"
                onClick={() => onSelectProject(prevProject)}
                className="inline-flex items-center gap-2.5 text-left px-4 py-2.5 rounded-xl bg-white/[0.04] hover:bg-white/[0.08] border border-white/10 transition-colors cursor-pointer max-w-full sm:max-w-[46%]"
              >
                <ArrowLeft className="w-4 h-4 text-zinc-400 flex-shrink-0" />
                <span className="text-xs sm:text-sm font-medium text-zinc-200 truncate">
                  {prevProject.title}
                </span>
              </button>
            ) : (
              <div />
            )}

            {nextProject && (
              <button
                type="button"
                onClick={() => onSelectProject(nextProject)}
                className="ml-auto inline-flex items-center gap-2.5 text-right px-4 py-2.5 rounded-xl bg-white/[0.04] hover:bg-white/[0.08] border border-white/10 transition-colors cursor-pointer max-w-full sm:max-w-[46%]"
              >
                <span className="text-xs sm:text-sm font-medium text-zinc-200 truncate">
                  {nextProject.title}
                </span>
                <ArrowRight className="w-4 h-4 text-zinc-400 flex-shrink-0" />
              </button>
            )}
          </div>
        )}
      </div>
    </motion.div>
  );
}
