<?php
    class Post {
        private $db;
        public function __construct() {
            $this->db = new Database();
        }
        //get Posts
        public function getPosts() {
            $sql = "SELECT *, posts.id as postId, users.id as userId, posts.created_at as postCreatedAt, users.created_at as userCreatedAt FROM posts INNER JOIN users ON posts.user_id = users.id ORDER BY posts.created_at DESC";
            $this->db->query($sql);
            $results = $this->db->resultSet();
            return $results;
        }
        //add Posts
        public function addPost($data) {
            $sql = 'INSERT INTO posts(title, body, user_id) VALUES(:title, :body, :user_id)';
            $this->db->query($sql);
            //bind parameters
            $this->db->bind(':title', $data['title']);
            $this->db->bind(':body', $data['body']);
            $this->db->bind(':user_id', $data['user_id']);
            //execute
            if($this->db->execute()) {
                return true;
            } else {
                return false;
            }
        }
        //Get a Post By its Id
        public function getPostById($id) {
            $sql = 'SELECT * FROM posts WHERE id = :id';
            $this->db->query($sql);
            $this->db->bind(':id', $id);
            $post = $this->db->single();
            return $post;
        }
        //Update post
        public function updatePost($data) {
            $sql = 'UPDATE posts SET title = :title, body = :body WHERE id = :id';
            $this->db->query($sql);
            //bind parameters
            $this->db->bind(':title', $data['title']);
            $this->db->bind(':body', $data['body']);
            $this->db->bind(':id', $data['id']);
            //execute
            if($this->db->execute()) {
                return true;
            } else {
                return false;
            }
        }
        //Delete Post
        public function deletePost($postId) {
            $sql = 'DELETE FROM posts WHERE id = :id';
            $this->db->query($sql);
            //bind parameters
            $this->db->bind(':id', $postId);
            //execute
            if($this->db->execute()) {
                return true;
            } else {
                return false;
            }
        }
    }