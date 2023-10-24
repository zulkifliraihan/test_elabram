<?php
namespace App\Http\Repository\TeamRepository;

use App\Models\Team;

class TeamRepository implements TeamInterface {
    private $team;

    public function __construct(Team $team)
    {
        $this->team = $team;
    }

    public function query(): ?object
    {
        $teams = $this->team->query();

        return $teams;
    }

    public function index(): ?object
    {
        $teams = $this->team->all();

        return $teams;
    }

    public function create($data): object
    {
        $team = $this->team->create($data);

        return $team;

    }

    public function detail($id): ?object
    {
        $team = $this->team->find($id);

        return $team;
    }

    public function update($id, $data): object
    {
        $team = $this->team->find($id);

        $team->update($data);

        return $team;
    }

    public function delete($id): object
    {
        $team = $this->team->find($id);

        $team->delete();

        return $team;
    }


}
