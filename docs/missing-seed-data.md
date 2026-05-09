# Missing Seed Data Checklist

Use this file to provide the remaining data that is not available in `docs/info.md` and `docs/albaseetsoft-website.html`.

Status:
- [ ] Team members
- [ ] Portfolio projects
- [ ] Testimonials
- [ ] Media assets (logo/favicon/section images)
- [ ] Optional: contact messages demo data

## 1) Team Members (`team_members`)

Required minimum per item:
- `name_en`
- `name_ar`
- `position_en`
- `position_ar`

Optional per item:
- `bio_en`, `bio_ar`
- `email`, `phone`
- `facebook`, `twitter`, `instagram`, `linkedin`
- `photo` (relative path in `storage/app/public/...`)
- `sort_order` (int), `is_active` (true/false)

Template:

```md
### Team Member 1
- name_en:
- name_ar:
- position_en:
- position_ar:
- bio_en:
- bio_ar:
- email:
- phone:
- facebook:
- twitter:
- instagram:
- linkedin:
- photo:
- sort_order:
- is_active:
```

## 2) Portfolio Projects (`portfolios`)

Required minimum per item:
- `title_en`
- `title_ar`
- `image` (required by DB)

Optional per item:
- `description_en`, `description_ar`
- `client_name_en`, `client_name_ar`
- `category_en`, `category_ar`
- `gallery` (JSON array of image paths)
- `project_url`
- `completed_at` (`YYYY-MM-DD`)
- `sort_order` (int), `is_featured` (true/false), `is_active` (true/false)

Template:

```md
### Portfolio 1
- title_en:
- title_ar:
- description_en:
- description_ar:
- client_name_en:
- client_name_ar:
- category_en:
- category_ar:
- image:
- gallery: []
- project_url:
- completed_at:
- sort_order:
- is_featured:
- is_active:
```

## 3) Testimonials (`testimonials`)

Required minimum per item:
- `client_name_en`
- `client_name_ar`
- `content_en`
- `content_ar`
- `rating` (1-5)

Optional per item:
- `client_position_en`, `client_position_ar`
- `client_company_en`, `client_company_ar`
- `client_photo` (relative path in `storage/app/public/...`)
- `sort_order` (int), `is_featured` (true/false), `is_active` (true/false)

Template:

```md
### Testimonial 1
- client_name_en:
- client_name_ar:
- client_position_en:
- client_position_ar:
- client_company_en:
- client_company_ar:
- content_en:
- content_ar:
- rating:
- client_photo:
- sort_order:
- is_featured:
- is_active:
```

## 4) Media Assets

Provide actual files under `storage/app/public/` and list relative paths.

Checklist:
- [ ] Company logo (`company_settings.logo`)
- [ ] Company favicon (`company_settings.favicon`)
- [ ] Hero background image (`hero_sections.background_image`)
- [ ] Hero foreground image (`hero_sections.foreground_image`)
- [ ] About image (`about_sections.image`)
- [ ] Service images (`services.image`)
- [ ] Team photos (`team_members.photo`)
- [ ] Portfolio main images (`portfolios.image`)
- [ ] Portfolio gallery images (`portfolios.gallery`)
- [ ] Testimonial photos (`testimonials.client_photo`)

Path examples:
- `settings/logo.png`
- `settings/favicon.ico`
- `hero/foreground.png`
- `about/company.jpg`
- `services/erp.jpg`
- `team/member-1.jpg`
- `portfolio/project-1.jpg`
- `testimonials/client-1.jpg`

## 5) Optional Contact Messages (`contact_messages`)

Not required for public website display, but useful for admin dashboard demo.

Template:

```md
### Contact Message 1
- name:
- email:
- phone:
- subject:
- message:
- is_read:
- is_replied:
```

## Notes

- Keep Arabic content in `*_ar` and English content in `*_en`.
- If an English value is not available yet, write `TBD` (do not leave it blank).
- For any image field, path must be relative to `storage/app/public/`.
