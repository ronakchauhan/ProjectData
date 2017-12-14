<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */


namespace Amasty\Shopby\Model\Adapter\Mysql\Aggregation;

use Magento\Catalog\Model\Product;
use Magento\Customer\Model\Session;
use Magento\Eav\Model\Config;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\App\ScopeResolverInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\DB\Select;
use Magento\Framework\Search\Adapter\Mysql\Aggregation\DataProviderInterface;
use Magento\Framework\Search\Request\BucketInterface;

class GroupDataProvider implements DataProviderInterface
{
    /**
     * @var Config
     */
    private $eavConfig;

    /**
     * @var Resource
     */
    private $resource;

    /**
     * @var ScopeResolverInterface
     */
    private $scopeResolver;

    /**
     * @var Session
     */
    private $customerSession;

    /**
     * @var AdapterInterface
     */
    private $connection;

    /**
     * GroupDataProvider constructor.
     * @param Config $eavConfig
     * @param ResourceConnection $resource
     * @param ScopeResolverInterface $scopeResolver
     * @param Session $customerSession
     */
    public function __construct(
        Config $eavConfig,
        ResourceConnection $resource,
        ScopeResolverInterface $scopeResolver,
        Session $customerSession
    ) {
        $this->eavConfig = $eavConfig;
        $this->resource = $resource;
        $this->connection = $resource->getConnection();
        $this->scopeResolver = $scopeResolver;
        $this->customerSession = $customerSession;
    }

    /**
     * @param BucketInterface $bucket
     * @param array $dimensions
     * @param Table $entityIdsTable
     * @return Select
     */
    public function getDataSet(
        BucketInterface $bucket,
        array $dimensions,
        Table $entityIdsTable
    ) {
        $currentScope = $this->scopeResolver->getScope($dimensions['scope']->getValue())->getId();
        $attribute = $this->eavConfig->getAttribute(Product::ENTITY, $bucket->getField());
        $select = $this->getSelect();
        $currentScopeId = $this->scopeResolver->getScope($currentScope)->getId();
        $table = $this->resource->getTableName('catalog_product_index_eav');
        $select->from(['main_table' => $table], ['entity_id', 'value'])
            ->where('main_table.attribute_id = ?', $attribute->getAttributeId())
            ->where('main_table.store_id = ? ', $currentScopeId)
            ->where('main_table.value IN (?)', $dimensions['groups']);
        $select->joinInner(
            ['entities' => $entityIdsTable->getName()],
            'main_table.entity_id  = entities.entity_id',
            []
        );

        return $select;
    }

    /**
     * @param Select $select
     * @return array
     */
    public function execute(Select $select)
    {
        return $this->connection->fetchAssoc($select);
    }

    /**
     * @return Select
     */
    private function getSelect()
    {
        return $this->connection->select();
    }
}