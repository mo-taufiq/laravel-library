<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Throwable;

class BookController extends Controller
{
    public function ViewBooks()
    {
        $books = DB::table('books')
            ->join('authors', 'books.author_id', '=', 'authors.author_id')
            ->select('books.*', 'authors.name as author_name')
            ->where('books.data_status', '=', 'active')
            ->get();

        return view('page_books', [
            "title" => "Books",
            "books" => $books,
        ]);
    }

    public function ViewBook($book_id)
    {
        $book = DB::table('books')
            ->join('authors', 'books.author_id', '=', 'authors.author_id')
            ->select('books.*', 'authors.name as author_name')
            ->where('books.book_id', '=', $book_id)
            ->where('books.data_status', '=', 'active')
            ->get();

        if (count($book) === 0) {
            return abort(404, 'Page not found');
        }

        return view('page_detail_book', [
            "title" => "Detail Book",
            "book" => $book[0],
        ]);
    }

    public function ViewAdminBooks()
    {
        $books = DB::table('books')
            ->join('authors', 'books.author_id', '=', 'authors.author_id')
            ->select('books.*', 'authors.name as author_name')
            ->where('books.data_status', '=', 'active')
            ->get();

        return view('page_manage_books', [
            "title" => "Manage Books",
            "books" => $books,
        ]);
    }

    public function ViewAdminAddEditBooks($book_id, Request $request)
    {
        $authors = Author::all();

        $data = [
            "title" => "Add Book",
            "method" => "post",
            "authors" => $authors,
            "role" => $request->session()->get('role'),
            "book" => null
        ];

        if ($book_id !== "add") {
            $book = DB::table('books')
                ->join('authors', 'books.author_id', '=', 'authors.author_id')
                ->select('books.*', 'authors.name as author_name')
                ->where('books.book_id', '=', $book_id)
                ->get();

            $data["title"] = "Edit Book";
            $data["method"] = "put";
            $data["book"] = json_encode($book[0]);
        }

        return view('page_add_edit_book', $data);
    }

    public function BookInsert(Request $request)
    {
        $validator = FacadesValidator::make($request->all(), [
            'title' => 'required',
            'author_id' => 'required',
        ]);

        if ($validator->fails()) {
            $data = $validator->errors()->messages();
            return response()->json([
                "data" => $data,
                "message" => "error validation",
                "success" => false,
            ], 422);
        }

        try {
            $book = Book::create([
                "book_id" => uniqid("BOOK_", true),
                "title" => $validator->validated()["title"],
                "author_id" => $validator->validated()["author_id"],
                "description" => $request->input('description'),
            ]);

            return response()->json([
                "data" => $book,
                "message" => "successfully created",
                "success" => true,
            ], 201);
        } catch (Throwable $error) {
            report($error);

            return response()->json([
                "error" => $error->getMessage(),
                "message" => "error",
                "success" => false,
            ], 500);
        }
    }

    public function BookUpdate($book_id, Request $request)
    {
        $validator = FacadesValidator::make($request->all(), [
            'title' => 'required',
            'author_id' => 'required',
        ]);

        if ($validator->fails()) {
            $data = $validator->errors()->messages();
            return response()->json([
                "data" => $data,
                "message" => "error validation",
                "success" => false,
            ], 422);
        }

        try {
            $book = Book::where('book_id', $book_id)->update([
                "title" => $validator->validated()["title"],
                "author_id" => $validator->validated()["author_id"],
                "description" => $request->input('description'),
            ]);

            return response()->json([
                "data" => $book,
                "message" => "successfully updated",
                "success" => true,
            ], 201);
        } catch (Throwable $error) {
            report($error);

            return response()->json([
                "error" => $error->getMessage(),
                "message" => "error",
                "success" => false,
            ], 500);
        }
    }

    public function BookDelete($book_id)
    {
        try {
            Book::where('book_id', $book_id)->update([
                "data_status" => "deleted"
            ]);

            return response()->json([
                "message" => "successfully deleted",
                "success" => true,
            ], 200);
        } catch (Throwable $error) {
            report($error);

            return response()->json([
                "error" => $error->getMessage(),
                "message" => "error",
                "success" => false,
            ], 500);
        }
    }
}
