<?php
/*************************************************************************************************
 * -----------------------------------------------------------------------------------------------
 * Ocara开源框架   普通控制器特性类Common
 * Copyright (c) http://www.ocara.cn All rights reserved.
 * -----------------------------------------------------------------------------------------------
 * @author Lin YiHu <linyhtianwa@163.com>
 ************************************************************************************************/
namespace Ocara\Controllers\Feature;

use Ocara\Interfaces\Feature;
use Ocara\Controllers\Feature\Base;

defined('OC_PATH') or exit('Forbidden!');

class Common extends Base implements Feature
{
    /**
     * 获取路由
     * @param $module
     * @param $controller
     * @param array $get
     * @return array|mixed
     * @throws \Ocara\Exceptions\Exception
     */
    public function getRoute($module, $controller, array $get)
    {
        $action = array_shift($get);

        if (empty($action)) {
            $action = ocConfig('DEFAULT_ACTION');
        }

        $_GET = array_values($get);
        if (empty($action)) {
            $action = ocConfig('DEFAULT_ACTION', 'index');
        }

        $route = array($module, $controller, $action);
        return $route;
    }
}