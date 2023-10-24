<?php
namespace App\Http\Repository\AuthRepository;

use App\Models\User;

class AuthRepository implements AuthInterface {
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index(): ?object
    {
        $user = $this->user->all();

        return $user;
    }

    public function create($data): object
    {
        $user = $this->user->create($data);

        return $user;

    }

    public function detail($id): ?object
    {
        $user = $this->user->find($id);

        return $user;
    }

    public function detailByEmail($email): ?object
    {
        $user = $this->user->where('email', $email)->first();

        return $user;
    }

    public function update($id, $data): object
    {
        $user = $this->user->find($id);

        $user->update($data);

        return $user;
    }

    public function delete($id): object
    {
        $user = $this->user->find($id);

        $user->delete();

        return $user;
    }


}
