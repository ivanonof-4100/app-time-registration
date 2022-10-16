{assign var=pageTitle value=$pageTitle|default:"No title"}
{assign var=pageContent value=$pageContent|default:"No content"}
{assign var=pageCharset value=$pageCharset|default:$smarty.const.SITE_DEFAULT_CHARSET}
{assign var=contentType value=$contentType|default:"text/html"}
{assign var=uriStyles value=$uriStyles|default:$smarty.const.URI_STYLES}

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="da">
<head>
  <title>{$pageTitle}</title>
  <meta http-equiv="content-type" content="{$contentType}; charset={$pageCharset}"/>
  <!-- *** layout stylesheet *** -->
  <link rel="stylesheet" type="text/css" href="{$uriStyles|cat:'main.css'}"/>

  <!-- *** color scheme stylesheet *** -->
  <link rel="stylesheet" type="text/css" href="{$uriStyles|cat:'color.css'}"/>
  {include file="meta_data.tpl"}
</head>

<body>
 {$pageContent}

 {if $smarty.const.DEBUG}
  <p align="left"><br/>
    <a href="http://www.smarty.net" target="_blank">
      <img src="{$smarty.const.URI_GRAPHICS_COMMON|cat:'smarty-80x15.png'}" alt="Read about Smarty" style="border:0;"/></a> Smarty template-engine ver.: {$smarty.version} - Template: <i><b>{$smarty.template}</b></i></p>
 {/if}
</body>
</html>
