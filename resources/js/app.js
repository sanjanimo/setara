import './bootstrap';
import './map';
import './locationpicker';
import './home-maps';
import Alpine from 'alpinejs';

import AOS from 'aos';
import 'aos/dist/aos.css';
import Lenis from '@studio-freight/lenis';

import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
window.L = L; // Bridge untuk file blade

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    // Lenis hanya mengintersep wheel di perangkat yang mendukung scroll halus.
    const supportsSmoothScroll = window.matchMedia('(prefers-reduced-motion: no-preference)').matches;
    const lenis = new Lenis({
        duration: 1.2,
        easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
        orientation: 'vertical',
        gestureOrientation: 'vertical',
        smoothWheel: supportsSmoothScroll,
        smoothTouch: false,
    });

    if (!supportsSmoothScroll) {
        lenis.stop();
        lenis.destroy();
    }

    function raf(time) {
        if (supportsSmoothScroll) lenis.raf(time);
        requestAnimationFrame(raf);
    }
    requestAnimationFrame(raf);

    // 2. Jalankan AOS
    AOS.init({
        once: true,
        offset: 50,
        duration: 800,
        easing: 'ease-out-cubic',
        disable: 'mobile'
    });

    // 3. Sinkronisasi AOS setelah render selesai
    window.addEventListener('load', () => {
        setTimeout(() => AOS.refresh(), 500);
    });
});
