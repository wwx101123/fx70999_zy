(global["webpackJsonp"] = global["webpackJsonp"] || []).push([["pages/shop/dui_list"],{

/***/ "./node_modules/babel-loader/lib/index.js!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader/index.js?!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader/index.js?!./node_modules/@dcloudio/webpack-uni-mp-loader/lib/script.js!./node_modules/vue-loader/lib/index.js?!D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\pages\\shop\\dui_list.vue?vue&type=script&lang=js&":
/*!******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--12-1!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--18-0!./node_modules/@dcloudio/webpack-uni-mp-loader/lib/script.js!./node_modules/vue-loader/lib??vue-loader-options!D:/phpStudy/WWW/fx70999_zy/uni-app/pages/shop/dui_list.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/* WEBPACK VAR INJECTION */(function(uni) {Object.defineProperty(exports, "__esModule", { value: true });exports.default = void 0;

































































var _flyInCart = _interopRequireDefault(__webpack_require__(/*! @/components/fly-in-cart/fly-in-cart.vue */ "D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\components\\fly-in-cart\\fly-in-cart.vue"));
var _mescrollMeituan = _interopRequireDefault(__webpack_require__(/*! ../../components/mescroll-diy/mescroll-meituan.vue */ "D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\components\\mescroll-diy\\mescroll-meituan.vue"));
var _common = _interopRequireDefault(__webpack_require__(/*! ../../common/common.js */ "D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\common\\common.js"));
var _uniLoadMore = _interopRequireDefault(__webpack_require__(/*! @/components/uni-load-more/uni-load-more.vue */ "D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\components\\uni-load-more\\uni-load-more.vue"));
var _empty = _interopRequireDefault(__webpack_require__(/*! @/components/empty */ "D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\components\\empty.vue"));
var _vuex = __webpack_require__(/*! vuex */ "./node_modules/vuex/dist/vuex.esm.js");function _interopRequireDefault(obj) {return obj && obj.__esModule ? obj : { default: obj };}function _objectSpread(target) {for (var i = 1; i < arguments.length; i++) {var source = arguments[i] != null ? arguments[i] : {};var ownKeys = Object.keys(source);if (typeof Object.getOwnPropertySymbols === 'function') {ownKeys = ownKeys.concat(Object.getOwnPropertySymbols(source).filter(function (sym) {return Object.getOwnPropertyDescriptor(source, sym).enumerable;}));}ownKeys.forEach(function (key) {_defineProperty(target, key, source[key]);});}return target;}function _defineProperty(obj, key, value) {if (key in obj) {Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true });} else {obj[key] = value;}return obj;}var _default =


{
  components: {
    MescrollUni: _mescrollMeituan.default,
    shopCarAnimation: _flyInCart.default },

  computed: _objectSpread({},
  (0, _vuex.mapState)(['hasLogin', 'userInfo', 'bi', 'goods_id'])),

  data: function data() {
    return {
      cateMaskState: 0, //分类面板展开状态
      mescroll: null, //mescroll实例对象
      // 下拉刷新的配置
      downOption: {
        use: true, // 是否启用下拉刷新; 默认true
        auto: true // 是否在初始化完毕之后自动执行下拉刷新的回调; 默认true
      },
      // 上拉加载的配置
      upOption: {
        use: false, // 是否启用上拉加载; 默认true
        auto: false },

      headerPosition: "fixed",
      headerTop: "0px",
      loadingType: 'more', //加载更多状态
      filterIndex: 0,
      Id: 0,
      cateId: 0, //已选三级分类id
      priceOrder: 0, //1 价格从低到高 2价格从高到低
      cateList: [],
      goodsList: [],
      totalAmount: 0 };

  },

  // 必须注册滚动到底部的事件,使上拉加载生效
  onReachBottom: function onReachBottom() {
    this.mescroll && this.mescroll.onReachBottom();
  },
  // 必须注册列表滚动事件,使下拉刷新生效
  onPageScroll: function onPageScroll(e) {
    this.mescroll && this.mescroll.onPageScroll(e);
  },
  onLoad: function onLoad(options) {
    wx.setNavigationBarTitle({
      title: options.title });





    this.cateId = options.tid;
    this.Id = options.sid;
  },

  methods: {

    // mescroll组件初始化的回调,可获取到mescroll对象
    mescrollInit: function mescrollInit(mescroll) {
      this.mescroll = mescroll;
    },
    // 下拉刷新的回调
    downCallback: function downCallback(mescroll) {
      mescroll.resetUpScroll(); // 重置列表为第一页 (自动执行 mescroll.num=1, 再触发upCallback方法 )
    },
    /*上拉加载的回调: mescroll携带page的参数, 其中num:当前页 从1开始, size:每页数据条数,默认10 */
    upCallback: function upCallback(mescroll) {
      var that = this;
      //联网加载数据
      this.getListDataFromNet(_common.default.get_goods_listUrl, mescroll.num, mescroll.size, function (curPageData) {
        //curPageData=[]; //打开本行注释,可演示列表无任何数据empty的配置

        //联网成功的回调,隐藏下拉刷新和上拉加载的状态;
        //mescroll会根据传的参数,自动判断列表如果无任何数据,则提示空;列表无下一页数据,则提示无更多数据;


        //方法一(推荐): 后台接口有返回列表的总页数 totalPage
        //mescroll.endByPage(curPageData.length, totalPage); //必传参数(当前页的数据个数, 总页数)

        //方法二(推荐): 后台接口有返回列表的总数据量 totalSize
        //mescroll.endBySize(curPageData.length, totalSize); //必传参数(当前页的数据个数, 总数据量)

        //方法三(推荐): 您有其他方式知道是否有下一页 hasNext
        //mescroll.endSuccess(curPageData.length, hasNext); //必传参数(当前页的数据个数, 是否有下一页true/false)

        //方法四 (不推荐),会存在一个小问题:比如列表共有20条数据,每页加载10条,共2页.如果只根据当前页的数据个数判断,则需翻到第三页才会知道无更多数据,如果传了hasNext,则翻到第二页即可显示无更多数据.
        mescroll.endSuccess(curPageData.data.length);

        //设置列表数据
        if (mescroll.num == 1) that.goodsList = []; //如果是第一页需手动制空列表
        that.goodsList = that.goodsList.concat(curPageData.data); //追加新数据

        that.cateList = curPageData.category;
        console.log(curPageData.dui_cart_money);
        that.totalAmount = curPageData.dui_cart_money;


      }, function () {
        //联网失败的回调,隐藏下拉刷新的状态
        mescroll.endErr();
      });
    },
    /*联网加载列表数据
       在您的实际项目中,请参考官方写法: http://www.mescroll.com/uni.html#tagUpCallback
       请忽略getListDataFromNet的逻辑,这里仅仅是在本地模拟分页数据,本地演示用
       实际项目以您服务器接口返回的数据为准,无需本地处理分页.
       * */
    getListDataFromNet: function getListDataFromNet(url, pageNum, pageSize, successCallback, errorCallback) {
      var that = this;
      console.log(that.filterIndex);
      //延时一秒,模拟联网
      setTimeout(function () {
        try {

          uni.request({
            url: url, //仅为示例，并非真实接口地址。
            data: {
              is_mobile: 1,
              category_id: that.cateId,
              user_id: that.userInfo.id,
              page_index: pageNum,
              page_size: pageSize,
              keyword: '',
              goods_type: 1,
              order: that.filterIndex,
              priceOrder: that.priceOrder },

            method: 'POST',
            header: {
              'content-type': 'application/x-www-form-urlencoded' },

            success: function success(res) {
              console.log(res.data.data);
              if (res.data.status == 1) {

                // that.orderList.push(res.data.data)

              } else {



              }
              if (res.data.data == null) {
                res.data.data = [];
              }
              successCallback && successCallback(res.data);





            } });


          // 
          // //模拟分页数据
          // var listData = [];
          // for (var i = (pageNum - 1) * pageSize; i < pageNum * pageSize; i++) {
          // 	if (i == mockData.length) break;
          // 	listData.push(mockData[i]);
          // }
          // //联网成功的回调
        } catch (e) {
          //联网失败的回调
          errorCallback && errorCallback();
        }
      }, 10);
    },


    //筛选点击
    tabClick: function tabClick(index) {
      if (this.filterIndex === index && index !== 2) {
        return;
      }
      this.filterIndex = index;
      if (index === 2) {
        this.priceOrder = this.priceOrder === 1 ? 2 : 1;
      } else {
        this.priceOrder = 0;
      }
      this.mescroll.triggerDownScroll();
      // this.loadData('refresh', 1);
      // uni.showLoading({
      // 	title: '正在加载'
      // })
    },
    //显示分类面板
    toggleCateMask: function toggleCateMask(type) {var _this = this;
      var timer = type === 'show' ? 10 : 300;
      var state = type === 'show' ? 1 : 0;
      this.cateMaskState = 2;
      setTimeout(function () {
        _this.cateMaskState = state;
      }, timer);
    },
    //分类点击
    changeCate: function changeCate(item) {
      this.cateId = item.id;
      this.toggleCateMask();
      uni.pageScrollTo({
        duration: 300,
        scrollTop: 0 });


      this.mescroll.triggerDownScroll();

    },
    //详情
    navToDetailPage: function navToDetailPage(item) {
      //测试数据没有写id，用title代替
      var id = item.id;
      uni.navigateTo({
        url: "/pages/product/product?id=".concat(id) });

    },
    stopPrevent: function stopPrevent() {},
    // 加入购物车
    addShopCar: function addShopCar(e) {
      console.log('加入购物车');
      // 成功的话，调用加入购物车动画 
      var that = this;
      console.log(e.currentTarget.id);
      uni.request({
        url: _common.default.dui_cart_goods_add, //仅为示例，并非真实接口地址。
        data: {
          "actiontype": 'add',
          "is_mobile": 1,
          "user_id": that.userInfo.id,
          "article_id": e.currentTarget.id,
          "goods_id": 0,
          "quantity": 1,
          "hot_id": 0 },

        method: 'POST',
        header: {
          'content-type': 'application/x-www-form-urlencoded' },

        success: function success(res) {
          console.log(res.data.status);
          if (res.data.status == 1) {


            that.$refs.carAnmation.touchOnGoods(e);


            that.totalAmount = res.data.amount;

            that.detail(that.userInfo, that.goods_id);

          } else {



          }



        } });

    },
    submit: function submit() {

      uni.navigateTo({
        url: '/pages/shop/createOrder' });


    } } };exports.default = _default;
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./node_modules/@dcloudio/uni-mp-weixin/dist/index.js */ "./node_modules/@dcloudio/uni-mp-weixin/dist/index.js")["default"]))

