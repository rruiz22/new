const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = {
  mode: 'development', // Cambiar a 'production' para producción
  entry: {
    app: './assets/scss/app.scss',
    notion: './assets/scss/theme/_notion.scss'
  },
  output: {
    path: path.resolve(__dirname, 'assets/css'),
    filename: '[name].bundle.js',
    clean: true,
  },
  module: {
    rules: [
      {
        test: /\.scss$/,
        use: [
          MiniCssExtractPlugin.loader,
          'css-loader',
          {
            loader: 'postcss-loader',
            options: {
              postcssOptions: {
                plugins: [
                  ['autoprefixer']
                ]
              }
            }
          },
          'sass-loader'
        ]
      },
      {
        test: /\.(png|svg|jpg|jpeg|gif)$/i,
        type: 'asset/resource',
        generator: {
          filename: 'images/[name][ext]'
        }
      },
      {
        test: /\.(woff|woff2|eot|ttf|otf)$/i,
        type: 'asset/resource',
        generator: {
          filename: 'fonts/[name][ext]'
        }
      }
    ]
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: '[name].css'
    })
  ],
  devtool: 'source-map',
  watch: false, // Cambiar a true para modo watch
  watchOptions: {
    ignored: /node_modules/,
    poll: 1000
  }
}; 