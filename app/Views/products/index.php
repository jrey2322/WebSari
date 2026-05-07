<?php  ?>
<?= view('layout/header') ?>
<?= view('layout/sidebar') ?>

<div class="ws-main">
    <header class="ws-topbar">
        <div class="page-title">
            <button class="mobile-toggle" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>
            <div class="d-flex flex-column">
                <span style="font-size: 1.1rem; line-height: 1.2">Products</span>
                <small style="font-size: .7rem"><?= count($products) ?> active items</small>
            </div>
        </div>
        <?php if (session()->get('user_role') === 'owner'): ?>
            <a href="<?= base_url('products/create') ?>"
               class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i>Add Product
            </a>
        <?php endif; ?>
    </header>

    <div class="ws-content fade-in">

        <!-- Flash -->
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

        <div class="ws-card">
            <div class="ws-card-body">
                <!-- Filters -->
                <div class="row g-2 mb-4">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" id="searchInput"
                                   class="form-control border-start-0"
                                   placeholder="Search product name or barcode...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select id="catFilter" class="form-select">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= esc($cat['name']) ?>">
                                    <?= esc($cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select id="stockFilter" class="form-select">
                            <option value="">All Stock</option>
                            <option value="low">Low Stock</option>
                            <option value="ok">In Stock</option>
                            <option value="out">Out of Stock</option>
                        </select>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table ws-table" id="prodTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Barcode</th>
                                <th>Price</th>
                                <th>Cost</th>
                                <th>Profit</th>
                                <th>Stock</th>
                                <th>Unit</th>
                                <?php if (session()->get('user_role') === 'owner'): ?>
                                    <th>Actions</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($products)): ?>
                                <tr>
                                    <td colspan="10" class="text-center py-5">
                                        <div style="font-size:2.5rem;">📦</div>
                                        <p class="text-muted mt-2">No products found.</p>
                                        <?php if (session()->get('user_role') === 'owner'): ?>
                                            <a href="<?= base_url('products/create') ?>"
                                               class="btn btn-primary btn-sm">
                                                Add your first product
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($products as $i => $p):
                                    $isLow  = $p['stock'] <= $p['low_stock_alert'] && $p['stock'] > 0;
                                    $isOut  = $p['stock'] <= 0;
                                    $profit = $p['price'] - $p['cost_price'];
                                    $stockLevel = $isOut ? 'out' : ($isLow ? 'low' : 'ok');
                                ?>
                                    <tr data-cat="<?= esc($p['category_name'] ?? '') ?>"
                                        data-stock="<?= $stockLevel ?>"
                                        data-search="<?= strtolower(esc($p['name']) . ' ' . esc($p['barcode'])) ?>">
                                        <td class="text-muted"><?= $i + 1 ?></td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <?php if ($p['image']): ?>
                                                    <img src="<?= base_url('uploads/products/'.$p['image']) ?>"
                                                         alt=""
                                                         style="width:40px;height:40px;object-fit:cover;
                                                                border-radius:8px;">
                                                <?php else: ?>
                                                    <div style="width:40px;height:40px;background:#f1f5f9;
                                                                border-radius:8px;display:flex;
                                                                align-items:center;justify-content:center;
                                                                font-size:1.2rem;">
                                                        🛒
                                                    </div>
                                                <?php endif; ?>
                                                <div>
                                                    <div class="fw-bold" style="font-size:.875rem;">
                                                        <?= esc($p['name']) ?>
                                                    </div>
                                                    <?php if ($p['description']): ?>
                                                        <div style="font-size:.72rem;color:#94a3b8;">
                                                            <?= esc(substr($p['description'],0,35)) ?>...
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge"
                                                  style="background:#f0f9ff;color:#0369a1;font-weight:700;">
                                                <?= esc($p['category_name'] ?? 'Uncategorized') ?>
                                            </span>
                                        </td>
                                        <td>
                                            <code style="font-size:.78rem;color:#64748b;">
                                                <?= esc($p['barcode'] ?: '—') ?>
                                            </code>
                                        </td>
                                        <td class="fw-bold text-primary">
                                            ₱<?= number_format($p['price'], 2) ?>
                                        </td>
                                        <td class="text-muted">
                                            ₱<?= number_format($p['cost_price'], 2) ?>
                                        </td>
                                        <td>
                                            <span class="fw-bold <?= $profit >= 0 ? 'text-success' : 'text-danger' ?>">
                                                ₱<?= number_format($profit, 2) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($isOut): ?>
                                                <span class="badge bg-danger">Out of Stock</span>
                                            <?php elseif ($isLow): ?>
                                                <span class="badge bg-warning text-dark">
                                                    <?= $p['stock'] ?> - Low
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-success">
                                                    <?= $p['stock'] ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-muted" style="font-size:.82rem;">
                                            <?= esc($p['unit']) ?>
                                        </td>
                                        <?php if (session()->get('user_role') === 'owner'): ?>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <button class="btn btn-sm btn-ws-orange"
                                                            style="padding:4px 10px;"
                                                            onclick="openRestock(<?= $p['id'] ?>,
                                                                    '<?= esc($p['name']) ?>',
                                                                    <?= $p['stock'] ?>,
                                                                    '<?= esc($p['unit']) ?>')"
                                                            title="Restock">
                                                        <i class="bi bi-plus-circle-fill"></i>
                                                    </button>
                                                    <a href="<?= base_url('products/edit/'.$p['id']) ?>"
                                                       class="btn btn-sm btn-light"
                                                       style="padding:4px 10px;"
                                                       title="Edit">
                                                        <i class="bi bi-pencil-fill text-primary"></i>
                                                    </a>
                                                    <button class="btn btn-sm btn-light"
                                                            style="padding:4px 10px;"
                                                            onclick="confirmDelete(<?= $p['id'] ?>,
                                                                     '<?= esc($p['name']) ?>')"
                                                            title="Delete">
                                                        <i class="bi bi-trash-fill text-danger"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius:14px;border:none;">
            <div class="modal-body p-4 text-center">
                <div class="mb-3 text-danger" style="font-size:3rem;">
                    <i class="bi bi-exclamation-circle"></i>
                </div>
                <h5 class="fw-bold">Delete Product?</h5>
                <p class="text-muted small">
                    Are you sure you want to delete <strong id="delProdName"></strong>? This cannot be undone.
                </p>
                <div class="d-grid gap-2 mt-4">
                    <a id="delLink" href="#" class="btn btn-danger">Delete Now</a>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Restock Modal -->
