<?= view('layout/header') ?>
<?= view('layout/sidebar') ?>

<div class="ws-main">
    <header class="ws-topbar">
        <div class="page-title">
            <button class="mobile-toggle" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>
            <div class="d-flex flex-column">
                <span style="font-size: 1.1rem; line-height: 1.2">Activity Log</span>
                <small style="font-size: .7rem">System-wide user actions and events</small>
            </div>
        </div>
    </header>

    <div class="ws-content fade-in">
        <div class="ws-card">
            <div class="ws-card-body">
                <div class="table-responsive">
                    <table class="table ws-table" id="logsTable">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>User</th>
                                <th>Activity</th>
                                <th>Module</th>
                                <th>Details</th>
                                <th>IP Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($logs)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">No activity logs found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($logs as $log): ?>
                                    <tr>
                                        <td class="text-nowrap">
                                            <?= date('M d, Y h:i A', strtotime($log['created_at'])) ?>
                                        </td>
                                        <td>
                                            <div class="fw-bold"><?= esc($log['user_name'] ?? 'System Admin') ?></div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">
                                                <?= esc($log['activity']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-uppercase small fw-bold text-primary">
                                                <?= esc($log['module']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted d-block" style="max-width: 300px; overflow: hidden; text-overflow: ellipsis;">
                                                <?= esc($log['details']) ?>
                                            </small>
                                        </td>
                                        <td>
                                            <code class="small"><?= esc($log['ip_address']) ?></code>
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

<script>
$(document).ready(function() {
    // Optional: Add search/filter if needed
});
</script>
