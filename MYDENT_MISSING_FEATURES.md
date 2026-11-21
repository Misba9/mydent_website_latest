# MYDENT 2.0 – Missing Functionality Report

**Audit mode:** Phase 1 read-only  
**Date:** 2026-11-19  
**Method:** Compare current codebase capabilities to a premium dental clinic management + marketing platform. Items here are **GAPS** (not bugs) unless noted.

Business-value filter applied: prefer features that protect revenue, reduce no-shows, improve clinical quality, or strengthen brand trust. Avoid feature bloat.

---

## 1. Capability scorecard

| Domain | Present | Partial | Missing |
|--------|---------|---------|---------|
| Marketing website | ✓ | Blog/CMS maturity | Design system |
| Appointments | ✓ | Slot conflict UI-only | Server lock, waitlist, reschedule UX |
| Clinical records | Visits/notes/Rx | Authz broken | Odontogram, treatment plans |
| Payments | Multi-gateway | Idempotency/webhooks | Reconciliation UI |
| Ecommerce | Cart/checkout | Stock, line items | Fulfillment tracking |
| Comms | In-app notifications, email hooks | | SMS/WhatsApp reminders |
| Analytics | Basic counts | | Revenue/occupancy cohorts |
| Compliance | Terms/privacy pages | | Consent forms, audit trail |
| Multi-location | | | Not supported |

---

## 2. High business-value gaps (Phase 4 candidates)

### MISS-001 — Server-enforced smart scheduling
| | |
|--|--|
| **Module** | Appointments |
| **Description** | Booked slots filtered in `getDoctorSession`, but store paths lack transactional overlap prevention, waitlist, or buffer rules beyond session gap. |
| **Severity** | High (ops) |
| **Status** | GAP (+ related BUG-015) |
| **Affected files** | `AppointmentRepository`, `DoctorSessionController` |
| **Root cause** | UI-centric scheduling |
| **Recommended solution** | Lock + unique active slot; optional waitlist; service-duration aware slots |
| **Testing** | Concurrent booking tests; holiday/gap edge cases |
| **Business value** | Prevents double-booking revenue loss and clinic chaos |

### MISS-002 — Automated appointment reminders (SMS / WhatsApp / email)
| | |
|--|--|
| **Module** | Communications |
| **Description** | Email notifications exist for some booking events; no first-class reminder scheduler (queues are `sync`; no reminder jobs catalogued). Floating WhatsApp chat ≠ reminders. |
| **Severity** | High |
| **Status** | GAP |
| **Recommended solution** | Queued jobs 24h/2h before appointment; WhatsApp Business or SMS provider; preference center |
| **Testing** | Fake queue asserts job payload; opt-out honored |
| **Business value** | Cuts no-shows — typically highest ROI clinic feature |

### MISS-003 — Patient self-service reschedule
| | |
|--|--|
| **Module** | Patient portal |
| **Description** | Cancel exists (with security issues); dedicated reschedule flow (pick new slot, keep history) not first-class. |
| **Severity** | Medium–High |
| **Status** | GAP |
| **Recommended solution** | Reschedule action with policy windows; audit trail |
| **Testing** | Reschedule within policy; outside policy blocked |
| **Business value** | Reduces front-desk load |

### MISS-004 — Payment reconciliation & receipts
| | |
|--|--|
| **Module** | Billing |
| **Description** | Transactions recorded per gateway without reconciliation dashboard, duplicate detection UI, or unified receipt center for ecommerce + appointments. |
| **Severity** | High |
| **Status** | GAP (payments also buggy) |
| **Recommended solution** | After idempotency fixes: reconciliation report, export, patient receipt PDF store |
| **Testing** | Matched vs unmatched gateway ids |
| **Business value** | Finance trust; dispute handling |

### MISS-005 — Ecommerce inventory & order tracking
| | |
|--|--|
| **Module** | Shop |
| **Description** | Product `quantity` exists but checkout does not decrement; orders store product **names** JSON; no shipment statuses beyond simple admin status. |
| **Severity** | High |
| **Status** | GAP (+ BUG-016) |
| **Recommended solution** | `order_items`, stock transactions, low-stock alerts, customer order tracking page |
| **Testing** | Oversell blocked; status emails |
| **Business value** | Aligners/products revenue integrity |

