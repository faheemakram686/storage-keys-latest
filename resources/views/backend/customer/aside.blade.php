<div class="card-aside card-aside-left user-aside toggle-slide toggle-slide-left toggle-break-lg toggle-screen-lg" data-content="userAside" data-toggle-screen="lg" data-toggle-overlay="true">
    <div class="card-inner-group" data-simplebar="init">
        <div class="simplebar-wrapper" style="margin: 0px;">
            <div class="simplebar-height-auto-observer-wrapper">
                <div class="simplebar-height-auto-observer"></div>
            </div>
            <div class="simplebar-mask">
                <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                    <div class="simplebar-content-wrapper" tabindex="0" role="region" aria-label="scrollable content" style="height: auto; overflow: hidden;">
                        <div class="simplebar-content" style="padding: 0px;">
                            <div class="card-inner">
                                <div class="user-card">
                                    <div class="user-avatar bg-primary">
                                        <span id="shortname">AB</span>
                                    </div>
                                    <div class="user-info">
                                        <span class="lead-text">{{(($data['customer']->company_name != null? $data['customer']->company_name:$data['customer']->customer_name))}}</span>
                                        @php
                                            $remindersOn = (int) ($data['customer']->getRawOriginal('invoice_reminders_enabled') ?? $data['customer']->invoice_reminders_enabled);
                                        @endphp
                                        <span class="badge {{ $remindersOn ? 'badge-success' : 'badge-light' }}" id="invoiceRemindersBadge">
                                            {{ $remindersOn ? 'Invoice reminders on' : 'Invoice reminders off' }}
                                        </span>
                                        <div class="custom-control custom-switch mt-2">
                                            <input type="checkbox" class="custom-control-input customer-invoice-reminders-toggle" id="asideInvoiceReminders" {{ $remindersOn ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="asideInvoiceReminders">Enable reminders</label>
                                        </div>
                                        <input type="hidden" name="customer_id" id="customer_id" value="{{$data['customer']->id}}">
                                    </div>
                                    <div class="user-action">
                                        <div class="dropdown">
                                            <a class="btn btn-icon btn-trigger me-n2" data-toggle="dropdown" href="#"><em class="icon ni ni-more-v"></em></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <ul class="link-list-opt no-bdr">
                                                    <li><a href="#"><em class="icon ni ni-camera-fill"></em><span>Change Photo</span></a></li>
                                                    <li><a href="#"><em class="icon ni ni-edit-fill"></em><span>Update Profile</span></a></li>
                                                    <li>
                                                        <a href="#" class="toggle-customer-invoice-reminders" data-enable="{{ $remindersOn ? 0 : 1 }}">
                                                            <em class="icon ni {{ $remindersOn ? 'ni-bell-off' : 'ni-bell' }}"></em>
                                                            <span>{{ $remindersOn ? 'Disable Reminders' : 'Enable Reminders' }}</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- .user-card -->
                            </div>
                            <!-- .card-inner -->
                            <!-- .card-inner -->
                            <div class="card-inner p-0">
                                <ul class="link-list-menu">
                                    <li><a class="{{ Route::is('customer-profile') ? 'active' : '' }}" href="{{url('admin/customer/profile/'.$data['customer']->id)}}"><em class="icon ni ni-user-fill-c"></em><span>Profile</span></a></li>
                                    <li><a class="{{ Route::is('customer-contacts') ? 'active' : '' }}" href="{{url('admin/customer/contacts/'.$data['customer']->id)}}"><em class="icon ni ni-users-fill"></em><span>Contacts</span></a></li>
                                    <li><a class="{{ Route::is('customer-leads') ? 'active' : '' }}" href="{{url('admin/customer/leads/'.$data['customer']->id)}}"><em class="icon ni ni-users-fill"></em><span>Leads</span></a></li>
                                    <li><a class="{{ Route::is('customer-estimates') ? 'active' : '' }}" href="{{url('admin/customer/estimates/'.$data['customer']->id)}}"><em class="icon ni ni-users-fill"></em><span>Estimates</span></a></li>
                                    <li><a class="{{ Route::is('customer-contracts') ? 'active' : '' }}" href="{{url('admin/customer/contracts/'.$data['customer']->id)}}"><em class="icon ni ni-users-fill"></em><span>Contracts</span></a></li>
                                    <li><a class="{{ Route::is('customer-invoices') ? 'active' : '' }}" href="{{url('admin/customer/invoices/'.$data['customer']->id)}}"><em class="icon ni ni-file-text-fill"></em><span>Invoices</span></a></li>
                                    <li><a class="{{ Route::is('customer-tasks') ? 'active' : '' }}" href="{{url('admin/customer/tasks/'.$data['customer']->id)}}"><em class="icon ni ni-activity-round-fill"></em><span>Tasks</span></a></li>
                                    <li><a class="{{ Route::is('customer-attachments') ? 'active' : '' }}" href="{{url('admin/customer/attachments/'.$data['customer']->id)}}"><em class="icon ni ni-activity-round-fill"></em><span>Attachments</span></a></li>
                                    <li><a class="{{ Route::is('customer-reminders') ? 'active' : '' }}" href="{{url('admin/customer/reminders/'.$data['customer']->id)}}"><em class="icon ni ni-activity-round-fill"></em><span>Reminders</span></a></li>

                                </ul>
                            </div>
                            <!-- .card-inner -->
                            <!-- .card-inner -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="simplebar-placeholder" style="width: auto; height: 604px;"></div>
        </div>
        <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
            <div class="simplebar-scrollbar simplebar-visible" style="width: 0px; display: none;"></div>
        </div>
        <div class="simplebar-track simplebar-vertical" style="visibility: hidden;">
            <div class="simplebar-scrollbar simplebar-visible" style="height: 0px; display: none;"></div>
        </div>
    </div>
    <!-- .card-inner-group -->
</div>
<script>
    (function($) {
        function csrfToken() {
            return $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}';
        }

        function syncReminderUi(enabled) {
            $('.customer-invoice-reminders-toggle').prop('checked', !!enabled);
            $('#invoiceRemindersBadge')
                .toggleClass('badge-success', !!enabled)
                .toggleClass('badge-light', !enabled)
                .text(enabled ? 'Invoice reminders on' : 'Invoice reminders off');
            $('label[for=customerInvoiceReminders]').text(enabled ? 'On' : 'Off');
            $('.toggle-customer-invoice-reminders').attr('data-enable', enabled ? 0 : 1);
            $('.toggle-customer-invoice-reminders span').text(enabled ? 'Disable Reminders' : 'Enable Reminders');
            $('.toggle-customer-invoice-reminders em').attr('class', 'icon ni ' + (enabled ? 'ni-bell-off' : 'ni-bell'));
        }

        function saveInvoiceReminders(enabled) {
            return $.ajax({
                url: '{{ url('admin/customer/toggle-invoice-reminders') }}',
                type: 'post',
                dataType: 'json',
                data: {
                    id: $('#customer_id').val(),
                    enabled: enabled ? 1 : 0,
                    _token: csrfToken()
                }
            });
        }

        $(document).off('change.invoiceReminders', '.customer-invoice-reminders-toggle')
            .on('change.invoiceReminders', '.customer-invoice-reminders-toggle', function() {
                var enabled = $(this).is(':checked') ? 1 : 0;
                saveInvoiceReminders(enabled).done(function(data) {
                    if (data.success) {
                        syncReminderUi(enabled);
                        if (typeof toastr !== 'undefined') {
                            toastr.success(enabled ? 'Invoice reminders enabled' : 'Invoice reminders disabled');
                        }
                    } else if (data.errors && typeof toastr !== 'undefined') {
                        toastr.error(data.errors);
                    }
                }).fail(function() {
                    $('.customer-invoice-reminders-toggle').prop('checked', !enabled);
                    if (typeof toastr !== 'undefined') {
                        toastr.error('Something went wrong');
                    }
                });
            });

        $(document).off('click.invoiceReminders', '.toggle-customer-invoice-reminders')
            .on('click.invoiceReminders', '.toggle-customer-invoice-reminders', function(e) {
                e.preventDefault();
                var enabled = parseInt($(this).attr('data-enable'), 10) ? 1 : 0;
                saveInvoiceReminders(enabled).done(function(data) {
                    if (data.success) {
                        syncReminderUi(enabled);
                        if (typeof toastr !== 'undefined') {
                            toastr.success(enabled ? 'Invoice reminders enabled' : 'Invoice reminders disabled');
                        }
                    }
                }).fail(function() {
                    if (typeof toastr !== 'undefined') {
                        toastr.error('Something went wrong');
                    }
                });
            });
    })(jQuery);
</script>