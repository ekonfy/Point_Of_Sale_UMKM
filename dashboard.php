<?php session_start(); if(!isset($_SESSION['user_id'])) header("Location: login.php"); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Laporan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
    <a href="index.php" class="btn btn-secondary mb-3">&larr; Kembali ke Kasir</a>
    <div class="row">
        <div class="col-md-6">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Omzet Hari Ini</div>
                <div class="card-body"><h2 id="omzet">Rp 0</h2></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-white bg-info mb-3">
                <div class="card-header">Total Transaksi</div>
                <div class="card-body"><h2 id="trx">0</h2></div>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">5 Produk Terlaris Hari Ini</div>
        <table class="table">
            <thead><tr><th>Produk</th><th>Terjual</th></tr></thead>
            <tbody id="top-table"></tbody>
        </table>
    </div>

    <script>
        fetch('api/reports.php').then(r => r.json()).then(d => {
            if(d.status === 'success') {
                document.getElementById('omzet').innerText = "Rp " + (parseInt(d.summary.omzet)||0).toLocaleString();
                document.getElementById('trx').innerText = d.summary.total_trx;
                document.getElementById('top-table').innerHTML = d.top.map(p => `<tr><td>${p.name}</td><td>${p.sold}</td></tr>`).join('');
            }
        });
    </script>
</body>
</html>