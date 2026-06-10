<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand"><div class="sidebar-brand-icon"><i data-lucide="store" style="width:22px;height:22px;"></i></div><div><h1 class="text-white font-bold text-base leading-tight">KASIR UMKM</h1><p class="text-slate-400 text-xs">Point of Sale v2.0</p></div></div>
    <nav class="sidebar-nav" id="sidebarNav">
        <button data-page="dashboard" class="active"><i data-lucide="layout-dashboard" style="width:20px;height:20px;"></i> Dashboard</button>
        <button data-page="categories"><i data-lucide="tags" style="width:20px;height:20px;"></i> Kategori</button>
        <button data-page="products"><i data-lucide="package" style="width:20px;height:20px;"></i> Kelola Produk</button>
        <button data-page="transactions"><i data-lucide="shopping-cart" style="width:20px;height:20px;"></i> Kasir</button>
        <button data-page="history"><i data-lucide="file-text" style="width:20px;height:20px;"></i> Riwayat Transaksi</button>
        <button data-page="reports"><i data-lucide="bar-chart-3" style="width:20px;height:20px;"></i> Laporan</button>
    </nav>
    <div class="sidebar-footer"><div class="avatar">A</div><div><p class="text-white text-sm font-semibold">Admin UMKM</p><p class="text-slate-400 text-xs">Kasir</p></div></div>
</aside>
<div class="main-wrap" id="mainWrap">
    <header class="topbar">
        <div class="flex items-center gap-3"><button id="menuToggle" class="lg:hidden btn btn-outline btn-xs p-2"><i data-lucide="menu" style="width:20px;height:20px;"></i></button><h2 id="pageTitle" class="text-xl font-bold text-slate-800">Dashboard</h2></div>
        <div class="flex items-center gap-3">
            <div class="hidden sm:flex flex-col text-right">
                <span id="current-date" class="text-sm text-slate-500 font-medium"></span>
                <span id="current-time" class="text-sm text-slate-500 font-medium"></span>
            </div>
            <div class="w-9 h-9 rounded-full bg-brand-600 flex items-center justify-center text-white font-bold text-sm">A</div>
        </div>
    </header>
    <main class="page-content" id="pageContainer"></main>
</div>
