<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;
    protected $table = 'clients';

    protected $fillable = [
        'id','name','last_name','status'
    ];

    protected $hidden = ['created_at','updated_at'];
    protected $dates = ['deleted_at'];

}
