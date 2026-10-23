<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'product_id',
        'image_path',
        'image_data',
        'is_primary',
        'sort_order',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'image_data',
    ];

    // PostgreSQL bytea needs a stream to accept raw bytes; other drivers take the string.
    public function setImageDataAttribute($value): void
    {
        $this->attributes['image_data'] = static::binary($value);
    }

    public static function binary($value)
    {
        if (! is_string($value) || \DB::getDriverName() !== 'pgsql') {
            return $value;
        }
        $h = fopen('php://temp', 'r+');
        fwrite($h, $value);
        rewind($h);

        return $h;
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
