<?php
/*
 * ----------------------------------------------------------------------------
 * 开发级配置-基本配置
 * ----------------------------------------------------------------------------
 */

/*
 * 框架配置
 */
$CONF['DEFAULT_CONTROLLER'] = 'home'; //默认控制器
$CONF['DEFAULT_ACTION'] = 'index'; //默认动作

/*
 * 系统模式配置
 * 分为开发模式和运营模式两种 
 * 配置为develop时是开发模式，其他均为运营模式 
 */
$CONF['SYS_MODEL'] = 'develop';

/*
 * 开发者中心标志配置
 */
$CONF['DEV_SIGN'] = 'dev';

/*
 * session配置
 */
$CONF['SESSION'] = array(
	'save_type'	 => 1, //session保存方式，1为自定义文件类型，2为数据库类型，3为缓存类型
	'save_time'	 => 0, //session的最长存活时间（单位为秒）
	'server'     => 'default', //服务器名称，数据库服务器名称或Redis服务器名
	'location'	 => 'cache/sessions', //保存位置，文件目录名、数据库表名或缓存前缀
);

/*
 * cookie配置
 */
$CONF['COOKIE'] = array(
	'path'		=>  '/', //有效路径
	'domain' 	=> '', //有效域名
	'secure' 	=> 0, //是否使用Https来传输cookie
	'httponly' 	=> 1, //是否禁止Javascript使用该cookie
);

/*
 * 安全配置
 */
$CONF['SQL_FILTER_KEYWORDS'] = 0; //是否过滤SQL关键词

/*
 * 日志配置
 */
$CONF['LOG_PATH'] = 'logs'; //日志保存路径，会放在resource/data目录下面

/*
 * AJAX配置
 */
$CONF['AJAX'] = array(
	'return_header_error_code' => 0, //AJAX是否在HTTP头部返回HTTP错误码
);

/*
 * 表单配置
 */
$CONF['FORM'] = array(
	'token_tag' 		  => 'temp_ocform_token', //表单令牌隐藏域名称
	'check_repeat_submit' => 1, // 表单重复提交检查
);

/*
 * 模板配置
 */
$CONF['TEMPLATE'] = array(
	'file_type'		 => 'php', //模板文件名
	'engine'		 => '', //模板引擎（如果使用默认的Smarty模板引擎，填Ocara\Service\Smarty）
	'default'		 => 'default', //默认模板名称
	'default_layout' => 'layout', //默认的layout名称
);

/*
 * Smarty模板配置
 * 可自由选择是否使用smarty模板
 */
$CONF['SMARTY'] = array(
	'use_cache'	 => 0, //是否使用缓存
	'left_sign'	 => '<{', //模板中左标记
	'right_sign' => '}>', //模板中右标记
);

/*
 * 分页配置
 */
$CONF['PAGE'] = array(
	'class_name' => '', //分页类名称，默认是Ocara\Service\Pager
	'page_param' => 'page', //URL查询字符串中分页参数名称
	'per_page'	 => 10, //每页显示多少条记录
	'per_show'   => 10, //一次显示多少页
);

/*
 * 验证码在$SESSION中的保存名称
 */
$CONF['VERIFYCODE_NAME'] = 'OCSESSCODE';

/*
 * 默认文档类型
 */
$CONF['DEFAULT_CONTENT_TYPE'] = 'html'; //默认页面文档类型
$CONF['DEFAULT_AJAX_CONTENT_TYPE'] = 'json'; //默认Ajax返回文档类型

/*
 * 默认字体（用于图片处理等）
 */
$CONF['DEFAULT_FONT'] = 'simhei';

/*
 * 时间格式设置
 */
$OC_CONF['DATE_FORMAT'] = array(
	'timezone' 	=> 'PRC', //时区设置
	'date'     	=> 'Y-m-d', //日期格式
	'datetime' 	=> 'Y-m-d H:i:s', //日期时间格式
	'time' 	  	=> 'H:i:s', //时间格式
);

/*
 * 自定义错误处理
 */
$CONF['ERROR_HANDLER'] = array(
	'program_error' => '', //自定义程序错误处理方式
	'exception_error' => '', //自定义异常错误处理方式

	/*
	 * 要取消显示的错误列表
	 * 比如E_WARNING,E_USER_WARNING等
	 */
	'except_error_list' => array(),
);