import React, { useState, useRef, useEffect, useCallback, useId } from 'react';
import './GlassFolder.css';

const FOLDER_WIDTH = 340;
const FOLDER_HEIGHT = 272;
const TAB_OFFSET_TOP = 54;
const FRONT_TAB_HEIGHT = 74;
const FRONT_HEIGHT = FOLDER_HEIGHT - FRONT_TAB_HEIGHT;
const SHEET_WIDTH = 272;
const SHEET_HEIGHT = 184;

const BACK_PATH = "M0 56A22 22 0 0 1 22 34L146 34C170 34 172 0 198 0L318 0A22 22 0 0 1 340 22L340 250A22 22 0 0 1 318 272L22 272A22 22 0 0 1 0 250Z";
const FRONT_PATH = "M0 22A22 22 0 0 1 22 0L118 0C144 0 146 32 172 32L318 32A22 22 0 0 1 340 54L340 " + (FRONT_HEIGHT - 22) + "A22 22 0 0 1 318 " + FRONT_HEIGHT + "L22 " + FRONT_HEIGHT + "A22 22 0 0 1 0 " + (FRONT_HEIGHT - 22) + "Z";

const createSvgMask = (w, h, path) => {
  const svg = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ${w} ${h}"><path fill="#000" d="${path}"/></svg>`;
  const url = `url("data:image/svg+xml,${encodeURIComponent(svg)}")`;
  return {
    WebkitMaskImage: url,
    maskImage: url,
    WebkitMaskSize: "100% 100%",
    maskSize: "100% 100%",
    WebkitMaskRepeat: "no-repeat",
    maskRepeat: "no-repeat"
  };
};

const BACK_MASK_STYLE = createSvgMask(FOLDER_WIDTH, FOLDER_HEIGHT, BACK_PATH);
const FRONT_MASK_STYLE = createSvgMask(FOLDER_WIDTH, FRONT_HEIGHT, FRONT_PATH);

function getSlotOffset(index, total) {
  return total <= 1 ? 0 : (index / (total - 1)) * 2 - 1;
}

function createPrng(seed) {
  let s = seed >>> 0;
  return () => {
    s = (s + 1831565813) >>> 0;
    let t = s;
    t = Math.imul(t ^ (t >>> 15), t | 1);
    t ^= t + Math.imul(t ^ (t >>> 7), t | 61);
    return ((t ^ (t >>> 14)) >>> 0) / 4294967296;
  };
}

function hashString(str) {
  const s = String(str || "");
  let h = 2166136261;
  for (let i = 0; i < s.length; i++) {
    h = Math.imul(h ^ s.charCodeAt(i), 16777619);
  }
  return h >>> 0;
}

