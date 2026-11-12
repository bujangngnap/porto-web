import React, { useState, useRef, useEffect, useCallback } from 'react';
import { motion, useScroll, useTransform, useSpring } from 'framer-motion';
import {
  Globe,
  Smartphone,
  Palette,
  ExternalLink,
  ArrowRight,
  ChevronLeft,
  ChevronRight,
} from 'lucide-react';
import FluxLines, { FLUX_DARK_PRESET, FLUX_LIGHT_PRESET } from './FluxLines';
import WordPortalZoom from './WordPortalZoom';

const COVERFLOW_PERSPECTIVE = 1600;
const COVERFLOW_SCALE_STEP = 0.16;
const COVERFLOW_DEPTH = 240;
const COVERFLOW_TILT = -3;
const COVERFLOW_DIM_ALPHA = 0.46;

function wrapCoverflowOffset(index, activePos, total) {
  let rel = index - activePos;
  if (total > 2) {
    rel = ((((rel % total) + total + total / 2) % total) - total / 2);
  }
  return rel;
}

/** Single scroll-driven card in the 3D Coverflow stage using GPU-composited transforms. */
function ScrollCoverflowCard({
  slide,
  index,
  total,
  activeSlide,
  cardWidth,
  cardHeight,
  gap,
  radius,
  onSelect,
}) {
  const transform = useTransform(activeSlide, (a) => {
    const rel = wrapCoverflowOffset(index, a, total);
    const ax = Math.abs(rel);
    const sc = Math.max(0.4, 1 - ax * COVERFLOW_SCALE_STEP);
    const tx = rel * (gap * 30);
    const tz = -ax * COVERFLOW_DEPTH;
    const ry = -rel * COVERFLOW_TILT;
    return `translate3d(calc(-50% + ${tx}px), -50%, ${tz}px) rotateY(${ry}deg) scale(${sc})`;
  });

  const opacity = useTransform(activeSlide, (a) => {
    const ax = Math.abs(wrapCoverflowOffset(index, a, total));
    if (ax <= 2) return 1;
    const fadeEnd = Math.min(2.6, Math.max(2.05, total / 2 - 0.15));
    if (ax >= fadeEnd) return 0;
    return 1 - (ax - 2) / (fadeEnd - 2);
  });

  const overlayOpacity = useTransform(activeSlide, (a) => {
    const ax = Math.abs(wrapCoverflowOffset(index, a, total));
    return Math.min(1, ax) * COVERFLOW_DIM_ALPHA;
  });

  const zIndex = useTransform(activeSlide, (a) =>
    Math.round((10 - Math.abs(wrapCoverflowOffset(index, a, total))) * 100)
  );

  const pointerEvents = useTransform(activeSlide, (a) =>
    Math.abs(wrapCoverflowOffset(index, a, total)) <= 2.2 ? 'auto' : 'none'
  );

  const src = slide.image?.src || '';

  return (
    <motion.div
      onClick={() => onSelect?.(index)}
      aria-label={slide.title || slide.image?.alt}
      style={{
        position: 'absolute',
        left: '50%',
        top: '50%',
        width: cardWidth,
        height: cardHeight,
        borderRadius: radius,
        overflow: 'hidden',
        transformStyle: 'preserve-3d',
        transformOrigin: 'center center',
        transform,
        opacity,
        zIndex,
        pointerEvents,
        backgroundColor: '#1a1a1a',
        cursor: 'pointer',
      }}
    >
      {src ? (
        <img
          src={src}
          alt={slide.image?.alt || slide.title || ''}
          draggable={false}
          className="absolute inset-0 w-full h-full object-cover block select-none"
        />
      ) : null}

      <motion.div
        style={{ opacity: overlayOpacity }}
        className="absolute inset-0 bg-black pointer-events-none"
      />
    </motion.div>
  );
}

