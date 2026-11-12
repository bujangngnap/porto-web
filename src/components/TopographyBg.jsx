import React, { useEffect, useRef } from 'react';

// Compact deterministic 2D Simplex Noise
const F2 = 0.5 * (Math.sqrt(3) - 1);
const G2 = (3 - Math.sqrt(3)) / 6;
const GRAD3 = [
  [1, 1], [-1, 1], [1, -1], [-1, -1],
  [1, 0], [-1, 0], [1, 0], [-1, 0],
  [0, 1], [0, -1], [0, 1], [0, -1],
];

function createPerm(seed = 4207) {
  const p = new Uint8Array(256);
  for (let i = 0; i < 256; i++) p[i] = i;
  let s = seed;
  for (let i = 255; i > 0; i--) {
    s = (s * 16807) % 2147483647;
    const j = s % (i + 1);
    const tmp = p[i];
    p[i] = p[j];
    p[j] = tmp;
  }
  const perm = new Uint8Array(512);
  const permMod12 = new Uint8Array(512);
  for (let i = 0; i < 512; i++) {
    perm[i] = p[i & 255];
    permMod12[i] = perm[i] % 12;
  }
  return { perm, permMod12 };
}

const { perm: PERM, permMod12: PERM_MOD12 } = createPerm(4207);

function simplex2(xin, yin) {
  let n0 = 0, n1 = 0, n2 = 0;
  const s = (xin + yin) * F2;
  const i = Math.floor(xin + s);
  const j = Math.floor(yin + s);
  const t = (i + j) * G2;
  const X0 = i - t;
  const Y0 = j - t;
  const x0 = xin - X0;
  const y0 = yin - Y0;

  let i1, j1;
  if (x0 > y0) {
    i1 = 1;
    j1 = 0;
  } else {
    i1 = 0;
    j1 = 1;
  }

  const x1 = x0 - i1 + G2;
  const y1 = y0 - j1 + G2;
  const x2 = x0 - 1 + 2 * G2;
  const y2 = y0 - 1 + 2 * G2;

  const ii = i & 255;
  const jj = j & 255;

  let t0 = 0.5 - x0 * x0 - y0 * y0;
  if (t0 >= 0) {
    const gi0 = PERM_MOD12[ii + PERM[jj]];
    t0 *= t0;
    n0 = t0 * t0 * (GRAD3[gi0][0] * x0 + GRAD3[gi0][1] * y0);
  }

  let t1 = 0.5 - x1 * x1 - y1 * y1;
  if (t1 >= 0) {
    const gi1 = PERM_MOD12[ii + i1 + PERM[jj + j1]];
    t1 *= t1;
    n1 = t1 * t1 * (GRAD3[gi1][0] * x1 + GRAD3[gi1][1] * y1);
  }

  let t2 = 0.5 - x2 * x2 - y2 * y2;
  if (t2 >= 0) {
    const gi2 = PERM_MOD12[ii + 1 + PERM[jj + 1]];
    t2 *= t2;
    n2 = t2 * t2 * (GRAD3[gi2][0] * x2 + GRAD3[gi2][1] * y2);
  }

  return 70 * (n0 + n1 + n2);
}

function parseHexToRgb(color) {
  if (typeof color === 'string' && color.startsWith('#')) {
    let hex = color.slice(1);
    if (hex.length === 3) {
      hex = hex.split('').map((c) => c + c).join('');
    }
    const num = parseInt(hex, 16);
    if (!Number.isNaN(num)) {
      return {
        r: (num >> 16) & 255,
        g: (num >> 8) & 255,
        b: num & 255,
      };
    }
  }
  return { r: 148, g: 163, b: 184 };
}

function interp(a, b, threshold) {
  const diff = b - a;
  if (Math.abs(diff) < 1e-6) return 0.5;
  const t = (threshold - a) / diff;
  return t < 0 ? 0 : t > 1 ? 1 : t;
}

