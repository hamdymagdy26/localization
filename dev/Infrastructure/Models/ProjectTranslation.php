<?php

namespace Dev\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectTranslation extends Model
{
    protected $guarded = ['id'];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
