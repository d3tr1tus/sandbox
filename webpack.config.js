require("dotenv").config();
const webpack = require("webpack");
const path = require("path");

const BUILD_DIR = path.resolve(__dirname, "www/dist");
const APP_DIR = path.resolve(__dirname, "assets/js");

module.exports = {
    entry: [
        "webpack-dev-server/client?http://localhost:3030",
        "webpack/hot/only-dev-server",
        `${APP_DIR}/index.js`,
    ],
    devServer: {
        // contentBase: __dirname + '/www', // načtení fyzických souborů
        disableHostCheck: true,
        // https: true,
        host: "0.0.0.0",
        public: "127.0.0.1:3030",
        port: 3030,
        stats: {
            colors: true,
        },
    },
    plugins: [
        new webpack.HotModuleReplacementPlugin(),
        new webpack.EnvironmentPlugin(["ENV", "API_URL"]),
        // new ExtractTextPlugin('style.css')
    ],
    // entry: APP_DIR + '/index.js',
    output: {
        path: BUILD_DIR,
        publicPath: "/dist/",
        filename: "index.js",
    },
    module : {
        loaders : [
            {
                test : /\.jsx?/,
                include : APP_DIR,
                loader : "babel-loader",
            },
        ],
    },
};
