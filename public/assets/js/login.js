function AppViewModel() {
  var self = this;
  var form = document.getElementById('login-form');
  var initialEmail = form.getAttribute('data-initial-email') || '';

  self.email = ko.observable(initialEmail);
  self.password = ko.observable('');

  self.emailError = ko.computed(function () {
    var emailRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
    if (self.email().length > 0 && !emailRegex.test(self.email())) {
      return '有効なメールアドレスを入力してください。';
    }
    return null;
  });

  self.isFormValid = ko.computed(function () {
    if (!self.email() || !self.password()) {
      return false;
    }
    return !self.emailError();
  });
}

document.addEventListener('DOMContentLoaded', function() {
  var loginForm = document.getElementById('login-form');
  if (loginForm) {
    ko.applyBindings(new AppViewModel(), loginForm);
  }
});