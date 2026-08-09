<style>
/* ==========================================================================
   SORÉNZA — Filament Panel Theme (Admin + Seller)
   MOBILE-FIRST. Whole dashboard tuned for phones, then progressively
   enhanced for tablet & desktop.
   Loaded via panels::body.end render hook.
   ========================================================================== */

:root {
    --sorenza-gold-1: #8b6914;
    --sorenza-gold-2: #BBA14F;
    --sorenza-gold-3: #DBC584;
    --sorenza-gradient: linear-gradient(90deg, #8b6914 0%, #BBA14F 50%, #DBC584 100%);
    --sorenza-gradient-soft: linear-gradient(135deg, rgba(219,197,132,0.16), rgba(139,105,20,0.06));
}

/* ==========================================================================
   MOBILE-FIRST BASE (applies at every size, then desktop overrides below)
   ========================================================================== */

/* Never let anything cause horizontal page scroll on phones */
html, body.fi-body { max-width: 100vw; overflow-x: hidden; }
.fi-main-ctn, .fi-main, .fi-page { min-width: 0 !important; max-width: 100%; }

/* Long words / URLs / IDs wrap instead of pushing layout wider */
.fi-ta-cell, .fi-in-entry, .fi-fo-field-wrp, .fi-header-heading,
.fi-section-header-heading, .fi-badge, .fi-ta-text-item {
    min-width: 0;
    overflow-wrap: anywhere;
    word-break: break-word;
}

/* Page content padding — comfortable on phone */
.fi-main-ctn > .fi-main {
    padding-left: 0.75rem !important;
    padding-right: 0.75rem !important;
}
.fi-page { padding-top: 0.75rem !important; }

/* Page header — stacked by default, wraps on all screens */
.fi-page-header,
.fi-header {
    display: flex !important;
    flex-direction: column !important;
    align-items: stretch !important;
    gap: 0.75rem !important;
}
.fi-header-heading {
    font-size: 1.1rem !important;
    line-height: 1.3 !important;
    font-weight: 700 !important;
    letter-spacing: -0.005em;
}
.fi-header-subheading { font-size: 0.78rem !important; line-height: 1.35 !important; }
.fi-page-heading, .fi-title { font-size: 1.1rem !important; }

/* Header actions — full-width row that wraps */
.fi-page .fi-header-actions,
.fi-page .fi-ac {
    display: flex !important;
    flex-wrap: wrap !important;
    gap: 0.5rem !important;
}
.fi-page .fi-header-actions .fi-btn,
.fi-page .fi-ac .fi-btn {
    flex: 1 1 auto;
    justify-content: center;
    min-height: 44px;
}

/* Top bar — sticky on phone */
.fi-topbar {
    position: sticky;
    top: 0;
    z-index: 40;
    backdrop-filter: saturate(180%) blur(10px);
    -webkit-backdrop-filter: saturate(180%) blur(10px);
}

/* Sidebar drawer — bottom padding so iOS home indicator doesn't hide nav */
.fi-sidebar-nav {
    padding-bottom: 120px !important;
}
.fi-sidebar-item-button,
.fi-topbar-item .fi-icon-btn { min-height: 44px; }

/* Inputs — 16px prevents iOS zoom-on-focus, tap-friendly height */
.fi-input,
.fi-select-input,
.fi-textarea {
    font-size: 16px !important;
    min-height: 44px;
    border-radius: 0.65rem !important;
}
.fi-textarea { min-height: 100px; }

/* Multi-column form grids → single column by default */
.fi-fo-component-ctn.grid,
.fi-section-content.grid,
.fi-fo-grid {
    grid-template-columns: repeat(1, minmax(0, 1fr)) !important;
    gap: 0.75rem !important;
}

/* Section padding — tight but readable */
.fi-section-content-ctn,
.fi-in-section-content-ctn { padding: 0.75rem !important; }

/* Section headers */
.fi-section-header { padding: 0.75rem !important; }
.fi-section-header-heading { font-size: 0.92rem !important; font-weight: 700 !important; line-height: 1.3 !important; }
.fi-section-header-description { font-size: 0.75rem !important; line-height: 1.35 !important; }

/* Body text sizing kept tight on phone */
.fi-body { font-size: 0.875rem; }

/* Modals — near-fullscreen ONLY on phone; normal size otherwise */
@media (max-width: 640px) {
    .fi-modal-window { max-width: 96vw !important; margin: 0.5rem !important; }
    .fi-modal-footer,
    .fi-modal-footer-actions,
    .fi-modal .fi-ac {
        display: flex !important;
        flex-wrap: wrap !important;
        gap: 0.5rem !important;
    }
    .fi-modal-footer .fi-btn,
    .fi-modal-footer-actions .fi-btn,
    .fi-modal .fi-ac .fi-btn {
        flex: 1 1 auto !important;
        min-height: 46px !important;
        justify-content: center !important;
    }
}

/* Desktop / tablet — leave Filament modal footer natural, just ensure comfortable buttons */
@media (min-width: 641px) {
    .fi-modal-footer .fi-btn,
    .fi-modal-footer-actions .fi-btn,
    .fi-modal .fi-ac .fi-btn {
        min-width: 96px;
        padding-left: 1rem;
        padding-right: 1rem;
    }
}

/* Notifications — full width so they're always readable */
.fi-no-notifications {
    left: 0.5rem !important;
    right: 0.5rem !important;
    max-width: none !important;
}
.fi-no-notification { width: 100% !important; }

/* Stats overview widgets — single column on phone */
.fi-wi-stats-overview-stats-ctn {
    display: grid !important;
    grid-template-columns: repeat(1, minmax(0, 1fr)) !important;
    gap: 0.75rem !important;
}
.fi-wi-stats-overview-stat { padding: 0.85rem !important; }
.fi-wi-stats-overview-stat-value {
    font-size: 1.35rem !important;
    line-height: 1.15 !important;
    overflow-wrap: anywhere;
}
.fi-wi-stats-overview-stat-label {
    font-size: 0.72rem !important;
    letter-spacing: 0.08em;
}
.fi-wi-stats-overview-stat-description { font-size: 0.72rem !important; }
.fi-wi-chart { min-height: 220px; }
.fi-wi-widget { min-width: 0; }

/* Tabs scroll horizontally on phone */
.fi-tabs {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
}
.fi-tabs::-webkit-scrollbar { display: none; }
.fi-tabs-tab { white-space: nowrap; }

/* Form action row — full-width buttons on phones, normal on desktop */
.fi-form-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    padding-top: 0.75rem !important;
}
.fi-form-actions .fi-btn { flex: 1 1 140px; min-height: 42px; justify-content: center; }
@media (min-width: 768px) {
    .fi-form-actions { justify-content: flex-start; }
    .fi-form-actions .fi-btn { flex: 0 0 auto; }
}

