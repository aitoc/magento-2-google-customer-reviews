<?php
/**
 *  Copyright © Aitoc. All rights reserved.
 */

namespace Aitoc\GoogleReviews\Model\Config;

class CustomDeliveryTime extends \Magento\Framework\App\Config\Value
{
    /** @var \Aitoc\GoogleReviews\Helper\CustomDeliveryTime */
    private $helper;

    protected function _construct()
    {
        parent::_construct();
        $this->helper = $this->getData('helper');
    }

    /**
     * Process data after load
     *
     * @return CustomDeliveryTime|void
     */
    protected function _afterLoad()
    {
        $value = $this->getValue();
        $value = $this->helper->makeArrayFieldValue($value);
        $this->setValue(json_encode($value));
    }

    /**
     * Prepare data before save
     *
     * @return CustomDeliveryTime|void
     */
    public function beforeSave()
    {
        $value = $this->getValue();
        $value = $this->helper->makeStorableArrayFieldValue($value);
        $this->setValue($value);
    }
}
