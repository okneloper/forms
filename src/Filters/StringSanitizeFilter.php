<?php

namespace Okneloper\Forms\Filters;

/**
 * Sanitizes string in a more sensible way than PHP's native filter.
 * This filter consists of the following filters:
 * - StripTagsFilter
 * - NativeFilter with FILTER_FLAG_STRIP_LOW
 */
class StringSanitizeFilter extends CompositeFilter
{
    public function __construct()
    {
        $this->addFilter(new StripTagsFilter());

        $this->addFilter(new NativeFilter(FILTER_UNSAFE_RAW, [
            'flags' => FILTER_FLAG_STRIP_LOW,
        ]));

        $this->addFilter(new StringTrimFilter());
    }
}
