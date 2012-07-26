<?php

namespace Framework\Data\Cache
{
	/**
	 * SAE KV引擎
	 * @author Luckyboys
	 */
	class SaeKV
	{
		/**
		 * 10年时间的秒数
		 * @var	int
		 */
		const TEN_YEAR_SECONDS = 315360000;
		
		/**
		 * SAE KV客户端
		 * @var	SaeKVClient
		 */
		private $_cache;
		
		/**
		 * 实例化
		 * @param	array	$config
		 */
		public function __construct( $config )
		{
			$this->_cache = new \SaeKVClient();
			$this->_initServer();
		}
		
		/**
		 * 初始化客户端
		 */
		private function _initServer()
		{
			$this->_cache->init();
		}
		
		/**
		 * 获取值
		 * @param	array|string $key	键名
		 * @return	string
		 */
		public function get( $key )
		{
			if( is_array( $key ) )
			{
				$result = $this->_cache->mget( $key );
				if( !is_array( $result ) )
				{
					return $result;
				}
				foreach( $result as $key => $value )
				{
					$result[$key] = $value = $this->_getValue( $value , $key );
					if( $value === false )
					{
						unset( $result[$key] );
					}
				}
				if( empty( $result ) )
				{
					return false;
				}
				return $result;
			}
			
			return $this->_getValue( $this->_cache->get( $key ) , $key );
		}
		
		/**
		 * 获取值
		 * @param	string	$value	值
		 * @param	string	$byKey	值所属的键
		 * @return	mixed|boolean
		 */
		private function _getValue( $value , $byKey )
		{
			if( $value === false )
			{
				return false;
			}
			
			$data = json_decode( $value , true );
			if( $data['expireTime'] < time() )
			{
				$this->delete( $byKey );
				return false;
			}
			
			return $data['value'];
		}
		
		/**
		 * 制作满足存储的值
		 * @param	mixed	$value	值
		 * @param	int	$timeout	超时时间
		 * @return	string
		 */
		private function _makeValue( $value , $timeout )
		{
			if( $timeout <= 0 )
			{
				$expireTime = time() + self::TEN_YEAR_SECONDS;
			}
			else 
			{
				$expireTime = time() + $timeout;
			}
			return json_encode( array( 'value' => $value , 'expireTime' => $expireTime ) );
		}
		
		/**
		 * 设置值
		 * @param	string	$key	键名
		 * @param	mixed	$value	值
		 * @param	int		$timeout	超时时间
		 * @return	boolean
		 */
		public function set( $key , $value , $timeout = 0 )
		{
			return $this->_cache->set( $key , $this->_makeValue( $value , $timeout ) );
		}
	    
	    /**
	     * 删除键值
		 * @param	string	$key	键名
	     * @return	boolean
	     */
	    public function delete( $key )
	    {
	        return $this->_cache->delete( $key );
	    }
	    
	    /**
	     * 获取错误状态
	     * @return	int
	     */
	    public function getErrorCode()
	    {
	    	return $this->_cache->errno();
	    }
	    
	    /**
	     * 获取错误信息
	     * @return	string
	     */
	    public function getErrorMessage()
	    {
	    	return $this->_cache->errmsg();
	    }
	    
	    /**
	     * 添加值
		 * @param	string	$key	键名
		 * @param	mixed	$value	值
		 * @param	int		$timeout	超时时间
		 * @return	boolean
	     */
	    public function add( $key , $value , $timeout = 0 )
	    {
	    	if( $this->get( $key ) !== false )
	    	{
	    		return false;
	    	}
	    	
	    	return $this->set( $key , $value , $timeout );
	    }
	}
}
?>