export default function GlassFolder({
  label = "Projects",
  meta = 0,
  tone = "dark",
  files = [],
  action = "more",
  menu = [],
  labelPlacement = "bottom-left",
  width = 320,
  appear = true,
  appearDelay = 0,
  onFileSelect,
  className = ""
}) {
  const instanceId = useId().replace(/:/g, "");
  const rootRef = useRef(null);
  const filesRef = useRef(null);
  const hitBtnRef = useRef(null);
  const menuBtnRef = useRef(null);
  const menuRef = useRef(null);

  const [isOpen, setIsOpen] = useState(false);
  const [menuOpen, setMenuOpen] = useState(false);
  const [isEntered, setIsEntered] = useState(!appear);

  const scale = width / FOLDER_WIDTH;
  const displayFiles = files.slice(Math.max(0, files.length - 4));

  // Intersection observer entrance
  useEffect(() => {
    if (!appear) return;
    const el = rootRef.current;
    if (!el || typeof IntersectionObserver === "undefined") {
      setIsEntered(true);
      return;
    }
    const triggerEnter = () => {
      observer.disconnect();
      setTimeout(() => setIsEntered(true), appearDelay);
    };
    const observer = new IntersectionObserver((entries) => {
      if (entries.some((entry) => entry.isIntersecting)) {
        triggerEnter();
      }
    }, { threshold: 0.05 });
    observer.observe(el);
    return () => observer.disconnect();
  }, [appear, appearDelay]);

  // Pointer tracking for subtle 3D tilt
  useEffect(() => {
    const root = rootRef.current;
    if (!root) return;

    let rafId = 0;
    let px = 0;
    let py = 0;
    let bounds = null;

    const updateVars = () => {
      rafId = 0;
      root.style.setProperty("--gf-px", px.toFixed(3));
      root.style.setProperty("--gf-py", py.toFixed(3));
    };

    const handlePointerEnter = (e) => {
      if (e.pointerType !== "touch") bounds = root.getBoundingClientRect();
    };

    const handlePointerMove = (e) => {
      if (e.pointerType === "touch") return;
      const b = bounds || (bounds = root.getBoundingClientRect());
      if (!b.width || !b.height) return;
      px = ((e.clientX - b.left) / b.width) * 2 - 1;
      py = ((e.clientY - b.top) / b.height) * 2 - 1;
      if (!rafId) rafId = requestAnimationFrame(updateVars);
    };

    const handlePointerLeave = () => {
      bounds = null;
      px = 0;
      py = 0;
      if (!rafId) rafId = requestAnimationFrame(updateVars);
    };

    root.addEventListener("pointerenter", handlePointerEnter, { passive: true });
    root.addEventListener("pointermove", handlePointerMove, { passive: true });
    root.addEventListener("pointerleave", handlePointerLeave, { passive: true });

    return () => {
      root.removeEventListener("pointerenter", handlePointerEnter);
      root.removeEventListener("pointermove", handlePointerMove);
      root.removeEventListener("pointerleave", handlePointerLeave);
      if (rafId) cancelAnimationFrame(rafId);
    };
  }, []);

  // Handle escape / outside clicks
  useEffect(() => {
    if (!isOpen && !menuOpen) return;

    const handleKeyDown = (e) => {
      if (e.key === "Escape") {
        if (menuOpen) setMenuOpen(false);
        else setIsOpen(false);
      }
    };

    const handlePointerDown = (e) => {
      const target = e.target;
      if (menuOpen && !menuRef.current?.contains(target) && !menuBtnRef.current?.contains(target)) {
        setMenuOpen(false);
      }
      if (isOpen && !menuOpen && !rootRef.current?.contains(target)) {
        setIsOpen(false);
      }
    };

    document.addEventListener("keydown", handleKeyDown);
    document.addEventListener("pointerdown", handlePointerDown);
    return () => {
      document.removeEventListener("keydown", handleKeyDown);
      document.removeEventListener("pointerdown", handlePointerDown);
    };
  }, [isOpen, menuOpen]);

  const toggleFolder = () => {
    setMenuOpen(false);
    setIsOpen(prev => !prev);
  };

  const menuItems = [
    { label: isOpen ? "Close folder" : "Open folder", onSelect: toggleFolder },
    ...menu
  ];

  return (
    <div
      ref={rootRef}
      className={`gf-root ${className}`}
      data-tone={tone}
      data-open={isOpen || undefined}
      data-entered={isEntered || undefined}
      data-place={labelPlacement}
      style={{
        width: width,
        height: (FOLDER_HEIGHT + TAB_OFFSET_TOP) * scale
      }}
    >
      <div
        className="gf-stage"
        style={{
          transform: `translateY(${TAB_OFFSET_TOP * scale}px) scale(${scale})`
        }}
      >
        <div className="gf-tilt">
          {/* Bottom shadow */}
          <div className="gf-shadow" aria-hidden="true" />

          {/* Back folder flap */}
          <div className="gf-back" style={BACK_MASK_STYLE} aria-hidden="true">
            <div className="gf-back-sheen" />
          </div>

          <svg
            className="gf-rim gf-back-rim"
            viewBox={`0 0 ${FOLDER_WIDTH} ${FOLDER_HEIGHT}`}
            aria-hidden="true"
          >
            <path d={BACK_PATH} />
          </svg>

          {/* Front folder flap */}
          <div className="gf-front" style={FRONT_MASK_STYLE}>
            <div className="gf-tint" aria-hidden="true" />
            <div className="gf-spec" aria-hidden="true" />

            <svg
              className="gf-rim gf-front-rim"
              viewBox={`0 0 ${FOLDER_WIDTH} ${FRONT_HEIGHT}`}
              aria-hidden="true"
            >
              <defs>
                <linearGradient id={`gf-rim-${instanceId}`} x1="0" y1="0" x2="0" y2="1">
                  <stop offset="0" stopColor="#fff" stopOpacity="0.95" />
                  <stop offset="0.35" stopColor="#fff" stopOpacity="0.35" />
                  <stop offset="1" stopColor="#fff" stopOpacity="0.12" />
                </linearGradient>
              </defs>
              <path d={FRONT_PATH} stroke={`url(#gf-rim-${instanceId})`} />
            </svg>

            {/* Click hit area to open/close */}
            <button
              ref={hitBtnRef}
              type="button"
              className="gf-hit cursor-pointer"
              aria-expanded={isOpen}
              aria-label={`${isOpen ? "Close " : "Open "}${label}`}
              onClick={toggleFolder}
            />

            {/* Folder label & counter */}
            <div className="gf-label pointer-events-none select-none">
              {meta !== undefined && (
                <span className="gf-meta font-mono font-bold">{meta}</span>
              )}
              <span className="gf-title font-bold tracking-tight">{label}</span>
            </div>
          </div>

          {/* Stacked files sticking out */}
          <div
            ref={filesRef}
            className="gf-files"
            role="list"
            aria-label={`${label} contents`}
          >
            {displayFiles.map((item, idx) => {
              const slot = getSlotOffset(idx, displayFiles.length);
              const rand = createPrng(hashString(item.name || "") + idx);
              const hasImage = !!item.image;

              return (
                <div key={idx} role="listitem" className="gf-sheet-slot">
                  <button
                    type="button"
                    className={`gf-sheet ${hasImage ? "gf-sheet--art" : ""} cursor-pointer`}
                    style={{
                      "--gf-slot": slot,
                      "--gf-depth": displayFiles.length - 1 - idx,
                      "--gf-jx": `${((rand() - 0.5) * 10).toFixed(2)}px`,
                      "--gf-jr": `${((rand() - 0.5) * 3.2).toFixed(2)}deg`,
                      zIndex: idx + 1
                    }}
                    onClick={() => onFileSelect && onFileSelect(item, idx)}
                  >
                    {hasImage ? (
                      <img
                        className="gf-sheet-art"
                        src={item.image}
                        alt={item.name || ""}
                        width={SHEET_WIDTH}
                        height={SHEET_HEIGHT}
                        style={item.objectPosition ? { objectPosition: item.objectPosition } : undefined}
                        loading="lazy"
                        draggable={false}
                      />
                    ) : (
                      <span className="gf-lines" aria-hidden="true">
                        <i style={{ width: "86%" }} />
                        <i style={{ width: "92%" }} />
                        <i style={{ width: "64%" }} />
                        <i style={{ width: "88%" }} />
                        <i style={{ width: "42%" }} />
                      </span>
                    )}

                    {(item.name || item.meta) && (
                      <span className="gf-sheet-head">
                        {item.name && <span className="gf-sheet-name">{item.name}</span>}
                        {item.meta && <span className="gf-sheet-meta">{item.meta}</span>}
                      </span>
                    )}
                  </button>
                </div>
              );
            })}
          </div>

          {/* Three-dots menu button */}
          {action === "more" && (
            <button
              ref={menuBtnRef}
              type="button"
              className="gf-key gf-key--more cursor-pointer"
              aria-label={`More options for ${label}`}
              aria-haspopup="menu"
              aria-expanded={menuOpen}
              onClick={(e) => {
                e.stopPropagation();
                setMenuOpen(prev => !prev);
              }}
            >
              <svg className="gf-glyph gf-glyph--more" viewBox="0 0 30 12" aria-hidden="true">
                <rect x="1" y="1.5" width="8" height="9" rx="3.4" />
                <rect x="11" y="1.5" width="8" height="9" rx="3.4" />
                <rect x="21" y="1.5" width="8" height="9" rx="3.4" />
              </svg>
            </button>
          )}

          {/* Dropdown Menu */}
          {action === "more" && menuOpen && (
            <div
              ref={menuRef}
              role="menu"
              aria-label={`${label} options`}
              className="gf-menu"
              data-open={menuOpen || undefined}
            >
              {menuItems.map((menuItem, mIdx) => (
                <button
                  key={mIdx}
                  type="button"
                  role="menuitem"
                  className="gf-menu-item cursor-pointer text-left font-medium"
                  onClick={() => {
                    setMenuOpen(false);
                    menuItem.onSelect && menuItem.onSelect();
                  }}
                >
                  {menuItem.label}
                </button>
              ))}
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
