<?php
/**************************************************************************\
 *   Best view set tab 4
 *   Created by Dennis.lan (C) Lan Jiangtao 
 *	 
 *	Description:
 *     public functions
 * 
 *  $HeadURL: http://192.168.0.126:9001/svn/EHR/trunk/eHR/libs/functions.php $
 *  $Id: functions.php 2959 2010-10-14 06:49:18Z dennis $
 *  $Rev: 2959 $ 
 *  $Date: 2010-10-14 14:49:18 +0800 (星期四, 14 十月 2010) $
 *  $Author: dennis $   
 *  $LastChangedDate: 2010-10-14 14:49:18 +0800 (星期四, 14 十月 2010) $
 *************************************************************************/

/**
 *   Security Check when php script execute
 *   @param  $check_value validate the value
 *   @param  $url the url when error occur redirect
 *   @return void, no return value
 */
function security_check($check_value, $url) {
	if (is_null ( $check_value ) || empty ( $check_value ) || ! isset ( $check_value )) {
		header ( 'Location: ' . $url );
		exit ();
	} // end if
} // end security_check()


/**
 *   For debug only,print array element
 *   @param $array array
 *   @return no return value, for debug only
 */
function pr($array) {
	if (is_array ( $array )) {
		print "<pre>";
		print_r ( $array );
		print "</pre>";
	} else {
		print "<font color='red'><b> Not a array variable.</b></font><br/>";
	}
}

/**
 *   get real database column name from a string
 *   the string must be start with "input_"
 *   help function of comb_query_where()
 *   @param $columnname string ,the string start with "input_"
 *   @return string after clear "input_"
 *   @author Dennis 
 *   @last update 2005-12-02 14:44:28 
 */
function get_real_column_name($columnname) {
	return str_replace ( "input_", "", $columnname );
}

/**
 *   combination query where condition from post variables
 *   @param $data array, url variable array, sample: array["key"]="value"
 *   @return string query where condition
 *   @author dennis 
 *   @last update 2005-12-02 14:54:39 
 */
function comb_query_where($data) {
	$query_where = "";
	if (is_array ( $data )) {
		foreach ( $data as $key => $value ) {
			if (! empty ( $value )) {
				$query_where .= " and " . get_real_column_name ( $key ) . " like '%$value%' ";
			}
		}
	}
	return $query_where;
}

function binary_search($array, $element) {
	/** Returns the found $element or 0 */
	$low = 0;
	$high = count ( $array ) - 1;
	while ( $low <= $high ) {
		$mid = floor ( ($low + $high) / 2 ); // C floors for you
		if ($element == $array [$mid]) {
			return $array [$mid];
		} else {
			if ($element < $array [$mid]) {
				$high = $mid - 1;
			} else {
				$low = $mid + 1;
			}
		}
	}
	return 0; // $element not found
}

/*
*	Get Employee Find Condition
*	add by jack 2006-9-7
*/
function get_query_where_in($whereArray) {
	if (empty ( $whereArray )) {
		return "";
	} else {
		$size1 = count ( $whereArray );
		$combine_string = "";
		for($i = 0; $i < $size1; $i ++) {
			$combine_string .= "'" . $whereArray [$i] . "',";
		}
		$combine_string = substr ( $combine_string, 0, strlen ( $combine_string ) - 1 );
		return $combine_string;
	}
}

/**
 *   get user real ip
 *   但如果客户端是使用代理服务器来访问，那取到的就是代理服务器的 IP 地址，而不是真正的客户端 IP 地址。
 *   要想透过代理服务器取得客户端的真实 IP 地址，就要使用 Request.ServerVariables("HTTP_X_FORWARDED_FOR") 来读取。 
 *   @author Jack 
 *   @last update 2006-11-2
 */
/**
 * 获得用户的真实IP地址
 *
 * @access  public
 * @return  string
 */
