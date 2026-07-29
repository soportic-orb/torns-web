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
                'torns-primary': '#0E7E8A',
                'torns-primary-dark': '#0A5A63',
                'torns-bg': '#FAFBFB',
                'torns-ink': '#16232A',
                'shift-morning': '#F5A623',
                'shift-evening': '#F0685B',
                'shift-night': '#4A5BC7',
            },
            fontFamily: {
                display: ['Bricolage Grotesque', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                sans: ['Instrument Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'],
            },
        },
    },
    plugins: [],
}
