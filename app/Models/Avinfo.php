<?php

namespace App\Models;

class Avinfo extends Model
{
    protected $table = "avinfo";

    public function getProxyLink()
    {
        $imgLink = $this->attributes['data_src'];
        $newImgLink = str_replace('https://cdnfd.me','http://proxy.mekelove.ml',$imgLink);
        return $newImgLink . '?http://cdnfd.me';
    }
}