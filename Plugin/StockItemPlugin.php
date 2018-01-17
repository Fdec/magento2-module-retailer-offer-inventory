<?php
/**
 * Plugin StockItemPlugin
 *
 * @category  Smile
 * @package   Smile\RetailerOfferInventory
 * @author    Fanny DECLERCK <fadec@smile.fr>
 * @copyright 2018 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Smile\RetailerOfferInventory\Plugin;

use Magento\CatalogInventory\Model\Stock\Item;
use Smile\RetailerOfferInventory\Helper\OfferStock as OfferStockHelper;

/**
 * Class StockItemPlugin
 *
 * @category Smile
 * @package  Smile\RetailerOfferInventory
 * @author   Fanny DECLERCK <fadec@smile.fr>
 */
class StockItemPlugin
{
    /**
     * @var OfferStockHelper
     */
    private $helper;

    /**
     * StockItemPlugin constructor.
     *
     * @param OfferStockHelper $offerStockHelper The Offer stock helper
     */
    public function __construct(
        OfferStockHelper $offerStockHelper
    ) {
        $this->helper = $offerStockHelper;
    }

    /**
     * Return offer availability (if any) instead of the product one.
     * @SuppressWarnings(PHPMD.UnusedFormalParameter) We do not need to call the parent method.
     *
     * @param Item $item   The item
     * @param Item $result Result
     *
     * @return Item
     * @throws \Exception
     */
    public function afterSetIsInStock(Item $item, $result)
    {
        if ($this->helper->useStoreOffers()) {
            $offerStock  = $this->helper->getCurrentOfferStock($item->getProductId());
            if ($offerStock !== null && $offerStock->getIsInStock() === 0) {
                return $this->setData(Item::IS_IN_STOCK, $offerStock->getIsInStock());
            }
        }

        return $result;
    }
}