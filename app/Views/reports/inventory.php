<?php ?>
<?= view('layout/header') ?>
<?= view('layout/sidebar') ?>

<div class="ws-main">
    <header class="ws-topbar">
        <div class="page-title">
            Inventory Report
            <small>Current Stock Overview</small>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= base_url('reports/sales') ?>" class="btn btn-light btn-sm">
                <i class="bi bi-cash-stack me-1"></i>Sales Report
            </a>
            <button onclick="window.print()" class="btn btn-success btn-sm">
                <i class="bi bi-printer me-1"></i>Print
            </button>
        </div>
    </header>

    <div class="ws-content fade-in">

        <!-- Summary Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="ws-card">
                    <div class="ws-card-body text-center">
                        <div style="font-size:.72rem;color:#64748b;font-weight:700;
                                    text-transform:uppercase;">Total Inventory Value</div>
                        <div style="font-size:1.8rem;font-weight:800;color:#2563eb;margin:6px 0;">
                            ₱<?= number_format($totalVal, 2) ?>
                        </div>
                        <small class="text-muted">Based on cost price</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="ws-card">
                    <div class="ws-card-body text-center">
                        <div style="font-size:.72rem;color:#64748b;font-weight:700;
                                    text-transform:uppercase;">Active Products</div>
                        <div style="font-size:1.8rem;font-weight:800;color:#16a34a;margin:6px 0;">
                            <?= count($products) ?>
                        </div>
                        <small class="text-muted">Items in stock</small>
                    </div>
                </div>
            </div>
            <?php
                $lowStock = array_filter($products, fn($p) => $p['stock'] <= $p['low_stock_alert']);
            ?>
            <div class="col-md-4">
                <div class="ws-card">
                    <div class="ws-card-body text-center">
                        <div style="font-size:.72rem;color:#64748b;font-weight:700;
                                    text-transform:uppercase;">Low Stock Alerts</div>
                        <div style="font-size:1.8rem;font-weight:800;color:#ea580c;margin:6px 0;">
                            <?= count($lowStock) ?>
                        </div>
                        <small class="text-muted">Items needing restock</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory Table -->
        <div class="ws-card">
            <div class="ws-card-body">
                <div class="table-responsive">
                    <table class="table ws-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Cost Price</th>
                                <th>Selling Price</th>
                                <th>Stock</th>
                                <th>Stock Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $p): ?>
                                <?php 
                                    $isLow = $p['stock'] <= $p['low_stock_alert'];
                                    $stockValue = $p['stock'] * $p['cost_price'];
                                ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold"><?= esc($p['name']) ?></div>
                                        <small class="text-muted"><?= esc($p['barcode']) ?></small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            <?= esc($p['category_name'] ?? 'Uncategorized') ?>
                                        </span>
                                    </td>
                                    <td>₱<?= number_format($p['cost_price'], 2) ?></td>
                                    <td>₱<?= number_format($p['price'], 2) ?></td>
                                    <td>
                                        <span class="<?= $isLow ? 'text-danger fw-bold' : '' ?>">
                                            <?= $p['stock'] ?> <?= esc($p['unit']) ?>
                                        </span>
                                        <?php if ($isLow): ?>
                                            <i class="bi bi-exclamation-triangle-fill text-warning ms-1" 
                                               title="Low Stock"></i>
                                        <?php endif; ?>
                                    </td>
                                    <td class="fw-bold">
                                        ₱<?= number_format($stockValue, 2) ?>
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

<style>
    @media print {
        .ws-sidebar, .ws-topbar, .btn, form { display: none !important; }
        .ws-main { margin-left: 0 !important; padding: 0 !important; }
        .ws-card { border: none !important; box-shadow: none !important; }
        .page-title small { display: block !important; }
    }
</style>
