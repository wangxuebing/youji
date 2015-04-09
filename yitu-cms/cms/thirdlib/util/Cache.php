<?php
/*
 * 操作缓存的类
 * TODO 支持持久化存储, 通过db表来模拟kv的持久化存储
 */

class Cache {
    /**
     * kv client object
     * @var memcache
     */
    protected $cache = null;

    /** 
     * 设置cache参数
     *
     * @param $serverConf，cache的后端服务器配置，格式如下：
     * array(
     *     array("ip"=>"10.100.8.47","port"=>"8887"),
     *     array("ip"=>"10.100.8.46","port"=>9587),
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
        }   
        catch( Exception $e ) { 
            throw new Exception("Init cache failed!");
        }   
    }

    /** 
     * 设置缓存
     * @param $key
     * @param $val 字符串
     * @param $expireTime
     * @param $persistence 是否支持持久化存储, true时会将数据存储到db中一份, 避免cache down掉
     * @return true表示成功, false表示失败
     */
    public function set($key, $val, $expireTime=0, $persistence=false) {
        $res = $this->cache->set($key, $val, MEMCACHE_COMPRESSED, $expireTime);
        return $res;
    }

    /** 
     * 读取缓存数据
     * @param $key
     * @param $persistence 是否支持持久化存储, true时cache如果取不到或者down, 会在从db表中读一次
     * @return string表示数据, false表示失败
     */
    public function get($key, $persistence=false) {
        $res = $this->cache->get($key);
        return $res;
    }

    /** 
     * 删除特定的缓存数据
     * @param $key
     * @param $persistence 是否支持持久化存储, true时会将db表中对应的条目也删除
     * @return true表示成功, false表示失败
     */
    public function delete($key) {
        $res = $this->cache->delete($key);
        return $res;
    }
}
