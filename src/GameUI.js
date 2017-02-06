/**
 * Created by lkl on 2016/11/11.
 */
(function() {
    // 游戏UI
    var Event = Laya.Event;
    var TiledMap = Laya.TiledMap;
    var Graphics = Laya.Graphics;
    var Rectangle = Laya.Rectangle;
    var Browser = Laya.Browser;
    var Handler = Laya.Handler;
    var Point = Laya.Point;
    var Sprite = Laya.Sprite;
    var timeLine = Laya.TimeLine;
    var Text = Laya.Text;
    var Label = Laya.Label;
    var ColorFilter = Laya.ColorFilter;
    var Button = Laya.Button;
    var ProgressBar = Laya.ProgressBar;
    var Image = Laya.Image;
    var self = null;

    function GameUI() {

        GameUI.__super.call(this);
        this.tiledMap = null;
        this.mX = 0;
        this.mY = 0;
        this.mLastMouseX = 0;
        this.mLastMouseY = 0;
        this.mapSprite = null;//地图容器，需要跟随地图移动的物品添加到这里
        this.mapLayer = null;//地图层，用来获取地图坐标



        this.UILayer = new Sprite(); //UI层不跟随地图移动
        this.UILayer.pos(0,0);
        this.UILayer.zOrder = 100;
        this.addChild(this.UILayer);
        this.currSelectedOBJ = null;

        self = this;



        this.createMap();

        Laya.stage.on(Event.MOUSE_DOWN, this, this.mouseDown);
        Laya.stage.on(Event.MOUSE_UP, this, this.mouseUp);

    }

    Laya.class(GameUI, "GameUI", Laya.Sprite);
    var _proto = GameUI.prototype;


    //创建地图
    _proto.createMap = function () {
        //创建地图对象
        this.tiledMap = new TiledMap();


        //创建地图，适当的时候调用destory销毁地图
        this.tiledMap.createMap("../laya/assets/map/map.json", new Rectangle(0, 0, Browser.clientWidth, Browser.clientHeight), new Handler(this, this.mapCompleteHandler));
    };

    //地图加载完成回调函数
    _proto.mapCompleteHandler = function () {
        this.mapSprite = this.tiledMap.mapSprite();
        this.mapSprite.zOrder = -1;
        this.mapLayer = this.tiledMap.getLayerByIndex(0);
        Laya.stage.on(Event.RESIZE, this, this.resize);


        this.BuildingLayer = new Sprite();
        this.BuildingLayer.zOrder = 1;
        var p = this.getPosByindex(0,0);
        this.BuildingLayer.pos(p.x,p.y);
        this.addChild(this.BuildingLayer);

        //

        //初始化界面UI
        this.initUI();
        this.initBuilding();

        this.resize();

    };


    //鼠标按下拖动地图
    _proto.mouseDown = function (e) {
        this.mLastMouseX = Laya.stage.mouseX;
        this.mLastMouseY = Laya.stage.mouseY;
        this.BuildingLayer.oldX = this.BuildingLayer.x;
        this.BuildingLayer.oldY = this.BuildingLayer.y;

        switch(e.target.name){
            case 'stage':
                if(this.currSelectedOBJ){
                    this.currSelectedOBJ.filters = null;
                    this.currSelectedOBJ = null;
                }

                break;
            case 'land':
                break
        }
        Laya.stage.on(Event.MOUSE_MOVE, this, this.mouseMove);

    };

    _proto.mouseMove = function (e) {
        //移动地图视口
        this.tiledMap.moveViewPort(this.mX - (Laya.stage.mouseX - this.mLastMouseX), this.mY - (Laya.stage.mouseY - this.mLastMouseY));

        var p = this.getPosByindex(0,0);
        this.BuildingLayer.pos(p.x,p.y);

    };

    _proto.mouseUp = function (e) {
        this.mX = this.mX - (Laya.stage.mouseX - this.mLastMouseX);
        this.mY = this.mY - (Laya.stage.mouseY - this.mLastMouseY);
        Laya.stage.off(Event.MOUSE_MOVE, this, this.mouseMove);
        console.log(this.getIndexByPos(Laya.stage.mouseX,Laya.stage.mouseY));
    };

    // 窗口大小改变，把地图的视口区域重设下
    _proto.resize = function () {
        //改变地图视口大小
        this.tiledMap.scale = 1;
        var pos = this.getPosByindex(15,15);
        this.mX = pos.x - Browser.clientWidth/2;
        this.mY = pos.y - Browser.clientHeight/2;
        this.tiledMap.changeViewPort(this.mX, this.mY, this.tiledMap.width, this.tiledMap.width);
        var p = this.getPosByindex(0,0);
        this.BuildingLayer.pos(p.x,p.y);

    };

    _proto.initUI = function() {
        //个人信息背景
        var user_info_bg = new Sprite();
        this.UILayer.addChild(user_info_bg);
        user_info_bg.loadImage("../laya/assets/UI/icon_bg.png");

        // 头像
        Laya.loader.load("../laya/assets/UI/header.jpg", Handler.create(this, function()
        {
            var t = Laya.loader.getRes("../laya/assets/UI/header.jpg");
            var header = new Sprite();
            header.graphics.drawTexture(t, 18, 27,60,60);
            user_info_bg.addChild(header);
            header.zOrder = 5;

        }));

        //头像框
        var header_bg = new Sprite();
        header_bg.loadImage("../laya/assets/UI/header_bg.png",8,16,80,80);
        user_info_bg.addChild(header_bg);
        header_bg.zOrder = 10;

        //昵称
        var nickname = new Text();
        nickname.text = "测试";
        nickname.stroke = 2;//描边宽度
        nickname.strokeColor = "#000000";
        nickname.size(130, 20);
        nickname.fontSize = 20;
        nickname.color = "#ffffff";
        nickname.overflow = Text.HIDDEN;//设置超出范围不显示
        nickname.pos(95, 55);
        user_info_bg.addChild(nickname);

        //经验条
        this.exp_bar = new ProgressBar("../laya/assets/UI/exp.png");

        this.exp_bar.width = 140;
        this.exp_bar.height = 28;

        this.exp_bar.x = 91;
        this.exp_bar.y = 16;

        this.exp_bar.sizeGrid = "7,7,7,7";
        this.exp_bar.value = 0.5223;
        this.exp_bar.changeHandler = new Handler(this, this.onExpchange);
        user_info_bg.addChild(this.exp_bar);

        //经验条文字
        this.exp_txt = new Text();
        this.exp_txt.text = (this.exp_bar.value*100).toFixed(2)+"%";
        this.exp_txt.align = "center";
        this.exp_txt.valign = "middle";
        this.exp_txt.stroke = 3;//描边宽度
        this.exp_txt.strokeColor = "#000000";
        this.exp_txt.size(140, 28);
        this.exp_txt.fontSize = 20;
        this.exp_txt.color = "#ffffff";
        this.exp_txt.pos(0, 0);
        this.exp_bar.addChild(this.exp_txt);

        //Laya.timer.loop(100, this, this.changeValue);

        //等级背景
        var level_bg = new Sprite();
        level_bg.size(67,67);
        level_bg.pivot(67,0);
        level_bg.loadImage("../laya/assets/UI/97.png",user_info_bg.width+3,-3);
        user_info_bg.addChild(level_bg);

        this.level_txt = new Text();
        this.level_txt.text = ""+18;
        //this.level_txt.borderColor = "#000000";
        this.level_txt.align = "center";
        this.level_txt.valign = "middle";
        this.level_txt.stroke = 3;//描边宽度
        this.level_txt.strokeColor = "#000000";
        this.level_txt.size(50, 28);
        this.level_txt.fontSize = 24;
        this.level_txt.color = "#ffffff";
        this.level_txt.pos(user_info_bg.width-this.level_txt.width-7, level_bg.height/2-this.level_txt.height/2-3);
        user_info_bg.addChild(this.level_txt);

        //金币背景
        var gold_bg = new Sprite();
        gold_bg.loadImage("../laya/assets/UI/write_bg.png");
        gold_bg.pos(Browser.clientWidth-150,10);
        this.UILayer.addChild(gold_bg);

        //金币图标
        var gold_icon = new Sprite();
        gold_icon.loadImage("../laya/assets/UI/98.png");
        gold_icon.pivot(Math.floor(67/2),Math.floor(67/2));
        gold_icon.pos(0,Math.floor(gold_bg.height/2));
        gold_bg.addChild(gold_icon);

        //添加金币按钮
        var add_gold_btn = new Sprite();
        add_gold_btn.loadImage("../laya/assets/UI/add_btn.png");
        add_gold_btn.pivot(Math.floor(50/2),Math.floor(50/2));
        add_gold_btn.pos(gold_bg.width-add_gold_btn.width/2+5,Math.floor(gold_bg.height/2));
        gold_bg.addChild(add_gold_btn);

        //金币文字
        this.gold_txt = new Text();
        this.gold_txt.text = ""+88;
        //this.gold_txt.borderColor = "#000000";
        this.gold_txt.align = "right";
        this.gold_txt.valign = "middle";
        this.gold_txt.stroke = 3;//描边宽度
        this.gold_txt.strokeColor = "#000000";
        this.gold_txt.size(80, 30);
        this.gold_txt.fontSize = 20;
        this.gold_txt.color = "#ffffff";
        this.gold_txt.pos(20,2);
        gold_bg.addChild(this.gold_txt);

        //乐豆背景
        var bean_bg = new Sprite();
        bean_bg.loadImage("../laya/assets/UI/write_bg.png");
        bean_bg.pos(Browser.clientWidth-150,65);
        this.UILayer.addChild(bean_bg);

        //乐豆图标
        var bean_icon = new Sprite();
        bean_icon.loadImage("../laya/assets/UI/99.png");
        bean_icon.pivot(Math.floor(67/2),Math.floor(67/2));
        bean_icon.pos(0,Math.floor(bean_bg.height/2));
        bean_bg.addChild(bean_icon);

        //添加乐豆按钮
        var add_bean_btn = new Sprite();
        add_bean_btn.loadImage("../laya/assets/UI/add_btn.png");
        add_bean_btn.pivot(Math.floor(50/2),Math.floor(50/2));
        add_bean_btn.pos(bean_bg.width-add_bean_btn.width/2+5,Math.floor(bean_bg.height/2));
        bean_bg.addChild(add_bean_btn);

        //乐豆文字
        this.bean_txt = new Text();
        this.bean_txt.text = ""+88;
        //this.bean_txt.borderColor = "#000000";
        this.bean_txt.align = "right";
        this.bean_txt.valign = "middle";
        this.bean_txt.stroke = 3;//描边宽度
        this.bean_txt.strokeColor = "#000000";
        this.bean_txt.size(80, 30);
        this.bean_txt.fontSize = 20;
        this.bean_txt.color = "#ffffff";
        this.bean_txt.pos(20,2);
        bean_bg.addChild(this.bean_txt);

        //指引任务按钮背景
        var guide_btn_bg = new Sprite();
        guide_btn_bg.loadImage("../laya/assets/UI/act_btn.png");
        guide_btn_bg.pos(0,120);
        this.UILayer.addChild(guide_btn_bg);

        //指引任务按钮人物头像
        var guide_btn_header = new Sprite();
        guide_btn_header.loadImage("../laya/assets/UI/50003.png",0,0,85,85);
        guide_btn_header.pivot(85/2,85/2);
        guide_btn_header.pos(Math.floor(69/2),Math.floor(69/2)-5);
        guide_btn_bg.addChild(guide_btn_header);

        /*//播种弹出框
        this.BZ_pop = new BZpop();

        this.BZ_pop.pivot(this.BZ_pop.width,this.BZ_pop.height);
        this.BZ_pop.pos(300,300);

        this.UILayer.addChild(this.BZ_pop);*/




    };

    _proto.initBuilding = function(){
        //仓库
        var depot = new Sprite();
        depot.loadImage("../laya/assets/tex/1000200.png");
        depot.pivot(Math.floor(depot.width/2),140);
        var p = this.getPosByindex(14,14);
        p = this.BuildingLayer.globalToLocal(p);//把地图上的获取的坐标变为建筑层的坐标
        depot.pos(p.x,p.y);
        this.BuildingLayer.addChild(depot);

        //土地
        var landPosIndex = [[17,15],[17,14],[17,13],[18,15],[18,14],[18,13]];//土地在地图上的格子坐标数组
        for(var i in landPosIndex){
            var land = new myland();
            land.pivot(64,32);
            land.land1.landIndex = i;
            var p = this.getPosByindex(landPosIndex[i][0],landPosIndex[i][1]);
            p = this.BuildingLayer.globalToLocal(p);//把地图上的获取的坐标变为建筑层的坐标
            land.pos(p.x,p.y);
            land.zOrder = landPosIndex[i][0]*100+(landPosIndex[i][1]);
            this.BuildingLayer.addChild(land);

        }





    };

    _proto.onClickLand = function(land) {
        console.log(land);
    };

    _proto.changeValue = function () {

        if (this.exp_bar.value >= 1)
            this.exp_bar.value = 0;
        this.exp_bar.value += 0.05;
    };

    _proto.onExpchange = function(value) {
        this.exp_txt.changeText((value*100).toFixed(2)+"%");
    };

    _proto.getPosByindex = function (col, row) {
        var p = new Point(0, 0);
        this.mapLayer.getScreenPositionByTilePos(col, row, p);

        p.x = Math.floor(p.x);
        p.y = Math.floor(p.y) + Math.floor(this.tiledMap.tileHeight / 2);
        return p;
    };

    _proto.getIndexByPos = function (x, y) {
        var p = new Point(0, 0);
        this.mapLayer.getTilePositionByScreenPos(x, y, p);
        p.x = Math.floor(p.x);
        p.y = Math.floor(p.y);
        return p;
    };


})();