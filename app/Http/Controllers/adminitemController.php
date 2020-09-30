<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use DB;
use App\member;
use App\rate;
use App\item;
use App\favorite_item;
use App\category;
use App\maincategory;
use App\setting;
use App\weight;

class adminitemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $mainactive = 'items';
        $subactive  = 'item';
        $logo       = DB::table('settings')->value('logo');
        $allitems   = item::orderBy('id', 'desc')->get();

        return view('admin.items.index', compact('mainactive', 'logo', 'subactive', 'allitems'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $mainactive = 'items';
        $subactive  = 'additem';
        $logo       = DB::table('settings')->value('logo');
        return view('admin.items.create', compact('mainactive', 'subactive', 'logo'));
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
            'artitle'     => 'required|max:200',
            'code'        => 'required',
            'price'       => 'required',

        ]);
        $newitem                = new item;
        $newitem->code          = $request['code'];
        $newitem->artitle       = $request['artitle'];
        $newitem->price         = $request['price'];
        $newitem->details       = $request['ardesc'];



        if ($request->hasFile('image')) {
            $image    = $request['image'];
            $filename = rand(0, 9999) . '.' . $image->getClientOriginalExtension();
            $image->move(base_path('users/images/'), $filename);
            $newitem->image = $filename;
        }
        $newitem->save();

        session()->flash('success', 'تم اضافة المنتج بنجاح');
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
        $mainactive = 'items';
        $subactive  = 'item';
        $logo       = DB::table('settings')->value('logo');
        $showitem   = item::findorfail($id);

        return view('admin.items.show', compact('mainactive', 'logo', 'subactive', 'showitem'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $mainactive = 'items';
        $subactive  = 'item';
        $logo       = DB::table('settings')->value('logo');
        $editem     = item::findorfail($id);

        return view('admin.items.edit', compact('mainactive', 'logo', 'subactive', 'editem'));
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
        $upitem = item::find($id);
        if (Input::has('suspensed')) {
            if ($upitem->suspensed == 0) {
                DB::table('items')->where('id', $id)->update(['suspensed' => 1]);
                session()->flash('success', 'تم تعطيل المنتج بنجاح');
                return back();
            } else {
                DB::table('items')->where('id', $id)->update(['suspensed' => 0]);
                session()->flash('success', 'تم تفعيل المنتج بنجاح');
                return back();
            }
        } else {
            $this->validate($request, [
                'artitle'     => 'required|max:200',
                'code'        => 'required',
                'price'       => 'required',

            ]);


            $upitem->code          = $request['code'];
            $upitem->artitle       = $request['artitle'];
            $upitem->price         = $request['price'];
            $upitem->details        = $request['ardesc'];

            if ($request->hasFile('image')) {
                $image    = $request['image'];
                $filename = rand(0, 9999) . '.' . $image->getClientOriginalExtension();
                $image->move(base_path('users/images/'), $filename);
                $upitem->image = $filename;
            }
            $upitem->save();

            session()->flash('success', 'تم تعديل المنتج بنجاح');
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

        $delitem = item::find($id);
        weight::where('item_id', $delitem->id)->delete();
        $delitem->delete();
        session()->flash('success', 'تم حذف المنتج بنجاح');
        return back();
    }

    public function deleteAll(Request $request)
    {
        $ids           = $request->ids;
        $selecteditems = DB::table("items")->whereIn('id', explode(",", $ids))->get();
        foreach ($selecteditems as $item) {

            weight::where('item_id', $item->id)->delete();
            item::where('id', $item->id)->delete();
        }
        return response()->json(['success' => "تم الحذف بنجاح"]);
    }
}