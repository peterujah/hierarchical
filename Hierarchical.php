<?php 
/**
 * Hierarchical - Light, simple  PHP and mysql Hierarchy data and organization chart
 * This class was heavily inspired by interview question
 * @license MIT
 * @author Peter https://github.com/peterujah
 */

class Hierarchical {
    /**
     * Hold html algorithm code
     *
     * @var int
     */
    const HTML = 1;

    /**
     * Hold array algorithm code
     *
     * @var int
     */
	const DATA = 2;

    /**
     * Hold google chart algorithm code
     *
     * @var int
     */
	const CHART = 3;

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
