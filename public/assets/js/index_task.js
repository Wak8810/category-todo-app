function TaskViewModel(task) {
  let self = this;
  self.id = task.id;
  self.title = task.title;
  self.category_color_code = task.category_color_code;

  // 緩い比較で、1と'1'を見て、チェックを管理
  self.is_completed = ko.observable(task.is_completed == 1);
  
  self.editUrl = '/tasks/edit/' + self.id;
}

function AppViewModel(initialData) {
  let self = this;
  
  let mappedTodoTasks = initialData.todo_tasks.map(function(task) { return new TaskViewModel(task, self); });
  let mappedDoneTasks = initialData.done_tasks.map(function(task) { return new TaskViewModel(task, self); });

  self.todoTasks = ko.observableArray(mappedTodoTasks);
  self.doneTasks = ko.observableArray(mappedDoneTasks);

  self.toggleTask = function(task) {
    let originalStatus = task.is_completed();

    if (self.todoTasks.indexOf(task) > -1) {
      self.todoTasks.remove(task);
      self.doneTasks.unshift(task);
      task.is_completed(true);
    } else {
      self.doneTasks.remove(task);
      self.todoTasks.unshift(task);
      task.is_completed(false);
    }

    // API通信とエラー時の差し戻し処理
    const csrfToken = document.querySelector('input[name="fuel_csrf_token"]').value;

    fetch('/api/tasks/toggle/' + task.id, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json'
      },
      //CSRF対策
      body: 'fuel_csrf_token=' + encodeURIComponent(csrfToken)
    })
    .then(function(response) {
      if (!response.ok) {
        throw new Error('Network response was not ok.');
      }
      return response.json();
    })
    .then(function(data) {
      if (data.status !== 'ok') {
        throw new Error(data.message || 'Server error');
      }
    })
    .catch(function(error) {
      console.error('Error toggling task:', error);
      // Doneに移動後
      if (task.is_completed()) { 
        self.doneTasks.remove(task);
        self.todoTasks.unshift(task);
      // Todoに移動後
      } else { 
        self.todoTasks.remove(task);
        self.doneTasks.unshift(task);
      }
      task.is_completed(originalStatus);
      alert('エラーが発生しました。もう一度お試しください。');
    });

    return true;
  };
}

document.addEventListener('DOMContentLoaded', function () {
  const taskAppElement = document.getElementById('task-app');
  if (taskAppElement) {
    const initialData = {
      todo_tasks: JSON.parse(taskAppElement.getAttribute('data-todo-tasks') || '[]'),
      done_tasks: JSON.parse(taskAppElement.getAttribute('data-done-tasks') || '[]')
    };
    ko.applyBindings(new AppViewModel(initialData), taskAppElement);
  }
});