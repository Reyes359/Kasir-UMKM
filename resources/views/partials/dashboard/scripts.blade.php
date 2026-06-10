<script src="https://cdn.jsdelivr.net/npm/lucide@0.263.0/dist/umd/lucide.min.js"></script>
<script>
    (function() {
        const todayStr = () => new Date().toISOString().slice(0,10);
        const timeStr = (d) => d.toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'});
        const dateStr = (d) => d.toLocaleDateString('id-ID',{day:'numeric',month:'short',year:'numeric'});
        function makeDate(daysAgo,hour,minute){const d=new Date();d.setDate(d.getDate()-daysAgo);d.setHours(hour,minute,0,0);return d;}

        const store = {
            categories: [
                {id:1,name:'Makanan Pokok',desc:'Beras, gula, garam, dll'},
                {id:2,name:'Minuman',desc:'Kopi, teh, air minum'},
                {id:3,name:'Bumbu & Rempah',desc:'Lada, kunyit, bawang'}
            ],
            products: [
                {id:1,code:'PRD-001',name:'Beras Premium 5kg',category:'Makanan Pokok',price:55000,cost:48000,stock:28,unit:'pcs'},
                {id:2,code:'PRD-002',name:'Gula Pasir 1kg',category:'Makanan Pokok',price:12500,cost:10000,stock:4,unit:'pcs'},
                {id:3,code:'PRD-003',name:'Minyak Goreng 2L',category:'Makanan Pokok',price:28000,cost:24000,stock:2,unit:'pcs'},
                {id:4,code:'PRD-004',name:'Kopi Bubuk 500g',category:'Minuman',price:35000,cost:28000,stock:16,unit:'pcs'},
                {id:5,code:'PRD-005',name:'Telur Ayam 1kg',category:'Makanan Pokok',price:24000,cost:20000,stock:6,unit:'kg'},
                {id:6,code:'PRD-006',name:'Teh Celup 100pcs',category:'Minuman',price:18000,cost:14000,stock:20,unit:'box'}
            ],
            transactions: [
                {id:'INV-20250114-001',date:makeDate(1,9,15),items:[{productId:1,name:'Beras Premium 5kg',price:55000,qty:2},{productId:4,name:'Kopi Bubuk 500g',price:35000,qty:1}],total:145000,paid:150000,change:5000,method:'💰 Tunai'},
                {id:'INV-20250114-002',date:makeDate(1,11,30),items:[{productId:2,name:'Gula Pasir 1kg',price:12500,qty:3},{productId:5,name:'Telur Ayam 1kg',price:24000,qty:1}],total:61500,paid:70000,change:8500,method:'📱 QRIS'},
                {id:'INV-20250114-003',date:makeDate(1,15,45),items:[{productId:3,name:'Minyak Goreng 2L',price:28000,qty:2},{productId:6,name:'Teh Celup 100pcs',price:18000,qty:1}],total:74000,paid:100000,change:26000,method:'🏦 Transfer Bank'},
                {id:'INV-20250115-001',date:makeDate(0,8,20),items:[{productId:1,name:'Beras Premium 5kg',price:55000,qty:1},{productId:2,name:'Gula Pasir 1kg',price:12500,qty:2}],total:80000,paid:80000,change:0,method:'💰 Tunai'},
                {id:'INV-20250115-002',date:makeDate(0,10,5),items:[{productId:4,name:'Kopi Bubuk 500g',price:35000,qty:2}],total:70000,paid:100000,change:30000,method:'💰 Tunai'},
                {id:'INV-20250115-003',date:makeDate(0,13,40),items:[{productId:5,name:'Telur Ayam 1kg',price:24000,qty:3},{productId:3,name:'Minyak Goreng 2L',price:28000,qty:1}],total:100000,paid:100000,change:0,method:'📱 QRIS'},
                {id:'INV-20250115-004',date:makeDate(0,16,10),items:[{productId:1,name:'Beras Premium 5kg',price:55000,qty:1},{productId:6,name:'Teh Celup 100pcs',price:18000,qty:2}],total:91000,paid:100000,change:9000,method:'💰 Tunai'}
            ],
            cart:[],
            currentPage:'dashboard',
            kasirFilter:'',
            nextProductId:7,
            nextCategoryId:4
        };

        function syncStockFromTransactions(){
            const map={};
            store.products.forEach(p=>map[p.id]=p.stock);
            store.transactions.forEach(tx=>{tx.items.forEach(it=>{if(map[it.productId]!==undefined)map[it.productId]-=it.qty;})});
            store.products.forEach(p=>p.stock=Math.max(0,map[p.id]||p.stock));
        }
        syncStockFromTransactions();

        function updateCategoryCounts(){
            store.categories.forEach(c=>c.productCount=store.products.filter(p=>p.category===c.name).length);
        }
        updateCategoryCounts();

        const $=(s,c=document)=>c.querySelector(s);
        const $$=(s,c=document)=>[...c.querySelectorAll(s)];
        const fmt=(n)=>'Rp '+Number(n).toLocaleString('id-ID');
        const fmtNum=(n)=>Number(n).toLocaleString('id-ID');

        function toast(msg,type='info'){
            const el=document.createElement('div');
            el.className='toast toast-'+type;
            const icons={success:'✅',error:'❌',info:'ℹ️'};
            el.innerHTML=`<span>${icons[type]||'ℹ️'}</span> ${msg}`;
            document.getElementById('toastContainer').appendChild(el);
            setTimeout(()=>{el.style.opacity='0';el.style.transition='opacity .3s ease';},2200);
            setTimeout(()=>el.remove(),2600);
        }

        function confirmDialog(msg){
            return new Promise(resolve=>{
                const overlay=document.createElement('div');
                overlay.className='modal-overlay';
                overlay.innerHTML=`<div class="modal-box" style="max-width:380px;text-align:center;"><i data-lucide="help-circle" style="width:40px;height:40px;color:#f59e0b;margin:0 auto 8px;"></i><p class="text-slate-700 font-semibold mb-4">${msg}</p><div class="flex gap-3 justify-center"><button class="btn btn-outline btn-xs" id="confirmNo">Batal</button><button class="btn btn-primary btn-xs" id="confirmYes">Ya, Lanjutkan</button></div></div>`;
                document.getElementById('modalContainer').appendChild(overlay);
                lucide.createIcons(overlay);
                overlay.querySelector('#confirmYes').onclick=()=>{overlay.remove();resolve(true);};
                overlay.querySelector('#confirmNo').onclick=()=>{overlay.remove();resolve(false);};
                overlay.addEventListener('click',(e)=>{if(e.target===overlay){overlay.remove();resolve(false);}});
            });
        }

        function showModal(title,contentHTML,onSubmit){
            const overlay=document.createElement('div');
            overlay.className='modal-overlay';
            overlay.innerHTML=`<div class="modal-box"><div class="flex items-center justify-between mb-4"><h3 class="text-lg font-bold text-slate-800">${title}</h3><button class="text-slate-400 hover:text-slate-600 transition text-2xl leading-none" id="modalCloseBtn">&times;</button></div><div id="modalBody">${contentHTML}</div></div>`;
            document.getElementById('modalContainer').appendChild(overlay);
            lucide.createIcons(overlay);
            const close=()=>overlay.remove();
            overlay.querySelector('#modalCloseBtn').onclick=close;
            overlay.addEventListener('click',(e)=>{if(e.target===overlay)close();});
            if(onSubmit){
                const form=overlay.querySelector('form');
                if(form){
                    form.setAttribute('novalidate','');
                    form.onsubmit=(e)=>{
                        e.preventDefault();
                        const data=new FormData(form);
                        const obj=Object.fromEntries(data.entries());
                        onSubmit(obj,close,form);
                    };
                }
            }
            return {overlay,close};
        }

        function clearFormErrors(form){
            if(!form)return;
            form.querySelectorAll('.field-error').forEach(el=>el.textContent='');
            form.querySelectorAll('.is-invalid').forEach(el=>el.classList.remove('is-invalid','border-red-500'));
        }

        function renderFormErrors(form,errors){
            clearFormErrors(form);
            if(!form)return;
            Object.entries(errors).forEach(([field,message])=>{
                const input=form.querySelector(`[name="${field}"]`);
                if(input){
                    input.classList.add('is-invalid','border-red-500');
                    let errorBox=input.parentElement.querySelector('.field-error');
                    if(!errorBox){
                        errorBox=document.createElement('div');
                        errorBox.className='field-error mt-1 text-sm text-red-500';
                        input.parentElement.appendChild(errorBox);
                    }
                    errorBox.textContent=message;
                }
            });
        }

        function validateCategoryForm(data){
            const errors={};
            const name=String(data.name||'').trim();
            const desc=String(data.desc||data.description||'').trim();
            if(!name){
                errors.name='Nama kategori wajib diisi.';
            } else if(name.length>255){
                errors.name='Nama kategori terlalu panjang.';
            }
            if(desc.length>255){
                errors.desc='Deskripsi terlalu panjang.';
            }
            return errors;
        }

        function validateProductForm(data){
            const errors={};
            const name=String(data.name||'').trim();
            const code=String(data.code||'').trim();
            const category=String(data.category||'').trim();
            const price=Number(data.price);
            const cost=Number(data.cost);
            const stock=Number(data.stock);
            const unit=String(data.unit||'').trim();
            if(!name){
                errors.name='Nama produk wajib diisi.';
            } else if(name.length>255){
                errors.name='Nama produk terlalu panjang.';
            }
            if(!category){
                errors.category='Kategori wajib dipilih.';
            }
            if(!code){
                errors.code='Kode produk wajib diisi.';
            } else if(code.length>50){
                errors.code='Kode produk terlalu panjang.';
            }
            if(!Number.isFinite(price) || price < 0){
                errors.price='Harga jual tidak boleh negatif.';
            }
            if(!Number.isFinite(cost) || cost < 0){
                errors.cost='Harga modal tidak boleh negatif.';
            }
            if(!Number.isFinite(stock) || stock < 0){
                errors.stock='Stok tidak boleh negatif.';
            }
            if(unit.length>50){
                errors.unit='Satuan terlalu panjang.';
            }
            return errors;
        }

        function getStockStatus(stock){
            if(stock<=3)return {label:'Kritis',cls:'badge-danger',icon:'🔴'};
            if(stock<=7)return {label:'Menipis',cls:'badge-warning',icon:'⚠️'};
            return {label:'Aman',cls:'badge-success',icon:'✅'};
        }

        function getTodayTransactions(){
            const today=todayStr();
            return store.transactions.filter(tx=>tx.date.toISOString().slice(0,10)===today);
        }
        function getMonthTransactions(){
            const now=new Date();
            return store.transactions.filter(tx=>tx.date.getMonth()===now.getMonth()&&tx.date.getFullYear()===now.getFullYear());
        }
        function getTopProducts(limit=5){
            const map={};
            store.transactions.forEach(tx=>{tx.items.forEach(it=>{if(!map[it.productId])map[it.productId]={productId:it.productId,name:it.name,qty:0,revenue:0};map[it.productId].qty+=it.qty;map[it.productId].revenue+=it.price*it.qty;})});
            return Object.values(map).sort((a,b)=>b.revenue-a.revenue).slice(0,limit);
        }

        function updateClock(){
            const now=new Date();
            const dateEl=document.getElementById('current-date');
            const timeEl=document.getElementById('current-time');
            if(dateEl)dateEl.textContent=now.toLocaleDateString('id-ID',{weekday:'long',day:'numeric',month:'long',year:'numeric'});
            if(timeEl)timeEl.textContent=now.toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit',second:'2-digit'});
        }
        updateClock();
        setInterval(updateClock,1000);

        function renderDashboard(){
            const todayTx=getTodayTransactions();
            const todayRevenue=todayTx.reduce((s,tx)=>s+tx.total,0);
            const lowStock=store.products.filter(p=>p.stock<=7);
            const recentTx=[...store.transactions].sort((a,b)=>b.date-a.date).slice(0,5);
            return `
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
              <div class="stat-card"><div><p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Produk</p><h3 class="text-2xl font-bold text-slate-800 mt-1">${store.products.length}</h3></div><div class="stat-icon bg-indigo-50 text-indigo-600"><i data-lucide="package" style="width:24px;height:24px;"></i></div></div>
              <div class="stat-card"><div><p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Transaksi Hari Ini</p><h3 class="text-2xl font-bold text-slate-800 mt-1">${todayTx.length}</h3></div><div class="stat-icon bg-blue-50 text-blue-600"><i data-lucide="receipt" style="width:24px;height:24px;"></i></div></div>
              <div class="stat-card"><div><p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Pendapatan Hari Ini</p><h3 class="text-2xl font-bold text-emerald-600 mt-1">${fmt(todayRevenue)}</h3></div><div class="stat-icon bg-emerald-50 text-emerald-600"><i data-lucide="trending-up" style="width:24px;height:24px;"></i></div></div>
              <div class="stat-card"><div><p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Stok Menipis</p><h3 class="text-2xl font-bold text-amber-600 mt-1">${lowStock.length} Produk</h3></div><div class="stat-icon bg-amber-50 text-amber-600"><i data-lucide="alert-triangle" style="width:24px;height:24px;"></i></div></div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <div class="card"><h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2"><i data-lucide="alert-circle" style="width:20px;height:20px;color:#f59e0b;"></i> Produk Stok Menipis</h3><div class="overflow-x-auto"><table><thead><tr><th>Produk</th><th>Stok</th><th>Status</th></tr></thead><tbody>${lowStock.slice(0,5).map(p=>{const s=getStockStatus(p.stock);return`<tr><td class="font-medium">${p.name}</td><td class="font-bold">${p.stock}</td><td><span class="badge ${s.cls}">${s.icon} ${s.label}</span></td></tr>`;}).join('')||'<tr><td colspan="3" class="text-center text-slate-400 py-6">Semua stok aman 🎉</td></tr>'}</tbody></table></div></div>
              <div class="card"><h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2"><i data-lucide="history" style="width:20px;height:20px;color:#3b82f6;"></i> Transaksi Terbaru</h3><div class="space-y-3">${recentTx.map(tx=>`<div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg border-l-4 border-brand-500 hover:shadow-sm transition cursor-pointer" onclick="window._viewTransactionDetail('${tx.id}')"><div><p class="font-semibold text-sm">${tx.id}</p><p class="text-xs text-slate-500">${dateStr(tx.date)} - ${timeStr(tx.date)}</p><p class="text-xs text-slate-400">${tx.method} · ${tx.items.length} item</p></div><p class="font-bold text-emerald-600 text-sm">+ ${fmt(tx.total)}</p></div>`).join('')||'<p class="text-center text-slate-400 py-6">Belum ada transaksi</p>'}</div></div>
            </div>`;
        }

        function renderCategories(){
            updateCategoryCounts();
            return `
            <div class="flex flex-wrap items-center justify-between gap-3 mb-6"><div><h3 class="text-xl font-bold text-slate-800">Kelola Kategori Produk</h3><p class="text-slate-500 text-sm">Tambah, edit, dan hapus kategori produk</p></div><button class="btn btn-primary" onclick="window._addCategory()"><i data-lucide="plus" style="width:18px;height:18px;"></i> Tambah Kategori</button></div>
            <div class="card"><div class="overflow-x-auto"><table><thead><tr><th>No</th><th>Nama Kategori</th><th>Deskripsi</th><th>Jml Produk</th><th class="text-center">Aksi</th></tr></thead><tbody>${store.categories.map((c,i)=>`<tr><td>${i+1}</td><td class="font-semibold">${c.name}</td><td class="text-slate-500 text-sm">${c.desc}</td><td><span class="badge badge-success">${c.productCount}</span></td><td class="text-center"><div class="flex gap-2 justify-center"><button class="btn btn-outline btn-xs" onclick="window._editCategory(${c.id})">✏️ Edit</button><button class="btn btn-danger btn-xs" onclick="window._deleteCategory(${c.id})">🗑️ Hapus</button></div></td></tr>`).join('')}</tbody></table></div></div>`;
        }

        function renderProducts(){
            return `
            <div class="flex flex-wrap items-center justify-between gap-3 mb-4"><div><h3 class="text-xl font-bold text-slate-800">Kelola Produk</h3><p class="text-slate-500 text-sm">Kelola stok dan harga produk UMKM Anda</p></div><button class="btn btn-primary" onclick="window._addProduct()"><i data-lucide="plus" style="width:18px;height:18px;"></i> Tambah Produk</button></div>
            <div class="flex flex-wrap gap-3 mb-5"><input type="search" id="productSearch" placeholder="Cari kode atau nama produk..." class="max-w-sm" oninput="window._filterProducts()"><select id="productCatFilter" onchange="window._filterProducts()" class="max-w-[200px]"><option value="">Semua Kategori</option>${store.categories.map(c=>`<option value="${c.name}">${c.name}</option>`).join('')}</select></div>
            <div class="card"><div class="overflow-x-auto"><table id="productTable"><thead><tr><th>Kode</th><th>Nama Produk</th><th>Kategori</th><th class="text-right">Harga Jual</th><th class="text-center">Stok</th><th class="text-center">Status</th><th class="text-center">Aksi</th></tr></thead><tbody id="productTableBody"></tbody></table></div></div>`;
        }

        function renderProductTableRows(filter='',catFilter=''){
            const tbody=document.getElementById('productTableBody');
            if(!tbody)return;
            const filtered=store.products.filter(p=>{
                const ms=!filter||p.name.toLowerCase().includes(filter.toLowerCase())||p.code.toLowerCase().includes(filter.toLowerCase());
                const mc=!catFilter||p.category===catFilter;
                return ms&&mc;
            });
            tbody.innerHTML=filtered.map(p=>{const s=getStockStatus(p.stock);return`<tr><td class="font-mono font-bold text-slate-600">${p.code}</td><td class="font-semibold">${p.name}</td><td class="text-slate-500">${p.category}</td><td class="text-right font-bold text-emerald-600">${fmt(p.price)}</td><td class="text-center font-bold ${p.stock<=7?'text-amber-600':'text-slate-800'}">${p.stock}</td><td class="text-center"><span class="badge ${s.cls}">${s.icon} ${s.label}</span></td><td class="text-center"><div class="flex gap-1 justify-center"><button class="btn btn-outline btn-xs" onclick="window._editProduct(${p.id})">✏️</button><button class="btn btn-danger btn-xs" onclick="window._deleteProduct(${p.id})">🗑️</button></div></td></tr>`;}).join('')||'<tr><td colspan="7" class="text-center text-slate-400 py-8">Tidak ada produk ditemukan</td></tr>';
        }

        function renderTransactions(){
            return `
            <div class="mb-5"><h3 class="text-xl font-bold text-slate-800">Kasir</h3><p class="text-slate-500 text-sm">Proses penjualan baru</p></div>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
              <div class="lg:col-span-2"><div class="card mb-5"><h4 class="text-lg font-bold text-slate-800 mb-3">🛍️ Pilih Produk</h4>
            <div class="flex flex-wrap gap-2 mb-4">
              <button onclick="window._filterKasir('')" class="kasir-filter text-xs px-3 py-1.5 rounded-lg font-bold tab-inactive" data-filter="">Semua</button>
              ${store.categories.map(c=>`<button onclick="window._filterKasir('${c.name}')" class="kasir-filter text-xs px-3 py-1.5 rounded-lg font-bold tab-inactive" data-filter="${c.name}">${c.name}</button>`).join('')}
            </div>
            <input type="search" id="txProductSearch" placeholder="Cari produk atau kode..." class="mb-4" oninput="window._filterTxProducts()"><div class="grid grid-cols-2 sm:grid-cols-3 gap-3" id="txProductGrid"></div></div></div>
              <div class="lg:col-span-1"><div class="card sticky" style="top:5rem;"><h4 class="text-lg font-bold text-slate-800 mb-3 flex items-center gap-2"><i data-lucide="shopping-cart" style="width:20px;height:20px;color:#4c6ef5;"></i> Keranjang</h4><div id="cartItems" class="mb-4 max-h-64 overflow-y-auto"></div><div class="bg-slate-50 rounded-lg p-3 mb-4 space-y-1 text-sm"><div class="flex justify-between"><span class="text-slate-500">Subtotal</span><span id="cartSubtotal" class="font-semibold">Rp 0</span></div><div class="flex justify-between text-base font-bold border-t pt-1"><span>Total</span><span id="cartTotal" class="text-brand-600">Rp 0</span></div></div><div class="space-y-3 mb-4"><div><label class="text-xs font-semibold text-slate-600 block mb-1">💵 Uang Bayar</label><input type="number" id="paidAmount" placeholder="0" oninput="window._calcChange()"></div><div><label class="text-xs font-semibold text-slate-600 block mb-1">🔄 Kembalian</label><input type="text" id="changeAmount" value="Rp 0" readonly class="bg-slate-100 font-bold text-emerald-600"></div><div><label class="text-xs font-semibold text-slate-600 block mb-1">💳 Metode</label><select id="paymentMethod"><option>💰 Tunai</option><option>🏦 Transfer Bank</option><option>📱 QRIS</option></select></div></div><button class="btn btn-primary w-full justify-center" onclick="window._processTransaction()"><i data-lucide="check-circle" style="width:18px;height:18px;"></i> Simpan Transaksi</button><button class="btn btn-outline w-full justify-center mt-2 text-sm" onclick="window._clearCart()">🗑️ Bersihkan</button></div></div>
            </div>`;
        }

        function renderHistory(){
            return `
            <div class="mb-5"><h3 class="text-xl font-bold text-slate-800">Riwayat Transaksi</h3><p class="text-slate-500 text-sm">Seluruh transaksi yang telah dilakukan</p></div>
            <div class="card mb-5"><div class="flex flex-wrap items-center justify-between gap-3 mb-4"><div class="flex flex-wrap gap-3"><input type="search" id="historySearch" placeholder="Cari invoice..." class="max-w-xs" oninput="window._filterHistory()"><input type="date" id="historyDateFrom" onchange="window._filterHistory()"><input type="date" id="historyDateTo" onchange="window._filterHistory()"><select id="historyMethod" onchange="window._filterHistory()"><option value="">Semua Metode</option><option value="💰 Tunai">💰 Tunai</option><option value="🏦 Transfer Bank">🏦 Transfer Bank</option><option value="📱 QRIS">📱 QRIS</option></select></div><button onclick="window.printLaporan()" class="btn-secondary py-3 px-4 rounded-xl font-bold text-sm flex items-center gap-2"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="printer" style="width:16px;height:16px" class="lucide lucide-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect width="12" height="8" x="6" y="14"></rect></svg> Cetak Laporan</button></div>
            <div class="overflow-x-auto"><table><thead><tr><th>Invoice</th><th>Tanggal</th><th>Metode</th><th>Item</th><th class="text-right">Total</th><th class="text-center">Aksi</th></tr></thead><tbody id="historyTableBody"></tbody></table></div></div>`;
        }

        function renderHistoryRows(filterText='',dateFrom='',dateTo='',methodFilter=''){
            const tbody=document.getElementById('historyTableBody');
            if(!tbody)return;
            let filtered=[...store.transactions].sort((a,b)=>b.date-a.date);
            if(filterText) filtered=filtered.filter(tx=>tx.id.toLowerCase().includes(filterText.toLowerCase()));
            if(dateFrom) filtered=filtered.filter(tx=>tx.date.toISOString().slice(0,10)>=dateFrom);
            if(dateTo) filtered=filtered.filter(tx=>tx.date.toISOString().slice(0,10)<=dateTo);
            if(methodFilter) filtered=filtered.filter(tx=>tx.method===methodFilter);
            tbody.innerHTML=filtered.map(tx=>`<tr><td class="font-mono font-semibold text-sm">${tx.id}</td><td class="text-sm">${dateStr(tx.date)} ${timeStr(tx.date)}</td><td>${tx.method}</td><td>${tx.items.length} item</td><td class="text-right font-bold text-emerald-600">${fmt(tx.total)}</td><td class="text-center"><button class="btn btn-outline btn-xs" onclick="window._viewTransactionDetail('${tx.id}')"><i data-lucide="eye" style="width:14px;height:14px;"></i> Detail</button></td></tr>`).join('')||'<tr><td colspan="6" class="text-center text-slate-400 py-8">Tidak ada transaksi ditemukan</td></tr>';
            lucide.createIcons(tbody);
        }

        window._viewTransactionDetail = function(invoiceId){
            const tx = store.transactions.find(t => t.id === invoiceId);
            if(!tx) return;
            const itemsHtml = tx.items.map(it => `
                <tr>
                    <td class="font-medium">${it.name}</td>
                    <td class="text-center">${it.qty}</td>
                    <td class="text-right">${fmt(it.price)}</td>
                    <td class="text-right font-semibold">${fmt(it.price * it.qty)}</td>
                </tr>`).join('');
            const modalContent = `
                <div class="space-y-3">
                    <div class="flex justify-between text-sm"><span class="text-slate-500">Invoice</span><span class="font-mono font-bold">${tx.id}</span></div>
                    <div class="flex justify-between text-sm"><span class="text-slate-500">Tanggal</span><span>${dateStr(tx.date)} ${timeStr(tx.date)}</span></div>
                    <div class="flex justify-between text-sm"><span class="text-slate-500">Metode</span><span>${tx.method}</span></div>
                    <div class="border-t pt-3"><h4 class="font-semibold mb-2">Detail Item</h4>
                    <table class="text-sm"><thead><tr><th>Produk</th><th class="text-center">Qty</th><th class="text-right">Harga</th><th class="text-right">Subtotal</th></tr></thead><tbody>${itemsHtml}</tbody></table></div>
                    <div class="border-t pt-2 flex justify-between font-bold"><span>Total</span><span>${fmt(tx.total)}</span></div>
                    <div class="flex justify-between text-sm"><span>Uang Bayar</span><span>${fmt(tx.paid)}</span></div>
                    <div class="flex justify-between text-sm"><span>Kembalian</span><span>${fmt(tx.change)}</span></div>
                </div>`;
            showModal('Detail Transaksi', modalContent);
        };

        window._filterHistory = function(){
            const search = document.getElementById('historySearch')?.value || '';
            const from = document.getElementById('historyDateFrom')?.value || '';
            const to = document.getElementById('historyDateTo')?.value || '';
            const method = document.getElementById('historyMethod')?.value || '';
            renderHistoryRows(search,from,to,method);
        };

        window.printLaporan = function(){
            const search = document.getElementById('historySearch')?.value || '';
            const from = document.getElementById('historyDateFrom')?.value || '';
            const to = document.getElementById('historyDateTo')?.value || '';
            const method = document.getElementById('historyMethod')?.value || '';
            let filtered=[...store.transactions].sort((a,b)=>b.date-a.date);
            if(search) filtered=filtered.filter(tx=>tx.id.toLowerCase().includes(search.toLowerCase()));
            if(from) filtered=filtered.filter(tx=>tx.date.toISOString().slice(0,10)>=from);
            if(to) filtered=filtered.filter(tx=>tx.date.toISOString().slice(0,10)<=to);
            if(method) filtered=filtered.filter(tx=>tx.method===method);
            const rows = filtered.map(tx=>`<tr><td>${tx.id}</td><td>${dateStr(tx.date)} ${timeStr(tx.date)}</td><td>${tx.method}</td><td>${tx.items.length}</td><td style="text-align:right">${fmt(tx.total)}</td></tr>`).join('') || '<tr><td colspan="5" style="text-align:center;padding:16px;color:#64748b;">Tidak ada transaksi yang dicetak</td></tr>';
            const html = `<!doctype html><html lang="id"><head><meta charset="UTF-8"><title>Laporan Transaksi</title><style>body{font-family:Inter,system-ui,sans-serif;color:#0f172a;padding:24px;background:#fff}h1{font-size:1.4rem;margin-bottom:.25rem}p{margin:.25rem 0 .75rem;color:#475569}table{width:100%;border-collapse:collapse;margin-top:1rem}th,td{padding:.75rem 1rem;border:1px solid #e2e8f0}th{text-align:left;background:#f8fafc;color:#334155}td{text-align:left}td.text-right{text-align:right}</style></head><body><h1>Laporan Transaksi</h1><p>Dicetak: ${dateStr(new Date())} ${timeStr(new Date())}</p><table><thead><tr><th>Invoice</th><th>Tanggal</th><th>Metode</th><th>Item</th><th style="text-align:right">Total</th></tr></thead><tbody>${rows}</tbody></table></body></html>`;
            const printWindow = window.open('','_blank');
            if(!printWindow) return toast('⚠️ Gagal membuka jendela cetak. Izinkan popup pada browser.','error');
            printWindow.document.write(html);
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
        };

        window._filterKasir = function(category) {
            store.kasirFilter = category || '';
            renderTxProductGrid(document.getElementById('txProductSearch')?.value || '', store.kasirFilter);
            document.querySelectorAll('.kasir-filter').forEach(btn => {
                if(btn.dataset.filter === store.kasirFilter){
                    btn.classList.add('tab-active');
                    btn.classList.remove('tab-inactive');
                } else {
                    btn.classList.add('tab-inactive');
                    btn.classList.remove('tab-active');
                }
            });
        };

        window._filterTxProducts = ()=>renderTxProductGrid(document.getElementById('txProductSearch')?.value||'', store.kasirFilter || '');

        function renderTxProductGrid(search='',category=''){
            const grid=document.getElementById('txProductGrid');
            if(!grid)return;
            const filtered=store.products.filter(p=>{
                const matchSearch = !search||p.name.toLowerCase().includes(search.toLowerCase())||p.code.toLowerCase().includes(search.toLowerCase());
                const matchCategory = !category||p.category===category;
                return matchSearch && matchCategory;
            });
            grid.innerHTML=filtered.map(p=>`<div class="product-grid-card" onclick="window._addToCart(${p.id})"><p class="font-bold text-sm text-slate-800 truncate">${p.name}</p><p class="text-xs text-slate-400">${p.code}</p><p class="price text-base mt-1">${fmt(p.price)}</p><p class="text-xs text-slate-400 mt-0.5">📦 Stok: <span class="${p.stock<=7?'text-amber-600 font-bold':''}">${p.stock}</span></p></div>`).join('')||'<p class="col-span-full text-center text-slate-400 py-6">Produk tidak ditemukan</p>';
        }

        function renderCart(){
            const container=document.getElementById('cartItems');
            if(!container)return;
            if(store.cart.length===0){container.innerHTML='<div class="text-center py-8 text-slate-400"><i data-lucide="inbox" style="width:36px;height:36px;margin:0 auto 8px;"></i><p class="text-sm">Keranjang kosong</p></div>';}
            else{container.innerHTML=store.cart.map(item=>`<div class="flex items-start gap-2 p-2 bg-slate-50 rounded-lg mb-2 text-sm"><div class="flex-1 min-w-0"><p class="font-semibold truncate">${item.name}</p><p class="text-xs text-slate-500">${fmt(item.price)}</p><div class="flex items-center gap-1 mt-1"><button class="btn btn-outline btn-xs px-1.5" onclick="window._updateCartQty(${item.id},${item.qty-1})">−</button><input type="number" value="${item.qty}" min="1" class="w-12 text-center text-xs py-0.5 px-1" onchange="window._updateCartQty(${item.id},this.value)"><button class="btn btn-outline btn-xs px-1.5" onclick="window._updateCartQty(${item.id},${item.qty+1})">+</button></div></div><button class="text-red-500 hover:text-red-700 flex-shrink-0 mt-1" onclick="window._removeFromCart(${item.id})"><i data-lucide="trash-2" style="width:16px;height:16px;"></i></button></div>`).join('');}
            lucide.createIcons(container);
            window._calcChange();
        }

        function calcCartTotals(){
            const subtotal=store.cart.reduce((s,i)=>s+i.price*i.qty,0);
            const se=document.getElementById('cartSubtotal'),te=document.getElementById('cartTotal');
            if(se)se.textContent=fmt(subtotal);if(te)te.textContent=fmt(subtotal);
            return subtotal;
        }

        function renderReports(){
            const monthTx=getMonthTransactions();
            const monthRevenue=monthTx.reduce((s,tx)=>s+tx.total,0);
            const avgTx=monthTx.length>0?Math.round(monthRevenue/monthTx.length):0;
            const totalUnits=monthTx.reduce((s,tx)=>s+tx.items.reduce((ss,it)=>ss+it.qty,0),0);
            const topProducts=getTopProducts(5);
            const prevMonthRevenue=Math.round(monthRevenue*0.88);
            const revenueGrowth=monthRevenue>0?Math.round((monthRevenue-prevMonthRevenue)/prevMonthRevenue*100):0;
            return `
            <div class="mb-5"><h3 class="text-xl font-bold text-slate-800">Laporan Penjualan</h3><p class="text-slate-500 text-sm">Analisis transaksi dan pendapatan — data real-time</p></div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
              <div class="stat-card border-l-4 border-indigo-500"><div><p class="text-xs text-slate-500">📊 Transaksi Bulan Ini</p><h3 class="text-xl font-bold text-slate-800 mt-1">${monthTx.length}</h3><p class="text-xs text-emerald-600 mt-0.5">↑ Data real</p></div></div>
              <div class="stat-card border-l-4 border-emerald-500"><div><p class="text-xs text-slate-500">💰 Pendapatan Bulan Ini</p><h3 class="text-xl font-bold text-slate-800 mt-1">${fmt(monthRevenue)}</h3><p class="text-xs ${revenueGrowth>=0?'text-emerald-600':'text-red-600'} mt-0.5">↑ ${revenueGrowth}% dari bulan lalu</p></div></div>
              <div class="stat-card border-l-4 border-blue-500"><div><p class="text-xs text-slate-500">📈 Rata-rata Transaksi</p><h3 class="text-xl font-bold text-slate-800 mt-1">${fmt(avgTx)}</h3><p class="text-xs text-slate-400 mt-0.5">Per transaksi</p></div></div>
              <div class="stat-card border-l-4 border-amber-500"><div><p class="text-xs text-slate-500">📦 Produk Terjual</p><h3 class="text-xl font-bold text-slate-800 mt-1">${fmtNum(totalUnits)}</h3><p class="text-xs text-slate-400 mt-0.5">unit bulan ini</p></div></div>
            </div>
            <div class="card"><h4 class="text-lg font-bold text-slate-800 mb-3">⭐ Produk Terpopuler</h4><div class="overflow-x-auto"><table><thead><tr><th>Produk</th><th>Kategori</th><th class="text-center">Terjual</th><th class="text-right">Pendapatan</th></tr></thead><tbody id="topProductsBody">${topProducts.map((p,i)=>{const prod=store.products.find(pr=>pr.id===p.productId);const cat=prod?prod.category:'-';const medals=['🏆','🥈','🥉'];return`<tr><td class="font-semibold">${medals[i]||'⭐'} ${p.name}</td><td class="text-slate-500">${cat}</td><td class="text-center font-bold text-brand-600">${fmtNum(p.qty)}</td><td class="text-right font-bold text-emerald-600">${fmt(p.revenue)}</td></tr>`;}).join('')||'<tr><td colspan="4" class="text-center text-slate-400 py-6">Belum ada data penjualan</td></tr>'}</tbody></table></div></div>`;
        }

        function navigateTo(page){
            store.currentPage=page;
            const titles={dashboard:'Dashboard',categories:'Kelola Kategori',products:'Kelola Produk',transactions:'Kasir',history:'Riwayat Transaksi',reports:'Laporan Penjualan'};
            document.getElementById('pageTitle').textContent=titles[page]||page;
            $$('button[data-page]',document.getElementById('sidebarNav')).forEach(b=>b.classList.remove('active'));
            const ab=$(`button[data-page="${page}"]`,document.getElementById('sidebarNav'));
            if(ab)ab.classList.add('active');
            const renderers={dashboard:renderDashboard,categories:renderCategories,products:renderProducts,transactions:renderTransactions,history:renderHistory,reports:renderReports};
            document.getElementById('pageContainer').innerHTML=renderers[page]?renderers[page]():'';
            lucide.createIcons();
            if(page==='products')renderProductTableRows();
            if(page==='transactions'){window._filterKasir(store.kasirFilter);renderCart();calcCartTotals();}
            if(page==='history')renderHistoryRows();
            document.getElementById('sidebar').classList.remove('open');
            updateClock();
        }
        document.getElementById('sidebarNav').addEventListener('click',(e)=>{const btn=e.target.closest('button[data-page]');if(btn)navigateTo(btn.dataset.page);});
        document.getElementById('menuToggle').addEventListener('click',()=>document.getElementById('sidebar').classList.toggle('open'));

        window._addCategory = function(){
            showModal('Tambah Kategori',`<form novalidate><div class="mb-3"><label class="block text-sm font-semibold text-slate-700 mb-1">Nama Kategori</label><input type="text" name="name" placeholder="Contoh: Makanan Pokok"><div class="field-error mt-1 text-sm text-red-500"></div></div><div class="mb-4"><label class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi</label><textarea name="desc" rows="2"></textarea><div class="field-error mt-1 text-sm text-red-500"></div></div><div class="flex gap-3"><button type="submit" class="btn btn-primary flex-1 justify-center">Simpan</button><button type="button" class="btn btn-outline flex-1 justify-center" onclick="this.closest('.modal-overlay').remove()">Batal</button></div></form>`,(data,close,form)=>{const errors=validateCategoryForm(data);if(Object.keys(errors).length){renderFormErrors(form,errors);return;}store.categories.push({id:store.nextCategoryId++,name:data.name.trim(),desc:String(data.desc||data.description||'').trim(),productCount:0});updateCategoryCounts();toast('✅ Kategori berhasil ditambahkan!','success');close();navigateTo('categories');});
        };
        window._editCategory = function(id){const cat=store.categories.find(c=>c.id===id);if(!cat)return;showModal('Edit Kategori',`<form novalidate><div class="mb-3"><label class="block text-sm font-semibold text-slate-700 mb-1">Nama Kategori</label><input type="text" name="name" value="${cat.name}"><div class="field-error mt-1 text-sm text-red-500"></div></div><div class="mb-4"><label class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi</label><textarea name="desc" rows="2">${cat.desc||''}</textarea><div class="field-error mt-1 text-sm text-red-500"></div></div><div class="flex gap-3"><button type="submit" class="btn btn-primary flex-1 justify-center">Simpan</button><button type="button" class="btn btn-outline flex-1 justify-center" onclick="this.closest('.modal-overlay').remove()">Batal</button></div></form>`,(data,close,form)=>{const errors=validateCategoryForm(data);if(Object.keys(errors).length){renderFormErrors(form,errors);return;}cat.name=data.name.trim();cat.desc=String(data.desc||data.description||'').trim();updateCategoryCounts();toast('✅ Kategori diperbarui!','success');close();navigateTo('categories');});};
        window._deleteCategory = async function(id){if(!await confirmDialog('Yakin hapus kategori?'))return;store.categories=store.categories.filter(c=>c.id!==id);updateCategoryCounts();toast('✅ Kategori dihapus!','success');navigateTo('categories');};
        window._addProduct = function(){showModal('Tambah Produk',`<form novalidate><div class="mb-3"><label class="block text-sm font-semibold text-slate-700 mb-1">Kategori</label><select name="category"><option value="">Pilih kategori</option>${store.categories.map(c=>`<option value="${c.name}">${c.name}</option>`).join('')}</select><div class="field-error mt-1 text-sm text-red-500"></div></div><div class="mb-3"><label class="block text-sm font-semibold text-slate-700 mb-1">Nama Produk</label><input type="text" name="name" placeholder="Contoh: Beras Premium 5kg"><div class="field-error mt-1 text-sm text-red-500"></div></div><div class="mb-3"><label class="block text-sm font-semibold text-slate-700 mb-1">Kode Produk</label><input type="text" name="code" placeholder="PRD-007"><div class="field-error mt-1 text-sm text-red-500"></div></div><div class="grid grid-cols-2 gap-3 mb-3"><div><label class="block text-sm font-semibold text-slate-700 mb-1">Harga Modal</label><input type="number" name="cost" placeholder="0"><div class="field-error mt-1 text-sm text-red-500"></div></div><div><label class="block text-sm font-semibold text-slate-700 mb-1">Harga Jual</label><input type="number" name="price" placeholder="0"><div class="field-error mt-1 text-sm text-red-500"></div></div></div><div class="grid grid-cols-2 gap-3 mb-4"><div><label class="block text-sm font-semibold text-slate-700 mb-1">Stok</label><input type="number" name="stock" placeholder="0"><div class="field-error mt-1 text-sm text-red-500"></div></div><div><label class="block text-sm font-semibold text-slate-700 mb-1">Satuan</label><input type="text" name="unit" placeholder="pcs"><div class="field-error mt-1 text-sm text-red-500"></div></div></div><div class="flex gap-3"><button type="submit" class="btn btn-primary flex-1 justify-center">Simpan</button><button type="button" class="btn btn-outline flex-1 justify-center" onclick="this.closest('.modal-overlay').remove()">Batal</button></div></form>`,(data,close,form)=>{const errors=validateProductForm(data);if(Object.keys(errors).length){renderFormErrors(form,errors);return;}store.products.push({id:store.nextProductId++,code:data.code.trim(),name:data.name.trim(),category:data.category.trim(),price:Number(data.price),cost:Number(data.cost),stock:Number(data.stock),unit:data.unit.trim()});updateCategoryCounts();toast('✅ Produk ditambahkan!','success');close();navigateTo('products');});};
        window._editProduct = function(id){const p=store.products.find(pr=>pr.id===id);if(!p)return;showModal('Edit Produk',`<form novalidate><div class="mb-3"><label class="block text-sm font-semibold text-slate-700 mb-1">Kategori</label><select name="category">${store.categories.map(c=>`<option value="${c.name}" ${c.name===p.category?'selected':''}>${c.name}</option>`).join('')}</select><div class="field-error mt-1 text-sm text-red-500"></div></div><div class="mb-3"><label class="block text-sm font-semibold text-slate-700 mb-1">Nama Produk</label><input type="text" name="name" value="${p.name}"><div class="field-error mt-1 text-sm text-red-500"></div></div><div class="mb-3"><label class="block text-sm font-semibold text-slate-700 mb-1">Kode Produk</label><input type="text" name="code" value="${p.code}"><div class="field-error mt-1 text-sm text-red-500"></div></div><div class="grid grid-cols-2 gap-3 mb-3"><div><label class="block text-sm font-semibold text-slate-700 mb-1">Harga Modal</label><input type="number" name="cost" value="${p.cost}"><div class="field-error mt-1 text-sm text-red-500"></div></div><div><label class="block text-sm font-semibold text-slate-700 mb-1">Harga Jual</label><input type="number" name="price" value="${p.price}"><div class="field-error mt-1 text-sm text-red-500"></div></div></div><div class="grid grid-cols-2 gap-3 mb-4"><div><label class="block text-sm font-semibold text-slate-700 mb-1">Stok</label><input type="number" name="stock" value="${p.stock}"><div class="field-error mt-1 text-sm text-red-500"></div></div><div><label class="block text-sm font-semibold text-slate-700 mb-1">Satuan</label><input type="text" name="unit" value="${p.unit}"><div class="field-error mt-1 text-sm text-red-500"></div></div></div><div class="flex gap-3"><button type="submit" class="btn btn-primary flex-1 justify-center">Simpan</button><button type="button" class="btn btn-outline flex-1 justify-center" onclick="this.closest('.modal-overlay').remove()">Batal</button></div></form>`,(data,close,form)=>{const oldCat=p.category;const errors=validateProductForm(data);if(Object.keys(errors).length){renderFormErrors(form,errors);return;}p.category=data.category.trim();p.name=data.name.trim();p.code=data.code.trim();p.cost=Number(data.cost);p.price=Number(data.price);p.stock=Number(data.stock);p.unit=data.unit.trim();if(oldCat!==data.category.trim())updateCategoryCounts();toast('✅ Produk diperbarui!','success');close();navigateTo('products');});};
        window._deleteProduct = async function(id){if(!await confirmDialog('Yakin hapus produk?'))return;store.products=store.products.filter(pr=>pr.id!==id);updateCategoryCounts();toast('✅ Produk dihapus!','success');navigateTo('products');};
        window._filterProducts = ()=>renderProductTableRows(document.getElementById('productSearch')?.value||'',document.getElementById('productCatFilter')?.value||'');
        window._filterTxProducts = ()=>renderTxProductGrid(document.getElementById('txProductSearch')?.value||'', store.kasirFilter || '');
        window._addToCart = function(productId){const p=store.products.find(pr=>pr.id===productId);if(!p)return;if(p.stock<=0){toast('⚠️ Stok habis!','error');return;}const existing=store.cart.find(i=>i.id===productId);if(existing){if(existing.qty>=p.stock){toast('⚠️ Stok tidak mencukupi!','error');return;}existing.qty++;}else store.cart.push({id:p.id,name:p.name,price:p.price,qty:1,maxStock:p.stock});renderCart();calcCartTotals();};
        window._removeFromCart = (productId)=>{store.cart=store.cart.filter(i=>i.id!==productId);renderCart();calcCartTotals();};
        window._updateCartQty = function(productId,qty){const item=store.cart.find(i=>i.id===productId);if(!item)return;const p=store.products.find(pr=>pr.id===productId);const max=p?p.stock:999;const nq=Math.max(1,Math.min(parseInt(qty)||1,max));if(nq>max)toast('⚠️ Stok tidak mencukupi!','error');item.qty=nq;renderCart();calcCartTotals();};
        window._calcChange = ()=>{const total=calcCartTotals();const paid=parseInt(document.getElementById('paidAmount')?.value)||0;const change=Math.max(0,paid-total);const ce=document.getElementById('changeAmount');if(ce)ce.value=fmt(change);};
        window._processTransaction = function(){
            if(store.cart.length===0){toast('⚠️ Keranjang kosong!','error');return;}
            const total=calcCartTotals();const paid=parseInt(document.getElementById('paidAmount')?.value)||0;
            if(paid<total){toast('⚠️ Uang tidak cukup!','error');return;}
            const method=document.getElementById('paymentMethod')?.value||'💰 Tunai';
            const invNo='INV-'+todayStr().replace(/-/g,'')+'-'+String(Math.floor(Math.random()*900)+100);
            const tx={id:invNo,date:new Date(),items:store.cart.map(i=>({productId:i.id,name:i.name,price:i.price,qty:i.qty})),total,paid,change:paid-total,method};
            store.transactions.push(tx);
            store.cart.forEach(item=>{const p=store.products.find(pr=>pr.id===item.id);if(p)p.stock=Math.max(0,p.stock-item.qty);});
            store.cart=[];renderCart();calcCartTotals();
            document.getElementById('paidAmount').value='';document.getElementById('changeAmount').value='Rp 0';
            window._filterKasir(store.kasirFilter);
            toast(`✅ Transaksi berhasil!\n📋 ${invNo} · ${fmt(total)}`,'success');
        };
        window._clearCart = async function(){if(store.cart.length===0)return;if(!await confirmDialog('Yakin bersihkan keranjang?'))return;store.cart=[];renderCart();calcCartTotals();document.getElementById('paidAmount').value='';document.getElementById('changeAmount').value='Rp 0';};

        navigateTo('dashboard');
        lucide.createIcons();
        console.log('🚀 Sistem Kasir UMKM v2.0 — Riwayat Transaksi siap!');
    })();
</script>
