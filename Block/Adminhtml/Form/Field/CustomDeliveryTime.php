<?php
/**
 * @author Aitoc Team
 *
 * @copyright Copyright (c) 2019 Aitoc (https://www.aitoc.com)
 */

namespace Aitoc\GoogleReviews\Block\Adminhtml\Form\Field;

use Aitoc\GoogleReviews\Helper\CustomDeliveryTime as Helper;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

class CustomDeliveryTime extends AbstractFieldArray
{
    /** @var  null|Carriers */
    private $carriersRenderer;

    /** @var  null|Countries */
    private $countriesRenderer;

    protected $_template = 'Aitoc_GoogleReviews::system/config/form/field/array.phtml';

    private function getCarriersRenderer()
    {
        if (!$this->carriersRenderer) {
            $this->carriersRenderer = $this->getLayout()->createBlock(
                Carriers::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }

        return $this->carriersRenderer;
    }

    protected function getCountriesRenderer()
    {
        if (!$this->countriesRenderer) {
            $this->countriesRenderer = $this->getLayout()->createBlock(
                Countries::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }

        return $this->countriesRenderer;
    }

    /**
     * Prepare to render
     *
     * @return void
     */
    protected function _prepareToRender()
    {
        $this->addColumn(
            Helper::SHIPPING_METHOD,
            ['label' => __('Shipping Method'), 'renderer' => $this->getCarriersRenderer()]
        );
        $this->addColumn(
            Helper::COUNTRIES,
            ['label' => __('Country'), 'renderer' => $this->getCountriesRenderer(),  'style' => 'width:50px']
        );
        $this->addColumn(
            Helper::DELIVERY_TIME,
            ['label' => __('Days to Deliver'),  'style' => 'width:50px']
        );

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add Delivery Time Rule');
    }

    /**
     * Prepare existing row data object
     *
     * @param \Magento\Framework\DataObject $row
     * @return void
     */
    protected function _prepareArrayRow(\Magento\Framework\DataObject $row)
    {
        $optionExtraAttr['option_' . $this->getCarriersRenderer()->calcOptionHash($row->getData(Helper::SHIPPING_METHOD))]
            = 'selected="selected"';
        $countries = $row->getData(Helper::COUNTRIES);
        foreach ($countries as $country) {
            $optionExtraAttr['option_' . $this->getCountriesRenderer()->calcOptionHash($country)]
                = 'selected="selected"';
        }

        $row->setData('option_extra_attrs', $optionExtraAttr);
    }
}
