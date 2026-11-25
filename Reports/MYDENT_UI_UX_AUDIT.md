# MYDENT 2.0 – UI/UX Audit

**Audit mode:** Phase 1 read-only (no visual redesign applied)  
**Date:** 2026-11-19  
**Target direction (Phase 3):** Premium dental — forest green / mint accents, warm white, elegant typography, consistent spacing, accessible contrast  

Evidence combines Blade/layout inspection. Runtime browser matrix (desktop/tablet/mobile) is **recommended in Phase 5** and marked POTENTIAL where not browser-verified in this pass.

---

## 1. UX summary

| Surface | Current state | Premium gap |
|---------|---------------|-------------|
| Public marketing | Feature-rich mega homepage; mixed brand/stock imagery | Not a coherent design system; cluttered first viewport |
| Booking | Functional multi-step style forms | Visual inconsistency vs marketing pages |
| Shop / cart / checkout | Separate front templates | Basic retail UX; weak trust signals |
| Admin | InfyOm/Metronic-like Bootstrap admin | Dense tables; dated chrome; Poppins default |
| Doctor portal | Reuses admin patterns | Limited “daily clinic” focus |
| Patient portal | Functional lists | Low emotional trust / clarity for care journey |

**Verdict:** Brand promise (premium clear aligners / dental) is partially present in copy and SEO, but **UI is template-heterogeneous**. Phase 3 should introduce a shared design system **after** Phase 2 stabilizes flows that buttons invoke.

---

## 2. Design system gaps

| Token | Today | Target |
|-------|-------|--------|
| Color | Mixed blues/greens/template defaults + dark mode admin | Forest green + mint + warm white |
| Type | Poppins (admin); marketing theme fonts vary | Expressive but professional pair (display + body) — avoid Inter/Roboto defaults |
| Spacing | Inconsistent section padding across Blades | 8-point scale |
| Components | Few reusable Blade components for marketing | Buttons, inputs, cards (interaction only), alerts, empty states |
| Motion | Limited / mixed libraries | 2–3 intentional motions (hero, CTA, scroll reveal) |
| Imagery | Mix of local `public/images/*` and third-party/CDN | Owned dental photography; no hotlinked clinic assets |

---

## 3. Public website findings

### UI-001 — Homepage is a mega-template (maintainability + UX)
| | |
|--|--|
| **Module** | Public home |
| **Description** | `medicals/index.blade.php` ~1386 lines with many sections, hardcoded `https://mydent.in/ecom`, CDN/stock imagery. First viewport likely overloaded vs “brand + one headline + one CTA + one visual” premium rule. |
| **Severity** | High |
| **Status** | CONFIRMED (structure) |
| **Affected files** | `resources/views/fronts/medicals/index.blade.php` |
| **Root cause** | Single-file marketing accumulation |
| **Recommended solution** | Split into Blade components/sections; one composition hero; remove hardcoded absolute URLs; use `route()` / `url()` |
| **Testing** | Visual QA desktop/mobile; Lighthouse; all CTAs resolve |

### UI-002 — Hardcoded / placeholder contact on issue pages
| | |
|--|--|
| **Module** | Issues / Contact |
| **Description** | Issue show uses placeholder phone/WhatsApp `1234567890` while homepage uses real clinic number. |
| **Severity** | Medium |
| **Status** | CONFIRMED |
| **Affected files** | `resources/views/fronts/issues/show.blade.php` vs home |
| **Root cause** | Incomplete content wiring |
| **Recommended solution** | Single source from Settings helpers |
| **Testing** | All contact CTAs dial/WhatsApp correct number |

### UI-003 — Third-party / stock hotlinked images
| | |
|--|--|
| **Module** | About / Services |
| **Description** | About/services templates reference external clinic/stock URLs — licensing, privacy, and reliability risk; brand inconsistency. |
| **Severity** | Medium |
| **Status** | CONFIRMED |
| **Affected files** | `medical_about_us.blade.php`, `medical_services.blade.php` |
| **Root cause** | Rapid content assembly |
| **Recommended solution** | Host optimized WebP/AVIF in `public/images`; consistent photography style |
| **Testing** | No external image hosts in production HTML |

