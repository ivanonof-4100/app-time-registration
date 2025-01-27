/*
 * Color-mode toggler for Bootstrap's docs: https://getbootstrap.com/
 * Copyright 2011-2023 The Bootstrap Authors
 * Licensed under the Creative Commons Attribution 3.0 Unported License.
 * 
 * Last edit: 07/01-2025, Ivan Mark Andersen <ivanonof@gmail.com>
 */
'use strict';

const setStoredTheme = p_theme => localStorage.setItem('theme_pref', p_theme);
const getStoredTheme = function() {
  if (localStorage.getItem('theme_pref') === null) {
    return null;
  } else {
    return localStorage.getItem('theme_pref');
  }
}

// Add theme-preference to the body-tag.
const setTheme_onBodyTag = function(p_theme) {
  document.body.setAttribute('data-bs-theme', p_theme);
}

/**
 * Get theme-preference on body-tag.
 * @returns string
 */
const getTheme_onBodyTag = function() {
  return document.body.getAttribute('data-bs-theme');
}

/**
 * Returns the theme that reflects the daylight.
 * @returns string
 */
const getHoursOfDay_dependedTheme = function() {
  const currentDate = new Date();
  const currentHour = currentDate.getHours();
  if (currentHour >=8 && currentHour <=16) {
    return 'light';
  } else {
    return 'dark';
  }
}

const isAutoPreference_darkModeOnly = function() {
  const darkModeOnly = Boolean(window.matchMedia("(prefers-color-scheme: dark)"));
  return darkModeOnly.valueOf();
}

const isAutoPreference_lightModeOnly = function() {
  const lightModeOnly = new Boolean(window.matchMedia("(prefers-color-scheme: light)"));
  return lightModeOnly.valueOf();
}

/**
 * Add current theme-color to body-element
 * Valid values for p_theme 'auto','dark','light'.
 * @returns void
 */
const setTheme = p_theme => {
  if (p_theme == 'auto') {
    if (window.matchMedia("(prefers-color-scheme:dark)").matches) {
      setTheme_onBodyTag('dark'); // Dark only
    } else if (window.matchMedia("(prefers-color-scheme:light)").matches) {
      setTheme_onBodyTag('light'); // Light only
    } else {
      // Default, if the browser has another value than 'dark' or 'light'.
      const daylightTheme = getHoursOfDay_dependedTheme();
      setTheme_onBodyTag(daylightTheme);
    }
  } else {
    setTheme_onBodyTag(p_theme);
  }
}