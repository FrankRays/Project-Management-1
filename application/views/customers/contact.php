<div class="page-header">
  <h2>Contacting <?php echo $user['FirstName'] . ' ' . $user['LastName'] ?> <small>Remember: All a customer needs is love</small></h2>
</div>

<form action="/customers/send_email/<?php echo $user['uid'] ?>" method="post">
  <fieldset>
    <div class="clearfix">
      <label for="contact-subject">Subject</label>
      <div class="input">
        <input type="text" id="contact-subject" name="subject" value="TFM Inquiry" />
      </div>
    </div>
    <div class="clearfix">
      <label for="contact-message">Message</label>
      <div class="input">
        <textarea id="contact-message" cols="50" rows="10" class="xxlarge" name="message">Hi <?php echo $user['FirstName'] . ', '?></textarea>
      </div>
    </div>
  </fieldset>
  <div class="actions">
    <input type="submit" class="btn primary" value="Send" />
    <a href="/customers/" class="btn">Cancel</a>
  </div>
</form>
