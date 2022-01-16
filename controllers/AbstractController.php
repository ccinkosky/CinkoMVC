<?php
/**
 * The AbstractController adds a few helpeful functions to all
 * controllers that extend it
 */
class AbstractController extends AppObject {

    public $post;

    /**
     * When a new instance of the controller is created this 
     * will take any json data in php://input from a post 
     * or similar and store it in $this->post
     * 
     * @return AbstractController
     */
    function __construct () {
        parent::__construct();
        $this->postBody = json_decode(@file_get_contents('php://input'),true);
        return $this;
    }

    /**
     * Simple function to check if the request method is POST
     * 
     * @return bool
     */
    public function isGET () : bool {
        return ($_SERVER['REQUEST_METHOD'] === 'GET');
    }

    /**
     * Simple function to check if the request method is POST
     * 
     * @return bool
     */
    public function isPOST () : bool {
        return ($_SERVER['REQUEST_METHOD'] === 'POST');
    }

    /**
     * Simple function to check if the request method is PUT
     * 
     * @return bool
     */
    public function isPUT () : bool {
        return ($_SERVER['REQUEST_METHOD'] === 'PUT');
    }

    /**
     * Simple function to check if the request method is DELETE
     * 
     * @return bool
     */
    public function isDELETE () : bool {
        return ($_SERVER['REQUEST_METHOD'] === 'DELETE');
    }

    /**
     * Simple function to present an array as json, like if you
     * were responding with json to some sort of API or AJAX call.
     * 
     * @param array $array
     * 
     * @return void
     */
    public function jsonView (array $array) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($array);
    }

    /**
     * This function will include the corresponding view
     * in the views/ directory
     * 
     * @param string $view
     * 
     * @return void
     */
    public function view (string $view) {
        include("../views/".$view.".php");
    }

}