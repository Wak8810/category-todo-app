function AppViewModel() {
  let self = this;
  let form = document.getElementById('register-form');
  let initialUsername = form.getAttribute('data-initial-username') || '';
  let initialEmail = form.getAttribute('data-initial-email') || '';

  self.username = ko.observable(initialUsername);
  self.email = ko.observable(initialEmail);
  self.password = ko.observable('');
  self.passwordConfirm = ko.observable('');

  self.usernameError = ko.computed(function() {
    const allowedCharsRegex = /^[\u3041-\u3093\u30A1-\u30F6\u4E00-\u9FA5a-zA-Z0-9_-\u30FC]+$/u;
    if (self.username().length > 0 && !allowedCharsRegex.test(self.username())) {
      return 'ユーザー名に使用できない文字が含まれています。日本語、英数字、ハイフン、アンダースコアのみ使用できます。';
    }
    if (self.username().length > 0 && self.username().length < 3) {
      return 'ユーザー名は3文字以上で入力してください。';
    }
    return null;
  });

  self.emailError = ko.computed(function() {
    let emailRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
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
  let registerForm = document.getElementById('register-form');
  if(registerForm) {
    ko.applyBindings(new AppViewModel(), registerForm);
  }
});