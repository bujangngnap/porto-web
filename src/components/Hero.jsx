import React, { useRef } from 'react';
import { motion, useScroll, useTransform } from 'framer-motion';
import AnimeParticles from './AnimeParticles';
import AnimeText from './AnimeText';

export default function Hero({ heroTopText = "WELCOME TO MY", heroBottomText = "PORTOFOLIO" }) {
  const containerRef = useRef(null);

  // Scroll progress for parallax effect
  const { scrollYProgress } = useScroll({
    target: containerRef,
    offset: ["start start", "end start"]
  });

  // Layer parallax transforms
  const skyY = useTransform(scrollYProgress, [0, 1], ["0%", "-12%"]);
  const mountainY = useTransform(scrollYProgress, [0, 1], ["0%", "-28%"]);
  const mountainScale = useTransform(scrollYProgress, [0, 1], [1, 1.05]);
  
  const titleY = useTransform(scrollYProgress, [0, 0.7, 1], ["0%", "-45%", "-80%"]);
  const titleOpacity = useTransform(scrollYProgress, [0, 0.45, 0.8], [1, 1, 0]);
  const titleScale = useTransform(scrollYProgress, [0, 0.7], [1, 0.92]);

  const groundY = useTransform(scrollYProgress, [0, 1], ["0%", "-55%"]);
  const forestScale = useTransform(scrollYProgress, [0, 0.4, 1], [1, 1.8, 3.8]);
  const forestOpacity = useTransform(scrollYProgress, [0, 0.25, 0.45], [1, 0.6, 0]);

  return (
    <section
      ref={containerRef}
      id="Home"
      className="relative w-full h-[180vh] md:h-[220vh] bg-[#030712] select-none"
    >
      {/* Sticky Viewport Container */}
      <div className="sticky top-0 w-full h-screen overflow-hidden">
        {/* Layer 1: Sky & Moon */}
        <motion.div
          style={{ y: skyY }}
          className="absolute inset-0 w-full h-full will-change-transform z-[0]"
        >
          <img
            src="/img10.svg"
            alt="Sky and Cosmic Moon"
            className="w-full h-full object-cover object-[center_20%]"
            loading="eager"
          />
          {/* Extension below sky */}
          <div className="absolute inset-x-0 top-full h-[40vh] bg-[#030712] pointer-events-none" />
        </motion.div>

        {/* Anime.js Ambient Floating Cosmic Particles */}
        <AnimeParticles count={24} className="absolute inset-0 z-[5] pointer-events-none" />

        {/* Layer 2: Mountain Peak */}
        <motion.div
          style={{ y: mountainY, scale: mountainScale }}
          className="absolute inset-0 w-full h-full will-change-transform z-[10]"
        >
          <img
            src="/img20.svg"
            alt="Mountain Range"
            className="w-full h-full object-cover object-[center_50%]"
            loading="eager"
          />
          {/* Solid extension block beneath mountain layer */}
          <div className="absolute inset-x-0 top-full h-[60vh] bg-[#030712] pointer-events-none" />
        </motion.div>

        {/* Layer 3: Central Cinematic Title with Anime.js Kinetic Staggered Wave */}
        <motion.div
          style={{ y: titleY, opacity: titleOpacity, scale: titleScale }}
          className="absolute inset-0 flex flex-col items-center justify-center z-[20] pointer-events-auto px-4"
        >
          <div className="w-full max-w-5xl mx-auto text-center -translate-y-4 sm:-translate-y-8 md:-translate-y-12">
            {/* Anime.js Staggered Typography with Hover Wave */}
            <h1
              className="text-4xl sm:text-5xl md:text-6xl lg:text-7xl xl:text-8xl font-black tracking-tight text-white uppercase leading-[1.08] drop-shadow-[0_15px_30px_rgba(0,0,0,0.8)] select-none"
              style={{ fontFamily: "var(--font-display)" }}
            >
              <span className="block text-white tracking-wide whitespace-nowrap">
                <AnimeText key={heroTopText} text={heroTopText} delay={200} />
              </span>
              <span className="block mt-1 sm:mt-2 text-white drop-shadow-[0_10px_25px_rgba(44,103,237,0.3)] whitespace-nowrap">
                <AnimeText key={heroBottomText} text={heroBottomText} delay={500} />
              </span>
            </h1>
          </div>
        </motion.div>

        {/* Layer 4: Ground Ridge Silhouette */}
        <motion.div
          style={{ y: groundY }}
          className="absolute inset-0 w-full h-full will-change-transform z-[30] pointer-events-none"
        >
          <img
            src="/img30.svg"
            alt="Ground Ridge"
            className="w-full h-full object-cover object-[center_35%]"
            loading="eager"
          />
          {/* Subtle gradient blend to ensure seamless transition to black */}
          <div className="absolute inset-x-0 bottom-0 h-32 bg-gradient-to-b from-transparent to-[#030712] pointer-events-none" />
          {/* Solid extension block beneath ground ridge so no gaps are exposed when scrolling up */}
          <div className="absolute inset-x-0 top-full h-[120vh] bg-[#030712] pointer-events-none" />
        </motion.div>

        {/* Layer 5: Forest Canopy Frame */}
        <motion.div
          style={{ scale: forestScale, opacity: forestOpacity }}
          className="absolute inset-0 w-full h-full will-change-transform z-[40] pointer-events-none"
        >
          <img
            src="/hutan.svg"
            alt="Forest Canopy Frame"
            className="w-full h-full object-cover object-bottom"
            loading="eager"
          />
        </motion.div>

        {/* Ambient bottom gradient blend into deep black */}
        <div className="absolute inset-x-0 bottom-0 h-48 bg-gradient-to-t from-[#030712] via-[#030712]/90 to-transparent pointer-events-none z-[35]" />
      </div>
    </section>
  );
}
