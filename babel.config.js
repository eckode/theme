const isDev = process.env.npm_lifecycle_event.includes("fe:start");
const plugins = isDev ? ["react-refresh/babel"] : [];
module.exports = {
  presets: ["@babel/env", "@babel/preset-react", "@babel/preset-typescript"],
  plugins,
};
