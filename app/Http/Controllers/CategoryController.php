<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Repositories\Category\CategoryRepositoryInterfaces;
use App\Http\Requests\CategoryRequest as Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    private $categoryRepository;

    public function __construct(CategoryRepositoryInterfaces $categoryRepository)
    {
        $this->middleware(function ($request, $next) {
           if (Gate::allows('manage-categories')) return $next($request);
           abort(403, 'Anda tidak memiliki hak akses !');;
        });
        $this->categoryRepository = $categoryRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $keyword = $request->get('name');
            if($keyword){
                $params['where'] = [
                    ['name', 'LIKE', "%$keyword%"]
                ];
            }

            $params['paginate'] = 10;
            $categories = $this->categoryRepository->getAllData($params);

            return view('categories.index', compact('categories'));

        } catch (\Exception $error) {
            return $error->getMessage();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $form = $request->except('image');
            $form['created_by'] = Auth::user()->id;
            $form['slug'] = \Str::slug($form['name'], '-');

            if ($request->file('image')) {
                $img_path = $request->file('image')->store('category_images', 'public');
                $form['image'] = $img_path;
            }

            $this->categoryRepository->store($form);
            DB::commit();

            return redirect()->route('categories.create')->with('status', 'Category successfully created');

        } catch (\Exception $error) {
            DB::rollBack();
            return $error->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $category = $this->categoryRepository->getByPrimaryKey($id);

            return view('categories.show', compact('category'));

        } catch (\Exception $error) {
            return $error->getMessage();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $category = $this->categoryRepository->getByPrimaryKey($id);

            return view('categories.edit', compact('category'));

        } catch (\Exception $error) {
            return $error->getMessage();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $name = $request->only('name');
            $slug = $request->only('slug');

            $form['name'] = $name;
            $form['slug'] = \Str::slug($name, '-');
            $form['updated_by'] = Auth::user()->id;

            $category = $this->categoryRepository->getByPrimaryKey($id);

            if ($request->file('image')) {
                if ($category->image && file_exists(storage_path('app/public/'. $category->image))) {
                    Storage::delete('public/'.$category->name);
                }
                $new_image = $request->file('image')->store('category_images', 'public');
                $form['image'] = $new_image;
            }

            $this->categoryRepository->updateByPrimaryKey($id, $form);

            DB::commit();

            return redirect()->route('categories.edit', compact($id))->with('status', 'Category successfully updated');


        } catch (\Exception $error) {
            DB::rollBack();
            return $error->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->categoryRepository->deleteByPrimaryKey($id);

            return redirect()->route('categories.index')->with('status', 'Category successfully moved to trash');
        } catch (\Exception $error) {
            return $error->getMessage();
        }
    }

    public function trash(Request $request)
    {
        try {
            $keyword = $request->get('name');
            if($keyword){
                $params['where'] = [
                    ['name', 'LIKE', "%$keyword%"]
                ];
            }

            $params['only_trashed'] = true;
            $params['paginate'] = 10;
//            $trashed_categories = $this->categoryRepository->getAllData($params);
            $trashed_categories = \App\Models\Category::onlyTrashed()->paginate(10);

            return view('categories.trash', compact('trashed_categories'));

        } catch (\Exception $error) {
            return $error->getMessage();
        }
    }

    public function restore($id)
    {
        try {
            $category = $this->categoryRepository->restore($id);

            return redirect()->route('categories.index')->with('status', $category);

        } catch (\Exception $error) {
            return $error->getMessage();
        }
    }

    public function deletePermanent($id)
    {
        try {
            $category = $this->categoryRepository->deletePermanent($id);

            return redirect()->route('categories.index')->with('status', $category);

        } catch (\Exception $error) {
            return $error->getMessage();
        }
    }

    public function ajaxSearch (Request $request)
    {
        try {
            $keyword = $request->get('q');
            return Category::where("name", "like", "%$keyword%")->get();

        } catch (\Exception $error) {
            return $error->getMessage();
        }
    }
}
