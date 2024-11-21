const path = require("path");

module.exports = {
  entry: "./src/index.js", // Archivo de entrada principal
  output: {
    path: path.resolve(__dirname, "public/js"), // Carpeta de salida
    filename: "bundle.js", // Nombre del archivo de salida
    publicPath: "/js/", // Ruta pública desde donde se servirán los archivos
  },
  module: {
    rules: [
      {
        test: /\.js$/, // Procesar archivos .js
        exclude: /node_modules/, // Excluir la carpeta node_modules
        use: {
          loader: "babel-loader", // Usa Babel para transpilar
          options: {
            presets: ["@babel/preset-env"], // Preset para JS moderno
          },
        },
      },
      {
        test: /\.css$/, // Procesar archivos CSS
        use: [
          "style-loader", // Inserta estilos en el DOM
          "css-loader", // Interpreta @import y url()
          {
            loader: "postcss-loader", // Procesa Tailwind CSS y otros plugins PostCSS
            options: {
              postcssOptions: {
                plugins: [
                  require("tailwindcss"), // Tailwind CSS
                  require("autoprefixer"), // Agrega prefijos automáticos para compatibilidad
                ],
              },
            },
          },
        ],
      },
      {
        test: /\.(png|jpg|gif|svg)$/, // Procesar imágenes
        type: "asset/resource", // Webpack 5 para manejar archivos estáticos
      },
    ],
  },
  devServer: {
    static: {
      directory: path.join(__dirname, "public"), // Carpeta pública
    },
    compress: true,
    port: 9000, // Puerto del servidor
    open: true, // Abre automáticamente el navegador
    historyApiFallback: {
      rewrites: [
        { from: /^\/$/, to: "/index.html" },
        { from: /^\/dashboard$/, to: "/dashboard.html" },
        { from: /^\/register$/, to: "/register.html" },
        { from: /^\/binnacle$/, to: "/binnacle.html" },
      ],
    },
  },

  mode: "development",
};
