<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'type',
    ];

    /**
     * Get the attribute values for the attribute.
     */
    public function attributeValues()
    {
        return $this->hasMany(AttributeValue::class);
    }

    /**
     * Get the projects that have this attribute.
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'attribute_values')
            ->withPivot('value');
    }
}
