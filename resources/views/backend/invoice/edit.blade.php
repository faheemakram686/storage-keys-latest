@extends('backend.layouts.app')
@section('title', '| Edit Invoice')
@section('content')
    <div class="components-preview wide-md mx-auto">
        <div class="nk-block nk-block-lg">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h4 class="title nk-block-title">Edit Invoice</h4>
                    </div>
                    <a href="{{url("admin/invoices")}}" class="btn btn-primary btn-sm d-none d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>Back</span></a>
                </div>
            </div>
            @isset($data)
            <div class="card">
                <div class="card-inner">
                    <form class="gy-3" action="{{url("admin/update-invoice")}}"  method="post" enctype="multipart/form-data" id="InvoiceUpdateForm">
                        @csrf
                        <input type="hidden" value="{{$data['invoice'][0]->id}}" name="invoice_id" id="invoice_id">
                        <div class="row g-4">

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Customer<span class="text-danger">*</span></label>
                                    <select name="customer_id" id="customer_id" class="form-control select2" data-live-search="true" required>
                                        <option value="">Choose One</option>
                                        @foreach( $data['customers'] as $customer)
                                            <option value="{{$customer->id}}" {{ (($customer->id == $data['invoice'][0]->customer_id)? 'selected':'') }} >{{$customer->full_display_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Invoice Type <span class="text-danger">*</span></label>
                                    <select name="invoice_type" id="invoice_type" class="form-control select2" data-live-search="true" required >
                                        <option value="" >Choose One</option>
                                        <option value="contract" {{(($data['invoice'][0]->type == "contract")? 'selected':'')}} >Contract</option>
                                        <option value="order" {{(($data['invoice'][0]->type == "order")? 'selected':'')}}>Order</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6" >
                                <div class="form-group contract-block " style="display: none;">
                                    <label>Contract<span class="text-danger ">*</span></label>
                                    <input type="hidden" name="contract_js" value="{{$data['invoice'][0]->contract_id}}" id="contract_id">
                                    <select name="contract_id" id="" class="form-control select2 ContractSection" data-live-search="true" >
                                        <option value="">Choose One</option>
                                    </select>
                                </div>
                                <div class="form-group order-block " style="display: none;" >
                                    <label>Order<span class="text-danger">*</span></label>
                                    <input type="hidden" name="order_js" value="{{$data['invoice'][0]->order_id}}" id="order_js">
                                    <select name="order_id" id="order_id" class="form-control select2 OrderSection" data-live-search="true">
                                        <option value="">Choose One</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6" >
                                <div class="form-group">
                                    <label>Invoice No. <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="invoice_no" value="{{$data['invoice'][0]->invoice_no}}" placeholder="Contract Value" >
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Invoice Date <span class="text-danger">*</span></label>
                                    <input class="form-control" type="date" name="invoice_date" value="{{$data['invoice'][0]->invoice_date}}" placeholder="Invoice Date" required >
                                </div>
                            </div>
                            <div class="col-lg-6" >
                                <div class="form-group">
                                    <label>Due Date <span class="text-danger">*</span></label>
                                    <input class="form-control" type="date" name="due_date" value="{{$data['invoice'][0]->due_date}}" placeholder="Due Date" required >
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Payment method Allowed <span class="text-danger"></span></label>
                                    <select name="payment_method" id="" class="form-control select2" data-live-search="true" required >
                                        <option value="">Choose One</option>
                                        <option value="cash"   {{(($data['invoice'][0]->payment_method == "cash")? 'selected':'')}}>Cash</option>
                                        <option value="bank" {{(($data['invoice'][0]->payment_method == "bank")? 'selected':'')}}>Bank</option>
                                        <option value="opm" {{(($data['invoice'][0]->payment_method == "opm")? 'selected':'')}}>Online Payment Method</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Invoice Value <span class="text-danger">*</span></label>
                                    <input class="form-control" type="number" step="any" name="invoice_value" id="invoice_value" placeholder="Invoice Value" required readonly>
                                </div>
                            </div>
                            <div class="col-lg-6" >
                                <div class="form-group">
                                    <label>Recurring Invoice?<span class="text-danger"></span></label>
                                    <div class="recurring-interval-wrap d-flex align-items-center flex-wrap">
                                        <select name="recurring" id="recurring" class="form-control recurring-select" required >
                                            <option  value="0"  {{(($data['invoice'][0]->recurring == "0")? 'selected':'')}}  >
                                                No
                                            </option>
                                            <option value="1" {{(($data['invoice'][0]->recurring == "1")? 'selected':'')}} >
                                                Every 1 month
                                            </option>
                                            <option value="2" {{(($data['invoice'][0]->recurring == "2")? 'selected':'')}}>
                                                Every 2 months
                                            </option>
                                            <option value="3" {{(($data['invoice'][0]->recurring == "3")? 'selected':'')}} >
                                                Every 3 months
                                            </option>
                                            <option value="4" {{(($data['invoice'][0]->recurring == "4")? 'selected':'')}}>
                                                Every 4 months
                                            </option>
                                            <option value="5" {{(($data['invoice'][0]->recurring == "5")? 'selected':'')}}>
                                                Every 5 months
                                            </option>
                                            <option value="6" {{(($data['invoice'][0]->recurring == "6")? 'selected':'')}}>
                                                Every 6 months
                                            </option>
                                            <option value="7" {{(($data['invoice'][0]->recurring == "7")? 'selected':'')}}>
                                                Every 7 months
                                            </option>
                                            <option value="8" {{(($data['invoice'][0]->recurring == "8")? 'selected':'')}}>
                                                Every 8 months
                                            </option>
                                            <option value="9" {{(($data['invoice'][0]->recurring == "9")? 'selected':'')}}>
                                                Every 9 months
                                            </option>
                                            <option value="10" {{(($data['invoice'][0]->recurring == "10")? 'selected':'')}}>
                                                Every 10 months
                                            </option>
                                            <option value="11" {{(($data['invoice'][0]->recurring == "11")? 'selected':'')}}>
                                                Every 11 months
                                            </option>
                                            <option value="12" {{(($data['invoice'][0]->recurring == "12")? 'selected':'')}}>
                                                Every 12 months
                                            </option>
                                            <option value="custom" {{(($data['invoice'][0]->recurring == "custom")? 'selected':'')}}>Custom</option>
                                        </select>
                                        <div class="custom-recurring d-none align-items-center">
                                            <input type="number" name="duration" id="duration" class="form-control" min="1" step="1" value="{{ $data['invoice'][0]->duration ?: 1 }}">
                                            <select name="duration_type" id="duration_type" class="form-control">
                                                <option value="days" {{(($data['invoice'][0]->duration_type == "days")? 'selected':'')}}>Day(s)</option>
                                                <option value="weeks" {{(($data['invoice'][0]->duration_type == "weeks")? 'selected':'')}}>Week(s)</option>
                                                <option value="months" {{(($data['invoice'][0]->duration_type == "months" || !$data['invoice'][0]->duration_type)? 'selected':'')}}>Month(s)</option>
                                                <option value="years" {{(($data['invoice'][0]->duration_type == "years")? 'selected':'')}}>Year(s)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6" >
                                <div class="form-control-wrap">
                                    <label for="no_cycle" class="mr-1">Total Cycles</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <label for="unlimited_cycles" class="mr-1">Infinity</label>
                                                <input id="unlimited_cycles" name="unlimited_cycles" value="1" {{(($data['invoice'][0]->no_cycle == "infinity")? 'checked':'')}} type="checkbox">
                                            </div>
                                        </div>
                                        <input id="no_cycle" name="no_cycle" type="number" step="1" min="0" value="{{ $data['invoice'][0]->no_cycle == 'infinity' ? 0 : $data['invoice'][0]->no_cycle }}" class="form-control" {{(($data['invoice'][0]->no_cycle == "infinity")? 'disabled':'')}}>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Sale Agent<span class="text-danger">*</span></label>
                                    <select name="sale_agent" id="" class="form-control select2" data-live-search="true" required>
                                        <option value="">Choose One</option>
                                        @foreach( $data['users'] as $user)
                                            <option value="{{$user->id}}" {{(($user->id == $data['invoice'][0]->user_id)? 'selected':'')}} >{{$user->first_name}} {{$user->last_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Products<span class="text-danger">*</span></label>
                                    <select name="product_id" id="" class="form-control select2 ProductSection" data-live-search="true" >
                                        <option value="">Choose One</option>
                                    </select>
                                </div>
                                {{--                                                                            <button type="button" name="add" id="add" class="btn btn-sm btn-success">add Items</button>--}}
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

                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <td colspan="4"></td>
                                                <td colspan="2">Subtotal</td>
                                                <td ><input class="form-control d-inline" name="sub_total" type="number" step="any" id="subtotal" value="0.00" min="0.00" readonly />AED</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4"></td>
                                                <td colspan="2">VAT Type</td>
                                                <td>
                                                    <select class="form-control form-select" id="vat_type" name="vat_type" required>
                                                        <option value="exclusive" {{ (($data['invoice'][0]->vat_type ?? 'exclusive') === 'exclusive') ? 'selected' : '' }}>VAT Exclusive</option>
                                                        <option value="inclusive" {{ (($data['invoice'][0]->vat_type ?? 'exclusive') === 'inclusive') ? 'selected' : '' }}>VAT Inclusive</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4"></td>
                                                <td colspan="2">VAT</td>
                                                <td ><input class="form-control d-inline" type="number" step="any" id="vat" name="vat" value="{{$data['invoice'][0]->vat}}" min="0">%</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4"></td>
                                                <td colspan="2">Grand Total</td>
                                                 <td ><input class="form-control d-inline" name="grand_total"  type="number" step="any" id="grandtotal" value="0.00" min="0.00" readonly />AED</td>
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
                                        <textarea class="form-control"  name="note">{{$data['invoice'][0]->note}}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-label" for="remarks">Remarks</label>
                                    <div class="form-control-wrap">
                                        <textarea class="form-control" name="remarks" id="remarks">{{$data['invoice'][0]->remarks}}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-label" for="status">Status</label>
                                    <select class="form-control form-select " id="status" name="status" required>
                                        <option value="1" {{(($data['invoice'][0]->status == 'Active')? 'selected':'')}}>Active</option>
                                        <option value="0" {{(($data['invoice'][0]->status == 'In-Active')? 'selected':'')}} >In-Active</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-md btn-primary btn-update" data-button="submit">Update</button>
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

            function toggleCustomRecurring() {
                var isCustom = $('#recurring').val() === 'custom';
                $('.custom-recurring').toggleClass('d-none', !isCustom);
            }
            $('#recurring').on('change', toggleCustomRecurring);
            toggleCustomRecurring();


            $("#invoice_type").on('change', function() {
                var type = $(this).val();
                if(type == 'contract')
                {
                    $(".contract-block").css({display: "block"});
                    $(".order-block").css({display: "none"})
                    $('#invoiceItems').empty();
                    dr=0;
                    $('#subtotal').val(0.00);
                    $('#grandtotal').val(0.00);
                }
                if(type == 'order')
                {
                    $(".order-block").css({display: "block"});
                    $(".contract-block").css({display: "none"});
                    $('#invoiceItems').empty();
                    dr=0;
                    $('#subtotal').val(0.00);
                    $('#grandtotal').val(0.00);
                }
            });

            $("#unlimited_cycles").change(function(event){
                if (this.checked){
                    $('#no_cycle').attr('disabled', 'disabled');
                    // alert("checked");
                } else {
                    $('#no_cycle').removeAttr('disabled')
                    // alert("not checked");
                }
            });

            if($('#unlimited_cycles').is(':checked'))
            {
                $('#no_cycle').attr('disabled', 'disabled');
                // checked
            }else
            {
                // unchecked
                $('#no_cycle').removeAttr('disabled');
            }

            $("#order_id").on('change', function() {
                var order_id = $(this).val();
                getOrderProcucts(order_id);
            });

            var customer_id=$('select[name=customer_id]').val();
            var type_selected=$('select[name=invoice_type]').val();
            var invoice_id=$('input[name=invoice_id]').val();

            getContracts(customer_id);
            getOrders(customer_id);
            getInvoiceItems(invoice_id);
            getProducts();

            if(type_selected == 'contract')
            {
                $(".contract-block").css({display: "block"});
                $(".order-block").css({display: "none"})

            }
            if(type_selected == 'order')
            {
                $(".order-block").css({display: "block"});
                $(".contract-block").css({display: "none"});
            }

            $('#add').click(function(){
                dr++;
                $('#dynamic_field').append('<tr id="row'+dr+'" class="dynamic-added"><td><input type="hidden" name="invoiceItems[id][]" placeholder="Enter your Name" class="form-control id_list" value="0" /><span>'+dr+'</span></td><td><input type="text" name="invoiceItems[name][]" placeholder="Enter your Name" class="form-control name_list" /></td><td><input type="text" name="invoiceItems[amount][]" placeholder="Enter your Amount" class="form-control amount_list" /></td><td><button type="button" name="remove" id="'+dr+'" class="btn  btn-sm btn-danger btn_remove"><em class="icon ni ni-trash-empty-fill"></em></button></td></tr>');

            });

            function getOrders(customer_id) {
                var order_id =  $('input[name=order_js]').val();
                $.ajax({
                    url: '{{ url('admin/get-customer-orders') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { customer_id: customer_id },
                    success: function(data) {
                        // console.log(data);
                        $('.OrderSection').empty();
                        var html3 = '';
                        var i;
                        var c = 0;
                        $('.OrderSection').html('<option value="">Select Order</option>');
                        if (data.length > 0) {
                            for (i = 0; i < data.length; i++) {
                                html3 += '<option  value="' + data[i].id + '" '+ ((data[i].id == order_id)? 'selected':'') +'>Order No# '+ data[i].id + '</option>';
                            }
                        } else {
                            var html3 = '<option value="">No Order Found</option>';
                        }
                        $('.OrderSection').append(html3);
                    },
                    error: function() {
                        toastr.error('any technical error');
                    }
                });
            }
            function getOrderProcucts(order_id) {
                $.ajax({
                    url: '{{ url('admin/get-order-products') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { order_id: order_id },
                    success: function(data) {
                        console.log(data[0].productdetail);
                        var html3 = '';
                        var i;
                        var c = 0;
                        // var total = 0.00;
                        $('#invoiceItems').empty();
                        $('input[name=invoice_value]').val();
                        for (i = 0; i < data.length; i++) {
                            dr++;
                            html3 += '<tr id="row' + dr + '" class="dynamic-added">' +
                                '<td><input type="hidden" name="invoiceItems[id][]" placeholder="id" class="form-control id_list" value="' + data[i].productdetail.id + '" /><span>' + dr + '</span></td>' +
                                '<td><input type="hidden" name="invoiceItems[cat][]" placeholder="cat" class="form-control cat_list" value="product" /></td>' +
                                '<td><input type="text" name="invoiceItems[name][]" placeholder="Item Name" class="form-control name_list" value="' + data[i].productdetail.p_name + '" /></td>' +
                                '<td><input type="number" step="any" name="invoiceItems[qty][]" placeholder="QTY" class="form-control qty_list" value="1" min="0" /></td>' +
                                '<td><input type="text" name="invoiceItems[unit][]" placeholder="Unit" class="form-control unit_list" value="" /></td>' +
                                '<td><input type="number" name="invoiceItems[amount][]" placeholder="Price" class="form-control amount_list" step="any" value="' + data[i].productdetail.sell_price + '" min="0.01" /></td>' +
                                '<td><input type="number" name="invoiceItems[total][]" placeholder="Total" class="form-control total" step="any" value="' + data[i].productdetail.sell_price + '" min="0.01" /></td>' +
                                '<td><button type="button" name="remove" id="' + dr + '" class="btn  btn-sm btn-danger btn_remove"><em class="icon ni ni-trash-empty-fill"></em></button></td>' +
                                '</tr>';
                            // total = parseFloat(data.sell_price);
                        }
                        $('#invoiceItems').append(html3);

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


            $(document).on('click', '.btn_remove', function(){
                var button_id = $(this).attr("id");
                $('#row'+button_id+'').remove();
                dr--;
                var total = calculateTotal();
                $('#subtotal').val(total);
                // calculateTotal();
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
                var vat = parseFloat($('#vat').val()) || 0;
                var vatType = $('#vat_type').val();
                var tax = vatType === 'inclusive' ? 0 : total * vat / 100;
                var grand = total + tax;
                $('#grandtotal').val(grand.toFixed(2));
                return total;
            }

            $('#dynamic_field').on('change', '.qty_list', function() {
                var row = $(this).closest('tr');
                calculateRowTotal(row);
                var total = calculateTotal();
                $('#subtotal').val(total);
            });

            $('#vat, #vat_type').on('change', function() {
                calculateTotal();
            });

            $("#customer_id").on('change', function() {
                var customer_id = $(this).val();
                getContracts(customer_id);
            });

            function getContracts(customer_id) {
                var contract_id =  $('input[name=contract_js]').val();

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
                                html3 += '<option  value="' + data[i].id + '" '+ ((data[i].id == contract_id)? 'selected':'') +'>' + data[i].subject + '</option>';
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
                            '<td><input type="number" step="any" name="invoiceItems[qty][]" placeholder="QTY" class="form-control qty_list" value="1" min="0" /></td>'+
                            '<td><input type="text" name="invoiceItems[unit][]" placeholder="Unit" class="form-control unit_list" value="" /></td>'+
                            '<td><input type="number" step="any" name="invoiceItems[amount][]" placeholder="Price" class="form-control amount_list" value="' + data.sell_price + '" min="0.01" /></td>'+
                            '<td><input type="number" step="any" name="invoiceItems[total][]" placeholder="Total" class="form-control total" value="' + data.sell_price + '" min="0.01" /></td>'+
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
                        var i;
                        const discount = (data.contract[0].estimate.term_length && data.contract[0].estimate.term_length.discount_percentage) ? data.contract[0].estimate.term_length.discount_percentage : 0;
                        const termLengthTitle = (data.contract[0].estimate.term_length && data.contract[0].estimate.term_length.title) ? data.contract[0].estimate.term_length.title : 'N/A';
                        const termPeriod = (data.contract[0].estimate.term_length && data.contract[0].estimate.term_length.term_period) ? data.contract[0].estimate.term_length.term_period : 1;

                        var units = data.contract[0].estimate.estimate_storage_units || [];
                        if (!units.length) {
                            units = [{
                                unit_price: data.contract[0].estimate.unit_price,
                                storage_unit_id: (data.contract[0].estimate.storageunit && data.contract[0].estimate.storageunit.id) ? data.contract[0].estimate.storageunit.id : '',
                                storageunit: data.contract[0].estimate.storageunit || null
                            }];
                        }

                        var html = '';
                        for (i = 0; i < units.length; i++) {
                            dr++;
                            var price = parseFloat(units[i].unit_price || 0);
                            var discountedPrice = price - (price * discount / 100);
                            var lineTotal = price * termPeriod;
                            var storageUnitName = (units[i].storageunit && units[i].storageunit.storage_unit_name) ? units[i].storageunit.storage_unit_name : ('Unit #' + (units[i].storage_unit_id || ''));
                            var itemId = units[i].storage_unit_id || '';

                            html += '<tr id="row'+dr+'" class="dynamic-added">'+
                                '<td><input type="hidden" name="invoiceItems[id][]" placeholder="id" class="form-control id_list" value="' + itemId + '" /><span>' + dr + '</span></td>'+
                                '<td><input type="hidden" name="invoiceItems[cat][]" placeholder="cat" class="form-control cat_list" value="storage_unit" /></td>'+
                                '<td><input type="text" name="invoiceItems[name][]" placeholder="Item Name" class="form-control name_list" value="' + storageUnitName +' / '+  termLengthTitle + '" /></td>'+
                                '<td><input type="number" step="any" name="invoiceItems[qty][]" placeholder="QTY" class="form-control qty_list" value="'+ termPeriod +'" min="0"/></td>'+
                                '<td><input type="text" name="invoiceItems[unit][]" placeholder="Unit" class="form-control unit_list" value="'+ termLengthTitle +'" /></td>'+
                                '<td><input type="text" name="invoiceItems[amount][]" placeholder="Price" class="form-control amount_list" value="' + discountedPrice + '" min="0.00" /></td>'+
                                '<td><input type="number" step="any" name="invoiceItems[total][]" placeholder="Total" class="form-control total" value="' + lineTotal + '" min="0.00" /></td>'+
                                '<td><button type="button" name="remove" id="'+dr+'" class="btn  btn-sm btn-danger btn_remove"><em class="icon ni ni-trash-empty-fill"></em></button></td>'+
                                '</tr>';
                        }

                        var addons = data.contract[0].estimate.estimate_addon || [];
                        for (i = 0; i < addons.length; i++) {
                            dr++;
                            html += '<tr id="row'+dr+'" class="dynamic-added">'+
                                '<td><input type="hidden" name="invoiceItems[id][]" placeholder="id" class="form-control id_list" value="' + addons[i].addon.id + '" /><span>' + dr + '</span></td>'+
                                '<td><input type="hidden" name="invoiceItems[cat][]" placeholder="cat" class="form-control cat_list" value="addon" /></td>'+
                                '<td><input type="text" name="invoiceItems[name][]" placeholder="Item Name" class="form-control name_list" value="' + addons[i].addon.name + '" /></td>'+
                                '<td><input type="number" step="any" name="invoiceItems[qty][]" placeholder="QTY" class="form-control qty_list" value="1" min="0" /></td>'+
                                '<td><input type="text" name="invoiceItems[unit][]" placeholder="Unit" class="form-control unit_list" value="" /></td>'+
                                '<td><input type="text" name="invoiceItems[amount][]" placeholder="Price" class="form-control amount_list" value="' + addons[i].price + '" min="0.00" /></td>'+
                                '<td><input type="number" step="any" name="invoiceItems[total][]" placeholder="Total" class="form-control total" value="' + addons[i].price + '"  min="0.00" /></td>'+
                                '<td><button type="button" name="remove" id="'+dr+'" class="btn btn-sm btn-danger btn_remove"><em class="icon ni ni-trash-empty-fill"></em></button></td>'+
                                '</tr>';
                        }

                        var contract = data.contract[0];
                        var estimate = contract.estimate || {};
                        var insuranceAmount = parseFloat(contract.insurance_amount || estimate.insurance_amount || 0);
                        if (insuranceAmount > 0) {
                            dr++;
                            var insuranceCover = contract.insurance_cover || estimate.insurance_cover || '';
                            var insuranceName = 'Insurance' + (insuranceCover ? (' (Cover ' + insuranceCover + ')') : '');
                            var insuranceId = contract.insurance_id || estimate.insurance_id || '';
                            var insuranceTotal = (insuranceAmount * termPeriod).toFixed(2);
                            html += '<tr id="row'+dr+'" class="dynamic-added">'+
                                '<td><input type="hidden" name="invoiceItems[id][]" placeholder="id" class="form-control id_list" value="' + insuranceId + '" /><span>' + dr + '</span></td>'+
                                '<td><input type="hidden" name="invoiceItems[cat][]" placeholder="cat" class="form-control cat_list" value="insurance" /></td>'+
                                '<td><input type="text" name="invoiceItems[name][]" placeholder="Item Name" class="form-control name_list" value="' + insuranceName + '" /></td>'+
                                '<td><input type="number" step="any" name="invoiceItems[qty][]" placeholder="QTY" class="form-control qty_list" value="'+ termPeriod +'" min="0"/></td>'+
                                '<td><input type="text" name="invoiceItems[unit][]" placeholder="Unit" class="form-control unit_list" value="'+ termLengthTitle +'" /></td>'+
                                '<td><input type="text" name="invoiceItems[amount][]" placeholder="Price" class="form-control amount_list" value="' + insuranceAmount.toFixed(2) + '" min="0.00" /></td>'+
                                '<td><input type="number" step="any" name="invoiceItems[total][]" placeholder="Total" class="form-control total" value="' + insuranceTotal + '" min="0.00" /></td>'+
                                '<td><button type="button" name="remove" id="'+dr+'" class="btn btn-sm btn-danger btn_remove"><em class="icon ni ni-trash-empty-fill"></em></button></td>'+
                                '</tr>';
                        }

                        $('#dynamic_field').append(html);
                        var total = calculateTotal();
                        $('#subtotal').val(total);
                        $('input[name=invoice_value]').val($('#grandtotal').val());

                    },
                    error: function() {
                        toastr.error('any technical error');
                    }
                });

            }

            function getInvoiceItems(invoice_id) {
                $.ajax({
                    url: '{{ url('admin/get-invoice-items') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { invoice_id: invoice_id },
                    success: function(data) {
                    console.log(data.invoiceItems)
                        var html = '';
                        var i;
                        var c = 0;

                        for (i = 0; i < data.invoiceItems.length; i++) {
                            dr++
                            html += '<tr id="row'+dr+'" class="dynamic-added">'+
                                '<td><input type="hidden" name="invoiceItems[id][]" placeholder="id" class="form-control id_list" value="' + data.invoiceItems[i].item_id + '" /><span>' + dr + '</span></td>'+
                                '<td><input type="hidden" name="invoiceItems[cat][]" placeholder="cat" class="form-control cat_list" value="' + data.invoiceItems[i].category + '" /></td>'+
                                '<td><input type="text" name="invoiceItems[name][]" placeholder="Item Name" class="form-control name_list" value="' + data.invoiceItems[i].item_name + '" /></td>'+
                                '<td><input type="number" step="any" name="invoiceItems[qty][]" placeholder="QTY" class="form-control qty_list" value="' + data.invoiceItems[i].quantity + '" min="0" /></td>'+
                                '<td><input type="text" name="invoiceItems[unit][]" placeholder="Unit" class="form-control unit_list" value="' + data.invoiceItems[i].unit + '" /></td>'+
                                '<td><input type="text" name="invoiceItems[amount][]" placeholder="Price" class="form-control amount_list" value="' + data.invoiceItems[i].unit_price + '" min="0.00" /></td>'+
                                '<td><input type="number" step="any" name="invoiceItems[total][]" placeholder="Total" class="form-control total" value="' + data.invoiceItems[i].total_price + '"  min="0.00" /></td>'+
                                '<td><button type="button" name="remove" id="'+dr+'" class="btn btn-sm btn-danger btn_remove"><em class="icon ni ni-trash-empty-fill"></em></button></td>'+
                                '</tr>';

                        }
                        // alert(html);
                        $('#dynamic_field').append(html);
                        var total = calculateTotal();
                        $('#subtotal').val(total);
                        // var totalhtml = total + ' AED';
                        // $('#subtotal').text(totalhtml);
                        // $('#grandtotal').text(totalhtml);
                        // $('input[name=invoice_value]').val(total);

                    },
                    error: function() {
                        toastr.error('any technical error');
                    }
                });

            }

            $('#InvoiceUpdateForm').on('submit', function(e) {

                e.preventDefault();
                var formData=$('#InvoiceUpdateForm').serialize()
                $.ajax({
                    type: "get",
                    url: '{{ url('admin/update-invoice') }}',
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('.btn-update').text('Updating...');
                        $(".btn-update").prop("disabled", true);
                    },
                    success: function(data) {

                        if (data.success) {
                            $('#InvoiceUpdateForm')[0].reset();
                            $('.close').click();
                            toastr.success(data.success);

                        }
                        if (data.errors) {
                            toastr.error(data.errors);
                            $('.btn-update').text('Update');
                            $(".btn-update").prop("disabled", false);
                        }
                    },

                    complete: function(data) {
                        $(".btn-update").html("Update");
                        $(".btn-update").prop("disabled", false);
                        window.location.href = "{{ url('admin/invoices')}}";
                    },

                    error: function() {;
                        toastr.error('any technical error');
                        $('.btn-update').text('Update');
                        $(".btn-update").prop("disabled", false);
                    }
                });


            });







        });
    </script>
@endsection



