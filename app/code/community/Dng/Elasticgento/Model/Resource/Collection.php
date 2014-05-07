<?php

/**
 * Elasticgento base collection
 *
 * @category  Dng
 * @package   Dng_Elasticgento
 * @author    Daniel Niedergesäß <daniel.niedergesaess@gmail.com>
 * @version   1.0.0
 */
abstract class Dng_Elasticgento_Model_Resource_Collection extends Mage_Eav_Model_Entity_Collection_Abstract
{
    const SORT_ORDER_ASC = 'ASC';
    const SORT_ORDER_DESC = 'DESC';

    /**
     * @var Dng_Elasticgento_Model_Resource_Client
     */
    protected $_client = null;

    /**
     * Current scope (store Id)
     *
     * @var int
     */
    protected $_storeId;

    /**
     * Entity object to define collection's attributes
     *
     * @var Mage_Eav_Model_Entity_Abstract
     */
    protected $_entity;

    /**
     * query object
     *
     * @var Elastica\Query()
     */
    protected $_query = null;

    /**
     * Filter object
     *
     * @var Elastica\Filter\BoolAnd
     */
    protected $_queryFilter = null;

    /**
     * array with filters to merge
     *
     * @var array
     */
    protected $_attributeFilters = array();

    /**
     * extra attributes to automaticly map
     *
     * @var array
     */
    protected $_selectExtraAttributes = array();

    /**
     * resource collection initalization
     *
     * @param string $model
     * @return Dng_Elasticgento_Model_Resource_Collection_Abstract
     */
    protected function _init($model, $entityModel = null)
    {
        $this->setItemObjectClass(Mage::getConfig()->getModelClassName($model));
        if ($entityModel === null) {
            $entityModel = $model;
        }
        $entity = Mage::getResourceSingleton($entityModel);
        $this->setEntity($entity);

        return $this;
    }

    /**
     * Set entity to use for attributes
     *
     * @param Mage_Eav_Model_Entity_Abstract $entity
     * @throws Mage_Eav_Exception
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    public function setEntity($entity)
    {
        if ($entity instanceof Mage_Eav_Model_Entity_Abstract) {
            $this->_entity = $entity;
        } elseif (is_string($entity) || $entity instanceof Mage_Core_Model_Config_Element) {
            $this->_entity = Mage::getModel('eav/entity')->setType($entity);
        } else {
            throw Mage::exception('Mage_Eav', Mage::helper('eav')->__('Invalid entity supplied: %s', print_r($entity, 1)));
        }
        return $this;
    }

    /**
     * Get collection's entity object
     *
     * @return Mage_Eav_Model_Entity_Abstract
     */
    public function getEntity()
    {
        if (empty($this->_entity)) {
            throw Mage::exception('Mage_Eav', Mage::helper('eav')->__('Entity is not initialized'));
        }
        return $this->_entity;
    }

    /**
     * Set store scope
     *
     * @param int|string|Mage_Core_Model_Store $store
     * @return Dng_Elasticgento_Model_Resource_Collection
     */
    public function setStore($store)
    {
        $this->setStoreId(Mage::app()->getStore($store)->getId());
        return $this;
    }

    /**
     * Set store scope
     *
     * @param int|string|Mage_Core_Model_Store $storeId
     * @return Dng_Elasticgento_Model_Resource_Collection
     */
    public function setStoreId($storeId)
    {
        if ($storeId instanceof Mage_Core_Model_Store) {
            $storeId = $storeId->getId();
        }
        $this->_storeId = (int)$storeId;
        return $this;
    }

    /**
     * @return Dng_Elasticgento_Model_Resource_Client
     */
    protected function getAdapter()
    {
        if (null === $this->_client) {

            $this->_client = Mage::getResourceModel('elasticgento/client');

        }
        return $this->_client;
    }

    /**
     * Init select
     *
     * @return Dng_Elasticgento_Model_Resource_Collection
     */
    protected function _initSelect()
    {
        return $this;
    }

    /**
     * Return current store id
     *
     * @return int
     */
    public function getStoreId()
    {
        if (true === is_null($this->_storeId)) {
            $this->setStoreId(Mage::app()->getStore()->getId());
        }
        return $this->_storeId;
    }


    /**
     * Before load action
     *
     * @return Dng_Elasticgento_Model_Resource_Collection
     */
    protected function _beforeLoad()
    {
        //set up query object
        $this->_queryFilter = new Elastica\Filter\BoolAnd();
        return $this;
    }

    /**
     * Hook for operations before rendering filters
     *
     * @return Dng_Elasticgento_Model_Resource_Collection
     */
    protected function _renderFiltersBefore()
    {
        return $this;
    }

    /**
     * render collection filters
     *
     * @return Dng_Elasticgento_Model_Resource_Collection
     */
    protected function _renderFilters()
    {
        $this->_queryFilter = new Elastica\Filter\BoolAnd();
        foreach ($this->_attributeFilters as $filter) {
            $this->_queryFilter->addFilter($filter);
        }
        return $this;
    }

