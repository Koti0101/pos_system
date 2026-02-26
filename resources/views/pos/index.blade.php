@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h5>Products</h5>
                </div>
                <div class="card-body">
                    
                    {{-- ========== ADD BARCODE SCANNER HERE ========== --}}
                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white">
                                <i class="bi bi-upc-scan"></i> 🔍
                            </span>
                            <input type="text" 
                                   id="barcodeInput" 
                                   class="form-control form-control-lg" 
                                   placeholder="Scan barcode or press Enter..."
                                   autofocus>
                            <span class="input-group-text" id="barcodeStatus"></span>
                        </div>
                        <small class="text-muted">
                            <i class="bi bi-info-circle"></i> Focus this field and scan a product barcode
                        </small>
                    </div>
                    {{-- ========== END BARCODE SCANNER ========== --}}

                    <div class="mb-3">
                        <input type="text" id="productSearch" class="form-control" placeholder="Or search products by name...">
                    </div>

                    <div class="row" id="productsGrid">
                        @foreach($products as $product)
                        <div class="col-md-4 col-sm-6 mb-3 product-item">
                            <div class="card h-100 product-card" 
                                 data-id="{{ $product->id }}"
                                 data-name="{{ $product->name }}"
                                 data-price="{{ $product->price }}"
                                 data-stock="{{ $product->quantity }}">
                                <div class="card-body">
                                    <h6 class="card-title">{{ $product->name }}</h6>
                                    <p class="text-muted mb-1">{{ $product->category->name }}</p>
                                    <p class="text-success fw-bold mb-1">FCFA {{ number_format($product->price) }}</p>
                                    <small class="text-muted">Stock: {{ $product->quantity }}</small>
                                    
                                    {{-- Show barcode if exists --}}
                                    @if(isset($product->barcode) && $product->barcode)
                                    <div class="mt-1">
                                        <span class="badge bg-secondary" style="font-family:monospace;font-size:10px;">
                                            {{ $product->barcode }}
                                        </span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header bg-primary text-white">
                    <h5>Current Sale</h5>
                </div>
                <div class="card-body">
                    <form id="checkoutForm" method="POST" action="{{ route('pos.checkout') }}">
                        @csrf
                        
                        <div id="cartItems" style="max-height: 300px; overflow-y: auto;">
                            <p class="text-muted text-center">Cart is empty</p>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <strong>Total:</strong>
                                <h5 id="total" class="text-primary">FCFA 0</h5>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Amount Paid</label>
                            <input type="number" name="amount_paid" id="amountPaid" class="form-control" step="0.01" min="0" required>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <strong>Change:</strong>
                                <span id="change" class="text-success">FCFA 0</span>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg" id="checkoutBtn" disabled>
                                Complete Sale
                            </button>
                            <button type="button" class="btn btn-outline-danger" id="clearCart">
                                Clear Cart
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.product-card {
    cursor: pointer;
    transition: all 0.3s;
}
.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
.cart-item {
    border-bottom: 1px solid #eee;
    padding: 10px 0;
}

/* Barcode input styling */
#barcodeInput:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

#barcodeStatus {
    min-width: 150px;
    font-size: 12px;
    font-weight: 600;
}

/* Scanner animation */
@keyframes scan {
    0%, 100% { border-color: #0d6efd; }
    50% { border-color: #0dcaf0; }
}

#barcodeInput.scanning {
    animation: scan 1s ease-in-out;
}
</style>

<script>
let cart = [];

// ========== BARCODE SCANNER FUNCTIONALITY ========== 
const barcodeInput = document.getElementById('barcodeInput');
const barcodeStatus = document.getElementById('barcodeStatus');

barcodeInput.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        const barcode = this.value.trim();
        
        if (barcode === '') return;
        
        // Add scanning animation
        this.classList.add('scanning');
        
        // Show searching status
        barcodeStatus.textContent = '🔍 Searching...';
        barcodeStatus.style.color = '#0d6efd';
        
        // Search for product by barcode
        fetch('{{ route('pos.barcode') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ barcode: barcode })
        })
        .then(response => response.json())
        .then(data => {
            barcodeInput.classList.remove('scanning');
            
            if (data.success) {
                // Product found - add to cart
                const product = data.product;
                addToCart(product.id, product.name, parseFloat(product.price), parseInt(product.quantity));
                
                // Show success status
                barcodeStatus.textContent = '✓ ' + product.name;
                barcodeStatus.style.color = '#198754';
                
                // Play success beep
                playBeep();
            } else {
                // Product not found
                barcodeStatus.textContent = '✗ ' + data.message;
                barcodeStatus.style.color = '#dc3545';
                
                // Play error sound
                playError();
            }
            
            // Clear input for next scan
            barcodeInput.value = '';
            
            // Clear status after 2 seconds
            setTimeout(() => {
                barcodeStatus.textContent = '';
            }, 2000);
        })
        .catch(error => {
            barcodeInput.classList.remove('scanning');
            barcodeStatus.textContent = '✗ Connection error';
            barcodeStatus.style.color = '#dc3545';
            barcodeInput.value = '';
            console.error('Barcode scan error:', error);
        });
    }
});

// Keep barcode input focused (so scanner always works)
document.addEventListener('click', function(e) {
    // Don't steal focus if user is typing in search or amount paid
    if (e.target.id !== 'productSearch' && 
        e.target.id !== 'amountPaid' && 
        e.target.tagName !== 'INPUT' && 
        e.target.tagName !== 'BUTTON') {
        barcodeInput.focus();
    }
});

