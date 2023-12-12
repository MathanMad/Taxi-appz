/**
 * 
 * @authors zhangxu (zhangxu9@leju.com)
 * @date    2015-08-08 11:25:48
 * @version 0.1.0
 */

;;
(function($) {
    var Zmagnifier = function(opts) {
        this.opts = opts;
        this.defaults = {
            zclass: 'zwrap',
            width: 100,
            height: 100
        };
        $.extend(this.defaults, this.opts);
        this.initPlugin();
    };
    var zProto = Zmagnifier.prototype;
    /**
     * 初始化插件
     * @return {undefined}
     */
    zProto.initPlugin = function() {
        this.createMaginfier();
        this.getRealSize();
    };
    /**
     * 放大镜事件添加
     * @param  {Object} event 事件对象
     * @return {undefined}
     */
    zProto.moveEvent = function(event) {
        var that = this,
            $magnifier = $('#magnifier_id'),
            $zwrap = $('.' + this.defaults.zclass),
            $zimg = $zwrap.find('.zimg'),
            zimg_w = parseInt($zimg.css('width')),
            zimg_h = parseInt($zimg.css('height')),
            magnifier_w = parseInt($magnifier.css('width')),
            magnifier_h = parseInt($magnifier.css('height'));
        /**
         * 放大镜移动事件
         * @return {undefined}
         */
        $zwrap.bind('mousemove', function(event) {
            that.mouse_x = event.clientX - $zwrap[0].offsetLeft,
                that.mouse_y = event.clientY - $zwrap[0].offsetTop;
            /**
             * 鼠标移入图片时，放大镜显示
             * @return {undefined}
             */
            if (that.mouse_x > 0 && that.mouse_y > 0 && that.mouse_x < $(this)[0].offsetWidth && that.mouse_y < $(this)[0].offsetHeight) {
                $magnifier.css('display', 'block');
            } else {
                $magnifier.css('display', 'none');
            }
            var background_px = -Math.round(that.mouse_x / zimg_w * that.real_w - magnifier_w / 2),
                background_py = -Math.round(that.mouse_y / zimg_h * that.real_h - magnifier_h / 2);
            $magnifier.css({
                'left': that.mouse_x - magnifier_w / 2 + 'px',
                'top': that.mouse_y - magnifier_h / 2 + 'px',
                'backgroundPosition': background_px + 'px ' + background_py + 'px'
            });
        });
    };
    /**
     * 创建放大镜元素
     * @return {undefined}
     */
    zProto.createMaginfier = function() {
        var _this = this,
            $zwrap = $('.' + this.defaults.zclass),
            $zimg = $zwrap.find('.zimg'),
            oMag = document.createElement('div');
        oMag.id = 'magnifier_id';
        $(oMag).addClass('zmagnifier');
        $(oMag).css({
            'width': _this.defaults.width,
            'height': _this.defaults.height
        });
        $zimg.before(oMag);
    };
    /**
     * 获取图片真实的尺寸，保存在real_w和real_h中
     * @return {undefined}
     */
    zProto.getRealSize = function() {
        var _this = this,
            $zwrap = $('.' + this.defaults.zclass),
            $zimg = $zwrap.find('.zimg');
        $zimg[0].onload = function() {
            var oImg = new Image();
            oImg.src = $zimg[0].src;
            _this.real_w = oImg.width;
            _this.real_h = oImg.height;
            _this.moveEvent();
        };
    };
    window.zConfig = function(opts) {
        return new Zmagnifier(opts);
    };
})($);