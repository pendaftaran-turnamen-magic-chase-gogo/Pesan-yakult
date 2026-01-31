<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Yakult Premium Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #1e293b; /* Luxury Dark Blue */
            --accent: #d4af37; /* Gold */
            --bg: #f3f4f6;
            --white: #ffffff;
            --success: #10b981;
            --danger: #ef4444;
            --glass: rgba(255, 255, 255, 0.95);
        }
        
        body { font-family: 'Poppins', sans-serif; background: var(--bg); margin: 0; padding-bottom: 120px; color: #333; }
        
        /* HEADER */
        .header { background: var(--primary); padding: 20px; border-radius: 0 0 30px 30px; color: white; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.2); margin-bottom: 20px; }
        .header h2 { margin: 0; font-weight: 600; letter-spacing: 1px; }

        /* PRODUCT GRID */
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; padding: 15px; max-width: 600px; margin: 0 auto; }
        .card { background: var(--white); padding: 15px; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); text-align: center; transition: transform 0.2s; border: 1px solid rgba(0,0,0,0.02); }
        .card:active { transform: scale(0.98); }
        
        .img-box { width: 100%; height: 120px; border-radius: 15px; overflow: hidden; margin-bottom: 10px; background: #f8f9fa; display:flex; align-items:center; justify-content:center; }
        .card img { width: 100%; height: 100%; object-fit: contain; }
        .p-name { font-weight: 600; font-size: 14px; margin-bottom: 5px; color: var(--primary); }
        .p-price { color: var(--accent); font-weight: 700; font-size: 15px; }

        .controls { display: flex; justify-content: space-between; align-items: center; margin-top: 10px; background: #f1f5f9; border-radius: 50px; padding: 5px; }
        .btn-c { width: 28px; height: 28px; border-radius: 50%; border: none; font-weight: bold; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 16px; transition: 0.2s; }
        .btn-min { background: var(--white); color: var(--danger); box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .btn-plus { background: var(--primary); color: var(--accent); }

        /* CART FLOATING */
        .cart-float { position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%); width: 90%; max-width: 500px; background: rgba(30, 41, 59, 0.95); backdrop-filter: blur(10px); color: white; border-radius: 20px; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 15px 40px rgba(0,0,0,0.3); z-index: 90; border: 1px solid rgba(255,255,255,0.1); }
        .cart-info b { display: block; font-size: 16px; color: var(--accent); }
        .cart-info small { color: #ccc; font-size: 11px; }
        .btn-chk { background: var(--accent); color: var(--primary); border: none; padding: 10px 20px; border-radius: 12px; font-weight: 700; cursor: pointer; box-shadow: 0 5px 15px rgba(212, 175, 55, 0.4); }

        /* POPUP & MODAL LUXURY */
        .overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); backdrop-filter: blur(5px); z-index: 100; justify-content: center; align-items: center; }
        .modal { background: var(--white); width: 85%; max-width: 400px; padding: 25px; border-radius: 24px; box-shadow: 0 20px 60px rgba(0,0,0,0.25); position: relative; animation: slideUp 0.3s ease; }
        @keyframes slideUp { from { opacity:0; transform:translateY(50px); } to { opacity:1; transform:translateY(0); } }

        .modal h3 { margin: 0 0 20px 0; color: var(--primary); text-align: center; font-size: 18px; border-bottom: 2px solid #f1f5f9; padding-bottom: 15px; }

        /* FORM STYLES */
        .form-group { margin-bottom: 15px; }
        .form-label { display: block; font-size: 12px; color: #64748b; margin-bottom: 5px; font-weight: 600; }
        .inp-modern { width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 12px; font-family: 'Poppins'; box-sizing: border-box; font-size: 14px; transition: 0.3s; background: #f8fafc; color: #334155; }
        .inp-modern:focus { border-color: var(--primary); background: white; outline: none; box-shadow: 0 0 0 3px rgba(30, 41, 59, 0.1); }
        
        textarea.inp-modern { resize: none; height: 80px; }

        /* MAP BUTTON */
        .loc-box { background: #f1f5f9; padding: 10px; border-radius: 12px; text-align: center; margin-bottom: 15px; border: 1px dashed #cbd5e1; }
        .btn-loc { background: #3b82f6; color: white; border: none; padding: 8px 15px; border-radius: 8px; font-size: 12px; cursor: pointer; display: inline-flex; align-items: center; gap: 5px; }
        .loc-status { font-size: 11px; color: #64748b; margin-top: 5px; display: block; }

        /* PAYMENT BOX (QRIS/ANIMATION) */
        .pay-box { min-height: 250px; display: flex; flex-direction: column; align-items: center; justify-content: center; background: #f8fafc; border-radius: 16px; border: 1px solid #e2e8f0; margin-bottom: 15px; padding: 15px; position: relative; overflow: hidden; }
        .qris-img { width: 100%; max-width: 220px; border-radius: 10px; mix-blend-mode: multiply; }
        .timer { font-size: 20px; font-weight: 700; color: var(--danger); margin: 10px 0; letter-spacing: 2px; }

        /* ANIMATION STATES */
        .anim-container { display: flex; flex-direction: column; align-items: center; justify-content: center; width: 100%; height: 100%; }
        .check-icon { width: 80px; height: 80px; background: var(--success); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 40px; animation: popIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275); margin-bottom: 15px; }
        .cross-icon { width: 80px; height: 80px; background: var(--danger); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 40px; animation: popIn 0.5s; margin-bottom: 15px; }
        @keyframes popIn { 0% { transform: scale(0); } 100% { transform: scale(1); } }

        /* BUTTONS */
        .btn-main { width: 100%; padding: 14px; border: none; border-radius: 12px; background: var(--success); color: white; font-weight: 600; font-size: 14px; cursor: pointer; transition: 0.3s; margin-top: 10px; }
        .btn-main:hover { background: #059669; }
        .btn-sec { width: 100%; padding: 12px; background: transparent; color: #64748b; border: none; cursor: pointer; font-size: 13px; margin-top: 5px; }
        
        .upload-btn-wrapper { position: relative; overflow: hidden; display: inline-block; width: 100%; margin-top: 10px; }
        .btn-upload { border: 2px dashed #cbd5e1; color: #64748b; background-color: white; padding: 8px 20px; border-radius: 8px; font-size: 13px; font-weight: bold; width: 100%; cursor: pointer; }
        .upload-btn-wrapper input[type=file] { font-size: 100px; position: absolute; left: 0; top: 0; opacity: 0; width: 100%; height: 100%; cursor: pointer; }

        .hidden { display: none; }
    </style>
</head>
<body>

    <div class="header">
        <h2>TOKOTOPARYA</h2>
        <small style="opacity: 0.8;">Premium Game Topup & Drinks</small>
    </div>

    <div class="grid" id="productGrid">
        </div>

    <div class="cart-float" id="cartFloat" style="display: none;">
        <div class="cart-info">
            <small>Total Pembayaran</small>
            <span id="floatTotal">Rp0</span>
        </div>
        <div style="display:flex; gap:10px;">
            <button class="btn-chk" onclick="openForm('cash')" style="background:#fff; color:#333;">CASH</button>
            <button class="btn-chk" onclick="openForm('qris')">QRIS</button>
        </div>
    </div>

    <div class="overlay" id="mainOverlay">
        
        <div class="modal" id="modalForm">
            <h3>INFORMASI PEMBELI</h3>
            
            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" id="inpName" class="inp-modern" placeholder="Nama Anda...">
            </div>
            
            <div class="form-group">
                <label class="form-label">WhatsApp (Aktif)</label>
                <input type="tel" id="inpWA" class="inp-modern" placeholder="08xxxxxxxxxx">
            </div>

            <div class="form-group">
                <label class="form-label">Pesan / Alamat (Max 150)</label>
                <textarea id="inpMsg" class="inp-modern" maxlength="150" placeholder="Catatan untuk penjual..."></textarea>
            </div>

            <div class="loc-box">
                <button class="btn-loc" onclick="getLoc()">
                    📍 Share Lokasi Saya
                </button>
                <span id="locStatus" class="loc-status">Belum ada lokasi</span>
            </div>

            <button class="btn-main" id="btnConfirmForm" onclick="submitForm()" style="background: #cbd5e1; cursor: not-allowed;" disabled>
                KONFIRMASI
            </button>
            <button class="btn-sec" onclick="closeOverlay()">Batal</button>
        </div>

        <div class="modal hidden" id="modalPayment">
            <h3 id="payTitle">PEMBAYARAN</h3>
            
            <div class="pay-box" id="dynamicBox">
                </div>

            <div id="paymentControls">
                <div id="qrisControls" class="hidden">
                    <div class="timer" id="timerDisplay">05:00</div>
                    <div class="upload-btn-wrapper">
                        <button class="btn-upload" id="btnFileText">Upload Bukti Transfer</button>
                        <input type="file" id="fileProof" accept="image/*" onchange="handleFile(this)">
                    </div>
                    <button class="btn-main" onclick="sendProof()">KIRIM BUKTI</button>
                </div>

                <div id="cashControls" class="hidden">
                    <p style="text-align:center; font-size:13px; color:#666;">Silakan tunggu konfirmasi Admin. Siapkan uang pas.</p>
                </div>
            </div>
             <button class="btn-sec" onclick="closeOverlay()">Tutup</button>
        </div>

    </div>

    <script type="module">
        import { db, ref, set, push, onValue, remove } from './firebase-config.js';

        // --- DATA PRODUK ---
        const products = [
            { id: 1, name: "Yakult Original", price: 10500, img: "Yakult-original.png" },
            { id: 2, name: "Yakult Mangga", price: 10500, img: "Yakult-mangga.png" },
            { id: 3, name: "Yakult Light", price: 13000, img: "Yakult-light.png" },
            { id: 4, name: "Test Produk", price: 100, img: "test.png" }
        ];

        let cart = {};
        let currentTxKey = null;
        let selectedType = '';
        let userLocation = null;
        let timerInterval = null;

        // RENDER PRODUK
        const grid = document.getElementById('productGrid');
        products.forEach(p => {
            grid.innerHTML += `
            <div class="card">
                <div class="img-box"><img src="img/${p.img}" alt="${p.name}"></div>
                <div class="p-name">${p.name}</div>
                <div class="p-price">Rp${p.price.toLocaleString('id-ID')}</div>
                <div class="controls">
                    <button class="btn-c btn-min" onclick="window.updCart(${p.id}, -1)">-</button>
                    <span id="qty-${p.id}" style="font-size:14px; font-weight:600;">0</span>
                    <button class="btn-c btn-plus" onclick="window.updCart(${p.id}, 1)">+</button>
                </div>
            </div>`;
        });

        window.updCart = (id, val) => {
            if(!cart[id]) cart[id] = 0;
            cart[id] += val;
            if(cart[id] <= 0) delete cart[id];
            document.getElementById(`qty-${id}`).innerText = cart[id] || 0;
            renderTotal();
        }

        function renderTotal() {
            let total = 0;
            Object.keys(cart).forEach(k => {
                const p = products.find(x => x.id == k);
                total += p.price * cart[k];
            });
            document.getElementById('floatTotal').innerHTML = `Rp${total.toLocaleString('id-ID')}`;
            document.getElementById('cartFloat').style.display = total > 0 ? 'flex' : 'none';
            return total;
        }

        // --- FLOW STEP 1: BUKA FORM ---
        window.openForm = (type) => {
            selectedType = type;
            document.getElementById('mainOverlay').style.display = 'flex';
            document.getElementById('modalForm').classList.remove('hidden');
            document.getElementById('modalPayment').classList.add('hidden');
        }

        // --- LOCATION LOGIC ---
        window.getLoc = () => {
            const status = document.getElementById('locStatus');
            const btn = document.getElementById('btnConfirmForm');
            
            if(!navigator.geolocation) {
                status.innerText = "Browser tidak dukung lokasi";
                return;
            }
            status.innerText = "Mencari koordinat...";
            
            navigator.geolocation.getCurrentPosition((pos) => {
                userLocation = {
                    lat: pos.coords.latitude,
                    lng: pos.coords.longitude
                };
                status.innerText = `Lokasi Terkunci: ${userLocation.lat.toFixed(4)}, ${userLocation.lng.toFixed(4)}`;
                status.style.color = "green";
                // Aktifkan tombol konfirmasi
                btn.disabled = false;
                btn.style.background = "var(--success)";
                btn.style.cursor = "pointer";
            }, (err) => {
                status.innerText = "Gagal: Izinkan akses lokasi!";
                status.style.color = "red";
            });
        }

        // --- FLOW STEP 2: SUBMIT FORM & BUKA PAYMENT ---
        window.submitForm = async () => {
            const name = document.getElementById('inpName').value;
            const wa = document.getElementById('inpWA').value;
            const msg = document.getElementById('inpMsg').value;

            if(!name || !wa) return alert("Nama dan WA wajib diisi!");
            if(!userLocation) return alert("Mohon share lokasi dulu!");

            // Pindah Modal
            document.getElementById('modalForm').classList.add('hidden');
            const payModal = document.getElementById('modalPayment');
            payModal.classList.remove('hidden');

            const rawTotal = renderTotal();
            const fee = (selectedType === 'qris') ? 200 : 0;
            const finalTotal = rawTotal + fee;

            // Inisialisasi Konten Payment Box
            const box = document.getElementById('dynamicBox');
            const qCtr = document.getElementById('qrisControls');
            const cCtr = document.getElementById('cashControls');

            if(selectedType === 'qris') {
                document.getElementById('payTitle').innerText = "SCAN QRIS";
                qCtr.classList.remove('hidden');
                cCtr.classList.add('hidden');
                
                box.innerHTML = `<div class="spinner" style="margin:20px;">Generating QRIS...</div>`;
                
                // Fetch QRIS Image via Proxy
                try {
                    const res = await fetch('proxy.php', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify({
                            amount: finalTotal,
                            qris_statis: "00020101021126570011ID.DANA.WWW011893600915380003780002098000378000303UMI51440014ID.CO.QRIS.WWW0215ID10243620012490303UMI5204549953033605802ID5910Warr2 Shop6015Kab. Bandung Ba6105402936304BF4C"
                        })
                    });
                    const data = await res.json();
                    if(data.status === 'success') {
                        box.innerHTML = `
                            <img src="data:image/png;base64,${data.qris_base64}" class="qris-img">
                            <p style="margin:5px 0 0 0; font-weight:bold;">Rp${finalTotal.toLocaleString()}</p>
                        `;
                        startTimer();
                    } else throw new Error("Gagal");
                } catch(e) {
                    box.innerHTML = `<p style="color:red">Gagal memuat QRIS</p>`;
                }

            } else {
                // CASH
                document.getElementById('payTitle').innerText = "PEMBAYARAN CASH";
                qCtr.classList.add('hidden');
                cCtr.classList.remove('hidden');
                
                // Tampilkan rincian
                let itemList = '';
                Object.keys(cart).forEach(k => {
                    const p = products.find(x => x.id == k);
                    itemList += `<div>${p.name} x${cart[k]}</div>`;
                });

                box.innerHTML = `
                    <div style="text-align:left; width:100%; font-size:14px;">
                        <h4 style="margin:0 0 10px 0; border-bottom:1px dashed #ccc; padding-bottom:5px;">Rincian Pesanan</h4>
                        ${itemList}
                        <h3 style="margin:10px 0; text-align:right;">Total: Rp${finalTotal.toLocaleString()}</h3>
                        <div style="background:#fff3cd; padding:10px; border-radius:8px; font-size:12px;">
                            Note: Mohon siapkan uang pas saat kurir datang.
                        </div>
                    </div>
                `;
            }

            // SIMPAN KE FIREBASE
            const newRef = push(ref(db, 'transactions'));
            currentTxKey = newRef.key;
            
            set(newRef, {
                type: selectedType,
                customer: {
                    name: name,
                    wa: wa,
                    msg: msg,
                    lat: userLocation.lat,
                    lng: userLocation.lng
                },
                cart: cart,
                total: finalTotal,
                status: 'pending',
                timestamp: Date.now()
            });

            // Jika Cash, langsung kirim notif telegram (Tanpa gambar)
            if(selectedType === 'cash') {
                sendTelegram(name, wa, msg, userLocation, finalTotal, 'CASH', null);
            }

            // LISTENER STATUS TRANSAKSI (Untuk Animasi)
            onValue(ref(db, `transactions/${currentTxKey}`), (snap) => {
                const val = snap.val();
                if(!val) return;
                
                const dBox = document.getElementById('dynamicBox');
                
                if(val.status === 'confirmed') {
                    // Animasi Centang
                    dBox.innerHTML = `
                        <div class="anim-container">
                            <div class="check-icon">✓</div>
                            <h4 style="color:var(--success); margin:0;">BERHASIL</h4>
                            <small>Pesanan diproses</small>
                        </div>
                    `;
                    document.getElementById('paymentControls').style.display = 'none';
                    clearInterval(timerInterval);
                } else if(val.status === 'rejected') {
                    // Animasi Silang
                    dBox.innerHTML = `
                        <div class="anim-container">
                            <div class="cross-icon">✕</div>
                            <h4 style="color:var(--danger); margin:0;">DITOLAK</h4>
                            <small>Hubungi Admin</small>
                        </div>
                    `;
                    document.getElementById('paymentControls').style.display = 'none';
                    clearInterval(timerInterval);
                }
            });
        }

        // --- TELEGRAM SENDER ---
        window.handleFile = (input) => {
            if(input.files && input.files[0]) {
                document.getElementById('btnFileText').innerText = "File: " + input.files[0].name;
            }
        }

        window.sendProof = () => {
            const input = document.getElementById('fileProof');
            if(!input.files || !input.files[0]) return alert("Pilih foto bukti dulu!");
            
            // Ambil data form
            const name = document.getElementById('inpName').value;
            const wa = document.getElementById('inpWA').value;
            const msg = document.getElementById('inpMsg').value;
            const total = renderTotal() + 200; // QRIS fee

            sendTelegram(name, wa, msg, userLocation, total, 'QRIS', input.files[0]);
            
            alert("Bukti terkirim! Menunggu konfirmasi admin...");
        }

        async function sendTelegram(name, wa, msg, loc, total, type, fileObj) {
            const mapsLink = `https://www.google.com/maps?q=${loc.lat},${loc.lng}`;
            const caption = `
<b>PESANAN BARU (${type})</b>
👤 <b>Nama:</b> ${name}
📞 <b>WA:</b> ${wa}
💰 <b>Total:</b> Rp${total.toLocaleString()}
📝 <b>Pesan:</b> ${msg}

📍 <a href="${mapsLink}">LIHAT LOKASI DI MAPS</a>
            `.trim();

            const formData = new FormData();
            formData.append('action', 'send_telegram');
            formData.append('caption', caption);
            if(fileObj) {
                formData.append('photo', fileObj);
            }

            // Kirim ke Proxy PHP
            await fetch('proxy.php', {
                method: 'POST',
                body: formData
            });
        }

        function startTimer() {
            let sec = 300;
            const el = document.getElementById('timerDisplay');
            clearInterval(timerInterval);
            timerInterval = setInterval(() => {
                sec--;
                let m = Math.floor(sec/60);
                let s = sec%60;
                el.innerText = `${m}:${s<10?'0'+s:s}`;
                if(sec<=0) {
                    clearInterval(timerInterval);
                    alert("Waktu habis");
                    closeOverlay();
                }
            }, 1000);
        }

        window.closeOverlay = () => {
            document.getElementById('mainOverlay').style.display = 'none';
            clearInterval(timerInterval);
        }
    </script>
</body>
</html>
