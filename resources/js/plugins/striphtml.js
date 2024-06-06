import Vue from 'vue'

/**
 * Copy to clipboard the text passed
 * @param {String} text string to copy
 * @param {String} toastText message to appear on the toast notification
 */
Vue.prototype.$striphtml = function (data, lenght = 10000) {

      var div = document.createElement("div");
      div.innerHTML = data;
      var text = div.textContent || div.innerText || "";
      return text.length > lenght ? text.substring(0, lenght) + "..." : text;
    
}