<?php
/*
 * eHR Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.areschina.com/license/LICENSE.txt.
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@areschina.com so we can send you a copy immediately.
 *
 * @category   eHR
 * @package    Calendar
 * @subpackage Calendar
 * @copyright  (C)1980 - 2008 ARES INERNATIONAL CORPORATION (http://www.areschina.com)
 * @license    http://www.areschina.com/license/LICENSE.txt.
 * @version    $Id:CaledarJSLoader.class.php Jan 11, 2008 2:53:41 PM Dennis $
 */
 
 /**
 *
 * @category   eHR
 * @package    Calendar
 * @subpackage Calendar
 * @example 
 *   $cal = new Calendar('./library/jscalendar/','zh_CN');
 *	 $cal->outputCalendarJS();
 *   echo $cal->makeInput(array('name'=>'begin_date','size'=>'10','readonly'=>'true'),array('eventName'=>'click'));
 *   echo $cal->makeInput(array('name'=>'end_date','size'=>'10','button'=>'true'),array('ifFormat'=>'%Y-%m-%d','showsTime'=>'true'));
 * @copyright  (C)1980 - 2008  ARES INERNATIONAL CORPORATION (http://www.areschina.com)
 * @version    1.0
 * @license    http://www.areschina.com/license/LICENSE.txt
 * @author     Dennis dennis.lan(at)gmail.com 
 */
class Calendar extends Base
{
    /**
     * source code 显示新的一行
     * @var string
     */
    const NEWLINE = '';
    
    private $_cal_lib_path;
    private $_cal_js_file;
    private $_cal_lang_file;
    private $_cal_setup_file;
    private $_cal_theme_file;
    private $_cal_options;
    /**
     * 初始化 JS 相关属性
     *
     * @param string $cal_lib_path JS日历控件所在目录
     * @param string $lang      JS 日历控件语言, default <i>en</i>
     * @param string $theme     JS 日历控件的皮肤
     * @param boolean $stripped default <i>true</i> 是否使用压缩过的 js
     * @access public
     * @author Dennis
     */
    public function __construct($cal_lib_path,
                                $lang     = 'en',
                                $theme    = 'Aqua',
                                $stripped = true)
    {
        //if (!is_dir($cal_lib_path)) trigger_error('JS Calendar Library Path Can not be null,Must be a Directory.',E_USER_ERROR);
        // default include the stripped whitespace js, for improve the perofrmance
        $stripped = $stripped ? '_stripped' : '';
        $this->_cal_js_file    = 'calendar'.$stripped.'.js';
        $this->_cal_setup_file = 'calendar-setup'.$stripped.'.js';
        $this->_cal_lang_file  = 'calendar-'.$lang.'.js';        
        $this->_cal_theme_file = ($theme == 'Aqua') ? 
        						 'skins/'.strtolower($theme).'/theme.css' :
                                 'theme/'.$theme.'.css';
        
        $this->_cal_lib_path   = preg_replace('/\/+$/', '/', $cal_lib_path);
        $this->_cal_options    = array('ifFormat'=>'%Y/%m/%d','daFormat'=>'%Y/%m/%d');
    }// end class Constructor
    
    /**
     * 设定 JS 日历控件属性
     *
     * @param string $name   属性名称
     * @param string $value  属性值
     * @return void
     * @access public
     * @author Dennis
     */
    public function setOption($name,$value)
    {
        $this->_cal_options["$name"] = $value;
    }// end setOption()
    
    /**
     * 输出 js/css html code, 直接 call <b>getLoadFilesCode</b>
     * @see getLoadFilesCode()
     * @param no
     * @return no
     * @access public
     * @author Dennis
     */
    public function outputCalendarJS()
    {
        echo $this->getLoadFilesCode();
    }// end outpubCalendarJS()
    
