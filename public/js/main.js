require.config({
  enforceDefine: false,
  baseUrl : '/js/',
  paths : {
    bootstrapScript: ['/bootstrap/bootstrap-5.3.3-dist/js/bootstrap.bundle.min'],
    themeScript: ['/js/theme_colormodes.min'],
    cookieScript: ['/js/cookie.min'],
    domReady: ['/js/requirejs/modules/domReady.min']
  }
});

// Declare a usefull sprintf function in native-JavaScript.
let sprintf = function(p_str) {
  let args=arguments, flag=true, i=1;

  str = p_str.replace(/%s/g, function() {
    let arg = args[i++];
    if (typeof arg === 'undefined') {
      flag = false;
      return '';
    } else {
      return arg;
    }
  });
  return (flag) ? str : '';
};

let isInBodyElement = function(p_node) {
  return (p_node === document.body) ? false : document.body.contains(p_node);
}

require(['themeScript'], function(themeScript) {
  // Update theme, when changeing the prefered theme-color in browser-settings.
  window.matchMedia("(prefers-color-scheme:light)").addEventListener('change', (event) => {
    if (event.matches) {
      // Here it will allways be 'auto'.
      const defaultTheme ='auto'; // Default
      setStoredTheme(defaultTheme);
      setTheme(defaultTheme);
    }
  });
});

require(['domReady!','cookieScript','bootstrapScript','themeScript'],
  function(doc, cookieScript, bootstrapScript, themeScript) {

  // Scroll-up button
  const btnScrollUp = doc.querySelector("#btn_scroll_up");
  btnScrollUp.addEventListener("click", (event) => {
    event.preventDefault();
    window.scrollTo(0,0);
  });

  // Initalize Tooltips in Bootstrap Front-end framework.
  // Read more URL: https://getbootstrap.com/docs/5.3/components/tooltips/#overview
  const tooltipTriggerList = doc.querySelectorAll('[data-bs-toggle="tooltip"]');
  const tooltipList = [...tooltipTriggerList].map(tooltipTriggerElement => new bootstrapScript.Tooltip(tooltipTriggerElement));

  // Cookie Alert-dialog
  /* When clicking on the agree-button,
   * Create a 1 day cookie to remember user's choice and close the banner.
   */
  const cookieAlert = doc.querySelector("#cookie_alert");
  if (!cookieAlert) {
    console.log("Cookie-alert was NOT found, you need an UI-element with id=\"cookie_alert\" ...");
  } else {
    const cookieToggleBtn = doc.querySelector("#btn_cookie_toggle");
    // Bind an event to the element.
    cookieToggleBtn.addEventListener("click", function(event) {
      event.preventDefault();
      cookieAlert.classList.remove("invisible");
      cookieAlert.classList.add("show");
      cookieAlert.offsetHeight;
  
      // Dispatch the accept-event
      window.dispatchEvent(new Event("cookieAlertToggleEvent"));
    });

    // Accept button
    const btnAcceptCookies = doc.querySelector("#btn_accept_cookies");
    btnAcceptCookies.addEventListener("click", function () {
      setCookie("acceptCookies", true, 1);
      localStorage.setItem('acceptCookies_wasSet', true);

      cookieAlert.classList.remove("show");
      cookieAlert.offsetHeight;

      // Dispatch the accept-event
      window.dispatchEvent(new Event("cookieAcceptEvent"));
    });

    // Decline button
    const btnDeclineCookies = doc.querySelector("#btn_decline_cookies");
    btnDeclineCookies.addEventListener("click", function () {
      setCookie("acceptCookies", false, 1);
      localStorage.setItem('acceptCookies_wasSet', true);

      cookieAlert.classList.remove("show");
      cookieAlert.offsetHeight;

      // Dispatch the accept-event
      window.dispatchEvent(new Event("cookieDeclineEvent"));
    });
  }

  if (!isCookiesEnabled()) {
    doc.getElementById("cookies_not_enabled").innerHTML = 'We dected that use of cookies was disabled, please enable it in your web-browser ...';
  }

  if (window.localStorage.getItem('acceptCookies_wasSet')) {
    cookieAlert.classList.remove("show");
    cookieAlert.classList.add("invisible");
    cookieAlert.offsetHeight;
  } else {
    cookieAlert.classList.remove("invisible");
    cookieAlert.classList.add("show");
    cookieAlert.offsetHeight;
  }

  // Theme - Make sure that both the storedTheme is set and added to the body-tag.
  const storedTheme = getStoredTheme();
  if (storedTheme === null) {
    // Start with default.
    const defaultTheme ='auto'; // Default
    setStoredTheme(defaultTheme);
    setTheme(defaultTheme);
  } else {
    setTheme(storedTheme);
  }

  // Actions on theme-switcher
  const optionRadioBtns = doc.querySelectorAll('input[type="radio"][data-bs-theme-value]').forEach(radioBtn => {
    radioBtn.addEventListener('click', (e) => {
      const selectedTheme = radioBtn.getAttribute('data-bs-theme-value');
      window.console.log('Selected theme: '+selectedTheme);
      setStoredTheme(selectedTheme);
      setTheme(selectedTheme);
    });
  });
});