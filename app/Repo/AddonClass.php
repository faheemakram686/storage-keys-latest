<?php

namespace App\Repo;

use App\Models\Addon;
use App\Models\Country;


class AddonClass implements Interfaces\AddonInterface
{

    public function getAllAddon()
    {
        $qry=Addon::Query();
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;

    }
    public function getStorageUnitAddon()
    {
        $qry=Addon::Query();
        $qry=$qry->where('category',"=","storage-unit");
        $qry=$qry->orwhere('category',"=","both");
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;

    }

    public function saveAddon($request)
    {
        // TODO: Implement saveAddon() method.
        if(Addon::where('name',$request->addon_name)->first()){
            return response()->json(['error' => 'Addon already exist'], 200);
        }
        $addon=new Addon();
        $addon->name=$request->addon_name;
        $addon->price=$request->addon_price;
        $addon->category=$request->addon_cat;
        $addon->status=$request->status;
        if($addon->save()){
            return response()->json(['success' => 'Record save successfully'], 200);
        }
    }

    public function deleteAddon($id)
    {
        // TODO: Implement deleteAddon() method.
        $addon =Addon::find($id);
        $addon->is_deleted=1;
        $addon->save();
        return 1;
    }

    public function editAddon($id)
    {
        // TODO: Implement editAddon() method.
        return $addon = Addon::find($id);
    }

    public function updateAddon($request)
    {
        // TODO: Implement updateAddon() method.
        $addon=Addon::find($request->addon_id);
        $addon->name=$request->edit_addon_name;
        $addon->price=$request->edit_addon_price;
        $addon->category=$request->edit_cat;
        $addon->status=$request->edit_status;
        $addon->save();
        return 1;
    }
}
