@extends('backend.layouts.app')
@section('title', '| Blogs')
@section('content')
    <div class="components-preview wide-md mx-auto">
        <div class="nk-block nk-block-lg">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h4 class="title nk-block-title">Create New Blog</h4>
                    </div>
                    <a href="{{url("admin/blog")}}" class="btn btn-primary btn-sm d-none d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>Back</span></a>
                </div>
            </div>
            <div class="card">
                <div class="card-inner">
                    <form class="gy-3 form-validate is-alter" action="{{url("admin/save-blog")}}"  method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="reviewer">Title</label>
                                    <div class="form-control-wrap">
                                        <input id="blog-title" type="text" class="form-control" name="title" placeholder="Title" value="" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="reviewer">Featured Image</label>
                                    <div class="form-control-wrap">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="featured_image" name="file">
                                            <label class="custom-file-label" for="featured_image">Choose file</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-label" for="reviewer">Description Content</label>
                                    <div class="form-control-wrap">
                                        <textarea class="summernote-basic form-control"  id="editor" name="description"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
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
                                    <button type="submit" class="btn btn-md btn-primary" data-button="submit">Save</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        ClassicEditor
            .create( document.querySelector( '#editor' ) )
            .catch( error => {
                console.error( error );
            } );
    </script>
@endsection



