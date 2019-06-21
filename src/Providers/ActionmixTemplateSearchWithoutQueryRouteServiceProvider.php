<?php //strict

namespace ActionmixTemplateSearchWithoutQuery\Providers;

use IO\Controllers\CategoryController;
use IO\Extensions\Constants\ShopUrls;
use IO\Helper\RouteConfig;
use Plenty\Plugin\RouteServiceProvider;
use Plenty\Plugin\Routing\Router;
use Plenty\Plugin\Routing\ApiRouter;
use IO\Providers\IORouteServiceProvider;

/**
 * Class ActionmixTemplateSearchWithoutQueryRouteServiceProvider
 * @package ActionmixTemplateSearchWithoutQuery\Providers
 */
class ActionmixTemplateSearchWithoutQueryRouteServiceProvider extends IORouteServiceProvider
{
    public function register()
	{
	}

    /**
     * Define the map routes to templates or REST resources
     * @param Router $router
     * @param ApiRouter $api
     * @throws \Plenty\Plugin\Routing\Exceptions\RouteReservedException
     */
	public function map(Router $router, ApiRouter $api)
	{
		parent::map( $router, $api);
	    $api->version(['v1'], ['namespace' => 'ActionmixTemplateSearchWithoutQuery\Api\Resources'], function ($api)
		{
            $api->resource('io/facet', 'FacetWithoutQueryResource');
		});
	}
}
