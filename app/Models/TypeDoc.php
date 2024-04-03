<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeDoc extends Model
{
    use HasFactory;

    protected $table  = 'type_docs';

    protected $fillable = [
        'name',
        'description',
        'created_at',
        'updated_at',
    ];

    public static function storeDoc(string $name, string $description = null)
    {
        date_default_timezone_set('America/Lima');
        return self::create([
            'name' => $name,
            'description' => $description,
            'created_at' => date('Y-m-d'),
        ]);
    }
}
