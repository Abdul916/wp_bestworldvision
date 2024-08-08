wpJsonpAmeliaBookingPlugin([22],{1545:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var s=a(693),i=a.n(s);e.default={data:function(){return{email:"",isValidEmail:!0,blogPosts:[],changelog:{version:"7.7",starter:{feature:["Square Integration - Simplify your transaction management with our Square integration, offering a reliable and efficient payment method for your services and events","WP Fusion Integration - Sync Amelia appointment bookings with over 50 CRMs and marketing platforms, and apply tags based on booked services"],improvement:[],translations:["Updated Turkish translation"],bugfix:["Fixed issue with notifications and additional language","Fixed issue with the Go back button on the Catalog form when one service/employee is preselected","Fixed issue with appointments that go over 24:00 on the Calendar page","Fixed issue with the Catalog booking form and Iphone 14/15","Fixed issue with displaying events in drop-downs on back-end pages","Fixed issue with Divi shortcodes and preselected parameters","Added missing strings for translation for the Customer panel"],other:["Other small bug fixes and stability improvements"]},basic:{feature:[],improvement:["Added the possibility for users to enable Onsite and WooCommerce payments","Improved logic by adding new event type in GA and FB pixel"],translations:[],bugfix:["Fixed issue with translating date on the new Event list form","Fixed issue with recurring appointments step on Step-by-step booking form","Fixed issue with multiply deposit on the event payment step"],other:[]},pro:{feature:[],improvement:["Optimized Packages page for adding new bookings"],translations:[],bugfix:["Fixed issue with same appointment time slots in packages for different services"],other:[]},developer:{feature:[],improvement:["Allowed custom settings parameter for integrations in the get time slots API call"],translations:[],bugfix:[],other:[]}},loading:!1}},methods:{clearValidation:function(){this.isValidEmail=!0},submitForm:function(){/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/.test(this.email)?(this.isValidEmail=!0,this.$refs.emailForm.submit()):this.isValidEmail=!1},getThisAndLowerLicences:function(){return this.$root.licence.isStarter?["starter"]:this.$root.licence.isBasic?["starter","basic"]:this.$root.licence.isPro?["starter","basic","pro"]:this.$root.licence.isDeveloper?["starter","basic","pro","developer"]:void 0},getHigherLicences:function(){return this.$root.licence.isStarter?["basic","pro","developer"]:this.$root.licence.isBasic?["pro","developer"]:this.$root.licence.isPro?["developer"]:this.$root.licence.isDeveloper?[]:void 0},getLicencesItems:function(t){var e=this,a=[];return t.forEach(function(t){e.changelog[t].feature.forEach(function(e){a.push({licence:t,type:"Feature",text:e})})}),t.forEach(function(t){e.changelog[t].improvement.forEach(function(e){a.push({licence:t,type:"Improvement",text:e})})}),t.forEach(function(t){e.changelog[t].translations.forEach(function(e){a.push({licence:t,type:"Translations",text:e})})}),t.forEach(function(t){e.changelog[t].bugfix.forEach(function(e){a.push({licence:t,type:"BugFix",text:e})})}),t.forEach(function(t){e.changelog[t].other.forEach(function(e){a.push({licence:t,text:e})})}),a},getNews:function(){var t=this;this.loading=!0,this.$http.get(this.$root.getAjaxUrl+"/whats-new").then(function(e){t.blogPosts=e.data.data.blogPosts?e.data.data.blogPosts.slice(0,6):[],t.loading=!1}).catch(function(e){t.loading=!1,console.log(e)})},getIconType:function(t){var e=t.toLowerCase();switch(e){case"improvement":case"bugfix":case"feature":case"translations":return e;default:return"plus"}}},created:function(){this.getNews()},components:{PageHeader:i.a}}},1546:function(t,e){t.exports={render:function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"am-wrap",attrs:{id:"am-whats-new"}},[a("div",{staticClass:"am-body"},[a("page-header"),t._v(" "),a("div",{staticClass:"am-whats-new-welcome am-section am-whats-new-section"},[a("div",{staticClass:"am-whats-new-welcome-left"},[a("div",{staticClass:"am-whats-new-welcome-title"},[t._v(t._s(t.$root.labels.welcome_to_amelia))]),t._v(" "),a("div",{staticClass:"am-whats-new-welcome-subtitle"},[t._v(t._s(t.$root.labels.welcome_congratz))]),t._v(" "),a("a",{staticClass:"am-whats-new-btn",attrs:{href:"https://www.youtube.com/c/AmeliaWordPressBookingPlugin",target:"_blank"}},[t._v("\n          "+t._s(t.$root.labels.discover_amelia)+"\n        ")])]),t._v(" "),a("div",{staticClass:"am-whats-new-welcome-right"},[a("img",{attrs:{src:t.$root.getUrl+"public/img/am-whats-new-welcome.webp"}})])]),t._v(" "),a("div",{staticClass:"am-whats-new-changelog am-section am-whats-new-section"},[a("div",{staticClass:"am-whats-new-changelog-left"},[a("div",{staticClass:"am-whats-new-changelog-header"},[a("div",{staticClass:"am-whats-new-changelog-title am-whats-new-blog-title-text"},[t._v("\n            "+t._s(t.$root.labels.amelia_changelog)+"\n          ")]),t._v(" "),a("div",{staticClass:"am-whats-new-changelog-subtitle am-whats-new-blog-subtitle-text"},[t._v("\n            "+t._s(t.$root.labels.current_version)+" "+t._s(t.changelog.version)+"\n          ")])]),t._v(" "),a("div",{staticClass:"am-whats-new-changelog-version"},[a("div",{staticClass:"am-whats-new-changelog-version-title"},[t._v("\n            "+t._s(t.$root.labels.version)+" "+t._s(t.changelog.version)+"\n          ")]),t._v(" "),a("div",{staticClass:"am-whats-new-changelog-subtitle am-whats-new-blog-subtitle-text"},[t._v("\n            "+t._s(t.$root.labels.version_subtitle)+"\n          ")])]),t._v(" "),a("div",{staticClass:"am-whats-new-changelog-list"},[a("p",{staticClass:"am-whats-new-changelog-list-title"},[t._v(t._s(t.$root.labels.included_plan_your))]),t._v(" "),t._l(t.getLicencesItems(t.getThisAndLowerLicences()),function(e,s){return a("div",{key:e.licence+e.type+s,staticClass:"am-whats-new-changelog-list-item"},[a("div",{staticClass:"am-whats-new-changelog-list-item-img-holder"},[e.type?a("img",{attrs:{src:t.$root.getUrl+"public/img/am-"+t.getIconType(e.type)+".svg"}}):t._e()]),t._v(" "),e.text?a("div",{staticClass:"am-whats-new-blog-subtitle-text",domProps:{innerHTML:t._s(e.text.replace(":",""))}}):t._e()])}),t._v(" "),!t.$root.licence.isDeveloper&&t.getLicencesItems(t.getHigherLicences()).length?a("p",{staticClass:"am-whats-new-changelog-list-title"},[t._v(t._s(t.$root.labels.included_plan_higher))]):t._e(),t._v(" "),t._l(t.getLicencesItems(t.getHigherLicences()),function(e,s){return t.$root.licence.isDeveloper?t._e():a("div",{key:s,staticClass:"am-whats-new-changelog-list-item"},[a("div",{staticClass:"am-whats-new-changelog-list-item-img-holder"},[e.type?a("img",{attrs:{src:t.$root.getUrl+"public/img/am-"+t.getIconType(e.type)+".svg"}}):t._e()]),t._v(" "),e.text?a("div",{staticClass:"am-whats-new-blog-subtitle-text",domProps:{innerHTML:t._s(e.text.replace(":",""))}}):t._e()])}),t._v(" "),a("a",{staticClass:"am-whats-new-changelog-link",attrs:{href:"https://wpamelia.com/changelog/",target:"_blank",rel:"nofollow"}},[t._v("\n            "+t._s(t.$root.labels.see_previous_versions)+"\n            "),a("img",{attrs:{src:t.$root.getUrl+"public/img/am-arrow-upper-right.svg"}})])],2)]),t._v(" "),a("div",{staticClass:"am-whats-new-changelog-right"},[a("div",{staticClass:"am-whats-new-blog-success-stories am-whats-new-blog-box"},[a("img",{attrs:{src:t.$root.getUrl+"public/img/am-success-stories.webp"}}),t._v(" "),a("div",{staticClass:"am-whats-new-blog-success-stories-title am-whats-new-blog-title-text"},[t._v("\n            "+t._s(t.$root.labels.take_a_look)+"\n          ")]),t._v(" "),a("a",{staticClass:"am-whats-new-btn",attrs:{href:"https://wpamelia.com/success-stories/",target:"_blank",rel:"nofollow"}},[t._v("\n            "+t._s(t.$root.labels.read_success_stories)+"\n          ")])])])]),t._v(" "),a("div",{staticClass:"am-whats-new-blog am-section am-whats-new-section"},[a("div",{staticClass:"am-whats-new-blog-left"},[a("div",{staticClass:"am-whats-new-blog-title am-whats-new-blog-title-text"},[t._v("\n          "+t._s(t.$root.labels.news_blog)),a("img",{attrs:{src:t.$root.getUrl+"public/img/am-ringing-bel.png"}})]),t._v(" "),t.loading?a("div",{staticClass:"am-whats-new-loader"},[a("img",{attrs:{src:t.$root.getUrl+"public/img/spinner.svg"}})]):a("div",{staticClass:"am-whats-new-blog-list"},t._l(t.blogPosts,function(e){return a("div",{key:e.href,staticClass:"am-whats-new-blog-list-item"},[a("p",[t._v(t._s(e.title))]),t._v(" "),a("a",{attrs:{href:e.href,target:"_blank",rel:"nofollow"}},[a("img",{attrs:{src:t.$root.getUrl+"public/img/am-arrow-upper-right.svg"}})])])}),0),t._v(" "),a("div",{staticClass:"am-whats-new-blog-subscribe"},[a("div",{staticClass:"am-whats-new-blog-subscribe-left"},[a("div",{staticClass:"am-whats-new-blog-subscribe-title"},[t._v(t._s(t.$root.labels.keep_up_to_date))]),t._v(" "),a("div",{staticClass:"am-whats-new-blog-subscribe-subtitle am-whats-new-blog-subtitle-text"},[t._v(t._s(t.$root.labels.never_miss_notifications))]),t._v(" "),a("div",{staticClass:"am-whats-new-blog-subscribe-form"},[a("form",{ref:"emailForm",staticClass:"am-whats-new-blog-subscribe-form",attrs:{action:"https://acumbamail.com/newform/subscribe/ET8rshmNeLvQox6J8U99sSJZ8B1DZo1mhOgs408R0mHYiwgmM/39828/",method:"post"},on:{submit:function(e){return e.preventDefault(),t.submitForm(e)}}},[a("div",{},[a("div",{staticStyle:{width:"100%",position:"relative"}},[t.isValidEmail?t._e():a("span",{staticStyle:{color:"red"},attrs:{id:"am-subscribe-error-msg"}},[t._v("Please enter a valid email address.")]),a("br"),t._v(" "),a("input",{directives:[{name:"model",rawName:"v-model",value:t.email,expression:"email"}],attrs:{id:"r0c0m1i1",name:"email_1",type:"email",placeholder:t.$root.labels.enter_your_email,required:""},domProps:{value:t.email},on:{keyup:t.clearValidation,input:function(e){e.target.composing||(t.email=e.target.value)}}}),a("br"),t._v(" "),a("input",{staticStyle:{position:"absolute",left:"-4900px"},attrs:{type:"text",name:"a781911c",tabindex:"-1",value:"","aria-hidden":"true",id:"a781911c",autocomplete:"off"}}),t._v(" "),a("br"),t._v(" "),a("input",{staticStyle:{position:"absolute",left:"-5000px"},attrs:{type:"email",name:"b781911c",tabindex:"-1",value:"","aria-hidden":"true",id:"b781911c",autocomplete:"off"}}),t._v(" "),a("br"),t._v(" "),a("input",{staticStyle:{position:"absolute",left:"-5100px"},attrs:{type:"checkbox",name:"c781911c",tabindex:"-1","aria-hidden":"true",id:"c781911c",autocomplete:"off"}}),t._v(" "),a("br")])]),t._v(" "),a("input",{attrs:{type:"hidden",name:"ok_redirect",id:"id_redirect",value:"/*You can insert the url that you want to redirect to after a valid registration here */"}}),t._v(" "),a("input",{staticClass:"am-whats-new-btn am-subscribe-btn",attrs:{type:"submit"},domProps:{value:t.$root.labels.subscribe}})])])]),t._v(" "),a("div",{staticClass:"am-whats-new-blog-subscribe-right"},[a("img",{attrs:{src:t.$root.getUrl+"public/img/am-subscribe-box.svg"}})])])]),t._v(" "),a("div",{staticClass:"am-whats-new-blog-right"},[a("div",{staticClass:"am-whats-new-blog-support am-whats-new-blog-box"},[a("img",{attrs:{src:t.$root.getUrl+"public/img/am-contact-support.svg"}}),t._v(" "),a("div",{staticClass:"am-whats-new-blog-title-text"},[t._v("\n            "+t._s(t.$root.labels.need_help)+"\n          ")]),t._v(" "),a("div",{staticClass:"am-whats-new-blog-support-subtitle am-whats-new-blog-subtitle-text"},[t._v("\n            "+t._s(t.$root.labels.our_support_team)+"\n          ")]),t._v(" "),a("a",{staticClass:"am-whats-new-btn",attrs:{href:"https://tmsplugins.ticksy.com/submit/#100012870",target:"_blank"}},[t._v("\n            "+t._s(t.$root.labels.contact_our_support)+"\n          ")])])])])],1)])},staticRenderFns:[]}},1621:function(t,e,a){var s=a(140)(a(1545),a(1546),!1,null,null,null);t.exports=s.exports},654:function(t,e,a){"use strict";e.a={data:function(){return{colors:["1788FB","4BBEC6","FBC22D","FA3C52","D696B8","689BCA","26CC2B","FD7E35","E38587","774DFB","31CDF3","6AB76C","FD5FA1","A697C5"],usedColors:[]}},methods:{deleteImage:function(t){t.pictureThumbPath="",t.pictureFullPath=""},getAppropriateUrlParams:function(t){if(!this.$root.settings.activation.disableUrlParams)return t;var e=JSON.parse(JSON.stringify(t));return["categories","services","packages","employees","providers","providerIds","extras","locations","events","types","dates"].forEach(function(t){if("extras"===t&&t in e){e.extras=JSON.parse(e.extras);var a=[];e.extras.forEach(function(t){a.push(t.id+"-"+t.quantity)}),e.extras=a.length?a:null}t in e&&Array.isArray(e[t])&&e[t].length&&(e[t]=e[t].join(","))}),e},inlineSVG:function(){var t=a(661);t.init({svgSelector:"img.svg-amelia",initClass:"js-inlinesvg"})},inlineSVGCabinet:function(){setTimeout(function(){a(661).init({svgSelector:"img.svg-cabinet",initClass:"js-inlinesvg"})},100)},imageFromText:function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{},a=arguments.length>2&&void 0!==arguments[2]&&arguments[2],s=this.getNameInitials(t),i=Math.floor(Math.random()*this.colors.length),o=this.colors[i];return this.usedColors.push(this.colors[i]),this.colors.splice(i,1),0===this.colors.length&&(this.colors=this.usedColors,this.usedColors=[]),a?e.firstName?this.$root.getUrl+"public/img/default-employee.svg":e.latitude?this.$root.getUrl+"public/img/default-location.svg":this.$root.getUrl+"public/img/default-service.svg":location.protocol+"//via.placeholder.com/120/"+o+"/fff?text="+s},pictureLoad:function(t,e){if(null!==t){var a=!0===e?t.firstName+" "+t.lastName:t.name;if(void 0!==a)return t.pictureThumbPath=t.pictureThumbPath||this.imageFromText(a),t.pictureThumbPath}},imageLoadError:function(t,e){var a=!0===e?t.firstName+" "+t.lastName:t.name;void 0!==a&&(t.pictureThumbPath=this.imageFromText(a,t,!0))},getNameInitials:function(t){return t.split(" ").map(function(t){return t.charAt(0)}).join("").toUpperCase().substring(0,3).replace(/[^\w\s]/g,"")}}}},661:function(t,e,a){(function(a){var s,i,o,n;n=void 0!==a?a:this.window||this.global,i=[],s=function(t){var e,a={},s=!!document.querySelector&&!!t.addEventListener,i={initClass:"js-inlinesvg",svgSelector:"img.svg"},o=function(){var t={},e=!1,a=0,s=arguments.length;"[object Boolean]"===Object.prototype.toString.call(arguments[0])&&(e=arguments[0],a++);for(var i=function(a){for(var s in a)Object.prototype.hasOwnProperty.call(a,s)&&(e&&"[object Object]"===Object.prototype.toString.call(a[s])?t[s]=o(!0,t[s],a[s]):t[s]=a[s])};s>a;a++){i(arguments[a])}return t},n=function(t){var a=document.querySelectorAll(e.svgSelector),s=function(t,e){return function(){return--t<1?e.apply(this,arguments):void 0}}(a.length,t);Array.prototype.forEach.call(a,function(t,a){var i=t.src||t.getAttribute("data-src"),o=t.attributes,n=new XMLHttpRequest;n.open("GET",i,!0),n.onload=function(){if(n.status>=200&&n.status<400){var a=(new DOMParser).parseFromString(n.responseText,"text/xml").getElementsByTagName("svg")[0];if(a.removeAttribute("xmlns:a"),a.removeAttribute("width"),a.removeAttribute("height"),a.removeAttribute("x"),a.removeAttribute("y"),a.removeAttribute("enable-background"),a.removeAttribute("xmlns:xlink"),a.removeAttribute("xml:space"),a.removeAttribute("version"),Array.prototype.slice.call(o).forEach(function(t){"src"!==t.name&&"alt"!==t.name&&a.setAttribute(t.name,t.value)}),a.classList?a.classList.add("inlined-svg"):a.className+=" inlined-svg",a.setAttribute("role","img"),o.longdesc){var i=document.createElementNS("http://www.w3.org/2000/svg","desc"),l=document.createTextNode(o.longdesc.value);i.appendChild(l),a.insertBefore(i,a.firstChild)}if(o.alt){a.setAttribute("aria-labelledby","title");var r=document.createElementNS("http://www.w3.org/2000/svg","title"),c=document.createTextNode(o.alt.value);r.appendChild(c),a.insertBefore(r,a.firstChild)}t.parentNode.replaceChild(a,t),s(e.svgSelector)}else console.error("There was an error retrieving the source of the SVG.")},n.onerror=function(){console.error("There was an error connecting to the origin server.")},n.send()})};return a.init=function(t,a){s&&(e=o(i,t||{}),n(a||function(){}),document.documentElement.className+=" "+e.initClass)},a}(n),void 0===(o="function"==typeof s?s.apply(e,i):s)||(t.exports=o)}).call(e,a(37))},693:function(t,e,a){var s=a(140)(a(696),a(697),!1,null,null,null);t.exports=s.exports},696:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var s=a(141),i=a(336),o=a(654);e.default={mixins:[s.a,i.a,o.a],props:["oldCustomize","appointmentsApproved","appointmentsPending","employeesTotal","customersTotal","locationsTotal","packagesTotal","resourcesTotal","servicesTotal","categoriesTotal","financeTotal","addNewTaxBtnDisplay","addNewCouponBtnDisplay","addNewCustomFieldBtnDisplay","locations","categories","bookableType","params","fetched"],methods:{showMainCustomize:function(){this.$emit("showMainCustomize",null)},showDialogCustomer:function(){this.$emit("newCustomerBtnClicked",null)},showDialogAppointment:function(){this.$emit("newAppointmentBtnClicked",null)},showDialogEvent:function(){this.$emit("newEventBtnClicked",null)},showDialogEmployee:function(){this.$emit("newEmployeeBtnClicked")},showDialogLocation:function(){this.$emit("newLocationBtnClicked")},showDialogService:function(){this.$emit("newServiceBtnClicked")},showDialogPackage:function(){this.$emit("newPackageBtnClicked")},showDialogPackageBooking:function(){this.$emit("newPackageBookingBtnClicked")},showDialogResource:function(){this.$emit("newResourceBtnClicked")},showDialogTax:function(){this.$emit("newTaxBtnClicked")},showDialogCoupon:function(){this.$emit("newCouponBtnClicked")},showDialogCustomFields:function(){this.$emit("newCustomFieldBtnClicked")},selectAllInCategory:function(t){this.$emit("selectAllInCategory",t)},changeFilter:function(){this.$emit("changeFilter")}}}},697:function(t,e){t.exports={render:function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"am-page-header am-section"},[a("el-row",{attrs:{type:"wpamelia-calendar"===t.$router.currentRoute.name?"":"flex",align:"middle"}},[a("el-col",{attrs:{span:"wpamelia-calendar"===t.$router.currentRoute.name?6:18}},[a("div",{staticClass:"am-logo"},[a("img",{staticClass:"logo-big",attrs:{width:"92",src:t.$root.getUrl+"public/img/amelia-logo-horizontal.svg"}}),t._v(" "),a("img",{staticClass:"logo-small",attrs:{width:"28",src:t.$root.getUrl+"public/img/amelia-logo-symbol.svg"}})]),t._v(" "),a("h1",{staticClass:"am-page-title"},[t._v("\n        "+t._s(t.bookableType?t.$root.labels[t.bookableType]:t.$router.currentRoute.meta.title)+"\n\n        "),t._v(" "),t.appointmentsApproved>=0?a("span",{staticClass:"am-appointments-number approved"},[t._v("\n          "+t._s(t.appointmentsApproved)+"\n        ")]):t._e(),t._v(" "),t.appointmentsPending>=0?a("span",{staticClass:"am-appointments-number pending"},[t._v("\n          "+t._s(t.appointmentsPending)+"\n        ")]):t._e(),t._v(" "),t.employeesTotal>=0&&!0===t.$root.settings.capabilities.canReadOthers?a("span",[a("span",{staticClass:"total-number"},[t._v(t._s(t.employeesTotal))]),t._v(" "+t._s(t.$root.labels.total)+"\n        ")]):t._e(),t._v(" "),t.customersTotal>=0?a("span",[a("span",{staticClass:"total-number"},[t._v(t._s(t.customersTotal))]),t._v(" "+t._s(t.$root.labels.total)+"\n        ")]):t._e(),t._v(" "),t.locationsTotal>=0?a("span",[a("span",{staticClass:"total-number"},[t._v(t._s(t.locationsTotal))]),t._v(" "+t._s(t.$root.labels.total)+"\n        ")]):t._e(),t._v(" "),t.servicesTotal>=0&&"services"===t.bookableType?a("span",[a("span",{staticClass:"total-number"},[t._v(t._s(t.servicesTotal))]),t._v(" "+t._s(t.$root.labels.total)+"\n        ")]):t._e(),t._v(" "),t.packagesTotal>=0&&"packages"===t.bookableType?a("span",[a("span",{staticClass:"total-number"},[t._v(t._s(t.packagesTotal))]),t._v(" "+t._s(t.$root.labels.total)+"\n        ")]):t._e(),t._v(" "),t.resourcesTotal>=0&&"resources"===t.bookableType?a("span",[a("span",{staticClass:"total-number"},[t._v(t._s(t.resourcesTotal))]),t._v(" "+t._s(t.$root.labels.total)+"\n        ")]):t._e(),t._v(" "),t.financeTotal>=0?a("span",[a("span",{staticClass:"total-number"},[t._v(t._s(t.financeTotal))]),t._v(" "+t._s(t.$root.labels.total)+"\n        ")]):t._e()])]),t._v(" "),a("el-col",{staticClass:"align-right v-calendar-column",attrs:{span:"wpamelia-calendar"===t.$router.currentRoute.name?18:6}},["wpamelia-appointments"===t.$router.currentRoute.name&&(!0===t.$root.settings.capabilities.canWriteOthers||"provider"===this.$root.settings.role&&this.$root.settings.roles.allowWriteAppointments)?a("el-button",{staticClass:"am-dialog-create",attrs:{type:"primary"},on:{click:t.showDialogAppointment}},[a("i",{staticClass:"el-icon-plus"}),t._v(" "),a("span",{staticClass:"button-text"},[t._v(t._s(t.$root.labels.new_appointment))])]):t._e(),t._v(" "),"wpamelia-events"===t.$router.currentRoute.name&&(!0===t.$root.settings.capabilities.canWriteOthers||"provider"===this.$root.settings.role&&this.$root.settings.roles.allowWriteEvents)?a("el-button",{staticClass:"am-dialog-create",attrs:{type:"primary"},on:{click:t.showDialogEvent}},[a("i",{staticClass:"el-icon-plus"}),t._v(" "),a("span",{staticClass:"button-text"},[t._v(t._s(t.$root.labels.new_event))])]):t._e(),t._v(" "),"wpamelia-employees"===t.$router.currentRoute.name&&!0===t.$root.settings.capabilities.canWrite&&!0===t.$root.settings.capabilities.canWriteOthers?a("el-button",{staticClass:"am-dialog-create",attrs:{type:"primary"},on:{click:t.showDialogEmployee}},[a("i",{staticClass:"el-icon-plus"}),t._v(" "),a("span",{staticClass:"button-text"},[t._v(t._s(t.$root.labels.add_employee))])]):t._e(),t._v(" "),"wpamelia-customers"===t.$router.currentRoute.name&&!0===t.$root.settings.capabilities.canWrite?a("el-button",{staticClass:"am-dialog-create",attrs:{type:"primary"},on:{click:t.showDialogCustomer}},[a("i",{staticClass:"el-icon-plus"}),t._v(" "),a("span",{staticClass:"button-text"},[t._v(t._s(t.$root.labels.add_customer))])]):t._e(),t._v(" "),"wpamelia-locations"===t.$router.currentRoute.name&&!0===t.$root.settings.capabilities.canWrite?a("el-button",{staticClass:"am-dialog-create",class:t.licenceClassDisabled(),attrs:{type:"primary",disabled:t.notInLicence()},on:{click:t.showDialogLocation}},[a("i",{staticClass:"el-icon-plus"}),t._v(" "),a("span",{staticClass:"button-text"},[t._v(t._s(t.$root.labels.add_location))])]):t._e(),t._v(" "),"wpamelia-services"===t.$router.currentRoute.name&&t.categoriesTotal>0&&!0===t.$root.settings.capabilities.canWrite&&"services"===t.bookableType?a("el-button",{staticClass:"am-dialog-create",attrs:{type:"primary"},on:{click:t.showDialogService}},[a("i",{staticClass:"el-icon-plus"}),t._v(" "),a("span",{staticClass:"button-text"},[t._v(t._s(t.$root.labels.add_service))])]):t._e(),t._v(" "),"wpamelia-services"===t.$router.currentRoute.name&&!0===t.$root.settings.capabilities.canWrite&&"packages"===t.bookableType?a("el-button",{staticClass:"am-dialog-create",class:t.licenceClassDisabled("pro"),attrs:{type:"primary",disabled:t.notInLicence("pro")},on:{click:function(e){t.packagesTotal>=0?t.showDialogPackage():t.showDialogPackageBooking()}}},[a("i",{staticClass:"el-icon-plus"}),t._v(" "),a("span",{staticClass:"button-text"},[t._v(t._s(t.packagesTotal>=0?t.$root.labels.add_package:t.$root.labels.book_package))])]):t._e(),t._v(" "),"wpamelia-services"===t.$router.currentRoute.name&&!0===t.$root.settings.capabilities.canWrite&&"resources"===t.bookableType?a("el-button",{staticClass:"am-dialog-create",class:t.licenceClassDisabled("pro"),attrs:{type:"primary",disabled:t.notInLicence("pro")},on:{click:function(e){return t.showDialogResource()}}},[a("i",{staticClass:"el-icon-plus"}),t._v(" "),a("span",{staticClass:"button-text"},[t._v(t._s(t.$root.labels.add_resource))])]):t._e(),t._v(" "),"wpamelia-finance"===t.$router.currentRoute.name&&t.addNewTaxBtnDisplay&&!0===t.$root.settings.capabilities.canWrite?a("el-button",{staticClass:"am-dialog-create",class:t.licenceClassDisabled(),attrs:{type:"primary",disabled:t.notInLicence()},on:{click:t.showDialogTax}},[a("i",{staticClass:"el-icon-plus"}),t._v(" "),a("span",{staticClass:"button-text"},[t._v(t._s(t.$root.labels.new_tax))])]):t._e(),t._v(" "),"wpamelia-finance"===t.$router.currentRoute.name&&t.addNewCouponBtnDisplay&&!0===t.$root.settings.capabilities.canWrite?a("el-button",{staticClass:"am-dialog-create",class:t.licenceClassDisabled("starter"),attrs:{type:"primary",disabled:t.notInLicence("starter")},on:{click:t.showDialogCoupon}},[a("i",{staticClass:"el-icon-plus"}),t._v(" "),a("span",{staticClass:"button-text"},[t._v(t._s(t.$root.labels.new_coupon))])]):t._e(),t._v(" "),a("transition",{attrs:{name:"fade"}},["wpamelia-cf"===t.$router.currentRoute.name&&t.addNewCustomFieldBtnDisplay?a("el-button",{staticClass:"am-dialog-create",attrs:{type:"primary"},on:{click:t.showDialogCustomFields}},[a("i",{staticClass:"el-icon-plus"}),t._v(" "),a("span",{staticClass:"button-text"},[t._v(t._s(t.$root.labels.add_custom_field))])]):t._e()],1),t._v(" "),"wpamelia-dashboard"===t.$router.currentRoute.name?a("div",{staticClass:"v-calendar-column"},[a("div",{staticClass:"el-form-item__content"},[a("v-date-picker",{attrs:{mode:"range","popover-visibility":"focus","popover-direction":"bottom","popover-align":"right","tint-color":"#1A84EE","show-day-popover":!1,"input-props":{class:"el-input__inner"},"is-expanded":!1,"is-required":!0,"input-class":"el-input__inner",formats:t.vCalendarFormats,"is-double-paned":!0},on:{input:t.changeFilter},model:{value:t.params.dates,callback:function(e){t.$set(t.params,"dates",e)},expression:"params.dates"}})],1)]):t._e(),t._v(" "),"wpamelia-calendar"===t.$router.currentRoute.name?a("div",{staticClass:"am-calendar-header-filters"},[a("el-form",{staticClass:"demo-form-inline",attrs:{inline:!0}},[a("el-form-item",{attrs:{label:t.$root.labels.services+":"}},[a("el-select",{attrs:{multiple:"",filterable:"","collapse-tags":"",loading:!t.fetched,placeholder:t.$root.labels.all_services},on:{change:t.changeFilter},model:{value:t.params.services,callback:function(e){t.$set(t.params,"services",e)},expression:"params.services"}},t._l(t.categories,function(e){return a("div",{key:e.id},[a("div",{staticClass:"am-drop-parent",on:{click:function(a){return t.selectAllInCategory(e.id)}}},[a("span",[t._v(t._s(e.name))])]),t._v(" "),t._l(e.serviceList,function(t){return a("el-option",{key:t.id,staticClass:"am-drop-child",attrs:{label:t.name,value:t.id}})})],2)}),0)],1),t._v(" "),a("el-form-item",{directives:[{name:"show",rawName:"v-show",value:t.locations.length,expression:"locations.length"}],attrs:{label:t.$root.labels.locations+":"}},[a("el-select",{attrs:{multiple:"",clearable:"","collapse-tags":"",placeholder:t.$root.labels.all_locations,loading:!t.fetched},on:{change:t.changeFilter},model:{value:t.params.locations,callback:function(e){t.$set(t.params,"locations",e)},expression:"params.locations"}},t._l(t.locations,function(t){return a("el-option",{key:t.id,attrs:{label:t.name,value:t.id}})}),1)],1)],1),t._v(" "),"wpamelia-calendar"===t.$router.currentRoute.name&&("admin"===t.$root.settings.role||"manager"===t.$root.settings.role||"provider"===t.$root.settings.role&&t.$root.settings.roles.allowWriteAppointments)?a("el-button",{staticClass:"am-dialog-create",attrs:{type:"primary"},on:{click:t.showDialogAppointment}},[a("i",{staticClass:"el-icon-plus"}),t._v(" "),a("span",{staticClass:"button-text"},[t._v(t._s(t.$root.labels.new_appointment))])]):t._e()],1):t._e(),t._v(" "),t.oldCustomize&&"wpamelia-customize"===t.$router.currentRoute.name?a("div",{staticClass:"am-calendar-header-filters"},[a("el-button",{staticClass:"am-dialog-create",attrs:{type:"primary"},on:{click:t.showMainCustomize}},[a("span",{staticClass:"button-text"},[t._v(t._s(t.$root.labels.go_back))])])],1):t._e()],1)],1)],1)},staticRenderFns:[]}}});