(global["webpackJsonp"] = global["webpackJsonp"] || []).push([["common/main"],{

/***/ "./node_modules/babel-loader/lib/index.js!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader/index.js?!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader/index.js?!./node_modules/@dcloudio/webpack-uni-mp-loader/lib/script.js!./node_modules/vue-loader/lib/index.js?!D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\App.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--12-1!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--18-0!./node_modules/@dcloudio/webpack-uni-mp-loader/lib/script.js!./node_modules/vue-loader/lib??vue-loader-options!D:/phpStudy/WWW/fx70999_zy/uni-app/App.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/* WEBPACK VAR INJECTION */(function(uni) {Object.defineProperty(exports, "__esModule", { value: true });exports.default = void 0;



var _vuex = __webpack_require__(/*! vuex */ "./node_modules/vuex/dist/vuex.esm.js");function _objectSpread(target) {for (var i = 1; i < arguments.length; i++) {var source = arguments[i] != null ? arguments[i] : {};var ownKeys = Object.keys(source);if (typeof Object.getOwnPropertySymbols === 'function') {ownKeys = ownKeys.concat(Object.getOwnPropertySymbols(source).filter(function (sym) {return Object.getOwnPropertyDescriptor(source, sym).enumerable;}));}ownKeys.forEach(function (key) {_defineProperty(target, key, source[key]);});}return target;}function _defineProperty(obj, key, value) {if (key in obj) {Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true });} else {obj[key] = value;}return obj;}var _default =


{
  methods: _objectSpread({},
  (0, _vuex.mapMutations)(['login'])),

  onLaunch: function onLaunch() {var _this = this;
    var userInfo = uni.getStorageSync('userInfo') || '';
    console.log(userInfo.id);
    if (userInfo.id) {
      //更新登陆状态
      uni.getStorage({
        key: 'userInfo',
        success: function success(res) {
          _this.login(res.data);
        } });

    }

  },
  onShow: function onShow() {
    console.log('App Show');
  },
  onHide: function onHide() {
    console.log('App Hide');
  } };exports.default = _default;
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./node_modules/@dcloudio/uni-mp-weixin/dist/index.js */ "./node_modules/@dcloudio/uni-mp-weixin/dist/index.js")["default"]))

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader/index.js?!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader/index.js?!./node_modules/@dcloudio/webpack-uni-mp-loader/lib/script.js!./node_modules/vue-loader/lib/index.js?!D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\components\\sunui-upimg\\sunui-upimg-basic.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--12-1!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--18-0!./node_modules/@dcloudio/webpack-uni-mp-loader/lib/script.js!./node_modules/vue-loader/lib??vue-loader-options!D:/phpStudy/WWW/fx70999_zy/uni-app/components/sunui-upimg/sunui-upimg-basic.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/* WEBPACK VAR INJECTION */(function(uni) {Object.defineProperty(exports, "__esModule", { value: true });exports.default = void 0;var _regenerator = _interopRequireDefault(__webpack_require__(/*! ./node_modules/@babel/runtime/regenerator */ "./node_modules/@babel/runtime/regenerator/index.js"));






























var _common = _interopRequireDefault(__webpack_require__(/*! ../../common/common.js */ "D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\common\\common.js"));function _interopRequireDefault(obj) {return obj && obj.__esModule ? obj : { default: obj };}function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) {try {var info = gen[key](arg);var value = info.value;} catch (error) {reject(error);return;}if (info.done) {resolve(value);} else {Promise.resolve(value).then(_next, _throw);}}function _asyncToGenerator(fn) {return function () {var self = this,args = arguments;return new Promise(function (resolve, reject) {var gen = fn.apply(self, args);function _next(value) {asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value);}function _throw(err) {asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err);}_next(undefined);});};}var _default =
{
  data: function data() {
    return {
      upload_after_list: [],
      upload_picture_list: [] };

  },
  name: 'sunui-upimg',
  props: {
    upImgConfig: {
      type: Object } },


  methods: {
    chooseImage: function chooseImage(count) {
      cImage(this, parseInt(count), this.upImgConfig);
    },
    uploadimage: function uploadimage(e) {
      uImage(this, e);
    },
    deleteImg: function deleteImg(e) {
      dImage(e, this);
    },
    previewImg: function previewImg(e) {
      pImage(e, this);
    },
    previewImgs: function previewImgs(e) {
      puImage(e, this);
    } }




  // 上传文件
};exports.default = _default;var upload_file_server = /*#__PURE__*/function () {var _ref = _asyncToGenerator( /*#__PURE__*/_regenerator.default.mark(function _callee4(_this, configs, upload_picture_list, j) {var basicConfig, upload_task;return _regenerator.default.wrap(function _callee4$(_context4) {while (1) {switch (_context4.prev = _context4.next) {case 0:

            basicConfig = {
              url: configs.basicConfig.url || "" };_context4.next = 3;return (

              uni.uploadFile({
                url: basicConfig.url,
                filePath: upload_picture_list[j]['path'],
                name: 'file',
                formData: {},
                success: function () {var _success = _asyncToGenerator( /*#__PURE__*/_regenerator.default.mark(function _callee(res) {var data;return _regenerator.default.wrap(function _callee$(_context) {while (1) {switch (_context.prev = _context.next) {case 0:if (!(
                            res.statusCode == 200)) {_context.next = 10;break;}
                            console.log(JSON.stringify(res));
                            data = JSON.parse(res.data);
                            console.log("%c \u540E\u7AEF\u4E0A\u4F20(\u6210\u529F\u8FD4\u56DE\u5730\u5740):".concat(data.url), 'color:#1AAD19');
                            // 先获取图片url(各个后端返回值不一，所以造成出入)
                            // 修改获取的图片返回值路径
                            // 提示：data.info改为你图片返回地址即可
                            upload_picture_list[j]['path_server'] = _common.default.IP + data.url;
                            upload_picture_list[j]['path'] = data.url; //修改返回图片地址即可(2019/05/06) => 无论是腾讯云还是阿里云或者其它(只要返回图片地址以及引入对应的js文件进行一些配置即可扩展本插件)
                            _this.upload_picture_list = upload_picture_list;_context.next = 9;return (
                              tImage(_this, upload_picture_list, configs.count));case 9:
                            uni.hideLoading();case 10:case "end":return _context.stop();}}}, _callee, this);}));function success(_x5) {return _success.apply(this, arguments);}return success;}(),


                fail: function () {var _fail = _asyncToGenerator( /*#__PURE__*/_regenerator.default.mark(function _callee2(err) {return _regenerator.default.wrap(function _callee2$(_context2) {while (1) {switch (_context2.prev = _context2.next) {case 0:
                            uni.showLoading({
                              title: "\u4E0A\u4F20\u5931\u8D25!" });

                            _this.upload_picture_list = [];
                            _this.upload_after_list = [];
                            setTimeout(function () {
                              uni.hideLoading();
                            }, 2000);
                            console.warn(err, '请检查是否CORB/CORS错误!');case 5:case "end":return _context2.stop();}}}, _callee2, this);}));function fail(_x6) {return _fail.apply(this, arguments);}return fail;}() }));case 3:upload_task = _context4.sent;


            upload_task.onProgressUpdate( /*#__PURE__*/function () {var _ref2 = _asyncToGenerator( /*#__PURE__*/_regenerator.default.mark(function _callee3(res) {var i, len;return _regenerator.default.wrap(function _callee3$(_context3) {while (1) {switch (_context3.prev = _context3.next) {case 0:
                        i = 0, len = _this.upload_picture_list.length;case 1:if (!(i < len)) {_context3.next = 8;break;}_context3.next = 4;return (
                          res.progress);case 4:upload_picture_list[i]['upload_percent'] = _context3.sent;case 5:i++;_context3.next = 1;break;case 8:

                        _this.upload_picture_list = upload_picture_list;case 9:case "end":return _context3.stop();}}}, _callee3, this);}));return function (_x7) {return _ref2.apply(this, arguments);};}());case 5:case "end":return _context4.stop();}}}, _callee4, this);}));return function upload_file_server(_x, _x2, _x3, _x4) {return _ref.apply(this, arguments);};}();



