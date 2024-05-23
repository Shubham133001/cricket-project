<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class CategoriesController extends Controller
{

    public function add(Request $request)
    {
        try {
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
                    $name = $img->getClientOriginalName();
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
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getcategories()
    {
        try {
            $data = \App\Models\Category::where('parent_id', 0)->with('children')->get();
            return response()->json([
                'success' => true,
                'categories' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function allcategory()
    {
        try {
            $data = \App\Models\Category::where('parent_id', 0)->with('children')->paginate(10);
            return response()->json([
                'success' => true,
                'categories' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    public function getallcategories()
    {
        try {
            $data = \App\Models\Category::get();
            $list = [];

            foreach ($data as $key => $value) {
                $value->slot_count = \App\Models\Slot::where('category_id', $value->id)->count();
                $value->dates = \App\Models\Slot::where('category_id', $value->id)->select('start_date')->groupBy('start_date')->get();
                foreach ($value->dates as $key => $date) {
                    $date->slots = \App\Models\Slot::where('category_id', $value->id)->where('start_date', $date->start_date)->get();
                    $date->slots->map(function ($item) {
                        $item->slot_time = $item->start_time . " - " . $item->end_time;
                        $item->slot_date = $item->start_date . " - " . $item->end_date;
                        $item->days = explode(',', $item->days);
                        return $item;
                    });
                }
                $list[] = $value;
            }
            return response()->json([
                'success' => true,
                'categories' => $list
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    public function getsubcategories(Request $request)
    {
        try {
            $data = \App\Models\Category::where('parent_id', $request->id)->with('children')->get();
            return response()->json([
                'success' => true,
                'categories' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getcategorieswithslots()
    {
        try {
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
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getcategorywithslots(Request $request)
    {
        try {
            $data = \App\Models\Category::where('id', $request->id)->with(['slots'])->first();
            return response()->json([
                'success' => true,
                'category' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function editcategories(Request $request)
    {
        try {
            $data = \App\Models\Category::find($request->id);
            return response()->json([
                'success' => true,
                'category' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updatecategories(Request $request)
    {
        try {
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
                
                foreach ($image as $k =>$img) {
                    $name = $img->getClientOriginalName();
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
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    public function changetype(Request $request)
    {
        try {
            $data = \App\Models\Category::find($request->id);
            $data->type = $request->type;
            $data->save();
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $data = \App\Models\Category::find($request->id);
            $data->delete();
            $data1 = \App\Models\Category::where('parent_id', $request->id)->delete();

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


}
