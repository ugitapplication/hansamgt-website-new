Copy the brand font into this directory with this EXACT name:

    AvenirLTStd-Roman.woff

Source (same file the live site uses):

    hansamgtWebsite_old/assets/frontend/wp-includes/fonts/AvenirLTStd-Roman.woff

Referenced by resources/css/app.css (@font-face, absolute path /fonts/...).

PowerShell (run from C:\laragon\www\hansamgt-website-new):

    New-Item -ItemType Directory -Force -Path public\fonts | Out-Null
    Copy-Item hansamgtWebsite_old\assets\frontend\wp-includes\fonts\AvenirLTStd-Roman.woff public\fonts\AvenirLTStd-Roman.woff