// 上传图片子组件->父组件(通用)
var tImage = /*#__PURE__*/function () {var _ref3 = _asyncToGenerator( /*#__PURE__*/_regenerator.default.mark(function _callee5(_this, upload_picture_list, count) {return _regenerator.default.wrap(function _callee5$(_context5) {while (1) {switch (_context5.prev = _context5.next) {case 0:_context5.next = 2;return (
              _this.$emit('onUpImg', _this.upload_picture_list));case 2:case "end":return _context5.stop();}}}, _callee5, this);}));return function tImage(_x8, _x9, _x10) {return _ref3.apply(this, arguments);};}();



// 上传图片(通用)
var uImage = /*#__PURE__*/function () {var _ref4 = _asyncToGenerator( /*#__PURE__*/_regenerator.default.mark(function _callee6(_this, config) {var j, len;return _regenerator.default.wrap(function _callee6$(_context6) {while (1) {switch (_context6.prev = _context6.next) {case 0:
            j = 0, len = _this.upload_picture_list.length;case 1:if (!(j < len)) {_context6.next = 8;break;}if (!(
            _this.upload_picture_list[j]['upload_percent'] == 0)) {_context6.next = 5;break;}_context6.next = 5;return (
              upload_file_server(_this, config, _this.upload_picture_list, j));case 5:j++;_context6.next = 1;break;case 8:case "end":return _context6.stop();}}}, _callee6, this);}));return function uImage(_x11, _x12) {return _ref4.apply(this, arguments);};}();




// 删除图片(通用)
var dImage = /*#__PURE__*/function () {var _ref5 = _asyncToGenerator( /*#__PURE__*/_regenerator.default.mark(function _callee7(e, _this) {return _regenerator.default.wrap(function _callee7$(_context7) {while (1) {switch (_context7.prev = _context7.next) {case 0:_context7.next = 2;return (
              _this.$emit('onImgDel', {
                url: e.currentTarget.dataset.url,
                index: e.currentTarget.dataset.index }));case 2:

            _this.upload_picture_list.splice(e.currentTarget.dataset.index, 1);
            _this.upload_after_list.splice(e.currentTarget.dataset.index, 1);
            _this.upload_picture_list = _this.upload_picture_list;case 5:case "end":return _context7.stop();}}}, _callee7, this);}));return function dImage(_x13, _x14) {return _ref5.apply(this, arguments);};}();


