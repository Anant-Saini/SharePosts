<?php
    class Pages extends Controller {
        public function __construct() {
            
        }
        public function index() {
            if(isLoggedIn()) {
                redirect('posts');
            }
            //Data to be passed in View
            $data = [
                'title' => 'SharePosts',
                'description' => 'Simple social network build on traversyMVC framework.'
            ];
            //Loading View
            $this->view('pages/index', $data);
        }
        public function about() {
            //Data to be passed in View
            $data = [
                'title' => 'About Us',
                'description' => 'App to share posts with other users.'
            ];
            //Loading View
            $this->view('pages/about', $data);
        }
    }