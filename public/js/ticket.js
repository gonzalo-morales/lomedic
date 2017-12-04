/* Extend jQuery with functions for PUT and DELETE requests. */
function _ajax_request(e,t,u,r,a){return jQuery.isFunction(t)&&(u=t,t={}),jQuery.ajax({type:a,url:e,data:t,success:u,dataType:r})}jQuery.extend({put:function(e,t,u,r){return _ajax_request(e,t,u,r,"PUT")},delete:function(e,t,u,r){return _ajax_request(e,t,u,r,"DELETE")}});
