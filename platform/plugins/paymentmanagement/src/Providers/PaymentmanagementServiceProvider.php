<?php

namespace Botble\PaymentManagement\Providers;

use Botble\Shortcode\View\View;
use Illuminate\Routing\Events\RouteMatched;
use Botble\Base\Supports\Helper;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Paymentmanagement\Models\PMMethod;
use Botble\Paymentmanagement\Repositories\Caches\PMCacheDecorator;
use Botble\Paymentmanagement\Repositories\Eloquent\PMRepository;
use Botble\Paymentmanagement\Repositories\Interfaces\PMInterface;
use Event;
use Illuminate\Support\ServiceProvider;
use Botble\Paymentmanagement\Models\DirectPaymentMethod;
use Botble\Paymentmanagement\Repositories\Caches\DirectPaymentMethodCacheDecorator;
use Botble\Paymentmanagement\Repositories\Eloquent\DirectPaymentMethodRepository;
use Botble\Paymentmanagement\Repositories\Interfaces\DirectPaymentMethodInterface;
use Language;
use SeoHelper;
use SlugHelper;

/**
 * @since 02/07/2016 09:50 AM
 */
class PaymentmanagementServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->app->bind(PMInterface::class, function () {
            return new PMCacheDecorator(new PMRepository(new PMMethod));
        });
        $this->app->bind(DirectPaymentMethodInterface::class, function () {
            return new DirectPaymentMethodCacheDecorator(new DirectPaymentMethodRepository(new DirectPaymentMethod));
        });
        Helper::autoload(__DIR__ . '/../../helpers');

    }

    public function boot()
    {
        $this->setNamespace('plugins/paymentmanagement')
            ->loadAndPublishConfigurations(['permissions'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes(['web', 'api'])
            ->loadMigrations()
            ->publishAssets();

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()
                ->registerItem([
                    'id'          => 'cms-plugins-payment-manag-user',
                    'priority'    => 300,
                    'parent_id'   => null,
                    'name'        => _('Payment Management'),
                    'icon'        => 'fas fa-money-check',
                    'url'         => route('paymentmanagement.index'),
                    'permissions' => ['paymentmanagement.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-payment-user-list',
                    'priority'    => 1,
                    'parent_id'   => 'cms-plugins-payment-manag-user',
                    'name'        => _('Users List'),
                    'icon'        => null,
                    'url'         => route('paymentmanagement.index'),
                    'permissions' => ['paymentmanagement.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-payment-method-list',
                    'priority'    => 2,
                    'parent_id'   => 'cms-plugins-payment-manag-user',
                    'name'        => _('Payment Method'),
                    'icon'        => null,
                    'url'         => route('paymentmanagement.create'),
                    'permissions' => ['paymentmanagement.create'],
                ])
                ;
        });

        $this->app->booted(function () {
            $models = [PaymentMethod::class];

            if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
                Language::registerModule($models);
            }

            SlugHelper::registerModule($models);
            SlugHelper::setPrefix(Tag::class, 'tag');

            SeoHelper::registerModule($models);
        });

        // if (function_exists('shortcode')) {
        //     view()->composer([
        //         'plugins/blog::themes.paymentManagement',
        //         'plugins/blog::themes.category',
        //         'plugins/blog::themes.tag',
        //     ], function (View $view) {
        //         $view->withShortcodes();
        //     });
        // }
    }
}
