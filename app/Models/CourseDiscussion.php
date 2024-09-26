<?php

namespace App\Models;

use Carbon\Carbon;
use App\Services\FileService;
use Illuminate\Support\Facades\Storage;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'filename'
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];



    public function learner()
    {
        return $this->belongsTo(Learner::class, 'learner_id');
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

    public static function register($data)
    {

        $post = self::create($data);
        return self::getOne($post->id);
    }

    public static function updatePost($data, $id)
    {
        $post = self::find($id);
        $post->update($data);
        return self::getOne($post->id);
    }

    public static function getAll($course_id)
    {
        $discussions = self::with('learner.identity', 'course', 'discussions.learner.identity')->where('course_id', $course_id)->whereNull('parent_id')->orderBy('created_at', 'desc')->get();
        $userId = Auth()->user()->identity_id;
        return $discussions->transform(function ($post) use ($userId) {
            $post->is_mine = $post->learner_id === $userId;
            $post->filename = $post->filename ? url('/api/auth/post-file/' . $post->filename) : null;
            $post->time_ago = Carbon::parse($post->created_at)->diffForHumans();
            $post->discussions->transform(function ($comment) use ($userId) {
                $comment->is_mine = $comment->learner_id === $userId;
                $comment->filename = $comment->filename ? url('/api/auth/post-file/' . $comment->filename) : null;
                $comment->time_ago = Carbon::parse($comment->created_at)->diffForHumans();
                return $comment;
            });
            return $post;
        });
    }
    public static function getOne($id)
    {
        $discussion = self::with('learner.identity', 'course', 'discussions')->find($id);
        $discussion->filename = $discussion->filename ? url('/api/auth/post-file/' . $discussion->filename) : null;
        $discussion->discussions->transform(function ($comment) {
            $comment->filename = $comment->filename ? url('/api/auth/post-file/' . $comment->filename) : null;
            $comment->time_ago = Carbon::parse($comment->created_at)->diffForHumans();
            return $comment;
        });
        return $discussion;
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
            $filename = $discussion->filename;
        }
        $response =  $discussion->delete();
        FileService::deleteFile($path, $filename);
        return $response;
    }

    public static function getPostFile($filename)
    {
        $filepath = 'uploads/posts/';
        return  FileService::getFile($filepath, $filename);
    }
}
