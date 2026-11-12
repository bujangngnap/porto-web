import React, { useEffect, useRef } from 'react';
import { animate, stagger } from 'animejs';

export default function AnimeText({
  text = "PORTOFOLIO",
  className = "",
  delay = 300,
  interactive = true
}) {
  const containerRef = useRef(null);

  useEffect(() => {
    if (!containerRef.current) return;
    const letters = containerRef.current.querySelectorAll('.anime-letter');

    // Initial cinematic entrance
    animate(letters, {
      opacity: [0, 1],
      translateY: [35, 0],
      scale: [0.7, 1],
      rotateZ: () => [(Math.random() - 0.5) * 15, 0],
      delay: stagger(45, { start: delay }),
      duration: 900,
      ease: 'outBack(1.7)'
    });
  }, [delay, text]);

  const handleHover = () => {
    if (!interactive || !containerRef.current) return;
    const letters = containerRef.current.querySelectorAll('.anime-letter');

    animate(letters, {
      translateY: [0, -14, 0],
      scale: [1, 1.15, 1],
      color: ['#ffffff', '#60a5fa', '#ffffff'],
      delay: stagger(30),
      duration: 600,
      ease: 'inOutQuad'
    });
  };

  const words = text.split(' ');

  return (
    <span
      ref={containerRef}
      onMouseEnter={handleHover}
      className={`inline-block cursor-default select-none ${className}`}
    >
      {words.map((word, wordIndex) => (
        <span key={wordIndex} className="inline-block whitespace-nowrap">
          {word.split('').map((char, charIndex) => (
            <span
              key={charIndex}
              className="anime-letter inline-block opacity-0 will-change-transform"
            >
              {char}
            </span>
          ))}
          {wordIndex < words.length - 1 && (
            <span className="anime-letter inline-block opacity-0 will-change-transform">
              &nbsp;
            </span>
          )}
        </span>
      ))}
    </span>
  );
}
