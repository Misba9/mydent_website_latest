# MYDENT 2.0 – Security Audit

**Audit mode:** Phase 1 read-only  
**Date:** 2026-11-19  
**Standards referenced:** OWASP ASVS / Top 10 (informal mapping)  
**Secrets policy:** Actual `.env` credentials are **not** reproduced here.

---

## 1. Security posture summary

| Category | Rating | Notes |
|----------|--------|-------|
| Authentication | Weak–Moderate | Breeze flows OK; password-change mass assignment Critical |
| Authorization / IDOR | Weak | Broken helpers, role casing, public payment routes |
| Payments | Weak | Webhook stub, weak idempotency, CSRF exceptions |
| Injection | Critical | Livewire `whereRaw` interpolation; public SQL import |
| Data exposure | High | Production SQL dumps in repo; verbose payment logs |
| Session / CSRF | Moderate | Framework CSRF on; dangerous exceptions for gateways |
| Upload / files | Moderate | Original filenames to public disk on some CMS paths |
| Transport | OK (code) | `URL::forceScheme('https')` in production |
| Logging / ops | Weak | HTTP migrate; log viewers; sync queue |

**Overall:** **Not secure for premium production** until Critical/High items below are remediated and retested.

---

## 2. Critical findings

### SEC-001 — Remote SQL execution via public ecommerce page
| | |
|--|--|
| **Module** | Front / Database |
| **Description** | Unauthenticated `GET /ecom` may execute `mydent_database_production_import.sql` through `DB::unprepared` when no products exist. Errors swallowed. |
| **Severity** | Critical |
| **Status** | CONFIRMED |
| **Affected files** | `FrontController@ecom`, SQL dump in project root |
| **Root cause** | Auto-import convenience on public route |
| **Recommended solution** | Delete code path; seed via CLI; remove dumps from deploy artifacts |
| **Testing** | Empty DB + `/ecom` → zero SQL side effects |
| **OWASP** | A03 Injection / A04 Insecure Design |

### SEC-002 — Privilege escalation on password change
| | |
|--|--|
| **Module** | Auth |
| **Description** | `$user->update($request->all())` with fillable `type`, `status`, `email_verified_at`. |
| **Severity** | Critical |
| **Status** | CONFIRMED |
| **Affected files** | `UserController@changePassword`, `User` model, `UpdateChangePasswordRequest` |
| **Root cause** | Mass assignment using unfiltered request |
| **Recommended solution** | Update password field only from `validated()` |
| **Testing** | Extra fields `type=1` ignored |
| **OWASP** | A01 Broken Access Control |

### SEC-003 — SQL injection in Livewire table search
| | |
|--|--|
| **Module** | Admin Livewire |
| **Description** | User search string interpolated into `whereRaw` in 16 components. |
| **Severity** | Critical |
| **Status** | CONFIRMED |
| **Affected files** | `app/Http/Livewire/*Table.php` (16 files) |
| **Root cause** | Unbound raw SQL |
| **Recommended solution** | Parameter binding / Query Builder `where like` |
| **Testing** | Malicious search payloads harmless; bindings present |
| **OWASP** | A03 Injection |

### SEC-004 — Authorization helper is existence-only
| | |
|--|--|
| **Module** | AuthZ |
| **Description** | `canAccessRecord()` returns true if ID exists — used for prescriptions. |
| **Severity** | Critical |
| **Status** | CONFIRMED |
| **Affected files** | `helpers.php`, `PrescriptionController` |
| **Root cause** | Misimplemented access check |
| **Recommended solution** | Policies with ownership |
| **Testing** | Cross-tenant record access denied |
| **OWASP** | A01 Broken Access Control |

### SEC-005 — Migrations force weak known passwords
| | |
|--|--|
| **Module** | Database |
| **Description** | Migration sets `admin@mydent.in` / doctor / patient passwords to `123456` (also by user id). |
| **Severity** | Critical |
| **Status** | CONFIRMED |
| **Affected files** | `database/migrations/2026_08_18_000004_ensure_test_user_credentials.php` |
| **Root cause** | Test credentials shipped as migration |
| **Recommended solution** | No-op future runs; **rotate production passwords immediately** if this ran; forbid password mutations in migrations |
| **Testing** | Credential rotation + login failure with `123456` on prod |
| **OWASP** | A07 Identification & Authentication Failures |

---

## 3. High findings

### SEC-006 — Public / weakly protected payment status mutation
| | |
|--|--|
| **Module** | Payments |
| **Description** | `PUT transaction-status` outside auth middleware; non-doctor authenticated users can mark paid. |
| **Severity** | High |
| **Status** | CONFIRMED |
| **Affected files** | `web.php`, `TransactionController@changeTransactionStatus` |
| **Solution** | Auth + role + ownership |
| **Testing** | Patient denied; admin/doctor allowed |
| **OWASP** | A01 |