// 选择图片(通用)
var cImage = function cImage(_this, count, configs) {
  var config = {
    basicConfig: {
      url: configs.basicConfig.url },

    count: count,
    notli: configs.notli,
    sourceType: configs.sourceType,
    sizeType: configs.sizeType,
    tips: configs.tips || false };

  console.log(config.count);
  console.log(_this.upload_after_list.length);

  if (config.count <= _this.upload_after_list.length) {
    uni.showToast({
      title: "\u53EA\u80FD\u4F20" + config.count + '张',
      icon: 'none' });


  } else {




    uni.chooseImage({
      count: config.notli ? config.count = 9 : _this.upload_after_list.length == 0 ? config.count : config.count -
      _this.upload_after_list.length,
      sizeType: config.sizeType == "" || config.sizeType == undefined || config.sizeType == true ? ['compressed'] : [
      'original'],

      sourceType: ['album', 'camera'],
      success: function () {var _success2 = _asyncToGenerator( /*#__PURE__*/_regenerator.default.mark(function _callee8(res) {var i, len;return _regenerator.default.wrap(function _callee8$(_context8) {while (1) {switch (_context8.prev = _context8.next) {case 0:
                  console.log(res);
                  for (i = 0, len = res.tempFiles.length; i < len; i++) {
                    res.tempFiles[i]['upload_percent'] = 0;
                    res.tempFiles[i]['path_server'] = '';
                    _this.upload_picture_list.push(res.tempFiles[i]);
                    _this.upload_picture_list.length > config.count ? _this.upload_picture_list = _this.upload_picture_list.slice(
                    0,
                    config.count) : '';
                  }
                  // 过滤多出的预览图片
                  _context8.next = 4;return fImage(_this, res, config);case 4:case "end":return _context8.stop();}}}, _callee8, this);}));function success(_x15) {return _success2.apply(this, arguments);}return success;}() });


  }
};

// 过滤超出的预览图片以及上传(通用)
var fImage = function fImage(_this, res, config) {
  !config.notli && config.count == _this.upload_picture_list.length ? uImage(_this, config) : '';
  config.notli && config.count == 9 ? uImage(_this, config) : '';
  _this.upload_after_list = _this.upload_after_list.concat(res.tempFilePaths).slice(0, config.count);
  _this.upload_picture_list = _this.upload_picture_list.slice(0, config.count);
};


// 上传前预览图片(通用)
var pImage = function pImage(e, _this) {
  uni.previewImage({
    current: _this.upload_after_list[e.currentTarget.dataset.index],
    urls: _this.upload_after_list });

};

// 上传后预览(通用)
var puImage = /*#__PURE__*/function () {var _ref6 = _asyncToGenerator( /*#__PURE__*/_regenerator.default.mark(function _callee9(e, _this) {var cacheImg, i, len;return _regenerator.default.wrap(function _callee9$(_context9) {while (1) {switch (_context9.prev = _context9.next) {case 0:
            cacheImg = [];
            for (i = 0, len = _this.upload_picture_list.length; i < len; i++) {
              cacheImg.push(_this.upload_picture_list[i].path);
            }
            uni.previewImage({
              current: cacheImg[e.currentTarget.dataset.idx],
              urls: cacheImg });case 3:case "end":return _context9.stop();}}}, _callee9, this);}));return function puImage(_x16, _x17) {return _ref6.apply(this, arguments);};}();
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./node_modules/@dcloudio/uni-mp-weixin/dist/index.js */ "./node_modules/@dcloudio/uni-mp-weixin/dist/index.js")["default"]))

/***/ }),

