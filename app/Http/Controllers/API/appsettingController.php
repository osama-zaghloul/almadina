<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use Validator;
use DB;
use App\notification;
use App\setting;
use App\contact;
use App\slider;
use App\category;
use App\Cutting;
use App\item;
use App\item_image;
use App\member;
use App\City;
use App\District;
use App\rate;
use App\maincategory;
use App\transfer;
use App\weight;
use Settings;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use LaravelFCM\Facades\FCM;

class appsettingController  extends BaseController
{
    public function settingindex(Request $request)
    {

        $jsonarr              = array();
        $setting              = setting::select('arabout', 'logo')->get();
        $jsonarr['info']      = $setting;
        return $this->sendResponse('success', $jsonarr);
    }

    public function contactus(Request $request)
    {
        $newcontact          = new contact();
        $newcontact->name    = $request->name;
        $newcontact->phone   = $request->phone;
        $newcontact->email   = $request->email;
        $newcontact->message = $request->message;
        $newcontact->save();
        $errormessage =  'تم ارسال الرسالة بنجاح';
        return $this->sendResponse('success', $errormessage);
    }



    public function home(Request $request)
    {

        $items = item::where('suspensed', 0)->orderBy('id', 'desc')->get();

        return $this->sendResponse('success', $items);
    }



    public function addtransfer(Request $request)
    {
        $newtransfer                = new transfer();
        $newtransfer->name          = $request->name;
        $newtransfer->phone         = $request->phone;
        $newtransfer->bank_name         = $request->bank_name;
        // $newtransfer->bill_number   = $request->bill_number;
        $info =  DB::table('orders')->where('order_number', $request->bill_number)->first();

        if ($info) {
            $newtransfer->bill_number   = $request->bill_number;
        }

        if ($request->hasFile('image')) {
            $image    = $request['image'];
            $filename = rand(0, 9999) . '.' . $image->getClientOriginalExtension();
            $image->move(base_path('users/images/'), $filename);
            $newtransfer->image = $filename;
        }
        $newtransfer->save();


        $errormessage = 'تم ارسال التحويل بنجاح';
        return $this->sendResponse('success', $errormessage);
    }
}