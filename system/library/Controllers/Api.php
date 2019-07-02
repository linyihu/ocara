<?php
/*************************************************************************************************
 * -----------------------------------------------------------------------------------------------
 * Ocara开源框架   API普通控制器类Api
 * Copyright (c) http://www.ocara.cn All rights reserved.
 * -----------------------------------------------------------------------------------------------
 * @author Lin YiHu <linyhtianwa@163.com>
 ************************************************************************************************/
namespace Ocara\Controllers;

use Ocara\Core\ControllerBase;
use Ocara\Core\Response;
use Ocara\Exceptions\Exception;
use Ocara\Interfaces\Controller as ControllerInterface;

class Api extends ControllerBase  implements ControllerInterface
{
    /**
     * 执行动作
     * @param string $actionMethod
     */
    public function doAction($actionMethod)
    {
        if (!$this->isFormSubmit()) {
            if (method_exists($this, 'isSubmit')) {
                $this->isFormSubmit($this->isSubmit());
            } elseif ($this->submitMethod() == 'post') {
                $this->isFormSubmit($this->request->isPost());
            }
        }

        if ($actionMethod == '__action') {
            $this->doClassAction();
        } else {
            $result = $this->$actionMethod();
            $this->render($result);
        }

        $this->fire(self::EVENT_AFTER_ACTION);
    }

    /**
     * 执行动作类实例
     */
    protected function doClassAction()
    {
        if (method_exists($this, '__action')) {
            $this->__action();
        }

        if (method_exists($this, 'registerForms')) {
            $this->registerForms();
        }

        $this->checkForm();
        $result = null;

        if ($this->request->isAjax()) {
            if (method_exists($this, 'ajax')) {
                $result = $this->ajax();
            }
            $this->render($result, false);
        } elseif ($this->isFormSubmit() && method_exists($this, 'submit')) {
            $result = $this->submit();
            $this->formManager->clearToken();
            $this->render($result, false);
        } else {
            if (method_exists($this, 'display')) {
                $this->display();
            }
            $this->render();
        }
    }

    /**
     * 渲染API
     * @param mxied $result
     * @throws Exception
     */
    public function render($result = null)
    {
        if ($this->hasRender()) return;
        $this->renderApi($result);
    }

    /**
     * 渲染API数据
     * @param null $data
     * @param null $message
     * @param string $status
     * @throws Exception
     */
    public function renderApi($data = null, $message = null, $status = 'success')
    {
        if (is_string($message)) {
            $message = $this->lang->get($message);
        }

        $this->response->setContentType($this->contentType);
        $this->fire(self::EVENT_AFTER_RENDER, array($data, $message, $status));

        $content = $this->view->renderApi($this->result);
        $this->view->outputApi($content);

        $this->fire(self::EVENT_AFTER_RENDER);
        $this->hasRender = true;
    }

    /**
     * 渲染前置事件
     * @return mixed
     */
    public function beforeRender($data, $message, $status)
    {
        $this->result = $this->api->getResult($data, $message, $status);

        if (!$this->response->getOption('statusCode')) {
            if ($this->result['status'] == 'success') {
                $this->response->setStatusCode(Response::STATUS_OK);
            } else {
                $this->response->setStatusCode(Response::STATUS_SERVER_ERROR);
            }
        }
    }
}