<?php
/**
 * This is a basic controller used to supply json responses to
 * requests from the React front end
 */
class IndexController extends AbstractController {

    /**
     * This is the default action for this controller. This would be
     * called with /index or /index/index
     * 
     * @return void
     */
    public function IndexAction () {
        echo "Hello Cinko!";
    }

    /**
     * This function has some basic security checks. It checkes if the
     * request method is POST and if the the value for "fetch" from
     * the POST body is "splashPage". You can add additional checks here
     * for added security. It then returns $this->config->splashPage as
     * json. $this->config->splashPage is set in the config.json file
     * 
     * @return void
     */
    public function splashPageAction () {
        if ($this->isPOST() && $this->postBody["fetch"] == "splashPage") {
            $this->jsonView((array)$this->config->splashPage);
        }
    }

    public function readMeAction () {
        if ($this->isPOST() && $this->postBody["fetch"] == "readMe") {
            $this->jsonView([
                "readMe" => file_get_contents(str_replace("controllers","",__DIR__)."README.md")
            ]);
        }
    }

}