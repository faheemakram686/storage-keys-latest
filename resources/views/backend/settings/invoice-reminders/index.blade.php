@extends('backend.layouts.app')
@section('title', '| Invoice Reminders')
@section('content')
    <div class="components-preview wide-md mx-auto">
        <div class="nk-block nk-block-lg">
            <div class="nk-block-head">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">Reminders Based on Due Date</h3>
                </div>
            </div>
            <div class="card card-preview">
                <div class="card-inner p-0">
                    <ul class="nk-tb-list" id="reminderList"></ul>
                    <div class="p-3">
                        <a href="#" class="text-primary" data-toggle="modal" data-target="#reminderModal" id="btnNewReminder">
                            <em class="icon ni ni-plus"></em> <strong>+ New Reminder</strong>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" id="reminderModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Automated Reminders</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="reminderForm">
                        @csrf
                        <input type="hidden" name="id" value="">
                        <div class="form-group">
                            <label>Name</label>
                            <input class="form-control" type="text" name="name" required>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="isEnabled" name="is_enabled" value="1" checked>
                                <label class="custom-control-label" for="isEnabled">Enable this reminder</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Remind</label>
                            <div class="form-inline">
                                <input class="form-control mr-2" type="number" min="0" name="trigger_days" value="1" style="width:90px;" required>
                                <span class="mr-2">day(s)</span>
                                <select class="form-control mr-2" name="trigger_relation" required>
                                    <option value="before">before</option>
                                    <option value="after">after</option>
                                    <option value="on">on</option>
                                </select>
                                <span>due date</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Remind</label>
                            <select class="form-control" name="recipient_type" required>
                                <option value="customer">customer</option>
                                <option value="customer_and_copy_me">customer and copy me</option>
                                <option value="me">me</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>From Address</label>
                            <select class="form-control" name="from_user_id">
                                <option value="">Choose one</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ trim($user->first_name.' '.$user->last_name) }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                            <small class="text-soft">This email address will be used as the from address while sending.</small>
                        </div>
                        <div class="form-group">
                            <label>Cc</label>
                            <input class="form-control" type="text" name="cc_emails" placeholder="Comma-separated emails">
                        </div>
                        <div class="form-group">
                            <label>Bcc</label>
                            <input class="form-control" type="text" name="bcc_emails" placeholder="Comma-separated emails">
                        </div>
                        <div class="form-group">
                            <label>Subject</label>
                            <input class="form-control" type="text" name="subject" required>
                        </div>
                        <div class="form-group">
                            <label>Use these strings in template:</label>
                            <p class="text-soft">@verbatim{{f_name}} {{l_name}} {{company_name}} {{invoice_no}} {{invoice_date}} {{due_date}} {{grand_total}} {{amount_due}}@endverbatim</p>
                            <label class="form-label">Body</label>
                            <div id="toolbar-container"></div>
                            <div id="editor" style="min-height:180px;border:1px solid #dbdfea;padding:12px;"></div>
                        </div>
                        <div class="text-right">
                            <button class="btn btn-primary btn-submit" type="submit">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        var reminderEditor = '';
        DecoupledEditor
            .create(document.querySelector('#editor'))
            .then(function(editor) {
                document.querySelector('#toolbar-container').appendChild(editor.ui.view.toolbar.element);
                reminderEditor = editor;
            })
            .catch(function(error) {
                console.error(error);
            });

        function recipientLabel(type) {
            if (type === 'me') return 'me';
            if (type === 'customer_and_copy_me') return 'customer and copy me';
            return 'customer';
        }

        function emailsToString(value) {
            if (Array.isArray(value)) {
                return value.join(', ');
            }
            return value || '';
        }

        function loadReminders() {
            $.ajax({
                url: '{{ url('admin/get-invoice-reminders') }}',
                type: 'get',
                dataType: 'json',
                success: function(data) {
                    var html = '';
                    for (var i = 0; i < data.length; i++) {
                        var item = data[i];
                        var checked = item.is_enabled ? 'checked' : '';
                        html += '<li class="nk-tb-item">' +
                            '<div class="nk-tb-col"><a href="#" class="btn-edit text-primary" data-id="' + item.id + '">' + item.name + '</a></div>' +
                            '<div class="nk-tb-col"><span>Remind ' + recipientLabel(item.recipient_type) + ' <strong>' + item.trigger_days + ' day(s) ' + item.trigger_relation + '</strong> due date</span></div>' +
                            '<div class="nk-tb-col nk-tb-col-tools">' +
                            '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input reminder-toggle" id="rem' + item.id + '" data-id="' + item.id + '" ' + checked + '>' +
                            '<label class="custom-control-label" for="rem' + item.id + '"></label></div></div>' +
                            '<div class="nk-tb-col nk-tb-col-tools">' +
                            '<div class="dropdown"><a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>' +
                            '<div class="dropdown-menu dropdown-menu-right"><ul class="link-list-opt no-bdr">' +
                            '<li><a href="#" class="btn-edit" data-id="' + item.id + '"><em class="icon ni ni-edit"></em><span>Edit</span></a></li>' +
                            '<li><a href="#" class="btn-delete" data-id="' + item.id + '"><em class="icon ni ni-trash"></em><span>Delete</span></a></li>' +
                            '</ul></div></div></div></li>';
                    }
                    $('#reminderList').html(html);
                },
                error: function() {
                    toastr.error('something went wrong');
                }
            });
        }

        function resetReminderForm() {
            $('#reminderForm')[0].reset();
            $('input[name=id]').val('');
            $('#isEnabled').prop('checked', true);
            if (reminderEditor) {
                reminderEditor.setData('');
            }
        }

        $(document).ready(function() {
            loadReminders();

            $('#btnNewReminder').on('click', function() {
                resetReminderForm();
            });

            $('#reminderForm').on('submit', function(e) {
                e.preventDefault();
                var id = $('input[name=id]').val();
                var payload = {
                    id: id,
                    name: $('input[name=name]').val(),
                    is_enabled: $('#isEnabled').is(':checked') ? 1 : 0,
                    trigger_days: $('input[name=trigger_days]').val(),
                    trigger_relation: $('select[name=trigger_relation]').val(),
                    recipient_type: $('select[name=recipient_type]').val(),
                    from_user_id: $('select[name=from_user_id]').val(),
                    cc_emails: $('input[name=cc_emails]').val(),
                    bcc_emails: $('input[name=bcc_emails]').val(),
                    subject: $('input[name=subject]').val(),
                    body: reminderEditor ? reminderEditor.getData() : ''
                };
                var url = id ? '{{ url('admin/update-invoice-reminder') }}' : '{{ url('admin/save-invoice-reminder') }}';
                $.ajax({
                    type: 'post',
                    url: url,
                    data: payload,
                    dataType: 'json',
                    headers: { 'X-CSRF-TOKEN': $('input[name=_token]').val() },
                    beforeSend: function() {
                        $('.btn-submit').text('Saving...').prop('disabled', true);
                    },
                    success: function(data) {
                        if (data.success) {
                            loadReminders();
                            resetReminderForm();
                            $('#reminderModal').modal('hide');
                            toastr.success(data.success);
                        }
                        if (data.errors) {
                            toastr.error(data.errors);
                        }
                    },
                    complete: function() {
                        $('.btn-submit').text('Save').prop('disabled', false);
                    },
                    error: function() {
                        toastr.error('any technical error');
                    }
                });
            });

            $('#reminderList').on('click', '.btn-edit', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                $.ajax({
                    url: '{{ url('admin/edit-invoice-reminder') }}',
                    type: 'get',
                    dataType: 'json',
                    data: { id: id },
                    success: function(res) {
                        $('input[name=id]').val(res.id);
                        $('input[name=name]').val(res.name);
                        $('#isEnabled').prop('checked', !!res.is_enabled);
                        $('input[name=trigger_days]').val(res.trigger_days);
                        $('select[name=trigger_relation]').val(res.trigger_relation);
                        $('select[name=recipient_type]').val(res.recipient_type);
                        $('select[name=from_user_id]').val(res.from_user_id || '');
                        $('input[name=cc_emails]').val(emailsToString(res.cc_emails));
                        $('input[name=bcc_emails]').val(emailsToString(res.bcc_emails));
                        $('input[name=subject]').val(res.subject);
                        if (reminderEditor) {
                            reminderEditor.setData(res.body || '');
                        }
                        $('#reminderModal').modal('show');
                    },
                    error: function() {
                        toastr.error('any technical error');
                    }
                });
            });

            $('#reminderList').on('click', '.btn-delete', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                $.ajax({
                    url: '{{ url('admin/delete-invoice-reminder') }}',
                    type: 'get',
                    dataType: 'json',
                    data: { id: id },
                    success: function(data) {
                        if (data.success) {
                            loadReminders();
                            toastr.success(data.success);
                        }
                    },
                    error: function() {
                        toastr.error('something went wrong');
                    }
                });
            });

            $('#reminderList').on('change', '.reminder-toggle', function() {
                $.ajax({
                    url: '{{ url('admin/toggle-invoice-reminder') }}',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        id: $(this).data('id'),
                        is_enabled: $(this).is(':checked') ? 1 : 0,
                        _token: $('input[name=_token]').val()
                    },
                    success: function(data) {
                        if (data.success) {
                            toastr.success(data.success);
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
