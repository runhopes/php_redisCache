<?php
class redisConnectionCache {
    private $redisConnection;

    public function __construct() {
        $this->redisConnection = new redisConnection();
        $this->redisConnection->connect('127.0.0.1', 6379);
    }

    public function addUser($id, $name, $email) {
        $this->redisConnection->hMSet("user:$id", [
            'name' => $name,
            'email' => $email
        ]);
    }

    public function getUser($id) {
        return $this->redisConnection->hGetAll("user:$id");
    }

    public function updateUser($id, $name, $email) {
        $this->redisConnection->hMSet("user:$id", [
            'name' => $name,
            'email' => $email
        ]);
    }

    public function deleteUser($id) {
        $this->redisConnection->delete("user:$id");
    }

    public function getAllUsers() {
        $keys = $this->redisConnection->keys("user:*");
        $users = [];
        foreach ($keys as $key) {
            $users[$key] = $this->redisConnection->hGetAll($key);
        }
        return $users;
    }
}
?>
