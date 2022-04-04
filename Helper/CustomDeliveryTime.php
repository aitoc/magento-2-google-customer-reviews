<?php
/**
 * @author Aitoc Team
 *
 * @copyright Copyright (c) 2019 Aitoc (https://www.aitoc.com)
 */

namespace Aitoc\GoogleReviews\Helper;

use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\App\Config\ScopeConfigInterface;

class CustomDeliveryTime
{
    const ANY_METHOD = 'any_method';
    const SHIPPING_METHOD = 'shipping_method';
    const COUNTRIES = 'countries';
    const DELIVERY_TIME = 'delivery_time';

    const XML_PATH_CUSTOM_DELIVERY_OFFSET = 'aitoc_google_reviews/general/custom_time';

    /** @var \Magento\Framework\Math\Random */
    private $mathRandom;

    /** @var ScopeConfigInterface  */
    private $scopeConfig;

    public function __construct(
        \Magento\Framework\Math\Random $mathRandom,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->mathRandom = $mathRandom;
        $this->scopeConfig = $scopeConfig;
    }


    /**
     * Retrieve fixed offset value.
     *
     * @param int|float|string|null $offset
     * @return int
     */
    private function fixDeliveryOffset($offset)
    {
        return (int) min(180, max(0, $offset));
    }

    /**
     * Generate a storable representation of a value.
     *
     * @param int|float|string|array $value
     * @return string
     */
    private function serializeValue($value)
    {
        if (is_numeric($value)) {
            $data = (float) $value;
            return (string) $data;
        } else if (is_array($value)) {
            $data = [];
            foreach ($value as $shipMethod => $countries) {
                if (!array_key_exists($shipMethod, $data)) {
                    $data[$shipMethod] = [];
                }

                foreach ($countries as $code => $offset) {
                    if (!array_key_exists($code, $data[$shipMethod])) {
                        $data[$shipMethod][$code] = $offset;
                    }
                }
            }

            return json_encode($data);
        } else {
            return '';
        }
    }

    /**
     * Create a value from a storable representation
     *
     * @param int|float|string $value
     * @return array
     */
    private function unserializeValue($value)
    {
        if (is_string($value) && !empty($value)) {
            return json_decode($value, true);
        }

        return [];
    }

    /**
     * Check whether value is in form retrieved by _encodeArrayFieldValue()
     *
     * @param string|array $value
     * @return bool
     */
    private function isEncodedArrayFieldValue($value)
    {
        if (!is_array($value)) {
            return false;
        }

        unset($value['__empty']);
        foreach ($value as $row) {
            if (!is_array($row) || !array_key_exists(self::DELIVERY_TIME, $row)
            ) {
                return false;
            }
        }

        return true;
    }

    /**
     * Encode value to be used in \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray.
     *
     * @param array $value
     * @return array
     */
    private function encodeArrayFieldValue(array $value)
    {
        $result = [];
        foreach ($value as $shipMethod => $countryData) {
            foreach ($countryData as $country => $deliveryOffset) {
                $resultId = $this->mathRandom->getUniqueHash('_');
                $result[$resultId] = [
                    self::SHIPPING_METHOD => $shipMethod,
                    self::COUNTRIES => explode(',', $country),
                    self::DELIVERY_TIME =>$this->fixDeliveryOffset($deliveryOffset)
                ];
            }
        }

        return $result;
    }

    /**
     * Decode value from used in \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray.
     *
     * @param array $value
     * @return array
     */
    private function decodeArrayFieldValue(array $value)
    {
        $result = [];
        unset($value['__empty']);
        foreach ($value as $row) {
            if (!is_array($row)
                || !array_key_exists(self::SHIPPING_METHOD, $row)
                || !array_key_exists(self::COUNTRIES, $row)
                || !array_key_exists(self::DELIVERY_TIME, $row)
            ) {
                continue;
            }

            if ('0' === $row[self::SHIPPING_METHOD] || [] === $row[self::COUNTRIES]) {
                continue;
            }

            $countries = implode(',', $row[self::COUNTRIES]);
            $result[$row[self::SHIPPING_METHOD]][$countries] =
                $this->fixDeliveryOffset($row[self::DELIVERY_TIME]);
        }

        // Push any_method last.
        if (isset($result[self::ANY_METHOD])) {
            $anyMethod = [self::ANY_METHOD => $result[self::ANY_METHOD]];
            unset($result[self::ANY_METHOD]);
            $result = array_merge($anyMethod, $result);
        }

        return $result;
    }

    /**
     * Retrieve value from config.
     *
     * @param string $shippingMethod
     * @param string $country
     * @return int|bool
     */
    public function getConfigValue($shippingMethod, $country)
    {
        $value = $this->scopeConfig->getValue(self::XML_PATH_CUSTOM_DELIVERY_OFFSET, ScopeInterface::SCOPE_WEBSITES);
        $value = $this->unserializeValue($value);
        if ($this->isEncodedArrayFieldValue($value)) {
            $value = $this->decodeArrayFieldValue($value);
        }

        if (isset($value[$shippingMethod])) {
            $method = $value[$shippingMethod];
            foreach ($method as $countries => $offset) {
                if (in_array($country, explode(',', $countries))) {
                    return $offset;
                }
            }
        }

        if (isset($value[self::ANY_METHOD])) {
            $method = $value[self::ANY_METHOD];
            foreach ($method as $countries => $offset) {
                if (in_array($country, explode(',', $countries))) {
                    return $offset;
                }
            }
        }

        return false;
    }

    /**
     * Make value readable by \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
     *
     * @param string|array $value
     * @return array
     */
    public function makeArrayFieldValue($value)
    {
        $value = $this->unserializeValue($value);
        if (!$this->isEncodedArrayFieldValue($value)) {
            $value = $this->encodeArrayFieldValue($value);
        }
        return $value;
    }

    /**
     * Make value ready for store
     *
     * @param string|array $value
     * @return string
     */
    public function makeStorableArrayFieldValue($value)
    {
        if ($this->isEncodedArrayFieldValue($value)) {
            $value = $this->decodeArrayFieldValue($value);
        }
        $value = $this->serializeValue($value);
        return $value;
    }
}