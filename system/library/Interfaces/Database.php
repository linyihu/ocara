<?php
/*************************************************************************************************
 * -----------------------------------------------------------------------------------------------
 * Ocara开源框架 数据库类接口Database
 * @Copyright (c) http://www.ocara.cn and http://www.ocaraframework.com All rights reserved.
 * -----------------------------------------------------------------------------------------------
 * @author Lin YiHu <linyhtianwa@163.com>
 ************************************************************************************************/

namespace Ocara\Interfaces;

defined('OC_PATH') or exit('Forbidden!');

/**
 * 数据库对象接口
 * @author Administrator
 */
interface Database
{
    /**
     * 获取PDO参数
     * @param array $config
     */
    public function getPdoParams($config);

    /**
     * 获取以表字段名为键值的数组
     * @param string $table
     */
    public function getFieldsInfo($table);

    /**
     * 设置数据库编码
     * @param $charset
     */
    public function setCharset($charset);

    /**
     * 选择数据库
     * @param string $name
     * @return mixed
     */
    public function baseSelectDatabase($name = null);

    /**
     * 加密字符串
     * @param $content
     * @return mixed
     */
    public function escapeString($content);

    /**
     * 单个字段值格式化为适合类型
     * @param $fieldsData
     * @param $field
     * @param $value
     * @return mixed
     */
    public function formatOneFieldValue($fieldsData, $field, $value);
}