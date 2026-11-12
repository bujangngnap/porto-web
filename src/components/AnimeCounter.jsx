import React, { useEffect, useRef, useState } from 'react';
import { animate } from 'animejs';

export default function AnimeCounter({ value = 10, suffix = "+", duration = 1800 }) {
  const [displayVal, setDisplayVal] = useState(0);
  const elRef = useRef(null);
  const hasAnimated = useRef(false);

  useEffect(() => {
    const el = elRef.current;
    if (!el || typeof IntersectionObserver === 'undefined') return;

    const observer = new IntersectionObserver(([entry]) => {
      if (entry.isIntersecting && !hasAnimated.current) {
        hasAnimated.current = true;
        const numValue = parseInt(value, 10) || 0;
        const targetObj = { count: 0 };

        animate(targetObj, {
          count: numValue,
          duration: duration,
          ease: 'outExpo',
          onUpdate: () => {
            setDisplayVal(Math.round(targetObj.count));
          }
        });
      }
    }, { threshold: 0.3 });

    observer.observe(el);
    return () => observer.disconnect();
  }, [value, duration]);

  return (
    <span ref={elRef} className="font-display">
      {displayVal}{suffix}
    </span>
  );
}
