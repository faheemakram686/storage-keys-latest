@php
    $permissions_array = \App\Models\Core\Auth\Permission::get()->pluck('name')->toArray();
    $userrole = \Illuminate\Support\Facades\Auth::user()->roles;

    foreach ($userrole as $roleaccess)
        {
            $role = \App\Models\Core\Auth\Role::findByName($roleaccess->name);
        }

@endphp
<!-- sidebar @s -->
<div class="nk-sidebar nk-sidebar-fixed is-light " data-content="sidebarMenu">
    <div class="nk-sidebar-element nk-sidebar-head">
        <div class="nk-sidebar-brand">
            <a href="/admin" class="logo-link nk-sidebar-logo">
                <img class="logo-light logo-img" src="{{ asset('sk-assets/assets/images/frontend/front-logo.png') }}" srcset="{{ asset('assets/images/frontend/front-logo.png') }} 2x" alt="logo">
                <img class="logo-dark logo-img" src="{{ asset('sk-assets/assets/images/frontend/front-logo.png') }}" srcset="{{ asset('assets/images/frontend/front-logo.png') }} 2x"
                    alt="logo-dark">
                <img class="logo-small logo-img logo-img-small d-none" src="{{ asset('sk-assets/assets/images/frontend/favicon.png') }}"
                    srcset="{{ asset('sk-assets/assets/images/frontend/favicon.png') }} 2x" alt="logo-small">
            </a>
        </div>
        <div class="nk-menu-trigger mr-n2">
            <a href="#" class="nk-nav-toggle nk-quick-nav-icon d-xl-none" data-target="sidebarMenu">
                <em class="icon ni ni-arrow-left"></em>
            </a>
            <a href="#" class="nk-nav-compact nk-quick-nav-icon d-none d-xl-inline-flex" data-target="sidebarMenu">
                <em class="icon ni ni-menu"></em>
            </a>
        </div>
    </div><!-- .nk-sidebar-element -->
    <div class="nk-sidebar-element">
        <div class="nk-sidebar-content">
            <div class="nk-sidebar-menu" data-simplebar>
                <ul class="nk-menu">
                    <li class="nk-menu-heading">
                        <h6 class="overline-title text-primary-alt">Dashboard</h6>
                    </li>
                    <li class="nk-menu-item">
                        <a href="{{ route('admin.index') }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-home-fill"></em></span>
                            <span class="nk-menu-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="nk-menu-item">
                        <a href="{{ url('dashboard') }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-home-fill"></em></span>
                            <span class="nk-menu-text">HRM</span>
                        </a>
                    </li>
                    <li class="nk-menu-item">
                        <a href="{{ route('email.index') }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-mail-fill"></em></span>
                            <span class="nk-menu-text">Emails</span>
                        </a>
                    </li>
                    @if($role->hasPermission('view_customer'))
                        <li class="nk-menu-item">
                            <a href="{{route('customer.index')}}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-users-fill"></em></span>
                                <span class="nk-menu-text">Customers</span>
                            </a>
                        </li>
                        <li class="nk-menu-item">
                            <a href="{{route('order.index')}}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-cart-fill"></em></span>
                                <span class="nk-menu-text">Orders</span>
                            </a>
                        </li>
                    @endif
                    @if( $role->hasPermission('view_lead','view_estimate','view_contract','view_invoice'))
                        <li class="nk-menu-heading">
                            <h6 class="overline-title text-primary-alt">CRM</h6>
                        </li>
                        <li class="nk-menu-item has-sub">
                            <a href="#" class="nk-menu-link nk-menu-toggle">
                                <span class="nk-menu-icon"><em class="icon ni ni-map"></em></span>
                                <span class="nk-menu-text">CRM</span>
                            </a>
                            <ul class="nk-menu-sub">
                                @if( $role->hasPermission('view_lead'))
                                <li class="nk-menu-item">
                                    <a href="{{ route('leads.index') }}" class="nk-menu-link"><span class="nk-menu-text">Leads</span></a>
                                </li>
                                @endif
                                    @if($role->hasPermission('view_estimate'))
                                <li class="nk-menu-item">
                                    <a href="{{ route('estimate.index') }}" class="nk-menu-link"><span class="nk-menu-text">Estimates</span></a>
                                </li>
                                    @endif
                                    @if($role->hasPermission('view_contract'))
                                <li class="nk-menu-item">
                                    <a href="{{ route('contract.index') }}" class="nk-menu-link"><span class="nk-menu-text">Contracts</span></a>
                                </li>
                                    @endif
                                    @if($role->hasPermission('view_invoice'))
                                <li class="nk-menu-item">
                                    <a href="{{ route('invoice.index') }}" class="nk-menu-link"><span class="nk-menu-text">Invoices</span></a>
                                </li>
                                    @endif
                            </ul>
                        </li>
                    @endif
                    @if($role->hasPermission('view_move_request','view_move_in','view_move_out'))
                        <li class="nk-menu-heading">
                            <h6 class="overline-title text-primary-alt">Movements & Requests</h6>
                        </li>
                            <li class="nk-menu-item has-sub">
                            <a href="#" class="nk-menu-link nk-menu-toggle">
                                <span class="nk-menu-icon"><em class="icon ni ni-move"></em></span>
                                <span class="nk-menu-text">Requests</span>
                            </a>
                            <ul class="nk-menu-sub">
                                @if($role->hasPermission('view_move_request'))
                                     <li class="nk-menu-item">
                                        <a href="{{Route('move-in-request.index')}}" class="nk-menu-link">
                                            <span class="nk-menu-text">Move Request</span>
                                        </a>
                                    </li>
                                @endif
                                    @if($role->hasPermission('view_move_in'))
                                    <li class="nk-menu-item">
                                        <a href="{{route('move-in.index')}}" class="nk-menu-link">
                                            <span class="nk-menu-text">Move-In</span>
                                        </a>
                                    </li>
                                    @endif
                                    @if($role->hasPermission('view_move_out'))
                                      <li class="nk-menu-item">
                                        <a href="{{route('move-out.index')}}" class="nk-menu-link">
                                            <span class="nk-menu-text">Move Out</span>
                                        </a>
                                    </li>
                                    @endif
{{--                                    @if($role->hasPermission('view_move_out'))--}}
{{--                                    <li class="nk-menu-item">--}}
{{--                                        <a href="#" class="nk-menu-link">--}}
{{--                                            <span class="nk-menu-text">Inquiries</span>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                    @endif--}}
                             </ul><!-- .nk-menu-sub -->
                        </li>
                    @endif
                    @if($role->hasPermission('view_warehouse','view_storage_level','view_storage_type','view_storage_size','view_storage_unit'))
                        <li class="nk-menu-heading">
                            <h6 class="overline-title text-primary-alt"> Storages</h6>
                        </li>
                        <li class="nk-menu-item has-sub">
                                <a href="#" class="nk-menu-link nk-menu-toggle">
                                    <span class="nk-menu-icon"><em class="icon ni ni-dashboard"></em></span>
                                    <span class="nk-menu-text">Warehouse</span>
                                </a>
                                <ul class="nk-menu-sub">
                                    @if($role->hasPermission('view_warehouse'))
                                        <li class="nk-menu-item">
                                            <a href="{{ route('warehouse.index') }}" class="nk-menu-link">
                                                <span class="nk-menu-text">Warehouses</span>
                                            </a>
                                        </li>
                                    @endif
                                    @if($role->hasPermission('view_storage_level'))
                                    <li class="nk-menu-item">
                                        <a href="{{ route('storage-unit-level.index') }}" class="nk-menu-link">
                                            <span class="nk-menu-text">Storage Unit Level</span>
                                        </a>
                                    </li>
                                      @endif
                                        @if($role->hasPermission('view_storage_type'))
                                        <li class="nk-menu-item">
                                            <a href="{{ route('storage_type.index') }}" class="nk-menu-link">
                                             <span class="nk-menu-text">Storage Types</span>
                                            </a>
                                        </li>
                                        @endif
                                        @if($role->hasPermission('view_storage_size'))
                                    <li class="nk-menu-item">
                                        <a href="{{ route('storage_size.index') }}" class="nk-menu-link">
                                            <span class="nk-menu-text">Storage Unit Size</span>
                                        </a>
                                    </li>
                                        @endif
                                        @if($role->hasPermission('view_storage_unit'))
                                         <li class="nk-menu-item">
                                            <a href="{{ route('storage_unit.index') }}" class="nk-menu-link">
                                                <span class="nk-menu-text">Storage Units</span>
                                            </a>
                                        </li>
                                        @endif
                                </ul><!-- .nk-menu-sub -->
                            </li>
                    @endif
                    @if($role->hasPermission('view_product','view_addon'))
                        <li class="nk-menu-heading">
                            <h6 class="overline-title text-primary-alt">Products</h6>
                        </li>
                            <li class="nk-menu-item has-sub">
                                <a href="#" class="nk-menu-link nk-menu-toggle">
                                    <span class="nk-menu-icon"><em class="icon ni ni-cart-fill"></em></span>
                                    <span class="nk-menu-text">Products</span>
                                </a>
                                <ul class="nk-menu-sub">
                                    @if($role->hasPermission('view_product'))
                                       <li class="nk-menu-item">
                                            <a href="{{ route('product.index') }}" class="nk-menu-link">
                                                <span class="nk-menu-text">Products</span>
                                            </a>
                                        </li>
                                    @endif
                                    @if($role->hasPermission('view_addon'))
                                      <li class="nk-menu-item">
                                            <a href="{{ route('addon.index') }}" class="nk-menu-link">
                                                <span class="nk-menu-text">Addons</span>
                                            </a>
                                        </li>
                                      @endif
                                </ul><!-- .nk-menu-sub -->
                            </li>
                    @endif
                    @if($role->hasPermission('view_country','view_city','view_location'))
                        <li class="nk-menu-heading">
                            <h6 class="overline-title text-primary-alt">Locations</h6>
                        </li>
                        <li class="nk-menu-item has-sub">
                            <a href="#" class="nk-menu-link nk-menu-toggle">
                                <span class="nk-menu-icon"><em class="icon ni ni-map"></em></span>
                                <span class="nk-menu-text">Locations</span>
                            </a>
                            <ul class="nk-menu-sub">
                                @if($role->hasPermission('view_country'))
                                    <li class="nk-menu-item">
                                        <a href="{{ route('country.index') }}" class="nk-menu-link"><span class="nk-menu-text">Countries</span></a>
                                    </li>
                                @endif
                                @if($role->hasPermission('view_city'))
                                    <li class="nk-menu-item">
                                        <a href="{{ route('city.index') }}" class="nk-menu-link"><span class="nk-menu-text">Cities</span></a>
                                    </li>
                                @endif
                                @if($role->hasPermission('view_location'))
                                   <li class="nk-menu-item">
                                        <a href="{{ route('location.index') }}" class="nk-menu-link"><span class="nk-menu-text">Locations</span></a>
                                    </li>
                                 @endif
                           </ul><!-- .nk-menu-sub -->
                        </li>
                    @endif
                    @if($role->hasPermission('view_coupon'))
                            <li class="nk-menu-item">
                                <a href="{{ route('coupon.index') }}" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-calendar-booking"></em></span>
                                    <span class="nk-menu-text">Coupons</span>
                                </a>
                            </li>
                    @endif
                    @if($role->hasPermission('view_insurance'))
                            <li class="nk-menu-item">
                                <a href="{{ route('insurance.index') }}" class="nk-menu-link" data-original-title="" title="">
                                    <span class="nk-menu-icon"><em class="icon ni ni-umbrela"></em></span>
                                    <span class="nk-menu-text">Insurances</span>
                                </a>
                            </li>
                    @endif
                    @if($role->hasPermission('view_blog'))
                            <li class="nk-menu-item">
                                <a href="{{ route('blog.index') }}" class="nk-menu-link" data-original-title="" title="">
                                    <span class="nk-menu-icon"><em class="icon ni ni-article"></em></span>
                                    <span class="nk-menu-text">Blogs</span>
                                </a>
                            </li>
                    @endif
                    @if($role->hasPermission('view_term_length'))
                            <li class="nk-menu-item">
                                <a href="{{route('term-length.index')}}" class="nk-menu-link" data-original-title="" title="">
                                    <span class="nk-menu-icon"><em class="icon ni ni-umbrela"></em></span>
                                    <span class="nk-menu-text">Term Length</span>
                                </a>
                            </li>
                    @endif
                    @if($role->hasPermission('view_app_settings','view_measurement_unit','view_notification_template','view_contract_template','view_require_document','view_lead_status','view_lead_source'))
                        <li class="nk-menu-heading">
                            <h6 class="overline-title text-primary-alt">Settings</h6>
                        </li>
                            <li class="nk-menu-item has-sub">
                                <a href="#" class="nk-menu-link nk-menu-toggle" data-original-title="" title="">
                                    <span class="nk-menu-icon"><em class="icon ni ni-opt-alt-fill"></em></span>
                                    <span class="nk-menu-text">Settings</span>
                                </a>
                                <ul class="nk-menu-sub">
                                    @if($role->hasPermission('view_app_settings'))
                                    <li class="nk-menu-item">
                                        <a href="{{ route('app-settings.index') }}" class="nk-menu-link" data-original-title="" title="">
                                            <span class="nk-menu-text">App Settings</span></a>
                                    </li>
                                    @endif
                                    @if($role->hasPermission('view_measurement_unit'))
                                    <li class="nk-menu-item">
                                        <a href="{{ route('measurement-unit.index') }}" class="nk-menu-link" data-original-title="" title="">
                                            <span class="nk-menu-text">Measurement Units</span></a>
                                    </li>
                                     @endif
                                        @if($role->hasPermission('view_notification_template'))
                                    <li class="nk-menu-item">
                                        <a href=" {{ route('email-template.index') }}" class="nk-menu-link" data-original-title="" title="">
                                            <span class="nk-menu-text">Email Templates</span></a>
                                    </li>
                                        @endif
                                        @if($role->hasPermission('view_contract_template'))
                                    <li class="nk-menu-item">
                                        <a href=" {{ route('contract-template.index') }}" class="nk-menu-link" data-original-title="" title="">
                                            <span class="nk-menu-text">Contract Templates</span></a>
                                    </li>
                                        @endif
                                        @if($role->hasPermission('view_require_document'))
                                    <li class="nk-menu-item">
                                        <a href=" {{ route('require_document.index') }}" class="nk-menu-link" data-original-title="" title="">
                                            <span class="nk-menu-text">Require Documents</span></a>
                                    </li>
                                        @endif
                                        @if($role->hasPermission('view_lead_status','view_lead_source'))
                                    <li class="nk-menu-item">
                                        <a href="#" class="nk-menu-link nk-menu-toggle" data-original-title="" title="">
                                            <span class="nk-menu-text">Lead Settings</span></a>
                                        <ul class="nk-menu-sub" >
                                            @if($role->hasPermission('view_lead_status'))
                                            <li class="nk-menu-item">
                                                <a href="{{route('lead_status.index')}}" class="nk-menu-link" data-original-title="" title="">
                                                <span class="nk-menu-text">Lead Status</span></a>
                                            </li>
                                            @endif
                                            @if($role->hasPermission('view_lead_source'))
                                            <li class="nk-menu-item">
                                                <a href="{{route('lead_source.index')}}" class="nk-menu-link" data-original-title="" title="">
                                                 <span class="nk-menu-text">Lead Source</span></a>
                                            </li>
                                            @endif
                                        </ul><!-- .nk-menu-sub -->
                                    </li>
                                        @endif
                                </ul><!-- .nk-menu-sub -->
                            </li>
                    @endif
                    @if($role->hasPermission('view_user','view_role'))
                        <li class="nk-menu-heading">
                            <h6 class="overline-title text-primary-alt">Users Settings</h6>
                        </li>
                    @endif
                    @if($role->hasPermission('view_user'))
                            <li class="nk-menu-item">
                                <a href="{{ route('users.index') }}" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-users-fill"></em></span>
                                    <span class="nk-menu-text">Users</span>
                                </a>
                            </li>
                    @endif
                    @if($role->hasPermission('view_role'))
                            <li class="nk-menu-item">
                                <a href="{{ route('roles.index') }}" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-block-over"></em></span>
                                    <span class="nk-menu-text">Roles</span>
                                </a>
                            </li>
                    @endif
                    @if($role->hasPermission('view_user'))
                        <li class="nk-menu-heading">
                            <h6 class="overline-title text-primary-alt">Reports</h6>
                        </li>
                        <li class="nk-menu-item has-sub">
                            <a href="#" class="nk-menu-link nk-menu-toggle">
                                <span class="nk-menu-icon"><em class="icon ni ni-reports"></em></span>
                                <span class="nk-menu-text">Reports</span>
                            </a>
                            <ul class="nk-menu-sub">
                                    <li class="nk-menu-item">
                                        <a href="{{ route('report.warehouse') }}" class="nk-menu-link"><span class="nk-menu-text">Warehouse Report</span></a>
                                    </li>
                                    <li class="nk-menu-item">
                                        <a href="{{ route('report.lead') }}" class="nk-menu-link"><span class="nk-menu-text">Leads Report</span></a>
                                    </li>
                                    <li class="nk-menu-item">
                                        <a href="{{ route('report.estimate') }}" class="nk-menu-link"><span class="nk-menu-text">Estimate Report</span></a>
                                    </li>
                                    <li class="nk-menu-item">
                                        <a href="{{ route('report.contract') }}" class="nk-menu-link"><span class="nk-menu-text">Contract Report</span></a>
                                    </li>
                                    <li class="nk-menu-item">
                                        <a href="{{ route('report.invoice') }}" class="nk-menu-link"><span class="nk-menu-text">Invoice Report</span></a>
                                    </li>
                                    <li class="nk-menu-item">
                                        <a href="{{ route('report.payment') }}" class="nk-menu-link"><span class="nk-menu-text">Payment Reports</span></a>
                                    </li>
{{--                                    <li class="nk-menu-item">--}}
{{--                                        <a href="{{ route('location.index') }}" class="nk-menu-link"><span class="nk-menu-text">MoveIn Request Report</span></a>--}}
{{--                                    </li>--}}
{{--                                    <li class="nk-menu-item">--}}
{{--                                        <a href="{{ route('location.index') }}" class="nk-menu-link"><span class="nk-menu-text">MoveIn Report</span></a>--}}
{{--                                    </li>--}}
{{--                                    <li class="nk-menu-item">--}}
{{--                                        <a href="{{ route('location.index') }}" class="nk-menu-link"><span class="nk-menu-text">MoveOut Report</span></a>--}}
{{--                                    </li>--}}

                            </ul><!-- .nk-menu-sub -->
                        </li>
                    @endif
                </ul><!-- .nk-menu -->
            </div><!-- .nk-sidebar-menu -->
        </div><!-- .nk-sidebar-content -->
    </div><!-- .nk-sidebar-element -->
</div>
<!-- sidebar @e -->
