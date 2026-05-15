module.exports = {
    content: [
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                // Deep Navy surfaces
                'navy': {
                    '900': '#060B18',
                    '800': '#0B1224',
                    '700': '#0F1730',
                    '600': '#141E3C',
                    '500': '#192348',
                    '400': '#1E2A52',
                    '300': '#253264',
                    '200': '#2E3D76',
                    '100': '#3A4D8A',
                },
                // Golden accent palette
                'gold': {
                    'DEFAULT': '#F5A623',
                    'light': '#FFBE45',
                    'dark': '#D4900A',
                    'muted': '#C8922A',
                },
                // Chrome text palette
                'chrome': {
                    'white': '#FFFFFF',
                    'ice': '#E2E8F0',
                    'silver': '#94A3B8',
                    'matte': '#64748B',
                    'graphite': '#475569',
                },
            },
            backgroundColor: {
                'surface': 'rgba(245, 166, 35, 0.03)',
                'surface-hover': 'rgba(245, 166, 35, 0.06)',
                'surface-active': 'rgba(245, 166, 35, 0.09)',
            },
            borderColor: {
                'edge': 'rgba(245, 166, 35, 0.06)',
                'edge-hover': 'rgba(245, 166, 35, 0.12)',
                'edge-active': 'rgba(245, 166, 35, 0.22)',
            },
            boxShadow: {
                'ambient': '0 8px 32px rgba(0, 0, 0, 0.4)',
                'lift': '0 16px 48px rgba(0, 0, 0, 0.5), 0 2px 8px rgba(0, 0, 0, 0.3)',
                'glow-gold': '0 0 20px rgba(245, 166, 35, 0.2), inset 0 0 20px rgba(245, 166, 35, 0.05)',
                'glow-white': '0 0 24px rgba(255, 255, 255, 0.06)',
                'inner-light': 'inset 0 1px 0 rgba(245, 166, 35, 0.04)',
                'premium': '0 20px 60px rgba(0, 0, 0, 0.6), 0 8px 24px rgba(0, 0, 0, 0.4)',
            },
            backdropBlur: {
                'xs': '2px',
                'sm': '4px',
                'md': '8px',
                'lg': '16px',
                'xl': '24px',
                '2xl': '40px',
            },
            fontFamily: {
                'sans': ['Inter', 'SF Pro Display', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'sans-serif'],
            },
            animation: {
                'float': 'float 8s ease-in-out infinite',
                'breathe': 'breathe 4s ease-in-out infinite',
                'fade-in': 'fade-in 0.6s ease-out',
                'slide-up': 'slide-up 0.6s ease-out',
                'slide-in': 'slide-in 0.5s ease-out',
                'glow-pulse': 'glow-pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                'shimmer': 'shimmer 2s infinite',
                'float-subtle': 'float-subtle 6s ease-in-out infinite',
            },
            keyframes: {
                float: {
                    '0%, 100%': { transform: 'translateY(0px)' },
                    '50%': { transform: 'translateY(-12px)' },
                },
                'float-subtle': {
                    '0%, 100%': { transform: 'translateY(0px)' },
                    '50%': { transform: 'translateY(-4px)' },
                },
                breathe: {
                    '0%, 100%': { opacity: '0.4' },
                    '50%': { opacity: '0.8' },
                },
                'glow-pulse': {
                    '0%, 100%': { opacity: '0.5' },
                    '50%': { opacity: '1' },
                },
                'shimmer': {
                    '0%': { backgroundPosition: '-1000px 0' },
                    '100%': { backgroundPosition: '1000px 0' },
                },
                'fade-in': {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                'slide-up': {
                    '0%': { opacity: '0', transform: 'translateY(16px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                'slide-in': {
                    '0%': { opacity: '0', transform: 'translateX(-16px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
            },
        },
    },
    plugins: [],
}
