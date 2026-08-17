@extends('ui.layouts.frontend')
@section('title', '| Cart')
@section('metaTitle', 'Shopping Cart | Storage Keys')
@section('metaDescription', 'Review packing supplies in your StorageKeys cart, update quantities and proceed to checkout.')

@section('content')
@php
    $cartCount = $cartItems->count();
    $cartTotal = \Cart::getTotal();
@endphp
<div class="sk-home">

    <section class="ps-hero">
        <div class="sk-container">
            <div class="ps-crumb"><a href="{{ url('/') }}">Home</a> <i class="fas fa-chevron-right"></i> <a href="{{ url('/shop') }}">Shop</a> <i class="fas fa-chevron-right"></i> <span>Cart</span></div>
            <span class="sk-eyebrow" style="color:#ffcf9e;">Your Cart</span>
            <h1>Review your <span>packing supplies</span></h1>
            <p class="lead">Check quantities, apply a coupon if you have one, then continue to checkout or keep shopping for boxes, tape and wrap.</p>
            <div class="ps-hero-cta">
                <a href="{{ url('/shop') }}" class="sk-btn sk-btn-primary"><i class="fas fa-box-open"></i> Continue Shopping</a>
                @if($cartCount)
                    <a href="{{ route('checkout') }}" class="sk-btn sk-btn-ghost"><i class="fas fa-lock"></i> Checkout</a>
                @endif
            </div>
            <div class="ps-hero-badges">
                <span class="ps-hbadge"><i class="fas fa-shopping-cart"></i> {{ $cartCount }} {{ $cartCount === 1 ? 'item' : 'items' }}</span>
                <span class="ps-hbadge"><i class="fas fa-tag"></i> Coupon support</span>
                <span class="ps-hbadge"><i class="fas fa-box"></i> Packing supplies</span>
            </div>
        </div>
    </section>

    <div class="ps-trust ct-trust">
        <div class="sk-container">
            <div class="ps-trust-in">
                <div class="ps-trust-i"><i class="fas fa-shopping-cart"></i> Review items</div>
                <div class="ps-trust-i"><i class="fas fa-sync-alt"></i> Update quantities</div>
                <div class="ps-trust-i"><i class="fas fa-ticket-alt"></i> Apply a coupon</div>
                <div class="ps-trust-i"><i class="fas fa-credit-card"></i> Proceed to checkout</div>
            </div>
        </div>
    </div>

    <section class="sk-section" id="ca-cart">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Shopping Cart</span>
                <h2>Your Selected Products</h2>
                <p>Update quantities and continue to checkout when you are ready.</p>
            </div>

            @if(session('success'))
                <div class="sh-alert">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="sh-alert ca-alert-error">{{ session('error') }}</div>
            @endif

            @if($cartCount)
                <div class="ca-layout sk-reveal">
                    <div class="ca-items">
                        <div class="ca-table-wrap shoping-cart-table table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($cartItems as $item)
                                    @php
                                        $img = !empty($item->attributes->image)
                                            ? asset('storage/uploads/product-images/'.$item->attributes->image)
                                            : asset('sk-assets/assets/images/frontend/product/1.png');
                                        $lineTotal = $item->price * $item->quantity;
                                    @endphp
                                    <tr>
                                        <td class="cart-product-remove">
                                            <form action="{{ route('cart.remove') }}" method="POST">
                                                @csrf
                                                <input type="hidden" value="{{ $item->id }}" name="id">
                                                <button type="submit" class="ca-remove" title="Remove item" aria-label="Remove {{ $item->name }}">&times;</button>
                                            </form>
                                        </td>
                                        <td class="cart-product-info">
                                            <div class="ca-product">
                                                <a href="{{ url('/shop') }}" class="ca-thumb" style="background-image:url('{{ $img }}');"></a>
                                                <h4><a href="{{ url('/shop') }}">{{ $item->name }}</a></h4>
                                            </div>
                                        </td>
                                        <td class="cart-product-price">AED {{ number_format((float) $item->price, 2) }}</td>
                                        <td class="cart-product-quantity">
                                            <form action="{{ route('cart.update') }}" id="cart_update_{{ $item->id }}" class="ca-qty-form" method="get">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $item->id }}">
                                                <div class="cart-plus-minus">
                                                    <input type="number" step="any" value="{{ $item->quantity }}" name="quantity" min="1" class="cart-plus-minus-box">
                                                </div>
                                            </form>
                                        </td>
                                        <td class="cart-product-subtotal">AED {{ number_format((float) $lineTotal, 2) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="ca-toolbar">
                            <form action="{{ route('apply.coupon') }}" method="post" class="ca-coupon cart-coupon">
                                @csrf
                                <input type="text" name="coupon_code" placeholder="Coupon code">
                                <button type="submit" class="sk-btn sk-btn-ghost">Apply Coupon</button>
                            </form>
                            <button type="button" id="ca-update-all" class="sk-btn sk-btn-primary">Update Cart</button>
                        </div>
                    </div>

                    <aside class="ca-summary shoping-cart-total">
                        <h4>Cart Totals</h4>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>Items</td>
                                    <td>{{ $cartCount }}</td>
                                </tr>
                                <tr>
                                    <td>Cart Subtotal</td>
                                    <td>AED {{ number_format((float) $cartTotal, 2) }}</td>
                                </tr>
                                <tr class="ca-total-row">
                                    <td><strong>Order Total</strong></td>
                                    <td><strong>AED {{ number_format((float) $cartTotal, 2) }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="btn-wrapper">
                            <a href="{{ route('checkout') }}" class="sk-btn sk-btn-primary ca-checkout">Proceed to checkout</a>
                            <a href="{{ url('/shop') }}" class="sk-btn sk-btn-ghost">Continue shopping</a>
                        </div>
                        <form action="{{ route('cart.clear') }}" method="POST" class="ca-clear">
                            @csrf
                            <button type="submit">Clear cart</button>
                        </form>
                    </aside>
                </div>
            @else
                <div class="bl-empty">
                    <div class="ic"><i class="fas fa-shopping-cart"></i></div>
                    <h3>Your cart is empty</h3>
                    <p>Add packing supplies from the shop, then return here to update quantities and checkout.</p>
                    <a href="{{ url('/shop') }}" class="sk-btn sk-btn-primary"><i class="fas fa-box-open"></i> Browse Shop</a>
                </div>
            @endif
        </div>
    </section>

    <div class="ps-mobilebar">
        <a href="tel:+971565018785"><i class="fas fa-phone-alt"></i> Call</a>
        <a href="https://wa.me/971565018785" class="wa"><i class="fab fa-whatsapp"></i> WhatsApp</a>
        <a href="{{ url('/shop') }}"><i class="fas fa-box-open"></i> Shop</a>
    </div>
</div>
@endsection

@section('javascriptWork')
<script>
    (function () {
        var btn = document.getElementById('ca-update-all');
        if (!btn) return;
        btn.addEventListener('click', function () {
            var forms = Array.prototype.slice.call(document.querySelectorAll('.ca-qty-form'));
            if (!forms.length) return;
            btn.disabled = true;
            var run = Promise.resolve();
            forms.forEach(function (form) {
                run = run.then(function () {
                    var params = new URLSearchParams(new FormData(form));
                    return fetch(form.action + '?' + params.toString(), { credentials: 'same-origin' });
                });
            });
            run.then(function () {
                window.location.href = {{ json_encode(route('cart.list')) }};
            }).catch(function () {
                btn.disabled = false;
            });
        });
    })();
</script>
@endsection
