const subsetFont = require("subset-font");
const glob = require("glob");
const path = require("path");
const fs = require("fs");
const ttf2woff2 = require("ttf2woff2");
const inputDir = path.resolve(__dirname, "../../static/fonts");
const outputDir = path.resolve(__dirname, "../../static/fonts");
const cssOutputFile = path.resolve(
  __dirname,
  "../sources/assets/scss/_optimized-fonts.scss"
);

// Characters you want to keep in the font
const latinGlyphs =
  "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789 !\"#$%&'()*+,-./:;<=>?@[\\]^_`{|}~ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýþÿĀāĂăĄąĆćĈĉĊċČčĎďĐđĒēĔĕĖėĘęĚěĜĝĞğĠġĢģĤĥĦħĨĩĪīĬĭĮįİıĲĳĴĵĶķĸĹĺĻļĽľĿŀŁłŃńŅņŇňŉŊŋŌōŎŏŐőŒœŔŕŖŗŘřŚśŜŝŞşŠšŢţŤťŦŧŨũŪūŬŭŮůŰűŲųŴŵŶŷŸŹźŻżŽžƒǺǻǼǽǾǿ";

const fontFaceTemplate = (fontName, fileName, fontFormat) => {
  return `@font-face {
  font-family: '${fontName}';
  src: url('../fonts/${fileName}.woff2') format('${fontFormat}');
  font-weight: normal;
  font-style: normal;
  font-display: swap;
}
`;
};

const extractFontName = (fontName) => {
  const regex =
    /(^.*?)(-Italic|-Bold|-Regular|-Light|-Medium|-Black|-ExtraBold|-SemiBold|-Thin)?(\.[a-zA-Z0-9]+)?$/;
  const match = fontName.match(regex);
  return match && match[1] ? match[1] : fontName;
};

glob(path.join(inputDir, "*.{ttf,woff2}"), async (err, files) => {
  if (err) {
    console.error(err);
    return;
  }

  let cssContent = "";

  for (const file of files) {
    try {
      const fontData = fs.readFileSync(file);
      const fontFormat = path.extname(file).substring(1); // Remove the leading dot
      const fileName = path.basename(file, path.extname(file));
      const fontName = extractFontName(fileName);
      const subsetName = "compressed";

      // Subset the font
      const subsettedFont = await subsetFont(fontData, latinGlyphs, {
        inputFormat: fontFormat,
        outputFormat: "ttf", // Set output format to TTF for the subsetFont function
        glyphs: latinGlyphs,
      });

      // Convert the subsetted TTF font to WOFF2
      const woff2Font = ttf2woff2(subsettedFont);

      fs.writeFileSync(
        path.join(outputDir, `${fileName}-${subsetName}.woff2`),
        woff2Font
      );
      console.log(
        `Optimized font file '${file}' and saved as '${fileName}-${subsetName}.woff2' in '${outputDir}'`
      );

      // Remove the original font file
      fs.unlinkSync(file);
      console.log(`Removed the original font file '${file}'`);

      cssContent += fontFaceTemplate(
        fontName,
        `${fileName}-${subsetName}`,
        "woff2" // Set the font format in the CSS to WOFF2
      );
    } catch (error) {
      console.error(`Error processing file '${file}':`, error);
    }
  }

  fs.writeFileSync(cssOutputFile, cssContent);
  console.log(`@font-face statements saved in '${cssOutputFile}'`);
});