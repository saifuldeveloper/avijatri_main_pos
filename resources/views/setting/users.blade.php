@extends('layouts.app', ['title' => 'অনুমতি দিন'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h2>ব্যবহারকারী তালিকা <small><a href="#" class="btn-new" data-toggle="modal"
                                data-target="#user-form"> নতুন ব্যবহারকারী
                            </a></small></h2>

                </div>
                <div class="card-body">

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>নাম</th>
                                <th>ইমেইল</th>
                                <th>ধরণ</th>
                                {{-- <th>অনুমতি তালিকা</th> --}}
                                <th>অপশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $role)
                                @php

                                @endphp

                                <tr>
                                    <td>{{ $role->users[0]->name }}</td>
                                    <td>{{ $role->users[0]->email }}</td>
                                    <td>
                                        <span class="badge badge-primary">{{ $role->name }}</span>
                                    </td>
                                    {{-- <td>
                                        @foreach ($role->users[0]->getAllPermissions() as $permission)
                                            <span class="badge badge-warning">{{ $permission->name }}</span>
                                        @endforeach
                                    </td> --}}

                                    <td>
                                        <a href="{{ route('role.permission', $role->id) }}"
                                            class="btn btn-success btn-sm btn-restore">এডিট</a>

                                        <a href="#" onclick="return confirm('আপনি কি নিশ্চিত?')"
                                            class="delete-form btn btn-danger btn-sm btn-force-delete">
                                            ডিলিট</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('page-script')
@endsection
