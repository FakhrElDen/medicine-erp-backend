<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

trait HasMediaAttributes
{
    public function setAttribute($key, $value)
    {
        if (!empty(array_intersect([$key, $key . '[]'], $this->mediaAttributes ?? []))) {
            if (is_iterable($value)) {
                $is_array = true;
                $array = $value;
                $value = $value[0];
            } else {
                $is_array = false;
            }

            // $this->clearMediaCollection($key);

            if ($value instanceof UploadedFile || file_exists($value)) {
                foreach ($is_array ? $array : [$value] as $media) {
                    $this->addMedia($media)->toMediaCollection($key);
                }
            } elseif (Str::startsWith($value, ['http://', 'https://'])) {
                foreach ($is_array ? $array : [$value] as $media) {
                    $this->addMediaFromUrl($value)->toMediaCollection($key);
                }
            }

            return;
        }

        if (method_exists($this, 'translatableSetAttribute')) {
            return $this->translatableSetAttribute($key, $value);
        }

        return parent::setAttribute($key, $value);
    }

    public function getAttribute($key)
    {
        if (Str::endsWith($key, '_media')) {
            $url = false;
        } else {
            $url = true;
        }

        $key_as_defined = current(array_intersect([$key, $key . '[]'], $this->mediaAttributes ?? []));

        if ($key_as_defined) {

            $media = $this->getMedia($key);
            $is_array = substr($key_as_defined, -2) == '[]';

            if ($is_array) {
                return $url ? $media->map->getUrl() : $media;
            } else {
                return $url && $media->last() ? $media->last()->getUrl() : $media->last();
            }
        }

        return parent::getAttribute($key);
    }
}
