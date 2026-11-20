import { dirname, join } from 'node:path'
import { fileURLToPath } from 'node:url'

// Content globs are anchored to this file so the build works from any CWD.
const themeRoot = dirname(fileURLToPath(import.meta.url))

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        join(themeRoot, 'layouts/**/*.blade.php'),
        join(themeRoot, 'partials/**/*.blade.php'),
        join(themeRoot, 'views/**/*.blade.php'),
        join(themeRoot, 'widgets/**/*.blade.php'),
        join(themeRoot, 'functions/**/*.php'),
        join(themeRoot, 'assets/js/**/*.js'),
    ],
    theme: {
        extend: {
            colors: {
                'torns-primary': '#227A96',
                'torns-primary-dark': '#176079',
                'torns-bg': '#F4F7F8',
                'torns-ink': '#0F2430',
                'shift-morning': '#F5A623',
                'shift-evening': '#F0685B',
                'shift-night': '#4A5BC7',
                'torns-deep': '#16384A',
                'torns-mist': '#DDEEF3',
                'torns-sky': '#7FD0E6',
            },
            fontFamily: {
                display: ['Poppins', 'Helvetica', 'Arial', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                sans: ['Instrument Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'],
            },
        },
    },
    plugins: [],
}
