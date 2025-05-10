/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/**/*.{vue,js,ts,jsx,tsx}',
  ],
  theme: {
    extend: {},
  },
  plugins: [require('@tailwindcss/forms')],
};
