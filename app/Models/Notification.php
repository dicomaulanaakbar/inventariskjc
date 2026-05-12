<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{
    protected $fillable = ['type', 'notifiable_id', 'notifiable_type'];

    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }
}
