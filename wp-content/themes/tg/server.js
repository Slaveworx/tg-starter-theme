const browserSync = require("browser-sync");
const { createProxyMiddleware } = require("http-proxy-middleware");
const cheerio = require("cheerio");

const apiProxy = createProxyMiddleware("/", {
  target: "http://tg.local",
  changeOrigin: true,
  pathRewrite: {
    "^/": "/",
  },
});

browserSync({
  server: {
    baseDir: "./",
    middleware: [apiProxy],
  },
  files: ["static/css/*.css", "static/js/*.js", "**/*.php"],
  notify: false,
  open: false,
  port: 3000,
});
