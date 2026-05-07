<?php // app/Views/utang/view.php ?>
<?= view('layout/header') ?>
<?= view('layout/sidebar') ?>

<div class="ws-main">
    <header class="ws-topbar">
        <div class="page-title">
            Utang Detail
            <small><?= esc($sale['customer_name']) ?></small>
        </div>
        <div class="d-flex gap-2">
            <?php if ($balance <= 0): ?>
                <span class="badge bg-success"
                      style="font-size:.85rem;padding:8px 14px">
                    ✅ Fully Paid
                </span>
            <?php endif; ?>
            <a href="<?= base_url('utang') ?>"
               class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Back
            </a>
        </div>
    </header>

    <div class="ws-content fade-in">

        <!-- Flash -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show mb-4">
                <i class="bi bi-check-circle-fill me-2"></i>
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close"
                        data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show mb-4">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close"
                        data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row g-4">

            <!-- LEFT: Sale Info + Payment History -->
            <div class="col-lg-7">

                <!-- Sale Details Card -->
                <div class="ws-card mb-4">
                    <div class="ws-card-body">

                        <div class="d-flex justify-content-between
                                    align-items-center mb-4">
                            <div>
                                <h5 class="fw-bold mb-1">
                                    <?= esc($sale['invoice_no']) ?>
                                </h5>
                                <small class="text-muted">
                                    <?= date('F j, Y g:i A',
                                        strtotime($sale['created_at'])) ?>
                                </small>
                            </div>
                            <span class="badge <?= $balance <= 0
                                ? 'bg-success' : 'bg-warning text-dark' ?>"
                                  style="font-size:.82rem;padding:7px 14px">
                                <?= $balance <= 0 ? 'PAID' : 'UTANG' ?>
                            </span>
                        </div>

                        <!-- Balance Summary -->
                        <div class="row g-2 mb-4">
                            <div class="col-4">
                                <div style="background:#f8fafc;border-radius:10px;
                                            padding:12px;text-align:center;
                                            border:1px solid #e2e8f0">
                                    <div style="font-size:.68rem;color:#64748b;
                                                font-weight:700;text-transform:uppercase">
                                        Total Debt
                                    </div>
                                    <div class="fw-bold text-primary mt-1"
                                         style="font-size:1.1rem">
                                        ₱<?= number_format($sale['total'], 2) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div style="background:#f0fdf4;border-radius:10px;
                                            padding:12px;text-align:center;
                                            border:1px solid #bbf7d0">
                                    <div style="font-size:.68rem;color:#64748b;
                                                font-weight:700;text-transform:uppercase">
                                        Total Paid
                                    </div>
                                    <div class="fw-bold text-success mt-1"
                                         style="font-size:1.1rem">
                                        ₱<?= number_format($totalPaid, 2) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div style="background:<?= $balance <= 0
                                                ? '#f0fdf4' : '#fef2f2' ?>;
                                            border-radius:10px;
                                            padding:12px;text-align:center;
                                            border:1px solid <?= $balance <= 0
                                                ? '#bbf7d0' : '#fecaca' ?>">
                                    <div style="font-size:.68rem;color:#64748b;
                                                font-weight:700;text-transform:uppercase">
                                        Balance
                                    </div>
                                    <div class="fw-bold mt-1"
                                         style="font-size:1.1rem;
                                                color:<?= $balance <= 0
                                                    ? '#16a34a' : '#dc2626' ?>">
                                        ₱<?= number_format($balance, 2) ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <?php
                            $pct = $sale['total'] > 0
                                ? round(($totalPaid / $sale['total']) * 100)
                                : 0;
                        ?>
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-1"
                                 style="font-size:.78rem;font-weight:700">
                                <span>Payment Progress</span>
                                <span><?= $pct ?>%</span>
                            </div>
                            <div class="progress"
                                 style="height:12px;border-radius:6px">
                                <div class="progress-bar bg-success"
                                     style="width:<?= $pct ?>%;
                                            border-radius:6px;
                                            transition:width .5s ease">
                                </div>
                            </div>
                        </div>

                        <!-- Items -->
                        <h6 class="fw-bold mb-2">Items Purchased</h6>
                        <table class="table ws-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($sale['items'] as $item): ?>
                                    <tr>
                                        <td class="fw-bold">
                                            <?= esc($item['product_name']) ?>
                                        </td>
                                        <td class="text-center">
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
                                <tr style="background:#f8fafc">
                                    <td colspan="3"
                                        class="text-end fw-bold">
                                        TOTAL:
                                    </td>
                                    <td class="text-end fw-bold text-primary">
                                        ₱<?= number_format($sale['total'], 2) ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Payment History -->
                <div class="ws-card">
                    <div class="ws-card-body">
                        <h6 class="fw-bold mb-3">
                            <i class="bi bi-clock-history me-2 text-primary"></i>
                            Payment History
                        </h6>

                        <?php if (empty($payments)): ?>
                            <div class="text-center py-3 text-muted">
                                <i class="bi bi-inbox"
                                   style="font-size:2rem;opacity:.3"></i>
                                <p class="mt-2" style="font-size:.82rem">
                                    No payments recorded yet.
                                </p>
                            </div>
                        <?php else: ?>
                            <div class="timeline">
                                <?php foreach ($payments as $pmt): ?>
                                    <div class="d-flex gap-3 mb-3 pb-3"
                                         style="border-bottom:1px dashed #e2e8f0">
                                        <div style="width:38px;height:38px;
                                                    background:#f0fdf4;
                                                    border-radius:50%;
                                                    display:flex;
                                                    align-items:center;
                                                    justify-content:center;
                                                    flex-shrink:0;
                                                    font-size:1rem">
                                            💰
                                        </div>
                                        <div style="flex:1">
                                            <div class="d-flex justify-content-between">
                                                <span class="fw-bold text-success">
                                                    +₱<?= number_format($pmt['amount'], 2) ?>
                                                </span>
                                                <span style="font-size:.75rem;
                                                             color:#64748b">
                                                    <?= date('M j, Y g:i A',
                                                        strtotime($pmt['created_at'])) ?>
                                                </span>
                                            </div>
                                            <div style="font-size:.78rem;color:#64748b">
                                                Recorded by:
                                                <?= esc($pmt['recorder_name'] ?? '—') ?>
                                            </div>
                                            <?php if ($pmt['notes']): ?>
                                                <div style="font-size:.75rem;
                                                            color:#94a3b8;
                                                            margin-top:2px">
                                                    📝 <?= esc($pmt['notes']) ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

            <!-- RIGHT: Record Payment -->
            <div class="col-lg-5">

                <!-- Customer Info -->
                <div class="ws-card mb-4">
                    <div class="ws-card-body">
                        <h6 class="fw-bold mb-3">Customer Info</h6>
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div style="width:50px;height:50px;
                                        background:linear-gradient(135deg,#f97316,#ea6c00);
                                        border-radius:50%;
                                        display:flex;align-items:center;
                                        justify-content:center;
                                        font-size:1.4rem;color:#fff;
                                        font-weight:800;flex-shrink:0">
                                <?= strtoupper(substr($sale['customer_name'], 0, 1)) ?>
                            </div>
                            <div>
                                <div class="fw-bold">
                                    <?= esc($sale['customer_name']) ?>
                                </div>
                                <div style="font-size:.75rem;color:#64748b">
                                    Since: <?= date('M j, Y',
                                        strtotime($sale['created_at'])) ?>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between"
                             style="font-size:.82rem;padding:10px;
                                    background:#fef2f2;border-radius:10px;
                                    border:1px solid #fecaca">
                            <span class="fw-bold">Outstanding Balance:</span>
                            <span class="fw-bold text-danger"
                                  style="font-size:1rem">
                                ₱<?= number_format($balance, 2) ?>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Record Payment Form -->
                <?php if ($balance > 0): ?>
                    <div class="ws-card mb-4">
                        <div class="ws-card-body">
                            <h6 class="fw-bold mb-3">
                                <i class="bi bi-cash-stack me-2 text-success"></i>
                                Record Payment
                            </h6>

                            <form action="<?= base_url('utang/pay/'.$sale['id']) ?>"
                                  method="POST">
                                <?= csrf_field() ?>

                                <div class="mb-3">
                                    <label class="form-label">
                                        Amount Received (₱) *
                                    </label>
                                    <input type="number"
                                           name="amount"
                                           id="payAmount"
                                           class="form-control"
                                           style="font-size:1.2rem;
                                                  font-weight:800;
                                                  text-align:center"
                                           min="1"
                                           max="<?= $balance ?>"
                                           step="0.01"
                                           placeholder="0.00"
                                           required
                                           oninput="checkAmount(this)">

                                    <!-- Quick amount buttons -->
                                    <div class="d-flex gap-1 flex-wrap mt-2">
                                        <button type="button"
                                                class="btn btn-outline-secondary btn-sm"
                                                style="font-size:.72rem;border-radius:8px"
                                                onclick="setAmt(<?= $balance ?>)">
                                            Full ₱<?= number_format($balance, 2) ?>
                                        </button>
                                        <?php
                                        $halves = [50, 100, 200, 500];
                                        foreach ($halves as $h) {
                                            if ($h < $balance) {
                                                echo '<button type="button"
                                                             class="btn btn-outline-secondary btn-sm"
                                                             style="font-size:.72rem;border-radius:8px"
                                                             onclick="setAmt(' . $h . ')">
                                                         ₱' . $h . '
                                                     </button>';
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Notes</label>
                                    <input type="text"
                                           name="notes"
                                           class="form-control"
                                           placeholder="e.g. Partial payment, bayad sa utang...">
                                </div>

                                <!-- Amount preview -->
                                <div class="mb-3 p-3 rounded-3"
                                     style="background:#f8fafc;
                                            border:1.5px solid #e2e8f0">
                                    <div class="d-flex justify-content-between mb-1"
                                         style="font-size:.82rem">
                                        <span class="text-muted">Balance Before:</span>
                                        <span class="fw-bold text-danger">
                                            ₱<?= number_format($balance, 2) ?>
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1"
                                         style="font-size:.82rem">
                                        <span class="text-muted">Payment:</span>
                                        <span class="fw-bold text-success"
                                              id="prevPay">
                                            ₱0.00
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-between"
                                         style="font-size:.88rem;
                                                border-top:1px dashed #e2e8f0;
                                                padding-top:6px;margin-top:4px">
                                        <span class="fw-bold">Remaining After:</span>
                                        <span class="fw-bold" id="prevBal"
                                              style="font-size:.95rem">
                                            ₱<?= number_format($balance, 2) ?>
                                        </span>
                                    </div>
                                </div>

                                <button type="submit"
                                        class="btn btn-success w-100 fw-bold"
                                        style="border-radius:10px;padding:12px">
                                    <i class="bi bi-cash me-2"></i>
                                    Record Payment
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Mark as fully paid (Owner only) -->
                    <?php if (session()->get('user_role') === 'owner'): ?>
                        <div class="ws-card">
                            <div class="ws-card-body">
                                <h6 class="fw-bold mb-2 text-muted">
                                    Owner Override
                                </h6>
                                <p class="text-muted" style="font-size:.78rem">
                                    Mark this utang as fully paid without
                                    recording specific payment amount.
                                </p>
                                <a href="<?= base_url('utang/markpaid/'.$sale['id']) ?>"
                                   class="btn btn-outline-success w-100"
                                   style="border-radius:10px"
                                   onclick="return confirm('Mark as fully paid?')">
                                    <i class="bi bi-check2-all me-2"></i>
                                    Mark as Fully Paid
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>

                <?php else: ?>
                    <!-- Fully paid state -->
                    <div class="ws-card">
                        <div class="ws-card-body text-center py-4">
                            <div style="font-size:3rem">✅</div>
                            <h5 class="fw-bold mt-3 text-success">
                                Fully Paid!
                            </h5>
                            <p class="text-muted" style="font-size:.82rem">
                                This utang has been completely settled.
                            </p>
                            <a href="<?= base_url('sales/invoice/'.$sale['id']) ?>"
                               class="btn btn-outline-primary btn-sm"
                               target="_blank">
                                <i class="bi bi-printer me-1"></i>
                                Print Receipt
                            </a>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    var maxBalance = <?= $balance ?>;

    function setAmt(val) {
        document.getElementById('payAmount').value = val;
        checkAmount(document.getElementById('payAmount'));
    }

    function checkAmount(input) {
        var val = parseFloat(input.value) || 0;
        if (val > maxBalance) val = maxBalance;
        var remaining = Math.max(0, maxBalance - val);

        document.getElementById('prevPay').textContent =
            '₱' + val.toFixed(2);
        document.getElementById('prevBal').textContent =
            '₱' + remaining.toFixed(2);
        document.getElementById('prevBal').style.color =
            remaining <= 0 ? '#16a34a' : '#dc2626';
    }
</script>
</body>
</html>