<?php
class CommonServer{
	/**
	 * enableHealth
	 * 允许进入健康列表的健康值
	 * 
	 * @var float
	 * @access private
	 */
	private $enableHealth = 40;
	
	/**
	 * filepath 
	 * server文件路径
	 *
	 * @var string
	 * @access private
	 */
	private $filepath = "";
	
	/**
	 * filepathBackup
	 * server备份文件路径
	 * 
	 * @var string
	 * @access private
	 */
	private $filepathBackup = "";
	
	/**
	 * arrServiceList 
	 * server list列表，支持直接将server list传入
	 *
	 * @var array
	 * @access private
	 */
	private $arrServiceList = array();
	private $arrServiceListBackup = array();

	/**
	 * hashId
	 * 用于多次请求都选取固定后端server的hashId
	 * 
	 * @var string
	 * @access private
	 */
	private $hashId = "";

	/**
	 * useHash
	 * 是否使用hash函数
	 * 
	 * @var mixed
	 * @access private
	 */
	private $useHash = "Uid";
	
	/**
	 * __construct 
	 * 
	 * @param mixed $serverConf 
	 * @access public
	 * @return void
	 */
	public function __construct($serverConf) {
		$this->setServerConf($serverConf);
		$healthPath = $this->getFilepath();
		if(!empty($healthPath)){
			$this->initServiceListByFile();
		}
	}
	
	/**
	 * getSearchRootService 
	 * 得到searchroot一个可用的服务器
	 * 
	 * @access public
	 * @return array('ip'=>'127.0.0.1','port'=>1234,'health'=>40);
	 */
	public function getSearchRootService()
	{
		$arrServiceList = $this->getServiceList();
		if(empty($arrServiceList)){
			throw new exception("service is empty");
		}
		$countServerList=count($this->arrServiceList);
		//如果有hashId，根据hashId%服务器数量得到，为了保持每次同一个uid都会落到后端同一个searchroot上面，避免searchroot数据不一致造成用户的体验不好
		if(!empty($this->hashId)){
			//防止hashId有负数情况
			$serviceNum=(($this->hashId%$countServerList)+$countServerList)%$countServerList;
		}
		//如果没有设置hashId，则自动随机生成一个可用的。
		else{
			$serviceNum=rand(0,$countServerList-1);
		}
		return $arrServiceList[$serviceNum];
	}

	/**
	 * initServiceListByFile 
	 * 根据文件名，备份文件名，允许通过的health值，得到符合要求的服务器IP、地址
	 * 
	 * @access private
	 * @return void
	 */
	private function initServiceListByFile()
	{
		$this->arrServiceList=array();
		$this->arrServiceListBackup=array();
		$healthPath = $this->getFilepath();
		$arrServers = array();
		$arrServices = file($healthPath);
		if(empty($arrServices)){
            if(file_exists($this->filepathBackup) && filesize($this->filepathBackup)){
                $healthPath = $this->filepathBackup;
                $arrServices = file($healthPath);
            }
            if(empty($arrServices)){
			    throw new exception("healthPath[$healthPath] doesn't have servers");
            }
		}
		foreach($arrServices as $key=>$line)
		{
			$serviceInfo =  preg_split("/\t/",$line);
			$tmp = array();
			$tmp['ip'] = $serviceInfo[0];
			$tmp['port'] = isset($serviceInfo[1])?$serviceInfo[1]:0;
			$tmp['health'] = str_replace("\n","",isset($serviceInfo[2])?$serviceInfo[2]:0);
			if($tmp['health'] >= $this->enableHealth){
				$this->arrServiceList[]=$tmp;
			}else{
				$this->arrServiceListBackup[]=$tmp;
			}
		}
		$this->arrServiceListBackup=ArrayUtil::sortKeyArray($this->arrServiceListBackup,'health',false);
		return true;
	}

	/**
	 * removeService 
	 * 
	 * @param mixed $service 
	 * @access public
	 * @return void
	 */
	public function removeService($service){
		foreach($this->arrServiceList as $k=>$v){
			if($v['ip']==$service['ip'] && $v['port']==$service['port'] && $v['health']==$service['health']){
				unset($this->arrServiceList[$k]);
				$this->arrServiceList=array_merge($this->arrServiceList);
				return true;
			}		
		}
		foreach($this->arrServiceListBackup as $k=>$v){
			if($v['ip']==$service['ip'] && $v['port']==$service['port'] && $v['health']==$service['health']){
				unset($this->arrServiceListBackup[$k]);
				$this->arrServiceListBackup=array_merge($this->arrServiceListBackup);
				return true;
			}
		}
		return false;
	}
	
