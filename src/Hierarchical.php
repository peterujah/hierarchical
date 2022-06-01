<?php 
/**
 * Hierarchical - Light, simple  PHP and mysql Hierarchy data and organization chart
 * This class was heavily inspired by interview question
 * @author      Peter Chigozie(NG) peterujah
 * @copyright   Copyright (c), 2019 Peter(NG) peterujah
 * @license     MIT public license
 */
namespace Peterujah\NanoBlock;

/**
 * Class Hierarchical.
 */
class Hierarchical {
	/**
	* Hold html algorithm code
	* @var int
	*/
	public const HTML = 1;

	/**
	* Hold array algorithm code
	* @var int
	*/
	 public const DATA = 2;

	/**
	* Hold google chart algorithm code
	* @var int
	*/
	public const CHART = 3;

	/**
	* Database connection
	* @var object
	*/
	protected $conn;

	/**
	* Holds current list
	* @var array
	*/
	private $arrayList = array();

	/**
	* Holds current execution algorithm
	* @var int
	*/
	private $runType = 1;
	
	/**
	* Holds current record count
	* @var int
	*/
	private $totalRecord = 0;

	/**
	* Hold last added userid
	* @var string
	*/
	private $lastInserted = null;

	/**
	* Constructor.
	* @param $conn mysql connection object
	* @param int $type execution algorithm type
	*/
	public function __construct($conn, $type = 1){
		$this->conn = $conn;
		$this->runType = $type;
	}
    
	/**
	* execute command
	* @param $name string user/person name 
	* @param $id string user/person account id or referer code
	* @return mixed data, html, array, json or null
	*/
	public function run($name, $id){
		if($this->runType == self::HTML){
			return $this->html($name, $id);
		}else if($this->runType == self::DATA){
			return $this->arrayData($name, $id, "");
		}else if($this->runType == self::CHART){
			return $this->chart($name, $id);
		}
		return null;
	}

	/**
	* Performs a query against the database. 
	* @param $id string user/person account id or referer code
	* @return object return a mysqli_result object.
	*/
	private function query($id){
		return mysqli_query($this->conn, "
			SELECT r.*, u.* 
			FROM hierarchical_referrer r
			INNER JOIN hierarchical_users u
			ON r.referrer_user_id = u.user_id
			WHERE r.referrer_parent_id = '{$id}'
			ORDER BY r.ref DESC
		");
	}
	
	/**
	* Add's new user to users table
	* @param $user string the userid or any unique identifier  
	* @param $name string The user display name 
	* @return Object $this
	*/
	public function add($user, $name){
		if(mysqli_query($this->conn, "
			INSERT INTO hierarchical_users (
				`user_id`, 
				`user_name`
			)
			VALUES (
				{$user}
				{$name}
			)
		")){
			$this->lastInserted = $user;
		}
		return $this;
	}
	
	/**
	* Assign's the last added user to a position  
	* @param $boss string the parent boss who this current user will be under
	* @return bool after it has been added
	*/
	public function under($boss){
		$added = false;
		if(!empty($this->lastInserted)){
			$added = mysqli_query($this->conn, "
				INSERT INTO hierarchical_referrer (
					`referrer_parent_id`, 
					`referrer_user_id`
				)
				VALUES (
					{$boss}
					{$this->lastInserted}
				)
			");
		}
		$this->lastInserted = null;
		return $added;
	}

    /**
     * build html result
     * @param $name string user/person name 
     * @param $id string user/person account id or referer code
     * @return html data
    */
	private function html($name, $id){
		$result = $this->query($id);
		$html = "<h3>".$name."</h3><ul>";
		while($row = mysqli_fetch_assoc($result)){
			$html .=  "
				<li>".$row['user_name']."</li>
			";
			echo $this->html($row["user_name"], $row["referrer_user_id"]);
		}
		$html .=  "</ul>";
		return $html;
	}

    /**
     * build array result
     * @param $name string user/person name 
     * @param $id string user/person account id or referer code
     * @param $parent string user/person account id or referer code of the parent referrer
     * @return array list of array
    */
	private function arrayData($name, $id, $parent){
		$result = $this->query($id);
		$addList[$id] = array(
			"name" => $name,
			"referrer_id" => $id,
			"parent_id" => $parent
		);
		while($row = mysqli_fetch_assoc($result)){
			$addList[$id]["downLiners"][] = array(
				"name" => $row['user_name'],
				"referrer_id" => $row["referrer_user_id"],
				"parent_id" => $row["referrer_parent_id"]
			);
			$this->arrayData($row["user_name"], $row["referrer_user_id"], $row["referrer_parent_id"]);
		}
		array_push($this->arrayList, $addList);
		$this->totalRecord = count($this->arrayList);
		return $this->arrayList;
	}

    /**
     * build google chart array list
     * @param $name string user/person name 
     * @param $id string user/person account id or referer code
     * @return json list of json string with ranks
    */
	private function chart($name, $id){
		$chart = array();
		foreach($this->arrayData($name, $id, "") as $row){
			foreach($row as $key => $value){
				$chart[] = array(
					array(
						"v" => (!empty($value["referrer_id"]) ? $value["referrer_id"] : ""),
						"f" => (!empty($value["name"]) ? $value["name"] : null)
					),
					(!empty($value["parent_id"]) ? $value["parent_id"] : ""),
					"upLiner"
				);
				if(!empty($value["downLiners"])){
					foreach($value["downLiners"] as $k => $v){
						$chart[] = array(
							array(
								"v" => (!empty($v["referrer_id"]) ? $v["referrer_id"] : ""),
								"f" => (!empty($v["name"]) ? $v["name"] : null)
							),
							(!empty($v["parent_id"]) ? $v["parent_id"] : ""),
							"downLiners"
						);
					}
				}
			}
		}
		$this->totalRecord = count($chart);
		return json_encode($chart);
	}

	/**
        * get list count
        * @return int count of object
        */
	public function count(){
		return $this->totalRecord;
	}
}