export default function TopographyBg({
  className = "w-full h-full",
  lineColor = "#94a3b8",
  lineOpacity = 0.35,
  levels = 14,
  speed = 0.35,
  paused = false,
  interactive = true,
}) {
  const canvasRef = useRef(null);

  useEffect(() => {
    const canvas = canvasRef.current;
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    if (!ctx) return;

    let animationFrameId = null;
    let isVisible = true;
    let time = 0;
    let lastTimestamp = performance.now();

    // Grid dimensions (22px cell + C1 quadratic Bezier chaining = vector-smooth at 60fps)
    let width = 300;
    let height = 300;
    let dpr = 1;
    const cellSize = 22;
    let cols = 0;
    let rows = 0;

    // Pre-allocated typed buffers (zero GC per frame)
    let field = new Float32Array(0);
    let edgeX = new Float32Array(0);
    let edgeY = new Float32Array(0);
    let adj0 = new Int32Array(0);
    let adj1 = new Int32Array(0);
    let visited = new Uint8Array(0);
    let activeEdges = new Int32Array(0);
    let polyX = new Float32Array(0);
    let polyY = new Float32Array(0);

    // Frame-rate independent interactive cursor state
    const pointer = {
      hasMoved: false,
      clientX: -9999,
      clientY: -9999,
      x: -9999,
      y: -9999,
      targetStrength: 0,
      strength: 0,
      rippleTime: -10,
      rippleX: 0,
      rippleY: 0,
    };

    const baseRgb = parseHexToRgb(lineColor);

    const resize = () => {
      const parent = canvas.parentElement;
      if (!parent) return;
      dpr = Math.min(window.devicePixelRatio || 1, 2);
      width = parent.clientWidth || 300;
      height = parent.clientHeight || 300;

      canvas.width = Math.floor(width * dpr);
      canvas.height = Math.floor(height * dpr);
      canvas.style.width = `${width}px`;
      canvas.style.height = `${height}px`;

      cols = Math.ceil(width / cellSize) + 2;
      rows = Math.ceil(height / cellSize) + 2;

      const numVerts = cols * rows;
      const numEdges = numVerts * 2;
      field = new Float32Array(numVerts);
      edgeX = new Float32Array(numEdges);
      edgeY = new Float32Array(numEdges);
      adj0 = new Int32Array(numEdges).fill(-1);
      adj1 = new Int32Array(numEdges).fill(-1);
      visited = new Uint8Array(numEdges);
      activeEdges = new Int32Array(numEdges);
      polyX = new Float32Array(numEdges);
      polyY = new Float32Array(numEdges);
    };

    resize();

    const ro = new ResizeObserver(() => resize());
    if (canvas.parentElement) {
      ro.observe(canvas.parentElement);
    }
    window.addEventListener('resize', resize);

    const handlePointerMove = (e) => {
      if (!interactive) return;
      pointer.hasMoved = true;
      pointer.clientX = e.clientX;
      pointer.clientY = e.clientY;
    };

    const handlePointerLeave = () => {
      pointer.hasMoved = false;
      pointer.targetStrength = 0;
    };

    const handlePointerDown = (e) => {
      if (!interactive) return;
      const rect = canvas.getBoundingClientRect();
      if (
        e.clientX >= rect.left &&
        e.clientX <= rect.right &&
        e.clientY >= rect.top &&
        e.clientY <= rect.bottom
      ) {
        pointer.rippleX = e.clientX - rect.left;
        pointer.rippleY = e.clientY - rect.top;
        pointer.rippleTime = time;
      }
    };

    if (interactive) {
      window.addEventListener('pointermove', handlePointerMove, { passive: true });
      window.addEventListener('pointerdown', handlePointerDown, { passive: true });
      document.addEventListener('mouseleave', handlePointerLeave);
    }

    const io = new IntersectionObserver(
      (entries) => {
        isVisible = entries[0]?.isIntersecting ?? true;
      },
      { rootMargin: '80px' }
    );
    io.observe(canvas);

    // Smooth elevation contour levels
    const totalLevels = Math.max(6, levels);
    const thresholds = [];
    const minT = -0.85;
    const maxT = 0.85;
    for (let k = 0; k < totalLevels; k++) {
      thresholds.push(minT + (k / (totalLevels - 1)) * (maxT - minT));
    }

    const draw = (now) => {
      animationFrameId = requestAnimationFrame(draw);

      const dt = Math.min(0.05, Math.max(0.001, (now - lastTimestamp) * 0.001));
      lastTimestamp = now;

      if (!isVisible || cols === 0 || rows === 0) return;

      if (!paused) {
        time += dt * 0.45 * speed;
      }

      // Sync cursor coordinates smoothly relative to current viewport scroll
      if (interactive && pointer.hasMoved) {
        const rect = canvas.getBoundingClientRect();
        const margin = 160;
        const nearCanvas =
          pointer.clientX >= rect.left - margin &&
          pointer.clientX <= rect.right + margin &&
          pointer.clientY >= rect.top - margin &&
          pointer.clientY <= rect.bottom + margin;

        if (nearCanvas) {
          const targetX = pointer.clientX - rect.left;
          const targetY = pointer.clientY - rect.top;
          if (pointer.x < -5000) {
            pointer.x = targetX;
            pointer.y = targetY;
          } else {
            const follow = 1 - Math.exp(-dt * 12);
            pointer.x += (targetX - pointer.x) * follow;
            pointer.y += (targetY - pointer.y) * follow;
          }
          pointer.targetStrength = 1;
        } else {
          pointer.targetStrength = 0;
        }
      }

      const fade = 1 - Math.exp(-dt * 8);
      pointer.strength += (pointer.targetStrength - pointer.strength) * fade;

      const hasCursor = interactive && pointer.strength > 0.008 && pointer.x > -4000;
      const cx = pointer.x;
      const cy = pointer.y;
      const cursorRadiusSq = 230 * 230;

      const rippleElapsed = time - pointer.rippleTime;
      const hasRipple = interactive && pointer.rippleTime > -5 && rippleElapsed >= 0 && rippleElapsed < 1.8;
      const rippleRadius = rippleElapsed * 280;

      // 1. Evaluate smooth 2D Simplex terrain heightfield
      const tSlow = time * 0.16;
      const scale1 = 0.0017;
      const scale2 = 0.0033;

      for (let r = 0; r < rows; r++) {
        const y = r * cellSize;
        const rowOffset = r * cols;
        for (let c = 0; c < cols; c++) {
          const x = c * cellSize;

          const n1 = simplex2(x * scale1 + tSlow, y * scale1 - tSlow * 0.65);
          const n2 = simplex2(x * scale2 - tSlow * 0.7 + 14.2, y * scale2 + tSlow * 0.5 + 27.9);
          let val = n1 * 0.82 + n2 * 0.18;

          // Smooth Gaussian dome around cursor (zero jagged ripples)
          if (hasCursor) {
            const dx = x - cx;
            const dy = y - cy;
            const dSq = dx * dx + dy * dy;
            if (dSq < cursorRadiusSq * 3.5) {
              const falloff = Math.exp(-dSq / cursorRadiusSq);
              val += falloff * pointer.strength * 0.62;
            }
          }

          // Smooth expanding click ripple
          if (hasRipple) {
            const rdx = x - pointer.rippleX;
            const rdy = y - pointer.rippleY;
            const dist = Math.hypot(rdx, rdy);
            const band = Math.abs(dist - rippleRadius);
            if (band < 110) {
              const amp = Math.cos((band / 110) * Math.PI * 0.5) * (1 - rippleElapsed / 1.8);
              val += amp * 0.4;
            }
          }

          field[rowOffset + c] = val;
        }
      }

      // 2. Clear & prepare canvas
      ctx.setTransform(1, 0, 0, 1, 0, 0);
      ctx.clearRect(0, 0, canvas.width, canvas.height);

      ctx.save();
      ctx.scale(dpr, dpr);
      ctx.lineCap = 'round';
      ctx.lineJoin = 'round';

      const makeStroke = (alpha) => {
        const baseStr = `rgba(${baseRgb.r}, ${baseRgb.g}, ${baseRgb.b}, ${alpha})`;
        if (!hasCursor) return baseStr;

        const grad = ctx.createRadialGradient(cx, cy, 8, cx, cy, 240);
        const hiAlpha = Math.min(0.88, alpha * 1.9);
        grad.addColorStop(0, `rgba(44, 103, 237, ${hiAlpha})`);
        grad.addColorStop(0.55, `rgba(${baseRgb.r}, ${baseRgb.g}, ${baseRgb.b}, ${Math.min(0.72, alpha * 1.3)})`);
        grad.addColorStop(1, baseStr);
        return grad;
      };

      const minorAlpha = Math.min(0.85, lineOpacity * 0.55);
      const majorAlpha = Math.min(0.95, lineOpacity * 1.25);
      const minorStroke = makeStroke(minorAlpha);
      const majorStroke = makeStroke(majorAlpha);

      // 3. Marching Squares + Polyline Chaining + Quadratic Bezier Curves
      for (let lvlIdx = 0; lvlIdx < thresholds.length; lvlIdx++) {
        const T = thresholds[lvlIdx];
        const isMajor = lvlIdx % 3 === 0;
        let activeCount = 0;

        const addSeg = (eA, ax, ay, eB, bx, by) => {
          if (adj0[eA] === -1) {
            adj0[eA] = eB;
            edgeX[eA] = ax;
            edgeY[eA] = ay;
            activeEdges[activeCount++] = eA;
          } else {
            adj1[eA] = eB;
          }

          if (adj0[eB] === -1) {
            adj0[eB] = eA;
            edgeX[eB] = bx;
            edgeY[eB] = by;
            activeEdges[activeCount++] = eB;
          } else {
            adj1[eB] = eA;
          }
        };

        for (let r = 0; r < rows - 1; r++) {
          const row0 = r * cols;
          const row1 = (r + 1) * cols;
          const y0 = r * cellSize;
          const y1 = y0 + cellSize;

          for (let c = 0; c < cols - 1; c++) {
            const v0 = field[row0 + c];
            const v1 = field[row0 + c + 1];
            const v2 = field[row1 + c + 1];
            const v3 = field[row1 + c];

            const cMin = Math.min(v0, v1, v2, v3);
            if (T < cMin) continue;
            const cMax = Math.max(v0, v1, v2, v3);
            if (T > cMax) continue;

            const idx =
              (v0 >= T ? 8 : 0) |
              (v1 >= T ? 4 : 0) |
              (v2 >= T ? 2 : 0) |
              (v3 >= T ? 1 : 0);

            if (idx === 0 || idx === 15) continue;

            const x0 = c * cellSize;
            const x1 = x0 + cellSize;

            // Unique edge IDs
            const eTop = (row0 + c) * 2;
            const eBottom = (row1 + c) * 2;
            const eLeft = (row0 + c) * 2 + 1;
            const eRight = (row0 + c + 1) * 2 + 1;

            const topX = x0 + interp(v0, v1, T) * cellSize;
            const rightY = y0 + interp(v1, v2, T) * cellSize;
            const bottomX = x0 + interp(v3, v2, T) * cellSize;
            const leftY = y0 + interp(v0, v3, T) * cellSize;

            switch (idx) {
              case 1:
              case 14:
                addSeg(eLeft, x0, leftY, eBottom, bottomX, y1);
                break;
              case 2:
              case 13:
                addSeg(eBottom, bottomX, y1, eRight, x1, rightY);
                break;
              case 3:
              case 12:
                addSeg(eLeft, x0, leftY, eRight, x1, rightY);
                break;
              case 4:
              case 11:
                addSeg(eTop, topX, y0, eRight, x1, rightY);
                break;
              case 6:
              case 9:
                addSeg(eTop, topX, y0, eBottom, bottomX, y1);
                break;
              case 7:
              case 8:
                addSeg(eTop, topX, y0, eLeft, x0, leftY);
                break;
              case 5: {
                const vc = (v0 + v1 + v2 + v3) * 0.25;
                if (vc >= T) {
                  addSeg(eTop, topX, y0, eLeft, x0, leftY);
                  addSeg(eBottom, bottomX, y1, eRight, x1, rightY);
                } else {
                  addSeg(eTop, topX, y0, eRight, x1, rightY);
                  addSeg(eLeft, x0, leftY, eBottom, bottomX, y1);
                }
                break;
              }
              case 10: {
                const vc = (v0 + v1 + v2 + v3) * 0.25;
                if (vc >= T) {
                  addSeg(eTop, topX, y0, eRight, x1, rightY);
                  addSeg(eLeft, x0, leftY, eBottom, bottomX, y1);
                } else {
                  addSeg(eTop, topX, y0, eLeft, x0, leftY);
                  addSeg(eBottom, bottomX, y1, eRight, x1, rightY);
                }
                break;
              }
            }
          }
        }

        if (activeCount === 0) continue;

        ctx.beginPath();

        // Pass A: Trace open polylines (starting from boundary endpoint edges)
        for (let i = 0; i < activeCount; i++) {
          const startEdge = activeEdges[i];
          if (visited[startEdge] || adj1[startEdge] !== -1) continue;

          let len = 0;
          let curr = startEdge;
          let prev = -1;

          while (curr !== -1 && !visited[curr]) {
            visited[curr] = 1;
            polyX[len] = edgeX[curr];
            polyY[len] = edgeY[curr];
            len++;
            const next = adj0[curr] !== prev ? adj0[curr] : adj1[curr];
            prev = curr;
            curr = next;
          }

          if (len === 2) {
            ctx.moveTo(polyX[0], polyY[0]);
            ctx.lineTo(polyX[1], polyY[1]);
          } else if (len >= 3) {
            ctx.moveTo(polyX[0], polyY[0]);
            for (let k = 1; k < len - 2; k++) {
              const midX = (polyX[k] + polyX[k + 1]) * 0.5;
              const midY = (polyY[k] + polyY[k + 1]) * 0.5;
              ctx.quadraticCurveTo(polyX[k], polyY[k], midX, midY);
            }
            ctx.quadraticCurveTo(polyX[len - 2], polyY[len - 2], polyX[len - 1], polyY[len - 1]);
          }
        }

        // Pass B: Trace closed loops with continuous C1 Bezier curves
        for (let i = 0; i < activeCount; i++) {
          const startEdge = activeEdges[i];
          if (visited[startEdge]) continue;

          let len = 0;
          let curr = startEdge;
          let prev = -1;

          while (curr !== -1 && !visited[curr]) {
            visited[curr] = 1;
            polyX[len] = edgeX[curr];
            polyY[len] = edgeY[curr];
            len++;
            const next = adj0[curr] !== prev ? adj0[curr] : adj1[curr];
            prev = curr;
            curr = next;
          }

          // Skip tiny speck artifacts (< 4 points)
          if (len >= 4) {
            const startX = (polyX[len - 1] + polyX[0]) * 0.5;
            const startY = (polyY[len - 1] + polyY[0]) * 0.5;
            ctx.moveTo(startX, startY);

            for (let k = 0; k < len; k++) {
              const nextIdx = k + 1 < len ? k + 1 : 0;
              const midX = (polyX[k] + polyX[nextIdx]) * 0.5;
              const midY = (polyY[k] + polyY[nextIdx]) * 0.5;
              ctx.quadraticCurveTo(polyX[k], polyY[k], midX, midY);
            }
          }
        }

        // Fast clean reset ONLY for touched active edges
        for (let i = 0; i < activeCount; i++) {
          const e = activeEdges[i];
          adj0[e] = -1;
          adj1[e] = -1;
          visited[e] = 0;
        }

        ctx.lineWidth = isMajor ? 1.35 : 0.85;
        ctx.strokeStyle = isMajor ? majorStroke : minorStroke;
        ctx.stroke();
      }

      ctx.restore();
    };

    animationFrameId = requestAnimationFrame(draw);

    return () => {
      ro.disconnect();
      io.disconnect();
      window.removeEventListener('resize', resize);
      if (interactive) {
        window.removeEventListener('pointermove', handlePointerMove);
        window.removeEventListener('pointerdown', handlePointerDown);
        document.removeEventListener('mouseleave', handlePointerLeave);
      }
      if (animationFrameId) cancelAnimationFrame(animationFrameId);
    };
  }, [lineColor, lineOpacity, levels, speed, paused, interactive]);

  return (
    <div className={`overflow-hidden pointer-events-none ${className}`}>
      <canvas ref={canvasRef} className="w-full h-full block" />
    </div>
  );
}
