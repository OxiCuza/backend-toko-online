<?php

namespace App\Http\Controllers;

use App\Repositories\User\UserRepositoryInterfaces;
use App\Http\Requests\UserRequest as Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    private $userRepository;

    function __construct(UserRepositoryInterfaces $userRepository)
    {
        $this->middleware(function ($request, $next) {
           if (Gate::allows('manage-users')) return $next($request);
           abort(403, 'Anda tidak memiliki akses !');
        });
        $this->userRepository = $userRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $keyword = $request->keyword;
        $status = $request->status;

        if ($status) {
            $params['where'] = [
                ['status', $status]
            ];
        }

        if ($keyword) {
            array_push($params['where'], ['email', 'like', "%$keyword%"]);
        }

        $params['paginate'] = 10;
        $data_user = $this->userRepository->getAllData($params);

        return view('users.index', compact('data_user'));
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
        $user = $this->userRepository->getByPrimaryKey($id);

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = $this->userRepository->getByPrimaryKey($id);

        return view('users.edit', compact('user'));
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
        $data = $request->except(['roles', 'avatar']);
        $data['roles'] = json_encode($request->roles);

        if ($request->file('avatar')) {
            $user = $this->userRepository->getByPrimaryKey($id);
            if ($user->avatar && file_exists(storage_path('app/public/'.$user->avatar))) {
                Storage::delete('public/'.$user->avatar);
            }
            $file = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $file;
        }

        $update = $this->userRepository->updateByPrimaryKey($id, $data);

        if ($update) {
            return redirect()->route('users.edit', $id)->with('status', 'User succesfully updated !');
        } else {
            return redirect()->route('users.edit', $id)->with('status', 'User unsuccesfully updated !');
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
        $destroy = $this->userRepository->deleteByPrimaryKey($id);

        if ($destroy) {
            return redirect()->route('users.index')->with('status', 'User succesfully deleted !');
        } else {
            return redirect()->route('users.index')->with('status', 'User unsuccesfully deleted !');
        }

    }
}
