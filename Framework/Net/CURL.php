<?php

namespace Framework\Net
{
	use Framework\Log\Error;
	
	/**
	 * Rest客户端
	 */
	class CURL
	{
		/**
		 * URL地址
		 * @var	string
		 */
		protected $url;
		
		/**
		 * 链接协议
		 * @var	int
		 */
		protected $protocolType;
		
		/**
		 * 超时时间（单位：秒）
		 * @var	int
		 */
		protected $timeout;
		
		/**
		 * 链接协议（Web）
		 * @var	int
		 */
		const PROTOCAL_TYPE_WEB = 1;
		
		/**
		 * 链接协议（TCP）
		 * @var	int
		 */
		const PROTOCAL_TYPE_TCP = 2;
		
		/**
		 * 实例化
		 * @param	string	$serverAddr	URL地址
		 * @param	int	$protocalType	链接协议
		 * @param	int	$timeout	链接超时
		 */
		public function __construct( $timeout = 10 )
		{
			$this->timeout = $timeout;
		}
		
		/**
		 * 更改URL地址
		 * @param	string	$url	URL地址
		 * @param	int	$protocalType	链接协议
		 */
		public function setURL( $url , $protocalType = self::PROTOCAL_TYPE_WEB )
		{
			$this->url = $url;
			$this->protocolType = $protocalType;
		}
		
		/**
		 * 调用
		 * @param	array	$postData	POST数据
		 * @return	string
		 * @throws	Exception
		 */
		public function call( $postData )
		{
			$data = $this->postRequest( $postData );
			
			if( empty( $data ) )
			{
				Error::getInstance( 'Net_CURL' )->addLog( "Net_CURL: Empty Return, url: ". $this->url , "postData: ". $this->_createPostString( $postData ) );
			}
			
			return $data;
		}
		
		/**
		 * 抛数据
		 * @param	array	$postData	POST数据
		 * @return	string
		 */
		protected function postRequest( $postData )
		{
			$ch = curl_init();
			curl_setopt( $ch , CURLOPT_POSTFIELDS , $this->_createPostString( $postData ) );
			curl_setopt( $ch , CURLOPT_RETURNTRANSFER , true );
			curl_setopt( $ch , CURLOPT_CONNECTTIMEOUT , $this->timeout );
			curl_setopt( $ch , CURLOPT_TIMEOUT , $this->timeout );
			switch( $this->protocolType )
			{
				case self::PROTOCAL_TYPE_WEB:
					curl_setopt( $ch , CURLOPT_USERAGENT, $this->_getUserAgent() );
					curl_setopt( $ch , CURLOPT_URL , $this->url );
					if( strpos( $this->url , "https" ) !== false )
					{
						curl_setopt( $ch , CURLOPT_SSL_VERIFYPEER , false );
					}
						
					break;
				case self::PROTOCAL_TYPE_TCP:
					
					list( $ip , $port ) = explode( ":" , $this->url );
					curl_setopt( $ch , CURLOPT_URL , $ip );
					curl_setopt( $ch , CURLOPT_PORT , $port );
					break;
			}
			
			$result = curl_exec( $ch );
			if( curl_errno( $ch ) > 0 )
			{
				$result = curl_error( $ch );
			}
			curl_close( $ch );
			return $result;
		}
		
		/**
		 * 获取User-Agent字符串
		 * @return	string
		 */
		private function _getUserAgent()
		{
			return 'REST API PHP5 Client 1.0 (curl) ' . phpversion();
		}
		
		/**
		 * 创建Post数据字符串
		 * @param	string	$postData	Post数据
		 * @return	string
		 */
		private function _createPostString( $postData ) 
		{
			switch( $this->protocolType )
			{
				case self::PROTOCAL_TYPE_WEB:
					return http_build_query( $postData );
					
				case self::PROTOCAL_TYPE_TCP:
					return json_encode( $postData );
			}
		}
	}
}
?>