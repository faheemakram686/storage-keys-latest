<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddonRequest;
use App\Http\Requests\CountryRequest;
use App\Repo\Interfaces\AddonInterface;

use Illuminate\Http\Request;

class AddonController extends Controller
{
    private $addon;

    public function __construct(AddonInterface $addon)
    {
        $this->addon = $addon;
    }


    public function index()
    {
        return view('backend.addon.index');
    }
    public function saveAddon(AddonRequest $request)
    {

        $validated = $request->validated();
        return $this->addon->saveAddon($request);

    }
    public function getAddon(Request $request)
    {

        return $this->addon->getAllAddon();

    }
    public function editAddon(Request $request)
    {
        $res=$this->addon->editAddon($request->id);
        return $res;
    }
    public function updateAddon(Request $request)
    {
        $res=$this->addon->updateAddon($request);
        return response()->json(['success' => 'Record updated successfully'], 200);
    }

    public function deleteAddon(Request $request)
    {
        $this->addon->deleteAddon($request->id);
        return response()->json(['success' => 'Record deleted successfully'], 200);

    }
}
