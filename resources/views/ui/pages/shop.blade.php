@extends('ui.layouts.frontend')
@section('title', '| Shop')
@section('metaTitle', 'Packing Supplies Shop | Storage Keys')
@section('metaDescription', 'Buy packing supplies and storage accessories from StorageKeys. Boxes, tape, wrap and more for your move or storage in the UAE.')

@section('content')
<div class="sk-home sh-page">

    <section class="ps-hero">
        <div class="sk-container">
            <h1>Packing supplies</h1>
            <p class="lead">In addition to our provided supplies with our unit, extra boxes and tape are available for purchase here.</p>
        </div>
    </section>

    <section class="sh-products" id="sh-products">
        <div class="sk-container">
            <div class="sh-wrap">
            @if(session('success'))
                <div class="sh-alert">{{ session('success') }} <a href="{{ url('/cart') }}">View cart</a></div>
            @endif

            @isset($data['product'])
                @if($data['product']->count())
                    <div class="sh-grid product_list sk-reveal">
                        @foreach($data['product']->getCollection()->sortBy(fn ($product) => stripos((string) $product->p_name, 'box') === false)->values() as $product)
                            @php
                                $img = $product->image
                                    ? asset('storage/uploads/product-images/'.$product->image)
                                    : asset('sk-assets/assets/images/frontend/product/1.png');
                                $sale = $product->sell_price - (($product->sell_price * $product->disc_amount) / 100);
                                $hasDisc = (float) $product->disc_amount > 0;
                                $isBox = stripos((string) $product->p_name, 'box') !== false;
                                $cartId = $isBox ? $product->id.'-s' : $product->id;
                                $cartName = $isBox ? $product->p_name.' (S - 45 cm)' : $product->p_name;
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
                                    <div class="sh-card-row">
                                        <h3>{{ $product->p_name }}</h3>
                                        <div class="sh-price">
                                            <span>AED {{ number_format($sale, 2) }}</span>
                                            @if($hasDisc)
                                                <del>AED {{ number_format($product->sell_price, 2) }}</del>
                                            @endif
                                        </div>
                                    </div>
                                    @if($isBox)
                                        <div class="sh-sizes" role="group" aria-label="Box size">
                                            <button type="button" class="sh-size is-on" data-size="s" data-label="S - 45 cm" aria-pressed="true">S - 45 cm</button>
                                            <button type="button" class="sh-size" data-size="m" data-label="M - 60 cm" aria-pressed="false">M - 60 cm</button>
                                            <button type="button" class="sh-size" data-size="l" data-label="L - 75 cm" aria-pressed="false">L - 75 cm</button>
                                        </div>
                                    @endif
                                    <form action="{{ route('cart.store') }}" method="POST" id="add_cart_{{ $product->id }}">
                                        @csrf
                                        <input type="hidden" value="{{ $cartId }}" name="id" data-base="{{ $product->id }}">
                                        <input type="hidden" value="{{ $cartName }}" name="name" data-base="{{ $product->p_name }}">
                                        <input type="hidden" value="{{ $sale }}" name="price">
                                        <input type="hidden" value="{{ $product->image }}" name="image">
                                        <input type="hidden" value="1" name="quantity">
                                    </form>
                                    <button type="submit" form="add_cart_{{ $product->id }}" class="sk-btn sk-btn-primary sh-add">
                                        Add to Cart
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

            <section class="sh-guide" aria-labelledby="sh-box-sizes">
                <h2 id="sh-box-sizes">Our box sizes</h2>
                <p>We provide a range of boxes for your things. Select your items from below and we'll recommend a box size for you.</p>
                <div class="sh-guide-grid" id="sh-guide-grid">
                    @foreach([
                        ['Clothing', 'Seasonal clothing, shoes, bags', 'fa-tshirt', 'm'],
                        ['Sports Gear', 'Roller skates, hockey sticks, balls', 'fa-bicycle', 'l'],
                        ['Appliances', 'Toaster, kitchen tools', 'fa-couch', 'm'],
                        ['Hobby Items', 'Figurines, collectibles, dolls', 'fa-chess-queen', 's'],
                        ['Documents', 'Papers, records, binders', 'fa-folder-open', 's'],
                        ['Electronics', 'Monitors, PC, speakers', 'fa-desktop', 'l'],
                    ] as [$name, $hint, $icon, $size])
                        <button type="button" class="sh-guide-item" aria-pressed="false" data-size="{{ $size }}" data-name="{{ $name }}">
                            <span class="ic"><i class="fas {{ $icon }}" aria-hidden="true"></i></span>
                            <span><strong>{{ $name }}</strong><small>{{ $hint }}</small></span>
                        </button>
                    @endforeach
                </div>
                <p class="sh-guide-result" id="sh-guide-result" aria-live="polite"></p>
                <div class="sh-guide-actions">
                    <button type="button" class="sk-btn sk-btn-primary sh-help" id="sh-help">Help me choose</button>
                </div>
            </section>
            </div>
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
        $('.product_list').on('click', '.sh-size', function () {
            var btn = $(this);
            var card = btn.closest('.sh-card');
            card.find('.sh-size').removeClass('is-on').attr('aria-pressed', 'false');
            btn.addClass('is-on').attr('aria-pressed', 'true');
            var form = card.find('form');
            form.find('[name=id]').val(form.find('[name=id]').data('base') + '-' + btn.data('size'));
            form.find('[name=name]').val(form.find('[name=name]').data('base') + ' (' + btn.data('label') + ')');
        });

        // "Our box sizes": tap items, then recommend the largest box any of them needs
        var boxLabels = { s: 'S - 45 cm', m: 'M - 60 cm', l: 'L - 75 cm' };
        $('#sh-guide-grid').on('click', '.sh-guide-item', function () {
            var on = $(this).attr('aria-pressed') !== 'true';
            $(this).attr('aria-pressed', on ? 'true' : 'false').toggleClass('is-hot', on);
            $('#sh-guide-result').text('');
        });
        $('#sh-help').on('click', function () {
            var picked = $('#sh-guide-grid .sh-guide-item[aria-pressed="true"]');
            var $result = $('#sh-guide-result');
            if (!picked.length) {
                $result.text('Select the items you plan to pack and we will recommend a box size.');
                return;
            }
            var order = ['s', 'm', 'l'];
            var best = 's';
            var names = [];
            picked.each(function () {
                var size = $(this).data('size');
                if (order.indexOf(size) > order.indexOf(best)) best = size;
                names.push($(this).data('name').toLowerCase());
            });
            var $sizeBtn = $('.product_list .sh-size[data-size="' + best + '"]').first();
            $result.html('We recommend the <strong>' + boxLabels[best] + '</strong> packing box for ' + names.join(', ') + '.' +
                ($sizeBtn.length ? ' <a href="#" id="sh-go-box">Choose this box</a>' : ''));
            if ($sizeBtn.length) $sizeBtn.trigger('click');
        });
        $('#sh-guide-result').on('click', '#sh-go-box', function (e) {
            e.preventDefault();
            var card = $('.product_list .sh-size.is-on').first().closest('.sh-card')[0];
            if (card) card.scrollIntoView({ behavior: 'smooth', block: 'center' });
        });

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
