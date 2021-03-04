<?php

namespace app\assets;

/**
 * Class siteCasesAsset
 * @package app\assets
 * @author Medinat Apanpa <medinatapampa@yahoo.com>
 */
class SiteCasesAsset extends AppAsset
{
    public $js = [
        'js/siteCases.js'
    ];

    public $depends = [
        ChartJsAsset::class
    ];
}