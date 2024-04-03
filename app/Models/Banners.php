<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banners extends Model
{
    use HasFactory;

    protected $table = 'banners';
    protected $fillable = [
        'type_file',
        'path_file',
        'status',
        'date',
    ];
    public $timestamps = false;

    public static function saveBanner(string $typeFile, string $pathFile)
    {
        date_default_timezone_set('America/Lima');

        return self::create([
            'type_file' => $typeFile,
            'path_file' => $pathFile,
            'status' => 1,
            'date' => date('Y-m-d'),
        ]);
    } 
}
