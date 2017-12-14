<?php

namespace Ktpl\Testimonial\Model\ResourceModel\Testimonial;

use Ktpl\Testimonial\Api\Data\TestimonialkInterface;
use \Ktpl\Testimonial\Model\ResourceModel\AbstractCollection;
use Magento\Store\Model\Store;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    /**
     * @var \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\EntityManager\MetadataPool
     */
    protected $metadataPool;

    /**
     * @param \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\DB\Adapter\AdapterInterface|null $connection
     * @param \Magento\Framework\Model\ResourceModel\Db\AbstractDb|null $resource
     */
    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\EntityManager\MetadataPool $metadataPool,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    ) {
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $storeManager, $metadataPool, $connection, $resource);
        $this->storeManager = $storeManager;
    }

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Ktpl\Testimonial\Model\Testimonial', 'Ktpl\Testimonial\Model\ResourceModel\Testimonial');

        $this->_map['fields']['store'] = 'store_table.store_id';
    }

    /**
     * Perform operations after collection load
     *
     * @return $this
     */
    protected function _afterLoad()
    {
        $this->performAfterLoad('ktpl_testimonials_store', "entity_id");

        return parent::_afterLoad();
    }
    /**
     * Add filter by store
     *
     * @param int
     * @return $this
     */
    public function addStoreFilter($storeId, $withAdmin = true)
    {
        $storeIds = [\Magento\Store\Model\Store::DEFAULT_STORE_ID, $storeId];

        $inCond = $this->getConnection()->prepareSqlCondition('store.store_id', ['in' => $storeIds]);
        
        $this->getSelect()->join(
            ['store' => $this->getTable('ktpl_testimonials_store')],
            'main_table.entity_id=store.entity_id',
            []
        );
        $this->getSelect()->where($inCond);
        return $this;
    }
}