(function(){function s(z){var y=u(z);var A=0;for(var x=0;x<y.length;x++){var w=y[x];var B=getElementsByTagNameOnlyChildren(w,["td","th"]);if(B.length>A){A=B.length;}}return A;}function f(w){return getElementsByTagNameOnlyChildren(w,["td","th"]);}function n(A){var z;for(var w=0;w<A.length;w++){var x=A[w];var y=getStyle(x,"text-align");if(typeof(z)=="undefined"){z=y;}else{if(y!==z){return"do_not_change";}}}if(typeof(z)=="undefined"||z==null){z="default";}if(z!="left"&&z!="center"&&z!="right"&&z!="default"){z="do_not_change";}return z;}function i(A){var z;for(var w=0;w<A.length;w++){var x=A[w];var y=getStyle(x,"vertical-align");if(typeof(z)=="undefined"){z=y;}else{if(y!==z){return"do_not_change";}}}if(typeof(z)=="undefined"||z==null){z="default";}if(z!="top"&&z!="middle"&&z!="bottom"&&z!="default"){z="do_not_change";}return z;}function v(y){var z=j(r(y));for(var x=0;x<z.length;x++){for(var w=0;w<z[x].length;w++){if(z[x][w]==y){return w;}}}return -1;}function g(w){if(w.hasAttribute("colspan")){var x=parseInt(w.getAttribute("colspan"));if(!isNaN(x)&&x>0){return x;}}return 1;}function e(w){if(w.hasAttribute("rowspan")){var x=parseInt(w.getAttribute("rowspan"));if(!isNaN(x)&&x>0){return x;}}return 1;}function u(D){var y=[];var E=[null,"tbody","thead","tfoot"];for(var x=0;x<E.length;x++){var F=E[x];var z=F==null?[D]:getElementsByTagNameOnlyChildren(D,F);if(z.length>0){for(var B=0;B<z.length;B++){var w=z[B];var C=getElementsByTagNameOnlyChildren(w,["tr"]);for(var A=0;A<C.length;A++){y.push(C[A]);}}}}var F=E[x];var z=getElementsByTagNameOnlyChildren(D,["thead","tfoot"]);for(var B=0;B<z.length;B++){if(getElementsByTagNameOnlyChildren(z[B],["td","th"]).length>0){y.push(z[B]);}}return y;}function m(A){var B=[];var z=u(A);for(var y=0;y<z.length;y++){var x=getElementsByTagNameOnlyChildren(z[y],["td","th"]);for(var w=0;w<x.length;w++){B.push(x[w]);}}return B;}function a(x){var y=[];var A=m(x);var w=n(A);if(w=="left"||w=="center"||w=="right"){y.push("text-align:"+w);}var z=i(A);if(z=="top"||z=="middle"||z=="bottom"){y.push("vertical-align:"+z);}return y.join(";");}function p(w){if(w.tagName=="THEAD"){return true;}for(var x=0;x<2;x++){w=w.parentNode;if(w==null||w.tagName=="TBODY"||w.tagName=="TABLE"){return false;}if(w.tagName=="THEAD"){return true;}}return false;}function l(w,x){var y=window.document.createElement(w);if(x.length>0){y.setAttribute("style",x);}y.innerHTML="&nbsp;";return y;}function o(w,y,x){var z=getEditorName();if(typeof window["doksoft_"+z+"_listeners"]==="undefined"){window["doksoft_"+z+"_listeners"]={};}if(typeof window["doksoft_"+z+"_listeners"][y]==="undefined"){window["doksoft_"+z+"_listeners"][y]={};}if(typeof window["doksoft_"+z+"_listeners"][y][getEditorId(w)]==="undefined"){window["doksoft_"+z+"_listeners"][y][getEditorId(w)]=[];}window["doksoft_"+z+"_listeners"][y][getEditorId(w)].push((function(){var A=w;return function(){x(A);};})());}function q(x,y){var z=getEditorName();if(typeof window["doksoft_"+z+"_listeners"]!=="undefined"&&typeof window["doksoft_"+z+"_listeners"][y]!=="undefined"&&typeof window["doksoft_"+z+"_listeners"][y][getEditorId(x)]!="undefined"){for(var w=0;w<window["doksoft_"+z+"_listeners"][y][getEditorId(x)].length;w++){window["doksoft_"+z+"_listeners"][y][getEditorId(x)][w](x);}}}function t(x,E,w){var D=getElementsByTagNameOnlyChildren(x,["td","th"]);var B=0;for(var z=0;z<D.length;z++){var F=D[z];if(B==E){var C=l(F.tagName,w);F.parentNode.insertBefore(C,F);return;}var A=g(F);B+=A;if(B>E){F.setAttribute("colspan",A+1);return;}}var y="td";if(D.length>0){y=D[D.length-1].tagName;}else{if(p(x)){y="th";}}for(;B<=E;B++){var C=l(F.tagName,w);x.appendChild(C);}}function c(y,C,B){var z=y.parentNode.tagName=="THEAD"?"th":"td";var E=s(C);var w=a(C);var x=window.document.createElement("tr");for(var A=0;A<E;A++){var D=window.document.createElement(z);if(w.length>0){D.setAttribute("style",w);}D.innerHTML="&nbsp;";x.appendChild(D);}if(B){y.parentNode.insertBefore(x,y);}else{if(y.nextSibling!=null){y.parentNode.insertBefore(x,y.nextSibling);}else{y.parentNode.appendChild(x);}}return x;}function d(w,x){o(w,"table_tools",x);}function k(w){q(w,"table_tools");}function r(w){var x=w.parentNode;while(x!=null){if(x.tagName=="TABLE"){return x;}x=x.parentNode;}return null;}function j(H){var G=u(H);var w=-1;var F=[];for(var A=0;A<G.length;A++){w++;!F[w]&&(F[w]=[]);var E=-1;var I=getElementsByTagNameOnlyChildren(G[A],["td","th"]);for(var z=0;z<I.length;z++){var D=I[z];E++;while(F[w][E]){E++;}var y=g(D);var B=e(D);for(var x=0;x<B;x++){if(!F[w+x]){F[w+x]=[];}for(var C=0;C<y;C++){F[w+x][E+C]=I[z];}}E+=y-1;}}return F;}function h(){return CKEDITOR.version.charAt(0)=="3"?3:4;}function b(y,x){if(h()==3){var w=(x.indexOf("doksoft_bootstrap_table_split_cell_vert_")==-1)?("doksoft_bootstrap_table_split_cell_vert_"+x):x;if(typeof(y.lang[w])!=="undefined"){return y.lang[w];}else{console.log("(v3) editor.lang['doksoft_bootstrap_table_split_cell_vert'] not defined");}}else{if(typeof(y.lang["doksoft_bootstrap_table_split_cell_vert"])!=="undefined"){if(typeof(y.lang["doksoft_bootstrap_table_split_cell_vert"][x])!=="undefined"){return y.lang["doksoft_bootstrap_table_split_cell_vert"][x];
}else{console.log("editor.lang['doksoft_bootstrap_table_split_cell_vert']['"+x+"'] not defined");}}else{console.log("editor.lang['doksoft_bootstrap_table_split_cell_vert'] not defined");}}return"";}CKEDITOR.plugins.add("doksoft_bootstrap_table_split_cell_vert",{icons:"doksoft_bootstrap_table_split_cell_vert",lang:"en,ru",init:function(x){var w={"add_row_up":"rowInsertBefore","add_row_down":"rowInsertAfter","add_col_left":"columnInsertBefore","add_col_right":"columnInsertAfter","delete_row":"rowDelete","delete_col":"columnDelete","delete_cell":"cellDelete","merge_cells":"cellMerge","merge_cell_right":"cellMergeRight","merge_cell_down":"cellMergeDown","split_cell_vert":"cellVerticalSplit","split_cell_hor":"cellHorizontalSplit","add_cell_left":"cellInsertBefore","add_cell_right":"cellInsertAfter"};x.ui.addButton("doksoft_bootstrap_table_split_cell_vert",{title:b(x,"doksoft_bootstrap_table_split_cell_vert".replace(/^doksoft(_bootstrap|_foundation)?_table_/,"")),icon:this.path+"icons/doksoft_bootstrap_table_split_cell_vert.png",command:w["doksoft_bootstrap_table_split_cell_vert".replace(/^doksoft(_bootstrap|_foundation)?_table_/,"")],onclick:function(){k(x);}});}});})();