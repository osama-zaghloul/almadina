<?php

namespace App\Http\Controllers\API;

use App\City;
use App\Cutting;
use App\Http\Controllers\API\BaseController as BaseController;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
use App\Mail\activationmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\notification;
use App\item;
use App\item_image;
use App\rate;
use App\favorite_item;
use App\order;
use App\member;
use App\setting;
use App\weight;
use Illuminate\Support\Facades\Auth;
use Validator;
use DB;


class itemController extends BaseController
{
    public function allcities(Request $request)
    {

        $keyword   = $request->keyword;

        $allcities = City::when($keyword, function ($query) use ($keyword) {
            return $query->where('name', 'like', '%' . $keyword . '%');
        })->orderBy('id', 'desc')->get();
        if (count($allcities) != 0) {

            return $this->sendResponse('success', $allcities);
        } else {
            $errormessage =  'لا يوجد مدن متاحة';
            return $this->sendError('success', $errormessage);
        }
    }

    public function showitem(Request $request)
    {
        $showitem = item::find($request->item_id);
        if ($showitem) {
            $iteminfo     = array();
            $weights     = array();
            $cuttings     = array();
            $current      = array();

            $weights = weight::where('item_id', $showitem->id)->get();
            $cuttings = Cutting::all();
            $setting = setting::first();

            $current['iteminfo'] = $showitem;
            $current['weights'] = $weights;
            $current['cuttings'] = $cuttings;
            $current['deliver'] = $setting->deliver_price;
            return $this->sendResponse('success', $current);
        } else {
            $errormessage =  'المنتج غير موجود';
            return $this->sendError('success', $errormessage);
        }
    }
}