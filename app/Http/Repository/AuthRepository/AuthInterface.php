<?php
namespace App\Http\Repository\AuthRepository;

interface AuthInterface {
    public function index(): ?object;
    public function create($data): object;
    public function detail($id): ?object;
    public function detailByEmail($email): ?object;
    public function update($id, $data): object;
    public function delete($id): object;
}
