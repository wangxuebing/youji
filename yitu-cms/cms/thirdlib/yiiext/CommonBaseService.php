<?php
/**
 * @file CommonBaseService.php
 * @author tangqingmao
 * @date 2013/11/28
 * service基类
 * 提供缓存等通用功能
 *
 */
class CommonBaseService {
    /**
     * kv client object
     * @var memcache
     */
    protected $cache = null;
    /**
     * 一级cache超时时间
     * @var int 单位s，默认5分钟
     */
    protected $expire1 = 600;
    /**
     * 二级cache超时时间
     * @var int 单位s，默认一天
     */
    protected $expire2 = 86400;
    /**
     * 二级cache是否使用，默认不使用
     * @var bool 默认false
     */
    protected $secondaryFlag = false;
    /**
     * 是否使用cache，在开发环境或者调试阶段可以关闭cache
     * @var bool 默认true
     */
    protected $cacheFlag = true;
    /**
     * 允许service设置拼接cacheKey的前缀，便于更新cache
     */
    protected $cacheKeyPrefix = '';

    /**
     * 设置BaseService的参数
     *
     * @param $serverConf，cache的后端服务器配置，格式如下：
     * array(
     *     array("ip"=>"127.0.0.1","port"=>"11211"),
     *     array("ip"=>"127.0.0.1","port"=>"11212"),
     * )
     *
     */
    public function __construct( $serverConf=array() ) {
		if ( count($serverConf) <= 0 ) {
            throw new Exception("Cache server info error!");
		}
        try {
            $this->cache = new Memcache();
			foreach ($serverConf as $server) {
				$this->cache->addServer($server['ip'], $server['port']);
			}
        } catch ( Exception $e ) {
            throw new Exception("Init cache failed!");
        }
    }

    /**
     * 设置缓存的超时时间
     * @param $expire1 一级cache超时时间，单位s
     * @param $expire2 二级cache超时时间，单位s
     */
    public function setExpire( $expire1, $expire2=86400 ) {
        $this->expire1 = $expire1;
        $this->expire2 = $expire2;
    }

    /**
     * 设置开启二级缓存
     */
    public function useSecondaryCache() {
        $this->secondaryFlag = true;
    }

    /**
     * 关闭cache功能
     */
    public function closeCache() {
        $this->cacheFlag = false;
    }

