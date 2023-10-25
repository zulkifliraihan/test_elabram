<?php

namespace App\Models;

use App\Enums\TeamRole;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

    protected $appends = ['currentRole'];

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

        /**
     * Many-to-Many relations with the user model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(config('teamwork.user_model'), config('teamwork.team_user_table'), 'team_id', 'user_id')->withTimestamps();
    }

    public function getCurrentRoleAttribute()
    {
        /** @var App\Models\User */
        $user = auth()->user();

        return TeamRole::tryFrom($user->teams()->firstWhere('team_id', $user->current_team_id)?->pivot->role);
    }
}
