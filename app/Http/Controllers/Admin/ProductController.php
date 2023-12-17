<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ProductAvailability;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductMedia;
use App\Models\ProductSize;
use App\Models\ProductVariant;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

interface ProductInterface
{
    public function viewProductList();
    public function viewProductCreate();
    public function viewProductUpdate($id);
    public function handleProductCreate(Request $request);
    public function handleProductUpdate(Request $request, $id);
    public function handleToggleProductStatus(Request $request);
    public function handleProductDelete($id);
}

class ProductController extends Controller implements ProductInterface
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * View Product List
     *
     * @return mixed
     */
    public function viewProductList(): mixed
    {
        try {

            $products = Product::all();
            $availablity = ProductAvailability::class;

            return view('admin.pages.product.product-list', [
                'products' => $products,
                'availablity' => $availablity
            ]);
        } catch (Exception $exception) {
            return redirect()->back()->with('message', [
                'status' => 'error',
                'title' => 'An error occcured',
                'description' => $exception->getMessage()
            ]);
        }
    }

    /**
     * View Product Create
     *
     * @return mixed
     */
    public function viewProductCreate(): mixed
    {
        try {

            $availablity = ProductAvailability::class;
            $parent_categories = Category::where('category_id', null)->get();
            $child_categories = Category::whereNot('category_id', null)->get();

            return view('admin.pages.product.product-create', [
                'availablity' => $availablity,
                'parent_categories' => $parent_categories,
                'child_categories' => $child_categories
            ]);
        } catch (Exception $exception) {
            return redirect()->back()->with('message', [
                'status' => 'error',
                'title' => 'An error occcured',
                'description' => $exception->getMessage()
            ]);
        }
    }

    /**
     * View Product Update
     *
     * @return mixed
     */
    public function viewProductUpdate($id): mixed
    {
        try {

            $product = Product::find($id);

            if (!$product) {
                return redirect()->back()->with('message', [
                    'status' => 'warning',
                    'title' => 'Coupon not found',
                    'description' => 'Coupon not found with specified ID'
                ]);
            }

            $availablity = ProductAvailability::class;
            $parent_categories = Category::where('category_id', null)->get();
            $child_categories = Category::whereNot('category_id', null)->get();

            return view('admin.pages.product.product-update', [
                'product' => $product,
                'availablity' => $availablity,
                'parent_categories' => $parent_categories,
                'child_categories' => $child_categories
            ]);
        } catch (Exception $exception) {
            return redirect()->back()->with('message', [
                'status' => 'error',
                'title' => 'An error occcured',
                'description' => $exception->getMessage()
            ]);
        }
    }

    /**
     * Handle Create Product
     *
     * @return RedirectResponse
     */
    public function handleProductCreate(Request $request): RedirectResponse
    {
        DB::beginTransaction();
        try {

            $validator = Validator::make($request->all(), [
                'parent_category_id' => ['required', 'numeric', 'exists:categories,id'],
                'child_category_id' => ['nullable', 'numeric', 'exists:categories,id'],
                'name' => ['required', 'string', 'min:1', 'max:250'],
                'sku' => ['nullable', 'string', 'min:1', 'max:250'],
                'slug' => ['nullable', 'string', 'min:1', 'max:500', 'unique:products'],
                'summary' => ['nullable', 'string', 'min:1', 'max:500'],
                'color' => ['nullable', 'string', 'min:1', 'max:20'],
                'description' => ['nullable', 'string', 'min:1', 'max:10000'],
                'tags' => ['nullable', 'json'],
                'highlights' => ['nullable', 'array'],
                'highlights.*' => ['required', 'string', 'min:1', 'max:250'],
                'variant_product_id' => ['nullable', 'array'],
                'variant_product_id.*' => ['required', 'numeric', 'exists:products,id'],
                'sizes_value' => ['nullable', 'array'],
                'sizes_value.*' => ['required', 'string', 'min:1', 'max:250'],
                'sizes_price_original' => ['nullable', 'array'],
                'sizes_price_original.*' => ['required', 'string', 'min:1', 'max:1000'],
                'sizes_price_discounted' => ['nullable', 'array'],
                'sizes_price_discounted.*' => ['required', 'string', 'min:1', 'max:20'],
                'meta_title' => ['required', 'string', 'min:1', 'max:250'],
                'meta_keywords' => ['nullable', 'json'],
                'meta_description' => ['nullable', 'string', 'min:1', 'max:500'],
                'price_original' => ['required', 'numeric', 'min:1', 'max:10000000'],
                'price_discounted' => ['nullable', 'numeric', 'min:1', 'max:10000000'],
                'tax_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
                'availability' => ['required', 'string', new Enum(ProductAvailability::class)],
                'thumbnail_image' => ['required', 'file', 'mimes:png,jpg,jpeg,webp,avif'],
                'product_media' => ['nullable', 'array'],
                'product_media.*' => ['required', 'file', 'mimes:png,jpg,jpeg,webp,avif']
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $product = new Product();
            $product->parent_category_id = $request->input('parent_category_id');
            $product->child_category_id = $request->input('child_category_id');
            $product->name = $request->input('name');
            $product->sku = $request->input('sku');
            $product->summary = $request->input('summary');
            $product->description = $request->input('description');
            $product->color = $request->input('color');
            $product->meta_title = $request->input('meta_title');
            $product->meta_description = $request->input('meta_description');
            $product->price_original = $request->input('price_original');
            $product->price_discounted = $request->input('price_discounted');
            $product->tax_percentage = $request->input('tax_percentage');
            $product->availability = $request->input('availability');
            $product->thumbnail_image = $request->file('thumbnail_image')->store('products');

            if ($request->input('slug'))
                $product->slug = $request->input('slug');
            else
                $product->slug = Str::random(30);

            if ($request->input('highlights')) {
                $highlights = [];
                foreach ($request->input('highlights') as $highlight) {
                    array_push($highlights, $highlight);
                }
                $product->highlights = json_encode($highlights);
            } else {
                $product->highlights = null;
            }

            if ($request->input('tags')) {
                $tags = [];
                foreach (json_decode($request->input('tags')) as $tag) {
                    array_push($tags, $tag->value);
                }
                $product->tags = json_encode($tags);
            } else {
                $product->tags = null;
            }

            if ($request->input('meta_keywords')) {
                $meta_keywords = [];
                foreach (json_decode($request->input('meta_keywords')) as $keyword) {
                    array_push($meta_keywords, $keyword->value);
                }
                $product->meta_keywords = json_encode($meta_keywords);
            } else {
                $product->meta_keywords = null;
            }

            $product->save();

            if ($request->input('sizes_value')) {
                foreach ($request->input('sizes_value') as $key => $size) {
                    $product_size = new ProductSize();
                    $product_size->product_id = $product->id;
                    $product_size->size = $request->input('sizes_value')[$key];
                    $product_size->price_original = $request->input('sizes_price_original')[$key];
                    $product_size->price_discounted = $request->input('sizes_price_discounted')[$key];
                    $product_size->save();
                }
            }

            if ($request->input('variant_product_id')) {
                foreach ($request->input('variant_product_id') as $key => $variant_id) {
                    $product_variant = new ProductVariant();
                    $product_variant->product_id = $product->id;
                    $product_variant->variant_product_id = $variant_id;
                    $product_variant->save();
                }
            }

            if ($request->product_media) {
                foreach ($request->product_media as $key => $file) {
                    if ($request->hasFile('product_media')) {
                        $product_media = new ProductMedia();
                        $product_media->product_id = $product->id;
                        // $product_media->priority = $request->input('product_media_priority')[$key];
                        $product_media->type = $file->getMimeType();
                        $product_media->path = $file->store('products');
                        $product_media->save();
                    }
                }
            }

            DB::commit();

            return redirect()->route('admin.view.product.list')->with('message', [
                'status' => 'success',
                'title' => 'Product created',
                'description' => 'The product is successfully created.'
            ]);
        } catch (Exception $exception) {

            DB::rollBack();

            return redirect()->back()->with('message', [
                'status' => 'error',
                'title' => 'An error occcured',
                'description' => $exception->getMessage()
            ]);
        }
    }

    /**
     * Handle Update Product
     *
     * @return RedirectResponse
     */
    public function handleProductUpdate(Request $request, $id): RedirectResponse
    {
        try {

            $product = Product::find($id);

            if (!$product) {
                return redirect()->back()->with('message', [
                    'status' => 'warning',
                    'title' => 'Product not found',
                    'description' => 'Product not found with specified ID'
                ]);
            }

            $validator = Validator::make($request->all(), [
                'parent_category_id' => ['required', 'numeric', 'exists:categories,id'],
                'child_category_id' => ['nullable', 'numeric', 'exists:categories,id'],
                'name' => ['required', 'string', 'min:1', 'max:250'],
                'sku' => ['required', 'string', 'min:1', 'max:250'],
                'slug' => ['required', 'string', 'min:1', 'max:500', Rule::unique('products')->ignore($product->slug, 'slug')],
                'summary' => ['nullable', 'string', 'min:1', 'max:500'],
                'color' => ['nullable', 'string', 'min:1', 'max:20'],
                'description' => ['nullable', 'string', 'min:1', 'max:10000'],
                'tags' => ['nullable', 'json'],
                'highlights' => ['nullable', 'array'],
                'highlights.*' => ['required', 'string', 'min:1', 'max:250'],
                'variant_product_id' => ['nullable', 'array'],
                'variant_product_id.*' => ['required', 'numeric', 'exists:products,id'],
                'sizes_value' => ['nullable', 'array'],
                'sizes_value.*' => ['required', 'string', 'min:1', 'max:250'],
                'sizes_price_original' => ['nullable', 'array'],
                'sizes_price_original.*' => ['required', 'string', 'min:1', 'max:1000'],
                'sizes_price_discounted' => ['nullable', 'array'],
                'sizes_price_discounted.*' => ['required', 'string', 'min:1', 'max:20'],
                'meta_title' => ['required', 'string', 'min:1', 'max:250'],
                'meta_keywords' => ['nullable', 'json'],
                'meta_description' => ['nullable', 'string', 'min:1', 'max:500'],
                'price_original' => ['required', 'numeric', 'min:1', 'max:10000000'],
                'price_discounted' => ['nullable', 'numeric', 'min:1', 'max:10000000'],
                'tax_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
                'availability' => ['required', 'string', new Enum(ProductAvailability::class)],
                'thumbnail_image' => ['nullable', 'file', 'mimes:png,jpg,jpeg,webp,avif'],
                'product_media' => ['nullable', 'array'],
                'product_media.*' => ['required', 'file', 'mimes:png,jpg,jpeg,webp,avif']
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $product->slug = $request->input('slug');
            $product->parent_category_id = $request->input('parent_category_id');
            $product->child_category_id = $request->input('child_category_id');
            $product->name = $request->input('name');
            $product->sku = $request->input('sku');
            $product->summary = $request->input('summary');
            $product->description = $request->input('description');
            $product->color = $request->input('color');
            $product->meta_title = $request->input('meta_title');
            $product->meta_description = $request->input('meta_description');
            $product->price_original = $request->input('price_original');
            $product->price_discounted = $request->input('price_discounted');
            $product->tax_percentage = $request->input('tax_percentage');
            $product->availability = $request->input('availability');

            if ($request->hasFile('thumbnail_image')) {
                if ($product->thumbnail_image)
                    Storage::delete($product->thumbnail_image);
                $product->thumbnail_image = $request->file('thumbnail_image')->store('products');
            }

            if ($request->input('highlights')) {
                $highlights = [];
                foreach ($request->input('highlights') as $highlight) {
                    array_push($highlights, $highlight);
                }
                $product->highlights = json_encode($highlights);
            } else {
                $product->highlights = null;
            }

            if ($request->input('tags')) {
                $tags = [];
                foreach (json_decode($request->input('tags')) as $tag) {
                    array_push($tags, $tag->value);
                }
                $product->tags = json_encode($tags);
            } else {
                $product->tags = null;
            }

            if ($request->input('meta_keywords')) {
                $meta_keywords = [];
                foreach (json_decode($request->input('meta_keywords')) as $keyword) {
                    array_push($meta_keywords, $keyword->value);
                }
                $product->meta_keywords = json_encode($meta_keywords);
            } else {
                $product->meta_keywords = null;
            }

            $product->update();

            if ($request->input('sizes_value')) {
                foreach ($request->input('sizes_value') as $key => $size) {

                    $product_size = ProductSize::where('product_id', $product->id)
                        ->where('size', $request->input('sizes_value'))
                        ->first();

                    if (!$product_size) {
                        $product_size = new ProductSize();
                        $product_size->product_id = $product->id;
                    }
                    
                    $product_size->size = $request->input('sizes_value')[$key];
                    $product_size->price_original = $request->input('sizes_price_original')[$key];
                    $product_size->price_discounted = $request->input('sizes_price_discounted')[$key];
                    $product_size->save();
                }
            }

            if ($request->input('variant_product_id')) {
                foreach ($request->input('variant_product_id') as $key => $variant_id) {

                    $product_variant = ProductVariant::where('product_id', $product->id)
                        ->where('variant_product_id', $variant_id)
                        ->first();

                    if (!$product_variant) {
                        $product_variant = new ProductVariant();
                        $product_variant->product_id = $product->id;
                        $product_variant->variant_product_id = $variant_id;
                        $product_variant->save();
                    }
                }
            }

            if ($request->product_media) {
                foreach ($request->product_media as $key => $file) {
                    if ($request->hasFile('product_media')) {
                        $product_media = new ProductMedia();
                        $product_media->product_id = $product->id;
                        // $product_media->priority = $request->input('product_media_priority')[$key];
                        $product_media->type = $file->getMimeType();
                        $product_media->path = $file->store('products');
                        $product_media->save();
                    }
                }
            }

            return redirect()->back()->with('message', [
                'status' => 'success',
                'title' => 'Changes saved',
                'description' => 'The changes are successfully saved.'
            ]);
        } catch (Exception $exception) {
            return redirect()->back()->with('message', [
                'status' => 'error',
                'title' => 'An error occcured',
                'description' => $exception->getMessage()
            ]);
        }
    }

    /**
     * Handle Toggle Product Status
     *
     * @return Response
     */
    public function handleToggleProductStatus(Request $request): Response
    {
        try {

            $validator = Validator::make($request->all(), [
                'product_id' => ['required', 'numeric', 'exists:products,id']
            ]);

            if ($validator->fails()) {
                return response([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                    'error' => $validator->errors()->getMessages()
                ], 200);
            }

            $product = Product::find($request->input('product_id'));
            $product->status = !$product->status;
            $product->update();

            return response([
                'status' => true,
                'message' => "Status successfully updated",
                'data' => $product
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response([
                'status' => false,
                'message' => "An error occcured",
                'error' => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Handle Delete Product
     *
     * @return RedirectResponse
     */
    public function handleProductDelete($id): RedirectResponse
    {
        try {

            $product = Product::find($id);

            if (!$product) {
                return redirect()->back()->with('message', [
                    'status' => 'warning',
                    'title' => 'Product not found',
                    'description' => 'Product not found with specified ID'
                ]);
            }

            $product->delete();

            return redirect()->route('admin.view.product.list')->with('message', [
                'status' => 'success',
                'title' => 'Product deleted',
                'description' => 'The product is successfully deleted.'
            ]);
        } catch (Exception $exception) {
            return redirect()->back()->with('message', [
                'status' => 'error',
                'title' => 'An error occcured',
                'description' => $exception->getMessage()
            ]);
        }
    }

    /**
     * Handle Delete Product Size
     *
     * @return RedirectResponse
     */
    public function handleProductSizeDelete($id): RedirectResponse
    {
        try {

            $product_size = ProductSize::find($id);

            if (!$product_size) {
                return redirect()->back()->with('message', [
                    'status' => 'warning',
                    'title' => 'Product size not found',
                    'description' => 'Product size not found with specified ID'
                ]);
            }

            $product_size->delete();

            return redirect()->back()->with('message',[
                'status' => 'success',
                'title' => 'Size Deleted',
                'description' => 'The product size is successfully deleted'
            ]);
        } catch (Exception $exception) {
            return redirect()->back()->with('message', [
                'status' => 'error',
                'title' => 'An error occcured',
                'description' => $exception->getMessage()
            ]);
        }
    }

    /**
     * Handle Delete Product Media
     *
     * @return RedirectResponse
     */
    public function handleProductMediaDelete($id): RedirectResponse
    {
        try {

            $product_media = ProductMedia::find($id);

            if (!$product_media) {
                return redirect()->back()->with('message', [
                    'status' => 'warning',
                    'title' => 'Product media not found',
                    'description' => 'Product media not found with specified ID'
                ]);
            }

            Storage::delete($product_media->path);

            $product_media->delete();

            return redirect()->back()->with('message',[
                'status' => 'success',
                'title' => 'Media Deleted',
                'description' => 'The product media is successfully deleted'
            ]);
        } catch (Exception $exception) {
            return redirect()->back()->with('message', [
                'status' => 'error',
                'title' => 'An error occcured',
                'description' => $exception->getMessage()
            ]);
        }
    }
}
