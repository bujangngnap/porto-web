import React, { useEffect, useRef } from 'react';
import { animate, stagger } from 'animejs';

export default function AnimeParticles({ count = 24, className = "absolute inset-0 pointer-events-none" }) {
  const containerRef = useRef(null);

  useEffect(() => {
    const container = containerRef.current;
    if (!container) return;

    const reduceMotion = window.matchMedia?.('(prefers-reduced-motion: reduce)').matches ?? false;
    if (reduceMotion) return;

    container.innerHTML = '';
    const particles = [];

    for (let i = 0; i < count; i++) {
      const dot = document.createElement('div');
      dot.className = 'anime-particle absolute rounded-full pointer-events-none will-change-transform';
      const size = Math.random() * 2.5 + 1.5;
      dot.style.width = `${size}px`;
      dot.style.height = `${size}px`;
      dot.style.left = `${Math.random() * 100}%`;
      dot.style.top = `${Math.random() * 100}%`;
      dot.style.background = i % 3 === 0 ? '#60a5fa' : i % 3 === 1 ? '#818cf8' : '#ffffff';
      dot.style.opacity = '0';
      container.appendChild(dot);
      particles.push(dot);
    }

    const anim = animate(particles, {
      translateY: () => [0, (Math.random() - 0.5) * 70],
      translateX: () => [0, (Math.random() - 0.5) * 50],
      scale: () => [Math.random() * 0.5 + 0.5, Math.random() * 1.3 + 0.9],
      opacity: () => [0.15, Math.random() * 0.65 + 0.25, 0.15],
      duration: () => Math.random() * 3000 + 3500,
      delay: stagger(140),
      loop: true,
      direction: 'alternate',
      ease: 'inOutQuad'
    });

    const io = new IntersectionObserver((entries) => {
      const visible = entries.some((entry) => entry.isIntersecting);
      if (visible) anim.play?.();
      else anim.pause?.();
    });
    io.observe(container);

    return () => {
      io.disconnect();
      anim.pause?.();
      container.innerHTML = '';
    };
  }, [count]);

  return <div ref={containerRef} className={className} />;
}
