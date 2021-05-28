<?php

namespace App\Http\Controllers;

use App\Repositories\User\UserRepositoryInterfaces;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private $userRepository;

    function __construct(UserRepositoryInterfaces $userRepository)
    {
        $this->userRepository = $userRepository;    
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("users.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->except(['roles', 'password', 'avatar']);
        $data['roles'] = json_encode($request->roles);
        $data['password'] = Hash::make($request->password);

        if ($request->file('avatar')) {
            $file = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $file;
        }

        $store = $this->userRepository->store($data);

        if ($store) {
            return redirect()->route('users.create')->with('status', 'User successfully created !');
        } else {
            return redirect()->route('users.create')->with('status', 'Something wrong !');
        }
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
