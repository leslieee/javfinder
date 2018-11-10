<?php

namespace App\Models;
use App\Services\Config;

class Avinfo extends Model
{
    protected $table = "avinfo";

    public function getProxyLink()
    {
        $imgLink = $this->attributes['data_src'];
        $newImgLink = str_replace('https://cdnfd.me',Config::get('proxy_host'),$imgLink);
        return $newImgLink . '?http://cdnfd.me';
    }
}