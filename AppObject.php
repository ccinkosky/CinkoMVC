<?php
/**
 * This is the base object that AbstractController and AbstractModel extend.
 * It provides some simple tools to make development easier
 */
class AppObject {

    public $config;
    public $db;

    /**
     * @return AppObject
     */
    public function __construct () {
        $this->config = $this->loadConfig();
        $this->db = $this->dbConnect();
        return $this;
    }

    /**
     * This function loads the config.json into $this->config.
     * 
     * @return object
     */
    private function loadConfig () : object {
        return json_decode(file_get_contents(__DIR__."/config.json"));
    }

    /**
     * This function takes the database values from $this->config->database
     * and returns a PDO object to $this->db if $this->config->databaseOn equals "true"
     * 
     * @return PDO|false
     */
    private function dbConnect () {
        if ($this->config->databaseOn) {
            $dsn = $this->config->database->type;
            $dsn .= ":host=".$this->config->database->host;
            $dsn .= ";dbname=".$this->config->database->db;
            $db = new PDO($dsn, $this->config->database->user, $this->config->database->pass);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $db;
        } else {
            return false;
        }
    }

    /**
     * This function will allow you to create cookies. The value
     * you store in the cookie can be a number, string or array.
     * Arrays are converted to a string and then converted back
     * to an array when using getCookie.
     * 
     * For example: 
     * 
     * $this->setCookie([
     *     "value1" => "some value",
     *     "value2" => "another value"
     * ]);
     * 
     * @param string $name
     * @param array|string $value
     * @param int $time=86400
     * 
     * @return void
     */
    public function setCookie (string $name, $value, int $time=86400) {
        $value = (is_array($value)) ? "%%array%%".json_encode($value) : $value;
        setCookie($name,$value,time()+$time,"/");
    }

    /**
     * This function returns the value of a cookie.
     * 
     * @param string $name
     * 
     * @return array|string
     */
    public function getCookie (string $name) {
        if(isset($_COOKIE[$name])) {
            $cookie = $_COOKIE[$name];
            if(substr($cookie, 0, 9) == "%%array%%") {
                return json_decode(str_replace("%%array%%","",$cookie),true);
            } else {
                return $cookie;
            }
        }
    }

    /**
     * This function will delete/expire a cookie
     * 
     * @param string $name
     * 
     * @return void
     */
    public function deleteCookie (string $name) {
        $this->setCookie($name,"",-3600);
    }
    
    /**
     * This function will send a basic email
     * 
     * @param string $to
     * @param string $subject
     * @param string $body
     * @param string|null $from
     * 
     * @return void
     */
    public function sendEmail (string $to, string $subject, string $body, string $from = null) {
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= is_null($from) ? "" : $from;
        mail($to,$subject,$body,$headers);
    }
    
    /**
     * This function will send an email using a template from
     * the views/emails/ directory. You pass in an array of data
     * and the function will find the keys in the template and
     * replace them with the values.
     * 
     * In the below example it will search for %%name%% and
     * %%address%% in the views/emails/test_email.html template
     * and replace it with "John Smith" and "123 Main St."
     * 
     * $this->sendTemplateEmail(
     *     "some_email@example.com",
     *     "This is a test email",
     *     "TestEmail",
     *     [
     *         "name" => "John Smith",
     *         "address" => "123 Main St."
     *     ]
     * ))
     * 
     * @param string $email
     * @param string $subject
     * @param string $template
     * @param string $from
     * @param array $data
     * 
     * @return void
     */
    public function sendTemplateEmail (string $email, string $from, string $subject, string $template, array $data) {
        $body = file_get_contents("../views/emails/".$template.".html");
        foreach ($data as $key => $value) {
            $body = str_replace("%%".$key."%%",$value,$body);
        }
        $this->sendEmail($email,$subject,$body,$from);
    }
    
    /**
     * This function makes returns if the email address is valid or not
     * 
     * @param string $email
     * 
     * @return bool
     */
    public function isValidEmail (string $email) : bool {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * This function just makes redirection a little easier
     * 
     * @param string $destination
     * 
     * @return void
     */
    public function redirect (string $destination) {
        header("Location: ".$destination);
    }

    /**
     * This function will take a camel case string and
     * return the snake case version.
     * 
     * @param string $input
     * 
     * @return string
     */
    public function camelToSnake (string $input) : string {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }

    /**
     * This function will take a snake case string and
     * return the camel case version
     * 
     * @param string $input
     * 
     * @return string
     */
    public function snakeToCamel (string $input) : string {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $input))));
    }

}