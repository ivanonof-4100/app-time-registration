{assign var=alertTitle value=$alertTitle|default:"Information"}
{assign var=alertText value=$alertText|default:""}
{assign var=dismissable value=$dismissable|default:TRUE}

{if $dismissable}
<div class="d-flex alert alert-success alert-dismissible" role="alert">
{else}
<div class="d-flex alert alert-success" role="alert">
{/if}
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" class="bi flex-shrink-0 me-2 icon-small" role="img" aria-label="Success">
    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
  </svg>
{if $dismissable}
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
{/if}
  <div>
   <h5 class="alert-heading">{$alertTitle|escape}</h5>
   <p class="mb-0">{$alertText|escape}</p>
  </div>
</div>