<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel Yakult</title>
    <script src="https://cdn.sheetjs.com/xlsx-0.20.0/package/dist/xlsx.full.min.js"></script>
    <style>
        body { font-family: monospace; background: #eee; padding: 20px; }
        .gate { position: fixed; top:0; left:0; width:100%; height:100%; background:#222; color:#fff; display:flex; flex-direction:column; align-items:center; justify-content:center; z-index:99; }
        .code { font-size: 30px; letter-spacing: 5px; border: 1px solid #555; padding: 10px; cursor: pointer; }
        
        .panel { display: none; background: white; padding: 20px; max-width: 800px; margin: 0 auto; }
        .stats { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; margin-bottom: 20px; }
        .box { background: #f8f9fa; padding: 15px; border: 1px solid #ddd; text-align: center; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 12px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #007bff; color: white; }
        
        .incoming { background: #fff3cd; padding: 10px; margin-bottom: 10px; border: 1px solid #ffeeba; display: flex; justify-content: space-between; align-items: center; }
        
        /* Modal Cash */
        .modal { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); justify-content:center; align-items:center; }
        .m-content { background:white; padding:20px; width:300px; }
    </style>
</head>
<body>

    <div class="gate" id="loginGate">
        <h3>ADMIN VERIFICATION</h3>
        <div class="code" id="secretCode" onclick="copyC()">?????</div>
        <div style="margin:10px;">Klik kode untuk salin</div>
        <input type="text" id="inputC" placeholder="Input Kode Disini">
        <button onclick="login()">MASUK</button>
        <div onclick="genC()" style="margin-top:20px; cursor:pointer; text-decoration:underline;">Refresh Kode</div>
    </div>

    <div class="panel" id="mainPanel">
        <h2 style="display:flex; justify-content:space-between;">
            ADMIN YAKULT 
            <span id="realtimeClock" style="font-size:16px;"></span>
        </h2>
        
        <div class="stats">
            <div class="box">
                <small>Total Produk Terjual</small><br>
                <b id="sProd">Rp0</b>
            </div>
            <div class="box">
                <small>Total Fee</small><br>
                <b id="sFee">Rp0</b>
            </div>
            <div class="box">
                <small>Total Pengeluaran</small><br>
                <b id="sExp">Rp0</b>
                <br><button onclick="openExp()">+ Input</button>
            </div>
        </div>

        <h3>Permintaan Konfirmasi</h3>
        <div id="reqList"></div>

        <h3>Riwayat Struk (ID: YKLT...)</h3>
        <button onclick="downloadXLS()">Download Excel</button>
        <button onclick="wipeData()" style="background:red; color:white;">Hapus Semua</button>
        <table id="tableHist">
            <thead>
                <tr><th>ID</th><th>Tipe</th><th>Detail</th><th>Nominal</th><th>Fee</th></tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <div class="modal" id="modalCash">
        <div class="m-content">
            <h4>Konfirmasi Cash</h4>
            <p>Total: <span id="cTotal"></span></p>
            Input Uang: <input type="number" id="cInput" oninput="calcChange()">
            <p>Kembalian: <b id="cChange">Rp0</b></p>
            <button onclick="confirmCash()">PROSES</button>
            <button onclick="document.getElementById('modalCash').style.display='none'">BATAL</button>
        </div>
    </div>

    <div class="modal" id="modalExp">
        <div class="m-content">
            <h4>Input Pengeluaran</h4>
            Ket: <input type="text" id="eNote"><br><br>
            Nominal: <input type="number" id="eNom"><br><br>
            <button onclick="saveExp()">SIMPAN</button>
            <button onclick="document.getElementById('modalExp').style.display='none'">BATAL</button>
        </div>
    </div>

    <script type="module">
        import { db, ref, set, push, onValue, update, remove, serverTimestamp } from './firebase-config.js';

        // --- LOGIN LOGIC ---
        let secret = "";
        window.genC = () => {
            secret = Math.random().toString(36).substring(2,7).toUpperCase();
            document.getElementById('secretCode').innerText = secret;
        }
        window.copyC = () => {
            navigator.clipboard.writeText(secret);
            alert("Kode disalin");
        }
        window.login = () => {
            if(document.getElementById('inputC').value.toUpperCase() === secret) {
                document.getElementById('loginGate').style.display = 'none';
                document.getElementById('mainPanel').style.display = 'block';
                startAdmin();
            } else {
                alert("Kode Salah");
                genC();
            }
        }
        genC();

        // --- ADMIN LOGIC ---
        let currentReq = null;

        function startAdmin() {
            setInterval(() => {
                document.getElementById('realtimeClock').innerText = new Date().toLocaleTimeString();
            }, 1000);

            // Listener Transaksi Masuk
            onValue(ref(db, 'transactions'), (snap) => {
                const list = document.getElementById('reqList');
                list.innerHTML = '';
                const data = snap.val();
                if(data) {
                    Object.keys(data).forEach(k => {
                        const t = data[k];
                        if(t.status === 'pending') {
                            const div = document.createElement('div');
                            div.className = 'incoming';
                            div.innerHTML = `
                                <span><b>${t.type.toUpperCase()}</b> - Rp${t.finalTotal.toLocaleString()}</span>
                                <div>
                                    <button onclick="act('${k}','reject')">Tolak (X)</button>
                                    <button onclick="preConfirm('${k}', '${t.type}', ${t.finalTotal})">Terima (✓)</button>
                                </div>
                            `;
                            list.appendChild(div);
                        }
                    });
                }
            });

            // Listener History
            onValue(ref(db, 'history'), (snap) => {
                const tb = document.querySelector('#tableHist tbody');
                tb.innerHTML = '';
                let tProd = 0, tFee = 0, tExp = 0;
                
                const data = snap.val();
                if(data) {
                    const sorted = Object.values(data).sort((a,b) => b.id.localeCompare(a.id));
                    sorted.forEach(d => {
                        if(d.type === 'expense') {
                            tExp += d.amount;
                        } else {
                            tProd += d.amount;
                            tFee += d.fee;
                        }

                        tb.innerHTML += `
                            <tr>
                                <td>${d.id}</td>
                                <td>${d.type}</td>
                                <td>${d.details}</td>
                                <td>Rp${d.amount.toLocaleString()}</td>
                                <td>Rp${d.fee.toLocaleString()}</td>
                            </tr>
                        `;
                    });
                }
                document.getElementById('sProd').innerText = `Rp${tProd.toLocaleString()}`;
                document.getElementById('sFee').innerText = `Rp${tFee.toLocaleString()}`;
                document.getElementById('sExp').innerText = `Rp${tExp.toLocaleString()}`;
            });
        }

        window.act = (key, action) => {
            if(action === 'reject') {
                update(ref(db, `transactions/${key}`), { status: 'rejected' });
                setTimeout(() => remove(ref(db, `transactions/${key}`)), 2000);
            }
        }

        window.preConfirm = (key, type, total) => {
            currentReq = { key, type, total };
            if(type === 'cash') {
                document.getElementById('modalCash').style.display = 'flex';
                document.getElementById('cTotal').innerText = `Rp${total.toLocaleString()}`;
            } else {
                finalize(key, 'QRIS', total, 200, 'Via QRIS Dinamis');
            }
        }

        window.calcChange = () => {
            const inp = document.getElementById('cInput').value;
            const chg = inp - currentReq.total;
            document.getElementById('cChange').innerText = `Rp${chg.toLocaleString()}`;
            if(chg < 0) document.getElementById('cChange').style.color = 'red';
            else document.getElementById('cChange').style.color = 'black';
        }

        window.confirmCash = () => {
            const inp = document.getElementById('cInput').value;
            if(inp < currentReq.total) return alert("Uang Kurang!");
            finalize(currentReq.key, 'CASH', currentReq.total, 0, `Cash (Bayar: ${inp})`);
            document.getElementById('modalCash').style.display = 'none';
            document.getElementById('cInput').value = '';
        }

        function finalize(key, typeLabel, total, fee, detail) {
            update(ref(db, `transactions/${key}`), { status: 'confirmed' });
            
            const now = new Date();
            const pad = n => n.toString().padStart(2,'0');
            const id = `YKLT${now.getFullYear()}${pad(now.getMonth()+1)}${pad(now.getDate())}${pad(now.getHours())}${pad(now.getMinutes())}${pad(now.getSeconds())}`;

            set(ref(db, `history/${id}`), {
                id: id,
                type: typeLabel,
                amount: total - fee, 
                fee: fee,
                details: detail,
                timestamp: serverTimestamp()
            });

            setTimeout(() => remove(ref(db, `transactions/${key}`)), 3000);
        }

        window.openExp = () => document.getElementById('modalExp').style.display = 'flex';
        window.saveExp = () => {
            const note = document.getElementById('eNote').value;
            const nom = parseInt(document.getElementById('eNom').value);
            
            const now = new Date();
            const pad = n => n.toString().padStart(2,'0');
            const id = `YKLT${now.getFullYear()}${pad(now.getMonth()+1)}${pad(now.getDate())}${pad(now.getHours())}${pad(now.getMinutes())}${pad(now.getSeconds())}`;

            set(ref(db, `history/${id}`), {
                id: id,
                type: 'expense',
                amount: nom,
                fee: 0,
                details: note,
                timestamp: serverTimestamp()
            });
            document.getElementById('modalExp').style.display = 'none';
        }

        window.wipeData = () => {
            if(confirm("Hapus SEMUA data?")) remove(ref(db, 'history'));
        }
        
        window.downloadXLS = () => {
            const tbl = document.getElementById("tableHist");
            const wb = XLSX.utils.table_to_book(tbl);
            XLSX.writeFile(wb, "Laporan_Yakult.xlsx");
        }
    </script>
</body>
</html>
