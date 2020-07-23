<?php
    /* Base Controller
     * Loads the Models ands Views
     */
    class Controller {
        //index method of controller class
        public function index() {
            #this method can be easily overrided in base class
        }
        //Loads model
        public function model($model) {
            //require model file
            require_once('../app/models/'. $model .'.php');
            //instantiate model
            return new $model();
        }
        //Loads View
        public function view($view, $data = []) {
            //check if view exists
            if(file_exists('../app/views/'. $view .'.php')) {
                require_once('../app/views/'. $view .'.php');
            } else {
                //view doesn't exist
                die('View Does not exist!');
            }
        }
    }