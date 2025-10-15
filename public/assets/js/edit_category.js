function CategoryViewModel(category) {
  let self = this;

  self.id = category.id;
  self.name = ko.observable(category.name);
  self.colorCode = ko.observable(category.color_code);

  self.nameError = ko.computed(function() {
    const allowedCharsRegex = /^[\u3041-\u3093\u30A1-\u30F6\u4E00-\u9FA5a-zA-Z0-9_-\u30FC]+$/u;
    if (self.name().length > 0 && !allowedCharsRegex.test(self.name())) {
      return 'カテゴリー名に使用できない文字が含まれています。日本語、英数字、ハイフン、アンダースコアのみ使用できます。';
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
