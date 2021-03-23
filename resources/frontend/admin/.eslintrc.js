module.exports = {
  root: true,
  env: {
    node: true,
  },
  'extends': 'vuetify',
  rules: {
    'no-console': 'off',
    'no-debugger': process.env.NODE_ENV === 'production' ? 'error' : 'off',
    "no-unused-vars": 'off',
    "vue/max-attributes-per-line": "off",
    "quotes": [2, "single", { "avoidEscape": true }]
  },
  parserOptions: {
    parser: 'babel-eslint',
  },
}
