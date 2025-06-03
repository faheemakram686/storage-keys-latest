@php
    $isEdit = isset($role) ? true : false;
    $url = $isEdit ? route('roles.update', $role) : route('roles.store');
@endphp

<div class="card">
    <div class="card-inner">
        <form class="gy-3 form-validate user-form is-alter" action="{{ $url }}" data-form="ajax-form" method="post"  data-datatable="#roles_table" data-redirect="{{ route('roles.index') }}">
            @csrf
            @if($isEdit)
                @method('put')
            @endif
            <div class="row g-4">
                <div class="col-lg-9">
                    <div class="form-group">
                        <label class="form-label" for="role_name">Role Name</label>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Role Name" value="{{ $isEdit ? $role->name : '' }}" required readonly>
                        </div>
                    </div>
                </div>
                <div class="align-self-end col-lg-3">
                    <div class="custom-control custom-control-md custom-checkbox custom-control">
                        <input type="checkbox" class="custom-control-input assign-all" id="assign_all" @if($isEdit && ($permissions->count() == $role->permissions->count())) checked @endif>
                        <label class="custom-control-label text-capitalize" for="assign_all">Assign all permissions</label>
                    </div>
                </div>
            </div>
            {{-- <div class="card-head">
                <h5 class="card-title">Permissions</h5>
            </div> --}}
            <div class="row g-4">
                @foreach ($permission_groups as $key => $permissions)
                    <div class="col-lg-3">
                        <h6 class="text-capitalize font-weight-bold mb-2">{{ $key }}</h6>
                        <div class="custom-control custom-control-md custom-checkbox custom-control pb-2">
                            <input type="checkbox" class="custom-control-input group-permissions" id="{{ $key }}" @if($isEdit && (count($permissions)==getAssignedPermissionsCount($role, $key))) checked @endif/>
                            <label class="custom-control-label text-capitalize" for="{{ $key }}">Select all</label>
                        </div>
                        @foreach($permissions as $permission)
                        <div>
                            <div class="custom-control custom-control-md custom-checkbox custom-control pb-2">
                                <input type="checkbox" class="custom-control-input" value="{{$permission->id}}" id="{{$permission->id }}" name="permissions[]" @if($isEdit && $role->hasPermissionTo($permission)) checked @endif>
                                <label class="custom-control-label text-capitalize" for="{{$permission->id }}">{{ $permission->title}}</label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endforeach
                <div class="col-lg-12">
                    <div class="form-group">
                        <button type="submit" class="btn btn-md btn-primary" data-button="submit">Save Changes</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        $(document).on('click', '.group-permissions', function() {
            if($(this).is(':checked')) {
                $(this).parent().siblings().find('.custom-control-input').prop('checked', true);
            } else {
                $(this).parent().siblings().find('.custom-control-input').prop('checked', false);
            }
        });
        $(document).on('click', '.assign-all', function() {
            if($(this).is(':checked')) {
                $('.custom-control-input').prop('checked', true);
            } else {
                $('.custom-control-input').prop('checked', false);
            }
        });
    </script>
@endpush
