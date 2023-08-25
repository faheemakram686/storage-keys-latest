<?php
namespace App\Repo\Interfaces;

interface MeasurementUnitInterface{
    public function saveMeasurementUnit($request);

    public function getMeasurementUnit();
    public function deleteMeasurementUnit($id);
    public function editMeasurementUnit($id);
    public function updateMeasurementUnit($request);
}
