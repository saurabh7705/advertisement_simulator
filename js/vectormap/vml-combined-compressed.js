jvm.VMLElement=function(b,a){if(!jvm.VMLElement.VMLInitialized){jvm.VMLElement.initializeVML()}jvm.VMLElement.parentClass.apply(this,arguments)};jvm.inherits(jvm.VMLElement,jvm.AbstractElement);jvm.VMLElement.VMLInitialized=false;jvm.VMLElement.initializeVML=function(){try{if(!document.namespaces.rvml){document.namespaces.add("rvml","urn:schemas-microsoft-com:vml")}jvm.VMLElement.prototype.createElement=function(b){return document.createElement("<rvml:"+b+' class="rvml">')}}catch(a){jvm.VMLElement.prototype.createElement=function(b){return document.createElement("<"+b+' xmlns="urn:schemas-microsoft.com:vml" class="rvml">')}}document.createStyleSheet().addRule(".rvml","behavior:url(#default#VML)");jvm.VMLElement.VMLInitialized=true};jvm.VMLElement.prototype.getElementCtr=function(a){return jvm["VML"+a]};jvm.VMLElement.prototype.addClass=function(a){jvm.$(this.node).addClass(a)};jvm.VMLElement.prototype.applyAttr=function(a,b){this.node[a]=b};jvm.VMLElement.prototype.getBBox=function(){var a=jvm.$(this.node);return{x:a.position().left/this.canvas.scale,y:a.position().top/this.canvas.scale,width:a.width()/this.canvas.scale,height:a.height()/this.canvas.scale}};jvm.VMLGroupElement=function(){jvm.VMLGroupElement.parentClass.call(this,"group");this.node.style.left="0px";this.node.style.top="0px";this.node.coordorigin="0 0"};jvm.inherits(jvm.VMLGroupElement,jvm.VMLElement);jvm.VMLGroupElement.prototype.add=function(a){this.node.appendChild(a.node)};jvm.VMLCanvasElement=function(b,c,a){this.classPrefix="VML";jvm.VMLCanvasElement.parentClass.call(this,"group");jvm.AbstractCanvasElement.apply(this,arguments);this.node.style.position="absolute"};jvm.inherits(jvm.VMLCanvasElement,jvm.VMLElement);jvm.mixin(jvm.VMLCanvasElement,jvm.AbstractCanvasElement);jvm.VMLCanvasElement.prototype.setSize=function(e,b){var f,a,d,c;this.width=e;this.height=b;this.node.style.width=e+"px";this.node.style.height=b+"px";this.node.coordsize=e+" "+b;this.node.coordorigin="0 0";if(this.rootElement){f=this.rootElement.node.getElementsByTagName("shape");for(d=0,c=f.length;d<c;d++){f[d].coordsize=e+" "+b;f[d].style.width=e+"px";f[d].style.height=b+"px"}a=this.node.getElementsByTagName("group");for(d=0,c=a.length;d<c;d++){a[d].coordsize=e+" "+b;a[d].style.width=e+"px";a[d].style.height=b+"px"}}};jvm.VMLCanvasElement.prototype.applyTransformParams=function(c,b,a){this.scale=c;this.transX=b;this.transY=a;this.rootElement.node.coordorigin=(this.width-b-this.width/100)+","+(this.height-a-this.height/100);this.rootElement.node.coordsize=this.width/c+","+this.height/c};jvm.VMLShapeElement=function(b,a){jvm.VMLShapeElement.parentClass.call(this,b,a);this.fillElement=new jvm.VMLElement("fill");this.strokeElement=new jvm.VMLElement("stroke");this.node.appendChild(this.fillElement.node);this.node.appendChild(this.strokeElement.node);this.node.stroked=false;jvm.AbstractShapeElement.apply(this,arguments)};jvm.inherits(jvm.VMLShapeElement,jvm.VMLElement);jvm.mixin(jvm.VMLShapeElement,jvm.AbstractShapeElement);jvm.VMLShapeElement.prototype.applyAttr=function(a,b){switch(a){case"fill":this.node.fillcolor=b;break;case"fill-opacity":this.fillElement.node.opacity=Math.round(b*100)+"%";break;case"stroke":if(b==="none"){this.node.stroked=false}else{this.node.stroked=true}this.node.strokecolor=b;break;case"stroke-opacity":this.strokeElement.node.opacity=Math.round(b*100)+"%";break;case"stroke-width":if(parseInt(b,10)===0){this.node.stroked=false}else{this.node.stroked=true}this.node.strokeweight=b;break;case"d":this.node.path=jvm.VMLPathElement.pathSvgToVml(b);break;default:jvm.VMLShapeElement.parentClass.prototype.applyAttr.apply(this,arguments)}};jvm.VMLPathElement=function(a,b){var c=new jvm.VMLElement("skew");jvm.VMLPathElement.parentClass.call(this,"shape",a,b);this.node.coordorigin="0 0";c.node.on=true;c.node.matrix="0.01,0,0,0.01,0,0";c.node.offset="0,0";this.node.appendChild(c.node)};jvm.inherits(jvm.VMLPathElement,jvm.VMLShapeElement);jvm.VMLPathElement.prototype.applyAttr=function(a,b){if(a==="d"){this.node.path=jvm.VMLPathElement.pathSvgToVml(b)}else{jvm.VMLShapeElement.prototype.applyAttr.call(this,a,b)}};jvm.VMLPathElement.pathSvgToVml=function(e){var b="",a=0,f=0,d,c;e=e.replace(/(-?\d+)e(-?\d+)/g,"0");return e.replace(/([MmLlHhVvCcSs])\s*((?:-?\d*(?:\.\d+)?\s*,?\s*)+)/g,function(m,k,n,h){n=n.replace(/(\d)-/g,"$1,-").replace(/^\s+/g,"").replace(/\s+$/g,"").replace(/\s+/g,",").split(",");if(!n[0]){n.shift()}for(var j=0,g=n.length;j<g;j++){n[j]=Math.round(100*n[j])}switch(k){case"m":a+=n[0];f+=n[1];return"t"+n.join(",");break;case"M":a=n[0];f=n[1];return"m"+n.join(",");break;case"l":a+=n[0];f+=n[1];return"r"+n.join(",");break;case"L":a=n[0];f=n[1];return"l"+n.join(",");break;case"h":a+=n[0];return"r"+n[0]+",0";break;case"H":a=n[0];return"l"+a+","+f;break;case"v":f+=n[0];return"r0,"+n[0];break;case"V":f=n[0];return"l"+a+","+f;break;case"c":d=a+n[n.length-4];c=f+n[n.length-3];a+=n[n.length-2];f+=n[n.length-1];return"v"+n.join(",");break;case"C":d=n[n.length-4];c=n[n.length-3];a=n[n.length-2];f=n[n.length-1];return"c"+n.join(",");break;case"s":n.unshift(f-c);n.unshift(a-d);d=a+n[n.length-4];c=f+n[n.length-3];a+=n[n.length-2];f+=n[n.length-1];return"v"+n.join(",");break;case"S":n.unshift(f+f-c);n.unshift(a+a-d);d=n[n.length-4];c=n[n.length-3];a=n[n.length-2];f=n[n.length-1];return"c"+n.join(",");break}return""}).replace(/z/g,"e")};jvm.VMLCircleElement=function(a,b){jvm.VMLCircleElement.parentClass.call(this,"oval",a,b)};jvm.inherits(jvm.VMLCircleElement,jvm.VMLShapeElement);jvm.VMLCircleElement.prototype.applyAttr=function(a,b){switch(a){case"r":this.node.style.width=b*2+"px";this.node.style.height=b*2+"px";this.applyAttr("cx",this.get("cx")||0);this.applyAttr("cy",this.get("cy")||0);break;case"cx":if(!b){return}this.node.style.left=b-(this.get("r")||0)+"px";break;case"cy":if(!b){return}this.node.style.top=b-(this.get("r")||0)+"px";break;default:jvm.VMLCircleElement.parentClass.prototype.applyAttr.call(this,a,b)}};