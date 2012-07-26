<?php

namespace Framework\Controller
{
	/**
	 * 所有Api控制器的基类
	 * @author	Luckyboys
	 * @since	2010.11.02
	 */
	abstract class ApiAbstract extends Base
	{
		/**
		 * 当前API调用的用户ID
		 * @var	int
		 */
		protected $userId = 0;
		
		/**
		 * 设置API调用者的用户ID
		 * @param	int $userId	用户ID
		 */
		public function setUser( $userId )
		{
			$this->userId = $userId;
		}
	}
}
?>