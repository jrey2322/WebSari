<?php // app/Views/utang/index.php ?>
<?= view('layout/header') ?>
<?= view('layout/sidebar') ?>

<div class="ws-main">
    <header class="ws-topbar">
        <div class="page-title">
            <button class="mobile-toggle" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>
            <div class="d-flex flex-column">
                <span style="font-size: 1.1rem; line-height: 1.2">📝 Utang Tracker</span>
                <small style="font-size: .7rem">Track customer debts</small>
            </div>
        </div>
    </header>

    <div class="ws-content fade-in">

        <!-- Flash Messages -->
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

        <!-- Summary Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="stat-card"
                     style="background:linear-gradient(135deg,#dc2626,#b91c1c)">
                    <div class="s-icon">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <h2><?= count($unpaid) ?></h2>
                    <p>Unpaid Utang</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card"
                     style="background:linear-gradient(135deg,#f97316,#ea6c00)">
                    <div class="s-icon">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <h2>₱<?= number_format($totalDebt, 2) ?></h2>
                    <p>Total Outstanding</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card"
                     style="background:linear-gradient(135deg,#16a34a,#15803d)">
                    <div class="s-icon">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <h2><?= count($paid) ?></h2>
                    <p>Fully Paid</p>
                </div>
            </div>
        </div>

        <!-- Unpaid Utang -->
        <div class="ws-card mb-4">
            <div class="ws-card-body">
                <h6 class="fw-bold mb-3">
                    <i class="bi bi-clock-fill text-danger me-2"></i>
                    Unpaid / Partial Payments
                    <span class="badge bg-danger ms-1">
                        <?= count($unpaid) ?>
                    </span>
                </h6>

                <?php if (empty($unpaid)): ?>
                    <div class="text-center py-4">
                        <div style="font-size:2.5rem">🎉</div>
                        <p class="text-muted mt-2">
                            No outstanding utang! Great job!
                        </p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table ws-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Invoice</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-end">Paid</th>
                                    <th class="text-end">Balance</th>
                                    <th>Progress</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($unpaid as $i => $u): ?>
                                    <?php
                                        $pct = $u['total'] > 0
                                            ? round(($u['paid_amount'] / $u['total']) * 100)
                                            : 0;
                                    ?>
                                    <tr>
                                        <td class="text-muted"><?= $i + 1 ?></td>
                                        <td>
                                            <a href="<?= base_url('utang/view/'.$u['id']) ?>"
                                               class="fw-bold text-primary
                                                      text-decoration-none">
                                                <?= esc($u['invoice_no']) ?>
                                            </a>
                                        </td>
                                        <td>
                                            <div class="fw-bold">
                                                <?= esc($u['customer_name']) ?>
                                            </div>
                                            <div style="font-size:.72rem;color:#94a3b8">
                                                <?= esc($u['cashier_name'] ?? '—') ?>
                                            </div>
                                        </td>
                                        <td style="font-size:.78rem;color:#64748b">
                                            <?= date('M j, Y', strtotime($u['created_at'])) ?>
                                            <div style="font-size:.7rem">
                                                <?= date('g:i A', strtotime($u['created_at'])) ?>
                                            </div>
                                        </td>
                                        <td class="text-end fw-bold">
                                            ₱<?= number_format($u['total'], 2) ?>
                                        </td>
                                        <td class="text-end text-success fw-bold">
                                            ₱<?= number_format($u['paid_amount'], 2) ?>
                                        </td>
                                        <td class="text-end fw-bold text-danger">
                                            ₱<?= number_format($u['balance'], 2) ?>
                                        </td>
                                        <td style="min-width:100px">
                                            <div class="d-flex align-items-center gap-1">
                                                <div class="progress flex-fill"
                                                     style="height:8px;border-radius:4px">
                                                    <div class="progress-bar bg-success"
                                                         style="width:<?= $pct ?>%">
                                                    </div>
                                                </div>
                                                <span style="font-size:.7rem;
                                                             color:#64748b;
                                                             white-space:nowrap">
                                                    <?= $pct ?>%
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="<?= base_url('utang/view/'.$u['id']) ?>"
                                                   class="btn btn-primary btn-sm"
                                                   style="padding:4px 10px;
                                                          font-size:.75rem">
                                                    <i class="bi bi-cash me-1"></i>Pay
                                                </a>
                                                <?php if (session()->get('user_role') === 'owner'): ?>
                                                    <a href="<?= base_url('utang/markpaid/'.$u['id']) ?>"
                                                       class="btn btn-success btn-sm"
                                                       style="padding:4px 8px"
                                                       onclick="return confirm('Mark as fully paid?')"
                                                       title="Mark as Fully Paid">
                                                        <i class="bi bi-check2-all"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Paid Utang -->
        <?php if (!empty($paid)): ?>
            <div class="ws-card">
                <div class="ws-card-body">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                        Fully Paid Utang
                        <span class="badge bg-success ms-1">
                            <?= count($paid) ?>
                        </span>
                    </h6>
                    <div class="table-responsive">
                        <table class="table ws-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Invoice</th>
                                    <th>Customer</th>
                                    <th class="text-end">Amount</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($paid as $i => $p): ?>
                                    <tr>
                                        <td class="text-muted"><?= $i + 1 ?></td>
                                        <td>
                                            <span class="fw-bold text-success">
                                                <?= esc($p['invoice_no']) ?>
                                            </span>
                                        </td>
                                        <td><?= esc($p['customer_name']) ?></td>
                                        <td class="text-end fw-bold">
                                            ₱<?= number_format($p['total'], 2) ?>
                                        </td>
                                        <td style="font-size:.78rem;color:#64748b">
                                            <?= date('M j, Y', strtotime($p['created_at'])) ?>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('utang/view/'.$p['id']) ?>"
                                               class="btn btn-sm btn-light"
                                               style="padding:3px 9px">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>