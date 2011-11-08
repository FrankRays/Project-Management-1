<div class="page-header">
  <h2>Reset User password</h2>
</div>

<div class="row">
  <div class="span6">
    <p class="block-message"><input type="button" id="new-user-password" class="btn info" value="Generate password" /></p>
    <p class="well block_message"><code id="new-password-area" class="alert-message">N/A</code></p>
  </div>

  <div class="span7">
    <form action="/customers/reset_password_handle" method="post">
      <fieldset>
        <div class="clearfix">
          <label for="reset-password-password">New Password</label>
          <div class="input">
            <input type="password" name="password" id="reset-password-password" />
          </div>
        </div>

        <div class="clearfix">
          <label for="reset-password-confirm">Confirm</label>
          <div class="input">
            <input type="password" name="confirm" id="reset-password-confirm" />
          </div>
        </div>
      </fieldset>

      <div class="actions">
        <input type="submit" value="Update password" class="btn primary" />
      </div>
    </form>
  </div>
</div>

