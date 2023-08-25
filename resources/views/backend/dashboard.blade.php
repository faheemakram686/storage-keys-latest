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
        </div><!-- .row -->

    </div><!-- .nk-block -->
    @endisset
</div>
@endsection