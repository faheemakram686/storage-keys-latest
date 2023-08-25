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
                                        <span class="lead-text">{{$data['lead'][0]->f_name}} {{$data['lead'][0]->l_name}}</span>
                                        <input type="hidden" name="lead_id" id="lead_id" value="{{$data['lead'][0]->id}}">
                                    </div>
                                    <div class="user-action">
                                        <div class="dropdown">
                                            <a class="btn btn-icon btn-trigger me-n2" data-toggle="dropdown" href="#"><em class="icon ni ni-more-v"></em></a>
                                            <div class="dropdown-menu dropdown-menu-right" >
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
                                    <li><a class="{{ Route::is('lead-profile') ? 'active' : '' }}" href="{{url('admin/lead/profile/'.$data['lead'][0]->id)}}"><em class="icon ni ni-user-fill-c"></em><span>Profile</span></a></li>
                                    <li><a class="{{ Route::is('lead-tasks') ? 'active' : '' }}" href="{{url('admin/lead/tasks/'.$data['lead'][0]->id)}}"><em class="icon ni ni-users-fill"></em><span>Tasks</span></a></li>
                                    <li><a class="{{ Route::is('lead-attachments') ? 'active' : '' }}" href="{{url('admin/lead/attachments/'.$data['lead'][0]->id)}}"><em class="icon ni ni-activity-round-fill"></em><span>Attachments</span></a></li>
                                    <li><a class="{{ Route::is('lead-reminders') ? 'active' : '' }}" href="{{url('admin/lead/reminders/'.$data['lead'][0]->id)}}"><em class="icon ni ni-activity-round-fill"></em><span>Reminders</span></a>
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