<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BookController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|string
     */
    public function index(Request $request)
    {
        try {
            $books = Book::with('categories');

            $status = $request->get('status');
            if ($status) {
                $books->where('status', $status);
            }

            $books = $books->paginate(10);

            return view('books.index', compact('books'));

        } catch (\Exception $error) {
            return $error->getMessage();
        }
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('books.create');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|string
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $form = $request->except('cover');
            $form['slug'] = Str::slug($request->get('title'));
            $form['created_by'] = Auth::user()->id;

            $cover = $request->file('cover');
            if ($cover) {
                $cover_path = $cover->store('book-covers', 'public');
                $form['cover'] = $cover_path;
            }

            $new_book = new Book();
            $new_book = $new_book->fill($form);
            $new_book->save();
            $new_book->categories()->attach($request->get('categories'));

            DB::commit();

            if ($request->get('save_action') == 'PUBLISH') {
                return redirect()->route('books.create')->with('status', 'Book successfully saved and published');

            } else {
                return redirect()->route('books.create')->with('status', 'Book saved as a draft');

            }

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
        //
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|string
     */
    public function edit($id)
    {
        try {
            $book = Book::find($id);

            return view('books.edit', compact('book'));

        } catch (\Exception $error) {
            return $error->getMessage();
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|string
     */
    public function update(Request $request, $id)
    {
        try {
            $update_book = $request->except('cover');
            $update_book['updated_by'] = Auth::id();

            $book = Book::find($id);

            if ($request->hasFile('cover')) {
                if ($book->cover && file_exists(storage_path("app/public/$book->cover"))) {
                    Storage::delete("public/$book->cover");
                }
                $new_path = $request->file('cover')->store('book-covers', 'public');
                $update_book['cover'] = $new_path;
            }

            $book->fill($update_book);
            $book->save();
            $book->categories()->sync($request->get('categories'));

            return redirect()->route('books.edit', $book->id)->with('status', 'Book successfully updated');

        } catch (\Exception $error) {
            return $error->getMessage();
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|string
     */
    public function destroy($id)
    {
        try {
            Book::find($id)->delete();

            return redirect()->route('books.index')->with('status', 'Book moved to trash');

        } catch (\Exception $error) {
            return $error->getMessage();
        }
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|string
     */
    public function trash()
    {
        try {
            $books = Book::onlyTrashed()->paginate(10);

            return view('books.trash', compact('books'));

        } catch (\Exception $error) {
            return $error->getMessage();
        }

    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|string
     */
    public function restore($id)
    {
        try {
            $book = Book::withTrashed()->find($id);

            if ($book->trashed()) {
                $book->restore();
                return redirect()->route('books.trash')->with('status', 'Book sucessfully restored');

            } else {
                return redirect()->route('books.trash')->with('status', 'Book is not in trash');

            }
        } catch (\Exception $error) {
            return $error->getMessage();
        }

    }

    public function deletePermanent($id)
    {
        try {
            $book = Book::withTrashed()->find($id);

            if ($book->trashed()) {
                $book->categories()->detach();
                $book->forceDelete();

                return redirect()->route('books.trash')->with('status', 'Book permanently deleted !');
            } else {
                return redirect()->route('books.trash')->with('status', 'Book is not in trash !')->with('status_type', 'alert');
            }
        } catch (\Exception $error) {
            return $error->getMessage();
        }

    }
}
