<?php
/**
 * @author Aitoc Team
 *
 * @copyright Copyright (c) 2019 Aitoc (https://www.aitoc.com)
 */

namespace Aitoc\GoogleReviews\Block;

use Magento\Sales\Model\Order;
use Magento\Framework\View\Element\Template;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;
use Aitoc\GoogleReviews\Helper\Config as ConfigHelper;

/**
 * @method setOrderIds(array $orderIds)
 * @method array getOrderIds()
 */
class Survey extends Template
{
    const SURVEY_DATA_FIELDS_COUNT = 6;

    /** @var \Aitoc\GoogleReviews\Helper\Config */
    private $configHelper;

    /** @var OrderCollectionFactory */
    private $salesOrderCollectionFactory;

    /** @var DateTime  */
    private $date;

    /** @var array */
    private $surveyData = [];

    public function __construct(
        Template\Context $context,
        ConfigHelper $configHelper,
        DateTime $date,
        OrderCollectionFactory $salesOrderCollectionFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->configHelper = $configHelper;
        $this->salesOrderCollectionFactory = $salesOrderCollectionFactory;
        $this->date = $date;
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        if ($this->isRenderAllowed()) {
            $this->prepareSurveyData();
            if ($this->isValid()) {
                return parent::_toHtml();
            }
        }

        return '';
    }

    /**
     * @return void
     */
    private function prepareSurveyData()
    {
        $this->surveyData = $this->getOrderData();
        $this->surveyData['merchant_id'] = $this->configHelper->getMerchantId();
        $this->surveyData['opt_in_style'] = $this->configHelper->getSurveyStyle();
    }

    /**
     * @return array
     */
    private function getOrderData()
    {
        $orderData = [];

        $orderIds = $this->getOrderIds();
        if (empty($orderIds) || !is_array($orderIds)) {
            return $orderData;
        }

        /** @var \Magento\Sales\Model\ResourceModel\Order\Collection $collection */
        $collection = $this->salesOrderCollectionFactory->create();
        $collection->addFieldToFilter('entity_id', ['in' => $orderIds]);

        if (!$collection->getSize()) {
            return $orderData;
        }

        foreach ($collection as $order) {
            /** @var Order $order */
            try {
                if (!$this->validateCustomerGroup($order->getCustomerGroupId())) {
                    throw new \Exception('Restricted customer group.');
                }

                $orderData['email'] = $order->getCustomerEmail();
                $orderData['order_id'] = $order->getIncrementId();

                if ($order->getIsVirtual()) {
                    $address = $order->getBillingAddress();
                } else {
                    $address = $order->getShippingAddress();
                }

                $orderData['delivery_country'] = $address->getCountryId();
                $orderData['estimated_delivery_date'] = $this->getDeliveryDate($order, $address->getCountryId());

                foreach ($orderData as $item) {
                    if (empty($item)) {
                        throw new \Exception('Invalid value.');
                    }
                }
            } catch (\Exception $e) {
                $orderData = [];
                continue;
            }

            break;
        }

        return $orderData;
    }

    /**
     * @return bool
     */
    private function isRenderAllowed()
    {
        return $this->configHelper->isModuleEnabled();
    }

    /**
     * @param $groupId
     * @return bool
     */
    private function validateCustomerGroup($groupId)
    {
        return $this->configHelper->isOfferSurveyToAllCustomers()
            || in_array($groupId, $this->configHelper->getCustomerGroupsToOffer());
    }

    /**
     * @return bool
     */
    private function isValid()
    {
        return count($this->surveyData) == self::SURVEY_DATA_FIELDS_COUNT;
    }

    /**
     * @param Order $order
     * @param string $countryCode
     * @return string
     */
    private function getDeliveryDate($order, $countryCode)
    {
        $createdDate = $this->date->date('Y-m-d', $order->getCreatedAt());
        if ($order->getIsVirtual()) {
            return $createdDate;
        }

        $offset = max(0, $this->configHelper->getCustomDeliveryTimeRules($order->getShippingMethod(), $countryCode));
        return $this->date->date('Y-m-d', $createdDate . ' + ' . $offset . 'days');
    }

    /**
     * @param $key
     * @return string
     */
    public function getSurveyData($key)
    {
        return $this->escapeHtml(isset($this->surveyData[$key]) ? $this->surveyData[$key] : null);
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        $lang = $this->configHelper->getSurveyLanguage();
        return $this->escapeHtml($lang);
    }
}