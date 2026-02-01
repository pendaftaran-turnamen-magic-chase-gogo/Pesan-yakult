<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - TOKO YAKULT</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;800&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <style>
        :root { --dark: #0f172a; --gold: #f59e0b; --sidebar: #1e293b; --card: #ffffff; --text: #334155; }
        body { font-family: 'Montserrat', sans-serif; background: #f1f5f9; margin: 0; display: flex; height: 100vh; overflow: hidden; }
        
        .sidebar { width: 250px; background: var(--sidebar); color: white; padding: 20px; display: flex; flex-direction: column; box-shadow: 5px 0 15px rgba(0,0,0,0.05); }
        .brand { font-size: 20px; font-weight: 800; color: var(--gold); margin-bottom: 40px; letter-spacing: 1px; }
        .menu-item { padding: 12px; margin-bottom: 5px; border-radius: 8px; cursor: pointer; color: #94a3b8; transition: 0.2s; }
        .menu-item:hover, .menu-item.active { background: rgba(255,255,255,0.1); color: white; }
        
        .content { flex: 1; padding: 30px; overflow-y: auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        
        .card-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: var(--card); padding: 20px; border-radius: 16px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); border-left: 4px solid var(--gold); }
        .stat-val { font-size: 24px; font-weight: 800; color: var(--dark); margin-top: 5px; }
        
        .req-card { background: white; border-radius: 16px; padding: 20px; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 5px 20px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; }
        .user-info { font-size: 14px; color: #64748b; }
        .user-info b { color: var(--dark); font-size: 16px; display: block; margin-bottom: 5px; }
        
        .btn { padding: 8px 16px; border-radius: 8px; border: none; cursor: pointer; font-weight: bold; margin-left: 5px; color: white; font-family: 'Montserrat'; font-size: 12px; }
        .btn-yes { background: #10b981; }
        .btn-no { background: #ef4444; }
        .btn-blue { background: #3b82f6; }
        .btn-wa { background: #25D366; text-decoration: none; display: inline-block; font-size: 12px; padding: 8px 16px; border-radius: 8px; color: white; }

        .modal-bg { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 999; justify-content: center; align-items: center; backdrop-filter: blur(5px); }
        .modal-box { background: white; padding: 30px; border-radius: 20px; width: 450px; max-width: 90%; box-shadow: 0 20px 40px rgba(0,0,0,0.2); }
        .item-row { display: flex; justify-content: space-between; border-bottom: 1px dashed #eee; padding: 10px 0; font-size: 14px; }
        
        #loginGate { position: fixed; width: 100%; height: 100%; background: var(--dark); z-index: 1000; display: flex; justify-content: center; align-items: center; color: white; }
        .login-box { background: rgba(255,255,255,0.05); padding: 40px; border-radius: 24px; border: 1px solid rgba(255,255,255,0.1); text-align: center; width: 320px; }
        .inp-login { width: 100%; padding: 15px; margin: 10px 0; border: none; border-radius: 12px; background: rgba(255,255,255,0.1); color: white; box-sizing: border-box; }
        .btn-login { width: 100%; padding: 15px; background: var(--gold); border: none; border-radius: 12px; color: var(--dark); font-weight: 800; cursor: pointer; margin-top: 10px; }
        
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 16px; overflow: hidden; margin-top: 20px; }
        th { background: #f8fafc; padding: 15px; text-align: left; color: #64748b; }
        td { padding: 15px; border-bottom: 1px solid #f1f5f9; }
    </style>
</head>
<body>

    <div id="loginGate">
        <div class="login-box">
            <h2 style="color:var(--gold);">ADMIN ACCESS</h2>
            <input type="text" id="user" class="inp-login" placeholder="Username">
            <input type="password" id="pass" class="inp-login" placeholder="Password">
            <button onclick="login()" class="btn-login">LOGIN</button>
        </div>
    </div>

    <div class="modal-bg" id="detailModal">
        <div class="modal-box">
            <h3>Detail Pesanan</h3>
            <div id="detailContent"></div>
            <div style="text-align:right; margin-top:20px; font-weight:800; font-size:18px;" id="detailTotal"></div>
            <button onclick="document.getElementById('detailModal').style.display='none'" class="btn btn-no" style="margin-top:20px; width:100%; padding:15px;">TUTUP</button>
        </div>
    </div>

    <div class="modal-bg" id="lossModal">
        <div class="modal-box">
            <h3>Input Kerugian</h3>
            <input type="number" id="lossAmount" class="inp-login" style="background:#f1f5f9; color:#333;" placeholder="Nominal (Contoh: 5000)">
            <input type="text" id="lossMsg" class="inp-login" style="background:#f1f5f9; color:#333;" placeholder="Keterangan (Contoh: beli gorengan)">
            <button onclick="submitLoss()" class="btn-login" style="margin-top:20px;">SIMPAN</button>
            <button onclick="document.getElementById('lossModal').style.display='none'" class="btn" style="color:#64748b; width:100%; margin-top:10px;">Batal</button>
        </div>
    </div>

    <div class="sidebar">
        <div class="brand">ADMIN TOKO</div>
        <div class="menu-item active">Dashboard</div>
        <div class="menu-item" onclick="document.getElementById('lossModal').style.display='flex'">Input Kerugian</div>
        <div style="margin-top:auto;">
            <button class="btn btn-no" onclick="logout()" style="width:100%; margin:0;">LOGOUT</button>
        </div>
    </div>

    <div class="content">
        <div class="header">
            <h2>Real-time Dashboard</h2>
        </div>
        
        <div class="card-grid">
            <div class="stat-card">
                <small>Produk Terjual</small>
                <div class="stat-val" id="statProduct">Rp0</div>
            </div>
            <div class="stat-card">
                <small>Total Fee</small>
                <div class="stat-val" id="statFee">Rp0</div>
            </div>
            <div class="stat-card">
                <small>Total Kerugian</small>
                <div class="stat-val" id="statLoss" style="color:var(--danger);">Rp0</div>
            </div>
            <div class="stat-card" style="border-left-color:#10b981;">
                <small>Pendapatan Bersih</small>
                <div class="stat-val" id="statNet" style="color:#10b981;">Rp0</div>
            </div>
        </div>

        <h3 style="margin-top:40px;">Pesanan Masuk (Pending)</h3>
        <div id="reqList">Loading...</div>

        <h3 style="margin-top:40px;">Riwayat Transaksi</h3>
        <table>
            <thead><tr><th>Waktu</th><th>Nama</th><th>Total</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody id="histList"></tbody>
        </table>
        
        <h3 style="margin-top:40px;">Laporan Kerugian</h3>
        <table>
            <thead><tr><th>Waktu</th><th>Keterangan</th><th>Nominal</th></tr></thead>
            <tbody id="lossList"></tbody>
        </table>
    </div>

    <script type="module">
        import { db, ref, update, remove, onValue, set, serverTimestamp } from './firebase-config.js';

        // AUTH SEDERHANA
        window.login = () => {
            const u = document.getElementById('user').value;
            const p = document.getElementById('pass').value;
            if(u === "arya1212" && p === "ab87bCBG$@y5542hhKLnb") {
                const expiry = Date.now() + (24 * 60 * 60 * 1000);
                localStorage.setItem('adm_expiry', expiry);
                document.getElementById('loginGate').style.display = 'none';
                init();
            } else alert("Username/Password Salah!");
        }
        window.logout = () => { localStorage.removeItem('adm_expiry'); location.reload(); }
        
        const expiry = localStorage.getItem('adm_expiry');
        if(expiry && Date.now() < parseInt(expiry)) {
            document.getElementById('loginGate').style.display = 'none';
            init();
        }

        let historyDataCache = {};

        function init() {
            // LISTENER PESANAN PENDING
            onValue(ref(db, 'transactions'), (snap) => {
                const div = document.getElementById('reqList');
                div.innerHTML = '';
                const data = snap.val();
                let hasPending = false;

                if(data) {
                    Object.keys(data).forEach(k => {
                        const t = data[k];
                        // Simpan data untuk akses tombol konfirmasi
                        window['tx_'+k] = t; 
                        
                        if(t.status === 'pending') {
                            hasPending = true;
                            const itemsJson = JSON.stringify(t.items).replace(/"/g, '&quot;');
                            div.innerHTML += `
                            <div class="req-card">
                                <div class="user-info">
                                    <b>${t.customer.name} (${t.type.toUpperCase()})</b>
                                    ${t.customer.wa} | ${t.customer.msg || '-'}
                                    <div style="color:var(--gold); font-weight:800; font-size:16px; margin-top:5px;">Rp${t.total.toLocaleString()}</div>
                                </div>
                                <div>
                                    <button class="btn btn-blue" onclick="showDetail('${itemsJson}', ${t.total})">Detail</button>
                                    <a href="https://wa.me/${t.customer.wa}" target="_blank" class="btn btn-wa">WhatsApp</a>
                                    <button class="btn btn-no" onclick="act('${k}', 'rejected')">Tolak</button>
                                    <button class="btn btn-yes" onclick="act('${k}', 'confirmed')">Terima</button>
                                </div>
                            </div>`;
                        }
                    });
                }
                if(!hasPending) div.innerHTML = "<i>Tidak ada pesanan aktif.</i>";
            });

            // LISTENER RIWAYAT & STATISTIK
            onValue(ref(db, 'history'), (snap) => {
                const hist = document.getElementById('histList');
                hist.innerHTML = '';
                let totalProd = 0;
                let totalFee = 0;
                const data = snap.val();
                historyDataCache = data || {};

                if(data) {
                    const sorted = Object.values(data).sort((a,b)=>b.timestamp-a.timestamp);
                    sorted.forEach(d => {
                        // hitung statistik
                        totalProd += (d.total - d.fee);
                        totalFee += d.fee;
                        
                        hist.innerHTML += `
                        <tr>
                            <td>${new Date(d.timestamp).toLocaleString()}</td>
                            <td>${d.customer_name}</td>
                            <td>Rp${d.total.toLocaleString()}</td>
                            <td><span style="color:#10b981; font-weight:bold;">Selesai</span></td>
                            <td><button class="btn btn-blue" style="font-size:10px; padding:5px 10px;" onclick="downloadStruk('${d.id}')">Download Struk</button></td>
                        </tr>`;
                    });
                }
                window.totalProd = totalProd;
                window.totalFee = totalFee;
                updateStats();
            });

            // LISTENER LOSSES
            onValue(ref(db, 'losses'), (snap) => {
                const list = document.getElementById('lossList');
                list.innerHTML = '';
                let totalLoss = 0;
                const data = snap.val();
                if(data) {
                    Object.values(data).sort((a,b)=>b.timestamp-a.timestamp).forEach(l => {
                        totalLoss += l.amount;
                        list.innerHTML += `<tr><td>${new Date(l.timestamp).toLocaleString()}</td><td>${l.msg}</td><td>Rp${l.amount.toLocaleString()}</td></tr>`;
                    });
                }
                window.totalLoss = totalLoss;
                updateStats();
            });
        }

        function updateStats() {
            const p = window.totalProd || 0;
            const f = window.totalFee || 0;
            const l = window.totalLoss || 0;
            document.getElementById('statProduct').innerText = `Rp${p.toLocaleString()}`;
            document.getElementById('statFee').innerText = `Rp${f.toLocaleString()}`;
            document.getElementById('statLoss').innerText = `Rp${l.toLocaleString()}`;
            document.getElementById('statNet').innerText = `Rp${(p + f - l).toLocaleString()}`;
        }

        window.showDetail = (itemsStr, total) => {
            const items = JSON.parse(itemsStr);
            const box = document.getElementById('detailContent');
            box.innerHTML = '';
            items.forEach(i => {
                box.innerHTML += `<div class="item-row"><span>${i.name} x${i.qty}</span><span>Rp${(i.price * i.qty).toLocaleString()}</span></div>`;
            });
            document.getElementById('detailTotal').innerText = "TOTAL: Rp" + total.toLocaleString();
            document.getElementById('detailModal').style.display = 'flex';
        }

        // AKSI TERIMA / TOLAK
        window.act = (key, status) => {
            const tx = window['tx_'+key];
            if(!tx) return;

            // 1. Update Status di Transactions agar UI User berubah (Hijau/Merah)
            update(ref(db, `transactions/${key}`), { status: status });

            // 2. Jika Diterima, pindahkan ke History
            if(status === 'confirmed') {
                const hid = 'INV-'+Date.now();
                const historyData = {
                    id: hid,
                    customer_name: tx.customer.name,
                    customer_wa: tx.customer.wa,
                    items: tx.items,
                    total: tx.total,
                    fee: tx.fee,
                    timestamp: serverTimestamp()
                };
                set(ref(db, `history/${hid}`), historyData);
            }
            
            // 3. Kirim Notifikasi Telegram
            const formData = new FormData();
            formData.append('action', 'send_telegram');
            formData.append('caption', `<b>UPDATE STATUS PESANAN</b>\n\nNama: ${tx.customer.name}\nStatus: ${status === 'confirmed' ? '✅ DITERIMA' : '❌ DITOLAK'}\nTotal: Rp${tx.total.toLocaleString()}`);
            fetch('proxy.php', { method: 'POST', body: formData });
            
            // 4. Hapus data dari daftar request setelah 5 detik agar bersih
            setTimeout(() => {
                remove(ref(db, `transactions/${key}`));
            }, 5000);
        }

        window.submitLoss = () => {
            const amt = parseInt(document.getElementById('lossAmount').value);
            const msg = document.getElementById('lossMsg').value;
            if(!amt || !msg) return alert("Isi nominal dan keterangan!");
            const lid = 'LOSS'+Date.now();
            set(ref(db, `losses/${lid}`), { amount: amt, msg: msg, timestamp: serverTimestamp() });
            document.getElementById('lossModal').style.display = 'none';
            document.getElementById('lossAmount').value = '';
            document.getElementById('lossMsg').value = '';
        }

        // FITUR DOWNLOAD STRUK PDF
        window.downloadStruk = (id) => {
            const { jsPDF } = window.jspdf;
            const data = historyDataCache[id];
            
            if(!data) return alert("Data struk tidak ditemukan!");

            const doc = new jsPDF({
                orientation: "portrait",
                unit: "mm",
                format: [80, 150] // Ukuran kertas struk thermal 80mm
            });

            doc.setFontSize(10);
            doc.text("TOKO SHOP YAKULT", 40, 10, { align: "center" });
            doc.setFontSize(8);
            doc.text("Jln. Raya Yakult No. 1", 40, 15, { align: "center" });
            doc.text("------------------------------------------------", 40, 20, { align: "center" });

            doc.text(`Tgl: ${new Date(data.timestamp).toLocaleString()}`, 5, 25);
            doc.text(`ID: ${data.id}`, 5, 30);
            doc.text(`Pelanggan: ${data.customer_name}`, 5, 35);
            doc.text("------------------------------------------------", 40, 40, { align: "center" });

            let y = 45;
            data.items.forEach(item => {
                doc.text(`${item.name} x${item.qty}`, 5, y);
                doc.text(`Rp${(item.price * item.qty).toLocaleString()}`, 75, y, { align: "right" });
                y += 5;
            });
            
            if(data.fee > 0) {
                doc.text(`Biaya Layanan`, 5, y);
                doc.text(`Rp${data.fee.toLocaleString()}`, 75, y, { align: "right" });
                y += 5;
            }

            doc.text("------------------------------------------------", 40, y, { align: "center" });
            y += 5;
            
            doc.setFontSize(10);
            doc.text("TOTAL", 5, y);
            doc.text(`Rp${data.total.toLocaleString()}`, 75, y, { align: "right" });

            doc.setFontSize(8);
            doc.text("Terima kasih sudah berbelanja!", 40, y + 15, { align: "center" });

            doc.save(`Struk-${data.customer_name}.pdf`);
        }
    </script>
</body>
</html>
