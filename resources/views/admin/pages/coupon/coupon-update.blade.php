@extends('admin.layouts.app')

@section('panel-header')
    <div>
        <ul class="breadcrumb">
            <li><a href="{{ route('admin.view.dashboard') }}">Admin</a></li>
            <li><i data-feather="chevron-right"></i></li>
            <li><a href="{{ route('admin.view.coupon.list') }}">Coupons</a></li>
            <li><i data-feather="chevron-right"></i></li>
            <li><a href="{{ route('admin.view.coupon.update', ['id' => $coupon->id]) }}">Edit Coupon</a></li>
        </ul>
        <h1 class="panel-title">Edit Coupon</h1>
    </div>
@endsection

@section('panel-body')
    <form action="{{ route('admin.handle.coupon.update', ['id' => $coupon->id]) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        <figure class="panel-card">
            <div class="panel-card-header">
                <div>
                    <h1 class="panel-card-title">Edit Information</h1>
                    <p class="panel-card-description">Please fill the required fields</p>
                </div>
                <div>
                    <button type="button" class="btn-danger-sm flex items-center justify-center" onclick="handleDelete()">
                        <span class="lg:block md:block sm:hidden mr-2">Delete</span>
                        <i data-feather="trash"></i>
                    </button>
                </div>
            </div>
            <div class="panel-card-body">
                <div class="grid 2xl:grid-cols-5 lg:grid-cols-4 md:grid-cols-2 sm:grid-cols-1 gap-5">

                    {{-- Name --}}
                    <div class="input-group">
                        <label for="name" class="input-label">Name <em>*</em></label>
                        <input type="text" name="name" value="{{ old('name', $coupon->name) }}"
                            class="input-box-md @error('name') input-invalid @enderror" placeholder="Enter Name"
                            minlength="1" maxlength="250" required>
                        @error('name')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Code --}}
                    <div class="input-group">
                        <label for="code" class="input-label">Code <em>*</em></label>
                        <input type="text" name="code" value="{{ old('code', $coupon->code) }}"
                            class="input-box-md @error('code') input-invalid @enderror" placeholder="Enter Code"
                            minlength="1" maxlength="100" required>
                        @error('code')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Start Date --}}
                    <div class="input-group">
                        <label for="start_date" class="input-label">Start Date <em>*</em></label>
                        <input type="date" name="start_date" value="{{ old('start_date', $coupon->start_date) }}"
                            class="input-box-md @error('start_date') input-invalid @enderror" required>
                        @error('start_date')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Expiry Date --}}
                    <div class="input-group">
                        <label for="expire_date" class="input-label">Expiry Date <em>*</em></label>
                        <input type="date" name="expire_date" value="{{ old('expire_date', $coupon->expire_date) }}"
                            class="input-box-md @error('expire_date') input-invalid @enderror" required>
                        @error('expire_date')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Discount Type --}}
                    <div class="flex flex-col">
                        <label for="discount_type" class="input-label">Discount Type <em>*</em></label>
                        <select name="discount_type" class="input-box-md @error('discount_type') input-invalid @enderror"
                            required>
                            <option value="">Select Type</option>
                            @foreach ($discount_type::cases() as $type)
                                <option @selected(old('discount_type', $coupon->discount_type) == $type->value) value="{{ $type->value }}">
                                    {{ $type->label() }}</option>
                            @endforeach
                        </select>
                        @error('discount_type')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Discount Value --}}
                    <div class="input-group">
                        <label for="discount_value" class="input-label">Discount Value <em>*</em></label>
                        <input type="number" name="discount_value"
                            value="{{ old('discount_value', $coupon->discount_value) }}"
                            class="input-box-md @error('discount_value') input-invalid @enderror"
                            placeholder="Enter Discount Value" min="1" max="100" required>
                        @error('discount_value')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Minimum Purchase --}}
                    <div class="input-group">
                        <label for="minimum_purchase" class="input-label">Minimum Purchase <em>*</em> <span>(In
                                {{ config('app.currency.code') }})</span></label>
                        <input type="number" name="minimum_purchase"
                            value="{{ old('minimum_purchase', $coupon->minimum_purchase) }}"
                            class="input-box-md @error('minimum_purchase') input-invalid @enderror"
                            placeholder="Enter Minimum Purchase" min="0" max="100000000" required>
                        @error('minimum_purchase')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Maximum Discount --}}
                    <div class="input-group">
                        <label for="maximum_discount" class="input-label">Maximum Discount <em>*</em> <span>(In
                                {{ config('app.currency.code') }})</span></label>
                        <input type="number" name="maximum_discount"
                            value="{{ old('maximum_discount', $coupon->maximum_discount) }}"
                            class="input-box-md @error('maximum_discount') input-invalid @enderror"
                            placeholder="Enter Maximum Discount" min="1" max="100000000" required>
                        @error('maximum_discount')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Summary --}}
                    <div class="input-group 2xl:col-span-5 lg:col-span-4 md:col-span-2">
                        <label for="summary" class="input-label">Summary <span>(Optional)</span></label>
                        <input type="text" name="summary" value="{{ old('summary', $coupon->summary) }}"
                            class="input-box-md @error('summary') input-invalid @enderror" placeholder="Enter Summary"
                            required minlength="1" maxlength="500">
                        @error('summary')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
            </div>
            <div class="panel-card-footer">
                <button type="submit" class="btn-primary-md md:w-fit sm:w-full">Save Changes</button>
            </div>
        </figure>
    </form>
@endsection

@section('panel-script')
    <script>
        document.getElementById('coupon-tab').classList.add('active');

        const handleDelete = () => {
            swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this coupon!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        window.location =
                            "{{ route('admin.handle.coupon.delete', ['id' => $coupon->id]) }}";
                    }
                });
        }
    </script>
@endsection
