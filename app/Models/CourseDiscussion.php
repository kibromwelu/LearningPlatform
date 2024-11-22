<?php

namespace App\Models;

use Carbon\Carbon;
use App\Services\FileService;
use Exception;
use Illuminate\Support\Facades\Storage;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use function PHPUnit\Framework\throwException;

class CourseDiscussion extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $fillable = [
        'parent_id',
        'message',
        'state',
        'learner_id',
        'course_id',
        'filenames'
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];



    public function learner()
    {
        return $this->belongsTo(Identity::class, 'learner_id');
    }
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
    public function discussion()
    {
        return $this->belongsTo(CourseDiscussion::class, 'parent_id');
    }
    public function discussions()
    {
        return $this->hasMany(CourseDiscussion::class, 'parent_id')->with('discussions');
    }

    public function getImageUrlAttribute()
    {
        return $this->filename ? url('/api/auth/post-file/' . $this->filename) : null;
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($discussion) {
            // Delete all child subscriptions before deleting the parent subscription
            $discussion->discussions()->each(function ($child) {
                $child->delete();
            });
        });
    }
    public function activityLogs()
    {
        return $this->morphMany(ActivityLog::class, 'target');
    }
    protected $casts = [
        'filenames' => 'array',
    ];

    public static function register($data)
    {

        // dd($data);
        $post = self::create($data);
        // dd($post->id);
        $activity = [
            "identity_id" => Auth()->user()->identity_id,
            'action_type' => 'post',
            'content_id' => $post->id,
            'content_type' => CourseDiscussion::class,
            'remark' => isset($data['parent_id']) ? 'Coment' :  'created discussion post'
        ];
        ActivityLog::store($activity);
        return self::getOne($post->id);
    }

    public static function updatePost($data, $id)
    {

        Log::info("MMMMMMMMMMMMMM", $data);
        $post = self::find($id);

        $post->update($data);

        return self::getOne($post->id);
    }

    public static function getAll($course_id)
    {
        $discussions = self::with('learner', 'course', 'discussions.learner')->where('course_id', $course_id)->whereNull('parent_id')->orderBy('created_at', 'desc')->get();
        $userId = Auth()->user()->identity_id;
        return $discussions->transform(function ($post) use ($userId) {
            $post->is_mine = $post->learner_id === $userId;
            $post->filepath =  url('/api/auth/post-file/');
            $post->filenames = json_decode($post->filenames);
            // $post->filename = $post->filename ? url('/api/auth/post-file/' . $post->filename) : null;
            $post->time_ago = Carbon::parse($post->created_at)->diffForHumans();
            $post->discussions->transform(function ($comment) use ($userId) {
                $comment->is_mine = $comment->learner_id === $userId;
                $comment->filenames = json_decode($comment->filenames);
                $comment->filepath =  url('/api/auth/post-file/');


                // $comment->filename = $comment->filename ? url('/api/auth/post-file/' . $comment->filename) : null;
                $comment->time_ago = Carbon::parse($comment->created_at)->diffForHumans();
                return $comment;
            });
            return $post;
        });
    }
    public static function getOne($id)
    {

        $discussion = self::with('learner', 'course', 'discussions.learner')->find($id);
        if ($discussion) {
            $discussion->filepath = url('/api/auth/post-file/');
            $discussion->filenames = $discussion->filenames;
            $discussion->discussions->transform(function ($comment) {
                $comment->filenames = $comment->filenames;
                $comment->time_ago = Carbon::parse($comment->created_at)->diffForHumans();
                return $comment;
            });

            return $discussion;
        } else {
            throw new Exception("Not Found", 404);
        }
    }
    public static function getPostChildren($id)
    {
        return self::where('parent_id', $id)->with('learner', 'discussion', 'course')->get();
    }

    public static function removePost($id)
    {
        $discussion = self::find($id);
        if ($discussion) {
            $path = 'uploads/posts/';
            $filenames = json_decode($discussion->filenames);
            DB::beginTransaction();
            try {
                if ($filenames) {
                    foreach ($filenames as $filename) {
                        FileService::deleteFile($path, $filename);
                    }
                }
                $discussion->delete();
                Db::commit();
                return true;
            } catch (Exception $th) {
                Db::rollBack();
                throw $th;
            }
        }
    }

    public static function getPostFile($filename)
    {
        $filepath = '/posts/';
        return  FileService::getFile($filepath, $filename);
    }
}
