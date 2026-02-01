<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>TOKO SHOP YAKULT</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #1e293b; --accent: #d4af37; --bg: #f3f4f6; --white: #ffffff; --success: #10b981; --danger: #ef4444; }
        body { font-family: 'Poppins', sans-serif; background: var(--bg); margin: 0; padding-bottom: 140px; color: #333; }
        
        .header { background: var(--primary); padding: 20px; border-radius: 0 0 30px 30px; color: white; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.2); position: relative; z-index: 10; }
        .admin-shortcut { position: absolute; top: 10px; right: 10px; opacity: 0.3; color: white; text-decoration: none; font-size: 10px; }

        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; padding: 15px; max-width: 600px; margin: 0 auto; }
        .card { background: var(--white); padding: 15px; border-radius: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); text-align: center; }
        .img-box { width: 100%; height: 120px; margin-bottom: 10px; display:flex; align-items:center; justify-content:center; border-radius: 15px; overflow: hidden; }
        .card img { max-width: 100%; max-height: 100%; border-radius: 15px; }
        
        .controls { display: flex; justify-content: space-between; align-items: center; margin-top: 10px; background: #f1f5f9; border-radius: 50px; padding: 5px; }
        .btn-c { width: 28px; height: 28px; border-radius: 50%; border: none; font-weight: bold; cursor: pointer; display: flex; align-items: center; justify-content: center; }
        .btn-min { background: var(--white); color: var(--danger); }
        .btn-plus { background: var(--primary); color: var(--accent); }

        .float-container { position: fixed; bottom: 20px; left: 0; width: 100%; z-index: 90; display: flex; flex-direction: column; align-items: center; gap: 10px; pointer-events: none; }
        .status-bar { pointer-events: auto; background: var(--primary); color: white; padding: 10px 20px; border-radius: 50px; font-size: 12px; display: flex; align-items: center; gap: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.3); cursor: pointer; transform: translateY(100px); transition: 0.3s; }
        .status-bar.show { transform: translateY(0); }
        .status-dot { width: 8px; height: 8px; background: var(--accent); border-radius: 50%; animation: pulse 1s infinite; }
        
        .cart-float { pointer-events: auto; width: 90%; max-width: 500px; background: rgba(30, 41, 59, 0.95); backdrop-filter: blur(10px); color: white; border-radius: 20px; padding: 15px 20px; display: none; justify-content: space-between; align-items: center; box-shadow: 0 15px 40px rgba(0,0,0,0.3); }
        .btn-chk { background: var(--accent); color: var(--primary); border: none; padding: 10px 20px; border-radius: 12px; font-weight: 700; cursor: pointer; }

        .overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); backdrop-filter: blur(5px); z-index: 100; justify-content: center; align-items: center; }
        .modal { background: var(--white); width: 85%; max-width: 400px; padding: 25px; border-radius: 24px; animation: slideUp 0.3s ease; max-height: 90vh; overflow-y: auto; }
        
        .pay-box { background: #f8fafc; padding: 15px; border-radius: 12px; text-align: center; margin: 15px 0; min-height: 200px; display: flex; flex-direction: column; justify-content: center; align-items: center; border: 1px solid #e2e8f0; position: relative; }
        .item-list { width: 100%; text-align: left; margin-bottom: 15px; font-size: 14px; }
        .item-row { display: flex; justify-content: space-between; padding: 5px 0; border-bottom: 1px dashed #eee; }
        .total-box { font-weight: 800; font-size: 18px; color: var(--primary); margin-top: 10px; text-align: right; }
        
        /* Custom File Input */
        .file-upload-wrapper { width: 100%; margin-top: 10px; }
        .file-upload-input { display: none; }
        .file-upload-btn { display: block; width: 100%; padding: 12px; background: #e2e8f0; color: #475569; border-radius: 12px; text-align: center; cursor: pointer; font-size: 13px; border: 2px dashed #cbd5e1; transition: 0.2s; }
        .file-upload-btn:hover { border-color: var(--primary); color: var(--primary); }
        .file-selected { background: #dcfce7; color: #166534; border-color: #166534; }

        .btn-act { width: 100%; padding: 12px; border-radius: 12px; border: none; font-weight: bold; cursor: pointer; margin-top: 10px; font-family: inherit; }
        .btn-primary { background: var(--primary); color: white; }
        .btn-cancel { background: transparent; color: #94a3b8; }
        
        .status-icon { font-size: 60px; margin-bottom: 10px; animation: popIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        @keyframes popIn { 0% { transform: scale(0); } 100% { transform: scale(1); } }
        @keyframes slideUp { from { transform:translateY(50px); opacity:0; } to { transform:translateY(0); opacity:1; } }
        @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.5; } 100% { opacity: 1; } }
        
        .inp-modern { width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 12px; margin-bottom: 10px; box-sizing: border-box; font-family: 'Poppins'; }
        .hidden { display: none !important; }
    </style>
</head>
<body>

    <div class="header">
        <a href="admin11ad.php" class="admin-shortcut">Admin Panel</a>
        <h2>TOKO SHOP YAKULT</h2>
        <small>Premium Yakult & Game Store</small>
    </div>

    <div class="grid" id="productGrid"></div>

    <div class="float-container">
        <div id="statusBar" class="status-bar" onclick="openPaymentModal()">
            <div class="status-dot"></div>
            <span>Status Pesanan (Klik)</span>
        </div>

        <div class="cart-float" id="cartFloat">
            <div>
                <small>Total</small>
                <b style="display:block; font-size:16px; color:var(--accent);" id="floatTotal">Rp0</b>
            </div>
            <div style="display:flex; gap:10px;">
                <button class="btn-chk" style="background:white; color:#333;" onclick="openForm('cash')">CASH</button>
                <button class="btn-chk" onclick="openForm('qris')">QRIS</button>
            </div>
        </div>
    </div>

    <div class="overlay" id="overlayForm">
        <div class="modal">
            <h3>Data Pembeli</h3>
            <input type="text" id="inpName" class="inp-modern" placeholder="Nama Lengkap">
            <input type="tel" id="inpWA" class="inp-modern" placeholder="Nomor WhatsApp (628...)">
            <textarea id="inpMsg" class="inp-modern" placeholder="Alamat Lengkap / Catatan..."></textarea>
            
            <div style="background:#f1f5f9; padding:10px; border-radius:10px; margin-bottom:15px; text-align:center;">
                <button onclick="getLoc()" style="background:#3b82f6; color:white; border:none; padding:8px 15px; border-radius:8px; cursor:pointer;">📍 Share Lokasi</button>
                <div id="locStatus" style="font-size:11px; margin-top:5px; color:#64748b;">Wajib diisi untuk pengiriman</div>
            </div>

            <button onclick="submitOrder()" class="btn-act" style="background:var(--success); color:white;">LANJUT PEMBAYARAN</button>
            <button onclick="closeOverlay('overlayForm')" class="btn-act btn-cancel">Batal</button>
        </div>
    </div>

    <div class="overlay" id="overlayPayment">
        <div class="modal">
            <h3 id="payTitle">Menunggu Pembayaran</h3>
            <div id="payItems" class="item-list"></div>
            
            <div class="pay-box" id="payContent">
                <div class="loader">Loading...</div>
            </div>
            
            <div id="payFooter">
                <div class="total-box" id="payTotalDisplay"></div>
                <div id="timerDisplay" style="color:var(--danger); font-weight:bold; margin:10px 0; text-align:center;"></div>
                
                <div id="proofSection" class="hidden">
                    <div class="file-upload-wrapper">
                        <label for="fileProof" class="file-upload-btn" id="fileLabel">
                            📂 Pilih Bukti Transfer
                        </label>
                        <input type="file" id="fileProof" class="file-upload-input" accept="image/*" onchange="previewFile()">
                    </div>
                    <button onclick="sendProof()" class="btn-act btn-primary">KIRIM BUKTI</button>
                </div>
            </div>
            
            <button onclick="cancelOrder()" id="btnCancelOrder" class="btn-act" style="background:var(--danger); color:white; margin-top:15px;">BATALKAN PESANAN</button>
            <button onclick="closeOverlay('overlayPayment')" class="btn-act btn-cancel">Tutup (Proses Berjalan)</button>
        </div>
    </div>

    <script type="module">
        import { db, ref, set, push, onValue, remove } from './firebase-config.js';

        // RAW QRIS STRING DARI USER
        const RAW_QRIS = "00020101021126570011ID.DANA.WWW011893600915380003780002098000378000303UMI51440014ID.CO.QRIS.WWW0215ID10243620012490303UMI5204549953033605802ID5910Warr2 Shop6015Kab. Bandung Ba6105402936304BF4C";

        const products = [
            { id: 1, name: "Yakult Original", price: 10500, img: "img/Yakult-original.png" },
            { id: 2, name: "Yakult Mangga", price: 12000, img: "img/Yakult-mangga.png" },
            { id: 3, name: "Yakult Light", price: 13000, img: "img/Yakult-light.png" },
            { id: 4, name: "Test Produk", price: 100, img: "img/test.png" }
        ];

        let cart = JSON.parse(localStorage.getItem('cart') || '{}');
        let activeTxId = localStorage.getItem('activeTxId');
        let userLoc = JSON.parse(localStorage.getItem('userLoc') || 'null');
        let selectedType = localStorage.getItem('selectedType') || 'qris';
        let timerInt = null;

        function init() {
            const g = document.getElementById('productGrid');
            g.innerHTML = '';
            products.forEach(p => {
                const qty = cart[p.id] || 0;
                g.innerHTML += `
                <div class="card">
                    <div class="img-box"><img src="${p.img}" alt="${p.name}" onerror="this.src='https://placehold.co/100?text=Produk'"></div>
                    <div class="p-name">${p.name}</div>
                    <div class="p-price">Rp${p.price.toLocaleString()}</div>
                    <div class="controls">
                        <button class="btn-c btn-min" onclick="window.updCart(${p.id}, -1)">-</button>
                        <span id="qty-${p.id}" style="font-weight:bold;">${qty}</span>
                        <button class="btn-c btn-plus" onclick="window.updCart(${p.id}, 1)">+</button>
                    </div>
                </div>`;
            });
            updateCartUI();
            if(activeTxId) checkActiveTx();
        }
        window.onload = init;

        window.updCart = (id, val) => {
            if(!cart[id]) cart[id] = 0;
            cart[id] += val;
            if(cart[id] <= 0) delete cart[id];
            localStorage.setItem('cart', JSON.stringify(cart));
            document.getElementById(`qty-${id}`).innerText = cart[id] || 0;
            updateCartUI();
        };

        function updateCartUI() {
            let total = 0;
            Object.keys(cart).forEach(k => {
                const p = products.find(x => x.id == k);
                if(p) total += p.price * cart[k];
            });
            document.getElementById('floatTotal').innerText = `Rp${total.toLocaleString()}`;
            // Sembunyikan cart jika sedang ada transaksi aktif
            document.getElementById('cartFloat').style.display = (total > 0 && !activeTxId) ? 'flex' : 'none';
        }

        window.openForm = (type) => {
            selectedType = type;
            localStorage.setItem('selectedType', type);
            document.getElementById('overlayForm').style.display = 'flex';
        };

        window.getLoc = () => {
            if(!navigator.geolocation) return alert("Browser tidak support lokasi");
            document.getElementById('locStatus').innerText = "Mencari...";
            navigator.geolocation.getCurrentPosition(p => {
                userLoc = { lat: p.coords.latitude, lng: p.coords.longitude };
                localStorage.setItem('userLoc', JSON.stringify(userLoc));
                document.getElementById('locStatus').innerText = "Lokasi terkunci!";
                document.getElementById('locStatus').style.color = "green";
            }, () => alert("Mohon izinkan lokasi!"));
        };

        window.closeOverlay = (id) => document.getElementById(id).style.display = 'none';

        window.submitOrder = async () => {
            const name = document.getElementById('inpName').value;
            const wa = document.getElementById('inpWA').value;
            const msg = document.getElementById('inpMsg').value;

            if(!name || !wa || !userLoc) return alert("Mohon lengkapi Data dan Lokasi!");

            let total = 0;
            let itemList = [];
            Object.keys(cart).forEach(k => {
                const p = products.find(x => x.id == k);
                total += p.price * cart[k];
                itemList.push({ name: p.name, qty: cart[k], price: p.price });
            });
            
            // Fee logic
            const fee = selectedType === 'qris' ? 200 : 0;
            const finalTotal = total + fee;

            const newRef = push(ref(db, 'transactions'));
            activeTxId = newRef.key;
            localStorage.setItem('activeTxId', activeTxId);

            const txData = {
                type: selectedType,
                customer: { name, wa, msg, lat: userLoc.lat, lng: userLoc.lng },
                items: itemList,
                total: finalTotal,
                fee: fee,
                status: 'pending',
                timestamp: Date.now()
            };

            await set(newRef, txData);
            
            document.getElementById('overlayForm').style.display = 'none';
            document.getElementById('cartFloat').style.display = 'none';
            
            // LOGIKA CASH: LANGSUNG KIRIM TELEGRAM
            if(selectedType === 'cash') {
                const telegramMsg = `<b>PESANAN BARU (CASH)</b>\n\nNama: ${name}\nWA: ${wa}\nAlamat: ${msg}\n\n<b>Item:</b>\n${itemList.map(i=>`- ${i.name} x${i.qty}`).join('\n')}\n\n<b>Total: Rp${finalTotal.toLocaleString()}</b>\n(Bayar di tempat)`;
                
                const formData = new FormData();
                formData.append('action', 'send_telegram');
                formData.append('caption', telegramMsg);
                formData.append('lat', userLoc.lat);
                formData.append('lng', userLoc.lng);
                fetch('proxy.php', { method: 'POST', body: formData });
            } else {
                // LOGIKA QRIS: Set Timer
                localStorage.setItem('qrisTimerTarget', Date.now() + (5 * 60 * 1000));
            }

            checkActiveTx();
            openPaymentModal();
        };

        window.openPaymentModal = () => {
            document.getElementById('overlayPayment').style.display = 'flex';
        }

        window.cancelOrder = async () => {
            if(!activeTxId) return;
            if(confirm("Yakin batalkan pesanan?")) {
                await remove(ref(db, `transactions/${activeTxId}`));
                localStorage.removeItem('activeTxId');
                localStorage.removeItem('cart');
                location.reload();
            }
        }

        function checkActiveTx() {
            if(!activeTxId) return;
            const statBar = document.getElementById('statusBar');
            statBar.classList.add('show');

            onValue(ref(db, `transactions/${activeTxId}`), (snap) => {
                const data = snap.val();
                if(!data) {
                    // Transaksi hilang (dihapus/batal)
                    localStorage.removeItem('activeTxId');
                    localStorage.removeItem('cart');
                    location.reload();
                    return;
                }
                renderPaymentUI(data);
            });
        }

        function renderPaymentUI(data) {
            const content = document.getElementById('payContent');
            const itemsBox = document.getElementById('payItems');
            const totalBox = document.getElementById('payTotalDisplay');
            const proofSec = document.getElementById('proofSection');
            const btnCancel = document.getElementById('btnCancelOrder');
            
            // Render Items
            itemsBox.innerHTML = '<h4>Detail Pesanan:</h4>';
            data.items.forEach(i => {
                itemsBox.innerHTML += `<div class="item-row"><span>${i.name} x${i.qty}</span><span>Rp${(i.price*i.qty).toLocaleString()}</span></div>`;
            });
            if(data.fee > 0) {
                itemsBox.innerHTML += `<div class="item-row"><span>Biaya Layanan</span><span>Rp${data.fee.toLocaleString()}</span></div>`;
            }
            totalBox.innerHTML = `TOTAL: Rp${data.total.toLocaleString()}`;

            // LOGIKA STATUS REALTIME
            if(data.status === 'confirmed') {
                // CENTANG HIJAU
                content.innerHTML = `
                    <div class="status-icon" style="color:var(--success);">✅</div>
                    <h3 style="color:var(--success);">PESANAN DITERIMA</h3>
                    <p>Terima kasih! Pesanan segera diproses/dikirim.</p>
                `;
                proofSec.classList.add('hidden');
                btnCancel.classList.add('hidden');
                document.getElementById('timerDisplay').classList.add('hidden');
                
                // Clear data after 5 sec
                setTimeout(() => {
                   localStorage.removeItem('activeTxId');
                   localStorage.removeItem('cart');
                   location.reload();
                }, 5000);

            } else if(data.status === 'rejected') {
                // SILANG MERAH
                content.innerHTML = `
                    <div class="status-icon" style="color:var(--danger);">❌</div>
                    <h3 style="color:var(--danger);">PESANAN DITOLAK</h3>
                    <p>Maaf, admin membatalkan pesanan ini.</p>
                `;
                proofSec.classList.add('hidden');
                btnCancel.classList.add('hidden');
                
                setTimeout(() => {
                   localStorage.removeItem('activeTxId');
                   location.reload();
                }, 5000);

            } else {
                // PENDING (Menunggu Bayar/Konfirmasi)
                if(data.type === 'qris') {
                    // Generate QR Image dari API
                    const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=${encodeURIComponent(RAW_QRIS)}`;
                    content.innerHTML = `
                        <img src="${qrUrl}" style="width:200px; height:200px; border-radius:10px; border:2px solid var(--primary);">
                        <p style="margin-top:10px; font-weight:bold;">Scan QRIS Dana</p>
                        <small>Silakan transfer sesuai total</small>
                    `;
                    proofSec.classList.remove('hidden');
                    startTimer();
                } else {
                    content.innerHTML = `
                        <div class="status-icon" style="font-size:50px;">💵</div>
                        <h3>PEMBAYARAN CASH</h3>
                        <p>Pesanan telah masuk ke Admin.<br>Mohon siapkan uang pas saat barang sampai.</p>
                    `;
                    proofSec.classList.add('hidden'); // Cash tidak perlu upload bukti
                    document.getElementById('timerDisplay').classList.add('hidden');
                }
            }
        }

        function startTimer() {
            if(timerInt) clearInterval(timerInt);
            const target = parseInt(localStorage.getItem('qrisTimerTarget'));
            timerInt = setInterval(() => {
                const now = Date.now();
                const diff = target - now;
                if(diff <= 0) {
                    clearInterval(timerInt);
                    document.getElementById('timerDisplay').innerText = "WAKTU HABIS - Silakan Refresh";
                    return;
                }
                const m = Math.floor(diff / 60000);
                const s = Math.floor((diff % 60000) / 1000);
                document.getElementById('timerDisplay').innerText = `Sisa Waktu: ${m.toString().padStart(2,'0')}:${s.toString().padStart(2,'0')}`;
            }, 1000);
        }

        // Preview nama file yang dipilih
        window.previewFile = () => {
            const file = document.getElementById('fileProof').files[0];
            const label = document.getElementById('fileLabel');
            if(file) {
                label.innerText = "✅ " + file.name;
                label.classList.add('file-selected');
            }
        }

        window.sendProof = () => {
            const file = document.getElementById('fileProof').files[0];
            if(!file) return alert("Silakan pilih foto bukti transfer terlebih dahulu!");
            
            const btn = document.querySelector('#proofSection button');
            btn.innerText = "Mengirim...";
            btn.disabled = true;

            // Fetch data transaksi untuk caption
            onValue(ref(db, `transactions/${activeTxId}`), (snap) => {
                const data = snap.val();
                if(!data) return;

                let detailMsg = `<b>BUKTI TRANSFER (QRIS)</b>\n\n`;
                detailMsg += `<b>Customer:</b> ${data.customer.name} (${data.customer.wa})\n`;
                detailMsg += `<b>Catatan:</b> ${data.customer.msg}\n\n`;
                detailMsg += `<b>Order:</b>\n`;
                data.items.forEach(i => detailMsg += `- ${i.name} x${i.qty}\n`);
                detailMsg += `Fee: Rp${data.fee.toLocaleString()}\n`;
                detailMsg += `<b>TOTAL: Rp${data.total.toLocaleString()}</b>`;

                const formData = new FormData();
                formData.append('action', 'send_telegram');
                formData.append('photo', file);
                formData.append('caption', detailMsg);
                formData.append('lat', data.customer.lat);
                formData.append('lng', data.customer.lng);

                fetch('proxy.php', { method: 'POST', body: formData })
                .then(res => res.text())
                .then(txt => {
                    alert("Bukti berhasil dikirim! Mohon tunggu konfirmasi admin.");
                    btn.innerText = "TERKIRIM ✅";
                })
                .catch(err => {
                    alert("Gagal mengirim bukti.");
                    btn.innerText = "KIRIM BUKTI";
                    btn.disabled = false;
                });

            }, { onlyOnce: true });
        }
    </script>
</body>
</html>
