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
                <span style="font-size: 1.1rem; line-height: 1.2">Sale Details</span>
                <small style="font-size: .7rem"><?= esc($sale['invoice_no']) ?></small>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= base_url('sales/invoice/'.$sale['id']) ?>"
               class="btn btn-success btn-sm" target="_blank">
                <i class="bi bi-printer me-1"></i>Print Receipt
            </a>
            <?php if (session()->get('user_role') === 'owner'
                      && $sale['status'] === 'completed'): ?>
                <a href="<?= base_url('sales/void/'.$sale['id']) ?>"
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('Void this sale and restore stock?')">
                    <i class="bi bi-x-circle me-1"></i>Void Sale
                </a>
            <?php endif; ?>
            <a href="<?= base_url('sales') ?>" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Back
            </a>
        </div>
    </header>

    <div class="ws-content fade-in">

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show mb-4">
                <i class="bi bi-check-circle-fill me-2"></i>
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row g-4">

            <!-- Items Table -->
            <div class="col-lg-8">
                <div class="ws-card">
                    <div class="ws-card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h5 class="fw-bold mb-1"><?= esc($sale['invoice_no']) ?></h5>
                                <small class="text-muted">
                                    <?= date('F j, Y g:i A',
                                        strtotime($sale['created_at'])) ?>
                                </small>
                            </div>
                            <?php
                                $st = $sale['status'];
                                $stClass = match($st) {
                                    'completed' => 'bg-success',
                                    'void'      => 'bg-secondary',
                                    'pending', 'utang' => 'bg-warning text-dark',
                                    default     => 'bg-secondary'
                                };
                            ?>
                            <span class="badge <?= $stClass ?>"
                                  style="font-size:.82rem;padding:7px 15px;">
                                <?= ($st === 'utang' || $st === 'pending') ? 'PENDING' : strtoupper($st) ?>
                            </span>
                        </div>

                        <table class="table ws-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($sale['items'] as $item): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold" style="font-size:.875rem;">
                                                <?= esc($item['product_name']) ?>
                                            </div>
                                            <small class="text-muted">
                                                <?= esc($item['unit'] ?? 'pcs') ?>
                                            </small>
                                        </td>
                                        <td class="text-center fw-bold">
                                            <?= $item['quantity'] ?>
                                        </td>
                                        <td class="text-end">
                                            ₱<?= number_format($item['price'], 2) ?>
                                        </td>
                                        <td class="text-end fw-bold">
                                            ₱<?= number_format($item['subtotal'], 2) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end text-muted">Subtotal:</td>
                                    <td class="text-end">
                                        ₱<?= number_format($sale['subtotal'], 2) ?>
                                    </td>
                                </tr>
                                <?php if ($sale['discount'] > 0): ?>
                                    <tr>
                                        <td colspan="3" class="text-end text-danger">Discount:</td>
                                        <td class="text-end text-danger fw-bold">
                                            −₱<?= number_format($sale['discount'], 2) ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold"
                                        style="font-size:1rem;">TOTAL:</td>
                                    <td class="text-end fw-bold text-primary"
                                        style="font-size:1.15rem;">
                                        ₱<?= number_format($sale['total'], 2) ?>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Transaction Info -->
            <div class="col-lg-4">
                <div class="ws-card">
                    <div class="ws-card-body">
                        <h6 class="fw-bold mb-4">Transaction Info</h6>
                        <?php $info = [
                            ['👤 Customer',   $sale['customer_name']],
                            ['💳 Cashier',    $sale['cashier_name'] ?? '—'],
                            ['💰 Payment',    strtoupper($sale['payment_method'])],
                            ['💵 Amount Paid','₱'.number_format($sale['amount_paid'],2)],
                            ['🔄 Change',     '₱'.number_format($sale['change_amount'],2)],
                        ];
                        foreach ($info as [$label, $val]): ?>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted"
                                      style="font-size:.78rem;font-weight:700;">
                                    <?= $label ?>
                                </span>
                                <span class="fw-bold" style="font-size:.82rem;">
                                    <?= esc($val) ?>
                                </span>
                            </div>
                        <?php endforeach; ?>

                        <?php if ($sale['notes']): ?>
                            <div class="mt-3 p-3 rounded-3"
                                 style="background:#f8fafc;border:1px solid #e2e8f0;">
                                <div style="font-size:.72rem;font-weight:700;color:#64748b;">
                                    NOTES
                                </div>
                                <div style="font-size:.82rem;margin-top:4px;">
                                    <?= esc($sale['notes']) ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="mt-4">
                            <a href="<?= base_url('sales/create') ?>"
                               class="btn btn-ws-orange w-100 fw-bold">
                                <i class="bi bi-plus-circle me-2"></i>New Sale
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>