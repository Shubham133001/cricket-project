<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\AdminGroup;
use App\Models\AdminPermission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminContoller extends Controller
{
    public function getadmins(Request $request)
    {
        try {
            $lastdata = array();
            $search = (isset($request->search) && !empty($request->search)) ? $request->search : '';
            $page = (isset($request->page) && !empty($request->page)) ? $request->page : '';
            $itemsPerPage = (isset($request->itemsPerPage) && !empty($request->itemsPerPage)) ? $request->itemsPerPage : '';
            $rowsPerPage = ($itemsPerPage) ? $itemsPerPage : 10;
            $adminquery = Admin::with('admingroup');
            $totalrecords = Admin::with('admingroup')->count();

            if ($search != "") {
                if (strtoupper($search) == "ACTIVE" || strtoupper($search) == "INACTIVE") {
                    $search = (strtoupper($search) == "ACTIVE") ? 1 : 0;
                    $adminquery = $adminquery->where('status', $search);
                } else {
                    $orwhere = "";
                    $checkadmingroupname = AdminGroup::where('name', 'like', '%' . $search . '%')->count();
                    if ($checkadmingroupname > 0) {
                        $getgroupnameid = AdminGroup::where('name', 'like', '%' . $search . '%')->get();
                        if (isset($getgroupnameid[0]->id) && $getgroupnameid[0]->id != "") {
                            $adminquery = $adminquery->where('admin_group_id', $getgroupnameid[0]->id);
                            $orwhere = "or";
                        }
                    }
                    if ($orwhere == "or") {
                        $adminquery = $adminquery->orWhere('email', 'like', '%' . $search . '%')->orWhere('name', 'like', '%' . $search . '%')->orWhere('id', 'like', '%' . $search . '%');
                    } else {
                        $adminquery = $adminquery->where('email', 'like', '%' . $search . '%')->orWhere('name', 'like', '%' . $search . '%')->orWhere('id', 'like', '%' . $search . '%');
                    }
                }
            }

            if ($request->sortBy != "") {
                $sort = $request->sortDesc == 'false' ? 'asc' : 'desc';
                $adminquery = $adminquery->orderBy($request->sortBy, $sort);
            } else {
                $adminquery = $adminquery->orderBy('id', 'desc');
            }

            if ($rowsPerPage == -1) {
                $alladmins = $adminquery->paginate($totalrecords);
            } else {
                $alladmins = $adminquery->paginate($rowsPerPage);
            }


            return response()->json([
                'success' => true,
                'message' => 'data retrived successfully',
                'records' => $alladmins,
                'total' => $totalrecords,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getadminuser(Request $request, $id)
    {
        try {
            $admin = Admin::with('admingroup')->find($id);
            if ($admin) {
                return response()->json([
                    'success' => true,
                    'message' => 'data retrived successfully',
                    'records' => $admin,
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Admin not found',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateadminuser(Request $request, $id)
    {
        try {
            $admin = Admin::find($id);
            if ($admin) {
                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'email' => 'required|unique:admins,email,' . $id,
                    'admin_group_id' => 'required'
                ]);
                if ($validator->fails()) {
                    $errormassageasstring = "";
                    foreach ($validator->errors()->all() as $error) {
                        $errormassageasstring .= $error . "<br>";
                    }
                    return response()->json([
                        'success' => false,
                        'message' => $errormassageasstring,
                    ], 200);
                }

                $status = ($request->status) ? 1 : 0;
                $admin->name = $request->name;
                $admin->email = $request->email;
                if ($request->has('password') && $request['password'] != "") {
                    $admin->password = Hash::make($request->password);
                }
                $admin->admin_group_id = $request->admin_group_id;
                $admin->status = $status;
                $admin->save();
                return response()->json([
                    'success' => true,
                    'message' => 'Admin updated successfully',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Admin not found',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteadminuser(Request $request, $id)
    {
        try {
            if ($id == 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'You can not delete super admin',
                ], 200);
            }
            $admin = Admin::find($id);
            if ($admin) {
                $admin->delete();
                return response()->json([
                    'success' => true,
                    'message' => 'Admin deleted successfully',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Admin not found',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getadmingroups(Request $request)
    {
        // $adminquery = AdminGroup::with('admins');
        // $totalrecords = AdminGroup::with('admins')->count();
        // $alladmins = $adminquery->paginate($totalrecords);
        // return response()->json([
        //     'success' => true,
        //     'message' => 'data retrived successfully',
        //     'records' => $alladmins,
        //     'total' => $totalrecords,
        // ], 200);
        try {
            $groups = AdminGroup::get();
            return response()->json([
                'success' => true,
                'groups' => $groups
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getadmingroupslist(Request $request)
    {
        try {
            $page = (isset($request->page) && !empty($request->page)) ? $request->page : '';
            $itemsPerPage = (isset($request->itemsPerPage) && !empty($request->itemsPerPage)) ? $request->itemsPerPage : '';
            $rowsPerPage = ($itemsPerPage) ? $itemsPerPage : 10;
            $search = (isset($request->search) && !empty($request->search)) ? $request->search : '';

            $adminquery = AdminGroup::with('admins');
            $totalrecords = AdminGroup::with('admins')->count();

            if ($search != "") {
                $orwhere = "";
                $checkadminname = Admin::where('name', 'like', '%' . $search . '%')->count();
                if ($checkadminname > 0) {
                    $getadminid = Admin::where('name', 'like', '%' . $search . '%')->get();
                    //we can make it where in for all will do later
                    if (isset($getadminid[0]->admin_group_id) && $getadminid[0]->admin_group_id != "") {
                        $adminquery = $adminquery->where('id', $getadminid[0]->admin_group_id);
                        $orwhere = "or";
                    }
                }
                if ($orwhere == "or") {
                    $adminquery = $adminquery->orWhere('name', 'like', '%' . $search . '%');
                } else {
                    $adminquery = $adminquery->where('name', 'like', '%' . $search . '%');
                }
            }

            if ($request->sortBy != "") {
                $sort = $request->sortDesc == 'false' ? 'asc' : 'desc';
                $adminquery = $adminquery->orderBy($request->sortBy, $sort);
            } else {
                $adminquery = $adminquery->orderBy('id', 'desc');
            }

            if ($rowsPerPage == -1) {
                $alladmins = $adminquery->paginate($totalrecords);
            } else {
                $alladmins = $adminquery->paginate($rowsPerPage);
            }




            foreach ($alladmins as $key => $grp) {
                $guserarray = [];
                foreach ($grp->admins as $k => $value) {
                    $guserarray[] = $value->name;
                }
                $alladmins[$key]->adminusers = implode(",", $guserarray);
            }
            return response()->json([
                'success' => true,
                'message' => 'data retrived successfully',
                'records' => $alladmins,
                'total' => $totalrecords,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    public function addadminuser(Request $request)
    {
        $fullname = (isset($request->fullname) && !empty($request->fullname)) ? $request->fullname : '';
        $email = (isset($request->email) && !empty($request->email)) ? $request->email : '';
        $password = (isset($request->password) && !empty($request->password)) ? $request->password : '';
        $admingroup = (isset($request->group) && !empty($request->group)) ? $request->group : '1';
        $status = ($request->status) ? 1 : 0;
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|unique:admins',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                ], 200);
            }
            $admin = new Admin();
            $admin->name = $fullname;
            $admin->email = $email;
            $admin->password = Hash::make($password);
            $admin->admin_group_id = $admingroup;
            $admin->status = $status;
            $admin->save();
            return response()->json([
                'success' => true,
                'message' => 'Admin added successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getmessage(),
            ], 200);
        }
    }

    public function deletegrpoup(Request $request, $id)
    {
        try {
            if ($id == 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'You can not delete super admin group',
                ], 200);
            }

            $checkgroupadmins = Admin::where('admin_group_id', $id)->count();
            if ($checkgroupadmins > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'You can not delete this group as it has admins',
                ], 200);
            }


            $admin = AdminGroup::find($id);
            if ($admin) {
                AdminPermission::where('admin_group_id', $id)->delete();
                $admin->delete();
                return response()->json([
                    'success' => true,
                    'message' => 'Admin group deleted successfully',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Admin group not found',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }




    public function addadmingroup(Request $request)
    {
        $groupname = (isset($request->name) && !empty($request->name)) ? $request->name : '';
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:admin_groups,name',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                ], 200);
            }
            $admin = new AdminGroup();
            $admin->name = $groupname;
            $admin->save();
            $groupspermissionarray = array();
            foreach ($request->routes as $key => $value) {

                $url = app('router')->getRoutes()->getByName($value)->uri;
                $groupspermissionarray[$key]["admin_group_id"] = $admin->id;
                $groupspermissionarray[$key]["urlid"] = $key;
                $groupspermissionarray[$key]["url"] = $url;
                $groupspermissionarray[$key]["slug"] = $value;
                $groupspermissionarray[$key]["groupname"] = $value;
            }
            AdminPermission::insert($groupspermissionarray);

            return response()->json([
                'success' => true,
                'message' => 'Admin group added successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getmessage(),
            ], 200);
        }
    }

    public function getgroupadmin(Request $request, $id)
    {
        try {
            $groupdata = AdminGroup::with('permissions')->find($id);
            if ($groupdata) {
                return response()->json([
                    'success' => true,
                    'message' => 'data retrived successfully',
                    'ff' => 'permission',
                    'records' => $groupdata,
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Admin not found',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateadmingroup(Request $request, $id)
    {
        $groupname = (isset($request->name) && !empty($request->name)) ? $request->name : '';
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:admin_groups,name,' . $id,
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                ], 200);
            }
            $admin = AdminGroup::find($id);
            $admin->name = $groupname;
            $admin->save();


            if ($id != 1) {
                if (isset($request->routes) && !empty($request->routes)) {
                    AdminPermission::where('admin_group_id', $id)->delete();
                    $groupspermissionarray = array();
                    foreach ($request->routes as $key => $value) {

                        $url = app('router')->getRoutes()->getByName($value)->uri;
                        $groupspermissionarray[$key]["admin_group_id"] = $admin->id;
                        $groupspermissionarray[$key]["urlid"] = $key;
                        $groupspermissionarray[$key]["url"] = $url;
                        $groupspermissionarray[$key]["slug"] = $value;
                        $groupspermissionarray[$key]["groupname"] = $value;
                    }
                    AdminPermission::insert($groupspermissionarray);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Admin group updated successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getmessage(),
            ], 200);
        }
    }

    public function getRouters(Request $request)
    {
        try {
            $routers = getAllRoutes();
            return response()->json([
                'success' => true,
                'message' => 'data retrived successfully',
                'records' => $routers,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    public function getcurretadmingroup()
    {
        try {
            $admin = Admin::select('admin_group_id')->find(auth()->user()->id);
            return response()->json([
                'success' => true,
                'message' => 'data retrived successfully',
                'data' => $admin,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
