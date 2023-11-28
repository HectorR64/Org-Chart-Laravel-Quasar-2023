<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
    protected $table = 'personal';
    protected $fillable = ['label', 'expand', 'parent_id', 'department_id'];

    //Relación muchos a uno con Department.
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    //Relacion uno a muchos
    public function children()
    {
        return $this->hasMany(Personal::class, 'parent_id');
    }
    //Relación inversa uno a uno con Personal (padre)
    public function parent()
    {
        return $this->belongsTo(Personal::class, 'parent_id');
    }
}
