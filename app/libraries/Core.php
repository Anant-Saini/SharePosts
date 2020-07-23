<?php
    /*
     * App Core Class
     * Creates URL and loads core controller
     * URL FORMAT - controller/method/params
    */
    class Core {
        protected $currentController = 'Pages';
        protected $currentMethod = 'index';
        protected $params = [];
        public function __construct() {
            //print_r($this->getUrl());
            $url = $this->getUrl();
            //Check in controllers for first value from $url array
            if(!is_null($url) && file_exists('../app/controllers/'.ucwords($url[0]).'.php')){
                //set currentController value if it exists
                $this->currentController = ucwords($url[0]);
                //remove the value from array (0th index of array)
                unset($url[0]);
            }
            require_once('../app/controllers/'.$this->currentController.'.php');
            //Instantiate the currentController class
            $this->currentController = new $this->currentController;
            //Check For second url part or value
            if(isset($url[1])){
                //check if the method exists in currentController class
                if(method_exists($this->currentController, $url[1])){
                    $this->currentMethod = $url[1];
                    //Unset the value
                    unset($url[1]);
                }
            }
            //Get Params
            $this->params = $url ? array_values($url) : [];
            //Call a callback with an array of params
            call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
        }
        public function getUrl() {
            if(isset($_GET['url'])){
                $url = rtrim($_GET['url'], '/');
                $url = filter_var($url, FILTER_SANITIZE_URL);
                $url = explode('/', $url);
                return $url;
            }
        }
    }  