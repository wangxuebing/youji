<?php
class CommonDbConnection extends CDbConnection {
    
	/**
     * 主库连接对象
     * 
     * @var PDO
     */
    public $master;
    
	/**
     * 从库连接对象
     * 
     * @var PDO
     */
    public $slave;
    
    /**
     * servers 
     * db服务器配置
	 * 当masterServerConf不存在时候，master使用该配置
	 * 当slaveServerConf不存在时slave使用该配置
     * 
     * @var Array
     * @access public
     */
    public $servers;
    
	/**
     * masterServerConf 
     * db主库服务器配置
     * 
     * @var Array
     * @access public
     */
    public $masterServerConf;
    
	/**
     * slaveServerConf
     * db从库服务器配置
     * 
     * @var Array
     * @access public
     */
    public $slaveServerConf;

	/**
	 * 以下两个见cdbConnection注释
	 */
	public $emulatePrepare=true;
	public $enableProfiling=true;

    /**
     * curSql 
     * 当前要执行的sql
     * 
     * @var mixed
     * @access public
     */
    public $curSql;
    
	/**
     * 事务层级，用来支持嵌套事务
     * 
     * @var int
     */
    public $transactionCounter = 0;
    
	/**
     * 当前的pdo
     * 
     * @var PDO
     */
    public $curPdo;
    
    public function __construct($dsn = 'mysql:', $username = '', $password = '') {
        parent::__construct ( $dsn, $username, $password );
    }
    
    /**
     * setActive 
     * 覆盖此方法，避免在里面进行数据库的连接，从而进行laze connect
     * 
     * @param mixed $value 
     * @access public
     * @return void
     */
    public function setActive($value) {
    }
    
    /**
     * getLastInsertID 
     * 
     * @param string $sequenceName 
     * @access public
     * @return void
     */
    public function getLastInsertID($sequenceName = '') {
        $pdo = $this->getMasterPdoInstance ();
        return $pdo->lastInsertId ( $sequenceName );
    }
    public function quoteValue($str) {
        if (is_int ( $str ) || is_float ( $str ))
            return $str;
        
        $pdo = $this->getMasterPdoInstance ();
        if (($value = $pdo->quote ( $str )) !== false)
            return $value;
        else // the driver doesn't support quote (e.g. oci)
            return "'" . addcslashes ( str_replace ( "'", "''", $str ), "\000\n\r\\\032" ) . "'";
    }
    public function getAttribute($name) {
        $this->setActive ( true );
        return $this->curPdo->getAttribute ( $name );
    }
    
    /**
     * 创建命令对象
     * @see CDbConnection::createCommand()
     */
    public function createCommand($query = null) {
        return new CommonDbCommand ( $this, $query );
    }
    
    public function setSql($sql) {
        $this->curSql = $sql;
    }
    /**
     * begin一个事务
     * 
     */
    public function beginTransaction() {
        $this->transactionCounter ++;
        if ($this->transactionCounter > 1) {
            return;
        }
        Yii::trace ( 'Starting transaction', 'system.db.CDbConnection' );
        $pdo = $this->getMasterPdoInstance ();
        $pdo->beginTransaction ();
    }
    /**
     * commit一个事务
     * 
     */
    public function commit() {
        $this->transactionCounter --;
        if ($this->transactionCounter > 0) {
            return;
        }
        Yii::trace ( 'Committing transaction', 'system.db.CDbTransaction' );
        $pdo = $this->getMasterPdoInstance ();
        $pdo->commit ();
    }
    /**
     * rollback一个事务
     */
    public function rollback() {
        $this->transactionCounter --;
        if ($this->transactionCounter > 0) {
            return;
        }
        Yii::trace ( 'Rolling back transaction', 'system.db.CDbTransaction' );
        $pdo = $this->getMasterPdoInstance ();
        $pdo->rollback ();
    }
    
    /**
     * 创建PDO实例
     * 这里实现的读写分离策略：
     * 如果有显示指定走主库或从库，则遵循指定；否则进行以下策略：
     * 如果是更新类sql，则走主库；
     * 如果之前已经走过主库，否则后续的sql全走主库；
     * 如果是查询类sql且之前未走过主库，则走从库
     * 
     * @return PDO the pdo instance
     */
    public function getPdoInstance() {
        $pdoClass = $this->pdoClass;
        if ($this->isMasterConnecton ( $this->curSql )) {
            $this->curPdo = $this->getMasterPdoInstance ();
            return $this->curPdo;
        } else {
            $this->curPdo = $this->getSlavePdoInstance ();
            return $this->curPdo;
        }
    }
    /**
     * 判断此次命令是否需要走主库
     * 
     * @param String $sql
     */
    protected function isMasterConnecton($sql) {
        if ($this->master != null) {
            return true;
        }
        $sql = trim ( $sql );
        if (preg_match ( "/^(SELECT|SHOW|DESCRIBE|DESC)/i", $sql ) === 0) {
            return true;
        }
        return false;
    }
    
    /**
     * getMasterPdoInstance 
     * 获取主库连接对象
     * 
     * @access protected
     * @return 主库的pdo连接句柄
     */
    protected function getMasterPdoInstance() {
        if ($this->master == null) {
            $this->master = $this->createMasterPdoInstance ();
        }
        return $this->master;
    }

