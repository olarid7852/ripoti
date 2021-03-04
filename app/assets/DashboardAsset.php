<?php

namespace app\assets;

/**
 * Class DashboardAsset
 * @package app\assets
 * @author Medinat Apanpa <medinatapampa@yahoo.com>
 */
class DashboardAsset extends AppAsset
{
    public $js = [
        'js/dashboard.js'
    ];

    public $depends = [
        ChartJsAsset::class
    ];
}