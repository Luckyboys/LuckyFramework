<?php

namespace Framework\Net
{
	class Url
	{
		/**
		 * 创建调用URL
		 * @param	string	$method	方法名
		 * @param	array	$parameters	参数
		 * @return	string
		 */
		public static function createUrl( $method , $parameters = array() )
		{
			return "?method={$method}" . ( count( $parameters ) > 0 ? ( '&' . http_build_query( $parameters ) ) : '' );
		}
	
		/**
		 * 创建CDN资源URL地址
		 * @param	string	$filePath	基于CDNLayer目录起始的相对地址
		 * @return	string
		 */
		public static function createCDNURL( $filePath )
		{
			return self::getCurrentSiteURL() .'CDNLayer/'. $filePath;
		}
	
		/**
		 * 获取当前站点地址
		 * @return	string
		 */
		public static function getCurrentSiteURL()
		{
			return self::_getProtocol() . "://" . self::_getHost() .'/';
		}
		
		/**
		 * 获取HTTP连接协议
		 * @return	string
		 */
		private static function _getProtocol()
		{
			return ( isset( $_SERVER['HTTPS'] ) && ( strtolower( $_SERVER['HTTPS'] ) != 'off' ) ) ? "https" : "http";
		}
		
		/**
		 * 获取域名或者IP地址
		 * @return	string
		 */
		private static function _getHost()
		{
			if( isset( $_SERVER['HTTP_HOST'] ) )
			{
				return $_SERVER['HTTP_HOST'];
			}
			else if( isset( $_SERVER['SERVER_NAME'] ) )
			{
				return $_SERVER['SERVER_NAME'] . self::_getPort();
			}
			else if( isset( $_SERVER['SERVER_ADDR'] ) )
			{
				return $_SERVER['SERVER_ADDR'] . self::_getPort();
			}
			else
			{
				return 'localhost';
			}
		}
		
		/**
		 * 获取端口
		 * @return	string
		 */
		private static function _getPort()
		{
			if( isset( $_SERVER['SERVER_PORT'] ) )
			{
				$port = ':' . $_SERVER['SERVER_PORT'];
				if( ( ':80' == $port && 'http' == self::_getProtocol() ) || ( ':443' == $port && 'https' == self::_getProtocol() ) )
				{
					$port = '';
				}
			}
			else
			{
				$port = '';
			}
			return $port;
		}
	}
}