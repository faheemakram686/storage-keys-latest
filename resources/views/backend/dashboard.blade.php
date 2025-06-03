@extends('backend.layouts.app')

@section('title', '| Dashboard')

@section('content')
<div class="nk-content-body">
    <div class="nk-block-head nk-block-head-sm">
        <div class="nk-block-between">
            <div class="nk-block-head-content">
                <h3 class="nk-block-title page-title">Dashboard</h3>
            </div>
        </div><!-- .nk-block-between -->
    </div><!-- .nk-block-head -->
    @isset($data)
    <div class="nk-block" id="home_data">
        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-3 mb-3">
                        <div class="card">
                            <div class="card-body p-0">
                                <div class="media p-3">
                                    <a href="javascript:void(0)" class="media-body" style="display: contents;">
                                        <div class="media-body">
                                            <h6 class="title">Total Leads</h6>
                                            <h3 class="mb-0 mt-2">{{($data['leads'])? :"0"}}</h3>
                                        </div>
                                        <div class="align-self-center text-center analytics-icon"
                                            style="font-size: 70px;">
                                            <em class="icon ni ni-calendar-check text-info"></em>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 mb-3">
                        <div class="card">
                            <div class="card-body p-0">
                                <div class="media p-3">
                                    <a href="javascript:void(0)" class="media-body" style="display: contents;">
                                        <div class="media-body">
                                            <h6 class="title">Total Estimates</h6>
                                            <h3 class="mb-0 mt-2">{{($data['estimates'])? :"0"}}</h3>
                                        </div>
                                        <div class="align-self-center text-center analytics-icon"
                                            style="font-size: 70px;">
                                            <em class="icon ni ni-calendar-booking text-info"></em>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 mb-3">
                        <div class="card">
                            <div class="card-body p-0">
                                <div class="media p-3">
                                    <a href="javascript:void(0)" class="media-body" style="display: contents;">
                                        <div class="media-body">
                                            <h6 class="title">Total Contracts</h6>
                                            <h3 class="mb-0 mt-2">{{($data['contracts'])? :"0"}}</h3>
                                        </div>
                                        <div class="align-self-center text-center analytics-icon"
                                            style="font-size: 70px;">
                                            <em class="icon ni ni-cc-off text-info"></em>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 mb-3">
                        <div class="card">
                            <div class="card-body p-0">
                                <div class="media p-3">
                                    <a href="javascript:void(0)" class="media-body" style="display: contents;">
                                        <div class="media-body">
                                            <h6 class="title">Total Customers</h6>
                                            <h3 class="mb-0 mt-2">{{($data['customers'])? :"0"}}</h3>
                                        </div>
                                        <div class="align-self-center text-center analytics-icon"
                                            style="font-size: 70px;">
                                            <em class="icon ni ni-users-fill text-info"></em>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-gs">
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-inner">
                        <div class="card-title-group mb-2">
                            <div class="card-title">
                                <h6 class="title">Statistics</h6>
                            </div>
                        </div>
                        <ul class="nk-store-statistics">
                            <li class="item">
                                <a href="javascript:void(0)">
                                    <div class="info">
                                        <div class="title">Total Storage Units</div>
                                        <div class="count">{{($data['storageunits'])? :"0"}}</div>
                                    </div>
                                </a>
                                <a href="javascript:void(0)"><em class="icon bg-primary-dim ni ni-grid-alt-fill"></em></a>
                            </li>
                            <li class="item">
                                <a href="javascript:void(0)">
                                    <div class="info">
                                        <div class="title">Total Products</div>
                                        <div class="count">{{($data['products'])? :"0"}}</div>
                                    </div>
                                </a>
                                <a href="javascript:void(0)"><em class="icon bg-info-dim ni ni-users"></em></a>
                            </li>
                            <li class="item">
                                <a href="javascript:void(0)">
                                    <div class="info">
                                        <div class="title">Total Addons</div>
                                        <div class="count">{{($data['addons'])? :"0"}}</div>
                                    </div>
                                </a>
                                <a href="javascript:void(0)"><em class="icon bg-pink-dim ni ni-calendar-check"></em></a>
                            </li>
                        </ul>
                    </div><!-- .card-inner -->
                </div><!-- .card -->
            </div><!-- .col -->
            <div class="col-xxl-3 col-md-6">
                <div class="card card-full overflow-hidden">
                    <div class="nk-ecwg nk-ecwg7 h-100">
                        <div class="card-inner flex-grow-1">
                            <div class="card-title-group mb-4">
                                <div class="card-title">
                                    <h6 class="title">Lead Statistics</h6>
                                </div>
                            </div>
                            <div class="nk-ecwg7-ck">
                                <div class="chartjs-size-monitor">
                                    <div class="chartjs-size-monitor-expand">
                                        <div class=""></div>
                                    </div>
                                    <div class="chartjs-size-monitor-shrink">
                                        <div class=""></div>
                                    </div>
                                </div>
                                <canvas class="ecommerce-doughnut-s1 chartjs-render-monitor" id="orderStatistics" width="180"
                                    height="180" style="display: block; width: 180px; height: 180px;"></canvas>
                            </div>
                            <ul class="nk-ecwg7-legends">
                                <li>
                                    <div class="title">
                                        <span class="dot dot-lg sq" data-bg="#816bff"
                                            style="background: rgb(129, 107, 255);"></span>
                                        <span>Completed</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="title">
                                        <span class="dot dot-lg sq" data-bg="#13c9f2" style="background: rgb(19, 201, 242);"></span>
                                        <span>Processing</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="title">
                                        <span class="dot dot-lg sq" data-bg="#ff82b7"
                                            style="background: rgb(255, 130, 183);"></span>
                                        <span>Cancelled</span>
                                    </div>
                                </li>
                            </ul>
                        </div><!-- .card-inner -->
                    </div>
                </div><!-- .card -->
            </div>

            <div class="col-xxl-8">
                <div class="card card-full">
                    <div class="card-inner">
                        <div class="card-title-group">
                            <div class="card-title">
                                <h6 class="title">Recent Orders</h6>
                            </div>
                        </div>
                    </div>
                    <div class="nk-tb-list mt-n2">
                        <div class="nk-tb-item nk-tb-head">
                            <div class="nk-tb-col"><span>Order No.</span></div>
                            <div class="nk-tb-col tb-col-sm"><span>Customer</span></div>
                            <div class="nk-tb-col tb-col-md"><span>Date</span></div>
                            <div class="nk-tb-col"><span>Amount</span></div>
                        </div>
                        @isset($data['order'])
                        @foreach($data['order'] as $order)
                            <div class="nk-tb-item">
                                <div class="nk-tb-col">
                                    <span class="tb-lead"><a href="#">#{{$order->id}}</a></span>
                                </div>
                                <div class="nk-tb-col tb-col-sm">
                                    <div class="user-card">
                                         <div class="user-avatar sm bg-purple-dim">
                                           <span>{{$order->customer->contact->first_name[0]}}{{$order->customer->contact->last_name[0]}} </span>
                                         </div>
                                        <div class="user-name">
                                            <span class="tb-lead">{{$order->customer->contact->first_name}} {{$order->customer->contact->last_name}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="nk-tb-col tb-col-md">
                                    <span class="tb-sub">{{$order->created_at}}</span>
                                </div>
                                <div class="nk-tb-col">
                                    <span class="tb-sub tb-amount">{{$order->sub_amount}} <span>AED</span></span>
                                </div>
                            </div>
                        @endforeach
                            @endisset
                    </div>
                </div><!-- .card -->
            </div>

            <div class="col-xxl-6">
                <div class="card card-full">
                    <div class="nk-ecwg nk-ecwg8 h-100">
                        <div class="card-inner">
                            <div class="card-title-group mb-3">
                                <div class="card-title">
                                    <h6 class="title">Sales Statistics</h6>
                                </div>
                                <div class="card-tools">
{{--                                    <div class="dropdown">--}}
{{--                                        <a href="#" class="dropdown-toggle link link-light link-sm dropdown-indicator" data-bs-toggle="dropdown">Weekly</a>--}}
{{--                                        <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">--}}
{{--                                            <ul class="link-list-opt no-bdr">--}}
{{--                                                <li><a href="#"><span>Daily</span></a></li>--}}
{{--                                                <li><a href="#" class="active"><span>Weekly</span></a></li>--}}
{{--                                                <li><a href="#"><span>Monthly</span></a></li>--}}
{{--                                            </ul>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
                                </div>
                            </div>
                            <ul class="nk-ecwg8-legends">
                                <li>
                                    <div class="title">
                                        <span class="dot dot-lg sq" data-bg="#6576ff"></span>
                                        <span>Total Order</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="title">
                                        <span class="dot dot-lg sq" data-bg="#eb6459"></span>
                                        <span>Cancelled Order</span>
                                    </div>
                                </li>
                            </ul>
                            <div class="nk-ecwg8-ck">
                                <canvas class="ecommerce-line-chart-s4" id="salesStatistics"></canvas>
                            </div>
                            <div class="chart-label-group ps-5">
                                <div class="chart-label">01 Oct, 2023</div>
                                <div class="chart-label">30 Oct, 2023</div>
                            </div>
                        </div><!-- .card-inner -->
                    </div>
                </div><!-- .card -->
            </div><!-- .col -->

        </div><!-- .row -->

    </div><!-- .nk-block -->
    @endisset
</div>
@endsection