<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Class ChartJsAsset
 * @package app\assets
 * @author Medinat Apanpa <medinatapampa@yahoo.com>
 */
class ChartJsAsset extends AssetBundle
{
    public $css = [
        '/css/chart/chart.min.css'
    ];

    public $js = [
        '/js/components/chart/chart.min.js'
    ];

    public $depends = [
        AppAsset::class
    ];
}