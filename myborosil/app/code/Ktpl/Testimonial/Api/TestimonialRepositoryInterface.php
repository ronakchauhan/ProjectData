<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ktpl\Testimonial\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Ktpl Testimonial CRUD interface.
 * @api
 */
interface TestimonialRepositoryInterface
{
    /**
     * Save Testimonial.
     *
     * @param \Ktpl\Testimonial\Api\Data\TestimonialInterface $testimonial
     * @return \Ktpl\Testimonial\Api\Data\TestimonialInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(Data\TestimonialInterface $testimonial);

    /**
     * Retrieve Testimonial.
     *
     * @param int $testimonialId
     * @return \Ktpl\Testimonial\Api\Data\TestimonialInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($entityId);

    /**
     * Retrieve Testimonials matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Krpl\Testimonial\Api\Data\TestimonialSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete Testimonial.
     *
     * @param \Krpl\Testimonial\Api\Data\TestimonialInterface $testimonial
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(Data\TestimonialInterface $testimonial);

    /**
     * Delete Testimonial by ID.
     *
     * @param int $testimonialId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($testimonialId);
}
