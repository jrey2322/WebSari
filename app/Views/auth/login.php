<?php  ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | WebSari 🏪</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Nunito', sans-serif; }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 60%, #0f172a 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-wrapper {
            width: 100%;
            max-width: 420px;
        }

        .auth-brand {
            text-align: center;
            margin-bottom: 28px;
        }

        .auth-brand .brand-icon {
            width: 70px; height: 70px;
            background: #f97316;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 12px;
        }

        .auth-brand h2 {
            color: #fff;
            font-weight: 800;
            margin: 0;
            font-size: 1.8rem;
        }

        .auth-brand p {
            color: rgba(255,255,255,.5);
            font-size: .82rem;
            margin: 0;
        }

        .auth-card {
            background: #fff;
            border-radius: 20px;
            padding: 36px;
            box-shadow: 0 25px 60px rgba(0,0,0,.35);
        }

        .auth-card h5 {
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 6px;
        }

        .auth-card .subtitle {
            color: #64748b;
            font-size: .82rem;
            margin-bottom: 24px;
        }

        .form-label {
            font-size: .8rem;
            font-weight: 700;
            color: #475569;
        }

        .form-control {
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            padding: 10px 14px;
            font-size: .875rem;
            font-family: 'Nunito', sans-serif;
        }

        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,.12);
        }

        .btn-login {
            background: #2563eb;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 11px;
            font-size: .9rem;
            font-weight: 700;
            width: 100%;
            transition: all .2s;
            font-family: 'Nunito', sans-serif;
        }

        .btn-login:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(37,99,235,.3);
        }

        .demo-box {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 10px;
            padding: 12px 15px;
            margin-top: 20px;
            font-size: .78rem;
            color: #0369a1;
        }

        .demo-box strong { color: #0c4a6e; }

        .auth-footer {
            text-align: center;
            margin-top: 22px;
            font-size: .82rem;
            color: rgba(255,255,255,.5);
        }

        .auth-footer a {
            color: #f97316;
            font-weight: 700;
            text-decoration: none;
        }

        .alert {
            border-radius: 10px;
            border: none;
            font-size: .855rem;
            font-family: 'Nunito', sans-serif;
            font-weight: 600;
        }

        .eye-toggle {
            cursor: pointer;
            border: 1.5px solid #e2e8f0;
            border-left: none;
            border-radius: 0 10px 10px 0;
            background: #fff;
            color: #94a3b8;
            padding: 0 14px;
        }
    </style>
</head>
<body>

<div class="auth-wrapper">

    <!-- Brand -->
    <div class="auth-brand">
        <div class="brand-icon">🏪</div>
        <h2>WebSari</h2>
        <p>Sari-Sari Store Management System</p>
    </div>

    <!-- Card -->
    <div class="auth-card">
        <h5>Welcome back! 👋</h5>
        <p class="subtitle">Login to manage your store</p>

        <!-- Alerts -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-circle me-1"></i>
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <i class="bi bi-check-circle me-1"></i>
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <?php foreach (session()->getFlashdata('errors') as $err): ?>
                    <div><i class="bi bi-x-circle me-1"></i><?= esc($err) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <form action="<?= base_url('login') ?>" method="POST">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text"
                          style="border-radius:10px 0 0 10px;
                                 border:1.5px solid #e2e8f0;border-right:none;
                                 background:#f8fafc;color:#94a3b8;">
                        <i class="bi bi-envelope-fill"></i>
                    </span>
                    <input type="email" name="email" class="form-control"
                           style="border-radius:0 10px 10px 0;border-left:none;"
                           placeholder="you@example.com"
                           value="<?= old('email') ?>" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"
                          style="border-radius:10px 0 0 10px;
                                 border:1.5px solid #e2e8f0;border-right:none;
                                 background:#f8fafc;color:#94a3b8;">
                        <i class="bi bi-lock-fill"></i>
                    </span>
                    <input type="password" name="password" id="pwInput"
                           class="form-control"
                           style="border-left:none;border-right:none;border-radius:0;"
                           placeholder="••••••••" required>
                    <span class="eye-toggle" onclick="togglePw()">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </span>
                </div>
            </div>

            <button type="submit" class="btn-login">
                <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
            </button>
        </form>

        <!-- Demo Info -->
        <div class="demo-box">
            <div class="fw-bold mb-1">🔑 Demo Accounts:</div>
            <div>Owner: <strong>owner@websari.com</strong> / <strong>owner123</strong></div>
            <div>Staff: <strong>staff@websari.com</strong> / <strong>password</strong></div>
        </div>
    </div>

    <!-- Footer -->
    <div class="auth-footer">
        Don't have an account?
        <a href="<?= base_url('register') ?>">Register here</a>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function togglePw() {
        const pw   = document.getElementById('pwInput');
        const icon = document.getElementById('eyeIcon');
        if (pw.type === 'password') {
            pw.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            pw.type = 'password';
            icon.className = 'bi bi-eye';
        }
    }
</script>
</body>
</html>