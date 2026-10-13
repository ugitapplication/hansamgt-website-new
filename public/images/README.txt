Copy the two live header brand files into this directory with these EXACT names:

    hansa_logo_new.png
    v4.gif

Sources (reference only, do not hotlink in production):

    hansamgtWebsite_old/assets/frontend/wp-content/uploads/2020/12/hansa_logo_new.png
    hansamgtWebsite_old/assets/frontend/wp-content/uploads/2020/12/v4.gif

The exact-replica header references them via asset('images/...') in:
- resources/views/partials/header.blade.php (logo 80px desktop / 70px mobile + v4.gif badge)
- resources/views/partials/seo.blade.php (og:image, favicon)

PowerShell (run from C:\laragon\www\hansamgt-website-new):

    Copy-Item hansamgtWebsite_old\assets\frontend\wp-content\uploads\2020\12\hansa_logo_new.png public\images\hansa_logo_new.png
    Copy-Item hansamgtWebsite_old\assets\frontend\wp-content\uploads\2020\12\v4.gif public\images\v4.gif
