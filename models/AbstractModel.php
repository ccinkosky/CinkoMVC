<?php
/**
 * The AbstractModel adds some new functions to make basic
 * queries to the database simpler. You can see these in action
 * in the example model AppUsersModel.php
 */
class AbstractModel extends AppObject {

    public $table;

    /**
     * When a new instance of the model is created, this takes
     * the class name and pulls the table name from it.
     * 
     * For example:
     * 
     * class AppUsersModel extends AbstractModel
     * 
     * ...then $this->table will be "app_users"
     * 
     * class AppUserMetaDataModel extends AbstractModel
     * 
     * ...then $this->table will be "app_user_meta_data"
     * 
     * @return AbstractModel
     */
    function __construct () {
        parent::__construct();
        $this->table = str_replace("_model", "", $this->camelToSnake(get_class($this)));
        if (!$this->tableExists() && method_exists($this, "migrate") && $this->config->databaseOn) {
            $this->migrate();
        }
        return $this;
    }

    /**
     * THis function checks if the table exists
     * 
     * @return bool
     */
    public function tableExists () : bool {
        try {
            $result = $this->db->query("SELECT 1 FROM {$this->table} LIMIT 1");
        } catch (Exception $e) {
            return FALSE;
        }
        return $result !== FALSE;
    }

    /**
     * This function will create a SELECT query utilizing
     * PDO prepared statements
     * 
     * @param array|string $fields
     * @param array|null $wheres
     * @param array|null $orderby
     * @param array|null $limit
     * 
     * @return array
     */
    public function select ($fields, array $wheres = null, array $orderby = null, array $limit = null) : array {
        $prepareVars = [];
        $fieldsPart = (!is_array($fields)) ? "*" : implode(",",$fields);
        $fromPart = " FROM ".$this->table;
        
        $processedWheres = $this->processWheres($wheres,$prepareVars);
        $wherePart = $processedWheres["wherePart"];
        $prepareVars = $processedWheres["prepareVars"];

        if (!is_null($orderby)) {
            if (isset($orderby["direction"])) {
                $orderpart = " ORDER BY ".$orderby["column"]." ".$orderby["direction"];
            } else {
                $orderpart =" ORDER BY ".$orderby["direction"];
            }
        } else {
            $orderpart = "";
        }

        if (!is_null($limit)) {
            if (isset($limit["offset"])) {
                $limitpart = " LIMIT ".$limit["limit"]." OFFSET ".$limit["offset"];
            } else {
                $limitpart =" LIMIT ".$limit["limit"];
            }
        } else {
            $limitpart = "";
        }

        $sql = "SELECT ".$fieldsPart.$fromPart.$wherePart.$orderpart.$limitpart;
        $stmt = $this->db->prepare($sql);
        $stmt->execute($prepareVars); 
        return $stmt->fetchAll();
    }

    /**
     * This function will create a INSERT query utilizing
     * PDO prepared statements
     * 
     * @param array $pairs
     * 
     * @return int|null
     */
    public function insert (array $pairs) : ?int {
        $fields = array_keys($pairs);
        $prepareVars = array_values($pairs);
        $q = [];
        for ($i=0; $i<count($prepareVars); $i++) { $q[] = "?"; }
        $sql = "INSERT INTO ".$this->table." (".implode(",",$fields).") VALUES (".implode(",",$q).")";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($prepareVars);
        return $this->db->lastInsertId();
    }

    /**
     * This function will create a UPDATE query utilizing
     * PDO prepared statements
     * 
     * @param array $pairs
     * @param array|null $wheres
     * 
     * @return void
     */
    public function update (array $pairs, array $wheres = null) {
        $sets = [];
        $prepareVars = array_values($pairs);
        foreach ($pairs as $key => $value) {
            $sets[] = $key." = ?";
        }

        $processedWheres = $this->processWheres($wheres,$prepareVars);
        $wherePart = $processedWheres["wherePart"];
        $prepareVars = $processedWheres["prepareVars"];

        $sql = "UPDATE ".$this->table." SET ".implode(", ",$sets).$wherePart;
        $stmt = $this->db->prepare($sql);
        $stmt->execute($prepareVars);
    }
    
    /**
     * This function will create a DELETE query utilizing
     * PDO prepared statements
     * 
     * @param array|null $wheres
     * 
     * @return void
     */
    public function delete (array $wheres = null) {
        if (!is_null($wheres)) {
            $prepareVars = [];
            $processedWheres = $this->processWheres($wheres,$prepareVars);
            $wherePart = $processedWheres["wherePart"];
            $prepareVars = $processedWheres["prepareVars"];
            
            $sql = "DELETE FROM ".$this->table." ".$wherePart;
            $stmt = $this->db->prepare($sql);
            $stmt->execute($prepareVars);
        }
    }

    /**
     * This function is to process the wheres in the wheres array
     * 
     * @param array $wheres
     * @param array $prepareVars
     * 
     * @return array
     */
    private function processWheres (array $wheres, array $prepareVars) : array {
        if (!is_null($wheres)) {
            $wherePart = " WHERE ";
            $whereArray = [];
            foreach ($wheres as $where) {
                $w = "(".$where[0]." ".$where[1]." ";
                if (is_array($where[2])) {
                    $w = $w."(";
                    $q = [];
                    foreach ($where[2] as $var) {
                        $prepareVars[] = $var;
                        $q[] = "?";
                    }
                    $w = $w.implode(",",$q).")";
                } else {
                    $prepareVars[] = $where[2];
                    $w = $w."?";
                }
                $w = $w.")";
                $whereArray[] = $w;
            }
            return [
                "wherePart" => $wherePart.implode(" AND ",$whereArray),
                "prepareVars" => $prepareVars
            ];
        } else { 
            return [
                "wherePart" => "",
                "prepareVars" => $prepareVars
            ];
        }
    }

}