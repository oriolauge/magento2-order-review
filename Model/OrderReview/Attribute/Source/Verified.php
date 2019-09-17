<?php

namespace OAG\OrderReview\Model\OrderReview\Attribute\Source;
use Magento\Framework\Data\OptionSourceInterface;

/**
 * Order review verified functionality model
 */
class Verified implements OptionSourceInterface
{
    /**
     * Verified values
     */
    const VERIFIED_YES = 1;

    const VERIFIED_NO = 0;

    /**
     * Retrieve option array
     *
     * @return string[]
     */
    public static function getOptionArray()
    {
        return [self::VERIFIED_YES => __('Yes'), self::VERIFIED_NO => __('No')];
    }

    /**
     * Retrieve option array with empty value
     *
     * @return string[]
     */
    public function toOptionArray()
    {
        $result = [];

        foreach (self::getOptionArray() as $index => $value) {
            $result[] = ['value' => $index, 'label' => $value];
        }

        return $result;
    }
}
