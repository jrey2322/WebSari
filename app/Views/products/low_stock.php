<?php  ?>
<?= view('layout/header') ?>
<?= view('layout/sidebar') ?>

<div class="ws-main">
    <header class="ws-topbar">
        <div class="page-title">
            ⚠️ Low Stock Alert
            <small><?= count($products) ?> items need restocking</small>
        </div>
        <a href="<?= base_url('products') ?>" class="btn btn-light btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Back to Products
        </a>
    </header>

    <div class="ws-content fade-in">
        <?php if (empty($products)): ?>
            <div class="ws-card">
                <div class="ws-card-body text-center py-5">
                    <div style="font-size:3.5rem;">✅</div>
                    <h5 class="fw-bold mt-3">All Products Well Stocked!</h5>
                    <p class="text-muted">No items need restocking right now.</p>
                    <a href="<?= base_url('products') ?>" class="btn btn-primary">
                        View All Products
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="ws-card">
                <div class="ws-card-body">
                    <div class="table-responsive">
                        <table class="table ws-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th class="text-center">Current Stock</th>
                                    <th class="text-center">Alert Level</th>
                                    <th>Unit</th>
                                    <th>Status</th>
                                    <?php if (session()->get('user_role')==='owner'): ?>
                                        <th>Action</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $i => $p): ?>
                                    <tr>
                                        <td class="text-muted"><?= $i+1 ?></td>
                                        <td class="fw-bold"><?= esc($p['name']) ?></td>
                                        <td>
                                            <span class="badge"
                                                  style="background:#f0f9ff;color:#0369a1;">
                                                <?= esc($p['category_name'] ?? '—') ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="fw-bold
                                                <?= $p['stock'] <= 0 ? 'text-danger' : 'text-warning' ?>"
                                                style="font-size:1.1rem;">
                                                <?= $p['stock'] ?>
                                            </span>
                                        </td>
                                        <td class="text-center text-muted">
                                            <?= $p['low_stock_alert'] ?>
                                        </td>
                                        <td class="text-muted"><?= esc($p['unit']) ?></td>
                                        <td>
                                            <?php if ($p['stock'] <= 0): ?>
                                                <span class="badge bg-danger">Out of Stock</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark">
                                                    Low Stock
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <?php if (session()->get('user_role')==='owner'): ?>
                                            <td>
                                                <a href="<?= base_url('products/edit/'.$p['id']) ?>"
                                                   class="btn btn-sm btn-primary"
                                                   style="padding:4px 12px;font-size:.75rem;">
                                                    <i class="bi bi-pencil me-1"></i>
                                                    Update Stock
                                                </a>
                                            </td>
                                        <?php endif; ?>
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