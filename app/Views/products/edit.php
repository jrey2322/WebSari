<?php  ?>
<?= view('layout/header') ?>
<?= view('layout/sidebar') ?>

<div class="ws-main">
    <header class="ws-topbar">
        <button class="btn btn-sm btn-light d-lg-none me-2"
                onclick="document.getElementById('wsSidebar').classList.toggle('show')">
            <i class="bi bi-list fs-5"></i>
        </button>
        <div class="page-title">
            Edit Product
            <small><?= esc($product['name']) ?></small>
        </div>
        <a href="<?= base_url('products') ?>" class="btn btn-light btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
    </header>

    <div class="ws-content fade-in">

        <!-- Errors -->
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger alert-dismissible fade show mb-4">
                <?php foreach (session()->getFlashdata('errors') as $e): ?>
                    <div><i class="bi bi-x-circle me-1"></i><?= esc($e) ?></div>
                <?php endforeach; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('products/update/' . $product['id']) ?>"
              method="POST"
              enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="row g-4">

                <!-- LEFT: Product Info -->
                <div class="col-lg-8">

                    <!-- Basic Info -->
                    <div class="ws-card mb-4">
                        <div class="ws-card-body">
                            <h6 class="fw-bold mb-4">
                                <i class="bi bi-box-seam me-2 text-primary"></i>
                                Product Information
                            </h6>
                            <div class="row g-3">

                                <div class="col-md-8">
                                    <label class="form-label">Product Name *</label>
                                    <input type="text"
                                           name="name"
                                           class="form-control"
                                           placeholder="e.g. Coke Mismo (250ml)"
                                           value="<?= esc(old('name', $product['name'])) ?>"
                                           required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Category</label>
                                    <select name="category_id" class="form-select">
                                        <option value="">-- No Category --</option>
                                        <?php foreach ($categories as $cat): ?>
                                            <option value="<?= $cat['id'] ?>"
                                                <?= (old('category_id', $product['category_id']) == $cat['id'])
                                                    ? 'selected' : '' ?>>
                                                <?= esc($cat['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Description</label>
                                    <textarea name="description"
                                              class="form-control"
                                              rows="2"
                                              placeholder="Optional short description"><?= esc(old('description', $product['description'])) ?></textarea>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Barcode</label>
                                    <input type="text"
                                           name="barcode"
                                           class="form-control"
                                           placeholder="Scan or type barcode"
                                           value="<?= esc(old('barcode', $product['barcode'])) ?>">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Unit</label>
                                    <select name="unit" class="form-select">
                                        <?php
                                        $units = [
                                            'pcs','bottle','can','pack','sachet',
                                            'box','kg','g','L','ml','loaf','dozen'
                                        ];
                                        foreach ($units as $u):
                                        ?>
                                            <option value="<?= $u ?>"
                                                <?= (old('unit', $product['unit']) === $u)
                                                    ? 'selected' : '' ?>>
                                                <?= $u ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Low Stock Alert</label>
                                    <input type="number"
                                           name="low_stock_alert"
                                           class="form-control"
                                           min="1"
                                           placeholder="5"
                                           value="<?= esc(old('low_stock_alert', $product['low_stock_alert'])) ?>">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="active"
                                            <?= (old('status', $product['status']) === 'active')
                                                ? 'selected' : '' ?>>
                                            Active
                                        </option>
                                        <option value="inactive"
                                            <?= (old('status', $product['status']) === 'inactive')
                                                ? 'selected' : '' ?>>
                                            Inactive
                                        </option>
                                    </select>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Pricing & Stock -->
                    <div class="ws-card">
                        <div class="ws-card-body">
                            <h6 class="fw-bold mb-4">
                                <i class="bi bi-tag me-2 text-success"></i>
                                Pricing & Stock
                            </h6>
                            <div class="row g-3">

                                <div class="col-md-4">
                                    <label class="form-label">Selling Price (₱) *</label>
                                    <input type="number"
                                           name="price"
                                           id="priceIn"
                                           class="form-control"
                                           min="0"
                                           step="0.01"
                                           placeholder="0.00"
                                           value="<?= esc(old('price', $product['price'])) ?>"
                                           oninput="calcProfit()"
                                           required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Cost Price (₱) *</label>
                                    <input type="number"
                                           name="cost_price"
                                           id="costIn"
                                           class="form-control"
                                           min="0"
                                           step="0.01"
                                           placeholder="0.00"
                                           value="<?= esc(old('cost_price', $product['cost_price'])) ?>"
                                           oninput="calcProfit()"
                                           required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Current Stock *</label>
                                    <input type="number"
                                           name="stock"
                                           class="form-control"
                                           min="0"
                                           placeholder="0"
                                           value="<?= esc(old('stock', $product['stock'])) ?>"
                                           required>
                                </div>

                            </div>

                            <!-- Profit Preview -->
                            <div class="mt-3 p-3 rounded-3"
                                 style="background:#f8fafc;border:1.5px solid #e2e8f0;">
                                <div class="row text-center">
                                    <div class="col-4">
                                        <div style="font-size:.72rem;color:#64748b;font-weight:700;">
                                            SELL PRICE
                                        </div>
                                        <div id="showPrice" class="fw-bold text-primary">
                                            ₱<?= number_format($product['price'], 2) ?>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div style="font-size:.72rem;color:#64748b;font-weight:700;">
                                            COST PRICE
                                        </div>
                                        <div id="showCost" class="fw-bold text-muted">
                                            ₱<?= number_format($product['cost_price'], 2) ?>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div style="font-size:.72rem;color:#64748b;font-weight:700;">
                                            PROFIT/UNIT
                                        </div>
                                        <div id="showProfit"
                                             class="fw-bold <?= ($product['price'] - $product['cost_price']) >= 0
                                                              ? 'text-success' : 'text-danger' ?>">
                                            ₱<?= number_format($product['price'] - $product['cost_price'], 2) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                <!-- RIGHT: Image & Actions -->
                <div class="col-lg-4">

                    <!-- Image Upload -->
                    <div class="ws-card mb-4">
                        <div class="ws-card-body">
                            <h6 class="fw-bold mb-3">
                                <i class="bi bi-image me-2 text-warning"></i>
                                Product Image
                            </h6>

                            <!-- Current Image Preview -->
                            <div id="imgPreview"
                                 onclick="document.getElementById('imgFile').click()"
                                 style="width:100%;height:180px;border:2px dashed #cbd5e1;
                                        border-radius:12px;overflow:hidden;cursor:pointer;
                                        display:flex;align-items:center;justify-content:center;
                                        background:#f8fafc;transition:border-color .2s;"
                                 onmouseover="this.style.borderColor='#2563eb'"
                                 onmouseout="this.style.borderColor='#cbd5e1'">

                                <?php if ($product['image']): ?>
                                    <img id="previewImg"
                                         src="<?= base_url('uploads/products/' . $product['image']) ?>"
                                         alt="Product Image"
                                         style="width:100%;height:100%;object-fit:cover;">
                                <?php else: ?>
                                    <div id="imgPrompt" class="text-center text-muted">
                                        <i class="bi bi-cloud-upload"
                                           style="font-size:2.2rem;"></i>
                                        <p style="font-size:.8rem;margin-top:8px;margin-bottom:0;">
                                            Click to upload<br>
                                            <span style="font-size:.7rem;">JPG, PNG (Max 2MB)</span>
                                        </p>
                                    </div>
                                    <img id="previewImg"
                                         src=""
                                         alt=""
                                         style="display:none;width:100%;height:100%;object-fit:cover;">
                                <?php endif; ?>
                            </div>

                            <input type="file"
                                   id="imgFile"
                                   name="image"
                                   accept="image/*"
                                   style="display:none;"
                                   onchange="previewImage(event)">

                            <p class="text-muted mt-2 mb-0" style="font-size:.72rem;">
                                <i class="bi bi-info-circle me-1"></i>
                                Leave empty to keep current image.
                            </p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="ws-card">
                        <div class="ws-card-body">
                            <h6 class="fw-bold mb-3">Actions</h6>

                            <button type="submit" class="btn btn-primary w-100 mb-2">
                                <i class="bi bi-save-fill me-2"></i>Update Product
                            </button>

                            <button type="button" class="btn btn-ws-orange w-100 mb-2"
                                    onclick="openRestock(<?= $product['id'] ?>, 
                                            '<?= esc($product['name']) ?>', 
                                            <?= $product['stock'] ?>, 
                                            '<?= esc($product['unit']) ?>')">
                                <i class="bi bi-plus-circle-fill me-2"></i>Restock Product
                            </button>

                            <a href="<?= base_url('products') ?>"
                               class="btn btn-light w-100 mb-2">
                                <i class="bi bi-x-circle me-1"></i>Cancel
                            </a>

                            <hr>

                            <a href="<?= base_url('products/delete/' . $product['id']) ?>"
                               class="btn btn-danger w-100"
                               onclick="return confirm('Delete this product?')">
                                <i class="bi bi-trash-fill me-2"></i>Delete Product
                            </a>
                        </div>
                    </div>

                    <!-- Product Info Card -->
                    <div class="ws-card mt-4">
                        <div class="ws-card-body">
                            <h6 class="fw-bold mb-3">Product Details</h6>
                            <div class="d-flex justify-content-between mb-2"
                                 style="font-size:.82rem;">
                                <span class="text-muted">Product ID:</span>
                                <span class="fw-bold">#<?= $product['id'] ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2"
                                 style="font-size:.82rem;">
                                <span class="text-muted">Current Stock:</span>
                                <span class="fw-bold
                                    <?= $product['stock'] <= 0
                                        ? 'text-danger'
                                        : ($product['stock'] <= $product['low_stock_alert']
                                            ? 'text-warning'
                                            : 'text-success') ?>">
                                    <?= $product['stock'] ?> <?= esc($product['unit']) ?>
                                </span>
                            </div>
                            <div class="d-flex justify-content-between mb-2"
                                 style="font-size:.82rem;">
                                <span class="text-muted">Status:</span>
                                <span class="badge
                                    <?= $product['status'] === 'active'
                                        ? 'bg-success' : 'bg-secondary' ?>">
                                    <?= ucfirst($product['status']) ?>
                                </span>
                            </div>
                            <div class="d-flex justify-content-between"
                                 style="font-size:.82rem;">
                                <span class="text-muted">Last Updated:</span>
                                <span style="font-size:.75rem;color:#64748b;">
                                    <?= date('M j, Y', strtotime($product['updated_at'])) ?>
                                </span>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </form>
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
    // ── Image Preview ─────────────────────────────────────
    function previewImage(e) {
        var file = e.target.files[0];
        if (!file) return;

        var reader = new FileReader();
        reader.onload = function(ev) {
            var img    = document.getElementById('previewImg');
            var prompt = document.getElementById('imgPrompt');

            img.src            = ev.target.result;
            img.style.display  = 'block';
            if (prompt) prompt.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }

    // ── Profit Calculator ─────────────────────────────────
    function calcProfit() {
        var price  = parseFloat(document.getElementById('priceIn').value) || 0;
        var cost   = parseFloat(document.getElementById('costIn').value)  || 0;
        var profit = price - cost;

        document.getElementById('showPrice').textContent =
            '₱' + price.toFixed(2);
        document.getElementById('showCost').textContent  =
            '₱' + cost.toFixed(2);
        document.getElementById('showProfit').textContent =
            '₱' + profit.toFixed(2);
        document.getElementById('showProfit').className =
            'fw-bold ' + (profit >= 0 ? 'text-success' : 'text-danger');
    }

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