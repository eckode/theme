// development config
const fs = require("fs");
const defaultConfig = require("@wordpress/scripts/config/webpack.config");
const { devConfig } = require("./utils");

const config = devConfig().then(() => {
  defaultConfig.devServer.allowedHosts ??= [];
  defaultConfig.devServer.server ??= { options: {} };
  defaultConfig.devServer.server.options ??= {};

  return {
    ...defaultConfig,
    devtool: "cheap-module-source-map",
    devServer: {
      ...defaultConfig.devServer,
      hot: true,
      historyApiFallback: true,
      client: {
        ...defaultConfig.devServer.client,
        logging: "error",
        webSocketURL: "wss://localhost:8887/ws",
      },
      headers: {
        ...defaultConfig.devServer.headers,
        "Access-Control-Allow-Origin": "*",
      },
      allowedHosts: [
        ...defaultConfig.devServer.allowedHosts,
        "jaredrethman.com",
      ],
      server: {
        ...defaultConfig.devServer.server,
        type: "https",
        options: {
          ...defaultConfig.devServer.server.options,
          key: fs.readFileSync("ssl/localhost.key"),
          cert: fs.readFileSync("ssl/localhost.cert"),
        },
      },
    },
  };
});

module.exports = config;
