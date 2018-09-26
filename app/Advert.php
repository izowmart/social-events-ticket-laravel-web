<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Advert extends Model
{
    use Sluggable;
    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        //we add a slug url to the name
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
    protected $fillable=[
        'admin_id','title','description','image_url','start_date','end_date',
    ];
}
