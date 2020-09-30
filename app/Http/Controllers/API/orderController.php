<?php

namespace App\Http\Controllers\API;

use App\Cutting;
use App\Events\alertNot;
use App\Http\Controllers\API\BaseController as BaseController;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\Request;
use App\member;
use App\notification;
use App\item;
use App\item_image;
use App\order_item;
use App\order;
use App\weight;
use Carbon\Carbon;
use DB;


class orderController extends BaseController
{

    public function makeorder(Request $request)
    {
        if ($request->total != 0) {

            $neworder                = new order();
            $order_number = date('dmY') . rand(0, 999);
            $neworder->order_number  = $order_number;
            $neworder->total         = $request->total;
            $neworder->paid          = $request->paid;
            $neworder->lat          = $request->lat;
            $neworder->lng          = $request->lng;
            $neworder->date          = $request->date;
            $neworder->time          = $request->time;
            $neworder->name          = $request->name;
            $neworder->phone          = $request->phone;
            $neworder->deliver        = $request->deliver;
            $neworder->notes        = $request->notes;
            $neworder->save();

            $orderarr  = $request->orderarr;
            $new_array = json_decode($orderarr, true);
            foreach ($new_array as $arr) {
                $neworderitem = new order_item();
                $neworderitem->order_id = $neworder->id;
                $neworderitem->item_id = $arr['item_id'];
                $neworderitem->qty     = $arr['qty'];
                $neworderitem->price   = $arr['price'];
                $neworderitem->cutting_id   = $arr['cutting_id'];
                if ($arr['weight_id']) {
                    $neworderitem->weight_id   = $arr['weight_id'];
                }
                if ($arr['headType']) {
                    $neworderitem->headType   = $arr['headType'];
                }


                $neworderitem->save();
            }

            // $orderitems  = order_item::where('order_id', $neworderitem->id)->get();
            $orderdetails = array();
            $itemarr      = array();

            foreach ($new_array as $orderitem) {
                $item = item::where('id', $orderitem['item_id'])->first();
                $weight = weight::where('id', $orderitem['weight_id'])->first();

                array_push(
                    $itemarr,
                    array(

                        "item_name"  => $item->artitle,
                        "price"     => $item->price,
                        "weight"  => $weight->weight_name,
                    )
                );
            }


            array_push(
                $orderdetails,
                array(

                    "order_number"  => $order_number,
                    "user_name"     => $request->name,
                    "user_phone"  => $request->phone,
                    "total"         => $request->total,
                    "date"        => $request->date,
                    "paid"          => $request->paid,
                    "deliver"          => $request->deliver,
                    "orderitems"         => $itemarr,
                )
            );

            $errormessage = 'تم ارسال الطلب بنجاح';
            $msg['orderdetails'] = $orderdetails;
            $msg['message'] = $errormessage;
            return $this->sendResponse('success', $msg);
        } else {
            return $this->sendError('success', 'عفوا الطلب غير صحيح من فضلك أضف منتجات');
        }
    }
}