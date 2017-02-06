(function() {

    var Stage       = Laya.Stage;
    var Browser = Laya.Browser;

    var Handler     = Laya.Handler;
    var WebGL       = Laya.WebGL;

    var GameSize    = {width: Browser.clientWidth, height: Browser.clientHeight};//定义游戏宽高适应设备分辨率为{width:Browser.clientWidth, ,height:Browser.clientHeight};

    (function () {
        //不支持WebGL时自动切换至Canvas
        Laya.init(GameSize.width, GameSize.height, WebGL);

        Laya.stage.alignV = Stage.ALIGN_MIDDLE;//垂直对齐方式，有"top"，"middle"，"bottom"三种值可选。
        Laya.stage.alignH = Stage.ALIGN_CENTER;//水平对齐方式,有"left"，"center"，"right"三种值可选。

        Laya.stage.scaleMode = "noscale";//适配模式(noscale,exactfit,showall,noborder,full,fixedwidth,fixedheight
        //竖横屏设置
        Laya.stage.screenMode = "horizontal";//none,horizontal,vertical
        //Laya.stage.bgColor = "";//背景颜色
        Laya.stage.name = 'stage';


        LoadResourse();

    })();

    //加载资源
    function LoadResourse()
    {

        Laya.loader.load(
            [
                {url:"../laya/assets/UI/shop_open_icon.png",type: Laya.loader.IMAGE},
                {url:"../laya/assets/UI/icon_bg.png",type: Laya.loader.IMAGE},
                {url:"../laya/assets/UI/button_blue.png",type: Laya.loader.IMAGE},
                {url:"../laya/assets/UI/exp.png",type: Laya.loader.IMAGE},
                {url:"../laya/assets/UI/exp$bar.png",type: Laya.loader.IMAGE},
                {url:"../laya/assets/UI/97.png",type: Laya.loader.IMAGE},
                {url:"../laya/assets/UI/98.png",type: Laya.loader.IMAGE},
                {url:"../laya/assets/UI/99.png",type: Laya.loader.IMAGE},
                {url:"../laya/assets/UI/50003.png",type: Laya.loader.IMAGE},
                {url:"../laya/assets/UI/act_btn.png",type: Laya.loader.IMAGE},
                {url:"../laya/assets/UI/add_btn.png",type: Laya.loader.IMAGE},
                {url:"../laya/assets/UI/header_bg.png",type: Laya.loader.IMAGE},
                {url:"../laya/assets/UI/write_bg.png",type: Laya.loader.IMAGE},
                {url:"../laya/assets/tex/1000200.png",type: Laya.loader.IMAGE},
                {url:"../laya/assets/tex/10004.png",type: Laya.loader.IMAGE},
                {url:"../laya/assets/tex/bottom.png",type: Laya.loader.IMAGE},
                {url:"../laya/assets/tex/2.png",type: Laya.loader.IMAGE},
                {url:"../laya/assets/tex/9978.png",type: Laya.loader.IMAGE},
                {url:"../laya/assets/tex/9979.png",type: Laya.loader.IMAGE},
                {url:"../laya/assets/tex/100001.png",type: Laya.loader.IMAGE},

            ],Handler.create(this, onAssetLoaded));
    }

    function onAssetLoaded()
    {
        //加载完成
        GameInit();

    }

    function GameInit()
    {

        var GUI = new GameUI();
        Laya.stage.addChild(GUI);

    }








})();


