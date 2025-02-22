<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable = ['name','address','owner_id','rent_amount'];

    public function owner()
    {
        return $this->belongsTo(User::class,'owner_id');
    }

    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }
}