/***/ }),

/***/ "./node_modules/mini-css-extract-plugin/dist/loader.js?!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader/index.js?!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/lib/loader.js?!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader/index.js?!./node_modules/vue-loader/lib/index.js?!D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\pages\\shop\\dui_list.vue?vue&type=style&index=0&lang=scss&":
/*!****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/mini-css-extract-plugin/dist/loader.js??ref--8-oneOf-1-0!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--8-oneOf-1-1!./node_modules/css-loader??ref--8-oneOf-1-2!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--8-oneOf-1-3!./node_modules/sass-loader/lib/loader.js??ref--8-oneOf-1-4!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--8-oneOf-1-5!./node_modules/vue-loader/lib??vue-loader-options!D:/phpStudy/WWW/fx70999_zy/uni-app/pages/shop/dui_list.vue?vue&type=style&index=0&lang=scss& ***!
  \****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader/index.js?!./node_modules/@dcloudio/webpack-uni-mp-loader/lib/template.js!./node_modules/vue-loader/lib/index.js?!D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\pages\\shop\\dui_list.vue?vue&type=template&id=60bad158&":
/*!****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--17-0!./node_modules/@dcloudio/webpack-uni-mp-loader/lib/template.js!./node_modules/vue-loader/lib??vue-loader-options!D:/phpStudy/WWW/fx70999_zy/uni-app/pages/shop/dui_list.vue?vue&type=template&id=60bad158& ***!
  \****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
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
  return _c(
    "view",
    { staticClass: "content" },
    [
      _c("div", {
        directives: [{ name: "title", rawName: "v-title" }],
        staticClass: "main",
        attrs: { "data-title": "登录" }
      }),
      _c(
        "view",
        {
          staticClass: "navbar",
          style: { position: _vm.headerPosition, top: _vm.headerTop }
        },
        [
          _c(
            "view",
            {
              staticClass: "nav-item",
              class: { current: _vm.filterIndex === 0 },
              attrs: { eventid: "3de9b805-0" },
              on: {
                click: function($event) {
                  _vm.tabClick(0)
                }
              }
            },
            [_vm._v("综合排序")]
          ),
          _c(
            "view",
            {
              staticClass: "nav-item",
              class: { current: _vm.filterIndex === 1 },
              attrs: { eventid: "3de9b805-1" },
              on: {
                click: function($event) {
                  _vm.tabClick(1)
                }
              }
            },
            [_vm._v("销量优先")]
          ),
          _c(
            "view",
            {
              staticClass: "nav-item",
              class: { current: _vm.filterIndex === 2 },
              attrs: { eventid: "3de9b805-2" },
              on: {
                click: function($event) {
                  _vm.tabClick(2)
                }
              }
            },
            [
              _c("text", [_vm._v("价格")]),
              _c("view", { staticClass: "p-box" }, [
                _c("text", {
                  staticClass: "yticon icon-shang",
                  class: {
                    active: _vm.priceOrder === 1 && _vm.filterIndex === 2
                  }
                }),
                _c("text", {
                  staticClass: "yticon icon-shang xia",
                  class: {
                    active: _vm.priceOrder === 2 && _vm.filterIndex === 2
                  }
                })
              ])
            ]
          ),
          _c("text", {
            staticClass: "cate-item yticon icon-fenlei1",
            attrs: { eventid: "3de9b805-3" },
            on: {
              click: function($event) {
                _vm.toggleCateMask("show")
              }
            }
          })
        ]
      ),
      _c(
        "mescroll-uni",
        {
          attrs: { eventid: "3de9b805-5", mpcomid: "3de9b805-0" },
          on: {
            down: _vm.downCallback,
            up: _vm.upCallback,
            init: _vm.mescrollInit
          }
        },
        [
          _c(
            "view",
            { staticClass: "goods-list" },
            _vm._l(_vm.goodsList, function(item, index) {
              return _c("view", { key: index, staticClass: "goods-item" }, [
                _c("view", { staticClass: "image-wrapper" }, [
                  _c("image", { attrs: { src: item.icon, mode: "aspectFill" } })
                ]),
                _c("text", { staticClass: "title clamp" }, [
                  _vm._v(_vm._s(item.title))
                ]),
                _c("view", { staticClass: "price-box" }, [
                  _c("text", { staticClass: "price" }, [
                    _vm._v(_vm._s(item.price))
                  ]),
                  _c(
                    "view",
                    {
                      staticClass: "pb-car iconfont",
                      attrs: {
                        id: item.id,
                        "data-img": item.icon,
                        eventid: "3de9b805-4-" + index
                      },
                      on: { tap: _vm.addShopCar }
                    },
                    [
                      _c("image", {
                        attrs: { src: "../../static/shopcar.png" }
                      })
                    ]
                  )
                ])
              ])
            })
          )
        ]
      ),
      _c(
        "view",
        {
          staticClass: "cate-mask",
          class:
            _vm.cateMaskState === 0
              ? "none"
              : _vm.cateMaskState === 1
              ? "show"
              : "",
          attrs: { eventid: "3de9b805-8" },
          on: { click: _vm.toggleCateMask }
        },
        [
          _c(
            "view",
            {
              staticClass: "cate-content",
              attrs: { eventid: "3de9b805-7" },
              on: {
                click: function($event) {
                  $event.stopPropagation()
                  $event.preventDefault()
                  _vm.stopPrevent($event)
                },
                touchmove: function($event) {
                  $event.stopPropagation()
                  $event.preventDefault()
                  _vm.stopPrevent($event)
                }
              }
            },
            [
              _c(
                "scroll-view",
                { staticClass: "cate-list", attrs: { "scroll-y": "" } },
                _vm._l(_vm.cateList, function(item, index0) {
                  return _c(
                    "view",
                    { key: item.id },
                    [
                      _c("view", { staticClass: "cate-item b-b two" }, [
                        _vm._v(_vm._s(item.title))
                      ]),
                      _vm._l(item.child, function(tItem, index1) {
                        return _c(
                          "view",
                          {
                            key: tItem.id,
                            staticClass: "cate-item b-b",
                            class: { active: tItem.id == _vm.cateId },
                            attrs: {
                              eventid: "3de9b805-6-" + index0 + "-" + index1
                            },
                            on: {
                              click: function($event) {
                                _vm.changeCate(tItem)
                              }
                            }
                          },
                          [_vm._v(_vm._s(tItem.title))]
                        )
                      })
                    ],
                    2
                  )
                })
              )
            ],
            1
          )
        ]
      ),
      _c("view", { staticClass: "footer" }, [
        _c("view", { staticClass: "price-content" }, [
          _c("text", [_vm._v("已选择")]),
          _c("text", { staticClass: "price-tip" }, [_vm._v("￥")]),
          _c("text", { staticClass: "price" }, [
            _vm._v(_vm._s(_vm.totalAmount))
          ])
        ]),
        _c(
          "text",
          {
            staticClass: "submit",
            attrs: { eventid: "3de9b805-9" },
            on: { click: _vm.submit }
          },
          [_vm._v("提交兑换")]
        )
      ]),
      _c("shopCarAnimation", {
        ref: "carAnmation",
        attrs: { cartx: "0.1", carty: "1.1", mpcomid: "3de9b805-1" }
      })
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\main.js?{\"page\":\"pages%2Fshop%2Fdui_list\"}":
/*!*************************************************************************************!*\
  !*** D:/phpStudy/WWW/fx70999_zy/uni-app/main.js?{"page":"pages%2Fshop%2Fdui_list"} ***!
  \*************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
__webpack_require__(/*! uni-pages */ "D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\pages.json");
var _mpvuePageFactory = _interopRequireDefault(__webpack_require__(/*! mpvue-page-factory */ "./node_modules/@dcloudio/vue-cli-plugin-uni/packages/mpvue-page-factory/index.js"));
var _dui_list = _interopRequireDefault(__webpack_require__(/*! ./pages/shop/dui_list.vue */ "D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\pages\\shop\\dui_list.vue"));function _interopRequireDefault(obj) {return obj && obj.__esModule ? obj : { default: obj };}
Page((0, _mpvuePageFactory.default)(_dui_list.default));

