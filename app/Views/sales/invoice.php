<?php  ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt - <?= esc($sale['invoice_no']) ?></title>
    <style>
        * {
            font-family: 'Courier New', Courier, monospace;
            margin: 0; padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #e5e7eb;
            display: flex;
            justify-content: center;
            padding: 30px 10px;
        }

        .receipt {
            background: #fff;
            width: 300px;
            padding: 24px 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,.15);
        }

        .r-header { text-align: center; margin-bottom: 14px; }
        .r-header .emoji { font-size: 2rem; }
        .r-header h2 {
            font-size: 1.2rem;
            font-weight: 900;
            letter-spacing: 1px;
            margin: 4px 0 2px;
        }
        .r-header p { font-size: .72rem; color: #555; }

        .divider-dashed {
            border: none;
            border-top: 1.5px dashed #ccc;
            margin: 10px 0;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            font-size: .72rem;
            margin-bottom: 3px;
            color: #333;
        }

        table { width: 100%; margin: 6px 0; }
        table th {
            font-size: .68rem;
            text-align: left;
            color: #666;
            padding-bottom: 4px;
        }
        table th:last-child,
        table td:last-child { text-align: right; }

        table td { font-size: .72rem; padding: 2px 0; }

        .totals .row {
            display: flex;
            justify-content: space-between;
            font-size: .75rem;
            margin-bottom: 2px;
        }

        .grand-total {
            display: flex;
            justify-content: space-between;
            font-size: 1rem;
            font-weight: 900;
            margin-top: 6px;
        }

        .r-footer {
            text-align: center;
            font-size: .68rem;
            color: #666;
            margin-top: 14px;
        }

        .status-badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: .65rem;
            font-weight: 900;
            background: #dcfce7;
            color: #15803d;
            margin-bottom: 6px;
        }

        @media print {
            body { background: none; padding: 0; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

<div class="receipt">
    <div class="r-header">
        <div class="emoji">🏪</div>
        <h2>WebSari Store</h2>
        <p>Your Neighborhood Sari-Sari Store</p>
        <p>📍 [Store Address Here]</p>
        <p>📞 [Phone Number Here]</p>
    </div>

    <hr class="divider-dashed">

    <div class="status-badge"><?= strtoupper($sale['status']) ?></div>

    <div class="info-row">
        <span>Invoice:</span>
        <span><strong><?= esc($sale['invoice_no']) ?></strong></span>
    </div>
    <div class="info-row">
        <span>Date:</span>
        <span><?= date('m/d/Y g:i A', strtotime($sale['created_at'])) ?></span>
    </div>
    <div class="info-row">
        <span>Customer:</span>
        <span><?= esc($sale['customer_name']) ?></span>
    </div>
    <div class="info-row">
        <span>Cashier:</span>
        <span><?= esc($sale['cashier_name'] ?? '—') ?></span>
    </div>
    <div class="info-row">
        <span>Payment:</span>
        <span><?= strtoupper($sale['payment_method']) ?></span>
    </div>

    <hr class="divider-dashed">

    <table>
        <thead>
            <tr>
                <th style="width:45%;">Item</th>
                <th style="text-align:center;">Qty</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sale['items'] as $item): ?>
                <tr>
                    <td><?= esc($item['product_name']) ?></td>
                    <td style="text-align:center;"><?= $item['quantity'] ?></td>
                    <td style="text-align:right;">
                        ₱<?= number_format($item['price'], 2) ?>
                    </td>
                    <td style="text-align:right;">
                        ₱<?= number_format($item['subtotal'], 2) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <hr class="divider-dashed">

    <div class="totals">
        <div class="row">
            <span>Subtotal:</span>
            <span>₱<?= number_format($sale['subtotal'], 2) ?></span>
        </div>
        <?php if ($sale['discount'] > 0): ?>
            <div class="row">
                <span>Discount:</span>
                <span>-₱<?= number_format($sale['discount'], 2) ?></span>
            </div>
        <?php endif; ?>
        <div class="grand-total">
            <span>TOTAL:</span>
            <span>₱<?= number_format($sale['total'], 2) ?></span>
        </div>
    </div>

    <hr class="divider-dashed">

    <div class="info-row">
        <span>Amount Paid (<?= strtoupper($sale['payment_method']) ?>):</span>
        <span>₱<?= number_format($sale['amount_paid'], 2) ?></span>
    </div>
    <div class="info-row" style="font-weight:900;">
        <span>Change:</span>
        <span>₱<?= number_format($sale['change_amount'], 2) ?></span>
    </div>

    <div class="r-footer">
        <hr class="divider-dashed" style="margin-bottom:10px;">
        <p>Salamat sa inyong pagbili! 😊</p>
        <p>Thank you and come again!</p>
        <p style="margin-top:6px;font-size:.6rem;">
            Powered by WebSari System
        </p>
    </div>

    <!-- Print Button -->
    <div class="no-print" style="text-align:center;margin-top:20px;">
        <button onclick="window.print()"
                style="background:#2563eb;color:#fff;border:none;
                       border-radius:9px;padding:10px 28px;
                       font-family:'Courier New',monospace;
                       font-size:.875rem;font-weight:700;cursor:pointer;">
            🖨️ Print Receipt
        </button>
    </div>
</div>

</body>
</html>