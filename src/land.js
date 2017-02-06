/**
 * Created by lkl on 2017/1/13.
 */
(function() {
    // 土地继承类
    var GlowFilter = Laya.GlowFilter;

    function myland() {

        myland.__super.call(this);
        this.mouseThrough = true;
        this.land1.name = 'land';
        this.land1.Status = 0;//0:未播种1:已播种
        this.land1.on(laya.events.Event.CLICK, this, this.onClickLand,[this.land1]);


    }

    Laya.class(myland, "myland",landUI);
    var _proto = myland.prototype;

    _proto.onClickLand = function(land) {
        var gameUI = this.parent.parent;
        if(gameUI.currSelectedOBJ){
            gameUI.currSelectedOBJ.filters = null;
        }
        //创建一个发光滤镜
        var glowFilter = new GlowFilter("#FFFFFF", 10, 0, 0);
        //设置滤镜集合为发光滤镜
        this.filters = [glowFilter];
        gameUI.currSelectedOBJ = this;


    };



})();