/**
 * Custom fixes for asset paths in app.js
 * This file overrides functions that use relative paths
 */

// Override the setLanguage function to use absolute paths
function setLanguage(lang) {
    if (document.getElementById("header-lang-img")) {
        const baseAssetsUrl = window.assetsUrl || ((window.baseUrl || '') + 'assets');
        
        if (lang == "en") {
            document.getElementById("header-lang-img").src = baseAssetsUrl + "/images/flags/us.svg";
        } else if (lang == "sp") {
            document.getElementById("header-lang-img").src = baseAssetsUrl + "/images/flags/spain.svg";
        } else if (lang == "gr") {
            document.getElementById("header-lang-img").src = baseAssetsUrl + "/images/flags/germany.svg";
        } else if (lang == "it") {
            document.getElementById("header-lang-img").src = baseAssetsUrl + "/images/flags/italy.svg";
        } else if (lang == "ru") {
            document.getElementById("header-lang-img").src = baseAssetsUrl + "/images/flags/russia.svg";
        } else if (lang == "ch") {
            document.getElementById("header-lang-img").src = baseAssetsUrl + "/images/flags/china.svg";
        } else if (lang == "fr") {
            document.getElementById("header-lang-img").src = baseAssetsUrl + "/images/flags/french.svg";
        } else if (lang == "ar") {
            document.getElementById("header-lang-img").src = baseAssetsUrl + "/images/flags/ae.svg";
        }
        localStorage.setItem("language", lang);
        language = localStorage.getItem("language");
        getLanguage();
    }
}

// Override the getLanguage function to use absolute paths
function getLanguage() {
    const default_lang = 'en'; // Default language
    let language = localStorage.getItem("language");
    
    language == null ? setLanguage(default_lang) : false;
    
    const baseAssetsUrl = window.assetsUrl || ((window.baseUrl || '') + 'assets');
    var request = new XMLHttpRequest();
    
    // Use absolute path for language files
    request.open("GET", baseAssetsUrl + "/lang/" + language + ".json");
    
    // Defining event listener for readystatechange event
    request.onreadystatechange = function () {
        // Check if the request is complete and was successful
        if (this.readyState === 4 && this.status === 200) {
            var data = JSON.parse(this.responseText);
            Object.keys(data).forEach(function (key) {
                var elements = document.querySelectorAll("[data-key='" + key + "']");
                Array.from(elements).forEach(function (elem) {
                    elem.textContent = data[key];
                });
            });
        }
    };
    // Sending the request to the server
    request.send();
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Override global functions if they exist
    if (typeof window.setLanguage !== 'undefined') {
        window.setLanguage = setLanguage;
    }
    if (typeof window.getLanguage !== 'undefined') {
        window.getLanguage = getLanguage;
    }
}); 