<div class="modal fade" id="restockModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius:14px;border:none;">
            <form action="<?= base_url('products/restock') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="product_id" id="restockId">
                <div class="modal-body p-4">
                    <h5 class="fw-bold mb-1">Restock Product</h5>
                    <p class="text-muted small mb-4" id="restockName"></p>
                    
                    <div class="mb-3">
                        <label class="form-label">Quantity to Add</label>
                        <div class="input-group">
                            <input type="number" name="quantity" 
                                   class="form-control" 
                                   value="1" min="1" required>
                            <span class="input-group-text" id="restockUnit"></span>
                        </div>
                        <small class="text-muted mt-1 d-block">
                            Current Stock: <span id="restockCurrent" class="fw-bold"></span>
                        </small>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-ws-orange">
                            <i class="bi bi-plus-lg me-1"></i>Add Stock
                        </button>
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Filtering logic
    function applyFilter() {
        const q     = document.getElementById('searchInput').value.toLowerCase();
        const cat   = document.getElementById('catFilter').value.toLowerCase();
        const stock = document.getElementById('stockFilter').value;

        document.querySelectorAll('#prodTable tbody tr').forEach(row => {
            const matchQ     = row.dataset.search?.includes(q) ?? true;
            const matchCat   = !cat   || row.dataset.cat?.toLowerCase().includes(cat);
            const matchStock = !stock || row.dataset.stock === stock;
            row.style.display = matchQ && matchCat && matchStock ? '' : 'none';
        });
    }

    ['searchInput','catFilter','stockFilter'].forEach(id => {
        document.getElementById(id)?.addEventListener('input', applyFilter);
        document.getElementById(id)?.addEventListener('change', applyFilter);
    });

    // Delete confirm
    function confirmDelete(id, name) {
        document.getElementById('delProdName').textContent = name;
        document.getElementById('delLink').href =
            '<?= base_url("products/delete") ?>/' + id;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }

    // Restock modal
    function openRestock(id, name, current, unit) {
        document.getElementById('restockId').value = id;
        document.getElementById('restockName').textContent = name;
        document.getElementById('restockCurrent').textContent = current + ' ' + unit;
        document.getElementById('restockUnit').textContent = unit;
        new bootstrap.Modal(document.getElementById('restockModal')).show();
    }
</script>
</body>
</html>