/***/ "./node_modules/mini-css-extract-plugin/dist/loader.js?!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader/index.js?!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/lib/loader.js?!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader/index.js?!./node_modules/vue-loader/lib/index.js?!D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\App.vue?vue&type=style&index=0&lang=scss&":
/*!************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/mini-css-extract-plugin/dist/loader.js??ref--8-oneOf-1-0!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--8-oneOf-1-1!./node_modules/css-loader??ref--8-oneOf-1-2!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--8-oneOf-1-3!./node_modules/sass-loader/lib/loader.js??ref--8-oneOf-1-4!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--8-oneOf-1-5!./node_modules/vue-loader/lib??vue-loader-options!D:/phpStudy/WWW/fx70999_zy/uni-app/App.vue?vue&type=style&index=0&lang=scss& ***!
  \************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "./node_modules/mini-css-extract-plugin/dist/loader.js?!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader/index.js?!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\components\\sunui-upimg\\sunui-upimg-basic.vue?vue&type=style&index=0&id=aa006a84&scoped=true&lang=css&":
/*!************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/mini-css-extract-plugin/dist/loader.js??ref--6-oneOf-1-0!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--6-oneOf-1-1!./node_modules/css-loader??ref--6-oneOf-1-2!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-oneOf-1-3!./node_modules/vue-loader/lib??vue-loader-options!D:/phpStudy/WWW/fx70999_zy/uni-app/components/sunui-upimg/sunui-upimg-basic.vue?vue&type=style&index=0&id=aa006a84&scoped=true&lang=css& ***!
  \************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader/index.js?!./node_modules/@dcloudio/webpack-uni-mp-loader/lib/template.js!./node_modules/vue-loader/lib/index.js?!D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\components\\sunui-upimg\\sunui-upimg-basic.vue?vue&type=template&id=aa006a84&scoped=true&name=sunui-upimg&":
/*!******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--17-0!./node_modules/@dcloudio/webpack-uni-mp-loader/lib/template.js!./node_modules/vue-loader/lib??vue-loader-options!D:/phpStudy/WWW/fx70999_zy/uni-app/components/sunui-upimg/sunui-upimg-basic.vue?vue&type=template&id=aa006a84&scoped=true&name=sunui-upimg& ***!
  \******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("view", [
    _c(
      "view",
      { staticClass: "sunsin_picture_list" },
      [
        _vm._l(_vm.upload_picture_list, function(item, index) {
          return _c(
            "view",
            { key: index, staticClass: "sunsin_picture_item" },
            [
              _c("image", {
                directives: [
                  {
                    name: "show",
                    rawName: "v-show",
                    value: item.upload_percent < 100,
                    expression: "item.upload_percent < 100"
                  }
                ],
                attrs: { src: item.path, mode: "aspectFill" }
              }),
              _c("image", {
                directives: [
                  {
                    name: "show",
                    rawName: "v-show",
                    value: item.upload_percent == 100,
                    expression: "item.upload_percent == 100"
                  }
                ],
                attrs: {
                  src: item.path_server,
                  mode: "aspectFill",
                  "data-idx": index,
                  eventid: "4bf25d7d-0-" + index
                },
                on: { click: _vm.previewImgs }
              }),
              _c(
                "view",
                {
                  directives: [
                    {
                      name: "show",
                      rawName: "v-show",
                      value: item.upload_percent < 100,
                      expression: "item.upload_percent < 100"
                    }
                  ],
                  staticClass: "sunsin_upload_progress",
                  attrs: {
                    "data-index": index,
                    eventid: "4bf25d7d-1-" + index
                  },
                  on: { click: _vm.previewImg }
                },
                [_vm._v(_vm._s(item.upload_percent) + "%")]
              ),
              _c("text", {
                staticClass: "del iconfont icon-shanchu",
                class:
                  _vm.upImgConfig.delBtnLocation == "left"
                    ? "left"
                    : _vm.upImgConfig.delBtnLocation == "right"
                    ? "right"
                    : _vm.upImgConfig.delBtnLocation == "bleft"
                    ? "bleft"
                    : _vm.upImgConfig.delBtnLocation == "bright"
                    ? "bright"
                    : "right",
                style:
                  "color:" +
                  _vm.upImgConfig.delIconText +
                  ";background-color:" +
                  _vm.upImgConfig.delIconColor,
                attrs: {
                  "data-url": item.path_server,
                  "data-index": index,
                  hidden: _vm.upImgConfig.isDelIcon || false,
                  eventid: "4bf25d7d-2-" + index
                },
                on: { click: _vm.deleteImg }
              })
            ]
          )
        }),
        _c("view", [
          _vm.upImgConfig.iconReplace == "" ||
          _vm.upImgConfig.iconReplace == undefined
            ? _c(
                "view",
                {
                  directives: [
                    {
                      name: "show",
                      rawName: "v-show",
                      value:
                        _vm.upload_picture_list.length <
                          _vm.upImgConfig.count || _vm.upImgConfig.notli,
                      expression:
                        "upload_picture_list.length<upImgConfig.count || upImgConfig.notli"
                    }
                  ],
                  staticClass: "sunsin_picture_item"
                },
                [
                  _c(
                    "view",
                    {
                      directives: [
                        {
                          name: "show",
                          rawName: "v-show",
                          value: !_vm.upImgConfig.isAddImage || false,
                          expression: "!upImgConfig.isAddImage || false"
                        }
                      ],
                      staticClass: "sunsin_add_image",
                      style:
                        "background-color:" + _vm.upImgConfig.upBgColor + "",
                      attrs: { eventid: "4bf25d7d-3" },
                      on: {
                        click: function($event) {
                          _vm.chooseImage(_vm.upImgConfig.count)
                        }
                      }
                    },
                    [
                      _c("view", {
                        staticClass: "icon-basic",
                        class:
                          _vm.upImgConfig.upSvgIconName == undefined ||
                          _vm.upImgConfig.upSvgIconName == ""
                            ? "icon-addicon"
                            : _vm.upImgConfig.upSvgIconName
                      }),
                      _c(
                        "view",
                        {
                          staticClass: "icon-text",
                          style:
                            "color:" +
                            _vm.upImgConfig.upIconColor +
                            ";width:100%;"
                        },
                        [
                          _vm._v(
                            _vm._s(
                              _vm.upImgConfig.upTextDesc == undefined ||
                                _vm.upImgConfig.upTextDesc == ""
                                ? "上传照片"
                                : _vm.upImgConfig.upTextDesc
                            )
                          )
                        ]
                      )
                    ]
                  )
                ]
              )
            : _c(
                "view",
                {
                  directives: [
                    {
                      name: "show",
                      rawName: "v-show",
                      value:
                        _vm.upload_picture_list.length <
                          _vm.upImgConfig.count || _vm.upImgConfig.notli,
                      expression:
                        "upload_picture_list.length<upImgConfig.count || upImgConfig.notli"
                    }
                  ],
                  staticClass: "sunsin_picture_item"
                },
                [
                  _c(
                    "view",
                    {
                      directives: [
                        {
                          name: "show",
                          rawName: "v-show",
                          value: _vm.upImgConfig.isAddImage || true,
                          expression: "upImgConfig.isAddImage || true"
                        }
                      ],
                      staticClass: "sunsin_add_image",
                      staticStyle: { "'background-color": "#fff" },
                      attrs: { eventid: "4bf25d7d-4" },
                      on: {
                        click: function($event) {
                          _vm.chooseImage(_vm.upImgConfig.count)
                        }
                      }
                    },
                    [
                      _c("image", {
                        staticClass: "icon_replace",
                        attrs: { src: _vm.upImgConfig.iconReplace }
                      })
                    ]
                  )
                ]
              )
        ])
      ],
      2
    )
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\App.vue":
/*!**************************************************!*\
  !*** D:/phpStudy/WWW/fx70999_zy/uni-app/App.vue ***!
  \**************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _App_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./App.vue?vue&type=script&lang=js& */ "D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\App.vue?vue&type=script&lang=js&");
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _App_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _App_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
/* harmony import */ var _App_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./App.vue?vue&type=style&index=0&lang=scss& */ "D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\App.vue?vue&type=style&index=0&lang=scss&");
/* harmony import */ var _E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");
var render, staticRenderFns





