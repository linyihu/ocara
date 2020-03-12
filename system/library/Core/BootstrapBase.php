<?php
/**
 * Created by PhpStorm.
 * User: BORUI-DIY
 * Date: 2017/6/25 0025
 * Time: 下午 1:50
 */

namespace Ocara\Core;

use Ocara\Core\Base;
use \Ocara\Exceptions\Exception;

abstract class BootstrapBase extends Base
{
    const EVENT_BEFORE_BOOTSTRAP = 'beforeBootstrap';

    /**
     * 注册事件
     * @throws Exception
     */
    public function registerEvents()
    {
        $this->event(self::EVENT_BEFORE_BOOTSTRAP)
            ->append(ocConfig(array('EVENTS', 'bootstrap', 'before_bootstrap'), null));
    }

    /**
     * 初始化
     */
    public function init()
    {
        if (empty($_SERVER['REQUEST_METHOD'])) {
            $_SERVER['REQUEST_METHOD'] = 'GET';
        }

        date_default_timezone_set(ocConfig(array('DATE_FORMAT', 'timezone'), 'PRC'));

        if (!@ini_get('short_open_tag')) {
            ocService()->error->show('need_short_open_tag');
        }

        $this->fire(self::EVENT_BEFORE_BOOTSTRAP);
    }
}