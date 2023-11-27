@extends('admin.layouts.app')

@section('panel-header')
    <div>
        <ul class="breadcrumb">
            <li><a href="{{ route('admin.view.dashboard') }}">Admin</a></li>
            <li><i data-feather="chevron-right"></i></li>
            <li><a href="{{ route('admin.view.category.list') }}">Categories</a></li>
            <li><i data-feather="chevron-right"></i></li>
            <li><a href="{{ route('admin.view.category.update', ['id' => $category->id]) }}">Edit Category</a></li>
        </ul>
        <h1 class="panel-title">Edit Category</h1>
    </div>
@endsection

@section('panel-body')
    <form action="{{ route('admin.handle.category.update', ['id' => $category->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <figure class="panel-card">
            <div class="panel-card-header">
                <div>
                    <h1 class="panel-card-title">Update Information</h1>
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
                        <input type="text" name="name" value="{{ old('name', $category->name) }}"
                            class="input-box-md @error('name') input-invalid @enderror" placeholder="Enter Name"
                            minlength="1" maxlength="250" required>
                        @error('name')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Priority --}}
                    <div class="input-group">
                        <label for="priority" class="input-label">Priority <span>(Optional)</span></label>
                        <input type="number" name="priority" value="{{ old('priority', $category->priority) }}"
                            class="input-box-md @error('priority') input-invalid @enderror" placeholder="Enter Priority"
                            min="0" max="1000">
                        @error('slug')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Slug --}}
                    <div class="input-group">
                        <label for="slug" class="input-label">Slug <span>(Optional)</span></label>
                        <input type="text" name="slug" value="{{ old('slug', $category->slug) }}"
                            class="input-box-md @error('slug') input-invalid @enderror" placeholder="Enter Slug"
                            minlength="1" maxlength="250">
                        @error('slug')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Parent Category --}}
                    <div class="flex flex-col">
                        <label for="category_id" class="input-label">Parent Category (Optional)</label>
                        <select name="category_id" class="input-box-md @error('discount_type') input-invalid @enderror">
                            <option value="">Select Parent Category</option>
                            @foreach ($categories as $item)
                                <option @selected(old('category_id', $category->category_id) == $item->id) value="{{ $item->id }}">{{$item->name}}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Summary --}}
                    <div class="input-group 2xl:col-span-5 lg:col-span-4 md:col-span-2">
                        <label for="summary" class="input-label">Summary <em>*</em></label>
                        <input type="text" name="summary" value="{{ old('summary', $category->summary) }}"
                            class="input-box-md @error('summary') input-invalid @enderror" placeholder="Enter Summary"
                            required minlength="1" maxlength="500">
                        @error('summary')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="input-group 2xl:col-span-5 lg:col-span-4 md:col-span-2 sm:col-span-1">
                        <label for="description" class="input-label">Description <span>(Optional)</span></label>
                        <div class="space-y-2">
                            <div class="flex space-x-2">
                                <button class="btn-primary-sm" type="button" onclick="format('bold')"><b>B</b></button>
                                <button class="btn-primary-sm" type="button" onclick="format('italic')"><i>I</i></button>
                                <button class="btn-primary-sm" type="button" onclick="format('insertunorderedlist')"><i
                                        data-feather="list" class="h-3 w-3"></i></button>
                            </div>
                            <div onkeyup="handleConvertHTML()"
                                class="input-box-md @error('description') input-invalid @enderror" contenteditable="true"
                                id="html-editor">
                                {!! old('description', $category->description) !!}
                            </div>
                            <input type="text" name="description" id="description" value="{{ old('description', $category->description) }}"
                                hidden>
                        </div>
                        <script>
                            function format(command, value) {
                                document.execCommand(command, false, value);
                            }

                            function handleConvertHTML() {
                                document.getElementById('description').value = document.getElementById('html-editor').innerHTML;
                            }
                        </script>
                        @error('description')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Thumbnail --}}
                    <div class="md:col-span-4 sm:col-span-1">
                        <label for="thumbnail_image" class="input-label">Thumbnail <span>(Format: png, jpg, jpeg,
                                webp)</span> <span>(Optional)</span></label>
                        <div class="flex space-x-3 my-2">
                            <div class="input-box-dragable">
                                <input type="file" accept="image/jpeg, image/jpg, image/png, image/webp"
                                    onchange="handleThumbnailPreview(event)" name="thumbnail_image">
                                <i data-feather="upload-cloud"></i>
                                <span>Darg and Drop Image Files</span>
                            </div>
                            <img src="{{ is_null($category->thumbnail_image) ? asset('admin/images/default-thumbnail.png') : asset('storage/' . $category->thumbnail_image) }}" id="thumbnail_image"
                                alt="thumbnail_image" class="input-thumbnail-preview">
                        </div>
                        @error('thumbnail_image')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Cover Image --}}
                    <div class="md:col-span-4 sm:col-span-1">
                        <label for="cover_image" class="input-label">Cover Image <span>(Format: png, jpg, jpeg,
                                webp)</span> <em>*</em></label>
                        <div class="flex space-x-3 my-2">
                            <div class="input-box-dragable">
                                <input type="file" accept="image/jpeg, image/jpg, image/png, image/webp"
                                    onchange="handleCoverImagePreview(event)" name="cover_image">
                                <i data-feather="upload-cloud"></i>
                                <span>Darg and Drop Image Files</span>
                            </div>
                            <img src="{{ is_null($category->cover_image) ? asset('admin/images/default-thumbnail.png') : asset('storage/' . $category->cover_image) }}"
                                id="cover_image" alt="cover_image" class="input-thumbnail-preview">
                        </div>
                        @error('cover_image')
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
        document.getElementById('category-tab').classList.add('active');

        const handleThumbnailPreview = (event) => {
            if (event.target.files.length == 0) {
                document.getElementById('thumbnail_image').src = "{{ is_null($category->thumbnail_image) ? asset('admin/images/default-thumbnail.png') : asset('storage/' . $category->thumbnail_image) }}";
            } else {
                document.getElementById('thumbnail_image').src = URL.createObjectURL(event.target.files[0])
            }
        }

        const handleCoverImagePreview = (event) => {
            if (event.target.files.length == 0) {
                document.getElementById('cover_image').src = "{{ is_null($category->cover_image) ? asset('admin/images/default-thumbnail.png') : asset('storage/' . $category->cover_image) }}";
            } else {
                document.getElementById('cover_image').src = URL.createObjectURL(event.target.files[0])
            }
        }

        const handleDelete = () => {
            swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this service!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        window.location =
                            "{{ route('admin.handle.category.delete', ['id' => $category->id]) }}";
                    }
                });
        }
    </script>
@endsection