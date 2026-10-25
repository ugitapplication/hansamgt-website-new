# HANSA Website: Old vs New — A Plain-English Comparison, Admin Guide and Improvement Recommendations

> **Prepared for:** Management, Marketing, HR, Admin and non-technical teams at HANSA Management Ltd.
>
> **Purpose:** A single document explaining, in plain language, what has changed between the old site (`https://hansamgt.com`) and the new site, what can be managed from the new admin panel, and what still needs improvement.
>
> **Verification date:** 25 October 2026
>
> **Basis:** Verified by directly inspecting the old live site's HTML and the running build of the new site, including its public pages and the `/admin` panel. Counts seen on screen (such as slides, posts and enquiries) may change over time.

---

## Table of Contents

1. [30-Second Summary](#1-30-second-summary)
2. [Accessing the New Site and Admin Panel](#2-accessing-the-new-site-and-admin-panel)
3. [Visible Changes](#3-visible-changes)
4. [The Three New Pages](#4-the-three-new-pages)
5. [Google Analytics](#5-google-analytics)
6. [SEO Improvements](#6-seo-improvements)
7. [Admin Panel — Complete Guide](#7-admin-panel--complete-guide)
8. [Migrating from the Old Site to the New One](#8-migrating-from-the-old-site-to-the-new-one)
9. [Verification Checklist](#9-verification-checklist)
10. [Improvement Recommendations](#10-improvement-recommendations)
11. [Risks and Operational Notes](#11-risks-and-operational-notes)
12. [Easily Overlooked Items](#12-easily-overlooked-items)
13. [Technical Appendix](#13-technical-appendix)

---

## 1. 30-Second Summary

The old site was essentially a polished digital brochure — images, text and a phone number. The new site keeps the same brand identity and design language, and adds **its own content management, lead capture, a careers page, a blog, a privacy policy, analytics and significantly improved SEO**.

| Area | Old site | New site | Why it matters |
|---|---|---|---|
| **Slider** | Revolution Slider, 3 fixed slides, 3 seconds | Purpose-built lightweight slider, currently 6 active slides, 5 seconds | Images can be changed from admin; faster on mobile |
| **CTA buttons** | Only a phone-number band (not clickable) | **“Request a Quote”** and **“Call 01708800870”** in the hero | Visitors can enquire or call in one click |
| **Floating WhatsApp** | None | Yes; ON/OFF from admin | The quickest way to reach you in Bangladesh |
| **Floating quick links** | Yes (7 links) | Yes; now on **every public page** | Jump to key homepage sections from any page |
| **Careers** | In the menu, but the link was `#` (it did not work) | A complete page | A credible destination for job seekers |
| **Insights (blog)** | None | Yes; currently 4 articles, plus an RSS feed | Brings new visitors through content on Google |
| **Privacy Policy** | None | A 13-section policy + cookie settings | Meets legal and trust requirements |
| **Google Analytics** | None | GA4 (`G-NJDKQTDFB5`), enabled after consent | See who visits, from where, and which pages they view |
| **SEO** | Basic title/description/OG | Canonical, Twitter card, structured data, sitemap, robots, breadcrumb, 301 redirect | The foundation for good visibility on Google |
| **Admin panel** | WordPress-based, limited control | Purpose-built `/admin`; 10 content sections + settings | The marketing team can do much more without a developer |
| **Forms** | Contact Form 7 (AJAX) | Purpose-built form; saved to the database + admin email + thank-you email to the visitor | No enquiry is lost |
| **Mobile** | Responsive old theme | New responsive layout | Most visitors now arrive on mobile |

**In one line:** The old site was a site “to look at”; the new site is a site “to look at + contact + run content + measure”.

---

## 2. Accessing the New Site and Admin Panel

| Item | Address / details |
|---|---|
| **Old site** | `https://hansamgt.com` |
| **New site (demo)** | `http://127.0.0.1:8000` — running on the developer's computer. Once live, the same site will sit at `https://hansamgt.com`. If the demo does not run on your own computer, ask the developer for the staging/live URL. |
| **Admin panel** | `http://127.0.0.1:8000/admin` (once live: `https://hansamgt.com/admin`) |
| **Admin email** | `admin@gmail.com` |
| **Admin password** | `111111` |
| **Log out** | **Sign out** in the top-right corner |

### ⚠️ Security warning

This password is for **demo/verification only**. Before going live, you must:

1. Change it to a strong password.
2. Enable **2FA** (two-step login) if possible.
3. Block unnecessary public access to `/admin`.
4. Decide who will be an admin, and document their responsibilities.

> The admin panel currently has **no screens for user management, roles (Admin/Editor) or password reset**. These must be done by the developer from the command line. See §7.8 for details.

---

## 3. Visible Changes

### 3.1 Homepage slider

| Aspect | Old | New |
|---|---|---|
| **Technology** | Revolution Slider (WordPress plugin) | Purpose-built lightweight slider (no heavy plugin) |
| **Number of slides** | 3, fixed in code | Currently 6 active; can be added, removed or hidden from admin |
| **Change interval** | 3 seconds | 5 seconds |
| **Controls** | Autoplay | Autoplay + previous/next arrows + dots + progress bar |
| **Text** | “HANSA Management Ltd.” + “Hospitality and Facility Management Services” | The same text, with cleaner typography |
| **Buttons** | None | **Request a Quote** and **Call** |
| **Mobile** | Old theme's height | Height reduces to fit the screen; images load faster |
| **Admin** | From the WordPress media library | **Homepage Management → Hero Slider** |

**User experience:** The old slider was much heavier (jQuery + Revolution Slider). The new one loads faster and takes up less space on mobile.

### 3.2 CTA (Call-to-Action) buttons

On the old site, the band below the hero reading “a concern of Unique Group” contained only a **phone number** — it was not a clickable button.

The new site has two buttons in the hero:

| Button | What it does | Where it goes |
|---|---|---|
| **Request a Quote** | Takes the visitor to the homepage enquiry form | `#emr_contact` (the “Let’s Talk” form near the bottom of the page) |
| **Call 01708800870** | Opens the phone dialer | `tel:+8801708800870` |

**Why this is better:** Visitors no longer have to copy a phone number; one click either opens the form or starts a call.

> The CTA text and destination are currently set in code. The CTA text cannot be changed from admin — this is on the future improvements list (§10).

### 3.3 Floating WhatsApp button

The old site had no WhatsApp button.

The new site has a green WhatsApp button at the bottom right of **every public page**:

- Clicking it opens `https://wa.me/8801708800870`.
- The number comes from **Site Settings → Contact → WhatsApp number**.
- The **Show the floating WhatsApp button** toggle turns it ON/OFF at any time.
- If the number is empty, invalid or only partially entered, the button does not appear automatically — a broken link is never published.
- The button is just a normal link; no data goes to WhatsApp unless the visitor clicks it.

**How to turn it off:** `Admin → Site Settings → Contact → Show the floating WhatsApp button` → turn it off → **Save settings**.

### 3.4 Floating quick-link rail

The old site had a floating menu on the right side (7 links). In the first version of the new site it appeared only on the homepage; **it now appears on every public page**.

The 7 links:

1. Our Services
2. HANSA In Numbers
3. Services at-a-glance
4. Why to Choose HANSA
5. Service Projects
6. News
7. Contact Us

- On the homepage, clicking a link scrolls smoothly to that section.
- On any other page, clicking a link goes to the homepage and then to the relevant section.
- The rail is hidden on mobile screens of 480 pixels or narrower (just as on the old site).
- The rail also appears on the 404 (page not found) screen.

### 3.5 Header, menu and footer

| Aspect | Old | New |
|---|---|---|
| **Menu** | Home, About, Services, Projects, Gallery, Careers (`#`), Contact | The same + **Careers** (working) + **Insights** |
| **Logo** | Fixed image | Can be changed from Site Settings |
| **Footer** | 16 service links, social icons | 4 key services + company links + Contact + Privacy + Cookie settings |
| **Copyright** | “©2026” fixed | Automatically uses the current year |
| **Social** | Some links broken | Only profiles with a URL are shown |

> **A trade-off:** The old footer had 16 service links; the new footer has 4 key services. More internal links are better for SEO, so the footer could be expanded in future.

### 3.6 Contact form

| Aspect | Old | New |
|---|---|---|
| **Technology** | Contact Form 7 + AJAX | Purpose-built Laravel form |
| **Fields** | Name, Email, Phone, Message | The same 4 fields |
| **Data storage** | Email only | **Saved to the database first**, then emailed to admin |
| **Email to visitor** | None | Thank-you/confirmation email |
| **Spam control** | reCAPTCHA (hidden) | Rate limit of 10 requests per minute (throttle) |
| **Admin view** | WordPress entries | **Operations → Contact Submissions** |

**Important:** Even if the mail server is misconfigured, the enquiry remains in the database. In other words, a lead is not lost just because an email failed.

### 3.7 Mobile experience

- The header collapses into a separate menu button on mobile.
- The slider's height reduces to fit the screen.
- The quick-link rail hides below 480px.
- The WhatsApp button remains on mobile.
- Tables/cards stack into a single column.
- The `prefers-reduced-motion` setting means animations are reduced for visitors who have turned them off.

### 3.8 404 page

The old site had a generic error page. The new site has a **branded 404 page** — “Page not found”, a link back home, and the quick-link rail. A `noindex` header is also sent so search engines do not index the wrong page.

### 3.9 Screenshots would make this document easier to follow

If this document is presented to management as a PDF or slide deck, adding the following pairs of screenshots would make the comparison clearest:

1. Old slider vs new slider (with CTA buttons)
2. Old phone-number band vs new two CTA buttons
3. New WhatsApp button + the admin ON/OFF toggle
4. Old Careers (`#`) vs the new Careers page
5. Old homepage news section vs the new Insights list
6. Old footer vs new Privacy + Cookie settings
7. Old WordPress admin vs the new `/admin` dashboard
8. New GA4 dashboard (period filter and page report)

---

## 4. The Three New Pages

### 4.1 Careers — `/careers`

On the old site, “Careers” was in the menu but the link was `#` — clicking it did nothing. This was a clear broken promise.

The new page includes:

- An introduction and a team photo.
- **“Why build your career at HANSA”** — 6 reasons (world-class clients, training center, multiple career paths, 24/7 operations, safety culture, and more).
- **“Areas we recruit for”** — Hospitality, Security/Fire Safety, Cleaning/Housekeeping, MEP, Corporate & Support.
- **“How to apply”** — email your CV to `info@hansamgt.com`, or call the information desk on `01708800870`.
- A “Join our team” CTA and an **Email your CV** button in the sidebar.

**Limitation:** There is still **no job listing table, application form or CV upload**. Applications are handled entirely by email/phone. This should be added in future (§10).

### 4.2 Insights — `/insights`

The old site had no blog/news page; the homepage only had 2 static training items.

The new Insights system includes:

- A list of articles (currently 4 published).
- A separate page for each article: title, date, cover image, excerpt, full text and related articles.
- 9 articles per page, with pagination.
- An **RSS feed**: `/insights/feed` — readable by external tools/newsletters.
- Per-article SEO: meta title, meta description, cover image alt text and social share image.
- If a future date is set, the article stays hidden (it does not publish automatically).

**Why it matters:** A blog can cover topics buyers search for on Google — for example “how to choose a facility management company”, “fire safety checklist” or “hotel housekeeping standards”. This brings new visitors without advertising.

**Limitation:** There are still no categories, search, share buttons or newsletter subscription.

### 4.3 Privacy Policy — `/privacy-policy`

The old site had no privacy policy, even though the form collected names, emails and phone numbers.

The new page has 13 sections:

- What information we collect (form + analytics cookies)
- Why we collect it, and on what legal basis
- Cookies and Google Analytics
- Who we share it with, and where the data goes
- How long we keep it
- User rights (access, correction, deletion, withdrawal of consent)
- Security, children's privacy and contact details

The **“Last updated”** date on the page comes from Site Settings (currently 17 October 2026). The **Cookie settings** button in the footer lets visitors change their consent at any time.

> ⚠️ **Needs reconciliation:** The policy states specific periods (such as 24 months / 12 months), but the system does not yet delete data automatically after those periods. Before going live, either align the policy wording with reality or add a data-retention mechanism.

---

## 5. Google Analytics

### 5.1 What has been added

- **Google Analytics 4** integration, measurement ID `G-NJDKQTDFB5`.
- Turned on/off from admin with the **Analytics enabled** toggle.
- A **consent-first** policy:
  - If the visitor does not **Accept**, no data goes to Google.
  - If they Decline, the site works fully; only analytics is disabled.
  - The decision can be changed from **Cookie settings** in the footer.
  - If the consent version number changes, everyone is asked again.

### 5.2 What the admin dashboard shows

The `/admin` dashboard has two parts:

**A) Site counts (5 cards)**

- Active Projects
- Active Team Members
- Active Hero Slides
- Published Posts
- Unread Messages

**B) GA4 analytics**

- **Analytics Period** filter: Today, Yesterday, Last 7 Days, Last 30 Days, This Month, Last Month.
- **GA4 Page Report** table: Page Title, Page Path, Views, Active Users, Sessions.
- **Current Active Users (Last 30 Mins)**
- **Active Users**, **Sessions**, **Page Views** — a summary for the selected period.

### 5.3 What can and cannot be measured

| Can be measured | Cannot be measured |
|---|---|
| How often each page is viewed | Who is making phone calls |
| How many unique visitors | Number of WhatsApp clicks (unless tracked as a separate event) |
| Which city/device visitors come from | Which enquiry turned into which sale |
| Sessions and engagement | Connection to a CRM |
| Traffic source (Google, direct, social) | Advertising spend accounting |

> **Important:** Because analytics starts only after consent, GA4 numbers will appear **lower** than the real number of visitors. This is normal and is the legally and ethically correct approach.
>
> Some paths from the old site (such as `/hansamgtWebsite/...`) may appear in the dashboard — that is historical data. It will take some time to get clean figures after the new site goes live.

### 5.4 How to turn it off

`Admin → Site Settings → Analytics & legal → Analytics enabled` → turn it off → **Save settings**.

---

## 6. SEO Improvements

SEO means making sure HANSA's pages appear properly when someone searches Google for something like “facility management company Dhaka”.

### 6.1 Old vs new

| SEO element | Old | New | Explanation |
|---|---|---|---|
| **Page title** | Present | Unique and specific on every page | Appears as the blue link on Google |
| **Meta description** | Present (brief) | Unique and detailed on every page | The description below the search result |
| **Canonical URL** | None | Present on every page | Reduces problems when the same content spreads across different URLs |
| **Open Graph** | Basic only | Complete (title, description, image, url, type) | A clean preview when shared on Facebook/LinkedIn |
| **Twitter card** | None | Present | Large image preview on X/Twitter |
| **Structured data (JSON-LD)** | None | Organization/LocalBusiness, WebSite, WebPage, BreadcrumbList, Service, BlogPosting, ItemList | Helps Google understand the business address, services and articles |
| **Sitemap** | None | `sitemap.xml` (14+ URLs, with lastmod) | Helps Google find pages quickly |
| **robots.txt** | None | `/admin` blocked, sitemap referenced | The admin panel does not appear in search |
| **Breadcrumb** | None | Present | Both users and Google understand the path |
| **A single H1** | Not guaranteed | One H1 per page | A basic rule of SEO and accessibility |
| **301 redirect** | — | Old URL → new URL | Preserves old rankings |
| **RSS feed** | None | For Insights | Makes content distribution easier |
| **Non-production noindex** | — | Local/staging is never indexed | A duplicate site cannot be indexed by mistake |

### 6.2 What this delivers in practice

- Submitting the sitemap in Google Search Console gets new pages indexed faster.
- Because each service page has its own title/description, you can control which page appears for which search.
- LocalBusiness schema increases the chance of showing the address and phone for local searches such as “HANSA Management Dhaka”.
- Insights articles receive BlogPosting schema, so date/author can appear in rich results.

### 6.3 Limitations

- **SEO fields for static pages** (Company Profile, Mission, Charter, CSR, the 4 service pages, Careers, Privacy) **cannot be changed from admin** — they are written in code.
- The canonical URL host is fixed to `https://hansamgt.com` in code; changing “Website URL” in Site Settings does not change the canonical automatically.
- `sitemap.xml` may not update automatically every time a post is added; `php artisan sitemap:generate` must be run manually.
- `og:image:alt` always reads “… logo”, even for article cover images.
- The site is still **English-only**; there is no Bangla version.

---

## 7. Admin Panel — Complete Guide

### 7.1 Login and dashboard

1. Open in your browser: `http://127.0.0.1:8000/admin`
2. Email: `admin@gmail.com`
3. Password: `111111`
4. Click **Sign in**.

After signing in, the **Dashboard** opens. **Global search** at the top lets you search content; **User menu → Sign out** is on the right.

The dashboard shows:

- 5 site counts (Projects, Team, Hero Slides, Posts, Unread Messages).
- The GA4 period filter and page report.
- Active Users, Sessions, Page Views.

#### Which task lives where — at a glance

| What I want to do | Where to go |
|---|---|
| Change the homepage slider | Homepage Management → **Hero Slider** |
| Write a new blog/article | Content → **Insights** |
| Add a new project | Content → **Projects** |
| Add photos to the gallery | Content → **Gallery** |
| Add/change a team member | Content → **Management** |
| Change client logos | Homepage Management → **Valued Clients** |
| Business concern logos | Homepage Management → **Business Concerns** |
| Homepage numbers/bullets/cards | Homepage Management → **Homepage Sections** |
| Phone, email, address, WhatsApp | Settings → **Site Settings → Contact** |
| Logo, favicon, footer text | Settings → **Site Settings → Branding** |
| Facebook/LinkedIn links | Settings → **Site Settings → Social profiles** |
| Turn Google Analytics on/off | Settings → **Site Settings → Analytics & legal** |
| Legal company name, map coordinates | Settings → **Site Settings → SEO & structured data** |
| New office/booth | Settings → **Offices** |
| Read enquiries | Operations → **Contact Submissions** |

### 7.2 Sidebar map

| Group | Menu | What it does |
|---|---|---|
| — | **Dashboard** | Summary and analytics |
| **Content** | **Insights** | Blog/articles |
| | **Projects** | Project list and homepage cards |
| | **Gallery** | Photo gallery |
| | **Management** | Team members and biographies |
| **Homepage Management** | **Hero Slider** | Homepage slider |
| | **Valued Clients** | Client logo carousel |
| | **Business Concerns** | Business concern logos |
| | **Homepage Sections** | Commitments, numbers, at-a-glance, “Why choose” |
| **Settings** | **Site Settings** | Phone, email, logo, social, GA, SEO |
| | **Offices** | Offices and service booths |
| **Operations** | **Contact Submissions** | Enquiry inbox |

### 7.3 Content

#### 7.3.1 Insights (`/admin/posts`)

**The list shows:** Cover, Title, Published, Publish date. Button: **New article**.

**Form fields:**

| Field | Meaning |
|---|---|
| Title | Article title |
| URL slug | Page address (for example `/insights/fire-safety`) |
| Excerpt | Short text shown in the list/summary |
| Body | Full text (rich text editor) |
| Cover image | Cover photo |
| Cover image alt text | Image description (accessibility/SEO) |
| Published | Whether it will be published |
| Publish date & time | Publication date and time |
| Meta title | Title shown on Google |
| Meta description | Description shown on Google |
| Social share image | Image used when shared on social media |

**How to use:** To write a new article, click **New article** → fill in the fields → **Create**. Keep **Published** switched on to publish it.

> If a future date is set, the article will not appear on the website. There is no separate automatic publishing at a scheduled time.

#### 7.3.2 Projects (`/admin/projects`)

**In the list:** Photo, Name, Category, Order, Homepage, Active. Button: **New project**.

**Fields:**

- **Project:** Title, Location, Category (Ongoing / Upcoming), Sort order, Photo, Photo alt text, Active.
- **Homepage card:** Show on the homepage, Homepage name, Homepage location, Homepage photo alt text, Homepage order.

**How to use:** Add a project and turn on **Show on the homepage** to make it appear in the “Service Projects” section. Use the Sort order number to arrange items.

#### 7.3.3 Gallery (`/admin/galleries`)

**In the list:** Photo, Caption, Order, Active. Button: **New photo**.

**Fields:** Photo, Caption (also used as alt text), Sort order, Active.

**How to use:** To add a new photo, click **New photo** → upload the image → write a Caption → set a Sort order.

#### 7.3.4 Management (`/admin/team-members`)

**In the list:** Photo, Name, Role, Order, Active. Button: **New team member**.

**Fields:** Name, Role / title, Reference (URL slug), Name in the dialog heading, Sort order, Card text, Full biography, Photo, Photo alt text, Active.

**How to use:** On the `/management` page, cards are shown and clicking one opens the biography (dialog). Use Sort order to arrange them.

> Changing **Reference** (slug) manually can break old deep links (such as `#chairman-message`). Change it carefully.

### 7.4 Homepage Management

#### 7.4.1 Hero Slider (`/admin/hero-slides`)

**In the list:** Preview, Label, Alt text, Order, Active. Button: **New hero slide**.

**Fields:** Photo (required, max 2 MB), Alt text, Admin label, Sort order, Active.

**How to use:** To add a new slide, click **New hero slide** → upload the image → write Alt text → set a Sort order → keep Active on.

> Hero images are limited to **2 MB**; other images are generally limited to **3 MB**. Large images will slow the site down.

#### 7.4.2 Valued Clients (`/admin/clients`)

**In the list:** Logo, Name, Alt text, Order, Active. Button: **New client**.

**Fields:** Logo (required, 2 MB), Name, Alt text, Sort order, Active.

#### 7.4.3 Business Concerns (`/admin/concerns`)

**In the list:** Logo, Name, Alt text, Order, Active. Button: **New concern**.

**Fields:** The same as Valued Clients — Logo, Name, Alt text, Sort order, Active.

#### 7.4.4 Homepage Sections (`/admin/homepage-section-items`)

This is where the items inside the four homepage sections live. Button: **New homepage item**.

**The fields change depending on the selected Section:**

| Section | What can be edited |
|---|---|
| **Our commitments (3 cards)** | Heading, Text, Icon, Image, Image alt text, Sort order, Active |
| **HANSA in Numbers (5 statistics)** | Label under the number, Number, “…or count from a year”, Sort order, Active |
| **Services at-a-glance (8 cards)** | Heading, Text, Image, Image alt text, Sort order, Active |
| **Why to choose HANSA (bullet points)** | Bullet point, Column (1/2), Sort order, Active |

**Important:**

- The **large section headings** cannot be changed here; only the items inside them.
- The **4 “Our Services” cards** on the homepage are not here — they are fixed in code.
- Items in this list cannot be reordered by dragging; use the **Sort order** number.

### 7.5 Settings

#### 7.5.1 Site Settings (`/admin/settings`)

Five tabs:

**A) Contact**

| Field | Purpose |
|---|---|
| Send enquiries to | Which email address receives form enquiries |
| Public email address | The email shown on the site |
| Primary phone | Main phone number |
| Emergency phone | Emergency phone (shown if provided) |
| WhatsApp number | Number used by the floating WhatsApp button |
| Fax | Fax number |
| Postal address (displayed) | The main address line |
| Street address, City, Postal code, Country code | Used for structured data/address |
| Show the floating WhatsApp button | WhatsApp button ON/OFF |

**B) Branding**

- Header logo
- Footer logo
- Favicon / app icon
- Default social share image
- Footer introduction (short footer text)

**C) Social profiles**

- Facebook, LinkedIn, YouTube, X (Twitter), Instagram.
- If a profile's URL is empty, its icon is not shown.

**D) Analytics & legal**

- Analytics enabled (GA4 ON/OFF)
- Measurement ID (`G-NJDKQTDFB5`)
- Consent version (changing it asks everyone for consent again)
- Privacy policy last updated (the date shown on the Privacy page)

**E) SEO & structured data**

- Legal name
- Website URL
- Parent company
- Parent company URL
- Founded (year)
- Short description
- Latitude / Longitude
- Opening hours

There are two buttons at the bottom: **Save settings** and **Send test email**.

> **Send test email** shows whether the mail server is working. It sends a test email to the “Send enquiries to” address.

#### 7.5.2 Offices (`/admin/offices`)

**In the list:** Name, Address, Mobile, Landline, Order, Active. Button: **New office**.

**Fields:** Name, Address, Mobile, Landline, Email, Sort order, Active, Latitude, Longitude.

**How to use:** Shown as office cards and on the map on the `/contact` page. To show an office on the map, **both Latitude and Longitude** must be correct.

> The old site's coordinates were placeholders; do not reuse them. Take accurate coordinates from Google Maps.

### 7.6 Operations

#### Contact Submissions (`/admin/contact-submissions`)

**In the list:** Name, Email, Phone, Message, Read, Received. New enquiries appear at the top.

**What you can do:**

- Open and read an enquiry.
- Mark it Read/Unread.
- Delete it (one or several — Bulk actions → Delete selected).
- Search by name/email.

**What you cannot do:**

- Reply directly from the panel.
- Export enquiries to CSV.
- Assign an enquiry to a staff member.
- Restore a deleted enquiry (there is no Recycle Bin).

### 7.7 Step-by-step guides

#### (a) Change the homepage slider

1. `Homepage Management → Hero Slider`
2. **New hero slide** (to add) or **Edit** on an existing slide
3. Upload an image (under 2 MB)
4. Set Alt text and Sort order
5. Keep **Active** on
6. **Create / Save changes**

#### (b) Turn off the WhatsApp button

1. `Settings → Site Settings → Contact`
2. Turn off **Show the floating WhatsApp button**
3. **Save settings**
4. Refresh the website — the button is gone

#### (c) Publish a new Insights article

1. `Content → Insights → New article`
2. Write the Title, URL slug, Excerpt and Body
3. Add a Cover image and alt text
4. Add Meta title/description (for SEO)
5. Turn on **Published** and set **Publish date & time**
6. **Create**

#### (d) Add a new project and show it on the homepage

1. `Content → Projects → New project`
2. Enter Title, Location, Category, Sort order and Photo
3. Turn on **Show on the homepage**
4. Enter Homepage name/location/order
5. **Create**

#### (e) Add a photo to the gallery

1. `Content → Gallery → New photo`
2. Upload the Photo
3. Write a Caption (alt text)
4. Set a Sort order and keep Active on
5. **Create**

#### (f) Add a new member to the management team

1. `Content → Management → New team member`
2. Enter Name, Role / title, Reference, Card text and Full biography
3. Add Photo and Photo alt text
4. Set a Sort order and keep Active on
5. **Create**

#### (g) Change a homepage statistic

1. `Homepage Management → Homepage Sections → New homepage item`
2. Section = **HANSA in Numbers**
3. Write the Label under the number (for example “Employees”)
4. Enter the Number (for example `1000`) — or use “…or count from a year” to calculate from a year
5. Set a Sort order and keep Active on
6. **Create**

#### (h) Add a new office/booth

1. `Settings → Offices → New office`
2. Enter Name, Address, Mobile, Landline and Email
3. Enter Latitude/Longitude (if it should appear on the map)
4. Set a Sort order and keep Active on
5. **Create**

#### (i) Review and manage enquiries

1. `Operations → Contact Submissions`
2. Open the new entry
3. Read it and mark it **Read**
4. Delete it if necessary
5. Reply by email/phone (there is no reply feature in the panel)

#### (j) Change the logo/phone/social links

1. `Settings → Site Settings`
2. Open the relevant tab (Branding / Contact / Social profiles)
3. Change the values
4. **Save settings**

#### (k) Turn Google Analytics on/off

1. `Settings → Site Settings → Analytics & legal`
2. Toggle **Analytics enabled**
3. **Save settings**

### 7.8 What cannot be done from admin

| Task | Status | Alternative |
|---|---|---|
| Create a new admin user | Not available | Developer runs `php artisan admin:create` |
| Roles/permissions (Admin/Editor) | Not available | Every admin can do everything |
| Password reset (UI) | Not available | Developer/CLI |
| 2FA (two-step login) | Not available | Should be added in future |
| Audit trail (who changed what) | Not available | — |
| Restore something that was deleted | Not available | Database backup |
| Edit static page copy | Not available | Code edit |
| Edit service page copy | Not available | Code edit |
| Edit Careers page copy | Not available | Code edit |
| Large homepage section headings | Not available | Code edit |
| Hero CTA button text/destination | Not available | Code edit |
| The 4 “Our Services” cards | Not available | Code edit |
| Navigation/footer labels | Not available | Code edit |
| Preview a draft post as a visitor | Not available | — |
| Auto-publish a post at a scheduled time | Partial | A future date keeps it hidden |
| Upload many images at once | Not available | One at a time |
| Bulk edit | Not available | Bulk delete only |
| Media library | Not available | Separate upload in each field |
| Export enquiries (CSV) | Not available | Developer/DB |
| Per-page SEO for static pages | Not available | Code edit |

---

## 8. Migrating from the Old Site to the New One

### 8.1 URLs and redirects

Old site URLs looked like this:

- `https://hansamgt.com/index.php/home`
- `https://hansamgt.com/index.php/home/history`
- `https://hansamgt.com/index.php/home/hardService`

The new site uses clean URLs:

- `/`
- `/company-profile`
- `/hospitality-management`

If someone visits an old URL, a **301 redirect** sends them to the new page. This is very important for SEO — old links and Google rankings carry over to the new site.

**How to verify:** Open an old URL in a browser. If it returns a 404 instead of the corresponding new page, tell the developer.

### 8.2 Content migration

| Old | New | Status |
|---|---|---|
| Home | `/` | ✅ |
| Company Profile | `/company-profile` | ✅ |
| Mission & Vision | `/mission-vision` | ✅ |
| Charter of Unique Group | `/charter-of-unique-group` | ✅ |
| Management | `/management` | ✅ |
| CSR | `/corporate-social-responsibility` | ✅ |
| 4 service pages | 4 new URLs | ✅ |
| Projects | `/projects` | ✅ |
| Gallery | `/gallery` | ✅ |
| Contact | `/contact` | ✅ |
| Careers (`#`) | `/careers` | ✅ New |
| — | `/insights` | ✅ New |
| — | `/privacy-policy` | ✅ New |

### 8.3 Phone, email and address

All key contact information now comes from **one place** (Site Settings). Previously it was copied across many files, so changing it in one place left stale values elsewhere. Now a single change updates the header, footer, contact page and schema everywhere.

---

## 9. Verification Checklist

A non-technical person can use this list to test the new site. Tick ✓ next to each item.

### Public site

- [ ] The homepage opens and the slider runs automatically.
- [ ] The slider's previous/next arrows work.
- [ ] Clicking **Request a Quote** scrolls to the enquiry form.
- [ ] Clicking **Call 01708800870** opens the phone dialer.
- [ ] Clicking the WhatsApp button on the right opens `wa.me` with the correct number.
- [ ] The quick-link rail appears on the homepage and all 7 links work.
- [ ] The rail also appears on another page (such as `/company-profile`); clicking a link goes to the relevant homepage section.
- [ ] On mobile the rail is hidden, but the WhatsApp button remains.
- [ ] Clicking **Careers** in the menu opens `/careers` (not `#` as before).
- [ ] The CV email link and phone number on the Careers page work.
- [ ] Clicking **Insights** in the menu opens the article list.
- [ ] Opening an article shows the full text, date and image.
- [ ] Opening `/insights/feed` shows the RSS feed.
- [ ] The **Privacy Policy** link in the footer works.
- [ ] The Privacy page has 13 sections and a “Last updated” date.
- [ ] The **Cookie settings** button is present in the footer.
- [ ] A fresh browser (with cache cleared) shows the cookie consent banner.
- [ ] Clicking **Decline** leaves the site working; no request to Google appears in the browser's Network tab.
- [ ] Clicking **Accept** shows the visit in GA4 real-time.
- [ ] Entering a wrong URL shows the branded 404 page, with the rail.
- [ ] The homepage, menu and form display correctly on mobile.
- [ ] Submitting an invalid email/empty field shows an understandable error.
- [ ] Submitting the form correctly shows a thank-you message.

### Admin

- [ ] `/admin` opens the login page.
- [ ] Logging in with `admin@gmail.com / 111111` works.
- [ ] The dashboard shows 5 counts and the GA4 widgets.
- [ ] Changing the Analytics Period changes the numbers.
- [ ] The sidebar has 4 groups and 10 resources.
- [ ] A new slide can be added from Hero Slider with **New hero slide**.
- [ ] A test slide appears on the website and can then be deleted.
- [ ] A new logo can be added under Valued Clients.
- [ ] The “HANSA in Numbers” figures can be changed under Homepage Sections.
- [ ] A new article can be created under Insights.
- [ ] Turning on **Show on the homepage** under Projects makes it appear on the homepage.
- [ ] Photos can be added under Gallery.
- [ ] Team members can be added under Management.
- [ ] New offices can be added under Offices.
- [ ] Changing phone/email in Site Settings and clicking **Save settings** updates the site.
- [ ] **Send test email** works (the email arrives).
- [ ] Turning off the WhatsApp toggle hides the button; turning it back on restores it.
- [ ] New enquiries appear in Contact Submissions and can be marked Read.
- [ ] After submitting the form, the admin email and the visitor's confirmation email both arrive.
- [ ] Deleting an entry removes it from the list (remember: it cannot be restored).

### Before launch (developer/admin)

- [ ] The admin password has been changed.
- [ ] `APP_ENV=production`, `APP_DEBUG=false`.
- [ ] Correct SMTP is configured and **Send test email** succeeds.
- [ ] `php artisan storage:link` has been run (uploaded images display).
- [ ] 301 redirects from old URLs have been verified.
- [ ] `sitemap.xml` is updated and submitted to Google Search Console.
- [ ] `/admin` is blocked in `robots.txt`.
- [ ] Backups and uptime monitoring are enabled.
- [ ] The Privacy Policy has been reviewed by a lawyer/responsible person.
- [ ] The policy has been reconciled with the data retention policy.

---

## 10. Improvement Recommendations

The recommendations below are organized by “what is needed” and “why it is needed”. Priority: P1 = before/immediately after launch, P2 = 1–3 months, P3 = 3–6 months.

### 10.1 P1 — Essential before launch

| # | Recommendation | Why | Owner |
|---|---|---|---|
| 1 | **Change the admin password, add 2FA, restrict `/admin`** | The default password is guessable; if the admin is compromised, the whole site is at risk | Developer |
| 2 | **Verify production settings and SMTP** | If emails fail, leads are only discovered late | Developer |
| 3 | **Ensure `storage:link` is in place** | Uploaded logos/images may 404 from `/storage/...` | Developer |
| 4 | **301 redirects and Search Console** | Preserve old rankings and links | Developer + Marketing |
| 5 | **Legal review of the Privacy Policy + reconcile retention** | If stated periods are not enforced, there is legal risk | Management + Lawyer |
| 6 | **Spam protection (honeypot/captcha) + SPF/DKIM** | Form spam and emails landing outside the inbox | Developer |
| 7 | **Backups and uptime monitoring** | Fast response if data is lost or the site goes down | IT |

### 10.2 P2 — Within the first 1–3 months

| # | Recommendation | Why | Estimated effort |
|---|---|---|---|
| 8 | **Make static pages editable from admin** (Company Profile, Mission, Charter, CSR, 4 service pages, Careers, Privacy) | Every text change currently needs a developer; Marketing is not independent | Large |
| 9 | **Job listings + application form + CV upload on Careers** | Currently email only; tracking and experience would improve | Medium |
| 10 | **Add Company, Service, Property type and Location to the enquiry form + CSV export** | Easier lead qualification and reporting | Medium |
| 11 | **Per-page SEO fields for static pages** | Each service page can rank for its own keywords | Medium |
| 12 | **Categories, search, share buttons and newsletter for Insights** | Increases blog reach and repeat visits | Medium |
| 13 | **Testimonials, case studies and certifications page** | Buyers look for “proof”, not just “claims” | Medium |
| 14 | **Company Profile PDF download** | Tender/procurement teams expect this | Small |
| 15 | **Image optimization (WebP/srcset) + homepage caching** | Mobile speed and SEO | Medium |
| 16 | **Automatically update the sitemap when a post is published** | New articles get indexed faster | Small |

### 10.3 P3 — Within 3–6 months

| # | Recommendation | Why |
|---|---|---|
| 17 | **Bangla version and language switcher** | A large share of local buyers search in Bangla |
| 18 | **FAQ + FAQPage schema** | Higher chance of showing answers directly in Google/AI search |
| 19 | **Location/service-area pages** (Dhaka, Banani, Uttara, Gulshan…) | Capture “near me” style local searches |
| 20 | **Accessibility audit** | Benefits users with disabilities and public tenders |
| 21 | **WhatsApp/call click tracking** | Measure which CTA actually generates leads |
| 22 | **Content calendar and a responsible owner** | SEO gains do not last unless the blog is updated regularly |

### 10.4 Quick wins

These can make a big difference with very little work:

1. Make the homepage CTA text editable from admin.
2. Restore all service links in the footer instead of only 4.
3. Add a short FAQ to each service page.
4. Update the website link and new photos on Google Business Profile.
5. Share Insights articles on LinkedIn/Facebook.
6. Update the Privacy page's “Last updated” date regularly.

---

## 11. Risks and Operational Notes

| Risk | What could happen | Mitigation |
|---|---|---|
| **Default admin password** | Admin takeover, content changes | Change before launch, add 2FA |
| **No `public/storage` symlink** | Uploaded images 404 | `php artisan storage:link` |
| **SMTP not configured** | Enquiry emails fail (they remain in the DB) | Verify with Send test email |
| **No backups** | Data/images lost | Daily database + file backups |
| **Privacy retention mismatch** | Legal complaint | Amend the policy or add auto-deletion |
| **Static sitemap** | New posts indexed late | Run `sitemap:generate` after publishing |
| **Canonical host fixed** | Wrong canonical if the domain changes | Use `site_url` in code |
| **Deletes cannot be undone** | Accidental deletion | Backups; confirm before deleting |
| **No audit trail** | Cannot tell who changed what | Add logging in future |
| **All admins are equal** | Accidental changes to important items | Add roles/permissions |
| **Large images** | Slow site | Respect the 2–3 MB limit, use WebP |
| **GA4 is consent-based** | Numbers appear lower | This is normal; declines cannot be counted either |
| **Old phone number** | Calls to the wrong number | Fix once in Site Settings |
| **Old coordinates** | Wrong location on the map | Enter accurate new coordinates |

### Backup plan (recommended)

- **Daily:** Database dump.
- **Weekly:** `storage/app/public` folder (uploaded images).
- **Monthly:** Full site + `.env` (stored securely).
- **Retention:** At least 30 days; on a separate server/cloud.
- **Testing:** Restore from backup once every 3 months.

---

## 12. Easily Overlooked Items

The following are often left out of documents but are essential for launch:

### Content and brand

- [ ] All copy from every old page is present on the new site (not just the menu, but the body text too).
- [ ] The footer address, phone, email and fax are correct.
- [ ] Social links actually work (some were broken on the old site).
- [ ] Logo, favicon and social share image are correct.
- [ ] The copyright year is automatic.
- [ ] English spelling and grammar.
- [ ] Image alt text (accessibility).

### Technical

- [ ] HTTPS/SSL certificate.
- [ ] Redirect `www` vs non-`www` to one of them.
- [ ] 301 map for old URLs.
- [ ] `sitemap.xml` and `robots.txt`.
- [ ] Google Search Console and Bing Webmaster Tools.
- [ ] Website link on Google Business Profile.
- [ ] Form emails reaching the inbox (check the spam folder too).
- [ ] Correct phone/WhatsApp numbers.
- [ ] 404 and 500 pages.
- [ ] Test on mobile and tablet.
- [ ] Test in Chrome, Safari, Edge and Firefox.
- [ ] Images load on a slow connection.
- [ ] Pages look right when printed/saved as PDF (for tenders).

### Legal and consent

- [ ] A lawyer has reviewed the Privacy Policy.
- [ ] Cookie consent works correctly.
- [ ] A data retention policy is actually in place.
- [ ] A responsible person/email for contact has been designated.
- [ ] Separate consent if email marketing is used.

### People and process

- [ ] Who will be an admin, and who can change what.
- [ ] Admin training for the marketing team.
- [ ] Ownership and schedule for content updates.
- [ ] The SLA for responding to enquiries (within how many hours).
- [ ] Monthly analytics review.
- [ ] Quarterly link and form testing.
- [ ] Annual privacy and security review.
- [ ] A support agreement/contact path with the developer.

---

## 13. Technical Appendix

> This section is for the developer/IT team; non-technical readers may skip it.

### 13.1 Stack

- **Framework:** Laravel 12 (PHP)
- **Admin:** Filament v4
- **Frontend:** Blade + Tailwind CSS + Vite
- **JavaScript:** vanilla JS (no jQuery/slider plugin)
- **Database:** MySQL/SQLite (depending on the environment)
- **Analytics:** GA4, consent-gated

### 13.2 Key files

| Purpose | File |
|---|---|
| Public layout | `resources/views/layouts/app.blade.php` |
| SEO | `resources/views/partials/seo.blade.php` |
| Header/footer | `resources/views/partials/header.blade.php`, `footer.blade.php` |
| WhatsApp | `resources/views/partials/whatsapp-float.blade.php` |
| Quick-link rail | `resources/views/pages/home/sections/_rail.blade.php` |
| Homepage | `resources/views/pages/home.blade.php` |
| Slider | `resources/views/pages/home/sections/_hero.blade.php` |
| Consent | `resources/views/partials/consent.blade.php`, `resources/js/consent.js` |
| Admin settings | `app/Filament/Pages/ManageSettings.php` |
| Admin resources | `app/Filament/Resources/` |
| Routes | `routes/web.php` |
| Sitemap | `public/sitemap.xml`, `app/Console/Commands/GenerateSitemap.php` |
| Robots | `public/robots.txt` |

### 13.3 Useful commands

```bash
# Make uploaded images web-accessible
php artisan storage:link

# Create a new admin (local)
php artisan admin:create

# Regenerate the sitemap
php artisan sitemap:generate

# Run tests
php artisan test

# Run only the quick-rail test
php artisan test --filter=QuickRailTest
```

### 13.4 Known limitations (developer notes)

- `HomepageSectionItem` supports numeric order only; there is no drag reordering.
- `Gallery` image is nullable — saving without an image can produce an empty `src`.
- The conditional fields in `HomepageSectionItem` are client-side in Livewire; switching sections can leave hidden values behind.
- The canonical host is fixed in code (`https://hansamgt.com`) and can diverge from `site_url` in Site Settings.
- `og:image:alt` always reads “… logo”.
- There is no automatic deletion for the retention periods stated in the Privacy Policy.
- Without the `public/storage` symlink, uploaded branding images 404.
- The admin has no user/role/2FA/audit trail.
- Static and service page content lives in code.
- Slider/client images are limited to 2 MB; other images to 3 MB.
- The site is English-only.

### 13.5 Testing status

- `tests/Feature/QuickRailTest.php` was added for the new quick rail (exactly one rail with 7 links on every public page).
- 14+ public pages and the 404 page were verified manually in a browser.
- Shell commands could not be run in this environment while the document was being prepared, so PHPUnit could not be run automatically. The developer should run `php artisan test` on the server to confirm.

---

## Final Note

The new site is far more complete than the old one — especially in **lead capture (CTA, WhatsApp, forms), content management, careers, blog, privacy and analytics**.

But it is not “finished”. A website is always an evolving product:

1. **Security** (passwords, 2FA, backups) comes first.
2. **Content** on a regular schedule (blog, projects, gallery).
3. **Measurement** (GA4, Search Console, enquiries).
4. **Improvement** (P1 → P2 → P3).

Use this document as a verification checklist. If something does not work or you have a question, tell the developer team and name the specific page/menu item.

---

*Prepared: 25 October 2026 · HANSA Management Ltd. Website Migration Documentation*
