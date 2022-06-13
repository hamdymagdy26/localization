<?php

namespace Dev\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Localization extends Model
{
    use SoftDeletes;

    protected $casts = [
        'active' => 'boolean',
        'default' => 'boolean'
    ];

    protected $guarded = ['id'];
}