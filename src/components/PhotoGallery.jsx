import React, { useState, useRef, useEffect, useMemo } from 'react';
import { motion, useScroll, useTransform, useSpring, useMotionValue } from 'framer-motion';
import { X } from 'lucide-react';
import FluxLines, { FLUX_LIGHT_PRESET } from './FluxLines';

// Golden ratio spiral positioning (137.5 degrees)
function createSpiralLayout(images) {
  return images.map((item, index) => {
    const angleRad = index * 137.5 * (Math.PI / 180);
    const radiusFactorX = 0.28 + (index % 3) * 0.08;
    const radiusFactorY = 0.26 + (index % 2) * 0.09;
    const rotationZ = (index % 2 === 0 ? 1 : -1) * (3 + (index % 5) * 2);
    return {
      item,
      angleCos: Math.cos(angleRad),
      angleSin: Math.sin(angleRad),
      radiusFactorX,
      radiusFactorY,
      rotationZ,
      index
    };
  });
}

function SpiralPhotoCard({ card, progress, pointer, total, stageSize, onSelect }) {
  const isMobile = stageSize.isMobile;
  const startRange = (card.index / total) * 0.45;
  const endRange = startRange + 0.55;

  const cardProgress = useTransform(progress, [startRange, endRange], [0, 1]);
  const zPos = useTransform(cardProgress, [0, 1], [isMobile ? -1200 : -1700, isMobile ? 350 : 500]);
  const opacity = useTransform(cardProgress, [0, 0.18, 0.82, 0.98], [0, 1, 1, 0]);
  const visibility = useTransform(opacity, val => (val <= 0.01 ? 'hidden' : 'visible'));
  const pointerEvents = useTransform(cardProgress, val => (val > 0.06 && val < 0.86 ? 'auto' : 'none'));

  const baseCardWidth = isMobile
    ? Math.max(135, Math.min(185, stageSize.width * 0.38))
    : Math.max(195, Math.min(285, stageSize.width * 0.19));
  const cardWidth = baseCardWidth * (card.item.widthScale || 1);

  const centerX = card.angleCos * (stageSize.width * card.radiusFactorX);
  const centerY = card.angleSin * (stageSize.height * card.radiusFactorY);
  const tiltFactor = isMobile ? 20 : 45;

  const transformString = useTransform([zPos, pointer.x, pointer.y], ([z, px, py]) => {
    const finalX = centerX - px * tiltFactor;
    const finalY = centerY - py * tiltFactor;
    return `translate3d(calc(-50% + ${finalX}px), calc(-50% + ${finalY}px), ${z}px) rotateZ(${card.rotationZ}deg)`;
  });

  return (
    <motion.div
      style={{
        width: cardWidth,
        transform: transformString,
        opacity,
        visibility,
        pointerEvents,
        zIndex: card.index + 1
      }}
      className={`absolute left-1/2 top-1/2 ${card.item.aspectClass || 'aspect-[4/5]'} will-change-transform select-none cursor-pointer`}
      onClick={() => onSelect(card.item)}
    >
      <div className="group relative h-full w-full overflow-hidden rounded-2xl bg-zinc-100 shadow-[0_8px_20px_rgba(15,23,42,0.14),0_2px_6px_rgba(15,23,42,0.08)] hover:shadow-[0_16px_32px_rgba(15,23,42,0.22)] transition-shadow duration-300 border border-zinc-200/80">
        <div className="h-full w-full transition-transform duration-700 ease-out group-hover:scale-105">
          <img
            src={card.item.src}
            alt={card.item.title || "Gallery photo"}
            loading="lazy"
            decoding="async"
            draggable={false}
            className="h-full w-full object-cover"
            style={card.item.objectPosition ? { objectPosition: card.item.objectPosition } : undefined}
          />
        </div>
      </div>
    </motion.div>
  );
}

