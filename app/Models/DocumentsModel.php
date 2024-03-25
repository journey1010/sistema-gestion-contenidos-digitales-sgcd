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

    public static function saveDoc()
    {
        
    }
}