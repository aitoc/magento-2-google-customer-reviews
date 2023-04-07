<?php
/**
 *  Copyright © Aitoc. All rights reserved.
 */

namespace Aitoc\GoogleReviews\Model\Source;

use Magento\Framework\Option\ArrayInterface;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory;

class ProductAttributesList implements ArrayInterface
{
    /**
     * @var CollectionFactory
     */
    protected $attributeFactory;

    /**
     * @param CollectionFactory $attributeFactory
     */
    public function __construct(CollectionFactory $attributeFactory)
    {
        $this->attributeFactory = $attributeFactory;
    }

    /**
     * Display available product attribute list
     *
     * @return array
     */
    public function toOptionArray()
    {
        $productAttributesArray = $this->getAvailableProductAttributesList();
        $arrayFirstElement = ['value' => '', 'label' => __('--Please Select--')];
        array_unshift($productAttributesArray, $arrayFirstElement);
        return $productAttributesArray;
    }

    /**
     * Get Frontend Label Enable Product Attribute List
     *
     * @return array
     */
    public function getAvailableProductAttributesList()
    {
        $attribute_data = [];
        $attributeInfo = $this->attributeFactory->create();
        foreach ($attributeInfo as $key => $items) {
            if ($items->getData('frontend_label')) {
                array_push($attribute_data, ['value'=>$items->getData('attribute_code'), 'label' => $items->getData('frontend_label')]);
            }
        }
        usort($attribute_data, function ($a, $b) {
              return $a['label'] <=> $b['label'];
        });
        return $attribute_data;
    }
}
