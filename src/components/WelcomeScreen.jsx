import React, { useState, useEffect, useRef } from 'react';
import { motion, AnimatePresence } from 'framer-motion';

export default function WelcomeScreen({ onComplete }) {
  const [progress, setProgress] = useState(0);
  const [isExiting, setIsExiting] = useState(false);
  const canvasRef = useRef(null);

  useEffect(() => {
    let current = 0;
    const interval = setInterval(() => {
      current++;
      const p = Math.min(100, current);
      setProgress(p);
      if (current >= 100) {
        clearInterval(interval);
        setTimeout(() => {
          setIsExiting(true);
          setTimeout(() => onComplete && onComplete(), 700);
        }, 350);
      }
    }, 38);

    return () => clearInterval(interval);
  }, [onComplete]);

  // Particle text canvas with seamless crisp text cross-fade
  useEffect(() => {
    const canvas = canvasRef.current;
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    let animId;
    let frameCount = 0;

    const dpr = Math.min(window.devicePixelRatio || 1, 2);
    const winW = window.innerWidth;
    const width = Math.min(winW - 24, 850);
    const height = 240;

    canvas.width = width * dpr;
    canvas.height = height * dpr;
    canvas.style.width = `${width}px`;
    canvas.style.height = `${height}px`;
    ctx.scale(dpr, dpr);

    // Create crisp offscreen text buffer
    const offCanvas = document.createElement('canvas');
    offCanvas.width = width * dpr;
    offCanvas.height = height * dpr;
    const offCtx = offCanvas.getContext('2d');
    offCtx.scale(dpr, dpr);

    offCtx.textAlign = 'center';
    offCtx.textBaseline = 'middle';

    const scale = Math.min(1, Math.max(0.65, width / 780));
    const font1 = Math.round(52 * scale);
    const font2 = Math.round(68 * scale);

    // Line 1: "Welcome To My"
    offCtx.font = `800 ${font1}px 'Plus Jakarta Sans', system-ui, sans-serif`;
    const grad1 = offCtx.createLinearGradient(width / 2 - 200, 0, width / 2 + 200, 0);
    grad1.addColorStop(0, '#ffffff');
    grad1.addColorStop(0.5, '#e0e7ff');
    grad1.addColorStop(1, '#93c5fd');
    offCtx.fillStyle = grad1;
    offCtx.fillText('Welcome To My', width / 2, height / 2 - font1 * 0.65);

    // Line 2: "Portofolio Website"
    offCtx.font = `900 ${font2}px 'Plus Jakarta Sans', system-ui, sans-serif`;
    offCtx.fillStyle = '#2c67ed';
    offCtx.shadowColor = 'rgba(44, 103, 237, 0.4)';
    offCtx.shadowBlur = 12;
    offCtx.fillText('Portofolio Website', width / 2, height / 2 + font2 * 0.6);
    offCtx.shadowBlur = 0;

    // Sample pixels for particle formation
    const sampleCanvas = document.createElement('canvas');
    sampleCanvas.width = width;
    sampleCanvas.height = height;
    const sampleCtx = sampleCanvas.getContext('2d');
    sampleCtx.drawImage(offCanvas, 0, 0, width * dpr, height * dpr, 0, 0, width, height);

    const imgData = sampleCtx.getImageData(0, 0, width, height).data;
    const particles = [];
    const step = width < 500 ? 3 : 3;

    for (let y = 0; y < height; y += step) {
      for (let x = 0; x < width; x += step) {
        const idx = (y * width + x) * 4;
        const alpha = imgData[idx + 3];
        if (alpha > 50) {
          const r = imgData[idx];
          const g = imgData[idx + 1];
          const b = imgData[idx + 2];

          // Distribute from edges for a cinematic fly-in effect
          const angle = Math.random() * Math.PI * 2;
          const dist = (width + height) * (0.4 + Math.random() * 0.5);

          particles.push({
            x: width / 2 + Math.cos(angle) * dist,
            y: height / 2 + Math.sin(angle) * dist,
            targetX: x,
            targetY: y,
            color: `rgb(${r}, ${g}, ${b})`,
            radius: Math.random() * 1.2 + 1.2,
            speed: 0.06 + Math.random() * 0.05,
          });
        }
      }
    }

    const render = () => {
      frameCount++;
      ctx.clearRect(0, 0, width, height);

      // Transition smoothly into the crisp original text buffer as particles arrive
      let textAlpha = 0;
      if (frameCount > 40) {
        textAlpha = Math.min(1, (frameCount - 40) / 30);
      }

      // Draw assembling particles (fading out as crisp text fades in)
      if (textAlpha < 1) {
        ctx.globalAlpha = 1 - textAlpha * 0.5;
        for (let i = 0; i < particles.length; i++) {
          const p = particles[i];
          p.x += (p.targetX - p.x) * p.speed;
          p.y += (p.targetY - p.y) * p.speed;

          ctx.fillStyle = p.color;
          ctx.beginPath();
          ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
          ctx.fill();
        }
      }

      // Draw crisp high-res text overlay so every character is 100% sharp and readable
      if (textAlpha > 0) {
        ctx.globalAlpha = textAlpha;
        ctx.drawImage(offCanvas, 0, 0, width * dpr, height * dpr, 0, 0, width, height);
      }
      ctx.globalAlpha = 1;

      animId = requestAnimationFrame(render);
    };

    render();

    return () => cancelAnimationFrame(animId);
  }, []);

  const handleSkip = () => {
    setIsExiting(true);
    setTimeout(() => onComplete && onComplete(), 300);
  };

  return (
    <AnimatePresence>
      {!isExiting && (
        <motion.div
          key="welcome-screen"
          initial={{ opacity: 1 }}
          exit={{ opacity: 0, filter: 'blur(16px)', scale: 1.04 }}
          transition={{ duration: 0.7, ease: [0.22, 1, 0.36, 1] }}
          className="fixed inset-0 z-[99999] flex flex-col items-center justify-center bg-[#030712] overflow-hidden select-none"
        >
          {/* Subtle electric blue ambient aura behind title */}
          <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[650px] h-[400px] bg-[radial-gradient(ellipse_at_center,rgba(44,103,237,0.22),transparent_70%)] pointer-events-none blur-2xl" />

          {/* Interactive particles text canvas */}
          <div className="relative z-10 w-full max-w-4xl px-4 flex flex-col items-center">
            <div className="w-full flex justify-center mb-6 sm:mb-8">
              <canvas ref={canvasRef} className="max-w-full" />
            </div>

            {/* Clean Loading bar matching live site */}
            <div className="w-64 sm:w-80 flex flex-col items-center gap-3">
              <div className="w-full flex justify-between items-center text-xs font-mono font-bold tracking-widest text-slate-400">
                <span className="flex items-center gap-1.5">
                  <span className="inline-block w-2 h-2 rounded-full bg-blue-500 animate-pulse" />
                  Loading
                </span>
                <span className="text-white">{progress}%</span>
              </div>

              <div className="w-full h-1.5 bg-white/10 rounded-full overflow-hidden relative">
                <motion.div
                  className="h-full bg-gradient-to-r from-blue-500 via-[#2c67ed] to-indigo-400 rounded-full shadow-[0_0_15px_rgba(44,103,237,0.8)]"
                  style={{ width: `${progress}%` }}
                />
              </div>
            </div>

            {/* Skip Intro button */}
            <button
              onClick={handleSkip}
              className="mt-8 text-xs font-mono text-slate-500 hover:text-white transition-colors duration-200 uppercase tracking-widest px-4 py-1.5 rounded-full border border-white/5 hover:border-white/20 bg-white/[0.02] cursor-pointer"
            >
              Skip Intro →
            </button>
          </div>
        </motion.div>
      )}
    </AnimatePresence>
  );
}
