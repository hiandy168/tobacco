var CLASS$=Laya.class;
var STATICATTR$=Laya.static;
var View=laya.ui.View;
var Dialog=laya.ui.Dialog;
var bzUI=(function(_super){
		function bzUI(){
			
		    this.bg=null;

			bzUI.__super.call(this);
		}

		CLASS$(bzUI,'ui.bzUI',_super);
		var __proto__=bzUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(bzUI.uiView);
		}

		STATICATTR$(bzUI,
		['uiView',function(){return this.uiView={"type":"Dialog","props":{"width":342,"height":285},"child":[{"type":"Image","props":{"y":0,"x":0,"width":342,"var":"bg","skin":"tex/2.png","height":285}}]};}
		]);
		return bzUI;
	})(Dialog);
var landUI=(function(_super){
		function landUI(){
			
		    this.land1=null;

			landUI.__super.call(this);
		}

		CLASS$(landUI,'ui.landUI',_super);
		var __proto__=landUI.prototype;
		__proto__.createChildren=function(){
		    
			laya.ui.Component.prototype.createChildren.call(this);
			this.createView(landUI.uiView);
		}

		STATICATTR$(landUI,
		['uiView',function(){return this.uiView={"type":"View","props":{"width":125,"height":64},"child":[{"type":"Image","props":{"y":0,"x":0,"var":"land1","skin":"tex/10004.png"},"child":[{"type":"Poly","props":{"y":37,"x":-41,"renderType":"hit","points":"44,-7,106,-37,164,-8,102,24","lineWidth":1,"lineColor":"#ff0000","fillColor":"#00ffff"}}]}]};}
		]);
		return landUI;
	})(View);