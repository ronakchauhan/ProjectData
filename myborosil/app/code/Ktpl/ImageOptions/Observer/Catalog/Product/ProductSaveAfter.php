<?php

namespace Ktpl\ImageOptions\Observer\Catalog\Product;

use Magento\Framework\Event\ObserverInterface;

class ProductSaveAfter implements ObserverInterface {

    protected $request;
    protected $resource;

    /**
     *
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Framework\App\ResourceConnection $resource\
     */
    public function __construct(
        \Magento\Framework\App\RequestInterface $request, \Magento\Framework\App\ResourceConnection $resource
    ) {
        $this->request = $request;
        $this->resource = $resource;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $data = $this->request->getPostValue();

        if (isset($data['product']['media_gallery']['images'])) {
            // print_r($images);exit;
            $connection = $this->resource->getConnection();
            $tableName = 'catalog_product_entity_media_gallery'; //gives table name with prefix
            $product = $observer->getProduct();
            $mediaGallery = $product->getMediaGallery();

            if (isset($mediaGallery['images'])) {
                foreach ($mediaGallery['images'] as $image) {
                        //Update Data into table
                    $isbigimageValue = !empty($image['isbigimage']) ? (int)$image['isbigimage'] : 0;
                    $sql = "UPDATE " . $tableName . " SET isbigimage = " . $isbigimageValue . " WHERE value_id = " . $image['value_id'];
                    $connection->query($sql);

                    $partsimageValue = !empty($image['partsimage']) ? (int)$image['partsimage'] : 0;
                    $sql = "UPDATE " . $tableName . " SET partsimage = " . $partsimageValue . " WHERE value_id = " . $image['value_id'];
                    $connection->query($sql);

                    $hidelistingimageValue = !empty($image['hidefromlisting']) ? (int)$image['hidefromlisting'] : 0;
                    $sql = "UPDATE " . $tableName . " SET hidefromlisting = " . $hidelistingimageValue . " WHERE value_id = " . $image['value_id'];
                    $connection->query($sql);
                }
            }
        }
    }

}