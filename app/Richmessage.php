<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;

class RichMessage extends Model
{
    public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dim_image_richmessage';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'richmessage_folder_id',
        'name',
        'alt_text',
        'original_photo_path',
        'rich_message_url',
        'image_size',
    ];

    public function setOriginalPhotoPathAttribute($newPhoto) 
    {
        if($newPhoto){
            $fileImage = $newPhoto;
            $destinationPath = 'images/richmessage/original_richmessage'; // upload path
            $extension = $fileImage->getClientOriginalExtension(); // getting image extension
            $fileName = rand(111111,999999).'.'.$extension; // renameing image
            $this->attributes['original_photo_path'] = \Request::root().$destinationPath.'/'.$fileName;
            $fileImage->move($destinationPath, $fileName); // uploading file to given path
        }
    }

    // public function getOriginalPhotoPathAttribute()
    // {
    //     $path = ($this->attributes['original_photo_path'] != '')? asset('images/richmessage/original_richmessage').'/'.$this->attributes['original_photo_path'] : 'https://dummyimage.com/160x160/8b8f8e/faf5fa.jpg&text=No+Photo';
    //     return $path;
    //     // return $this->attributes['photo_path'] ?: 'http://www.ipsumimage.com/160x160?l=No+Photo';
    // }

    // public function richeMessageItems()
    // {
    //     return $this->hasMany(RichMessageItem::class,'rich_message_id','id');
    // }
}
