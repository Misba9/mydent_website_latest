# MYDENT 2.0 – Prioritized Bug Report

**Audit mode:** Phase 1 read-only  
**Date:** 2026-11-19  
**Legend:** CONFIRMED = proven in source · POTENTIAL = needs runtime proof for full impact

For every issue: ID · Module · Description · Severity · Affected files · Root cause · Solution · Testing · Status

---

## Critical

### BUG-001 — Public `/ecom` can execute production SQL dump
| | |
|--|--|
| **Module** | Ecommerce / Database |
| **Description** | When `Product::count() === 0`, the public ecom page reads `mydent_database_production_import.sql` and runs statements via `DB::unprepared`, swallowing all errors. |
| **Severity** | Critical |
| **Status** | CONFIRMED |
| **Affected files** | `app/Http/Controllers/Front/FrontController.php` (ecom), `mydent_database_production_import.sql` |
| **Root cause** | Dev convenience auto-seed left on a public route. |
| **Recommended solution** | Remove HTTP SQL import entirely. Seed via Artisan only. Delete or gitignore production dumps from web-accessible project root. |
| **Testing** | Empty products table + hit `/ecom` → no SQL executed; products remain empty unless seeded via CLI. |

### BUG-002 — Privilege escalation via change-password mass assignment
| | |
|--|--|
| **Module** | Authentication |
| **Description** | `changePassword` does `$user->update($request->all())`. `User` fillable includes `type`, `status`, `email_verified_at`. Extra POST fields are not stripped by FormRequest rules. |
| **Severity** | Critical |
| **Status** | CONFIRMED |
| **Affected files** | `app/Http/Controllers/UserController.php`, `app/Http/Requests/UpdateChangePasswordRequest.php`, `app/Models/User.php` |
| **Root cause** | Updating with `$request->all()` instead of validated password fields only. |
| **Recommended solution** | `$user->update(['password' => Hash::make($request->validated()['new_password'])]);` Remove `type`/`status`/`email_verified_at` from mass-assignable where possible; use explicit admin flows. |
| **Testing** | Authenticated patient POSTs password change with `type=1` → type unchanged; password updates. |

### BUG-003 — Migrations reset credentials and import dumps
| | |
|--|--|
| **Module** | Database / Migrations |
| **Description** | Migrations under `2026_08_18_*` import SQL dumps and force admin/doctor/patient passwords to `123456` (also match by id 1/2/3). |
| **Severity** | Critical |
| **Status** | CONFIRMED |
| **Affected files** | `database/migrations/2026_08_18_000001_sync_all_mydent_backup_data.php`, `..._000002_force_import_all_mydent_data.php`, `..._000004_ensure_test_user_credentials.php` |
| **Root cause** | Data fixes incorrectly implemented as migrations. |
| **Recommended solution** | Do **not** delete migrations that already ran in prod. Mark as no-op going forward; rotate all affected passwords; move seeding to documented Artisan commands. Never put password resets in new migrations. |
| **Testing** | Fresh migrate on empty DB does not overwrite passwords of arbitrary users; production password rotation verified. |

### BUG-004 — Livewire search SQL injection (16 tables)
| | |
|--|--|
| **Module** | Admin / Livewire |
| **Description** | Searchable columns use `whereRaw("... like '%{$direction}%'")` interpolating user search input. |
| **Severity** | Critical |
| **Status** | CONFIRMED |
| **Affected files** | `DoctorTable`, `AppointmentTable`, `PatientTable`, `StaffTable`, `VisitTable`, `TransactionTable`, and 10 other Livewire tables under `app/Http/Livewire/` |
| **Root cause** | String interpolation into raw SQL. |
| **Recommended solution** | Use parameter binding: `whereRaw('... like ?', ['%'.$term.'%'])` or `where('col', 'like', '%'.$term.'%')`. Centralize in base table component. |
| **Testing** | Search `%' OR 1=1 --` returns normal empty/filtered results; no SQL error; query log shows bindings. |

### BUG-005 — `canAccessRecord` does not authorize
| | |
|--|--|
| **Module** | Authorization |
| **Description** | Helper returns true whenever a row with that ID exists. Used for prescription show/edit. |
| **Severity** | Critical |
| **Status** | CONFIRMED |
| **Affected files** | `app/helpers.php`, `app/Http/Controllers/PrescriptionController.php` |
| **Root cause** | Existence check misused as access control. |
| **Recommended solution** | Replace with Laravel Policies (PrescriptionPolicy, AppointmentPolicy, etc.) enforcing patient/doctor/admin scopes. |
| **Testing** | Patient A cannot view Patient B prescription by ID; doctor only own patients. |

