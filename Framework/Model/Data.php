<?php

namespace Framework\Model
{
	use Framework\Core\Common;
	use Framework\Exception\Base as Exception_Base;
	
	/** 
	 * 普通数据访问基层
	 * @author Lucky
	 */
	abstract class Data implements Save
	{
		/**
		 * 存储在缓存中的键名
		 * @var	string
		 */
		private $_cacheIndexKey;
		
		/**
		 * 缓存对象
		 * @var	iCache
		 */
		private $_cacheEngine;
		
		/**
		 * 缓存写锁对象
		 * @var	iCache
		 */
		private $_cacheLockEngine;
		
		/**
		 * 缓存主键名
		 * @var	string
		 */
		private $_databaseConfigKey;
		
		/**
		 * 模块数据
		 * @var	array
		 */
		protected $data;
		
		/**
		 * 模块是否加锁
		 * @var	boolean
		 */
		protected $_lock = false;
		
		/**
		 * 数据是否已经修改（是否有脏数据）
		 * @var	array(
		 * 			{$tableName:string}:array(
		 * 				{$action:int}:\Framework\Model\DataOperation
		 * 			)
		 * 		)
		 */
		private $_dirtyData = array();
		
		/**
		 * 数据需要读取的字段名（如果是多条记录，需要加入id字段）
		 * @var	array(
		 * 			{tableName}:array(			//数据表名称
		 * 				querySQL:string			//搜索数据SQL
		 * 				isNeedFindAll:boolean	//是否需要搜索多条
		 * 			)
		 * 		)
		 */
		protected $dbColumns = array();
		
		/**
		 * 是否后置删除缓存
		 * @var	boolean
		 */
		private $_isPostProcessingDeleteCache = false;
		
		/**
		 * 索引数据（搜索条件）
		 * @var array(
		 * 			{key}:{value}
		 * 		)
		 */
		protected $indexData = array();
		
		/**
		 * 所用的memcache
		 * @var string
		 */
		protected $dataCache = 'data';
		
		/**
		 * 当一个数据对象只代表一条记录时，可以使用这个数据键来更新数据库
		 * @var	string
		 */
		const ONLY_ONE_RECORD_KEY = 'oneRecord';
		
		/**
		 * 是否使用Memcache
		 * @var	boolean
		 */
		protected $isUseMemcache = true;
		
		/**
		 * 初始化模块
		 * @param	string	$dataKey	数据库配置的键名
		 * @param	boolean	$lock		是否需要加锁
		 * @param	boolean	$isNotReadData		是否不需要读取数据（高风险选项，只适合用户注册时使用）
		 * @param	boolean	$isMock		是否模拟对象
		 */
		protected function __construct( $databaseConfigKey , $lock = false , $isNotReadData = false , $isMock = false )
		{
			$this->_databaseConfigKey = $databaseConfigKey;
			
			if( $isMock )
			{
				return;
			}
			
			if( $this->isUseMemcache )
			{
				$this->_cacheIndexKey = $this->makeCacheIndexKey();
				if( empty( $this->_cacheIndexKey ) )
				{
					throw new Exception_Base( Exception_Base::STATUS_CACHE_INDEX_KEY_ERROR );
				}
				
				$this->_cacheEngine = Common::getCache( $this->dataCache );
				$this->_cacheLockEngine = Common::getCache( 'lock' );
			}
			
			if( $lock )
			{
				$this->_lock();
			}
			
			$this->_init( $isNotReadData );
		}
		
		/**
		 * 析构
		 */
		public function __destruct()
		{
			if( $this->_lock )
			{
				$this->_unlock();
			}
		}
		
		/**
		 * 初始化所有数据
		 * @param	boolean $isNotReadData		是否不需要读取数据（高风险选项，只适合用户注册时使用）
		 */
		private function _init( $isNotReadData = false )
		{
			if( !$isNotReadData && $this->isUseMemcache )
			{
				$this->_loadFromCache();
			}
			
			if( !is_array( $this->data ) )
			{
				$this->_loadFromDb( $isNotReadData );
				$this->afterLoadDb();
				if( $this->isUseMemcache && !$isNotReadData )
				{
					$this->_saveToCache();
				}
			}
		}
		
