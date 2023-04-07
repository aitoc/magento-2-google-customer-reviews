<?php
/**
 *  Copyright © Aitoc. All rights reserved.
 */

namespace Aitoc\GoogleReviews\Block;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Model\Order;
use Magento\Framework\View\Element\Template;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;
use Aitoc\GoogleReviews\Helper\Config as ConfigHelper;
use Magento\Catalog\Model\ProductRepository;
use Psr\Log\LoggerInterface;
use Exception;

/**
 * @method setOrderIds(array $orderIds)
 * @method array getOrderIds()
 */
class Survey extends Template
{
    protected const SURVEY_DATA_FIELDS_COUNT = 7;

    /** @var \Aitoc\GoogleReviews\Helper\Config */
    private $configHelper;

    /** @var OrderCollectionFactory */
    private $salesOrderCollectionFactory;

    /** @var DateTime  */
    private $date;

    /** @var array */
    private $surveyData = [];

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param Template\Context $context
     * @param ConfigHelper $configHelper
     * @param DateTime $date
     * @param OrderCollectionFactory $salesOrderCollectionFactory
     * @param ProductRepository $productRepository
     * @param LoggerInterface $logger
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        ConfigHelper $configHelper,
        DateTime $date,
        OrderCollectionFactory $salesOrderCollectionFactory,
        ProductRepository $productRepository,
        LoggerInterface $logger,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->configHelper = $configHelper;
        $this->salesOrderCollectionFactory = $salesOrderCollectionFactory;
        $this->productRepository = $productRepository;
        $this->logger = $logger;
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
                    $this->logger->info('Restricted customer group.');
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
                        $this->logger->info("Invalid value");
                    }
                }

                $purchaseItems = $order->getAllVisibleItems();
                $gtinData = [];
                if ($purchaseItems) {
                    foreach ($purchaseItems as $item) {
                        $product = $this->getProductById($item->getProductId());
                        $attributes = $product->getAttributes();
                        if ($attributes) {
                            $gtinData = $this->getAdminSelectedProductAttributeValue($attributes, $product, $gtinData);
                        }
                    }
                }
                $orderData['gtin'] = implode(', ', $gtinData);
            } catch (Exception $e) {
                $this->logger->info($e->getMessage());
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
     * @param int $groupId
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
     * @param string $key
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

    /**
     * Get Product by Product Id
     *
     * @param int $id
     * @return \Magento\Catalog\Api\Data\ProductInterface|mixed|null
     */
    public function getProductById($id)
    {
        try {
            return $this->productRepository->getById($id);
        } catch (NoSuchEntityException $e) {
            $this->logger->info($id.' Product can not find in the database '.$e->getMessage());
        }
    }

    /**
     * Get Admin Selected GTIN Product Attribute Value
     *
     * @param mixed $attributes
     * @param mixed $product
     * @param array $gtinValues
     * @return mixed
     */
    public function getAdminSelectedProductAttributeValue($attributes, $product, $gtinValues)
    {
        foreach ($attributes as $attribute) {
            if ($this->configHelper->getGtinAttributeCode()) {
                if ($attribute->getAttributeCode() == $this->configHelper->getGtinAttributeCode()) {
                    $attributeValue =  $this->escapeHtml($attribute->getFrontend()->getValue($product));
                    if ($attributeValue) {
                        $gtinValues[] = '{"gtin":"'.$attributeValue.'"}';
                    }
                }
            }
        }
        return $gtinValues;
    }

    /**
     * Get GTIN Array Data by Key
     *
     * @param string $key
     * @return mixed|null
     */
    public function getGtinValuesFromKey($key)
    {
        return isset($this->surveyData[$key]) ? $this->surveyData[$key] : null;
    }
}
