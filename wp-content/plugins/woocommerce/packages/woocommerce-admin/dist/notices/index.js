this.wc=this.wc||{},this.wc.notices=function(t){var e={};function n(r){if(e[r])return e[r].exports;var i=e[r]={i:r,l:!1,exports:{}};return t[r].call(i.exports,i,i.exports,n),i.l=!0,i.exports}return n.m=t,n.c=e,n.d=function(t,e,r){n.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:r})},n.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},n.t=function(t,e){if(1&e&&(t=n(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var r=Object.create(null);if(n.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var i in t)n.d(r,i,function(e){return t[e]}.bind(null,i));return r},n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,"a",e),e},n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},n.p="",n(n.s=468)}({129:function(t,e){t.exports=window.wp.notices},4:function(t,e){t.exports=window.lodash},468:function(t,e,n){"use strict";n.r(e);var r={};n.r(r),n.d(r,"createNotice",(function(){return f})),n.d(r,"createSuccessNotice",(function(){return a})),n.d(r,"createInfoNotice",(function(){return l})),n.d(r,"createErrorNotice",(function(){return d})),n.d(r,"createWarningNotice",(function(){return p})),n.d(r,"removeNotice",(function(){return b}));var i={};n.r(i),n.d(i,"getNotices",(function(){return O}));n(129);var o=n(7),c=n(4);var u=(t=>e=>(n={},r)=>{const i=r[t];if(void 0===i)return n;const o=e(n[i],r);return o===n[i]?n:{...n,[i]:o}})("context")((t=[],e)=>{switch(e.type){case"CREATE_NOTICE":return[...Object(c.reject)(t,{id:e.notice.id}),e.notice];case"REMOVE_NOTICE":return Object(c.reject)(t,{id:e.id})}return t});const s="global";function f(t="info",e,n={}){const{speak:r=!0,isDismissible:i=!0,context:o=s,id:u=Object(c.uniqueId)(o),actions:f=[],type:a="default",__unstableHTML:l,icon:d=null,explicitDismiss:p=!1,onDismiss:b=null}=n;return{type:"CREATE_NOTICE",context:o,notice:{id:u,status:t,content:e=String(e),spokenMessage:r?e:null,__unstableHTML:l,isDismissible:i,actions:f,type:a,icon:d,explicitDismiss:p,onDismiss:b}}}function a(t,e){return f("success",t,e)}function l(t,e){return f("info",t,e)}function d(t,e){return f("error",t,e)}function p(t,e){return f("warning",t,e)}function b(t,e=s){return{type:"REMOVE_NOTICE",id:t,context:e}}const y=[];function O(t,e=s){return t[e]||y}Object(o.registerStore)("core/notices2",{reducer:u,actions:r,selectors:i})},7:function(t,e){t.exports=window.wp.data}});