<?php
/*************************************************************************************************
 * -----------------------------------------------------------------------------------------------
 * Ocara开源框架   普通控制器特性类Common
 * Copyright (c) http://www.ocara.cn All rights reserved.
 * -----------------------------------------------------------------------------------------------
 * @author Lin YiHu <linyhtianwa@163.com>
 ************************************************************************************************/
namespace Ocara\Feature;
use Ocara\Interfaces\Feature;
use Ocara\Ocara;
use Ocara\Request;
use Ocara\Route;
use Ocara\Url;

defined('OC_PATH') or exit('Forbidden!');

class Common extends FeatureBase implements Feature
{
    /**
     * 获取路由
     * @param array $get
     * @return array|bool|mixed|null
     */
    public function getAction(array $get)
    {
        $action = ocGet(0, $get);

        if ($action) {
            ocDel($get, 0);
        } else {
            $action = ocConfig('DEFAULT_ACTION');
        }

        $_GET = array_values($get);
        return $action;
    }
}