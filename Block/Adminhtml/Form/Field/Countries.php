<?php
/**
 *  Copyright © Aitoc. All rights reserved.
 */

namespace Aitoc\GoogleReviews\Block\Adminhtml\Form\Field;

/**
 * @method setName(string $value)
 * @method string getName()
 */
class Countries extends \Magento\Framework\View\Element\Html\Select
{
    /** @var array */
    private $countries;

    /** @var \Magento\Directory\Model\ResourceModel\Country\Collection */
    private $countryCollection;

    public function __construct(
        \Magento\Framework\View\Element\Context $context,
        \Magento\Directory\Model\ResourceModel\Country\CollectionFactory $countryCollectionFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->countryCollection = $countryCollectionFactory->create();
    }

    protected function getCountries()
    {
        if ($this->countries === null) {
            $countries = $this->countryCollection->toOptionArray(false);
            foreach ($countries as $country) {
                if (!isset($country['is_region_visible']) || $country['is_region_visible']) {
                    $this->countries[$country['value']] = $country['label'];
                }
            }
        }

        return $this->countries;
    }

    /**
     * @return string
     */
    public function _toHtml()
    {
        if (!$this->getOptions()) {
            foreach ($this->getCountries() as $value => $title) {
                $this->addOption($value, $this->escapeHtml($title));
            }
        }
        $this->setExtraParams('multiple="multiple" style="width:240px"');
        return parent::_toHtml();
    }

    /**
     * Sets name for input element
     *
     * @param string $value
     * @return $this
     */
    public function setInputName($value)
    {
        return $this->setName($value . '[]');
    }
}