/***/ }),

/***/ "D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\pages\\shop\\dui_list.vue":
/*!******************************************************************!*\
  !*** D:/phpStudy/WWW/fx70999_zy/uni-app/pages/shop/dui_list.vue ***!
  \******************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _dui_list_vue_vue_type_template_id_60bad158___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./dui_list.vue?vue&type=template&id=60bad158& */ "D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\pages\\shop\\dui_list.vue?vue&type=template&id=60bad158&");
/* harmony import */ var _dui_list_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./dui_list.vue?vue&type=script&lang=js& */ "D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\pages\\shop\\dui_list.vue?vue&type=script&lang=js&");
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _dui_list_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _dui_list_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__[key]; }) }(__WEBPACK_IMPORT_KEY__));
/* harmony import */ var _dui_list_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./dui_list.vue?vue&type=style&index=0&lang=scss& */ "D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\pages\\shop\\dui_list.vue?vue&type=style&index=0&lang=scss&");
/* harmony import */ var _E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _dui_list_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _dui_list_vue_vue_type_template_id_60bad158___WEBPACK_IMPORTED_MODULE_0__["render"],
  _dui_list_vue_vue_type_template_id_60bad158___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "D:/phpStudy/WWW/fx70999_zy/uni-app/pages/shop/dui_list.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\pages\\shop\\dui_list.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************!*\
  !*** D:/phpStudy/WWW/fx70999_zy/uni-app/pages/shop/dui_list.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _E_HBuilderX_plugins_uniapp_cli_node_modules_babel_loader_lib_index_js_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_12_1_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_18_0_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_script_js_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_dui_list_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!./node_modules/babel-loader/lib!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--12-1!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--18-0!./node_modules/@dcloudio/webpack-uni-mp-loader/lib/script.js!./node_modules/vue-loader/lib??vue-loader-options!./dui_list.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader/index.js?!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader/index.js?!./node_modules/@dcloudio/webpack-uni-mp-loader/lib/script.js!./node_modules/vue-loader/lib/index.js?!D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\pages\\shop\\dui_list.vue?vue&type=script&lang=js&");
