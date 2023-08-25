<?php
namespace App\Repo;

use App\Models\Location;
use App\Models\MeasurementUnit;
use App\Models\StorageType;
use App\Models\Warehouse;
use App\Repo\Interfaces\MeasurementUnitInterface;
use App\Repo\Interfaces\StorageTypeInterface;
use App\Repo\Interfaces\WarehouseInterface;
use Illuminate\Http\Request;

class MeasurementUnitClass implements MeasurementUnitInterface {

    public function saveMeasurementUnit($request)
    {
        // TODO: Implement saveMeasurementUnit() method.
        if(MeasurementUnit::where('value',$request->value)->first()){
            return response()->json(['error' => 'Measurement Unit already exist'], 200);
        }
        $mu=new MeasurementUnit();
        $mu->value=$request->value;
        $mu->status=$request->status;
        if($mu->save()){
            return response()->json(['success' => 'Record save successfully'], 200);
        }
    }

    public function getMeasurementUnit()
    {
        // TODO: Implement getMeasurementUnit() method.
        $qry=MeasurementUnit::Query();
        $qry=$qry->where('is_deleted',0)->orderBy('id','DESC');
        $qry=$qry->get();
        return $qry;
    }

    public function deleteMeasurementUnit($id)
    {
        // TODO: Implement deleteMeasurementUnit() method.
        $country=MeasurementUnit::find($id);
        $country->is_deleted=1;
        $country->save();
        return 1;
    }

    public function editMeasurementUnit($id)
    {
        // TODO: Implement editMeasurementUnit() method.
        return $country=MeasurementUnit::find($id);
    }

    public function updateMeasurementUnit($request)
    {
        // TODO: Implement updateMeasurementUnit() method.
        $country=MeasurementUnit::find($request->id);
        $country->value=$request->e_value;
        $country->status=$request->e_status;
        $country->save();
        return 1;
    }
}
