<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */

namespace Amasty\Shopby\Model\Search\Adapter\Mysql;

use Amasty\Shopby\Model\Adapter\Mysql\Aggregation\GroupDataProvider;


/**
 * Copyright © 2016 Amasty. All rights reserved.
 */
class AggregationAdapter
{
    /**
     * @var \Magento\Framework\Search\Adapter\Mysql\Mapper
     */
    protected $mapper;

    /**
     * @var \Magento\Framework\Search\Adapter\Mysql\TemporaryStorageFactory
     */
    protected $temporaryStorageFactory;

    /**
     * @var \Magento\Framework\Search\Adapter\Mysql\Aggregation\Builder\Container
     */
    protected $aggregationContainer;

    /**
     * @var \Magento\Framework\Search\Adapter\Mysql\Aggregation\DataProviderContainer
     */
    protected $dataProviderContainer;

    protected $groupDataProvider;

    /**
     * AggregationAdapter constructor.
     * @param \Magento\Framework\Search\Adapter\Mysql\Mapper $mapper
     * @param \Magento\Framework\Search\Adapter\Mysql\TemporaryStorageFactory $temporaryStorageFactory
     * @param \Magento\Framework\Search\Adapter\Mysql\Aggregation\Builder\Container $aggregationContainer
     * @param \Magento\Framework\Search\Adapter\Mysql\Aggregation\DataProviderContainer $dataProviderContainer
     * @param \Amasty\Shopby\Model\Adapter\Mysql\Aggregation\GroupDataProviderFactory $groupDataProvider
     */
    public function __construct(
        \Magento\Framework\Search\Adapter\Mysql\Mapper $mapper,
        \Magento\Framework\Search\Adapter\Mysql\TemporaryStorageFactory $temporaryStorageFactory,
        \Magento\Framework\Search\Adapter\Mysql\Aggregation\Builder\Container $aggregationContainer,
        \Magento\Framework\Search\Adapter\Mysql\Aggregation\DataProviderContainer $dataProviderContainer,
        \Amasty\Shopby\Model\Adapter\Mysql\Aggregation\GroupDataProviderFactory $groupDataProvider
    ) {
        $this->mapper = $mapper;
        $this->temporaryStorageFactory = $temporaryStorageFactory;
        $this->aggregationContainer = $aggregationContainer;
        $this->dataProviderContainer = $dataProviderContainer;
        $this->groupDataProvider = $groupDataProvider;
    }

    /**
     * @param \Magento\Framework\Search\RequestInterface $request
     * @param $attributeCode
     * @return array
     */
    public function getBucketByRequest(\Magento\Framework\Search\RequestInterface $request, $attributeCode)
    {
        $query = $this->mapper->buildQuery($request);
        $temporaryStorage = $this->temporaryStorageFactory->create();
        $documentsTable = $temporaryStorage->storeDocumentsFromSelect($query);
        $dataProvider = $this->dataProviderContainer->get($request->getIndex());

        $buckets = $request->getAggregation();
        $attributeCodeGroup = $attributeCode;
        $attributeCode = $attributeCode . "_bucket";

        $currentBucket = null;
        foreach ($buckets as $bucket) {
            if ($bucket->getName() == $attributeCode) {
                $currentBucket = $bucket;
                break;
            }
        }

        if (is_null($currentBucket)) {
            return [];
        }


        $aggregationBuilder = $this->aggregationContainer->get($currentBucket->getType());

        $aggregation = $aggregationBuilder->build(
            $dataProvider,
            $request->getDimensions(),
            $currentBucket,
            $documentsTable
        );

        return $aggregation;
    }

    /**
     * @param \Magento\Framework\Search\RequestInterface $request
     * @param $groups
     * @param $attributeCode
     * @return array
     */
    public function getBucketByRequestGroup(
        \Magento\Framework\Search\RequestInterface $request,
        $groups,
        $attributeCode
    ) {
        $query = $this->mapper->buildQuery($request);

        $temporaryStorage = $this->temporaryStorageFactory->create();
        $documentsTable = $temporaryStorage->storeDocumentsFromSelect($query);
        $dataProvider = $this->groupDataProvider->create();
        $buckets = $request->getAggregation();
        $attributeCodeGroup = $attributeCode;
        $attributeCode = $attributeCode . "_bucket";

        $currentBucket = null;
        foreach ($buckets as $bucket) {
            if ($bucket->getName() == $attributeCode) {
                $currentBucket = $bucket;
                break;
            }
        }

        if (is_null($currentBucket)) {
            return [];
        }
        $aggregation = [];
        foreach ($groups as $code => $group) {
            $aggregationBuilder = $this->aggregationContainer->get($currentBucket->getType());
            $dimensions = $request->getDimensions();
            $dimensions['groups'] = $group;
            $select = $dataProvider->getDataSet($currentBucket, $dimensions, $documentsTable);
            $data = $dataProvider->execute($select);
            $count = $this->calcProducts($data);
            if ($count) {
                $aggregation[$code] = ['value' => $code, 'count' => $count];
            }
        }

        return $aggregation;
    }

    /**
     * @param $data
     * @return int
     */
    protected function calcProducts($data)
    {
        $array = [];
        $count = 0;
        foreach ($data as $value) {
            if (!in_array($value['entity_id'], $array)) {
                $array[] = $value['entity_id'];
                $count++;
            }
        }

        return $count;
    }
}
