'use client';

import { useEffect, useRef } from 'react';
import './FluxLines.css';

const MAX_COLORS = 8;
const INTRO_SECONDS = 1.8;
const FLOW = { up: [0, 1], down: [0, -1], left: [-1, 0], right: [1, 0] };
const SHARED_START = typeof performance !== 'undefined' ? performance.now() : 0;
const TICKERS = new Set();
let sharedRaf = 0;

const runSharedTick = now => {
  sharedRaf = 0;
  let more = false;
  TICKERS.forEach(step => {
    if (step(now)) more = true;
    else TICKERS.delete(step);
  });
  if (more && TICKERS.size && !sharedRaf) {
    sharedRaf = requestAnimationFrame(runSharedTick);
  }
};

const scheduleSharedTick = () => {
  if (!sharedRaf && TICKERS.size) {
    sharedRaf = requestAnimationFrame(runSharedTick);
  }
};

const VERTEX = `#version 300 es
in vec2 aPosition;
void main() {
  gl_Position = vec4(aPosition, 0.0, 1.0);
}`;

const FIELD = `
float hash(vec3 p3) {
  p3 = fract(p3 * 0.1031);
  p3 += dot(p3, p3.zyx + 33.33);
  return fract((p3.x + p3.y) * p3.z);
}

float smin(float a, float b, float k) {
  float r = exp2(-a / k) + exp2(-b / k);
  return -k * log2(r);
}

float sinlerp(float a, float b, float w) {
  return mix(a, b, w * w * (3.0 - 2.0 * w));
}

float vn(vec2 p, float s, float seed) {
  vec2 cellp = floor(p / s);
  vec2 relp = mod(p, s);
  float g1 = hash(vec3(cellp, seed));
  float g2 = hash(vec3(cellp.x + 1.0, cellp.y, seed));
  float g3 = hash(vec3(cellp.x + 1.0, cellp.y + 1.0, seed));
  float g4 = hash(vec3(cellp.x, cellp.y + 1.0, seed));
  float bx = sinlerp(g1, g2, relp.x / s);
  float tx = sinlerp(g4, g3, relp.x / s);
  return sinlerp(bx, tx, relp.y / s);
}

float dbn(vec2 p, float s, float seed) {
  float o = s / 2.0;
  float n0 = vn(p, s, seed);
  float n1 = vn(p + vec2(o, o), s, seed + 0.1);
  float n2 = vn(p + vec2(-o, o), s, seed + 0.2);
  float n3 = vn(p + vec2(o, -o), s, seed + 0.3);
  float n4 = vn(p + vec2(-o, -o), s, seed + 0.4);
  return (2.0 * n0 + 1.5 * n1 + 1.25 * n2 + 1.125 * n3 + n4) / 7.0;
}
`;

