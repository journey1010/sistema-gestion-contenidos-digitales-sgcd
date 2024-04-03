<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Post extends Model
{
    use HasFactory;

    protected $table =  'publicaciones';

    protected $fillable = [
       'title',
       'description',
       'origin_name_file',
       'path_file',
       'user_id',
       'created_at'
   ];


   public static function savePost(string $title, string $description, string $originNameFile, string $pathFile, int $userId)
   {
        date_default_timezone_set('America/Lima');
        return self::create([
            'title' => $title,
            'description' => $description,
            'origin_name_file' => $originNameFile,
            'path_file' => $pathFile,
            'user_id' => $userId,
            'created_at' => date('Y-m-d'),
        ]);
   }

   public static function getLastEvents($category, $numberItems)
   {
       $eventFileSub = DB::table('event_file')
           ->select('id_event_file', DB::raw('MIN(path_file) as path_file'))
           ->groupBy('id_event_file');

       $eventsWithFiles = DB::table('events')
           ->joinSub($eventFileSub, 'event_files', function ($join) {
               $join->on('events.id', '=', 'event_files.id_event_file');
           })
           ->when($category, function ($query, $category) {
               return $query->where('category_event', $category);
           })
           ->where('status' , '=' , 1)
           ->orderBy('events.created_at', 'desc')
           ->take($numberItems)
           ->select('events.*', 'event_files.path_file')
           ->get();


       if ($eventsWithFiles->isEmpty()) {
           throw new \Exception('Not found data for this category');
       }

       foreach ($eventsWithFiles as $event) {
           $event->files = asset(Storage::url($event->path_file));
       }

       return $eventsWithFiles;

   }

   public static function getPaginateEvents($category, $numberItems, $currentPage)
   {
       $eventFileSub = DB::table('event_file')
           ->select('id_event_file', DB::raw('MIN(path_file) as path_file'))
           ->groupBy('id_event_file');

       $events = DB::table('events')
           ->joinSub($eventFileSub, 'event_files',function($join) {
              $join->on('events.id', '=', 'event_files.id_event_file');
           })
           ->where([['events.category_event', '=', $category], ['events.status', '=', 1]])
           ->orderBy('events.created_at', 'desc')
           ->select('events.id', 'events.title', 'events.body', 'events.activity_date', 'events.author', 'events.created_at', 'path_file')
           ->paginate($numberItems, ['*'], 'page', $currentPage);

       foreach ($events as $event) {
           $event->body = substr($event->body, 0, 40) . '...';
           $event->files = asset(Storage::url($event->path_file));
       }

       $response = [
           'data' => $events->items(),
           'current_page' => $events->currentPage(),
           'total_pages' => $events->total(),
           'per_pages' => $events->perPage(),
           'last_page' => $events->lastPage()
       ];
       
       return $response;
   }

   public static function getSpecificEvent($idEvent)
   {
       $eventsWithFiles = DB::table('events')
       ->join('event_file', 'events.id', '=', 'event_file.id_event_file')
       ->where([
           ['events.id', '=', $idEvent],
           ['events.status', '=', 1]
       ])
       ->orderBy('events.created_at', 'desc')
       ->select('events.*', 'event_file.path_file')
       ->get();

       if ($eventsWithFiles->isEmpty()) {
           return throw new \Exception('Not found data for this id');
       }

       $eventsGrouped = collect();
       
       foreach ($eventsWithFiles as $row) {
           if (!$eventsGrouped->has($row->id)) {
               $eventsGrouped->put($row->id, (object)[
                   'id' => $row->id,
                   'title' => $row->title,
                   'body' => substr($row->body, 0, 80) . '...' ,
                   'activity_date' => $row->activity_date,
                   'category' => $row->category_event,
                   'author' => $row->author,
                   'created_at' => $row->created_at,
                   'files' => [],
               ]);
           }
       
           $event = $eventsGrouped->get($row->id);
           $event->files[] = (object)[
               'path_file' => asset(Storage::url($row->path_file))
           ];
       }
       return $event; 
   }

   public static function updateEvent($datos)
   {   
       extract($datos);
        
       $affeted = DB::table('events')
       ->where('id', '=', $idEvent)
       ->update([
           'title' => $title,
           'body' => $body,
           'activity_date' => $activity_date,
           'category_event' => $category_event,
           'author' => $author,
           'status' => true,
           'updated_at' => date('Y-m-d H:i:s')
       ]);

   }
}
