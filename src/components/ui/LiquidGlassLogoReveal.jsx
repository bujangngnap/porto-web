import React, { useEffect, useRef, useState } from 'react';

/**
 * LiquidGlassLogoReveal — WebGL2 + TinySDF optical glass reveal component.
 * Based on open-source MIT engine by Zanwei Guo (github.com/zanwei/glass-icon-reveal).
 */
export default function LiquidGlassLogoReveal({
  src = '/ashr-icon.png',
  alt = 'Brand Logo',
  tileSize = 200,
  background = '#0d0d10',
  plateColor = '#141418',
  logoColor = '#f4f4f5',
  loop = false,
  className = 'w-64 h-64',
  onComplete,
}) {
  const canvasRef = useRef(null);
  const [fallback, setFallback] = useState(false);

  useEffect(() => {
    const canvas = canvasRef.current;
    if (!canvas) return undefined;

    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (reduceMotion) {
      setFallback(true);
      onComplete?.();
      return undefined;
    }

    let cancelled = false;
    let instance = null;

    const ensureScript = () =>
      new Promise((resolve, reject) => {
        if (window.AshrLiquidGlassReveal) return resolve(window.AshrLiquidGlassReveal);
        const existing = document.querySelector('script[data-ashr-liquid-glass]');
        if (existing) {
          existing.addEventListener('load', () => resolve(window.AshrLiquidGlassReveal));
          existing.addEventListener('error', reject);
          return;
        }
        const script = document.createElement('script');
        script.src = '/js/liquid-glass-reveal.js';
        script.dataset.ashrLiquidGlass = '1';
        script.onload = () => resolve(window.AshrLiquidGlassReveal);
        script.onerror = reject;
        document.head.appendChild(script);
      });

    const start = async () => {
      try {
        const api = await ensureScript();
        if (cancelled || !api) return;
        instance = await api.mount(canvas, {
          src,
          tileSize,
          background,
          plateColor,
          logoColor,
          loop,
          onComplete,
          onFallback: () => {
            if (!cancelled) setFallback(true);
          },
        });
      } catch {
        if (!cancelled) setFallback(true);
        onComplete?.();
      }
    };

    start();

    return () => {
      cancelled = true;
      instance?.destroy?.();
    };
  }, [src, tileSize, background, plateColor, logoColor, loop, onComplete]);

  return (
    <div className={`relative flex items-center justify-center ${className}`}>
      {!fallback ? (
        <canvas ref={canvasRef} className="w-full h-full block" aria-label={alt} />
      ) : (
        <img src={src} alt={alt} className="w-24 h-24 rounded-2xl object-cover border border-white/15" />
      )}
    </div>
  );
}
