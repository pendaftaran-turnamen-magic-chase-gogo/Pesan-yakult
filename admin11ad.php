<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - TOKOTOPARYA</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --dark: #0f172a;
            --gold: #f59e0b;
            --sidebar: #1e293b;
            --card: #ffffff;
            --text: #334155;
        }
        body { font-family: 'Montserrat', sans-serif; background: #f1f5f9; margin: 0; display: flex; height: 100vh; overflow: hidden; }
        
        /* LOGIN GATE */
        .login-gate { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: var(--dark); z-index: 999; display: flex; flex-direction: column; justify-content: center; align-items: center; color: white; }
        .login-box { background: rgba(255,255,255,0.05); padding: 40px; border-radius: 20px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1); width: 300px; text-align: center; }
        .inp-login { width: 100%; padding: 12px; margin: 10px 0; border: none; border-radius: 8px; background: rgba(255,255,255,0.1); color: white; box-sizing: border-box; }
        .btn-login { width: 100%; padding: 12px; background: var(--gold); border: none; border-radius: 8px; color: var(--dark); font-weight: bold; cursor: pointer; margin-top: 10px; transition: 0.3s; }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(245, 158, 11, 0.4); }

        /* DASHBOARD */
        .sidebar { width: 250px; background: var(--sidebar); color: white; padding: 20px; display: flex; flex-direction: column; box-shadow: 5px 0 15px rgba(0,0,0,0.05); }
        .brand { font-size: 20px; font-weight: 800; color: var(--gold); margin-bottom: 40px; letter-spacing: 1px; }
        .menu-item { padding: 12px; margin-bottom: 5px; border-radius: 8px; cursor: pointer; color: #94a3b8; transition: 0.2s; }
        .menu-item:hover, .menu-item.active { background: rgba(255,255,255,0.1); color: white; }

        .content { flex: 1; padding: 30px; overflow-y: auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        
        .card-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: var(--card); padding: 20px; border-radius: 16px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); border-left: 4px solid var(--gold); }
        .stat-val { font-size: 24px; font-weight: 800; color: var(--dark); margin-top: 5px; }
        
        .req-card { background: white; border-radius: 16px; padding: 20px; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 5px 20px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; }
        .user-info { font-size: 14px; color: #64748b; }
        .user-info b { color: var(--dark); font-size: 16px; display: block; margin-bottom: 5px; }
        
        .act-btn { padding: 8px 16px; border-radius: 8px; border: none; cursor: pointer; font-weight: bold; margin-left: 5px; color: white; }
        .btn-yes { background: #10b981; }
        .btn-no { background: #ef4444; }
        .btn-wa { background: #25D366; text-decoration: none; display: inline-block; font-size: 12px; padding: 5px 10px; border-radius: 4px; color: white; margin-top: 5px; }
        .btn-map { background: #3b82f6; text-decoration: none; display: inline-block; font-size: 12px; padding: 5px 10px; border-radius: 4px; color: white; }

        table { width: 100%; border-collapse: collapse; background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.02); font-size: 13px; }
        th { background: #f8fafc; padding: 15px; text-align: left; color: #64748b; font-weight: 600; }
        td { padding: 15px; border-bottom: 1px solid #f1f5f9; color: #334155; }
    </style>
</head>
<body>

    <div class="login-gate" id="loginGate">
        <div class="login-box">
            <h2 style="margin-top:0;">ADMIN ACCESS</h2>
            <input type="text" id="uName" class="inp-login" placeholder="Username">
            <input type="password" id="uPass" class="inp-login" placeholder="Password">
            <button class="btn-login" onclick="doLogin()">SECURE LOGIN</button>
        </div>
    </div>

    <div class="sidebar">
        <div class="brand">TOKOTOPARYA</div>
        <div class="menu-item active">Dashboard</div>
        <div class="menu-item" onclick="wipeData()">Hapus Riwayat</div>
        <div class="menu-item" onclick="doLogout()">Logout</div>
    </div>

    <div class="content">
        <div class="header">
            <h2 style="margin:0; color:var(--dark);">Dashboard Overview</h2>
            <div style="font-size:14px; color:#64748b;" id="clock">00:00:00</div>
        </div>

        <div class="card-grid">
            <div class="stat-card">
                <small>Pendapatan Bersih</small>
                <div class="stat-val" id="sInc">Rp0</div>
            </div>
            <div class="stat-card">
                <small>Total Fee Layanan</small>
                <div class="stat-val" id="sFee">Rp0</div>
            </div>
            <div class="stat-card">
                <small>Transaksi Pending</small>
                <div class="stat-val" id="sPend">0</div>
            </div>
        </div>

        <h3 style="color:var(--dark);">Permintaan Masuk</h3>
        <div id="requestList"></div>

        <h3 style="color:var(--dark); margin-top:40px;">Riwayat Transaksi</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Tipe</th>
                    <th>Total</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody id="histTable"></tbody>
        </table>
    </div>

    <script type="module">
        import { db, ref, set, update, remove, onValue, serverTimestamp } from './firebase-config.js';

        // --- AUTH LOGIC ---
        const USER = "arya1212";
        const PASS = "ab87bCBG$@y5542hhKLnb";

        window.doLogin = () => {
            const u = document.getElementById('uName').value;
            const p = document.getElementById('uPass').value;
            const remember = true; // Sesuai request "klik" 1 hari

            if(u === USER && p === PASS) {
                const expiry = new Date().getTime() + (24 * 60 * 60 * 1000); // 1 Hari
                localStorage.setItem('admin_auth', expiry);
                document.getElementById('loginGate').style.display = 'none';
                initApp();
            } else {
                alert("Akses Ditolak!");
            }
        }
        
        window.doLogout = () => {
            localStorage.removeItem('admin_auth');
            location.reload();
        }

        // Cek Login saat load
        const auth = localStorage.getItem('admin_auth');
        if(auth && new Date().getTime() < parseInt(auth)) {
            document.getElementById('loginGate').style.display = 'none';
            initApp();
        }

        // --- APP LOGIC ---
        function initApp() {
            setInterval(() => document.getElementById('clock').innerText = new Date().toLocaleTimeString(), 1000);

            // Listener Pending
            onValue(ref(db, 'transactions'), (snap) => {
                const list = document.getElementById('requestList');
                list.innerHTML = '';
                let pendCount = 0;
                
                const data = snap.val();
                if(data) {
                    Object.keys(data).forEach(k => {
                        const t = data[k];
                        if(t.status === 'pending') {
                            pendCount++;
                            const mapLink = `https://www.google.com/maps?q=${t.customer.lat},${t.customer.lng}`;
                            const waLink = `https://wa.me/${t.customer.wa}`;
                            
                            const div = document.createElement('div');
                            div.className = 'req-card';
                            div.innerHTML = `
                                <div class="user-info">
                                    <b>${t.customer.name.toUpperCase()} (Rp${t.total.toLocaleString()})</b>
                                    Tipe: <span style="color:${t.type=='qris'?'#d4af37':'#10b981'}; font-weight:bold;">${t.type.toUpperCase()}</span><br>
                                    Pesan: "${t.customer.msg}"<br>
                                    <a href="${waLink}" target="_blank" class="btn-wa">Chat WA</a>
                                    <a href="${mapLink}" target="_blank" class="btn-map">Lihat Lokasi</a>
                                </div>
                                <div>
                                    <button class="act-btn btn-no" onclick="act('${k}', 'reject')">TOLAK</button>
                                    <button class="act-btn btn-yes" onclick="act('${k}', 'confirm', ${t.total}, ${t.type=='qris'?200:0}, '${t.customer.name}')">TERIMA</button>
                                </div>
                            `;
                            list.appendChild(div);
                        }
                    });
                }
                document.getElementById('sPend').innerText = pendCount;
            });

            // Listener History
            onValue(ref(db, 'history'), (snap) => {
                const tb = document.getElementById('histTable');
                tb.innerHTML = '';
                let inc = 0, fee = 0;
                
                const data = snap.val();
                if(data) {
                    const sorted = Object.values(data).sort((a,b) => b.timestamp - a.timestamp);
                    sorted.forEach(d => {
                        inc += d.amount;
                        fee += d.fee;
                        
                        const date = new Date(d.timestamp).toLocaleString();
                        tb.innerHTML += `
                            <tr>
                                <td>${d.id}</td>
                                <td>${d.customer}</td>
                                <td>${d.type.toUpperCase()}</td>
                                <td>Rp${(d.amount+d.fee).toLocaleString()}</td>
                                <td>${date}</td>
                            </tr>
                        `;
                    });
                }
                document.getElementById('sInc').innerText = `Rp${inc.toLocaleString()}`;
                document.getElementById('sFee').innerText = `Rp${fee.toLocaleString()}`;
            });
        }

        window.act = (key, action, total, fee, name) => {
            if(action === 'reject') {
                update(ref(db, `transactions/${key}`), { status: 'rejected' });
                // Hapus data setelah delay agar animasi user selesai
                setTimeout(() => remove(ref(db, `transactions/${key}`)), 5000);
            } else {
                update(ref(db, `transactions/${key}`), { status: 'confirmed' });
                
                // Masuk History
                const now = new Date();
                const id = `TRX${now.getTime()}`;
                set(ref(db, `history/${id}`), {
                    id: id,
                    customer: name,
                    type: 'income',
                    amount: total - fee,
                    fee: fee,
                    timestamp: serverTimestamp()
                });

                setTimeout(() => remove(ref(db, `transactions/${key}`)), 5000);
            }
        }
        
        window.wipeData = () => {
            if(confirm("Yakin hapus semua riwayat?")) remove(ref(db, 'history'));
        }
    </script>
</body>
</html>
