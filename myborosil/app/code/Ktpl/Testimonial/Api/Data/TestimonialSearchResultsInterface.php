<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ktpl\Testimonial\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for Ktpl Testimonial search results.
 * @api
 */
interface TestimonialSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get Testimonial list.
     *
     * @return \Ktpl\Testimonial\Api\Data\TestimonialInterface[]
     */
    public function getItems();

    /**
     * Set Testimonial list.
     *
     * @param \Ktpl\Testimonial\Api\Data\TestimonialInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
