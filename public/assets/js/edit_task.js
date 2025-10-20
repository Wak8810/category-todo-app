function TaskViewModel(task) {
  const self = this;

  self.id = task.id;
  self.title = ko.observable(task.title);
  self.category_id = ko.observable(task.category_id);

  self.titleError = ko.computed(function() {
    if (self.title().length === 0) {
      return 'タスク名は必須です。';
    }
    if (self.title().length > 255) {
      return 'タスク名は255文字以内で入力してください。';
    }
    return null;
  });

  self.isFormValid = ko.computed(function() {
    return !self.titleError();
  });

  self.updateUrl = '/tasks/update/' + self.id;
}

document.addEventListener('DOMContentLoaded', function() {
  const view = document.getElementById('edit-task-view');
  if (!view) {
    return;
  }
  const taskDataString = view.getAttribute('data-task');
  if (!taskDataString) {
    return;
  }
  try {
    const taskData = JSON.parse(taskDataString);
    ko.applyBindings(new TaskViewModel(taskData), view);
  } catch (e) {
    console.error("Failed to parse task data:", e);
  }
});