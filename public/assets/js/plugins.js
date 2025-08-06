/*
Template Name: Velzon - Admin & Dashboard Template
Author: Themesbrand
Version: 4.3.0
Website: https://Themesbrand.com/
Contact: Themesbrand@gmail.com
File: Common Plugins Js File
*/

//Common plugins
if(document.querySelectorAll("[toast-list]") || document.querySelectorAll('[data-choices]') || document.querySelectorAll("[data-provider]")){ 
  // Load Toastify
  const toastifyScript = document.createElement('script');
  toastifyScript.type = 'text/javascript';
  toastifyScript.src = 'https://cdn.jsdelivr.net/npm/toastify-js';
  toastifyScript.async = true;
  document.head.appendChild(toastifyScript);
  
  // Load Choices.js
  const choicesScript = document.createElement('script');
  choicesScript.type = 'text/javascript';
  choicesScript.src = (window.baseUrl || '') + 'assets/libs/choices.js/public/assets/scripts/choices.min.js';
  choicesScript.async = true;
  document.head.appendChild(choicesScript);
  
  // Load Flatpickr
  const flatpickrScript = document.createElement('script');
  flatpickrScript.type = 'text/javascript';
  flatpickrScript.src = (window.baseUrl || '') + 'assets/libs/flatpickr/flatpickr.min.js';
  flatpickrScript.async = true;
  document.head.appendChild(flatpickrScript);
}