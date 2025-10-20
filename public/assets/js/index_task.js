function TaskViewModel(task) {
  let self = this;
  self.id = task.id;
  self.title = task.title;
  self.category_id = task.category_id;
  self.category_color_code = task.category_color_code;
  self.is_completed = ko.observable(task.is_completed == 1);
  self.editUrl = '/tasks/edit/' + self.id;
}

function CategoryButtonViewModel(category, colorCode) {
  let self = this;
  self.id = category.id;
  self.name = category.name;
  self.shortName = category.short_name;
  self.colorCode = colorCode || '#cccccc'; // デフォルト色
  self.isSelected = ko.observable(false);

  self.toggle = function() {
    self.isSelected(!self.isSelected());
  };
}

function AppViewModel(initialData) {
  let self = this;
  
  const allTasksRaw = initialData.todo_tasks.concat(initialData.done_tasks);
  self.allTasks = ko.observableArray(allTasksRaw.map(task => new TaskViewModel(task)));

  const categoryColorMap = allTasksRaw.reduce((map, task) => {
    if (task.category_id && task.category_color_code) {
      map[task.category_id] = task.category_color_code;
    }
    return map;
  }, {});

  self.categoryButtons = ko.observableArray(
    initialData.categories.map(cat => new CategoryButtonViewModel(cat, categoryColorMap[cat.id]))
  );

  self.chunkedCategoryButtons = ko.computed(function() {
    const buttons = self.categoryButtons();
    const chunkSize = 5;
    const result = [];
    for (let i = 0; i < buttons.length; i += chunkSize) {
      result.push(buttons.slice(i, i + chunkSize));
    }
    return result;
  });

  self.selectedCategoryIds = ko.computed(function() {
    return self.categoryButtons()
      .filter(button => button.isSelected())
      .map(button => String(button.id));
  });

  self.isCategoryFilterVisible = ko.observable(false);

  self.toggleCategoryFilter = function() {
    self.isCategoryFilterVisible(!self.isCategoryFilterVisible());
  };

  self.todoTasks = ko.computed(function() {
    const selectedIds = self.selectedCategoryIds();
    return self.allTasks().filter(function(task) {
      const isTodo = !task.is_completed();
      if (selectedIds.length === 0) {
        return isTodo;
      }
      const isInCategory = selectedIds.includes(String(task.category_id));
      return isTodo && isInCategory;
    });
  });

  self.doneTasks = ko.computed(function() {
    const selectedIds = self.selectedCategoryIds();
    return self.allTasks().filter(function(task) {
      const isDone = task.is_completed();
      if (selectedIds.length === 0) {
        return isDone;
      }
      const isInCategory = selectedIds.includes(String(task.category_id));
      return isDone && isInCategory;
    });
  });

  self.toggleTask = function(task) {
    const originalStatus = task.is_completed();
    task.is_completed(!originalStatus);

    const csrfToken = document.querySelector('input[name="fuel_csrf_token"]').value;

    fetch('/api/tasks/toggle/' + task.id, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json'
      },
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
      if (data.new_csrf_token) {
        document.querySelector('input[name="fuel_csrf_token"]').value = data.new_csrf_token;
      }
    })
    .catch(function(error) {
      console.error('Error toggling task:', error);
      task.is_completed(originalStatus);
      alert('エラーが発生しました: ' + error.message);
    });
  };
}

document.addEventListener('DOMContentLoaded', function () {
  const taskAppElement = document.getElementById('task-app');
  if (taskAppElement) {
    const initialData = {
      todo_tasks: JSON.parse(taskAppElement.getAttribute('data-todo-tasks') || '[]'),
      done_tasks: JSON.parse(taskAppElement.getAttribute('data-done-tasks') || '[]'),
      categories: JSON.parse(taskAppElement.getAttribute('data-categories') || '[]')
    };
    ko.applyBindings(new AppViewModel(initialData), taskAppElement);
  }
});