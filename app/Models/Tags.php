<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Products;

class Tags extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $table = "tags";

    protected $fillable = [
        'tag_name' 
    ];
    
    public function product(){
        return $this->belongsTo(Products::class);
    }    
}
