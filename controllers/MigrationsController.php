<?php
class MigrationsController extends AbstractController {

    /**
     * This function finds declared models and executes thier
     * migrate method if it exists
     *  
     * @return void
     */
    public function runAction () {
        if ($this->config->allowMigrations) {
            $options = getopt("c:a:",[]);
            echo (empty($options)) ? "<pre>"."\n" : "";
            echo "CinkoMVC Migrations"."\n\n";
            echo "Finding Models..."."\n\n";
            foreach (get_declared_classes() as $className) {
                if (substr($className,-5) == "Model") {
                    echo " - ".$className."\n";
                    $object = new $className;
                    if (method_exists($object, "migrate")) {
                        echo "   - Migrating...";
                        $method = "migrate";
                        $object->$method();
                        echo " Done"."\n\n";
                    } else {
                        echo "   - ".$className."->migrate() not found"."\n\n";
                    }
                }
            }
            echo (empty($options)) ? "</pre>"."\n" : "";
        } else {
            echo (empty($options)) ? "<pre>"."\n" : "";
            echo "CinkoMVC Migrations are disabled"."\n\n";
            echo (empty($options)) ? "</pre>"."\n" : "";
        }
    }

}