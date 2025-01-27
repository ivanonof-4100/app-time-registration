{assign var=flashMessage value=$flashMessage}
{assign var=widgetTemplateName value=$flashMessage->getTemplateName()}

{if isset($flashMessage)}
    {include file="{$widgetTemplateName}" alertTitle="Successfully saved" alertText={$flashMessage->getAttr_flashMessage()}}
{*
  {include file="alert_warning.tpl" alertTitle="Advarsel" alertText="Husk, at du IKKE kan registere timer på dage der ligger i fremtiden."}
  {include file="alert_danger.tpl" alertTitle="Alvorlig fejl" alertText="Husk, at du IKKE kan registere timer på dage der ligger i fremtiden."}
  {include file="alert_info.tpl" alertTitle="Information" alertText="Husk, at du IKKE kan registere timer på dage der ligger i fremtiden."}
  {include file="alert_success.tpl" alertTitle="Dine data er nu gemt" alertText="Din tidregistering blev gemt ..."}
*}
{/if}