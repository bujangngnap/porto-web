import React, { useId, useRef, useLayoutEffect, useState } from 'react';
import './WordPortalZoom.css';

const DEFAULT_FONT = '"Arial Black", "Arial", sans-serif';

const clamp = (val, min = 0, max = 1) => Math.min(max, Math.max(min, val));
const smoothstep = (min, max, val) => {
  const t = clamp((val - min) / (max - min));
  return t * t * (3 - 2 * t);
};

// Find the largest hollow hole inside glyph (like in letter 'O')
function findHole(ctx, char, font) {
  try {
    const canvas = ctx.canvas;
    ctx.font = font;
    const m = ctx.measureText(char);
    const pad = 8;
    const bbLeft = Math.ceil(m.actualBoundingBoxLeft || 0);
    const bbAscent = Math.ceil(m.actualBoundingBoxAscent || 80);

    canvas.width = Math.max(1, Math.ceil((m.actualBoundingBoxLeft || 0) + (m.actualBoundingBoxRight || m.width || 60)) + pad * 2);
    canvas.height = Math.max(1, Math.ceil((m.actualBoundingBoxAscent || 80) + (m.actualBoundingBoxDescent || 20)) + pad * 2);

    ctx.font = font;
    ctx.fontKerning = "none";
    ctx.fillText(char, pad + bbLeft, pad + bbAscent);

    const { width: w, height: h } = canvas;
    const imgData = ctx.getImageData(0, 0, w, h).data;
    const grid = new Uint16Array(w + 1);

    let maxRadius = 0;
    let bestX = 0;
    let bestY = 0;

    for (let y = 0; y < h; y++) {
      let prev = 0;
      for (let x = 0; x < w; x++) {
        const up = grid[x + 1];
        grid[x + 1] = imgData[(y * w + x) * 4 + 3] > 245 ? Math.min(up, grid[x], prev) + 1 : 0;
        prev = up;
        if (grid[x + 1] > maxRadius) {
          maxRadius = grid[x + 1];
          bestX = x;
          bestY = y;
        }
      }
    }

    if (maxRadius < 3) return null;
    return {
      x: (bestX + 1 - maxRadius / 2 - pad - bbLeft) / 3,
      y: (bestY + 1 - maxRadius / 2 - pad - bbAscent) / 3,
      radius: (maxRadius / 2 - 1) / 3
    };
  } catch {
    return null;
  }
}

