<?php  ?>
<?= view('layout/header') ?>
<?= view('layout/sidebar') ?>

<style>
/* POS Specific */
.pos-wrap {
    display: flex;
    height: calc(100vh - 64px);
    overflow: hidden;
}

.pos-left {
    flex: 1;
    overflow-y: auto;
    padding: 18px;
    background: #f1f5f9;
}

.pos-right {
    width: 360px;
    flex-shrink: 0;
    background: #fff;
    display: flex;
    flex-direction: column;
    border-left: 1px solid #e2e8f0;
}

/* Product Grid */
.prod-card {
    background: #fff;
    border-radius: 12px;
    padding: 12px;
    cursor: pointer;
    border: 2px solid transparent;
    transition: all .2s;
    height: 100%;
    box-shadow: 0 1px 3px rgba(0,0,0,.06);
}

.prod-card:hover {
    border-color: #2563eb;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(37,99,235,.15);
}

.prod-card.disabled {
    opacity: .5;
    cursor: not-allowed;
    pointer-events: none;
}

.prod-thumb {
    width: 100%;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
    margin-bottom: 8px;
    background: #f8fafc;
}

.prod-thumb-placeholder {
    width: 100%;
    height: 80px;
    background: #f1f5f9;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    margin-bottom: 8px;
}

