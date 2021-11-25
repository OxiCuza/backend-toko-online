<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|string
     */
    public function index()
    {
        try {
            $books = Book::with('categories')->paginate(10);

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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
