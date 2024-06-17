<?php

namespace Modules\Setting\Entities;

use App\Models\BaseModel;
use Illuminate\Http\UploadedFile;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Modules\Setting\Enums\SettingType;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Setting extends BaseModel implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'key',
        'type',
        'value',
    ];

    public static function booted()
    {
        static::updated(fn () => Cache::forget('settings'));
    }

    public static function getValue($key)
    {
        $settings = Cache::rememberForever('settings', fn () => self::get());
        return $settings->firstWhere('key', $key)?->value;
    }

    public static function setValue($key, $value, $type = null)
    {
        $type ??= match (true) {
            is_array($value) => SettingType::JSON,
            ctype_digit($value) => SettingType::INTEGER,
            $value instanceof UploadedFile || file_exists($value) => SettingType::FILE,
            default => SettingType::STRING,
        };

        return self::updateOrCreate(['key' => $key], ['type' => $type, 'value' => $value]);
    }

    protected function value(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                return match ((int) $attributes['type']) {
                    SettingType::STRING => $value,
                    SettingType::INTEGER => (int) $value,
                    SettingType::FILE => $this->getFirstMediaUrl(),
                    SettingType::JSON => json_decode($value ?? '[]', true),
                    default => $value,
                };
            },
            set: function ($value, $attributes) {
                return match ((int) $attributes['type']) {
                    SettingType::STRING => $value,
                    SettingType::INTEGER => $value,
                    SettingType::FILE => $this->addFileAfterSaving($value),
                    SettingType::JSON => json_encode($value ?? []),
                };
            }
        );
    }

    protected function addFileAfterSaving($value)
    {
        self::saved(function (self $model) use ($value) {
            Event::forget('eloquent.saved: ' . self::class);
            $model->clearMediaCollection()->addMedia($value)->toMediaCollection();
        });

        return null;
    }
}
