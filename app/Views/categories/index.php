<?php  ?>
<?= view('layout/header') ?>
<?= view('layout/sidebar') ?>

<div class="ws-main">
    <header class="ws-topbar">
        <div class="page-title">Categories
            <small><?= count($categories) ?> total</small>
        </div>
        <button class="btn btn-primary btn-sm"
                data-bs-toggle="modal" data-bs-target="#addCatModal">
            <i class="bi bi-plus-lg me-1"></i>Add Category
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

        <div class="row g-3">
            <?php foreach ($categories as $cat): ?>
                <div class="col-md-4 col-lg-3">
                    <div class="ws-card">
                        <div class="ws-card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="fw-bold mb-1"><?= esc($cat['name']) ?></h6>
                                    <p class="text-muted mb-2" style="font-size:.78rem;">
                                        <?= esc($cat['description'] ?: 'No description') ?>
                                    </p>
                                    <span class="badge bg-primary">
                                        <?= $cat['product_count'] ?> products
                                    </span>
                                </div>
                                <div class="d-flex flex-column gap-1">
                                    <button class="btn btn-sm btn-light"
                                            style="padding:3px 8px;"
                                            onclick="editCat(<?= $cat['id'] ?>,
                                                            '<?= esc($cat['name']) ?>',
                                                            '<?= esc($cat['description']) ?>')">
                                        <i class="bi bi-pencil text-primary"></i>
                                    </button>
                                    <a href="<?= base_url('categories/delete/'.$cat['id']) ?>"
                                       class="btn btn-sm btn-light"
                                       style="padding:3px 8px;"
                                       onclick="return confirm('Delete this category?')">
                                        <i class="bi bi-trash text-danger"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (empty($categories)): ?>
                <div class="col-12 text-center py-5">
                    <div style="font-size:3rem;">🏷️</div>
                    <p class="text-muted mt-2">No categories yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addCatModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;border:none;">
            <div class="modal-header border-0 pb-0">
                <h6 class="fw-bold">Add Category</h6>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('categories/store') ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Category Name *</label>
                        <input type="text" name="name" class="form-control"
                               placeholder="e.g. Beverages" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="2"
                                  placeholder="Optional..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editCatModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;border:none;">
            <div class="modal-header border-0 pb-0">
                <h6 class="fw-bold">Edit Category</h6>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editCatForm" method="POST">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Category Name *</label>
                        <input type="text" name="name" id="editName"
                               class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" id="editDesc"
                                  class="form-control" rows="2"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function editCat(id, name, desc) {
        document.getElementById('editCatForm').action =
            '<?= base_url("categories/update") ?>/' + id;
        document.getElementById('editName').value = name;
        document.getElementById('editDesc').value  = desc;
        new bootstrap.Modal(document.getElementById('editCatModal')).show();
    }
</script>
</body>
</html>