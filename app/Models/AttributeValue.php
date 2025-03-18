<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'attribute_id',
        'project_id',
        'value',
    ];

    /**
     * Get the attribute that owns the value.
     */
    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    /**
     * Get the project that owns the value.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