    /**
     * 通用方法，从缓存中获取数据
     * 首先从一级缓存中取数据，没有从后端服务取数据。如果从后端获取失败同时启用了二级缓存，会从二级缓存取数据，并更新一级缓存
     * 上述过程取数据都失败，返回null，并会打印错误日志
     * 
     * @param string $cacheKey
     * @param string $method
     * @param array $params
     * @return $data, null表示获取数据失败
     */
    public function getDataFromCache($cacheKey, $method, $params = array()) {
        $data = null;
        $cacheKey1 = $this->cacheKeyPrefix . 'cache1_' . $cacheKey;
        $cacheKey2 = $this->cacheKeyPrefix . 'cache2_' . $cacheKey;

        //从一级cache中读取数据
//        TimerUtil::start( $method.'FromCache1' );
        try {
            $data = $this->cache->get( $cacheKey1 ); //取不到返回false
        }
        catch( Exception $e ){
            Yii::log( "Get {$cacheKey1} from cache1 error! " . $e->getMessage() , CLogger::LEVEL_WARNING, __METHOD__ );
            $data = null;
        }
//        TimerUtil::stop( $method.'FromCache1' );
        if( $data !== null && $data !== false){
            return $data;
        }
        //cache中没有数据，从后端获取
//        TimerUtil::start( $method.'FromServer' );
        try {
            $data = call_user_func_array ( array( $this, $method ), $params );
        }
        catch( Exception $e ) {
            Yii::log( "Get data from server exception! " . $e->getMessage () . "\n" . $e->getTraceAsString (), CLogger::LEVEL_ERROR, __METHOD__ );
            $data = null;
        }
//        TimerUtil::stop( $method.'FromServer' );

        if( $data !== null ) {
//            TimerUtil::start( $method.'SetCache' );
            try {
                $expire1 = $this->expire1;
                $res = $this->cache->set( $cacheKey1, $data, MEMCACHE_COMPRESSED, $expire1 );
                if( $res != 0 ) {
                    Yii::log( "Store {$cacheKey1} to cache1 error, code is " . $res, CLogger::LEVEL_INFO, __METHOD__ );
                }
                else {
                    Yii::log( "Store {$cacheKey1} to cache1", CLogger::LEVEL_INFO, __METHOD__ );
                }
                if( $this->secondaryFlag ) {
                    $expire2 = $this->expire2;
                    $res = $this->cache->set( $cacheKey2, $data, MEMCACHE_COMPRESSED, $expire2 );
                    if( $res != 0 ) {
                        Yii::log( "Store {$cacheKey2} to cache2 error, code is " . $res, CLogger::LEVEL_INFO, __METHOD__ );
                    }
                    else {
                        Yii::log( "Store {$cacheKey2} to cache2", CLogger::LEVEL_INFO, __METHOD__ );
                    }
                }
            }
            catch( Exception $e ) {
                Yii::log( "Store to cache error: " . $e->getMessage(), CLogger::LEVEL_WARNING, __METHOD__ );
            }
//            TimerUtil::stop( $method.'SetCache' );
            return $data;
        }
        //一级cache中没有，从后端获取失败，如果启用二级cache，从二级cache中取
        if( $this->secondaryFlag ) {
//            TimerUtil::start( $method.'FromCache2' );
            try {
                $data = $this->cache->get( $cacheKey2 );
                Yii::log( "Get {$cacheKey2} from cache2!", CLogger::LEVEL_WARNING, __METHOD__ );
            }
            catch( Exception $e ) {
                Yii::log( "Get {$cacheKey2} from cache2 error! " . $e->getMessage() , CLogger::LEVEL_WARNING, __METHOD__ );
                $data = null;
            }
//            TimerUtil::stop( $method.'FromCache2' );
            if( $data!==null ) {
//                TimerUtil::start( $method.'SetCache2FromCache1' );
                try {
                    $expire1 = $this->expire1;
                    $res = $this->cache->set( $cacheKey1, $data, MEMCACHE_COMPRESSED, $expire1 );
                    if( $res != 0 ) {
                        Yii::log( "Store to cache1 from cache2 error, code is " . $res, CLogger::LEVEL_INFO, __METHOD__ );
                    }
                    else {
                        Yii::log( "Store to cache1 from cache2!", CLogger::LEVEL_INFO, __METHOD__ );
                    }
                }
                catch( Exception $e ) {
                    Yii::log( "Store to cache error! " . $e->getMessage(), CLogger::LEVEL_WARNING, __METHOD__ );
                }
//                TimerUtil::stop( $method.'SetCache2FromCache1' );
                return $data;
            }
        }
        //二级cache也没有取到数据，抛异常
        throw new Exception("Get data from cache and server is error!");
    }
    
    /**
     * 通用方法，关闭缓存功能时，直接从后端服务获取数据
     * 
     * @param string $method
     * @param array $params
     * @return $data, null表示获取数据失败
     */
    public function getDataFromServer( $method, $params = array() ) {
//        TimerUtil::start( $method.'FromServer' );
		try {
        	$data = call_user_func_array ( array( $this, $method ), $params );
		}
		catch(Exception $e) {
        	throw new Exception("Get data from server is error! " . $e->getMessage());
		}
//		TimerUtil::stop( $method.'FromServer' );
        return $data;
    }

    public function __call($name, $args) {
        $pos = strrpos( $name, "FromCache" );
        if($pos === false || $pos != strlen ( $name ) - 9) {
            throw new Exception ( "undefined method:$name" );
        }
        $callback = substr( $name, 0, $pos );
        $method = $this->getClassFile( get_class( $this ) ) . "::$callback";
        $key = $method . "_" . serialize( $args );

        if( $this->cacheFlag ) {
            $data = $this->getDataFromCache( $key, $callback, $args );
        }
        else {
            $data = $this->getDataFromServer( $callback, $args );
        }
        return $data;
    }

    /**
     * 获取调用的service类所在的文件名，用来拼装cache的key
     * 防止多个项目中有相同的service类中定义相同的方法，造成key重复
     * 
     * @param string $class 类名
     * @return string, 该类所在的文件完整路径
     */
    protected function getClassFile( $class ) {
        $fileName = $class . '.php';
        $files = get_included_files();
        foreach( $files as $file ) {
            if( substr_count( $file, $fileName ) > 0 ) {
                return $file;
            }
        }
        return $fileName;
    }
}
