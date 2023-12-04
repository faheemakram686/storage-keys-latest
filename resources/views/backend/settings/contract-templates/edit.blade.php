@extends('backend.layouts.app')
@section('title', '| Edit Contract Template')
@section('content')
    <div class="components-preview wide-md mx-auto">
        <div class="nk-block nk-block-lg">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h4 class="title nk-block-title">Edit Contract Template</h4>
                    </div>
                    <a href="{{url("admin/contract-template")}}" class="btn btn-primary btn-sm d-none d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>Back</span></a>
                </div>
            </div>
            <div class="card">
                <div class="card-inner">
                    <form  action="{{url("admin/update-contract-template")}}"  method="post"  id="updateCountryForm">
                        @csrf
                        @isset($data)
                            <input type="hidden" name="id" value="{{$data->id}}">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Template Title<span class="text-danger"> * </span></label>
                                        <input class="form-control " type="text" value="{{$data->temp_title}}" name="temp_title" placeholder="Enter Title" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Use these strings in template:</label>
                                        <p> @verbatim{{company_name}} - {{phone}} - {{address}} - {{city}} - {{country}} - {{unit_no}}  - {{contact.first_name}} {{contact.last_name}} - {{contact.phone}} - {{contact.phone}} -{{contact.email}} - {{storage_fee}} - {{addon_fee}} @endverbatim</p>
                                        <label>Template Content<span class="text-danger"></span></label>
                                        <div id="toolbar-container"></div>
                                        <div id="editor">
                                            @isset($data->temp_body) {!! $data->temp_body  !!}  @endisset
                                        </div>
{{--                                        <textarea class="summernote-basic"   id="editor" name="temp_body"> {{$data->temp_body}}</textarea>--}}
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Status <span class="text-danger"></span></label>
                                        <select name="status" id="status" class="form-control" required>
                                            <option value="">Choose One</option>
                                            <option value="1" {{$data->status == 'Active' ? 'selected' : ''}}>Active</option>
                                            <option value="0" {{$data->status == 'In-Active' ? 'selected' : ''}}>In-Active</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="float-right">
                                <button class="btn btn-primary mt-2 btn-update" type="submit">Save Changes</button>
                            </div>
                        @endisset
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function() {

            var myeditor ='';
            var temp_data = '';
            var temp_title = '';
            var temp_id = '';
            DecoupledEditor
                .create( document.querySelector( '#editor' ), {

                })
                .then( editor => {
                    const toolbarContainer = document.querySelector( '#toolbar-container' )
                    toolbarContainer.appendChild( editor.ui.view.toolbar.element );
                    myeditor=editor;
                } )
                .catch( error => {
                    console.error( error );
                } );

            $('#updateCountryForm').on('submit', function(e) {
                e.preventDefault();

                var body = myeditor.getData();
                var newtitle =$("input[name=temp_title]").val();
                var id =$("input[name=id]").val();
                var status= $('#status').find(":selected").val();

                $.ajax({
                    url: '{{ url('admin/update-contract-template') }}',
                    type: 'get',
                    async: false,
                    dataType: 'json',
                    data: { id:id,temp_title:newtitle,temp_body:body,status:status},
                    beforeSend: function() {
                        $('.btn-update').text('loading...');
                        $(".btn-update").prop("disabled", true);
                    },
                    success: function(data) {

                        if (data.success) {
                            $('#updateCountryForm')[0].reset();
                            $('.close').click();
                            toastr.success(data.success);
                            window.location.href = "{{ route('contract-template.index')}}";
                        }
                        if (data.errors) {
                            toastr.error(data.errors);
                            $('.btn-update').text('Save Changes');
                            $(".btn-update").prop("disabled", false);
                        }
                    },
                    complete: function(data) {
                        $(".btn-update").html("Save Changes");
                        $(".btn-update").prop("disabled", false);

                    },
                    error: function() {;
                        toastr.error('any technical error');
                        $('.btn-update').text('Save Changes');
                        $(".btn-update").prop("disabled", false);
                    }
                });


            });

        });
    </script>

@endsection