		/**
		 * 从缓存加载数据
		 */
		private function _loadFromCache()
		{
			$this->data = $this->_cacheEngine->get( $this->_cacheIndexKey );
		}
		
		/**
		 * 将数据保存到缓存
		 */
		private function _saveToCache()
		{
			if( is_array( $this->data ) )
			{
				$this->_cacheEngine->set( $this->_cacheIndexKey , $this->data );
			}
		}
		
		/**
		 * 从数据库中获取数据
		 * @param	boolean $isNotReadData		是否不需要读取数据（高风险选项，只适合用户注册时使用）
		 */
		private function _loadFromDb( $isNotReadData = false )
		{
			$this->data = array();
			
			if( empty( $this->_databaseConfigKey ) )
			{
				return;
			}
			
			if( !$isNotReadData )
			{
				$db = & Common::getDB( $this->_databaseConfigKey );
			}
			
			foreach( $this->dbColumns as $tableName => $tableConfig )
			{
				$data = array();
				if( !$isNotReadData )
				{
					if( $tableConfig['isNeedFindAll'] )
					{
						$data = $db->getDatas( $tableConfig['querySQL'] );
					}
					else 
					{
						$data = $db->getOneRecord( $tableConfig['querySQL'] );
					}
				}
				
				if( !$data )
				{
					$data = $this->emptyDataWhenloadFromDB( $tableName );
				}
				
				$data = $this->formatFromDBData( $tableName , $data );
				$this->data = array_merge( $this->data , $data );
			}
		}
		
		/**
		 * 将数据保存到数据库中
		 */
		private function _saveToDb()
		{
			$db = null;
			$postData = array();
			foreach( $this->_dirtyData as $table => $keys )
			{
				foreach( $keys as $key => $dataOperation )
				{
					$sql = $this->formatToSQL( $table , $dataOperation->getAction() , $key , $dataOperation->getData() );
					
					if( $sql )
					{
						$postData[$this->_databaseConfigKey][] = $sql;
						
						/* if( !$db )
						{
							$db = Common::getDB( $this->_databaseConfigKey );
						}
						$db->execute( $sql );
						if( $db->getErrorNumber() > 0 )
						{
							;
						} */
					}
				}
			}
			
			if( $postData )
			{
				\Framework\TaskQueue\Creator::getTaskQueue( 'Database' )->addTask(
					\Framework\Net\Url::getCurrentSiteURL() .'bAckGRounD.php?method=database.execute' , array(
						'data' => json_encode(
							array(
								'sqls' => $postData ,
							)
						)
					)
				);
				\Framework\TaskQueue\Creator::getTaskQueue( 'Database' )->commit();
			}
			$this->_dirtyData = array();
		}
		
		/**
		 * 更新数据到数据库
		 * @param	string	$table	表名
		 * @param	string|int	$key	键
		 * @param	int	$action	数据动作
		 */
		protected function updateToDb( $table , $action , $data , $key = self::ONLY_ONE_RECORD_KEY )
		{
			if( $this->_isNeedMergeDataAction( $table , $key ) )
			{
				$this->_dirtyData[$table][$key]->mergeOperation( $action , $data );
			}
			else
			{
				$this->_dirtyData[$table][$key] = new DataOperation( $action , $data );
			}
		}
		
		/**
		 * 判断是否需要合并数据操作
		 * @param	string	$table	表名
		 * @param	string|int	$key	键
		 */
		private function _isNeedMergeDataAction( $table , $key )
		{
			return isset( $this->_dirtyData[$table] )
				&& isset( $this->_dirtyData[$table][$key] );
		}
		
