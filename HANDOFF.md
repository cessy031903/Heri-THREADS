# Heri-THREADS — Handoff (short version)

---

## PART 1 — You (before AnyDesk)

1. Site works on your PC.
2. Run: `npm run build`
3. Zip/copy the **whole** `IfugaoTall` folder → USB or Drive.
4. Give it to her.

**Include in the copy:** `vendor/`, `public/build/`, `.env`, `database/database.sqlite`, `storage/app/public/`  
**Skip:** `node_modules/` (not needed to run the site)

### About `.env` and database (read this)

| File | What it is |
|------|------------|
| `.env.example` | Empty template in Git — **not used to run the site** |
| `.env` | **Your real settings** (APP_KEY, database, etc.) — **you already have this** on your PC. **Copy it inside the folder** so her laptop works with zero setup. |
| `database/database.sqlite` | **The actual database** (dances, attires, guides, admin user). Tables are already there — **no migrations needed** on her PC if you copy this file. |

**Migrations** (`php artisan migrate --seed`) = only if she has **no** `database.sqlite` (fresh empty project).  
**Whole-folder handoff** = copy `.env` + `database.sqlite` → she does **not** run migrations.

---

## PART 2 — Her laptop (AnyDesk)

1. Install **Laragon** → https://laragon.org → **Start All**
2. Paste folder → `C:\laragon\www\IfugaoTall`
3. Laragon → **Terminal**:
   ```powershell
   cd IfugaoTall
   php artisan storage:link
   php artisan serve
   ```
   *(Skip `storage:link` if images already show.)*

4. Browser → http://127.0.0.1:8000  
5. Admin → http://127.0.0.1:8000/login  
6. **Change password** (don’t keep `admin1234`):

   ```powershell
   php artisan tinker
   ```
   ```php
   $u = App\Models\User::where('email', 'admin@ifugao.local')->first();
   $u->password = Hash::make('HerNewPassword123');
   $u->save();
   exit
   ```

   Then `php artisan serve` again.

**Default login (change this):** `admin@ifugao.local` / `admin1234`

---

## Every day (her routine)

1. Laragon → **Start All**
2. Terminal:
   ```powershell
   cd C:\laragon\www\IfugaoTall
   php artisan serve
   ```
3. Browser → http://127.0.0.1:8000

---

## Quick fixes

| Problem | Fix |
|---------|-----|
| No styling | You forgot `npm run build` before zip — rebuild and re-copy |
| No images | `php artisan storage:link` |
| `php` not found | Use **Laragon → Terminal** |
| Empty site / errors | `.env` or `database.sqlite` missing from zip — copy them again |

---

## URLs

- Site: http://127.0.0.1:8000  
- Login: http://127.0.0.1:8000/login  
- Admin: http://127.0.0.1:8000/admin  

---

*Putting the site on the real internet (hosting/domain) is a separate job — not covered here.*