/* Pagination wraps and centers */
.fi-ta-pagination-records-per-page-selector,
.fi-pagination {
    display: flex !important;
    flex-wrap: wrap !important;
    justify-content: center !important;
    gap: 0.5rem !important;
}

/* ==========================================================================
   MOBILE POLISH — brand, topbar, buttons, badges
   ========================================================================== */

@media (max-width: 767px) {

    /* Sorénza brand in top bar — fit next to the menu toggle */
    .sorenza-brand-wrapper { flex-wrap: nowrap !important; overflow: hidden; }
    .sorenza-text {
        font-size: 0.85rem !important;
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        max-width: 55vw;
    }
    .sorenza-logo { height: 1.4rem !important; }

    /* Topbar itself compact */
    .fi-topbar { padding: 0.4rem 0.6rem !important; }
    .fi-topbar-user-menu-trigger, .fi-topbar-item .fi-icon-btn { padding: 0.4rem !important; }

    /* Buttons: shrink text so labels don't push out of the card */
    .fi-btn { font-size: 0.82rem !important; padding: 0.5rem 0.85rem !important; }
    .fi-btn .fi-btn-label { line-height: 1.15; }
    .fi-icon-btn { width: 38px; height: 38px; }

    /* Badges tighter */
    .fi-badge {
        font-size: 0.68rem !important;
        padding: 0.15rem 0.5rem !important;
    }

    /* Header sub-content margin trim */
    .fi-header-heading { margin: 0 !important; }

    /* Breadcrumbs wrap */
    .fi-breadcrumbs { flex-wrap: wrap; font-size: 0.72rem !important; }
}

