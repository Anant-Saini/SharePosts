<?php
    class Posts extends Controller {
        private $postModel;
        private $userModel;
        public function __construct() {
            if(!isLoggedIn()) {
                redirect('users/login');
            }
            $this->postModel = $this->model('Post');
            $this->userModel = $this->model('User');
        }
        //post homepage
        public function index() {
            //get posts
            $posts = $this->postModel->getPosts();
            $data = [
                'posts' => $posts
            ];
            $this->view('posts/index', $data);
        }
        //add new post
        public function add() {
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                //SANITIZE POST ARRAY
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                $data = [
                    'title' => trim($_POST['title']),
                    'body' => trim($_POST['body']),
                    'user_id' => $_SESSION['user_id'],
                    'title_err' => '',
                    'body_err' => ''
                ];
                //Validate Title Data
                if(empty($data['title'])) {
                    $data['title_err'] = 'Please enter title.';
                }
                //Validate Body Data
                if(empty($data['body'])) {
                    $data['body_err'] = 'Please enter body.';
                }
                //check if errors are empty
                if( empty($data['title_err']) && empty($data['body_err']) ) {
                    //Add Post to database
                    if($this->postModel->addPost($data)) {
                        flash('post_message', 'Post Added Successfully!');
                        redirect('posts');
                    } else {
                        die('Something went wrong!');
                    }
                } else {
                    //Load with errors
                    $this->view('posts/add', $data);
                }

            } else {
                $data = [
                    'title' => '',
                    'body' => '',
                    'title_err' => '',
                    'body_err' => ''
                ];
                $this->view('posts/add', $data);
            }
        }
        //Show Post
        public function show($id) {
            $post = $this->postModel->getPostById($id);
            $user = $this->userModel->getUserById($post->user_id);
            $data = [
                'post_title' => $post->title,
                'post_body' => $post->body,
                'post_id' => $post->id,
                'post_user_id' => $post->user_id,
                'post_created_at' => $post->created_at,
                'user_name' => $user->name
            ];
            $this->view('posts/show', $data);
        }
        //Edit Post
        public function edit($postId) {
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                //SANITIZE POST ARRAY
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                $data = [
                    'id'    => $postId,
                    'title' => trim($_POST['title']),
                    'body' => trim($_POST['body']),
                    'user_id' => $_SESSION['user_id'],
                    'title_err' => '',
                    'body_err' => ''
                ];
                //Validate Title Data
                if(empty($data['title'])) {
                    $data['title_err'] = 'Please enter title.';
                }
                //Validate Body Data
                if(empty($data['body'])) {
                    $data['body_err'] = 'Please enter body.';
                }
                //check if errors are empty
                if( empty($data['title_err']) && empty($data['body_err']) ) {
                    //Adding Updated Post to database
                    if($this->postModel->updatePost($data)) {
                        flash('post_message', 'Post Updated Successfully!');
                        redirect('posts');
                    } else {
                        die('Something went wrong!');
                    }
                } else {
                    //Load with errors
                    $this->view('posts/edit', $data);
                }

            } else {
                $post = $this->postModel->getPostById($postId);
                //Check if user has access to edit
                if($post->user_id != $_SESSION['user_id']) {
                    redirect('posts');
                }
                $data = [
                    'id' => $postId,
                    'title' => $post->title,
                    'body' => $post->body,
                    'user_id' => $post->user_id,
                    'title_err' => '',
                    'body_err' => ''
                ];
                $this->view('posts/edit', $data);
            }
        }
        //Delete Post
        public function delete($postId) {
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                
                //getting existing post from model
                $post = $this->postModel->getPostById($postId);
                //Check if user has access to delete
                if($post->user_id != $_SESSION['user_id']) {
                    redirect('posts');
                }

                if($this->postModel->deletePost($postId)) {
                    flash('post_message', 'Post Successfully Deleted!!');
                    redirect('posts');
                } else {
                    die('Something went wrong!!');
                }

            } else {
                redirect('posts');
            }
        }
    }