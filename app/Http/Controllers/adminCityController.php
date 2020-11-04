<?php

namespace App\Http\Controllers;

use App\City;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class adminCityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mainactive      = 'cities';
        $subactive       = 'city';

        $logo = DB::table('settings')->value('logo');
        $cities = City::all();
        return view('admin.cities.index',compact('logo','cities','mainactive','subactive'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name_ar'   => 'required|unique:cities',
            'name_en'   => 'required|unique:cities',

         ]);

        $newcity              = new City();
        $newcity->name_ar = $request['name_ar'];
        $newcity->name_en = $request['name_en'];

        if ($request->hasFile('image')) {
            $city = $request['image'];
            $img_name = rand(0, 999) . '.' . $city->getClientOriginalExtension();
            $city->move(base_path('users/images/'), $img_name);
            $newcity->image   = $img_name;
        }
        // dd($newcity);
        $newcity->save();
        session()->flash('success','تم إضافة مدينة جديدة');
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
        $mainactive      = 'cities';
        $subactive       = 'city';
        $logo            = DB::table('settings')->value('logo');
        $showcategory    = Category::find($id);
        $city            = City::find($id);
        $mytotal         = 0;
        return view('admin.cities.show', compact('mainactive', 'subactive', 'logo', 'city' , 'showcategory','mytotal'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $upcity = City::find($id);
        $this->validate($request,[
            'name_ar'   => 'required|unique:cities,name_ar,'.$id,
            'name_en'   => 'required|unique:cities,name_en,'.$id,
         ]);

         $upcity->name_ar     = $request['name_ar'];
         $upcity->name_en     = $request['name_en'];

         if ($request->hasFile('image')) {
            $city = $request['image'];
            $img_name = rand(0, 999) . '.' . $city->getClientOriginalExtension();
            $city->move(base_path('users/images/'), $img_name);
            $upcity->image   = $img_name;
        }

        $upcity->save();
        session()->flash('success','تم تعديل اسم المدينة بنجاح');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delcity = City::find($id);
        // if($delcategory)
        // {
        //     self::delete_parent($id);
        //     session()->flash('success','تم حذف التقطيع بنجاح');
        // }
        $delcity->delete();
        return back();
    }
}
