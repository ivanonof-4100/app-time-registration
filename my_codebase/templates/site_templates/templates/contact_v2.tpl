<form action="assets/php/demo-contacts-process.php" method="post" id="sky-form3" class="sky-form" novalidate="novalidate">
  <header>Contacts form</header>
  <fieldset>
    <div class="row">
      <section class="col col-6">
        <label class="label">Name</label>
        <label class="input">
        <i class="icon-append fa fa-user"></i>
        <input type="text" name="name" id="name">
        </label>
      </section>
      <section class="col col-6">
        <label class="label">E-mail</label>
        <label class="input">
        <i class="icon-append fa fa-envelope-o"></i>
        <input type="email" name="email" id="email">
        </label>
      </section>
    </div>
    <section>
      <label class="label">Subject</label>
      <label class="input">
      <i class="icon-append fa fa-tag"></i>
      <input type="text" name="subject" id="subject">
      </label>
    </section>
    <section>
      <label class="label">Message</label>
      <label class="textarea">
      <i class="icon-append fa fa-comment"></i>
      <textarea rows="4" name="message" id="message"></textarea>
      </label>
    </section>
    <section>
      <label class="label">Enter characters below:</label>
      <label class="input input-captcha">
      <img src="assets/plugins/sky-forms/version-2.0.1/captcha/image.php?&lt;?php echo time(); ?&gt;" width="100" height="32" alt="Captcha image">
      <input type="text" maxlength="6" name="captcha" id="captcha">
      </label>
    </section>
    <section>
      <label class="checkbox"><input type="checkbox" name="copy"><i></i>Send a copy to my e-mail address</label>
    </section>
  </fieldset>
  <footer>
    <button type="submit" class="button">Send message</button>
  </footer>
  <div class="message">
    <i class="rounded-x fa fa-check"></i>
    <p>Your message was successfully sent!</p>
  </div>
</form>
