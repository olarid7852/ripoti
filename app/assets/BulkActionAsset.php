<?php
namespace app\assets;
use yii\web\AssetBundle;
/**
 * Class BulkActionAsset
 * @package app\assets
 * @author Malomo Damilare <damilaremalomo@gmail.com>
 */
class BulkActionAsset extends AssetBundle
{
    public $js = [
        'js/components/bulk-action.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}