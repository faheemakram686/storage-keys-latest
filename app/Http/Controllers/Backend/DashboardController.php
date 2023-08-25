<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Core\Auth\User;
use App\Repo\Interfaces\AdminDashboardInterface;
use Illuminate\Http\Request;
use Auth;

class DashboardController extends Controller
{

    private $dashboard;

    public function __construct(AdminDashboardInterface $dashboard)
    {
        $this->dashboard = $dashboard;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data['leads']  = $this->dashboard->getLeadsCount();
        $data['estimates']  = $this->dashboard->getEstimatesCount();
        $data['customers']  = $this->dashboard->getCustomersCount();
        $data['contracts']  = $this->dashboard->getContractCount();
        $data['addons']  = $this->dashboard->getAddonsCount();
        $data['products']  = $this->dashboard->getProductsCount();
        $data['storageunits']  = $this->dashboard->getStorageUnitsCount();
        return view('backend.dashboard')->with(compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