function get_real_ip()
{
    static $realip = NULL;
    if ($realip !== NULL)
    {
        return $realip;
    }
    if (isset($_SERVER))
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            /* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */
            foreach ($arr AS $ip)
            {
                $ip = trim($ip);
                if ($ip != 'unknown')
                {
                    $realip = $ip;
                    break;
                }
            }
        }
        elseif (isset($_SERVER['HTTP_CLIENT_IP']))
        {
            $realip = $_SERVER['HTTP_CLIENT_IP'];
        }
        else
        {
            if (isset($_SERVER['REMOTE_ADDR']))
            {
                $realip = $_SERVER['REMOTE_ADDR'];
            }
            else
            {
                $realip = '0.0.0.0';
            }
        }
    }
    else
    {
        if (getenv('HTTP_X_FORWARDED_FOR'))
        {
            $realip = getenv('HTTP_X_FORWARDED_FOR');
        }
        elseif (getenv('HTTP_CLIENT_IP'))
        {
            $realip = getenv('HTTP_CLIENT_IP');
        }
        else
        {
            $realip = getenv('REMOTE_ADDR');
        }
    }
    preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
    $realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';
    return $realip;
}



/****************************************** 
this will return an array composed of a 4 item array for each language the os supports
1. full language abbreviation, like en-ca
2. primary language, like en
3. full language string, like English (Canada)
4. primary language string, like English
 *******************************************/

// choice of redirection header or just getting language data
// to call this you only need to use the $feature parameter
function get_language() {
	// add by dennis 2010-09-10 
	if (isset($GLOBALS['config']['default_language'])) return $GLOBALS['config']['default_language'];
	// get the languages
	$a_languages = languages ();
	$index = '';
	$complete = '';
	//prepare user language array
	$user_languages = array ();
	//check to see if language is set
	if (isset ( $_SERVER ["HTTP_ACCEPT_LANGUAGE"] )) {
		//explode languages into array
		$languages = strtolower ( $_SERVER ["HTTP_ACCEPT_LANGUAGE"] );
		$languages = explode ( ",", $languages );
		foreach ( $languages as $language_list ) {
			// pull out the language, place languages into array of full and primary
			// string structure: 
			$temp_array = array ();
			// slice out the part before ; on first step, the part before - on second, place into array
			$temp_array [0] = substr ( $language_list, 0, strcspn ( $language_list, ';' ) ); //full language
			$temp_array [1] = substr ( $language_list, 0, 2 ); // cut out primary language
			//place this array into main $user_languages language array
			$user_languages [] = $temp_array;
		}
		
		//start going through each one
		for($i = 0; $i < count ( $user_languages ); $i ++) {
			foreach ( $a_languages as $index => $complete ) {
				if ($index == $user_languages [$i] [0]) {
					// complete language, like english (canada) 
					$user_languages [$i] [2] = $complete;
					// extract working language, like english
					$user_languages [$i] [3] = substr ( $complete, 0, strcspn ( $complete, ' (' ) );
				} // end if
			} // end foreach
		} // end for
	} else {
	// if no languages found
		$user_languages [0] = array ('', '', '', '' ); //return blank array.
	}
	return $user_languages [0] [0];
} // end get_language()


/**
 * full standard ISO language code
 *
 * @return array
 */
