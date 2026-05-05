<?php  ?>
<?= view('layout/header') ?>
<?= view('layout/sidebar') ?>

<div class="ws-main">
    <header class="ws-topbar">
        <div class="page-title">Add Product <small>Fill in product details below</small></div>
        <a href="<?= base_url('products') ?>" class="btn btn-light btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
    </header>

    <div class="ws-content fade-in">
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger alert-dismissible fade show mb-4">
                <?php foreach (session()->getFlashdata('errors') as $e): ?>
                    <div><i class="bi bi-x-circle me-1"></i><?= esc($e) ?></div>
                <?php endforeach; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('products/store') ?>"
              method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="row g-4">

                <!-- Main Info -->
                <div class="col-lg-8">
                    <div class="ws-card">
                        <div class="ws-card-body">
                            <h6 class="fw-bold mb-4">
                                <i class="bi bi-box-seam me-2 text-primary"></i>
                                Product Information
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="form-label">Product Name *</label>
                                    <input type="text" name="name" class="form-control"
                                           placeholder="e.g. Coke Mismo (250ml)"
                                           value="<?= old('name') ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Category</label>
                                    <select name="category_id" class="form-select">
                                        <option value="">-- Select --</option>
                                        <?php foreach ($categories as $cat): ?>
                                            <option value="<?= $cat['id'] ?>"
                                                <?= old('category_id') == $cat['id'] ? 'selected' : '' ?>>
                                                <?= esc($cat['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control" rows="2"
                                              placeholder="Optional short description"><?= old('description') ?></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Barcode</label>
                                    <input type="text" name="barcode" class="form-control"
                                           placeholder="Scan or type barcode"
                                           value="<?= old('barcode') ?>">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Unit</label>
                                    <select name="unit" class="form-select">
                                        <?php foreach (['pcs','bottle','can','pack','sachet',
                                                        'box','kg','g','L','ml','loaf',
                                                        'dozen'] as $u): ?>
                                            <option value="<?= $u ?>"
                                                    <?= old('unit') === $u ? 'selected' : '' ?>>
                                                <?= $u ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Low Stock Alert</label>
                                    <input type="number" name="low_stock_alert"
                                           class="form-control" min="1"
                                           placeholder="5"
                                           value="<?= old('low_stock_alert', 5) ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing -->
                    <div class="ws-card mt-4">
                        <div class="ws-card-body">
                            <h6 class="fw-bold mb-4">
                                <i class="bi bi-tag me-2 text-success"></i>
                                Pricing & Stock
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Selling Price (₱) *</label>
                                    <input type="number" name="price" id="priceIn"
                                           class="form-control" min="0" step="0.01"
                                           placeholder="0.00"
                                           value="<?= old('price') ?>"
                                           oninput="calcProfit()" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Cost Price (₱) *</label>
                                    <input type="number" name="cost_price" id="costIn"
                                           class="form-control" min="0" step="0.01"
                                           placeholder="0.00"
                                           value="<?= old('cost_price') ?>"
                                           oninput="calcProfit()" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Initial Stock *</label>
                                    <input type="number" name="stock" class="form-control"
                                           min="0" placeholder="0"
                                           value="<?= old('stock', 0) ?>" required>
                                </div>
                            </div>

                            <!-- Profit Preview -->
                            <div class="mt-3 p-3 rounded-3"
                                 style="background:#f8fafc;border:1.5px solid #e2e8f0;">
                                <div class="d-flex justify-content-between"
                                     style="font-size:.82rem;">
                                    <span class="text-muted">Profit per unit:</span>
                                    <span id="profitDisplay" class="fw-bold text-success">
                                        ₱0.00
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between mt-1"
                                     style="font-size:.82rem;">
                                    <span class="text-muted">Profit margin:</span>
                                    <span id="marginDisplay" class="fw-bold text-primary">
                                        0%
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Image Upload -->
                <div class="col-lg-4">
                    <div class="ws-card">
                        <div class="ws-card-body">
                            <h6 class="fw-bold mb-3">
                                <i class="bi bi-image me-2 text-warning"></i>
                                Product Image
                            </h6>
                            <div id="imgPreview"
                                 onclick="document.getElementById('imgFile').click()"
                                 style="width:100%;height:180px;background:#f8fafc;
                                        border:2px dashed #cbd5e1;border-radius:12px;
                                        display:flex;flex-direction:column;
                                        align-items:center;justify-content:center;
                                        cursor:pointer;overflow:hidden;
                                        transition:border-color .2s;"
                                 onmouseover="this.style.borderColor='#2563eb'"
                                 onmouseout="this.style.borderColor='#cbd5e1'">
                                <div id="imgPrompt" class="text-center text-muted">
                                    <i class="bi bi-cloud-upload"
                                       style="font-size:2.2rem;"></i>
                                    <p style="font-size:.8rem;margin-top:8px;margin-bottom:0;">
                                        Click to upload<br>
                                        <span style="font-size:.7rem;">JPG, PNG (Max 2MB)</span>
                                    </p>
                                </div>
                                <img id="previewImg" src="" alt=""
                                     style="display:none;width:100%;height:100%;
                                            object-fit:cover;">
                            </div>
                            <input type="file" id="imgFile" name="image"
                                   accept="image/*" style="display:none;"
                                   onchange="previewImg(event)">
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div class="ws-card mt-4">
                        <div class="ws-card-body">
                            <button type="submit" class="btn btn-primary w-100 mb-2">
                                <i class="bi bi-save-fill me-2"></i>Save Product
                            </button>
                            <a href="<?= base_url('products') ?>"
                               class="btn btn-light w-100">
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function previewImg(e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = ev => {
            document.getElementById('previewImg').src = ev.target.result;
            document.getElementById('previewImg').style.display = 'block';
            document.getElementById('imgPrompt').style.display  = 'none';
        };
        reader.readAsDataURL(file);
    }

    function calcProfit() {
        const price  = parseFloat(document.getElementById('priceIn').value) || 0;
        const cost   = parseFloat(document.getElementById('costIn').value)  || 0;
        const profit = price - cost;
        const margin = price > 0 ? ((profit / price) * 100).toFixed(1) : 0;

        document.getElementById('profitDisplay').textContent =
            '₱' + profit.toFixed(2);
        document.getElementById('profitDisplay').className =
            'fw-bold ' + (profit >= 0 ? 'text-success' : 'text-danger');

        document.getElementById('marginDisplay').textContent = margin + '%';
    }
</script>
</body>
</html>