/* ==========================================================================
   TABLES — the real dashboard content — CARD LAYOUT ON MOBILE
   Filament's <table> is unusable on phones. We reshape each row into a card.
   ========================================================================== */

@media (max-width: 767px) {

    .fi-ta-ctn { overflow: visible !important; border: 0 !important; box-shadow: none !important; background: transparent !important; }

    /* Hide the horizontal scroll container's inner sizing */
    .fi-ta-table { display: block !important; min-width: 0 !important; width: 100% !important; }
    .fi-ta-header { display: none !important; } /* thead hidden — labels come from cell::before */

    /* Body & rows become blocks */
    .fi-ta-table > tbody,
    .fi-ta-table > tbody > tr {
        display: block !important;
        width: 100% !important;
    }

    /* Each row = a card */
    .fi-ta-row {
        margin-bottom: 0.75rem;
        border: 1px solid rgba(187,161,79,0.20) !important;
        border-radius: 0.9rem !important;
        background: rgba(255,255,255,0.92) !important;
        box-shadow: 0 1px 2px rgba(17,24,39,0.04), 0 8px 22px -14px rgba(139,105,20,0.20) !important;
        padding: 0.5rem 0.75rem !important;
        overflow: hidden;
    }
    .dark .fi-ta-row {
        background: rgba(22,22,25,0.92) !important;
        border-color: rgba(187,161,79,0.18) !important;
        box-shadow: 0 1px 2px rgba(0,0,0,0.35), 0 8px 22px -14px rgba(0,0,0,0.55) !important;
        color: #e5e7eb;
    }

    /* Cells become key/value rows */
    .fi-ta-cell {
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        gap: 0.5rem !important;
        padding: 0.4rem 0 !important;
        border: 0 !important;
        border-bottom: 1px dashed rgba(0,0,0,0.06) !important;
        width: 100% !important;
        min-width: 0 !important;
        font-size: 0.82rem !important;
        text-align: right;
    }
    .dark .fi-ta-cell { border-bottom-color: rgba(255,255,255,0.06) !important; }
    .fi-ta-cell:last-of-type { border-bottom: 0 !important; }

    /* Insert the column label from data-label attribute (see JS below) */
    .fi-ta-cell::before {
        content: attr(data-label);
        display: inline-block;
        font-size: 0.62rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--sorenza-gold-1);
        opacity: 0.9;
        flex: 0 0 auto;
        max-width: 45%;
        text-align: left;
        line-height: 1.2;
    }
    .dark .fi-ta-cell::before { color: var(--sorenza-gold-3); }

    /* Cell content shrinks + wraps so nothing pushes the row wider than the card */
    .fi-ta-cell > * {
        min-width: 0;
        max-width: 100%;
        overflow-wrap: anywhere;
    }
    .fi-ta-text-item, .fi-ta-cell .fi-badge {
        font-size: 0.8rem !important;
        line-height: 1.35;
    }

    /* Images / avatars inside cells stay small */
    .fi-ta-cell img, .fi-ta-image-item img {
        max-height: 40px !important;
        max-width: 40px !important;
        object-fit: cover;
        border-radius: 0.5rem;
    }

    /* Row actions cell — bump to full row, tap-friendly */
    .fi-ta-actions-cell,
    td:has(.fi-ta-actions) {
        justify-content: flex-end !important;
    }
    .fi-ta-actions {
        display: flex !important;
        flex-wrap: wrap !important;
        gap: 0.4rem !important;
        justify-content: flex-end !important;
        width: 100%;
    }
    .fi-ta-actions .fi-btn,
    .fi-ta-actions .fi-icon-btn {
        min-height: 40px;
        min-width: 40px;
        border-radius: 999px !important;
    }
    /* Show text next to icons when there's room */
    .fi-ta-actions .fi-btn-label { display: inline; }

    /* Selection checkbox cell full width row */
    .fi-ta-record-checkbox-cell { padding: 0.4rem 0 !important; border-bottom: 1px dashed rgba(0,0,0,0.06) !important; }

    /* Table toolbar (search / filters) — stacked */
    .fi-ta-header-ctn,
    .fi-ta-header-toolbar {
        display: flex !important;
        flex-direction: column !important;
        gap: 0.5rem !important;
        padding: 0.5rem !important;
    }
    .fi-ta-search-field { width: 100% !important; }
    .fi-ta-search-field .fi-input {
        min-height: 44px;
        font-size: 16px;
        border-radius: 999px !important;
    }
    .fi-ta-filters-form-ctn,
    .fi-ta-filters-form {
        grid-template-columns: repeat(1, minmax(0, 1fr)) !important;
    }

    /* Empty state — nice on phone */
    .fi-ta-empty-state { padding: 2rem 1rem !important; text-align: center; }

    /* Bulk actions button stays visible */
    .fi-ta-selection-indicator {
        position: sticky;
        top: 3.5rem;
        z-index: 30;
        border-radius: 0.75rem;
        padding: 0.75rem !important;
    }
}

