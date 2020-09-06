<?php

namespace Botble\Referral\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Slug\Traits\SlugTrait;
use Botble\Base\Models\BaseModel;
use Botble\RealEstate\Models\Property;
use Botble\Vendor\Models\Vendor;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Commission extends BaseModel
{
    use SlugTrait;
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'commission';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The date fields for the model.clear
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'property_id',
        'percentage',
        'commission',
        'admin_commission',
        'admin_comm_percentage',
        'client_commission',
        'client_comm_percentage',
        'vendor_commission',
        'vendor_comm_percentage',
        'property_price',
        'reference_id',
        'status',
        'type',
        'author_id',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    /**
     * @return BelongsToMany
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'reference_id')->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'property_id')->withDefault();
    }

    // /**
    //  * @return HasMany
    //  */
    // public function children(): HasMany
    // {
    //     return $this->hasMany(Category::class, 'parent_id');
    // }

    // protected static function boot()
    // {
    //     parent::boot();

    //     self::deleting(function (Category $category) {
    //         $category->posts()->detach();
    //     });
    // }
}