export default function PhotoGallery({ photos = [], hideBackground = false }) {
  const containerRef = useRef(null);
  const [activePhoto, setActivePhoto] = useState(null);
  const [stageSize, setStageSize] = useState({ width: 1440, height: 900, isMobile: false });

  // Spring physics for pointer tracking
  const mouseX = useMotionValue(0);
  const mouseY = useMotionValue(0);
  const springConfig = { stiffness: 60, damping: 25, mass: 0.5 };
  const smoothX = useSpring(mouseX, springConfig);
  const smoothY = useSpring(mouseY, springConfig);

  useEffect(() => {
    const handleResize = () => {
      setStageSize({
        width: window.innerWidth,
        height: window.innerHeight,
        isMobile: window.innerWidth < 768
      });
    };
    handleResize();
    window.addEventListener('resize', handleResize);
    return () => window.removeEventListener('resize', handleResize);
  }, []);

  useEffect(() => {
    const el = containerRef.current;
    if (!el) return;
    let inView = false;
    const io = new IntersectionObserver((entries) => {
      inView = entries.some((entry) => entry.isIntersecting);
    });
    io.observe(el);

    const handlePointerMove = (e) => {
      if (!inView) return;
      mouseX.set((e.clientX / window.innerWidth - 0.5) * 2);
      mouseY.set((e.clientY / window.innerHeight - 0.5) * 2);
    };
    window.addEventListener('pointermove', handlePointerMove, { passive: true });
    return () => {
      io.disconnect();
      window.removeEventListener('pointermove', handlePointerMove);
    };
  }, [mouseX, mouseY]);

  // Framer Motion scroll progress
  const { scrollYProgress } = useScroll({
    target: containerRef,
    offset: ["start 30%", "end end"]
  });

  const smoothProgress = useSpring(scrollYProgress, { stiffness: 80, damping: 30, restDelta: 0.0001 });
  const mappedProgress = useTransform(smoothProgress, [0, 1], [0.14, 1.02]);
  const indicatorOpacity = useTransform(smoothProgress, [0, 0.16], [1, 0]);

  // Ensure 9 cards for beautiful spiral fullness
  const displayPhotos = useMemo(() => {
    if (photos.length >= 8) return photos.slice(0, 9);
    const repeated = [];
    while (repeated.length < 9) {
      repeated.push(...photos);
    }
    return repeated.slice(0, 9);
  }, [photos]);

  const cards = useMemo(() => createSpiralLayout(displayPhotos), [displayPhotos]);

  return (
    <section
      id="Gallery"
      ref={containerRef}
      className={`relative z-10 w-full select-none ${hideBackground ? 'bg-transparent' : 'bg-[#fafafa]'}`}
      style={{ height: '240vh' }}
    >
      {/* Interactive FluxLines fluid backdrop on light canvas */}
      {!hideBackground && (
        <div className="absolute inset-0 pointer-events-none z-0">
          <div className="sticky top-0 h-screen w-full">
            <FluxLines {...FLUX_LIGHT_PRESET} />
          </div>
        </div>
      )}

      {/* Sticky 3D Tunnel Viewport */}
      <div
        className="sticky top-0 h-screen w-full overflow-hidden bg-transparent text-zinc-900 flex items-center justify-center z-10"
        style={{
          perspective: stageSize.isMobile ? '900px' : '1200px',
          transformStyle: 'preserve-3d'
        }}
      >
        {/* Scroll down prompt line */}
        <motion.div
          style={{ opacity: indicatorOpacity }}
          className="pointer-events-none absolute bottom-8 z-20 flex flex-col items-center gap-2 text-xs font-semibold text-zinc-500 tracking-wider uppercase font-mono"
        >
          <span>Scroll To Explore</span>
          <div className="h-6 w-px bg-zinc-400 animate-pulse" />
        </motion.div>

        {/* 3D Spiral Cards Stage */}
        <div className="absolute inset-0 pointer-events-none" style={{ transformStyle: 'preserve-3d' }}>
          {cards.map((card, idx) => (
            <SpiralPhotoCard
              key={idx}
              card={card}
              progress={mappedProgress}
              pointer={{ x: smoothX, y: smoothY }}
              total={cards.length}
              stageSize={stageSize}
              onSelect={(item) => setActivePhoto(item)}
            />
          ))}
        </div>
      </div>

      {/* Lightbox Modal */}
      {activePhoto && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-8 bg-black/85 backdrop-blur-md">
          <div
            className="fixed inset-0 cursor-pointer"
            onClick={() => setActivePhoto(null)}
          />
          <div className="relative z-10 max-w-4xl max-h-[85vh] w-full rounded-3xl overflow-hidden bg-white border border-zinc-200 shadow-2xl">
            <button
              onClick={() => setActivePhoto(null)}
              className="absolute top-4 right-4 p-2.5 rounded-full bg-black/60 text-white hover:bg-black/90 transition-colors z-20"
            >
              <X className="w-5 h-5" />
            </button>
            <img
              src={activePhoto.src}
              alt={activePhoto.title || "Photo"}
              className="w-full max-h-[75vh] object-contain bg-zinc-100"
            />
            {activePhoto.title && (
              <div className="p-5 bg-white flex items-center justify-between">
                <h3 className="text-base font-bold text-zinc-900">{activePhoto.title}</h3>
                <span className="text-xs font-mono text-zinc-500">{activePhoto.category}</span>
              </div>
            )}
          </div>
        </div>
      )}
    </section>
  );
}
