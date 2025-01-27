{include file="page_header.tpl" pageDomainTitle=$pageDomainTitle}
{include file="page_header.tpl" pageDomainTitle=$pageDomainTitle}

{assign var=errorTitle value=$errorTitle|default:"Script execution halted!"}
{assign var=errorMessage value=$errorMessage|default:"No error-message."}
{assign var=errorColor value=$errorColor|default:"red"}

<h2>{$errorTitle}</h2>
<p>TEST We are here!<span style="color:{$errorColor};">{$errorMessage}</span></p>

{if $smarty.const.DEBUG}
<p>Smarty template-engine: <i><b>v{$smarty.version}</b></i> - Template: <i><b>{$smarty.template}</b></i></p>
{/if}
{include file="page_footer.tpl"}