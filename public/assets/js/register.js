function AppViewModel() {
  var self = this;
  var form = document.getElementById('register-form');
  var initialUsername = form.getAttribute('data-initial-username') || '';
  var initialEmail = form.getAttribute('data-initial-email') || '';

  self.username = ko.observable(initialUsername);
  self.email = ko.observable(initialEmail);
  self.password = ko.observable('');
  self.passwordConfirm = ko.observable('');

  self.usernameError = ko.computed(function() {
    if (self.username().length > 0 && self.username().length < 3) {
      return 'ユーザー名は3文字以上で入力してください。';
    }
    return null;
  });

  self.emailError = ko.computed(function() {
    var emailRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
    if (self.email().length > 0 && !emailRegex.test(self.email())) {
      return '有効なメールアドレスを入力してください。';
    }
    return null;
  });

  self.passwordError = ko.computed(function() {
    if (self.password().length > 0 && self.password().length < 8) {
      return 'パスワードは8文字以上で入力してください。';
    }
    return null;
  });

  self.passwordConfirmError = ko.computed(function() {
    if (self.passwordConfirm().length > 0 && self.passwordConfirm() !== self.password()) {
      return 'パスワードが一致しません。';
    }
    return null;
  });

  self.isFormValid = ko.computed(function() {
    if (!self.username() || !self.email() || !self.password() || !self.passwordConfirm()) {
      return false;
    }
    return !self.usernameError() && !self.emailError() && !self.passwordError() && !self.passwordConfirmError();
  });
}

document.addEventListener('DOMContentLoaded', function() {
  var registerForm = document.getElementById('register-form');
  if(registerForm) {
    ko.applyBindings(new AppViewModel(), registerForm);
  }
});