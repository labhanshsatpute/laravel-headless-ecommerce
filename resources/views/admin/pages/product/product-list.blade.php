@extends('admin.layouts.app')

@section('panel-header')
    <div>
        <ul class="breadcrumb">
            <li><a href="{{ route('admin.view.dashboard') }}">Admin</a></li>
            <li><i data-feather="chevron-right"></i></li>
            <li><a href="{{ route('admin.view.product.list') }}">Products</a></li>
        </ul>
        <h1 class="panel-title">Products</h1>
    </div>
@endsection

@section('panel-body')
    <figure class="panel-card">
        <div class="panel-card-header">
            <div>
                <h1 class="panel-card-title">Products</h1>
                <p class="panel-card-description">List of all products in the system</p>
            </div>
            <div>
                <a href="{{ route('admin.view.product.create') }}" class="btn-primary-sm flex">
                    <span class="lg:block md:block sm:hidden mr-2">Add Product</span>
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
                        <th>Category</th>
                        <th>Price</th>
                        <th>Availability</th>
                        <th>Status</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->parent_category->name }}</td>
                                <td class="space-x-1">
                                    @if (is_null($product->price_discounted))
                                    <span>{{ config('app.currency.symbol') . round($product->price_original,0) }}</span>
                                    @else
                                    <span>{{ config('app.currency.symbol'). round($product->price_discounted, 0) }}</span>
                                    <span class="line-through">{{ config('app.currency.symbol'). round($product->price_original, 0) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @switch($product->availability)
                                        @case($availablity::IN_STOCK->value)
                                        <div class="table-status-success">{{$availablity::IN_STOCK->label() }}</div>
                                        @break
                                        @case($availablity::OUT_OF_STOCK->value)
                                        <div class="table-status-danger">{{$availablity::OUT_OF_STOCK->label() }}</div>
                                        @break
                                        @case($availablity::PRE_ORDER->value)
                                        <div class="table-status-warning">{{$availablity::PRE_ORDER->label() }}</div>
                                        @break
                                    @endswitch
                                </td>
                                <td>
                                    <label class="toggler-switch">
                                        <input onchange="handleUpdateStatus({{ $product->id }})"
                                            @checked($product->status) type="checkbox">
                                        <div class="slider"></div>
                                    </label>
                                </td>
                                <td>
                                    <div class="table-dropdown">
                                        <button>Options<i data-feather="chevron-down"
                                                class="ml-1 toggler-icon"></i></button>
                                        <div class="dropdown-menu">
                                            <ul>
                                                <li><a href="{{ route('admin.view.product.update', ['id' => $product->id]) }}"
                                                        class="dropdown-link-primary"><i data-feather="edit"
                                                            class="mr-1"></i> Edit Product</a></li>
                                                <li><a href="javascript:handleDelete({{ $product->id }});"
                                                        class="dropdown-link-danger"><i data-feather="trash-2"
                                                            class="mr-1"></i> Delete Product</a></li>
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
        document.getElementById('product-tab').classList.add('active');

        const handleUpdateStatus = (id) => {
            fetch("{{ route('admin.handle.product.status') }}", {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    product_id: id,
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
                    text: "Once deleted, you will not be able to recover this product!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        window.location = `{{ url('admin/product/delete') }}/${id}`;
                    }
                });
        }
    </script>
@endsection
