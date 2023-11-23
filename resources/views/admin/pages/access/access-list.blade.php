@extends('admin.layouts.app')

@section('panel-header')
    <div>
        <ul class="breadcrumb">
            <li><a href="{{route('admin.view.dashboard')}}">Admin</a></li>
            <li><i data-feather="chevron-right"></i></li>
            <li><a href="{{route('admin.view.access.list')}}">Admin Access</a></li>
        </ul>
        <h1 class="panel-title">Admin Access</h1>
    </div>
@endsection


@section('panel-body')
<figure class="panel-card">
    <div class="panel-card-header">
        <div>
            <h1 class="panel-card-title">Admin Access</h1>
            <p class="panel-card-description">List of all admins access in the system</p>
        </div>
        <div>
            <a href="{{ route('admin.view.access.create') }}" class="btn-primary-sm flex">
                <span class="lg:block md:block sm:hidden mr-2">Add Access</span>
                <i data-feather="plus"></i>
            </a>
        </div>
    </div>
    <div class="panel-card-body">
        <div class="panel-card-table">
            <table class="data-table">
                <thead>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Role</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    @foreach ($admins as $admin)
                        <tr>
                            <td>{{ $admin->id }}</td>
                            <td>{{ $admin->name }}</td>
                            <td>{{ $admin->email }}</td>
                            <td>{{ $admin->phone }}</td>
                            <td>
                                <label class="toggler-switch">
                                    <input onchange="handleUpdateStatus({{$admin->id}})" @checked($admin->status) type="checkbox">
                                    <div class="slider"></div>
                                </label>
                            </td>
                            <td>
                                {{-- @switch($admin->role)
                                    @case($roles::ADMINISTRATOR->value)
                                        <div class="table-status-success">{{ $roles::ADMINISTRATOR->label() }}</div>
                                        @break
                                    @case($roles::MANAGER->value)
                                        <div class="table-status-warning">{{ $roles::MANAGER->label() }}</div>
                                        @break
                                @endswitch --}}
                            </td>
                            <td>
                                <div class="table-dropdown">
                                    <button>Options<i data-feather="chevron-down" class="ml-1 toggler-icon"></i></button>
                                    <div class="dropdown-menu">
                                        <ul>
                                            <li><a href="{{route('admin.view.access.update',['id' => $admin->id])}}" class="dropdown-link-primary"><i data-feather="edit" class="mr-1"></i> Edit Access</a></li>
                                            <li><a href="javascript:handleDelete({{$admin->id}});" class="dropdown-link-danger"><i data-feather="trash-2" class="mr-1"></i> Delete Access</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</figure>

@endsection

@section('panel-script')
<script>
    document.getElementById('access-tab').classList.add('active');
</script>
@endsection