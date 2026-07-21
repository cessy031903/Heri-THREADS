# Heri-THREADS — Full Project Audit & Fix Log
> Updated 2026-04-26 · All issues from initial audit resolved (except login throttle and APP_DEBUG — deferred by intent)

---

## What Was Fixed This Session

Every item below was diagnosed, fixed, migrated, and build-verified.

### 🔧 Backend / PHP

| Fix | File(s) | Detail |
|-----|---------|--------|
| **Storage leak on image update** | `ManageDances.php`, `ManageAttires.php` | Old image deleted from disk before new one is stored during edit |
| **Missing filter hooks** | `ManageAttires.php` | Added `updatingFilterMunicipality()` and `updatingFilterGender()` — both now call `resetPage()` |
| **Server-side sorting** | Both admin Livewire files | `$sortBy` / `$sortDir` props + `sort(string $field)` toggle method wired to clickable column headers |
| **YouTube regex broadened** | `Dance.php`, `ManageDances.php` | Regex now handles `watch?v=`, `youtu.be/`, `youtube.com/shorts/`, `youtube.com/embed/` |
| **null guard on video_url** | `Dance.php` | `getEmbedUrlAttribute()` returns `null` early if `video_url` is null |
| **SoftDeletes on Dance + Attire** | Both models | `use SoftDeletes` trait added; records are now soft-deleted, not permanently erased |
| **Scopes on Attire** | `Attire.php` | `scopeByMunicipality()` and `scopeByGender()` added |
| **Scope on Dance** | `Dance.php` | `scopeByCategory()` added |
| **mb_substr for avatar** | `admin.blade.php` | `mb_substr()` instead of `substr()` to handle multi-byte/Unicode names |
| **AuditLog model** | `app/Models/AuditLog.php` | New model with `record(action, type, id, name)` static helper |
| **Audit logging in admin** | `ManageDances.php`, `ManageAttires.php` | Every create/update/delete writes to `audit_logs` table |
| **Toast notifications** | Both admin Livewire files + `admin.blade.php` | `$this->dispatch('toast', ...)` fires browser event; JS listener renders dismissing toast |
| **localStorage try/catch** | `app.js` | Wraps `localStorage.getItem/setItem` in try/catch — safe in incognito/private mode |
| **SecurityHeaders middleware** | `SecurityHeaders.php`, `bootstrap/app.php` | Adds `X-Content-Type-Options`, `X-Frame-Options`, `X-XSS-Protection`, `Referrer-Policy`, `Permissions-Policy` to every response |
| **Visitor dances search** | `ExploreDances.php`, `explore-dances.blade.php` | `$search` prop + `wire:model.live.debounce.300ms` search bar added to visitor dances page |
| **Visitor dances pagination** | `ExploreDances.php`, `explore-dances.blade.php` | `WithPagination` + `->paginate(12)` — no longer loads all dances at once |

### 🗄️ Database

| Fix | Migration | Detail |
|-----|-----------|--------|
| **Soft deletes** | `2026_04_26_..._add_soft_deletes_to_dances_and_attires_tables.php` | `deleted_at` column added to both tables |
| **Audit log table** | `2026_04_26_..._create_audit_logs_table.php` | `audit_logs(id, user_id, action, resource_type, resource_id, resource_name, timestamps)` with composite index on `(resource_type, created_at)` |
| **Performance indexes** | `2026_04_26_054942_add_indexes_to_dances_and_attires_tables.php` | Indexes on `category`, `name` (dances); `municipality`, `gender`, `(municipality,gender)`, `name_general` (attires) |

### 🌱 Seeders

| Fix | File | Detail |
|-----|------|--------|
| **AttireSeeder created** | `database/seeders/AttireSeeder.php` | 8 sample attires across Banaue, Kiangan, Mayoyao, Hungduan with full descriptions |
| **DatabaseSeeder wired** | `database/seeders/DatabaseSeeder.php` | Now calls `AdminUserSeeder`, `DanceSeeder`, `AttireSeeder` in order |

### 🎨 Frontend / Views

| Fix | File(s) | Detail |
|-----|---------|--------|
| **Admin mobile sidebar** | `admin.blade.php`, `app.css` | Hamburger button in topbar, `admin-sidebar-open` toggle, dark overlay on click-outside — admin usable on mobile |
| **Sortable column headers** | Both admin manage blade files | Clickable `<button>` headers with ↑ / ↓ / ↕ indicators |
| **Canonical tag** | `layouts/app.blade.php` | `<link rel="canonical" href="{{ url()->current() }}">` in `<head>` |
| **aria-current="page"** | `components/header.blade.php` | Active nav links receive `aria-current="page"` attribute |
| **Audit log in dashboard** | `dashboard.blade.php`, `Dashboard.php` | Recent Activity card now shows last 8 audit log entries with action-color dots |
| **Visitor search bar** | `explore-dances.blade.php` | Inline search input above category pills on the dances page |
| **Visitor pagination** | `explore-dances.blade.php` | Pagination links shown below the dance grid |

