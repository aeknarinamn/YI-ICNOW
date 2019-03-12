<?php

namespace YellowProject;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LineEmoticon extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'line_emoticons';
    protected $appends = ['sent_unicode'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'unicode',
        'file_name',
    ];


    /**
     * A flag to indicate whether or not to use timestamp on the record
     *
     * @var array
     */
    public $timestamps = false;



     /**
     * Set the user's first name.
     *
     * @param  string  $value
     * @return string
     */
    public function setUnicodeAttribute($strHex)
    {
        $this->attributes['unicode'] = hexdec($strHex);
    }

    public function getSentUnicodeAttribute()
    {
    	$value = $this->unicode;
    	$enCode = iconv('UCS-4LE', 'UTF-8', pack('V', $value));

    	return $enCode;
    }
}
