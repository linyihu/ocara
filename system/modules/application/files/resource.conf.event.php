<?php
/*
 * ----------------------------------------------------------------------------
 * 开发级配置-智能事件
 * ----------------------------------------------------------------------------
 */

/*
 * 错误输出事件
 */
$CONF['EVENT']['error'] = array(
	'output'      => '', // 输出错误日志
	'write_log'   => '', // 记录错误日志
);

/*
 * 权限检测事件
 */
$CONF['EVENT']['auth'] = array(
	'check' => '', //权限检测事件
	'check_error' => '', //无权限错误事件
);

/*
 * 表单使用事件
 */
$CONF['EVENT']['form'] = array(
	'check_error'    => '', //表单检测失败时的事件
	'generate_token' => '', //表单令牌加密算法的事件
);

/*
 * 数据库相关事件
 */
$CONF['EVENT']['database'] = array(
	'before_execute_sql' => '', //执行SQL语句前的事件，适合于写SQL语句日志
	'after_execute_sql' => '', //执行SQL语句完成后的事件，适合于写SQL语句结果日志
);

/*
 * 数据模型Model相关事件
 */
$CONF['EVENT']['model'] = array(
	//Model查询缓存的事件
	'query' => array(
		'save_cache_data' => '', //保存为缓存数据的事件
		'get_cache_data' => '',  //获取缓存数据的事件
	)
);

/*
 * die()或exit()函数事件
 */
$CONF['EVENT']['oc_die'] = '';