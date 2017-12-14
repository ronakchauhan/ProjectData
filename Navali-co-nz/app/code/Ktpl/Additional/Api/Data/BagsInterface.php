<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ktpl\Additional\Api\Data;

/**
 * Static Bag interface.
 * @api
 */
interface BagsInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ENTITY_ID = 'entity_id';
    const NAME      = 'name';
    const EMAIl     = 'email';
    const COMMENT   = 'comment';
    const BAGIMAGE  = 'bag_image';

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
     * Get valid from date
     *
     * @return string|null
     */
    public function getEmail();

    /**
     * Get comment
     *
     * @return string|null
     */
    public function getComment();

    /**
     * Get bag image
     *
     * @return string|null
     */
    public function getBagImage();


    /**
     * Set ID
     *
     * @param int $bannerId
     * @return BagsInterface
     */
    public function setEntityId($bannerId);

    /**
     * Set name
     *
     * @param string $name
     * @return BagsInterface
     */
    public function setName($name);

    /**
     * Set email
     *
     * @param string $email
     * @return BagsInterface
     */
    public function setEmail($email);

    /**
     * Set comment
     *
     * @param string $comment
     * @return BagsInterface
     */
    public function setComment($comment);

    /**
     * Set bag image
     *
     * @param string $bagimage
     * @return BagsInterface
     */
    public function setBagImage($bagimage);
}
