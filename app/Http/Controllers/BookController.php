<?php

namespace App\Http\Controllers;

use App\Http\Responses\BaseResponse;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BookController extends Controller
{
    // TODO: get all book 
    public function index(BaseResponse $response)
    {
        // get all categories
        $categories = Category::all();
        // get all authors
        $authors = Author::all();
        // map books collection
        $books = Book::all()->map(function ($book) use ($categories, $authors) {
            // filter category by id category from book
            // append category object to book object
            $book['category'] = $categories->filter(fn ($category) => $category->id == $book->category_id);
            // filter author by id author from book
            // append author object to book object
            $book['author'] = $authors->filter(fn ($author) => $author->id == $book->author_id);
            return $book;
        });

        return $response->success(data: $books);
    }

    // TODO: store book data to database
    public function store(Request $request, BaseResponse $response)
    {
        // validate uer input
        $validated = Validator::make($request->all(), [
            'category_id' => 'required|numeric|min:1',
            'author_id' => 'required|numeric|min:1',
            'title' => 'required|min:3|max:255',
            'published_at' => 'required|date',
            'total_pages' => 'required|numeric|min:1',
            'description' => 'required|min:5',
            'image' => 'required|file|image|mimes:jpg,jpeg,png',
            'book_language' => 'required|min:2|max:20',
            'isbn' => 'required|numeric|unique:books|max:13',
            'publisher' => 'required|min:3|max:100',
            'type' => 'required|min:3|max:20'
        ]);
        // if validation fails return feedback message
        if ($validated->fails()) return $response->error(message: $validated->errors()->all());

        // get validated data
        $validated = $validated->getData();
        // store image name with the host and port
        $validated['image'] = $request->getSchemeAndHttpHost() . '/storage/' . $validated['image']->store('images', 'public');
        // store data to database
        $book = Book::create($validated);
        // send response message
        return $response->success(data: $book, message: 'Book data successfully added!');
    }

    // TODO: get one book by id
    public function show($id)
    {
        // get book data by specified id
        $book = Book::find($id);
        // check book data
        if (empty($book)) return BaseResponse::error('Uknown Book data with id = ' . $id);

        // find category by given category_id from book data
        $category = Category::find($book->category_id);
        // check if category with the given id is exists
        if (empty($category))  return BaseResponse::error('Uknown Category with id = ' . $id);

        // find author by given author_id from book data
        $author = Author::find($book->author_id);
        // check ifauthor with the given id is exists
        if (empty($author))  return BaseResponse::error('Uknown Author data with id = ' . $id);


        // add category object to book object
        $book['category'] = $category;
        // add author object to book object
        $book['author'] = $author;

        return BaseResponse::success($book);
    }

    // TODO: update book by id
    public function update(Request $request, $id)
    {

        // get book data by specified id
        $book = Book::find($id);
        // check book data
        if (empty($book)) return BaseResponse::error('Uknown Book data with id = ' . $id);

        // validate uer input
        $rules = [
            'category_id' => 'numeric|min:1',
            'author_id' => 'numeric|min:1',
            'title' => 'min:3|max:255',
            'published_at' => 'date',
            'total_pages' => 'numeric|min:1',
            'description' => 'min:5',
            'image' => 'file|image|mimes:jpg,jpeg,png',
            'book_language' => 'min:2|max:20',
            'isbn' => 'numeric|digits_between:1,13',
            'publisher' => 'min:3|max:100',
            'type' => 'min:3|max:20'
        ];
        // if isbn number changed
        // add unique validation rules to isbn field
        if ($book->isbn != $request->input('isbn')) $rules['isbn'] .= '|unique:books';
        $validated = Validator::make($request->all(), $rules);

        // if validation fails return feedback message
        if ($validated->fails()) return BaseResponse::error(message: $validated->errors()->all());
        // get validated data
        $validated = $validated->getData();
        // check wheter user upload an image
        if ($request->file('image')) {
            // clean image name from host and port
            $image = Str::of($book->image)->remove($request->schemeAndHttpHost() . '/storage/');
            // delete image from storage
            Storage::disk('public')->delete($image);
            // store new image name with the host and port
            $validated['image'] = $request->getSchemeAndHttpHost() . '/storage/' . $validated['image']->store('images', 'public');
        }
        $book->update($validated);
        return BaseResponse::success(message: 'Book data successfully updated!');
    }


    // TODO: delete book data by id
    public function destroy(Request $request, $id)
    {
        // get book data by specified id
        $book = Book::find($id);
        // check book data
        if (empty($book)) return BaseResponse::error('Uknown Book data with id = ' . $id);

        // clean image name from host and port
        $image = Str::of($book->image)->remove($request->schemeAndHttpHost() . '/storage/');
        // delete image from storage
        Storage::disk('public')->delete($image);
        $book->delete();

        return BaseResponse::success(message: "Book with id $book->id succesfully deleted!",);
    }
}
