<?php // app/Views/sales/create.php ?>
<?= view('layout/header') ?>
<?= view('layout/sidebar') ?>

<style>
.pos-wrap {
    display: flex;
    height: calc(100vh - 64px);
    overflow: hidden;
}
.pos-left {
    flex: 1;
    overflow-y: auto;
    padding: 16px;
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
.prod-card {
    background: #fff;
    border-radius: 12px;
    padding: 10px;
    cursor: pointer;
    border: 2px solid transparent;
    transition: all .2s;
    height: 100%;
    box-shadow: 0 1px 4px rgba(0,0,0,.07);
    position: relative;
    user-select: none;
}
.prod-card:hover {
    border-color: #2563eb;
    box-shadow: 0 4px 16px rgba(37,99,235,.15);
}
.prod-card.out { opacity:.45; cursor:not-allowed; pointer-events:none; }
.prod-thumb-box {
    width:100%; height:75px;
    background:#f1f5f9;
    border-radius:8px;
    display:flex; align-items:center; justify-content:center;
    font-size:1.6rem; margin-bottom:7px; overflow:hidden;
}
.prod-thumb-box img { width:100%; height:100%; object-fit:cover; }
.cart-badge {
    position:absolute; top:7px; right:7px;
    background:#2563eb; color:#fff;
    border-radius:50%; width:20px; height:20px;
    font-size:.6rem; font-weight:800;
    display:none; align-items:center; justify-content:center;
}
.cart-head {
    padding:14px 16px;
    background:linear-gradient(135deg,#0f172a,#1e3a5f);
    color:#fff; flex-shrink:0;
}
.cart-body { flex:1; overflow-y:auto; padding:10px; }
.cart-foot { padding:12px 14px; border-top:1px solid #e2e8f0; flex-shrink:0; }
.cart-item {
    background:#f8fafc; border-radius:10px;
    padding:9px 11px; margin-bottom:7px;
    border:1px solid #e2e8f0;
}
.qty-wrap {
    display:flex; align-items:center; gap:3px;
    background:#fff; border:1.5px solid #e2e8f0;
    border-radius:9px; padding:2px 4px;
    position: relative;
    z-index: 10;
}
.qbtn {
    width:26px; height:26px; border:none;
    border-radius:6px; background:#f1f5f9;
    font-size:.9rem; font-weight:800;
    cursor:pointer; display:flex;
    align-items:center; justify-content:center;
    color:#475569; transition:all .15s; flex-shrink:0;
    position: relative;
    z-index: 11;
}
.qbtn:hover { background:#2563eb; color:#fff; }
.qbtn.m:hover { background:#dc2626; color:#fff; }
.qty-num {
    width:36px; text-align:center; border:none;
    background:transparent; font-size:.88rem;
    font-weight:800; color:#1e293b; outline:none;
    font-family:'Nunito',sans-serif;
}
.qty-num::-webkit-outer-spin-button,
.qty-num::-webkit-inner-spin-button { -webkit-appearance:none; }
.qty-num[type=number] { -moz-appearance:textfield; }
.cat-pill {
    border:1.5px solid #e2e8f0; background:#fff;
    border-radius:20px; padding:4px 14px;
    font-size:.77rem; font-weight:700; cursor:pointer;
    white-space:nowrap; color:#475569; transition:all .2s;
}
.cat-pill.on { background:#2563eb; border-color:#2563eb; color:#fff; }
.pay-opt {
    flex:1; border:1.5px solid #e2e8f0; background:#f8fafc;
    border-radius:9px; padding:7px 4px; text-align:center;
    cursor:pointer; font-size:.77rem; font-weight:700;
    color:#475569; transition:all .2s;
}
.pay-opt.on { border-color:#2563eb; background:#eff6ff; color:#1d4ed8; }
</style>

<div class="ws-main" style="margin-left:var(--sidebar-w)">

    <header class="ws-topbar">
        <div class="page-title">
            🛒 Point of Sale
            <small>WebSari</small>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <input type="text" id="srch"
                   class="form-control form-control-sm"
                   style="width:200px"
                   placeholder="Search product...">
            <a href="<?= base_url('sales') ?>"
               class="btn btn-light btn-sm">
                ← Back
            </a>
        </div>
    </header>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger m-3 py-2 mb-0">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <div class="pos-wrap">

        <!-- Products -->
        <div class="pos-left">
            <!-- Category pills -->
            <div class="d-flex gap-2 flex-wrap mb-3" id="cats">
                <div class="cat-pill on" onclick="catFilter('all',this)">All</div>
                <?php
                $seen = [];
                foreach ($products as $pr) {
                    $c = $pr['category_name'] ?? '';
                    if ($c && !in_array($c, $seen)) {
                        $seen[] = $c;
                        echo '<div class="cat-pill" onclick="catFilter(\''
                             . esc($c) . '\',this)">' . esc($c) . '</div>';
                    }
                }
                ?>
            </div>

            <!-- Grid -->
            <div class="row g-2" id="grid">
                <?php foreach ($products as $pr): ?>
                <div class="col-6 col-md-4 col-lg-3 pi"
                     data-cat="<?= esc($pr['category_name'] ?? '') ?>"
                     data-name="<?= strtolower(esc($pr['name'])) ?>"
                     data-bc="<?= strtolower(esc($pr['barcode'] ?? '')) ?>">
                    <div class="prod-card <?= $pr['stock'] <= 0 ? 'out' : '' ?>"
                         onclick="tapProduct(<?= (int)$pr['id'] ?>,
                                  <?= htmlspecialchars(json_encode($pr['name'])) ?>,
                                  <?= (float)$pr['price'] ?>,
                                  <?= (int)$pr['stock'] ?>,
                                  <?= htmlspecialchars(json_encode($pr['unit'] ?? 'pcs')) ?>)">
                        <div class="cart-badge" id="cb-<?= $pr['id'] ?>"></div>
                        <div class="prod-thumb-box">
                            <?php if ($pr['image']): ?>
                                <img src="<?= base_url('uploads/products/'.$pr['image']) ?>"
                                     alt="">
                            <?php else: ?>
                                🛒
                            <?php endif; ?>
                        </div>
                        <div class="fw-bold text-truncate"
                             style="font-size:.77rem">
                            <?= esc($pr['name']) ?>
                        </div>
                        <div class="d-flex justify-content-between
                                    align-items-center mt-1">
                            <span class="fw-bold text-primary"
                                  style="font-size:.8rem">
                                ₱<?= number_format($pr['price'],2) ?>
                            </span>
                            <span class="badge <?= $pr['stock'] <= 0
                                ? 'bg-danger'
                                : ($pr['stock'] <= $pr['low_stock_alert']
                                    ? 'bg-warning text-dark'
                                    : 'bg-success') ?>"
                                  style="font-size:.6rem">
                                <?= $pr['stock'] ?>
                            </span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Cart -->
        <div class="pos-right">
            <div class="cart-head">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="fw-bold" style="font-size:.95rem">
                            🛒 Order Cart
                        </span>
                        <span id="cnt"
                              class="badge ms-1"
                              style="background:rgba(255,255,255,.18);
                                     font-size:.65rem">
                            0
                        </span>
                    </div>
                    <button onclick="clearCart()"
                            style="background:rgba(220,38,38,.3);border:none;
                                   color:#fff;border-radius:7px;
                                   padding:3px 9px;cursor:pointer;
                                   font-size:.8rem">
                        🗑
                    </button>
                </div>
                <div style="margin-top:6px;font-size:.72rem;
                            color:rgba(255,255,255,.45)">
                    Customer:
                    <input id="cust" type="text"
                           placeholder="Walk-in Customer"
                           style="background:transparent;border:none;
                                  border-bottom:1px solid rgba(255,255,255,.2);
                                  color:rgba(255,255,255,.8);
                                  font-size:.72rem;outline:none;
                                  width:150px;padding:1px 3px;
                                  font-family:Nunito,sans-serif">
                </div>
            </div>

            <div class="cart-body" id="cartBody">
                <div id="cartItems"></div>
                <div id="empty" class="text-center py-5 text-muted">
                    <div style="font-size:2.8rem;opacity:.2">🛒</div>
                    <p style="font-size:.8rem;margin-top:8px">
                        Cart is empty<br>
                        <small>Tap a product to add</small>
                    </p>
                </div>
            </div>

            <div class="cart-foot">
                <!-- Total -->
                <div style="background:#f8fafc;border-radius:10px;
                            padding:10px 12px;border:1px solid #e2e8f0;
                            margin-bottom:10px">
                    <div class="d-flex justify-content-between"
                         style="font-size:.8rem">
                        <span class="text-muted">Subtotal:</span>
                        <span id="sub" class="fw-bold">₱0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mt-1"
                         style="font-size:1rem;border-top:1px dashed #e2e8f0;
                                padding-top:7px;margin-top:6px">
                        <span class="fw-bold">TOTAL:</span>
                        <span id="tot" class="fw-bold text-primary"
                              style="font-size:1.15rem">
                            ₱0.00
                        </span>
                    </div>
                </div>

                <!-- Payment -->
                <div style="font-size:.7rem;font-weight:700;color:#64748b;
                            margin-bottom:5px;text-transform:uppercase">
                    Payment:
                </div>
                <div class="d-flex gap-1 mb-2" id="payBtns">
                    <div class="pay-opt on" data-p="cash"
                         onclick="setPay('cash')">💵 Cash</div>
                    <div class="pay-opt" data-p="gcash"
                         onclick="setPay('gcash')">📱 GCash</div>
                    <div class="pay-opt" data-p="utang"
                         onclick="setPay('utang')">📝 Utang</div>
                </div>

                <!-- Amount paid -->
                <div id="cashDiv">
                    <div style="font-size:.7rem;font-weight:700;
                                color:#64748b;margin-bottom:3px;
                                text-transform:uppercase">
                        Amount Paid (₱):
                    </div>
                    <input type="number" id="paid"
                           class="form-control mb-1"
                           style="border-radius:9px"
                           placeholder="0"
                           min="0" step="1"
                           oninput="calcChange()">
                    <div class="d-flex justify-content-between"
                         style="font-size:.85rem;background:#f0fdf4;
                                border-radius:8px;padding:6px 10px">
                        <span class="fw-bold text-muted">Change:</span>
                        <span id="chng" class="fw-bold text-success">
                            ₱0.00
                        </span>
                    </div>
                </div>

                <!-- Complete Sale Button -->
                <button id="sellBtn"
                        class="btn btn-primary w-100 fw-bold mt-2"
                        style="border-radius:10px;padding:12px;font-size:.95rem"
                        onclick="doSale()"
                        disabled>
                    ✅ Complete Sale
                </button>
            </div>
        </div>
    </div>
</div>

<!-- THE FORM -->
<form id="saleForm"
      action="<?= base_url('sales/store') ?>"
      method="POST"
      style="display:none">
    <?= csrf_field() ?>
    <input type="hidden" id="f_items"  name="items">
    <input type="hidden" id="f_cust"   name="customer_name"  value="Walk-in Customer">
    <input type="hidden" id="f_disc"   name="discount"       value="0">
    <input type="hidden" id="f_paid"   name="amount_paid"    value="0">
    <input type="hidden" id="f_pay"    name="payment_method" value="cash">
    <input type="hidden" id="f_notes"  name="notes"          value="">
</form>

<!-- Qty Modal -->
<div class="modal fade" id="qm" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content"
             style="border-radius:16px;border:none;
                    box-shadow:0 20px 50px rgba(0,0,0,.2)">
            <div class="modal-body p-4 text-center">
                <h6 class="fw-bold mb-0" id="qmTitle"></h6>
                <p class="text-muted mb-3" id="qmStock"
                   style="font-size:.75rem"></p>

                <div class="d-flex align-items-center
                            justify-content-center gap-3 mb-3">
                    <button class="btn btn-danger"
                            style="width:44px;height:44px;
                                   border-radius:11px;font-size:1.4rem;
                                   font-weight:800;padding:0"
                            onclick="mAdj(-1)">−</button>

                    <input type="number" id="qmNum"
                           class="form-control text-center fw-bold"
                           style="width:80px;font-size:1.4rem;
                                  border-radius:10px;border:2px solid #2563eb"
                           min="1" value="1"
                           oninput="mSync()">

                    <button class="btn btn-success"
                            style="width:44px;height:44px;
                                   border-radius:11px;font-size:1.4rem;
                                   font-weight:800;padding:0"
                            onclick="mAdj(1)">+</button>
                </div>

                <div style="background:#eff6ff;border-radius:10px;
                            padding:10px;margin-bottom:14px">
                    <div style="font-size:.7rem;color:#64748b;font-weight:700">
                        SUBTOTAL
                    </div>
                    <div id="qmSub" class="fw-bold text-primary"
                         style="font-size:1.4rem">
                        ₱0.00
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-light flex-fill"
                            data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary flex-fill fw-bold"
                            onclick="mConfirm()">
                        Add to Cart
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
'use strict';

// ── State ─────────────────────────────────────────────
var CART    = [];
var PAY     = 'cash';
var MP      = null;   // modal product
var modal   = null;

window.addEventListener('load', function() {
    modal = new bootstrap.Modal(document.getElementById('qm'));
});

// ── Tap product → add to cart directly (1 unit) ──────
function tapProduct(id, name, price, stock, unit) {
    if (stock <= 0) return;

    var inCart = 0;
    var cartIdx = -1;
    for (var i = 0; i < CART.length; i++) {
        if (CART[i].pid === id) { 
            inCart = CART[i].qty; 
            cartIdx = i;
            break; 
        }
    }

    if (inCart + 1 > stock) {
        alert('Max stock reached for ' + name);
        return;
    }

    if (cartIdx !== -1) {
        CART[cartIdx].qty += 1;
        CART[cartIdx].subtotal = CART[cartIdx].qty * CART[cartIdx].price;
    } else {
        CART.push({
            pid      : id,
            name     : name,
            price    : price,
            qty      : 1,
            subtotal : price,
            maxQty   : stock,
            unit     : unit
        });
    }

    renderCart();
    setBadge(id);
}

// ── Modal helpers ─────────────────────────────────────
function mAdj(d) {
    if (!MP) return;
    var el  = document.getElementById('qmNum');
    var val = (parseInt(el.value) || 1) + d;
    if (val < 1)       val = 1;
    if (val > MP.maxAdd) val = MP.maxAdd;
    el.value = String(val);
    mSync();
}

function mSync() {
    if (!MP) return;
    var qty = parseInt(document.getElementById('qmNum').value) || 1;
    if (qty < 1)        qty = 1;
    if (qty > MP.maxAdd) qty = MP.maxAdd;
    document.getElementById('qmNum').value = String(qty);
    document.getElementById('qmSub').textContent =
        '₱' + (qty * MP.price).toFixed(2);
}

function mConfirm() {
    if (!MP) return;
    var qty = parseInt(document.getElementById('qmNum').value) || 1;
    if (qty < 1 || qty > MP.maxAdd) return;

    // Check existing in cart
    var found = false;
    for (var i = 0; i < CART.length; i++) {
        if (CART[i].pid === MP.id) {
            CART[i].qty     += qty;
            CART[i].subtotal = CART[i].qty * CART[i].price;
            found = true;
            break;
        }
    }
    if (!found) {
        CART.push({
            pid      : MP.id,
            name     : MP.name,
            price    : MP.price,
            qty      : qty,
            subtotal : qty * MP.price,
            maxQty   : MP.stock,
            unit     : MP.unit
        });
    }

    modal.hide();
    renderCart();
    setBadge(MP.id);
}

// ── Cart +/- ──────────────────────────────────────────
function qChange(idx, d) {
    console.log('qChange called:', {idx: idx, delta: d});
    var item = CART[idx];
    if (!item) {
        console.error('Item not found at index:', idx);
        return;
    }

    var currentQty = parseInt(item.qty) || 0;
    var newQty = currentQty + d;

    console.log('Updating qty:', {name: item.name, from: currentQty, to: newQty, max: item.maxQty});

    if (newQty <= 0) {
        if (confirm('Remove ' + item.name + '?')) {
            var pid = item.pid;
            CART.splice(idx, 1);
            setBadge(pid, true);
            renderCart();
        }
        return;
    }

    if (newQty > item.maxQty) {
        alert('Only ' + item.maxQty + ' ' + item.unit + ' available!');
        return;
    }

    item.qty = newQty;
    item.subtotal = newQty * item.price;
    setBadge(item.pid);
    renderCart();
}

function qSet(idx, val) {
    var item   = CART[idx];
    var newQty = parseInt(val) || 1;
    if (newQty < 1)          newQty = 1;
    if (newQty > item.maxQty) newQty = item.maxQty;
    item.qty      = newQty;
    item.subtotal = newQty * item.price;
    setBadge(item.pid);
    renderCart();
}

function removeItem(idx) {
    var pid = CART[idx].pid;
    CART.splice(idx, 1);
    setBadge(pid, true);
    renderCart();
}

function clearCart() {
    if (!CART.length) return;
    if (!confirm('Clear cart?')) return;
    var pids = CART.map(function(i) { return i.pid; });
    CART = [];
    pids.forEach(function(p) { setBadge(p, true); });
    renderCart();
}

// ── Render Cart ───────────────────────────────────────
function renderCart() {
    var itemsContainer = document.getElementById('cartItems');
    var emptyMsg = document.getElementById('empty');

    if (!CART.length) {
        itemsContainer.innerHTML = '';
        emptyMsg.style.display = 'block';
        document.getElementById('sellBtn').disabled = true;
        document.getElementById('cnt').textContent  = '0';
        updateTotals();
        return;
    }

    emptyMsg.style.display = 'none';

    var totalQty = CART.reduce(function(s, i) { return s + i.qty; }, 0);
    document.getElementById('cnt').textContent = totalQty;

    var html = '';
    CART.forEach(function(item, idx) {
        var name = hEsc(item.name);
        var subtotal = parseFloat(item.subtotal).toFixed(2);
        var price = parseFloat(item.price).toFixed(2);
        
        html += `
        <div class="cart-item">
            <div class="d-flex justify-content-between align-items-start mb-1">
                <div style="flex:1;min-width:0">
                    <div class="fw-bold text-truncate" style="font-size:.8rem">
                        ${name}
                    </div>
                    <div style="font-size:.7rem;color:#64748b">
                        ₱${price} / ${item.unit}
                    </div>
                </div>
                <button type="button" onclick="removeItem(${idx})"
                        style="background:none;border:none;color:#ef4444;font-size:.95rem;padding:0 0 0 5px;cursor:pointer;position:relative;z-index:20">
                    ✕
                </button>
            </div>
            <div class="d-flex align-items-center justify-content-between">
                <div class="qty-wrap">
                    <button type="button" class="qbtn m"
                            onclick="event.stopPropagation(); qChange(${idx}, -1)">
                        −
                    </button>
                    <input type="number"
                           class="qty-num"
                           value="${item.qty}"
                           min="1"
                           max="${item.maxQty}"
                           onchange="qSet(${idx}, this.value)"
                           onclick="this.select()">
                    <button type="button" class="qbtn"
                            onclick="event.stopPropagation(); qChange(${idx}, 1)">
                        +
                    </button>
                </div>
                <div class="fw-bold" style="font-size:.88rem">
                    ₱${subtotal}
                </div>
            </div>
        </div>`;
    });

    itemsContainer.innerHTML = html;
    document.getElementById('sellBtn').disabled = false;
    updateTotals();
}

// ── Totals ────────────────────────────────────────────
function updateTotals() {
    var sub = CART.reduce(function(s, i) { return s + i.subtotal; }, 0);
    document.getElementById('sub').textContent =
        '₱' + sub.toFixed(2);
    document.getElementById('tot').textContent =
        '₱' + sub.toFixed(2);
    calcChange();
}

function calcChange() {
    var tot  = parseFloat(
        document.getElementById('tot').textContent.replace('₱','')
    ) || 0;
    var paid = parseFloat(
        document.getElementById('paid').value
    ) || 0;
    var chng = paid - tot;
    var el   = document.getElementById('chng');
    el.textContent  = '₱' + Math.max(0, chng).toFixed(2);
    el.style.color  = chng >= 0 ? '#16a34a' : '#dc2626';
}

// ── Payment ───────────────────────────────────────────
function setPay(p) {
    PAY = p;
    document.querySelectorAll('.pay-opt').forEach(function(b) {
        b.classList.remove('on');
    });
    document.querySelector('[data-p="' + p + '"]').classList.add('on');
    document.getElementById('cashDiv').style.display =
        p === 'utang' ? 'none' : 'block';
}

// ── Badge on product card ─────────────────────────────
function setBadge(pid, remove) {
    var el = document.getElementById('cb-' + pid);
    if (!el) return;
    if (remove) { el.style.display = 'none'; el.textContent = ''; return; }
    var found = CART.find(function(i) { return i.pid === pid; });
    if (found) {
        el.textContent  = found.qty;
        el.style.display = 'flex';
    } else {
        el.style.display = 'none';
    }
}

// ── COMPLETE SALE ─────────────────────────────────────
function doSale() {
    if (!CART.length) {
        alert('Cart is empty!');
        return;
    }

    var tot  = parseFloat(
        document.getElementById('tot').textContent.replace('₱','')
    ) || 0;
    var paid = parseFloat(
        document.getElementById('paid').value
    ) || 0;

    if (PAY === 'cash' && paid < tot) {
        alert('Amount paid is less than total!\nTotal: ₱' +
              tot.toFixed(2) + '\nPaid: ₱' + paid.toFixed(2));
        document.getElementById('paid').focus();
        return;
    }

    // ✅ Build items array exactly as PHP expects
    var items = CART.map(function(item) {
        return {
            product_id : item.pid,
            qty        : item.qty,
            price      : item.price,
            subtotal   : item.subtotal
        };
    });

    var itemsJSON = JSON.stringify(items);

    // ✅ Fill hidden form fields
    document.getElementById('f_items').value = itemsJSON;
    document.getElementById('f_cust').value  =
        (document.getElementById('cust').value || '').trim() ||
        'Walk-in Customer';
    document.getElementById('f_disc').value  = '0';
    document.getElementById('f_paid').value  =
        PAY === 'utang' ? '0' : String(paid);
    document.getElementById('f_pay').value   = PAY;
    document.getElementById('f_notes').value = '';

    // ✅ Verify data before submitting
    console.log('=== SUBMITTING SALE ===');
    console.log('Items JSON:', itemsJSON);
    console.log('Customer:', document.getElementById('f_cust').value);
    console.log('Payment:', PAY);
    console.log('Paid:', document.getElementById('f_paid').value);
    console.log('Total:', tot);

    // ✅ Show loading state
    var btn          = document.getElementById('sellBtn');
    btn.disabled     = true;
    btn.textContent  = 'Processing...';

    // ✅ Submit
    var form = document.getElementById('saleForm');
    console.log('Form action:', form.action);
    console.log('Form method:', form.method);

    form.submit();
}

// ── Search ────────────────────────────────────────────
document.getElementById('srch').addEventListener('input', function() {
    var q = this.value.toLowerCase().trim();
    document.querySelectorAll('.pi').forEach(function(el) {
        var ok = !q ||
                 el.dataset.name.includes(q) ||
                 el.dataset.bc.includes(q);
        el.style.display = ok ? '' : 'none';
    });
});

// ── Category filter ───────────────────────────────────
function catFilter(cat, el) {
    document.querySelectorAll('.cat-pill').forEach(function(p) {
        p.classList.remove('on');
    });
    el.classList.add('on');
    document.querySelectorAll('.pi').forEach(function(item) {
        item.style.display =
            (cat === 'all' || item.dataset.cat === cat) ? '' : 'none';
    });
    document.getElementById('srch').value = '';
}

// ── Escape HTML ───────────────────────────────────────
function hEsc(s) {
    return String(s)
        .replace(/&/g,'&amp;').replace(/</g,'&lt;')
        .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
</script>

</body>
</html>