(self.webpackChunk=self.webpackChunk||[]).push([[472],{1027:(e,t,s)=>{"use strict";s.d(t,{Z:()=>o});const n={props:["on"]};const o=(0,s(1900).Z)(n,(function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("div",[s("transition",{attrs:{"leave-active-class":"transition ease-in duration-1000","leave-class":"opacity-100","leave-to-class":"opacity-0"}},[s("div",{directives:[{name:"show",rawName:"v-show",value:e.on,expression:"on"}],staticClass:"text-sm text-gray-600"},[e._t("default")],2)])],1)}),[],!1,null,null,null).exports},7173:(e,t,s)=>{"use strict";s.d(t,{Z:()=>o});const n={components:{JetSectionTitle:s(4459).Z}};const o=(0,s(1900).Z)(n,(function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("div",{staticClass:"md:grid md:grid-cols-3 md:gap-6"},[s("jet-section-title",{scopedSlots:e._u([{key:"title",fn:function(){return[e._t("title")]},proxy:!0},{key:"description",fn:function(){return[e._t("description")]},proxy:!0}],null,!0)}),e._v(" "),s("div",{staticClass:"mt-5 md:mt-0 md:col-span-2"},[s("div",{staticClass:"px-4 py-5 sm:p-6 bg-white shadow sm:rounded-lg"},[e._t("content")],2)])],1)}),[],!1,null,null,null).exports},4359:(e,t,s)=>{"use strict";s.d(t,{Z:()=>o});const n={props:{type:{type:String,default:"submit"}}};const o=(0,s(1900).Z)(n,(function(){var e=this,t=e.$createElement;return(e._self._c||t)("button",{staticClass:"inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150",attrs:{type:e.type}},[e._t("default")],2)}),[],!1,null,null,null).exports},556:(e,t,s)=>{"use strict";s.d(t,{Z:()=>o});const n={components:{Modal:s(933).Z},props:{show:{default:!1},maxWidth:{default:"2xl"},closeable:{default:!0}},methods:{close:function(){this.$emit("close")}}};const o=(0,s(1900).Z)(n,(function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("modal",{attrs:{show:e.show,"max-width":e.maxWidth,closeable:e.closeable},on:{close:e.close}},[s("div",{staticClass:"bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4"},[s("div",{staticClass:"sm:flex sm:items-start"},[s("div",{staticClass:"mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10"},[s("svg",{staticClass:"h-6 w-6 text-red-600",attrs:{stroke:"currentColor",fill:"none",viewBox:"0 0 24 24"}},[s("path",{attrs:{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"}})])]),e._v(" "),s("div",{staticClass:"mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left"},[s("h3",{staticClass:"text-lg"},[e._t("title")],2),e._v(" "),s("div",{staticClass:"mt-2"},[e._t("content")],2)])])]),e._v(" "),s("div",{staticClass:"px-6 py-4 bg-gray-100 text-right"},[e._t("footer")],2)])}),[],!1,null,null,null).exports},109:(e,t,s)=>{"use strict";s.d(t,{Z:()=>o});const n={props:{type:{type:String,default:"button"}}};const o=(0,s(1900).Z)(n,(function(){var e=this,t=e.$createElement;return(e._self._c||t)("button",{staticClass:"inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:border-red-700 focus:shadow-outline-red active:bg-red-600 transition ease-in-out duration-150",attrs:{type:e.type}},[e._t("default")],2)}),[],!1,null,null,null).exports},1409:(e,t,s)=>{"use strict";s.d(t,{Z:()=>o});const n={components:{Modal:s(933).Z},props:{show:{default:!1},maxWidth:{default:"2xl"},closeable:{default:!0}},methods:{close:function(){this.$emit("close")}}};const o=(0,s(1900).Z)(n,(function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("modal",{attrs:{show:e.show,"max-width":e.maxWidth,closeable:e.closeable},on:{close:e.close}},[s("div",{staticClass:"px-6 py-4"},[s("div",{staticClass:"text-lg"},[e._t("title")],2),e._v(" "),s("div",{staticClass:"mt-4"},[e._t("content")],2)]),e._v(" "),s("div",{staticClass:"px-6 py-4 bg-gray-100 text-right"},[e._t("footer")],2)])}),[],!1,null,null,null).exports},3292:(e,t,s)=>{"use strict";s.d(t,{Z:()=>o});const n={components:{JetSectionTitle:s(4459).Z},computed:{hasActions:function(){return!!this.$slots.actions}}};const o=(0,s(1900).Z)(n,(function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("div",{staticClass:"md:grid md:grid-cols-3 md:gap-6"},[s("jet-section-title",{scopedSlots:e._u([{key:"title",fn:function(){return[e._t("title")]},proxy:!0},{key:"description",fn:function(){return[e._t("description")]},proxy:!0}],null,!0)}),e._v(" "),s("div",{staticClass:"mt-5 md:mt-0 md:col-span-2"},[s("form",{on:{submit:function(t){return t.preventDefault(),e.$emit("submitted")}}},[s("div",{staticClass:"shadow overflow-hidden sm:rounded-md"},[s("div",{staticClass:"px-4 py-5 bg-white sm:p-6"},[s("div",{staticClass:"grid grid-cols-6 gap-6"},[e._t("form")],2)]),e._v(" "),e.hasActions?s("div",{staticClass:"flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6"},[e._t("actions")],2):e._e()])])])],1)}),[],!1,null,null,null).exports},9006:(e,t,s)=>{"use strict";s.d(t,{Z:()=>o});const n={props:["value"],methods:{focus:function(){this.$refs.input.focus()}}};const o=(0,s(1900).Z)(n,(function(){var e=this,t=e.$createElement;return(e._self._c||t)("input",{ref:"input",staticClass:"border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm",domProps:{value:e.value},on:{input:function(t){return e.$emit("input",t.target.value)}}})}),[],!1,null,null,null).exports},7804:(e,t,s)=>{"use strict";s.d(t,{Z:()=>o});const n={props:["message"]};const o=(0,s(1900).Z)(n,(function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("div",{directives:[{name:"show",rawName:"v-show",value:e.message,expression:"message"}]},[s("p",{staticClass:"text-sm text-red-600"},[e._v("\n        "+e._s(e.message)+"\n    ")])])}),[],!1,null,null,null).exports},5667:(e,t,s)=>{"use strict";s.d(t,{Z:()=>o});const n={props:["value"]};const o=(0,s(1900).Z)(n,(function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("label",{staticClass:"block font-medium text-sm text-gray-700"},[e.value?s("span",[e._v(e._s(e.value))]):s("span",[e._t("default")],2)])}),[],!1,null,null,null).exports},933:(e,t,s)=>{"use strict";s.d(t,{Z:()=>o});const n={props:{show:{default:!1},maxWidth:{default:"2xl"},closeable:{default:!0}},methods:{close:function(){this.closeable&&this.$emit("close")}},watch:{show:{immediate:!0,handler:function(e){document.body.style.overflow=e?"hidden":null}}},created:function(){var e=this,t=function(t){"Escape"===t.key&&e.show&&e.close()};document.addEventListener("keydown",t),this.$once("hook:destroyed",(function(){document.removeEventListener("keydown",t)}))},computed:{maxWidthClass:function(){return{sm:"sm:max-w-sm",md:"sm:max-w-md",lg:"sm:max-w-lg",xl:"sm:max-w-xl","2xl":"sm:max-w-2xl"}[this.maxWidth]}}};const o=(0,s(1900).Z)(n,(function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("portal",{attrs:{to:"modal"}},[s("transition",{attrs:{"leave-active-class":"duration-200"}},[s("div",{directives:[{name:"show",rawName:"v-show",value:e.show,expression:"show"}],staticClass:"fixed top-0 inset-x-0 px-4 pt-6 sm:px-0 sm:flex sm:items-top sm:justify-center"},[s("transition",{attrs:{"enter-active-class":"ease-out duration-300","enter-class":"opacity-0","enter-to-class":"opacity-100","leave-active-class":"ease-in duration-200","leave-class":"opacity-100","leave-to-class":"opacity-0"}},[s("div",{directives:[{name:"show",rawName:"v-show",value:e.show,expression:"show"}],staticClass:"fixed inset-0 transform transition-all",on:{click:e.close}},[s("div",{staticClass:"absolute inset-0 bg-gray-500 opacity-75"})])]),e._v(" "),s("transition",{attrs:{"enter-active-class":"ease-out duration-300","enter-class":"opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95","enter-to-class":"opacity-100 translate-y-0 sm:scale-100","leave-active-class":"ease-in duration-200","leave-class":"opacity-100 translate-y-0 sm:scale-100","leave-to-class":"opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"}},[s("div",{directives:[{name:"show",rawName:"v-show",value:e.show,expression:"show"}],staticClass:"bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full",class:e.maxWidthClass},[e._t("default")],2)])],1)])],1)}),[],!1,null,null,null).exports},4760:(e,t,s)=>{"use strict";s.d(t,{Z:()=>o});const n={props:{type:{type:String,default:"button"}}};const o=(0,s(1900).Z)(n,(function(){var e=this,t=e.$createElement;return(e._self._c||t)("button",{staticClass:"inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150",attrs:{type:e.type}},[e._t("default")],2)}),[],!1,null,null,null).exports},8195:(e,t,s)=>{"use strict";s.d(t,{Z:()=>n});const n=(0,s(1900).Z)({},(function(){var e=this,t=e.$createElement;e._self._c;return e._m(0)}),[function(){var e=this.$createElement,t=this._self._c||e;return t("div",{staticClass:"hidden sm:block"},[t("div",{staticClass:"py-8"},[t("div",{staticClass:"border-t border-gray-200"})])])}],!1,null,null,null).exports},4459:(e,t,s)=>{"use strict";s.d(t,{Z:()=>n});const n=(0,s(1900).Z)({},(function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("div",{staticClass:"md:col-span-1"},[s("div",{staticClass:"px-4 sm:px-0"},[s("h3",{staticClass:"text-lg font-medium text-gray-900"},[e._t("title")],2),e._v(" "),s("p",{staticClass:"mt-1 text-sm text-gray-600"},[e._t("description")],2)])])}),[],!1,null,null,null).exports},8472:(e,t,s)=>{"use strict";s.r(t),s.d(t,{default:()=>h});var n=s(1027),o=s(7173),i=s(4359),r=s(556),a=s(109),l=s(1409),c=s(3292),u=s(9006),d=s(7804),p=s(5667),m=s(4760),f=s(8195);const v={components:{JetActionMessage:n.Z,JetActionSection:o.Z,JetButton:i.Z,JetConfirmationModal:r.Z,JetDangerButton:a.Z,JetDialogModal:l.Z,JetFormSection:c.Z,JetInput:u.Z,JetInputError:d.Z,JetLabel:p.Z,JetSecondaryButton:m.Z,JetSectionBorder:f.Z},props:["tokens","availablePermissions","defaultPermissions"],data:function(){return{createApiTokenForm:this.$inertia.form({name:"",permissions:this.defaultPermissions},{bag:"createApiToken",resetOnSuccess:!0}),updateApiTokenForm:this.$inertia.form({permissions:[]},{resetOnSuccess:!1,bag:"updateApiToken"}),deleteApiTokenForm:this.$inertia.form(),displayingToken:!1,managingPermissionsFor:null,apiTokenBeingDeleted:null}},methods:{createApiToken:function(){var e=this;this.createApiTokenForm.post("/user/api-tokens",{preserveScroll:!0}).then((function(t){e.createApiTokenForm.hasErrors()||(e.displayingToken=!0)}))},manageApiTokenPermissions:function(e){this.updateApiTokenForm.permissions=e.abilities,this.managingPermissionsFor=e},updateApiToken:function(){var e=this;this.updateApiTokenForm.put("/user/api-tokens/"+this.managingPermissionsFor.id,{preserveScroll:!0,preserveState:!0}).then((function(t){e.managingPermissionsFor=null}))},confirmApiTokenDeletion:function(e){this.apiTokenBeingDeleted=e},deleteApiToken:function(){var e=this;this.deleteApiTokenForm.delete("/user/api-tokens/"+this.apiTokenBeingDeleted.id,{preserveScroll:!0,preserveState:!0}).then((function(){e.apiTokenBeingDeleted=null}))},fromNow:function(e){return moment(e).local().fromNow()}}};const h=(0,s(1900).Z)(v,(function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("div",[s("jet-form-section",{on:{submitted:e.createApiToken},scopedSlots:e._u([{key:"title",fn:function(){return[e._v("\n      Create API Token\n    ")]},proxy:!0},{key:"description",fn:function(){return[e._v("\n      API tokens allow third-party services to authenticate with our application on your behalf.\n    ")]},proxy:!0},{key:"form",fn:function(){return[s("div",{staticClass:"col-span-6 sm:col-span-4"},[s("jet-label",{attrs:{for:"name",value:"Name"}}),e._v(" "),s("jet-input",{staticClass:"mt-1 block w-full",attrs:{id:"name",type:"text",autofocus:""},model:{value:e.createApiTokenForm.name,callback:function(t){e.$set(e.createApiTokenForm,"name",t)},expression:"createApiTokenForm.name"}}),e._v(" "),s("jet-input-error",{staticClass:"mt-2",attrs:{message:e.createApiTokenForm.error("name")}})],1),e._v(" "),e.availablePermissions.length>0?s("div",{staticClass:"col-span-6"},[s("jet-label",{attrs:{for:"permissions",value:"Permissions"}}),e._v(" "),s("div",{staticClass:"mt-2 grid grid-cols-1 md:grid-cols-2 gap-4"},e._l(e.availablePermissions,(function(t){return s("div",{key:t},[s("label",{staticClass:"flex items-center"},[s("input",{directives:[{name:"model",rawName:"v-model",value:e.createApiTokenForm.permissions,expression:"createApiTokenForm.permissions"}],staticClass:"rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50",attrs:{type:"checkbox"},domProps:{value:t,checked:Array.isArray(e.createApiTokenForm.permissions)?e._i(e.createApiTokenForm.permissions,t)>-1:e.createApiTokenForm.permissions},on:{change:function(s){var n=e.createApiTokenForm.permissions,o=s.target,i=!!o.checked;if(Array.isArray(n)){var r=t,a=e._i(n,r);o.checked?a<0&&e.$set(e.createApiTokenForm,"permissions",n.concat([r])):a>-1&&e.$set(e.createApiTokenForm,"permissions",n.slice(0,a).concat(n.slice(a+1)))}else e.$set(e.createApiTokenForm,"permissions",i)}}}),e._v(" "),s("span",{staticClass:"ml-2 text-sm text-gray-600"},[e._v(e._s(t))])])])})),0)],1):e._e()]},proxy:!0},{key:"actions",fn:function(){return[s("jet-action-message",{staticClass:"mr-3",attrs:{on:e.createApiTokenForm.recentlySuccessful}},[e._v("\n        Created.\n      ")]),e._v(" "),s("jet-button",{class:{"opacity-25":e.createApiTokenForm.processing},attrs:{disabled:e.createApiTokenForm.processing}},[e._v("\n        Create\n      ")])]},proxy:!0}])}),e._v(" "),e.tokens.length>0?s("div",[s("jet-section-border"),e._v(" "),s("div",{staticClass:"mt-10 sm:mt-0"},[s("jet-action-section",{scopedSlots:e._u([{key:"title",fn:function(){return[e._v("\n          Manage API Tokens\n        ")]},proxy:!0},{key:"description",fn:function(){return[e._v("\n          You may delete any of your existing tokens if they are no longer needed.\n        ")]},proxy:!0},{key:"content",fn:function(){return[s("div",{staticClass:"space-y-6"},e._l(e.tokens,(function(t){return s("div",{staticClass:"flex items-center justify-between"},[s("div",[e._v("\n                "+e._s(t.name)+"\n              ")]),e._v(" "),s("div",{staticClass:"flex items-center"},[t.last_used_at?s("div",{staticClass:"text-sm text-gray-400"},[e._v("\n                  Last used "+e._s(e.fromNow(t.last_used_at))+"\n                ")]):e._e(),e._v(" "),e.availablePermissions.length>0?s("button",{staticClass:"cursor-pointer ml-6 text-sm text-gray-400 underline focus:outline-none",on:{click:function(s){return e.manageApiTokenPermissions(t)}}},[e._v("\n                  Permissions\n                ")]):e._e(),e._v(" "),s("button",{staticClass:"cursor-pointer ml-6 text-sm text-red-500 focus:outline-none",on:{click:function(s){return e.confirmApiTokenDeletion(t)}}},[e._v("\n                  Delete\n                ")])])])})),0)]},proxy:!0}],null,!1,1144617728)})],1)],1):e._e(),e._v(" "),s("jet-dialog-modal",{attrs:{show:e.displayingToken},on:{close:function(t){e.displayingToken=!1}},scopedSlots:e._u([{key:"title",fn:function(){return[e._v("\n      API Token\n    ")]},proxy:!0},{key:"content",fn:function(){return[s("div",[e._v("\n        Please copy your new API token. For your security, it won't be shown again.\n      ")]),e._v(" "),e.$page.props.jetstream.flash.token?s("div",{staticClass:"mt-4 bg-gray-100 px-4 py-2 rounded font-mono text-sm text-gray-500"},[e._v("\n        "+e._s(e.$page.props.jetstream.flash.token)+"\n      ")]):e._e()]},proxy:!0},{key:"footer",fn:function(){return[s("jet-secondary-button",{nativeOn:{click:function(t){e.displayingToken=!1}}},[e._v("\n        Close\n      ")])]},proxy:!0}])}),e._v(" "),s("jet-dialog-modal",{attrs:{show:e.managingPermissionsFor},on:{close:function(t){e.managingPermissionsFor=null}},scopedSlots:e._u([{key:"title",fn:function(){return[e._v("\n      API Token Permissions\n    ")]},proxy:!0},{key:"content",fn:function(){return[s("div",{staticClass:"grid grid-cols-1 md:grid-cols-2 gap-4"},e._l(e.availablePermissions,(function(t){return s("div",{key:t},[s("label",{staticClass:"flex items-center"},[s("input",{directives:[{name:"model",rawName:"v-model",value:e.updateApiTokenForm.permissions,expression:"updateApiTokenForm.permissions"}],staticClass:"rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50",attrs:{type:"checkbox"},domProps:{value:t,checked:Array.isArray(e.updateApiTokenForm.permissions)?e._i(e.updateApiTokenForm.permissions,t)>-1:e.updateApiTokenForm.permissions},on:{change:function(s){var n=e.updateApiTokenForm.permissions,o=s.target,i=!!o.checked;if(Array.isArray(n)){var r=t,a=e._i(n,r);o.checked?a<0&&e.$set(e.updateApiTokenForm,"permissions",n.concat([r])):a>-1&&e.$set(e.updateApiTokenForm,"permissions",n.slice(0,a).concat(n.slice(a+1)))}else e.$set(e.updateApiTokenForm,"permissions",i)}}}),e._v(" "),s("span",{staticClass:"ml-2 text-sm text-gray-600"},[e._v(e._s(t))])])])})),0)]},proxy:!0},{key:"footer",fn:function(){return[s("jet-secondary-button",{nativeOn:{click:function(t){e.managingPermissionsFor=null}}},[e._v("\n        Nevermind\n      ")]),e._v(" "),s("jet-button",{staticClass:"ml-2",class:{"opacity-25":e.updateApiTokenForm.processing},attrs:{disabled:e.updateApiTokenForm.processing},nativeOn:{click:function(t){return e.updateApiToken(t)}}},[e._v("\n        Save\n      ")])]},proxy:!0}])}),e._v(" "),s("jet-confirmation-modal",{attrs:{show:e.apiTokenBeingDeleted},on:{close:function(t){e.apiTokenBeingDeleted=null}},scopedSlots:e._u([{key:"title",fn:function(){return[e._v("\n      Delete API Token\n    ")]},proxy:!0},{key:"content",fn:function(){return[e._v("\n      Are you sure you would like to delete this API token?\n    ")]},proxy:!0},{key:"footer",fn:function(){return[s("jet-secondary-button",{nativeOn:{click:function(t){e.apiTokenBeingDeleted=null}}},[e._v("\n        Nevermind\n      ")]),e._v(" "),s("jet-danger-button",{staticClass:"ml-2",class:{"opacity-25":e.deleteApiTokenForm.processing},attrs:{disabled:e.deleteApiTokenForm.processing},nativeOn:{click:function(t){return e.deleteApiToken(t)}}},[e._v("\n        Delete\n      ")])]},proxy:!0}])})],1)}),[],!1,null,null,null).exports},1900:(e,t,s)=>{"use strict";function n(e,t,s,n,o,i,r,a){var l,c="function"==typeof e?e.options:e;if(t&&(c.render=t,c.staticRenderFns=s,c._compiled=!0),n&&(c.functional=!0),i&&(c._scopeId="data-v-"+i),r?(l=function(e){(e=e||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(e=__VUE_SSR_CONTEXT__),o&&o.call(this,e),e&&e._registeredComponents&&e._registeredComponents.add(r)},c._ssrRegister=l):o&&(l=a?function(){o.call(this,(c.functional?this.parent:this).$root.$options.shadowRoot)}:o),l)if(c.functional){c._injectStyles=l;var u=c.render;c.render=function(e,t){return l.call(t),u(e,t)}}else{var d=c.beforeCreate;c.beforeCreate=d?[].concat(d,l):[l]}return{exports:e,options:c}}s.d(t,{Z:()=>n})}}]);