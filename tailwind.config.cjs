import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import tailwindcss from 'tailwindcss';

/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'class',
  content: [
    "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
    "./storage/framework/views/*.php",
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./src/**/*.{js,ts,jsx,tsx,mdx}",
     "./node_modules/flowbite/**/*.js",
     "./src/**/*.{html,js}"
  ],
  theme: {
    extend: {},
  },
  plugins: [
      require('flowbite/plugin'),
      require('@tailwindcss/forms'),
    ],
}
