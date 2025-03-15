<?php
namespace App\Controller;

use App\Controller\AppController;

class TasksController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
        $this->response = $this->response->withType('application/json');
    }

    public function index()
    {
        $tasks = $this->Tasks->find()->all();
        $this->set(compact('tasks'));
        $this->viewBuilder()->setOption('serialize', ['tasks']);
    }

    public function view($id = null)
    {
        $task = $this->Tasks->get($id);
        $this->set(compact('task'));
        $this->viewBuilder()->setOption('serialize', ['task']);
    }

    public function add()
    {
        $this->request->allowMethod(['post']);
        $task = $this->Tasks->newEntity($this->request->getData());
        if ($this->Tasks->save($task)) {
            $message = 'Task created';
        } else {
            $message = 'Error creating task';
        }
        $this->set(compact('task', 'message'));
        $this->viewBuilder()->setOption('serialize', ['task', 'message']);
    }

    public function edit($id = null)
    {
        $this->request->allowMethod(['put']);
        $task = $this->Tasks->get($id);
        $task = $this->Tasks->patchEntity($task, $this->request->getData());
        if ($this->Tasks->save($task)) {
            $message = 'Task updated';
        } else {
            $message = 'Error updating task';
        }
        $this->set(compact('task', 'message'));
        $this->viewBuilder()->setOption('serialize', ['task', 'message']);
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['delete']);
        $task = $this->Tasks->get($id);
        $message = $this->Tasks->delete($task) ? 'Task deleted' : 'Error deleting task';
        $this->set(compact('message'));
        $this->viewBuilder()->setOption('serialize', ['message']);
    }
}