    /**
     * Hook for operations after rendering filters
     *
     * @return Dng_Elasticgento_Model_Resource_Collection
     */
    protected function _renderFiltersAfter()
    {
        return $this;
    }

    /**
     * Hook for operations before rendering query object
     *
     * @return Dng_Elasticgento_Model_Resource_Collection
     */
    protected function _renderQueryBefore()
    {
        $this->_query = new Elastica\Query();
        return $this;
    }

    /**
     * render collection facets
     *
     * @return Dng_Elasticgento_Model_Resource_Collection
     */
    protected function _renderQuery()
    {
        $queryString = new Elastica\Query\MatchAll();
        $filteredQuery = new Elastica\Query\Filtered(
            $queryString,
            $this->_queryFilter

        );
        $this->_query->setQuery($filteredQuery);
        return $this;
    }

    /**
     * Hook for operations after query object
     *
     * @return Dng_Elasticgento_Model_Resource_Collection
     */
    protected function _renderQueryAfter()
    {
        return $this;
    }

    /**
     * Hook for operations before rendering facets
     *
     * @return Dng_Elasticgento_Model_Resource_Collection
     */
    protected function _renderFacetsBefore()
    {
        return $this;
    }

    /**
     * render collection facets
     *
     * @return Dng_Elasticgento_Model_Resource_Collection
     */
    protected function _renderFacets()
    {
        return $this;
    }

    /**
     * Hook for operations after rendering facets
     *
     * @return Dng_Elasticgento_Model_Resource_Collection
     */
    protected function _renderFacetsAfter()
    {
        return $this;
    }

    /**
     * render collection sort
     *
     * @return Dng_Elasticgento_Model_Resource_Collection
     */
    protected function _renderOrders()
    {
        return $this;
    }

    /**
     * add facet condition
     *
     * @param Mage_Catalog_Model_Category $category
     */
    public function addFacetCondition()
    {
        return $this;
    }

    /**
     * Load collection data into object items
     *
     * @return Dng_Elasticgento_Model_Resource_Collection
     */
    public function load($printQuery = false, $logQuery = false)
    {
        if ($this->isLoaded()) {
            return $this;
        }
        Varien_Profiler::start('__ELASTICGENTO_COLLECTION_BEFORE_LOAD__');
        Mage::dispatchEvent('elasticgento_collection_abstract_load_before', array('collection' => $this));
        $this->_beforeLoad();
        Varien_Profiler::stop('__ELASTICGENTO_COLLECTION_BEFORE_LOAD__');

        /**
         * render filters
         */
        Varien_Profiler::start('__ELASTICGENTO_RENDER_FILTERS__');
        $this->_renderFiltersBefore();
        $this->_renderFilters();
        $this->_renderFiltersAfter();
        Varien_Profiler::stop('__ELASTICGENTO_RENDER_FILTERS__');

        /**
         * render query object
         */
        Varien_Profiler::start('__ELASTICGENTO_RENDER_QUERY__');
        $this->_renderQueryBefore();
        $this->_renderQuery();
        $this->_renderQueryAfter();
        Varien_Profiler::stop('__ELASTICGENTO_RENDER_QUERY__');

        /**
         * render orders
         */
        Varien_Profiler::start('__ELASTICGENTO_RENDER_ORDERS__');
        $this->_renderOrders();
        Varien_Profiler::stop('__ELASTICGENTO_RENDER_ORDERS__');

        /**
         * render facets
         */
        Varien_Profiler::start('__ELASTICGENTO_RENDER_FACETS__');
        $this->_renderFacetsBefore();
        $this->_renderFacets();
        $this->_renderFacetsAfter();
        Varien_Profiler::stop('__ELASTICGENTO_RENDER_FACETS__');

        Varien_Profiler::start('__ELASTICGENTO_QUERY__');
        $this->_query->setFrom($this->getPageSize() * ($this->_curPage - 1));
        $this->_query->setSize($this->getPageSize());
        $type = $this->getAdapter()->getIndex($this->_storeId)->getType($this->getEntity()->getType());
        try {
//            var_dump(json_encode($this->_query->toArray()));
            $results = $type->search($this->_query);
        } catch (Exception $e) {
//            var_dump($e->getMessage());
        }
        Varien_Profiler::stop('__ELASTICGENTO_QUERY__');
        $this->_afterLoad();
        Mage::dispatchEvent('elasticgento_collection_abstract_load_after', array('collection' => $this));
        Varien_Profiler::stop('__EAV_COLLECTION_AFTER_LOAD__');
        $this->_setIsLoaded();
        return $this;
    }

    /**
     * Get all data array for collection
     *
     * @return array
     */
    public function getData()
    {
        return $this->_data;
    }
}