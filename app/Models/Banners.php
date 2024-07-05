<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\ModelNotFoundException;

class Banners extends Model
{
    use HasFactory;

    protected $table = 'banners';
    protected $fillable = [
        'app_name',
        'type_file',
        'path_file',
        'status',
        'date',
    ];

    public $timestamps = false;

    public static function saveBanner(string $appName, string $typeFile, string $pathFile)
    {
        date_default_timezone_set('America/Lima');

        return self::create([
            'app_name' => $appName,
            'type_file' => $typeFile,
            'path_file' => $pathFile,
            'status' => 1,
            'date' => date('Y-m-d'),
        ]);
    } 

    public static function getListBanner(string $appName, int $numberItems)
    {
        $listBanner = DB::table('banners as b')
        ->select('b.id', 'b.path_file as file')
        ->where('b.status', '=', '1')
        ->where('b.app_name', $appName)
        ->orderBy('b.date', 'desc')
        ->take($numberItems)
        ->get();

        return $listBanner;
    }

    public static function deleteBanner(int $bannerId)
    {
        $banner = self::find($bannerId);
        if($banner){
            DB::table('banners')->where('id', '=', $bannerId)->delete();
            return $banner->path_file;
        }
    }
}
