<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
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

    /*
    |--------------------------------------------------------------------------
    | Handle Category Create
    |--------------------------------------------------------------------------
    */
    public function handleCategoryCreate(Request $request)
    {
        try {

            $validation = Validator::make($request->all(), [
                'category_id' => ['required', 'numeric', 'exists:categories,id'],
                'name' => ['required', 'string', 'min:1', 'max:250', 'unique:categories'],
                'slug' => ['nullable', 'string', 'min:1', 'max:500', 'unique:categories'],
                'summary' => ['required', 'string', 'min:1', 'max:500'],
                'description' => ['nullable', 'string', 'min:1', 'max:1000'],
                'thumbnail' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp'],
                'cover_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp'],
                'priority' => ['nullable', 'numeric']
            ]);

            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            }

            $category = new Category();
            $category->category_id = $request->input('category_id');
            $category->name = $request->input('name');
            $category->summary = $request->input('summary');
            $category->description = $request->input('description');
            $category->thumbnail = $request->file('thumbnail')->store('categories');
            $category->cover_image = $request->file('cover_image')->store('categories');
            $category->priority = $request->input('priority');

            if ($request->has('slug'))
                $category->slug = $request->input('slug');
            else
                $category->slug = Str::random(64);

            $category->save();

            return redirect()->route('admin.view.parent.category.list')->with('message', [
                'status' => 'success',
                'title' => 'Category Created',
                'description' => 'The category is successfully created.'
            ]);
        } catch (Exception $exception) {
            return redirect()->back()->with('message', [
                'status' => 'error',
                'title' => 'An error occcured',
                'description' => $exception->getMessage()
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Handle Category Update
    |--------------------------------------------------------------------------
    */
    public function handleCategoryUpdate(Request $request, $id)
    {
        try {

            $category = Category::find($id);

            if (!$category) {
                return redirect()->back()->with('message', [
                    'status' => 'warning',
                    'title' => 'Category not found',
                    'description' => 'Category not found with specified ID'
                ]);
            }

            $validation = Validator::make($request->all(), [
                'category_id' => ['required', 'numeric', 'exists:categories,id'],
                'name' => ['required', 'string', 'min:1', 'max:250', Rule::unique('categories')->ignore($category->name, 'name')],
                'slug' => ['required', 'string', 'min:1', 'max:500', Rule::unique('categories')->ignore($category->slug, 'slug')],
                'summary' => ['required', 'string', 'min:1', 'max:500'],
                'description' => ['nullable', 'string', 'min:1', 'max:1000'],
                'thumbnail' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp'],
                'cover_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp'],
                'priority' => ['nullable', 'numeric']
            ]);

            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            }

            $category->category_id = $request->input('category_id');
            $category->name = $request->input('name');
            $category->slug = $request->input('slug');
            $category->summary = $request->input('summary');
            $category->description = $request->input('description');
            if ($request->hasFile('thumbnail')) {
                if (!is_null($category->thumbnail))
                    Storage::delete($category->thumbnail);
                $category->thumbnail = $request->file('thumbnail')->store('categories');
            }
            if ($request->hasFile('cover_image')) {
                if (!is_null($category->cover_image))
                    Storage::delete($category->cover_image);
                $category->cover_image = $request->file('cover_image')->store('categories');
            }
            $category->priority = $request->input('priority');
            $category->update();

            return redirect()->route('admin.view.parent.category.list')->with('message', [
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
}
