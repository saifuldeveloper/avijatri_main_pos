@extends('layouts.app', ['title' => 'অনুমতি দিন'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">অনুমতি তালিকা</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">

                            <form action="{{ route('permission.store') }}" method="post">
                                @csrf
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {{-- role name select --}}
                                        <label for="role">ব্যবহারকারীর ধরণ</label>
                                        <select name="role" id="role" class="form-control" required>
                                            <option value="">ব্যবহারকারীর ধরণ নির্বাচন করুন</option>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label>Permission: <span> * </span>
                                            <input type="checkbox" id="permissionAll"> All Permissions
                                        </label>
                                    </div>
                                </div>

                                <div class="row">
                                    @foreach ($permissions as $permission)
                                        <div class="col-lg-4 col-sm-6 col-12">
                                            <div class="form-group" style="display: flex; align-items: center; gap:4px;">
                                                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                    id="{{ $permission->name }}">
                                                <label for="{{ $permission->name }}" style="margin-bottom:2px;">
                                                    {{ __('permission.' . $permission->name) }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">সাবমিট</button>
                                </div>
                            </form>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('page-script')
    <script>
        $("#permissionAll").click(function() {
            $("input[type=checkbox]").prop("checked", $(this).prop("checked"));
        });


        $("input[type=checkbox]").click(function() {
            if (!$(this).prop("checked")) {
                $("#permissionAll").prop("checked", false);
            }
        });
    </script>
@endsection
