<?php
/**
 *  Copyright © Aitoc. All rights reserved.
 */

namespace Aitoc\GoogleReviews\Block\Adminhtml\Form\Field;

use Aitoc\GoogleReviews\Helper\CustomDeliveryTime as Helper;

class Carriers extends \Magento\Framework\View\Element\Html\Select
{
    /** @var array */
    private $carriers;

    /** @var \Magento\Shipping\Model\Config */
    private $shippingMethodConfig;

    public function __construct(
        \Magento\Framework\View\Element\Context $context,
        \Magento\Shipping\Model\Config $shippingMethodConfig,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->shippingMethodConfig = $shippingMethodConfig;
    }

    protected function getCarriers()
    {
        if ($this->carriers === null) {
            $this->carriers = [Helper::ANY_METHOD => __('Any Method')];

            $carriers = $this->shippingMethodConfig->getActiveCarriers();
            foreach ($carriers as $carrierCode => $carrier) {
                if ($methods = $carrier->getAllowedMethods()) {
                    if (!$carrierTitle = $this->_scopeConfig->getValue("carriers/$carrierCode/title", 'store')) {
                        $carrierTitle = __($carrierCode);
                    }
                    foreach ($methods as $methodCode => $methodTitle) {
                        $value = $carrierCode . '_' . $methodCode;
                        $this->carriers[$value] = $carrierTitle . ' — ' . $methodTitle;
                    }
                }
            }
        }

        return $this->carriers;
    }

    /**
     * @param string $value
     * @return mixed
     */
    public function setInputName($value)
    {
        return $this->setName($value);
    }

    /**
     * @return string
     */
    public function _toHtml()
    {
        if (!$this->getOptions()) {
            foreach ($this->getCarriers() as $value => $title) {
                $this->addOption($value, $this->escapeHtml($title));
            }
        }

        $this->setExtraParams('style="width:220px"');
        return parent::_toHtml();
    }
}
