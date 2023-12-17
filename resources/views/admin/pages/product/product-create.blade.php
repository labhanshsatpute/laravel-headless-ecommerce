@extends('admin.layouts.app')

@section('panel-header')
    <div>
        <ul class="breadcrumb">
            <li><a href="{{ route('admin.view.dashboard') }}">Admin</a></li>
            <li><i data-feather="chevron-right"></i></li>
            <li><a href="{{ route('admin.view.product.list') }}">Products</a></li>
            <li><i data-feather="chevron-right"></i></li>
            <li><a href="{{ route('admin.view.product.create') }}">Add Product</a></li>
        </ul>
        <h1 class="panel-title">Add Product</h1>
    </div>
@endsection

@section('panel-body')
    <form action="{{ route('admin.handle.product.create') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <figure class="panel-card">
            <div class="panel-card-header">
                <div>
                    <h1 class="panel-card-title">Add Information</h1>
                    <p class="panel-card-description">Please fill the required fields</p>
                </div>
            </div>
            <div class="panel-card-body">
                <div class="grid 2xl:grid-cols-5 lg:grid-cols-4 md:grid-cols-2 sm:grid-cols-1 gap-5">

                    {{-- Divider Title --}}
                    <div class="2xl:col-span-5 lg:col-span-4 md:col-span-2 sm:col-span-1">
                        <h1 class="font-semibold">General Information</h1>
                    </div>

                    {{-- Name --}}
                    <div class="input-group 2xl:col-span-3 md:col-span-2 sm:col-span-1">
                        <label for="name" class="input-label">Name <em>*</em></label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="input-box-md @error('name') input-invalid @enderror" placeholder="Enter Name"
                            minlength="1" maxlength="250" required>
                        @error('name')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- SKU --}}
                    <div class="input-group">
                        <label for="sku" class="input-label">SKU <span>(Optional)</span></label>
                        <input type="text" name="sku" value="{{ old('sku') }}"
                            class="input-box-md @error('sku') input-invalid @enderror" placeholder="Enter SKU"
                            minlength="1" maxlength="250">
                        @error('sku')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Slug --}}
                    <div class="input-group">
                        <label for="slug" class="input-label">Slug <span>(Optional)</span></label>
                        <input type="text" name="slug" value="{{ old('slug') }}"
                            class="input-box-md @error('slug') input-invalid @enderror" placeholder="Enter Slug"
                            minlength="1" maxlength="250">
                        @error('slug')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Parent Category --}}
                    <div class="input-group">
                        <label for="parent_category_id" class="input-label">Parent Category <em>*</em></label>
                        <select name="parent_category_id"
                            class="input-box-md @error('parent_category_id') input-invalid @enderror" required>
                            <option value="">Select Category</option>
                            @foreach ($parent_categories as $category)
                                <option @selected(old('parent_category_id') == $category->id) value="{{ $category->id }}">
                                    {{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('parent_category_id')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Child Category --}}
                    <div class="input-group">
                        <label for="child_category_id" class="input-label">Child Category <span>(Optional)</span></label>
                        <select name="child_category_id"
                            class="input-box-md @error('child_category_id') input-invalid @enderror">
                            <option value="">Select Category</option>
                            @foreach ($child_categories as $category)
                                <option @selected(old('child_category_id') == $category->id) value="{{ $category->id }}">
                                    {{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('child_category_id')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Summary --}}
                    <div class="input-group 2xl:col-span-5 lg:col-span-4 md:col-span-2">
                        <label for="summary" class="input-label">Summary <span>(Optional)</span></label>
                        <input type="text" name="summary" value="{{ old('summary') }}"
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
                                {!! old('description') !!}
                            </div>
                            <input type="text" name="description" id="description" value="{{ old('description') }}"
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

                    {{-- Divider Title --}}
                    <div class="2xl:col-span-5 lg:col-span-4 md:col-span-2 sm:col-span-1">
                        <h1 class="font-semibold">Pricing Information</h1>
                    </div>

                    {{-- Original Price --}}
                    <div class="input-group">
                        <label for="price_original" class="input-label">Original Price <em>*</em> <span>(In
                                {{ config('app.currency.code') }})</span></label>
                        <input type="number" step="any" name="price_original" value="{{ old('price_original') }}"
                            class="input-box-md @error('price_original') input-invalid @enderror"
                            placeholder="Enter Original Price" required min="0" max="1000000">
                        @error('price_original')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Discounted Price --}}
                    <div class="input-group">
                        <label for="price_discounted" class="input-label">Discounted Price <span>(Optional)</span>
                            <span>(In {{ config('app.currency.code') }})</span></label>
                        <input type="number" step="any" name="price_discounted"
                            value="{{ old('price_discounted') }}"
                            class="input-box-md @error('price_discounted') input-invalid @enderror"
                            placeholder="Enter discounted price" min="0" max="1000000">
                        @error('price_discounted')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Tax Percentage --}}
                    <div class="input-group">
                        <label for="tax_percentage" class="input-label">Tax Percentage <span> (Optional)</span> <span>(In
                                %)</span></label>
                        <input type="number" step="any" name="tax_percentage" value="{{ old('tax_percentage') }}"
                            class="input-box-md @error('tax_percentage') input-invalid @enderror"
                            placeholder="Enter discounted price" min="0" max="100">
                        @error('tax_percentage')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Divider Title --}}
                    <div class="2xl:col-span-5 lg:col-span-4 md:col-span-2 sm:col-span-1">
                        <h1 class="font-semibold">SEO Information</h1>
                    </div>

                    {{-- Meta Title --}}
                    <div class="flex flex-col md:col-span-2 sm:col-span-1">
                        <label for="meta_title" class="input-label">Meta Title <em>*</em></label>
                        <input type="text" name="meta_title" value="{{ old('meta_title') }}"
                            class="input-box-md @error('meta_title') input-invalid @enderror"
                            placeholder="Enter Meta Title" minlength="1" maxlength="250">
                        @error('meta_title')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Meta Keywords --}}
                    <div class="flex flex-col md:col-span-2 sm:col-span-1">
                        <label for="meta_keywords" class="input-label">Meta Keywords <span>(Optional)</span></label>
                        <input type="text" name="meta_keywords" value="{{ old('meta_keywords') }}"
                            class="input-box-md @error('meta_keywords') input-invalid @enderror"
                            placeholder="Enter Meta Keywords" minlength="1" maxlength="1000">
                        @error('meta_keywords')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Meta Description --}}
                    <div class="flex flex-col md:col-span-4 sm:col-span-1">
                        <label for="meta_description" class="input-label">Meta Description <span>(Optional)</span></label>
                        <textarea name="meta_description" rows="3"
                            class="input-box-md @error('meta_description') input-invalid @enderror" placeholder="Enter Meta Description"
                            minlength="1" maxlength="1000">{{ old('meta_description') }}</textarea>
                        @error('meta_description')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Divider Title --}}
                    <div class="2xl:col-span-5 lg:col-span-4 md:col-span-2 sm:col-span-1">
                        <h1 class="font-semibold">Other Information</h1>
                    </div>

                    {{-- Tags --}}
                    <div class="input-group">
                        <label for="tags" class="input-label">Tags <span>(Optional)</span></label>
                        <input type="text" name="tags" value="{{ old('tags') }}"
                            class="input-box-md @error('tags') input-invalid @enderror" placeholder="Enter Tags">
                        @error('tags')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Color --}}
                    <div class="input-group">
                        <label for="color" class="input-label">Color <span>(Optional)</span></label>
                        <input type="color" name="color" value="{{ old('color') }}" class="h-12 w-12">
                        @error('color')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Highlights --}}
                    <div class="input-group md:col-span-2 sm:col-span-1">
                        <label for="highlights" class="input-label">Highlights <span>(Optional)</span></label>
                        <div class="space-y-2">
                            <div class="space-y-2" id="highlights-inputs">

                            </div>
                            <button type="button" onclick="handleCreateHighlightInput(null)"
                                class="btn-secondary-sm">Add Highlight Input</button>
                        </div>
                        @error('highlights')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Divider Title --}}
                    <div class="2xl:col-span-5 lg:col-span-4 md:col-span-2 sm:col-span-1">
                        <h1 class="font-semibold">Sizes & Variants Information</h1>
                    </div>

                    {{-- Sizes --}}
                    <div class="input-group 2xl:col-span-5 lg:col-span-4 md:col-span-2 sm:col-span-1">
                        <label for="sizes" class="input-label">Sizes <span>(Optional)</span></label>
                        <div class="space-y-2">
                            <div class="space-y-2" id="sizes-inputs">

                            </div>
                            <button type="button" onclick="handleCreateSizeInput(null,null,null)"
                                class="btn-secondary-sm">Add Size Input</button>
                        </div>
                        @error('sizes')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Divider Title --}}
                    <div class="2xl:col-span-5 lg:col-span-4 md:col-span-2 sm:col-span-1">
                        <h1 class="font-semibold">Thumbnail & Other Media</h1>
                    </div>

                    {{-- Thumbnail --}}
                    <div class="input-group 2xl:col-span-5 lg:col-span-4 md:col-span-2 sm:col-span-1">
                        <label for="thumbnail_image" class="input-label">Thumbnail <span>(Format: png, jpg, jpeg, webp, avif)</span> <em>*</em></label>
                        <div class="flex space-x-3 my-2">
                            <div class="input-box-dragable">
                                <input type="file" accept="image/jpeg, image/jpg, image/png, image/webp, image/avif"
                                    onchange="handleThumbnailPreview(event)" name="thumbnail_image" required>
                                <i data-feather="upload-cloud"></i>
                                <span>Darg and Drop Image Files</span>
                            </div>
                            <img src="{{ asset('admin/images/default-thumbnail.png') }}" id="thumbnail_image"
                                alt="thumbnail_image" class="input-thumbnail-preview">
                        </div>
                        @error('thumbnail_image')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Product Media --}}
                    <div class="input-group 2xl:col-span-5 lg:col-span-4 md:col-span-2 sm:col-span-1">
                        <label for="product_media" class="input-label">Media (Format: png, jpg, jpeg, webp, mp4,
                            pdf)</label>
                        <div class="space-y-2 my-2">
                            <div class="input-box-dragable">
                                <input type="file" multiple onchange="handleMediaPreview(event)"
                                    name="product_media[]">
                                <i data-feather="upload-cloud"></i>
                                <span>Darg and Drop Image Files</span>
                            </div>
                            <div class="grid md:grid-cols-4 sm:grid-cols-2 gap-5" id="media-preview-div">

                            </div>
                        </div>
                        @error('product_media')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Divider Title --}}
                    <div class="2xl:col-span-5 lg:col-span-4 md:col-span-2 sm:col-span-1">
                        <h1 class="font-semibold">Availability</h1>
                    </div>

                    {{-- Availability --}}
                    <div class="input-group 2xl:col-span-5 lg:col-span-4 md:col-span-2 sm:col-span-1">
                        <div class="space-x-3 flex">
                            @foreach ($availablity::cases() as $item)
                                <div class="input-radio">
                                    <input type="radio" value="{{ $item->value }}" @checked(old('availability') == $item->value)
                                        name="availability" id="availability-{{ $item->value }}" required>
                                    <label for="availability-{{ $item->value }}">{{ $item->label() }}</label>
                                </div>
                            @endforeach
                        </div>
                        @error('availability')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
            </div>
            <div class="panel-card-footer">
                <button type="submit" class="btn-primary-md md:w-fit sm:w-full">Add Product</button>
            </div>
        </figure>
    </form>