/** 3D Coverflow gallery synchronized continuously to vertical page scroll progress. */
function ScrollCoverflowStage({
  slides,
  activeSlide,
  cardWidth,
  cardHeight,
  gap,
  onSelectSlide,
  onStep,
}) {
  const radius = (3 / 20) * (Math.min(cardWidth, cardHeight) / 2);

  const handleKeyDown = (e) => {
    if (e.key === 'ArrowRight') {
      e.preventDefault();
      onStep?.(1);
    } else if (e.key === 'ArrowLeft') {
      e.preventDefault();
      onStep?.(-1);
    }
  };

  return (
    <div className="coverflow-ig-stage relative z-10 w-full flex items-center justify-center py-2 sm:py-6">
      <div className="w-full h-[340px] sm:h-[420px] lg:h-[470px]">
        <div
          role="group"
          aria-roledescription="carousel"
          tabIndex={0}
          onKeyDown={handleKeyDown}
          style={{
            position: 'relative',
            width: '100%',
            height: '100%',
            minWidth: 320,
            minHeight: 320,
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            perspective: `${COVERFLOW_PERSPECTIVE}px`,
            overflow: 'hidden',
            outline: 'none',
          }}
        >
          <div
            style={{
              position: 'relative',
              width: cardWidth,
              height: cardHeight,
              transformStyle: 'preserve-3d',
            }}
          >
            {slides.map((slide, idx) => (
              <ScrollCoverflowCard
                key={slide.image?.src || idx}
                slide={slide}
                index={idx}
                total={slides.length}
                activeSlide={activeSlide}
                cardWidth={cardWidth}
                cardHeight={cardHeight}
                gap={gap}
                radius={radius}
                onSelect={onSelectSlide}
              />
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}

function SlideArrowButton({ direction, onClick, disabled = false, className = '' }) {
  const isPrev = direction < 0;
  const Icon = isPrev ? ChevronLeft : ChevronRight;
  return (
    <button
      type="button"
      onClick={onClick}
      disabled={disabled}
      aria-label={isPrev ? 'Slide Sebelumnya' : 'Slide Berikutnya'}
      className={`w-11 h-11 sm:w-12 sm:h-12 rounded-full bg-[#2c67ed] hover:bg-[#1d4ed8] hover:scale-105 active:scale-95 text-white shadow-[0_8px_22px_rgba(44,103,237,0.35)] transition-all duration-200 flex items-center justify-center cursor-pointer disabled:opacity-35 disabled:hover:scale-100 disabled:hover:bg-[#2c67ed] disabled:cursor-not-allowed ${className}`}
    >
      <Icon className="w-5 h-5 sm:w-6 sm:h-6" strokeWidth={2.5} />
    </button>
  );
}

// Standard 4:5 Instagram feed post proportions across breakpoints
function getCoverflowMetrics() {
  if (typeof window === 'undefined') {
    return { width: 344, height: 430, gap: 14 };
  }
  const w = window.innerWidth;
  if (w < 480) return { width: 240, height: 300, gap: 9 };
  if (w < 1024) return { width: 288, height: 360, gap: 12 };
  if (w < 1280) return { width: 320, height: 400, gap: 13 };
  return { width: 344, height: 430, gap: 14 };
}

export default function Portfolio({
  projectsWebsite = [],
  projectsAplikasi = [],
  projectsDesain = [],
  designSlides = [],
  projects = [],
  certificatesSkills = [],
  certificatesAwards = [],
  activeTab: controlledActiveTab,
  onTabChange,
  onSelectProject
}) {
  const [internalTab, setInternalTab] = useState(0); // 0: Website, 1: Aplikasi, 2: Desain
  const activeTab = controlledActiveTab !== undefined ? controlledActiveTab : internalTab;
  const isDesignTab = activeTab === 2;

  const [portalLayers, setPortalLayers] = useState({ dark: true, light: false });
  const [coverflowSize, setCoverflowSize] = useState(getCoverflowMetrics);
  const [navBounds, setNavBounds] = useState({ canPrev: false, canNext: true });

  const containerRef = useRef(null);
  const trackRef = useRef(null);
  const [scrollRange, setScrollRange] = useState(1200);

  const setActiveTab = (tabIdx) => {
    if (onTabChange) onTabChange(tabIdx);
    setInternalTab(tabIdx);

    if (tabIdx !== activeTab && containerRef.current) {
      const rect = containerRef.current.getBoundingClientRect();
      if (rect.top < 0) {
        const galleryTop = rect.top + window.scrollY;
        requestAnimationFrame(() => {
          window.lenis?.resize();
          window.scrollTo(0, galleryTop);
          window.lenis?.scrollTo(galleryTop, { immediate: true, force: true });
        });
      }
    }
  };

  useEffect(() => {
    const handleResize = () => setCoverflowSize(getCoverflowMetrics());
    window.addEventListener('resize', handleResize);
    return () => window.removeEventListener('resize', handleResize);
  }, []);

  useEffect(() => {
    const id = requestAnimationFrame(() => window.lenis?.resize());
    return () => cancelAnimationFrame(id);
  }, [activeTab]);

  // Group items by category with robust fallbacks
  const websiteList = projectsWebsite.length > 0
    ? projectsWebsite
    : projects.filter(p => p.id && String(p.id).startsWith('web'));

  const appList = projectsAplikasi.length > 0
    ? projectsAplikasi
    : projects.filter(p => p.id && String(p.id).startsWith('app'));

  const designList = projectsDesain.length > 0
    ? projectsDesain
    : projects.filter(p => p.id && String(p.id).startsWith('des'));

  const designSlideItems = designSlides.length > 0
    ? designSlides
    : designList.map((p) => ({ image: { src: p.img, alt: p.title }, title: p.title }));

  // Measure card track width vs window width
  useEffect(() => {
    const updateRange = () => {
      if (trackRef.current) {
        const trackWidth = trackRef.current.scrollWidth;
        const windowWidth = window.innerWidth;
        const padding = windowWidth * 0.15;
        const diff = Math.max(0, trackWidth - windowWidth + padding);
        setScrollRange(diff);
      }
    };

    updateRange();
    window.addEventListener('resize', updateRange);
    const timer = setTimeout(updateRange, 150);
    return () => {
      window.removeEventListener('resize', updateRange);
      clearTimeout(timer);
    };
  }, [activeTab, websiteList, appList, designList]);

  // Framer Motion scroll binding for horizontal track
  const { scrollYProgress } = useScroll({
    target: containerRef,
    offset: ["start start", "end end"]
  });

  const smoothProgress = useSpring(scrollYProgress, { stiffness: 100, damping: 26, restDelta: 0.001 });
  const translateX = useTransform(smoothProgress, [0, 1], [0, -scrollRange]);
  const activeDesignSlide = useTransform(
    smoothProgress,
    [0, 1],
    [0, Math.max(0, designSlideItems.length - 1)]
  );

  useEffect(() => {
    const updateBounds = (p) => {
      const canPrev = p > 0.03;
      const canNext = p < 0.97;
      setNavBounds((prev) =>
        prev.canPrev === canPrev && prev.canNext === canNext ? prev : { canPrev, canNext }
      );
    };
    updateBounds(scrollYProgress.get());
    return scrollYProgress.on('change', updateBounds);
  }, [scrollYProgress, activeTab]);

  const scrollToStop = useCallback((stopIdx, listLen) => {
    const container = containerRef.current;
    if (!container) return;
    const rect = container.getBoundingClientRect();
    const totalScroll = Math.max(1, container.offsetHeight - window.innerHeight);
    const galleryStartY = rect.top + window.scrollY;
    const count = Math.max(2, listLen);
    const clampedStop = Math.min(count - 1, Math.max(0, stopIdx));
    const targetY = galleryStartY + (clampedStop / (count - 1)) * totalScroll;

    if (window.lenis) {
      window.lenis.scrollTo(targetY, { duration: 0.7 });
    } else {
      window.scrollTo({ top: targetY, behavior: 'smooth' });
    }
  }, []);

  const handleSlideStep = useCallback(
    (dir) => {
      const container = containerRef.current;
      if (!container) return;
      const rect = container.getBoundingClientRect();
      const totalScroll = Math.max(1, container.offsetHeight - window.innerHeight);
      const curP = Math.min(1, Math.max(0, -rect.top / totalScroll));
      const listLen =
        activeTab === 0
          ? websiteList.length
          : activeTab === 1
            ? appList.length
            : designSlideItems.length;
      const count = Math.max(2, listLen);
      const currentStop = Math.round(curP * (count - 1));
      scrollToStop(currentStop + dir, listLen);
    },
    [activeTab, websiteList.length, appList.length, designSlideItems.length, scrollToStop]
  );

  const handleSelectDesignSlide = useCallback(
    (targetIdx) => {
      scrollToStop(targetIdx, designSlideItems.length);
    },
    [scrollToStop, designSlideItems.length]
  );

  const canPrev = navBounds.canPrev;
  const canNext = navBounds.canNext;

  const tabs = [
    { label: "Website", icon: Globe },
    { label: "Aplikasi", icon: Smartphone },
    { label: "Desain", icon: Palette },
  ];

  const renderProjectCard = (project) => (
    <div
      key={project.id}
      className="w-[300px] sm:w-[380px] md:w-[420px] flex-shrink-0 flex flex-col justify-between rounded-3xl bg-white border border-zinc-200/90 shadow-[0_22px_50px_-12px_rgba(15,23,42,0.16),0_10px_24px_-8px_rgba(15,23,42,0.09),0_2px_8px_rgba(15,23,42,0.05)] hover:shadow-[0_26px_60px_-12px_rgba(44,103,237,0.22),0_12px_28px_-8px_rgba(15,23,42,0.12)] transition-all duration-300 hover:-translate-y-2 overflow-hidden group/card"
    >
      {/* Image banner */}
      <div
        onClick={() => onSelectProject?.(project)}
        className="relative aspect-[16/10] overflow-hidden bg-zinc-100 cursor-pointer"
      >
        <img
          src={project.img}
          alt={project.title}
          className="w-full h-full object-cover object-top transition-transform duration-500 group-hover/card:scale-105"
          style={project.imgObjectPosition ? { objectPosition: project.imgObjectPosition } : undefined}
          loading="lazy"
        />
      </div>

      {/* Body */}
      <div className="p-6 flex flex-col justify-between flex-1">
        <div>
          <h3
            onClick={() => onSelectProject?.(project)}
            className="text-xl font-bold text-zinc-900 group-hover/card:text-[#2c67ed] transition-colors tracking-tight line-clamp-1 cursor-pointer"
            style={{ fontFamily: 'var(--font-display)' }}
          >
            {project.title}
          </h3>
          <p className="mt-2 text-sm text-zinc-600 line-clamp-2 leading-relaxed">
            {project.description}
          </p>

          {/* Tags */}
          <div className="mt-4 flex flex-wrap gap-1.5">
            {project.tags.slice(0, 3).map((tag) => (
              <span
                key={tag}
                className="px-2.5 py-0.5 rounded-md text-[11px] font-medium bg-zinc-100 text-zinc-600 border border-zinc-200/60"
              >
                {tag}
              </span>
            ))}
            {project.tags.length > 3 && (
              <span className="px-2.5 py-0.5 rounded-md text-[11px] font-medium bg-zinc-100 text-zinc-500">
                +{project.tags.length - 3}
              </span>
            )}
          </div>
        </div>

        {/* Actions */}
        <div className="mt-5 pt-4 border-t border-zinc-100 flex items-center gap-2.5">
          <button
            type="button"
            onClick={() => onSelectProject?.(project)}
            className="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold bg-[#2c67ed] hover:bg-[#1d4ed8] active:scale-[0.98] text-white shadow-[0_6px_16px_rgba(44,103,237,0.28)] hover:shadow-[0_8px_22px_rgba(44,103,237,0.38)] transition-all duration-200 cursor-pointer"
            style={{ fontFamily: 'var(--font-display)' }}
          >
            <span>Lihat Detail</span>
            <ArrowRight className="w-4 h-4 transition-transform duration-200 group-hover/card:translate-x-0.5" />
          </button>

          {project.link && (
            <a
              href={project.link}
              target="_blank"
              rel="noopener noreferrer"
              onClick={(e) => e.stopPropagation()}
              className="p-2.5 rounded-xl bg-zinc-100 border border-zinc-200/80 text-zinc-600 hover:text-zinc-900 hover:bg-zinc-200/70 transition-colors"
              title="Buka Website"
            >
              <ExternalLink className="w-4 h-4" />
            </a>
          )}
        </div>
      </div>
    </div>
  );

  return (
    <WordPortalZoom
      id="Portofolio"
      word="PORTOFOLIO"
      scrollLength={1.7}
      fontFamily='"Arial Black", "Arial", sans-serif'
      fontWeight={900}
      onLayerChange={setPortalLayers}
      backdrop={
        <div className="relative w-full h-full bg-[#030712] pointer-events-none">
          <FluxLines {...FLUX_DARK_PRESET} paused={!portalLayers.dark} />
          <div className="absolute inset-x-0 top-0 h-44 bg-gradient-to-b from-[#030712] via-[#030712]/90 to-transparent pointer-events-none" />
        </div>
      }
      lightField={<FluxLines {...FLUX_LIGHT_PRESET} paused={!portalLayers.light} />}
    >
      {/* Portfolio Gallery Content (Revealed inside the zoom portal) */}
      <div
        ref={containerRef}
        id="Portofolio-Gallery"
        className="relative w-full h-[280vh] bg-[#fafafa] text-zinc-900 select-none"
      >
        {/* Sticky pinned viewport for scroll-driven slide animation across all tabs */}
        <div className="sticky top-0 h-screen w-full overflow-hidden flex flex-col justify-between z-10">
          {/* Interactive FluxLines fluid backdrop on light field */}
          <div className="absolute inset-0 pointer-events-none z-0">
            <FluxLines {...FLUX_LIGHT_PRESET} />
          </div>

          {/* Header & Tabs */}
          <div className="relative z-20 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 sm:pt-20">
            <div className="text-center mb-6">
              <h2
                className="text-4xl sm:text-5xl md:text-6xl font-black tracking-tight text-zinc-900"
                style={{ fontFamily: 'var(--font-display)' }}
              >
                Portofolio
              </h2>
            </div>

            {/* Tab switcher buttons */}
            <div className="relative flex rounded-2xl w-full max-w-3xl mx-auto bg-white p-2 soft-tabbar-shadow">
              {tabs.map((tab, idx) => {
                const Icon = tab.icon;
                const isActive = activeTab === idx;
                return (
                  <button
                    key={tab.label}
                    onClick={() => setActiveTab(idx)}
                    className={`flex-1 flex flex-col items-center justify-center gap-1.5 px-3 py-3 md:py-3.5 text-xs sm:text-sm md:text-[0.95rem] font-semibold rounded-xl mx-0.5 transition-[transform,background-color,color,box-shadow] duration-200 cursor-pointer ${
                      isActive
                        ? 'text-white shadow-[0_8px_18px_rgba(44,103,237,0.28),0_2px_6px_rgba(44,103,237,0.16)]'
                        : 'text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100/80'
                    }`}
                    style={
                      isActive
                        ? { background: '#2c67ed', fontFamily: 'var(--font-display)' }
                        : { fontFamily: 'var(--font-display)' }
                    }
                  >
                    <Icon className="w-5 h-5 flex-shrink-0" />
                    <span>{tab.label}</span>
                  </button>
                );
              })}
            </div>
          </div>

          {/* Slide Stage + Circular Arrow Controls */}
          <div className="relative z-10 w-full flex-1 flex flex-col justify-center">
            <div className="relative w-full flex items-center">
              {/* Desktop flanking circular arrows */}
              <div className="hidden md:block absolute left-5 lg:left-8 top-1/2 -translate-y-1/2 z-30">
                <SlideArrowButton
                  direction={-1}
                  onClick={() => handleSlideStep(-1)}
                  disabled={!canPrev}
                />
              </div>
              <div className="hidden md:block absolute right-5 lg:right-8 top-1/2 -translate-y-1/2 z-30">
                <SlideArrowButton
                  direction={1}
                  onClick={() => handleSlideStep(1)}
                  disabled={!canNext}
                />
              </div>

              {/* Desain Tab: Scroll-Driven 3D Coverflow Photo Slideshow */}
              {isDesignTab ? (
                <ScrollCoverflowStage
                  slides={designSlideItems}
                  activeSlide={activeDesignSlide}
                  cardWidth={coverflowSize.width}
                  cardHeight={coverflowSize.height}
                  gap={coverflowSize.gap}
                  onSelectSlide={handleSelectDesignSlide}
                  onStep={handleSlideStep}
                />
              ) : (
                /* Horizontal Sliding Track Driven by Scroll for Website & Aplikasi */
                <div className="relative z-10 w-full overflow-hidden flex items-center py-6 sm:py-10">
                  <motion.div
                    ref={trackRef}
                    style={{ x: translateX }}
                    className="flex items-stretch gap-6 px-[6%] md:px-[12%] flex-nowrap w-max will-change-transform"
                  >
                    {/* Tab 0: Website */}
                    {activeTab === 0 && websiteList.map((project) => renderProjectCard(project))}

                    {/* Tab 1: Aplikasi */}
                    {activeTab === 1 && appList.map((project) => renderProjectCard(project))}
                  </motion.div>
                </div>
              )}
            </div>

            {/* Mobile bottom circular arrow controls */}
            <div className="flex md:hidden items-center justify-center gap-4 pt-1 pb-5 relative z-30">
              <SlideArrowButton
                direction={-1}
                onClick={() => handleSlideStep(-1)}
                disabled={!canPrev}
              />
              <SlideArrowButton
                direction={1}
                onClick={() => handleSlideStep(1)}
                disabled={!canNext}
              />
            </div>
          </div>
        </div>
      </div>
    </WordPortalZoom>
  );
}
