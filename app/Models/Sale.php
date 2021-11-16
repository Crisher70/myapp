<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use SoftDeletes;
    protected $table = 'sales';

    protected $fillable = [
        'id','client_id','iva','discount', 'total', 'sold_at'
    ];

    protected $hidden = ['created_at','updated_at'];
    protected $dates = ['deleted_at'];

    public function details(){
        return $this->hasMany(Detail::class);
    }

    public function client(){
        return $this->belongsTo(Client::class);
    }

    public static function boot() {
        parent::boot();

        static::deleting(function($sale) { 
             $sale->details()->delete();
        });
    }
}
