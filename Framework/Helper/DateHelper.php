<?php

namespace Framework\Helper
{
	class DateHelper
	{
		/**
		 * 英文语言
		 */
		const LANG_EN = 0;
		
		/**
		 * 繁体中文语言
		 */
		const LANG_TC = 1;
		
		/**
		 * 简体中文语言
		 */
		const LANG_SC = 2;
		
		/**
		 * 日文语言
		 */
		const LANG_JP = 3;
		
		/**
		 * 韩文语言
		 */
		const LANG_KR = 4;
		
		/**
		 * 一天的秒数
		 * @var	int
		 */
		const ONE_DAY_SECONDS = 86400;
		
		/**
		 * 时间单位（天）
		 * @var	int
		 */
		const OFFSET_UNIT_DAY = 1;
		
		/**
		 * 时间单位（周）
		 * @var	int
		 */
		const OFFSET_UNIT_WEEK = 2;
		
		/**
		 * 时间单位（月）
		 * @var	int
		 */
		const OFFSET_UNIT_MONTH = 3;
		
		/**
		 * 时间单位（年）
		 * @var	int
		 */
		const OFFSET_UNIT_YEAR = 4;
		
		/**
		 * 语言词典
		 * @var	array(
		 * 			{language}:array(
		 * 				asSoon:string
		 * 				second:string
		 * 				minute:string
		 * 				hour:string
		 * 				day:string
		 * 				month:string
		 * 				year:string
		 * 				multiSecond:string
		 * 				multiMinute:string
		 * 				multiHour:string
		 * 				multiDay:string
		 * 				multiMonth:string
		 * 				multiYear:string
		 * 				ago:string
		 * 				after:string
		 * 			)
		 * 		)
		 */
		private static $_langs = array(
			self::LANG_EN => array(
				"asSoon" => "As Soon" ,
				"second" => "Second" ,
				"minute" => "Minute" ,
				"hour" => "Hour" ,
				"day" => "Day" ,
				"week" => "Week" ,
				"month" => "Month" ,
				"year" => "Year" ,
				"multiSecond" => "Seconds" ,
				"multiMinute" => "Minutes" ,
				"multiHour" => "Hours" ,
				"multiDay" => "Days" ,
				"multiWeek" => "Weeks" ,
				"multiMonth" => "Months" ,
				"multiYear" => "Years" ,
				"ago" => " ago" ,
				"after" => " after" ,
			) ,
			self::LANG_TC => array(
				"asSoon" => "剛剛" ,
				"second" => "秒" ,
				"minute" => "分鐘" ,
				"hour" => "小時" ,
				"day" => "天" ,
				"week" => "週" ,
				"month" => "個月" ,
				"year" => "年" ,
				"multiSecond" => "秒" ,
				"multiMinute" => "分鐘" ,
				"multiHour" => "小時" ,
				"multiDay" => "天" ,
				"multiWeek" => "週" ,
				"multiMonth" => "個月" ,
				"multiYear" => "年" ,
				"ago" => "前" ,
				"after" => "後" ,
			) ,
			self::LANG_SC => array(
				"asSoon" => "刚刚" ,
				"second" => "秒" ,
				"minute" => "分钟" ,
				"hour" => "小时" ,
				"day" => "天" ,
				"week" => "周" ,
				"month" => "个月" ,
				"year" => "年" ,
				"multiSecond" => "秒" ,
				"multiMinute" => "分钟" ,
				"multiHour" => "小时" ,
				"multiDay" => "天" ,
				"multiWeek" => "周" ,
				"multiMonth" => "个月" ,
				"multiYear" => "年" ,
				"ago" => "前" ,
				"after" => "后" ,
			) ,
			self::LANG_JP => array(
				"asSoon" => "ついさっき" ,
				"second" => "秒" ,
				"minute" => "分" ,
				"hour" => "時間" ,
				"day" => "日" ,
				"week" => "週" ,
				"month" => "ヶ月" ,
				"year" => "年" ,
				"multiSecond" => "秒" ,
				"multiMinute" => "分" ,
				"multiHour" => "時間" ,
				"multiDay" => "日" ,
				"multiWeek" => "週" ,
				"multiMonth" => "ヶ月" ,
				"multiYear" => "年" ,
				"ago" => "前" ,
				"after" => "後う" ,
			) ,
			self::LANG_KR => array(
				"asSoon" => "방금" ,
				"second" => "초" ,
				"minute" => "분" ,
				"hour" => "시간" ,
				"day" => "일" ,
				"week" => "주" ,
				"month" => "개 월" ,
				"year" => "년" ,
				"multiSecond" => "초" ,
				"multiMinute" => "분" ,
				"multiHour" => "시간" ,
				"multiDay" => "일" ,
				"multiWeek" => "주" ,
				"multiMonth" => "개 월" ,
				"multiYear" => "년" ,
				"ago" => "앞" ,
				"after" => "뒤" ,
			) ,
		);
		
		/**
		 * 单位升价词典
		 * @var	array
		 */
		private static $_unitDictionary = array(
			array(
				"unitKey" => 'second' ,
				"unitsKey" => 'multiSecond' ,
				"upPoint" => 60 ,
			) ,
			array(
				"unitKey" => 'minute' ,
				"unitsKey" => 'multiMinute' ,
				"upPoint" => 60 ,
			) ,
			array(
				"unitKey" => 'hour' ,
				"unitsKey" => 'multiHour' ,
				"upPoint" => 24 ,
			) ,
			array(
				"unitKey" => 'day' ,
				"unitsKey" => 'multiDay' ,
				"upPoint" => 30 ,
			) ,
			array(
				"unitKey" => 'month' ,
				"unitsKey" => 'multiMonth' ,
				"upPoint" => 12 ,
			) ,
			array(
				"unit" => 'year' ,
				"units" => 'multiYear' ,
				"upPoint" => 1000 ,
			) ,
		);
		
