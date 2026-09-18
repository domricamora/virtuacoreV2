/**
 * Vite entry. The ported bundle is deliberately dependency-free at the top level:
 * GSAP and the WebGL hero are dynamically imported only when a page contains the
 * section that needs them AND the device and motion preference allow it, so Vite
 * splits each into its own chunk.
 */
import './main.js';
