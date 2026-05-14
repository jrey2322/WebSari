<?php  ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'WebSari') ?> | WebSari 🏪</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
    /* =============================================
       WEBSARI - GLOBAL STYLES
    ============================================= */
    :root {
        --primary:    #2563eb;
        --primary-dk: #1d4ed8;
        --secondary:  #f97316;
        --success:    #16a34a;
        --danger:     #dc2626;
        --warning:    #d97706;
        --sidebar-w:  255px;
        --topbar-h:   64px;
        --bg:         #f1f5f9;
        --card-bg:    #ffffff;
        --text:       #1e293b;
        --muted:      #64748b;
        --border:     #e2e8f0;
    }

    * {
        font-family: 'Nunito', sans-serif;
        box-sizing: border-box;
    }

    body {
        background: var(--bg);
        color: var(--text);
        overflow-x: hidden;
    }

    /* ── SIDEBAR ────────────────────────────── */
    .ws-sidebar {
        position: fixed;
        top: 0; left: 0;
        width: var(--sidebar-w);
        height: 100vh;
        background: linear-gradient(180deg, #0f172a 0%, #1e3a5f 100%);
        z-index: 1050;
        overflow-y: auto;
        transition: transform .3s ease;
        display: flex;
        flex-direction: column;
    }

    .ws-sidebar::-webkit-scrollbar { width: 4px; }
    .ws-sidebar::-webkit-scrollbar-thumb {
        background: rgba(255,255,255,.15);
        border-radius: 2px;
    }

    /* Brand */
    .ws-brand {
        padding: 22px 20px 18px;
        border-bottom: 1px solid rgba(255,255,255,.08);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .ws-brand-icon {
        width: 42px; height: 42px;
        background: var(--secondary);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
    }

    .ws-brand h5 {
        color: #fff;
        font-weight: 800;
        font-size: 1.1rem;
        margin: 0;
        line-height: 1.2;
    }

    .ws-brand small {
        color: rgba(255,255,255,.4);
        font-size: .68rem;
    }

    /* Nav sections */
    .ws-nav { padding: 12px 0; flex: 1; }

    .ws-nav-section {
        color: rgba(255,255,255,.3);
        font-size: .65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        padding: 10px 20px 4px;
    }

    .ws-nav-link {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 14px;
        margin: 2px 10px;
        border-radius: 9px;
        color: rgba(255,255,255,.6);
        text-decoration: none;
        font-size: .855rem;
        font-weight: 600;
        transition: all .2s;
        position: relative;
    }

    .ws-nav-link i {
        font-size: 1.05rem;
        width: 22px;
        text-align: center;
        flex-shrink: 0;
    }

    .ws-nav-link:hover {
        background: rgba(255,255,255,.08);
        color: #fff;
    }

    .ws-nav-link.active {
        background: rgba(37,99,235,.35);
        color: #fff;
    }

    .ws-nav-link.active::before {
        content: '';
        position: absolute;
        left: 0; top: 20%; bottom: 20%;
        width: 3px;
        background: var(--secondary);
        border-radius: 0 3px 3px 0;
    }

    .ws-nav-badge {
        margin-left: auto;
        background: var(--danger);
        color: #fff;
        font-size: .62rem;
        padding: 2px 7px;
        border-radius: 20px;
        font-weight: 700;
    }

    /* Sidebar footer */
    .ws-sidebar-footer {
        padding: 14px 18px;
        border-top: 1px solid rgba(255,255,255,.08);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .ws-avatar {
        width: 36px; height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 800;
        font-size: .85rem;
        flex-shrink: 0;
    }

    .ws-sidebar-footer .info { flex: 1; min-width: 0; }
    .ws-sidebar-footer .info .name {
        color: #fff;
        font-size: .8rem;
        font-weight: 700;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .ws-sidebar-footer .info .role {
        color: rgba(255,255,255,.4);
        font-size: .68rem;
    }

    .ws-logout {
        color: rgba(255,255,255,.4);
        font-size: 1.1rem;
        text-decoration: none;
        transition: color .2s;
    }
    .ws-logout:hover { color: var(--danger); }

    /* ── MAIN CONTENT ───────────────────────── */
    .ws-main {
        margin-left: var(--sidebar-w);
        min-height: 100vh;
        transition: margin .3s;
    }

    /* ── TOPBAR ─────────────────────────────── */
    .ws-topbar {
        height: var(--topbar-h);
        background: #fff;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        padding: 0 24px;
        gap: 16px;
        position: sticky;
        top: 0;
        z-index: 900;
    }

    .ws-topbar .page-title {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--text);
        margin: 0;
        flex: 1;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .mobile-toggle {
        display: none;
        background: none;
        border: none;
        font-size: 1.5rem;
        color: var(--text);
        padding: 0;
        cursor: pointer;
    }

    .ws-topbar .page-title small {
        display: block;
        font-size: .72rem;
        color: var(--muted);
        font-weight: 500;
    }

    /* ── PAGE CONTENT ───────────────────────── */
    .ws-content { padding: 24px; }

    /* ── CARDS ──────────────────────────────── */
    .ws-card {
        background: var(--card-bg);
        border-radius: 14px;
        box-shadow: 0 1px 3px rgba(0,0,0,.06),
                    0 4px 16px rgba(0,0,0,.04);
        border: 1px solid var(--border);
        overflow: hidden;
    }

    .ws-card-body { padding: 22px; }

    /* Stat Cards */
    .stat-card {
        border-radius: 14px;
        padding: 20px;
        color: #fff;
        position: relative;
        overflow: hidden;
        border: none;
    }

    .stat-card::after {
        content: '';
        position: absolute;
        right: -18px; top: -18px;
        width: 90px; height: 90px;
        border-radius: 50%;
        background: rgba(255,255,255,.12);
    }

    .stat-card .s-icon {
        width: 46px; height: 46px;
        background: rgba(255,255,255,.22);
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        margin-bottom: 14px;
    }

    .stat-card h2 {
        font-size: 1.75rem;
        font-weight: 800;
        margin: 0 0 3px;
    }

    .stat-card p {
        margin: 0;
        font-size: .8rem;
        opacity: .82;
    }

    /* ── TABLES ─────────────────────────────── */
    .ws-table { font-size: .855rem; }

    .ws-table thead th {
        background: #f8fafc;
        color: var(--muted);
        font-weight: 700;
        font-size: .72rem;
        text-transform: uppercase;
        letter-spacing: .5px;
        border: none;
        padding: 11px 14px;
        white-space: nowrap;
    }

    .ws-table tbody td {
        padding: 11px 14px;
        border-color: var(--border);
        vertical-align: middle;
    }

    .ws-table tbody tr:hover { background: #f8fafc; }

    /* ── FORMS ──────────────────────────────── */
    .form-control, .form-select {
        border-radius: 9px;
        border: 1.5px solid var(--border);
        font-size: .875rem;
        padding: 9px 13px;
        transition: all .2s;
        font-family: 'Nunito', sans-serif;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(37,99,235,.12);
    }

    .form-label {
        font-size: .8rem;
        font-weight: 700;
        color: #475569;
        margin-bottom: 5px;
    }

    /* ── BUTTONS ────────────────────────────── */
    .btn {
        font-size: .845rem;
        font-weight: 700;
        border-radius: 9px;
        padding: 8px 18px;
        transition: all .2s;
        font-family: 'Nunito', sans-serif;
    }

    .btn-primary {
        background: var(--primary);
        border-color: var(--primary);
    }

    .btn-primary:hover {
        background: var(--primary-dk);
        border-color: var(--primary-dk);
        transform: translateY(-1px);
    }

    .btn-ws-orange {
        background: var(--secondary);
        color: #fff;
        border-color: var(--secondary);
    }

    .btn-ws-orange:hover {
        background: #ea6c00;
        color: #fff;
        transform: translateY(-1px);
    }

    .btn-ws-blue {
        background: var(--primary);
        color: #fff;
        border-color: var(--primary);
    }

    .btn-ws-blue:hover {
        background: var(--primary-dk);
        color: #fff;
        transform: translateY(-1px);
    }

    /* ── BADGES ─────────────────────────────── */
    .badge {
        font-size: .7rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 20px;
    }

    /* ── ALERTS ─────────────────────────────── */
    .alert {
        border: none;
        border-radius: 11px;
        font-size: .875rem;
        font-weight: 600;
    }

    /* ── ROLE BADGE ─────────────────────────── */
    .role-owner {
        background: #fef3c7;
        color: #92400e;
    }

    .role-staff {
        background: #dbeafe;
        color: #1e40af;
    }

    /* ── SCROLLBAR ──────────────────────────── */
    ::-webkit-scrollbar { width: 5px; height: 5px; }
    ::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    /* ── MOBILE OVERRIDES ────────────────────── */
    @media (max-width: 991.98px) {
        :root { --sidebar-w: 0px !important; }
        .ws-sidebar {
            transform: translateX(-100%);
            width: 280px;
        }
        .ws-sidebar.show {
            transform: translateX(0);
            box-shadow: 0 0 50px rgba(0,0,0,0.5);
        }
        .ws-main { 
            margin-left: 0 !important; 
            width: 100% !important;
        }
        .ws-content { padding: 15px; }
        .ws-topbar { padding: 0 15px; }
        .mobile-toggle { display: block !important; }
        .ws-sidebar-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.4);
            z-index: 1040;
        }
        .ws-sidebar-overlay.show { display: block; }
    }

    /* ── UTILITIES ──────────────────────────── */

    /* ── ANIMATIONS ─────────────────────────── */
    .fade-in {
        animation: fadeIn .35s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    </style>
</head>
<body class="<?= isset($body_class) ? $body_class : '' ?>">

<!-- Sidebar Overlay -->
<div class="ws-sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<script>
    function toggleSidebar() {
        const sidebar = document.querySelector('.ws-sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        sidebar.classList.toggle('show');
        overlay.classList.toggle('show');
    }
</script>