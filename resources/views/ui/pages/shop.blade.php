@extends('ui.layouts.frontend')
@section('title', '| Shop')
@section('metaTitle', 'Packing Supplies Shop | Storage Keys')
@section('metaDescription', 'Buy packing supplies and storage accessories from StorageKeys. Boxes, tape, wrap and more for your move or storage in the UAE.')

@section('content')
<div class="sk-home">

    <section class="ps-hero">
        <div class="sk-container">
            <div class="ps-crumb"><a href="{{ url('/') }}">Home</a> <i class="fas fa-chevron-right"></i> <span>Shop</span></div>
            <span class="sk-eyebrow" style="color:#ffcf9e;">Shop</span>
            <h1>Packing supplies for <span>storage &amp; moving</span></h1>
            <p class="lead">Boxes, tape, wrap and other essentials to pack safely and make the most of your storage unit in Sharjah and Dubai.</p>
            <div class="ps-hero-cta">
                <a href="#sh-products" class="sk-btn sk-btn-primary"><i class="fas fa-box-open"></i> Browse Products</a>
                <a href="{{ url('/cart') }}" class="sk-btn sk-btn-ghost"><i class="fas fa-shopping-cart"></i> View Cart</a>
            </div>
            <div class="ps-hero-badges">
                <span class="ps-hbadge"><i class="fas fa-box"></i> Boxes &amp; cartons</span>
                <span class="ps-hbadge"><i class="fas fa-tape"></i> Tape &amp; wrap</span>
                <span class="ps-hbadge"><i class="fas fa-shield-alt"></i> Packing protection</span>
            </div>
        </div>
    </section>

    <div class="ps-trust ct-trust">
        <div class="sk-container">
            <div class="ps-trust-in">
                <div class="ps-trust-i"><i class="fas fa-box-open"></i> Packing supplies</div>
                <div class="ps-trust-i"><i class="fas fa-truck"></i> Ready for your move</div>
                <div class="ps-trust-i"><i class="fas fa-shopping-cart"></i> Add to cart</div>
                <div class="ps-trust-i"><i class="fas fa-headset"></i> Team support</div>
            </div>
        </div>
    </div>

    <section class="sk-section" id="sh-products">
        <div class="sk-container">
            <div class="sk-section-head">
                <span class="sk-eyebrow" style="justify-content:center;">Our Shop</span>
                <h2>Storage &amp; Packing Products</h2>
                <p>Choose the supplies you need, add them to your cart, and we will help you get ready for storage or moving.</p>
            </div>

            @if(session('success'))
                <div class="sh-alert">{{ session('success') }} <a href="{{ url('/cart') }}">View cart</a></div>
            @endif

            @isset($data['product'])
                @if($data['product']->count())
                    <div class="sh-grid product_list sk-reveal">
                        @foreach($data['product'] as $product)
                            @php
                                $img = $product->image
                                    ? asset('storage/uploads/product-images/'.$product->image)
                                    : asset('sk-assets/assets/images/frontend/product/1.png');
                                $sale = $product->sell_price - (($product->sell_price * $product->disc_amount) / 100);
                                $hasDisc = (float) $product->disc_amount > 0;
                            @endphp
                            <article class="sh-card">
                                <div class="sh-card-img" style="background-image:url('{{ $img }}');">
                                    @if($hasDisc)
                                        <span class="sh-badge">Sale</span>
                                    @endif
                                    <button type="button" class="sh-quick btn-quick" data="{{ $product->id }}" data-toggle="modal" data-target="#quick_view_modal" title="Quick view">
                                        <i class="far fa-eye"></i>
                                    </button>
                                </div>
                                <div class="sh-card-body">
                                    <h3>{{ $product->p_name }}</h3>
                                    <div class="sh-price">
                                        <span>AED {{ number_format($sale, 2) }}</span>
                                        @if($hasDisc)
                                            <del>AED {{ number_format($product->sell_price, 2) }}</del>
                                        @endif
                                    </div>
                                    <form action="{{ route('cart.store') }}" method="POST" id="add_cart_{{ $product->id }}">
                                        @csrf
                                        <input type="hidden" value="{{ $product->id }}" name="id">
                                        <input type="hidden" value="{{ $product->p_name }}" name="name">
                                        <input type="hidden" value="{{ $sale }}" name="price">
                                        <input type="hidden" value="{{ $product->image }}" name="image">
                                        <input type="hidden" value="1" name="quantity">
                                    </form>
                                    <button type="submit" form="add_cart_{{ $product->id }}" class="sk-btn sk-btn-primary sh-add">
                                        <i class="fas fa-shopping-cart"></i> Add to Cart
                                    </button>
                                </div>
                            </article>
                        @endforeach
                    </div>
                    @if($data['product']->hasPages())
                        <div class="bl-pagination">
                            {{ $data['product']->links('pagination::bootstrap-4') }}
                        </div>
                    @endif
                @else
                    <div class="bl-empty">
                        <div class="ic"><i class="fas fa-box-open"></i></div>
                        <h3>No products available</h3>
                        <p>Packing supplies will appear here when they are added. You can still contact us for storage and moving help.</p>
                        <a href="{{ url('/contact-us') }}" class="sk-btn sk-btn-primary"><i class="fas fa-envelope"></i> Contact Us</a>
                    </div>
                @endif
            @endisset
        </div>
    </section>

    <div class="ps-mobilebar">
        <a href="tel:+971565018785"><i class="fas fa-phone"></i> Call</a>
        <a href="https://wa.me/971565018785" class="wa"><i class="fab fa-whatsapp"></i> WhatsApp</a>
        <a href="{{ url('/cart') }}"><i class="fas fa-shopping-cart"></i> Cart</a>
    </div>
