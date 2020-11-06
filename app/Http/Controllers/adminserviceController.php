<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use DB;
use App\member;
use App\Service;
use App\Category;
use App\order;
use App\order_item;
use App\setting;
use App\Servceimage;
use App\Icon;
class adminserviceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $mainactive = 'services';
        $subactive  = 'service';
        $logo       = DB::table('settings')->value('logo');
        $services   = Service::orderBy('id', 'desc')->get();

        return view('admin.services.index', compact('mainactive', 'logo', 'subactive', 'services'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $mainactive   = 'services';
        $subactive    = 'addservice';
        $logo         = DB::table('settings')->value('logo');
        $categories   = Category::get();
        $icons        = Icon::get();
        return view('admin.services.create', compact('mainactive', 'subactive', 'logo' ,'categories' , 'icons'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'name'              => 'required|max:200',
            'des'               => 'required',
            'price'             => 'required',
            'icon'              => 'required',
            'category_id'       => 'required',
        ]);
        $newservice                        = new Service;
        $newservice->name                  = $request['name'];
        $newservice->price                 = $request['price'];
        $newservice->des                   = $request['des'];
        $newservice->icon                   = $request['icon'];
        $newservice->category_id           = $request['category_id'];

        if ($request->hasFile('image')) {
            $city = $request['image'];
            $img_name = rand(0, 999) . '.' . $city->getClientOriginalExtension();
            $city->move(base_path('users/images/'), $img_name);
            $newservice->image   = $img_name;
        }
        // dd($newservice);
        $newservice->save();

        session()->flash('success', 'تم اضافة الخدمه بنجاح');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $mainactive      = 'services';
        $subactive       = 'addservice';
        $logo            = DB::table('settings')->value('logo');
        $showservice     = Service::findorfail($id);
        $category        = Service::all();
        $icons           = Icon::first();
        return view('admin.services.show', compact('mainactive', 'logo', 'subactive', 'showservice','category' ,'icons'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $mainactive   = 'services';
        $subactive    = 'addservice';
        $logo         = DB::table('settings')->value('logo');
        $categories   = Category::all();
        $service      = Service::findorfail($id);
        $icons        = Icon::get();
        return view('admin.services.edit', compact('mainactive', 'logo', 'subactive', 'service' , 'categories', 'icons'));
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
        $newservice = Service::find($id);
        if (request()->has('servReq')) {
            if ($newservice->servReq == 0) {
                DB::table('services')->where('id', $id)->update(['servReq' => 1]);
                session()->flash('success', 'تم طلب الخدمه بنجاح');
                return back();
            } else {
                DB::table('services')->where('id', $id)->update(['servReq' => 0]);
                session()->flash('success', 'تم الغاء الخدمه بنجاح');
                return back();
            }
        } else {
            $this->validate($request, [
                'name'              => 'required|max:200',
                'des'               => 'required',
               'price'              => 'required',
               'icon'               => 'required',
              'category_id'         => 'required|exists:categories,id',
            ]);
            $newservice->name                  = $request['name'];
            $newservice->price                 = $request['price'];
            $newservice->des                   = $request['des'];
            $newservice->icon                  = $request['icon'];
            $newservice->category_id           = $request['category_id'];

            if ($request->hasFile('image')) {
                $city = $request['image'];
                $img_name = rand(0, 999) . '.' . $city->getClientOriginalExtension();
                $city->move(base_path('users/images/'), $img_name);
                $newservice->image   = $img_name;
            }
            //  dd($newservice);

            $newservice->save();

            session()->flash('success', 'تم تعديل الخدمه بنجاح');
            return back();
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
        $delitem = Service::find($id);
        $delitem->delete();
        session()->flash('success', 'تم حذف الخدمه بنجاح');
        return back();
    }
}
