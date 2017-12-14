<?php

namespace Ktpl\BannerSlider\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\Component\Listing\Columns\Column;

class Image extends Column
{
    const NAME = 'image';
    const ALT_FIELD = 'title';

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param Image $imageHelper
     * @param UrlInterface $urlBuilder
     * @param StoreManagerInterface $storeManager
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        StoreManagerInterface $storeManager,
        array $components = [],
        array $data = []
    ) {
        $this->storeManager = $storeManager;
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if(isset($dataSource['data']['items'])) {
            foreach($dataSource['data']['items'] as &$item) {
                if(isset($item[self::NAME]) && trim($item[self::NAME]) != '') {
                    $url = $this->storeManager->getStore()->getBaseUrl(
                        \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                    ) . 'banners/image/' . $item[self::NAME];

                    $item[self::NAME . '_src'] = $url;
                    $item[self::NAME . '_alt'] = isset($item[self::ALT_FIELD])? $item[self::ALT_FIELD]: '';
                    $item[self::NAME . '_link'] = $this->urlBuilder->getUrl(
                        'banner/index/edit',
                        ['entity_id' => $item['entity_id']]
                    );
                    $item[self::NAME . '_orig_src'] = $url;
                }
            }
        }

        return $dataSource;
    }
}