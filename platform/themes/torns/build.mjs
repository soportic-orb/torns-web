// Build pipeline for the torns theme.
//
// Compiles theme assets locally so the server never needs Node:
//   assets/js/main.js   --(Vite, IIFE, minified)-->  public/js/main.js
//   assets/css/style.css --(Tailwind + PostCSS)-->   public/css/style.css
//   assets/fonts, assets/images --(copy)-->          public/fonts, public/images
//
// Everything is then mirrored to <repo>/public/themes/torns (what the web
// server actually serves), matching `php artisan cms:theme:assets:publish`.
//
// Usage (from the repository root):
//   npm run build:torns

import { build as viteBuild } from 'vite'
import tailwindcss from 'tailwindcss'
import autoprefixer from 'autoprefixer'
import cssnano from 'cssnano'
import postcss from 'postcss'
import postcssImport from 'postcss-import'
import { cp, mkdir, readFile, writeFile } from 'node:fs/promises'
import { dirname, resolve } from 'node:path'
import { fileURLToPath } from 'node:url'

const THEME_ROOT = dirname(fileURLToPath(import.meta.url))
const REPO_ROOT = resolve(THEME_ROOT, '../../..')
const THEME_PUBLIC = resolve(THEME_ROOT, 'public')
const SERVED_PUBLIC = resolve(REPO_ROOT, 'public/themes/torns')

async function buildJs() {
    await viteBuild({
        configFile: false,
        logLevel: 'warn',
        publicDir: false,
        build: {
            emptyOutDir: false,
            outDir: resolve(THEME_PUBLIC, 'js'),
            minify: 'esbuild',
            sourcemap: false,
            target: 'es2018',
            lib: {
                entry: resolve(THEME_ROOT, 'assets/js/main.js'),
                formats: ['iife'],
                name: '__torns_theme',
                fileName: () => 'main.js',
                cssFileName: 'main',
            },
        },
    })
}

async function buildCss() {
    const from = resolve(THEME_ROOT, 'assets/css/style.css')
    const to = resolve(THEME_PUBLIC, 'css/style.css')
    const source = await readFile(from, 'utf8')

    const result = await postcss([
        postcssImport(),
        tailwindcss(resolve(THEME_ROOT, 'tailwind.config.js')),
        autoprefixer,
        cssnano({ preset: 'default' }),
    ]).process(source, { from, to })

    await mkdir(dirname(to), { recursive: true })
    await writeFile(to, result.css)
}

async function copyStatic() {
    await cp(resolve(THEME_ROOT, 'assets/fonts'), resolve(THEME_PUBLIC, 'fonts'), { recursive: true })
    await cp(resolve(THEME_ROOT, 'assets/images'), resolve(THEME_PUBLIC, 'images'), { recursive: true })
}

async function publish() {
    await cp(THEME_PUBLIC, SERVED_PUBLIC, { recursive: true })
}

const startedAt = Date.now()
await Promise.all([buildJs(), buildCss(), copyStatic()])
await publish()
console.log(`[torns] theme assets built and published in ${Date.now() - startedAt}ms`)
