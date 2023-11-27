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
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

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

            $categories = Category::all();

            return view('admin.pages.category.category-update', [
                'category' => $category,
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
     * Handle Create Category
     *
     * @return RedirectResponse
     */
    public function handleCategoryCreate(Request $request): RedirectResponse
    {
        try {

            $validation = Validator::make($request->all(), [
                'category_id' => ['nullable', 'numeric', 'exists:categories,id'],
                'name' => ['required', 'string', 'min:1', 'max:250', 'unique:categories'],
                'slug' => ['nullable', 'string', 'min:1', 'max:500', 'unique:categories'],
                'summary' => ['nullable', 'string', 'min:1', 'max:500'],
                'description' => ['nullable', 'string', 'min:1', 'max:1000'],
                'thumbnail_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp'],
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
            if ($request->hasFile('thumbnail_image')) {
                $category->thumbnail_image = $request->file('thumbnail_image')->store('categories');
            }
            if ($request->hasFile('cover_image')) {
                $category->cover_image = $request->file('cover_image')->store('categories');
            }
            $category->priority = $request->input('priority');

            if ($request->input('slug'))
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
     * Handle Update Category
     *
     * @return RedirectResponse
     */
    public function handleCategoryUpdate(Request $request, $id): RedirectResponse
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
                'thumbnail_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp'],
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
            if ($request->hasFile('thumbnail_image')) {
                if ($category->thumbnail_image)
                    Storage::delete($category->thumbnail_image);
                $category->thumbnail_image = $request->file('thumbnail_image')->store('categories');
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
     * Handle Toggle Category Status
     *
     * @return Response
     */
    public function handleToggleCategoryStatus(Request $request): Response
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
                ], Response::HTTP_OK);
            }

            $category = Category::find($request->input('category_id'));
            $category->status = !$category->status;
            $category->update();

            return response([
                'status' => true,
                'message' => "Status successfully updated",
                'data' => $category
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
     * Handle Delete Category
     *
     * @return RedirectResponse
     */
    public function handleCategoryDelete($id): RedirectResponse
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
