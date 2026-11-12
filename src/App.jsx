import React, { useState, useEffect, useRef, useCallback } from 'react';
import Lenis from 'lenis';
import 'lenis/dist/lenis.css';
import { portfolioData } from './data/portfolioData';
import { resolveApiUrl, resolveMediaUrl } from './lib/api';
import Navbar from './components/Navbar';
import Hero from './components/Hero';
import About from './components/About';
import Portfolio from './components/Portfolio';
import PhotoGallery from './components/PhotoGallery';
import Contact from './components/Contact';
import BeamWordmarkFooter from '@/components/ui/beam-wordmark-footer';
import FluxLines, { FLUX_LIGHT_PRESET } from './components/FluxLines';
import ProjectDetailPage from './components/ProjectDetailPage';

const PROJECT_LIST_KEYS = ['projectsWebsite', 'projectsAplikasi', 'projectsDesain'];

function normalizeProjects(list = []) {
  return list.map((p) => ({
    ...p,
    img: resolveMediaUrl(p.img),
  }));
}

function findProjectByHash(hash, dataSource = portfolioData) {
  if (!hash || !hash.startsWith('#project-')) return null;
  const id = hash.slice('#project-'.length);
  const all = [
    ...(dataSource.projectsWebsite || []),
    ...(dataSource.projectsAplikasi || []),
    ...(dataSource.projectsDesain || []),
    ...(dataSource.projects || []),
  ];
  return all.find((p) => String(p.id) === id) || null;
}

function getSiblingProjects(project, dataSource = portfolioData) {
  if (!project) return { categoryLabel: 'Projects', siblings: [] };
  for (const key of PROJECT_LIST_KEYS) {
    const list = dataSource[key] || [];
    if (list.some((p) => p.id === project.id)) {
      return { categoryLabel: 'Projects', siblings: list };
    }
  }
  return { categoryLabel: 'Projects', siblings: dataSource.projects || [] };
}