const FRAGMENT = `#version 300 es
precision highp float;
precision highp int;
uniform vec2 uResolution;
uniform float uTime;
uniform vec2 uFlow;
uniform float uSpeed;
uniform float uScale;
uniform float uTurbulence;
uniform float uFluidity;
uniform float uRimWidth;
uniform float uSharpness;
uniform float uShimmer;
uniform float uGlow;
uniform float uLine;
uniform float uMaxHalf;
uniform vec3 uColors[${MAX_COLORS}];
uniform int uColorCount;
uniform vec4 uMagnet;
uniform vec4 uSwirl;
uniform float uReveal;
uniform float uLight;
uniform float uOpaque;
uniform vec3 uBackground;
uniform float uOpacity;
uniform float uUseWorld;
uniform float uWorldY;
uniform float uRatio;
out vec4 outColor;
${FIELD}

vec3 palette(float h) {
  float x = clamp(h, 0.0, 1.0) * float(uColorCount - 1);
  int i = int(floor(x));
  int j = min(i + 1, uColorCount - 1);
  return mix(uColors[i], uColors[j], smoothstep(0.0, 1.0, x - float(i)));
}

void main() {
  float ref = 700.0 / max(uScale, 0.05);
  float normY = uUseWorld > 0.5 ? (700.0 * max(uRatio, 0.5)) : uResolution.y;
  vec2 frag = gl_FragCoord.xy;
  vec2 toMagnet = (frag - uMagnet.xy) / normY;
  float pull = uMagnet.z * exp(-dot(toMagnet, toMagnet) / (uMagnet.w * uMagnet.w));
  if (pull > 0.001) {
    float angle = pull * 0.55;
    float c = cos(angle);
    float s = sin(angle);
    frag = uMagnet.xy + mat2(c, s, -s, c) * (frag - uMagnet.xy);
  }
  if (uSwirl.w > 0.0) {
    vec2 d = frag - uSwirl.xy;
    float reach = uSwirl.z;
    float angle = uSwirl.w * exp(-dot(d, d) / (reach * reach));
    float c = cos(angle);
    float s = sin(angle);
    frag = uSwirl.xy + mat2(c, s, -s, c) * d;
  }
  vec2 p = uUseWorld > 0.5
    ? vec2(frag.x, uWorldY - frag.y) / normY * ref
    : frag / uResolution.y * ref;

  float spd = 200.0 * uSpeed;
  float t = uTime;
  vec2 dir = uFlow;
  vec2 perp = vec2(-dir.y, dir.x);
  float distort1 = vn(p + perp * (t * spd), 60.0, 10.0) * 50.0 * uTurbulence;
  float distort2 = vn(p - perp * (t * spd), 120.0, 15.0) * 100.0 * uTurbulence;
  float peaks = dbn(p + distort1 + dir * (t * spd * 0.5), 40.0, 1.0);
  float peaks2 = dbn(p + distort2 - dir * (t * spd * 0.5), 40.0, 0.0);
  float field = smin(peaks, peaks2, max(uFluidity, 0.001));

  float slope = length(vec2(dFdx(field), dFdy(field)));
  float gap = abs(field - 0.4);
  float pixels = gap / max(slope, 1e-6);
  float halfBand = max(uRimWidth, 0.001) * 0.5;
  float across = max(gap / halfBand, pixels / uMaxHalf);
  float band = 5.0 * uRimWidth * (1.0 - across);
  float reveal = smoothstep(0.2, 0.85, pull);
  float shimmer = vn(p + dir * (t * spd * 0.5), 60.0, 12.0) * uShimmer * (1.0 - reveal * 0.75);
  float lit = clamp(band - shimmer, 0.0, 1.0);
  float glow = pow(lit, uSharpness) * uGlow * (1.0 + reveal * 0.25);
  float core = uLine > 0.0 ? smoothstep(uLine + 0.75, uLine - 0.75, pixels) * max(smoothstep(0.08, 0.6, glow), reveal) : 0.0;
  float shine = clamp(glow, 0.0, 1.0);
  float energy = max(shine * shine, core * 0.85) * uReveal;

  float h = clamp(0.5 + (peaks - peaks2) * 0.8, 0.0, 1.0);
  vec3 tint = palette(h);

  if (uLight > 0.5) {
    float chroma = max(tint.r, max(tint.g, tint.b)) - min(tint.r, min(tint.g, tint.b));
    vec3 ink = mix(vec3(0.07, 0.07, 0.08), tint * 0.55, clamp(chroma * 1.6, 0.0, 1.0));
    float cover = energy * uOpacity;
    outColor = uOpaque > 0.5 ? vec4(mix(uBackground, ink, cover), 1.0) : vec4(ink * cover, cover);
    return;
  }

  vec3 light = tint * energy * uOpacity;
  outColor = uOpaque > 0.5 ? vec4(uBackground + light, 1.0) : vec4(light, max(light.r, max(light.g, light.b)));
}`;

const clamp = (value, min, max) => Math.min(max, Math.max(min, value));

const compile = (gl, type, source) => {
  const shader = gl.createShader(type);
  if (!shader) return null;
  gl.shaderSource(shader, source);
  gl.compileShader(shader);
  if (gl.getShaderParameter(shader, gl.COMPILE_STATUS)) return shader;
  gl.deleteShader(shader);
  return null;
};

