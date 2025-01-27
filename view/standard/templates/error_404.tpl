<section>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12 col-offset-2">
      <div lang="en" class="error-mesg text-center">
         <span class="error-mesg-title" style="font-size:5.5em;line-height:1.2em;"><i>404</i></span>
         <h2><strong>The requested page was not found!</strong></h2>
         <p>The requested URI was not found on this web-server. That’s all we know.<br/>
If you fiddled with the URL you know perfectly well why this error happend,<br/>
so do not be surprised, if that is the case. ;-)
         </p>
      </div>
    </div><!-- col -->
  </div><!-- row -->

  <div class="row">
    <div class="col-md-12">
       <p class="text-center">
         <a class="btn btn-primary btn-lg btn-go-back" href="/">Back Home</a>
       </p>
    </div><!-- col -->
  </div><!-- row -->

{if $smarty.const.DEBUG}
<p>Template: <i><b>{nocache}{$smarty.template}{/nocache}</b></i></p>
{/if}
</div><!-- container -->
</section>