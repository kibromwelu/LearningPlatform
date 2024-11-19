<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PhpParser\Node\Stmt\TryCatch;

class Poll extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'created_by',
        'content',
        'expires_at',
        'state'
    ];

    public function user()
    {
        return $this->belongsTo(Identity::class, 'created_by');
    }
    public function choices()
    {
        return $this->hasMany(PollChoice::class);
    }
    public function votes()
    {
        return $this->hasMany(PollVotes::class);
    }
    public static function getAll()
    {
        return self::with('user')->with(['choices' => function ($query) {
            $query->withCount('votes');
        }])->get();
    }
    public static function getPoll($pollId)
    {

        return self::with('user')->with(['choices' => function ($query) {
            $query->withCount('votes'); // Count the votes for each choice
        }])->withCount('votes')->find($pollId);

        return self::with('user', 'choices', 'votes')->withCount('votes')->find($pollId);
    }
    public static function store($data)
    {
        DB::beginTransaction();
        try {
            $poll = [];
            $poll['created_by'] = Auth()->user()->identity_id;
            $poll['content'] = $data['content'];
            if (isset($data['expires_at'])) {
                $poll['expires_at'] = $data['expires_at'];
            }

            $choices = $data['choices'];
            $createdPol = self::create($poll);
            foreach ($choices as &$choice) {
                $choice['poll_id'] = $createdPol->id;
                $choice['id'] = (string) Str::uuid();
            }
            PollChoice::store($choices);
            Db::commit();
            return $createdPol;
        } catch (\Throwable $th) {
            Db::rollBack();
            throw new Exception('something went wrong' . $th, 400);
        }
    }
    public static function updatePoll($data, Poll $poll)
    {
        // dd($data);
        $poll->update($data);
        return $poll;
    }
}
