<?php

namespace Framework\Error
{
	use Framework\Core\Common;
	class ErrorList implements \Iterator , \Countable , \ArrayAccess
	{
		/**
		 * 错误列表
		 * @var	array()
		 */
		private $_errorList;
		
		/**
		 * 位置坐标
		 * @var	int
		 */
		private $_position;
		
		/**
		 * 实例化
		 */
		public function __construct()
		{
			$this->clear();
		}
		
		/**
		 * 添加错误
		 * @param	int	$errorCode	错误码
		 */
		public function addError( $errorCode )
		{
			$this->_errorList[] = $errorCode;
		}
		
		/**
		 * 清空错误列表
		 */
		public function clear()
		{
			$this->_errorList = array();
			$this->_position = 0;
		}
		
		/**
		 * 是否有错误
		 * @return	boolean
		 */
		public function hasError()
		{
			return count( $this->_errorList ) > 0;
		}
		
		/* (non-PHPdoc)
		 * @see Iterator::current()
		 */
		public function current()
		{
			return $this->_errorList[$this->_position];
		}
	
		/* (non-PHPdoc)
		 * @see Iterator::next()
		 */
		public function next()
		{
			$this->_position++;
		}
	
		/* (non-PHPdoc)
		 * @see Iterator::key()
		 */
		public function key()
		{
			return $this->_position;
		}
	
		/* (non-PHPdoc)
		 * @see Iterator::valid()
		 */
		public function valid()
		{
			return isset( $this->_errorList[$this->_position] );
		}
	
		/* (non-PHPdoc)
		 * @see Iterator::rewind()
		 */
		public function rewind()
		{
			$this->_position = 0;
		}
	
		/* (non-PHPdoc)
		 * @see ArrayAccess::offsetExists()
		 */
		public function offsetExists( $offset )
		{
			return isset( $this->_errorList[$offset] );
		}
	
		/* (non-PHPdoc)
		 * @see ArrayAccess::offsetGet()
		 */
		public function offsetGet( $offset )
		{
			return isset( $this->_errorList[$offset] ) ? $this->_errorList[$offset] : null;
		}
	
		/* (non-PHPdoc)
		 * @see ArrayAccess::offsetSet()
		 */
		public function offsetSet( $offset , $value )
		{
			if( is_null( $offset ) )
			{
				$this->_position[] = $value;
			}
			else
			{
				$this->_position[$offset] = $value;
			}
		}
	
		/* (non-PHPdoc)
		 * @see ArrayAccess::offsetUnset()
		 */
		public function offsetUnset( $offset )
		{
			if( isset( $this->_errorList[$offset] ) )
			{
				unset( $this->_errorList[$offset] );
			}
		}
	
		/* (non-PHPdoc)
		 * @see Countable::count()
		 */
		public function count()
		{
			return count( $this->_errorList );
		}
		
		/**
		 * 获取错误列表
		 * @return	array
		 */
		public function getErrors()
		{
			$errorMessages = Common::getConfig( 'ErrorCode' );
			$errors = array();
			foreach( $this->_errorList as $errorCode )
			{
				$errors[] = strlen( $errorMessages->getMessage( $errorCode ) ) == 0 ? "Error Code: {$errorCode}" : $errorMessages->getMessage( $errorCode );
			}
			return $errors;
		}
	}
}
?>