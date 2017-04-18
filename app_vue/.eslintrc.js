module.exports = {
  root: true,
  parser: 'babel-eslint',
  parserOptions: {
    sourceType: 'module'
  },
  // https://github.com/feross/standard/blob/master/RULES.md#javascript-standard-style
  extends: 'standard',
  // required to lint *.vue files
  plugins: [
    'html'
  ],
  // add your custom rules here
  'rules': {
    // allow paren-less arrow functions
    'arrow-parens': 0,
    // allow async-await
    'generator-star-spacing': 0,
    // allow debugger during development
    'no-debugger': process.env.NODE_ENV === 'production' ? 2 : 0,
    'no-tabs': 0,
    'indent': 0,
    'no-mixed-spaces-and-tabs': 0,
    'space-before-function-paren': 0,
    'key-spacing': 0,
    'semi': 0,
    'no-trailing-spaces': 0,
    'keyword-spacing': 0,
    'space-before-blocks': 0,
    'no-multiple-empty-lines': 0,
    'comma-spacing': 0,
    'comma-dangle': 0,
    'spaced-comment': 0,
    'no-unused-vars': 0,
      'brace-style ': 0,
      'no-multi-spaces': 0,
      'one-var': 0,
      'space-in-parens': 0,
      'no-extra-boolean-cast':0,
      'brace-style':0,
      'handle-callback-err':0,
      'padded-blocks':0,
      'camelcase':0,
      'no-duplicate-imports':0,
      'space-infix-ops':0,
      'quotes':0,
      'no-undef':0,
      'new-cap':0,
      'func-call-spacing':0,
      'no-unexpected-multiline':0

  },
    env: {
        browser: true,
        jquery: true
    }
}
