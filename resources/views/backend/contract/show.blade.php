@extends('backend.layouts.app')
@section('title', '| Contract')
@section('content')

    {{--    <script src="https://cdn.ckeditor.com/ckeditor5/38.0.1/decoupled-document/ckeditor.js"></script>--}}
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h4 class="title nk-block-title">Contract Information</h4>
                    </div>
                    <a href="{{url("admin/contract")}}" class="btn btn-primary btn-sm d-none d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>Back</span></a>
                </div>
            </div>
            <div class="nk-content-body">
                <div class="nk-block">
                    @isset($data['contract'][0])
                        <div class="card">
                            <div class="card-aside-wrap">
                                <div class="card-inner card-inner-lg">
                                    <div class="nk-block-head">
                                        <div class="nk-block-between g-3">
                                            <div class="nk-block-head-content">
                                                <h4 class="nk-block-title page-title">Contract <strong class="text-primary small">#{{$data['contract'][0]->id ?? ''}}</strong></h4>
                                                <div class="nk-block-des text-soft">
                                                    <ul class="list-inline">
                                                        <li>Created At: <span class="text-base">{{$data['contract'][0]->created_at ?? ''}}</span></li>
                                                        @isset($data['contract'][0]->status)
                                                            <span class="badge {{(($data['contract'][0]->status == 'Approved') ? " badge-success":"badge-danger")}}">{{$data['contract'][0]->status}}</span>
                                                        @endisset
                                                    </ul>
                                                </div>
                                            </div>
                                            @isset($data['appSettings'][3]->value)
                                                @if($data['appSettings'][3]->value == auth()->id() && isset($data['contract'][0]->status) && $data['contract'][0]->status == 'Not Approved' )
                                                    <div class="d-flex align-center">
                                                        <div class="nk-tab-actions me-n1">
                                                            <a class="btn  btn-primary " title="Approve" id="btn-approve" href="#">Approve</a>
                                                            <a class="btn btn-danger "  data-toggle="modal" data-target="#declineModal" title="Decline" href="#">Decline</a>
                                                        </div>
                                                        <div class="nk-block-head-content align-self-start d-lg-none">
                                                            <a href="#" class="toggle btn btn-icon btn-trigger" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                                        </div>
                                                    </div>
                                                @elseif(isset($data['appSettings'][4]->value) && $data['appSettings'][4]->value == auth()->id() && isset($data['contract'][0]->status) && $data['contract'][0]->status == 'Approved Level 1' )
                                                    <div class="d-flex align-center">
                                                        <div class="nk-tab-actions me-n1">
                                                            <a class="btn  btn-primary " title="Approve" id="btn-approve" href="#">Approve</a>
                                                            <a class="btn btn-danger "  data-toggle="modal" data-target="#declineModal" title="Decline" href="#">Decline</a>
                                                        </div>
                                                        <div class="nk-block-head-content align-self-start d-lg-none">
                                                            <a href="#" class="toggle btn btn-icon btn-trigger" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                                        </div>
                                                    </div>
                                                @elseif(isset($data['appSettings'][5]->value) && $data['appSettings'][5]->value == auth()->id() && isset($data['contract'][0]->status) && $data['contract'][0]->status == 'Approved Level 2' )
                                                    <div class="d-flex align-center">
                                                        <div class="nk-tab-actions me-n1">
                                                            <a class="btn  btn-primary " title="Approve" id="btn-approve" href="#">Approve</a>
                                                            <a class="btn btn-danger "  data-toggle="modal" data-target="#declineModal" title="Decline" href="#">Decline</a>
                                                        </div>
                                                        <div class="nk-block-head-content align-self-start d-lg-none">
                                                            <a href="#" class="toggle btn btn-icon btn-trigger" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endisset

                                        </div>
                                    </div><!-- .nk-block-head -->
                                    <div class="nk-block">
                                        <form method="post" id="ContentForm">
                                            @csrf
                                            <input type="hidden" name="id" value="{{$data['contract'][0]->id ?? ''}}">
                                            <div class="row">
                                                <div class="col-md-11">
                                                    <div class="form-group">
                                                        <label>Select Template<span class="text-danger"> * </span></label>
                                                        <select name="contract_temp_id" id="temp_id" class="form-control select2" data-live-search="true" required>
                                                            <option value="">Choose One</option>
                                                            @isset($data['contract_template'])
                                                                @foreach( $data['contract_template'] as $template)
                                                                    <option value="{{$template->id}}" @isset($data['contract'][0]->contractTemplate->id) {{(($template->id == $data['contract'][0]->contractTemplate->id)? 'selected':'') }} @endisset >{{$template->temp_title}}</option>
                                                                @endforeach
                                                            @endisset
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-11">
                                                    <div class="form-group">
                                                        <label for="">Use these strings in template:</label>
                                                        <p> @verbatim{{company_name}} - {{phone}} - {{address}} - {{city}} - {{country}} - {{unit_no}}  - {{contact.first_name}} {{contact.last_name}} - {{contact.phone}} - {{contact.phone}} -{{contact.email}} - {{storage_fee}} - {{addon_fee}} @endverbatim</p>
                                                        <label>Contract Content<span class="text-danger"></span></label>
                                                        <div id="toolbar-container"></div>
                                                        <div id="editor">
                                                            @isset($data['contract'][0]->contractTemplate->temp_body)
                                                                {!! $data['contract'][0]->contractTemplate->temp_body !!}
                                                            @endisset
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="float-right">
                                                <button class="btn btn-primary mt-2 btn-submit" type="submit">Save</button>
                                            </div>
                                        </form>
                                    </div><!-- .nk-block -->
                                </div>
                                <div class="modal fade" tabindex="-1" role="dialog" id="declineModal" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title text-capitalize" id="ajax_model_title">Decline Estimate</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body" data-form="ajax-form-modal">
                                                <form method="post" action="{{ url('admin/decline-contract') }}" id="DeclineForm">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <input class="form-control" type="hidden" name="type_id" value="{{$data['contract'][0]->id ?? ''}}" required>
                                                                <input class="form-control" type="hidden" name="type" value="contract" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="decline_reason"> Decline Reason Note <span class="text-danger"></span></label>
                                                                <textarea name="decline_reason" id="decline_reason" class="form-control" placeholder="Enter contract decline reason note...."></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="float-right">
                                                        <button class="btn btn-primary mt-2 btn-submit" type="submit">Save</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div><!-- .modal-content -->
                                    </div><!-- .modla-dialog -->
                                </div>
                                @include('backend.contract.aside')
                                <!-- card-aside -->
                            </div>
                            <!-- .card-aside-wrap -->
                        </div>
                    @else
                        <div class="alert alert-danger">Contract data not found</div>
                    @endisset
                    <!-- .card -->
                </div>
                <!-- .nk-block -->
                <!-- Modal -->
                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="myModalLabel">Template Changes</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" name="temp_change" type="radio" value="new"   id="flexCheckDefaultnew"  data-validation="required"  />
                                            <label class="check-container" for="flexCheckDefaultnew">Create New Template </label>
                                            <input type="text" class="form-control"  name="temp_title"  id="temp_title" style="height:35px;">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" name="temp_change" type="radio" value="update"   id="flexCheckDefaultupdate"  data-validation="required" />
                                            <label class="check-container" for="flexCheckDefaultupdate">Update this template</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary btn-change">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var myeditor = '';
        var temp_data = '';
        var temp_title = '';
        var editor_content = '';
        var temp_id = '';

        @isset($data['contract'][0]->contractTemplate->temp_body)
            temp_data = {!! json_encode($data['contract'][0]->contractTemplate->temp_body) !!};
        editor_content = {!! json_encode($data['contract'][0]->contractTemplate->temp_body) !!};
        temp_id = {{ $data['contract'][0]->contractTemplate->id ?? 'null' }};
        @endisset

        DecoupledEditor
            .create(document.querySelector('#editor'))
            .then(editor => {
                const toolbarContainer = document.querySelector('#toolbar-container');
                if (toolbarContainer) {
                    toolbarContainer.appendChild(editor.ui.view.toolbar.element);
                }
                myeditor = editor;
                editor_content = editor.getData();
            })
            .catch(error => {
                console.error(error);
            });
    </script>
    <script>
        $(document).ready(function() {
            var id = $('#temp_id').find(":selected").val();

            $('#temp_id').change(function() {
                var id = $('#temp_id').val();
                if (!id) return;

                $.ajax({
                    url: '{{ url('admin/get-contract-template') }}',
                    type: 'get',
                    dataType: 'json',
                    data: { id: id },
                    success: function(data) {
                        if (data && data.length > 0) {
                            temp_id = data[0].id || '';
                            temp_title = data[0].temp_title || '';
                            temp_data = data[0].temp_body || '';
                            if (myeditor) {
                                myeditor.setData(data[0].temp_body || '');
                            }
                        }
                    },
                    error: function() {
                        toastr.error('Something went wrong');
                    }
                });
            });

            $('#ContentForm').on('submit', function(e) {
                e.preventDefault();
                if (!myeditor) return;

                const editor_data = myeditor.getData();
                var formData = $('#ContentForm').serialize();

                if (temp_data === editor_data) {
                    $.ajax({
                        url: '{{ url('admin/update-contract-id') }}',
                        type: 'get',
                        dataType: 'json',
                        data: formData,
                        success: function(data) {
                            if (data && data.success) {
                                $('.close').click();
                                toastr.success(data.success);
                            } else if (data && data.errors) {
                                toastr.error(data.errors);
                            }
                        },
                        error: function() {
                            toastr.error('Technical error occurred');
                        }
                    });
                } else {
                    $('#temp_title').val(temp_title || '');
                    $('#myModal').modal('show');
                }
            });

            $(".btn-change").click(function() {
                var radioGroup = $('input[name="temp_change"]:checked');
                if (radioGroup.length === 0) {
                    alert('Please select an option');
                    return;
                }

                if (!myeditor) return;

                const editor_data = myeditor.getData();
                var changetype = $('input[name="temp_change"]:checked').val();
                var newtitle = $('#temp_title').val();

                if (changetype == 'new' && newtitle) {
                    insertNew(newtitle, editor_data);
                } else if (changetype == 'update' && temp_id) {
                    update(temp_id, temp_title, editor_data);
                }
            });

            function insertNew(title, body) {
                $.ajax({
                    url: '{{ url('admin/save-contract-template') }}',
                    type: 'get',
                    dataType: 'json',
                    data: { temp_title: title, temp_body: body, status: 1 },
                    success: function(data) {
                        if (data && data.success) {
                            $('.close').click();
                            toastr.success(data.success);
                        } else if (data && data.errors) {
                            toastr.error(data.errors);
                        }
                    },
                    error: function() {
                        toastr.error('Technical error occurred');
                    }
                });
            }

            function update(id, title, body) {
                $.ajax({
                    url: '{{ url('admin/update-contract-template') }}',
                    type: 'get',
                    dataType: 'json',
                    data: { id: id, temp_title: title, temp_body: body, status: 1 },
                    success: function(data) {
                        if (data && data.success) {
                            $('.close').click();
                            toastr.success(data.success);
                        } else if (data && data.errors) {
                            toastr.error(data.errors);
                        }
                    },
                    error: function() {
                        toastr.error('Technical error occurred');
                    }
                });
            }
        });
    </script>
    <script>
        $('#DeclineForm').on('submit', function(e) {
            e.preventDefault();
            var formData = $('#DeclineForm').serialize();

            $.ajax({
                type: "get",
                url: '{{ url('admin/decline-contract') }}',
                data: formData,
                beforeSend: function() {
                    $('.btn-submit').text('Saving...');
                    $(".btn-submit").prop("disabled", true);
                },
                success: function(data) {
                    if (data && data.success) {
                        $('#DeclineForm')[0].reset();
                        $('.close').click();
                        toastr.success(data.success);
                    } else if (data && data.errors) {
                        toastr.error(data.errors);
                    }
                },
                complete: function() {
                    $(".btn-submit").html("Save");
                    $(".btn-submit").prop("disabled", false);
                },
                error: function() {
                    toastr.error('Technical error occurred');
                    $('.btn-submit').text('Save');
                    $(".btn-submit").prop("disabled", false);
                }
            });
        });

        $('#btn-approve').on('click', function(e) {
            e.preventDefault();
            var id = $('input[name="id"]').val();
            if (!id) return;

            $.ajax({
                url: '{{ url('admin/approve-contract') }}',
                type: 'get',
                dataType: 'json',
                data: {id: id},
                success: function(data) {
                    if (data && data.success) {
                        toastr.success(data.success);
                        window.location.reload();
                    } else if (data && data.error) {
                        toastr.error(data.error);
                    }
                },
                error: function() {
                    toastr.error('Something went wrong');
                }
            });
        });
    </script>
@endsection