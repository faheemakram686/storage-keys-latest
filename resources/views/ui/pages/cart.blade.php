@extends('ui.layouts.frontend2')
@section('title', '| Cart')
@section('content')

   
<div class="ltn__utilize-overlay"></div>

    <!-- BREADCRUMB AREA START -->
    <div class="ltn__breadcrumb-area text-left bg-overlay-white-30 bg-image "  data-bg="{{ asset('sk-assets/assets/images/frontend/bg/Inner_Small_Banner_3.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__breadcrumb-inner">
                        <h1 class="page-title">Cart</h1>
                        <div class="ltn__breadcrumb-list">
                            <ul>
                                <li><a href="index.html"><span class="ltn__secondary-color"><i class="fas fa-home"></i></span> Home</a></li>
                                <li>Cart</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- BREADCRUMB AREA END -->

    <!-- SHOPING CART AREA START -->
    <div class="liton__shoping-cart-area mb-120">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="shoping-cart-inner">
                        <div class="shoping-cart-table table-responsive">
                            <table class="table">
{{--                                <thead>--}}
{{--                                    <th class="cart-product-remove">Remove</th>--}}
{{--                                    <th class="cart-product-image">Image</th>--}}
{{--                                    <th class="cart-product-info">Product</th>--}}
{{--                                    <th class="cart-product-price">Price</th>--}}
{{--                                    <th class="cart-product-quantity">Quantity</th>--}}
{{--                                    <th class="cart-product-subtotal">Subtotal</th>--}}
{{--                                </thead>--}}
                                <tbody>
                                @foreach ($cartItems as $item)
                                <tr>
                                    <td class="cart-product-remove">
                                        <form action="{{ route('cart.remove') }}" method="POST">
                                            @csrf
                                            <input type="hidden" value="{{ $item->id }}" name="id">
                                            <button class="px-4 py-2 text-white bg-red">x</button>
                                        </form></td>
                                    <td class="cart-product-image">
                                        <a href="product-details.html"><img src="{{ asset('storage/uploads/product-images/'.$item->attributes->image) }}" alt="#"></a>
                                    </td>
                                    <td class="cart-product-info">
                                        <h4><a href="#">{{ $item->name }}</a></h4>
                                    </td>
                                    <td class="cart-product-price"> {{ $item->price }}</td>
                                    <td class="cart-product-quantity">
                                        <form action="{{ route('cart.update') }}" id="cart_update" method="get">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $item->id}}" >
                                        <div class="cart-plus-minus">
                                            <input type="number" value="{{ $item->quantity }}" name="quantity" min="1" class="cart-plus-minus-box">

                                        </div>
                                        </form>
                                    </td>
                                    <td class="cart-product-subtotal">{{ $item->price * $item->quantity }}</td>
                                </tr>
                                @endforeach
                                <tr class="cart-coupon-row">
                                    <td colspan="6">
                                        <div class="cart-coupon">
                                            <form action="{{ route('apply.coupon') }}" method="post">
                                            @csrf
                                            <input type="text" name="cart-coupon" placeholder="Coupon code">
                                            <button type="submit" class="btn theme-btn-2 btn-effect-2">Apply Coupon</button>
                                            </form>
                                        </div>
                                    </td>
                                    <td>
                                        <button href="javascript:{}" onclick="document.getElementById('cart_update').submit();" class="btn theme-btn-2 btn-effect-2-- disabled">Update Cart</button>
                                    </td>

                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="shoping-cart-total mt-50">
                            <h4>Cart Totals</h4>
                            <table class="table">
                                <tbody>
                                <tr>
                                    <td>Cart Subtotal</td>
                                    <td>{{Cart::getTotal()}}</td>
                                </tr>
{{--                                <tr>--}}
{{--                                    <td>Shipping and Handing</td>--}}
{{--                                    <td>$15.00</td>--}}
{{--                                </tr>--}}
{{--                                <tr>--}}
{{--                                    <td>Vat</td>--}}
{{--                                    <td>$00.00</td>--}}
{{--                                </tr>--}}
{{--                                <tr>--}}
{{--                                    <td><strong>Order Total</strong></td>--}}
{{--                                    <td><strong>$633.00</strong></td>--}}
{{--                                </tr>--}}
                                </tbody>
                            </table>
                            <div class="btn-wrapper text-right">
                                <a href="{{url(Route('checkout'))}}" class="theme-btn-1 btn btn-effect-1">Proceed to checkout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- SHOPING CART AREA END -->
@endsection