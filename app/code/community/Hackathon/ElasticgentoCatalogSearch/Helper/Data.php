<?php

/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * PHP Version 5.3
 *
 * @category  Hackathon
 * @package   Hackathon_ElasticgentoCore
 * @author    Daniel Niedergesäß <daniel.niedergesaess@gmail.com>
 * @author    Andreas Emer <emer ÄT mothership.de>
 * @author    Michael Ryvlin <ryvlin@gmail.com>
 * @author    Johann Nicklas <johann@n1klas.de>
 * @copyright Copyright (c) 2014 Hackathon
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://mage-hackathon.de/
 *
 * Elasticgento CatalogSearch module helper
 *
 */
class Hackathon_ElasticgentoCatalogSearch_Helper_Data extends Mage_Core_Helper_Abstract
{

    /** config path to enable or disable catalog search through elasticsearch  */
    const CONFIG_FLAG_ELASTICGENTO_CATALOGSEARCH_ENABLED = 'elasticgento/catalogsearch/active';

    /**
     * check if the search is activated
     *
     * @return bool
     */
    public function isSearchActive()
    {
        return Mage::getStoreConfigFlag(static::CONFIG_FLAG_ELASTICGENTO_CATALOGSEARCH_ENABLED);
    }

    /**
     * return the adapter for querying elasticsearch
     *
     * @return Hackathon_ElasticgentoCore_Model_Resource_Client
     */
    public function getAdapter()
    {
        return Mage::getResourceSingleton('elasticgento/client');
    }

}