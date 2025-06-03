<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Core\Auth\Role;
use App\Models\Core\Auth\User;
use App\Repo\Interfaces\UserInterface;
use App\Repo\RoleClass;

use App\Services\Core\Auth\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $user;
    private  $role;

    public function __construct(UserInterface $user,UserService $service)
    {
        $this->role = new RoleClass();
        $this->user = $user;
        $this->service = $service;
    }
   public function index()
    {
         $data['role']=$this->role->getRole();
        return view('backend.users.index')->with(compact('data'));
    }
    public function getUser()
    {
        return $res=$this->user->getUser();
    }
    public function saveUser(Request $request)
    {
         $res=$this->user->saveUser($request);

        return $res;
    }
    public function deleteUser(Request $request)
    {
        $this->user->deleteUser($request->id);
        return response()->json(['success' => 'Record deleted successfully'], 200);
    }
    public function editUser(Request $request)
    {
        $data['role']=$this->role->getRole();
        $data['st']=$this->user->editUser($request->id);
        return $data;
    }
    public function updateUser(Request $request)
    {
//        return $request->all();
        $res=$this->user->updateUser($request);
        return response()->json(['success' => 'Record updated successfully'], 200);
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