---

## High

### BUG-006 — Transaction status change route lacks auth middleware
| | |
|--|--|
| **Module** | Payments / Appointments |
| **Description** | `PUT transaction-status` is registered outside auth groups. Controller only special-cases doctors; other authenticated roles (e.g. patient) can mark appointments paid. Guests may error on null user — still a broken trust boundary. |
| **Severity** | High |
| **Status** | CONFIRMED |
| **Affected files** | `routes/web.php`, `app/Http/Controllers/TransactionController.php` |
| **Root cause** | Privileged endpoint not wrapped in `auth` + role middleware. |
| **Recommended solution** | Move under admin/doctor middleware; require permission; verify ownership; CSRF retained. |
| **Testing** | Guest → 401/redirect; patient → 403; authorized admin/doctor → success once. |

### BUG-007 — Public manual payment creates transactions
| | |
|--|--|
| **Module** | Payments |
| **Description** | `GET /manually-payment` creates a pending `Transaction` for any `appointmentId` without auth. |
| **Severity** | High |
| **Status** | CONFIRMED |
| **Affected files** | `routes/web.php`, `AppointmentController@manuallyPayment` |
| **Root cause** | Payment initiation treated as public. |
| **Recommended solution** | Auth + ownership; prefer POST; rate-limit; idempotent if pending exists. |
| **Testing** | Guest cannot create; patient only for own appointment; duplicate call does not duplicate rows. |

### BUG-008 — Razorpay appointment webhook stub
| | |
|--|--|
| **Module** | Payments |
| **Description** | Webhook logs payload, empty success branch, returns false; route is GET; no signature check. |
| **Severity** | High |
| **Status** | CONFIRMED |
| **Affected files** | `RazorpayController.php`, `routes/web.php` |
| **Root cause** | Incomplete implementation. |
| **Recommended solution** | POST-only; verify `X-Razorpay-Signature`; idempotent mark-paid; reject unsigned. |
| **Testing** | Unsigned → 401; signed capture once → one transaction; replay → no duplicate. |

### BUG-009 — Payment transactions not idempotent
| | |
|--|--|
| **Module** | Payments |
| **Description** | Success handlers create `Transaction` without unique constraint / firstOrCreate across Razorpay, Stripe, PayPal, Paystack, PayTM, Authorize. |
| **Severity** | High |
| **Status** | CONFIRMED |
| **Affected files** | Payment controllers + `transactions` migration |
| **Root cause** | Missing idempotency design. |
| **Recommended solution** | Unique index on `transaction_id`; skip if appointment already PAID; DB transaction. |
| **Testing** | Replay gateway success twice → single row. |

### BUG-010 — Checkout amount not bound to Razorpay order
| | |
|--|--|
| **Module** | Ecommerce |
| **Description** | `amount_paise` stored in session but verify recomputes from mutable cart and never compares to frozen amount or Razorpay payment entity amount. |
| **Severity** | High |
| **Status** | CONFIRMED |
| **Affected files** | `CheckoutController.php` |
| **Root cause** | Incomplete verification pipeline. |
| **Recommended solution** | Freeze line items + amount; compare session + Razorpay API amount/currency/order_id; reject mismatch. |
| **Testing** | Change cart after create-order then verify → 422. |

### BUG-011 — Prescription doctor authz never executes
| | |
|--|--|
| **Module** | Prescriptions |
| **Description** | Code checks `hasRole('Doctor')` but DB role is `doctor`. Uses `getLogInUser()->owner_id` which does not exist on User (should be `doctor->id`). |
| **Severity** | High |
| **Status** | CONFIRMED |
| **Affected files** | `PrescriptionController.php`, `PrescriptionRepository.php`, `helpers.php` (`getLoggedinDoctor`) |
| **Root cause** | Copied hospital template patterns / case mismatch. |
| **Recommended solution** | Standardize role string `doctor`; scope by `auth()->user()->doctor->id`. |
| **Testing** | Doctor ownership branch fires; cross-doctor access denied. |

### BUG-012 — Appointment / prescription PDF IDOR
| | |
|--|--|
| **Module** | Appointments / Prescriptions |
| **Description** | PDF endpoints lack consistent ownership checks; patient routes allow prescription create/edit. |
| **Severity** | High |
| **Status** | CONFIRMED (authorization gaps in controller code) |
| **Affected files** | `AppointmentController` PDF methods, `PrescriptionController`, `routes/patient.php` |
| **Root cause** | Missing policies. |
| **Recommended solution** | Policies on download; patients read-only for Rx; doctors/admin write. |
| **Testing** | Cross-patient PDF → 403. |

