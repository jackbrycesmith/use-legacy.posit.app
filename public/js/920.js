(self.webpackChunk=self.webpackChunk||[]).push([[920],{2619:(t,e,s)=>{"use strict";s.d(e,{Z:()=>n});const n=function(t,e,s,n){for(var r=t.length,o=s+(n?1:-1);n?o--:++o<r;)if(e(t[o],o,t))return o;return-1}},5389:(t,e,s)=>{"use strict";s.d(e,{Z:()=>a});var n=s(2619);const r=function(t){return t!=t};const o=function(t,e,s){for(var n=s-1,r=t.length;++n<r;)if(t[n]===e)return n;return-1};const a=function(t,e,s){return e==e?o(t,e,s):(0,n.Z)(t,r,s)}},8325:(t,e,s)=>{"use strict";s.d(e,{Z:()=>p});var n=s(3503),r=s(6581),o=s(5389);const a=function(t,e){for(var s=t.length;s--&&(0,o.Z)(e,t[s],0)>-1;);return s};const i=function(t,e){for(var s=-1,n=t.length;++s<n&&(0,o.Z)(e,t[s],0)>-1;);return s};var l=s(945),u=s(3633),c=/^\s+|\s+$/g;const p=function(t,e,s){if((t=(0,u.Z)(t))&&(s||void 0===e))return t.replace(c,"");if(!t||!(e=(0,n.Z)(e)))return t;var o=(0,l.Z)(t),p=(0,l.Z)(e),f=i(o,p),d=a(o,p)+1;return(0,r.Z)(o,f,d).join("")}},7084:(t,e,s)=>{"use strict";s.d(e,{Z:()=>r});const n={props:["on"]};const r=(0,s(1900).Z)(n,(function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",[s("transition",{attrs:{"leave-active-class":"transition ease-in duration-1000","leave-class":"opacity-100","leave-to-class":"opacity-0"}},[s("div",{directives:[{name:"show",rawName:"v-show",value:t.on,expression:"on"}],staticClass:"text-sm text-gray-600"},[t._t("default")],2)])],1)}),[],!1,null,null,null).exports},8517:(t,e,s)=>{"use strict";s.d(e,{Z:()=>r});const n={props:{type:{type:String,default:"submit"}}};const r=(0,s(1900).Z)(n,(function(){var t=this,e=t.$createElement;return(t._self._c||e)("button",{staticClass:"inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150",attrs:{type:t.type}},[t._t("default")],2)}),[],!1,null,null,null).exports},9311:(t,e,s)=>{"use strict";s.d(e,{Z:()=>r});const n={components:{JetSectionTitle:s(3677).Z},computed:{hasActions:function(){return!!this.$slots.actions}}};const r=(0,s(1900).Z)(n,(function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"md:grid md:grid-cols-3 md:gap-6"},[s("jet-section-title",{scopedSlots:t._u([{key:"title",fn:function(){return[t._t("title")]},proxy:!0},{key:"description",fn:function(){return[t._t("description")]},proxy:!0}],null,!0)}),t._v(" "),s("div",{staticClass:"mt-5 md:mt-0 md:col-span-2"},[s("form",{on:{submit:function(e){return e.preventDefault(),t.$emit("submitted")}}},[s("div",{staticClass:"shadow overflow-hidden sm:rounded-md"},[s("div",{staticClass:"px-4 py-5 bg-white sm:p-6"},[s("div",{staticClass:"grid grid-cols-6 gap-6"},[t._t("form")],2)]),t._v(" "),t.hasActions?s("div",{staticClass:"flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6"},[t._t("actions")],2):t._e()])])])],1)}),[],!1,null,null,null).exports},7807:(t,e,s)=>{"use strict";s.d(e,{Z:()=>r});const n={props:["value"],methods:{focus:function(){this.$refs.input.focus()}}};const r=(0,s(1900).Z)(n,(function(){var t=this,e=t.$createElement;return(t._self._c||e)("input",{ref:"input",staticClass:"form-input rounded-md shadow-sm",domProps:{value:t.value},on:{input:function(e){return t.$emit("input",e.target.value)}}})}),[],!1,null,null,null).exports},5592:(t,e,s)=>{"use strict";s.d(e,{Z:()=>r});const n={props:["message"]};const r=(0,s(1900).Z)(n,(function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{directives:[{name:"show",rawName:"v-show",value:t.message,expression:"message"}]},[s("p",{staticClass:"text-sm text-red-600"},[t._v("\n        "+t._s(t.message)+"\n    ")])])}),[],!1,null,null,null).exports},9896:(t,e,s)=>{"use strict";s.d(e,{Z:()=>r});const n={props:["value"]};const r=(0,s(1900).Z)(n,(function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("label",{staticClass:"block font-medium text-sm text-gray-700"},[t.value?s("span",[t._v(t._s(t.value))]):s("span",[t._t("default")],2)])}),[],!1,null,null,null).exports},7630:(t,e,s)=>{"use strict";s.d(e,{Z:()=>r});const n={props:{type:{type:String,default:"button"}}};const r=(0,s(1900).Z)(n,(function(){var t=this,e=t.$createElement;return(t._self._c||e)("button",{staticClass:"inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150",attrs:{type:t.type}},[t._t("default")],2)}),[],!1,null,null,null).exports},3677:(t,e,s)=>{"use strict";s.d(e,{Z:()=>n});const n=(0,s(1900).Z)({},(function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"md:col-span-1"},[s("div",{staticClass:"px-4 sm:px-0"},[s("h3",{staticClass:"text-lg font-medium text-gray-900"},[t._t("title")],2),t._v(" "),s("p",{staticClass:"mt-1 text-sm text-gray-600"},[t._t("description")],2)])])}),[],!1,null,null,null).exports},7920:(t,e,s)=>{"use strict";s.r(e),s.d(e,{default:()=>d});var n=s(8517),r=s(9311),o=s(7807),a=s(5592),i=s(9896),l=s(7084),u=s(7630),c=s(8325),p=s(6472);const f={components:{JetActionMessage:l.Z,JetButton:n.Z,JetFormSection:r.Z,JetInput:o.Z,JetInputError:a.Z,JetLabel:i.Z,JetSecondaryButton:u.Z},props:{user:Object},data:function(){return{form:this.$inertia.form({_method:"PUT",name:this.user.name,email:this.user.email,photo:null},{bag:"updateProfileInformation",resetOnSuccess:!1}),photoPreview:null}},computed:{userAvatarInitial:function(){var t=(0,c.Z)(this.$page.props.user.name);return t&&t.length>0?(0,p.e)(t):(0,p.e)(this.$page.props.user.email)}},methods:{updateProfileInformation:function(){this.$refs.photo&&(this.form.photo=this.$refs.photo.files[0]),this.form.post("/user/profile-information",{preserveScroll:!0})},selectNewPhoto:function(){this.$refs.photo.click()},updatePhotoPreview:function(){var t=this,e=new FileReader;e.onload=function(e){t.photoPreview=e.target.result},e.readAsDataURL(this.$refs.photo.files[0])},deletePhoto:function(){var t=this;this.$inertia.delete("/user/profile-photo",{preserveScroll:!0}).then((function(){t.photoPreview=null}))}}};const d=(0,s(1900).Z)(f,(function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("jet-form-section",{on:{submitted:t.updateProfileInformation},scopedSlots:t._u([{key:"title",fn:function(){return[t._v("\n        Profile Information\n    ")]},proxy:!0},{key:"description",fn:function(){return[t._v("\n        Update your account's profile information and email address.\n    ")]},proxy:!0},{key:"form",fn:function(){return[t.$page.props.jetstream.managesProfilePhotos?s("div",{staticClass:"col-span-6 sm:col-span-4"},[s("input",{ref:"photo",staticClass:"hidden",attrs:{type:"file"},on:{change:t.updatePhotoPreview}}),t._v(" "),s("jet-label",{attrs:{for:"photo",value:"Photo"}}),t._v(" "),s("div",{directives:[{name:"show",rawName:"v-show",value:!t.photoPreview,expression:"! photoPreview"}],staticClass:"mt-2"},[t.user.profile_photo_url?s("img",{staticClass:"rounded-full h-20 w-20 object-cover",attrs:{src:t.user.profile_photo_url,alt:"Current Profile Photo"}}):s("div",{staticClass:"h-20 w-20 rounded-full bg-primary-yellow-400 text-gray-900 font-semibold flex items-center justify-center text-2xl"},[t._v("\n                "+t._s(t.userAvatarInitial)+"\n              ")])]),t._v(" "),s("div",{directives:[{name:"show",rawName:"v-show",value:t.photoPreview,expression:"photoPreview"}],staticClass:"mt-2"},[s("span",{staticClass:"block rounded-full w-20 h-20",style:"background-size: cover; background-repeat: no-repeat; background-position: center center; background-image: url('"+t.photoPreview+"');"})]),t._v(" "),s("jet-secondary-button",{staticClass:"mt-2 mr-2",attrs:{type:"button"},nativeOn:{click:function(e){return e.preventDefault(),t.selectNewPhoto(e)}}},[t._v("\n                Select A New Photo\n            ")]),t._v(" "),t.user.profile_photo_path?s("jet-secondary-button",{staticClass:"mt-2",attrs:{type:"button"},nativeOn:{click:function(e){return e.preventDefault(),t.deletePhoto(e)}}},[t._v("\n                Remove Photo\n            ")]):t._e(),t._v(" "),s("jet-input-error",{staticClass:"mt-2",attrs:{message:t.form.error("photo")}})],1):t._e(),t._v(" "),s("div",{staticClass:"col-span-6 sm:col-span-4"},[s("jet-label",{attrs:{for:"name",value:"Name"}}),t._v(" "),s("jet-input",{staticClass:"mt-1 block w-full",attrs:{id:"name",type:"text",autocomplete:"name"},model:{value:t.form.name,callback:function(e){t.$set(t.form,"name",e)},expression:"form.name"}}),t._v(" "),s("jet-input-error",{staticClass:"mt-2",attrs:{message:t.form.error("name")}})],1),t._v(" "),s("div",{staticClass:"col-span-6 sm:col-span-4"},[s("jet-label",{attrs:{for:"email",value:"Email"}}),t._v(" "),s("jet-input",{staticClass:"mt-1 block w-full",attrs:{id:"email",type:"email"},model:{value:t.form.email,callback:function(e){t.$set(t.form,"email",e)},expression:"form.email"}}),t._v(" "),s("jet-input-error",{staticClass:"mt-2",attrs:{message:t.form.error("email")}})],1)]},proxy:!0},{key:"actions",fn:function(){return[s("jet-action-message",{staticClass:"mr-3",attrs:{on:t.form.recentlySuccessful}},[t._v("\n            Saved.\n        ")]),t._v(" "),s("jet-button",{class:{"opacity-25":t.form.processing},attrs:{disabled:t.form.processing}},[t._v("\n            Save\n        ")])]},proxy:!0}])})}),[],!1,null,null,null).exports},1900:(t,e,s)=>{"use strict";function n(t,e,s,n,r,o,a,i){var l,u="function"==typeof t?t.options:t;if(e&&(u.render=e,u.staticRenderFns=s,u._compiled=!0),n&&(u.functional=!0),o&&(u._scopeId="data-v-"+o),a?(l=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),r&&r.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(a)},u._ssrRegister=l):r&&(l=i?function(){r.call(this,(u.functional?this.parent:this).$root.$options.shadowRoot)}:r),l)if(u.functional){u._injectStyles=l;var c=u.render;u.render=function(t,e){return l.call(e),c(t,e)}}else{var p=u.beforeCreate;u.beforeCreate=p?[].concat(p,l):[l]}return{exports:t,options:u}}s.d(e,{Z:()=>n})}}]);