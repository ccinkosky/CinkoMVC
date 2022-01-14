<?php
/**
 * This is a basic controller used to supply json responses to
 * requests from the React front end
 */
class IndexController extends AbstractController {

    /**
     * This function checks to make sure the request method is a post
     * as well as if the post variable "get" is equal to "indexText"
     * this returns the contents of $this->config->indexText as json
     * 
     * $this->config->indexText is set in the config.json file
     * 
     * @return void
     */
    public function textAction () {
        if ($this->isPost() && $this->post["get"] == "indexText") {
            $this->jsonView((array)$this->config->indexText);
        }
    }

}