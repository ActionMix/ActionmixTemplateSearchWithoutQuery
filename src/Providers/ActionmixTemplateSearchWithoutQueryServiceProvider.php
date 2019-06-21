<?php

namespace ActionmixTemplateSearchWithoutQuery\Providers;

use IO\Extensions\Functions\Partial;
use IO\Helper\CategoryKey;
use IO\Helper\CategoryMap;
use IO\Helper\TemplateContainer;
use IO\Helper\ComponentContainer;
use IO\Services\ContentCaching\Services\Container;
use IO\Services\ItemSearch\Helper\ResultFieldTemplate;

use Plenty\Plugin\ServiceProvider;
use Plenty\Plugin\Templates\Twig;
use Plenty\Plugin\Events\Dispatcher;
use Plenty\Plugin\ConfigRepository;

use Ceres\Caching\NavigationCacheSettings;
use Ceres\Caching\SideNavigationCacheSettings;

use ActionmixTemplateSearchWithoutQuery\Contexts\ActionmixTemplateSearchWithoutQueryItemSearchContext;
use ActionmixTemplateSearchWithoutQuery\Providers\ActionmixTemplateSearchWithoutQueryRouteServiceProvider;

/**
 * Class ActionmixTemplateSearchWithoutQueryServiceProvider
 * @package ActionmixTemplateSearchWithoutQuery\Providers
 */
class ActionmixTemplateSearchWithoutQueryServiceProvider extends ServiceProvider
{
    const EVENT_LISTENER_PRIORITY = 0;
    const THEME_NAME = 'ActionmixTemplateSearchWithoutQuery';

    public function register()
    {
        $this->getApplication()->register(ActionmixTemplateSearchWithoutQueryRouteServiceProvider::class);
    }

    public function boot(Twig $twig, Dispatcher $eventDispatcher, ConfigRepository $config)
    {
        // Override contexts
        $eventDispatcher->listen('IO.ctx.search', function (TemplateContainer $container) {
            $container->setContext(ActionmixTemplateSearchWithoutQueryItemSearchContext::class);
            return false;
        }, self::EVENT_LISTENER_PRIORITY);
    }
}