@endsection

@section('panel-script')
    <script>
        document.getElementById('product-tab').classList.add('active');

        const handleThumbnailPreview = (event) => {
            if (event.target.files.length == 0) {
                document.getElementById('thumbnail_image').src = "{{ asset('admin/images/default-thumbnail.png') }}";
            } else {
                document.getElementById('thumbnail_image').src = URL.createObjectURL(event.target.files[0])
            }
        }

        new Tagify(document.querySelector('input[name=meta_keywords]'));
        new Tagify(document.querySelector('input[name=tags]'));

        const handleCreateHighlightInput = (highlight) => {

            let parentDiv = document.createElement('div');
            parentDiv.className = "flex space-x-2";

            let highlightInput = document.createElement('input');
            highlightInput.type = "text";
            highlightInput.className = "input-box-md w-full";
            highlightInput.name = "highlights[]";
            highlightInput.value = highlight;
            highlightInput.required = true;
            highlightInput.min = 1;
            highlightInput.max = 250;
            highlightInput.placeholder = "Highlight";

            let remove = document.createElement('button');
            remove.className = "btn-danger-md";
            remove.innerHTML = ' &times ';
            remove.type = "button";
            remove.onclick = (event) => {
                event.target.parentNode.remove();
            }

            parentDiv.append(highlightInput, remove);
            document.getElementById('highlights-inputs').appendChild(parentDiv);
        }

        const handleCreateSizeInput = (value, price_original, price_discounted) => {

            let parentDiv = document.createElement('div');
            parentDiv.className = "flex space-x-2";

            let sizeValueInput = document.createElement('input');
            sizeValueInput.type = "text";
            sizeValueInput.className = "input-box-md w-full";
            sizeValueInput.name = "sizes_value[]";
            sizeValueInput.value = value;
            sizeValueInput.required = true;
            sizeValueInput.min = 1;
            sizeValueInput.max = 250;
            sizeValueInput.placeholder = "Enter Size Value";

            let sizePriceOriginalInput = document.createElement('input');
            sizePriceOriginalInput.type = "number";
            sizePriceOriginalInput.className = "input-box-md w-full";
            sizePriceOriginalInput.name = "sizes_price_original[]";
            sizePriceOriginalInput.value = price_original;
            sizePriceOriginalInput.required = true;
            sizePriceOriginalInput.step = "any";
            sizePriceOriginalInput.min = 0;
            sizePriceOriginalInput.max = 100000;
            sizePriceOriginalInput.placeholder = "Enter Original Price";

            let sizePriceDiscountedInput = document.createElement('input');
            sizePriceDiscountedInput.type = "number";
            sizePriceDiscountedInput.className = "input-box-md w-full";
            sizePriceDiscountedInput.name = "sizes_price_discounted[]";
            sizePriceDiscountedInput.value = price_discounted;
            sizePriceDiscountedInput.step = "any";
            sizePriceDiscountedInput.min = 0;
            sizePriceDiscountedInput.max = 100000;
            sizePriceDiscountedInput.placeholder = "Enter Discounted Price";

            let remove = document.createElement('button');
            remove.className = "btn-danger-md";
            remove.innerHTML = ' &times ';
            remove.type = "button";
            remove.onclick = (event) => {
                event.target.parentNode.remove();
            }

            parentDiv.append(sizeValueInput, sizePriceOriginalInput, sizePriceDiscountedInput, remove);
            document.getElementById('sizes-inputs').appendChild(parentDiv);
        }

        const handleMediaPreview = (event) => {
            document.getElementById('media-preview-div').replaceChildren();
            Object.values(event.target.files).forEach(file => {

                let parentDiv = document.createElement('div');
                parentDiv.className =
                    "h-fit w-full flex flex-col items-center justify-center overflow-hidden border rounded-md p-2";

                switch (file.type) {
                    case "image/jpeg":
                    case "image/jpg":
                    case "image/png":
                    case "image/webp":
                        var element = document.createElement('img');
                        element.className = "h-auto w-wull border rounded";
                        element.src = URL.createObjectURL(file);
                        parentDiv.appendChild(element);
                        break;

                    case "video/mp4":
                        var element = document.createElement('video');
                        element.className = "h-auto w-wull border rounded";
                        element.controls = true;
                        element.src = URL.createObjectURL(file);
                        parentDiv.appendChild(element);
                        break;

                    default:
                        var element = document.createElement('a');
                        element.className = "btn-secondary-md";
                        element.innerHTML = "Preview";
                        element.target = "_blank";
                        element.href = URL.createObjectURL(file);
                        parentDiv.appendChild(element);
                        break;
                }

                var name = document.createElement('p');
                name.className = "text-xs text-center mt-2 font-medium";
                name.innerHTML = file.name;
                parentDiv.appendChild(name);

                document.getElementById('media-preview-div').appendChild(parentDiv);
            });
        }
    </script>
@endsection
