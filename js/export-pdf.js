require('dotenv').config({ path: '../.env' });
const puppeteer = require('puppeteer');
const fs = require('fs');
const path = require('path');

const jobTypes =  process.env.JOB_TYPES_TO_EXPORT?.split(',') || ['web_developer'];
const baseUrl = process.env.APP_URL;

(async () => {
  const browser = await puppeteer.launch();
  for (const job of jobTypes) {
    const page = await browser.newPage();
    const url = `${baseUrl}?job=${job}`;
    await page.goto(url, { waitUntil: 'networkidle0' });
    const outputPath = path.resolve(__dirname, `../public/resume/${job}.pdf`);
    await page.pdf({
      path: outputPath,
      format: 'A4',
      printBackground: true,
      margin: {
        top: '0mm',
        bottom: '0mm',
        left: '0mm',
        right: '0mm'
      }
    });
    console.log(`✔️ Exported: ${outputPath}`);
  }
  await browser.close();
})();
