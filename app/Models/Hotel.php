<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'location', 'description', 'image'];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    use HasFactory;
}
