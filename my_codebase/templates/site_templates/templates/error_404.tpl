{include file="page_header.tpl" pageTitle=$pageTitle}

<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
{include file="info_browser_outdated.tpl"}

<section class="background-pattern-series-four">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div lang="en" class="error-mesg">
                    <span class="error-mesg-title"><i>404</i></span>
                    <h2><strong>The requested page was not found!</strong></h2>
                    <p>The requested URI was not found on this web-server. That’s all we know.<br/>
If you fiddled with the URL you know perfectly well why this error happend,<br/>
 so do not be surprised, if that is the case. ;-)
                    </p>
                    <a class="btn btn-primary btn-lg btn-go-back" href="/pages/">Back home</a>
                </div>
            </div>
        </div><!-- row -->
</section>
    </div><!-- container -->

{if $smarty.const.DEBUG}
<div>
<p>Template: <i><b>{nocache}{$smarty.template}{/nocache}</b></i></p>
</div>
{/if}
{include file="page_footer.tpl"}
