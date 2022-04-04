<?php
/**
 * @author Aitoc Team
 *
 * @copyright Copyright (c) 2019 Aitoc (https://www.aitoc.com)
 */

namespace Aitoc\GoogleReviews\Model\Source;

use Magento\Framework\Locale\TranslatedLists;
use Magento\Framework\Option\ArrayInterface;

class Language implements ArrayInterface
{
     /** @var \Magento\Framework\Locale\TranslatedLists */
    private $translatedLists;

    public function __construct(TranslatedLists $translatedLists)
    {
        $this->translatedLists = $translatedLists;
    }

    public function toOptionArray()
    {
        $allowedLanguages = [
            "cs", "da", "de", "en_AU", "en_GB", "en_US", "es", "fr",
            "it", "ja", "nl", "no", "pl", "pt_BR", "ru", "sv", "tr"
        ];

        $result = [__('User Environment Defined')];
        $locales = $this->translatedLists->getOptionLocales();

        foreach ($locales as $language) {
            foreach ($allowedLanguages as $index => $allowed) {
                if (strpos($language['value'], $allowed) === 0) {
                    unset($allowedLanguages[$index]);
                    $label = $language['label'];

                    if (strlen($allowed) == 2) {
                        $label = preg_replace('/(.*?)\(.*?\)/', '$1', $label);
                    }

                    $result[$allowed] = $label;
                }
            }
        }

        return $result;
    }
}