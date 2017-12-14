<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ktpl\Testimonial\Model;

use Ktpl\Testimonial\Api\Data;
use Ktpl\Testimonial\Api\TestimonialRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Ktpl\Testimonial\Model\ResourceModel\Testimonial as ResourceTestimonial;
use Ktpl\Testimonial\Model\ResourceModel\Testimonial\CollectionFactory as TestimonialCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class TestimonialRepository
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class TestimonialRepository implements TestimonialRepositoryInterface
{
    /**
     * @var ResourceTestimonial
     */
    protected $resource;

    /**
     * @var TestimonialFactory
     */
    protected $testimonialFactory;

    /**
     * @var TestimonialCollectionFactory
     */
    protected $testimonialCollectionFactory;

    /**
     * @var Data\TestimonialSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var DataObjectProcessor
     */
    protected $dataObjectProcessor;

    /**
     * @var \Ktpl\Testimonial\Api\Data\TestimonialInterfaceFactory
     */
    protected $dataTestimonialFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param ResourceTestimonial $resource
     * @param TestimonialFactory $testimonialFactory
     * @param Data\TestimonialInterfaceFactory $dataTestimonialFactory
     * @param TestimonialCollectionFactory $testimonialCollectionFactory
     * @param Data\TestimonialSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceTestimonial $resource,
        TestimonialFactory $testimonialFactory,
        \Ktpl\Testimonial\Api\Data\TestimonialInterfaceFactory $dataTestimonialFactory,
        TestimonialCollectionFactory $testimonialCollectionFactory,
        Data\TestimonialSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->testimonialFactory = $testimonialFactory;
        $this->testimonialCollectionFactory = $testimonialCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataTestimonialFactory = $dataTestimonialFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * Save Testimonial data
     *
     * @param \Ktpl\Testimonial\Api\Data\TestimonialInterface $testimonial
     * @return Testimonial
     * @throws CouldNotSaveException
     */
    public function save(Data\TestimonialInterface $testimonial)
    {
        $storeId = $this->storeManager->getStore()->getId();
        $testimonial->setStoreId($storeId);
        try {
            $this->resource->save($testimonial);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $testimonial;
    }

    /**
     * Load Testimonial data by given Testimonial Identity
     *
     * @param string $testimonialId
     * @return Testimonial
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($testimonialId)
    {
        $testimonial = $this->testimonialFactory->create();
        $this->resource->load($testimonial, $testimonialId);
        if (!$testimonial->getId()) {
            throw new NoSuchEntityException(__('Testimonial with id "%1" does not exist.', $testimonialId));
        }
        return $testimonial;
    }

    /**
     * Load Testimonial data collection by given search criteria
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     * @return \Ktpl\Testimonial\Model\ResourceModel\Testimonial\Collection
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $collection = $this->testimonialCollectionFactory->create();
        foreach ($criteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                if ($filter->getField() === 'store_id') {
                    $collection->addStoreFilter($filter->getValue(), false);
                    continue;
                }
                $condition = $filter->getConditionType() ?: 'eq';
                $collection->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
            }
        }
        $searchResults->setTotalCount($collection->getSize());
        $sortOrders = $criteria->getSortOrders();
        if ($sortOrders) {
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }
        $collection->setCurPage($criteria->getCurrentPage());
        $collection->setPageSize($criteria->getPageSize());
        $testimonials = [];
        /** @var Testimonial $testimonialModel */
        foreach ($collection as $testimonialModel) {
            $testimonialData = $this->dataTestimonialFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $testimonialData,
                $testimonialModel->getData(),
                'Ktpl\Testimonial\Api\Data\TestimonialInterface'
            );
            $testimonials[] = $this->dataObjectProcessor->buildOutputDataArray(
                $testimonialData,
                'Ktpl\Testimonial\Api\Data\TestimonialInterface'
            );
        }
        $searchResults->setItems($testimonials);
        return $searchResults;
    }

    /**
     * Delete Testimonial
     *
     * @param \Ktpl\Testimonial\Api\Data\TestimonialInterface $testimonial
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(Data\TestimonialInterface $testimonial)
    {
        try {
            $this->resource->delete($testimonial);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * Delete Testimonial by given Testimonial Identity
     *
     * @param string $testimonialId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($testimonialId)
    {
        return $this->delete($this->getById($testimonialId));
    }
}
