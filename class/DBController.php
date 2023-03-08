<?php 
class DBController {
    private $host = "localhost";
    private $user = "mike";
    private $password = "mike";
    private $database = "jbeleave";
    private $conn;

    function __construct() {
        $this->conn = $this->connectDB();
    }

    function connectDB() {
        $conn = mysqli_connect($this->host, $this->user, $this->password, $this->database);
        return $conn;
    }

    function runBaseQuery($query) {
        $result = $this->conn->query($query);   
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $resultset[] = $row;
            }
        }
        return $resultset;
    }
    
    public function bindQueryParams($stmt, $paramType, $paramArray=array()){
        $paramValueReference[] = & $paramType;
        for($i = 0; $i < count($paramArray); $i++){
            $paramValueReference[] = & $paramArray[$i];
        }
        call_user_func_array(array($stmt,'bind_param'), $paramValueReference);
    }
    
    function runQuery($query, $param_type, $param_value_array) {
        $sql = $this->conn->prepare($query);
        $this->bindQueryParams($sql, $param_type, $param_value_array);
        $sql->execute();
        $result = $sql->get_result();
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $resultset[] = $row;
            }
        }
        
        if(!empty($resultset)) {
            return $resultset;
        }
    }

    public function execute($query, $paramType, $paramArray=array()){
        $stmt = $this->conn->prepare($query);
        if(!empty($paramType) && !empty($paramArray)){
            $this->bindQueryParams($stmt, $paramType, $paramArray);
        }
        $stmt->execute();
    }

    public function getRecordCount($query, $paramType, $paramArray=array()){
        $stmt = $this->conn->prepare($query);
        if(!empty($paramType) && !empty($paramArray)){
            $this->bindQueryParams($stmt, $paramType, $paramArray);
        }
        $stmt->execute();
        $stmt->store_result();
        $recordCount = $stmt->num_rows;
        return $recordCount;
    }

    public function insert($query, $paramType, $paramArray=array()){
        $stmt = $this->conn->prepare($query);
        $this->bindQueryParams($stmt, $paramType, $paramArray);
        $stmt->execute();
        $insertId = $stmt->insert_id;
        return $insertId;
    }

    public function select($query, $paramType, $paramArray=array()){
        $stmt = $this->conn->prepare($query);
        if(!empty($paramType) && !empty($paramArray)){
            $this->bindQueryParams($stmt, $paramType, $paramArray);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc())
            $resultset[] = $row;
        }
        if(!empty($resultset)){
            return $resultset;
        }
    }

    function update($query, $param_type, $param_value_array) {
        $sql = $this->conn->prepare($query);
        $this->bindQueryParams($sql, $param_type, $param_value_array);
        $sql->execute();
    }

    public function delete($query, $paramType, $paramArray=array()){
        $stmt = $this->conn->prepare($query);
        if(!empty($paramType) && !empty($paramArray)){
            $this->bindQueryParams($stmt, $paramType, $paramArray);
        }
        $stmt->execute();
    }
}

// $connCheck = new DBController();
// $connCheck->connectDB();
?>