export default function WordPortalZoom({
  id = "Portofolio",
  word = "PORTOFOLIO",
  scrollLength = 1.7,
  fontFamily = DEFAULT_FONT,
  fontWeight = 900,
  backdrop,
  lightField,
  children,
  onProgress,
  onLayerChange,
  className = "",
  style = {}
}) {
  const instanceId = `gp-${useId().replace(/[^a-zA-Z0-9]/g, "")}`;
  const rootId = id || instanceId;
  const clipId = `${instanceId}-clip`;

  const rootRef = useRef(null);
  const pinRef = useRef(null);
  const svgRef = useRef(null);
  const clipPathRef = useRef(null);
  const textRef = useRef(null);
  const rectRef = useRef(null);
  const contentRef = useRef(null);
  const progressCallbackRef = useRef(onProgress);
  const layerCallbackRef = useRef(onLayerChange);

  useLayoutEffect(() => {
    progressCallbackRef.current = onProgress;
    layerCallbackRef.current = onLayerChange;
  }, [onProgress, onLayerChange]);

  const cleanWord = (word || "PORTOFOLIO").trim();

  useLayoutEffect(() => {
    const root = rootRef.current;
    const pin = pinRef.current;
    const svg = svgRef.current;
    const clipPath = clipPathRef.current;
    const textEl = textRef.current;
    const rect = rectRef.current;
    const content = contentRef.current;

    if (!root || !pin || !svg || !clipPath || !textEl || !rect) return;

    const canvas = document.createElement("canvas");
    const ctx = canvas.getContext("2d", { willReadFrequently: true });

    let rafId = 0;
    let stageWidth = 1;
    let stageHeight = 1;
    let totalScrollDist = 1;
    let baseScale = 1;
    let targetZoomScale = 1;
    let centerPos = { x: 0, y: 0 };
    let holeTarget = null;
    let lastLayers = { dark: true, light: false };
    let boundingBox = { x: 0, y: 0, width: 1, height: 1 };

    textEl.style.fontFamily = fontFamily || DEFAULT_FONT;

    const measureGlyphs = () => {
      if (!ctx) return false;
      const fontStr = `${fontWeight} 100px ${fontFamily || DEFAULT_FONT}`;
      const largeFontStr = `${fontWeight} 300px ${fontFamily || DEFAULT_FONT}`;
      ctx.font = fontStr;
      ctx.fontKerning = "none";

      const fullMetrics = ctx.measureText(cleanWord);
      const bbLeft = fullMetrics.actualBoundingBoxLeft || 0;
      const bbRight = fullMetrics.actualBoundingBoxRight || fullMetrics.width || 0;
      const totalW = bbLeft + bbRight;
      const totalH = (fullMetrics.actualBoundingBoxAscent || 75) + (fullMetrics.actualBoundingBoxDescent || 20);

      if (!totalW || !totalH) return false;

      boundingBox = {
        x: -bbLeft,
        y: -(fullMetrics.actualBoundingBoxAscent || 75),
        width: totalW,
        height: totalH
      };

      centerPos = {
        x: boundingBox.x + boundingBox.width / 2,
        y: boundingBox.y + boundingBox.height / 2
      };

      // Search for the focus hole (in letter O)
      const holes = [];
      let cursorX = 0;
      for (let i = 0; i < cleanWord.length; i++) {
        const char = cleanWord[i];
        ctx.font = fontStr;
        const charM = ctx.measureText(char);
        const charHole = findHole(ctx, char, largeFontStr);
        if (charHole) {
          holes.push({
            ...charHole,
            x: charHole.x + cursorX,
            index: i
          });
        }
        cursorX += charM.width;
      }

      // Pick the best hole nearest to center (the 'O' in PORTOFOLIO)
      holeTarget = holes.sort((a, b) => b.radius - a.radius || Math.abs(a.x - centerPos.x) - Math.abs(b.x - centerPos.x))[0] || null;
      return true;
    };

    const updateGeometry = () => {
      if (!root.clientWidth) return;
      stageWidth = Math.max(1, pin.clientWidth);
      stageHeight = Math.max(1, pin.clientHeight);

      if (!measureGlyphs()) return;

      const scaleX = (stageWidth * 0.9) / boundingBox.width;
      const scaleY = (stageHeight * 0.36) / boundingBox.height;
      baseScale = Math.min(scaleX, scaleY);

      targetZoomScale = holeTarget ? Math.max(baseScale, Math.hypot(stageWidth, stageHeight) / (holeTarget.radius * 1.35)) : baseScale * 40;

      const viewportH = stageHeight;
      totalScrollDist = (scrollLength - 1) * viewportH;
      root.style.setProperty("--gp-length", String(scrollLength));
      root.style.setProperty("--gp-height", `${viewportH}px`);
      root.dataset.gpReady = "true";
      root.dataset.gpMotion = "on";
    };

    const applyProgress = (progress) => {
      const p = clamp(progress);
      const easeCurve = p < 0.5 ? 4 * p * p * p : 1 - Math.pow(-2 * p + 2, 3) / 2;
      const curScale = Math.exp(Math.log(baseScale) + Math.log(targetZoomScale / baseScale) * easeCurve);

      const targetX = holeTarget ? holeTarget.x : centerPos.x;
      const targetY = holeTarget ? holeTarget.y : centerPos.y;

      const factor = targetZoomScale === baseScale ? 0 : (1 / curScale - 1 / baseScale) / (1 / targetZoomScale - 1 / baseScale);
      const curFocusX = centerPos.x + (targetX - centerPos.x) * factor;
      const curFocusY = centerPos.y + (targetY - centerPos.y) * factor;

      const rotateAngle = -3 * smoothstep(0.06, 0.5, p) * (1 - smoothstep(0.62, 0.92, p));
      const rad = (rotateAngle * Math.PI) / 180;

      const stageCenterX = stageWidth / 2 / curScale;
      const stageCenterY = (stageHeight * 0.48) / curScale;

      clipPath.setAttribute("transform", `scale(${curScale}) rotate(${rotateAngle})`);
      textEl.setAttribute(
        "transform",
        `translate(${Math.cos(rad) * stageCenterX + Math.sin(rad) * stageCenterY - curFocusX} ${-Math.sin(rad) * stageCenterX + Math.cos(rad) * stageCenterY - curFocusY})`
      );

      if (p >= 0.88) {
        rect.removeAttribute("clip-path");
        rect.style.clipPath = "none";
      } else {
        const cpVal = `url(#${clipId})`;
        rect.setAttribute("clip-path", cpVal);
        rect.style.clipPath = cpVal;
      }

      root.style.setProperty("--gp-reveal", String(smoothstep(0.75, 0.92, p)));
      root.style.setProperty("--gp-light-flux", String(smoothstep(0.88, 0.98, p)));
      root.dataset.gpEntered = String(p >= 0.86);

      const contentTop = content ? content.getBoundingClientRect().top : Infinity;
      const dark = p < 0.92 && contentTop > 2;
      const light = p >= 0.84 && contentTop > 2;
      if (dark !== lastLayers.dark || light !== lastLayers.light) {
        lastLayers = { dark, light };
        layerCallbackRef.current?.(lastLayers);
      }

      if (progressCallbackRef.current) {
        progressCallbackRef.current(p);
      }
    };

    const onScroll = () => {
      const topOffset = root.getBoundingClientRect().top;
      const progress = clamp(-topOffset / totalScrollDist);
      applyProgress(progress);
    };

    const handleResize = () => {
      updateGeometry();
      onScroll();
    };

    updateGeometry();
    onScroll();

    window.addEventListener("scroll", onScroll, { passive: true });
    window.addEventListener("resize", handleResize);

    return () => {
      window.removeEventListener("scroll", onScroll);
      window.removeEventListener("resize", handleResize);
      if (rafId) cancelAnimationFrame(rafId);
    };
  }, [cleanWord, scrollLength, fontFamily, fontWeight, clipId]);

  return (
    <div
      ref={rootRef}
      id={rootId}
      className={`gp-root ${className}`}
      style={{
        "--gp-paper": "#030712",
        "--gp-ink": "#ffffff",
        "--gp-field": "#fafafa",
        "--gp-foreground": "#09090b",
        ...style
      }}
    >
      <div data-gp-viewport aria-hidden="true" />

      {/* Pinned Viewport Container */}
      <div ref={pinRef} data-gp-pin>
        {/* Backdrop Dark Topography Layer */}
        {backdrop && (
          <div data-gp-backdrop>
            {backdrop}
          </div>
        )}

        {/* SVG Word Portal Mask */}
        <svg ref={svgRef} data-gp-art aria-hidden="true" focusable="false">
          <defs>
            <clipPath ref={clipPathRef} id={clipId} clipPathUnits="userSpaceOnUse">
              <text
                ref={textRef}
                data-gp-glyph
                x="0"
                y="0"
                style={{
                  fontFamily: fontFamily || DEFAULT_FONT,
                  fontWeight: fontWeight || 900,
                  fontSize: 100,
                  fontKerning: "none",
                  letterSpacing: 0
                }}
              >
                {cleanWord}
              </text>
            </clipPath>
          </defs>

          {/* Light field revealed through letter hole */}
          <rect
            ref={rectRef}
            data-gp-field-rect
            x="0"
            y="0"
            width="100%"
            height="100%"
            fill="var(--gp-field, #fafafa)"
            clipPath={`url(#${clipId})`}
          />
        </svg>

        {/* Continuous light texture revealed smoothly once portal fills viewport */}
        {lightField && (
          <div
            data-gp-light-field
            style={{
              position: 'absolute',
              inset: 0,
              zIndex: 2,
              pointerEvents: 'none',
              opacity: 'var(--gp-light-flux, 0)',
            }}
          >
            {lightField}
          </div>
        )}

        {/* Fallback Text while loading */}
        <span data-gp-fallback aria-hidden="true" style={{ fontFamily, fontWeight }}>
          {cleanWord}
        </span>
      </div>

      {/* Content Revealed Inside the Word Portal (Portfolio Showcase) */}
      <div ref={contentRef} data-gp-content id={`${rootId}-content`}>
        {children}
      </div>
    </div>
  );
}