/* harmony import */ var _E_HBuilderX_plugins_uniapp_cli_node_modules_babel_loader_lib_index_js_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_12_1_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_18_0_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_script_js_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_dui_list_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_E_HBuilderX_plugins_uniapp_cli_node_modules_babel_loader_lib_index_js_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_12_1_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_18_0_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_script_js_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_dui_list_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _E_HBuilderX_plugins_uniapp_cli_node_modules_babel_loader_lib_index_js_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_12_1_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_18_0_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_script_js_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_dui_list_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _E_HBuilderX_plugins_uniapp_cli_node_modules_babel_loader_lib_index_js_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_12_1_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_18_0_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_script_js_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_dui_list_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_E_HBuilderX_plugins_uniapp_cli_node_modules_babel_loader_lib_index_js_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_12_1_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_18_0_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_script_js_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_dui_list_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ "D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\pages\\shop\\dui_list.vue?vue&type=style&index=0&lang=scss&":
/*!****************************************************************************************************!*\
  !*** D:/phpStudy/WWW/fx70999_zy/uni-app/pages/shop/dui_list.vue?vue&type=style&index=0&lang=scss& ***!
  \****************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _E_HBuilderX_plugins_uniapp_cli_node_modules_mini_css_extract_plugin_dist_loader_js_ref_8_oneOf_1_0_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_8_oneOf_1_1_E_HBuilderX_plugins_uniapp_cli_node_modules_css_loader_index_js_ref_8_oneOf_1_2_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_loaders_stylePostLoader_js_E_HBuilderX_plugins_uniapp_cli_node_modules_postcss_loader_src_index_js_ref_8_oneOf_1_3_E_HBuilderX_plugins_uniapp_cli_node_modules_sass_loader_lib_loader_js_ref_8_oneOf_1_4_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_8_oneOf_1_5_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_dui_list_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!./node_modules/mini-css-extract-plugin/dist/loader.js??ref--8-oneOf-1-0!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--8-oneOf-1-1!./node_modules/css-loader??ref--8-oneOf-1-2!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--8-oneOf-1-3!./node_modules/sass-loader/lib/loader.js??ref--8-oneOf-1-4!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--8-oneOf-1-5!./node_modules/vue-loader/lib??vue-loader-options!./dui_list.vue?vue&type=style&index=0&lang=scss& */ "./node_modules/mini-css-extract-plugin/dist/loader.js?!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader/index.js?!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/lib/loader.js?!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader/index.js?!./node_modules/vue-loader/lib/index.js?!D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\pages\\shop\\dui_list.vue?vue&type=style&index=0&lang=scss&");