</div>

<div class="modal fade sh-modal" id="quick_view_modal" tabindex="-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <button type="button" class="sh-modal-close" data-dismiss="modal" aria-label="Close">&times;</button>
            <form action="{{ route('cart.store') }}" method="POST" id="cart_form">
                @csrf
                <input type="hidden" value="" name="id">
                <input type="hidden" value="" name="name">
                <input type="hidden" value="" name="price">
                <input type="hidden" value="" name="image">
                <div class="sh-modal-grid">
                    <div class="sh-modal-img">
                        <img id="product-image" src="{{ asset('sk-assets/assets/images/frontend/product/4.png') }}" alt="">
                    </div>
                    <div class="sh-modal-info">
                        <h3 id="product_name">Product</h3>
                        <div class="sh-price">
                            <span id="product_price">AED 0.00</span>
                            <del id="product_price_discount"></del>
                        </div>
                        <div class="sh-qty">
                            <label>Quantity</label>
                            <input type="number" min="1" value="1" name="quantity" class="cart-plus-minus-box">
                        </div>
                        <button type="submit" class="sk-btn sk-btn-primary">
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('javascriptWork')
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    $(document).ready(function () {
        $('.product_list').on('click', '.btn-quick', function () {
            var id = $(this).attr('data');
            $.ajax({
                url: '{{ url('product-detail') }}',
                type: 'get',
                async: false,
                dataType: 'json',
                data: { id: id },
                success: function (res) {
                    var sale = res.sell_price - ((res.sell_price * res.disc_amount) / 100);
                    $('#product_name').text(res.p_name);
                    $('#product_price').text('AED ' + Number(sale).toFixed(2));
                    if (Number(res.disc_amount) > 0) {
                        $('#product_price_discount').text('AED ' + Number(res.sell_price).toFixed(2)).show();
                    } else {
                        $('#product_price_discount').hide();
                    }
                    $('#product-image').attr('src', '{{ asset('storage/uploads/product-images') }}/' + res.image);
                    $('#cart_form input[name=id]').val(id);
                    $('#cart_form input[name=name]').val(res.p_name);
                    $('#cart_form input[name=price]').val(sale);
                    $('#cart_form input[name=image]').val(res.image);
                    $('#cart_form input[name=quantity]').val(1);
                },
                error: function () {
                    toastr.error('any technical error');
                }
            });
        });
    });
</script>
@endsection
