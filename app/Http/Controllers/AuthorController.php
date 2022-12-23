<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Http\Responses\BaseResponse;
use App\Models\Author;

class AuthorController extends Controller
{


    // TODO: get all category 
    public function index()
    {
        return BaseResponse::success(Author::all());
    }

    // TODO: store author data to database
    public function store(Request $request, BaseResponse $response)
    {
        // validate user input
        $validated = Validator::make($request->all(), [
            'name' => 'required|min:3|max:50',
            'birth_date' => 'required|date',
            'gender' => 'required|numeric|between:0,1',
            'photo' => 'required|file|image|mimes:jpg,jpeg,png',
            'bio' => 'required|min:4',
            'address' => 'required|min:4',
            'phone_number' => 'required|min:1|max:13|unique:authors',
            'email' => 'required|email|min:3|max:50|unique:authors'

        ]);
        // if validation fails return feedback message
        if ($validated->fails()) return $response->error(message: $validated->errors()->all());

        // get validated data
        $validated = $validated->getData();
        // store photo name with the host and port
        $validated['photo'] = $request->getSchemeAndHttpHost() . '/storage/' . $validated['photo']->store('images/photo', 'public');
        // store validated data to database
        $author = Author::create($validated);
        return BaseResponse::success($author, 'Author data successfully added!');
    }

    // TODO: get one author by id
    public function show($id)
    {
        // check is author data exist
        $author = Author::find($id);
        if (empty($author)) return BaseResponse::error('Uknown author with id = ' . $id);

        // return response data
        return BaseResponse::success($author);
    }

    // TODO: update author by id
    public function update(Request $request, $id)
    {
        // check is author data exist
        $author = Author::find($id);
        if (empty($author)) return BaseResponse::error('Uknown author with id = ' . $id);

        // set validation rules
        $rules =
            [
                'name' => 'min:3|max:50',
                'birth_date' => 'date',
                'gender' => 'numeric|between:0,1',
                'photo' => 'file|image|mimes:jpg,jpeg,png',
                'bio' => 'min:4',
                'address' => 'min:4',
                'phone_number' => 'min:1|max:13',
                'email' => 'email|min:3|max:50'

            ];
        // if phone number / email input chaged
        // add unique validation rule
        if ($request->input('email') != $author->email) $rules['email'] .= '|unique:authors';
        if ($request->input('phone_number') != $author->phone_number) $rules['phone_number'] .= '|unique:authors';
        // validate user input
        $validated = Validator::make($request->all(), $rules);

        // if validation fails return feedback message
        if ($validated->fails()) return BaseResponse::error(message: $validated->errors()->all());

        // get validated data
        $validated = $validated->getData();

        // check wheter user upload an image
        if ($request->file('photo')) {
            // clean image name from host and port
            $logo = Str::of($author->logo)->remove($request->schemeAndHttpHost() . '/storage/');
            // delete logo from storage
            Storage::disk('public')->delete($logo);
            // store new logo name with the host and port
            $validated['photo'] = $request->getSchemeAndHttpHost() . '/storage/' . $validated['photo']->store('images/photo', 'public');
        }
        // update specified author
        $author->update($validated);
        // return response message
        return BaseResponse::success(message: "Author with id = $id was successfully updated!");
    }


    // TODO: delete author data by id
    public function destroy(Request $request, $id)
    {
        // check is author data exist
        $author = Author::find($id);
        if (empty($author)) return BaseResponse::error('Uknown author with id = ' . $id);

        // clean image name from host and port
        $photo = Str::of($author->photo)->remove($request->schemeAndHttpHost() . '/storage/');
        // delete photo from storage
        Storage::disk('public')->delete($photo);
        // delete specified author
        $author->delete();
        return BaseResponse::success(message: "Author with id = $id was successfully deleted!");
    }
}
