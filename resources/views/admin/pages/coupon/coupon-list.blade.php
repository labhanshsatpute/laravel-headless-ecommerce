@extends('admin.layouts.app')

@section('panel-header')
    <div>
        <ul class="breadcrumb">
            <li><a href="{{ route('admin.view.dashboard') }}">Admin</a></li>
            <li><i data-feather="chevron-right"></i></li>
            <li><a href="{{ route('admin.view.coupon.list') }}">Coupons</a></li>
        </ul>
        <h1 class="panel-title">Coupons</h1>
    </div>
@endsection

@section('panel-body')
    <figure class="panel-card">
        <div class="panel-card-header">
            <div>
                <h1 class="panel-card-title">Coupon Codes</h1>
                <p class="panel-card-description">List of all coupon codes in the system</p>
            </div>
            <div>
                <a href="{{ route('admin.view.coupon.create') }}" class="btn-primary-sm flex">
                    <span class="lg:block md:block sm:hidden mr-2">Add Coupon</span>
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
                        <th>Code</th>
                        <th>Discount</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        @foreach ($coupons as $coupon)
                            <tr>
                                <td>{{ $coupon->id }}</td>
                                <td>{{ $coupon->name }}</td>
                                <td>{{ $coupon->code }}</td>
                                <td>
                                    @switch($coupon->discount_type)
                                        @case($discount_type::FIXED->value)
                                            {{ config('app.currency.symbol') . round($coupon->discount_value, 0) . ' Off' }}
                                        @break

                                        @case($discount_type::PERCENTAGE->value)
                                            {{ round($coupon->discount_value, 0) . '% Off' }}
                                        @break
                                    @endswitch
                                </td>
                                <td>
                                    {{ date('d/m/Y', strtotime($coupon->start_date)) }} -
                                    {{ date('d/m/Y', strtotime($coupon->expire_date)) }}
                                </td>
                                <td>
                                    <label class="toggler-switch">
                                        <input onchange="handleUpdateStatus({{ $coupon->id }})"
                                            @checked($coupon->status) type="checkbox">
                                        <div class="slider"></div>
                                    </label>
                                </td>
                                <td>
                                    <div class="table-dropdown">
                                        <button>Options<i data-feather="chevron-down"
                                                class="ml-1 toggler-icon"></i></button>
                                        <div class="dropdown-menu">
                                            <ul>
                                                <li><a href="{{ route('admin.view.coupon.update', ['id' => $coupon->id]) }}"
                                                        class="dropdown-link-primary"><i data-feather="edit"
                                                            class="mr-1"></i> Edit Coupon</a></li>
                                                <li><a href="javascript:handleDelete({{ $coupon->id }});"
                                                        class="dropdown-link-danger"><i data-feather="trash-2"
                                                            class="mr-1"></i> Delete Coupon</a></li>
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
        document.getElementById('coupon-tab').classList.add('active');

        const handleUpdateStatus = (id) => {
            fetch("{{ route('admin.handle.coupon.status') }}", {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: id,
                    _token: "{{ csrf_token() }}"
                })
            }).then((response) => {
                return response.json();
            }).catch((error) => {
                swal({
                    title: "Internal server error",
                    text: "An error occured, please try again",
                    icon: "error",
                })
            });
        }

        const handleDelete = (id) => {
            swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this coupon!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        window.location = `{{ url('admin/coupon/delete') }}/${id}`;
                    }
                });
        }
    </script>
@endsection
