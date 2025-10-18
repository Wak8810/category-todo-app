function TaskViewModel(task) {
  this.id = task.id;
  this.title = task.title;
  this.category_color_code = task.category_color_code;
  
  this.editUrl = '/tasks/edit/' + this.id;
  this.toggleUrl = '/tasks/toggle/' + this.id;
}

function TaskListViewModel(tasks) {
  var self = this;
  self.tasks = ko.observableArray(tasks.map(function(task) {
    return new TaskViewModel(task);
  }));
}

document.addEventListener('DOMContentLoaded', function () {
  const todoListElement = document.getElementById('todo-list');
  if (todoListElement) {
    const todoTasksData = JSON.parse(todoListElement.getAttribute('data-tasks') || '[]');
    ko.applyBindings(new TaskListViewModel(todoTasksData), todoListElement);
  }

  const doneListElement = document.getElementById('done-list');
  if (doneListElement) {
    const doneTasksData = JSON.parse(doneListElement.getAttribute('data-tasks') || '[]');
    ko.applyBindings(new TaskListViewModel(doneTasksData), doneListElement);
  }
});