/* normalize component */

var component = Object(_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _App_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"],
  render,
  staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "D:/phpStudy/WWW/fx70999_zy/uni-app/App.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\App.vue?vue&type=script&lang=js&":
/*!***************************************************************************!*\
  !*** D:/phpStudy/WWW/fx70999_zy/uni-app/App.vue?vue&type=script&lang=js& ***!
  \***************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _E_HBuilderX_plugins_uniapp_cli_node_modules_babel_loader_lib_index_js_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_12_1_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_18_0_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_script_js_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_App_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!./node_modules/babel-loader/lib!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--12-1!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--18-0!./node_modules/@dcloudio/webpack-uni-mp-loader/lib/script.js!./node_modules/vue-loader/lib??vue-loader-options!./App.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader/index.js?!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader/index.js?!./node_modules/@dcloudio/webpack-uni-mp-loader/lib/script.js!./node_modules/vue-loader/lib/index.js?!D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\App.vue?vue&type=script&lang=js&");
/* harmony import */ var _E_HBuilderX_plugins_uniapp_cli_node_modules_babel_loader_lib_index_js_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_12_1_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_18_0_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_script_js_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_App_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_E_HBuilderX_plugins_uniapp_cli_node_modules_babel_loader_lib_index_js_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_12_1_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_18_0_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_script_js_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_App_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _E_HBuilderX_plugins_uniapp_cli_node_modules_babel_loader_lib_index_js_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_12_1_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_18_0_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_script_js_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_App_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _E_HBuilderX_plugins_uniapp_cli_node_modules_babel_loader_lib_index_js_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_12_1_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_18_0_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_script_js_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_App_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_E_HBuilderX_plugins_uniapp_cli_node_modules_babel_loader_lib_index_js_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_12_1_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_18_0_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_script_js_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_App_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ "D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\App.vue?vue&type=style&index=0&lang=scss&":
/*!************************************************************************************!*\
  !*** D:/phpStudy/WWW/fx70999_zy/uni-app/App.vue?vue&type=style&index=0&lang=scss& ***!
  \************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _E_HBuilderX_plugins_uniapp_cli_node_modules_mini_css_extract_plugin_dist_loader_js_ref_8_oneOf_1_0_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_8_oneOf_1_1_E_HBuilderX_plugins_uniapp_cli_node_modules_css_loader_index_js_ref_8_oneOf_1_2_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_loaders_stylePostLoader_js_E_HBuilderX_plugins_uniapp_cli_node_modules_postcss_loader_src_index_js_ref_8_oneOf_1_3_E_HBuilderX_plugins_uniapp_cli_node_modules_sass_loader_lib_loader_js_ref_8_oneOf_1_4_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_8_oneOf_1_5_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_App_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!./node_modules/mini-css-extract-plugin/dist/loader.js??ref--8-oneOf-1-0!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--8-oneOf-1-1!./node_modules/css-loader??ref--8-oneOf-1-2!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--8-oneOf-1-3!./node_modules/sass-loader/lib/loader.js??ref--8-oneOf-1-4!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--8-oneOf-1-5!./node_modules/vue-loader/lib??vue-loader-options!./App.vue?vue&type=style&index=0&lang=scss& */ "./node_modules/mini-css-extract-plugin/dist/loader.js?!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader/index.js?!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/lib/loader.js?!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader/index.js?!./node_modules/vue-loader/lib/index.js?!D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\App.vue?vue&type=style&index=0&lang=scss&");
/* harmony import */ var _E_HBuilderX_plugins_uniapp_cli_node_modules_mini_css_extract_plugin_dist_loader_js_ref_8_oneOf_1_0_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_8_oneOf_1_1_E_HBuilderX_plugins_uniapp_cli_node_modules_css_loader_index_js_ref_8_oneOf_1_2_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_loaders_stylePostLoader_js_E_HBuilderX_plugins_uniapp_cli_node_modules_postcss_loader_src_index_js_ref_8_oneOf_1_3_E_HBuilderX_plugins_uniapp_cli_node_modules_sass_loader_lib_loader_js_ref_8_oneOf_1_4_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_8_oneOf_1_5_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_App_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_E_HBuilderX_plugins_uniapp_cli_node_modules_mini_css_extract_plugin_dist_loader_js_ref_8_oneOf_1_0_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_8_oneOf_1_1_E_HBuilderX_plugins_uniapp_cli_node_modules_css_loader_index_js_ref_8_oneOf_1_2_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_loaders_stylePostLoader_js_E_HBuilderX_plugins_uniapp_cli_node_modules_postcss_loader_src_index_js_ref_8_oneOf_1_3_E_HBuilderX_plugins_uniapp_cli_node_modules_sass_loader_lib_loader_js_ref_8_oneOf_1_4_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_8_oneOf_1_5_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_App_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _E_HBuilderX_plugins_uniapp_cli_node_modules_mini_css_extract_plugin_dist_loader_js_ref_8_oneOf_1_0_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_8_oneOf_1_1_E_HBuilderX_plugins_uniapp_cli_node_modules_css_loader_index_js_ref_8_oneOf_1_2_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_loaders_stylePostLoader_js_E_HBuilderX_plugins_uniapp_cli_node_modules_postcss_loader_src_index_js_ref_8_oneOf_1_3_E_HBuilderX_plugins_uniapp_cli_node_modules_sass_loader_lib_loader_js_ref_8_oneOf_1_4_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_8_oneOf_1_5_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_App_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _E_HBuilderX_plugins_uniapp_cli_node_modules_mini_css_extract_plugin_dist_loader_js_ref_8_oneOf_1_0_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_8_oneOf_1_1_E_HBuilderX_plugins_uniapp_cli_node_modules_css_loader_index_js_ref_8_oneOf_1_2_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_loaders_stylePostLoader_js_E_HBuilderX_plugins_uniapp_cli_node_modules_postcss_loader_src_index_js_ref_8_oneOf_1_3_E_HBuilderX_plugins_uniapp_cli_node_modules_sass_loader_lib_loader_js_ref_8_oneOf_1_4_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_8_oneOf_1_5_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_App_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_E_HBuilderX_plugins_uniapp_cli_node_modules_mini_css_extract_plugin_dist_loader_js_ref_8_oneOf_1_0_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_8_oneOf_1_1_E_HBuilderX_plugins_uniapp_cli_node_modules_css_loader_index_js_ref_8_oneOf_1_2_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_loaders_stylePostLoader_js_E_HBuilderX_plugins_uniapp_cli_node_modules_postcss_loader_src_index_js_ref_8_oneOf_1_3_E_HBuilderX_plugins_uniapp_cli_node_modules_sass_loader_lib_loader_js_ref_8_oneOf_1_4_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_8_oneOf_1_5_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_App_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ "D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\components\\sunui-upimg\\sunui-upimg-basic.vue":
/*!***************************************************************************************!*\
  !*** D:/phpStudy/WWW/fx70999_zy/uni-app/components/sunui-upimg/sunui-upimg-basic.vue ***!
  \***************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _sunui_upimg_basic_vue_vue_type_template_id_aa006a84_scoped_true_name_sunui_upimg___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./sunui-upimg-basic.vue?vue&type=template&id=aa006a84&scoped=true&name=sunui-upimg& */ "D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\components\\sunui-upimg\\sunui-upimg-basic.vue?vue&type=template&id=aa006a84&scoped=true&name=sunui-upimg&");