	/**
	 * getServiceList 
	 * 得到所有的server list
	 *
	 * @access public
	 * @return array(array('ip'=>'127.0.0.1','port'=>1234,'health'=>40));
	 */
	public function getServiceList()
	{
		if(!empty($this->arrServiceList)){
			return $this->arrServiceList;
		}
		return $this->arrServiceListBackup;
	}
	
	/**
	 * setServerConf 
	 * 设置server conf 包括服务的ip port 超时时间等
     * array(
     *     //直接配置ip，该选项与文件获得服务地址只可二选一，优先文件地址选项
     *     'arrServiceList'=>array(
     *         array("ip"=>"10.100.8.47","port"=>"8887"),
     *         array("ip"=>"10.100.8.46","port"=>9587),
     *     ),
     *     //search root health list文件地址
     *     'filepath'=>'/dev/shm/searchroothealthy.list',
     *     'filepathBackup'=>'/usr/local/webserver/goso/searchroothealthy.list',
     *     //允许通过服务的健康值
     *     'enableHealth'=>40,
	 *     //用于每次连接固定后端的hashId
	 *     'hashId'=>21313,
     * ) 
	 *
	 * @param mixed $serverConf 
	 * @access public
	 * @return void
	 */
	public function setServerConf($serverConf)
	{
		if(isset($serverConf['enableHealth'])){
			$this->setEnableHealth($serverConf['enableHealth']);
		}
		if(isset($serverConf['filepath'])){
			$this->setFilepath($serverConf['filepath']);
		}
		if(isset($serverConf['filepathBackup'])){
			$this->setFilepathBackup($serverConf['filepathBackup']);
		}
		if(isset($serverConf['hashId'])){
			$this->setHashId($serverConf['hashId']);
		}
		if(isset($serverConf['useHash'])){
			$this->setUseHash($serverConf['useHash']);
		}
		if(isset($serverConf['arrServiceList'])){
			$this->setArrServiceList($serverConf['arrServiceList']);
		}
	}

	/**
	 * setArrServiceList 
	 * 
	 * @param mixed $arrServiceList 
	 * @access public
	 * @return void
	 */
	public function setArrServiceList($arrServiceList)
	{
		$this->arrServiceList=array();
		$this->arrServiceListBackup=array();
		foreach($arrServiceList as $k=>$v){
			$tmp = $v;
			if(!isset($v['ip']) || !isset($v['port'])){
				throw new exception("arrService must have ip and port:".print_r($arrServiceList,true));
			}
			//如果没有设置健康值，则将默认的健康值给予
			if(!isset($v['health'])){
				$tmp['health'] = $this->enableHealth;
			}
			$this->arrServiceList[]=$tmp;
		}
	}

	/**
	 * setEnableHealth 
	 * 
	 * @access public
	 * @return void
	 */
	public function setEnableHealth($enableHealth)
	{
		$this->enableHealth=intval($enableHealth);
	}
	
	/**
	 * setFilepath 
	 * 
	 * @param mixed $filepath 
	 * @access public
	 * @return void
	 */
	public function setFilepath($filepath)
	{
		if(!empty($filepath)){
			$this->filepath=$filepath;
		}
	}

	/**
	 * setFilepathBackup 
	 * 
	 * @param mixed $filepathBackup 
	 * @access public
	 * @return void
	 */
	public function setFilepathBackup($filepathBackup)
	{
		if(!empty($filepathBackup)){
			$this->filepathBackup=$filepathBackup;
		}
		else{
			$this->filepathBackup=$this->filepath."_backup";
		}
	}

	/**
	 * getFilepath 
	 * 
	 * @access private
	 * @return void
	 */
	private function getFilepath()
	{
		$healthPath = "";
		if(file_exists($this->filepath) && filesize($this->filepath)){
			$healthPath = $this->filepath;
		//如果健康的文件不存在，使用备份文件
		}else if(file_exists($this->filepathBackup) && filesize($this->filepathBackup)){
			$healthPath = $this->filepathBackup;
		}
		return $healthPath;
	}

	/**
	 * setHashId 
	 * 
	 * @param mixed $hashId 
	 * @access public
	 * @return void
	 */
	public function setHashId($hashId)
	{
		if(!empty($hashId)){
			$this->hashId=StringUtil::s2i($hashId);
		}
	}
	
	/**
	 * setUseHash 
	 * 
	 * @param mixed $useHash 
	 * @access public
	 * @return void
	 */
	public function setUseHash($useHash)
	{
		if("Uid" == $useHash){
			$this->hashId = ServerUtil::getUidInt64();
		}
	}

}
