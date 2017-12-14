<?php

namespace Ktpl\Blog\Api;

interface ManagementInterface
{
    /**
     * Create new item.
     *
     * @api
     * @param string $data.
     * @return string.
     */
    public function create($data);

    /**
     * Update item by id.
     *
     * @api
     * @param int $id.
     * @param string $data.
     * @return string.
     */
    public function update($id, $data);

    /**
     * Remove item by id.
     *
     * @api
     * @param int $id.
     * @return bool.
     */
    public function delete($id);

    /**
     * Get item by id.
     *
     * @api
     * @param int $id.
     * @return bool.
     */
    public function get($id);

    /**
     * Get item by id and store id, only if item published
     *
     * @api
     * @param int $id
     * @param  int $storeId
     * @return bool.
     */
    public function view($id, $storeId);
}