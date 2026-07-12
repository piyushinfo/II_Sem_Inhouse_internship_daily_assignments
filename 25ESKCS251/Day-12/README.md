# Day 12 — Making Your Project Production Ready

Student: **Piyush Sharma** · Roll No: **25ESKCS001** (rename the top-level folder to your actual roll number)

This build starts from your **actual Day 11 files** (thanks for sharing
them) and layers today's three modules on top — profile photo uploads,
live student search, and dashboard/UI polish — plus a project review
checklist. Nothing about your Day 9–11 code style was changed unless a
today's mission specifically required it.

## Good news: no database migration needed today

Your `schema.sql` already had a `photo VARCHAR(255) NULL` column since
Day 9 — it was just being filled with the literal string `"placeholder.png"`
because uploads were UI-only back then. So today's photo feature is a
**pure code change**, no `ALTER TABLE` required. `getStudentPhoto()` in
`functions.php` treats the old `"placeholder.png"` value the same as "no
photo" and falls back to `assets/default.png`.

If you're setting the database up fresh, `schema.sql` and
`users_schema.sql` are unchanged from Day 11 — just run them as before.

## What changed today, file by file

| File | What changed |
|---|---|
| `functions.php` | Added `getStudentPhoto($photo)` — returns the real upload path, or `assets/default.png` if there's no photo (or the old placeholder value) on disk. |
| `header.php` | Added the Bootstrap Icons CDN link + `.student-avatar` CSS. Your emoji-based nav is untouched. |
| `index.html` | The old "UI-only" file input is now a real upload with `enctype="multipart/form-data"`, plus a live preview image (JS). |
| `process_form.php` | Replaced the `$photo = "placeholder.png"` stub with real validation (type/size) + `move_uploaded_file()`. Your existing escaped-string INSERT pattern is untouched. |
| `edit.php` | Added an optional "replace photo" file input (keeps the existing photo if left empty) and added `photo` to the prepared-statement `UPDATE`. |
| `students.php` | Added an avatar column to the table. Search now covers **name, branch, course** (previously name/branch/email) to match today's spec. Course column is now highlighted too. Bootstrap Icons added to Search/Clear/Edit/Delete/Register buttons. |
| `dashboard.php` | Added an "Added This Week" stat card (7-day `date_registered` filter) and a bonus "Recent Registrations" widget showing the last 5 students with photos. |
| `assets/default.png` | New — the fallback avatar image. |
| `uploads/` | New empty folder (with `.gitkeep`) where uploaded photos are saved. |

**Unchanged today:** `db_connect.php`, `auth_check.php`, `login.php`,
`logout.php`, `403.php`, `delete.php`, `schema.sql`, `alter_table.sql`,
`users_schema.sql`.

## Setup (XAMPP / Laragon on Windows)

1. Copy the entire `Day-12` folder into `htdocs`
   (e.g. `D:\APPS\XAMPP\htdocs\Day-12`) — copy the whole folder, not
   individual files, to avoid the "missing file" bug from Day 11.
2. Start Apache and MySQL.
3. Your existing `student_management` database works as-is — no SQL to
   run today. (Fresh setup: import `schema.sql`, then `users_schema.sql`.)
4. Confirm `uploads/` exists inside `Day-12` and is writable by Apache.
5. Visit `http://localhost/Day-12/login.php`:
   - Email: `admin@scrumdigital.com`
   - Password: `admin123`

## Self-Review Checklist (Module 4)

- [ ] Every form (add, edit, login) shows a clear error for empty/invalid
      fields — nothing fails silently.
- [ ] Upload an oversized file and a non-image file — both show a clear
      error instead of failing silently.
- [ ] Click every nav link and button — none are broken.
- [ ] `students.php` with zero students, and a search with zero results,
      both show a friendly empty state (already true from Day 11).
- [ ] DevTools → Toggle Device Toolbar — check dashboard, student list,
      and forms on a phone-sized screen.
- [ ] Log out, then try to open `dashboard.php` or `students.php`
      directly — you should be redirected to `login.php`.

## Git push

```bash
git add Day-12/
git commit -m "Day 12: Profile photo uploads, student search by course, and dashboard polish"
git push origin 25ESKCS001
```

## Next: Day 13 — Demo Day

Prepare the 3-minute walkthrough: project story → feature demo
(login → CRUD → upload → search) → architecture → challenges & learnings.
