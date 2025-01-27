{include file="page_header.tpl" pageDomainTitle=$pageDomainTitle}

<div class="container">
  <div class="row">
    <div class="col-md-12">
{include file="info_browser_outdated.tpl"}

    <p>Her kan du ligge en besked til mig, hvis du er interesseret i min professionelle profil.<br/>
    Beskriv hvad det handler om og hvorfor du kontakter mig, så vil jeg vende tilbage til dig.</p>
<section class="col-md-8 clearfix">
  <form name="contact_form" enctype="application/x-www-form-urlencoded" method="post" action="/pages/contact.php"  class="form-horizontal contact-form clearfix">
      <label for="contact_person_name">Navn:</label>
      <input type="text" id="name_contact_name" name="contact_person_name" tabindex="1" class="form-control" placeholder="Navn" autofocus="autofocus" required="required" />

      <label for="contact_person_email">E-mail:</label>
      <input type="email" id="contact_person_email" name="contact_person_email" tabindex="2" class="form-control" placeholder="E-mail" required="required" />

      <label for="contact_person_phone">Telefon:</label>
      <input type="text" id="contact_person_phone" name="contact_person_phone" tabindex="3" class="form-control" placeholder="Telefon" />

      <label for="contact_message">Besked:</label>
      <textarea id="contact_message" name="contact_message" tabindex="4" class="form-control" rows="7" placeholder="Din besked ..." required="required"></textarea>

      <label for="contact_antispam">Anti-spam:</label>
<div class="row">
  <div class="col-sm-12 col-md-7">
    <div role="group" class="input-group" style="margin-bottom:5px;">
      <input name="contact_antispam" type="text" tabindex="5" placeholder="Gengiv anti-spam koden ..." class="form-control" aria-label="Text input" />
      <div class="input-group-btn">
        <button type="button" class="btn btn-default btn-help" data-container="body" data-toggle="popover" data-placement="top" data-content="Anti-spam koden kan normalt kun tydes af mennesker og dermed beviser du, at du er et menneske og ikke en søge-robot.">
        </button>
      </div><!-- input-group-btn -->
    </div><!-- input-group -->
  </div><!-- col -->
  
  <div class="col-sm-12 col-md-5">
    <div role="group" class="input-group">
  {nocache}<img id="antispam_image" src="/images/antispam_image_v2.php" style="height:34px;width:105px;display:inline-block;"/>{/nocache}
  <button type="button" tabindex="7" class="btn btn-default btn-refresh" onclick="reloadAntispamImage();">Genopfrisk</button>
    </div><!-- input-group-btn -->
  </div><!-- col -->
</div><!-- row -->

    <input name="btn_submit" type="submit" tabindex="8" data-toggle="modal" data-target="#alertModal" value="Send forespørgsel" class="btn btn-block btn-primary btn-lg btn-send" style="margin-top:15px;"/>
  </form>
</section><!-- col -->

<section class="col-lg-4">
  <p style="margin-left:20px;margin-right:20px;">
    <i class="fa fa-5x fa-paper-plane-o" aria-hidden="true" style="color:#585858;font-size:7em;"></i>
<!--
  <img src="/images/web.jpg" class="img-responsive" style="width:259px;height:194px;" />
-->
  </p>
  <p>
  <span><b>BEMÆRK!</b><br/>
  <strong>Denne side er under udvikling!</strong>
  <br/>
   Back-enden til denne side er ikke helt implementeret endnu.
  <br/>I mellemtiden kan du eksempelvis kontakte mig via LinkedIn.com
  </span>
  </p>
</section><!-- col -->
  </div><!-- row -->
</div><!-- container -->

     </div><!-- col -->
  </div><!-- row -->
</div><!-- container -->

<script type="text/javascript" src="/js/anti-spam.js"></script>
{if $smarty.const.DEBUG}
<div>
<p>Template: <i><b>{$smarty.template}</b></i></p>
</div>
{/if}
{include file="page_footer.tpl"}