<style>
    :root {
        --sidebar-w: 260px;
        --primary: #4c6ef5;
        --primary-dark: #364fc7;
        --surface: #ffffff;
        --text-main: #1e293b;
        --text-muted: #64748b;
        --border: #e8ecf1;
        --shadow-sm: 0 1px 2px rgba(0,0,0,.04);
        --shadow-md: 0 4px 12px rgba(0,0,0,.06);
        --shadow-lg: 0 12px 32px rgba(0,0,0,.08);
        --shadow-xl: 0 20px 48px rgba(0,0,0,.12);
        --radius: 12px;
        --radius-lg: 16px;
    }
    * { box-sizing: border-box; }
    html, body { height: 100%; margin: 0; padding: 0; font-family: 'Inter', 'Segoe UI', system-ui, sans-serif; background: #f1f5f9; color: var(--text-main); -webkit-font-smoothing: antialiased; }
    .sidebar { width: var(--sidebar-w); background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%); position: fixed; height: 100%; z-index: 40; display: flex; flex-direction: column; box-shadow: 4px 0 24px rgba(0,0,0,.18); }
    .sidebar-brand { padding: 1.5rem 1.25rem; border-bottom: 1px solid rgba(255,255,255,.08); display: flex; align-items: center; gap: .75rem; }
    .sidebar-brand-icon { width: 42px; height: 42px; border-radius: var(--radius); background: linear-gradient(135deg, #4c6ef5, #7c3aed); display: flex; align-items: center; justify-content: center; color: #fff; flex-shrink: 0; }
    .sidebar-nav { flex: 1; padding: .75rem; display: flex; flex-direction: column; gap: 2px; overflow-y: auto; }
    .sidebar-nav button { width: 100%; text-align: left; padding: .7rem .9rem; border-radius: 8px; display: flex; align-items: center; gap: .7rem; color: #94a3b8; font-weight: 500; font-size: .9rem; transition: all .18s ease; cursor: pointer; border: none; background: transparent; position: relative; }
    .sidebar-nav button:hover { background: rgba(255,255,255,.05); color: #e2e8f0; }
    .sidebar-nav button.active { background: rgba(76,110,245,.2); color: #fff; font-weight: 600; box-shadow: inset 0 0 0 1px rgba(76,110,245,.35); }
    .sidebar-nav button.active::before { content: ''; position: absolute; left: 0; top: 50%; transform: translateY(-50%); width: 3px; height: 24px; border-radius: 0 4px 4px 0; background: #4c6ef5; }
    .sidebar-footer { padding: 1rem 1.25rem; border-top: 1px solid rgba(255,255,255,.08); display: flex; align-items: center; gap: .6rem; }
    .sidebar-footer .avatar { width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #4c6ef5, #7c3aed); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: .85rem; }
    .main-wrap { margin-left: var(--sidebar-w); min-height: 100vh; display: flex; flex-direction: column; }
    .topbar { background: #fff; padding: .9rem 1.75rem; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--border); position: sticky; top: 0; z-index: 30; gap: 1rem; flex-wrap: wrap; }
    .page-content { padding: 1.75rem; flex: 1; animation: slideUp .35s cubic-bezier(0.16, 1, 0.3, 1); }
    @keyframes slideUp { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: translateY(0); } }
    .card { background: #fff; border-radius: var(--radius-lg); padding: 1.5rem; box-shadow: var(--shadow-sm); border: 1px solid var(--border); transition: all .25s ease; }
    .card:hover { box-shadow: var(--shadow-md); }
    .stat-card { background: #fff; border-radius: var(--radius-lg); padding: 1.25rem 1.5rem; box-shadow: var(--shadow-sm); border: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; gap: 1rem; transition: all .25s ease; }
    .stat-card:hover { box-shadow: var(--shadow-lg); transform: translateY(-2px); }
    .stat-icon { width: 48px; height: 48px; border-radius: var(--radius); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .btn { display: inline-flex; align-items: center; gap: .5rem; padding: .6rem 1.2rem; border-radius: 8px; font-weight: 600; font-size: .875rem; cursor: pointer; border: none; transition: all .2s ease; white-space: nowrap; }
    .btn-primary { background: var(--primary); color: #fff; box-shadow: 0 2px 8px rgba(76,110,245,.3); }
    .btn-primary:hover { background: var(--primary-dark); box-shadow: 0 6px 18px rgba(76,110,245,.4); transform: translateY(-1px); }
    .btn-outline { background: #fff; color: var(--text-main); border: 1.5px solid var(--border); }
    .btn-outline:hover { border-color: #cbd5e1; background: #f8fafc; }
    .btn-secondary { background: #f8fafc; color: #334155; border: 1.5px solid #cbd5e1; }
    .btn-secondary:hover { background: #e2e8f0; }
    .tab-inactive { background: #f8fafc; color: #334155; border: 1px solid #cbd5e1; }
    .tab-active { background: var(--primary); color: #fff; border: 1px solid var(--primary); }
    .tab-inactive:hover { background: #e2e8f0; }
    .btn-danger { background: #fff; color: #dc2626; border: 1.5px solid #fecaca; }
    .btn-danger:hover { background: #fef2f2; border-color: #f87171; }
    .btn-xs { padding: .25rem .55rem; font-size: .72rem; border-radius: 5px; }
    .badge { display: inline-flex; align-items: center; gap: 4px; padding: .25rem .65rem; border-radius: 20px; font-size: .73rem; font-weight: 600; white-space: nowrap; }
    .badge-success { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
    .badge-warning { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }
    .badge-danger { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
    table { width: 100%; border-collapse: collapse; font-size: .875rem; }
    table th { text-align: left; padding: .7rem .9rem; font-weight: 600; color: var(--text-muted); font-size: .75rem; text-transform: uppercase; letter-spacing: .03em; background: #f8fafc; border-bottom: 2px solid var(--border); }
    table td { padding: .75rem .9rem; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    table tbody tr { transition: background .15s ease; }
    table tbody tr:hover { background: #f8fafc; }
    input, select, textarea { width: 100%; padding: .6rem .75rem; border: 1.5px solid var(--border); border-radius: 8px; font-size: .875rem; transition: all .2s ease; background: #fff; color: var(--text-main); outline: none; font-family: inherit; }
    input:focus, select:focus, textarea:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(76,110,245,.1); }
    .modal-overlay { position: fixed; inset: 0; background: rgba(15,23,42,.55); z-index: 100; display: flex; align-items: center; justify-content: center; padding: 1rem; animation: fadeIn .2s ease; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    .modal-box { background: #fff; border-radius: var(--radius-lg); padding: 1.75rem; width: 100%; max-width: 480px; box-shadow: var(--shadow-xl); animation: scaleIn .25s cubic-bezier(0.16, 1, 0.3, 1); max-height: 90vh; overflow-y: auto; }
    @keyframes scaleIn { from { opacity: 0; transform: scale(0.94); } to { opacity: 1; transform: scale(1); } }
    .toast { position: fixed; top: 1.5rem; right: 1.5rem; z-index: 200; padding: .85rem 1.25rem; border-radius: 8px; font-weight: 600; font-size: .875rem; box-shadow: var(--shadow-lg); animation: slideDown .35s cubic-bezier(0.16, 1, 0.3, 1); display: flex; align-items: center; gap: .5rem; max-width: 420px; }
    @keyframes slideDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
    .toast-success { background: #059669; color: #fff; } .toast-error { background: #dc2626; color: #fff; } .toast-info { background: #1e293b; color: #fff; }
    .product-grid-card { background: #fff; border-radius: var(--radius); padding: 1rem; cursor: pointer; border: 2px solid transparent; transition: all .22s ease; box-shadow: var(--shadow-sm); }
    .product-grid-card:hover { border-color: #cbd5e1; box-shadow: var(--shadow-md); transform: translateY(-2px); }
    .product-grid-card:active { transform: scale(.97); }
    .product-grid-card .price { background: linear-gradient(135deg, #4c6ef5, #7c3aed); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-weight: 700; font-size: 1.05rem; }
    ::-webkit-scrollbar { width: 5px; height: 5px; } ::-webkit-scrollbar-track { background: transparent; } ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    @media (max-width: 768px) { .sidebar { transform: translateX(-100%); transition: transform .3s ease; } .sidebar.open { transform: translateX(0); } .main-wrap { margin-left: 0; } .page-content { padding: 1rem; } .topbar { padding: .7rem 1rem; } }
</style>
