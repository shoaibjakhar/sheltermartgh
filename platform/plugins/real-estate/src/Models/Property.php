<?php

namespace Botble\RealEstate\Models;

use Botble\ACL\Models\User;
use Botble\Base\Models\BaseModel;
use Botble\Base\Traits\EnumCastable;
use Botble\RealEstate\Enums\ModerationStatusEnum;
use Botble\Location\Models\City;
use Botble\RealEstate\Enums\PropertyPeriodEnum;
use Botble\RealEstate\Enums\PropertyStatusEnum;
use Botble\RealEstate\Enums\PropertyTypeEnum;
use Botble\Slug\Traits\SlugTrait;
use Exception;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Arr;

class Property extends BaseModel
{
    use SlugTrait;
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 're_properties';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'type',
        'description',
        'content',
        'location',
        'images',
        'project_id',
        'number_bedroom',
        'number_bathroom',
        'number_floor',
        'square',
        'price',
        'status',
        'is_featured',
        'currency_id',
        'city_id',
        'period',
        'author_id',
        'author_type',
        'category_id',
        'expire_date',
        'auto_renew',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status'            => PropertyStatusEnum::class,
        'moderation_status' => ModerationStatusEnum::class,
        'type'              => PropertyTypeEnum::class,
        'period'            => PropertyPeriodEnum::class,
    ];

    /**
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'expire_date',
    ];

    /**
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id')->withDefault();
    }

    /**
     * @return BelongsToMany
     */
    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class, 're_property_features', 'property_id', 'feature_id');
    }

    /**
     * @param string $value
     * @return array
     */
    public function getImagesAttribute($value)
    {
        try {
            if ($value === '[null]') {
                return [];
            }

            return json_decode($value) ?: [];
        } catch (Exception $exception) {
            return [];
        }
    }

    /**
     * @return string|null
     */
    public function getImageAttribute(): ?string
    {
        return Arr::first($this->images) ?? null;
    }

    /**
     * @return BelongsTo
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class)->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class)->withDefault();
    }

    /**
     * @return MorphTo
     */
    public function author(): MorphTo
    {
        if (!is_plugin_active('vendor')) {
            return $this->morphTo(null, User::class)->withDefault();
        }

        return $this->morphTo()->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id')->withDefault();
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotExpired($query)
    {
        return $query->where(function ($query) {
            $query->where('expire_date', '>=', now(config('app.timezone'))->toDateTimeString())
                ->orWhere('never_expired', true);
        });
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExpired($query)
    {
        return $query->where(function ($query) {
            $query->where('expire_date', '<', now(config('app.timezone'))->toDateTimeString())
                ->where('never_expired', false);
        });
    }
}
