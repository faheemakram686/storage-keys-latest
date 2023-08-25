<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repo\Interfaces\MeasurementUnitInterface;
use Illuminate\Http\Request;

class MeasurementUnitController extends Controller
{
    private $mUnits;

    public function __construct(MeasurementUnitInterface $mUnits)
    {
        $this->mUnits = $mUnits;
    }
    public function index()
    {
        return view('backend.measuement-unit.index');
    }

    public function saveMeasurementUnit(Request $request)
    {

        return $this->mUnits->saveMeasurementUnit($request);
    }
    public function getMeasurementUnit(Request $request)
    {

        return $this->mUnits->getMeasurementUnit();

    }
    public function deleteMeasurementUnit(Request $request)
    {
        $this->mUnits->deleteMeasurementUnit($request->id);
        return response()->json(['success' => 'Record deleted successfully'], 200);

    }
    public function editMeasurementUnit(Request $request)
    {
        $res=$this->mUnits->editMeasurementUnit($request->id);
        return $res;
    }

    public function updateMeasurementUnit(Request $request)
    {
        $res=$this->mUnits->updateMeasurementUnit($request);
        return response()->json(['success' => 'Record updated successfully'], 200);
    }





}
