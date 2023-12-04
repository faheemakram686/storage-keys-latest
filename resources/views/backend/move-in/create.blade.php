@extends('backend.layouts.app')
@section('title', '| Move-In')
@section('content')
    <div class="components-preview wide-md mx-auto">
        <div class="nk-block nk-block-lg">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h4 class="title nk-block-title">Create New Move-In</h4>
                    </div>
                    <a href="{{url("admin/move-in")}}" class="btn btn-primary btn-sm d-none d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>Back</span></a>
                </div>
            </div>
            @isset($data)
            <div class="card">
                <div class="card-inner">
                    <form class="gy-3  is-alter" action="{{url("admin/save-move-in")}}"  method="post" enctype="multipart/form-data" id="MoveInForm">
                        @csrf
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Customer<span class="text-danger">*</span></label>
                                    <select name="customer_id" id="customer_id" class="form-control select2" data-live-search="true" required>
                                        <option value="">Choose One</option>
                                        @isset($data['customers'])
                                        @foreach( $data['customers'] as $customer)
                                        <option value="{{$customer->id}}">{{$customer->customer_name}}</option>
                                        @endforeach
                                        @endisset
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Contract<span class="text-danger">*</span></label>
                                    <select name="contract_id" id="" class="form-control select2 ContractSection" data-live-search="true" required>
                                        <option value="">Choose One</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Enter Or Scan Barcode<span class="text-danger">*</span></label>
                                    <input type="number" name="barcode_no" id="barcode_no" class="form-control" placeholder="Scan or Enter Barcode to add in list......">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="invoice-bills">
                                    <div class="table-responsive">

                                        <table class="table table-striped" id="dynamic_field">
                                            <thead>
                                            <tr>
                                                <th class="">Sr No#</th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th >Bar Code</th>
                                                <th>Description</th>
                                                <th>Status</th>
                                            </tr>
                                            </thead>
                                            <tbody id="invoiceItems">

                                            </tbody>
{{--                                            <tfoot>--}}
{{--                                            <tr>--}}
{{--                                                <td colspan="7"></td>--}}
{{--                                                <td colspan="">Barcode Labels</td>--}}
{{--                                                <td ><input class="form-control d-inline" name="sub_total" type="number" id="subtotal" value="0" readonly /></td>--}}
{{--                                            </tr>--}}
{{--                                            <tr>--}}
{{--                                                <td colspan="7"></td>--}}
{{--                                                <td colspan="">Move-In</td>--}}
{{--                                                <td ><input class="form-control d-inline" type="number" id="moveinItems" name="moveinItems" value="0" readonly></td>--}}
{{--                                            </tr>--}}
{{--                                            <tr>--}}
{{--                                                <td colspan="7"></td>--}}
{{--                                                <td colspan="">Total</td>--}}
{{--                                                 <td ><input class="form-control d-inline" name="grand_total"  type="number" id="grandtotal" value="0" readonly /></td>--}}
{{--                                            </tr>--}}
{{--                                            </tfoot>--}}
                                        </table>
{{--                                        <div class="nk-notes ff-italic fs-12px text-soft"> Invoice was created on a computer and is valid without the signature and seal. </div>--}}
                                    </div>
                                </div><!-- .invoice-bills -->
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-md btn-primary btn-submit" data-button="submit">Move In</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endisset
        </div>
    </div>


    <script>
        $(document).ready(function() {
            var dr=0;
            var checked = 0;



            $(document).on('click', '.countButton', function(){
                var button_id = $(this).attr("id");
                var checkedCount = $(".checkbox:checked").length;
                var totalitems =  $('#subtotal').val();
                $('#moveinItems').val(checkedCount);
                $('#grandtotal').val(totalitems - checkedCount);
            });
            var $barcodeInput = $('#barcode_no');
            var typingTimer;
            var typingDelay = 500;
            $barcodeInput.on('input', function() {
                clearTimeout(typingTimer);

                var query = $barcodeInput.val().trim();
                if (query !== '') {
                    typingTimer = setTimeout(function() {
                       var contract_id = $('.ContractSection').val();
                       if(contract_id != '')
                       {

                           getBarcodeLabels(query,contract_id);
                       }else{
                           toastr.error("Please select customer and contract...");
                        }

                    }, typingDelay);
                } else {
                    // $barcodeList.empty();
                }
            });
            $('#MoveInForm').on('keyup keypress', function(e) {
                var keyCode = e.keyCode || e.which;
                if (keyCode === 13) {
                    e.preventDefault();
                    return false;
                }
            });


            // $('#barcode_no').on('input', function() {
            //     var query =  $('#barcode_no').val().trim();
            //     if (query !== '') {
            //         getBarcodeLabels(query);
            //     }
            // });


            $("#customer_id").on('change', function() {
                var customer_id = $(this).val();
                getContracts(customer_id);
            });
            function getContracts(customer_id) {
                $.ajax({
                    url: '{{ url('admin/get-customer-contracts') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { customer_id: customer_id },
                    success: function(data) {
                        // console.log(data);
                        $('.ContractSection').empty();
                        var html3 = '';
                        var i;
                        var c = 0;
                        $('.ContractSection').html('<option value="">Select Contract</option>');
                        if (data.length > 0) {
                            for (i = 0; i < data.length; i++) {
                                html3 += '<option  value="' + data[i].id + '">' + data[i].subject + '</option>';
                            }
                        } else {
                            var html3 = '<option value="">No Contract Found</option>';
                        }
                        $('.ContractSection').append(html3);
                    },
                    error: function() {
                        toastr.error('any technical error');
                    }
                });
            }

            $(".ContractSection").on('change', function() {
                var contract_id = $(this).val();
                // getBarcodeLabels(contract_id);
            });
            function getBarcodeLabels(code,contract_id) {
                $.ajax({
                    url: '{{ url('admin/get-barcode-label') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { code: code },
                    success: function(data) {
                        console.log(data);
                        if(contract_id == data[0].moverequest.contract_id && data[0].status == "Pending" ) {

                            dr = 0;
                            var html = '';
                            var i;
                            var c = 0;
                            dr++;
                            for (i = 0; i < data.length; i++) {
                                c++;
                                html += '<tr id="row' + dr + '" class="dynamic-added">' +
                                    '<td><input type="hidden" name="barcodeItems[id][]"  placeholder="id" class="form-control id_list" value="' + data[i].id + '" /><span>' + dr + '</span></td>' +
                                    '<td></td>' +
                                    '<td></td>' +
                                    '<td></td>' +
                                    '<td></td>' +
                                    '<td><input type="text" name="barcodeItems[code][]" placeholder="Code" class="form-control code_list" value="' + data[i].code + '" readonly /></td>' +
                                    '<td><textarea type="text" name="barcodeItems[des][]" placeholder="Description" class="form-control des_list" ></textarea></td>' +
                                    '<td> ' +
                                    ' <span class="badge ' + ((data[i].status == 'Moved') ? 'badge-success' : 'badge-danger') + ' ">' + data[i].status + '</span>' +
                                    '</td>' +
                                    '</tr>';
                                dr++;

                            }
                            $('#invoiceItems').append(html);
                            $('#subtotal').val(c);
                            $('#moveinItems').val(0);
                        }else
                        {
                            toastr.error("Invalid Contract Barcode...");
                        }


                    },
                    error: function() {
                        toastr.error('any technical error');
                    }
                });

            }


            $('#MoveInForm').on('submit', function(e) {
                e.preventDefault();
                var formData=$('#MoveInForm').serialize()

                $.ajax({
                    type: "get",
                    url: '{{ url('admin/save-move-in') }}',
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('.btn-submit').text('Saving...');
                        $(".btn-submit").prop("disabled", true);
                    },
                    success: function(data) {

                        if (data.success) {
                            $('#MoveInForm')[0].reset();
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
                        window.location.href = "{{ url('admin/move-in')}}";
                    },

                    error: function() {;
                        toastr.error('any technical error');
                        $('.btn-submit').text('Save');
                        $(".btn-submit").prop("disabled", false);
                    }
                });


            });







        });
    </script>
@endsection