function languages() {
	// pack abbreviation/language array
	// important note: you must have the default language as the last item in each major language, after all the
	// en-ca type entries, so en would be last in that case
	$a_languages = array ('af' => 'Afrikaans', 
	 'sq' => 'Albanian', 'ar-dz' => 'Arabic (Algeria)',
	 'ar-bh' => 'Arabic (Bahrain)', 'ar-eg' => 'Arabic (Egypt)', 
	 'ar-iq' => 'Arabic (Iraq)', 'ar-jo' => 'Arabic (Jordan)', 
	 'ar-kw' => 'Arabic (Kuwait)', 'ar-lb' => 'Arabic (Lebanon)', 
	 'ar-ly' => 'Arabic (libya)', 'ar-ma' => 'Arabic (Morocco)', 
	 'ar-om' => 'Arabic (Oman)', 'ar-qa' => 'Arabic (Qatar)', 
	 'ar-sa' => 'Arabic (Saudi Arabia)', 'ar-sy' => 'Arabic (Syria)', 
	 'ar-tn' => 'Arabic (Tunisia)', 'ar-ae' => 'Arabic (U.A.E.)', 
	 'ar-ye' => 'Arabic (Yemen)', 'ar' => 'Arabic', 
	 'hy' => 'Armenian', 'as' => 'Assamese', 
	 'az' => 'Azeri', 'eu' => 'Basque', 
	 'be' => 'Belarusian', 'bn' => 'Bengali', 
	 'bg' => 'Bulgarian', 'ca' => 'Catalan', 
	 'zh-cn' => 'Chinese (China)', 'zh-hk' => 'Chinese (Hong Kong SAR)',
	 'zh-mo' => 'Chinese (Macau SAR)', 'zh-sg' => 'Chinese (Singapore)',
	 'zh-tw' => 'Chinese (Taiwan)', 'zh' => 'Chinese', 
	 'hr' => 'Croatian', 'cs' => 'Czech', 
	 'da' => 'Danish', 'div' => 'Divehi',
	 'nl-be' => 'Dutch (Belgium)', 'nl' => 'Dutch (Netherlands)', 
	 'en-au' => 'English (Australia)', 'en-bz' => 'English (Belize)', 
	 'en-ca' => 'English (Canada)', 'en-ie' => 'English (Ireland)', 
	 'en-jm' => 'English (Jamaica)', 'en-nz' => 'English (New Zealand)', 
	 'en-ph' => 'English (Philippines)', 'en-za' => 'English (South Africa)',
	 'en-tt' => 'English (Trinidad)', 'en-gb' => 'English (United Kingdom)', 
	 'en-us' => 'English (United States)', 'en-zw' => 'English (Zimbabwe)', 
	 'en' => 'English', 'us' => 'English (United States)', 'et' => 'Estonian', 'fo' => 'Faeroese', 'fa' => 'Farsi', 'fi' => 'Finnish', 'fr-be' => 'French (Belgium)', 'fr-ca' => 'French (Canada)', 'fr-lu' => 'French (Luxembourg)', 'fr-mc' => 'French (Monaco)', 'fr-ch' => 'French (Switzerland)', 'fr' => 'French (France)', 'mk' => 'FYRO Macedonian', 'gd' => 'Gaelic', 'ka' => 'Georgian', 'de-at' => 'German (Austria)', 'de-li' => 'German (Liechtenstein)', 'de-lu' => 'German (Luxembourg)', 'de-ch' => 'German (Switzerland)', 'de' => 'German (Germany)', 'el' => 'Greek', 'gu' => 'Gujarati', 'he' => 'Hebrew', 'hi' => 'Hindi', 'hu' => 'Hungarian', 'is' => 'Icelandic', 'id' => 'Indonesian', 'it-ch' => 'Italian (Switzerland)', 'it' => 'Italian (Italy)', 'ja' => 'Japanese', 'kn' => 'Kannada', 'kk' => 'Kazakh', 'kok' => 'Konkani', 'ko' => 'Korean', 'kz' => 'Kyrgyz', 'lv' => 'Latvian', 'lt' => 'Lithuanian', 'ms' => 'Malay', 'ml' => 'Malayalam', 'mt' => 'Maltese', 'mr' => 'Marathi', 'mn' => 'Mongolian (Cyrillic)', 'ne' => 'Nepali (India)', 'nb-no' => 'Norwegian (Bokmal)', 'nn-no' => 'Norwegian (Nynorsk)', 'no' => 'Norwegian (Bokmal)', 'or' => 'Oriya', 'pl' => 'Polish', 'pt-br' => 'Portuguese (Brazil)', 'pt' => 'Portuguese (Portugal)', 'pa' => 'Punjabi', 'rm' => 'Rhaeto-Romanic', 'ro-md' => 'Romanian (Moldova)', 'ro' => 'Romanian', 'ru-md' => 'Russian (Moldova)', 'ru' => 'Russian', 'sa' => 'Sanskrit', 'sr' => 'Serbian', 'sk' => 'Slovak', 'ls' => 'Slovenian', 'sb' => 'Sorbian', 'es-ar' => 'Spanish (Argentina)', 'es-bo' => 'Spanish (Bolivia)', 'es-cl' => 'Spanish (Chile)', 'es-co' => 'Spanish (Colombia)', 'es-cr' => 'Spanish (Costa Rica)', 'es-do' => 'Spanish (Dominican Republic)', 'es-ec' => 'Spanish (Ecuador)', 'es-sv' => 'Spanish (El Salvador)', 'es-gt' => 'Spanish (Guatemala)', 'es-hn' => 'Spanish (Honduras)', 'es-mx' => 'Spanish (Mexico)', 'es-ni' => 'Spanish (Nicaragua)', 'es-pa' => 'Spanish (Panama)', 'es-py' => 'Spanish (Paraguay)', 'es-pe' => 'Spanish (Peru)', 'es-pr' => 'Spanish (Puerto Rico)', 'es-us' => 'Spanish (United States)', 'es-uy' => 'Spanish (Uruguay)', 'es-ve' => 'Spanish (Venezuela)', 'es' => 'Spanish (Traditional Sort)', 'sx' => 'Sutu', 'sw' => 'Swahili', 'sv-fi' => 'Swedish (Finland)', 'sv' => 'Swedish', 'syr' => 'Syriac', 'ta' => 'Tamil', 'tt' => 'Tatar', 'te' => 'Telugu', 'th' => 'Thai', 'ts' => 'Tsonga', 'tn' => 'Tswana', 'tr' => 'Turkish', 'uk' => 'Ukrainian', 'ur' => 'Urdu', 'uz' => 'Uzbek', 'vi' => 'Vietnamese', 'xh' => 'Xhosa', 'yi' => 'Yiddish', 'zu' => 'Zulu' );
	
	return $a_languages;
} // end languages()


