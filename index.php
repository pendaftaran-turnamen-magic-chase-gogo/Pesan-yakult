<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir Yakult - PHP Version</title>
    <style>
        :root { --primary: #0056b3; --danger: #c82333; --success: #218838; --bg: #f4f6f9; }
        body { font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background: var(--bg); margin: 0; padding-bottom: 250px; -webkit-font-smoothing: antialiased; }
        
        /* Grid Produk */
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; padding: 15px; }
        .card { background: white; padding: 15px; border-radius: 20px; text-align: center; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        
        /* Gambar Rounded & Cover */
        .img-container {
            width: 100px; height: 100px; margin: 0 auto 10px auto;
            border-radius: 20px; overflow: hidden; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.05); 
        }
        .card img { width: 100%; height: 100%; object-fit: cover; display: block; }
        
        .prod-name { font-weight: bold; margin: 8px 0; font-size: 14px; color: #333; }
        .controls { display: flex; justify-content: center; gap: 10px; align-items: center; margin-top: 5px; }
        
        /* Tombol Bulat */
        .btn-c { width: 32px; height: 32px; border-radius: 50%; border: none; color: white; font-weight: bold; cursor: pointer; font-size: 18px; display: flex; align-items: center; justify-content: center; }
        .red { background: var(--danger); }
        .blue { background: var(--primary); }

        /* Footer */
        .cart { position: fixed; bottom: 0; left: 0; right: 0; background: white; padding: 20px; border-radius: 20px 20px 0 0; box-shadow: 0 -4px 20px rgba(0,0,0,0.1); z-index: 90; }
        .cart-item { display: flex; justify-content: space-between; border-bottom: 1px dashed #eee; padding: 8px 0; font-size: 14px; color: #333; }
        .total-box { font-weight: 800; text-align: right; margin: 15px 0; font-size: 16px; color: #000; }
        .actions { display: flex; gap: 10px; }
        .actions button { flex: 1; padding: 14px; border: none; border-radius: 8px; color: white; font-weight: bold; cursor: pointer; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; }
        
        /* Popup */
        .overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 1000; justify-content: center; align-items: center; backdrop-filter: blur(2px); }
        .popup { background: white; width: 85%; max-width: 340px; padding: 25px 20px; border-radius: 16px; text-align: center; position: relative; box-shadow: 0 10px 30px rgba(0,0,0,0.25); }
        .close-btn { position: absolute; top: 15px; right: 15px; background: var(--danger); color: white; border: none; width: 30px; height: 30px; border-radius: 8px; cursor: pointer; font-weight: bold; display: flex; align-items: center; justify-content: center; }
        .popup h3 { margin-top: 0; margin-bottom: 20px; font-size: 18px; color: #333; }

        /* QRIS Image & Loading */
        .qris-container { min-height: 200px; display: flex; align-items: center; justify-content: center; flex-direction: column; }
        .qris-img { width: 100%; max-width: 250px; height: auto; border-radius: 12px; display: none; }
        .loading-spinner { border: 4px solid #f3f3f3; border-top: 4px solid #3498db; border-radius: 50%; width: 30px; height: 30px; animation: spin 1s linear infinite; margin-bottom: 10px; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

        /* Rincian */
        .detail-box { background: #f8f9fa; border-radius: 10px; padding: 15px; margin: 15px 0; text-align: left; border: 1px solid #e9ecef; }
        .detail-row { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px; color: #555; }
        .detail-total { display: flex; justify-content: space-between; margin-top: 10px; padding-top: 10px; border-top: 1px dashed #ccc; font-weight: 800; font-size: 16px; color: #000; }
        
        .timer { font-size: 24px; color: var(--danger); font-weight: 700; margin: 15px 0 5px 0; letter-spacing: 1px; }
        .status-text { font-style: italic; color: #666; font-size: 14px; margin-top: 5px; }

        /* Animasi */
        .check-anim { width: 70px; height: 70px; background: var(--success); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 35px; margin: 20px auto; transform: scale(0); transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        .show-check { transform: scale(1); }
        .popup.success-bg { background-color: #e8f5e9; }
    </style>
</head>
<body>

    <div class="grid" id="productGrid"></div>

    <div class="cart">
        <div id="cartList"></div>
        <div class="total-box" id="grandTotal">Total (Rp0)</div>
        <div class="actions">
            <button class="blue" onclick="checkout('cash')" style="background-color: #0d6efd;">CASH</button>
            <button class="red" onclick="resetCart()" style="background-color: #dc3545;">BATALKAN</button>
            <button style="background-color: #198754;" onclick="checkout('qris')">QRIS</button>
        </div>
        <div style="text-align: center; margin-top: 15px;">
            <a href="admin11ad.php" style="font-size: 11px; color: #adb5bd; text-decoration: none;">Admin Access</a>
        </div>
    </div>

    <div class="overlay" id="paymentOverlay">
        <div class="popup" id="popupInner">
            <button class="close-btn" onclick="closePopup()">✕</button>
            <h3 id="popupTitle">Pembayaran</h3>
            
            <div id="qrisArea" style="display:none;">
                <div class="qris-container">
                    <div id="qrisLoading">
                        <div class="loading-spinner"></div>
                        <small>Membuat QRIS Dinamis...</small>
                    </div>
                    <img id="qrisImage" class="qris-img" src="" alt="QRIS Dinamis">
                </div>
                
                <div class="detail-box">
                    <div class="detail-row">
                        <span>Total Produk</span>
                        <span id="dispPrice">Rp0</span>
                    </div>
                    <div class="detail-row" style="color: #d63384;">
                        <span>Biaya Layanan</span>
                        <span id="dispFee">Rp200</span>
                    </div>
                    <div class="detail-total">
                        <span>Total Bayar</span>
                        <span id="dispFinal">Rp0</span>
                    </div>
                </div>

                <div class="timer" id="timerDisplay">05:00</div>
            </div>

            <div id="statusMsg" class="status-text">Menunggu Konfirmasi Admin...</div>
            <div id="checkIcon" class="check-anim">✓</div>
        </div>
    </div>

    <script type="module">
        import { db, ref, set, push, onValue, remove } from './firebase-config.js';

        // --- SOLUSI CORS: GUNAKAN PROXY PHP LOKAL ---
        const API_URL = "proxy.php"; 

        const STATIC_QRIS = "00020101021126570011ID.DANA.WWW011893600915380003780002098000378000303UMI51440014ID.CO.QRIS.WWW0215ID10243620012490303UMI5204549953033605802ID5910Warr2 Shop6015Kab. Bandung Ba6105402936304BF4C";

        const products = [
            { id: 1, name: "Yakult Original", price: 10500, img: "Yakult-original.png" },
            { id: 2, name: "Yakult Mangga", price: 10500, img: "Yakult-mangga.png" },
            { id: 3, name: "Yakult Light", price: 13000, img: "Yakult-light.png" },
            { id: 4, name: "Test Produk", price: 100, img: "test.png" }
        ];

        let cart = {};
        let currentTxKey = null;
        let timerInterval = null;

        // Render Produk
        const pGrid = document.getElementById('productGrid');
        products.forEach(p => {
            const div = document.createElement('div');
            div.className = 'card';
            div.innerHTML = `
                <div class="img-container">
                    <img src="img/${p.img}" alt="${p.name}">
                </div>
                <div class="prod-name">${p.name}</div>
                <div class="controls">
                    <button class="btn-c red" onclick="window.modCart(${p.id}, -1)">-</button>
                    <span style="font-size:14px; margin:0 5px;">Rp${p.price.toLocaleString('id-ID')}</span>
                    <button class="btn-c blue" onclick="window.modCart(${p.id}, 1)">+</button>
                </div>
            `;
            pGrid.appendChild(div);
        });

        window.modCart = (id, val) => {
            if(!cart[id]) cart[id] = 0;
            cart[id] += val;
            if(cart[id] <= 0) delete cart[id];
            renderCart();
        }

        window.resetCart = () => {
            cart = {};
            renderCart();
        }

        function renderCart() {
            const list = document.getElementById('cartList');
            const totalEl = document.getElementById('grandTotal');
            list.innerHTML = '';
            let total = 0;
            
            Object.keys(cart).forEach(k => {
                const p = products.find(x => x.id == k);
                const sub = p.price * cart[k];
                total += sub;
                list.innerHTML += `
                    <div class="cart-item">
                        <span>${p.name} (${cart[k]})</span>
                        <span>Rp${sub.toLocaleString('id-ID')}</span>
                    </div>`;
            });
            totalEl.innerText = `Total (Rp${total.toLocaleString('id-ID')})`;
            return total;
        }

        // --- FETCH KE PROXY PHP ---
        async function fetchQrisImage(amount) {
            try {
                // Request ke proxy.php (satu domain, jadi aman dari CORS)
                const response = await fetch(API_URL, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        amount: amount.toString(),
                        qris_statis: STATIC_QRIS
                    })
                });

                // Cek jika proxy error (misal 404 atau 500)
                if(!response.ok) throw new Error("Server Proxy Error");

                const data = await response.json();

                if (data.status === 'success' && data.qris_base64) {
                    return data.qris_base64;
                } else {
                    throw new Error(data.message || 'Gagal generate QRIS');
                }
            } catch (error) {
                console.error("API Error:", error);
                alert("Gagal memuat QRIS. Pastikan file proxy.php ada dan berjalan di server PHP.");
                return null;
            }
        }

        // --- CHECKOUT ---
        window.checkout = async (type) => {
            const total = renderCart();
            if(total === 0) return alert("Pilih produk dulu!");

            const fee = (type === 'qris') ? 200 : 0;
            const final = total + fee;

            const overlay = document.getElementById('paymentOverlay');
            const qrisArea = document.getElementById('qrisArea');
            const popupInner = document.getElementById('popupInner');
            
            overlay.style.display = 'flex';
            popupInner.classList.remove('success-bg');
            document.getElementById('checkIcon').classList.remove('show-check');
            document.getElementById('statusMsg').innerText = "Menunggu Konfirmasi Admin...";
            document.getElementById('statusMsg').style.display = 'block';

            if(type === 'qris') {
                qrisArea.style.display = 'block';
                document.getElementById('dispPrice').innerText = `Rp${total.toLocaleString('id-ID')}`;
                document.getElementById('dispFee').innerText = `Rp${fee.toLocaleString('id-ID')}`;
                document.getElementById('dispFinal').innerText = `Rp${final.toLocaleString('id-ID')}`;

                document.getElementById('qrisLoading').style.display = 'block';
                document.getElementById('qrisImage').style.display = 'none';
                
                const base64Code = await fetchQrisImage(final);
                
                if (base64Code) {
                    document.getElementById('qrisLoading').style.display = 'none';
                    const imgEl = document.getElementById('qrisImage');
                    imgEl.src = `data:image/png;base64,${base64Code}`;
                    imgEl.style.display = 'block';
                    startTimer();
                } else {
                    closePopup();
                    return; 
                }
            } else {
                qrisArea.style.display = 'none';
            }

            // Firebase Logic
            const newRef = push(ref(db, 'transactions'));
            currentTxKey = newRef.key;
            
            set(newRef, {
                type: type,
                cart: cart,
                rawTotal: total,
                fee: fee,
                finalTotal: final,
                status: 'pending',
                timestamp: Date.now()
            });

            onValue(ref(db, `transactions/${currentTxKey}`), (snap) => {
                const val = snap.val();
                if(!val) return;

                if(val.status === 'confirmed') {
                    successAnimation();
                } else if (val.status === 'rejected') {
                    clearInterval(timerInterval);
                    alert("Pembayaran Dibatalkan oleh Admin");
                    closePopup();
                }
            });
        }

        function startTimer() {
            let sec = 300; 
            const tEl = document.getElementById('timerDisplay');
            tEl.innerText = "05:00";
            
            clearInterval(timerInterval);
            timerInterval = setInterval(() => {
                sec--;
                let m = Math.floor(sec / 60);
                let s = sec % 60;
                tEl.innerText = `${m < 10 ? '0'+m : m}:${s < 10 ? '0'+s : s}`;
                
                if(sec <= 0) {
                    clearInterval(timerInterval);
                    alert("Waktu Habis");
                    closePopup();
                }
            }, 1000);
        }

        function successAnimation() {
            clearInterval(timerInterval);
            document.getElementById('qrisArea').style.display = 'none';
            document.getElementById('statusMsg').style.display = 'none'; 
            
            const popup = document.getElementById('popupInner');
            popup.classList.add('success-bg');
            popup.innerHTML = `
                <div class="check-anim show-check">✓</div>
                <h3 style="color:green; margin-top:10px;">PEMBAYARAN BERHASIL!</h3>
                <button class="close-btn" onclick="location.reload()" style="position:relative; top:0; right:0; margin-top:10px; width:100px;">Tutup</button>
            `;
            cart = {};
            renderCart();
        }

        window.closePopup = () => {
            document.getElementById('paymentOverlay').style.display = 'none';
            clearInterval(timerInterval);
            if(currentTxKey) {
                currentTxKey = null;
            }
            if(document.querySelector('.success-bg')) {
                location.reload(); 
            }
        }
    </script>
</body>
</html>
