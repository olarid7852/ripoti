<?php

namespace app\assets;

use CottaCush\Yii2\Assets\AssetBundle;
/**
 * Class MaterialDesignIconsAsset
 * @package app\assets
 * @author Bolade Oye <bolade@cottacush.com>
 * @author Maryfaith Mgbede <adaamgbede@gmail.com>
 */
class MaterialDesignIconsAsset extends AssetBundle
{
    public $css = [
        'css/material-design-icons.css'
    ];
    public $productionCss = [
        'https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined'
    ];
}