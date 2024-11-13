<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PollVotes extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = [
        'user_id',
        'poll_id',
        'poll_choice_id'
    ];
    public function poll()
    {
        return $this->belongsTo(Poll::class, 'poll_id');
    }
    public function choice()
    {
        return $this->belongsTo(PollChoice::class, 'choice_id');
    }
    public function user()
    {
        return $this->belongsTo(Identity::class, 'user_id')->select('id', 'first_name', 'last_name');
    }

    public static function choosePoll($data, $pollId)
    {
        $userId =  Auth()->user()->identity_id;
        $existingVote = self::where('poll_id', $pollId)->where('user_id', $userId)->first();
        if ($existingVote) {
            throw new Exception('You have already choosen.', 400);
        } else {
            $data['user_id'] = $userId;
            $data['poll_id'] = $pollId;
            return self::create($data);
        }
    }
    public static function getChoser($pollChoiceId)
    {
        return self::where('poll_choice_id', $pollChoiceId)->with('user')->get();
    }
}
