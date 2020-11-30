(self.webpackChunk=self.webpackChunk||[]).push([[511],{297:(t,e,s)=>{"use strict";s.d(e,{Z:()=>o});const n={components:{JetSectionTitle:s(3677).Z}};const o=(0,s(1900).Z)(n,(function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"md:grid md:grid-cols-3 md:gap-6"},[s("jet-section-title",{scopedSlots:t._u([{key:"title",fn:function(){return[t._t("title")]},proxy:!0},{key:"description",fn:function(){return[t._t("description")]},proxy:!0}],null,!0)}),t._v(" "),s("div",{staticClass:"mt-5 md:mt-0 md:col-span-2"},[s("div",{staticClass:"px-4 py-5 sm:p-6 bg-white shadow sm:rounded-lg"},[t._t("content")],2)])],1)}),[],!1,null,null,null).exports},8517:(t,e,s)=>{"use strict";s.d(e,{Z:()=>o});const n={props:{type:{type:String,default:"submit"}}};const o=(0,s(1900).Z)(n,(function(){var t=this,e=t.$createElement;return(t._self._c||e)("button",{staticClass:"inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150",attrs:{type:t.type}},[t._t("default")],2)}),[],!1,null,null,null).exports},4569:(t,e,s)=>{"use strict";s.d(e,{Z:()=>o});const n={props:{type:{type:String,default:"button"}}};const o=(0,s(1900).Z)(n,(function(){var t=this,e=t.$createElement;return(t._self._c||e)("button",{staticClass:"inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:border-red-700 focus:shadow-outline-red active:bg-red-600 transition ease-in-out duration-150",attrs:{type:t.type}},[t._t("default")],2)}),[],!1,null,null,null).exports},7346:(t,e,s)=>{"use strict";s.d(e,{Z:()=>o});const n={components:{Modal:s(5590).Z},props:{show:{default:!1},maxWidth:{default:"2xl"},closeable:{default:!0}},methods:{close:function(){this.$emit("close")}}};const o=(0,s(1900).Z)(n,(function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("modal",{attrs:{show:t.show,"max-width":t.maxWidth,closeable:t.closeable},on:{close:t.close}},[s("div",{staticClass:"px-6 py-4"},[s("div",{staticClass:"text-lg"},[t._t("title")],2),t._v(" "),s("div",{staticClass:"mt-4"},[t._t("content")],2)]),t._v(" "),s("div",{staticClass:"px-6 py-4 bg-gray-100 text-right"},[t._t("footer")],2)])}),[],!1,null,null,null).exports},4882:(t,e,s)=>{"use strict";s.d(e,{Z:()=>o});const n={props:["value"],methods:{focus:function(){this.$refs.input.focus()}}};const o=(0,s(1900).Z)(n,(function(){var t=this,e=t.$createElement;return(t._self._c||e)("input",{ref:"input",staticClass:"border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm",domProps:{value:t.value},on:{input:function(e){return t.$emit("input",e.target.value)}}})}),[],!1,null,null,null).exports},5592:(t,e,s)=>{"use strict";s.d(e,{Z:()=>o});const n={props:["message"]};const o=(0,s(1900).Z)(n,(function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{directives:[{name:"show",rawName:"v-show",value:t.message,expression:"message"}]},[s("p",{staticClass:"text-sm text-red-600"},[t._v("\n        "+t._s(t.message)+"\n    ")])])}),[],!1,null,null,null).exports},5590:(t,e,s)=>{"use strict";s.d(e,{Z:()=>o});const n={props:{show:{default:!1},maxWidth:{default:"2xl"},closeable:{default:!0}},methods:{close:function(){this.closeable&&this.$emit("close")}},watch:{show:{immediate:!0,handler:function(t){document.body.style.overflow=t?"hidden":null}}},created:function(){var t=this,e=function(e){"Escape"===e.key&&t.show&&t.close()};document.addEventListener("keydown",e),this.$once("hook:destroyed",(function(){document.removeEventListener("keydown",e)}))},computed:{maxWidthClass:function(){return{sm:"sm:max-w-sm",md:"sm:max-w-md",lg:"sm:max-w-lg",xl:"sm:max-w-xl","2xl":"sm:max-w-2xl"}[this.maxWidth]}}};const o=(0,s(1900).Z)(n,(function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("portal",{attrs:{to:"modal"}},[s("transition",{attrs:{"leave-active-class":"duration-200"}},[s("div",{directives:[{name:"show",rawName:"v-show",value:t.show,expression:"show"}],staticClass:"fixed top-0 inset-x-0 px-4 pt-6 sm:px-0 sm:flex sm:items-top sm:justify-center"},[s("transition",{attrs:{"enter-active-class":"ease-out duration-300","enter-class":"opacity-0","enter-to-class":"opacity-100","leave-active-class":"ease-in duration-200","leave-class":"opacity-100","leave-to-class":"opacity-0"}},[s("div",{directives:[{name:"show",rawName:"v-show",value:t.show,expression:"show"}],staticClass:"fixed inset-0 transform transition-all",on:{click:t.close}},[s("div",{staticClass:"absolute inset-0 bg-gray-500 opacity-75"})])]),t._v(" "),s("transition",{attrs:{"enter-active-class":"ease-out duration-300","enter-class":"opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95","enter-to-class":"opacity-100 translate-y-0 sm:scale-100","leave-active-class":"ease-in duration-200","leave-class":"opacity-100 translate-y-0 sm:scale-100","leave-to-class":"opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"}},[s("div",{directives:[{name:"show",rawName:"v-show",value:t.show,expression:"show"}],staticClass:"bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full",class:t.maxWidthClass},[t._t("default")],2)])],1)])],1)}),[],!1,null,null,null).exports},7630:(t,e,s)=>{"use strict";s.d(e,{Z:()=>o});const n={props:{type:{type:String,default:"button"}}};const o=(0,s(1900).Z)(n,(function(){var t=this,e=t.$createElement;return(t._self._c||e)("button",{staticClass:"inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150",attrs:{type:t.type}},[t._t("default")],2)}),[],!1,null,null,null).exports},3677:(t,e,s)=>{"use strict";s.d(e,{Z:()=>n});const n=(0,s(1900).Z)({},(function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"md:col-span-1"},[s("div",{staticClass:"px-4 sm:px-0"},[s("h3",{staticClass:"text-lg font-medium text-gray-900"},[t._t("title")],2),t._v(" "),s("p",{staticClass:"mt-1 text-sm text-gray-600"},[t._t("description")],2)])])}),[],!1,null,null,null).exports},5511:(t,e,s)=>{"use strict";s.r(e),s.d(e,{default:()=>d});var n=s(297),o=s(8517),r=s(7346),a=s(4569),i=s(4882),l=s(5592),c=s(7630);const u={components:{JetActionSection:n.Z,JetButton:o.Z,JetDangerButton:a.Z,JetDialogModal:r.Z,JetInput:i.Z,JetInputError:l.Z,JetSecondaryButton:c.Z},data:function(){return{confirmingUserDeletion:!1,deleting:!1,form:this.$inertia.form({_method:"DELETE",password:""},{bag:"deleteUser"})}},methods:{confirmUserDeletion:function(){var t=this;this.form.password="",this.confirmingUserDeletion=!0,setTimeout((function(){t.$refs.password.focus()}),250)},deleteUser:function(){var t=this;this.form.post("/user",{preserveScroll:!0}).then((function(e){t.form.hasErrors()||(t.confirmingUserDeletion=!1)}))}}};const d=(0,s(1900).Z)(u,(function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("jet-action-section",{scopedSlots:t._u([{key:"title",fn:function(){return[t._v("\n    Delete Account\n  ")]},proxy:!0},{key:"description",fn:function(){return[t._v("\n    Permanently delete your account.\n  ")]},proxy:!0},{key:"content",fn:function(){return[s("div",{staticClass:"max-w-xl text-sm text-gray-600"},[t._v("\n      Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.\n    ")]),t._v(" "),s("div",{staticClass:"mt-5"},[s("jet-danger-button",{nativeOn:{click:function(e){return t.confirmUserDeletion(e)}}},[t._v("\n        Delete Account\n      ")])],1),t._v(" "),s("jet-dialog-modal",{attrs:{show:t.confirmingUserDeletion},on:{close:function(e){t.confirmingUserDeletion=!1}},scopedSlots:t._u([{key:"title",fn:function(){return[t._v("\n        Delete Account\n      ")]},proxy:!0},{key:"content",fn:function(){return[t._v("\n        Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.\n        "),s("div",{staticClass:"mt-4"},[s("jet-input",{ref:"password",staticClass:"mt-1 block w-3/4",attrs:{type:"password",placeholder:"Password"},nativeOn:{keyup:function(e){return!e.type.indexOf("key")&&t._k(e.keyCode,"enter",13,e.key,"Enter")?null:t.deleteUser(e)}},model:{value:t.form.password,callback:function(e){t.$set(t.form,"password",e)},expression:"form.password"}}),t._v(" "),s("jet-input-error",{staticClass:"mt-2",attrs:{message:t.form.error("password")}})],1)]},proxy:!0},{key:"footer",fn:function(){return[s("jet-secondary-button",{nativeOn:{click:function(e){t.confirmingUserDeletion=!1}}},[t._v("\n          Nevermind\n        ")]),t._v(" "),s("jet-danger-button",{staticClass:"ml-2",class:{"opacity-25":t.form.processing},attrs:{disabled:t.form.processing},nativeOn:{click:function(e){return t.deleteUser(e)}}},[t._v("\n          Delete Account\n        ")])]},proxy:!0}])})]},proxy:!0}])})}),[],!1,null,null,null).exports},1900:(t,e,s)=>{"use strict";function n(t,e,s,n,o,r,a,i){var l,c="function"==typeof t?t.options:t;if(e&&(c.render=e,c.staticRenderFns=s,c._compiled=!0),n&&(c.functional=!0),r&&(c._scopeId="data-v-"+r),a?(l=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),o&&o.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(a)},c._ssrRegister=l):o&&(l=i?function(){o.call(this,(c.functional?this.parent:this).$root.$options.shadowRoot)}:o),l)if(c.functional){c._injectStyles=l;var u=c.render;c.render=function(t,e){return l.call(e),u(t,e)}}else{var d=c.beforeCreate;c.beforeCreate=d?[].concat(d,l):[l]}return{exports:t,options:c}}s.d(e,{Z:()=>n})}}]);