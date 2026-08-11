export default {
  plugins: {
    '@tailwindcss/postcss': {},
    'autoprefixer': {
      // Tambahkan browser yang lebih lama
      overrideBrowserslist: ['> 1%', 'last 3 versions', 'Safari >= 12', 'iOS >= 12', 'not dead']
    },
    'postcss-preset-env': {
      features: {
        'cascade-layers': false,
        'nesting-rules': false,
      },
      // Target browser yang lebih luas
      browsers: ['> 1%', 'last 3 versions', 'Safari >= 12', 'iOS >= 12']
    }
  }
}
