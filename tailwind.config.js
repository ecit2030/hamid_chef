module.exports = {
	darkMode: 'class',
	content: [
		'./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
		'./storage/framework/views/*.php',
		'./resources/**/*.blade.php',
		'./resources/**/*.js',
		'./resources/**/*.vue',
	],
	theme: {
		extend: {
			fontFamily: {
				sans: ['Almarai', 'Arial', 'sans-serif'],
				arabic: ['Almarai', 'Arial', 'sans-serif'],
				english: ['Ahlan', 'Arial', 'sans-serif'],
				cairo: ['Almarai', 'Arial', 'sans-serif'],
			},
			colors: {
				primary: {
					DEFAULT: '#083064',
					50: '#E6EBF2',
					100: '#CCD7E5',
					200: '#99AFCB',
					300: '#6687B1',
					400: '#335F97',
					500: '#083064',
					600: '#062650',
					700: '#051D3C',
					800: '#031328',
					900: '#020A14',
				},
				secondary: {
					DEFAULT: '#CBE4F8',
					50: '#FFFFFF',
					100: '#FFFFFF',
					200: '#FFFFFF',
					300: '#F5FAFD',
					400: '#E0EFF9',
					500: '#CBE4F8',
					600: '#A3D1F3',
					700: '#7BBEEE',
					800: '#53ABE9',
					900: '#2B98E4',
				},
				// Dark mode specific colors
				dark: {
					bg: '#0f172a',
					card: '#1e293b',
					border: '#334155',
					text: '#e2e8f0',
					muted: '#94a3b8',
				}
			},
			// Animation utilities for modern design
			animation: {
				'float': 'float 6s ease-in-out infinite',
				'float-slow': 'float 8s ease-in-out infinite',
				'float-fast': 'float 4s ease-in-out infinite',
				'pulse-slow': 'pulse 3s ease-in-out infinite',
				'bounce-slow': 'bounce 2s ease-in-out infinite',
				'gradient': 'gradient 8s ease infinite',
				'shimmer': 'shimmer 2s linear infinite',
			},
			keyframes: {
				float: {
					'0%, 100%': { transform: 'translateY(0)' },
					'50%': { transform: 'translateY(-20px)' },
				},
				gradient: {
					'0%, 100%': { backgroundPosition: '0% 50%' },
					'50%': { backgroundPosition: '100% 50%' },
				},
				shimmer: {
					'0%': { backgroundPosition: '-200% 0' },
					'100%': { backgroundPosition: '200% 0' },
				},
			},
			// Backdrop blur utilities
			backdropBlur: {
				xs: '2px',
			},
			// Box shadow for glassmorphism
			boxShadow: {
				'glass': '0 8px 32px 0 rgba(31, 38, 135, 0.15)',
				'glass-dark': '0 8px 32px 0 rgba(0, 0, 0, 0.3)',
				'glow': '0 0 20px rgba(203, 228, 248, 0.5)',
				'glow-primary': '0 0 20px rgba(8, 48, 100, 0.3)',
			},
		}
	},
	plugins: [
		require('tailwindcss-rtl'),
		require('@tailwindcss/forms'),
	],
}
