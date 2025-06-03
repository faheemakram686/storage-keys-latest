@extends('backend.layouts.app')
@section('title', '| Invoice')
@section('content')
    <div class="components-preview wide-md mx-auto">
        <div class="nk-block nk-block-lg">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h4 class="title nk-block-title">Create New Invoice</h4>
                    </div>
                    <a href="{{url("admin/invoices")}}" class="btn btn-primary btn-sm d-none d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>Back</span></a>
                </div>
            </div>
            @isset($data)
            <div class="card">
                <div class="card-inner">
                    <form class="gy-3 form-validate is-alter" action="{{url("admin/save-invoice")}}"  method="post" enctype="multipart/form-data" id="InvoiceForm">
                        @csrf
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Customer<span class="text-danger">*</span></label>
                                    <select name="customer_id" id="customer_id" class="form-control select2" data-live-search="true" required>
                                        <option value="">Choose One</option>
                                        @foreach( $data['customers'] as $customer)
                                        <option value="{{$customer->id}}">{{$customer->company_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Contract<span class="text-danger">*</span></label>
                                    <select name="contract_id" id="" class="form-control select2 ContractSection" data-live-search="true" required>
                                        <option value="">Choose One</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Invoice No. <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="invoice_no" value="{{\App\Models\Invoice::generateInvoiceNumber()}}" placeholder="Contract Value" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Payment method Allowed <span class="text-danger"></span></label>
                                    <select name="sale_agent" id="" class="form-control select2" data-live-search="true" required multiple>
                                        <option value="">Choose One</option>
                                        <option value="cash" selected>Cash</option>
                                        <option value="bank">Bank</option>
                                        <option value="opm">Online Payment Method</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Invoice Value <span class="text-danger">*</span></label>
                                    <input class="form-control" type="number" name="invoice_value" id="invoice_value" placeholder="Invoice Value" required>
                                </div>
                                <div class="form-group">
                                    <label>Sale Agent<span class="text-danger">*</span></label>
                                    <select name="sale_agent" id="" class="form-control select2" data-live-search="true" required>
                                        <option value="">Choose One</option>
                                        @foreach( $data['users'] as $user)
                                            <option value="{{$user->id}}" {{(($user->id = auth()->id())? 'selected':'')}} >{{$user->first_name}} {{$user->last_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Invoice Date <span class="text-danger">*</span></label>
                                    <input class="form-control" type="date" name="invoice_date" placeholder="Invoice Date" >
                                </div>
                            </div>
                            <div  class="col-lg-6" >
                                <div class="form-group">
                                    <label>Due Date <span class="text-danger">*</span></label>
                                    <input class="form-control" type="date" name="due_date" placeholder="Due Date" >
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Products<span class="text-danger">*</span></label>
                                    <select name="product_id" id="" class="form-control select2 ProductSection" data-live-search="true" >
                                        <option value="">Choose One</option>
                                    </select>
                                </div>
{{--                                <button type="button" name="add" id="add" class="btn btn-sm btn-success">add Items</button>--}}
                            </div>
                            <div class="col-lg-12">
                                <div class="invoice-bills">
                                    <div class="table-responsive">

                                        <table class="table table-striped" id="dynamic_field">
                                            <thead>
                                            <tr>
                                                <th class="w-70px">Sr No#</th>
                                                <th></th>
                                                <th class="w-40">Description</th>
                                                <th>QTY</th>
                                                <th>Unit</th>
                                                <th>Price</th>
                                                <th>Total</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody id="invoiceItems">

{{--                                                <tr>--}}
{{--                                                    <td><input type="hidden" name="id[]" placeholder="Enter your Name" class="form-control id_list" /><span>1</span></td>--}}
{{--                                                    <td><input type="text" name="name[]" placeholder="Enter your Name" class="form-control name_list" /></td>--}}
{{--                                                    <td><input type="text" name="amount[]" placeholder="Enter your Name" class="form-control amount_list" /></td>--}}
{{--                                                    <td></td>--}}
{{--                                                </tr>--}}

                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <td colspan="4"></td>
                                                <td colspan="2">Subtotal</td>
                                                <td ><input class="form-control d-inline" name="sub_total" type="number" id="subtotal" value="0.00" min="0.00" readonly />AED</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4"></td>
                                                <td colspan="2">VAT</td>
                                                <td ><input class="form-control d-inline" type="number" id="vat" name="vat" value="5" min="0">%</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4"></td>
                                                <td colspan="2">Grand Total</td>
                                                 <td ><input class="form-control d-inline" name="grand_total"  type="number" id="grandtotal" value="0.00" min="0.00" readonly />AED</td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                        <div class="nk-notes ff-italic fs-12px text-soft"> Invoice was created on a computer and is valid without the signature and seal. </div>
                                    </div>
                                </div><!-- .invoice-bills -->
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-label" for="reviewer">Client Note</label>
                                    <div class="form-control-wrap">
                                        <textarea class="form-control"  name="note"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-label" for="status">Status</label>
                                    <select class="form-control form-select " id="status" name="status" required>
                                        <option value="0">Active</option>
                                        <option value="1">In-Active</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-md btn-primary btn-submit" data-button="submit">Save</button>
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
            getProducts();
            $('#add').click(function(){
                dr++;
                $('#dynamic_field').append('<tr id="row'+dr+'" class="dynamic-added"><td><input type="hidden" name="invoiceItems[id][]" placeholder="Enter your Name" class="form-control id_list" value="0" /><span>'+dr+'</span></td><td><input type="hidden" name="invoiceItems[cat][]" placeholder="cat" class="form-control cat_list" value="product" /></td><td><input type="text" name="invoiceItems[name][]" placeholder="Enter your Name" class="form-control name_list" /></td><td><input type="number" name="invoiceItems[qty][]" placeholder="QTY" class="form-control qty_list" value="1" min="0" /></td><td><input type="text" name="invoiceItems[unit][]" placeholder="Unit" class="form-control unit_list" value="" /></td><td><input type="text" name="invoiceItems[amount][]" placeholder="Enter your Amount" class="form-control amount_list" /></td><td><input type="number" name="invoiceItems[total][]" placeholder="Total" class="form-control total" value="" min="0.01" /></td><td><button type="button" name="remove" id="'+dr+'" class="btn  btn-sm btn-danger btn_remove"><em class="icon ni ni-trash-empty-fill"></em></button></td></tr>');

            });

            $(document).on('click', '.btn_remove', function(){
                var button_id = $(this).attr("id");
                $('#row'+button_id+'').remove();
                dr--;
                var total = calculateTotal();
                $('#subtotal').val(total);
            });
            function calculateRowTotal(row) {
                var quantity = row.find('.qty_list').val();
                var unitPrice = row.find('.amount_list').val();
                var itemTotal = quantity * unitPrice;
                row.find('.total').val(itemTotal);

                return itemTotal;
            }

            // Calculate total price for all rows
            function calculateTotal() {
                var total = 0;

                $('#dynamic_field tbody tr').each(function() {
                    var rowTotal = calculateRowTotal($(this));
                    total += rowTotal;
                });
                var gtotal = total;
                var vat = $('#vat').val();
                var tax = gtotal / 100 * vat;
                var grand = gtotal + tax;
                $('#grandtotal').val(grand);
                return total;
            }

            $('#dynamic_field').on('change', '.qty_list', function() {
                var row = $(this).closest('tr');
                calculateRowTotal(row);
                var total = calculateTotal();
                $('#subtotal').val(total);
            });

            $('#dynamic_field').on('change', '#vat', function() {
                var vat = $(this).val();
                calculateTotal();
            });

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

            function getProducts() {
                $.ajax({
                    url: '{{ url('admin/get-product') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',

                    success: function(data) {
                        console.log(data);
                        $('.ProductSection').empty();
                        var html3 = '';
                        var i;
                        var c = 0;
                        $('.ProductSection').html('<option value="">Select Product</option>');
                        if (data.length > 0) {
                            for (i = 0; i < data.length; i++) {
                                html3 += '<option  value="' + data[i].id + '">' + data[i].p_name + '</option>';
                            }
                        } else {
                            var html3 = '<option value="">No Product Found</option>';
                        }
                        $('.ProductSection').append(html3);
                    },
                    error: function() {
                        toastr.error('any technical error');
                    }
                });
            }

            $(".ProductSection").on('change', function() {
                var product_id = $(this).val();
                 getProduct(product_id);
            });
            function getProduct(product_id) {
                $.ajax({
                    url: '{{ url('admin/get-product-detail') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { product_id: product_id },
                    success: function(data) {
                        console.log(data);
                        var html3 = '';
                        var i;
                        var c = 0;
                        // var total = 0.00;
                        dr++;
                        $('input[name=invoice_value]').val();
                        var   html = '<tr id="row'+dr+'" class="dynamic-added">'+
                            '<td><input type="hidden" name="invoiceItems[id][]" placeholder="id" class="form-control id_list" value="' + data.id + '" /><span>' + dr + '</span></td>'+
                            '<td><input type="hidden" name="invoiceItems[cat][]" placeholder="cat" class="form-control cat_list" value="product" /></td>'+
                            '<td><input type="text" name="invoiceItems[name][]" placeholder="Item Name" class="form-control name_list" value="' + data.p_name + '" /></td>'+
                            '<td><input type="number" name="invoiceItems[qty][]" placeholder="QTY" class="form-control qty_list" value="1" min="0" /></td>'+
                            '<td><input type="text" name="invoiceItems[unit][]" placeholder="Unit" class="form-control unit_list" value="" /></td>'+
                            '<td><input type="number" name="invoiceItems[amount][]" placeholder="Price" class="form-control amount_list" value="' + data.sell_price + '" min="0.01" /></td>'+
                            '<td><input type="number" name="invoiceItems[total][]" placeholder="Total" class="form-control total" value="' + data.sell_price + '" min="0.01" /></td>'+
                            '<td><button type="button" name="remove" id="'+dr+'" class="btn  btn-sm btn-danger btn_remove"><em class="icon ni ni-trash-empty-fill"></em></button></td>'+
                            '</tr>';
                        // total = parseFloat(data.sell_price);

                        $('#dynamic_field').append(html);
                        var total = calculateTotal();
                        $('#subtotal').val(total);
                        // var totalhtml = total + ' AED';
                        // $('#subtotal').text(totalhtml);
                        // $('#grandtotal').text(totalhtml);
                        $('input[name=invoice_value]').val(total);

                    },
                    error: function() {
                        toastr.error('any technical error');
                    }
                });

            }

            $(".ContractSection").on('change', function() {
                var contract_id = $(this).val();
                getEstimate(contract_id);
            });
            function getEstimate(contract_id) {
                $.ajax({
                    url: '{{ url('admin/contract-estimate') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { contract_id: contract_id },
                    success: function(data) {
                        console.log(data);
                        var html3 = '';
                        var i;
                        var c = 0;
                        // var total = 0.00;
                        dr++;
                        $('input[name=invoice_value]').val();
                        var   html = '<tr id="row'+dr+'" class="dynamic-added">'+
                            '<td><input type="hidden" name="invoiceItems[id][]" placeholder="id" class="form-control id_list" value="' + data.contract[0].estimate.storageunit.id + '" /><span>' + dr + '</span></td>'+
                            '<td><input type="hidden" name="invoiceItems[cat][]" placeholder="cat" class="form-control cat_list" value="storage_unit" /></td>'+
                            '<td><input type="text" name="invoiceItems[name][]" placeholder="Item Name" class="form-control name_list" value="' + data.contract[0].estimate.storageunit.storage_unit_name +' / '+  data.contract[0].estimate.term_length.title + '" /></td>'+
                            '<td><input type="number" name="invoiceItems[qty][]" placeholder="QTY" class="form-control qty_list" value="1" min="0"/></td>'+
                            '<td><input type="text" name="invoiceItems[unit][]" placeholder="Unit" class="form-control unit_list" value="" /></td>'+
                            '<td><input type="text" name="invoiceItems[amount][]" placeholder="Price" class="form-control amount_list" value="' + data.contract[0].estimate.unit_price + '" min="0.00" /></td>'+
                            '<td><input type="number" name="invoiceItems[total][]" placeholder="Total" class="form-control total" value="' + data.contract[0].estimate.unit_price + '" min="0.00" /></td>'+
                            '<td><button type="button" name="remove" id="'+dr+'" class="btn  btn-sm btn-danger btn_remove"><em class="icon ni ni-trash-empty-fill"></em></button></td>'+
                            '</tr>';
                        // total = parseFloat(data.contract[0].estimate.unit_price);

                        for (i = 0; i < data.contract[0].estimate.estimate_addon.length; i++) {
                            dr++;
                            html += '<tr id="row'+dr+'" class="dynamic-added">'+
                                '<td><input type="hidden" name="invoiceItems[id][]" placeholder="id" class="form-control id_list" value="' + data.contract[0].estimate.estimate_addon[i].addon.id + '" /><span>' + dr + '</span></td>'+
                                '<td><input type="hidden" name="invoiceItems[cat][]" placeholder="cat" class="form-control cat_list" value="addon" /></td>'+
                                '<td><input type="text" name="invoiceItems[name][]" placeholder="Item Name" class="form-control name_list" value="' + data.contract[0].estimate.estimate_addon[i].addon.name + '" /></td>'+
                                '<td><input type="number" name="invoiceItems[qty][]" placeholder="QTY" class="form-control qty_list" value="1" min="0" /></td>'+
                                '<td><input type="text" name="invoiceItems[unit][]" placeholder="Unit" class="form-control unit_list" value="" /></td>'+
                                '<td><input type="text" name="invoiceItems[amount][]" placeholder="Price" class="form-control amount_list" value="' + data.contract[0].estimate.estimate_addon[i].price + '" min="0.00" /></td>'+
                                '<td><input type="number" name="invoiceItems[total][]" placeholder="Total" class="form-control total" value="' + data.contract[0].estimate.estimate_addon[i].price + '"  min="0.00" /></td>'+
                                '<td><button type="button" name="remove" id="'+dr+'" class="btn btn-sm btn-danger btn_remove"><em class="icon ni ni-trash-empty-fill"></em></button></td>'+
                                '</tr>';
                            // total = total +  parseFloat( data.contract[0].estimate.estimate_addon[i].price);

                        }
                        $('#dynamic_field').append(html);
                        var total = calculateTotal();
                        $('#subtotal').val(total);
                        // var totalhtml = total + ' AED';
                        // $('#subtotal').text(totalhtml);
                        // $('#grandtotal').text(totalhtml);
                        $('input[name=invoice_value]').val(total);

                    },
                    error: function() {
                        toastr.error('any technical error');
                    }
                });

            }

            $('#InvoiceForm').on('submit', function(e) {

                e.preventDefault();
                var formData=$('#InvoiceForm').serialize()
                $.ajax({
                    type: "get",
                    url: '{{ url('admin/save-invoice') }}',
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('.btn-submit').text('Saving...');
                        $(".btn-submit").prop("disabled", true);
                    },
                    success: function(data) {

                        if (data.success) {
                            $('#InvoiceForm')[0].reset();
                            $('.close').click();
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
                        {{--window.location.href = "{{ url('admin/invoices')}}";--}}
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



