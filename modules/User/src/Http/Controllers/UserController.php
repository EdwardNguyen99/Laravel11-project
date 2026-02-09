<?php

namespace Modules\User\src\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Modules\User\src\Http\Requests\StoreUserRequest;
use Modules\User\src\Http\Requests\UpdateUserRequest;
use Modules\User\src\Repositories\UserRepository;

class UserController extends Controller
{
    protected $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function index()
    {
        $users = $this->userRepo->all();
        return view('user::lists', compact('users'));
    }

    public function create()
    {
        return view('user::create');
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $this->userRepo->create($data);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit($id)
    {
        $user = $this->userRepo->find($id);

        return view('user::edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $data = $request->validated();

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $this->userRepo->update($data, $id);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $this->userRepo->delete($id);

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
