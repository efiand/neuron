import svelte from 'rollup-plugin-svelte';
import resolve from '@rollup/plugin-node-resolve';
import commonjs from '@rollup/plugin-commonjs';
import browsersync from 'rollup-plugin-browsersync';
import { terser } from 'rollup-plugin-terser';
import { globalStyle, less, postcss } from 'svelte-preprocess';
import { string } from 'rollup-plugin-string';
import del from 'rollup-plugin-delete';

const production = !process.env.ROLLUP_WATCH;

export default {
	input: `source/js/index.js`,
	output: {
		sourcemap: !production,
		format: `iife`,
		name: `app`,
		file: `public/js/script.min.js`
	},
	plugins: [
		svelte({
      dev: !production,
      preprocess: [
				less(),
				globalStyle(), // важно выполнять после less
        postcss({
          plugins: [
						require(`mqpacker`)(),
						require(`autoprefixer`)(),
						require(`cssnano`)()
          ]
        })
      ],
			css: (css) => css.write(`public/css/style.min.css`, !production),
			onwarn: (warning, handler) => {
				if (warning.code === `a11y-no-onchange`) return;
        handler(warning);
      }
    }),
		resolve({
			browser: true,
			dedupe: (importee) => importee === `svelte` || importee.startsWith(`svelte/`)
    }),
    string({
      include: 'source/sprite/**/*.svg'
    }),
		commonjs(),
		!production && browsersync({
			cors: true,
			notify: false,
			open: false,
			port: 5000,
			proxy: `https://neuron`,
			ui: false
		}),
    production && terser(),
    production && del({
      targets: [`**/*.map`, `!node_modules/**/*.map`]
    })
	],
	watch: {
		clearScreen: true
	}
};
