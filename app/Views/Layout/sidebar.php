<?php 
$role    = session()->get('user_role');
$name    = session()->get('user_name');
$current = current_url();

function isActive(string $path): string {
    return str_contains(current_url(), $path) ? 'active' : '';
}
?>

<aside class="ws-sidebar">
    <!-- Brand -->
    <div class="ws-brand">
        <div class="ws-brand-icon">🏪</div>
        <div>
            <h5>WebSari</h5>
            <small>Sari-Sari Store System</small>
        </div>
        <!-- Close btn for mobile -->
        <button class="btn btn-link text-white ms-auto d-lg-none p-0" onclick="toggleSidebar()">
            <i class="bi bi-x-lg" style="font-size: 1.5rem;"></i>
        </button>
    </div>
    </div>

    <!-- Navigation -->
    <nav class="ws-nav">

        <div class="ws-nav-section">Main</div>

        <a href="<?= base_url('dashboard') ?>"
           class="ws-nav-link <?= isActive('/dashboard') ?>">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <div class="ws-nav-section mt-1">Inventory</div>

        <a href="<?= base_url('products') ?>"
           class="ws-nav-link <?= isActive('/products') ?>">
            <i class="bi bi-box-seam-fill"></i> Products
        </a>

        <?php if ($role === 'owner'): ?>
        <a href="<?= base_url('categories') ?>"
           class="ws-nav-link <?= isActive('/categories') ?>">
            <i class="bi bi-tags-fill"></i> Categories
        </a>
        <?php endif; ?>

        <a href="<?= base_url('products/low-stock') ?>"
           class="ws-nav-link <?= isActive('low-stock') ?>">
            <i class="bi bi-exclamation-triangle-fill"></i> Low Stock
        </a>

        <div class="ws-nav-section mt-1">Sales</div>

        <a href="<?= base_url('sales/create') ?>"
           class="ws-nav-link <?= isActive('sales/create') ?>">
            <i class="bi bi-cart-plus-fill"></i> New Sale (POS)
        </a>

        <a href="<?= base_url('sales') ?>"
           class="ws-nav-link <?= (str_contains(current_url(),'/sales') &&
                                   !str_contains(current_url(),'create')) ? 'active' : '' ?>">
            <i class="bi bi-receipt-cutoff"></i> Sales History
        </a>

        <a href="<?= base_url('utang') ?>"
   class="ws-nav-link <?= isActive('/utang') ?>">
    <i class="bi bi-pencil-square"></i> Utang Tracker
</a>

        <?php if ($role === 'owner'): ?>
        <div class="ws-nav-section mt-1">Owner</div>

        <a href="<?= base_url('reports/sales') ?>"
           class="ws-nav-link <?= isActive('/reports') ?>">
            <i class="bi bi-bar-chart-line-fill"></i> Reports
        </a>

        <a href="<?= base_url('users') ?>"
           class="ws-nav-link <?= isActive('/users') ?>">
            <i class="bi bi-people-fill"></i> Manage Staff
        </a>

        <a href="<?= base_url('logs') ?>"
           class="ws-nav-link <?= isActive('/logs') ?>">
            <i class="bi bi-journal-text"></i> Activity Log
        </a>
        <?php endif; ?>

    </nav>

    <!-- Footer -->
    <div class="ws-sidebar-footer">
        <div class="ws-avatar">
            <?= strtoupper(substr($name, 0, 1)) ?>
        </div>
        <div class="info">
            <div class="name"><?= esc($name) ?></div>
            <div class="role"><?= ucfirst($role) ?></div>
        </div>
        <a href="<?= base_url('logout') ?>" class="ws-logout" title="Logout">
            <i class="bi bi-box-arrow-right"></i>
        </a>
    </div>

</aside>