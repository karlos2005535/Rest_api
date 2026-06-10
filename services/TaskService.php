<?php
class TaskService {
    private $repository;
    private $redis;
    private $cacheKey = 'tasks_list_cache';

    public function __construct($repository, $redis) {
        $this->repository = $repository;
        $this->redis = $redis;
    }

    private function clearCache() {
        if ($this->redis) {
            $this->redis->del($this->cacheKey);
        }
    }

    public function getTasks() {
        if ($this->redis && $this->redis->exists($this->cacheKey)) {
            return json_decode($this->redis->get($this->cacheKey), true);
        }
        $tasks = $this->repository->getAllTasks();
        if ($this->redis) {
            $this->redis->setex($this->cacheKey, 300, json_encode($tasks));
        }
        return $tasks;
    }

    public function addTask($data) {
        if (!isset($data->title)) return false;
        $desc = isset($data->description) ? $data->description : '';
        $status = isset($data->status) ? $data->status : 'To Do';
        
        $result = $this->repository->createTask($data->title, $desc, $status);
        if ($result) $this->clearCache();
        return $result;
    }

    public function updateTask($id, $data) {
        if (!$id || !isset($data->title)) return false;
        $desc = isset($data->description) ? $data->description : '';
        $status = isset($data->status) ? $data->status : 'To Do';

        $result = $this->repository->updateTask($id, $data->title, $desc, $status);
        if ($result) $this->clearCache();
        return $result;
    }

    public function updateStatus($id, $data) {
        if (!$id || !isset($data->status)) return false;
        $result = $this->repository->updateStatus($id, $data->status);
        if ($result) $this->clearCache();
        return $result;
    }

    public function deleteTask($id) {
        if (!$id) return false;
        $result = $this->repository->deleteTask($id);
        if ($result) $this->clearCache();
        return $result;
    }
}
?>