		/**
		 * 保存数据
		 */
		public function save()
		{
			if( $this->_lock )
			{
				$this->_saveToDb();
				
				if( !$this->isUseMemcache )
				{
					$this->_unlock();
					return ;
				}
				
				if( !$this->_isPostProcessingDeleteCache )
				{
					$this->_saveToCache();
					$this->_unlock();
				}
				else 
				{
					$this->deleteCache();
				}
			}
		}
	
		/**
		 * 加锁
		 */
		private function _lock()
		{
			if( $this->isUseMemcache && !$this->_cacheLockEngine->add( $this->_cacheIndexKey . '_lock' , 1 , 5 ) )
			{
				throw new Exception_Base( Exception_Base::STATUS_LOCK_MEMCACHE_ERROR );
			}
			
			$this->_lock = true;
		}
		
		/**
		 * 解锁
		 */
		private function _unlock()
		{
			if( $this->isUseMemcache )
			{
				$this->_cacheLockEngine->delete( $this->_cacheIndexKey . '_lock' );
			}
			$this->_lock = false;
		}
		
		/**
		 * 获取是否已经加锁
		 * @return	boolean
		 */
		public function isLocked()
		{
			return $this->_lock;
		}
		
		/**
		 * 删除缓存数据
		 * @return	boolean
		 */
		public function deleteCache( $isPostProcessing = false )
		{
			if( !$isPostProcessing )
			{
				$result = false;
				if( $this->_lock )
				{
					$result = $this->_cacheEngine->delete( $this->_cacheIndexKey );
					if( $result )
					{
						$this->_unlock();
					}
				}
				return $result;
			}
	
			else 
			{
				$this->_isPostProcessingDeleteCache = true;
				return true;
			}
		}
		
		/**
		 * 切换缓存Key
		 * @param	int $userId	用户ID
		 * @param	string $cacheKey	[optional]缓存主键名
		 * @return	boolean
		 */
		protected function changeCacheKey( $newCacheIndexKey )
		{
			$result = false;
			if( $this->_lock && is_array( $this->data ) )
			{
				if( !$this->_cacheLockEngine->add( $newCacheIndexKey . '_lock' , 1 , 5 ) )
				{
					throw new Exception_Base( Exception_Base::STATUS_LOCK_MEMCACHE_ERROR );
				}
				
				$result = $this->_cacheEngine->set( $newCacheIndexKey , $this->data );
				if( $result )
				{
					$result = $this->deleteCache();
				}
				
				if( $result )
				{
					$this->_lock = true;
					$this->_cacheIndexKey = $newCacheIndexKey;
				}
			}
	
			return $result;
		}
		
		/**
		 * 在读取完数据库之后的一些操作
		 */
		protected function afterLoadDb()
		{
			;
		}
		
		/**
		 * 获取数据信息
		 * @return	array
		 */
		public function getData()
		{
			return $this->data;
		}
		
		/**
		 * 获取缓存主键
		 * @return	string
		 */
		public function getCacheKey()
		{
			return $this->_cacheIndexKey;
		}
		
		/**
		 * 获取唯一索引键
		 */
		public function getIndexKey()
		{
			return $this->makeCacheIndexKey();
		}
		
		/**
		 * 格式化保存到数据库的数据
		 * @param	array $table	表名
		 * @param	array $action	操作动作
		 * @param	array $key		数据键
		 * @return	array
		 */
		abstract protected function formatToSQL( $table , $action , $key　, $data );
		
		/**
		 * 格式化从数据库读取出来的数据
		 * @param	array $table	表名
		 * @param	array $data		数据
		 * @return	string
		 */
		abstract protected function formatFromDBData( $table , $data );
		
		/**
		 * 当获取数据是发现返回是空数据
		 * @param	array $table	表名
		 * @return	array
		 */
		abstract protected function emptyDataWhenloadFromDB( $table );
		
		/**
		 * 计算索引缓存键值
		 * @return	string
		 */
		abstract protected function makeCacheIndexKey();
	}
}
?>