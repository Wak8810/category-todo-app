function CategoryViewModel(category) {
  let self = this;
  self.id = category.id;
  self.name = category.name;
  self.displayName = category.display_name;
  self.colorCode = category.color_code;
  self.editUrl = '/categories/edit/' + self.id;
}

function AppViewModel(initialData) {
  let self = this;

  self.categories = ko.observableArray(initialData.categories.map(cat => new CategoryViewModel(cat)));

  self.name = ko.observable(initialData.initialName || '');
  self.colorCode = ko.observable(initialData.initialColorCode || '#000000');

  self.nameError = ko.computed(function() {
    if (self.name().length === 0) {
      return null;
    }
    if (self.name().length > 255) {
      return 'カテゴリー名は255文字以内で入力してください。';
    }
    return null;
  });

  self.colorCodeError = ko.computed(function() {
    let colorRegex = /^#[0-9a-fA-F]{6}$/;
    if (!colorRegex.test(self.colorCode())) {
      return 'カラーコードの形式が正しくありません。';
    }
    return null;
  });

  self.isFormValid = ko.computed(function() {
    if (!self.name() || !self.colorCode()) {
      return false;
    }
    return !self.nameError() && !self.colorCodeError();
  });
}

document.addEventListener('DOMContentLoaded', function () {
  const container = document.getElementById('category-management-container');
  if (container) {
    const categoryCreateForm = document.getElementById('category-create-form');
    const initialData = {
      categories: JSON.parse(container.getAttribute('data-categories') || '[]'),
      initialName: categoryCreateForm.getAttribute('data-initial-name') || '',
      initialColorCode: categoryCreateForm.getAttribute('data-initial-color-code') || '#000000',
    };
    ko.applyBindings(new AppViewModel(initialData), container);
  }
});