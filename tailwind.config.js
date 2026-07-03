/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                // 60% - Sage Green (Primary)
                primary: {
                    DEFAULT: '#9FCBAD',
                    50:  '#f0f7f2',
                    100: '#dceee2',
                    200: '#bcddc8',
                    300: '#9FCBAD',
                    400: '#76b38e',
                    500: '#549770',
                    600: '#3f7a58',
                    700: '#336248',
                    800: '#2a4e3a',
                    900: '#234030',
                },
                // 30% - Light Cream (Secondary)
                secondary: {
                    DEFAULT: '#F1F7D4',
                    50:  '#fafdf0',
                    100: '#F1F7D4',
                    200: '#e3f0a8',
                    300: '#d0e57b',
                    400: '#bcd651',
                    500: '#a3c232',
                    600: '#7d9824',
                    700: '#60751b',
                    800: '#4c5d16',
                    900: '#3d4c13',
                },
                // 10% - Blue Accent
                accent: {
                    DEFAULT: '#1591DC',
                    50:  '#e8f4fd',
                    100: '#c4e2f8',
                    200: '#90c9f2',
                    300: '#56abea',
                    400: '#1591DC',
                    500: '#0e7bc4',
                    600: '#0b63a0',
                    700: '#094e7f',
                    800: '#073c62',
                    900: '#052c48',
                },
            },
            fontFamily: {
                sans: ['Inter', 'system-ui', 'sans-serif'],
            },
            boxShadow: {
                'card': '0 2px 8px 0 rgba(159,203,173,0.18), 0 1px 3px 0 rgba(0,0,0,0.07)',
                'card-hover': '0 8px 24px 0 rgba(159,203,173,0.30), 0 2px 8px 0 rgba(0,0,0,0.08)',
                'sidebar': '4px 0 20px 0 rgba(0,0,0,0.08)',
            },
            animation: {
                'fade-in': 'fadeIn 0.3s ease-out',
                'slide-in': 'slideIn 0.3s ease-out',
                'slide-up': 'slideUp 0.4s ease-out',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                slideIn: {
                    '0%': { transform: 'translateX(-100%)' },
                    '100%': { transform: 'translateX(0)' },
                },
                slideUp: {
                    '0%': { transform: 'translateY(16px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
}
