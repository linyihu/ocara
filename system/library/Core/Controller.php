<?php
/*************************************************************************************************
 * -----------------------------------------------------------------------------------------------
 * Ocara开源框架   应用控制器基类Controller
 * Copyright (c) http://www.ocara.cn All rights reserved.
 * -----------------------------------------------------------------------------------------------
 * @author Lin YiHu <linyhtianwa@163.com>
 ************************************************************************************************/
namespace Ocara\Core;

use Ocara\Core\ServiceProvider;
use Ocara\Interfaces\Controller as ControllerInterface;
use Ocara\Core\Route;

defined('OC_PATH') or exit('Forbidden!');

class Controller extends serviceProvider implements ControllerInterface
{
	/**
	 * @var $_provider 控制器提供者
	 */
	protected $_models;
	protected $_provider;
    protected $_isSubmit = null;
    protected $_submitMethod = 'post';
    protected $_checkForm = true;
    protected $_hasRender = false;

    protected static $_providerType;

	/**
	 * 初始化设置
	 */
	public function init()
	{
	    $route = $this->getRoute();
        $provider = Route::getProviderClass(self::providerType());

        if (!ocClassExists($provider)){
            ocService()->error->show('not_exists_class', $provider);
        }

		$this->_provider = new $provider(compact('route'));
        $this->_provider->bindEvents($this);

		$this->config->set('SOURCE.ajax.return_result', array($this->_provider, 'formatAjaxResult'));

		method_exists($this, '_start') && $this->_start();
		method_exists($this, '_module') && $this->_module();
		method_exists($this, '_control') && $this->_control();
	}

	/**
	 * 获取当前的提供者
	 * @return 控制器提供者
	 */
	public function provider()
	{
		return $this->_provider;
	}

    /**
     * 获取提供者类型
     */
	public static function providerType()
    {
	    return self::$_providerType ? ucfirst(self::$_providerType): 'Common';
    }

    /**
     * 获取当前路由
     * @return mixed
     */
	public function getRoute($name = null)
    {
	    return call_user_func_array(array(ocService()->app, 'getRoute'), func_get_args());
    }

	/**
	 * 执行动作
	 * @param string $actionMethod
	 */
	public function doAction($actionMethod)
	{
        $doWay = $this->_provider->getDoWay();

		if (!$this->_provider->isSubmit()) {
			if (method_exists($this, '_isSubmit')) {
				$this->_provider->isSubmit($this->_isSubmit());
			} elseif ($this->submitMethod() == 'post') {
				$this->_provider->isSubmit($this->request->isPost());
			}
		}

		if ($doWay == 'common') {
			$this->doCommonAction();
		} elseif($doWay == 'api') {
            $this->doApiAction();
		}
	}

	/**
	 * 执行动作（类方法）
	 */
	public function doCommonAction()
	{
		method_exists($this, '_action') && $this->_action();
		method_exists($this, '_form') && $this->_form();
		$this->checkForm();

		if ($this->request->isAjax()) {
		    $result = null;
		    if (method_exists($this, '_ajax')) {
                $result = $this->_ajax();
            }
			$this->_provider->ajaxReturn($result);
		} elseif ($this->_provider->isSubmit() && method_exists($this, '_submit')) {
			$this->_submit();
			$this->_provider->formManager->clearToken();
		} else{
			method_exists($this, '_display') && $this->_display();
            if (!$this->_provider->hasRender()) {
                $this->_provider->renderFile();
            }
		}
	}

	/**
	 * 执行动作
	 * @param string $actionMethod
	 */
	public function doApiAction($actionMethod)
	{
		if ($actionMethod == '_action') {
			$result = $this->_action();
		} else {
			$result = $this->$actionMethod();
		}

		$this->ajax->ajaxSuccess($result);
	}

    /**
     * 设置和获取表单提交方式
     * @param string $method
     * @return string
     */
    public function submitMethod($method = null)
    {
        if (isset($method)) {
            $method = $method == 'get' ? 'get' : 'post';
            $this->_submitMethod = $method;
        }
        return $this->_submitMethod;
    }

