@extends('backend.layouts.app')
@section('title', '| Move-In Request')
@section('content')
    <div class="components-preview wide-md mx-auto">
        <div class="nk-block nk-block-lg">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Barcode List</h3>
                        <div class="nk-block-des text-soft">
                            <h5 class="">{{$data['moveInRequest'][0]->customer->company_name}}</h5>
                            <h6 class="">{{$data['moveInRequest'][0]->contract->subject}}</h6>
                        </div>
                    </div>
                    <div class="nk-block-head-content">
                        <div class="toggle-wrap nk-block-tools-toggle">
                            <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                            <div class="toggle-expand-content" data-content="pageMenu">
                                <ul class="nk-block-tools g-3">
                                    <li class="nk-block-tools-opt">
                                        <a  href="{{url('admin/print-barcode-labels').'/'.$data['moveInRequest'][0]->id}}" class="btn btn-primary btn-sm" ><em class="icon ni ni-printer-fill"></em><span>Print All</span></a>
                                    </li>
                                    <li class="nk-block-tools-opt">
                                        <a href="#" class="btn btn-primary btn-sm"  data-toggle="modal" data-target="#generateBarcode"><em class="icon ni ni-plus"></em><span>Generate Barcode</span></a>
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
                            <th class="nk-tb-col text-left"><span class="sub-text">Sr No.</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Barcode</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Generated Barcodes</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Status</span></th>
                            <th class="nk-tb-col tb-col-mb text-right"><span class="sub-text">Actions</span></th>
                        </tr>
                        </thead>
                        <tbody id="countryTable">
                        @foreach($data['barcode'] as  $key => $barcode)
                            <tr class="nk-tb-item odd">
                             <td class="nk-tb-col nk-tb-col-tools sorting_1">{{$key+1}}</td>
                             <td class="nk-tb-col nk-tb-col-tools">{{$barcode->code}}</td>
                            <td class="nk-tb-col nk-tb-col-tools"> <img src="data:image/png;base64,{{DNS1D::getBarcodePNG($barcode->code, 'C39',1,33,array(0,0,0), true)}}" alt="barcode" /></td>
                            <td class="nk-tb-col nk-tb-col-tools" >
                                <span class="{{(($barcode->status == 'Moved')? 'badge badge-success':'badge badge-danger')}}">{{$barcode->status}}</span>
                                </td>
                            <td class="nk-tb-col nk-tb-col-tools">
                                <ul class="nk-tb-actions gx-1">
                                    <li>
                                        <div class="drodown">
                                             <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <ul class="link-list-opt no-bdr">
                                                    <li><a href="{{url('admin/reprint-barcode-labels').'/'.$barcode->code}}" class="btn-label"  ><em class="icon ni ni-printer-fill"></em><span>Reprint Label</span></a></li>
{{--                                                    <li><a href="#" class="btn-edit" data='' data-toggle="modal" data-target="#editCountry"><em class="icon ni ni-edit"></em><span>Edit</span></a></li>--}}
                                                    <li><a href="#" class="btn-delete" data="{{$barcode->id}}"><em class="icon ni ni-trash"></em><span>Delete</span></a></li>
                                                    </ul>
                                                 </div>
                                            </div>
                                         </li>
                                     </ul>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- .card-preview -->
        </div>
        <!-- nk-block -->
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" id="generateBarcode" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-capitalize" id="ajax_model_title">Generate Barcode Label</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" data-form="ajax-form-modal">
                    <form method="post" action="{{ url('admin/barcode-label') }}" id="updateCountryForm1">
                        @csrf
                        <input type="hidden" name="request_id" value="{{$data['moveInRequest'][0]->id}}">
                        <input type="hidden" name="contract_id" value="{{$data['moveInRequest'][0]->contract->id}}">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Quantity<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="Enter Quantity" name="qty" id="qty" required>
                                </div>
                            </div>
                        </div>

                        <div class="float-right">
                            <button class="btn btn-primary mt-2 btn-genrate" type="submit">Generate Label</button>
                        </div>
                    </form>

                </div>
            </div><!-- .modal-content -->
        </div><!-- .modla-dialog -->
    </div>
    <script>

        $(document).ready(function() {
            $('#countryTable').on('click', '.btn-delete', function() {
                var id = $(this).attr('data');

                $.ajax({
                    url: '{{ url('admin/delete-barcode-label') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { id: id},
                    success: function(data) {
                        if (data.success) {
                            toastr.success('Record deleted successfully');
                            location.reload();
                        }else{
                            toastr.success('Record not deleted');
                        }

                    },
                    error: function() {
                        toastr.error('something went wrong');
                    }

                });

            });

        });
    </script>

@endsection



