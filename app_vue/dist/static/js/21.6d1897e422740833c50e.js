webpackJsonp([21,25],{330:function(t,e,s){e=t.exports=s(7)(),e.push([t.id,"","",{version:3,sources:[],names:[],mappings:"",file:"socket-io.vue",sourceRoot:"webpack://"}])},360:function(t,e,s){var n=s(330);"string"==typeof n&&(n=[[t.id,n,""]]);s(8)(n,{});n.locals&&(t.exports=n.locals)},412:function(t,e,s){s(360);var n=s(6)(s(965),s(474),null,null);t.exports=n.exports},474:function(t,e){t.exports={render:function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",[t._v("\n\n\n    "+t._s(t.msg)+"\n\n    "),s("div",{staticClass:"input-group",staticStyle:{"text-align":"center"}},[s("input",{directives:[{name:"model",rawName:"v-model",value:t.msg,expression:"msg"}],staticClass:"form-control",staticStyle:{position:"absolute",float:"right"},attrs:{type:"text"},domProps:{value:t.msg},on:{input:function(e){e.target.composing||(t.msg=e.target.value)}}}),t._v(" "),t._m(0)])])},staticRenderFns:[function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("span",{staticClass:"input-group-btn"},[s("button",{staticClass:"btn btn-default",staticStyle:{width:"400px"},attrs:{type:"button"}},[t._v("Go!")])])}]}},965:function(t,e){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default={data:function(){return{msg:""}},methods:{msg:function(){this.$socket.emit(this.msg,{a:5,b:3})}},socket:{events:{changed:function(t){console.log("Something changed: "+t)}}}}}});
//# sourceMappingURL=21.6d1897e422740833c50e.js.map