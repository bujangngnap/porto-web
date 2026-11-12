import React, { useState } from 'react';
import { motion } from 'framer-motion';
import { Mail, GraduationCap, Briefcase, Award, Code2, Sparkles, FolderGit2, CheckCircle2 } from 'lucide-react';
import SpotlightCard from './SpotlightCard';
import GlassFolder from './GlassFolder';

function TimelineItem({
  index,
  isLast,
  isActive,
  dimmed,
  onHover,
  logo,
  title,
  year,
  heading,
  metaLeft,
  metaBadge,
  fallbackIcon,
  fallbackIconClass,
  description,
}) {
  return (
    <div
      onMouseEnter={onHover}
      className={`relative flex items-start group transition-all duration-300 cursor-pointer ${
        isLast ? "pb-1" : "pb-6 sm:pb-7"
      } ${!dimmed || isActive ? "opacity-100" : "opacity-45 hover:opacity-90"}`}
    >
      {/* Connecting line between nodes only */}
      {!isLast && (
        <div className="absolute left-[17px] top-9 bottom-0 w-[2px] bg-white/15 z-0" aria-hidden="true" />
      )}

      {/* Number badge */}
      <div className="relative z-10 flex-shrink-0 mr-3.5 sm:mr-5">
        <div
          className={`w-9 h-9 rounded-full flex items-center justify-center text-xs sm:text-sm font-bold transition-all duration-300 ${
            isActive
              ? "bg-white text-zinc-950 shadow-[0_0_20px_rgba(255,255,255,0.35)] ring-4 ring-white/15 scale-105"
              : "bg-zinc-900/90 text-zinc-400 border border-white/20 group-hover:border-white/40 group-hover:text-zinc-200"
          }`}
        >
          {index + 1}
        </div>
      </div>

      {/* Content */}
      <div className="flex-1 min-w-0 pt-0.5">
        {/* Top row: Logo aligned with number badge + Title + Year pill on sm+ */}
        <div className="flex items-start gap-2.5 sm:gap-3">
          {logo ? (
            <div className="w-7 h-7 sm:w-8 sm:h-8 mt-0.5 sm:mt-0 rounded-full bg-white p-0.5 flex items-center justify-center overflow-hidden flex-shrink-0 shadow-sm">
              <img src={logo} alt={title} className="w-full h-full object-contain" />
            </div>
          ) : (
            <div className={`w-7 h-7 sm:w-8 sm:h-8 mt-0.5 sm:mt-0 rounded-full flex items-center justify-center flex-shrink-0 ${fallbackIconClass}`}>
              {fallbackIcon}
            </div>
          )}

          <div className="flex-1 min-w-0 min-h-[28px] sm:min-h-[32px] mt-0.5 sm:mt-0 flex items-center justify-between gap-3">
            <span className="text-sm sm:text-[15px] font-semibold text-zinc-200 leading-snug">
              {title}
            </span>
            {year && (
              <span className="hidden sm:inline-flex flex-shrink-0 text-xs px-2.5 py-0.5 rounded-full bg-white/5 border border-white/10 text-zinc-400 font-medium whitespace-nowrap">
                {year}
              </span>
            )}
          </div>
        </div>

        {/* Role / Degree */}
        <div className="mt-2 flex items-center">
          <h5 className="text-base sm:text-lg font-bold text-white tracking-tight leading-snug">
            {heading}
          </h5>
        </div>

        {/* Subtitle & Tag (+ Year pill on mobile for uniform alignment) */}
        <div className="mt-1.5 flex flex-col sm:flex-row sm:flex-wrap sm:items-center gap-1.5 sm:gap-2 text-xs text-zinc-400">
          {metaLeft && <span>{metaLeft}</span>}
          <div className="flex flex-wrap items-center gap-1.5 sm:gap-2">
            {metaLeft && metaBadge && <span className="hidden sm:inline text-zinc-600">•</span>}
            {year && (
              <span className="sm:hidden px-2 py-0.5 rounded-full bg-white/5 border border-white/10 text-zinc-400 font-medium whitespace-nowrap">
                {year}
              </span>
            )}
            {metaBadge && (
              <span className="px-2 py-0.5 rounded-md bg-white/5 border border-white/10 text-zinc-300 font-medium whitespace-nowrap">
                {metaBadge}
              </span>
            )}
          </div>
        </div>

        {/* Description */}
        {description && (
          <p className="text-xs text-zinc-400/80 mt-2 leading-relaxed">
            {description}
          </p>
        )}
      </div>
    </div>
  );
}

