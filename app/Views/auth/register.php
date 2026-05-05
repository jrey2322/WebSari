<?php  ?>
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
        * { font-family: 'Nunito', sans-serif; }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 60%, #0f172a 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-wrapper { width: 100%; max-width: 460px; }

        .auth-brand {
            text-align: center;
            margin-bottom: 24px;
        }

        .brand-icon {
            width: 65px; height: 65px;
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
            margin: 0;
        }

        .auth-brand p {
            color: rgba(255,255,255,.45);
            font-size: .8rem;
        }

        .auth-card {
            background: #fff;
            border-radius: 20px;
            padding: 34px;
            box-shadow: 0 25px 60px rgba(0,0,0,.35);
        }

        .auth-card h5 { font-weight: 800; color: #1e293b; margin-bottom: 4px; }
        .subtitle { color: #64748b; font-size: .82rem; margin-bottom: 22px; }

        .form-label { font-size: .8rem; font-weight: 700; color: #475569; }

        .form-control, .form-select {
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            padding: 9px 13px;
            font-size: .875rem;
            font-family: 'Nunito', sans-serif;
        }

        .form-control:focus, .form-select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,.12);
        }

        .role-selector {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 4px;
        }

        .role-option {
            border: 2px solid #e2e8f0;
            border-radius: 11px;
            padding: 12px;
            text-align: center;
            cursor: pointer;
            transition: all .2s;
        }

        .role-option:hover { border-color: #93c5fd; background: #eff6ff; }

        .role-option.selected {
            border-color: #2563eb;
            background: #eff6ff;
        }

        .role-option input { display: none; }

        .role-option .icon { font-size: 1.5rem; display: block; margin-bottom: 4px; }
        .role-option .label { font-size: .82rem; font-weight: 700; color: #1e293b; }
        .role-option .desc { font-size: .7rem; color: #64748b; }

        .btn-register {
            background: #f97316;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 11px;
            font-size: .9rem;
            font-weight: 700;
            width: 100%;
            font-family: 'Nunito', sans-serif;
            transition: all .2s;
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
        }

        .auth-footer {
            text-align: center;
            margin-top: 20px;
            font-size: .82rem;
            color: rgba(255,255,255,.5);
        }

        .auth-footer a { color: #f97316; font-weight: 700; text-decoration: none; }
    </style>
</head>
<body>
<div class="auth-wrapper">

    <div class="auth-brand">
        <div class="brand-icon">🏪</div>
        <h2>WebSari</h2>
        <p>Create your account</p>
    </div>

    <div class="auth-card">
        <h5>Register Account 🎉</h5>
        <p class="subtitle">Join WebSari and manage your store</p>

        <!-- Errors -->
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <?php foreach (session()->getFlashdata('errors') as $err): ?>
                    <div><i class="bi bi-x-circle me-1"></i><?= esc($err) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('register') ?>" method="POST">
            <?= csrf_field() ?>

            <!-- Role Selector -->
            <div class="mb-4">
                <label class="form-label d-block mb-2">Register as:</label>
                <div class="role-selector">
                    <label class="role-option selected" id="roleOwner">
                        <input type="radio" name="role" value="owner" checked
                               onchange="selectRole('owner')">
                        <span class="icon">👑</span>
                        <span class="label">Store Owner</span>
                        <span class="desc">Full access</span>
                    </label>
                    <label class="role-option" id="roleStaff">
                        <input type="radio" name="role" value="staff"
                               onchange="selectRole('staff')">
                        <span class="icon">👤</span>
                        <span class="label">Staff</span>
                        <span class="desc">Sales & inventory</span>
                    </label>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-control"
                           placeholder="Juan dela Cruz"
                           value="<?= old('name') ?>" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Email Address *</label>
                    <input type="email" name="email" class="form-control"
                           placeholder="juan@example.com"
                           value="<?= old('email') ?>" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" class="form-control"
                           placeholder="09XXXXXXXXX"
                           value="<?= old('phone') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Password *</label>
                    <input type="password" name="password" id="pw1"
                           class="form-control"
                           placeholder="Min. 6 characters" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Confirm Password *</label>
                    <input type="password" name="confirm_password"
                           class="form-control" placeholder="Repeat password" required>
                </div>
                <div class="col-12 mt-1">
                    <button type="submit" class="btn-register">
                        <i class="bi bi-person-plus-fill me-2"></i>Create Account
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="auth-footer">
        Already have an account?
        <a href="<?= base_url('login') ?>">Sign in</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function selectRole(role) {
        document.getElementById('roleOwner').classList.toggle('selected', role === 'owner');
        document.getElementById('roleStaff').classList.toggle('selected', role === 'staff');
    }
</script>
</body>
</html>