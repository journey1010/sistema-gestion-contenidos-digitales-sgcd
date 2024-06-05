<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table =  'publicaciones';

    protected $fillable = [
       'app_name',
       'title',
       'description',
       'origin_name_file',
       'path_file',
       'user_id',
       'created_at'
   ];

   public static function savePost(string $appName, string $title, string $description, string $originNameFile, string $pathFile, int $userId)
   {
        date_default_timezone_set('America/Lima');
        return self::create([
            'app_name' => $appName,
            'title' => $title,
            'description' => $description,
            'origin_name_file' => $originNameFile,
            'path_file' => $pathFile,
            'user_id' => $userId,
            'created_at' => date('Y-m-d'),
        ]);
   }

   public static function getPaginatePost(string $appName, int $numberItems, int $currentPage)
   {
        $lists = self::select('id', 'title', 'description', 'path_file as file', 'created_at as date')
                ->where('app_name', $appName)
                ->orderBy('created_at', 'desc')
                ->paginate($numberItems, ['*'], 'page', $currentPage);
        $list = [
            'items' => $lists->items(),
            'total_items' => $lists->total(),
        ];
        return $list;
   }

   public static function getSpecificPost(int $postId)
   {
       return self::select('id', 'description', 'path_file as file', 'created_at as date')
            ->where('id', $postId)
            ->first(); 
   }
}
