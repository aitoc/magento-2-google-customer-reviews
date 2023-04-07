<?php
/**
 *  Copyright © Aitoc. All rights reserved.
 */

namespace Aitoc\GoogleReviews\Block;

use Magento\Framework\View\Element\Template;
use Aitoc\GoogleReviews\Helper\Config as ConfigHelper;

class Badge extends Template
{
    /** @var \Aitoc\GoogleReviews\Helper\Config */
    private $configHelper;

    public function __construct(
        Template\Context $context,
        ConfigHelper $configHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->configHelper = $configHelper;
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        return $this->configHelper->isBadgeEnabled() ? parent::_toHtml() : '';
    }

    /**
     * @return int
     */
    public function getMerchantId()
    {
        return $this->configHelper->getMerchantId();
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        $pos = $this->configHelper->getBadgePosition();
        return $this->escapeHtml($pos);
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        $lang = $this->configHelper->getBadgeLanguage();
        return $this->escapeHtml($lang);
    }
}