### BUG-013 — Unauthenticated appointment cancel link
| | |
|--|--|
| **Module** | Appointments |
| **Description** | `cancelAppointment/{patient_id}/{appointment_unique_id}` decrypts and cancels without auth, expiry, or signature beyond reversible Crypt payload. |
| **Severity** | High |
| **Status** | CONFIRMED |
| **Affected files** | `AppointmentController@cancelAppointment`, `routes/web.php` |
| **Root cause** | Email cancel link without signed temporary URL. |
| **Recommended solution** | Laravel signed URLs with expiry; record token use; confirm page. |
| **Testing** | Tampered/expired link fails; valid link cancels once. |

### BUG-014 — Duplicate named routes (`cart.add`, shared names)
| | |
|--|--|
| **Module** | Routing |
| **Description** | `cart.add` registered twice; many identical names across web/doctor/patient files. Last registration wins — Ziggy/JS may call wrong endpoint. |
| **Severity** | High |
| **Status** | CONFIRMED |
| **Affected files** | `routes/web.php`, `routes/doctor.php`, `routes/patient.php` |
| **Root cause** | Copy-paste route files without name namespacing. |
| **Recommended solution** | Unique names (`doctors.appointments.*` already prefixed in places); remove duplicate cart definitions. |
| **Testing** | `route:list` shows unique names; front cart add hits CartController. |

### BUG-015 — No server-side double-booking prevention
| | |
|--|--|
| **Module** | Appointments |
| **Description** | Slots marked booked in `getDoctorSession`, but `store` / `frontSideStore` do not lock or re-validate slot uniqueness in DB transaction. |
| **Severity** | High |
| **Status** | CONFIRMED (absence of overlap check in repository store) |
| **Affected files** | `AppointmentRepository.php`, `DoctorSessionController.php` |
| **Root cause** | UI-only conflict handling. |
| **Recommended solution** | Re-check slot inside DB transaction with lock; unique constraint on (doctor_id, date, from_time, from_time_type) for active statuses. |
| **Testing** | Parallel booking same slot → one success, one validation error. |

### BUG-016 — Orders store names only; stock never decrements
| | |
|--|--|
| **Module** | Ecommerce |
| **Description** | Checkout saves `json_encode($productNames)` into cast `array` column (double-encode risk); product `quantity` unused at sale. |
| **Severity** | High |
| **Status** | CONFIRMED |
| **Affected files** | `CheckoutController.php`, `Order.php`, `Product.php` |
| **Root cause** | Incomplete ecommerce domain model. |
| **Recommended solution** | `order_items` table; pass array (not json_encode) to cast; decrement stock in transaction; reject oversell. |
| **Testing** | Order with qty 2 reduces stock by 2; oversell blocked. |

### BUG-017 — HTTP upgrade routes can force migrate
| | |
|--|--|
| **Module** | Operations |
| **Description** | Authenticated admin with `manage_admin_dashboard` can hit `/upgrade/database` to `migrate --force`. |
| **Severity** | High |
| **Status** | CONFIRMED (mitigated partially by `UpgradeRouteAuthorizationTest`) |
| **Affected files** | `routes/upgrade.php` |
| **Root cause** | Ops tooling exposed on web. |
| **Recommended solution** | Remove from web; CLI/deploy only; disable in production config. |
| **Testing** | Routes 404 in production; existing feature test still passes for non-admin denial. |

---

## Medium

### BUG-018 — CheckUserStatus runs action before blocking disabled users
| | |
|--|--|
| **Module** | Middleware |
| **Severity** | Medium |
| **Status** | CONFIRMED |
| **Affected files** | `app/Http/Middleware/CheckUserStatus.php` |
| **Root cause** | `$response = $next($request)` before status checks. |
| **Solution** | Validate status/email before `$next`. |
| **Testing** | Disabled user POST creates no side effects. |

### BUG-019 — Razorpay appointment signature uses `!=` not `hash_equals`
| | |
|--|--|
| **Module** | Payments |
| **Severity** | Medium |
| **Status** | CONFIRMED |
| **Affected files** | `RazorpayController.php` |
| **Solution** | Use `hash_equals`; fail closed with error flash. |
| **Testing** | Invalid signature never marks paid. |

### BUG-020 — Order `products` double JSON encoding
| | |
|--|--|
| **Module** | Ecommerce |
| **Severity** | Medium |
| **Status** | CONFIRMED |
| **Affected files** | `CheckoutController.php`, `Order.php` (`'products' => 'array'`) |
| **Solution** | Pass PHP array to `Order::create`. |
| **Testing** | Retrieved order products is array of names/items, not nested JSON string. |

