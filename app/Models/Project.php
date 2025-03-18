<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'status',
    ];

    /**
     * Get the users assigned to the project.
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Get the timesheets for the project.
     */
    public function timesheets()
    {
        return $this->hasMany(Timesheet::class);
    }

    /**
     * Get the attribute values for the project.
     */
    public function attributeValues()
    {
        return $this->hasMany(AttributeValue::class);
    }

    /**
     * Get the attributes for the project through attribute values.
     */
    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'attribute_values')
            ->withPivot('value');
    }
}
