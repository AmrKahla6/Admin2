<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Appointment;
use App\Category;
use App\Service;
use App\City;
use DB;

class adminappointmentController extends Controller
{
    public function index()
    {
        $mainactive      = 'appointments';
        $subactive       = 'appointment';
        $logo            = DB::table('settings')->value('logo');
        $appointments    = Appointment::all();
        // $allcategories   = category::where('parent',0)->get();
        $category        = Category::all();
        $service         = Service::all();
        $city            = City::all();
        return view('admin.appointments.index', compact('mainactive', 'subactive', 'logo', 'appointments' ,'category','service','city'));
    }
    public function show()
    {
        //
    }
    public function create()
    {
        $mainactive  = "setting";
        $subactive   = "about";
        $logo        = DB::table('settings')->value('logo');
        $appointment = Appointment::find();

        return view('admin.appointments.create', compact('appointment', 'mainactive', 'logo', 'subactive'));
    }
    public function store()
    {
        //
    }
    public function edit()
    {
        //
    }
    public function update()
    {
        //
    }
    public function destroy()
    {
        //
    }
}
