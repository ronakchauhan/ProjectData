<?php 

namespace Ktpl\ProductView\Plugin\Bundle\Ui\DataProvider\Product\Form\Modifier;

use Magento\Bundle\Ui\DataProvider\Product\Form\Modifier\BundlePanel;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Framework\ObjectManagerInterface;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use Magento\Bundle\Model\Product\Type;
use Magento\Bundle\Api\ProductOptionRepositoryInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;

class Composite
{

    /**
     * @var LocatorInterface
     */
    protected $locator;

    /**
     * @var array
     */
    protected $modifiers = [];

    /**
     * Object Manager
     *
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var ProductOptionRepositoryInterface
     */
    protected $optionsRepository;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @param LocatorInterface $locator
     * @param ProductOptionRepositoryInterface $optionsRepository
     * @param ProductRepositoryInterface $productRepository
     * @param array $modifiers
     */
    public function __construct(
        LocatorInterface $locator,
        ProductOptionRepositoryInterface $optionsRepository,
        ProductRepositoryInterface $productRepository,
        array $modifiers = []
    ) {
        $this->locator = $locator;
        $this->optionsRepository = $optionsRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function aroundModifyData(
        \Magento\Bundle\Ui\DataProvider\Product\Form\Modifier\Composite $subject,
        callable $proceed,
        $data
    ) {

        $data = $proceed($data);
        /** @var \Magento\Catalog\Api\Data\ProductInterface $product */
        $product = $this->locator->getProduct();
        $modelId = $product->getId();
        $isBundleProduct = $product->getTypeId() === Type::TYPE_CODE;
        if ($isBundleProduct && $modelId) {
            $data[$modelId][BundlePanel::CODE_BUNDLE_OPTIONS][BundlePanel::CODE_BUNDLE_OPTIONS] = [];
            /** @var \Magento\Bundle\Api\Data\OptionInterface $option */
            foreach ($this->optionsRepository->getList($product->getSku()) as $option) {
                $selections = [];
                /** @var \Magento\Bundle\Api\Data\LinkInterface $productLink */
                foreach ($option->getProductLinks() as $productLink) {
                    $linkedProduct = $this->productRepository->get($productLink->getSku());
                    $integerQty = 1;
                    if ($linkedProduct->getExtensionAttributes()->getStockItem()) {
                        if ($linkedProduct->getExtensionAttributes()->getStockItem()->getIsQtyDecimal()) {
                            $integerQty = 0;
                        }
                    }
                    $selections[] = [
                        'selection_id' => $productLink->getId(),
                        'option_id' => $productLink->getOptionId(),
                        'product_id' => $linkedProduct->getId(),
                        'name' => $linkedProduct->getName(),
                        'sku' => $linkedProduct->getSku(),
                        'is_default' => ($productLink->getIsDefault()) ? '1' : '0',
                        'selection_price_value' => $productLink->getPrice(),
                        'selection_price_type' => $productLink->getPriceType(),
                        'selection_qty' => (bool)$integerQty ? (int)$productLink->getQty() : $productLink->getQty(),
                        'selection_can_change_qty' => $productLink->getCanChangeQuantity(),
                        'selection_qty_is_integer' => (bool)$integerQty,
                        'position' => $productLink->getPosition(),
                        'delete' => '',
                    ];
                }
                $data[$modelId][BundlePanel::CODE_BUNDLE_OPTIONS][BundlePanel::CODE_BUNDLE_OPTIONS][] = [
                    'position' => $option->getPosition(),
                    'option_id' => $option->getOptionId(),
                    'title' => $option->getTitle(),
                    'option_label' => $option->getOptionLabel(),
                    'default_title' => $option->getDefaultTitle(),
                    'type' => $option->getType(),
                    'required' => ($option->getRequired()) ? '1' : '0',
                    'bundle_selections' => $selections,
                ];
            }
        }
        return $data;
    }
}
