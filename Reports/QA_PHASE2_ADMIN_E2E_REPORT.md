# MYDENT QA – Phase 2 Admin Panel E2E Report

**Date:** 2026-11-22  
**Scope:** Admin panel module inventory, CRUD persistence, Playwright Chromium E2E  
**Environment:** Local Laravel (`127.0.0.1:8000`) + SQLite QA DB (no production MySQL)

---

## Verdict

**Phase 2 Admin E2E: PASS**

| Check | Result |
|-------|--------|
| Playwright `e2e/tests/admin/` (Chromium) | **69/69 passed** |
| HTTP CRUD script `scripts/qa_admin_crud_http.php` | **PASS** (login, specialization, FAQ, patient, 22-path module matrix) |
| Module HTTP matrix (authenticated) | All **200** (no 500s in matrix) |

---

## Coverage

- Admin login → dashboard
- Module inventory loads (doctors, patients, appointments, services, specializations, staff, roles, products, orders, transactions, FAQs, settings, CMS, enquiries, subscribers, visits, medicines, banners, videos, articles, holidays, clinic schedules, geo/currency, profile)
- CRUD: patient create, FAQ create + list persistence, specialization modal AJAX create, product create submit, settings form, orders list, profile edit
- Modal-only specialization create URL not a 500

---

## Bugs found and fixed (root cause)

| ID | Severity | Finding | Fix |
|----|----------|---------|-----|
| **QA-2026-015** | High | FAQ index always showed “No FAQs found” — controller never passed `$faqs` | `FaqController@index` now loads `Faq::latest()->get()` into the view |
| **QA-2026-016** | High | Livewire table “Add …” header buttons never rendered — `showButtonOnHeader` / `buttonComponent` unused by Rappasoft toolbar | `LivewireTableComponent::booted()` maps existing button views into `toolbar-right-start` |
| **QA-2026-017** | High | Specialization Add button id `#addSpecializationBtn` did not match JS listener `#createSpecialization` | Button id aligned to `#createSpecialization` |
| **QA-2026-018** | Blocker (local) | Broken `public/js/pages.js` Mix output threw on ESM `import` (turbo/flatpickr) → `listenClick` undefined → admin modals dead | Restored working `pages.js` from git; source switched to `require()` for turbo + flatpickr l10n |
| **QA-2026-019** | High | Wiring header buttons surfaced missing views (`doctor_holiday.components.add_button`, FAQ/testimonial/doctor-appointment add buttons) → `/admin/holidays` 500 | Added missing add-button blades; `booted()` only wires when `view()->exists()` |

---

## Notes / residual risk

- **Do not rebuild `pages.js` with the previous ESM `import` turbo/flatpickr sources** without Mix ESM support; sources are now `require()`-based for a safe future rebuild.
- Settings Contact No UI can show both “Valid Number” and “Invalid Number” labels (display quirk); form still loads.
- Deep write coverage beyond patient/FAQ/specialization/product is still thin (tracker QA-2026-010 partially addressed).
- Razorpay sandbox keys still blank (QA-2026-003) — online payments out of Phase 2 scope.

---

## Commands used

```bash
php scripts/qa_admin_crud_http.php
PLAYWRIGHT_BROWSERS_PATH="$HOME/Library/Caches/ms-playwright" \
  npm run test:e2e -- --project=chromium e2e/tests/admin/
```
