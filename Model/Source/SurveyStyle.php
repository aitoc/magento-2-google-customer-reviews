<?php
/**
 * @author Aitoc Team
 *
 * @copyright Copyright (c) 2019 Aitoc (https://www.aitoc.com)
 */

namespace Aitoc\GoogleReviews\Model\Source;

use Magento\Framework\Locale\TranslatedLists;
use Magento\Framework\Option\ArrayInterface;

class SurveyStyle implements ArrayInterface
{
    public function toOptionArray()
    {
        return [
            'CENTER_DIALOG' => __('Center Dialog'),
            'BOTTOM_RIGHT_DIALOG' => __('Bottom Right Dialog'),
            'BOTTOM_LEFT_DIALOG' => __('Bottom Left Dialog'),
            'TOP_RIGHT_DIALOG' => __('Top Right Dialog'),
            'TOP_LEFT_DIALOG' => __('Top Left Dialog'),
            'BOTTOM_TRAY' => __('Bottom Tray')
        ];
    }
}