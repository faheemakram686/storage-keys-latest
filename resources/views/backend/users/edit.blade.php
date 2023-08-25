@extends('backend.layouts.app')

@section('title', '| Roles')

@section('content')
    <div class="components-preview wide-md mx-auto">
        <div class="nk-block nk-block-lg">
            <div class="nk-block-head">
                <div class="nk-block-head-content">
                    <h4 class="title nk-block-title">Add User</h4>
                    <div class="nk-block-des">
                        {{-- <p>You can make style out your....</p> --}}
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-inner">
                    <div class="card-head">
                        <h5 class="card-title">User Info</h5>
                    </div>
                    <form action="{{route('users.store')}}" class="gy-3 form-validate user-form is-alter"
                        method="POST">
                        @csrf
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="full-name-1">First Name</label>
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" id="full-name-1" name="firstname" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="full-name-1">Last Name</label>
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" id="full-name-1" name="lastname" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="full-name-1">Email</label>
                                    <div class="form-control-wrap">
                                        <input type="email" class="form-control" id="full-name-1" name="email" required>
                                    </div>
                                </div>
                            </div>
    
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="phone-no-1">Phone</label>
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" id="phone-no-1" name="phone" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="pay-amount-1">Address</label>
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" id="pay-amount-1" name="address" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="card">
                                    <input name="intro" type="hidden">
                                    <label class="form-label" for="phone-no-1">Introduction</label>
                                    <div id="editor-container">
    
                                    </div>
    
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-lg btn-primary">Save</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection