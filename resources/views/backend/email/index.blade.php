@extends('backend.layouts.app')
@section('title', '| Emails')
@section('content')
    <div class="components-preview wide-md mx-auto">
        <div class="nk-block nk-block-lg">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Email List</h3>
                        <div class="nk-block-des text-soft">
                            <h5>{{ LaravelGmail::user() }}</h5>
                            @if(LaravelGmail::check())
{{--                                    @dd($data);--}}
                                <a href="{{ url('oauth/gmail/logout') }}">logout</a>
                            @else
                                <a href="{{ url('oauth/gmail') }}">login</a>
                            @endif
                        </div>
                    </div>
                    <div class="nk-block-head-content">
                        <div class="toggle-wrap nk-block-tools-toggle">
                            <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                            <div class="toggle-expand-content" data-content="pageMenu">
                                <ul class="nk-block-tools g-3">
                                    <li class="nk-block-tools-opt">
                                        <a href="#" class="btn btn-primary btn-sm"  data-toggle="modal" data-target="#addCountry"><em class="icon ni ni-plus"></em><span>New Email</span></a>
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
                            <th class="nk-tb-col text-left"><span class="sub-text">ID</span></th>
                            <th class="nk-tb-col"><span class="sub-text">From</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Subject</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Body Extract</span></th>
                            <th class="nk-tb-col"><span class="sub-text">Created Date</span></th>
                            <th class="nk-tb-col tb-col-mb text-right"><span class="sub-text">Actions</span></th>
                        </tr>
                        </thead>
                        <tbody id="countryTable">
                        @isset($data)
                        @foreach($data['emails']  as $key => $header)
{{--                            @dd($header['date']);--}}
                        <tr class="nk-tb-item odd">
                            <td class="nk-tb-col nk-tb-col-tools">{{$key+1}}</td>
                            <td class="nk-tb-col nk-tb-col-tools">{{$header['from']['name']}}</td>
                            <td class="nk-tb-col nk-tb-col-tools"><a href="{{url('admin/get-email-detail')}}/{{$header['id']}}">{{$header['subject']}}</a></td>
                            <td class="nk-tb-col nk-tb-col-tools">{{strip_tags(Illuminate\Support\Str::words($header['body'],20))}}</td>
                            <td class="nk-tb-col nk-tb-col-tools">{{$header['date']}}</td>
                            <td class="nk-tb-col nk-tb-col-tools">
                                 <ul class="nk-tb-actions gx-1">
                                    <li>
                                        <div class="drodown">
                                            <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <ul class="link-list-opt no-bdr">
                                                    <li><a href="#" class="btn-edit" data='' data-toggle="modal" data-target="#editCountry"><em class="icon ni ni-edit"></em><span>Replay</span></a></li>
                                                    <li><a href="#" class="btn-delete" data=''><em class="icon ni ni-trash"></em><span>Delete</span></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                 </ul>
                            </td>
                        </tr>
                        @endforeach
                            @endisset
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- .card-preview -->
        </div>
        <!-- nk-block -->
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-capitalize" id="ajax_model_title">Add New Insurance</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" data-form="ajax-form-modal">
                    <div class="card">
                        <div class="card-inner">
                            <form action="#" class="nk-wizard nk-wizard-simple is-alter" id="wizard-01">
                                <div class="nk-wizard-head">
                                    <h5>Step 1</h5>
                                </div>
                                <div class="nk-wizard-content">
                                    <div class="row gy-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="fw-first-name">First Name</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" data-msg="Required" class="form-control required" id="fw-first-name" name="fw-first-name" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="fw-last-name">Last Name</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" data-msg="Required" class="form-control required" id="fw-last-name" name="fw-last-name" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="fw-email-address">Email Address</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" data-msg="Required" data-msg-email="Wrong Email" class="form-control required email" id="fw-email-address" name="fw-email-address" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="fw-mobile-number">Mobile Number</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" data-msg="Required" class="form-control required" id="fw-mobile-number" name="fw-mobile-number" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="fw-nationality">Country</label>
                                                <div class="form-control-wrap ">
                                                    <div class="form-control-select">
                                                        <select class="form-control required" data-msg="Required" id="fw-nationality" name="fw-nationality" required>
                                                            <option value="">Select Country</option>
                                                            <option value="us">United State</option>
                                                            <option value="uk">United KingDom</option>
                                                            <option value="fr">France</option>
                                                            <option value="ch">China</option>
                                                            <option value="cr">Czech Republic</option>
                                                            <option value="cb">Colombia</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- .col -->
                                    </div>
                                </div>
                                <div class="nk-wizard-head">
                                    <h5>Step 2</h5>
                                </div>
                                <div class="nk-wizard-content">
                                    <div class="row gy-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="fw-username">Username</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" data-msg="Required" class="form-control required" id="fw-username" name="fw-username" required>
                                                </div>
                                            </div>
                                        </div><!-- .col -->
                                    </div><!-- .row -->
                                    <div class="row gy-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="fw-password">Password</label>
                                                <div class="form-control-wrap">
                                                    <input type="password" data-msg="Required" class="form-control required" id="fw-password" name="fw-password" required>
                                                </div>
                                            </div>
                                        </div><!-- .col -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="fw-re-password">Re-Password</label>
                                                <div class="form-control-wrap">
                                                    <input type="password" data-msg="Required" class="form-control required" id="fw-re-password" name="fw-re-password" required>
                                                </div>
                                            </div>
                                        </div><!-- .col -->
                                        <div class="col-md-12">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" data-msg="Required" class="custom-control-input required" name="fw-policy" id="fw-policy" required>
                                                <label class="custom-control-label" for="fw-policy">I agreed Terms and policy</label>
                                            </div>
                                        </div>
                                    </div><!-- .row -->
                                </div>
                                <div class="nk-wizard-head">
                                    <h5>Step 3</h5>
                                </div>
                                <div class="nk-wizard-content">
                                    <div class="row gy-2">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label" for="fw-token-address">Token Address</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" data-msg="Required" class="form-control required" id="fw-token-address" name="fw-token-address" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">I want to contribute</label>
                                            <ul class="d-flex flex-wrap g-2">
                                                <li>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" data-msg="Required" class="custom-control-input required" name="fw-ethcount" id="fw-lt1eth" required>
                                                        <label class="custom-control-label" for="fw-lt1eth">Less than 1 ETH</label>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" data-msg="Required" class="custom-control-input required" name="fw-ethcount" id="fw-ov1eth" required>
                                                        <label class="custom-control-label" for="fw-ov1eth">Over than 1 ETH</label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="fw-telegram-username">Telegram Username</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" data-msg="Required" class="form-control required" id="fw-telegram-username" name="fw-telegram-username" required>
                                                </div>
                                            </div>
                                        </div><!-- .col -->
                                    </div><!-- .row -->
                                </div>
                            </form>
                        </div>
                    </div>


                </div>
            </div><!-- .modal-content -->
        </div><!-- .modla-dialog -->
    </div>


    <div class="modal fade" role="dialog" id="addCountry" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Compose Message</h6>
                    <button href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></button>
                </div>
                <form method="post" action="{{ url('admin/send-email') }}" id="SendEmail" enctype="multipart/form-data" >
                    @csrf
                <div class="modal-body p-0">
                    <div class="nk-reply-form-header">
                        <div class="nk-reply-form-group">
                            <div class="nk-reply-form-input-group">

                                <div class="nk-reply-form-input nk-reply-form-input-to">
                                    <label class="label">To</label>
                                    <input type="text"  name="to" class="input-mail tagify" placeholder="Recipient" data-whitelist="team@softnio.com, help@softnio.com, contact@softnio.com" required>
                                </div>
                                <div class="nk-reply-form-input nk-reply-form-input-cc" data-content="mail-cc">
                                    <label class="label">Cc</label>
                                    <input type="text" name="cc" class="input-mail tagify" required>
                                    <a href="#" class="toggle-opt" data-target="mail-cc"><em class="icon ni ni-cross"></em></a>
                                </div>
                                <div class="nk-reply-form-input nk-reply-form-input-bcc" data-content="mail-bcc">
                                    <label class="label">Bcc</label>
                                    <input type="text" name="bcc" class="input-mail tagify" required>
                                    <a href="#" class="toggle-opt" data-target="mail-bcc"><em class="icon ni ni-cross"></em></a>
                                </div>
                            </div>
                            <ul class="nk-reply-form-nav">
                                <li><a tabindex="-1" class="toggle-opt" data-target="mail-cc" href="#">CC</a></li>
                                <li><a tabindex="-1" class="toggle-opt" data-target="mail-bcc" href="#">BCC</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="nk-reply-form-editor">
                        <div class="nk-reply-form-field">
                            <input type="text" name="subject" class="form-control form-control-simple" placeholder="Subject" required>
                        </div>
                        <div class="nk-reply-form-field">
                            <textarea name="messege" class="form-control form-control-simple no-resize ex-large" placeholder="Hello" required></textarea>
                        </div>
                    </div><!-- .nk-reply-form-editor -->
                    <div class="nk-reply-form-tools">
                        <ul class="nk-reply-form-actions g-1">
                            <li class="me-2"><button class="btn btn-primary" type="submit">Send</button></li>
                            <li>
                                <div class="dropdown">
                                    <a class="btn btn-icon btn-sm" data-bs-toggle="dropdown" href="#"><em class="icon ni ni-hash" data-bs-toggle="tooltip" data-bs-placement="top" title="Template"></em></a>
                                    <div class="dropdown-menu">
                                        <ul class="link-list-opt no-bdr link-list-template">
                                            <li class="opt-head"><span>Quick Insert</span></li>
                                            <li><a href="#"><span>Thank you message</span></a></li>
                                            <li><a href="#"><span>Your issues solved</span></a></li>
                                            <li><a href="#"><span>Thank you message</span></a></li>
                                            <li class="divider">
                                            <li><a href="#"><em class="icon ni ni-file-plus"></em><span>Save as Template</span></a></li>
                                            <li><a href="#"><em class="icon ni ni-notes-alt"></em><span>Manage Template</span></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <a  id="logo" class="btn btn-icon btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Upload Attachment" href="#"><em class="icon ni ni-clip-v"></em></a>
                                <input id="logo-input" preview="#logo" type="file" class="d-none" name="attachment" >
                            </li>
                            <li class="d-none d-sm-block">
                                <a class="btn btn-icon btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Insert Emoji" href="#"><em class="icon ni ni-happy"></em></a>
                            </li>
                            <li class="d-none d-sm-block">
                                <a class="btn btn-icon btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Upload Images" href="#"><em class="icon ni ni-img"></em></a>
                            </li>
                        </ul>
                        <ul class="nk-reply-form-actions g-1">
                            <li>
                                <div class="dropdown">
                                    <a href="#" class="dropdown-toggle btn-trigger btn btn-icon" data-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <ul class="link-list-opt no-bdr">
                                            <li><a href="#"><span>Another Option</span></a></li>
                                            <li><a href="#"><span>More Option</span></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <a href="#" class="btn-trigger btn btn-icon me-n2"><em class="icon ni ni-trash"></em></a>
                            </li>
                        </ul>
                    </div><!-- .nk-reply-form-tools -->
                </div><!-- .modal-body -->
                </form>
            </div><!-- .modal-content -->
        </div><!-- .modla-dialog -->
    </div><!-- .modal -->

    <div class="modal fade" tabindex="-1" role="dialog" id="editCountry" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-capitalize" id="ajax_model_title">Edit Insurance</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" data-form="ajax-form-modal">
                    <form method="post" action="{{ url('admin/send-email') }}" id="updateCountryForm">
                        @csrf
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label> Name <span class="text-danger"></span></label>
                                    <div class="form-control-wrap">
                                        <input type="hidden" name="id">
                                        <input class="form-control" type="text" name="e_name" placeholder="Name" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="status">Monthly Amount</label>
                                    <div class="form-control-wrap">
                                        <input class="form-control" type="number" name="e_monthly_amount" placeholder="Monthly Amount" required>
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="status">Cover</label>
                                    <div class="form-control-wrap">
                                        <input class="form-control" type="text" name="e_cover" placeholder="Cover" required>
                                    </div>

                                </div>
                            </div>



                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="status">Status</label>
                                    <select class="form-control e" id="status" name="e_status" required=>
                                        <option value="1" data-select2-id="39">Active</option>
                                        <option value="0">In-Active</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="float-right">
                            <button class="btn btn-primary mt-2 btn-update" type="submit">Save Changes</button>
                        </div>
                    </form>

                </div>
            </div><!-- .modal-content -->
        </div><!-- .modla-dialog -->
    </div>

    <script>
        $(document).ready(function() {

            $('#logo').click(function(){
                $('#logo-input').trigger('click');
            });

            $('#SendEmail').on('submit', function(e) {
                e.preventDefault();
                var formData=$('#SendEmail').serialize()
                $.ajax({
                    type: "get",
                    url: '{{ url('admin/send-email') }}',
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('.btn-submit').text('Saving...');
                        $(".btn-submit").prop("disabled", true);
                    },
                    success: function(data) {

                        if (data.success) {
                            $('#SendEmail')[0].reset();
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
                    },

                    error: function(data) {
                        console.log(data.errors);
                        toastr.error(data.errors);
                        $('.btn-submit').text('Save');
                        $(".btn-submit").prop("disabled", false);
                    }
                });


            });


        });
    </script>
@endsection