    /**
     * 设置和获取是否表单提交
     * @param bool $isSubmit
     * @return bool
     */
    public function isSubmit($isSubmit = null)
    {
        if (isset($isSubmit)) {
            $this->_isSubmit = $isSubmit ? true : false;
        } else {
            return $this->_isSubmit;
        }
    }

    /**
     * 获取表单提交的数据
     * @param null $key
     * @param null $default
     * @return array|null|string
     */
    public function getSubmit($key = null, $default = null)
    {
        $data = $this->_submitMethod == 'post' ? $_POST : $_GET;
        $data = ocService()->request->getRequestValue($data, $key, $default);
        return $data;
    }

    /**
     * 获取表单并自动验证
     * @param null $name
     * @return $this|Form
     * @throws \Ocara\Core\Exception
     */
    public function form($name = null)
    {
        $model = null;
        if (!$name) {
            $name = ocService()->app->getRoute('controller');
            $model = $this->model();
        }

        $form = $this->formManager->get($name);
        if (!$form) {
            $form = $this->formManager->create($name);
            if ($model) {
                $form->model($model, false);
            }
            $this->event(self::EVENT_AFTER_CREATE_FORM)->fire(array($name, $form));
        }

        return $form;
    }

    /**
     * 新建表单后处理
     * @param $name
     * @param $form
     * @param Event $event
     */
    public function afterCreateForm($name, $form, Event $event = null)
    {
        $this->view->assign($name, $form);
    }

    /**
     * 开启/关闭/检测表单验证功能
     * @param null $check
     * @return bool
     */
    public function isCheckForm($check = null)
    {
        if ($check === null) {
            return $this->_checkForm;
        }
        $this->_checkForm = $check ? true : false;
    }

    /**
     * 数据模型字段验证
     * @param array $data
     * @param string|object $model
     * @param Validator|null $validator
     * @return mixed
     */
    public function validate($data, $model, Validator &$validator = null)
    {
        $validator = $validator ? : $this->validator;

        if (is_object($model)) {
            if ($model instanceof DatabaseModel) {
                $class = $model->getClass();
            } else {
                ocService()->error->show('fault_model_object');
            }
        } else {
            $class = $model;
        }

        $data = DatabaseModel::mapData($data, $class);
        $rules = DatabaseModel::getConfig('VALIDATE', null, $class);
        $lang = DatabaseModel::getConfig('LANG', null, $class);
        $result = $validator->setRules($rules)->setLang($lang)->validate($data);

        return $result;
    }

    /**
     * 表单检测
     */
    public function checkForm()
    {
        $this->isSubmit();
        if (!($this->_isSubmit && $this->_checkForm && $this->formManager->get()))
            return true;

        $tokenTag  = $this->formToken->getTokenTag();
        $postToken = $this->getSubmit($tokenTag);
        $postForm = $this->formManager->getSubmitForm($postToken);

        if ($postForm) {
            $data = $this->getSubmit();
            $this->formManager->validate($postForm, $data);
        }

        return true;
    }

    /**
     * 获取或设置Model-静态属性保存
     * @param string $class
     * @return mixed
     */
    public function model($class = null)
    {
        if (empty($class)) {
            $class = '\app\dal\models\main\\' . ucfirst(ocService()->app->getRoute('controller'));
        }

        if (isset($this->_models[$class])) {
            $model = $this->_models[$class];
            if (is_object($model) && $model instanceof ModelBase) {
                return $model;
            }
        }

        $this->_models[$class] = new $class();
        return $this->_models[$class];
    }

	/**
	 * 获取不存在的属性时
	 * @param string $key
	 * @return array|null
	 */
	public function &__get($key)
	{
        if ($this->hasProperty($key)) {
            $value = &$this->getProperty($key);
            return $value;
        }

		if ($instance = $this->_provider->getService($key)) {
			return $instance;
		}

        ocService()->error->show('no_property', array($key));
	}

	/**
	 * 调用未定义的方法时
	 * @param string $name
	 * @param array $params
	 * @return mixed
	 * @throws Exception\Exception
	 */
	public function __call($name, $params)
	{
        if (isset($this->_traits[$name])) {
            return call_user_func_array($this->_traits[$name], $params);
        }

        if (is_object($this->_provider)) {
            return call_user_func_array(array(&$this->_provider, $name), $params);
        }

        ocService()->error->show('no_method', array($name));
	}
}