/**
 * Check emp photo exists
 *
 * @param string $filename file name (full path)
 * @param string $filext   file extension (.jpg .png ect..)
 * @return boolean
 * @author Dennis
 */
function checkFileExists($filename, $filext) {
	$file_exts = explode ( ',', $filext );
	//pr($file_exts);
	foreach ( $file_exts as $ext ) {
		//echo $filename.$ext.'<br/>';
		if (@fopen ( $filename . $ext, 'r' ))
			return $filename . $ext;
	} // end foreach
	return null;
} // end checkFileExists()


// Data Filter Here
/**
 * 过滤数据
 *
 * @param string $data
 * @return string
 */
function dataFilter($data) {
	return htmlentities ( $data, ENT_QUOTES );
} // end dataFilter()


/**
 * 提交资料后显示相应的成功,失败,消息的页面
 *
 * @param string $msg_text
 * @param string $msg_type
 * @return void
 * @author Dennis 2008-09-12 Happy Birthday to Dennis (K)
 * 
 */
function showMsgPage($msg_text, $msg_type = 'information') {
	$allow_msg_type = array ('information', 'success', 'error', 'warning' );
	if (in_array ( $msg_type, $allow_msg_type )) {
		header ( 'Location: ?scriptname=page_' . $msg_type . '&msgtxt=' . urlencode ( $msg_text ) );
		exit ();
	} else {
		trigger_error ( 'Undefined Message Type :' . $msg_type . '<br> Only support following message type:' . var_export ( $allow_msg_type ), E_USER_ERROR );
	} // end if
} // end showMsgPage()


function showMsg($msg_text, $msg_type = 'information', $back_url = null) {
	$backurl = is_null ( $back_url ) ? 'Javascript:history.back();' : $back_url;
	$page_header = <<<eof
	<?xml version="1.0" encoding="utf-8"?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="expires" content="wed, 20 Feb 2000 08:30:00 GMT" />
	<meta http-equiv="pragma" content="no-cache" />
	<meta http-equiv="imagetoolbar" content="no" />
	<meta name="owner" content="Dennis Lan/R&D/ARES CHINA" />
	<meta name="author" content="Dennis Lan, Lan Jiangtao" />
	<meta name="Copyright" content="ARES China Inc." />
	<meta name="description" content="eHR for HCP" />
	<link rel="icon" href="../img/ares.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="../img/ares.ico" type="image/x-icon" />
	<link rel="stylesheet" href="../css/blueprint/screen.css"
		type="text/css" media="screen, projection" />
	<link rel="stylesheet" href="../css/blueprint/print.css" type="text/css"
		media="print" />
	<!--[if IE]><link rel="stylesheet" href="../css/blueprint/ie.css" type="text/css" media="screen, projection"/><![endif]-->
	<link rel="stylesheet" href="../css/default.css" type="text/css" />
	</head>
	<body class="page-container">
	<div class="span-4"></div>
	<div class="span-8">
eof;
	$msg_header = <<<eof
	<div class="sidebox resources">
		<div class="x-box-tl">
			<div class="x-box-tr">
				<div class="x-box-tc"></div>
			</div>
		</div>
		<div class="x-box-ml">
			<div class="x-box-mr">
				<div class="x-box-mc">
					<h3 style="margin:3px;"><img src=%s.png></h3>
					<hr noshade="noshade" size="1" style="color: rgb(128, 128, 128);" />
					<!--{/if}-->
					<ul>
eof;
	$msg_footer = <<<eof
					<div align="right">
						<br>
						<a href=%s><< Back </a>
					<div>
					</ul>
				</div>
			</div>
		</div>
		<div class="x-box-bl">
			<div class="x-box-br">
				<div class="x-box-bc"></div>
			</div>
		</div>
eof;
	$page_footer = <<<eof
		<div class="span-4 last">&nbsp;</div>
		<div>&nbsp;</div>
		</body>
		</html>
eof;
	echo sprintf ( $page_header . $msg_header . $msg_text . $msg_footer . $page_footer, $GLOBALS ['config'] ['img_dir'] . '/' . $msg_type, $backurl, date ( 'Y' ) );
	exit ();
} // end showMsg()


