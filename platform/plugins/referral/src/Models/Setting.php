<?php

namespace Botble\Referral\Models;

use Botble\ACL\Models\User;
use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Revision\RevisionableTrait;
use Botble\Slug\Traits\SlugTrait;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Setting extends BaseModel
{
    use RevisionableTrait;
    use SlugTrait;
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'referral_commission';

    /**
     * @var mixed
     */
    protected $revisionEnabled = true;

    /**
     * @var mixed
     */
    protected $revisionCleanup = true;

    /**
     * @var int
     */
    protected $historyLimit = 20;

    /**
     * @var array
     */
    protected $dontKeepRevisionOf = [
        'content',
        'views',
    ];

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
        'comession',
        'admin_comm',
        'client_comm',
        'vendor_comm',
        'property_type',
        'status',
        'author_id',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    // /**
    //  * @return BelongsTo
    //  */
    // public function user(): BelongsTo
    // {
    //     return $this->belongsTo(User::class)->withDefault();
    // }

    // /**
    //  * @return BelongsToMany
    //  */
    // public function tags(): BelongsToMany
    // {
    //     return $this->belongsToMany(Tag::class, 'post_tags');
    // }

    // /**
    //  * @return BelongsToMany
    //  */
    // public function categories(): BelongsToMany
    // {
    //     return $this->belongsToMany(Category::class, 'post_categories');
    // }

    // /**
    //  * @return MorphTo
    //  */
    // public function author(): MorphTo
    // {
    //     return $this->morphTo();
    // }

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::deleting(function (Post $post) {
    //         $post->categories()->detach();
    //         $post->tags()->detach();
    //     });
    // }
}