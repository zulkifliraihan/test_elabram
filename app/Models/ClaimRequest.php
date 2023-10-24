<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ClaimRequest extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'category_id', 'team_id', 'request_user_id', 'review_user_id', 'currency_id',
        'status', 'reason', 'date', 'amount', 'description'
    ];

    protected $appends = ['filesUrl'];

    public function category() {
        return $this->belongsTo(CategoryClaim::class,'category_id');
    }

    public function team() {
        return $this->belongsTo(Team::class,'team_id');
    }

    public function requester() {
        return $this->belongsTo(User::class,'request_user_id');
    }

    public function reviewer() {
        return $this->belongsTo(User::class,'review_user_id');
    }

    public function currency() {
        return $this->belongsTo(Country::class, 'currency_id')->select('id', 'name', 'currency_name', 'currency_symbol');
    }

    public function files(): Attribute {
        return Attribute::make(fn () => $this->getMedia('claimrequest-file_support'));
    }

    public function getFilesUrlAttribute() {
        return $this->getMedia('claimrequest-file_support')->map(function (Media $media) {
            return $media->getUrl();
        });
    }
}