/**
 * 从多语中 Get 下拉清单
 *
 * @param string $langcode  语言代码,如 ZHS,ZHT,EN
 * @param string $listid    多语的 name
 * @author Dennis
 * @return array
 * 
 */
function getMultiLangList($langcode, $listid) {
	global $g_db_sql;
	$sql = <<<eof
		select seq as list_value, value as list_label
		  from app_muti_lang
		 where name = :list_id
		   and lang_code = :lang_code
		   and type_code = 'LL'
eof;
	$g_db_sql->SetFetchMode(ADODB_FETCH_NUM);
	return $g_db_sql->GetArray ( $sql, array ('list_id' => $listid, 'lang_code' => $langcode ) );
} // end getMultiLangList()


/**
 * Get Error Message
 *
 * @param string $programno
 * @param string $langcode
 * @param string $msgkey
 */
function getMultiLangMsg($programno, $langcode, $msgkey) {
	global $g_db_sql;
	$sql = <<<eof
		select value as msg
		  from app_muti_lang
		 where program_no = :program_no
		   and lang_code  = :lang_code
		   and name       = :msg_key
eof;
	//$g_db_sql->debug = true;
	return $g_db_sql->GetOne ( $sql, array ('program_no' => $programno, 'lang_code' => $langcode, 'msg_key' => $msgkey ) );
} // end getMultiLangMsg()


 /*
  * array 返回 option 的html
  */
function gf_getDropDownListHtml($list,$select_value){
		$html='';
		foreach ($list as $key=>$row){
			$selected=($row['ID']==$select_value)?'selected':'';
		    $html.="<option value='".$row['ID']."'  ".$selected." > ".$row['TEXT']." </option> \r\n";
		}
		return $html;
}

/*  結束時間  */
function gf_getEndTime($p_date,$p_begin_time,$p_end_time){
   // 计算加班起始结束日期时间
	$_date = explode ( '-', $p_date );
	$_btime = explode ( ':', $p_begin_time );
	$_etime = explode ( ':',$p_end_time);
	$_begin_date = mktime ( $_btime [0], $_btime [1], 0, $_date [1], $_date [2], $_date [0] );
	$_end_date = mktime ( $_etime [0], $_etime [1], 0, $_date [1], $_date [2], $_date [0] );
	$begin_time = date ( 'Y-m-d H:i', $_begin_date );
	$end_time = date ( 'Y-m-d H:i', $_end_date );
	
	// 如果加班结束时间小于开始时间, 表示其跨天
	if ($_end_date < $_begin_date) {
		$end_time = date ('Y-m-d H:i', mktime ( $_etime [0], $_etime [1], 0, $_date [1], $_date [2] + 1, $_date [0] ) );
	}
	return $end_time;
}

// 取菜单标题多语
function gf_getMenuTitle($langcode='',$menu_code='') {
	global $g_db_sql;
	if(empty($menu_code)) return '';
	$sql="select aml.value
	      from  APP_MUTI_LANG aml
	      where aml.PROGRAM_NO = 'HCP'
             and aml.type_code='MT'
             and aml.lang_code='".$langcode."'
             and aml.name='".$menu_code."'
		";
	//echo $sql;
	return $g_db_sql->GetOne($sql);
} // end getMultiLangList()

 function recombineArray($data)
{
    $result = false;
    if (is_array($data))
    {
        $cnt = count($data);
        for ($i=0; $i<$cnt; $i++)
        {
            $result[strtoupper($data[$i][0])] = $data[$i][1];
        }// end loop
    }// end if
    return $result;
}// end _recombineArray()
/**
 * 取得提示信息(error message)多语资料
 *
 * @param string $langcode    语言代码
 * @param string $programno   程式代码
 * @return array
 * @author Dennis 
 */
