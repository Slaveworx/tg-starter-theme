const browserSync = require("browser-sync");
const { createProxyMiddleware } = require("http-proxy-middleware");
const cheerio = require("cheerio");

const apiProxy = createProxyMiddleware("/", {
  target: "http://tg.local",
  changeOrigin: true,
  pathRewrite: {
    "^/": "/",
  },
  onProxyRes: function (proxyRes, req, res) {
    if (
      proxyRes.headers["content-type"] &&
      proxyRes.headers["content-type"].match(/text\/html/)
    ) {
      const chunks = [];
      proxyRes.on("data", function (chunk) {
        chunks.push(chunk);
      });
      proxyRes.on("end", function () {
        let body = Buffer.concat(chunks);
        const $ = cheerio.load(body.toString());
        $("a").each(function () {
          const href = $(this).attr("href");
          if (href && href.match(/tg\.local/)) {
            $(this).attr("href", href.replace(/tg\.local/g, "localhost:3000"));
          }
        });
        const modifiedBody = $.html();
        const modifiedLength = Buffer.byteLength(modifiedBody, "utf-8");
        proxyRes.headers["content-length"] = modifiedLength;
        res.setHeader("content-length", modifiedLength);
        res.setHeader("content-type", "text/html");
        res.write(modifiedBody);
        res.end();
      });
    }
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
