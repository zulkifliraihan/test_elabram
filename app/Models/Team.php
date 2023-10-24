<?php

namespace App\Models;

use Mpociot\Teamwork\TeamworkTeam;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Mpociot\Teamwork\Traits\TeamworkTeamTrait;

class Team extends Model
{
    use TeamworkTeamTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table;

    /**
     * @var array
     */
    protected $fillable = [
        'owner_id', 'name', 'address', 'phone', 'finish_onboarding_at'
    ];

    /**
     * Creates a new instance of the model.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = Config::get('teamwork.teams_table');
    }
}
