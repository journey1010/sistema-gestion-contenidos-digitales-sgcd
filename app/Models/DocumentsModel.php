<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}