	protected function getPdoConnection($servers,$dbname,$username,$password,$_attributes)
	{
		if(empty($servers)){
			throw new CDbException ( Yii::t ( 'yii', 'empty servers' ) );
		}
        shuffle ( $servers );
        for($i = 0; $i < count ( $servers ); $i ++) {
            try {
                $server = $servers [$i];
				if(isset($server['connectionString'])){
					$connectionString = $server['connectionString'];
				}else{
					$connectionString = "mysql:host=".$server['ip'].";dbname=".$dbname;
				}
                $pdo = $this->_createPdoInstance ( $connectionString, $username, $password, $_attributes );
                Yii::log ( "connect server succ:" . print_r($server,true), CLogger::LEVEL_INFO, __METHOD__ );
                return $pdo;
            } catch ( Exception $e ) {
                Yii::log ( "connect server fail:" . print_r($server,true), CLogger::LEVEL_WARNING, __METHOD__ );
            }
        }
        Yii::log ( "server:" . print_r($servers,true) . ",msg:" . $e->getMessage (), "connect server fail", CLogger::LEVEL_ERROR, __METHOD__ );
        throw new CDbException ( Yii::t ( 'yii', 'connect all server fail' ) );
	}
    
	/**
     * getSlavePdoInstance 
     * 获取从库连接对象
     * 
     * @access protected
     * @return 从库的pdo连接句柄
     */
    protected function getSlavePdoInstance() {
        if ($this->slave == null) {
            $this->slave = $this->createSlavePdoInstance ();
        }
        return $this->slave;
    }
    
	/**
     * 创建主库连接对象
     * 
     * @throws CDbException
     */
    protected function createMasterPdoInstance() {
		if (empty($this->masterServerConf)){
			if (! is_array ( $this->servers ) || count ( $this->servers ) == 0) {
				throw new CDbException ( Yii::t ( 'yii', 'config error for DbConnection::connectionStrings' ) );
			}
			$server = $this->servers [0];
			$username = $server['username'];
			$password = $server['password'];
			$dbname = "";
			$_attributes = array();
			if ( isset ( $server['_attributes'] )) {
				$_attributes = $server['_attributes'];
			}
			$masterServers = array($server);
		}else{
			if(!isset($this->masterServerConf['serverConf'])){
				throw new CDbException( Yii::t ( 'yii', ' no serverConf error for DbConnection' ) );
			}
			$commonServer = new CommonServer($this->masterServerConf['serverConf']);
			$masterServers = $commonServer->getServiceList();
			$dbname = $this->masterServerConf['dbname'];
			$username = $this->masterServerConf['username'];
			$password = $this->masterServerConf['password'];
			$dbname = $this->masterServerConf['dbname'];
			$_attributes = array();
			if ( isset ( $this->masterServerConf['_attributes'] )) {
				$_attributes = $this->masterServerConf['_attributes'];
			}
		}
		return $this->getPdoConnection($masterServers,$dbname,$username,$password,$_attributes);
		
	}
	/**
	 * 获取从库连接对象
	 * 
	 * @throws CDbException
	 */
	protected function createSlavePdoInstance() {
		if (empty($this->slaveServerConf)){
			if (! is_array ( $this->servers ) || count ( $this->servers ) == 0) {
				throw new CDbException ( Yii::t ( 'yii', 'config error for DbConnection::connectionStrings' ) );
			}
			if (count ( $this->servers ) == 1) {
				return $this->createMasterPdoInstance ();
			}
			$slaveServers = $this->servers;
			array_shift ( $slaveServers );
			$username = $slaveServers[0]['username'];
			$password = $slaveServers[0]['password'];
			$dbname = "";
			$_attributes = array();
			if ( isset ( $slaveServers[0]['_attributes'] )) {
				$_attributes = $slaveServers[0]['_attributes'];
			}
		}else{
			if(!isset($this->slaveServerConf['serverConf'])){
				throw new CDbException( Yii::t ( 'yii', ' no serverConf error for DbConnection' ) );
			}
			$commonServer = new CommonServer($this->slaveServerConf['serverConf']);
			$slaveServers = $commonServer->getServiceList();
			$dbname = $this->slaveServerConf['dbname'];
			$username = $this->slaveServerConf['username'];
			$password = $this->slaveServerConf['password'];
			$dbname = $this->slaveServerConf['dbname'];
			$_attributes = array();
			if ( isset ( $this->slaveServerConf['_attributes'] )) {
				$_attributes = $this->slaveServerConf['_attributes'];
			}
		}
		return $this->getPdoConnection($slaveServers,$dbname,$username,$password,$_attributes);
    }
    
	/**
     * 创建一个数据库连接
     * 
     * @param String $connStr
     * @param String $userName
     * @param String $password
     * @param Array $attributes
     */
    protected function _createPdoInstance($connStr, $userName, $password, $attributes) {
        $pdo = new Pdo ( $connStr, $userName, $password, $attributes );
        $this->initConnection ( $pdo );
        
        return $pdo;
    }
}