### UI-004 — Layout/asset duplication (CDN + local)
| | |
|--|--|
| **Module** | Front assets |
| **Description** | Mix of local Mix builds and CDN scripts/styles; Turbo/Turbolinks opt-outs increase complexity. |
| **Severity** | Medium |
| **Status** | POTENTIAL (performance/UX impact runtime-dependent) |
| **Affected files** | `fronts/layouts/app.blade.php`, `layouts/app.blade.php`, `webpack.mix.js` |
| **Recommended solution** | One asset pipeline; defer non-critical JS; self-host fonts |
| **Testing** | Network waterfalls; no double Bootstrap |

### UI-005 — SEO present but content templates inconsistent
| | |
|--|--|
| **Module** | SEO |
| **Description** | Layout has solid meta, OG, Twitter, JSON-LD Dentist schema. Inner pages may not always set `@yield` meta uniquely. |
| **Severity** | Low–Medium |
| **Status** | CONFIRMED baseline / POTENTIAL per-page gaps |
| **Affected files** | `fronts/layouts/app.blade.php`, page Blades |
| **Recommended solution** | Per-route meta + canonical; blog/article unique descriptions |
| **Testing** | View-source on key URLs; Search Console |

### UI-006 — Shop / cart / checkout trust UX
| | |
|--|--|
| **Module** | Ecommerce |
| **Description** | Functional cart/checkout exist; order model stores product names only — receipt UX limited; no inventory messaging. |
| **Severity** | Medium |
| **Status** | CONFIRMED (functional UX gap) |
| **Affected files** | `fronts/cart/*`, `fronts/checkout/*`, `Order` model |
| **Recommended solution** | Line-item receipts, order status timeline, stock availability messaging |
| **Testing** | Purchase happy path + empty cart states |

### UI-007 — Blog / articles presentation
| | |
|--|--|
| **Module** | CMS |
| **Description** | Basic listing/detail; CMS maturity (draft, reading time, related posts) weak for premium content marketing. |
| **Severity** | Low |
| **Status** | GAP |
| **Recommended solution** | Editorial layout aligned with brand; related CTAs to booking |
| **Testing** | Content hierarchy / mobile readability |

---

## 4. Admin dashboard findings

### UI-008 — Dated admin chrome vs premium brand
| | |
|--|--|
| **Module** | Admin |
| **Description** | Classic Bootstrap admin (Poppins, dense tables, impersonate columns, dark mode). Functional for ops; not “premium dental” visually. |
| **Severity** | Medium |
| **Status** | CONFIRMED |
| **Affected files** | `resources/views/layouts/*`, Livewire table views |
| **Recommended solution** | Soft redesign: sidebar, KPI cards, filter bars; keep Livewire tables |
| **Testing** | Task success rates for book/check-in/payment |

### UI-009 — Analytics shallow for overview
| | |
|--|--|
| **Module** | Dashboard |
| **Description** | Counts for today/upcoming appointments; limited revenue/funnel visualization for clinic leadership. |
| **Severity** | Medium |
| **Status** | GAP |
| **Recommended solution** | ApexCharts already depended — use for revenue, occupancy, channel mix |
| **Testing** | Dashboard numbers match DB fixtures |

### UI-010 — Filter / table UX bugs
| | |
|--|--|
| **Module** | Appointments table |
| **Description** | Payment status filter property mismatch (see BUG-021) → users may think UI is broken. |
| **Severity** | Medium |
| **Status** | CONFIRMED |
| **Affected files** | `AppointmentTable.php` |
| **Recommended solution** | Fix binding; clear empty states |
| **Testing** | Filter combinations |

---

## 5. Doctor portal findings

### UI-011 — Daily clinic workflow not primary
| | |
|--|--|
| **Module** | Doctor |
| **Description** | Dashboard + calendar + appointment lists exist; “today’s chairside” flow (check-in → notes → Rx → checkout) is fragmented across screens. |
| **Severity** | Medium |
| **Status** | GAP |
| **Recommended solution** | Today board with sequential actions; quick visit note entry |
| **Testing** | Time-on-task for complete visit |

