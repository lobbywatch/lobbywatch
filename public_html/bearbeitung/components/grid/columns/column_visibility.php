<?php

class ColumnVisibility
{
    const PHONE = 1;
    const TABLET = 2;
    const DESKTOP = 3;
    const LARGE_DESKTOP = 4;

    /**
     * @param array $modes
     *
     * @return int
     */
    public static function getMinimalVisibility(array $modes)
    {
        if (count($modes) === 0) {
            return self::PHONE;
        }

        return min($modes);
    }
}
