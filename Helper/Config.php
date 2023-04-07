<?php
/**
 *  Copyright © Aitoc. All rights reserved.
 */

namespace Aitoc\GoogleReviews\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Aitoc\GoogleReviews\Helper\CustomDeliveryTime;

class Config extends AbstractHelper
{
    public const MODULE_ENABLED_PATH = 'aitoc_google_reviews/general/enabled';
    public const MERCHANT_ID_PATH = 'aitoc_google_reviews/general/merchant_id';
    public const DELIVERY_OFFSET_PATH = 'aitoc_google_reviews/general/delivery_time';

    public const SURVEY_ALL_CUSTOMERS_PATH = 'aitoc_google_reviews/survey/all_customers';
    public const SURVEY_CUSTOMER_GROUPS_PATH = 'aitoc_google_reviews/survey/customer_groups';
    public const SURVEY_LANGUAGE_PATH = 'aitoc_google_reviews/survey/language';
    public const SURVEY_STYLE_PATH = 'aitoc_google_reviews/survey/style';

    public const BADGE_ENABLED_PATH = 'aitoc_google_reviews/badge/enabled';
    public const BADGE_POSITION_PATH = 'aitoc_google_reviews/badge/position';
    public const BADGE_LANGUAGE_PATH = 'aitoc_google_reviews/badge/language';
    public const GTIN_ATTRIBUTE_PATH = 'aitoc_google_reviews/general/gtn_attribute_value';

    /**
     * @var CustomDeliveryTime
     */
    private $customTimeHelper;

    public function __construct(
        Context $context,
        CustomDeliveryTime $CustomDeliveryTime
    ) {
        parent::__construct($context);
        $this->customTimeHelper = $CustomDeliveryTime;
    }

    /**
     * @return bool
     */
    public function isModuleEnabled()
    {
        return (bool) $this->scopeConfig
            ->getValue(self::MODULE_ENABLED_PATH, ScopeInterface::SCOPE_WEBSITES);
    }

    /**
     * @return int
     */
    public function getMerchantId()
    {
        return (int) $this->scopeConfig
            ->getValue(self::MERCHANT_ID_PATH, ScopeInterface::SCOPE_WEBSITES);
    }

    /**
     * @return int
     */
    public function getDeliveryOffset()
    {
        return (int) max(0, $this->scopeConfig
            ->getValue(self::DELIVERY_OFFSET_PATH, ScopeInterface::SCOPE_WEBSITES));
    }

    /**
     * @return bool
     */
    public function isOfferSurveyToAllCustomers()
    {
        return (bool) $this->scopeConfig
            ->getValue(self::SURVEY_ALL_CUSTOMERS_PATH, ScopeInterface::SCOPE_WEBSITES);
    }

    /**
     * @return array
     */
    public function getCustomerGroupsToOffer()
    {
        return explode(',', $this->scopeConfig
            ->getValue(self::SURVEY_CUSTOMER_GROUPS_PATH, ScopeInterface::SCOPE_WEBSITES));
    }

    /**
     * @return string
     */
    public function getSurveyStyle()
    {
        return $this->scopeConfig->getValue(self::SURVEY_STYLE_PATH, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function getSurveyLanguage()
    {
        return $this->scopeConfig->getValue(self::SURVEY_LANGUAGE_PATH, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return bool
     */
    public function isBadgeEnabled()
    {
        return $this->scopeConfig->getValue(self::BADGE_ENABLED_PATH, ScopeInterface::SCOPE_WEBSITES)
            && $this->isModuleEnabled();
    }

    /**
     * @return string
     */
    public function getBadgePosition()
    {
        return $this->scopeConfig->getValue(self::BADGE_POSITION_PATH, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function getBadgeLanguage()
    {
        return $this->scopeConfig->getValue(self::BADGE_LANGUAGE_PATH, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @param string $shippingMethod
     * @param string $country
     * @return int
     */
    public function getCustomDeliveryTimeRules($shippingMethod, $country)
    {
        $customValue = $this->customTimeHelper->getConfigValue($shippingMethod, $country);
        return $customValue ?: $this->scopeConfig
            ->getValue(self::DELIVERY_OFFSET_PATH, ScopeInterface::SCOPE_WEBSITES);
    }

    /**
     * Get GTIN Attribute Code Value
     *
     * @return mixed
     */
    public function getGtinAttributeCode()
    {
        return $this->scopeConfig->getValue(self::GTIN_ATTRIBUTE_PATH, ScopeInterface::SCOPE_WEBSITES);
    }
}
