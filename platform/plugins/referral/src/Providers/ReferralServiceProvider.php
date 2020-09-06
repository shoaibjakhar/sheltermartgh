<?php

namespace Botble\Referral\Providers;

use Botble\Shortcode\View\View;
use Illuminate\Routing\Events\RouteMatched;
use Botble\Base\Supports\Helper;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Referral\Models\Setting;
use Botble\Referral\Repositories\Caches\SettingCacheDecorator;
use Botble\Referral\Repositories\Eloquent\SettingRepository;
use Botble\Referral\Repositories\Interfaces\SettingInterface;
use Event;
use Illuminate\Support\ServiceProvider;
use Botble\Referral\Models\Commission;
use Botble\Referral\Repositories\Caches\CommissionCacheDecorator;
use Botble\Referral\Repositories\Eloquent\CommissionRepository;
use Botble\Referral\Repositories\Interfaces\CommissionInterface;
use Botble\Referral\Models\Tag;
use Botble\Referral\Repositories\Caches\TagCacheDecorator;
use Botble\Referral\Repositories\Eloquent\TagRepository;
use Botble\Referral\Repositories\Interfaces\TagInterface;
use Language;
use SeoHelper;
use SlugHelper;

/**
 * @since 02/07/2016 09:50 AM
 */
class ReferralServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->app->bind(SettingInterface::class, function () {
            return new SettingCacheDecorator(new SettingRepository(new Setting));
        });

        $this->app->bind(CommissionInterface::class, function () {
            return new CommissionCacheDecorator(new CommissionRepository(new Commission));
        });

        $this->app->bind(TagInterface::class, function () {
            return new TagCacheDecorator(new TagRepository(new Tag));
        });

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    public function boot()
    {
        $this->setNamespace('plugins/referral')
            ->loadAndPublishConfigurations(['permissions'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes(['web', 'api'])
            ->loadMigrations()
            ->publishAssets();

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()
                ->registerItem([
                    'id'          => 'cms-plugins-referral',
                    'priority'    => 5,
                    'parent_id'   => null,
                    'name'        => _('Referral Management'),
                    'icon'        => 'fa fa-paper-plane',
                    'url'         => route('referral.index'),
                    'permissions' => ['referral.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-commission-paid',
                    'priority'    => 1,
                    'parent_id'   => 'cms-plugins-referral',
                    'name'        => _('Paid Commissions'),
                    'icon'        => null,
                    'url'         => route('commission.index','type=paid'),
                    'permissions' => ['commission.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-commission-pend',
                    'priority'    => 2,
                    'parent_id'   => 'cms-plugins-referral',
                    'name'        => _('Commissions Pending'),
                    'icon'        => null,
                    'url'         => route('commission.index','type=pend'),
                    'permissions' => ['commission.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-commiss-sale-cal',
                    'priority'    => 3,
                    'parent_id'   => 'cms-plugins-referral',
                    'name'        => _('Commissions Sale Setting'),
                    'icon'        => null,
                    'url'         => route('referral.create','type=sale'),
                    'permissions' => ['referral.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-commiss-rent-cal',
                    'priority'    => 4,
                    'parent_id'   => 'cms-plugins-referral',
                    'name'        => _('Commissions Rent Setting'),
                    'icon'        => null,
                    'url'         => route('referral.create','type=rent'),
                    'permissions' => ['referral.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-commission-cal',
                    'priority'    => 1000,
                    'parent_id'   => null,
                    'name'        => _('Commissions Calculater'),
                    'icon'        => 'fa fa-calculator',
                    'url'         => route('commission.create'),
                    'permissions' => ['commission.create'],
                ]);

        });

        $this->app->booted(function () {
            $models = [Setting::class, Commission::class, Tag::class];

            if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
                Language::registerModule($models);
            }

            SlugHelper::registerModule($models);
          

            SeoHelper::registerModule($models);
        });

        if (function_exists('shortcode')) {
            view()->composer([
                'plugins/Referral::themes.post',
                'plugins/Referral::themes.category',
                'plugins/Referral::themes.tag',
            ], function (View $view) {
                $view->withShortcodes();
            });
        }
    }
}
