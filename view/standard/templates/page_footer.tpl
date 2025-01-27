{assign var=currentYear value=$smarty.now|date_format:"%Y" nocache}
{assign var=pageLangIdent value=$pageLangIdent|default:"en"}

<footer class="footer page-footer">
<section class="section-footer-links fst-normal text-center">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 col-md-12 col-lg-12">
<a href="/{$pageLangIdent}/sitemap/" rel="canonical" title="Læs mere om dette site">Sitemap</a>
&nbsp;|&nbsp;
<a href="/{$pageLangIdent}/cookie-policy/" rel="canonical" title="Læs Cookie-politik">Cookie-politik</a>
&nbsp;|&nbsp;
<a href="/{$pageLangIdent}/privacy-policy/" rel="canonical" title="Læs Persondatapolitik">Persondatapolitik</a>
&nbsp;|&nbsp;
<a href="/{$pageLangIdent}/powered-by/" rel="canonical" title="Læs mere om dette site">Powered by</a>
      </div><!-- col -->
    </div><!-- row -->
  </div><!-- container -->
</section>

<section class="section-footer-content">
<div class="container">
  <div class="row">
    <div class="col-12">
<p class="text-center">
<span class="logo-text text-lowercase">{$smarty.const.SITE_DOMAIN_NAME|escape}</span>
</p>
<p class="text-center" lang="en">
Copyright &copy {$currentYear} <strong>Ivan Mark Andersen</strong>.</p>
<div class="social-links p-4">
 <p class="text-center font-italic">
  <i>Følg mig på <strong>Sociale medier</strong></i>
 </p>
 <p>
  <a href="https://www.linkedin.com/in/ivan-mark-andersen-1451538b" target="_blank" rel="external noopener" title="LinkedIn">
    <img loading="lazy" src="/bootstrap/bootstrap-icons/icons/linkedin.svg" role="img" class="icon icon-size-large" aria-hidden="true"/></a>
  <a href="https://github.com/ivanonof-4100" target="_blank" rel="external noopener" title="GitHub">
    <img loading="lazy" src="/bootstrap/bootstrap-icons/icons/github.svg" role="img" class="icon icon-size-large" aria-hidden="true"/></a>
 </p>
</div>

<div class="scroll-up">
<a id="btn_scroll_up" role="button" class="scroll-up page-scroll goto-top-link" title="Tilbage til toppen">
  <svg xmlns="http://www.w3.org/2000/svg" role="img" class="icon icon-size-small" aria-hidden="true" focusable="true" viewBox="0 0 512 512">
 <g>
    <path d="M256,5.333C114.88,5.333,0,117.76,0,256s114.88,250.667,256,250.667S512,394.24,512,256S397.12,5.333,256,5.333z
           M256,485.333C126.613,485.333,21.333,382.4,21.333,256S126.613,26.667,256,26.667S490.667,129.493,490.667,256
          S385.387,485.333,256,485.333z"/>
    <path d="M264.32,147.947c-4.053-4.587-10.987-5.013-15.573-0.96c-0.32,0.32-0.64,0.64-0.96,0.96L109.12,318.613
          c-3.733,4.587-2.987,11.307,1.6,15.04s11.307,2.987,15.04-1.6L256,171.627l130.347,160.427c3.733,4.587,10.453,5.227,15.04,1.6
          c4.587-3.733,5.227-10.453,1.6-15.04L264.32,147.947z"/>
  </g>
  </svg>
</a>
</div>
    </div><!-- col -->
  </div><!-- row -->

{if isset($smarty.const.APP_DEBUG_MODE) && $smarty.const.APP_DEBUG_MODE}
  <div>
<p class="fw-semibold fst-italic p-2">
Template: <span class="text-nowrap text-lowercase">{$smarty.template}</span>
</p>
  </div>
{/if}
</div><!-- container -->
</section>
</footer>