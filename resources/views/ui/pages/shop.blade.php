@extends('ui.layouts.frontend2')
@section('title', '| Shop')
@section('content')

   
<div class="ltn__utilize-overlay"></div>

    <!-- BREADCRUMB AREA START -->
    <div class="ltn__breadcrumb-area text-left bg-overlay-white-30 bg-image "  data-bg="{{ asset('sk-assets/assets/images/frontend/bg/Inner_Small_Banner_3.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__breadcrumb-inner">
                        <h1 class="page-title">Shop Grid</h1>
                        <div class="ltn__breadcrumb-list">
                            <ul>
                                <li><a href="index.html"><span class="ltn__secondary-color"><i class="fas fa-home"></i></span> Home</a></li>
                                <li>Shop Grid</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- BREADCRUMB AREA END -->
    
    <!-- PRODUCT DETAILS AREA START -->
    <div class="ltn__product-area ltn__product-gutter mb-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__shop-options">
                        <ul>
                            <li>
                                <div class="ltn__grid-list-tab-menu ">
                                    <div class="nav">
                                        <a class="active show" data-toggle="tab" href="#liton_product_grid"><i class="fas fa-th-large"></i></a>
                                        <a data-toggle="tab" href="#liton_product_list"><i class="fas fa-list"></i></a>
                                    </div>
                                </div>
                            </li>

                        </ul>
                    </div>
                    @isset($data['product'])
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="liton_product_grid">
                            <div class="ltn__product-tab-content-inner ltn__product-grid-view">
                                <div class="row product_list" >
                                    @foreach($data['product'] as $product)
                                    <!-- ltn__product-item -->
                                    <div class="col-xl-3 col-lg-4 col-sm-6 col-6">
                                        <div class="ltn__product-item ltn__product-item-3 text-center">
                                            <div class="product-img">
                                                <a href="#"><img src="{{ asset('storage/uploads/product-images/'.$product->image) }}" alt="#"></a>
                                                <div class="product-badge">
                                                    <ul>
                                                        <li class="sale-badge">New</li>
                                                    </ul>
                                                </div>
                                                <div class="product-hover-action">
                                                    <ul>
                                                        <li>
                                                            <a href="#" title="Quick View" class="btn-quick" data="{{$product->id}}" data-toggle="modal" data-target="#quick_view_modal">
                                                                <i class="far fa-eye"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('cart.store') }}" method="POST" id="add_cart" enctype="multipart/form-data">
                                                                @csrf
                                                                <input type="hidden" value="{{ $product->id }}" name="id">
                                                                <input type="hidden" value="{{ $product->p_name }}" name="name">
                                                                <input type="hidden" value="{{$product->sell_price - (($product->sell_price * $product->disc_amount)/100) }}" name="price">
                                                                <input type="hidden" value="{{ $product->image }}"  name="image">
                                                                <input type="hidden" value="1" name="quantity">
                                                            </form>
                                                            <a href="javascript:{}" onclick="document.getElementById('add_cart').submit();" title="Add to Cart" data-toggle="modal" data-target="#add_to_cart_modal">
                                                                <i class="fas fa-shopping-cart"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product-info">
                                                <div class="product-ratting">
                                                    <ul>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star-half-alt"></i></a></li>
                                                        <li><a href="#"><i class="far fa-star"></i></a></li>
                                                    </ul>
                                                </div>
                                                <h2 class="product-title"><a href="#">{{$product->p_name}}</a></h2>
                                                <div class="product-price">
                                                    <span>{{$product->sell_price - (($product->sell_price * $product->disc_amount)/100) }}</span>
                                                    <del>{{$product->sell_price}}</del>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="liton_product_list">
                            <div class="ltn__product-tab-content-inner ltn__product-list-view">
                                <div class="row product_list">
                                    @foreach($data['product'] as $product)
                                    <!-- ltn__product-item -->
                                    <div class="col-lg-12">
                                        <div class="ltn__product-item ltn__product-item-3">
                                            <div class="product-img">
                                                <a href="product-details.html"><img src="{{ asset('storage/uploads/product-images/'.$product->image) }}" alt="#"></a>
                                            </div>
                                            <div class="product-info">
                                                <h2 class="product-title"><a href="product-details.html">{{$product->p_name}}</a></h2>
                                                <div class="product-ratting">
                                                    <ul>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star"></i></a></li>
                                                        <li><a href="#"><i class="fas fa-star-half-alt"></i></a></li>
                                                        <li><a href="#"><i class="far fa-star"></i></a></li>
                                                    </ul>
                                                </div>
                                                <div class="product-price">
                                                    <span>{{$product->sell_price - (($product->sell_price * $product->disc_amount)/100) }}</span>
                                                    <del>{{$product->sell_price}}</del>
                                                </div>
{{--                                                <div class="product-brief">--}}
{{--                                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Recusandae asperiores sit odit nesciunt,  aliquid, deleniti non et ut dolorem!</p>--}}
{{--                                                </div>--}}
                                                <div class="product-hover-action">
                                                    <ul>
                                                        <li>
                                                            <a href="#" title="Quick View" class="btn-quick" data="{{$product->id}}" data-toggle="modal" data-target="#quick_view_modal">
                                                                <i class="far fa-eye"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('cart.store') }}" method="POST" id="add_cart_form" enctype="multipart/form-data">
                                                                @csrf
                                                                <input type="hidden" value="{{$product->id}}" name="id">
                                                                <input type="hidden" value="{{ $product->p_name }}" name="name">
                                                                <input type="hidden" value="{{$product->sell_price - (($product->sell_price * $product->disc_amount)/100) }}" name="price">
                                                                <input type="hidden" value="{{ $product->image }}"  name="image">
                                                                <input type="hidden" value="1" name="quantity">
                                                            </form>
                                                            <a href="javascript:{}" onclick="document.getElementById('add_cart_form').submit();" title="Add to Cart" data-toggle="modal" type="submit" form="add_cart_form" data-target="#add_to_cart_modal">
                                                                <i class="fas fa-shopping-cart"></i>
                                                            </a>
                                                        </li>

                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @endisset
{{--                    <div class="ltn__pagination-area text-center">--}}
{{--                        <div class="ltn__pagination">--}}
{{--                            {!! $data['product']->links()  !!}--}}
{{--                            <ul>--}}
{{--                                <li><a href="#"><i class="fas fa-angle-double-left"></i></a></li>--}}
{{--                                <li><a href="#">1</a></li>--}}
{{--                                <li class="active"><a href="#">2</a></li>--}}
{{--                                <li><a href="#">3</a></li>--}}
{{--                                <li><a href="#">...</a></li>--}}
{{--                                <li><a href="#">10</a></li>--}}
{{--                                <li><a href="#"><i class="fas fa-angle-double-right"></i></a></li>--}}
{{--                            </ul>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </div>
            </div>
        </div>
    </div>
    <!-- PRODUCT DETAILS AREA END -->


    <!-- MODAL AREA START (Quick View Modal) -->
    <div class="ltn__modal-area ltn__quick-view-modal-area">
        <div class="modal fade" id="quick_view_modal" tabindex="-1">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            <!-- <i class="fas fa-times"></i> -->
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('cart.store') }}" method="POST" id="cart_form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" value="" name="id">
                            <input type="hidden" value="" name="name">
                            <input type="hidden" value="" name="price">
                            <input type="hidden" value=""  name="image">
                         <div class="ltn__quick-view-modal-inner">
                             <div class="modal-product-item">
                                <div class="row">
                                    <div class="col-lg-6 col-12">
                                        <div class="modal-product-img">
                                            <img id="product-image" src="{{ asset('sk-assets/assets/images/frontend/product/4.png') }}" alt="#">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <div class="modal-product-info">
                                            <h3 id="product_name">Brake Conversion Kit</h3>
                                            <div class="product-price">
                                                <span id="product_price">Dh. 149.00</span>
                                                <del id="product_price_discount">Dh. 165.00</del>
                                            </div>

                                            <div class="ltn__product-details-menu-2">
                                                <ul>
                                                    <li>
                                                        <div class="cart-plus-minus">
                                                            <input type="text" value="01" name="quantity" class="cart-plus-minus-box">
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:{}" onclick="document.getElementById('cart_form').submit();" class="theme-btn-1 btn btn-effect-1" title="Add to Cart">
                                                            <i class="fas fa-shopping-cart"></i>
                                                            <span>ADD TO CART</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                             </div>
                         </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL AREA END -->

    <!-- MODAL AREA START (Add To Cart Modal) -->
    <div class="ltn__modal-area ltn__add-to-cart-modal-area">
        <div class="modal fade" id="add_to_cart_modal" tabindex="-1">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                         <div class="ltn__quick-view-modal-inner">
                             <div class="modal-product-item">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="modal-product-img">
                                            <img src="{{ asset('sk-assets/assets/images/frontend/product/1.png') }}" alt="#">
                                        </div>
                                         <div class="modal-product-info">
                                            <h5><a href="#">Brake Conversion Kit</a></h5>
                                            <p class="added-cart"><i class="fa fa-check-circle"></i>  Successfully added to your Cart</p>
                                            <div class="btn-wrapper">
                                                <a href="cart.html" class="theme-btn-1 btn btn-effect-1">View Cart</a>
                                                <a href="checkout.html" class="theme-btn-2 btn btn-effect-2">Checkout</a>
                                            </div>
                                         </div>
                                         <!-- additional-info -->
                                         <div class="additional-info d-none">
                                            <p>We want to give you <b>10% discount</b> for your first order, <br>  Use discount code at checkout</p>
                                            <div class="payment-method">
                                                <img src="{{ asset('sk-assets/assets/images/frontend/icons/payment.png') }}" alt="#">
                                            </div>
                                         </div>
                                    </div>
                                </div>
                             </div>
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL AREA END -->

    <!-- MODAL AREA START (Wishlist Modal) -->
    <div class="ltn__modal-area ltn__add-to-cart-modal-area">
        <div class="modal fade" id="liton_wishlist_modal" tabindex="-1">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                         <div class="ltn__quick-view-modal-inner">
                             <div class="modal-product-item">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="modal-product-img">
                                            <img src="{{ asset('sk-assets/assets/images/frontend/product/7.png') }}" alt="#">
                                        </div>
                                         <div class="modal-product-info">
                                            <h5><a href="product-details.html">Brake Conversion Kit</a></h5>
                                            <p class="added-cart"><i class="fa fa-check-circle"></i>  Successfully added to your Wishlist</p>
                                            <div class="btn-wrapper">
                                                <a href="wishlist.html" class="theme-btn-1 btn btn-effect-1">View Wishlist</a>
                                            </div>
                                         </div>
                                         <!-- additional-info -->
                                         <div class="additional-info d-none">
                                            <p>We want to give you <b>10% discount</b> for your first order, <br>  Use discount code at checkout</p>
                                            <div class="payment-method">
                                                <img src="{{ asset('sk-assets/assets/images/frontend/icons/payment.png') }}" alt="#">
                                            </div>
                                         </div>
                                    </div>
                                </div>
                             </div>
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL AREA END -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    $(document).ready(function() {



        $('.product_list').on('click', '.btn-quick', function() {
            var id = $(this).attr('data');
            $.ajax({
                url: '{{ url('product-detail') }}',
                type: 'get',
                async: false,
                dataType: 'json',
                data: { id: id },
                success: function(res) {
                    console.log(res);
                    $('#product_name').text(res.p_name);
                    $('#product_price').text(res.sell_price - ((res.sell_price * res.disc_amount)/100));
                    $('#product_price_discount').text(res.sell_price);
                    $("#product-image").attr("src", "{{ asset('storage/uploads/product-images/')}}/"+res.image);

                    $('input[name=id]').val(id);
                    $('input[name=name]').val(res.p_name);
                    $('input[name=price]').val(res.sell_price - ((res.sell_price * res.disc_amount)/100));
                    $('input[name=image]').val(res.image);


                },
                error: function() {
                    toastr.error('any technical error');
                }
            });
        });


        $('#LeadForm').on('submit', function(e) {
            e.preventDefault();
            var formData=$('#LeadForm').serialize()
            $.ajax({
                type: "get",
                url: '{{ url('/save-lead') }}',
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('.btn-submit').text('Saving...');
                    $(".btn-submit").prop("disabled", true);
                },
                success: function(data) {
                    if (data.success) {
                        $('#LeadForm')[0].reset();
                        toastr.success(data.success);
                        {{--window.location.href = "{{ url('booking')}}";--}}
                    }
                    if (data.errors) {
                        toastr.error(data.errors);
                        $('.btn-submit').text('Save');
                        $(".btn-submit").prop("disabled", false);
                    }
                },
                complete: function(data) {
                    $(".btn-submit").html("Save");
                    $(".btn-submit").prop("disabled", false);
                },
                error: function() {
                    toastr.error('any technical error');
                    $('.btn-submit').text('Save');
                    $(".btn-submit").prop("disabled", false);
                }
            });
        });
    });
</script>
@endsection
