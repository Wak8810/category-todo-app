function CategoryIndexViewModel(form) {
  let self = this;

  const initialName = form.getAttribute('data-initial-name') || '';
  const initialColorCode = form.getAttribute('data-initial-color-code') || '#000000';

  self.name = ko.observable(initialName);
  self.colorCode = ko.observable(initialColorCode);

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
    if (self.colorCode().length > 0 && !colorRegex.test(self.colorCode())) {
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

document.addEventListener('DOMContentLoaded', function() {
  let createForm = document.getElementById('category-create-form');
  if(createForm) {
    ko.applyBindings(new CategoryIndexViewModel(createForm), createForm);
  }
});
