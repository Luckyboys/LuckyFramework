<?php

namespace Framework\Helper
{
	/**
	 * 验证助手
	 * @author Luckyboys
	 */
	class ValidateHelper
	{
		public static function isEmail( $email )
		{
			if( preg_match( '#^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$#' , $email ) > 0 )
			{
				return true;
			}
			return false;
		}
	}
}
?>