/* Slightly larger phones / small tablets — 2-column stats but keep card table */
@media (min-width: 640px) and (max-width: 767px) {
    .fi-wi-stats-overview-stats-ctn {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    }
}

/* ==========================================================================
   TABLET (>= 768px) — restore native table but keep touch niceties
   ========================================================================== */
@media (min-width: 768px) {

    .fi-main-ctn > .fi-main { padding-left: 1.25rem !important; padding-right: 1.25rem !important; }
    .fi-page { padding-top: 1.25rem !important; }

    .fi-page-header,
    .fi-header {
        flex-direction: row !important;
        align-items: center !important;
    }
    .fi-header-heading { font-size: 1.6rem !important; }

    .fi-ta-ctn {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        border-radius: 1rem;
    }
    .fi-ta-table { min-width: 640px; }

    .fi-wi-stats-overview-stats-ctn {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    }

    .fi-fo-component-ctn.grid,
    .fi-section-content.grid,
    .fi-fo-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    }

    .fi-form-actions {
        position: static;
        margin: 0;
        padding: 1rem 0 0 0 !important;
        border-top: 0;
        background: transparent;
    }
}

/* ==========================================================================
   DESKTOP (>= 1024px) — full layout
   ========================================================================== */
@media (min-width: 1024px) {

    .fi-topbar { position: static; }
    .fi-main-ctn > .fi-main { padding-left: 2rem !important; padding-right: 2rem !important; }
    .fi-page { padding-top: 1.5rem !important; }
    .fi-header-heading { font-size: 1.9rem !important; }

    .fi-wi-stats-overview-stats-ctn {
        grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
    }

    /* Restore Filament's original form grid — let :columns() decide */
    .fi-fo-component-ctn.grid,
    .fi-section-content.grid,
    .fi-fo-grid { grid-template-columns: revert !important; gap: 1.25rem !important; }
}

/* ==========================================================================
   VISUAL POLISH — Sorénza brand accents (all screens)
   ========================================================================== */

