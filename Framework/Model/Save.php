<?php

namespace Framework\Model
{
	interface Save
	{
		/**
		 * 保存数据
		 */
		public function save();
		
		/**
		 * 获取缓存主键
		 * @return	string
		 */
		public function getCacheKey();
	}
}
?>