		/**
		 * 格式化时间与时间之间的差距
		 * @param	int $time1	时间1
		 * @param	int $time2	时间2（默认当前时间）
		 * @param	boolean	$isAccuracy	是否精确显示相差时间
		 * @return	string
		 */
		public static function formatTimeDistance( $time1 , $time2 = null , $isAccuracy = true , $lang = self::LANG_SC )
		{
			//判断时间2是否为空
			if( $time2 === null )
			{
				//为空：获取当前时间
				$time2 = time();
			}
			//不为空：继续
			
			//计算时间2和时间1之间的秒数差距
			$time = abs( $time2 - $time1 );
			
			//判断差距时间是否少于5秒
			if( $time <= 5 )
			{
				//少于等于5秒：返回刚刚
				return self::$_langs[$lang]["asSoon"];
			}
			//大于5秒：继续
			
			//循环计算计算
			$returnValue = "";
			$unitPos = 0;
			while( $time > 0 && $unitPos < 6 )
			{
				$howUnit = $time % self::$_unitDictionary[$unitPos]["upPoint"];
				
				//如果当前数量大于0
				if( $howUnit > 0 )
				{
					//加入返回值
					$howUnitWord = $howUnit .
						( ( $howUnit > 1 ) ?
							self::$_langs[$lang][self::$_unitDictionary[$unitPos]["unitKey"]] :
							self::$_langs[$lang][self::$_unitDictionary[$unitPos]["unitsKey"]]
						);
					if( $isAccuracy )
					{
						$returnValue = $howUnitWord . $returnValue;
					}
					else 
					{
						$returnValue = $howUnitWord;
					}
				}
				
				$time = intval( $time / self::$_unitDictionary[$unitPos]["upPoint"] );
				$unitPos++;
			}
			
			$returnValue .= ( $time2 > $time1 ? self::$_langs[$lang]["ago"] : self::$_langs[$lang]["after"] );
			
			return $returnValue;
		}
		
		/**
		 * 计算给予时间的当天起始时间和结束时间
		 * @param	int $time	UNIX时间戳
		 * @return	array(
		 * 				startTime:int	//起始时间
		 * 				endTime:int		//结束时间
		 * 			)
		 */
		public static function computeTodayTime( $time )
		{
			//获取当前时区与格林威治时间相差多少秒
			$secondOfTimeZone = date( "Z" );
			
			//先把指定时间加上了时区相差的描述
			$time += $secondOfTimeZone;
			
			//计算起始时间和结束时间，并返回
			return array(
				"startTime" => ( $time - ( $time % self::ONE_DAY_SECONDS ) - $secondOfTimeZone ) ,
				"endTime" => ( $time - ( $time % self::ONE_DAY_SECONDS ) - $secondOfTimeZone ) + self::ONE_DAY_SECONDS - 1 ,
			);
		}
		
		/**
		 * 计算给予时间的当天起始时间和结束时间
		 * @param	int $time	UNIX时间戳
		 * @return	array(
		 * 				startTime:int	//起始时间
		 * 				endTime:int		//结束时间
		 * 			)
		 */
		public static function computeYestodayTime( $time )
		{
			$todayTime = self::computeTodayTime( $time );
			return array(
				"startTime" => $todayTime['startTime'] - self::ONE_DAY_SECONDS ,
				"endTime" => $todayTime['endTime'] - self::ONE_DAY_SECONDS ,
			);
		}
		
		/**
		 * 计算偏移的时间
		 * @param	int	$offset	偏移量
		 * @param	int	$unit	时间单位
		 */
		public static function computeOffsetTime( $offset , $unit , $time )
		{
			if( $offset == 0 )
			{
				return $time;
			}
			$offsetWord = ( $offset > 0 ) ? "+{$offset}" : "{$offset}";
			switch( $unit )
			{
				case self::OFFSET_UNIT_DAY:
					
					return strtotime( $offsetWord .' day' , $time );
					
				case self::OFFSET_UNIT_WEEK:
					
					return strtotime( $offsetWord .' week' , $time );
					
				case self::OFFSET_UNIT_MONTH:
					
					return strtotime( $offsetWord .' month' , $time );
					
				case self::OFFSET_UNIT_YEAR:
					
					return strtotime( $offsetWord .' year' , $time );
			}
			
			return $time;
		}
		
		/**
		 * 获取便宜时间单位的名称
		 * @param	int	$point	时间数量
		 * @param	int	$unit	时间单位
		 * @param	int	$lang	语言
		 */
		public static function getOffsetUnitName( $point , $unit , $lang = self::LANG_SC )
		{
			switch( $unit )
			{
				case self::OFFSET_UNIT_DAY:
					
					return self::$_langs[$lang][( $point > 1 ) ? 'multiDay' : 'day'];
					
				case self::OFFSET_UNIT_WEEK:
					
					return self::$_langs[$lang][( $point > 1 ) ? 'multiDay' : 'week'];
					
				case self::OFFSET_UNIT_MONTH:
					
					return self::$_langs[$lang][( $point > 1 ) ? 'multiMonth' : 'month'];
					
				case self::OFFSET_UNIT_YEAR:
					
					return self::$_langs[$lang][( $point > 1 ) ? 'multiYear' : 'year'];
			}
		}
	}
}

?>