### SEC-007 — Public manual payment transaction creation
| | |
|--|--|
| **Module** | Payments |
| **Description** | `GET manually-payment` creates transactions without auth. |
| **Severity** | High |
| **Status** | CONFIRMED |
| **Affected files** | `AppointmentController@manuallyPayment` |
| **Solution** | Authenticate; POST; ownership |
| **Testing** | Guest denied |
| **OWASP** | A01 |

### SEC-008 — Razorpay webhook without signature verification
| | |
|--|--|
| **Module** | Payments |
| **Description** | Stub handler; GET route; no HMAC of webhook secret. |
| **Severity** | High |
| **Status** | CONFIRMED |
| **Affected files** | `RazorpayController@paymentSuccessWebHook` |
| **Solution** | Verify signature; POST; idempotent processing |
| **Testing** | Unsigned rejected |
| **OWASP** | A08 Software & Data Integrity Failures |

### SEC-009 — Duplicate payment acceptance (no idempotency)
| | |
|--|--|
| **Module** | Payments |
| **Description** | Replay of success callbacks can insert multiple transactions / re-mark paid. |
| **Severity** | High |
| **Status** | CONFIRMED |
| **Affected files** | All gateway success handlers; `transactions` table |
| **Solution** | Unique `transaction_id`; early return if PAID |
| **Testing** | Replay → one row |
| **OWASP** | A04 |

### SEC-010 — Role casing + invalid `owner_id` skips doctor Rx checks
| | |
|--|--|
| **Module** | Prescriptions |
| **Description** | `hasRole('Doctor')` never true (role is `doctor`); `owner_id` not on User. |
| **Severity** | High |
| **Status** | CONFIRMED |
| **Affected files** | `PrescriptionController`, `PrescriptionRepository`, `getLoggedinDoctor()` |
| **Solution** | Fix role strings and doctor id resolution |
| **Testing** | Ownership checks execute |
| **OWASP** | A01 |

### SEC-011 — Appointment cancel without auth / signed expiry
| | |
|--|--|
| **Module** | Appointments |
| **Description** | Public cancel by patient_id + encrypted unique id. |
| **Severity** | High |
| **Status** | CONFIRMED |
| **Affected files** | `AppointmentController@cancelAppointment` |
| **Solution** | Signed temporary URLs |
| **Testing** | Expired/tampered fail |
| **OWASP** | A01 |

### SEC-012 — Notification mark-read without ownership
| | |
|--|--|
| **Module** | Notifications |
| **Description** | Routes to mark notifications read lack robust user scoping (public/auth gap per controller review). |
| **Severity** | High |
| **Status** | CONFIRMED (route/controller inspection) |
| **Affected files** | `NotificationController`, `web.php` |
| **Solution** | Auth + `where user_id` |
| **Testing** | Cross-user mark-read denied |
| **OWASP** | A01 |

### SEC-013 — Patient booking may accept client `patient_id`
| | |
|--|--|
| **Module** | Appointments |
| **Description** | Patient appointment create authorize-true patterns; repository trusts input patient_id (IDOR risk). |
| **Severity** | High |
| **Status** | CONFIRMED gap in trust boundary |
| **Affected files** | `routes/patient.php`, appointment requests/repository |
| **Solution** | Force `patient_id` from session; ignore client value |
| **Testing** | Patient A cannot book as Patient B |
| **OWASP** | A01 |

### SEC-014 — PayTM callback re-logins user from order payload
| | |
|--|--|
| **Module** | Payments / Session |
| **Description** | `Auth::loginUsingId($loginUserId)` derived from order id string after callback. |
| **Severity** | High |
| **Status** | CONFIRMED |
| **Affected files** | `PayTMController.php`, CSRF except `paytm-callback` |
| **Solution** | Never establish session from payment callback user id; restore prior session or require re-auth |
| **Testing** | Callback does not escalate to another account |
| **OWASP** | A07 |

### SEC-015 — HTTP Artisan migrate/seed
| | |
|--|--|
| **Module** | Operations |
| **Description** | `/upgrade/database` runs `migrate --force` for users with `manage_admin_dashboard`. |
| **Severity** | High |
| **Status** | CONFIRMED |
| **Affected files** | `routes/upgrade.php` |
| **Solution** | Remove from web; deploy pipeline only |
| **Testing** | 404 in production |
| **OWASP** | A05 Security Misconfiguration |

### SEC-016 — Sensitive SQL dumps and archives in repository
| | |
|--|--|
| **Module** | Data protection |
| **Description** | Production SQL dumps and large zip artifacts present in project root (may contain PII). |
| **Severity** | High |
| **Status** | CONFIRMED (files present; content not dumped in this report) |
| **Affected files** | `*.sql`, `dist.zip`, related archives |
| **Solution** | Remove from git history if committed; store offline encrypted; add to `.gitignore` |
| **Testing** | Clean deploy artifact scan |
| **OWASP** | A02 Cryptographic Failures / A01 |

