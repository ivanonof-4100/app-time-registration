{include file="page_header.tpl" pageTitle=$pageTitle}
{assign var=errorTitle value=$errorTitle|default:"Script execution halted!"}
{assign var=errorMessage value=$errorMessage|default:"No error-message."}
{assign var=errorColor value=$errorColor|default:"red"}

<!--    <div class="main wrapper clearfix"> -->
<section class="background-pattern-series-four">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div lang="en" class="error-mesg text-center">
                    <span class="error-mesg-title" style="font-size:6.5em;"><i>500</i></span>
                    <h2><strong>{$errorTitle}</strong></h2>
                    <p>We are of cause sorry for the inconvenience that this error has caused you.<br/>
                        The internal-error has been logged and our <strong>web-master</strong> will fix the bug as soon as possible.</p>
                </div>
            </div><!-- col -->
        </div><!-- row -->

        <div class="row">
          <div class="col-md-12">
          <p class="text-center"><a class="btn btn-primary btn-lg btn-go-back" href="/">Back Home</a></p>
          </div><!-- col -->
        </div><!-- row -->

        <div class="row">
          <div class="col-md-12">
            <div class="bs-callout bs-callout-error overlay-color-white">
              <div lang="en" class="error-mesg">
              <h3 class="text-center"><i class="fa fa-info-circle" aria-hidden="true"></i></h3>
<p class="text-start"><span style="color:{$errorColor};">{nocache}{$errorMessage}{/nocache}</span></p>
              </div>
            </div>
          </div><!-- col -->
        </div><!-- row -->
             </div>
</section>
{if $smarty.const.DEBUG}
<p>Template: <i><b>{nocache}{$smarty.template}{/nocache}</b></i></p>
{/if}
    </div><!-- container -->

{include file="page_footer.tpl"}