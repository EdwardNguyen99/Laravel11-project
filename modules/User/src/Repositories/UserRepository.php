<?php

namespace Modules\User\src\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Hash;
use Modules\User\src\Models\User;
use Modules\User\src\Repositories\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function getModel()
    {
        return User::class;
    }

    public function getUsers($limit = 10)
    {
        return $this->model->limit($limit)->get();
    }


    public function setPassword($password, $id)
    {
        return $this->update(['password' => $password], $id);
    }

    public function checkPassword($password, $id)
    {
        $user = $this->find($id);
        if (!$user) {
            return false;
        }

        return Hash::check($password, $user->password);
    }
}
