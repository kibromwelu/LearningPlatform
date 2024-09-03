<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    use HasUuids;
    protected $fillable = [
        'name',
        'description',
        'price',
        'user_id',
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function applyDiscount($percentage)
    {
        $this->price = $this->price - ($this->price * ($percentage / 100));
        $this->save();
    }
    public static function createProduct($data)
    {
        return self::create($data);
    }

    public static function updateProduct($data,$id)
    {
        // dd($data);
        $product = self::findOrFail($id);
        // dd($product);
        $product->update($data);
        return $product;
    }

    public static function getAll($num){
        return self::paginate($num);
    }
    public static function getOne($id){
        return self::findOrFail($id);
    }
    public static function deleteProduct($id)
    {
        $product = self::findOrFail($id);
        $product->delete();
        return response()->json(['message'=>"Item Deleted"]);
    }
}
