{include file="page_header.tpl" pageTitle=$pageTitle|cat:' - '|cat:$smarty.server.SERVER_NAME}

<div class="main wrapper clearfix">
 <h1>{$pageTitle}</h1>
    {$phpinfo_content}

{include file="page_footer.tpl"}