/* harmony import */ var _E_HBuilderX_plugins_uniapp_cli_node_modules_mini_css_extract_plugin_dist_loader_js_ref_8_oneOf_1_0_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_8_oneOf_1_1_E_HBuilderX_plugins_uniapp_cli_node_modules_css_loader_index_js_ref_8_oneOf_1_2_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_loaders_stylePostLoader_js_E_HBuilderX_plugins_uniapp_cli_node_modules_postcss_loader_src_index_js_ref_8_oneOf_1_3_E_HBuilderX_plugins_uniapp_cli_node_modules_sass_loader_lib_loader_js_ref_8_oneOf_1_4_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_8_oneOf_1_5_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_dui_list_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_E_HBuilderX_plugins_uniapp_cli_node_modules_mini_css_extract_plugin_dist_loader_js_ref_8_oneOf_1_0_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_8_oneOf_1_1_E_HBuilderX_plugins_uniapp_cli_node_modules_css_loader_index_js_ref_8_oneOf_1_2_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_loaders_stylePostLoader_js_E_HBuilderX_plugins_uniapp_cli_node_modules_postcss_loader_src_index_js_ref_8_oneOf_1_3_E_HBuilderX_plugins_uniapp_cli_node_modules_sass_loader_lib_loader_js_ref_8_oneOf_1_4_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_8_oneOf_1_5_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_dui_list_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _E_HBuilderX_plugins_uniapp_cli_node_modules_mini_css_extract_plugin_dist_loader_js_ref_8_oneOf_1_0_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_8_oneOf_1_1_E_HBuilderX_plugins_uniapp_cli_node_modules_css_loader_index_js_ref_8_oneOf_1_2_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_loaders_stylePostLoader_js_E_HBuilderX_plugins_uniapp_cli_node_modules_postcss_loader_src_index_js_ref_8_oneOf_1_3_E_HBuilderX_plugins_uniapp_cli_node_modules_sass_loader_lib_loader_js_ref_8_oneOf_1_4_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_8_oneOf_1_5_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_dui_list_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _E_HBuilderX_plugins_uniapp_cli_node_modules_mini_css_extract_plugin_dist_loader_js_ref_8_oneOf_1_0_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_8_oneOf_1_1_E_HBuilderX_plugins_uniapp_cli_node_modules_css_loader_index_js_ref_8_oneOf_1_2_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_loaders_stylePostLoader_js_E_HBuilderX_plugins_uniapp_cli_node_modules_postcss_loader_src_index_js_ref_8_oneOf_1_3_E_HBuilderX_plugins_uniapp_cli_node_modules_sass_loader_lib_loader_js_ref_8_oneOf_1_4_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_8_oneOf_1_5_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_dui_list_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_E_HBuilderX_plugins_uniapp_cli_node_modules_mini_css_extract_plugin_dist_loader_js_ref_8_oneOf_1_0_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_8_oneOf_1_1_E_HBuilderX_plugins_uniapp_cli_node_modules_css_loader_index_js_ref_8_oneOf_1_2_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_loaders_stylePostLoader_js_E_HBuilderX_plugins_uniapp_cli_node_modules_postcss_loader_src_index_js_ref_8_oneOf_1_3_E_HBuilderX_plugins_uniapp_cli_node_modules_sass_loader_lib_loader_js_ref_8_oneOf_1_4_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_8_oneOf_1_5_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_dui_list_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ "D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\pages\\shop\\dui_list.vue?vue&type=template&id=60bad158&":
/*!*************************************************************************************************!*\
  !*** D:/phpStudy/WWW/fx70999_zy/uni-app/pages/shop/dui_list.vue?vue&type=template&id=60bad158& ***!
  \*************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_17_0_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_template_js_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_dui_list_vue_vue_type_template_id_60bad158___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader??ref--17-0!./node_modules/@dcloudio/webpack-uni-mp-loader/lib/template.js!./node_modules/vue-loader/lib??vue-loader-options!./dui_list.vue?vue&type=template&id=60bad158& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/@dcloudio/vue-cli-plugin-uni/packages/webpack-preprocess-loader/index.js?!./node_modules/@dcloudio/webpack-uni-mp-loader/lib/template.js!./node_modules/vue-loader/lib/index.js?!D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\pages\\shop\\dui_list.vue?vue&type=template&id=60bad158&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_17_0_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_template_js_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_dui_list_vue_vue_type_template_id_60bad158___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_vue_cli_plugin_uni_packages_webpack_preprocess_loader_index_js_ref_17_0_E_HBuilderX_plugins_uniapp_cli_node_modules_dcloudio_webpack_uni_mp_loader_lib_template_js_E_HBuilderX_plugins_uniapp_cli_node_modules_vue_loader_lib_index_js_vue_loader_options_dui_list_vue_vue_type_template_id_60bad158___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

},[["D:\\phpStudy\\WWW\\fx70999_zy\\uni-app\\main.js?{\"page\":\"pages%2Fshop%2Fdui_list\"}","common/runtime","common/vendor"]]]);
//# sourceMappingURL=../../../.sourcemap/mp-weixin/pages/shop/dui_list.js.map