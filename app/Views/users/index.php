<?php ?>
<?= view('layout/header') ?>
<?= view('layout/sidebar') ?>

<div class="ws-main">
    <header class="ws-topbar">
        <div class="page-title">
            Manage Staff
            <small>Users & Access Control</small>
        </div>
        <button class="btn btn-primary btn-sm"
                data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="bi bi-person-plus-fill me-1"></i>Add Staff
        </button>
    </header>

    <div class="ws-content fade-in">

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show mb-4">
                <i class="bi bi-check-circle-fill me-2"></i>
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show mb-4">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger alert-dismissible fade show mb-4">
                <?php foreach (session()->getFlashdata('errors') as $e): ?>
                    <div><i class="bi bi-x-circle me-1"></i><?= esc($e) ?></div>
                <?php endforeach; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="ws-card">
            <div class="ws-card-body">
                <div class="table-responsive">
                    <table class="table ws-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $i => $u): ?>
                                <tr>
                                    <td class="text-muted"><?= $i + 1 ?></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="ws-avatar" style="width:32px;height:32px;font-size:.75rem;">
                                                <?= strtoupper(substr($u['name'],0,1)) ?>
                                            </div>
                                            <span class="fw-bold"><?= esc($u['name']) ?></span>
                                        </div>
                                    </td>
                                    <td class="text-muted"><?= esc($u['email']) ?></td>
                                    <td class="text-muted">
                                        <?= esc($u['phone'] ?: '—') ?>
                                    </td>
                                    <td>
                                        <span class="badge role-<?= $u['role'] ?>">
                                            <?= ucfirst($u['role']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge
                                            <?= $u['status'] === 'active' ? 'bg-success' : 'bg-secondary' ?>">
                                            <?= ucfirst($u['status']) ?>
                                        </span>
                                    </td>
                                    <td style="font-size:.78rem;color:#64748b;">
                                        <?= date('M j, Y', strtotime($u['created_at'])) ?>
                                    </td>
                                    <td>
                                        <?php if ($u['id'] != session()->get('user_id')): ?>
                                            <div class="d-flex gap-1">
                                                <a href="<?= base_url('users/toggle/'.$u['id']) ?>"
                                                   class="btn btn-sm
                                                       <?= $u['status']==='active'
                                                           ? 'btn-warning' : 'btn-success' ?>"
                                                   style="padding:3px 9px;font-size:.72rem;"
                                                   title="Toggle Status">
                                                    <?= $u['status']==='active' ? 'Deactivate' : 'Activate' ?>
                                                </a>
                                                <?php if ($u['role'] !== 'owner'): ?>
                                                    <a href="<?= base_url('users/delete/'.$u['id']) ?>"
                                                       class="btn btn-sm btn-danger"
                                                       style="padding:3px 9px;"
                                                       onclick="return confirm('Delete this user?')">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted" style="font-size:.75rem;">
                                                (You)
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Staff Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;border:none;">
            <div class="modal-header border-0 pb-0">
                <h6 class="fw-bold">
                    <i class="bi bi-person-plus-fill me-2 text-primary"></i>
                    Add Staff Account
                </h6>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('users/store') ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="name"
                                   class="form-control" required
                                   placeholder="Juan dela Cruz">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email"
                                   class="form-control" required
                                   placeholder="juan@email.com">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone"
                                   class="form-control"
                                   placeholder="09XXXXXXXXX">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Password *</label>
                            <input type="password" name="password"
                                   class="form-control" required
                                   placeholder="Min. 6 characters">
                        </div>
                        <div class="col-12">
                            <button type="submit"
                                    class="btn btn-primary w-100">
                                <i class="bi bi-person-plus-fill me-2"></i>
                                Create Staff Account
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>