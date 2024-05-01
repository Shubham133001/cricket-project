<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoriesController extends Controller
{

    public function add(Request $request)
    {
        $slug = \Str::slug($request->name);
        // check if slug already exists
        $check = \App\Models\Category::where('slug', $slug)->count();
        if ($check > 0) {
            $slug = $slug . '-' . $check;
        }
        $data = new \App\Models\Category();
        //check if image is present
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            foreach ($image as $img) {
                $name = time() . '.' . $img->getClientOriginalExtension();
                $destinationPath = storage_path('app/public/images');

                $img->move(
                    $destinationPath,
                    $name
                );
                $imageslist[] = $name;
            }
            $data->image = implode(',', $imageslist);
        }

        $data->name = $request->name;
        $data->slug = $slug;
        $data->description = $request->description;
        // $data->image = $request->image;
        $data->status = $request->status;
        $data->parent_id = $request->parent_id;
        $data->location = $request->location;
        $data->location_data = $request->location_data;
        $data->status = 'Active';
        $data->save();
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function getcategories()
    {
        $data = \App\Models\Category::where('parent_id', 0)->with('children')->get();
        return response()->json([
            'success' => true,
            'categories' => $data
        ]);
    }
    public function getallcategories()
    {
        $data = \App\Models\Category::get();
        return response()->json([
            'success' => true,
            'categories' => $data
        ]);
    }
    public function getsubcategories(Request $request)
    {
        $data = \App\Models\Category::where('parent_id', $request->id)->with('children')->get();
        return response()->json([
            'success' => true,
            'categories' => $data
        ]);
    }

    public function getcategorieswithslots()
    {
        $data = \App\Models\Category::where('parent_id', 0)->with(['children' => function ($query) {
            $query->with('slots', function ($query) {
                $query->with('bookings', function ($query) {
                    $query->where('date', date('Y-m-d'))->where('status', 'Active');
                });
            });
        }, 'slots'])->get();
        return response()->json([
            'success' => true,
            'categories' => $data
        ]);
    }

    public function getcategorywithslots(Request $request)
    {
        $data = \App\Models\Category::where('id', $request->id)->with(['slots'])->first();
        return response()->json([
            'success' => true,
            'category' => $data
        ]);
    }

    public function editcategories(Request $request)
    {
        $data = \App\Models\Category::find($request->id);
        return response()->json([
            'success' => true,
            'category' => $data
        ]);
    }

    public function updatecategories(Request $request)
    {
        $slug = \Str::slug($request->name);
        // check if slug already exists
        $check = \App\Models\Category::where('slug', $slug)->where('id', '!=', $request->id)->count();
        if ($check > 0) {
            $slug = $slug . '-' . $check;
        }
        $data = \App\Models\Category::find($request->id);
        //check if image is present
        $imageslist = [];

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            foreach ($image as $img) {
                $name = time() . '.' . $img->getClientOriginalExtension();
                $destinationPath = storage_path('app/public/images');

                $img->move($destinationPath, $name);
                $imageslist[] = $name;
            }
            $data->image = implode(',', $imageslist);
        }

        $data->name = $request->name;
        $data->description = $request->description;
        $data->status = $request->status;
        $data->parent_id = $request->parent_id;
        $data->status = 'Active';
        $data->location = $request->location;
        $data->location_data = $request->location_data;
        $data->save();
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
    public function changetype(Request $request)
    {
        $data = \App\Models\Category::find($request->id);
        $data->type = $request->type;
        $data->save();
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function delete(Request $request)
    {
        $data = \App\Models\Category::find($request->id);
        $data->delete();
        $data1 = \App\Models\Category::where('parent_id', $request->id)->delete();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
