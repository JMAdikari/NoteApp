/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './public/index.html',
    './src/**/*.{vue,js,ts,jsx,tsx}',
  ],
  theme: {
    extend: {
      colors: {
        'dark-primary': '#1f2937',
        'dark-secondary': '#374151',
        'dark-accent': '#4b5563',
      },
    },
  },
  plugins: [],
}