function get_multi_lang($langcode,$programno)
{
	global $g_db_sql;
	$sql = <<<eof
		select name,value
		 from  app_muti_lang
		where  program_no  = :program_no
		  and  lang_code   = :langcode
		  and  type_code   = 'MP'
eof;
	$g_db_sql->SetFetchMode(ADODB_FETCH_NUM);
	$rs = $g_db_sql->GetArray($sql,array('program_no'=>$programno,
										 'langcode'=>$langcode));
	return recombineArray($rs);
}

// 找多语值
function get_app_muti_lang($program_no,$name,$lang_code,$type_code='IT'){
	global $g_db_sql;
	$sql = "select VALUE  
		 from 
			app_muti_lang
	   where program_no='".$program_no."'
		 and name='".$name."'
		 and type_code='".$type_code."'
		 and lang_code='".$lang_code."'
		 ";
	$value = $g_db_sql->GetOne($sql);
	return $value;
		
}

/**
 *  cols is define in table return true
 */
function is_defined_column($table_name,$col_name){
	global $g_db_sql;
	$sql="
		select 1 from all_tab_columns 
		where table_name='".$table_name."'  
		and column_name='".$col_name."'
		";
	return $g_db_sql->GetOne($sql);
}

/**
 * 是否是设定程式
 *
 * @param string $scripname
 * @return boolean
 * @author Dennis 20090608
 */
function is_app_defined($scripname)
{
	global $g_db_sql;
	$sql = 'select 1 from ehr_program_setup_master where program_no = :program_no';
	//$g_db_sql->debug = true;
	return $g_db_sql->GetOne($sql,array('program_no'=>$scripname));
}


/**
 *  Try PHP header redirect, then Java redirect, then try http redirect.:
 * @param $url
 * @return void
 */
function redirect($url){
    if (!headers_sent()){    //If headers not sent yet... then do php redirect
        header('Location: '.$url); exit;
    }else{  //If headers are sent... do java redirect... if java disabled, do html redirect.
        echo '<script type="text/javascript">';
        echo 'window.location.href="'.$url.'";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
        echo '</noscript>'; exit;
    }
}// end redirect()

/**
 * Turn on HTTPS - Detect if HTTPS, if not on, then turn on HTTPS:
 * @return void
 */
function SSLon(){
    if($_SERVER['HTTPS'] != 'on'){
        $url = "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        redirect($url);
    }
}//end SSLon()

/**
 * Turn Off HTTPS -- Detect if HTTPS, if so, then turn off HTTPS:
 * @return unknown_type
 */
function SSLoff(){
    if($_SERVER['HTTPS'] == 'on'){
        $url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        redirect($url);
    }
}// end SSLoff()

/**
 * Company Logo
 * @author Dennis 2010-09-07
 */
function getLogoUrl()
{
	// default logo
    $company_logo = $GLOBALS['config']['img_dir'].'/logo.gif';
    $support_ext = array('png','gif','jpg','jpeg','PNG','GIF','JPG','JPEG');
    $cust_logo   = $GLOBALS['config']['upload_dir'].'/userfile/'.'com_logo';
    foreach ($support_ext as $v) {
	    $f = $cust_logo.'.'.$v;
	    if (file_exists($f)) return $f;
    }
    return $company_logo;
}
/*
function encrypt($string, $key) {
	$result = '';
	for($i=0; $i<strlen($string); $i++) {
		$char = substr($string, $i, 1);
		$keychar = substr($key, ($i % strlen($key))-1, 1);
		$char = chr(ord($char)+ord($keychar));
		$result.=$char;
	}
	return base64_encode($result);
}

function decrypt($string, $key) {
	$result = '';
	$string = base64_decode($string);
	for($i=0; $i<strlen($string); $i++){
		$char = substr($string, $i, 1);
		$keychar = substr($key, ($i % strlen($key))-1, 1);
		$char = chr(ord($char)-ord($keychar));
		$result.=$char;
	}
	return $result;
}*/