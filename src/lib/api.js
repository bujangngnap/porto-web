export const API_BASE = (import.meta.env.VITE_API_BASE_URL || '').replace(/\/$/, '');

export function resolveApiUrl(path) {
  return `${API_BASE}${path}`;
}

export function resolveMediaUrl(path) {
  if (!path) return '';
  if (path.startsWith('http://') || path.startsWith('https://') || path.startsWith('data:')) {
    return path;
  }
  if (path.startsWith('/storage/') && API_BASE) {
    return `${API_BASE}${path}`;
  }
  return path;
}
