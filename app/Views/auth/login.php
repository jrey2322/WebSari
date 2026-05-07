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
        * {
            font-family: 'Nunito', sans-serif;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
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

        .brand-icon {
            width: 70px;
            height: 70px;
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
            font-size: 1.9rem;
            margin: 0 0 4px;
        }

        .auth-brand p {
            color: rgba(255,255,255,0.45);
            font-size: .82rem;
            margin: 0;
        }

        .auth-card {
            background: #fff;
            border-radius: 20px;
            padding: 36px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.35);
        }

        .auth-card h5 {
            font-weight: 800;
            color: #1e293b;
            font-size: 1.2rem;
            margin: 0 0 4px;
        }

        .subtitle {
            color: #64748b;
            font-size: .82rem;
            margin-bottom: 24px;
        }

        .form-label {
            font-size: .8rem;
            font-weight: 700;
            color: #475569;
            margin-bottom: 5px;
        }

        .form-control {
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            padding: 10px 14px;
            font-size: .875rem;
            font-family: 'Nunito', sans-serif;
            transition: border-color .2s, box-shadow .2s;
        }

        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,.12);
            outline: none;
        }

        .input-icon {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-right: none;
            border-radius: 10px 0 0 10px;
            padding: 0 12px;
            display: flex;
            align-items: center;
            color: #94a3b8;
        }

        .input-icon-right {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-left: none;
            border-radius: 0 10px 10px 0;
            padding: 0 12px;
            display: flex;
            align-items: center;
            color: #94a3b8;
            cursor: pointer;
        }

        .form-control.with-left-icon {
            border-left: none;
            border-radius: 0 10px 10px 0;
        }

        .form-control.with-both-icon {
            border-left: none;
            border-right: none;
            border-radius: 0;
        }

        .btn-login {
            background: #2563eb;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-size: .9rem;
            font-weight: 700;
            width: 100%;
            cursor: pointer;
            font-family: 'Nunito', sans-serif;
            transition: background .2s, transform .2s;
        }

        .btn-login:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
        }

        .alert {
            border-radius: 10px;
            border: none;
            font-size: .855rem;
            font-family: 'Nunito', sans-serif;
            font-weight: 600;
            padding: 12px 16px;
            margin-bottom: 20px;
        }

        .alert-danger {
            background: #fef2f2;
            color: #dc2626;
        }

        .alert-success {
            background: #f0fdf4;
            color: #16a34a;
        }

        .demo-box {
            background: #f0f9ff;
            border: 1.5px solid #bae6fd;
            border-radius: 10px;
            padding: 12px 15px;
            margin-top: 20px;
            font-size: .78rem;
            color: #0369a1;
        }

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

        .auth-footer a:hover {
            text-decoration: underline;
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
        <p class="subtitle">Sign in to manage your store</p>

        <!-- Error Alert -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <!-- Success Alert -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill me-2"></i>
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>

        <!-- Errors List -->
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <?php foreach (session()->getFlashdata('errors') as $err): ?>
                    <div><i class="bi bi-x-circle me-1"></i><?= esc($err) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form action="<?= base_url('login') ?>" method="POST">
            <?= csrf_field() ?>

            <!-- Email -->
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <div class="d-flex">
                    <span class="input-icon">
                        <i class="bi bi-envelope-fill"></i>
                    </span>
                    <input type="email"
                           name="email"
                           class="form-control with-left-icon"
                           placeholder="you@example.com"
                           value="<?= esc(old('email')) ?>"
                           required>
                </div>
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label class="form-label">Password</label>
                <div class="d-flex">
                    <span class="input-icon">
                        <i class="bi bi-lock-fill"></i>
                    </span>
                    <input type="password"
                           name="password"
                           id="pwInput"
                           class="form-control with-both-icon"
                           placeholder="••••••••"
                           required>
                    <span class="input-icon-right" onclick="togglePw()">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </span>
                </div>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn-login">
                <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
            </button>
        </form>

        <!-- Demo Credentials -->
        <div class="demo-box">
            <div style="font-weight:800;margin-bottom:6px;">
                🔑 Demo Accounts:
            </div>
            <div>
                <strong>Owner</strong> →
                admin@websari.com /
                <strong>adminsari</strong>
            </div>

        </div>
    </div>

    <!-- Footer -->
    <div class="auth-footer">
        Don't have an account?
        <a href="<?= base_url('register') ?>">Register here</a>
    </div>

</div>

<script>
    function togglePw() {
        var input = document.getElementById('pwInput');
        var icon  = document.getElementById('eyeIcon');
        if (input.type === 'password') {
            input.type    = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            input.type    = 'password';
            icon.className = 'bi bi-eye';
        }
    }
</script>

</body>
</html>