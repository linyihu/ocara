<?php
/*************************************************************************************************
 * -----------------------------------------------------------------------------------------------
 * Ocara开源框架   缓存类接口Cache
 * Copyright (c) http://www.ocara.cn All rights reserved.
 * -----------------------------------------------------------------------------------------------
 * @author Lin YiHu <linyhtianwa@163.com>
 ************************************************************************************************/

namespace Ocara\Interfaces;

use Ocara\Core\Container;

defined('OC_PATH') or exit('Forbidden!');

interface Bootstrap
{
    /**
     * 初始化
     */
    public function init();

    /**
     * 开发启动
     * @param array $route
     * @return mixed
     */
    public function start($route = array());
}