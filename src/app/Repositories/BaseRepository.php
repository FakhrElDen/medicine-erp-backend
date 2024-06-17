<?php

namespace App\Repositories;

class BaseRepository
{
    public function deleteMedia($media_id, $pharmacy)
    {
        $media = $pharmacy->getMedia('pharmacy_media')->where('id', $media_id)->first();

        return $media->delete();
    }
}
