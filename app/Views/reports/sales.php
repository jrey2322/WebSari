<?php ?>
<?= view('layout/header') ?>
<?= view('layout/sidebar') ?>

<div class="ws-main">
    <header class="ws-topbar">
        <div class="page-title">
            <button class="mobile-toggle" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>
            <div class="d-flex flex-column">
                <span style="font-size: 1.1rem; line-height: 1.2">Sales Report</span>
                <small style="font-size: .7rem"><?= date('M j',strtotime($from)).' – '.date('M j, Y',strtotime($to)) ?></small>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= base_url('reports/inventory') ?>" class="btn btn-light btn-sm">
                <i class="bi bi-clipboard-data me-1"></i>Inventory Report
            </a>
            <button onclick="window.print()" class="btn btn-success btn-sm">
                <i class="bi bi-printer me-1"></i>Print
            </button>
        </div>
    </header>

    <div class="ws-content fade-in">

        <!-- Date Filter -->
        <div class="ws-card mb-4">
            <div class="ws-card-body">
                <form method="GET" action="<?= base_url('reports/sales') ?>"
                      class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">From Date</label>
                        <input type="date" name="from" class="form-control"
                               value="<?= $from ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">To Date</label>
                        <input type="date" name="to" class="form-control"
                               value="<?= $to ?>">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-funnel me-1"></i>Filter
                        </button>
                    </div>
                    <div class="col-md-4 text-end">
                        <div style="font-size:.78rem;color:#64748b;">
                            Showing <?= count($sales) ?> transactions
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row g-3 mb-4">
            <?php
                $completed = array_filter($sales, fn($s) => $s['status'] === 'completed');
                $utang     = array_filter($sales, fn($s) => $s['status'] === 'utang');
                $void      = array_filter($sales, fn($s) => $s['status'] === 'void');
                $utangTot  = array_sum(array_column(iterator_to_array(
                                 new ArrayIterator($utang)), 'total'));
            ?>
            <div class="col-md-3">
                <div class="ws-card">
                    <div class="ws-card-body text-center">
                        <div style="font-size:.72rem;color:#64748b;font-weight:700;
                                    text-transform:uppercase;">Total Revenue</div>
                        <div style="font-size:1.8rem;font-weight:800;color:#2563eb;margin:6px 0;">
                            ₱<?= number_format($totalRev, 2) ?>
                        </div>
                        <small class="text-muted">From completed sales</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="ws-card">
                    <div class="ws-card-body text-center">
                        <div style="font-size:.72rem;color:#64748b;font-weight:700;
                                    text-transform:uppercase;">Transactions</div>
                        <div style="font-size:1.8rem;font-weight:800;color:#16a34a;margin:6px 0;">
                            <?= count($completed) ?>
                        </div>
                        <small class="text-muted">Completed sales</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="ws-card">
                    <div class="ws-card-body text-center">
                        <div style="font-size:.72rem;color:#64748b;font-weight:700;
                                    text-transform:uppercase;">Utang Amount</div>
                        <div style="font-size:1.8rem;font-weight:800;color:#dc2626;margin:6px 0;">
                            ₱<?= number_format($utangTot, 2) ?>
                        </div>
                        <small class="text-muted"><?= count($utang) ?> utang transactions</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="ws-card">
                    <div class="ws-card-body text-center">
                        <div style="font-size:.72rem;color:#64748b;font-weight:700;
                                    text-transform:uppercase;">Avg. per Sale</div>
                        <div style="font-size:1.8rem;font-weight:800;color:#7c3aed;margin:6px 0;">
                            ₱<?= count($completed) > 0
                                ? number_format($totalRev / count($completed), 2)
                                : '0.00' ?>
                        </div>
                        <small class="text-muted">Average transaction</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Table -->
        <div class="ws-card">
            <div class="ws-card-body">
                <div class="table-responsive">
                    <table class="table ws-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Invoice</th>
                                <th>Customer</th>
                                <th>Cashier</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th class="text-end">Total</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($sales)): ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        No sales for this period.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($sales as $i => $s): ?>
                                    <tr>
                                        <td class="text-muted"><?= $i+1 ?></td>
                                        <td>
                                            <a href="<?= base_url('sales/view/'.$s['id']) ?>"
                                               class="fw-bold text-primary text-decoration-none">
                                                <?= esc($s['invoice_no']) ?>
                                            </a>
                                        </td>
                                        <td><?= esc($s['customer_name']) ?></td>
                                        <td class="text-muted">
                                            <?= esc($s['cashier_name'] ?? '—') ?>
                                        </td>
                                        <td>
                                            <?php $pm = $s['payment_method'];
                                                  $pC = match($pm) {
                                                      'cash'  => 'bg-success',
                                                      'gcash' => 'bg-primary',
                                                      'utang' => 'bg-danger',
                                                      default => 'bg-secondary'
                                                  }; ?>
                                            <span class="badge <?= $pC ?>">
                                                <?= strtoupper($pm) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php $st = $s['status'];
                                                  $sC = match($st) {
                                                      'completed' => 'bg-success',
                                                      'void'      => 'bg-secondary',
                                                      'utang'     => 'bg-warning text-dark',
                                                      default     => 'bg-secondary'
                                                  }; ?>
                                            <span class="badge <?= $sC ?>">
                                                <?= ucfirst($st) ?>
                                            </span>
                                        </td>
                                        <td class="text-end fw-bold">
                                            ₱<?= number_format($s['total'], 2) ?>
                                        </td>
                                        <td style="font-size:.78rem;color:#64748b;">
                                            <?= date('M j, Y g:i A',
                                                strtotime($s['created_at'])) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr style="background:#f8fafc;">
                                    <td colspan="6" class="text-end fw-bold">
                                        Grand Total:
                                    </td>
                                    <td class="text-end fw-bold text-primary"
                                        style="font-size:1rem;">
                                        ₱<?= number_format($totalRev, 2) ?>
                                    </td>
                                    <td></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>