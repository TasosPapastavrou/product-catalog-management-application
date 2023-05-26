<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tags;

class Products extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $table = "products";

    protected $fillable = [
        'name','price','code', 'category'
    ];
    
    public function tags(){
        return $this->hasMany(Tags::class);
    }   
}
