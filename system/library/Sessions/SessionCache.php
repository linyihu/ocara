<?php
/*************************************************************************************************
 * -----------------------------------------------------------------------------------------------
 * Ocara开源框架   Session文件方式处理类SessionFile
 * Copyright (c) http://www.ocara.cn All rights reserved.
 * -----------------------------------------------------------------------------------------------
 * @author Lin YiHu <linyhtianwa@163.com>
 ************************************************************************************************/
namespace Ocara\Sessions;

use Ocara\Core\CacheFactory;
use Ocara\Exceptions\Exception;
use Ocara\Core\ServiceProvider;

defined('OC_PATH') or exit('Forbidden!');

class SessionCache extends ServiceProvider
{
    private $prefix;

    /**
     * 注册服务
     * @throws Exception
     */
    public function register()
    {
        parent::register(); // TODO: Change the autogenerated stub

        $cacheName = ocConfig(array('SESSION', 'options', 'server'), CacheFactory::getDefaultServer());
        $this->container->bindSingleton('_plugin', function () use ($cacheName){
            CacheFactory::create($cacheName);
        });
    }

    /**
     * 初始化
     */
    public function init()
    {
        $prefix = ocConfig(array('SESSION', 'options', 'location'), 'session_');
        $this->prefix = $prefix;
    }

    /**
     * session打开
     * @return bool
     */
    public function open()
    {
		if (is_object($this->plugin(false))) {
			return true;
		}
        return false;
    }

    /**
     * session关闭
     * @return bool
     */
    public function close()
    {
        return true;
    }

    /**
     * 读取session信息
     * @param $id
     * @return bool
     */
    public function read($id)
    {
    	$this->plugin()->get($this->prefix . $id);
    	return false;
    }

    /**
     * 保存session
     * @param $id
     * @param $data
     * @return bool
     */
    public function write($id, $data)
    {
        try {
            $this->plugin()->set($this->prefix . $id, $data);
        } catch(\Exception $exception) {
            ocService()->error->show($exception->getMessage());
        }

        return true;
    }

    /**
     * 销毁session
     * @param string $id
     * @return bool
     */
    public function destroy($id)
    {
        $this->plugin()->delete($this->prefix . $id);
        return true;
    }

    /**
     * 垃圾回收
     * @param $maxlifetime
     * @return bool
     */
    public function gc($maxlifetime)
    {
        return true;
    }
}