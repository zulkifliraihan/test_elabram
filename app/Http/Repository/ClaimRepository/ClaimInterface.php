<?php
namespace App\Http\Repository\ClaimRepository;

interface ClaimInterface {
    public function query(): ?object;
    public function index($with = []): ?object;
    public function create($data): object;
    public function detail($id, $with = []): ?object;
    public function update($id, $data): object;
    public function delete($id): object;
}
