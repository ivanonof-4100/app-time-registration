{assign var=alertTitle value=$alertTitle|default:"Info"}
{assign var=alertText value=$alertText|default:""}
{assign var=dismissable value=$dismissable|default:TRUE}

{if $dismissable}
<div class="d-flex alert alert-primary alert-dismissible" role="alert">
{else}
<div class="d-flex alert alert-primary alert-dismissible" role="alert">
{/if}
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" class="bi flex-shrink-0 me-2 icon-small" role="img" aria-label="Info">
    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
  </svg>
{if $dismissable}
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
{/if}
  <div>
   <h5 class="alert-heading">{$alertTitle|escape}</h5>
   <p class="mb-0">{$alertText|escape}</p>
  </div>
</div>