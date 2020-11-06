<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use App\Mail\notificationmail;
use App\Mail\contactmail;
use Illuminate\Support\Facades\Mail;
use DB;
use Carbon\Carbon;
use App\order_item;
use App\Category;
use App\City;
use App\Comment;
use App\Rate;
use App\Categoryimages;

class adminCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mainactive      = 'categories';
        $subactive       = 'category';
        $logo            = DB::table('settings')->value('logo');
        $cities          = City::all();
        $categories      = Category::orderBy('id', 'desc')->get();
        // $allcategories   = category::where('parent',0)->get();
        // $categories = Category::all();
        return view('admin.categories.index', compact('mainactive', 'subactive', 'logo', 'categories' , 'cities'));
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
        $this->validate($request, [
            'name'      => 'required',
            'des'       => 'required',
            'city_id'   => 'required',
        ]);
        $newcategory              = new Category;
        $newcategory->name        = $request['name'];
        $newcategory->des         = $request['des'];
        $newcategory->city_id     = $request['city_id'];
        $newcategory->lat         = $request['lat'];
        $newcategory->lng         = $request['lng'];

        $newcategory->save();

        $items = $request['items'];
        if ($items) {
            foreach ($items as $item) {
                $newimg = new Categoryimages;
                $img_name = rand(0, 999) . '.' . $item->getClientOriginalExtension();
                $item->move(base_path('users/images/'), $img_name);
                $newimg->image   = $img_name;
                $newimg->category_id = $newcategory->id;
                $newimg->save();
            }
        }

        $newcategory->save();
        session()->flash('success', 'تم اضافة  صالون جديد');
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
        $mainactive      = 'categories';
        $subactive       = 'category';
        $logo            = DB::table('settings')->value('logo');
        $category        = Category::find($id);
        $cities          = City::all();
        $adimg           = Categoryimages::where('category_id', $id)->first();
        $adimages        = Categoryimages::where('category_id', $id)->get();
        return view('admin.categories.show', compact('mainactive', 'subactive', 'logo', 'cities' , 'category' , 'adimg' , 'adimages'));
        }

        public function rating(Request $request)
        {
            request()->validate([
                'rate' => 'required'
            ]);
            $category = Category::find($request->id);
            $rating   = new \willvincent\Rateable\Rating;
            $rating->rating = $request->rate;
            $rating->user_id = auth()->user()->id;
            $category->ratings()->save($rating);
            session()->flash('success', 'تم اضافة تقيم');
            return back();
        }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $mainactive      = 'categories';
        $subactive       = 'category';
        $logo            = DB::table('settings')->value('logo');
        $cities          = City::orderBy('id', 'desc')->get();
        $adimages        = Categoryimages::where('category_id', $id)->get();
        // $allcategories   = category::where('parent',0)->get();
        $category        = Category::find($id);
        return view('admin.categories.edit', compact('mainactive', 'subactive', 'logo', 'category' , 'cities'));
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
        $upcategory = Category::find($id);
        $this->validate($request, [
            'name'      => 'required',
            'des'       => 'required',
            'city_id'   => 'required',
        ]);

        $upcategory->name        = $request['name'];
        $upcategory->des         = $request['des'];
        $upcategory->city_id     = $request['city_id'];
        $upcategory->lat         = $request['lat'];
        $upcategory->lng         = $request['lng'];

        $upcategory->save();

        $items = $request['items'];

        if ($items) {
            foreach ($items as $item) {
                $newimg   = new Categoryimages;
                $img_name = rand(0, 999) . '.' . $item->getClientOriginalExtension();
                $item->move(base_path('users/images/'), $img_name);
                $newimg->image   = $img_name;
                $newimg->category_id = $id;
                $newimg->save();
            }
        }

        session()->flash('success', 'تم تعديل الصالون بنجاح');
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
        if (request()->has('del-single-image')) {
            $delimg = Categoryimages::find($id)->delete();
            session()->flash('success', 'تم حذف الصورة بنجاح');
            return back();
        } else {
            $delcat = Category::find($id);
            Categoryimages::where('category_id', $id)->delete();
            $delcat->delete();
            session()->flash('success', 'تم حذف المنتج بنجاح');
            return back();
        }
    }

    // public function deleteAll(Request $request)
    // {
    //     $ids    = $request->ids;
    //     $categories = DB::table("cuttings")->whereIn('id', explode(",", $ids))->get();
    //     foreach ($categories as $category) {
    //         self::delete_parent($category->id);
    //     }
    //     return response()->json(['success' => "تم الحذف بنجاح"]);
    // }

    public function showcomments($id)
    {
        $mainactive = 'categories';
        $subactive  = 'categorycomments';
        $logo       = DB::table('settings')->value('logo');
        $comments   = comment::where('category_id', $id)->get();
        return view('admin.categories.showcomments', compact('mainactive', 'logo', 'subactive', 'comments'));
    }

    public function showrates($id)
    {
        $mainactive   = 'categories';
        $subactive    ='category';
        $logo         = DB::table('settings')->value('logo');
        $convdates    = array();
        $showitem     = category::find($id);
        $allrates     = rate::where('category_id',$id)->orderby('created_date','desc')->get();


        foreach($allrates as $comment)
        {
            $date_arr[]= date('j F Y', strtotime($comment->created_date));
            foreach($date_arr as $convdate)
            {
                if (!in_array($convdate, $convdates))
                {
                    array_push($convdates, $convdate);
                }
            }
        }
        return view('admin.categories.showrates',compact('mainactive','logo','subactive','allrates','showitem','convdates'));
    }
}
