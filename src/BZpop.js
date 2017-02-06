/**
 * Created by lkl on 2017/1/16.
 */
(function() {
    // 播种弹出框继承类
    var Sprite = Laya.Sprite;
    var Text = Laya.Text;
    var Event = Laya.Event;
    var Point = Laya.Point;
    function BZpop() {

        BZpop.__super.call(this);
        this.mouseThrough = false;
        this.name = 'BZpop';

        //this.bg.scaleX = 1.2;
        //this.bg.scaleY = 1.2;



        this.posArr = [[268,52],[180,78],[110,146],[60,226],[304,124],[232,164],[180,232]];

        this.init();

    }

    Laya.class(BZpop, "BZpop",bzUI);
    var _proto = BZpop.prototype;

    _proto.init = function() {
        this.createItem("../laya/assets/tex/100001.png",0);
    };

    _proto.createItem = function(url,index) {
        var seed = new Sprite();
        seed.index = index;
        seed.loadImage(url,0,0,80,80);
        seed.pivot(seed.width/2,seed.height/2);
        seed.pos(this.posArr[index][0],this.posArr[index][1]);
        this.addChild(seed);

        //数字背景
        var num_bg = new Sprite();
        num_bg.loadImage("../laya/assets/tex/9978.png",-18,14,38,26);
        seed.addChild(num_bg);

        //数字
        var num = new Text();
        num.text = "12";
        num.fontSize = 18;
        num.align = 'center';
        num.valign = "middle";
        num.size(38,26);
        num.pos(-18,14);
        num_bg.addChild(num);

        //箭头
        var jt = new Sprite();
        jt.name = 'jt';
        jt.loadImage("../laya/assets/tex/9979.png",25,60,38,22);
        seed.addChild(jt);
        
        seed.on(Event.MOUSE_DOWN,this,this.mouseDown,[seed]);
        seed.on(Event.MOUSE_UP,this,this.mouseUp,[seed]);


    };

    _proto.mouseDown = function(seed) {
        
        var jt = seed.getChildByName('jt');
        jt.visible = false;
        seed.on(Event.MOUSE_MOVE,this,this.mouseMove,[seed]);
    };

    _proto.mouseMove = function(seed) {
        console.log(Laya.stage.mouseX+','+Laya.stage.mouseY);

        var point = new Point(Laya.stage.mouseX,Laya.stage.mouseY);
        var p = this.globalToLocal(point);
        //seed.globalToLocal()
        seed.x = point.x;
        seed.y = point.y;
    };

    _proto.mouseUp = function(seed) {
        var jt = seed.getChildByName('jt');
        jt.visible = true;
        seed.x = this.posArr[seed.index][0];
        seed.y = this.posArr[seed.index][1];
        seed.off(Event.MOUSE_MOVE,this,this.mouseMove);
    };



})();