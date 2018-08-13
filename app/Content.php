<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    protected $table = 'contents';
    protected $fillable = [
        'link', 'page_number', 'x', 'y', 'width', 'height', 'ebook_id', 'link_type_id'
    ];

    public function ebook() {
        return $this->belongsTo('App/Ebook', 'ebook_id');
    }

    public function linkType(){
        return $this->belongsTo('App/LinkType', 'link_type_id');
    }
}
