<?php  ?>
<?= view('layout/header') ?>
<?= view('layout/sidebar') ?>

<div class="ws-main">

    <!-- Topbar -->
    <header class="ws-topbar">
        <button class="btn btn-sm btn-light d-lg-none me-2"
                onclick="document.getElementById('wsSidebar').classList.toggle('show')">
            <i class="bi bi-list fs-5"></i>
        </button>
        <div class="page-title">
            Dashboard
            <small><?= date('l, F j, Y') ?></small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge <?= session()->get('user_role') === 'owner' ? 'role-owner' : 'role-staff' ?>">
                <?= ucfirst(session()->get('user_role')) ?>
            </span>
            <a href="<?= base_url('sales/create') ?>" class="btn btn-ws-orange btn-sm">
                <i class="bi bi-cart-plus-fill me-1"></i>New Sale
            </a>
        </div>
    </header>

    <!-- Content -->
    <div class="ws-content fade-in">

        <!-- Flash -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show mb-4">
                <i class="bi bi-check-circle-fill me-2"></i>
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Stat Cards -->
        <div class="row g-3 mb-4">

            <div class="col-6 col-md-3">
                <div class="stat-card"
                     style="background:linear-gradient(135deg,#2563eb,#1d4ed8);">
                    <div class="s-icon"><i class="bi bi-currency-dollar"></i></div>
                    <h2>₱<?= number_format($todayRevenue, 2) ?></h2>
                    <p>Today's Revenue</p>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="stat-card"
                     style="background:linear-gradient(135deg,#f97316,#ea6c00);">
                    <div class="s-icon"><i class="bi bi-receipt"></i></div>
                    <h2><?= $todayCount ?></h2>
                    <p>Sales Today</p>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="stat-card"
                     style="background:linear-gradient(135deg,#16a34a,#15803d);">
                    <div class="s-icon"><i class="bi bi-box-seam-fill"></i></div>
                    <h2><?= $totalProducts ?></h2>
                    <p>Active Products</p>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="stat-card"
                     style="background:linear-gradient(135deg,#7c3aed,#6d28d9);">
                    <div class="s-icon"><i class="bi bi-bank2"></i></div>
                    <h2>₱<?= number_format($inventoryValue, 0) ?></h2>
                    <p>Inventory Value</p>
                </div>
            </div>

        </div>

        <!-- Chart + Low Stock -->
        <div class="row g-3 mb-4">

            <!-- Sales Chart -->
            <div class="col-lg-8">
                <div class="ws-card h-100">
                    <div class="ws-card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="fw-bold mb-0">Monthly Revenue</h6>
                                <small class="text-muted"><?= date('Y') ?> Overview</small>
                            </div>
                        </div>
                        <canvas id="revenueChart" height="110"></canvas>
                    </div>
                </div>
            </div>

            <!-- Low Stock -->
            <div class="col-lg-4">
                <div class="ws-card h-100">
                    <div class="ws-card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="fw-bold mb-0">
                                    <i class="bi bi-exclamation-triangle-fill text-warning me-1"></i>
                                    Low Stock
                                </h6>
                                <small class="text-muted">Needs restocking</small>
                            </div>
                            <a href="<?= base_url('products/low-stock') ?>"
                               class="btn btn-sm btn-light" style="font-size:.72rem;">
                                View All
                            </a>
                        </div>

                        <?php if (empty($lowStockItems)): ?>
                            <div class="text-center py-4">
                                <div style="font-size:2.5rem;">✅</div>
                                <p class="text-muted mt-2 mb-0" style="font-size:.82rem;">
                                    All products well stocked!
                                </p>
                            </div>
                        <?php else: ?>
                            <div style="max-height:280px;overflow-y:auto;">
                                <?php foreach (array_slice($lowStockItems, 0, 8) as $item): ?>
                                    <div class="d-flex align-items-center
                                                justify-content-between py-2 border-bottom">
                                        <div style="flex:1;min-width:0;">
                                            <div class="fw-bold text-truncate"
                                                 style="font-size:.82rem;">
                                                <?= esc($item['name']) ?>
                                            </div>
                                            <div style="font-size:.72rem;color:#94a3b8;">
                                                <?= esc($item['category_name'] ?? '—') ?>
                                            </div>
                                        </div>
                                        <span class="badge bg-danger ms-2">
                                            <?= $item['stock'] ?> <?= esc($item['unit']) ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>

        <!-- Recent Sales -->
        <div class="ws-card">
            <div class="ws-card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="fw-bold mb-0">Recent Transactions</h6>
                        <small class="text-muted">Latest sales activity</small>
                    </div>
                    <a href="<?= base_url('sales') ?>" class="btn btn-primary btn-sm">
                        View All
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table ws-table mb-0">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Customer</th>
                                <th>Cashier</th>
                                <th>Total</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recentSales)): ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-5">
                                        <div style="font-size:2rem;">🛒</div>
                                        <p class="mt-2 mb-0">No sales yet.</p>
                                        <a href="<?= base_url('sales/create') ?>"
                                           class="btn btn-ws-orange btn-sm mt-2">
                                            Make your first sale!
                                        </a>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach (array_slice($recentSales, 0, 10) as $sale): ?>
                                    <tr>
                                        <td>
                                            <span class="fw-bold text-primary">
                                                <?= esc($sale['invoice_no']) ?>
                                            </span>
                                        </td>
                                        <td><?= esc($sale['customer_name']) ?></td>
                                        <td class="text-muted">
                                            <?= esc($sale['cashier_name'] ?? '—') ?>
                                        </td>
                                        <td>
                                            <strong>₱<?= number_format($sale['total'], 2) ?></strong>
                                        </td>
                                        <td>
                                            <?php
                                                $pm = $sale['payment_method'];
                                                $pmClass = match($pm) {
                                                    'cash'  => 'bg-success',
                                                    'gcash' => 'bg-primary',
                                                    'utang' => 'bg-danger',
                                                    default => 'bg-secondary'
                                                };
                                            ?>
                                            <span class="badge <?= $pmClass ?>">
                                                <?= strtoupper($pm) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php
                                                $st = $sale['status'];
                                                $stClass = match($st) {
                                                    'completed' => 'bg-success',
                                                    'void'      => 'bg-secondary',
                                                    'utang'     => 'bg-warning text-dark',
                                                    default     => 'bg-secondary'
                                                };
                                            ?>
                                            <span class="badge <?= $stClass ?>">
                                                <?= ucfirst($st) ?>
                                            </span>
                                        </td>
                                        <td class="text-muted" style="font-size:.78rem;">
                                            <?= date('M j g:i A',
                                                strtotime($sale['created_at'])) ?>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('sales/view/'.$sale['id']) ?>"
                                               class="btn btn-sm btn-light"
                                               style="padding:3px 9px;">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div><!-- /ws-content -->
</div><!-- /ws-main -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const ctx = document.getElementById('revenueChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= $chartLabels ?>,
        datasets: [{
            label: 'Revenue (₱)',
            data: <?= $chartData ?>,
            backgroundColor: 'rgba(37, 99, 235, 0.12)',
            borderColor: '#2563eb',
            borderWidth: 2,
            borderRadius: 8,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => '₱' + ctx.parsed.y.toLocaleString('en-PH',
                        { minimumFractionDigits: 2 })
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: '#f1f5f9' },
                ticks: {
                    callback: v => '₱' + v.toLocaleString()
                }
            },
            x: { grid: { display: false } }
        }
    }
});
</script>
</body>
</html>