body.fi-body {
    background:
        radial-gradient(1000px 400px at 100% -200px, rgba(219,197,132,0.10), transparent 60%),
        radial-gradient(800px 500px at -200px 100%, rgba(255,220,180,0.10), transparent 60%),
        var(--fi-color-gray-50, #f9fafb);
}
.dark body.fi-body {
    background:
        radial-gradient(900px 380px at 100% -180px, rgba(187,161,79,0.09), transparent 60%),
        radial-gradient(700px 460px at -180px 100%, rgba(139,105,20,0.08), transparent 60%),
        #0b0e12 !important;
    color-scheme: dark;
}
/* Force main content backdrop dark (Filament sometimes leaves it lighter) */
.dark .fi-main-ctn,
.dark .fi-main { background: transparent !important; }

/* Cards / sections */
.fi-section,
.fi-fo-section,
.fi-in-section,
.fi-wi-widget > .fi-section {
    border: 1px solid rgba(187,161,79,0.18) !important;
    box-shadow: 0 1px 2px rgba(17,24,39,0.04), 0 6px 20px -12px rgba(139,105,20,0.18) !important;
    border-radius: 1rem !important;
    background: rgba(255,255,255,0.9);
    backdrop-filter: saturate(160%) blur(6px);
}
.dark .fi-section,
.dark .fi-fo-section,
.dark .fi-in-section,
.dark .fi-wi-widget > .fi-section {
    background: rgba(22,22,25,0.92) !important;
    border-color: rgba(187,161,79,0.18) !important;
    box-shadow: 0 1px 2px rgba(0,0,0,0.35), 0 10px 30px -18px rgba(0,0,0,0.55) !important;
}
.dark .fi-section-header { border-bottom-color: rgba(187,161,79,0.14); }
.dark .fi-section-header-heading { color: #f3f4f6; }
.dark .fi-section-header-description { color: #9ca3af; }

/* Top bar */
.fi-topbar {
    background: rgba(255,255,255,0.85) !important;
    border-bottom: 1px solid rgba(187,161,79,0.18) !important;
}
.dark .fi-topbar {
    background: rgba(14,16,20,0.92) !important;
    border-bottom: 1px solid rgba(187,161,79,0.18) !important;
    box-shadow: 0 6px 24px -18px rgba(0,0,0,0.7);
}
.dark .fi-topbar .fi-icon-btn { color: #d1d5db !important; }
.dark .fi-topbar .fi-icon-btn:hover { background: rgba(187,161,79,0.10) !important; color: var(--sorenza-gold-3) !important; }

/* Primary buttons — subtle gold; only visible where Filament already picks primary */
.fi-btn { transition: all 180ms ease; }
.fi-btn-color-primary:hover,
.fi-ac-btn.fi-color-primary:hover,
.fi-btn.fi-color-primary:hover {
    filter: brightness(1.03) saturate(1.02);
}
.fi-header-actions .fi-btn { border-radius: 0.6rem; }

/* Table header cells (desktop) */
.fi-ta-header-cell {
    font-size: 0.7rem !important;
    letter-spacing: 0.14em !important;
    text-transform: uppercase !important;
    color: #6b7280 !important;
    font-weight: 600 !important;
}
.dark .fi-ta-header-cell { color: #9ca3af !important; }
.dark .fi-ta-header-ctn,
.dark .fi-ta-header-toolbar { background: rgba(255,255,255,0.02); }
.fi-ta-row:hover {
    background: rgba(219,197,132,0.06) !important;
}
.dark .fi-ta-row:hover { background: rgba(219,197,132,0.05) !important; }
.dark .fi-ta-row td { border-color: rgba(255,255,255,0.05) !important; }
.dark .fi-ta-empty-state { color: #9ca3af; }
.fi-badge {
    border-radius: 999px !important;
    padding: 0.2rem 0.6rem !important;
    font-weight: 600;
}

/* Input focus */
.fi-input:focus,
.fi-select-input:focus,
.fi-textarea:focus {
    border-color: var(--sorenza-gold-2) !important;
    box-shadow: 0 0 0 3px rgba(187,161,79,0.20) !important;
}
/* Dark-mode inputs */
.dark .fi-input,
.dark .fi-select-input,
.dark .fi-textarea {
    background: rgba(255,255,255,0.03) !important;
    border-color: rgba(255,255,255,0.10) !important;
    color: #e5e7eb !important;
}
.dark .fi-input::placeholder,
.dark .fi-textarea::placeholder { color: #6b7280 !important; }
.dark .fi-fo-field-wrp-label,
.dark .fi-fo-field-wrp-hint { color: #d1d5db !important; }

/* Stats overview polish */
.fi-wi-stats-overview-stat {
    border-radius: 1rem !important;
    border: 1px solid rgba(187,161,79,0.18) !important;
    overflow: hidden;
    position: relative;
    background: linear-gradient(180deg, rgba(255,255,255,0.95), rgba(250,247,240,0.85)) !important;
}
.dark .fi-wi-stats-overview-stat {
    background: linear-gradient(180deg, rgba(24,24,27,0.95), rgba(24,20,15,0.85)) !important;
    border-color: rgba(187,161,79,0.14) !important;
}
.fi-wi-stats-overview-stat::before {
    content: '';
    position: absolute;
    inset: 0 0 auto 0;
    height: 3px;
    background: var(--sorenza-gradient);
    opacity: 0.9;
}
.fi-wi-stats-overview-stat-value {
    font-weight: 700;
    font-size: 1.75rem !important;
    background: linear-gradient(90deg, var(--sorenza-gold-1), var(--sorenza-gold-2));
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
}
.dark .fi-wi-stats-overview-stat-value {
    background: linear-gradient(90deg, var(--sorenza-gold-3), #f0dfa8);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
}
.dark .fi-wi-stats-overview-stat-label { color: #d1d5db !important; }
.dark .fi-wi-stats-overview-stat-description { color: #9ca3af !important; }

/* Sidebar accents */
.fi-sidebar-group-label {
    font-size: 0.7rem !important;
    letter-spacing: 0.22em !important;
    text-transform: uppercase !important;
    color: var(--sorenza-gold-1) !important;
    font-weight: 600 !important;
}
.dark .fi-sidebar-group-label { color: var(--sorenza-gold-3) !important; }
.fi-sidebar-item.fi-active > .fi-sidebar-item-button,
.fi-sidebar-item-button.fi-active {
    background: var(--sorenza-gradient-soft) !important;
    color: var(--sorenza-gold-1) !important;
    font-weight: 600 !important;
    position: relative;
}
.fi-sidebar-item.fi-active > .fi-sidebar-item-button::before {
    content: '';
    position: absolute;
    left: 0; top: 6px; bottom: 6px;
    width: 3px;
    border-radius: 0 3px 3px 0;
    background: var(--sorenza-gradient);
}
.dark .fi-sidebar-item.fi-active > .fi-sidebar-item-button {
    color: var(--sorenza-gold-3) !important;
    background: linear-gradient(135deg, rgba(219,197,132,0.10), rgba(139,105,20,0.05)) !important;
}

/* Sidebar chrome in dark */
.dark .fi-sidebar {
    background: linear-gradient(180deg, rgba(14,16,20,0.96), rgba(18,15,12,0.96)) !important;
    border-right: 1px solid rgba(187,161,79,0.14) !important;
}
.dark .fi-sidebar-item-button {
    color: #d1d5db !important;
}
.dark .fi-sidebar-item-button:hover {
    background: rgba(219,197,132,0.06) !important;
    color: var(--sorenza-gold-3) !important;
}
.dark .fi-sidebar-header {
    background: transparent !important;
    border-bottom: 1px solid rgba(187,161,79,0.12) !important;
}

/* Nav badge in dark */
.dark .fi-sidebar-item-badge { background: rgba(187,161,79,0.20) !important; color: var(--sorenza-gold-3) !important; }

/* Login screen */
.fi-simple-layout {
    background:
        radial-gradient(600px 400px at 20% 30%, rgba(187,161,79,0.18), transparent 60%),
        radial-gradient(600px 400px at 80% 70%, rgba(255,220,180,0.20), transparent 60%),
        #faf7f0 !important;
    padding: 1rem !important;
}
.dark .fi-simple-layout {
    background:
        radial-gradient(600px 400px at 20% 30%, rgba(187,161,79,0.14), transparent 60%),
        radial-gradient(600px 400px at 80% 70%, rgba(139,105,20,0.14), transparent 60%),
        #0b0d10 !important;
}
.dark .fi-simple-main {
    background: rgba(22,22,25,0.94) !important;
    border-color: rgba(187,161,79,0.22) !important;
    box-shadow: 0 20px 60px -20px rgba(0,0,0,0.7) !important;
}
.dark .fi-simple-header-heading { color: #f3f4f6 !important; }
.dark .fi-simple-header-subheading { color: #9ca3af !important; }
.fi-simple-main {
    border: 1px solid rgba(187,161,79,0.20) !important;
    box-shadow: 0 20px 60px -20px rgba(139,105,20,0.30) !important;
    border-radius: 1.25rem !important;
    background: rgba(255,255,255,0.9) !important;
    max-width: 26rem !important;
}

.fi-pagination-item[aria-current="page"] {
    background: var(--sorenza-gradient) !important;
    color: #fff !important;
    border: 0 !important;
}

/* ---------- Extra dark-mode text + surface polish ---------- */
.dark .fi-header-subheading,
.dark .fi-breadcrumbs { color: #9ca3af !important; }

.dark .fi-dropdown-panel,
.dark .fi-modal-window,
.dark .fi-fo-select-option {
    background: #17181b !important;
    border-color: rgba(187,161,79,0.16) !important;
    color: #e5e7eb;
}
.dark .fi-dropdown-list-item:hover,
.dark .fi-fo-select-option:hover {
    background: rgba(219,197,132,0.08) !important;
    color: var(--sorenza-gold-3) !important;
}

/* Filament's own borders and dividers muted */
.dark hr, .dark .fi-divider { border-color: rgba(255,255,255,0.06) !important; }

/* Notifications dark */
.dark .fi-no-notification {
    background: #17181b !important;
    border: 1px solid rgba(187,161,79,0.16) !important;
    color: #e5e7eb;
}
</style>

{{-- ==========================================================================
     JS: give every table cell a data-label from its column header
     so CSS ::before content works for the mobile card layout.
     Runs on load + after every Livewire DOM update.
     ========================================================================== --}}
<script>
(function () {
    function labelize(root) {
        (root || document).querySelectorAll('.fi-ta-table').forEach(function (table) {
            var headers = table.querySelectorAll('thead th');
            if (!headers.length) return;

            var labels = Array.from(headers).map(function (th) {
                return (th.innerText || th.textContent || '').trim();
            });

            table.querySelectorAll('tbody tr').forEach(function (row) {
                Array.from(row.children).forEach(function (cell, i) {
                    if (!cell.hasAttribute('data-label') && labels[i]) {
                        cell.setAttribute('data-label', labels[i]);
                    }
                });
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function () { labelize(); });
    document.addEventListener('livewire:navigated', function () { labelize(); });
    document.addEventListener('livewire:init', function () {
        if (window.Livewire && window.Livewire.hook) {
            window.Livewire.hook('morph.updated', function ({ el }) { labelize(el); });
            window.Livewire.hook('commit', function ({ succeed }) {
                succeed(function () { setTimeout(labelize, 0); });
            });
        }
    });
    // Fallback: observe DOM mutations so async table loads still get labels
    if (typeof MutationObserver !== 'undefined') {
        var mo = new MutationObserver(function (m) {
            for (var i = 0; i < m.length; i++) {
                if (m[i].addedNodes && m[i].addedNodes.length) {
                    labelize();
                    break;
                }
            }
        });
        mo.observe(document.body, { childList: true, subtree: true });
    }
})();
</script>
