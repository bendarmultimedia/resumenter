# Resumenter
A simple script that allows you to create a resume based on data stored in .json files
You can add different versions of resumes on one template easily print them or export them to PDF. The library comes in handy for applying for various positions, where the priority of skills depends on the job position.

![thumbnail](https://raw.githubusercontent.com/bendarmultimedia/resumenter/refs/heads/master/public/img/thumbnail.png?token=GHSAT0AAAAAADGB5LFXCUVMUYRDGFD72RPE2ECMD7Q)

---
## 🛠️ Requirements
- PHP 8.2+
- Composer
- Node.js (tested on v22)
- npm or yarn
---
## 🚀 Installation
First install dependencies by composer running command:
```text
composer install
```
Then Go to ``./js/`` folder and run:

    npm install

Configure rename ``./.env.example`` to ``./.env`` or create it with proper data
### .env options:
#### Public url of the app - it's for assets and export for pdf:
``APP_URL=http://www.test/resumenter/``

#### Names of jobs for export to PDF by export-pdf.json
``JOB_TYPES_TO_EXPORT=wordpress_developer``

#### Path to JSON files containing data for the resume
``DATA_PATH=/data/job_positions``

#### Prefix of routes if the app is in sub-path of domain
``ROUTE_PREFIX=/resumenter``

After setting the options, go to ``./js/`` and call ``npm run build``

Now you can see online version of resumes. The specific job can be reached by ``appURL/job_name``,
where ***job_name*** is the name of file in ``data/job.json``
---
## 📄Exporting to PDF
In ``./js/`` run ``node export-pdf.js``. Jobs defined in .env file will be exported in ``./public/resume/``

---
## 🧱Dependencies and Technology selection
### Used dependencies:
The library uses PHP for reading data and [Twig](https://github.com/twigphp/Twig) as a templating system.

You can modify resume template thanks [tailwindcss](https://github.com/tailwindlabs/tailwindcss) which is compiled by [Vite](https://github.com/vitejs/vite).

There is [Puppeteer](https://github.com/puppeteer/puppeteer) running by [node](https://nodejs.org/en) used to quick export templates to PDF.

The class ***Router*** rewrites parameters for friendly URLs. The rules for http server are in ***.htaccess***.

### Technology selection and alternatives:
🐘 The **PHP** was being used as a backend language because I have more experience in this technology. In the other way **Vue.js** or any different js library could be alternative.
In this project I'd like to show some different technologies mixed together.

For PDF files, a different solution could be used to stick to one backend technology. You could use [mPDF](https://github.com/mpdf/mpdf) or [wkhtmltopdf](https://wkhtmltopdf.org/).

Experience with these technologies has shown that there are sometimes problems with correct CSS rendering.
For a larger project, I would probably choose one of these.
---
## 📈 What could be better?
This is simple example of using multiple web techniques like **PHP**, **JavaScript**, **Node**, **Apache**, ***SCSS***, **TailwindCSS** and **Twig**.
The templates could be inherited and more optimized in accordance with DRY methodology, but maybe there will be an update in the future.😀
The router can work without a defined prefix - support can be added in the future.⏰

---

💖 Thanks for your attention.

**Patryk Cieślak**