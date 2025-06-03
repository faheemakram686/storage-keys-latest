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
                                        <input type="hidden" name="customer_id" id="customer_id" value="{{$data['customer']->id}}">
                                    </div>
                                    <div class="user-action">
                                        <div class="dropdown">
                                            <a class="btn btn-icon btn-trigger me-n2" data-toggle="dropdown" href="#"><em class="icon ni ni-more-v"></em></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <ul class="link-list-opt no-bdr">
                                                    <li><a href="#"><em class="icon ni ni-camera-fill"></em><span>Change Photo</span></a></li>
                                                    <li><a href="#"><em class="icon ni ni-edit-fill"></em><span>Update Profile</span></a></li>
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
                                    <li><a class="{{ Route::is('customer-tasks') ? 'active' : '' }}" href="{{url('admin/customer/tasks/'.$data['customer']->id)}}"><em class="icon ni ni-activity-round-fill"></em><span>Tasks</span></a></li>
                                    <li><a class="{{ Route::is('customer-attachments') ? 'active' : '' }}" href="{{url('admin/customer/attachments/'.$data['customer']->id)}}"><em class="icon ni ni-activity-round-fill"></em><span>Attachments</span></a></li>
                                    <li><a class="{{ Route::is('customer-reminders') ? 'active' : '' }}" href="{{url('admin/customer/reminders/'.$data['customer']->id)}}"><em class="icon ni ni-activity-round-fill"></em><span>Reminders</span></a>
                                    </li><li><a href="#"><em class="icon ni ni-activity-round-fill"></em><span>Notes</span></a>
                                    </li><li><a href="#"><em class="icon ni ni-activity-round-fill"></em><span>Activity Logs</span></a>

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