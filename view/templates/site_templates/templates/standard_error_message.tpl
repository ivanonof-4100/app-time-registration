{include file="page_header.tpl" pageDomainTitle=$pageDomainTitle}

{assign var=errorTitle value=$errorTitle|default:"Script execution halted!"}
{assign var=errorMessage value=$errorMessage|default:"No error-message."}
{assign var=errorColor value=$errorColor|default:"red"}

<h1>{nocache}{$pageTitle}{/nocache}</h1>
<h2>{nocache}{$errorTitle}{/nocache}</h2>
<p><span style="color:{$errorColor};">{nocache}{$errorMessage}{/nocache}</span></p>

{if $smarty.const.DEBUG}
<p>Template: <i><b>{nocache}{$smarty.template}{/nocache}</b></i></p>
{/if}
{include file="page_footer.tpl"}