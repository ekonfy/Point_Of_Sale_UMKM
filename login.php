<!DOCTYPE html>
<html>
<head>
    <title>Login POS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height:100vh;">
    <div class="card p-4 shadow" style="width:350px;">
        <h3 class="text-center mb-3">POS Login</h3>
        <form id="loginForm">
            <input type="text" id="username" class="form-control mb-2" placeholder="Username" required>
            <input type="password" id="password" class="form-control mb-3" placeholder="Password" required>
            <button class="btn btn-primary w-100">Login</button>
        </form>
    </div>
    <script>
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const res = await fetch('api/login.php', {
                method: 'POST',
                body: JSON.stringify({
                    username: document.getElementById('username').value,
                    password: document.getElementById('password').value
                })
            });
            const data = await res.json();
            if(data.status === 'success') location.href = data.redirect;
            else alert(data.message);
        });
    </script>
</body>
</html>