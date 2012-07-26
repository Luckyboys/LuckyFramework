<?php
namespace Framework\Net
{
	class SendData
	{

		/**
		 * 主机名
		 * @var	string
		 */
		private $_host = '';

		/**
		 * 端口
		 * @var	int
		 */
		private $_port = 0;

		/**
		 * 查询数据
		 * @var	string
		 */
		private $_query = '';

		/**
		 * 路径
		 * @var	string
		 */
		private $_path = '';

		/**
		 * POST数据
		 * @var	array(
		 * 			{$key: string}: string
		 * 		)
		 */
		private $_postData = array();

		/**
		 * 实例化
		 * @param	string	$url	URL地址
		 * @param	array	$postData	post数据
		 * 								array(
		 * 									{$key: string}: string
		 * 								)
		 */
		private function __construct( $url , $postData = array() )
		{
			$addressFamily = parse_url( $url );
			$this->_host = $addressFamily['host'];
			$this->_port = isset( $addressFamily['port'] ) ? $addressFamily['port'] : 80;
			$this->_query = $addressFamily['query'];
			$this->_path = $addressFamily['path'];
			$this->_postData = $postData;
		}

		/**
		 * 获取实例
		 * @param	string	$url	URL地址
		 * @param	array	$postData	post数据
		 * 								array(
		 * 									{$key: string}: string
		 * 								)
		 */
		public static function getInstance( $url , $postData = array() )
		{
			return new self( $url , $postData );
		}

		/**
		 * 发送请求
		 * @return	boolean
		 */
		public function request()
		{
			$method = $this->_getMethod();
			
			$fp = fsockopen( $this->_host , $this->_port , $errorCode , $errorMessage , 5 );
			if( !$fp )
			{
				return false;
			}

			$header = "{$method} {$this->_path}?{$this->_query} HTTP/1.1\r\n";
			$header .= "Host: {$this->_host}\r\n";
			$header .= "Connection: Close\r\n";
			
			if( $this->_postData )
			{
				$postString = http_build_query( $this->_postData );
				$header .= "Content-Type: application/x-www-form-urlencoded\r\n"; //POST数据
				$header .= "Content-Length: " . strlen( $postString ) . "\r\n\r\n"; //POST数据的长度
				$header .= $postString; //传递POST数据
			}
			$header .= "\r\n";
			
			fwrite( $fp , $header );
			
			fclose( $fp );
			return true;
		}

		/**
		 * 获取方法
		 * @return	string
		 */
		private function _getMethod()
		{
			if( $this->_postData )
			{
				return "POST";
			}
			return "GET";
		}
	}
}
?>