<?php
/**
 * @author Aitoc Team
 *
 * @copyright Copyright (c) 2019 Aitoc (https://www.aitoc.com)
 */

namespace Aitoc\GoogleReviews\Model\Config;

/**
 * Backend for serialized array data
 *
 * @method mixed string|array getValue()
 */
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
     * @return void
     */
    protected function _afterLoad()
    {
        $value = $this->getValue();
        $value = $this->helper->makeArrayFieldValue($value);
        $this->setValue($value);
    }

    /**
     * Prepare data before save
     *
     * @return void
     */
    public function beforeSave()
    {
        $value = $this->getValue();
        $value = $this->helper->makeStorableArrayFieldValue($value);
        $this->setValue($value);
    }
}
