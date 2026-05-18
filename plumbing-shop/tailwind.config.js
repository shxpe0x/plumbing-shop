import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        // Брейкпоинты адаптивности (Req 25.1: корректное отображение на 320–1920 px).
        // xs   — 320 px  (минимальная поддерживаемая ширина мобильных устройств)
        // sm   — 640 px  (стандарт Tailwind, малые мобильные/планшеты в портрете)
        // md   — 768 px  (Req 25.3: на ширине < md показываем «гамбургер»-меню)
        // lg   — 1024 px (планшеты/небольшие десктопы)
        // xl   — 1280 px (десктопы)
        // 2xl  — 1536 px (большие десктопы; контент укладывается до 1920 px без горизонтальной прокрутки)
        screens: {
            xs: '320px',
            sm: '640px',
            md: '768px',
            lg: '1024px',
            xl: '1280px',
            '2xl': '1536px',
        },
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            minHeight: {
                // Req 25.6: минимальный размер touch-цели 44×44 px на мобильных
                touch: '44px',
            },
            minWidth: {
                touch: '44px',
            },
        },
    },

    plugins: [forms],
};