export default function App() {
  const [catalog, setCatalog] = useState(() => ({
    personal: portfolioData.personal,
    education: portfolioData.education,
    experience: portfolioData.experience,
    socialLinks: portfolioData.socialLinks,
    projectsWebsite: portfolioData.projectsWebsite,
    projectsAplikasi: portfolioData.projectsAplikasi,
    projectsDesain: portfolioData.projectsDesain,
    projects: portfolioData.projects,
    gallery: portfolioData.gallery,
    stats: portfolioData.stats,
  }));
  const [activeSection, setActiveSection] = useState('Home');
  const [portfolioTab, setPortfolioTab] = useState(0); // 0: Website, 1: Aplikasi, 2: Desain
  const [selectedProject, setSelectedProject] = useState(() => findProjectByHash(window.location.hash, portfolioData));
  const savedScrollYRef = useRef(0);
  const pendingRestoreRef = useRef(false);

  // Hydrate portfolio content, projects & gallery live from Laravel backend API
  useEffect(() => {
    let active = true;
    fetch(resolveApiUrl('/api/portfolio'), { headers: { Accept: 'application/json' } })
      .then((res) => (res.ok ? res.json() : null))
      .then((data) => {
        if (!active || !data) return;
        const nextPersonal = data.personal
          ? {
              ...portfolioData.personal,
              ...data.personal,
              avatar: resolveMediaUrl(data.personal.avatar || portfolioData.personal.avatar),
              avatarReveal: resolveMediaUrl(data.personal.avatarReveal || portfolioData.personal.avatarReveal),
              contactPhoto: resolveMediaUrl(data.personal.contactPhoto || '/profile.jpg'),
              resumeUrl:
                data.personal.resumeUrl && data.personal.resumeUrl !== '#'
                  ? resolveMediaUrl(data.personal.resumeUrl)
                  : '#',
            }
          : portfolioData.personal;

        const nextEducation =
          Array.isArray(data.education) && data.education.length
            ? data.education.map((item) => ({ ...item, logo: resolveMediaUrl(item.logo) }))
            : portfolioData.education;

        const nextExperience =
          Array.isArray(data.experience) && data.experience.length
            ? data.experience.map((item) => ({ ...item, logo: resolveMediaUrl(item.logo) }))
            : portfolioData.experience;

        const nextSocialLinks =
          Array.isArray(data.socialLinks) && data.socialLinks.length
            ? data.socialLinks
            : portfolioData.socialLinks;

        const nextWebsite = normalizeProjects(data.projectsWebsite || []);
        const nextAplikasi = normalizeProjects(data.projectsAplikasi || []);
        const nextDesain = normalizeProjects(data.projectsDesain || []);
        const nextAll = normalizeProjects(data.projects || []);
        const nextGallery = (data.gallery || []).map((g) => ({
          ...g,
          src: resolveMediaUrl(g.src),
        }));

        const nextCatalog = {
          personal: nextPersonal,
          education: nextEducation,
          experience: nextExperience,
          socialLinks: nextSocialLinks,
          projectsWebsite: nextWebsite.length ? nextWebsite : portfolioData.projectsWebsite,
          projectsAplikasi: nextAplikasi.length ? nextAplikasi : portfolioData.projectsAplikasi,
          projectsDesain: nextDesain.length ? nextDesain : portfolioData.projectsDesain,
          projects: nextAll.length ? nextAll : portfolioData.projects,
          gallery: nextGallery.length ? nextGallery : portfolioData.gallery,
          stats: {
            ...portfolioData.stats,
            totalProjects: data.totalProjects || portfolioData.stats.totalProjects,
          },
        };
        setCatalog(nextCatalog);

        const hashMatch = findProjectByHash(window.location.hash, nextCatalog);
        if (hashMatch) {
          setSelectedProject(hashMatch);
        }
      })
      .catch(() => {});

    return () => {
      active = false;
    };
  }, []);

  const handleOpenProject = useCallback((project) => {
    savedScrollYRef.current = window.scrollY;
    pendingRestoreRef.current = false;
    window.history.pushState(
      { projectId: project.id, scrollY: savedScrollYRef.current },
      '',
      `#project-${project.id}`
    );
    setSelectedProject(project);
  }, []);

  const handleSwitchProject = useCallback((project) => {
    window.history.replaceState(
      { projectId: project.id, scrollY: savedScrollYRef.current },
      '',
      `#project-${project.id}`
    );
    setSelectedProject(project);
  }, []);

  const handleCloseProject = useCallback(() => {
    if (window.history.state?.projectId) {
      window.history.back();
      return;
    }
    pendingRestoreRef.current = true;
    window.history.replaceState(null, '', window.location.pathname + window.location.search);
    setSelectedProject(null);
  }, []);

  // Sync browser Back/Forward navigation with project detail view
  useEffect(() => {
    if ('scrollRestoration' in window.history) {
      window.history.scrollRestoration = 'manual';
    }
    const onPopState = () => {
      const next = findProjectByHash(window.location.hash, catalog);
      if (next) {
        pendingRestoreRef.current = false;
        setSelectedProject(next);
      } else {
        pendingRestoreRef.current = true;
        setSelectedProject(null);
      }
    };
    window.addEventListener('popstate', onPopState);
    return () => window.removeEventListener('popstate', onPopState);
  }, [catalog]);

  // Scroll to top on project detail view, and restore portfolio scroll position on Back
  useEffect(() => {
    if (selectedProject) {
      const jumpTop = () => {
        window.lenis?.resize();
        window.scrollTo(0, 0);
        window.lenis?.scrollTo(0, { immediate: true, force: true });
      };
      jumpTop();
      const rafId = requestAnimationFrame(jumpTop);
      return () => cancelAnimationFrame(rafId);
    }

    if (pendingRestoreRef.current) {
      pendingRestoreRef.current = false;
      const targetY = savedScrollYRef.current;
      const restore = () => {
        window.lenis?.resize();
        if (targetY > 0) {
          window.scrollTo(0, targetY);
          window.lenis?.scrollTo(targetY, { immediate: true, force: true });
        } else {
          const gallery = document.getElementById('Portofolio-Gallery');
          if (gallery) {
            const galleryY = gallery.getBoundingClientRect().top + window.scrollY;
            window.scrollTo(0, galleryY);
            window.lenis?.scrollTo(galleryY, { immediate: true, force: true });
          }
        }
      };
      let innerRaf = 0;
      const outerRaf = requestAnimationFrame(() => {
        innerRaf = requestAnimationFrame(restore);
      });
      const timerId = setTimeout(restore, 120);
      return () => {
        cancelAnimationFrame(outerRaf);
        if (innerRaf) cancelAnimationFrame(innerRaf);
        clearTimeout(timerId);
      };
    }
  }, [selectedProject]);

  // Initialize Lenis Buttery Smooth Momentum Scrolling
  useEffect(() => {
    const lenis = new Lenis({
      duration: 1.2,
      easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
      smoothWheel: true,
      touchMultiplier: 1.5,
    });

    window.lenis = lenis;

    function raf(time) {
      lenis.raf(time);
      requestAnimationFrame(raf);
    }
    const animId = requestAnimationFrame(raf);

    return () => {
      cancelAnimationFrame(animId);
      lenis.destroy();
      window.lenis = null;
    };
  }, []);

  // Track active section on scroll
  useEffect(() => {
    const handleScroll = () => {
      const sections = ['Home', 'About', 'Portofolio', 'Contact'];
      const scrollPosition = window.scrollY + 250;

      for (const sectionId of sections) {
        const el = document.getElementById(sectionId);
        if (el) {
          const top = el.getBoundingClientRect().top + window.scrollY;
          const height = el.offsetHeight;
          if (scrollPosition >= top && scrollPosition < top + height) {
            setActiveSection(sectionId);
            break;
          }
        }
      }
    };

    window.addEventListener('scroll', handleScroll, { passive: true });
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  if (selectedProject) {
    const { categoryLabel, siblings } = getSiblingProjects(selectedProject, catalog);
    return (
      <div className="min-h-screen bg-[#030712] text-white selection:bg-[#2c67ed] selection:text-white antialiased">
        <ProjectDetailPage
          key={selectedProject.id}
          project={selectedProject}
          categoryLabel={categoryLabel}
          siblingProjects={siblings}
          onSelectProject={handleSwitchProject}
          onBack={handleCloseProject}
        />
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-[#030712] text-white selection:bg-[#2c67ed] selection:text-white antialiased">
      {/* Floating Glassmorphic Navbar */}
      <Navbar
        activeSection={activeSection}
        resumeUrl={catalog.personal.resumeUrl}
        githubUrl={catalog.socialLinks.find(s => s.type === 'github')?.url}
      />

      {/* Main Content Sections */}
      <main className="w-full">
        {/* Cinematic Multi-Layer Parallax Hero with Anime.js */}
        <Hero
          heroTopText={catalog.personal.heroTopText || "WELCOME TO MY"}
          heroBottomText={catalog.personal.heroBottomText || "PORTOFOLIO"}
        />

        {/* About Section with Interactive Flashlight Spotlight Card & 3D Glass Folders */}
        <About
          personal={catalog.personal}
          stats={catalog.stats}
          education={catalog.education}
          experience={catalog.experience}
          skillCategories={portfolioData.skillCategories}
          projectsWebsite={catalog.projectsWebsite}
          projectsAplikasi={catalog.projectsAplikasi}
          projectsDesain={catalog.projectsDesain}
          projects={catalog.projects}
          onSelectPortfolioTab={(idx) => setPortfolioTab(idx)}
        />

        {/* Pinning Horizontal Scroll Showcase for Website, Aplikasi, and Coverflow for Desain */}
        <Portfolio
          projectsWebsite={catalog.projectsWebsite}
          projectsAplikasi={catalog.projectsAplikasi}
          projectsDesain={catalog.projectsDesain}
          designSlides={portfolioData.designSlides}
          projects={catalog.projects}
          activeTab={portfolioTab}
          onTabChange={(idx) => setPortfolioTab(idx)}
          onSelectProject={handleOpenProject}
        />

        {/* Gallery, Contact & Seamless Ashr Wordmark Footer sharing one light FluxLines canvas */}
        <div className="relative w-full bg-[#fafafa]">
          <div className="absolute inset-0 pointer-events-none z-0">
            <div className="sticky top-0 h-screen w-full">
              <FluxLines {...FLUX_LIGHT_PRESET} />
            </div>
          </div>

          {/* 3D Perspective Spiral Scroll Photo Gallery */}
          <PhotoGallery photos={catalog.gallery} hideBackground />

          <Contact
            personal={catalog.personal}
            socialLinks={catalog.socialLinks}
            hideBackground
          />

          <BeamWordmarkFooter
            id="Footer"
            brand="Ashr"
            wordmark="Ashr"
            showDetails={false}
            showBackdrop={false}
            softFootMask
            background="transparent"
            ink="#09090b"
            accent="#2c67ed"
            wordTop="#09090b"
            wordFoot="#111113"
            lit="#71717a"
            fontSans='"Plus Jakarta Sans", ui-sans-serif, system-ui, sans-serif'
            wordWeight={800}
            cut={0.1}
            className="z-10"
          />
        </div>
      </main>
    </div>
  );
}
