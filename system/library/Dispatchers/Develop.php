<?php
/*************************************************************************************************
 * -----------------------------------------------------------------------------------------------
 * Ocara开源框架  开发者中心控制器管理类controller_admin
 * Copyright (c) http://www.ocara.cn All rights reserved.
 * -----------------------------------------------------------------------------------------------
 * @author Lin YiHu <linyhtianwa@163.com>
 ************************************************************************************************/
namespace Ocara\Dispatchers;

use Ocara\Core\Base;
use Ocara\Modules\Develop\DevelopController;

defined('OC_PATH') or exit('Forbidden!');

class Develop extends Base
{
    /**
     * 分发路由控制器
     * @param $route
     * @throws \Ocara\Exceptions\Exception
     */
    public function dispatch($route)
    {
        if (empty($route['controller']) || empty($route['action'])) {
            ocService()->error->show('null_route');
        }

        $actionMethod = $route['action'] . 'Action';
        $uController = ucfirst($route['controller']);

        $class = sprintf('Ocara\Develop\Controller\%s\%s', $uController, $uController . 'Controller');
        $controller = new $class();
        $controller->$actionMethod();
    }
}