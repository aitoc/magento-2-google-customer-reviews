<?php
/**
 * @author Aitoc Team
 *
 * @copyright Copyright (c) 2019 Aitoc (https://www.aitoc.com]
 */

namespace Aitoc\GoogleReviews\Model\Source;

use Magento\Framework\Option\ArrayInterface;

class BadgePosition implements ArrayInterface
{
    public function toOptionArray()
    {
        return [
            'BOTTOM_RIGHT' => __('Bottom Right'),
            'BOTTOM_LEFT' => __('Bottom Left'),
        ];
    }
}