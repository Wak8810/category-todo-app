function CategoryViewModel(category) {
  let self = this;

  self.id = category.id;
  self.name = ko.observable(category.name);
  self.colorCode = ko.observable(category.color_code);

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

  self.updateUrl = ko.pureComputed(function() {
    return '/categories/update/' + self.id;
  });
}

document.addEventListener('DOMContentLoaded', function() {
  const view = document.getElementById('edit-category-view');
  if (view) {
    const categoryDataString = view.getAttribute('data-category');
    if (categoryDataString) {
      try {
        const categoryData = JSON.parse(categoryDataString);
        ko.applyBindings(new CategoryViewModel(categoryData), view);
      } catch (e) {
        console.error("Failed to parse category data:", e);
      }
    }
  }
});
