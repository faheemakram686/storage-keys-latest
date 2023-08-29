@extends('backend.layouts.app')
@section('title', '| Invoice')
@section('content')
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h4 class="title nk-block-title">Invoice </h4>
                    </div>
                    <a href="{{url("admin/invoices")}}" class="btn btn-primary btn-sm d-none d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>Back</span></a>
                </div>
            </div>
            <div class="nk-content-body">
                <div class="nk-block">
                    @isset($data)
                        <div class="card">
                            <div class="card-aside-wrap">
                                <div class="card-inner card-inner-lg">
                                    <div class="nk-block-head">
                                        <div class="nk-block-between">
                                            <div class="nk-block-head-content">
                                                <h4 class="nk-block-title page-title">Record Payment for <strong class="text-primary small">#{{$data['invoice'][0]->invoice_no}}</strong></h4>
                                                <div class="nk-block-des text-soft">
                                                    <ul class="list-inline">
                                                        <li>Created At: <span class="text-base">{{$data['invoice'][0]->created_at}}</span></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- .nk-block-head -->
                                    <div class="nk-block">
                                        <div class="row g-gs">
                                            <div class="col-lg-12">
                                                <div class="card">
                                                    <div class="card-inner">
                                                        <form action="{{url('admin/save-payment')}}" method="post" id="PaymentForm">
                                                            @csrf
                                                            <input type="hidden" name="invoice_id" value="{{$data['invoice'][0]->id}}">
                                                            <div class="form-group">
                                                                <label class="form-label" for="amount_received">Amount Received <span class="text-danger">*</span></label>
                                                                <div class="form-control-wrap">
                                                                    @isset($data['payment'])
                                                                    <input type="number" class="form-control" value="{{$data['invoice'][0]->grand_total - $data['payment']}}" name="amount_received" id="amount_received" required>
                                                                    @endisset
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label" for="payment_date">Payment Date <span class="text-danger">*</span></label>
                                                                <div class="form-control-wrap">
                                                                    <input type="date" class="form-control" id="payment_date" name="payment_date" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label" for="payment_mode">Payment Mode <span class="text-danger">*</span></label>
                                                                <div class="form-control-wrap">
                                                                    <select class="form-control select2" name="payment_mode" id="payment_mode" required>
                                                                        <option>Choose One</option>
                                                                        <option value="1" >Cash</option>
                                                                        <option value="2" >Bank</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label" for="trans_id">Transaction ID</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text" class="form-control" name="trans_id" id="trans_id">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label" for="cf-default-textarea">Leave a note</label>
                                                                <div class="form-control-wrap">
                                                                    <textarea class="form-control form-control-sm" name="note" id="cf-default-textarea" placeholder="Leave a note"></textarea>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <button type="submit" class="btn btn-md btn-primary">Save Payment</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- .nk-block -->
                                </div>
                                @include('backend.invoice.aside')
                                <!-- card-aside -->
                            </div>
                            <!-- .card-aside-wrap -->
                        </div>
                    @endisset
                    <!-- .card -->
                </div>
                <!-- .nk-block -->
            </div>

        </div>
    </div>

    <script>

        $('#PaymentForm').on('submit', function(e) {

            e.preventDefault();
            var formData=$('#PaymentForm').serialize()
            $.ajax({
                type: "get",
                url: '{{ url('admin/save-payment') }}',
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('.btn-submit').text('Saving...');
                    $(".btn-submit").prop("disabled", true);
                },
                success: function(data) {

                    if (data.success) {
                        $('#PaymentForm')[0].reset();
                        toastr.success(data.success);

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

                error: function() {;
                    toastr.error('any technical error');
                    $('.btn-submit').text('Save');
                    $(".btn-submit").prop("disabled", false);
                }
            });


        });


    </script>
@endsection



