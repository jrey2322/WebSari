<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | WebSari 🏪</title>
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
            max-width: 480px;
        }

        .auth-brand {
            text-align: center;
            margin-bottom: 24px;
        }

        .brand-icon {
            width: 65px;
            height: 65px;
            background: #f97316;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.9rem;
            margin: 0 auto 10px;
        }

        .auth-brand h2 {
            color: #fff;
            font-weight: 800;
            font-size: 1.7rem;
            margin: 0 0 4px;
        }

        .auth-brand p {
            color: rgba(255,255,255,.45);
            font-size: .8rem;
            margin: 0;
        }

        .auth-card {
            background: #fff;
            border-radius: 20px;
            padding: 34px;
            box-shadow: 0 25px 60px rgba(0,0,0,.35);
        }

        .auth-card h5 {
            font-weight: 800;
            color: #1e293b;
            font-size: 1.15rem;
            margin: 0 0 4px;
        }

        .subtitle {
            color: #64748b;
            font-size: .82rem;
            margin-bottom: 22px;
        }

        .form-label {
            font-size: .8rem;
            font-weight: 700;
            color: #475569;
            margin-bottom: 5px;
        }

        .form-control, .form-select {
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            padding: 9px 13px;
            font-size: .875rem;
            font-family: 'Nunito', sans-serif;
            transition: border-color .2s, box-shadow .2s;
        }

        .form-control:focus, .form-select:focus {
            border-color: #f97316;
            box-shadow: 0 0 0 3px rgba(249,115,22,.12);
            outline: none;
        }

        /* Role selector */
        .role-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 4px;
        }

        .role-option {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px 10px;
            text-align: center;
            cursor: pointer;
            transition: all .2s;
            position: relative;
        }

        .role-option:hover {
            border-color: #fdba74;
            background: #fff7ed;
        }

        .role-option.selected {
            border-color: #f97316;
            background: #fff7ed;
        }

        .role-option input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .role-option .r-icon {
            font-size: 1.6rem;
            display: block;
            margin-bottom: 5px;
        }

        .role-option .r-label {
            font-size: .82rem;
            font-weight: 700;
            color: #1e293b;
            display: block;
        }

        .role-option .r-desc {
            font-size: .68rem;
            color: #64748b;
            display: block;
            margin-top: 2px;
        }

        .btn-register {
            background: #f97316;
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

        .btn-register:hover {
            background: #ea6c00;
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

        .auth-footer {
            text-align: center;
            margin-top: 20px;
            font-size: .82rem;
            color: rgba(255,255,255,.5);
        }

        .auth-footer a {
            color: #f97316;
            font-weight: 700;
            text-decoration: none;
        }

        .auth-footer a:hover { text-decoration: underline; }

        .divider {
            height: 1px;
            background: #e2e8f0;
            margin: 18px 0;
        }
    </style>
</head>
<body>

<div class="auth-wrapper">

    <!-- Brand -->
    <div class="auth-brand">
        <div class="brand-icon">🏪</div>
        <h2>WebSari</h2>
        <p>Create your account</p>
    </div>

    <!-- Card -->
    <div class="auth-card">
        <h5>Create Account 🎉</h5>
        <p class="subtitle">Join WebSari and manage your store</p>

        <!-- Errors -->
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <?php foreach (session()->getFlashdata('errors') as $err): ?>
                    <div>
                        <i class="bi bi-x-circle me-1"></i>
                        <?= esc($err) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-circle me-1"></i>
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <!-- Register Form -->
        <form action="<?= base_url('register') ?>" method="POST">
            <?= csrf_field() ?>

            <!-- Role Selection -->
            <div class="mb-3">
                <label class="form-label d-block mb-2">Register as:</label>
                <div class="role-grid">
                    <label class="role-option selected" id="optOwner">
                        <input type="radio"
                               name="role"
                               value="owner"
                               checked
                               onchange="selectRole('owner')">
                        <span class="r-icon">👑</span>
                        <span class="r-label">Store Owner</span>
                        <span class="r-desc">Full access & reports</span>
                    </label>
                    <label class="role-option" id="optStaff">
                        <input type="radio"
                               name="role"
                               value="staff"
                               onchange="selectRole('staff')">
                        <span class="r-icon">👤</span>
                        <span class="r-label">Staff / Cashier</span>
                        <span class="r-desc">Sales & inventory</span>
                    </label>
                </div>
            </div>

            <div class="divider"></div>

            <!-- Name -->
            <div class="mb-3">
                <label class="form-label">Full Name *</label>
                <input type="text"
                       name="name"
                       class="form-control"
                       placeholder="Juan dela Cruz"
                       value="<?= esc(old('name')) ?>"
                       required>
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label class="form-label">Email Address *</label>
                <input type="email"
                       name="email"
                       class="form-control"
                       placeholder="juan@example.com"
                       value="<?= esc(old('email')) ?>"
                       required>
            </div>

            <!-- Phone -->
            <div class="mb-3">
                <label class="form-label">Phone Number</label>
                <input type="text"
                       name="phone"
                       class="form-control"
                       placeholder="09XXXXXXXXX"
                       value="<?= esc(old('phone')) ?>">
            </div>

            <!-- Passwords -->
            <div class="row g-2 mb-3">
                <div class="col-6">
                    <label class="form-label">Password *</label>
                    <input type="password"
                           name="password"
                           class="form-control"
                           placeholder="Min. 6 chars"
                           required>
                </div>
                <div class="col-6">
                    <label class="form-label">Confirm *</label>
                    <input type="password"
                           name="confirm_password"
                           class="form-control"
                           placeholder="Repeat"
                           required>
                </div>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn-register">
                <i class="bi bi-person-plus-fill me-2"></i>Create Account
            </button>
        </form>
    </div>

    <!-- Footer -->
    <div class="auth-footer">
        Already have an account?
        <a href="<?= base_url('login') ?>">Sign in here</a>
    </div>

</div>

<script>
    function selectRole(role) {
        var ownerOpt = document.getElementById('optOwner');
        var staffOpt = document.getElementById('optStaff');

        if (role === 'owner') {
            ownerOpt.classList.add('selected');
            staffOpt.classList.remove('selected');
        } else {
            staffOpt.classList.add('selected');
            ownerOpt.classList.remove('selected');
        }
    }
</script>

</body>
</html>