/* Cart */
.cart-head {
    padding: 18px;
    background: linear-gradient(135deg, #0f172a, #1e3a5f);
    color: #fff;
}

.cart-body {
    flex: 1;
    overflow-y: auto;
    padding: 12px;
}

.cart-foot { padding: 14px; border-top: 1px solid #e2e8f0; }

.cart-item {
    background: #f8fafc;
    border-radius: 10px;
    padding: 10px 12px;
    margin-bottom: 8px;
    border: 1px solid #e2e8f0;
}

.qty-btn {
    width: 28px; height: 28px;
    border: 1.5px solid #e2e8f0;
    background: #fff;
    border-radius: 7px;
    cursor: pointer;
    font-size: .9rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all .15s;
    color: #475569;
}

.qty-btn:hover { background: #2563eb; color: #fff; border-color: #2563eb; }

.pay-btn {
    border: 1.5px solid #e2e8f0;
    background: #f8fafc;
    border-radius: 9px;
    padding: 8px 5px;
    text-align: center;
    cursor: pointer;
    transition: all .2s;
    font-size: .78rem;
    font-weight: 700;
    color: #475569;
    flex: 1;
}

.pay-btn.selected {
    border-color: #2563eb;
    background: #eff6ff;
    color: #2563eb;
}

.cat-pill {
    border: 1.5px solid #e2e8f0;
    background: #fff;
    border-radius: 20px;
    padding: 5px 14px;
    font-size: .78rem;
    font-weight: 700;
    cursor: pointer;
    white-space: nowrap;
    transition: all .2s;
    color: #475569;
}

.cat-pill.active {
    background: #2563eb;
    border-color: #2563eb;
    color: #fff;
}
</style>

<div class="ws-main" style="margin-left: var(--sidebar-w);">

    <!-- Topbar -->
    <header class="ws-topbar">
        <div class="page-title">
            🛒 Point of Sale
            <small>WebSari Sari-Sari Store</small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <input type="text" id="searchProd" class="form-control form-control-sm"
                   style="width:200px;" placeholder="🔍 Search or scan barcode...">
            <a href="<?= base_url('sales') ?>" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Back
            </a>
        </div>
    </header>

    <!-- Flash -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger m-3 mb-0">
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <!-- POS Layout -->
    <div class="pos-wrap">

        <!-- LEFT: Products -->
        <div class="pos-left">
            <!-- Category Pills -->
            <div class="d-flex gap-2 flex-wrap mb-3">
                <div class="cat-pill active" data-cat="all"
                     onclick="filterCat('all', this)">All</div>
                <?php
                    $cats = array_unique(array_column($products, 'category_name'));
                    foreach ($cats as $cat):
                        if (!$cat) continue;
                ?>
                    <div class="cat-pill" data-cat="<?= esc($cat) ?>"
                         onclick="filterCat('<?= esc($cat) ?>', this)">
                        <?= esc($cat) ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Product Grid -->
            <div class="row g-2" id="prodGrid">
                <?php foreach ($products as $p): ?>
                    <div class="col-6 col-sm-4 col-md-3 prod-item"
                         data-cat="<?= esc($p['category_name'] ?? '') ?>"
                         data-name="<?= strtolower(esc($p['name'])) ?>"
                         data-barcode="<?= strtolower(esc($p['barcode'] ?? '')) ?>">
                        <div class="prod-card <?= $p['stock'] <= 0 ? 'disabled' : '' ?>"
                             onclick="addToCart(<?= htmlspecialchars(json_encode($p)) ?>)">
                            <?php if ($p['image']): ?>
                                <img src="<?= base_url('uploads/products/'.$p['image']) ?>"
                                     class="prod-thumb" alt="">
                            <?php else: ?>
                                <div class="prod-thumb-placeholder">🛒</div>
                            <?php endif; ?>
                            <div class="fw-bold" style="font-size:.78rem;line-height:1.3;">
                                <?= esc($p['name']) ?>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-1">
                                <span class="fw-bold text-primary" style="font-size:.82rem;">
                                    ₱<?= number_format($p['price'], 2) ?>
                                </span>
                                <?php if ($p['stock'] <= 0): ?>
                                    <span class="badge bg-danger" style="font-size:.6rem;">
                                        Out
                                    </span>
                                <?php elseif ($p['stock'] <= $p['low_stock_alert']): ?>
                                    <span class="badge bg-warning text-dark"
                                          style="font-size:.6rem;">
                                        <?= $p['stock'] ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-success" style="font-size:.6rem;">
                                        <?= $p['stock'] ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- RIGHT: Cart -->
        <div class="pos-right">

            <!-- Cart Header -->
            <div class="cart-head">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold mb-0">
                            <i class="bi bi-cart3 me-1"></i>Order Cart
                        </h6>
                        <small style="opacity:.5;font-size:.7rem;">
                            Customer:
                            <input type="text" id="custName"
                                   placeholder="Walk-in Customer"
                                   style="background:transparent;border:none;
                                          color:rgba(255,255,255,.8);font-size:.7rem;
                                          outline:none;width:130px;font-family:'Nunito',sans-serif;">
                        </small>
                    </div>
                    <button class="btn btn-sm"
                            style="background:rgba(255,255,255,.15);color:#fff;
                                   border-radius:8px;padding:4px 10px;"
                            onclick="clearCart()">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>

            <!-- Cart Items -->
            <div class="cart-body" id="cartBody">
                <div id="emptyMsg" class="text-center py-5 text-muted">
                    <div style="font-size:3rem;opacity:.3;">🛒</div>
                    <p style="font-size:.82rem;margin-top:8px;">Cart is empty</p>
                    <p style="font-size:.72rem;">Tap a product to add it</p>
                </div>
            </div>

            <!-- Cart Footer -->
            <div class="cart-foot">
                <!-- Totals -->
                <div class="mb-2">
                    <div class="d-flex justify-content-between mb-1"
                         style="font-size:.8rem;">
                        <span class="text-muted">Subtotal:</span>
                        <span id="subtotalLbl">₱0.00</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-1"
                         style="font-size:.8rem;">
                        <span class="text-muted">Discount (₱):</span>
                        <input type="number" id="discountIn"
                               class="form-control form-control-sm text-end"
                               style="width:90px;" min="0" step="1"
                               placeholder="0" oninput="updateTotals()">
                    </div>
                    <div class="d-flex justify-content-between"
                         style="font-size:.95rem;border-top:1.5px dashed #e2e8f0;
                                padding-top:8px;margin-top:4px;">
                        <span class="fw-bold">TOTAL:</span>
                        <span id="totalLbl" class="fw-bold text-primary"
                              style="font-size:1.15rem;">₱0.00</span>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="mb-2">
                    <div style="font-size:.72rem;font-weight:700;color:#64748b;
                                margin-bottom:5px;">Payment Method:</div>
                    <div class="d-flex gap-1">
                        <div class="pay-btn selected" data-pay="cash"
                             onclick="selectPay('cash')">
                            💵 Cash
                        </div>
                        <div class="pay-btn" data-pay="gcash"
                             onclick="selectPay('gcash')">
                            📱 GCash
                        </div>
                        <div class="pay-btn" data-pay="utang"
                             onclick="selectPay('utang')">
                            📝 Utang
                        </div>
                    </div>
                </div>

                <!-- Amount Paid (Cash only) -->
                <div id="cashSection" class="mb-2">
                    <label style="font-size:.72rem;font-weight:700;color:#64748b;">
                        Amount Paid (₱):
                    </label>
                    <input type="number" id="amtPaid"
                           class="form-control form-control-sm" min="0" step="1"
                           placeholder="Enter amount" oninput="calcChange()">
                    <div class="d-flex justify-content-between mt-1"
                         style="font-size:.82rem;">
                        <span class="text-muted">Change:</span>
                        <span id="changeLbl" class="fw-bold text-success">₱0.00</span>
                    </div>
                </div>

                <!-- Notes -->
                <div class="mb-2">
                    <input type="text" id="notesIn" class="form-control form-control-sm"
                           placeholder="Notes (optional)...">
                </div>

                <!-- Checkout Button -->
                <button id="checkoutBtn" class="btn btn-primary w-100 fw-bold"
                        onclick="processCheckout()" disabled>
                    <i class="bi bi-check2-circle me-2"></i>Complete Sale
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Hidden Form -->
<form id="saleForm" action="<?= base_url('sales/store') ?>"
      method="POST" style="display:none;">
    <?= csrf_field() ?>
    <input type="hidden" name="items"          id="fItems">
    <input type="hidden" name="customer_name"  id="fCust">
    <input type="hidden" name="discount"       id="fDiscount">
    <input type="hidden" name="amount_paid"    id="fPaid">
    <input type="hidden" name="payment_method" id="fPay" value="cash">
    <input type="hidden" name="notes"          id="fNotes">
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let cart       = [];
    let payMethod  = 'cash';

    // ── Add to Cart ───────────────────────────
    function addToCart(p) {
        const existing = cart.find(i => i.product_id === p.id);

        if (existing) {
            if (existing.qty >= p.stock) {
                alert('⚠️ Max stock reached for ' + p.name); return;
            }
            existing.qty++;
            existing.subtotal = existing.qty * existing.price;
        } else {
            cart.push({
                product_id : p.id,
                name       : p.name,
                price      : parseFloat(p.price),
                qty        : 1,
                subtotal   : parseFloat(p.price),
                max        : p.stock,
            });
        }
        renderCart();
    }

    // ── Render Cart ───────────────────────────
    function renderCart() {
        const body   = document.getElementById('cartBody');
        const empty  = document.getElementById('emptyMsg');

        if (cart.length === 0) {
            body.innerHTML = '';
            body.appendChild(empty);
            empty.style.display = 'block';
            document.getElementById('checkoutBtn').disabled = true;
            updateTotals();
            return;
        }

        empty.style.display = 'none';
        let html = '';

        cart.forEach((item, idx) => {
            html += `
            <div class="cart-item">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <span class="fw-bold"
                          style="font-size:.8rem;flex:1;line-height:1.3;">
                        ${esc(item.name)}
                    </span>
                    <button onclick="removeItem(${idx})"
                            style="background:none;border:none;color:#ef4444;
                                   font-size:.9rem;padding:0;cursor:pointer;
                                   margin-left:6px;">
                        <i class="bi bi-x-circle-fill"></i>
                    </button>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span style="color:#2563eb;font-size:.8rem;font-weight:700;">
                        ₱${item.price.toFixed(2)}
                    </span>
                    <div class="d-flex align-items-center gap-1">
                        <div class="qty-btn" onclick="changeQty(${idx},-1)">−</div>
                        <span style="font-size:.875rem;font-weight:700;
                                     min-width:26px;text-align:center;">
                            ${item.qty}
                        </span>
                        <div class="qty-btn" onclick="changeQty(${idx},1)">+</div>
                    </div>
                    <span style="font-size:.82rem;font-weight:700;">
                        ₱${item.subtotal.toFixed(2)}
                    </span>
                </div>
            </div>`;
        });

        body.innerHTML = html;
        document.getElementById('checkoutBtn').disabled = false;
        updateTotals();
    }

    function changeQty(idx, delta) {
        cart[idx].qty += delta;
        if (cart[idx].qty <= 0) {
            cart.splice(idx, 1);
        } else if (cart[idx].qty > cart[idx].max) {
            cart[idx].qty = cart[idx].max;
        } else {
            cart[idx].subtotal = cart[idx].qty * cart[idx].price;
        }
        renderCart();
    }

    function removeItem(idx) {
        cart.splice(idx, 1);
        renderCart();
    }

    function clearCart() {
        if (cart.length > 0 && confirm('Clear all items?')) {
            cart = [];
            renderCart();
        }
    }

    // ── Totals ────────────────────────────────
    function updateTotals() {
        const sub  = cart.reduce((s, i) => s + i.subtotal, 0);
        const disc = parseFloat(document.getElementById('discountIn').value) || 0;
        const tot  = Math.max(0, sub - disc);

        document.getElementById('subtotalLbl').textContent = '₱' + sub.toFixed(2);
        document.getElementById('totalLbl').textContent    = '₱' + tot.toFixed(2);
        calcChange();
    }

    function calcChange() {
        const tot    = parseFloat(
                           document.getElementById('totalLbl')
                                   .textContent.replace('₱','')) || 0;
        const paid   = parseFloat(
                           document.getElementById('amtPaid').value) || 0;
        const change = paid - tot;

        document.getElementById('changeLbl').textContent =
            '₱' + Math.max(0, change).toFixed(2);
        document.getElementById('changeLbl').style.color =
            change >= 0 ? '#16a34a' : '#dc2626';
    }

    // ── Payment Method ────────────────────────
    function selectPay(method) {
        payMethod = method;
        document.querySelectorAll('.pay-btn')
                .forEach(b => b.classList.remove('selected'));
        document.querySelector(`[data-pay="${method}"]`)
                .classList.add('selected');

        const cashSection = document.getElementById('cashSection');
        cashSection.style.display = method === 'utang' ? 'none' : 'block';
    }

    // ── Checkout ──────────────────────────────
    function processCheckout() {
        if (cart.length === 0) return;

        const total = parseFloat(
            document.getElementById('totalLbl').textContent.replace('₱','')) || 0;
        const paid  = parseFloat(
            document.getElementById('amtPaid').value) || 0;

        if (payMethod === 'cash' && paid < total) {
            alert('⚠️ Amount paid is less than total!');
            document.getElementById('amtPaid').focus();
            return;
        }

        const disc  = parseFloat(
            document.getElementById('discountIn').value) || 0;

        document.getElementById('fItems').value    = JSON.stringify(cart);
        document.getElementById('fCust').value     = document.getElementById('custName').value || 'Walk-in';
        document.getElementById('fDiscount').value = disc;
        document.getElementById('fPaid').value     = payMethod === 'utang' ? 0 : paid;
        document.getElementById('fPay').value      = payMethod;
        document.getElementById('fNotes').value    = document.getElementById('notesIn').value;

        document.getElementById('saleForm').submit();
    }

    // ── Search ────────────────────────────────
    document.getElementById('searchProd').addEventListener('input', function() {
        const q = this.value.toLowerCase();
        document.querySelectorAll('.prod-item').forEach(el => {
            const match = el.dataset.name.includes(q) ||
                          el.dataset.barcode.includes(q);
            el.style.display = match ? '' : 'none';
        });
    });

    // ── Category Filter ───────────────────────
    function filterCat(cat, el) {
        document.querySelectorAll('.cat-pill').forEach(p => p.classList.remove('active'));
        el.classList.add('active');
        document.querySelectorAll('.prod-item').forEach(item => {
            item.style.display =
                (cat === 'all' || item.dataset.cat === cat) ? '' : 'none';
        });
    }

    // XSS escape
    function esc(str) {
        return String(str)
            .replace(/&/g,'&amp;').replace(/</g,'&lt;')
            .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }
</script>
</body>
</html>