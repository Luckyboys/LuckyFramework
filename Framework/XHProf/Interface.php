<?php

namespace Framework\XHProf
{
	/**
	 * XHProf客户端接口
	 * @author Lucky
	 */
	interface iXHProf
	{
		/**
		 * 开启 XHProf
		 */
		public function startXHProf();
		
		/**
		 * 关闭 XHProf
		 */
		public function endXHProf();
	}
}
?>