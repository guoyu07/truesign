webpackJsonp([31,38],{391:function(t,e,s){e=t.exports=s(4)(),e.push([t.id,"","",{version:3,sources:[],names:[],mappings:"",file:"socket-io.vue",sourceRoot:"webpack://"}])},446:function(t,e,s){var n=s(391);"string"==typeof n&&(n=[[t.id,n,""]]);s(5)(n,{});n.locals&&(t.exports=n.locals)},540:function(t,e,s){s(446);var n=s(3)(s(1142),s(608),null,null);t.exports=n.exports},608:function(t,e){t.exports={render:function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",[t._v("\n\n\n    "+t._s(t.msg)+"\n\n    "),s("div",{staticClass:"input-group",staticStyle:{"text-align":"center"}},[s("input",{directives:[{name:"model",rawName:"v-model",value:t.msg,expression:"msg"}],staticClass:"form-control",staticStyle:{position:"absolute",float:"right"},attrs:{type:"text"},domProps:{value:t.msg},on:{input:function(e){e.target.composing||(t.msg=e.target.value)}}}),t._v(" "),t._m(0)])])},staticRenderFns:[function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("span",{staticClass:"input-group-btn"},[s("button",{staticClass:"btn btn-default",staticStyle:{width:"400px"},attrs:{type:"button"}},[t._v("Go!")])])}]}},1142:function(t,e){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default={data:function(){return{msg:""}},methods:{msg:function(){this.$socket.emit(this.msg,{a:5,b:3})}},socket:{events:{changed:function(t){console.log("Something changed: "+t)}}}}}});
//# sourceMappingURL=31.38d07265dac3028d1046.js.map