### UI-012 — Clinical notes UX depends on broken authz
| | |
|--|--|
| **Module** | Prescriptions / Visits |
| **Description** | Even if UI works for admin, doctor ownership bugs undermine trust when wrong records appear or checks silently skip. |
| **Severity** | High (functional trust) |
| **Status** | CONFIRMED (backend) impacting UX |
| **Recommended solution** | Fix Phase 2 authz before UI polish |
| **Testing** | Doctor only sees own patients |

---

## 6. Patient portal findings

### UI-013 — Care journey clarity
| | |
|--|--|
| **Module** | Patient |
| **Description** | Separate areas for appointments, visits, payments, reviews. Missing unified timeline (“your treatment journey”). |
| **Severity** | Medium |
| **Status** | GAP |
| **Recommended solution** | Timeline home: upcoming → past visits → Rx → payments |
| **Testing** | First-login comprehension test |

### UI-014 — Booking feedback / free consult semantics
| | |
|--|--|
| **Module** | Booking |
| **Description** | Front booking forced free/manual; messaging vs payment status PAID=0 may confuse patients and staff in portals. |
| **Severity** | Medium |
| **Status** | CONFIRMED |
| **Recommended solution** | Clear “Consultation request received” states; align badges |
| **Testing** | Patient sees accurate payment/status labels |

---

## 7. Responsiveness & accessibility

| ID | Finding | Severity | Status |
|----|---------|----------|--------|
| UI-015 | Viewport meta present on front/admin | Low (positive) | CONFIRMED |
| UI-016 | Mobile nav / mega home sections — risk of clutter and horizontal overflow | Medium | POTENTIAL — verify in Phase 5 |
| UI-017 | Admin tables on mobile — Livewire tables often require horizontal scroll | Medium | POTENTIAL |
| UI-018 | Contrast / focus states not systematized | Medium | GAP |
| UI-019 | Form labels / error announcements inconsistent (flash vs inline) | Medium | GAP |
| UI-020 | Motion / autoplay video sections may lack reduced-motion handling | Low | POTENTIAL |

**Accessibility target (Phase 3+):** WCAG 2.2 AA for public + patient portals; keyboardable booking and checkout.

---

## 8. Interaction & broken-control inventory

| Control | Observation | Status |
|---------|-------------|--------|
| Login / role redirects | Present via helpers | CONFIRMED pattern |
| Front appointment book | API returns free booking | CONFIRMED |
| Cart add | Duplicate route names — risk of wrong handler | CONFIRMED risk |
| Checkout Razorpay | Stronger than appointment path | CONFIRMED |
| Issue WhatsApp CTA | Placeholder number | CONFIRMED broken content |
| Articles index | Method `ind` — works if wired but confusing | CONFIRMED |
| Payment status admin toggle | Authz weak — UI may allow wrong role | CONFIRMED |

Do **not** assume every button is broken without runtime proof. Items above are code-evidenced.

---

## 9. Phase 3 redesign principles (approved direction)

1. **One composition** per first viewport on marketing pages.  
2. **Brand first** — MyDent as hero-level signal.  
3. **Forest green + mint + warm white**; avoid purple-gradient AI clichés and flat single-color pages.  
4. **Full-bleed hero** for landing; no card soup in hero.  
5. **Reusable Blade components**; keep Livewire; keep route names.  
6. **No new SPA framework** unless explicitly approved later.  
7. Redesign **after** Critical/High backend fixes so polished UI is not glued to broken auth/payments.

---

## 10. UI/UX testing requirements

- Desktop 1440 / tablet 768 / mobile 390 screenshots for all redesigned templates  
- Keyboard-only booking + checkout  
- Color contrast checks on primary CTAs  
- Broken-link crawl of header/footer  
- Playwright smoke: home → book → login portals  

---

*End of UI/UX audit.*
