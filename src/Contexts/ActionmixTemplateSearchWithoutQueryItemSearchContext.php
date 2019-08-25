<?php

namespace ActionmixTemplateSearchWithoutQuery\Contexts;

use Ceres\Contexts\ItemSearchContext;
use Ceres\Helper\SearchOptions;
use IO\Helper\ContextInterface;
use IO\Services\ItemSearch\SearchPresets\Facets;
use IO\Services\CheckoutService;
use IO\Extensions\Filters\NumberFormatFilter;
use ActionmixTemplateSearchWithoutQuery\Services\ItemSearch\SearchPresets\SearchItemsWithoutQuery;
use ActionmixTemplateSearchWithoutQuery\Services\ItemSearch\SearchPresets\FacetsWithoutQuery;
use Plenty\Plugin\Translation\Translator;

class ActionmixTemplateSearchWithoutQueryItemSearchContext extends ItemSearchContext implements ContextInterface
{
    use \Ceres\Contexts\ItemListContext;

    public $isSearch;
    public $searchString;
    public $isSearchWithoutQuery;

    public function init($params)
    {
        parent::init($params);

        $itemListOptions = [
            'page'          => $this->getParam( 'page', 1 ),
            'itemsPerPage'  => $this->getParam( 'itemsPerPage', $this->ceresConfig->pagination->rowsPerPage[0] * $this->ceresConfig->pagination->columnsPerPage ),
            'sorting'       => $this->getParam( 'sorting', $this->ceresConfig->sorting->defaultSortingSearch ),
            'facets'        => $this->getParam( 'facets', '' ),
            'query'         => $this->getParam( 'query', '' ),
            'priceMin'      => $this->request->get('priceMin', 0),
            'priceMax'      => $this->request->get('priceMax', 0)
        ];

        $this->initItemList(
            [
                'itemList' => SearchItemsWithoutQuery::getSearchFactory( $itemListOptions ),
                'facets'   => Facets::getSearchFactory( $itemListOptions )
            ],
            $itemListOptions,
            SearchOptions::SCOPE_SEARCH
        );

        $this->isSearch = true;
        $this->searchString = $itemListOptions['query'];
        if ($this->searchString == '') {
            $facetArray = explode(',', $itemListOptions['facets']);
            foreach ($this->facets as $facet) {
                foreach ($facet['values'] as $facetValue) {
                    if (in_array($facetValue['id'], $facetArray)) {
                        $this->searchString = $this->searchString . ', ' . $facetValue['name'];
                    }
                }
            }
            $this->searchString = substr($this->searchString, 2);

            if ($itemListOptions['priceMin'] + $itemListOptions['priceMax'] > 0) {
                $checkoutService = pluginApp(CheckoutService::class);
                $numberFormatFilter = pluginApp(NumberFormatFilter::class);
                $translator = pluginApp(Translator::class);

                if ($itemListOptions['priceMax'] <= 0) {
                    $this->searchString = $this->searchString . ', ' . $translator->trans("Ceres::Template.itemFrom") . ' ' .$numberFormatFilter->formatMonetary($itemListOptions['priceMin'], $checkoutService->getCurrency());
                } else if ($itemListOptions['priceMin'] <= 0) {
                    $this->searchString = $this->searchString . ', ' . $translator->trans("Ceres::Template.itemTo") . ' ' .  $numberFormatFilter->formatMonetary($itemListOptions['priceMax'], $checkoutService->getCurrency());
                } else {
                    $this->searchString = $this->searchString . ', ' . $numberFormatFilter->formatMonetary($itemListOptions['priceMin'], $checkoutService->getCurrency()) . " - " . $numberFormatFilter->formatMonetary($itemListOptions['priceMax'], $checkoutService->getCurrency());
                }
            }
        }
        $this->isSearchWithoutQuery = true;
    }
}