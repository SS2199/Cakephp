import { Component, OnInit } from '@angular/core';
import { TaskService } from './services/task.service';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent implements OnInit {
  tasks: any[] = [];
  isEditing = false;
  task = { id: null as number | null, title: '', description: '' };

  constructor(private taskService: TaskService) {}

  ngOnInit(): void {
    this.getTasks();
  }

  // Fetch all tasks
  getTasks(): void {
    this.taskService.getTasks().subscribe(
      (response) => {
        this.tasks = response.tasks;
      },
      (error) => {
        console.error('Error fetching tasks:', error);
      }
    );
  }

  // Add or update task
  onSubmit(): void {
    if (this.isEditing) {
      this.taskService.updateTask(this.task.id!, this.task).subscribe(() => {
        this.getTasks(); // Refresh list
        this.resetForm();
      });
    } else {
      this.taskService.addTask(this.task).subscribe(() => {
        this.getTasks(); // Refresh list
        this.resetForm();
      });
    }
  }

  // Edit existing task
  editTask(task: any): void {
    this.task = { ...task };
    this.isEditing = true;
  }

  // Delete task
  deleteTask(id: number): void {
    this.taskService.deleteTask(id).subscribe(() => {
      this.getTasks(); // Refresh list
    });
  }

  // Reset form
  resetForm(): void {
    this.task = { id: null, title: '', description: '' };
    this.isEditing = false;
  }
}