    /**
     * 取得Load日历控件所有 js/css html code
     * <link rel="stylesheet" type="text/css" href="xxx.css"/>
     * <script type="text/javascript" src="xxx.js"></script>
     * @param no
     * @return string
     * @access public
     * @author Dennis
     */
    public function getLoadFilesCode()
    {
    	global $jsloaded;
    	$code = '';
    	if (!$jsloaded)
    	{
	        $code  = '<link rel="stylesheet" type="text/css" media="all" href="';
	        $code .= $this->_cal_lib_path.$this->_cal_theme_file;
	        $code .= '" />'.self::NEWLINE;
	        $script_begin_tag = '<script type="text/javascript" src="';
	        $script_end_tag   = '"></script>'.self::NEWLINE;
	        $code .= $script_begin_tag.$this->_cal_lib_path.$this->_cal_js_file.$script_end_tag;
	        $code .= $script_begin_tag.$this->_cal_lib_path.'lang/'.$this->_cal_lang_file.$script_end_tag;
	        $code .= $script_begin_tag.$this->_cal_lib_path.$this->_cal_setup_file.$script_end_tag;
	        $jsloaded = true;
    	}        
        return $code;        
    }// end getLoadFilesCode()
    
    /**
     * Generate Calenar setup script
     *
     * @param array $other_options Calendar properties array
     * @return string JavasScript Calendar Code
     * @access public
     * @author Dennis
     */
    public function makeCalendar(array $other_options = array())
    {
        $js_options = $this->_makeJsHash(array_merge($this->_cal_options,$other_options));
        $code  = '<script type="text/javascript">Calendar.setup({'.$js_options.'});</script>';
        return $code;
    }// end makeCalendar()
    
    /**
     * 产生 input calendar html code
     *
     * @param array $cal_options
     * @param array $field_attrs
     * @return string
     * @access public
     * @author Dennis
     */
    public function makeInput(array $field_attrs = array(),array $cal_options = array())
    {
        $id = isset($field_attrs['id']) ? $field_attrs['id'] : $this->_genID();
        $attrstr = $this->_makeHtmlAttribute(array_merge($field_attrs,array('id'=>$id,'type'=>'text')));
        //$code  = "<input {$attrstr} style=\"background: url(".$this->_cal_lib_path."img.gif) no-repeat 100% 50%;\" />";
        $code = sprintf('<input %s %s />',$attrstr,(!isset($field_attrs['button'])? 
        											'style="background: url('.$this->_cal_lib_path.'img.gif) no-repeat 100% 50%;"' :
                                                    ''));
        $inputConfig = array();
        $inputConfig['inputField']= $id;
        if (isset($field_attrs['button']))
        {
            $inputConfig['button'] = $this->_triggerID($id);
            $code .= '<a href="#" id="'.$this->_triggerID($id).'">';
            $code .= '<img valign="middle" border="0" src="'.$this->_cal_lib_path.'img.gif" alt="Choose Date"/></a>';
        }// end if
        $options = array_merge($cal_options,$inputConfig);
        $code .= $this->makeCalendar($options);
        unset($inputConfig);
        return $code;
    }// end makeInput();
    
    /**
     * Make a Javascript Hash Array Code
     *
     * @param array $array  
     * @return string Javascript code string
     * @access private
     * @author Dennis
     */
    private function _makeJsHash(array $array)
    {
        $jstr = '';
        reset($array);
        while(list($key,$value)=each($array))
        {
            if (is_bool($value))
            {
                $value = $value ? 'true' : 'false';
            }
            else if (!is_numeric($value))
            {
                $value = '"'.$value.'"';
            }// end if
            if ($jstr) $jstr .= ',';
            $jstr .= '"' . $key . '":' . $value;
        }// end while loop
        return $jstr;
    }// end _makeJsHash()
    
    /**
     * Trigger Button id(f-cal-trigger+InputField Unique ID)
     *
     * @param string $id
     * @return string
     */
    private function _triggerID($id)
    {
        return 'f-cal-trigger'.$id;
    }// end _triggerID()
    
    
    /**
     * Generate a unique field id
     * @param  string $id unique id prefix words
     * @return string the unique field id(GUID)
     * @access private
     * @author Dennis
     */
    private function _genID($id = 'cal')
    {
        return getGUID($id);
    }// end _genID
    
    /**
     * re-combine inputField properties array
     *
     * @param array $attrs input Field properties array
     * @return array inputField properties array
     * @access private
     * @author Dennis
     */
    private function _makeHtmlAttribute(array $attrs)
    {
        $attrstr = '';
        reset($attrs);
        while(list($key,$value) = each($attrs))
        {
            $attrstr .= $key .'="'.$value.'" ';
        }// end while
        return $attrstr;
    }// end _makeHtmlAttribute()
}// end class CalendarJSLoader
?>