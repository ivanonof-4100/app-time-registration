{assign var=alertTitle value=$alertTitle|default:"Warning"}
{assign var=alertText value=$alertText|default:""}

<div class="d-flex alert alert-danger alert-dismissible" role="alert">
  <svg xmlns="http://www.w3.org/2000/svg" class="bi flex-shrink-0 me-2 icon-small" viewBox="0 0 16 16" role="img" aria-label="Warning">
    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
  </svg>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  <div>
   <h5 class="alert-heading">{$alertTitle|escape}</h5>
   <p class="mb-0">{$alertText|escape}</p>
  </div>
</div>