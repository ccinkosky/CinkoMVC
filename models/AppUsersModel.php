<?php
/**
 * This is an example of a model that extends AbstractModel
 * to show examples of the functions AbstractModel adds
 * 
 * Since the model is called AppUsersModel, it will assume
 * that the table it is working with is "app_users", so: 
 * 
 * $this->table = "app_users";
 * 
 * ...however you can change the table anytime like so:
 * 
 * $this->table = "some_other_table";
 * 
 * You can see all these functions in action in the example
 * controller UserController.php
 */
class AppUsersModel extends AbstractModel {

    /**
     * This function is an example of how you can use the normal
     * PDO functionality (https://phpdelusions.net/pdo). 
     * Sometimes this is needed to do more complicated queries.
     * 
     * @param int $id
     * 
     * @return array
     */
    public function pdoExample (int $id) : array {
        $sql = "SELECT 
                au.email, 
                aumd.value as color_preference
                FROM app_users au, app_users_meta_data aumd
                WHERE au.id = aumd.user_id
                AND aumd.name = 'color_preference'
                AND au.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]); 
        return $stmt->fetchAll();
    }
    
    /**
     * This is an example function to insert a new
     * record into the app_users table. Something like this
     * could be used when a new user registers
     * 
     * @param string $email
     * @param string $pass
     * 
     * The insert method returns the last_insert_id()
     * @return int
     */
    public function createUser (string $email, string $pass) : int {
        return $this->insert([
            "email"             => $email,
            "password"          => md5($pass),
            "registration_date" => time()
        ]);
    }

    /**
     * This is an example function to select records by email
     * that registered after a certain date
     * 
     * @param string $email
     * @param int $date
     * 
     * @return array
     */
    public function getUserByEmailAfterDate (string $email, int $date) : array {
        return $this->select(
            $fields = ["id","email","registration_date"],
            $where = [
                ["email","=",$email],
                ["registration_date",">=",$date]
            ]
        );
    }

    /**
     * This function is an example of how to update a record.
     * This particular example could be used when a user
     * updates their password
     * 
     * @param int $id
     * @param string $pass
     * 
     * @return void
     */
    public function updatePassword (int $id, string $pass) {
        $this->update(
            $pairs = ["password" => md5($pass)],
            $where = [["id","=",$id]]
        );
    }

    /**
     * This is an example function showing how to delete a record
     * 
     * @param int $id
     * 
     * @return void
     */
    public function deleteUser (int $id) {
        $this->delete([
            $where = [["id","=",$id]]
        ]);
    }

}