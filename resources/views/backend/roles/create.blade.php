@extends('backend.layouts.app')

@section('title', '| Roles')

@section('content')
    <div class="components-preview wide-md mx-auto">
        <div class="nk-block nk-block-lg">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h4 class="title nk-block-title">Add Role Permissions</h4>
                    </div>
                    <a href="{{ route('roles.index') }}" class="btn btn-primary d-none d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>Back</span></a>
                </div>
            </div>
            @include('backend.roles.addform')
        </div>
    </div>
@endsection
