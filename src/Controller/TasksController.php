<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\MethodNotAllowedException;

class TasksController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    public function index()
    {
        $tasks = $this->Tasks->find()->all();
        $this->set([
            'tasks' => $tasks,
            '_serialize' => ['tasks']
        ]);
    }

    public function view($id = null)
    {
        if (!$id) {
            throw new BadRequestException('Task ID is required.');
        }

        $task = $this->Tasks->findById($id)->first();
        if (!$task) {
            throw new NotFoundException('Task not found.');
        }

        $this->set([
            'task' => $task,
            '_serialize' => ['task']
        ]);
    }

    public function add()
    {
        $this->request->allowMethod(['post']);

        $task = $this->Tasks->newEntity($this->request->getData());
        if ($this->Tasks->save($task)) {
            $message = 'Task created successfully';
        } else {
            $message = 'Error creating task';
        }

        $this->set([
            'task' => $task,
            'message' => $message,
            '_serialize' => ['task', 'message']
        ]);
    }

    public function edit($id = null)
    {
        $this->request->allowMethod(['put']);

        if (!$id) {
            throw new BadRequestException('Task ID is required.');
        }

        $task = $this->Tasks->get($id);
        if (!$task) {
            throw new NotFoundException('Task not found.');
        }

        $task = $this->Tasks->patchEntity($task, $this->request->getData());
        if ($this->Tasks->save($task)) {
            $message = 'Task updated successfully';
        } else {
            $message = 'Error updating task';
        }

        $this->set([
            'task' => $task,
            'message' => $message,
            '_serialize' => ['task', 'message']
        ]);
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['delete']);

        if (!$id) {
            throw new BadRequestException('Task ID is required.');
        }

        $task = $this->Tasks->get($id);
        if (!$task) {
            throw new NotFoundException('Task not found.');
        }

        if ($this->Tasks->delete($task)) {
            $message = 'Task deleted successfully';
        } else {
            $message = 'Error deleting task';
        }

        $this->set([
            'message' => $message,
            '_serialize' => ['message']
        ]);
    }
}