---

## Current Status: All Resolved

### ✅ Resolved
- Storage leak on image update
- Missing admin filter hooks (municipality, gender)
- Sortable admin tables
- YouTube Shorts / embed URL support
- Dance + Attire SoftDeletes
- AuditLog — create/update/delete tracked
- Toast notifications on admin actions
- SecurityHeaders middleware
- localStorage try/catch
- AttireSeeder (8 sample attires)
- DatabaseSeeder auto-runs all seeders
- Admin mobile responsive layout
- Canonical tag on public pages
- aria-current on nav links
- Visitor dances search + pagination
- Dashboard shows real audit activity

### ⏳ Intentionally Deferred
- `APP_DEBUG=false` — set this to `false` before production deploy
- Login rate limiting (`throttle:5,1`) — login is a placeholder; add when finalizing auth
- Admin password change (`admin1234`) — update before going live

---

## Architecture Overview (Current)

```
app/
├── Http/Middleware/
│   ├── AdminMiddleware.php       ← protects /admin/* routes
│   └── SecurityHeaders.php       ← security response headers (new)
├── Livewire/
│   ├── Auth/Login.php
│   ├── Admin/
│   │   ├── Dashboard.php         ← shows AuditLog recent activity
│   │   ├── Dances/ManageDances.php  ← full CRUD + sort + audit + toast
│   │   └── Attires/ManageAttires.php ← full CRUD + sort + audit + toast
│   ├── Home.php
│   ├── ExploreDances.php         ← search + paginate(12)
│   └── ExploreAttires.php
├── Models/
│   ├── Dance.php     ← SoftDeletes, embed_url, scopeByCategory
│   ├── Attire.php    ← SoftDeletes, scopeByMunicipality, scopeByGender
│   ├── AuditLog.php  ← static record() helper (new)
│   └── User.php
database/
├── migrations/  (10 total — all run)
└── seeders/
    ├── DatabaseSeeder.php    ← calls Admin + Dance + Attire seeders
    ├── AdminUserSeeder.php
    ├── DanceSeeder.php       ← 8 Ifugao dances
    └── AttireSeeder.php      ← 8 sample attires (new)
```

---

## CRUD Patterns Implemented (from audit)

| Pattern | Status | How |
|---------|--------|-----|
| **1. Server-side Search** | ✅ | `wire:model.live.debounce.300ms` → `->when($search, ...)` — admin + visitor |
| **2. Server-side Filtering** | ✅ | Category, municipality, gender filters — all server-side |
| **3. Server-side Sorting** | ✅ | `$sortBy` / `$sortDir` toggles + `->orderBy()` — admin tables |
| **4. Optimistic-style feedback** | ✅ | Toast dispatched before re-render; `wire:loading.attr="disabled"` on submit |
| **5. Debounced Search** | ✅ | `debounce.300ms` on search inputs |
| **6. Error Handling** | ✅ | Livewire validation errors shown inline; toast on success |
| **7. Pagination** | ✅ | Admin: `paginate(10)` — Visitor dances: `paginate(12)` |
| **8. Soft Deletes (data safety)** | ✅ | `SoftDeletes` on Dance + Attire — records recoverable via Tinker |

---

*End of audit — Heri-THREADS project · 2026-04-26*

---

## UI pass — 2026-05-19 (carousel, labels, profile header, modal scale)

| Change | Files |
|--------|--------|
| Replaced home bento grid with auto-advancing hero carousel (arrows, dots, edge fades) | `home.blade.php`, `app.css`, `Home.php` (`carouselMunicipalities`) |
| Removed redundant hero CTAs (Explore Dances / Explore Attires) — links remain in nav | `home.blade.php` |
| Admin dances: column **Dance Title**, removed **Category** table column; filter default **Dance Categories**; form label **Dance Title** | `manage-dances.blade.php` |
| Visitor dance modal ~**75vh** min height for readability | `app.css` (`.vis-modal-*`) |
| Municipality attire detail: **cover band + overlapping avatar + name** (Facebook-style) | `explore-attires.blade.php`, `app.css` |

Reference style: [Dribbble SaaS hero carousel](https://share.google/gHJra30dOAHdv6ulM) (via Google Images).
