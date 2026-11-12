import React, { useState, useRef, useEffect, useCallback } from 'react';
import { motion, useMotionValue, useSpring, useMotionTemplate } from 'framer-motion';

export default function SpotlightCard({
  baseImage = "/profile.png",
  revealImage = "/PhotoBlackPanther.png",
  className = ""
}) {
  const [isHovered, setIsHovered] = useState(false);
  const [isMobile, setIsMobile] = useState(false);
  const boundsRef = useRef(null);

  // Motion values for smooth cursor tracking
  const mouseX = useMotionValue(-1000);
  const mouseY = useMotionValue(-1000);

  const springConfig = { damping: 25, stiffness: 280, mass: 0.5 };
  const smoothX = useSpring(mouseX, springConfig);
  const smoothY = useSpring(mouseY, springConfig);

  // Dynamic radial gradient spotlight mask
  const maskString = useMotionTemplate`radial-gradient(circle 130px at ${smoothX}px ${smoothY}px, black 55%, transparent 100%)`;

  useEffect(() => {
    setIsMobile(window.matchMedia("(hover: none)").matches);
  }, []);

  const handleMouseEnter = useCallback((e) => {
    boundsRef.current = e.currentTarget.getBoundingClientRect();
    setIsHovered(true);
  }, []);

  const handleMouseMove = useCallback((e) => {
    const bounds = boundsRef.current || e.currentTarget.getBoundingClientRect();
    boundsRef.current = bounds;
    mouseX.set(e.clientX - bounds.left);
    mouseY.set(e.clientY - bounds.top);
  }, [mouseX, mouseY]);

  const handleMouseLeave = useCallback(() => {
    boundsRef.current = null;
    setIsHovered(false);
    mouseX.set(-1000);
    mouseY.set(-1000);
  }, [mouseX, mouseY]);

  return (
    <div className={`relative flex justify-center items-center ${className}`}>
      {/* Seamless portrait container with bottom gradient mask matching reference site */}
      <div
        className="relative z-10"
        style={{
          WebkitMaskImage: "linear-gradient(to bottom, black 75%, transparent 100%)",
          maskImage: "linear-gradient(to bottom, black 75%, transparent 100%)",
          isolation: "isolate"
        }}
      >
        <div
          className="w-[280px] h-[360px] sm:w-[320px] sm:h-[430px] lg:w-[340px] lg:h-[470px] xl:w-[380px] xl:h-[520px] cursor-none relative select-none"
          onMouseEnter={handleMouseEnter}
          onMouseMove={handleMouseMove}
          onMouseLeave={handleMouseLeave}
        >
          {/* Base Cutout Photo - 100% transparent background */}
          <img
            src={baseImage}
            alt="Profile portrait"
            decoding="async"
            className="absolute inset-0 w-full h-full object-contain object-bottom pointer-events-none select-none"
            loading="eager"
          />

          {/* Easter egg Black Panther Wakanda Salute reveal layer */}
          {isHovered && (
            <motion.div
              className="absolute inset-0 pointer-events-none z-10"
              style={{
                WebkitMaskImage: maskString,
                maskImage: maskString,
                WebkitMaskRepeat: 'no-repeat',
                maskRepeat: 'no-repeat',
              }}
            >
              <img
                src={revealImage}
                alt="Secret Hero Reveal"
                decoding="async"
                className="w-full h-full object-contain object-bottom select-none"
              />
            </motion.div>
          )}

          {/* Flashlight cursor dot */}
          {!isMobile && isHovered && (
            <motion.div
              className="absolute left-0 top-0 w-2.5 h-2.5 -ml-1.25 -mt-1.25 rounded-full pointer-events-none z-50 bg-white shadow-[0_0_0_1.5px_rgba(0,0,0,0.55)]"
              style={{ x: smoothX, y: smoothY }}
            />
          )}
        </div>
      </div>
    </div>
  );
}
