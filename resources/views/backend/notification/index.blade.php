@extends('backend.layouts.app')
@section('title', '| Notification')
@section('content')
    <div class="components-preview wide-md mx-auto">
        <div class="nk-block nk-block-lg">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Notification List</h3>
                        <div class="nk-block-des text-soft">

                        </div>
                    </div>
                    <div class="nk-block-head-content">
                        <div class="toggle-wrap nk-block-tools-toggle">
                            <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                            <div class="toggle-expand-content" data-content="pageMenu">
                                <ul class="nk-block-tools g-3">
                                    <li class="nk-block-tools-opt">
{{--                                        <a href="#" class="btn btn-primary btn-sm"  data-toggle="modal" data-target="#addCountry"><em class="icon ni ni-plus"></em><span>Add Country</span></a>--}}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card card-preview">
                <div class="card-inner">
                    <table class="datatable-init-export nk-tb-list nk-tb-ulist" data-auto-responsive="true"  id="datatable" >
                        <thead>
                        <tr class="nk-tb-item nk-tb-head">
                            <th class="nk-tb-col"><span class="sub-text">Title</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Date Time</span></th>
                        </tr>
                        </thead>
                        <tbody >
                        @isset($data)
                            @foreach($data as $notification)
                        <tr class="nk-tb-item odd">
                            <td class="nk-tb-col nk-tb-col-tools"><a href="{{$notification->data['url']}}">{{$notification->data['message']}}</a></td>
                            <td class="nk-tb-col nk-tb-col-tools">{{$notification->created_at}}</td>
                        </tr>
                            @endforeach
                        @endisset
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- .card-preview -->
        </div>
        <!-- nk-block -->
    </div>



@endsection