const link = gl => {
  const vertex = compile(gl, gl.VERTEX_SHADER, VERTEX);
  const fragment = compile(gl, gl.FRAGMENT_SHADER, FRAGMENT);
  const program = gl.createProgram();
  if (!vertex || !fragment || !program) return null;
  gl.attachShader(program, vertex);
  gl.attachShader(program, fragment);
  gl.bindAttribLocation(program, 0, 'aPosition');
  gl.linkProgram(program);
  gl.deleteShader(vertex);
  gl.deleteShader(fragment);
  if (!gl.getProgramParameter(program, gl.LINK_STATUS)) {
    gl.deleteProgram(program);
    return null;
  }
  const uniforms = {};
  const count = gl.getProgramParameter(program, gl.ACTIVE_UNIFORMS);
  for (let i = 0; i < count; i++) {
    const info = gl.getActiveUniform(program, i);
    if (info) uniforms[info.name.replace('[0]', '')] = gl.getUniformLocation(program, info.name);
  }
  return { program, uniforms };
};

const FluxLines = ({
  colors = ['#ffffff', '#ffffff', '#ffffff'],
  speed = 0.5,
  scale = 1.6,
  turbulence = 1,
  fluidity = 0.1,
  rimWidth = 0.2,
  sharpness = 2.5,
  shimmer = 1.5,
  glow = 2,
  lineWidth = 1,
  flowDirection = 'down',
  opacity = 1,
  mouseInteraction = true,
  mouseStrength = 1,
  mouseRadius = 0.35,
  mouseDampening = 0.15,
  clickSwirl = true,
  intro = true,
  lightMode = false,
  transparent = true,
  backgroundColor,
  paused = false,
  worldSpace = false,
  dpr,
  mixBlendMode,
  className = '',
  style,
  ...rest
}) => {
  const containerRef = useRef(null);
  const canvasRef = useRef(null);
  const wakeRef = useRef(null);
  const settings = {
    colors: Array.isArray(colors) && colors.length ? colors.slice(0, MAX_COLORS) : ['#ffffff'],
    speed,
    scale: Math.max(0.05, scale),
    turbulence,
    fluidity: Math.max(0.001, fluidity),
    rimWidth: Math.max(0.001, rimWidth),
    sharpness: Math.max(0.1, sharpness),
    shimmer: Math.max(0, shimmer),
    glow: Math.max(0, glow),
    lineWidth: Math.max(0, lineWidth),
    flow: FLOW[flowDirection] ?? FLOW.down,
    opacity: clamp(opacity, 0, 1),
    mouseInteraction,
    mouseStrength: Math.max(0, mouseStrength),
    mouseRadius: Math.max(0.01, mouseRadius),
    mouseDampening: Math.max(0, mouseDampening),
    clickSwirl,
    intro,
    lightMode,
    transparent,
    backgroundColor,
    paused,
    worldSpace,
    dpr
  };
  const settingsRef = useRef(settings);
  settingsRef.current = settings;

  useEffect(() => {
    const container = containerRef.current;
    const canvas = canvasRef.current;
    const gl = canvas?.getContext('webgl2', {
      alpha: true,
      premultipliedAlpha: true,
      antialias: false,
      depth: false,
      stencil: false
    });
    if (!container || !canvas || !gl) return undefined;

    const program = link(gl);
    if (!program) return undefined;
    const u = program.uniforms;

    const buffer = gl.createBuffer();
    const vao = gl.createVertexArray();
    gl.bindVertexArray(vao);
    gl.bindBuffer(gl.ARRAY_BUFFER, buffer);
    gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([-1, -1, 3, -1, -1, 3]), gl.STATIC_DRAW);
    gl.enableVertexAttribArray(0);
    gl.vertexAttribPointer(0, 2, gl.FLOAT, false, 0, 0);

    const probe = document.createElement('canvas').getContext('2d', { willReadFrequently: true });
    const cache = new Map();
    const parse = (value, fallback) => {
      if (!value) return fallback;
      const key = String(value);
      if (cache.has(key)) return cache.get(key);
      let rgb = fallback;
      if (probe) {
        probe.clearRect(0, 0, 1, 1);
        probe.fillStyle = '#000000';
        probe.fillStyle = key;
        probe.fillRect(0, 0, 1, 1);
        const [r, g, b] = probe.getImageData(0, 0, 1, 1).data;
        rgb = [r / 255, g / 255, b / 255];
      }
      cache.set(key, rgb);
      return rgb;
    };
    const palette = new Float32Array(MAX_COLORS * 3);
    let lastPaletteKey = '';
    let lastBgKey = '';
    let cachedBg = [1, 1, 1];

    const reduce = window.matchMedia?.('(prefers-reduced-motion: reduce)').matches ?? false;
    const pointer = { x: 0, y: 0, clientX: 0, clientY: 0, hasClient: false, inside: false };
    const magnet = { x: 0, y: 0, active: 0 };
    const swirl = { x: 0, y: 0, age: 10 };
    let time = settingsRef.current.worldSpace ? (performance.now() - SHARED_START) * 0.001 : 0;
    let reveal = settingsRef.current.intro && !reduce ? 0 : 1;
    let last = 0;
    const initRect = container.getBoundingClientRect();
    let visible = initRect.bottom >= -150 && initRect.top <= window.innerHeight + 150;

    const render = () => {
      const s = settingsRef.current;
      const ratio = canvas.width / Math.max(1, container.clientWidth);
      const rect = s.worldSpace ? container.getBoundingClientRect() : null;
      const worldY = rect ? (window.scrollY + rect.bottom) * ratio : 0;
      const pKey = s.colors.join('|');
      if (pKey !== lastPaletteKey) {
        lastPaletteKey = pKey;
        s.colors.forEach((value, index) => palette.set(parse(value, [1, 1, 1]), index * 3));
      }
      const bgKey = `${s.lightMode ? 1 : 0}:${s.backgroundColor || ''}`;
      if (bgKey !== lastBgKey) {
        lastBgKey = bgKey;
        cachedBg = parse(s.backgroundColor, s.lightMode ? [1, 1, 1] : [0.0706, 0.0588, 0.0902]);
      }
      const intro = Math.min(1, reveal);
      gl.viewport(0, 0, canvas.width, canvas.height);
      gl.useProgram(program.program);
      gl.bindVertexArray(vao);
      gl.uniform2f(u.uResolution, canvas.width, canvas.height);
      gl.uniform1f(u.uTime, time);
      gl.uniform2f(u.uFlow, s.flow[0], s.flow[1]);
      gl.uniform1f(u.uSpeed, s.speed);
      gl.uniform1f(u.uScale, s.scale);
      gl.uniform1f(u.uTurbulence, s.turbulence);
      gl.uniform1f(u.uFluidity, s.fluidity);
      gl.uniform1f(u.uRimWidth, s.rimWidth);
      gl.uniform1f(u.uSharpness, s.sharpness);
      gl.uniform1f(u.uShimmer, s.shimmer);
      gl.uniform1f(u.uGlow, s.glow);
      gl.uniform1f(u.uLine, s.lineWidth * ratio * 0.5);
      gl.uniform1f(u.uMaxHalf, 28 * ratio);
      gl.uniform3fv(u.uColors, palette);
      gl.uniform1i(u.uColorCount, s.colors.length);
      gl.uniform4f(u.uMagnet, magnet.x, magnet.y, magnet.active * s.mouseStrength, s.mouseRadius);
      const age = swirl.age;
      const twist = 1.9 * Math.min(1, age / 0.23) * Math.exp(-Math.max(0, age - 0.23) * 1.1);
      const swirlScale = s.worldSpace ? 700 * ratio : canvas.height;
      gl.uniform4f(u.uSwirl, swirl.x, swirl.y, swirlScale * 0.22 * (1 + age * 0.5), age < 6 ? twist : 0);
      gl.uniform1f(u.uReveal, intro * intro * (3 - 2 * intro));
      gl.uniform1f(u.uLight, s.lightMode ? 1 : 0);
      gl.uniform1f(u.uOpaque, s.transparent ? 0 : 1);
      gl.uniform3fv(u.uBackground, cachedBg);
      gl.uniform1f(u.uOpacity, s.opacity);
      gl.uniform1f(u.uUseWorld, s.worldSpace ? 1 : 0);
      gl.uniform1f(u.uWorldY, worldY);
      gl.uniform1f(u.uRatio, ratio);
      gl.drawArrays(gl.TRIANGLES, 0, 3);
    };

    const syncPointerFromClient = () => {
      if (!pointer.hasClient) return;
      const rect = container.getBoundingClientRect();
      const margin = settingsRef.current.worldSpace ? 560 : 0;
      const x = pointer.clientX - rect.left;
      const y = pointer.clientY - rect.top;
      const ratio = canvas.width / Math.max(1, rect.width);
      pointer.inside = x >= -margin && y >= -margin && x <= rect.width + margin && y <= rect.height + margin;
      pointer.x = x * ratio;
      pointer.y = (rect.height - y) * ratio;
    };

    const step = now => {
      if (!visible || document.hidden) {
        last = 0;
        return false;
      }
      const s = settingsRef.current;
      const dt = last ? clamp((now - last) / 1000, 0.0005, 0.05) : 1 / 60;
      last = now;
      const motion = s.paused || reduce ? 0 : 1;
      if (s.worldSpace && motion > 0) {
        time = (now - SHARED_START) * 0.001;
      } else {
        time += dt * motion;
      }
      swirl.age += dt;
      if (s.intro && !reduce && reveal < 1) reveal = Math.min(1, reveal + dt / INTRO_SECONDS);
      else if (!s.intro || reduce) reveal = 1;

      if (s.worldSpace && pointer.hasClient) {
        syncPointerFromClient();
      }

      const engaged = s.mouseInteraction && pointer.inside;
      const follow = s.mouseDampening > 0 ? 1 - Math.exp(-dt / s.mouseDampening) : 1;
      if (engaged) {
        magnet.x += (pointer.x - magnet.x) * follow;
        magnet.y += (pointer.y - magnet.y) * follow;
      }
      const target = engaged ? 1 : 0;
      magnet.active += (target - magnet.active) * (1 - Math.exp(-dt / 0.3));
      if (Math.abs(target - magnet.active) < 0.001) magnet.active = target;

      render();
      const busy = motion > 0 || magnet.active !== target || reveal < 1 || swirl.age < 6;
      if (!busy) last = 0;
      return busy;
    };

    const wake = () => {
      const s = settingsRef.current;
      if (visible && !document.hidden && !s.paused && !reduce) {
        TICKERS.add(step);
        scheduleSharedTick();
      }
    };

    const syncClock = () => {
      const s = settingsRef.current;
      if (s.worldSpace && !s.paused && !reduce) {
        time = (performance.now() - SHARED_START) * 0.001;
      }
    };

    const resize = () => {
      const s = settingsRef.current;
      const width = Math.max(1, container.clientWidth);
      const height = Math.max(1, container.clientHeight);
      const ratio = s.dpr ? Math.min(s.dpr, 2) : Math.min(window.devicePixelRatio || 1, 1);
      canvas.width = Math.max(1, Math.round(width * ratio));
      canvas.height = Math.max(1, Math.round(height * ratio));
      if (!s.paused) {
        syncClock();
        render();
        wake();
      }
    };

    const locate = event => {
      pointer.clientX = event.clientX;
      pointer.clientY = event.clientY;
      pointer.hasClient = true;
      syncPointerFromClient();
    };

    const onScroll = () => {
      if (!settingsRef.current.worldSpace) return;
      const rect = container.getBoundingClientRect();
      const inView = rect.bottom >= -150 && rect.top <= window.innerHeight + 150;
      visible = inView;
      if (!inView || settingsRef.current.paused) return;
      const wasInside = pointer.inside;
      syncPointerFromClient();
      if (pointer.inside && !wasInside && magnet.active < 0.05) {
        magnet.x = pointer.x;
        magnet.y = pointer.y;
      }
      wake();
    };

    const onPointerMove = event => {
      const wasInside = pointer.inside;
      locate(event);
      if (pointer.inside && !wasInside && magnet.active < 0.05) {
        magnet.x = pointer.x;
        magnet.y = pointer.y;
      }
      wake();
    };

    const onPointerDown = event => {
      onPointerMove(event);
      if (!pointer.inside || !settingsRef.current.clickSwirl || reduce) return;
      swirl.x = pointer.x;
      swirl.y = pointer.y;
      swirl.age = 0;
      wake();
    };

    const onPointerUp = event => {
      if (event.pointerType !== 'mouse') {
        pointer.inside = false;
        pointer.hasClient = false;
      }
    };

    const onPointerLeave = () => {
      pointer.inside = false;
      pointer.hasClient = false;
    };

    const onVisibility = () => {
      last = 0;
      wake();
    };

    const resizeObserver = new ResizeObserver(resize);
    resizeObserver.observe(container);
    const intersection = new IntersectionObserver(entries => {
      visible = entries.some(entry => entry.isIntersecting);
      if (visible) {
        last = 0;
        wake();
      }
    });
    intersection.observe(container);
    window.addEventListener('pointermove', onPointerMove, { passive: true });
    window.addEventListener('pointerdown', onPointerDown, { passive: true });
    window.addEventListener('pointerup', onPointerUp, { passive: true });
    window.addEventListener('pointercancel', onPointerUp, { passive: true });
    window.addEventListener('scroll', onScroll, { passive: true });
    document.documentElement.addEventListener('pointerleave', onPointerLeave);
    document.addEventListener('visibilitychange', onVisibility);
    resize();

    let lastDpr = settingsRef.current.dpr;
    wakeRef.current = () => {
      const s = settingsRef.current;
      if (s.paused) {
        TICKERS.delete(step);
        last = 0;
        return;
      }
      if (s.dpr !== lastDpr) {
        lastDpr = s.dpr;
        resize();
        return;
      }
      if (!TICKERS.has(step) && visible) {
        syncClock();
        render();
      }
      wake();
    };

    return () => {
      TICKERS.delete(step);
      wakeRef.current = null;
      resizeObserver.disconnect();
      intersection.disconnect();
      window.removeEventListener('pointermove', onPointerMove);
      window.removeEventListener('pointerdown', onPointerDown);
      window.removeEventListener('pointerup', onPointerUp);
      window.removeEventListener('pointercancel', onPointerUp);
      window.removeEventListener('scroll', onScroll);
      document.documentElement.removeEventListener('pointerleave', onPointerLeave);
      document.removeEventListener('visibilitychange', onVisibility);
      gl.deleteProgram(program.program);
      gl.deleteBuffer(buffer);
      gl.deleteVertexArray(vao);
    };
  }, []);

  useEffect(() => {
    wakeRef.current?.();
  });

  return (
    <div
      ref={containerRef}
      className={`flux-lines-container${className ? ` ${className}` : ''}`}
      style={mixBlendMode ? { ...style, mixBlendMode } : style}
      {...rest}
    >
      <canvas ref={canvasRef} className="flux-lines-canvas" aria-hidden="true" />
    </div>
  );
};

export const FLUX_LIGHT_PRESET = {
  lightMode: true,
  colors: ['#94a3b8', '#475569', '#2c67ed'],
  speed: 0.45,
  scale: 1.6,
  fluidity: 0.16,
  rimWidth: 0.2,
  shimmer: 0.45,
  glow: 2.0,
  lineWidth: 1,
  flowDirection: 'down',
  mouseInteraction: true,
  clickSwirl: true,
  intro: false,
  worldSpace: true,
  opacity: 0.42,
};

export const FLUX_DARK_PRESET = {
  lightMode: false,
  colors: ['#ffffff', '#60a5fa', '#2c67ed'],
  speed: 0.45,
  scale: 1.6,
  fluidity: 0.12,
  rimWidth: 0.2,
  shimmer: 0.85,
  glow: 2.0,
  lineWidth: 1,
  flowDirection: 'down',
  mouseInteraction: true,
  clickSwirl: true,
  opacity: 0.55,
};

export default FluxLines;