/* harmony import */ var _sunui_upimg_basic_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./sunui-upimg-basic.vue?vue&type=script&lang=js& */ "D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\components\\sunui-upimg\\sunui-upimg-basic.vue?vue&type=script&lang=js&");
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _sunui_upimg_basic_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _sunui_upimg_basic_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__[key]; }) }(__WEBPACK_IMPORT_KEY__));
/* harmony import */ var _sunui_upimg_basic_vue_vue_type_style_index_0_id_aa006a84_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./sunui-upimg-basic.vue?vue&type=style&index=0&id=aa006a84&scoped=true&lang=css& */ "D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\components\\sunui-upimg\\sunui-upimg-basic.vue?vue&type=style&index=0&id=aa006a84&scoped=true&lang=css&");
/* harmony import */ var _E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _sunui_upimg_basic_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _sunui_upimg_basic_vue_vue_type_template_id_aa006a84_scoped_true_name_sunui_upimg___WEBPACK_IMPORTED_MODULE_0__["render"],
  _sunui_upimg_basic_vue_vue_type_template_id_aa006a84_scoped_true_name_sunui_upimg___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "aa006a84",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "D:/phpStudy/WWW/fx70999_zy/uni-app/components/sunui-upimg/sunui-upimg-basic.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\components\\sunui-upimg\\sunui-upimg-basic.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************************!*\
  !*** D:/phpStudy/WWW/fx70999_zy/uni-app/components/sunui-upimg/sunui-upimg-basic.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _E_HBuilderX_plugins_uniapp_cli_node_modules_babel_loader_lib_index_js_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_12_1_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_18_0_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_script_js_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_sunui_upimg_basic_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!./node_modules/babel-loader/lib!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--12-1!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--18-0!./node_modules/@dcloudio/webpack-uni-mp-loader/lib/script.js!./node_modules/vue-loader/lib??vue-loader-options!./sunui-upimg-basic.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader/index.js?!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader/index.js?!./node_modules/@dcloudio/webpack-uni-mp-loader/lib/script.js!./node_modules/vue-loader/lib/index.js?!D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\components\\sunui-upimg\\sunui-upimg-basic.vue?vue&type=script&lang=js&");
/* harmony import */ var _E_HBuilderX_plugins_uniapp_cli_node_modules_babel_loader_lib_index_js_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_12_1_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_18_0_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_script_js_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_sunui_upimg_basic_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_E_HBuilderX_plugins_uniapp_cli_node_modules_babel_loader_lib_index_js_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_12_1_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_18_0_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_script_js_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_sunui_upimg_basic_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _E_HBuilderX_plugins_uniapp_cli_node_modules_babel_loader_lib_index_js_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_12_1_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_18_0_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_script_js_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_sunui_upimg_basic_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _E_HBuilderX_plugins_uniapp_cli_node_modules_babel_loader_lib_index_js_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_12_1_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_18_0_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_script_js_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_sunui_upimg_basic_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_E_HBuilderX_plugins_uniapp_cli_node_modules_babel_loader_lib_index_js_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_12_1_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_18_0_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_script_js_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_sunui_upimg_basic_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ "D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\components\\sunui-upimg\\sunui-upimg-basic.vue?vue&type=style&index=0&id=aa006a84&scoped=true&lang=css&":
/*!************************************************************************************************************************************************!*\
  !*** D:/phpStudy/WWW/fx70999_zy/uni-app/components/sunui-upimg/sunui-upimg-basic.vue?vue&type=style&index=0&id=aa006a84&scoped=true&lang=css& ***!
  \************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _E_HBuilderX_plugins_uniapp_cli_node_modules_mini_css_extract_plugin_dist_loader_js_ref_6_oneOf_1_0_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_6_oneOf_1_1_E_HBuilderX_plugins_uniapp_cli_node_modules_css_loader_index_js_ref_6_oneOf_1_2_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_loaders_stylePostLoader_js_E_HBuilderX_plugins_uniapp_cli_node_modules_postcss_loader_src_index_js_ref_6_oneOf_1_3_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_sunui_upimg_basic_vue_vue_type_style_index_0_id_aa006a84_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!./node_modules/mini-css-extract-plugin/dist/loader.js??ref--6-oneOf-1-0!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--6-oneOf-1-1!./node_modules/css-loader??ref--6-oneOf-1-2!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-oneOf-1-3!./node_modules/vue-loader/lib??vue-loader-options!./sunui-upimg-basic.vue?vue&type=style&index=0&id=aa006a84&scoped=true&lang=css& */ "./node_modules/mini-css-extract-plugin/dist/loader.js?!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader/index.js?!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\components\\sunui-upimg\\sunui-upimg-basic.vue?vue&type=style&index=0&id=aa006a84&scoped=true&lang=css&");
