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
                <span style="font-size: 1.1rem; line-height: 1.2">Sales History</span>
                <small style="font-size: .7rem">All transactions</small>
            </div>
        </div>
        <a href="<?= base_url('sales/create') ?>"
           class="btn btn-ws-orange btn-sm">
            <i class="bi bi-cart-plus-fill me-1"></i>New Sale
        </a>
    </header>

    <div class="ws-content fade-in">

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show mb-4">
                <i class="bi bi-check-circle-fill me-2"></i>
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="ws-card">
            <div class="ws-card-body">

                <!-- Filters -->
                <div class="row g-2 mb-4">
                    <div class="col-md-4">
                        <input type="text" id="sSearch"
                               class="form-control"
                               placeholder="🔍 Search invoice or customer...">
                    </div>
                    <div class="col-md-2">
                        <select id="sPayment" class="form-select">
                            <option value="">All Payments</option>
                            <option value="cash">Cash</option>
                            <option value="gcash">GCash</option>
                            <option value="utang">Utang</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select id="sStatus" class="form-select">
                            <option value="">All Status</option>
                            <option value="completed">Completed</option>
                            <option value="utang">Utang</option>
                            <option value="void">Void</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" id="sDate" class="form-control"
                               value="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-light w-100"
                                onclick="clearSalesFilter()">
                            Clear Filters
                        </button>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table ws-table" id="salesTbl">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Invoice</th>
                                <th>Customer</th>
                                <th>Cashier</th>
                                <th>Total</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($sales)): ?>
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <div style="font-size:2.5rem;">🧾</div>
                                        <p class="text-muted mt-2">No sales records yet.</p>
                                        <a href="<?= base_url('sales/create') ?>"
                                           class="btn btn-ws-orange btn-sm mt-1">
                                            Make your first sale!
                                        </a>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($sales as $i => $s): ?>
                                    <tr data-payment="<?= $s['payment_method'] ?>"
                                        data-status="<?= $s['status'] ?>"
                                        data-date="<?= date('Y-m-d', strtotime($s['created_at'])) ?>"
                                        data-search="<?= strtolower($s['invoice_no'].' '.$s['customer_name']) ?>">
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
                                        <td class="fw-bold">
                                            ₱<?= number_format($s['total'], 2) ?>
                                        </td>
                                        <td>
                                            <?php $pm = $s['payment_method'];
                                                  $pmC = match($pm) {
                                                      'cash'  => 'bg-success',
                                                      'gcash' => 'bg-primary',
                                                      'utang' => 'bg-danger',
                                                      default => 'bg-secondary'
                                                  }; ?>
                                            <span class="badge <?= $pmC ?>">
                                                <?= strtoupper($pm) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php $st = $s['status'];
                                                  $stC = match($st) {
                                                      'completed' => 'bg-success',
                                                      'void'      => 'bg-secondary',
                                                      'utang'     => 'bg-warning text-dark',
                                                      default     => 'bg-secondary'
                                                  }; ?>
                                            <span class="badge <?= $stC ?>">
                                                <?= ucfirst($st) ?>
                                            </span>
                                        </td>
                                        <td style="font-size:.78rem;color:#64748b;">
                                            <?= date('M j, Y g:i A',
                                                strtotime($s['created_at'])) ?>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="<?= base_url('sales/view/'.$s['id']) ?>"
                                                   class="btn btn-sm btn-light"
                                                   style="padding:3px 9px;"
                                                   title="View">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="<?= base_url('sales/invoice/'.$s['id']) ?>"
                                                   class="btn btn-sm btn-success"
                                                   style="padding:3px 9px;"
                                                   target="_blank" title="Print">
                                                    <i class="bi bi-printer"></i>
                                                </a>
                                            </div>
                                        </td>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function filterSales() {
        const q   = document.getElementById('sSearch').value.toLowerCase();
        const pay = document.getElementById('sPayment').value;
        const st  = document.getElementById('sStatus').value;
        const dt  = document.getElementById('sDate').value;

        document.querySelectorAll('#salesTbl tbody tr').forEach(row => {
            const ok =
                (!q   || row.dataset.search?.includes(q)) &&
                (!pay || row.dataset.payment === pay)     &&
                (!st  || row.dataset.status  === st)      &&
                (!dt  || row.dataset.date    === dt);
            row.style.display = ok ? '' : 'none';
        });
    }

    function clearSalesFilter() {
        ['sSearch','sPayment','sStatus'].forEach(id => {
            document.getElementById(id).value = '';
        });
        document.getElementById('sDate').value = '';
        filterSales();
    }

    ['sSearch','sPayment','sStatus','sDate'].forEach(id => {
        document.getElementById(id)?.addEventListener('input',  filterSales);
        document.getElementById(id)?.addEventListener('change', filterSales);
    });

    // Auto-filter by today on load
    filterSales();
</script>
</body>
</html>