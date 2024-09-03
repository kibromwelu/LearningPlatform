<?php

namespace App\Models;
use Illuminate\Support\Facades\Storage;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use App\Http\Requests\UpdateprofileRequest;
use Carbon\Carbon;

class Profile extends Model
{
    // use RecordState;

    // use HasUuids;
    public $incrementing = false;
    protected $fillable = [
        'id',
        'identity_id',
        'age',
        'biography',
        'category',
        'religion',
        'marital_status',
        'education_level',
        'mother_tongue_language',
        'income_source',
        'occupation',
        'employment_term',
        'organization',
        'household_size',
        'height',
        'weight',
        'avatar',
        'cover',
        'file_number',
        'state',
        'status',
        'purpose'
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];


    public static function change($request, $iid)
    {
        Profile::where('identity_id', $iid)->firstOrFail()->update($request);
        return Profile::where('identity_id', $iid)->firstOrFail();
    }
    public static function register($data){
        // dd($data);
        $profile = self::create($data);
        $profile = self::updateOrCreate(['identity_id'=>$data['identity_id']],$data);
        return $profile;
    }
    public static function updateProfile( $request,$id)
    {
        $data = $request->all();
        // dd($data);
        $data['identity_id']= $id;
        if ($request->hasFile('cover')) {
            $cover = $request->file('cover');
            $coverFileName = time() . '_cover_' . $cover->getClientOriginalName();
            $cover->storeAs('uploads/covers', $coverFileName, 'public');
            $data['cover'] = $coverFileName;
        }
        // dd($data);
        if ($request->hasFile('profile_pic')) {
            $file = $request->file('profile_pic');
            $fileName = time() . '_profile_' . $file->getClientOriginalName();
            $file->storeAs('uploads/profiles', $fileName, 'public');
            $data['avatar'] = $fileName;
        }
        $profile = self::where('identity_id',$id)->first();
        $profile_pic = $profile->avatar ?? null;
        $old_cover = $profile->cover ?? null;
        if(!$profile){
            $data['identity_id'] = $id;
        }
        // dd($data);
       
        $profile = $profile ? $profile->update($data) : self::register($data);
        $profile = self::where('identity_id',$id)->first();
            
            
        if($request->hasFile('profile_pic') && $profile_pic){
            $oldProfilePic = $profile_pic;
            $filePath = 'uploads/profiles/'.$oldProfilePic;
            if (Storage::disk('public')->exists($filePath)) {
                 Storage::disk('public')->delete($filePath);
            }
        }
        if($request->hasFile('cover' && $old_cover)){
            $oldProfilePic = $old_cover;
            $filePath = 'uploads/covers/'.$oldProfilePic;
            if (Storage::disk('public')->exists($filePath)) {
                 Storage::disk('public')->delete($filePath);
            }
        }
        return $profile;
    }
   
    public function identity()
    { 
        return $this->belongsTo(Models::IDENTITY)
            ->select(
                'id',
                'id_number',
                'first_name',
                'middle_name',
                'last_name'
            );
    }
    // public function scopeActive(Builder $query): void
    // {
    //     $query->where('state', '<>', -1);
    // }
    public static function calculateAge($birth_date)
    {
        return Carbon::parse($birth_date)->age;
    }
    public static function getMyProfile($identityId){
        // dd($identityId);
        return self::where('identity_id', $identityId)->first();
    }
}
