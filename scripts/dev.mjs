/**
 * Combined dev launcher for the React frontend (Vite :5173) and Laravel backend/admin (:8000).
 * Ensures running `npm run dev` starts both servers automatically unless `:8000` is already active.
 */
import { spawn } from 'node:child_process';
import net from 'node:net';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const rootDir = path.resolve(__dirname, '..');
const viteEntry = path.join(rootDir, 'node_modules', 'vite', 'bin', 'vite.js');
const extraArgs = process.argv.slice(2);

function isPortListening(port, host = '127.0.0.1') {
  return new Promise((resolve) => {
    const socket = net.createConnection({ port, host });
    const done = (up) => {
      socket.removeAllListeners();
      socket.destroy();
      resolve(up);
    };
    socket.setTimeout(350);
    socket.once('connect', () => done(true));
    socket.once('timeout', () => done(false));
    socket.once('error', () => done(false));
  });
}

function killTree(child) {
  if (!child || child.killed || child.exitCode !== null) return;
  if (process.platform === 'win32') {
    spawn('taskkill', ['/PID', String(child.pid), '/T', '/F'], { stdio: 'ignore' });
  } else {
    child.kill('SIGTERM');
  }
}

function pipePrefixed(stream, label, writer) {
  let buf = '';
  stream.on('data', (chunk) => {
    buf += chunk.toString();
    const lines = buf.split(/\r?\n/);
    buf = lines.pop() ?? '';
    for (const line of lines) {
      if (line.length > 0) writer.write(`${label} ${line}\n`);
    }
  });
  stream.on('end', () => {
    if (buf.length > 0) writer.write(`${label} ${buf}\n`);
  });
}

async function main() {
  const children = [];
  let shuttingDown = false;

  const shutdown = (code = 0) => {
    if (shuttingDown) return;
    shuttingDown = true;
    for (const child of children) killTree(child);
    setTimeout(() => process.exit(code), 150);
  };

  process.on('SIGINT', () => shutdown(0));
  process.on('SIGTERM', () => shutdown(0));

  const apiAlreadyUp = await isPortListening(8000);
  if (apiAlreadyUp) {
    console.log('[api] Laravel backend sudah aktif di http://127.0.0.1:8000 (admin: http://127.0.0.1:8000/admin)');
  } else {
    const apiChild = process.platform === 'win32'
      ? spawn('powershell', ['-NoProfile', '-ExecutionPolicy', 'Bypass', '-File', path.join(rootDir, 'backend', 'serve.ps1')], {
          cwd: rootDir,
          stdio: ['ignore', 'pipe', 'pipe'],
        })
      : spawn('php', ['artisan', 'serve', '--host=127.0.0.1', '--port=8000'], {
          cwd: path.join(rootDir, 'backend'),
          stdio: ['ignore', 'pipe', 'pipe'],
        });

    children.push(apiChild);
    pipePrefixed(apiChild.stdout, '[api]', process.stdout);
    pipePrefixed(apiChild.stderr, '[api]', process.stderr);

    apiChild.on('exit', (code) => {
      if (!shuttingDown && code && code !== 0) {
        console.error(`[api] Backend berhenti dengan kode ${code}`);
      }
    });
  }

  const viteArgs = [viteEntry];
  if (!extraArgs.includes('--clearScreen')) {
    viteArgs.push('--clearScreen', 'false');
  }
  viteArgs.push(...extraArgs);

  const webChild = spawn(process.execPath, viteArgs, {
    cwd: rootDir,
    env: { ...process.env, FORCE_COLOR: '1' },
    stdio: ['inherit', 'pipe', 'pipe'],
  });
  children.push(webChild);
  pipePrefixed(webChild.stdout, '[web]', process.stdout);
  pipePrefixed(webChild.stderr, '[web]', process.stderr);

  webChild.on('exit', (code) => shutdown(code ?? 0));
}

main().catch((err) => {
  console.error('[dev] Gagal menjalankan server:', err);
  process.exit(1);
});