### MISS-006 — Treatment plans / aligner case tracking
| | |
|--|--|
| **Module** | Clinical / Ortho |
| **Description** | MyDent brand centers clear aligners, but clinical model is generic visits — no case stages, impressions, refinement tracking. |
| **Severity** | High (brand fit) |
| **Status** | GAP |
| **Recommended solution** | TreatmentPlan model linked to patient; stage timeline; patient-visible progress |
| **Testing** | Stage transitions; patient can view plan |
| **Business value** | Differentiates vs generic clinic script |

---

## 3. Clinical / premium dental gaps

| ID | Feature | Severity | Value notes |
|----|---------|----------|-------------|
| MISS-007 | Odontogram / dental charting | Medium | Specialty expectation |
| MISS-008 | Digital intake & consent forms | Medium | Compliance + desk efficiency |
| MISS-009 | Imaging / document vault (X-rays, consents, Rx PDFs) | Medium | Continuity of care |
| MISS-010 | Lab work orders | Low–Medium | Specialty workflows |
| MISS-011 | Insurance / claims | Low (market-dependent) | Evaluate India clinic need before build |
| MISS-012 | Multi-clinic / multi-location | Medium | Only if expansion planned |

---

## 4. Admin / analytics gaps

| ID | Feature | Severity | Notes |
|----|---------|----------|-------|
| MISS-013 | Revenue reports (gateway, service, doctor) | High | ApexCharts already available |
| MISS-014 | Utilization / occupancy by doctor | Medium | Uses sessions + appointments |
| MISS-015 | Funnel: visit site → book → show → pay | Medium | Needs event logging |
| MISS-016 | Staff role permissions UX | Medium | `staff` role exists in DB; little route usage found |
| MISS-017 | Audit log of clinical/payment changes | Medium | Security + disputes |

---

## 5. Marketing / CMS gaps

| ID | Feature | Severity | Notes |
|----|---------|----------|-------|
| MISS-018 | Draft/publish, slugs, scheduled posts | Medium | Blog/article maturity |
| MISS-019 | Per-page SEO manager in admin | Medium | Layout SEO exists; CMS control partial |
| MISS-020 | Case studies linked to real anonymized outcomes | Low | Content ops |
| MISS-021 | Review solicitation after visit | Low | Builds trust |

---

## 6. Platform / engineering gaps (enable the above)

| ID | Feature | Severity | Notes |
|----|---------|----------|-------|
| MISS-022 | Domain automated tests (booking, pay, ecom) | High | TEST-001 |
| MISS-023 | Playwright E2E suite | Medium | Phase 5 |
| MISS-024 | Async queues (Redis/database) in production | High | Required for reminders |
| MISS-025 | Cron / scheduler documentation on Hostinger | Medium | `schedule:run` |
| MISS-026 | Normalized order schema + indexes | High | DB-003/DB-004 |
| MISS-027 | Vite migration / Livewire upgrade | Low–Medium | Tech debt — not urgent vs security |

---

## 7. Explicitly out of scope (for now)

Do **not** implement unless business case approved:

- Full hospital HIS modules unrelated to dental  
- Native mobile apps  
- Unnecessary new frontend frameworks (React/Vue SPA rewrite)  
- Additional payment gateways before Razorpay hardening  
- AI chatbot diagnosis features  

---

## 8. Suggested Phase 4 priority (after Phase 2–3)

1. MISS-001 scheduling locks + MISS-002 reminders + MISS-024 queues  
2. MISS-005 inventory/orders + MISS-004 reconciliation  
3. MISS-003 reschedule + MISS-013 revenue reports  
4. MISS-006 aligner treatment plans (brand differentiator)  
5. Clinical extras (MISS-007–010) based on doctor feedback  

Each item should include: migration (additive only), UI, tests, and rollback notes — never destructive data migrations.

---

*End of missing features report.*