// Sound effects
function playBeep() {
    const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZiTYIGGS57OibUhENTKXh8bllHAU2jdXyzn0vBSl+zPDdlUQLF2G16+mnVhELRp/g8r9vIQUsgc/y24o3CBhju+znm1IRDEuk4PG6aB4FNIzU8tGAMQYnfM3w45dGCxdhtuvopVcRC0af4PK+cCIFK4DN8tuKOAgZY7zs6JxUEgxLpODxu2seBDOKzvHSgjMGJ3vM8OScTQwYYrbr6aZaFApFnd7xwHIkBSuBzvLcizoIGGO77OicUxIMTKPf8LxnHwU1i9Tx0YU2Bidgvczx45lLD');
    audio.volume = 0.3;
    audio.play().catch(() => {});
}

function playError() {
    const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZiTYIGGS57OibUhENTKXh8bllHAU2jdXyzn0vBSl+zPDdlUQLF2G16+mnVhELRp/g8r9vIQUsgc/y24o3CBhju+znm1IRDEuk4PG6aB4FNIzU8tGAMQYnfM3w45dGCxdhtuvopVcRC0af4PK+cCIFK4DN8tuKOAgZY7zs6JxUEgxLpODxu2seBDOKzvHSgjMGJ3vM8OScTQwYYrbr6aZaFApFnd7xwHIkBSuBzvLcizoIGGO77OicUxIMTKPf8LxnHwU1i9Tx0YU2Bidgvczx45lLD');
    audio.volume = 0.5;
    audio.play().catch(() => {});
}

// ========== EXISTING CART FUNCTIONS (Keep as is) ========== 

document.querySelectorAll('.product-card').forEach(card => {
    card.addEventListener('click', function() {
        addToCart(this.dataset.id, this.dataset.name, parseFloat(this.dataset.price), parseInt(this.dataset.stock));
    });
});

function addToCart(id, name, price, stock) {
    const existingItem = cart.find(item => item.product_id == id);

    if (existingItem) {
        if (existingItem.quantity < stock) {
            existingItem.quantity++;
        } else {
            alert('Insufficient stock!');
            return;
        }
    } else {
        cart.push({
            product_id: id,
            name: name,
            price: price,
            quantity: 1,
            stock: stock
        });
    }

    updateCart();
}

function removeFromCart(id) {
    cart = cart.filter(item => item.product_id != id);
    updateCart();
}

function updateQuantity(id, quantity) {
    const item = cart.find(item => item.product_id == id);
    if (item) {
        if (quantity > 0 && quantity <= item.stock) {
            item.quantity = quantity;
        } else if (quantity > item.stock) {
            alert('Insufficient stock!');
            return;
        }
        updateCart();
    }
}

function updateCart() {
    const cartContainer = document.getElementById('cartItems');
    
    if (cart.length === 0) {
        cartContainer.innerHTML = '<p class="text-muted text-center">Cart is empty</p>';
        document.getElementById('checkoutBtn').disabled = true;
    } else {
        let html = '';
        cart.forEach(item => {
            const subtotal = item.price * item.quantity;
            html += `
                <div class="cart-item">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong>${item.name}</strong>
                        <button type="button" class="btn btn-sm btn-danger" onclick="removeFromCart(${item.product_id})">×</button>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="input-group" style="width: 100px;">
                            <button type="button" class="btn btn-sm btn-outline-secondary" 
                                    onclick="updateQuantity(${item.product_id}, ${item.quantity - 1})">-</button>
                            <input type="number" class="form-control form-control-sm text-center" 
                                   value="${item.quantity}" min="1" max="${item.stock}"
                                   onchange="updateQuantity(${item.product_id}, parseInt(this.value))">
                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                    onclick="updateQuantity(${item.product_id}, ${item.quantity + 1})">+</button>
                        </div>
                        <span>FCFA ${subtotal.toLocaleString()}</span>
                    </div>
                </div>
            `;
        });
        cartContainer.innerHTML = html;
        document.getElementById('checkoutBtn').disabled = false;
    }

    calculateTotal();
}

function calculateTotal() {
    const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

    document.getElementById('total').textContent = 'FCFA ' + total.toLocaleString();

    updateCartInput();
    calculateChange();
}

function updateCartInput() {
    document.querySelectorAll('input[name^="cart"]').forEach(el => el.remove());
    
    const form = document.getElementById('checkoutForm');
    cart.forEach((item, index) => {
        ['product_id', 'quantity', 'price'].forEach(field => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `cart[${index}][${field}]`;
            input.value = item[field];
            form.appendChild(input);
        });
    });
}

function calculateChange() {
    const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const amountPaid = parseFloat(document.getElementById('amountPaid').value) || 0;
    const change = amountPaid - total;
    
    document.getElementById('change').textContent = 'FCFA ' + (change >= 0 ? change.toLocaleString() : '0');
}

document.getElementById('clearCart').addEventListener('click', function() {
    if (confirm('Are you sure?')) {
        cart = [];
        updateCart();
    }
});

document.getElementById('amountPaid').addEventListener('input', calculateChange);

document.getElementById('productSearch').addEventListener('input', function() {
    const search = this.value.toLowerCase();
    document.querySelectorAll('.product-item').forEach(item => {
        const name = item.querySelector('.product-card').dataset.name.toLowerCase();
        item.style.display = name.includes(search) ? '' : 'none';
    });
});
</script>
@endsection