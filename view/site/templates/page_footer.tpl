{assign var="currentYear" value=$smarty.now|date_format:"%Y" nocache}

</section>

<!-- FOOTER -->
<footer class="footer page-footer">
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div>
        <p class="text-center">
           <span style="color:#333333;" class="fa fa-info-circle" aria-hidden="true"></span>&nbsp; <a href="#about_cookies">Cookies</a> | <a href="#powered_by">Powered by</a> | <a href="/pages/contact.php">Kontakt mig</a></p>
        <p class="text-center" style="color:#585858;padding-top:15px;padding-bottom:5px;"><i class="fa fa-3x fa-html5" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<i class="fa fa-3x fa-css3" aria-hidden="true"></i></p>
      </div>
    </div><!-- col -->
  </div><!--- row -->
</div><!-- container -->
</footer>

{if $smarty.const.DEBUG}
<div>
<p>Template: <i><b>{$smarty.template}</b></i></p>
</div>
{/if}

<!-- jQuery and JS bundle w/ Popper.js -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>
</html>