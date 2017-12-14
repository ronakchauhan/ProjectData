<?php

namespace Ktpl\BannerSlider\Model\Banner\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class IsActive
 */
class IsActive implements OptionSourceInterface
{
    /**
     * @var \Ktpl\BannerSlider\Model\Banner
     */
    protected $bannerModel;

    /**
     * Constructor
     *
     * @param \Ktpl\BannerSlider\Model\Banner $bannerModel
     */
    public function __construct(\Ktpl\BannerSlider\Model\Banner $bannerModel)
    {
        $this->bannerModel = $bannerModel;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->bannerModel->getAvailableStatuses();
        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}
