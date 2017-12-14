<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ktpl\Testimonial\Api\Data;

/**
 * Static Testimonial interface.
 * @api
 */
interface TestimonialInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ENTITY_ID   = 'entity_id';
    const NAME        = 'name';
    const TITLE       = 'title';
    const DESCRIPTION = 'description';
    const DATE        = 'date';
    const CREATED_AT  = 'created_at';
    const UPDATED_AT  = 'updated_at';
    const STATUS      = 'status';

    /**#@-*/

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getEntityId();

    /**
     * Get Name
     *
     * @return string|null
     */
    public function getName();

    /**
     * Get Title
     *
     * @return string|null
     */
    public function getTitle();

    /**
     * Get Description
     *
     * @return string|null
     */
    public function getDescription();

    /**
     * Get Testimonial Date
     *
     * @return string|null
     */
    public function getDate();

    /**
     * Get Created Date
     *
     * @return string|null
     */
    public function getCreatedAt();
    /**
     * Get Updated Date
     *
     * @return string|null
     */
    public function getUpdatedAt();
    /**
     * Get Status
     *
     * @return string|null
     */
    public function getStatus();

    /**
     * Set ID
     *
     * @param int $entityId
     * @return TestimonialInterface
     */
    public function setEntityId($entityId);

    /**
     * Set name
     *
     * @param string $name
     * @return TestimonialInterface
     */
    public function setName($name);

    /**
     * Set Title
     *
     * @param string $title
     * @return TestimonialInterface
     */
    public function setTitle($title);

    /**
     * Set Description
     *
     * @param string $description
     * @return TestimonialInterface
     */
    public function setDescription($description);

    /**
     * Set Testimonial Date
     *
     * @param string $date
     * @return TestimonialInterface
     */
    public function setDate($date);

    /**
     * Set Created Date
     *
     * @param string $createdDate
     * @return TestimonialInterface
     */
    public function setCreatedAt($createdDate);

    /**
     * Set Updated Date
     *
     * @param string $updatedAt
     * @return TestimonialInterface
     */
    public function setUpdatedAt($updatedAt);

    /**
     * Set Status
     *
     * @param string $status
     * @return TestimonialInterface
     */
    public function setStatus($status);
}