/* harmony import */ var _E_HBuilderX_plugins_uniapp_cli_node_modules_mini_css_extract_plugin_dist_loader_js_ref_6_oneOf_1_0_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_6_oneOf_1_1_E_HBuilderX_plugins_uniapp_cli_node_modules_css_loader_index_js_ref_6_oneOf_1_2_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_loaders_stylePostLoader_js_E_HBuilderX_plugins_uniapp_cli_node_modules_postcss_loader_src_index_js_ref_6_oneOf_1_3_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_sunui_upimg_basic_vue_vue_type_style_index_0_id_aa006a84_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_E_HBuilderX_plugins_uniapp_cli_node_modules_mini_css_extract_plugin_dist_loader_js_ref_6_oneOf_1_0_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_6_oneOf_1_1_E_HBuilderX_plugins_uniapp_cli_node_modules_css_loader_index_js_ref_6_oneOf_1_2_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_loaders_stylePostLoader_js_E_HBuilderX_plugins_uniapp_cli_node_modules_postcss_loader_src_index_js_ref_6_oneOf_1_3_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_sunui_upimg_basic_vue_vue_type_style_index_0_id_aa006a84_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _E_HBuilderX_plugins_uniapp_cli_node_modules_mini_css_extract_plugin_dist_loader_js_ref_6_oneOf_1_0_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_6_oneOf_1_1_E_HBuilderX_plugins_uniapp_cli_node_modules_css_loader_index_js_ref_6_oneOf_1_2_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_loaders_stylePostLoader_js_E_HBuilderX_plugins_uniapp_cli_node_modules_postcss_loader_src_index_js_ref_6_oneOf_1_3_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_sunui_upimg_basic_vue_vue_type_style_index_0_id_aa006a84_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _E_HBuilderX_plugins_uniapp_cli_node_modules_mini_css_extract_plugin_dist_loader_js_ref_6_oneOf_1_0_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_6_oneOf_1_1_E_HBuilderX_plugins_uniapp_cli_node_modules_css_loader_index_js_ref_6_oneOf_1_2_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_loaders_stylePostLoader_js_E_HBuilderX_plugins_uniapp_cli_node_modules_postcss_loader_src_index_js_ref_6_oneOf_1_3_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_sunui_upimg_basic_vue_vue_type_style_index_0_id_aa006a84_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_E_HBuilderX_plugins_uniapp_cli_node_modules_mini_css_extract_plugin_dist_loader_js_ref_6_oneOf_1_0_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_6_oneOf_1_1_E_HBuilderX_plugins_uniapp_cli_node_modules_css_loader_index_js_ref_6_oneOf_1_2_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_loaders_stylePostLoader_js_E_HBuilderX_plugins_uniapp_cli_node_modules_postcss_loader_src_index_js_ref_6_oneOf_1_3_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_sunui_upimg_basic_vue_vue_type_style_index_0_id_aa006a84_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ "D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\components\\sunui-upimg\\sunui-upimg-basic.vue?vue&type=template&id=aa006a84&scoped=true&name=sunui-upimg&":
/*!***************************************************************************************************************************************************!*\
  !*** D:/phpStudy/WWW/fx70999_zy/uni-app/components/sunui-upimg/sunui-upimg-basic.vue?vue&type=template&id=aa006a84&scoped=true&name=sunui-upimg& ***!
  \***************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_17_0_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_template_js_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_sunui_upimg_basic_vue_vue_type_template_id_aa006a84_scoped_true_name_sunui_upimg___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--17-0!./node_modules/@dcloudio/webpack-uni-mp-loader/lib/template.js!./node_modules/vue-loader/lib??vue-loader-options!./sunui-upimg-basic.vue?vue&type=template&id=aa006a84&scoped=true&name=sunui-upimg& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader/index.js?!./node_modules/@dcloudio/webpack-uni-mp-loader/lib/template.js!./node_modules/vue-loader/lib/index.js?!D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\components\\sunui-upimg\\sunui-upimg-basic.vue?vue&type=template&id=aa006a84&scoped=true&name=sunui-upimg&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_17_0_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_template_js_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_sunui_upimg_basic_vue_vue_type_template_id_aa006a84_scoped_true_name_sunui_upimg___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_17_0_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_template_js_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_sunui_upimg_basic_vue_vue_type_template_id_aa006a84_scoped_true_name_sunui_upimg___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\main.js":
/*!**************************************************!*\
  !*** D:/phpStudy/WWW/fx70999_zy/uni-app/main.js ***!
  \**************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/* WEBPACK VAR INJECTION */(function(uni) {__webpack_require__(/*! uni-pages */ "D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\pages.json");
var _vue = _interopRequireDefault(__webpack_require__(/*! vue */ "./node_modules/@dcloudio/vue-cli-plugin-uni/packages/mpvue/index.js"));
var _store = _interopRequireDefault(__webpack_require__(/*! ./store */ "D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\store\\index.js"));
var _App = _interopRequireDefault(__webpack_require__(/*! ./App */ "D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\App.vue"));
var _sunuiUpimgBasic = _interopRequireDefault(__webpack_require__(/*! components/sunui-upimg/sunui-upimg-basic.vue */ "D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\components\\sunui-upimg\\sunui-upimg-basic.vue"));

var _Json = _interopRequireDefault(__webpack_require__(/*! ./Json */ "D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\Json.js"));function _interopRequireDefault(obj) {return obj && obj.__esModule ? obj : { default: obj };}function _objectSpread(target) {for (var i = 1; i < arguments.length; i++) {var source = arguments[i] != null ? arguments[i] : {};var ownKeys = Object.keys(source);if (typeof Object.getOwnPropertySymbols === 'function') {ownKeys = ownKeys.concat(Object.getOwnPropertySymbols(source).filter(function (sym) {return Object.getOwnPropertyDescriptor(source, sym).enumerable;}));}ownKeys.forEach(function (key) {_defineProperty(target, key, source[key]);});}return target;}function _defineProperty(obj, key, value) {if (key in obj) {Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true });} else {obj[key] = value;}return obj;} //测试用数据
/**
 *  因工具函数属于公司资产, 所以直接在Vue实例挂载几个常用的函数
 *  所有测试用数据均存放于根目录json.js
 *  
 *  css部分使用了App.vue下的全局样式和iconfont图标，有需要图标库的可以留言。
 *  示例使用了uni.scss下的变量, 除变量外已尽量移除特有语法,可直接替换为其他预处理器使用
 */
