<?php

namespace Ktpl\Blog\Model\Config\Source;

/**
 * Used in recent post widget
 *
 */
class Tag implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var \Ktpl\Blog\Model\ResourceModel\Tag\CollectionFactory
     */
    protected $tagCollectionFactory;

    /**
     * @var array
     */
    protected $options;

    /**
     * Initialize dependencies.
     *
     * @param \Ktpl\Blog\Model\ResourceModel\Tag\CollectionFactory $authorCollectionFactory
     * @param void
     */
    public function __construct(
        \Ktpl\Blog\Model\ResourceModel\Tag\CollectionFactory $tagCollectionFactory
    ) {
        $this->tagCollectionFactory = $tagCollectionFactory;
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        if ($this->options === null) {
            $this->options = [];
            $collection = $this->tagCollectionFactory->create();
            $collection->setOrder('title');

            foreach ($collection as $item) {
                $this->options[] = [
                    'label' => $item->getTitle(),
                    'value' => $item->getId(),
                ];
            }
        }

        return $this->options;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        $array = [];
        foreach ($this->toOptionArray() as $item) {
            $array[$item['value']] = $item['label'];
        }
        return $array;
    }

}
