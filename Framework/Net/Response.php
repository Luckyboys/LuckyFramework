<?php

namespace Framework\Net
{
	use Framework\Error\ErrorList;
	/**
	 * 网络回应类
	 * @author Luckyboys
	 */
	class Response
	{
		/**
		 * 重定向页面
		 * @param	string	$url	URL地址
		 */
		public static function redirect( $url )
		{
			header( "Location: {$url}\r\n" );
		}
		
		/**
		 * 打印JSON输出
		 * @param	array	$datas	数据
		 */
		public static function echoJSON( $datas )
		{
			echo json_encode( array( 'datas' => $datas , 'status' => true ) );
		}
		
		/**
		 * 打印错误的JSON
		 */
		public static function echoErrorJSON( ErrorList $errorList )
		{
			echo json_encode( array( 'errorList' => $errorList->getErrors() , 'status' => false ) );
		}
	}
}
?>