@extends('admin.layouts.app')

@section('panel-header')
    <div>
        <ul class="breadcrumb">
            <li><a href="{{ route('admin.view.dashboard') }}">Admin</a></li>
            <li><i data-feather="chevron-right"></i></li>
            <li><a href="{{ route('admin.view.category.list') }}">Categories</a></li>
        </ul>
        <h1 class="panel-title">Categories</h1>
    </div>
@endsection

@section('panel-body')
    <div class="grid md:grid-cols-3 sm:grid-cols-1 md:gap-7 sm:gap-5">

        @foreach ($categories as $category)
            <figure class="panel-card">
                <div class="h-[160px] w-full overflow-clip flex items-center justify-center">
                    <img src="{{ asset('storage/' . $category->cover_image) }}" alt="cover_image" class="w-full">
                </div>
                <div class="panel-card-body space-y-1">
                    <h1 class="title">{{ $category->name }}</h1>
                    <h1 class="description">{{ $category->summary }}</h1>
                    <div>
                        <div class="pt-2">
                            <a href="{{ route('admin.view.category.update', ['id' => $category->id]) }}"
                                class="btn-primary-sm w-full flex items-center justify-center space-x-2"><span>Edit
                                    Service</span><i data-feather="edit"></i></a>
                        </div>
                    </div>
                </div>
            </figure>
        @endforeach

        <div class="panel-card">
            <div class="panel-card-body space-y-3">
                <div>
                    <h1 class="title">Create a New Category</h1>
                    <p class="description">Click to add a new category</p>
                </div>
                <hr class="opacity-0">
                <a href="{{ route('admin.view.category.create') }}">
                <button type="button" class="btn-primary-sm w-full">Add Category</button></a>
            </div>
        </div>

    </div>
@endsection

@section('panel-script')
    <script>
        document.getElementById('category-tab').classList.add('active');
    </script>
@endsection
