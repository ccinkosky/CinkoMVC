<?php
class JobsController extends AbstractController {

    public function setupPageAction () {
        $base_dir = str_replace("/controllers","",__DIR__);
        $dir_name = $_GET["dir_name"];
        $react_class = $_GET["react_class"];
        
        // Create react component directory
        $react_dir = $base_dir."/public/components/rzaapp/".$dir_name;
        mkdir($react_dir);
        echo "Directory Created: ".$react_dir."\n\n";
        
        // Create main react app js
        $react_app_string = file_get_contents($base_dir."/views/setup/Rza.App.template");
        $react_app_string = str_replace("%%class%%",$react_class,$react_app_string);
        file_put_contents($react_dir."/".$dir_name.".js",$react_app_string);
        echo "File Created: ".$react_dir."/".$dir_name.".js"."\n\n";

        // Create react component
        $react_class_string = file_get_contents($base_dir."/views/setup/Rza.Component.template");
        $react_class_string = str_replace("%%class%%",$react_class,$react_class_string);
        file_put_contents($react_dir."/".$react_class.".js",$react_class_string);
        echo "File Created: ".$react_dir."/".$react_class.".js"."\n\n";
        
        // Create controller
        $controller = ucwords($dir_name)."Controller";
        $react_controller_string = file_get_contents($base_dir."/views/setup/Rza.Controller.template");
        $react_controller_string = str_replace("%%controller%%",$controller,$react_controller_string);
        $react_controller_string = str_replace("%%dir_name%%",$dir_name,$react_controller_string);
        file_put_contents($base_dir."/controllers/".$controller.".php",$react_controller_string);
        echo "File Created: ".$base_dir."/controllers/".$controller.".php"."\n\n";

        echo "Complete!"."\n\n";
    }

}