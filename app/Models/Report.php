<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function object(): BelongsTo
    {
        return $this
            ->belongsTo('App\Models\\' . ucfirst($this->object_type), 'object_id', 'id')
            ->withTrashed();
    }
}
