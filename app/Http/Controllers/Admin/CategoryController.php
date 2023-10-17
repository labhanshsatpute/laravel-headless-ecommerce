<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Exception;

interface CategoryInterface
{
    public function viewCategoryList();
    public function viewCategoryCreate();
    public function viewCategoryUpdate($id);
    public function handleCategoryCreate(Request $request);
    public function handleCategoryUpdate(Request $request, $id);
    public function handleToggleCategoryStatus(Request $request);
    public function handleCategoryDelete($id);
}

class CategoryController extends Controller implements CategoryInterface
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
     * View Categories List
     *
     * @return mixed
     */
    public function viewCategoryList(): mixed
    {
        try {

            $categories = Category::all();

            return view('admin.pages.category.category-list', [
                'categories' => $categories
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
     * View Category Create
     *
     * @return mixed
     */
    public function viewCategoryCreate(): mixed
    {
        try {

            $categories = Category::all();

            return view('admin.pages.category.category-create', [
                'categories' => $categories
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
     * View Category Update
     *
     * @return mixed
     */
    public function viewCategoryUpdate($id): mixed
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

            return view('admin.pages.category.category-update', [
                'category' => $category
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
     * Create Category
     *
     * @return mixed
     */
    public function handleCategoryCreate(Request $request): mixed
    {
        try {

            $validation = Validator::make($request->all(), [
                'category_id' => ['nullable', 'numeric', 'exists:categories,id'],
                'name' => ['required', 'string', 'min:1', 'max:250', 'unique:categories'],
                'slug' => ['nullable', 'string', 'min:1', 'max:500', 'unique:categories'],
                'summary' => ['nullable', 'string', 'min:1', 'max:500'],
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

            return redirect()->route('admin.view.category.list')->with('message', [
                'status' => 'success',
                'title' => 'Category created',
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

    /**
     * Update Category
     *
     * @return mixed
     */
    public function handleCategoryUpdate(Request $request, $id): mixed
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
                'category_id' => ['nullable', 'numeric', 'exists:categories,id'],
                'name' => [
                    'required', 'string', 'min:1', 'max:250',
                    Rule::unique('categories')->ignore($category->name, 'name')
                ],
                'slug' => [
                    'required', 'string', 'min:1', 'max:500',
                    Rule::unique('categories')->ignore($category->slug, 'slug')
                ],
                'summary' => ['nullable', 'string', 'min:1', 'max:500'],
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
                if ($category->thumbnail)
                    Storage::delete($category->thumbnail);
                $category->thumbnail = $request->file('thumbnail')->store('categories');
            }
            if ($request->hasFile('cover_image')) {
                if ($category->cover_image)
                    Storage::delete($category->cover_image);
                $category->cover_image = $request->file('cover_image')->store('categories');
            }
            $category->priority = $request->input('priority');
            $category->update();

            return redirect()->route('admin.view.category.list')->with('message', [
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
     * Toggle Category Status
     *
     * @return mixed
     */
    public function handleToggleCategoryStatus(Request $request): mixed
    {
        try {

            $validation = Validator::make($request->all(), [
                'category_id' => ['required', 'numeric', 'exists:categories,id']
            ]);

            if ($validation->fails()) {
                return response([
                    'status' => false,
                    'message' => $validation->errors()->first(),
                    'error' => $validation->errors()->getMessages()
                ], 200);
            }

            $category = Category::find($request->input('category_id'));
            $category->status = !$category->status;
            $category->update();

            return response([
                'status' => true,
                'message' => "Status successfully updated",
                'data' => $category
            ], 200);
        } catch (Exception $exception) {
            return response([
                'status' => false,
                'message' => "An error occcured",
                'error' => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Delete Category
     *
     * @return mixed
     */
    public function handleCategoryDelete($id): mixed
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

            $category->delete();

            return redirect()->route('admin.view.category.list')->with('message', [
                'status' => 'success',
                'title' => 'Category deleted',
                'description' => 'The category is successfully deleted.'
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
