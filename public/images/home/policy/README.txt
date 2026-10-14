Homepage images live under public/images/home/ with ORIGINAL legacy filenames.

Copy them from the legacy backup (run from C:\laragon\www\hansamgt-website-new):

  $old = 'hansamgtWebsite_old\assets\frontend\wp-content\uploads'
  $new = 'public\images\home'
  New-Item -ItemType Directory -Force $new\slider, $new\policy, $new\glance, `
    $new\bg, $new\projects, $new\news, $new\clients, $new\concerns | Out-Null

  # Hero slider (7)
  Copy-Item $old\2020\12\hotel-housekeeping-bed-making-hansa-management.jpg $new\slider\
  Copy-Item $old\2020\12\hansa-management-team-group-photo.jpg $new\slider\
  Copy-Item $old\2020\12\security-guards-uniform-inspection-hansa-management.jpg $new\slider\
  Copy-Item $old\2020\12\cleaning-building-hansa-management.jpg $new\slider\
  Copy-Item $old\2020\12\floor-cleaning-machine-terrace-hansa-management.jpg $new\slider\
  Copy-Item $old\2020\12\hansa-employee-training.jpg $new\slider\
  Copy-Item $old\2020\12\front-office-reception-desk-hansa-management.jpg $new\slider\

  # Info boxes (3)
  Copy-Item $old\project\p-1.jpg, $old\project\safety.jpg, $old\project\never_sleep.jpg $new\policy\

  # Services at-a-glance (8)
  Copy-Item $old\project\p-13.jpg, $old\project\p-5.jpg, $old\project\p-6.jpg, `
    $old\project\p-29.jpg, $old\project\p-10.jpg, $old\project\p-25.jpg, `
    $old\project\p-2.jpg, $old\project\p-16.jpg $new\glance\

  # Band backgrounds (2)
  Copy-Item $old\2020\12\policy\1.jpg $new\bg\counter.jpg
  Copy-Item $old\2019\09\Service-Excellence-copyb643.jpg $new\bg\why.jpg

  # Service projects (4)
  Copy-Item $old\2020\12\project\150304_DCC_B.RGB_color.jpg, $old\2020\12\project\BORAK_mehnur.jpg, `
    $old\2020\12\project\park_valy.jpg, $old\2020\12\project\Heights.jpg $new\projects\

  # Latest news (2)
  Copy-Item $old\news\news3.jpg, $old\news\news2.jpg $new\news\

  # Clients (18) and concerns (13)
  Copy-Item $old\clients\* $new\clients\
  Copy-Item $old\concern\* $new\concerns\

Blade references (do not hotlink the legacy backup in production):
  resources/views/pages/home/sections/*.blade.php via asset('images/home/...')
