<?php session_start(); if(!isset($_SESSION['user_id'])) header("Location: login.php"); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Kasir POS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .product-card { cursor: pointer; height: 100%; transition: 0.2s; }
        .product-card:hover { border-color: #0d6efd; background-color: #f8f9fa; }
        .cart-area { height: 80vh; overflow-y: auto; }
        @media print {
            .no-print { display: none !important; }
            #receipt { display: block !important; position: absolute; left: 0; top: 0; width: 100%; }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-primary mb-3 no-print">
        <div class="container-fluid">
            <span class="navbar-brand">Kasir: <?php echo $_SESSION['username']; ?></span>
            <div>
                <a href="dashboard.php" class="btn btn-sm btn-light">Laporan</a>
                <a href="api/logout.php" class="btn btn-sm btn-danger">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 no-print">
                <input type="text" id="search" class="form-control mb-3" placeholder="Cari Produk...">
                <div class="row" id="product-grid"></div>
            </div>
            
            <div class="col-md-4 no-print">
                <div class="card shadow">
                    <div class="card-header bg-white">Keranjang</div>
                    <div class="card-body cart-area">
                        <ul class="list-group" id="cart-list"></ul>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between"><strong>Total:</strong> <span id="total-display">0</span></div>
                        <input type="number" id="pay-input" class="form-control my-2" placeholder="Bayar (Rp)">
                        <button onclick="checkout()" class="btn btn-success w-100">BAYAR</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="receipt" style="display:none; font-family: monospace; font-size: 12px; width: 58mm;">
        <div class="text-center">TOKO KITA<br>----------------</div>
        <div id="receipt-body"></div>
        <div class="text-center">----------------<br>Terima Kasih</div>
    </div>

    <script>
        let products = [], cart = [];
        
        // Load Products
        fetch('api/products.php').then(r => r.json()).then(d => {
            products = d.data;
            renderProducts(products);
        });

        function renderProducts(list) {
            document.getElementById('product-grid').innerHTML = list.map(p => `
                <div class="col-md-3 mb-3">
                    <div class="card product-card" onclick="addToCart(${p.id})">
                        <div class="card-body text-center p-2">
                            <h6>${p.name}</h6>
                            <small class="text-muted">Rp ${parseInt(p.price).toLocaleString()}</small><br>
                            <span class="badge bg-secondary">Stok: ${p.stock}</span>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        document.getElementById('search').addEventListener('input', e => {
            renderProducts(products.filter(p => p.name.toLowerCase().includes(e.target.value.toLowerCase())));
        });

        function addToCart(id) {
            let item = cart.find(c => c.id == id);
            let prod = products.find(p => p.id == id);
            if (item) {
                if(item.qty < prod.stock) item.qty++;
            } else {
                if(prod.stock > 0) cart.push({...prod, qty: 1});
            }
            renderCart();
        }

        function renderCart() {
            let total = 0;
            document.getElementById('cart-list').innerHTML = cart.map((c, i) => {
                total += c.price * c.qty;
                return `<li class="list-group-item d-flex justify-content-between align-items-center">
                    <small>${c.name} x${c.qty}</small>
                    <span>${(c.price * c.qty).toLocaleString()} 
                    <button class="btn btn-xs btn-danger ms-1" onclick="cart.splice(${i},1);renderCart()">x</button></span>
                </li>`;
            }).join('');
            document.getElementById('total-display').innerText = total.toLocaleString();
        }

        async function checkout() {
            let total = cart.reduce((a, b) => a + (b.price * b.qty), 0);
            let paid = document.getElementById('pay-input').value;
            if(!cart.length || paid < total) return alert("Cek Keranjang / Pembayaran!");

            let res = await fetch('api/checkout.php', {
                method: 'POST',
                body: JSON.stringify({ total_price: total, paid_amount: paid, change_amount: paid-total, items: cart })
            });
            let data = await res.json();
            
            if(data.status === 'success') {
                printReceipt(data.invoice, total, paid, paid-total);
                cart = []; renderCart(); document.getElementById('pay-input').value = '';
                alert("Transaksi Berhasil!");
                location.reload(); 
            } else { alert(data.message); }
        }

        function printReceipt(inv, tot, pay, change) {
            let html = `<p>Inv: ${inv}<br>${new Date().toLocaleString()}</p><table>`;
            cart.forEach(c => html += `<tr><td>${c.name} x${c.qty}</td><td align="right">${(c.price*c.qty).toLocaleString()}</td></tr>`);
            html += `</table><br>Total: ${tot.toLocaleString()}<br>Bayar: ${pay.toLocaleString()}<br>Kembali: ${change.toLocaleString()}`;
            document.getElementById('receipt-body').innerHTML = html;
            window.print();
        }
    </script>
</body>
</html>