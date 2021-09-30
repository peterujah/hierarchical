<?php 
class Hierarchical {
	protected $conn;
	private $arrayList = array();
	private $runType = 1;
	const HTML = 1;
	const LIST = 2;
	const CHART = 3;
	public function __construct($conn, $type = 1){
		$this->conn = $conn;
		$this->runType = $type;
	}

	public function run($name, $id){
		if($this->runType == self::HTML){
			return $this->html($name, $id);
		}else if($this->runType == self::LIST){
			return $this->array($name, $id, "");
		}else if($this->runType == self::CHART){
			return $this->chart($name, $id);
		}
		return null;
	}

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

	private function array($name, $id, $parent){
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
			$this->array($row["user_name"], $row["referrer_user_id"], $row["referrer_parent_id"]);
		}
		array_push($this->arrayList, $addList);
		return $this->arrayList;
	}

	private function chart($name, $id){
		$chart = array();
		foreach($this->array($name, $id, "") as $row){
			foreach($row as $key => $value){
				$chart[] = array(
					array(
						"v" => $value["referrer_id"]??"",
						"f" => $value["name"]??null 
					),
					$value["parent_id"]??"",
					"upLiner"
				);
				if(!empty($value["downLiners"])){
					foreach($value["downLiners"] as $k => $v){
						$chart[] = array(
							array(
								"v" => $v["referrer_id"]??"",
								"f" => $v["name"]??null 
							),
							$v["parent_id"]??"",
							"downLiners"
						);
					}
				}
			}
		}
		return json_encode($chart);
	}
}