---

## 4. Medium findings

| ID | Title | Status | Notes |
|----|-------|--------|-------|
| SEC-017 | CSRF exceptions for Razorpay/PayTM browser POSTs | CONFIRMED | Compensate with signatures + idempotency |
| SEC-018 | XSS middleware strip_tags + CMS bypass | CONFIRMED | Prefer output escaping + HTML Purifier for CMS |
| SEC-019 | CheckUserStatus after `$next` | CONFIRMED | Disabled users may execute once |
| SEC-020 | Impersonation can target privileged users | POTENTIAL | Restrict targets; audit log |
| SEC-021 | Uploads use original filenames under public | CONFIRMED pattern | Randomize names; validate MIME |
| SEC-022 | Payment success logs full payloads | CONFIRMED | Redact PAN-like / signature fields |
| SEC-023 | Log viewer routes for admins | CONFIRMED | Restrict tightly; avoid in shared hosting without IP allowlist |
| SEC-024 | Checkout amount not verified vs Razorpay entity | CONFIRMED | See BUG-010 |
| SEC-025 | Appointment Razorpay uses `!=` for signature | CONFIRMED | Timing-safe compare |
| SEC-026 | Front booking auto-verifies email | CONFIRMED | Skip email verification on public signup-via-book |
| SEC-027 | Rate limiting only on some auth email routes | CONFIRMED | Add throttle to booking, login, payment init |

---

## 5. Low findings

| ID | Title | Status |
|----|-------|--------|
| SEC-028 | Debug `print_r` in PayPal error paths | CONFIRMED |
| SEC-029 | `.env.example` may contain realistic credential shapes | CONFIRMED pattern — use placeholders |
| SEC-030 | debugbar in composer `require` | CONFIRMED |
| SEC-031 | APP_DEBUG true in local `.env` | Expected locally; ensure false in prod |

---

## 6. Access control map (IDOR)

| Resource | Patient | Doctor | Guest | Notes |
|----------|---------|--------|-------|-------|
| Appointment show | Scoped | Scoped | N/A | OK |
| Appointment store patient_id | Risk | Admin | Front book creates user | Force server-side id |
| Appointment PDF | Gap | Gap | N/A | Add policy |
| Cancel appointment link | N/A | N/A | Possible | Signed URL needed |
| Visit show | Scoped | Via doctor routes | N/A | Patient OK |
| Prescription show/edit/PDF | Broken helper | Checks never fire | N/A | Critical |
| Transaction show | Scoped | Partial | N/A | Status change weak |
| Notifications | Gap | Gap | Gap | Fix ownership |
| Manual payment | N/A | N/A | Open | Critical/High |
| Ecom SQL import | N/A | N/A | Open | Critical |

---

## 7. Payment security checklist

| Control | Appointment Razorpay | Ecom Razorpay | Other gateways |
|---------|----------------------|---------------|----------------|
| Server-side signature | Partial (`!=`) | Yes (`hash_equals`) | Varies |
| Amount verification | Appointment row | **Missing** | Partial |
| Webhook authenticity | **Missing** | N/A | Weak/missing |
| Idempotency | **Missing** | **Missing** | **Missing** |
| CSRF on browser return | Excepted | Protected | Excepted (PayTM) |
| Authz on initiate | Weak | Public cart OK | Weak |
| No session login from callback | OK | OK | **PayTM fails** |

---

## 8. Secrets & configuration hygiene

**Do not commit or paste production secrets into tickets.**

Checklist for operators (manual verification required):
- [ ] `APP_DEBUG=false` on Hostinger
- [ ] `APP_ENV=production`
- [ ] Strong unique `APP_KEY`
- [ ] Razorpay live keys only on prod; webhook secret configured
- [ ] Mail credentials rotated if ever committed
- [ ] Demo passwords (`123456`) disabled on production
- [ ] SQL dumps not web-accessible under `public/`
- [ ] `storage/` and `.env` not downloadable

---

## 9. Recommended security remediation order

1. SEC-001, SEC-002, SEC-003, SEC-004, SEC-005 (immediate)  
2. SEC-006–SEC-016 (same sprint as Phase 2 backend)  
3. SEC-017–SEC-027 (hardening)  
4. Penetration test / paid Razorpay sandbox replay after fixes  
5. Only then Phase 3 UI work  

---

## 10. Testing requirements (security)

- PHPUnit feature tests for each Critical/High authz fix  
- Payment replay tests with fake signed payloads (never real charges in CI)  
- Livewire search injection tests  
- Manual checklist: role matrix (admin/doctor/patient/guest) × sensitive endpoints  
- Optional: Playwright authz smoke after Phase 5  

---

*End of security audit. No credentials disclosed.*
