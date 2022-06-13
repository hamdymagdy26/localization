<?php

namespace Dev\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Project
 * @package Dev\Infrastructure\Models
 */
class Project extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $guarded = ['id'];

    public function management()
    {
        return $this->belongsTo(Management::class, 'management_id', 'id');
    }

    public function studentGenders()
    {
        return $this->hasMany(ProjectStudentGender::class, 'project_id', 'id');
    }

    public function translations()
    {
        return $this->hasMany(ProjectTranslation::class, 'project_id', 'id');
    }

    public function translation()
    {
        return $this->hasOne(ProjectTranslation::class, 'project_id', 'id')->withDefault([
            'name' => null,
            'description' => null
        ]);
    }
}
