# MYDENT 2.0 – Implementation Roadmap

**Date:** 2026-11-19  
**Status:** Phase 1 complete — **awaiting approval before any code changes**  
**Constraint:** Preserve business logic, production data, and route names where possible. Never delete existing migrations to “fix” errors. Never deploy untested changes.

---

## 1. Phase status

| Phase | Name | Status |
|-------|------|--------|
| 1 | Read-only complete audit | **DONE** — documents delivered |
| 2 | Backend stabilization | **IN PROGRESS** — Sprint 2.0 complete; 2.2/2.3 partial |
| 3 | Premium UI/UX redesign | After Phase 2 Critical/High |
| 4 | Advanced functionality | Selective, value-based |
| 5 | Testing (PHPUnit + Playwright) | Parallel from Phase 2 onward |
| 6 | Production readiness | Before Hostinger deploy |

### Phase 2 progress log (2026-11-19)

Completed:
- Sprint 2.0 Critical security (ecom SQL import removed, password mass-assign fixed, Livewire SQLi parameterized, `canAccessRecord` ownership, credential/SQL migrations no-op’d, payment/notification routes auth-wrapped)
- Prescription/Appointment policies + PDF authorize
- Doctor role casing + `doctor->id` scoping
- `CheckUserStatus` checks before controller
- Razorpay appointment: `hash_equals`, idempotent mark-paid, signed webhook (POST), onboard authz
- Checkout: frozen cart/amount verification, stock decrement, no double JSON encode on COD/online
- Appointment slot lock + unique-id generator fix + additive index migration
- PHPUnit: `SecurityCriticalFixesTest`, `CanAccessRecordTest`, `CheckoutAmountFreezeTest`, `RazorpayWebhookTest`

Remaining Phase 2:
- Broader route-name dedupe across doctor/patient AJAX helpers
- PayPal/Paystack/Authorize idempotency parity with Razorpay/PayTM/Stripe
- Patient prescription write route lockdown (read-only)

Recently completed (continued session):
- PayTM: removed `loginUsingId`; idempotent TXN handling; ownership on initiate
- Stripe: require `payment_status === paid`; duplicate session guard
- Cancel links: temporary signed URLs (+ Crypt fallback); mail templates updated
- Patient booking: force `patient_id` + server charges in request/controller
- Cart duplicate named routes removed; upgrade HTTP routes disabled in production
- AppointmentTable payment-status filter property fix

### Delivered audit artifacts

1. `MYDENT_TECHNICAL_AUDIT.md`  
2. `MYDENT_BUG_REPORT.md`  
3. `MYDENT_SECURITY_AUDIT.md`  
4. `MYDENT_UI_UX_AUDIT.md`  
5. `MYDENT_MISSING_FEATURES.md`  
6. `MYDENT_IMPLEMENTATION_ROADMAP.md` (this file)

---

## 2. Guiding principles

1. Inspect → fix module-by-module → test → document.  
2. Do not replace working clinical business logic casually.  
3. Do not remove features; harden them.  
4. No fake payment success in tests or code.  
5. No credential or patient data in commits/docs.  
6. Additive migrations only; no data wipes.  
7. Keep Livewire; no unnecessary SPA rewrite.  
8. UI redesign must not paper over broken auth/payments.

---

## 3. Phase 2 – Backend stabilization (proposed sprints)

### Sprint 2.0 – Emergency security (1–3 days)

| Order | IDs | Work |
|------|-----|------|
| 1 | BUG-001 / SEC-001 | Remove `/ecom` SQL import; quarantine SQL dumps from deploy |
| 2 | BUG-002 / SEC-002 | Password change updates password only |
| 3 | BUG-004 / SEC-003 | Parameterize all Livewire `whereRaw` searches |
| 4 | BUG-005 / SEC-004 | Replace `canAccessRecord` with policies (start: Prescription, Appointment) |
| 5 | BUG-003 / SEC-005 | No-op dangerous credential migrations going forward; **ops: rotate prod passwords** |
| 6 | BUG-006, BUG-007 | Auth-wrap transaction-status + manually-payment |

**Exit criteria:** Critical SEC items closed; PHPUnit for password mass-assign + Livewire search + public payment routes.

### Sprint 2.1 – AuthZ & roles (2–4 days)