var msg = function msg(title) {var duration = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 1500;var mask = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : false;var icon = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : 'none';
  //统一提示方便全局修改
  if (Boolean(title) === false) {
    return;
  }
  uni.showToast({
    title: title,
    duration: duration,
    mask: mask,
    icon: icon });

};
var json = function json(type) {
  //模拟异步请求数据
  return new Promise(function (resolve) {
    setTimeout(function () {
      resolve(_Json.default[type]);
    }, 500);
  });
};

var prePage = function prePage() {
  var pages = getCurrentPages();
  var prePage = pages[pages.length - 2];



  return prePage.$vm;
};
// 设置浏览器标题
_vue.default.directive('title', {
  inserted: function inserted(el, binding) {
    document.title = el.dataset.title;
  } });


_vue.default.component('sunui-upbasic', _sunuiUpimgBasic.default);
_vue.default.config.productionTip = false;
_vue.default.prototype.$fire = new _vue.default();
_vue.default.prototype.$store = _store.default;
_vue.default.prototype.$api = {
  msg: msg,
  json: json,
  prePage: prePage };


_App.default.mpType = 'app';

var app = new _vue.default(_objectSpread({},
_App.default));

app.$mount();
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./node_modules/@dcloudio/uni-mp-weixin/dist/index.js */ "./node_modules/@dcloudio/uni-mp-weixin/dist/index.js")["default"]))

/***/ }),

/***/ "D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\store\\index.js":
/*!*********************************************************!*\
  !*** D:/phpStudy/WWW/fx70999_zy/uni-app/store/index.js ***!
  \*********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/* WEBPACK VAR INJECTION */(function(uni) {Object.defineProperty(exports, "__esModule", { value: true });exports.default = void 0;var _vue = _interopRequireDefault(__webpack_require__(/*! vue */ "./node_modules/@dcloudio/vue-cli-plugin-uni/packages/mpvue/index.js"));
var _vuex = _interopRequireDefault(__webpack_require__(/*! vuex */ "./node_modules/vuex/dist/vuex.esm.js"));function _interopRequireDefault(obj) {return obj && obj.__esModule ? obj : { default: obj };}

_vue.default.use(_vuex.default);

var store = new _vuex.default.Store({
  state: {
    hasLogin: false,
    userInfo: {},
    bi: {},
    user_id: 0,


    //首屏广告上次显示时间
    splashAdvertPreTime: 0 },



  mutations: {

    login: function login(state, provider) {
      state.hasLogin = true;
      state.userInfo = provider.data;
      if (provider.data != null) {

        console.log(provider.data.re_share_title);
        state.user_id = provider.data.id;
        state.bi = provider.bi;
        uni.setStorage({ //缓存用户登陆状态
          key: 'userInfo',
          data: provider.data });

        uni.setStorage({ //缓存用户登陆状态
          key: 'bi',
          data: provider.bi });

        uni.setStorage({ //缓存用户登陆状态
          key: 'user_id',
          data: provider.data.id });

      }
    },
    logout: function logout(state) {
      console.log(2222);
      state.hasLogin = false;
      // state.userInfo = {};
      state.bi = {};

      uni.removeStorage({
        key: 'userInfo' });

      uni.removeStorage({
        key: 'bi' });

    } },

  actions: {} });var _default =




store;exports.default = _default;
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./node_modules/@dcloudio/uni-mp-weixin/dist/index.js */ "./node_modules/@dcloudio/uni-mp-weixin/dist/index.js")["default"]))

/***/ })

},[["D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\main.js","common/runtime","common/vendor"]]]);
//# sourceMappingURL=../../.sourcemap/mp-weixin/common/main.js.map