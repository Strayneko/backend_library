<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use App\Http\Responses\BaseResponse;

class CategoryController extends Controller
{

    // TODO: get all category 
    public function index()
    {
        return BaseResponse::success(Category::all());
    }

    // TODO: store category data to database
    public function store(Request $request, BaseResponse $response)
    {
        // validate user input
        $validated = Validator::make($request->all(), [
            'name' => 'required|min:3|max:50|unique:categories|lowercase',
            'logo' => 'required|file|image|mimes:jpg,jpeg,png',
            'description' => 'required|min:3',
            'age_rating' => 'required|numeric|min:1',

        ]);

        // if validation fails return feedback message
        if ($validated->fails()) return $response->error(message: $validated->errors()->all());

        // get validated data
        $validated = $validated->getData();
        // store image name with the host and port
        $validated['logo'] = $request->getSchemeAndHttpHost() . '/storage/' . $validated['logo']->store('images/logo', 'public');
        // store data to database
        $category = Category::create($validated);
        // send response message
        return $response->success(data: $category, message: 'Book data successfully added!');
    }

    // TODO: get one category by id
    public function show($id)
    {
        $category = Category::find($id);
        // check is category exist
        if (empty($category)) return BaseResponse::error('Uknown category with id = ' . $id);

        // return response message
        return BaseResponse::success($category);
    }

    // TODO: update category by id
    public function update(Request $request, $id)
    {
        // check is category exist
        $category = Category::find($id);
        if (empty($category)) return BaseResponse::error('Uknown category with id = ' . $id);

        // validate user input
        $validated = Validator::make($request->all(), [
            'name' => 'min:3|max:50|unique:categories',
            'logo' => 'file|image|mimes:jpg,jpeg,png',
            'description' => 'min:3',
            'age_rating' => 'numeric|min:1',

        ]);
        // if validation fails return feedback message
        if ($validated->fails()) return BaseResponse::error(message: $validated->errors()->all());

        // get validated data
        $validated = $validated->getData();

        // check wheter user upload an image
        if ($request->file('logo')) {
            // clean image name from host and port
            $logo = Str::of($category->logo)->remove($request->schemeAndHttpHost() . '/storage/');
            // delete logo from storage
            Storage::disk('public')->delete($logo);
            // store new logo name with the host and port
            $validated['logo'] = $request->getSchemeAndHttpHost() . '/storage/' . $validated['logo']->store('images/logo', 'public');
        }
        // update specified category
        $category->update($validated);
        // return response message
        return BaseResponse::success(message: "Category with id = $id was successfully updated!");
    }


    // TODO: delete category data by id
    public function destroy(Request $request, $id)
    {
        // check is category exist
        $category = Category::find($id);
        if (empty($category)) return BaseResponse::error('Uknown category with id = ' . $id);

        // clean image name from host and port
        $logo = Str::of($category->logo)->remove($request->schemeAndHttpHost() . '/storage/');
        // delete logo from storage
        Storage::disk('public')->delete($logo);
        // delete specified category
        $category->delete();
        return BaseResponse::success(message: "Category with id = $id was successfully deleted!");
    }
}