| Order | IDs | Work |
|------|-----|------|
| 1 | BUG-011 / SEC-010 | Normalize `doctor` role casing; fix `doctor->id` scoping |
| 2 | BUG-012 | PDF / prescription IDOR policies; patient Rx read-only |
| 3 | BUG-013 / SEC-011 | Signed cancel URLs |
| 4 | SEC-012, SEC-013 | Notifications ownership; force patient_id from session |
| 5 | BUG-018 | Fix `CheckUserStatus` order |
| 6 | BUG-017 / SEC-015 | Remove/disable HTTP upgrade migrate in production |

**Exit criteria:** Role matrix tests green (admin/doctor/patient/guest × sensitive routes).

### Sprint 2.2 – Appointments & scheduling (3–5 days)

| Order | IDs | Work |
|------|-----|------|
| 1 | BUG-015 / MISS-001 | Transactional slot lock + validation on store/frontSideStore |
| 2 | BUG-022 | Consistent free-consult payment semantics |
| 3 | BUG-025 | Fix unique id generator recursion |
| 4 | DB indexes | Additive indexes on appointments (doctor_id, date, status), transactions.transaction_id unique |
| 5 | Doctor availability | Harden holiday/gap edge cases; timezone review |

**Exit criteria:** Parallel booking test; booking + cancel + status change feature tests.

### Sprint 2.3 – Payments (Razorpay first) (4–6 days)

| Order | IDs | Work |
|------|-----|------|
| 1 | BUG-008 / SEC-008 | Real Razorpay webhook (signature + idempotent) |
| 2 | BUG-009 / SEC-009 | Unique transaction_id + already-paid guards (all gateways) |
| 3 | BUG-019 | `hash_equals` on appointment Razorpay |
| 4 | BUG-010 / SEC-024 | Freeze checkout amount; verify vs Razorpay payment |
| 5 | SEC-014 | Remove PayTM `loginUsingId` session establishment |
| 6 | BUG-026 | Narrow CSRF exceptions where safe |

**Exit criteria:** Sandbox Razorpay success/fail/replay/webhook tests; no duplicate transactions.

### Sprint 2.4 – Ecommerce & CMS (3–5 days)

| Order | IDs | Work |
|------|-----|------|
| 1 | BUG-016 / MISS-005 | `order_items`, stock decrement, oversell protection |
| 2 | BUG-020 | Fix products cast double-encode |
| 3 | BUG-014 | Deduplicate route names (`cart.add`, shared names) |
| 4 | BUG-021, BUG-023, BUG-024 | Table filter, issue phone, articles index |
| 5 | Orders admin | Reliable status updates + payment_status display |

**Exit criteria:** COD + Razorpay checkout tests; stock integrity test.

### Sprint 2.5 – Clinical records polish (2–4 days)

| Order | Work |
|------|------|
| 1 | Visit notes/problems/observations authorization review |
| 2 | Prescription medicine relation fixes (MODEL-001) if blocking |
| 3 | Patient visit history query performance |
| 4 | Medicine bill / inventory consistency checks |

**Exit criteria:** Doctor cannot access other doctor’s clinical artifacts; patient sees own only.

---

## 4. Phase 3 – Premium UI/UX redesign

**Start only when Sprint 2.0–2.3 exit criteria met.**

### 3.A Design system foundation

- CSS variables: forest green, mint, warm white, neutrals, danger/success  
- Typography pair (non-generic)  
- Blade components: `x-button`, `x-input`, `x-alert`, `x-section`, `x-empty-state`  
- Motion tokens (subtle)  
- Do **not** introduce React/Vue

### 3.B Public website (order)

1. Layout shell (header/footer)  
2. Homepage (single composition hero)  
3. About, Services, Doctors  
4. Appointment booking  
5. Contact, FAQs  
6. Blog / articles  
7. Shop, Cart, Checkout  

### 3.C Portals

1. Admin overview + navigation chrome  
2. Appointment / doctor / patient / staff management views  
3. Doctor today board + calendar + visit/Rx screens  
4. Patient timeline dashboard + booking/history/payments/profile  

### 3.D Constraints

- Preserve route names  
- Preserve Livewire table behavior  
- Mobile-first QA each template  
- No hero card clutter; brand-first marketing  

---

## 5. Phase 4 – Advanced functionality (selective)

Implement only after value review:

| Priority | Feature | Depends on |
|----------|---------|------------|
| P1 | Appointment reminders (email + WhatsApp/SMS) | Queues + scheduler on Hostinger |
| P1 | Scheduling locks / waitlist | Sprint 2.2 |
| P1 | Inventory alerts + order tracking | Sprint 2.4 |
| P2 | Patient reschedule | AuthZ + scheduling |
| P2 | Revenue / occupancy reports | Clean payments data |
| P2 | Payment reconciliation UI | Idempotent transactions |
| P3 | Aligner treatment plans | Clinical stakeholder input |
| P3 | Odontogram / consents / document vault | Doctor feedback |

Skip until justified: insurance HIS, multi-location, native apps, extra gateways.

---

## 6. Phase 5 – Testing strategy

### PHPUnit (expand continuously from Phase 2)

- Auth: admin/doctor/patient login + role gates  
- IDOR suites for appointments, visits, prescriptions, PDFs  
- Appointment create/cancel/double-book  
- Razorpay verify success/fail/replay/webhook (mocked API)  
- Checkout COD + Razorpay amount mismatch  
- Stock decrement  
- Upgrade routes remain forbidden to non-admin  

### Playwright (Phase 5 practical set)

- Public: home → services → book appointment smoke  
- Patient: login → appointments → profile  
- Doctor: login → today appointments  
- Admin: login → appointments filter  
- Shop: add to cart → checkout page (sandbox payment optional)  
- Responsive snapshots: 1440 / 768 / 390  

### Manual

- Browser matrix (Chrome, Safari, Firefox)  
- Payment sandbox with real Razorpay test keys (human-operated)  
- Security regression checklist from `MYDENT_SECURITY_AUDIT.md`  

**Rule:** Do not fabricate pass results. CI must run what we claim.

---

## 7. Phase 6 – Production readiness checklist

### Before deploy

- [ ] `APP_DEBUG=false`, `APP_ENV=production`  
- [ ] HTTPS verified; `FORCE_HTTPS` / TrustProxies correct for Hostinger  
- [ ] DB backup taken and restore tested  
- [ ] Storage symlink + permissions  
- [ ] Razorpay **live** keys + webhook URL + secret  
- [ ] Mail deliverability (SPF/DKIM)  
- [ ] Queue worker / `schedule:run` cron if reminders enabled  
- [ ] Logs writable; no PII spam  
- [ ] Sitemap `/sitemap.xml` OK  
- [ ] Migrations reviewed (additive only); rollback plan written  
- [ ] `deploy.sh` updated: backup step, `npm run production` if assets changed, health ping  
- [ ] Demo passwords rotated; SQL dumps not on server  
- [ ] Smoke test on staging (or maintenance window plan)  

### Deploy rules

- Never deploy Phase 3-only cosmetic builds over known Critical security holes  
- Prefer maintenance window for payment-sensitive releases  
- Keep previous release artifact for rollback  

---

## 8. Effort estimate (indicative)

| Phase | Calendar (1 senior full-stack) |
|-------|--------------------------------|
| Phase 2 | 2–4 weeks |
| Phase 3 | 2–3 weeks |
| Phase 4 (P1 only) | 1–2 weeks |
| Phase 5 hardening | Ongoing; 3–5 days concentrated |
| Phase 6 | 2–3 days |

Estimates assume Hostinger access, Razorpay test keys, and no major scope expansion.

---

## 9. Approval gate

**Please confirm before Phase 2 starts:**

1. Acknowledge Critical findings (especially SEC-001–005) and plan production password rotation if credential migration ran.  
2. Approve Sprint 2.0 order (or request reprioritization).  
3. Confirm Razorpay is the primary gateway to harden first (others: idempotency only until needed).  
4. Confirm UI redesign waits until Sprint 2.3 payments exit criteria.  
5. Confirm no production deploy until Phase 5 smoke + Phase 6 checklist.

Reply with **“Approve Phase 2”** (optionally with priority tweaks) to begin implementation. No source files will be modified until then (audit markdown excepted).

---

## 10. Risk register (top)

| Risk | Mitigation |
|------|------------|
| Production already has weak passwords from migration | Immediate rotation + force reset |
| Payment changes break live checkout | Feature flags / maintenance; sandbox first |
| Route rename breaks Ziggy | Prefer keep names; fix duplicates carefully |
| UI redesign churn | Design system first; page-by-page PRs |
| Hostinger cron/queue limits | Document fallback sync + email-only reminders |

---

*End of roadmap. Phase 1 complete.*
