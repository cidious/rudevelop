<div class="col-md-6 col-md-offset-3 col-sm-12">
 <div class="modal-header">
  <h3>Регистрация пользователя</h3>
 </div>
 <div class="modal-body">
  <form method="post" id="register-form">
   {{ hidden_field("id":"token", "name":security.getTokenKey(), "value": security.getToken()) }}
   <div class="form-group has-feedback" id="login-group">
    <label class="control-label" for="login">логин</label>
    {{ text_field("login", "class": "form-control", "title":"login", "required":"required") }}
    <span id="login-icon" class="glyphicon form-control-feedback" aria-hidden="true"></span>
    <span id="login-help" class="help-block"></span>
   </div>

   <div class="form-group has-feedback" id="password1-group">
    <label class="control-label" for="password1">пароль</label>
    {{ password_field("password1", "class": "form-control", "title":"password", "required":"required") }}
    <span id="password1-icon" class="glyphicon form-control-feedback" aria-hidden="true"></span>
    <span id="password1-help" class="help-block"></span>
   </div>

   <div class="form-group has-feedback" id="password2-group">
    <label class="control-label" for="password2">подтверждение пароля</label>
    {{ password_field("password2", "class": "form-control", "title":"password", "required":"required") }}
    <span id="password2-icon" class="glyphicon form-control-feedback" aria-hidden="true"></span>
    <span id="password2-help" class="help-block"></span>
    <span class="help-block">ваш пароль должен содержать цифры, буквы, завязку, развитие, кульминацию и неожиданный финал</span>
   </div>

   <div class="form-group has-feedback" id="email-group">
    <label class="control-label" for="email">почта</label>
    {{ email_field("email", "class": "form-control", "title":"email", "required":"required") }}
    <span id="email-icon" class="glyphicon form-control-feedback" aria-hidden="true"></span>
    <span id="email-help" class="help-block"></span>
   </div>

   <div class="checkbox">
    <label class="control-label">
     {{ check_field('remember') }} Запомнить
    </label>
   </div>

   {{ submit_button("Зарегистрировать", "class": "btn btn-default") }}
  </form>
 </div>
</div>
