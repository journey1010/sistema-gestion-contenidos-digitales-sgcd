<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DocumentsModel extends Model
{
    use HasFactory;

    protected $table = 'docs';

    protected $fillable = [
        'type_doc_id',
        'user_id',
        'title',
        'description',
        'original_file_name',
        'path_file'
    ];

    public static function saveDoc(int $typeDoc, int $userId, string $title, string $description, string $originalName, string $pathFile)
    {
        return self::create([
            'type_doc_id' => $typeDoc,
            'user_id' => $userId,
            'title' => $title,
            'description' => $description,
            'original_file_name' => $originalName,
            'path_file' => $pathFile
        ]);
    }

    public static function listAllDoc(int $itemsPerPage = 4, int $page =1)
    {   
        $lists = DB::table('docs as d')
                ->select('d.title', 'd.description', 'd.path_file as file', 't.name')
                ->join('type_docs as t', 'd.type_doc_id', '=', 't.id')
                ->where('d.type_doc_id', '=', 1)
                ->orderByDesc('d.created_at')
                ->paginate($itemsPerPage, ['*'], 'page', $page);
        $list =[
            'items' => $lists->items(),
            'total_items' => $lists->total(),
        ];

        return $list;
    }  

    public static function listDocPerType(int $itemsPerPage = 4, int $page =1, int $typeDocId)
    {   
        $lists = DB::table('docs as d')
                ->select('d.title', 'd.description', 'd.path_file as file', 't.name')
                ->join('type_docs as t', 'd.type_doc_id', '=', 't.id')
                ->where('d.type_doc_id', '=', $typeDocId)
                ->orderByDesc('d.created_at')
                ->paginate($itemsPerPage, ['*'], 'page', $page);
        $list =[
            'items' => $lists->items(),
            'total_items' => $lists->total(),
        ];

        return $list;
    }  
}