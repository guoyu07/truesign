webpackJsonp([22,38],{413:function(e,n,t){n=e.exports=t(4)(),n.push([e.id,"body{margin:0}","",{version:3,sources:["/./src/components/project/spa/spa.vue"],names:[],mappings:"AACA,KACE,QAAU,CACX",file:"spa.vue",sourcesContent:["\nbody {\n  margin: 0;\n}\n"],sourceRoot:"webpack://"}])},469:function(e,n,t){var o=t(413);"string"==typeof o&&(o=[[e.id,o,""]]);t(5)(o,{});o.locals&&(e.exports=o.locals)},531:function(e,n,t){t(469);var o=t(3)(t(1134),t(632),null,null);e.exports=o.exports},632:function(e,n){e.exports={render:function(){var e=this,n=e.$createElement,t=e._self._c||n;return t("div",[t("router-view",{attrs:{name:"spa_header"}}),e._v(" "),t("div",{directives:[{name:"loading",rawName:"v-loading.body",value:e.fullscreenLoading,expression:"fullscreenLoading",modifiers:{body:!0}}],attrs:{id:"app1"},on:{click:e.openFullScreen}}),e._v(" "),t("div",{attrs:{id:"app2"},on:{click:e.openFullScreen}}),e._v(" "),t("router-view",{attrs:{name:"spa_navbar"}}),e._v(" "),t("router-view",{attrs:{name:"spa_sidebar"}}),e._v(" "),t("router-view",{attrs:{name:"spa_content"}}),e._v(" "),t("router-view",{attrs:{name:"spa_footer"}})],1)},staticRenderFns:[]}},1134:function(e,n,t){"use strict";Object.defineProperty(n,"__esModule",{value:!0});var o=t(248);n.default={data:function(){return{is_loading:!0,loadingInstance:"",fullscreenLoading:!1}},created:function(){},methods:{stop_loading:function(){this.is_loading=!0},openFullScreen:function(){var e=o.Loading.service({fullscreen:!1,target:"#app1"});setTimeout(function(){e.close()},3e3)}},mounted:function(){}}}});
//# sourceMappingURL=22.e2b886eac28f33284171.js.map