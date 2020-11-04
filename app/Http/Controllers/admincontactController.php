<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contact;
use Mail;
use DB;

class admincontactController extends Controller
{
    public function getContact()
    {
        $mainactive      = 'contact-us';
        $subactive       = 'contact';
        $logo            = DB::table('settings')->value('logo');
        $contact_us      = Contact::all();
        return view('admin.contact_us.index' , compact('mainactive' , 'subactive' , 'logo' , 'contact_us'));
    }

     public function saveContact(Request $request)
     {
        $this->validate($request, [
            'name'         => 'required',
            'email'        => 'required|email',
            'subject'      => 'nallable',
            'phone_number' => 'nullable',
            'message'      => 'required'
        ]);

        $contact = new Contact;

        $contact->name         = $request->name;
        $contact->email        = $request->email;
        $contact->subject      = $request->subject;
        $contact->phone_number = $request->phone_number;
        $contact->message      = $request->message;

        $contact->save();

        \Mail::send('admin.contact_us.contact_email',
             array(
                 'name'           => $request->get('name'),
                 'email'          => $request->get('email'),
                 'subject'        => $request->get('subject'),
                 'phone_number'   => $request->get('phone_number'),
                 'user_message'   => $request->get('message'),

             ), function($message) use ($request)
               {
                  $message->from($request->email);
                  $message->to('amrkahla6@gmail.com');
               });

          return back()->with('success', 'شكرا علي تواصلك معنا !');
    }

    public function showContact($id)
    {
        $mainactive        = 'contact-us';
        $subactive         = 'contact';
        $logo              = DB::table('settings')->value('logo');
        $showmessage       = Contact::find($id);
        return view('admin.contact_us.showMessage', compact('mainactive', 'subactive', 'logo', 'showmessage'));

    }

    public function destroy($id)
    {
        $delmessage = Contact::find($id);
            $delmessage->delete();
            return back();
    }
}
