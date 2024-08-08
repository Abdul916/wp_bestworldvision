;!function(){function e(e){return e&&e.__esModule?e.default:e}const t=e=>{for(;e.firstChild;)e.removeChild(e.firstChild)},a=(e,t,a)=>{if(e.matches(t))return e;if("BODY"===e.tagName||"HTML"===e.tagName)return;let s;a=a||20;for(let s=0;s<a;s++){const a=e.parentNode;if(!a||"BODY"===a.tagName||"HTML"===a.tagName)break;if(a.matches(t))return a;e=a}return s},s=e=>{e.classList.add("is-loading")},n=e=>{e.classList.remove("is-loading")};var i;function r(){const r=document.querySelector(".kirki-settings-page");if(!r)return;const o=r.querySelector(".installation-progress-metabox");if(!o)return;const l=o.querySelector(".installation-progress-list");if(!l)return;let d=!1;const c=kirkiSettings.recommendedPlugins.udb;function u(t){d||(d=!0,f("Activating Ultimate Dashboard","loading"),e(i).ajax({async:!0,type:"GET",url:c.activationUrl,success:function(){m("Ultimate Dashboard has been activated successfully.","done"),f("All done! Redirecting...","loading"),p(t,c.redirectUrl)},error:function(e){e.errorCode&&e.errorMessage?m(e.errorMessage,"failed"):e.responseJSON&&e.responseJSON.data?m(e.responseJSON.data,"failed"):m("Something went wrong. Please try again later.","failed"),p(t,"")}}))}function f(e,t){if(!l)return;const a=document.createElement("li");a.className="installation-progress","done"===t?a.classList.add("is-done"):"failed"===t?a.classList.add("is-failed"):a.classList.add("is-loading");const s=document.createElement("div");s.className="progress-icon",a.appendChild(s);const n=document.createElement("div");n.className="progress-text",n.innerHTML=e,a.appendChild(n),l.appendChild(a)}function m(e,t){if(!l)return;const a=l.querySelector(".installation-progress:last-child");if(a&&("done"===t?(a.classList.remove("is-loading"),a.classList.add("is-done")):"failed"===t?(a.classList.remove("is-loading"),a.classList.add("is-failed")):(a.classList.remove("is-done"),a.classList.remove("is-failed"),a.classList.add("is-loading")),e)){const t=a.querySelector(".progress-text");if(!t)return;t.innerHTML=e}}function g(e,t){const a=document.querySelectorAll(".kirki-install-udb");a.length&&a.forEach((a=>{"disable"===e&&t&&a===t||(a.tagName.toLowerCase(),"disable"===e?a.classList.add("is-loading"):a.classList.remove("is-loading"))}))}function p(e,t){t&&window.setTimeout((()=>{window.location.replace(t)}),1e3),n(e),d=!1,g("enable",e)}document.addEventListener("click",(function(n){const h=a(n.target,".kirki-install-udb");if(!h)return;n.preventDefault(),function(a){if(!r)return;if(d)return;(function(e){o&&l&&(t(l),o.classList.remove("is-hidden"));d=!0,g("disable",e),s(e)})(a),f("Preparing...","loading");const n=r.dataset.setupUdbNonce?r.dataset.setupUdbNonce:"";e(i).ajax({url:ajaxurl,method:"POST",data:{action:"kirki_prepare_install_udb",nonce:n}}).done((function(e){return e.success?e.data.finished?(m("Ultimate Dashboard has already been installed.","done"),f(e.data.message,"done"),f("All done! Redirecting...","loading"),void p(a,c.redirectUrl)):(m(e.data.message,"done"),d=!1,void function(e){if(d)return;d=!0,f("Installing Ultimate Dashboard","loading"),wp.updates.installPlugin({slug:c.slug,success:function(){m("Ultimate Dashboard has been installed successfully","done"),d=!1,u(e)},error:function(t){let a=!0;t.errorCode&&t.errorMessage?"folder_exists"===t.errorCode?(m("Ultimate Dashboard has already been installed.","done"),d=!1,a=!1,u(e)):m(t.errorMessage,"failed"):t.responseJSON&&t.responseJSON.data?m(t.responseJSON.data,"failed"):m("Something went wrong. Please try again later.","failed"),a&&p(e,"")}})}(a)):(m(e.data,"failed"),void p(a,""))})).fail((function(e){let t="Something went wrong. Please try again later.";e.responseJSON&&e.responseJSON.data&&(t=e.responseJSON.data),m(t,"failed"),p(a,"")}))}(h)}))}i=jQuery,function(){e(i)(".heatbox-tab-nav-item").on("click",(function(){e(i)(".heatbox-tab-nav-item").removeClass("active"),e(i)(this).addClass("active");const t=this.querySelector("a");if(!t)return;if(-1===t.href.indexOf("#"))return;const a=t.href.substring(t.href.indexOf("#")+1);e(i)(".heatbox-panel-wrapper .heatbox-admin-panel").css("display","none"),e(i)(".heatbox-panel-wrapper .kirki-"+a+"-panel").css("display","block")})),window.addEventListener("load",(function(){let t=window.location.hash.substring(1),a=null;t||(a=document.querySelector(".heatbox-tab-nav-item.active"),a&&a.dataset.tab&&(t=a.dataset.tab),t=t||"settings"),e(i)(".heatbox-tab-nav-item").removeClass("active"),e(i)(".heatbox-tab-nav-item.kirki-"+t+"-panel").addClass("active"),e(i)(".heatbox-panel-wrapper .heatbox-admin-panel").css("display","none"),e(i)(".heatbox-panel-wrapper .kirki-"+t+"-panel").css("display","block")})),r();const t=document.querySelector(".kirki-clear-font-cache-metabox");if(!t)return;var a=t.querySelector(".submission-status");if(!a)return;const s=t.querySelector(".kirki-clear-font-cache");if(!s)return;s.addEventListener("click",(function(e){if(n)return;n=!0;const t=this;t.classList.add("is-loading"),o&&window.clearTimeout(o);o=0;var s={action:"kirki_clear_font_cache",nonce:t.dataset.nonce};jQuery.ajax({url:ajaxurl,type:"POST",data:s}).done((function(e){l(e.success?"success":"error",e.data)})).fail((function(e){l("error","Something went wrong.")})).always((function(e){n=!1,t.classList.remove("is-loading"),o=window.setTimeout((function(){!function(){if(!a)return;a.textContent="",a.classList.remove("is-success"),a.classList.remove("is-error"),a.classList.add("is-hidden")}()}),4e3)}))}));let n=!1,o=0;function l(e,t){a&&(a.textContent=t,a.classList.add("success"===e?"is-success":"is-error"),a.classList.remove("is-hidden"))}}()}();
//# sourceMappingURL=settings.js.map
