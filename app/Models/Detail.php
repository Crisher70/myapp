<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Detail extends Model
{
    use SoftDeletes;
    protected $table = 'details';

    protected $fillable = [
        'id','sale_id','product_id','quantity', 'sub_total'
    ];

    protected $hidden = ['created_at','updated_at'];
    protected $dates = ['deleted_at'];

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
