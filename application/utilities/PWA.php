<?php

class PWA
{
    public static function orientationArr(int $langId): array
    {
        return [
            'Portrait' => Label::getLabel('PWALBL_Portrait', $langId),
            'Landscape' => Label::getLabel('PWALBL_Landscape', $langId),
        ];
    }
    
    public static function displayArr(int $langId): array
    {
        return [
            'Full Screen' => Label::getLabel('PWALBL_Full_Screen', $langId),
            'Standalone' => Label::getLabel('PWALBL_Standalone', $langId),
            'Minimal UI' => Label::getLabel('PWALBL_Minimal_UI', $langId),
            'Browser' => Label::getLabel('PWALBL_Browser', $langId)
        ];
    }
}