### BUG-021 — Appointment payment-status Livewire filter property mismatch
| | |
|--|--|
| **Module** | Admin UI |
| **Severity** | Medium |
| **Status** | CONFIRMED (code inspection) |
| **Affected files** | `AppointmentTable.php` |
| **Solution** | Align filter property names with query usage. |
| **Testing** | Payment status filter returns correct rows. |

### BUG-022 — Front booking marks payment PAID with zero amount
| | |
|--|--|
| **Module** | Appointments |
| **Severity** | Medium |
| **Status** | CONFIRMED |
| **Affected files** | `AppointmentRepository@frontSideStore`, `AppointmentController@frontAppointmentBook` |
| **Description** | Front flow forces free/manual booking but repository sets `payment_type` PAID / payable 0 — reporting will overstate paid appointments. |
| **Solution** | Use consistent MANUAL/PENDING unpaid semantics for free consults. |
| **Testing** | Front book appears as unpaid/manual in admin reports. |

### BUG-023 — Issue page placeholder phone/WhatsApp
| | |
|--|--|
| **Module** | Public UI |
| **Severity** | Medium |
| **Status** | CONFIRMED |
| **Affected files** | `resources/views/fronts/issues/show.blade.php` |
| **Solution** | Use clinic settings contact helpers. |
| **Testing** | Issue page shows same phone as homepage. |

### BUG-024 — Articles index method named `ind`
| | |
|--|--|
| **Module** | CMS |
| **Severity** | Medium |
| **Status** | CONFIRMED |
| **Affected files** | `routes/web.php`, `Admin/ArticleController.php` |
| **Solution** | Rename to `index` (public) without breaking admin resource. |
| **Testing** | `/articles` returns 200. |

### BUG-025 — `generateAppointmentUniqueId` discards recursive return
| | |
|--|--|
| **Module** | Appointments |
| **Severity** | Medium |
| **Status** | CONFIRMED |
| **Affected files** | `app/Models/Appointment.php` |
| **Solution** | `return self::generateAppointmentUniqueId();` on collision. |
| **Testing** | Forced collision still yields unique id. |

### BUG-026 — CSRF exceptions on Razorpay/PayTM browser callbacks
| | |
|--|--|
| **Module** | Security / Payments |
| **Severity** | Medium |
| **Status** | CONFIRMED |
| **Affected files** | `VerifyCsrfToken.php` |
| **Solution** | Prefer webhooks; keep CSRF where possible; rely on signature + idempotency. |
| **Testing** | Forged POST without valid payment signature fails. |

---

## Low

### BUG-027 — PayPal controller debug output
| | |
|--|--|
| **Module** | Payments |
| **Severity** | Low |
| **Status** | CONFIRMED (per audit of PaypalController) |
| **Solution** | Remove `print_r` / dumps; log server-side only. |

### BUG-028 — Holiday controller filename typo
| | |
|--|--|
| **Module** | Code quality |
| **Severity** | Low |
| **Status** | CONFIRMED |
| **Affected files** | `HolidayContoller.php` |
| **Solution** | Rename class/file carefully with PSR-4; update imports. |

### BUG-029 — Composer package `version: 8.0.0` while Laravel 10
| | |
|--|--|
| **Module** | Tooling |
| **Severity** | Low |
| **Status** | CONFIRMED |
| **Solution** | Remove misleading version field. |

### BUG-030 — debugbar in production `require`
| | |
|--|--|
| **Module** | Dependencies |
| **Severity** | Low–Medium |
| **Status** | CONFIRMED |
| **Solution** | Move to `require-dev`; ensure disabled in prod. |

---

## Potential risks (not fully runtime-proven)

| ID | Severity | Description |
|----|----------|-------------|
| POT-001 | High | Shared Ziggy route names may break doctor/patient JS for `get-service`, transactions, etc. |
| POT-002 | Medium | Stale cart prices if admin changes product price after add |
| POT-003 | Medium | Authorize.net hardcoded SANDBOX mode in controller |
| POT-004 | Medium | Production SQL/password migrations may already have run on Hostinger — passwords may still be weak until rotated |
| POT-005 | Low | CDN outage breaks homepage sections relying on external images |

---

## Priority fix order

1. BUG-001, BUG-002, BUG-003, BUG-004, BUG-005  
2. BUG-006, BUG-007, BUG-008, BUG-009, BUG-010, BUG-011, BUG-012, BUG-013  
3. BUG-014, BUG-015, BUG-016, BUG-017  
4. Medium/Low backlog  

---

*End of bug report.*
