var path = require('path')
var utils = require('./utils')
var config = require('../config')
var vueLoaderConfig = require('./vue-loader.conf')
var webpack = require('webpack')
function resolve (dir) {
  return path.join(__dirname, '..', dir)
}



const webpackConfig = {
  entry: {
    app: './src/main.js'
  },
    plugins: [
        new webpack.ProvidePlugin({
            jQuery: "jquery",
            $: "jquery",
            jquery: "jquery",
            "window.jquery": "jquery",
            "THREE":'three/build/three',
            "Stats":'three/examples/js/libs/stats.min'
        })
    ],
  output: {
    path: config.build.assetsRoot,
    filename: '[name].js',
    publicPath: process.env.NODE_ENV === 'production'
      ? config.build.assetsPublicPath
      : config.dev.assetsPublicPath
  },
  resolve: {
        extensions: ['.js', '.vue', '.json'],
        alias: {
            'vue$': 'vue/dist/vue.esm.js',
            '@': resolve('src'),
            'components': resolve('src/components'),
            'api': resolve('src/api'),
            'assets': resolve('src/assets'),

        }
    },
  module: {
    rules: [
      {
        test: /\.(js|vue)$/,
        loader: 'eslint-loader',
        enforce: 'pre',
        include: [resolve('src'), resolve('test')],
        exclude: [resolve('src/api/lib')],
        options: {
          formatter: require('eslint-friendly-formatter')
        }
      },
        {
            test: /\.js$/,
            loader: 'babel-loader',
            include: [resolve('src'), resolve('test')]
        },
      {
        test: /\.vue$/,
        loader: 'vue-loader',
        options: vueLoaderConfig
      },

      {
        test: /\.(png|jpe?g|gif|svg)(\?.*)?$/,
        loader: 'url-loader',
        options: {
          limit: 10000,
          name: utils.assetsPath('img/[name].[hash:7].[ext]')
        }
      },
      {
        test: /\.(woff2?|eot|ttf|otf)(\?.*)?$/,
        loader: 'url-loader',
        options: {
          limit: 10000,
          name: utils.assetsPath('fonts/[name].[hash:7].[ext]')
        }
      },
      {
        test: /\.(fnt?|vert|frag|otf)(\?.*)?$/,
        loader: 'url-loader',
        options: {
          limit: 10000,
          name: utils.assetsPath('obj/[name].[hash:7].[ext]')
        }
      }
    ]
  }
}


module.exports = webpackConfig
// const merge = require('webpack-merge')
// const vuxLoader = require('vux-loader')
// module.exports = vuxLoader.merge(webpackConfig, {
//     plugins: [
//         {
//             name: 'vux-ui'
//         },
//         {
//             name: 'duplicate-style'
//         }
//     ]
// })