export default function About({
  personal,
  stats,
  education,
  experience,
  skillCategories,
  projects = [],
  projectsWebsite = [],
  projectsAplikasi = [],
  projectsDesain = [],
  certificatesSkills = [],
  certificatesAwards = [],
  onSelectPortfolioTab
}) {
  const [activeEduIndex, setActiveEduIndex] = useState(null);
  const [activeExpIndex, setActiveExpIndex] = useState(null);

  const handleFolderClick = (tabIndex) => {
    if (onSelectPortfolioTab) {
      onSelectPortfolioTab(tabIndex);
    }
    const el = document.getElementById("Portofolio");
    if (el) {
      el.scrollIntoView({ behavior: "smooth" });
    }
  };

  // Convert items into folder sheet preview format
  const websiteItems = projectsWebsite.length > 0 ? projectsWebsite : projects.filter(p => p.id && String(p.id).startsWith('web'));
  const appItems = projectsAplikasi.length > 0 ? projectsAplikasi : projects.filter(p => p.id && String(p.id).startsWith('app'));
  const designItems = projectsDesain.length > 0 ? projectsDesain : projects.filter(p => p.id && String(p.id).startsWith('des'));

  const toFolderFile = (p) => ({
    name: p.title,
    meta: p.category,
    image: p.img,
    objectPosition: p.imgObjectPosition
  });

  const websiteFolderFiles = websiteItems.slice(0, 4).map(toFolderFile);
  const appFolderFiles = appItems.slice(0, 4).map(toFolderFile);
  const designFolderFiles = designItems.slice(0, 4).map(toFolderFile);

  return (
    <section id="About" className="relative w-full py-24 bg-[#030712] overflow-hidden">
      <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Section Header */}
        <div className="text-center mb-16">
          <motion.h2
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            className="text-4xl sm:text-5xl font-extrabold text-white tracking-tight"
            style={{ fontFamily: 'var(--font-display)' }}
          >
            About Me
          </motion.h2>
        </div>

        {/* 🌟 Desktop Layout (lg and up): 3-Column Symmetrical Panoramic with Photo at True Center */}
        <div className="hidden lg:grid grid-cols-[1.25fr_1.25fr_1.1fr] gap-6 xl:gap-8 items-center mb-24">
          {/* Left Column: Greeting, Name (2 clean bold lines), and Contact Me button */}
          <motion.div
            initial={{ opacity: 0, x: -30 }}
            whileInView={{ opacity: 1, x: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.6 }}
            className="min-w-0 space-y-6 text-left pr-2 xl:pr-4"
          >
            <h3
              className="text-4xl sm:text-5xl lg:text-4xl xl:text-5xl 2xl:text-6xl font-extrabold tracking-tight leading-[1.06]"
              style={{ fontFamily: 'var(--font-display)' }}
            >
              <span className="grad-vi block mb-2 text-2xl sm:text-3xl lg:text-2xl xl:text-3xl font-bold">
                {personal.greeting || "Hi, I'm"}
              </span>
              <span className="block text-white whitespace-nowrap">
                {personal.firstName}
              </span>
              <span className="block text-white whitespace-nowrap mt-0.5">
                {personal.lastName}
              </span>
            </h3>

            <div className="mt-8">
              <a
                href="#Contact"
                className="inline-flex px-6 py-3 rounded-xl font-semibold bg-[#2c67ed] hover:bg-[#1d4ed8] transition-[transform,background-color] duration-200 hover:scale-[1.03] items-center gap-2 text-white whitespace-nowrap cursor-pointer shadow-[0_8px_20px_rgba(44,103,237,0.35)]"
                style={{ fontFamily: 'var(--font-display)' }}
              >
                <Mail className="w-4 h-4" />
                <span>Contact Me</span>
              </a>
            </div>
          </motion.div>

          {/* Center Column: Cutout Portrait with Spotlight / Flashlight Easter Egg */}
          <motion.div
            initial={{ opacity: 0, scale: 0.95 }}
            whileInView={{ opacity: 1, scale: 1 }}
            viewport={{ once: true }}
            transition={{ duration: 0.6 }}
            className="min-w-0 flex justify-center items-center"
          >
            <SpotlightCard
              baseImage={personal.avatar}
              revealImage={personal.avatarReveal}
            />
          </motion.div>

          {/* Right Column: Bio Paragraph & View Projects button */}
          <motion.div
            initial={{ opacity: 0, x: 30 }}
            whileInView={{ opacity: 1, x: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.6 }}
            className="min-w-0 space-y-6 text-left"
          >
            <div className="text-base sm:text-lg leading-relaxed text-slate-300">
              <p>{personal.bio}</p>
            </div>

            <div className="mt-8">
              <a
                href="#Portofolio"
                onClick={(e) => {
                  e.preventDefault();
                  handleFolderClick(0);
                }}
              >
                <button
                  className="px-6 py-3 rounded-xl font-semibold bg-[#2c67ed] hover:bg-[#1d4ed8] transition-[transform,background-color] duration-200 hover:scale-[1.03] flex items-center gap-2 text-white whitespace-nowrap cursor-pointer shadow-[0_8px_20px_rgba(44,103,237,0.35)]"
                  style={{ fontFamily: 'var(--font-display)' }}
                >
                  <Code2 className="w-4 h-4" />
                  <span>View Projects</span>
                </button>
              </a>
            </div>
          </motion.div>
        </div>

        {/* 📱 Mobile Layout (< lg): Stacked matching mobile reference */}
        <div className="flex flex-col lg:hidden items-center text-left mb-20 space-y-8">
          {/* Centered Photo */}
          <motion.div
            initial={{ opacity: 0, scale: 0.95 }}
            whileInView={{ opacity: 1, scale: 1 }}
            viewport={{ once: true }}
            className="w-full flex justify-center"
          >
            <SpotlightCard
              baseImage={personal.avatar}
              revealImage={personal.avatarReveal}
            />
          </motion.div>

          {/* Text and Actions */}
          <motion.div
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            className="w-full max-w-md px-2"
          >
            <span className="text-3xl sm:text-4xl font-black text-[#2c67ed] block mb-1 tracking-tight">
              {personal.greeting || "Hi, I'm"}
            </span>
            <h3
              className="text-4xl sm:text-5xl font-black text-white tracking-tight leading-tight mb-4"
              style={{ fontFamily: 'var(--font-display)' }}
            >
              {personal.name}
            </h3>
            <p className="text-sm sm:text-base text-slate-300 leading-relaxed font-normal mb-6">
              {personal.bio}
            </p>

            {/* Stacked Full-Width Buttons */}
            <div className="flex flex-col gap-3.5 w-full">
              <a
                href="#Contact"
                className="w-full flex items-center justify-center gap-2.5 py-4 px-6 rounded-2xl font-bold bg-[#2c67ed] hover:bg-[#1d4ed8] text-white shadow-[0_8px_20px_rgba(44,103,237,0.35)] active:scale-[0.98] transition-all text-sm"
              >
                <Mail className="w-4 h-4" />
                <span>Contact Me</span>
              </a>

              <a
                href="#Portofolio"
                className="w-full flex items-center justify-center gap-2.5 py-4 px-6 rounded-2xl font-bold bg-[#2c67ed] hover:bg-[#1d4ed8] text-white shadow-[0_8px_20px_rgba(44,103,237,0.35)] active:scale-[0.98] transition-all text-sm"
              >
                <Code2 className="w-4 h-4" />
                <span>View Projects</span>
              </a>
            </div>
          </motion.div>
        </div>

        {/* 🌟 3D Glassmorphic Interactive Folders (The exact component from the reference!) */}
        <div className="mb-24">
          <div className="text-center mb-8">
            <span className="text-xs font-mono uppercase tracking-widest text-slate-400">
              Interactive Dossier Folders (Click or Hover to Open)
            </span>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-y-12 gap-x-8 justify-items-center">
            {/* Website Folder */}
            <motion.div
              initial={{ opacity: 0, y: 30 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.6 }}
              className="flex flex-col items-center"
            >
              <GlassFolder
                label="Website"
                meta={websiteItems.length}
                tone="dark"
                width={320}
                files={websiteFolderFiles}
                onFileSelect={() => handleFolderClick(0)}
                menu={[
                  { label: "Buka Semua Website", onSelect: () => handleFolderClick(0) }
                ]}
              />
            </motion.div>

            {/* Aplikasi Folder */}
            <motion.div
              initial={{ opacity: 0, y: 30 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.6, delay: 0.1 }}
              className="flex flex-col items-center"
            >
              <GlassFolder
                label="Aplikasi"
                meta={appItems.length}
                tone="dark"
                width={320}
                files={appFolderFiles}
                onFileSelect={() => handleFolderClick(1)}
                menu={[
                  { label: "Buka Semua Aplikasi", onSelect: () => handleFolderClick(1) }
                ]}
              />
            </motion.div>

            {/* Desain Folder */}
            <motion.div
              initial={{ opacity: 0, y: 30 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.6, delay: 0.2 }}
              className="flex flex-col items-center md:col-span-2 xl:col-span-1"
            >
              <GlassFolder
                label="Desain"
                meta={designItems.length}
                tone="dark"
                width={320}
                files={designFolderFiles}
                onFileSelect={() => handleFolderClick(2)}
                menu={[
                  { label: "Buka Semua Desain", onSelect: () => handleFolderClick(2) }
                ]}
              />
            </motion.div>
          </div>
        </div>

        {/* Education & Experience Dual Timelines */}
        <div className="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12 mt-14">
          {/* Education Timeline */}
          <div className="flex flex-col justify-between" onMouseLeave={() => setActiveEduIndex(null)}>
            <div>
              <h4 className="text-xl font-bold text-white mb-6" style={{ fontFamily: "var(--font-display)" }}>
                Education
              </h4>
              <div className="relative pt-2 pb-2 px-2">
                <div>
                  {education.map((item, index) => (
                    <TimelineItem
                      key={item.id}
                      index={index}
                      isLast={index === education.length - 1}
                      isActive={activeEduIndex === index}
                      dimmed={activeEduIndex !== null}
                      onHover={() => setActiveEduIndex(index)}
                      logo={item.logo}
                      title={item.school}
                      year={item.year}
                      heading={item.degree}
                      metaLeft={item.faculty}
                      metaBadge={item.tag || item.subInfo || "Undergraduate"}
                      fallbackIcon={<GraduationCap className="w-4 h-4" />}
                      fallbackIconClass="bg-blue-500/10 border border-blue-500/20 text-blue-400"
                      description={item.description}
                    />
                  ))}
                </div>
              </div>
            </div>
          </div>

          {/* Experience Timeline */}
          <div className="flex flex-col justify-between" onMouseLeave={() => setActiveExpIndex(null)}>
            <div>
              <h4 className="text-xl font-bold text-white mb-6" style={{ fontFamily: "var(--font-display)" }}>
                Experience
              </h4>
              <div className="relative pt-2 pb-2 px-2">
                <div>
                  {experience.map((item, index) => (
                    <TimelineItem
                      key={item.id}
                      index={index}
                      isLast={index === experience.length - 1}
                      isActive={activeExpIndex === index}
                      dimmed={activeExpIndex !== null}
                      onHover={() => setActiveExpIndex(index)}
                      logo={item.logo}
                      title={item.company}
                      year={item.year}
                      heading={item.role}
                      metaLeft={item.subtitle}
                      metaBadge={item.tag}
                      fallbackIcon={<Briefcase className="w-3.5 h-3.5" />}
                      fallbackIconClass="bg-indigo-500/10 border border-indigo-500/20 text-indigo-400"
                      description={item.description}
                    />
                  ))}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
