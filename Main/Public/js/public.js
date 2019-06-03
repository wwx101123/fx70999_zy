var mui = function(t, e) {
    var i = /complete|loaded|interactive/
      , s = /^#([\w-]+)$/
      , n = /^\.([\w-]+)$/
      , o = /^[\w-]+$/
      , a = /translate(?:3d)?\((.+?)\)/
      , r = /matrix(3d)?\((.+?)\)/
      , l = function(e, i) {
        if (i = i || t,
        !e)
            return c();
        if ("object" == typeof e)
            return l.isArrayLike(e) ? c(l.slice.call(e), null) : c([e], null);
        if ("function" == typeof e)
            return l.ready(e);
        if ("string" == typeof e)
            try {
                if (e = e.trim(),
                s.test(e)) {
                    var n = t.getElementById(RegExp.$1);
                    return c(n ? [n] : [])
                }
                return c(l.qsa(e, i), e)
            } catch (t) {}
        return c()
    }
      , c = function(t, e) {
        return t = t || [],
        Object.setPrototypeOf(t, l.fn),
        t.selector = e || "",
        t
    };
    l.uuid = 0,
    l.data = {},
    l.extend = function() {
        var t, e, i, s, n, o, a = arguments[0] || {}, r = 1, c = arguments.length, u = !1;
        for ("boolean" == typeof a && (u = a,
        a = arguments[r] || {},
        r++),
        "object" == typeof a || l.isFunction(a) || (a = {}),
        r === c && (a = this,
        r--); r < c; r++)
            if (null != (t = arguments[r]))
                for (e in t)
                    i = a[e],
                    s = t[e],
                    a !== s && (u && s && (l.isPlainObject(s) || (n = l.isArray(s))) ? (n ? (n = !1,
                    o = i && l.isArray(i) ? i : []) : o = i && l.isPlainObject(i) ? i : {},
                    a[e] = l.extend(u, o, s)) : void 0 !== s && (a[e] = s));
        return a
    }
    ,
    l.noop = function() {}
    ,
    l.slice = [].slice,
    l.filter = [].filter,
    l.type = function(t) {
        return null == t ? String(t) : u[{}.toString.call(t)] || "object"
    }
    ,
    l.isArray = Array.isArray || function(t) {
        return t instanceof Array
    }
    ,
    l.isArrayLike = function(t) {
        var e = !!t && "length"in t && t.length
          , i = l.type(t);
        return "function" !== i && !l.isWindow(t) && ("array" === i || 0 === e || "number" == typeof e && e > 0 && e - 1 in t)
    }
    ,
    l.isWindow = function(t) {
        return null != t && t === t.window
    }
    ,
    l.isObject = function(t) {
        return "object" === l.type(t)
    }
    ,
    l.isPlainObject = function(t) {
        return l.isObject(t) && !l.isWindow(t) && Object.getPrototypeOf(t) === Object.prototype
    }
    ,
    l.isEmptyObject = function(t) {
        for (var e in t)
            if (void 0 !== e)
                return !1;
        return !0
    }
    ,
    l.isFunction = function(t) {
        return "function" === l.type(t)
    }
    ,
    l.qsa = function(e, i) {
        return i = i || t,
        l.slice.call(n.test(e) ? i.getElementsByClassName(RegExp.$1) : o.test(e) ? i.getElementsByTagName(e) : i.querySelectorAll(e))
    }
    ,
    l.ready = function(e) {
        return i.test(t.readyState) ? e(l) : t.addEventListener("DOMContentLoaded", function() {
            e(l)
        }, !1),
        this
    }
    ,
    l.buffer = function(t, e, i) {
        function s() {
            n && (n.cancel(),
            n = 0),
            o = l.now(),
            t.apply(i || this, arguments),
            a = l.now()
        }
        var n, o = 0, a = 0, e = e || 150;
        return l.extend(function() {
            !o || a >= o && l.now() - a > e || a < o && l.now() - o > 8 * e ? s() : (n && n.cancel(),
            n = l.later(s, e, null, arguments))
        }, {
            stop: function() {
                n && (n.cancel(),
                n = 0)
            }
        })
    }
    ,
    l.each = function(t, e, i) {
        if (!t)
            return this;
        if ("number" == typeof t.length)
            [].every.call(t, function(t, i) {
                return !1 !== e.call(t, i, t)
            });
        else
            for (var s in t)
                if (i) {
                    if (t.hasOwnProperty(s) && !1 === e.call(t[s], s, t[s]))
                        return t
                } else if (!1 === e.call(t[s], s, t[s]))
                    return t;
        return this
    }
    ,
    l.focus = function(t) {
        l.os.ios ? setTimeout(function() {
            t.focus()
        }, 10) : t.focus()
    }
    ,
    l.trigger = function(t, e, i) {
        return t.dispatchEvent(new CustomEvent(e,{
            detail: i,
            bubbles: !0,
            cancelable: !0
        })),
        this
    }
    ,
    l.getStyles = function(t, e) {
        var i = t.ownerDocument.defaultView.getComputedStyle(t, null);
        return e ? i.getPropertyValue(e) || i[e] : i
    }
    ,
    l.parseTranslate = function(t, e) {
        var i = t.match(a);
        return i && i[1] || (i = ["", "0,0,0"]),
        i = i[1].split(","),
        i = {
            x: parseFloat(i[0]),
            y: parseFloat(i[1]),
            z: parseFloat(i[2])
        },
        e && i.hasOwnProperty(e) ? i[e] : i
    }
    ,
    l.parseTranslateMatrix = function(t, e) {
        var i = t.match(r)
          , s = i && i[1];
        i ? (i = i[2].split(","),
        "3d" === s ? i = i.slice(12, 15) : (i.push(0),
        i = i.slice(4, 7))) : i = [0, 0, 0];
        var n = {
            x: parseFloat(i[0]),
            y: parseFloat(i[1]),
            z: parseFloat(i[2])
        };
        return e && n.hasOwnProperty(e) ? n[e] : n
    }
    ,
    l.hooks = {},
    l.addAction = function(t, e) {
        var i = l.hooks[t];
        return i || (i = []),
        e.index = e.index || 1e3,
        i.push(e),
        i.sort(function(t, e) {
            return t.index - e.index
        }),
        l.hooks[t] = i,
        l.hooks[t]
    }
    ,
    l.doAction = function(t, e) {
        l.isFunction(e) ? l.each(l.hooks[t], e) : l.each(l.hooks[t], function(t, e) {
            return !e.handle()
        })
    }
    ,
    l.later = function(t, e, i, s) {
        e = e || 0;
        var n, o, a = t, r = s;
        return "string" == typeof t && (a = i[t]),
        n = function() {
            a.apply(i, l.isArray(r) ? r : [r])
        }
        ,
        o = setTimeout(n, e),
        {
            id: o,
            cancel: function() {
                clearTimeout(o)
            }
        }
    }
    ,
    l.now = Date.now || function() {
        return +new Date
    }
    ;
    var u = {};
    return l.each(["Boolean", "Number", "String", "Function", "Array", "Date", "RegExp", "Object", "Error"], function(t, e) {
        u["[object " + e + "]"] = e.toLowerCase()
    }),
    window.JSON && (l.parseJSON = JSON.parse),
    l.fn = {
        each: function(t) {
            return [].every.call(this, function(e, i) {
                return !1 !== t.call(e, i, e)
            }),
            this
        }
    },
    "function" == typeof define && define.amd && define("mui", [], function() {
        return l
    }),
    l
}(document);
!function(t, e) {
    function i(i) {
        this.os = {};
        var s = [function() {
            var t = i.match(/(MicroMessenger)\/([\d\.]+)/i);
            return t && (this.os.wechat = {
                version: t[2].replace(/_/g, ".")
            }),
            !1
        }
        , function() {
            var t = i.match(/(Android);?[\s\/]+([\d.]+)?/);
            return t && (this.os.android = !0,
            this.os.version = t[2],
            this.os.isBadAndroid = !/Chrome\/\d/.test(e.navigator.appVersion)),
            !0 === this.os.android
        }
        , function() {
            var t = i.match(/(iPhone\sOS)\s([\d_]+)/);
            if (t)
                this.os.ios = this.os.iphone = !0,
                this.os.version = t[2].replace(/_/g, ".");
            else {
                var e = i.match(/(iPad).*OS\s([\d_]+)/);
                e && (this.os.ios = this.os.ipad = !0,
                this.os.version = e[2].replace(/_/g, "."))
            }
            return !0 === this.os.ios
        }
        ];
        [].every.call(s, function(e) {
            return !e.call(t)
        })
    }
    i.call(t, navigator.userAgent)
}(mui, window),
function(t, e) {
    function i(i) {
        this.os = this.os || {},
        i.match(/Html5Plus/i) && (this.os.plus = !0,
        t(function() {
            e.body.classList.add("mui-plus")
        }),
        i.match(/StreamApp/i) && (this.os.stream = !0,
        t(function() {
            e.body.classList.add("mui-plus-stream")
        })))
    }
    i.call(t, navigator.userAgent)
}(mui, document),
function(t) {
    "ontouchstart"in window ? (t.isTouchable = !0,
    t.EVENT_START = "touchstart",
    t.EVENT_MOVE = "touchmove",
    t.EVENT_END = "touchend") : (t.isTouchable = !1,
    t.EVENT_START = "mousedown",
    t.EVENT_MOVE = "mousemove",
    t.EVENT_END = "mouseup"),
    t.EVENT_CANCEL = "touchcancel",
    t.EVENT_CLICK = "click";
    var e = 1
      , i = {}
      , s = {
        preventDefault: "isDefaultPrevented",
        stopImmediatePropagation: "isImmediatePropagationStopped",
        stopPropagation: "isPropagationStopped"
    }
      , n = function() {
        return !0
    }
      , o = function() {
        return !1
    }
      , a = function(e, i) {
        return e.detail ? e.detail.currentTarget = i : e.detail = {
            currentTarget: i
        },
        t.each(s, function(t, i) {
            var s = e[t];
            e[t] = function() {
                return this[i] = n,
                s && s.apply(e, arguments)
            }
            ,
            e[i] = o
        }, !0),
        e
    }
      , r = function(t) {
        return t && (t._mid || (t._mid = e++))
    }
      , l = {}
      , c = function(e, s, n, o) {
        return function(n) {
            for (var o = i[e._mid][s], r = [], l = n.target, c = {}; l && l !== document && l !== e && (!~["click", "tap", "doubletap", "longtap", "hold"].indexOf(s) || !l.disabled && !l.classList.contains("mui-disabled")); l = l.parentNode) {
                var u = {};
                t.each(o, function(i, s) {
                    c[i] || (c[i] = t.qsa(i, e)),
                    c[i] && ~c[i].indexOf(l) && (u[i] || (u[i] = s))
                }, !0),
                t.isEmptyObject(u) || r.push({
                    element: l,
                    handlers: u
                })
            }
            c = null,
            n = a(n),
            t.each(r, function(e, i) {
                l = i.element;
                var o = l.tagName;
                if ("tap" === s && "INPUT" !== o && "TEXTAREA" !== o && "SELECT" !== o && (n.preventDefault(),
                n.detail && n.detail.gesture && n.detail.gesture.preventDefault()),
                t.each(i.handlers, function(e, i) {
                    t.each(i, function(t, e) {
                        !1 === e.call(l, n) && (n.preventDefault(),
                        n.stopPropagation())
                    }, !0)
                }, !0),
                n.isPropagationStopped())
                    return !1
            }, !0)
        }
    }
      , u = function(t, e) {
        var i = l[r(t)]
          , s = [];
        if (i) {
            if (s = [],
            e) {
                var n = function(t) {
                    return t.type === e
                };
                return i.filter(n)
            }
            s = i
        }
        return s
    }
      , d = /^(INPUT|TEXTAREA|BUTTON|SELECT)$/;
    t.fn.on = function(e, s, n) {
        return this.each(function() {
            var o = this;
            r(o),
            r(n);
            var a = !1
              , u = i[o._mid] || (i[o._mid] = {})
              , h = u[e] || (u[e] = {});
            if (t.isEmptyObject(h) && (a = !0),
            (h[s] || (h[s] = [])).push(n),
            a) {
                var p = l[r(o)];
                p || (p = []);
                var f = c(o, e);
                p.push(f),
                f.i = p.length - 1,
                f.type = e,
                l[r(o)] = p,
                o.addEventListener(e, f),
                "tap" === e && o.addEventListener("click", function(t) {
                    if (t.target) {
                        var e = t.target.tagName;
                        if (!d.test(e))
                            if ("A" === e) {
                                var i = t.target.href;
                                i && ~i.indexOf("tel:") || t.preventDefault()
                            } else
                                t.preventDefault()
                    }
                })
            }
        })
    }
    ,
    t.fn.off = function(e, s, n) {
        return this.each(function() {
            var o = r(this);
            if (e)
                if (s)
                    if (n) {
                        var a = i[o] && i[o][e] && i[o][e][s];
                        t.each(a, function(t, e) {
                            if (r(e) === r(n))
                                return a.splice(t, 1),
                                !1
                        }, !0)
                    } else
                        i[o] && i[o][e] && delete i[o][e][s];
                else
                    i[o] && delete i[o][e];
            else
                i[o] && delete i[o];
            i[o] ? i[o][e] && !t.isEmptyObject(i[o][e]) || u(this, e).forEach(function(t) {
                this.removeEventListener(t.type, t),
                delete l[o][t.i]
            }
            .bind(this)) : u(this).forEach(function(t) {
                this.removeEventListener(t.type, t),
                delete l[o][t.i]
            }
            .bind(this))
        })
    }
}(mui),
function(t, e, i) {
    t.targets = {},
    t.targetHandles = [],
    t.registerTarget = function(e) {
        return e.index = e.index || 1e3,
        t.targetHandles.push(e),
        t.targetHandles.sort(function(t, e) {
            return t.index - e.index
        }),
        t.targetHandles
    }
    ,
    e.addEventListener(t.EVENT_START, function(e) {
        for (var s = e.target, n = {}; s && s !== i; s = s.parentNode) {
            var o = !1;
            if (t.each(t.targetHandles, function(i, a) {
                var r = a.name;
                o || n[r] || !a.hasOwnProperty("handle") ? n[r] || !1 !== a.isReset && (t.targets[r] = !1) : (t.targets[r] = a.handle(e, s),
                t.targets[r] && (n[r] = !0,
                !0 !== a.isContinue && (o = !0)))
            }),
            o)
                break
        }
    }),
    e.addEventListener("click", function(e) {
        for (var s = e.target, n = !1; s && s !== i && ("A" !== s.tagName || (t.each(t.targetHandles, function(t, i) {
            i.name;
            if (i.hasOwnProperty("handle") && i.handle(e, s))
                return n = !0,
                e.preventDefault(),
                !1
        }),
        !n)); s = s.parentNode)
            ;
    })
}(mui, window, document),
function(t) {
    void 0 === String.prototype.trim && (String.prototype.trim = function() {
        return this.replace(/^\s+|\s+$/g, "")
    }
    ),
    Object.setPrototypeOf = Object.setPrototypeOf || function(t, e) {
        return t.__proto__ = e,
        t
    }
}(),
function() {
    function t(t, e) {
        e = e || {
            bubbles: !1,
            cancelable: !1,
            detail: void 0
        };
        var i = document.createEvent("Events")
          , s = !0;
        for (var n in e)
            "bubbles" === n ? s = !!e[n] : i[n] = e[n];
        return i.initEvent(t, s, !0),
        i
    }
    void 0 === window.CustomEvent && (t.prototype = window.Event.prototype,
    window.CustomEvent = t)
}(),
Function.prototype.bind = Function.prototype.bind || function(t) {
    var e = Array.prototype.splice.call(arguments, 1)
      , i = this
      , s = function() {
        var n = e.concat(Array.prototype.splice.call(arguments, 0));
        if (!(this instanceof s))
            return i.apply(t, n);
        i.apply(this, n)
    };
    return s.prototype = i.prototype,
    s
}
,
function(t) {
    "classList"in t.documentElement || !Object.defineProperty || "undefined" == typeof HTMLElement || Object.defineProperty(HTMLElement.prototype, "classList", {
        get: function() {
            function t(t) {
                return function(i) {
                    var s = e.className.split(/\s+/)
                      , n = s.indexOf(i);
                    t(s, n, i),
                    e.className = s.join(" ")
                }
            }
            var e = this
              , i = {
                add: t(function(t, e, i) {
                    ~e || t.push(i)
                }),
                remove: t(function(t, e) {
                    ~e && t.splice(e, 1)
                }),
                toggle: t(function(t, e, i) {
                    ~e ? t.splice(e, 1) : t.push(i)
                }),
                contains: function(t) {
                    return !!~e.className.split(/\s+/).indexOf(t)
                },
                item: function(t) {
                    return e.className.split(/\s+/)[t] || null
                }
            };
            return Object.defineProperty(i, "length", {
                get: function() {
                    return e.className.split(/\s+/).length
                }
            }),
            i
        }
    })
}(document),
function(t) {
    if (!t.requestAnimationFrame) {
        var e = 0;
        t.requestAnimationFrame = t.webkitRequestAnimationFrame || function(i, s) {
            var n = (new Date).getTime()
              , o = Math.max(0, 16.7 - (n - e))
              , a = t.setTimeout(function() {
                i(n + o)
            }, o);
            return e = n + o,
            a
        }
        ,
        t.cancelAnimationFrame = t.webkitCancelAnimationFrame || t.webkitCancelRequestAnimationFrame || function(t) {
            clearTimeout(t)
        }
    }
}(window),
function(t, e, i) {
    if ((t.os.android || t.os.ios) && !e.FastClick) {
        var s = function(t, e) {
            return "LABEL" === e.tagName && e.parentNode && (e = e.parentNode.querySelector("input")),
            !(!e || "radio" !== e.type && "checkbox" !== e.type || e.disabled) && e
        };
        t.registerTarget({
            name: "click",
            index: 40,
            handle: s,
            target: !1
        });
        var n = function(i) {
            var s = t.targets.click;
            if (s) {
                var n, o;
                document.activeElement && document.activeElement !== s && document.activeElement.blur(),
                o = i.detail.gesture.changedTouches[0],
                n = document.createEvent("MouseEvents"),
                n.initMouseEvent("click", !0, !0, e, 1, o.screenX, o.screenY, o.clientX, o.clientY, !1, !1, !1, !1, 0, null),
                n.forwardedTouchEvent = !0,
                s.dispatchEvent(n),
                i.detail && i.detail.gesture.preventDefault()
            }
        };
        e.addEventListener("tap", n),
        e.addEventListener("doubletap", n),
        e.addEventListener("click", function(e) {
            if (t.targets.click && !e.forwardedTouchEvent)
                return e.stopImmediatePropagation ? e.stopImmediatePropagation() : e.propagationStopped = !0,
                e.stopPropagation(),
                e.preventDefault(),
                !1
        }, !0)
    }
}(mui, window),
function(t, e) {
    t(function() {
        if (t.os.ios) {
            e.addEventListener("focusin", function(i) {
                if (!(t.os.plus && window.plus && plus.webview.currentWebview().children().length > 0)) {
                    var s = i.target;
                    if (s.tagName && ("TEXTAREA" === s.tagName || "INPUT" === s.tagName && ("text" === s.type || "search" === s.type || "number" === s.type))) {
                        if (s.disabled || s.readOnly)
                            return;
                        e.body.classList.add("mui-focusin");
                        for (var n = !1; s && s !== e; s = s.parentNode) {
                            var o = s.classList;
                            if (o && o.contains("mui-bar-tab") || o.contains("mui-bar-footer") || o.contains("mui-bar-footer-secondary") || o.contains("mui-bar-footer-secondary-tab")) {
                                n = !0;
                                break
                            }
                        }
                        if (n) {
                            var a = e.body.scrollHeight
                              , r = e.body.scrollLeft;
                            setTimeout(function() {
                                window.scrollTo(r, a)
                            }, 20)
                        }
                    }
                }
            }),
            e.addEventListener("focusout", function(t) {
                var i = e.body.classList;
                i.contains("mui-focusin") && (i.remove("mui-focusin"),
                setTimeout(function() {
                    window.scrollTo(e.body.scrollLeft, e.body.scrollTop)
                }, 20))
            })
        }
    })
}(mui, document),
function(t) {
    t.namespace = "mui",
    t.classNamePrefix = t.namespace + "-",
    t.classSelectorPrefix = "." + t.classNamePrefix,
    t.className = function(e) {
        return t.classNamePrefix + e
    }
    ,
    t.classSelector = function(e) {
        return e.replace(/\./g, t.classSelectorPrefix)
    }
    ,
    t.eventName = function(e, i) {
        return e + (t.namespace ? "." + t.namespace : "") + (i ? "." + i : "")
    }
}(mui),
function(t, e) {
    t.gestures = {
        session: {}
    },
    t.preventDefault = function(t) {
        t.preventDefault()
    }
    ,
    t.stopPropagation = function(t) {
        t.stopPropagation()
    }
    ,
    t.addGesture = function(e) {
        return t.addAction("gestures", e)
    }
    ;
    var i = Math.round
      , s = Math.abs
      , n = Math.sqrt
      , o = (Math.atan,
    Math.atan2)
      , a = function(t, e, i) {
        i || (i = ["x", "y"]);
        var s = e[i[0]] - t[i[0]]
          , o = e[i[1]] - t[i[1]];
        return n(s * s + o * o)
    }
      , r = function(t, e) {
        if (t.length >= 2 && e.length >= 2) {
            var i = ["pageX", "pageY"];
            return a(e[1], e[0], i) / a(t[1], t[0], i)
        }
        return 1
    }
      , l = function(t, e, i) {
        i || (i = ["x", "y"]);
        var s = e[i[0]] - t[i[0]]
          , n = e[i[1]] - t[i[1]];
        return 180 * o(n, s) / Math.PI
    }
      , c = function(t, e) {
        return t === e ? "" : s(t) >= s(e) ? t > 0 ? "left" : "right" : e > 0 ? "up" : "down"
    }
      , u = function(t, e) {
        var i = ["pageX", "pageY"];
        return l(e[1], e[0], i) - l(t[1], t[0], i)
    }
      , d = function(t, e, i) {
        return {
            x: e / t || 0,
            y: i / t || 0
        }
    }
      , h = function(e, i) {
        t.gestures.stoped || t.doAction("gestures", function(s, n) {
            t.gestures.stoped || !1 !== t.options.gestureConfig[n.name] && n.handle(e, i)
        })
    }
      , p = function(t, e) {
        for (; t; ) {
            if (t == e)
                return !0;
            t = t.parentNode
        }
        return !1
    }
      , f = function(t, e, i) {
        for (var s = [], n = [], o = 0; o < t.length; ) {
            var a = e ? t[o][e] : t[o];
            n.indexOf(a) < 0 && s.push(t[o]),
            n[o] = a,
            o++
        }
        return i && (s = e ? s.sort(function(t, i) {
            return t[e] > i[e]
        }) : s.sort()),
        s
    }
      , m = function(t) {
        var e = t.length;
        if (1 === e)
            return {
                x: i(t[0].pageX),
                y: i(t[0].pageY)
            };
        for (var s = 0, n = 0, o = 0; o < e; )
            s += t[o].pageX,
            n += t[o].pageY,
            o++;
        return {
            x: i(s / e),
            y: i(n / e)
        }
    }
      , g = function() {
        return t.options.gestureConfig.pinch
    }
      , v = function(e) {
        for (var s = [], n = 0; n < e.touches.length; )
            s[n] = {
                pageX: i(e.touches[n].pageX),
                pageY: i(e.touches[n].pageY)
            },
            n++;
        return {
            timestamp: t.now(),
            gesture: e.gesture,
            touches: s,
            center: m(e.touches),
            deltaX: e.deltaX,
            deltaY: e.deltaY
        }
    }
      , b = function(e) {
        var i = t.gestures.session
          , s = e.center
          , n = i.offsetDelta || {}
          , o = i.prevDelta || {}
          , a = i.prevTouch || {};
        e.gesture.type !== t.EVENT_START && e.gesture.type !== t.EVENT_END || (o = i.prevDelta = {
            x: a.deltaX || 0,
            y: a.deltaY || 0
        },
        n = i.offsetDelta = {
            x: s.x,
            y: s.y
        }),
        e.deltaX = o.x + (s.x - n.x),
        e.deltaY = o.y + (s.y - n.y)
    }
      , w = function(e) {
        var i = t.gestures.session
          , s = e.touches
          , n = s.length;
        i.firstTouch || (i.firstTouch = v(e)),
        g() && n > 1 && !i.firstMultiTouch ? i.firstMultiTouch = v(e) : 1 === n && (i.firstMultiTouch = !1);
        var o = i.firstTouch
          , d = i.firstMultiTouch
          , h = d ? d.center : o.center
          , p = e.center = m(s);
        e.timestamp = t.now(),
        e.deltaTime = e.timestamp - o.timestamp,
        e.angle = l(h, p),
        e.distance = a(h, p),
        b(e),
        e.offsetDirection = c(e.deltaX, e.deltaY),
        e.scale = d ? r(d.touches, s) : 1,
        e.rotation = d ? u(d.touches, s) : 0,
        y(e)
    }
      , y = function(e) {
        var i, n, o, a, r = t.gestures.session, l = r.lastInterval || e, u = e.timestamp - l.timestamp;
        if (e.gesture.type != t.EVENT_CANCEL && (u > 25 || void 0 === l.velocity)) {
            var h = l.deltaX - e.deltaX
              , p = l.deltaY - e.deltaY
              , f = d(u, h, p);
            n = f.x,
            o = f.y,
            i = s(f.x) > s(f.y) ? f.x : f.y,
            a = c(h, p) || l.direction,
            r.lastInterval = e
        } else
            i = l.velocity,
            n = l.velocityX,
            o = l.velocityY,
            a = l.direction;
        e.velocity = i,
        e.velocityX = n,
        e.velocityY = o,
        e.direction = a
    }
      , L = {}
      , _ = function(t) {
        for (var e = 0; e < t.length; e++)
            !t.identifier && (t.identifier = 0);
        return t
    }
      , T = function(e, i) {
        var s = _(t.slice.call(e.touches || [e]))
          , n = e.type
          , o = []
          , a = [];
        if (n !== t.EVENT_START && n !== t.EVENT_MOVE || 1 !== s.length) {
            var r = 0
              , o = []
              , a = []
              , l = _(t.slice.call(e.changedTouches || [e]));
            i.target = e.target;
            var c = t.gestures.session.target || e.target;
            if (o = s.filter(function(t) {
                return p(t.target, c)
            }),
            n === t.EVENT_START)
                for (r = 0; r < o.length; )
                    L[o[r].identifier] = !0,
                    r++;
            for (r = 0; r < l.length; )
                L[l[r].identifier] && a.push(l[r]),
                n !== t.EVENT_END && n !== t.EVENT_CANCEL || delete L[l[r].identifier],
                r++;
            if (!a.length)
                return !1
        } else
            L[s[0].identifier] = !0,
            o = s,
            a = s,
            i.target = e.target;
        o = f(o.concat(a), "identifier", !0);
        var u = o.length
          , d = a.length;
        return n === t.EVENT_START && u - d == 0 && (i.isFirst = !0,
        t.gestures.touch = t.gestures.session = {
            target: e.target
        }),
        i.isFinal = (n === t.EVENT_END || n === t.EVENT_CANCEL) && u - d == 0,
        i.touches = o,
        i.changedTouches = a,
        !0
    }
      , E = function(e) {
        var i = {
            gesture: e
        };
        T(e, i) && (w(i),
        h(e, i),
        t.gestures.session.prevTouch = i,
        e.type !== t.EVENT_END || t.isTouchable || (t.gestures.touch = t.gestures.session = {}))
    };
    e.addEventListener(t.EVENT_START, E),
    e.addEventListener(t.EVENT_MOVE, E),
    e.addEventListener(t.EVENT_END, E),
    e.addEventListener(t.EVENT_CANCEL, E),
    e.addEventListener(t.EVENT_CLICK, function(e) {
        (t.os.android || t.os.ios) && (t.targets.popover && e.target === t.targets.popover || t.targets.tab || t.targets.offcanvas || t.targets.modal) && e.preventDefault()
    }, !0),
    t.isScrolling = !1;
    var S = null;
    e.addEventListener("scroll", function() {
        t.isScrolling = !0,
        S && clearTimeout(S),
        S = setTimeout(function() {
            t.isScrolling = !1
        }, 250)
    })
}(mui, window),
function(t, e) {
    var i = 0
      , s = function(e, s) {
        var n = t.gestures.session
          , o = this.options
          , a = t.now();
        switch (e.type) {
        case t.EVENT_MOVE:
            a - i > 300 && (i = a,
            n.flickStart = s.center);
            break;
        case t.EVENT_END:
        case t.EVENT_CANCEL:
            s.flick = !1,
            n.flickStart && o.flickMaxTime > a - i && s.distance > o.flickMinDistince && (s.flick = !0,
            s.flickTime = a - i,
            s.flickDistanceX = s.center.x - n.flickStart.x,
            s.flickDistanceY = s.center.y - n.flickStart.y,
            t.trigger(n.target, "flick", s),
            t.trigger(n.target, "flick" + s.direction, s))
        }
    };
    t.addGesture({
        name: "flick",
        index: 5,
        handle: s,
        options: {
            flickMaxTime: 200,
            flickMinDistince: 10
        }
    })
}(mui),
function(t, e) {
    var i = function(e, i) {
        var s = t.gestures.session;
        if (e.type === t.EVENT_END || e.type === t.EVENT_CANCEL) {
            var n = this.options;
            i.swipe = !1,
            i.direction && n.swipeMaxTime > i.deltaTime && i.distance > n.swipeMinDistince && (i.swipe = !0,
            t.trigger(s.target, "swipe", i),
            t.trigger(s.target, "swipe" + i.direction, i))
        }
    };
    t.addGesture({
        name: "swipe",
        index: 10,
        handle: i,
        options: {
            swipeMaxTime: 300,
            swipeMinDistince: 18
        }
    })
}(mui),
function(t, e) {
    var i = function(e, i) {
        var s = t.gestures.session;
        switch (e.type) {
        case t.EVENT_START:
            break;
        case t.EVENT_MOVE:
            if (!i.direction || !s.target)
                return;
            s.lockDirection && s.startDirection && s.startDirection && s.startDirection !== i.direction && ("up" === s.startDirection || "down" === s.startDirection ? i.direction = i.deltaY < 0 ? "up" : "down" : i.direction = i.deltaX < 0 ? "left" : "right"),
            s.drag || (s.drag = !0,
            t.trigger(s.target, "dragstart", i)),
            t.trigger(s.target, "drag", i),
            t.trigger(s.target, "drag" + i.direction, i);
            break;
        case t.EVENT_END:
        case t.EVENT_CANCEL:
            s.drag && i.isFinal && t.trigger(s.target, "dragend", i)
        }
    };
    t.addGesture({
        name: "drag",
        index: 20,
        handle: i,
        options: {
            fingers: 1
        }
    })
}(mui),
function(t, e) {
    var i, s, n = function(e, n) {
        var o = t.gestures.session
          , a = this.options;
        switch (e.type) {
        case t.EVENT_END:
            if (!n.isFinal)
                return;
            var r = o.target;
            if (!r || r.disabled || r.classList && r.classList.contains("mui-disabled"))
                return;
            if (n.distance < a.tapMaxDistance && n.deltaTime < a.tapMaxTime) {
                if (t.options.gestureConfig.doubletap && i && i === r && s && n.timestamp - s < a.tapMaxInterval)
                    return t.trigger(r, "doubletap", n),
                    s = t.now(),
                    void (i = r);
                t.trigger(r, "tap", n),
                s = t.now(),
                i = r
            }
        }
    };
    t.addGesture({
        name: "tap",
        index: 30,
        handle: n,
        options: {
            fingers: 1,
            tapMaxInterval: 300,
            tapMaxDistance: 5,
            tapMaxTime: 250
        }
    })
}(mui),
function(t, e) {
    var i, s = function(e, s) {
        var n = t.gestures.session
          , o = this.options;
        switch (e.type) {
        case t.EVENT_START:
            clearTimeout(i),
            i = setTimeout(function() {
                t.trigger(n.target, "longtap", s)
            }, o.holdTimeout);
            break;
        case t.EVENT_MOVE:
            s.distance > o.holdThreshold && clearTimeout(i);
            break;
        case t.EVENT_END:
        case t.EVENT_CANCEL:
            clearTimeout(i)
        }
    };
    t.addGesture({
        name: "longtap",
        index: 10,
        handle: s,
        options: {
            fingers: 1,
            holdTimeout: 500,
            holdThreshold: 2
        }
    })
}(mui),
function(t, e) {
    var i, s = function(e, s) {
        var n = t.gestures.session
          , o = this.options;
        switch (e.type) {
        case t.EVENT_START:
            t.options.gestureConfig.hold && (i && clearTimeout(i),
            i = setTimeout(function() {
                s.hold = !0,
                t.trigger(n.target, "hold", s)
            }, o.holdTimeout));
            break;
        case t.EVENT_MOVE:
            break;
        case t.EVENT_END:
        case t.EVENT_CANCEL:
            i && (clearTimeout(i) && (i = null),
            t.trigger(n.target, "release", s))
        }
    };
    t.addGesture({
        name: "hold",
        index: 10,
        handle: s,
        options: {
            fingers: 1,
            holdTimeout: 0
        }
    })
}(mui),
function(t, e) {
    var i = function(i, s) {
        var n = this.options
          , o = t.gestures.session;
        switch (i.type) {
        case t.EVENT_START:
            break;
        case t.EVENT_MOVE:
            if (t.options.gestureConfig.pinch) {
                if (s.touches.length < 2)
                    return;
                o.pinch || (o.pinch = !0,
                t.trigger(o.target, "pinchstart", s)),
                t.trigger(o.target, e, s);
                var a = s.scale
                  , r = s.rotation
                  , l = void 0 === s.lastScale ? 1 : s.lastScale;
                a > l ? (l = a - 1e-12,
                t.trigger(o.target, "pinchout", s)) : a < l && (l = a + 1e-12,
                t.trigger(o.target, "pinchin", s)),
                Math.abs(r) > n.minRotationAngle && t.trigger(o.target, "rotate", s)
            }
            break;
        case t.EVENT_END:
        case t.EVENT_CANCEL:
            t.options.gestureConfig.pinch && o.pinch && 2 === s.touches.length && (o.pinch = !1,
            t.trigger(o.target, "pinchend", s))
        }
    };
    t.addGesture({
        name: e,
        index: 10,
        handle: i,
        options: {
            minRotationAngle: 0
        }
    })
}(mui, "pinch"),
function(t) {
    function e(t, e) {
        var i = "MUI_SCROLL_POSITION_" + document.location.href + "_" + e.src
          , s = parseFloat(localStorage.getItem(i)) || 0;
        s && function(t) {
            e.onload = function() {
                window.scrollTo(0, t)
            }
        }(s),
        setInterval(function() {
            var t = window.scrollY;
            s !== t && (localStorage.setItem(i, t + ""),
            s = t)
        }, 100)
    }
    t.global = t.options = {
        gestureConfig: {
            tap: !0,
            doubletap: !1,
            longtap: !1,
            hold: !1,
            flick: !0,
            swipe: !0,
            drag: !0,
            pinch: !1
        }
    },
    t.initGlobal = function(e) {
        return t.options = t.extend(!0, t.global, e),
        this
    }
    ;
    var i = {}
      , s = !1;
    t.init = function(e) {
        return s = !0,
        t.options = t.extend(!0, t.global, e || {}),
        t.ready(function() {
            t.doAction("inits", function(e, s) {
                !(i[s.name] && !s.repeat) && (s.handle.call(t),
                i[s.name] = !0)
            })
        }),
        this
    }
    ,
    t.addInit = function(e) {
        return t.addAction("inits", e)
    }
    ,
    t.addInit({
        name: "iframe",
        index: 100,
        handle: function() {
            var e = t.options
              , i = e.subpages || [];
            !t.os.plus && i.length && n(i[0])
        }
    });
    var n = function(i) {
        var s = document.createElement("div");
        s.className = "mui-iframe-wrapper";
        var n = i.styles || {};
        "string" != typeof n.top && (n.top = "0px"),
        "string" != typeof n.bottom && (n.bottom = "0px"),
        s.style.top = n.top,
        s.style.bottom = n.bottom;
        var o = document.createElement("iframe");
        o.src = i.url,
        o.id = i.id || i.url,
        o.name = o.id,
        s.appendChild(o),
        document.body.appendChild(s),
        t.os.wechat && e(s, o)
    };
    t(function() {
        var e = document.body.classList
          , i = [];
        t.os.ios ? (i.push({
            os: "ios",
            version: t.os.version
        }),
        e.add("mui-ios")) : t.os.android && (i.push({
            os: "android",
            version: t.os.version
        }),
        e.add("mui-android")),
        t.os.wechat && (i.push({
            os: "wechat",
            version: t.os.wechat.version
        }),
        e.add("mui-wechat")),
        i.length && t.each(i, function(i, s) {
            var n = "";
            s.version && t.each(s.version.split("."), function(i, o) {
                n = n + (n ? "-" : "") + o,
                e.add(t.className(s.os + "-" + n))
            })
        })
    })
}(mui),
function(t) {
    var e = {
        swipeBack: !1,
        preloadPages: [],
        preloadLimit: 10,
        keyEventBind: {
            backbutton: !0,
            menubutton: !0
        }
    }
      , i = {
        autoShow: !0,
        duration: t.os.ios ? 200 : 100,
        aniShow: "slide-in-right"
    };
    t.options.show && (i = t.extend(!0, i, t.options.show)),
    t.currentWebview = null,
    t.isHomePage = !1,
    t.extend(!0, t.global, e),
    t.extend(!0, t.options, e),
    t.waitingOptions = function(e) {
        return t.extend(!0, {}, {
            autoShow: !0,
            title: "",
            modal: !1
        }, e)
    }
    ,
    t.showOptions = function(e) {
        return t.extend(!0, {}, i, e)
    }
    ,
    t.windowOptions = function(e) {
        return t.extend({
            scalable: !1,
            bounce: ""
        }, e)
    }
    ,
    t.plusReady = function(t) {
        return window.plus ? setTimeout(function() {
            t()
        }, 0) : document.addEventListener("plusready", function() {
            t()
        }, !1),
        this
    }
    ,
    t.fire = function(e, i, s) {
        e && ("" !== s && (s = s || {},
        t.isPlainObject(s) && (s = JSON.stringify(s || {}).replace(/\'/g, "\\u0027").replace(/\\/g, "\\u005c"))),
        e.evalJS("typeof mui!=='undefined'&&mui.receive('" + i + "','" + s + "')"))
    }
    ,
    t.receive = function(e, i) {
        if (e) {
            try {
                i && (i = JSON.parse(i))
            } catch (t) {}
            t.trigger(document, e, i)
        }
    }
    ;
    var s = function(e) {
        if (!e.preloaded) {
            t.fire(e, "preload");
            for (var i = e.children(), s = 0; s < i.length; s++)
                t.fire(i[s], "preload");
            e.preloaded = !0
        }
    }
      , n = function(e, i, s) {
        if (s) {
            if (!e[i + "ed"]) {
                t.fire(e, i);
                for (var n = e.children(), o = 0; o < n.length; o++)
                    t.fire(n[o], i);
                e[i + "ed"] = !0
            }
        } else {
            t.fire(e, i);
            for (var n = e.children(), o = 0; o < n.length; o++)
                t.fire(n[o], i)
        }
    };
    t.openWindow = function(e, i, o) {
        if ("object" == typeof e ? (o = e,
        e = o.url,
        i = o.id || e) : "object" == typeof i ? (o = i,
        i = e) : i = i || e,
        !t.os.plus)
            return void (t.os.ios || t.os.android ? window.top.location.href = e : window.parent.location.href = e);
        if (window.plus) {
            o = o || {};
            var a, r, l = o.params || {}, c = null, u = null;
            if (t.webviews[i] && (u = t.webviews[i],
            plus.webview.getWebviewById(i) && (c = u.webview)),
            u && c)
                return a = u.show,
                a = o.show ? t.extend(a, o.show) : a,
                c.show(a.aniShow, a.duration, function() {
                    s(c),
                    n(c, "pagebeforeshow", !1)
                }),
                u.afterShowMethodName && c.evalJS(u.afterShowMethodName + "('" + JSON.stringify(l) + "')"),
                c;
            if (!0 !== o.createNew) {
                if (c = plus.webview.getWebviewById(i))
                    return a = t.showOptions(o.show),
                    a.autoShow && c.show(a.aniShow, a.duration, function() {
                        s(c),
                        n(c, "pagebeforeshow", !1)
                    }),
                    c;
                if (!e)
                    throw new Error("webview[" + i + "] does not exist")
            }
            var d = t.waitingOptions(o.waiting);
            if (d.autoShow && (r = plus.nativeUI.showWaiting(d.title, d.options)),
            o = t.extend(o, {
                id: i,
                url: e
            }),
            c = t.createWindow(o),
            a = t.showOptions(o.show),
            a.autoShow) {
                var h = function() {
                    r && r.close(),
                    c.show(a.aniShow, a.duration, function() {}),
                    c.showed = !0,
                    o.afterShowMethodName && c.evalJS(o.afterShowMethodName + "('" + JSON.stringify(l) + "')")
                };
                e ? (c.addEventListener("titleUpdate", h, !1),
                c.addEventListener("loaded", function() {
                    s(c),
                    n(c, "pagebeforeshow", !1)
                }, !1)) : h()
            }
            return c
        }
    }
    ,
    t.createWindow = function(e, i) {
        if (window.plus) {
            var s, n = e.id || e.url;
            if (e.preload) {
                t.webviews[n] && t.webviews[n].webview.getURL() ? s = t.webviews[n].webview : (!0 !== e.createNew && (s = plus.webview.getWebviewById(n)),
                s || (s = plus.webview.create(e.url, n, t.windowOptions(e.styles), t.extend({
                    preload: !0
                }, e.extras)),
                e.subpages && t.each(e.subpages, function(e, i) {
                    var n = i.id || i.url;
                    if (n) {
                        var o = plus.webview.getWebviewById(n);
                        o || (o = plus.webview.create(i.url, n, t.windowOptions(i.styles), t.extend({
                            preload: !0
                        }, i.extras))),
                        s.append(o)
                    }
                }))),
                t.webviews[n] = {
                    webview: s,
                    preload: !0,
                    show: t.showOptions(e.show),
                    afterShowMethodName: e.afterShowMethodName
                };
                var o = t.data.preloads
                  , a = o.indexOf(n);
                if (~a && o.splice(a, 1),
                o.push(n),
                o.length > t.options.preloadLimit) {
                    var r = t.data.preloads.shift()
                      , l = t.webviews[r];
                    l && l.webview && t.closeAll(l.webview),
                    delete t.webviews[r]
                }
            } else
                !1 !== i && (s = plus.webview.create(e.url, n, t.windowOptions(e.styles), e.extras),
                e.subpages && t.each(e.subpages, function(e, i) {
                    var n = i.id || i.url
                      , o = plus.webview.getWebviewById(n);
                    o || (o = plus.webview.create(i.url, n, t.windowOptions(i.styles), i.extras)),
                    s.append(o)
                }));
            return s
        }
    }
    ,
    t.preload = function(e) {
        return e.preload || (e.preload = !0),
        t.createWindow(e)
    }
    ,
    t.closeOpened = function(e) {
        var i = e.opened();
        if (i)
            for (var s = 0, n = i.length; s < n; s++) {
                var o = i[s]
                  , a = o.opened();
                a && a.length > 0 ? (t.closeOpened(o),
                o.close("none")) : o.parent() !== e && o.close("none")
            }
    }
    ,
    t.closeAll = function(e, i) {
        t.closeOpened(e),
        i ? e.close(i) : e.close()
    }
    ,
    t.createWindows = function(e) {
        t.each(e, function(e, i) {
            t.createWindow(i, !1)
        })
    }
    ,
    t.appendWebview = function(e) {
        if (window.plus) {
            var i, s = e.id || e.url;
            return t.webviews[s] || (plus.webview.getWebviewById(s) || (i = plus.webview.create(e.url, s, e.styles, e.extras)),
            plus.webview.currentWebview().append(i),
            t.webviews[s] = e),
            i
        }
    }
    ,
    t.webviews = {},
    t.data.preloads = [],
    t.plusReady(function() {
        t.currentWebview = plus.webview.currentWebview()
    }),
    t.addInit({
        name: "5+",
        index: 100,
        handle: function() {
            var e = t.options
              , i = e.subpages || [];
            t.os.plus && t.plusReady(function() {
                t.each(i, function(e, i) {
                    t.appendWebview(i)
                }),
                plus.webview.currentWebview() === plus.webview.getWebviewById(plus.runtime.appid) && (t.isHomePage = !0,
                setTimeout(function() {
                    s(plus.webview.currentWebview())
                }, 300)),
                t.os.ios && t.options.statusBarBackground && plus.navigator.setStatusBarBackground(t.options.statusBarBackground),
                t.os.android && parseFloat(t.os.version) < 4.4 && null == plus.webview.currentWebview().parent() && document.addEventListener("resume", function() {
                    var t = document.body;
                    t.style.display = "none",
                    setTimeout(function() {
                        t.style.display = ""
                    }, 10)
                })
            })
        }
    }),
    window.addEventListener("preload", function() {
        var e = t.options.preloadPages || [];
        t.plusReady(function() {
            t.each(e, function(e, i) {
                t.createWindow(t.extend(i, {
                    preload: !0
                }))
            })
        })
    }),
    t.supportStatusbarOffset = function() {
        return t.os.plus && t.os.ios && parseFloat(t.os.version) >= 7
    }
    ,
    t.ready(function() {
        t.supportStatusbarOffset() && document.body.classList.add("mui-statusbar")
    })
}(mui),
function(t, e) {
    t.addBack = function(e) {
        return t.addAction("backs", e)
    }
    ,
    t.addBack({
        name: "browser",
        index: 100,
        handle: function() {
            return e.history.length > 0 && (e.history.go(-1),
            !0)
        }
    }),
    t.back = function() {
        "function" == typeof t.options.beforeback && !1 === t.options.beforeback() || t.doAction("backs")
    }
    ,
    e.addEventListener("tap", function(e) {
        var i = t.targets.action;
        i && i.classList.contains("mui-action-back") && (t.back(),
        t.targets.action = !1)
    }),
    e.addEventListener("swiperight", function(e) {
        var i = e.detail;
        !0 === t.options.swipeBack && Math.abs(i.angle) < 3 && t.back()
    })
}(mui, window),
function(t, e) {
    t.os.plus && t.os.android && t.addBack({
        name: "mui",
        index: 5,
        handle: function() {
            if (t.targets._popover && t.targets._popover.classList.contains("mui-active"))
                return t(t.targets._popover).popover("hide"),
                !0;
            var e = document.querySelector(".mui-off-canvas-wrap.mui-active");
            if (e)
                return t(e).offCanvas("close"),
                !0;
            var i = t.isFunction(t.getPreviewImage) && t.getPreviewImage();
            return i && i.isShown() ? (i.close(),
            !0) : t.closePopup()
        }
    }),
    t.__back__first = null,
    t.addBack({
        name: "5+",
        index: 10,
        handle: function() {
            if (!e.plus)
                return !1;
            var i = plus.webview.currentWebview()
              , s = i.parent();
            return s ? s.evalJS("mui&&mui.back();") : i.canBack(function(s) {
                s.canBack ? e.history.back() : i.id === plus.runtime.appid ? t.__back__first ? (new Date).getTime() - t.__back__first < 2e3 && plus.runtime.quit() : (t.__back__first = (new Date).getTime(),
                mui.toast("再按一次退出应用"),
                setTimeout(function() {
                    t.__back__first = null
                }, 2e3)) : i.preload ? i.hide("auto") : t.closeAll(i)
            }),
            !0
        }
    }),
    t.menu = function() {
        var i = document.querySelector(".mui-action-menu");
        if (i)
            t.trigger(i, t.EVENT_START),
            t.trigger(i, "tap");
        else if (e.plus) {
            var s = t.currentWebview
              , n = s.parent();
            n && n.evalJS("mui&&mui.menu();")
        }
    }
    ;
    var i = function() {
        t.back()
    }
      , s = function() {
        t.menu()
    };
    t.plusReady(function() {
        t.options.keyEventBind.backbutton && plus.key.addEventListener("backbutton", i, !1),
        t.options.keyEventBind.menubutton && plus.key.addEventListener("menubutton", s, !1)
    }),
    t.addInit({
        name: "keyEventBind",
        index: 1e3,
        handle: function() {
            t.plusReady(function() {
                t.options.keyEventBind.backbutton || plus.key.removeEventListener("backbutton", i),
                t.options.keyEventBind.menubutton || plus.key.removeEventListener("menubutton", s)
            })
        }
    })
}(mui, window),
function(t) {
    t.addInit({
        name: "pullrefresh",
        index: 1e3,
        handle: function() {
            var e = t.options
              , i = e.pullRefresh || {}
              , s = i.down && i.down.hasOwnProperty("callback")
              , n = i.up && i.up.hasOwnProperty("callback");
            if (s || n) {
                var o = i.container;
                if (o) {
                    var a = t(o);
                    1 === a.length && (t.os.plus && t.os.android ? t.plusReady(function() {
                        var e = plus.webview.currentWebview();
                        if (n) {
                            var o = {};
                            o.up = i.up,
                            o.webviewId = e.id || e.getURL(),
                            a.pullRefresh(o)
                        }
                        if (s) {
                            var r = e.parent()
                              , l = e.id || e.getURL();
                            if (r) {
                                n || a.pullRefresh({
                                    webviewId: l
                                });
                                var c = {
                                    webviewId: l
                                };
                                c.down = t.extend({}, i.down),
                                c.down.callback = "_CALLBACK",
                                r.evalJS("mui&&mui(document.querySelector('.mui-content')).pullRefresh('" + JSON.stringify(c) + "')")
                            }
                        }
                    }) : a.pullRefresh(i))
                }
            }
        }
    })
}(mui),
function(t, e, i) {
    var s = "application/json"
      , n = /<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi
      , o = /^(?:text|application)\/javascript/i
      , a = /^(?:text|application)\/xml/i
      , r = /^\s*$/;
    t.ajaxSettings = {
        type: "GET",
        beforeSend: t.noop,
        success: t.noop,
        error: t.noop,
        complete: t.noop,
        context: null,
        xhr: function(t) {
            return new e.XMLHttpRequest
        },
        accepts: {
            script: "text/javascript, application/javascript, application/x-javascript",
            json: s,
            xml: "application/xml, text/xml",
            html: "text/html",
            text: "text/plain"
        },
        timeout: 0,
        processData: !0,
        cache: !0
    };
    var l = function(t, e) {
        var i = e.context;
        if (!1 === e.beforeSend.call(i, t, e))
            return !1
    }
      , c = function(t, e, i) {
        i.success.call(i.context, t, "success", e),
        d("success", e, i)
    }
      , u = function(t, e, i, s) {
        s.error.call(s.context, i, e, t),
        d(e, i, s)
    }
      , d = function(t, e, i) {
        i.complete.call(i.context, e, t)
    }
      , h = function(e, i, s, n) {
        var o, a = t.isArray(i), r = t.isPlainObject(i);
        t.each(i, function(i, l) {
            o = t.type(l),
            n && (i = s ? n : n + "[" + (r || "object" === o || "array" === o ? i : "") + "]"),
            !n && a ? e.add(l.name, l.value) : "array" === o || !s && "object" === o ? h(e, l, s, i) : e.add(i, l)
        })
    }
      , p = function(e) {
        if (e.processData && e.data && "string" != typeof e.data) {
            var i = e.contentType;
            !i && e.headers && (i = e.headers["Content-Type"]),
            i && ~i.indexOf(s) ? e.data = JSON.stringify(e.data) : e.data = t.param(e.data, e.traditional)
        }
        !e.data || e.type && "GET" !== e.type.toUpperCase() || (e.url = f(e.url, e.data),
        e.data = void 0)
    }
      , f = function(t, e) {
        return "" === e ? t : (t + "&" + e).replace(/[&?]{1,2}/, "?")
    }
      , m = function(t) {
        return t && (t = t.split(";", 2)[0]),
        t && ("text/html" === t ? "html" : t === s ? "json" : o.test(t) ? "script" : a.test(t) && "xml") || "text"
    }
      , g = function(e, i, s, n) {
        return t.isFunction(i) && (n = s,
        s = i,
        i = void 0),
        t.isFunction(s) || (n = s,
        s = void 0),
        {
            url: e,
            data: i,
            success: s,
            dataType: n
        }
    };
    t.ajax = function(i, s) {
        "object" == typeof i && (s = i,
        i = void 0);
        var n = s || {};
        n.url = i || n.url;
        for (var o in t.ajaxSettings)
            void 0 === n[o] && (n[o] = t.ajaxSettings[o]);
        p(n);
        var a = n.dataType;
        !1 !== n.cache && (s && !0 === s.cache || "script" !== a) || (n.url = f(n.url, "_=" + t.now()));
        var d, h = n.accepts[a && a.toLowerCase()], g = {}, v = function(t, e) {
            g[t.toLowerCase()] = [t, e]
        }, b = /^([\w-]+:)\/\//.test(n.url) ? RegExp.$1 : e.location.protocol, w = n.xhr(n), y = w.setRequestHeader;
        if (v("X-Requested-With", "XMLHttpRequest"),
        v("Accept", h || "*/*"),
        (h = n.mimeType || h) && (h.indexOf(",") > -1 && (h = h.split(",", 2)[0]),
        w.overrideMimeType && w.overrideMimeType(h)),
        (n.contentType || !1 !== n.contentType && n.data && "GET" !== n.type.toUpperCase()) && v("Content-Type", n.contentType || "application/x-www-form-urlencoded"),
        n.headers)
            for (var L in n.headers)
                v(L, n.headers[L]);
        if (w.setRequestHeader = v,
        w.onreadystatechange = function() {
            if (4 === w.readyState) {
                w.onreadystatechange = t.noop,
                clearTimeout(d);
                var e, i = !1, s = "file:" === b;
                if (w.status >= 200 && w.status < 300 || 304 === w.status || 0 === w.status && s && w.responseText) {
                    a = a || m(n.mimeType || w.getResponseHeader("content-type")),
                    e = w.responseText;
                    try {
                        "script" === a ? (0,
                        eval)(e) : "xml" === a ? e = w.responseXML : "json" === a && (e = r.test(e) ? null : t.parseJSON(e))
                    } catch (t) {
                        i = t
                    }
                    i ? u(i, "parsererror", w, n) : c(e, w, n)
                } else {
                    var o = w.status ? "error" : "abort"
                      , l = w.statusText || null;
                    s && (o = "error",
                    l = "404"),
                    u(l, o, w, n)
                }
            }
        }
        ,
        !1 === l(w, n))
            return w.abort(),
            u(null, "abort", w, n),
            w;
        if (n.xhrFields)
            for (var L in n.xhrFields)
                w[L] = n.xhrFields[L];
        var _ = !("async"in n) || n.async;
        w.open(n.type.toUpperCase(), n.url, _, n.username, n.password);
        for (var L in g)
            g.hasOwnProperty(L) && y.apply(w, g[L]);
        return n.timeout > 0 && (d = setTimeout(function() {
            w.onreadystatechange = t.noop,
            w.abort(),
            u(null, "timeout", w, n)
        }, n.timeout)),
        w.send(n.data ? n.data : null),
        w
    }
    ,
    t.param = function(t, e) {
        var i = [];
        return i.add = function(t, e) {
            this.push(encodeURIComponent(t) + "=" + encodeURIComponent(e))
        }
        ,
        h(i, t, e),
        i.join("&").replace(/%20/g, "+")
    }
    ,
    t.get = function() {
        return t.ajax(g.apply(null, arguments))
    }
    ,
    t.post = function() {
        var e = g.apply(null, arguments);
        return e.type = "POST",
        t.ajax(e)
    }
    ,
    t.getJSON = function() {
        var e = g.apply(null, arguments);
        return e.dataType = "json",
        t.ajax(e)
    }
    ,
    t.fn.load = function(e, i, s) {
        if (!this.length)
            return this;
        var o, a = this, r = e.split(/\s/), l = g(e, i, s), c = l.success;
        return r.length > 1 && (l.url = r[0],
        o = r[1]),
        l.success = function(t) {
            if (o) {
                var e = document.createElement("div");
                e.innerHTML = t.replace(n, "");
                var i = document.createElement("div")
                  , s = e.querySelectorAll(o);
                if (s && s.length > 0)
                    for (var r = 0, l = s.length; r < l; r++)
                        i.appendChild(s[r]);
                a[0].innerHTML = i.innerHTML
            } else
                a[0].innerHTML = t;
            c && c.apply(a, arguments)
        }
        ,
        t.ajax(l),
        this
    }
}(mui, window),
function(t) {
    var e = document.createElement("a");
    e.href = window.location.href,
    t.plusReady(function() {
        t.ajaxSettings = t.extend(t.ajaxSettings, {
            xhr: function(t) {
                if (t.crossDomain)
                    return new plus.net.XMLHttpRequest;
                if ("file:" !== e.protocol) {
                    var i = document.createElement("a");
                    if (i.href = t.url,
                    i.href = i.href,
                    t.crossDomain = e.protocol + "//" + e.host != i.protocol + "//" + i.host,
                    t.crossDomain)
                        return new plus.net.XMLHttpRequest
                }
                return new window.XMLHttpRequest
            }
        })
    })
}(mui),
function(t, e, i) {
    t.offset = function(t) {
        var i = {
            top: 0,
            left: 0
        };
        return void 0 !== typeof t.getBoundingClientRect && (i = t.getBoundingClientRect()),
        {
            top: i.top + e.pageYOffset - t.clientTop,
            left: i.left + e.pageXOffset - t.clientLeft
        }
    }
}(mui, window),
function(t, e) {
    t.scrollTo = function(t, i, s) {
        i = i || 1e3;
        var n = function(i) {
            if (i <= 0)
                return e.scrollTo(0, t),
                void (s && s());
            var o = t - e.scrollY;
            setTimeout(function() {
                e.scrollTo(0, e.scrollY + o / i * 10),
                n(i - 10)
            }, 16.7)
        };
        n(i)
    }
    ,
    t.animationFrame = function(t) {
        var e, i, s;
        return function() {
            e = arguments,
            s = this,
            i || (i = !0,
            requestAnimationFrame(function() {
                t.apply(s, e),
                i = !1
            }))
        }
    }
}(mui, window),
function(t) {
    var e = !1
      , i = /xyz/.test(function() {
        xyz
    }) ? /\b_super\b/ : /.*/
      , s = function() {};
    s.extend = function(t) {
        function s() {
            !e && this.init && this.init.apply(this, arguments)
        }
        var n = this.prototype;
        e = !0;
        var o = new this;
        e = !1;
        for (var a in t)
            o[a] = "function" == typeof t[a] && "function" == typeof n[a] && i.test(t[a]) ? function(t, e) {
                return function() {
                    var i = this._super;
                    this._super = n[t];
                    var s = e.apply(this, arguments);
                    return this._super = i,
                    s
                }
            }(a, t[a]) : t[a];
        return s.prototype = o,
        s.prototype.constructor = s,
        s.extend = arguments.callee,
        s
    }
    ,
    t.Class = s
}(mui),
function(t, e, i) {
    var s = "mui-pull-loading mui-icon mui-icon-pulldown"
      , n = "mui-pull-loading mui-icon mui-spinner"
      , o = ['<div class="mui-pull">', '<div class="{icon}"></div>', '<div class="mui-pull-caption">{contentrefresh}</div>', "</div>"].join("")
      , a = {
        init: function(e, i) {
            this._super(e, t.extend(!0, {
                scrollY: !0,
                scrollX: !1,
                indicators: !0,
                deceleration: .003,
                down: {
                    height: .65 * window.rem,
                    contentinit: "下拉可以刷新",
                    contentdown: "下拉可以刷新",
                    contentover: "释放立即刷新",
                    contentrefresh: "正在刷新..."
                },
                up: {
                    height: .65 * window.rem,
                    auto: !1,
                    contentinit: "",
                    contentdown: "",
                    contentrefresh: "正在加载...",
                    contentnomore: "没了哦，别拉了！",
                    duration: 300
                }
            }, i))
        },
        _init: function() {
            this._super(),
            this._initPocket()
        },
        _initPulldownRefresh: function() {
            this.pulldown = !0,
            this.pullPocket = this.topPocket,
            this.pullPocket.classList.add("mui-block"),
            this.pullPocket.classList.add("mui-visibility"),
            this.pullCaption = this.topCaption,
            this.pullLoading = this.topLoading
        },
        _initPullupRefresh: function() {
            this.pulldown = !1,
            this.pullPocket = this.bottomPocket,
            this.pullPocket.classList.add("mui-block"),
            this.pullPocket.classList.add("mui-visibility"),
            this.pullCaption = this.bottomCaption,
            this.pullLoading = this.bottomLoading
        },
        _initPocket: function() {
            var t = this.options;
            t.down && t.down.hasOwnProperty("callback") && (this.topPocket = this.scroller.querySelector(".mui-pull-top-pocket"),
            this.topPocket || (this.topPocket = this._createPocket("mui-pull-top-pocket", t.down, s),
            this.wrapper.insertBefore(this.topPocket, this.wrapper.firstChild)),
            this.topLoading = this.topPocket.querySelector(".mui-pull-loading"),
            this.topCaption = this.topPocket.querySelector(".mui-pull-caption")),
            t.up && t.up.hasOwnProperty("callback") && (this.bottomPocket = this.scroller.querySelector(".mui-pull-bottom-pocket"),
            this.bottomPocket || (this.bottomPocket = this._createPocket("mui-pull-bottom-pocket", t.up, n),
            this.scroller.appendChild(this.bottomPocket)),
            this.bottomLoading = this.bottomPocket.querySelector(".mui-pull-loading"),
            this.bottomCaption = this.bottomPocket.querySelector(".mui-pull-caption"),
            this.wrapper.addEventListener("scrollbottom", this))
        },
        _createPocket: function(t, i, s) {
            var n = e.createElement("div");
            return n.className = t,
            n.innerHTML = o.replace("{contentrefresh}", i.contentinit).replace("{icon}", s),
            n
        },
        _resetPullDownLoading: function() {
            var t = this.pullLoading;
            t && (this.pullCaption.innerHTML = this.options.down.contentdown,
            t.style.webkitTransition = "",
            t.style.webkitTransform = "",
            t.style.webkitAnimation = "",
            t.className = s)
        },
        _setCaptionClass: function(t, e, i) {
            if (!t)
                switch (i) {
                case this.options.up.contentdown:
                    e.className = "mui-pull-caption mui-pull-caption-down";
                    break;
                case this.options.up.contentrefresh:
                    e.className = "mui-pull-caption mui-pull-caption-refresh";
                    break;
                case this.options.up.contentnomore:
                    e.className = "mui-pull-caption mui-pull-caption-nomore"
                }
        },
        _setCaption: function(t, e) {
            if (!this.loading) {
                var i = this.options
                  , o = this.pullPocket
                  , a = this.pullCaption
                  , r = this.pullLoading
                  , l = this.pulldown
                  , c = this;
                o && (e ? setTimeout(function() {
                    a.innerHTML = c.lastTitle = t,
                    l ? r.className = s : (c._setCaptionClass(!1, a, t),
                    r.className = n),
                    r.style.webkitAnimation = "",
                    r.style.webkitTransition = "",
                    r.style.webkitTransform = ""
                }, 100) : t !== this.lastTitle && (a.innerHTML = t,
                l ? t === i.down.contentrefresh ? (r.className = n,
                r.style.webkitAnimation = "spinner-spin 1s step-end infinite") : t === i.down.contentover ? (r.className = "mui-pull-loading mui-icon mui-icon-pulldown",
                r.style.webkitTransition = "-webkit-transform 0.3s ease-in",
                r.style.webkitTransform = "rotate(180deg)") : t === i.down.contentdown && (r.className = s,
                r.style.webkitTransition = "-webkit-transform 0.3s ease-in",
                r.style.webkitTransform = "rotate(0deg)") : (t === i.up.contentrefresh ? r.className = n + " mui-visibility" : r.className = n + " mui-hidden",
                c._setCaptionClass(!1, a, t)),
                this.lastTitle = t))
            }
        }
    };
    t.PullRefresh = a
}(mui, document),
function(t, e, i, s) {
    var n = {
        quadratic: {
            style: "cubic-bezier(0.25, 0.46, 0.45, 0.94)",
            fn: function(t) {
                return t * (2 - t)
            }
        },
        circular: {
            style: "cubic-bezier(0.1, 0.57, 0.1, 1)",
            fn: function(t) {
                return Math.sqrt(1 - --t * t)
            }
        },
        outCirc: {
            style: "cubic-bezier(0.075, 0.82, 0.165, 1)"
        },
        outCubic: {
            style: "cubic-bezier(0.165, 0.84, 0.44, 1)"
        }
    }
      , o = t.Class.extend({
        init: function(e, i) {
            this.wrapper = this.element = e,
            this.scroller = this.wrapper.children[0],
            this.scrollerStyle = this.scroller && this.scroller.style,
            this.stopped = !1,
            this.options = t.extend(!0, {
                scrollY: !0,
                scrollX: !1,
                startX: 0,
                startY: 0,
                indicators: !0,
                stopPropagation: !1,
                hardwareAccelerated: !0,
                fixedBadAndorid: !1,
                preventDefaultException: {
                    tagName: /^(INPUT|TEXTAREA|BUTTON|SELECT|VIDEO)$/
                },
                momentum: !0,
                snapX: .5,
                snap: !1,
                bounce: !0,
                bounceTime: 500,
                bounceEasing: n.outCirc,
                scrollTime: 500,
                scrollEasing: n.outCubic,
                directionLockThreshold: 5,
                parallaxElement: !1,
                parallaxRatio: .5
            }, i),
            this.x = 0,
            this.y = 0,
            this.translateZ = this.options.hardwareAccelerated ? " translateZ(0)" : "",
            this._init(),
            this.scroller && (this.refresh(),
            this.scrollTo(this.options.startX, this.options.startY))
        },
        _init: function() {
            this._initParallax(),
            this._initIndicators(),
            this._initEvent()
        },
        _initParallax: function() {
            this.options.parallaxElement && (this.parallaxElement = i.querySelector(this.options.parallaxElement),
            this.parallaxStyle = this.parallaxElement.style,
            this.parallaxHeight = this.parallaxElement.offsetHeight,
            this.parallaxImgStyle = this.parallaxElement.querySelector("img").style)
        },
        _initIndicators: function() {
            var t = this;
            if (t.indicators = [],
            this.options.indicators) {
                var e, i = [];
                t.options.scrollY && (e = {
                    el: this._createScrollBar("mui-scrollbar-vertical"),
                    listenX: !1
                },
                this.wrapper.appendChild(e.el),
                i.push(e)),
                this.options.scrollX && (e = {
                    el: this._createScrollBar("mui-scrollbar-horizontal"),
                    listenY: !1
                },
                this.wrapper.appendChild(e.el),
                i.push(e));
                for (var s = i.length; s--; )
                    this.indicators.push(new a(this,i[s]))
            }
        },
        _initSnap: function() {
            this.currentPage = {},
            this.pages = [];
            for (var t = this.snaps, e = t.length, i = 0, s = -1, n = 0, o = 0, a = 0, r = 0, l = 0; l < e; l++) {
                var c = t[l]
                  , u = c.offsetLeft
                  , d = c.offsetWidth;
                (0 === l || u <= t[l - 1].offsetLeft) && (i = 0,
                s++),
                this.pages[i] || (this.pages[i] = []),
                n = this._getSnapX(u),
                r = Math.round(d * this.options.snapX),
                o = n - r,
                a = n - d + r,
                this.pages[i][s] = {
                    x: n,
                    leftX: o,
                    rightX: a,
                    pageX: i,
                    element: c
                },
                c.classList.contains("mui-active") && (this.currentPage = this.pages[i][0]),
                n >= this.maxScrollX && i++
            }
            this.options.startX = this.currentPage.x || 0
        },
        _getSnapX: function(t) {
            return Math.max(Math.min(0, -t + this.wrapperWidth / 2), this.maxScrollX)
        },
        _gotoPage: function(t) {
            this.currentPage = this.pages[Math.min(t, this.pages.length - 1)][0];
            for (var e = 0, i = this.snaps.length; e < i; e++)
                e === t ? this.snaps[e].classList.add("mui-active") : this.snaps[e].classList.remove("mui-active");
            this.scrollTo(this.currentPage.x, 0, this.options.scrollTime)
        },
        _nearestSnap: function(t) {
            if (!this.pages.length)
                return {
                    x: 0,
                    pageX: 0
                };
            var e = 0
              , i = this.pages.length;
            for (t > 0 ? t = 0 : t < this.maxScrollX && (t = this.maxScrollX); e < i; e++) {
                if (t >= ("left" === this.direction ? this.pages[e][0].leftX : this.pages[e][0].rightX))
                    return this.pages[e][0]
            }
            return {
                x: 0,
                pageX: 0
            }
        },
        _initEvent: function(i) {
            var s = i ? "removeEventListener" : "addEventListener";
            e[s]("orientationchange", this),
            e[s]("resize", this),
            this.scroller[s]("webkitTransitionEnd", this),
            this.wrapper[s](t.EVENT_START, this),
            this.wrapper[s](t.EVENT_CANCEL, this),
            this.wrapper[s](t.EVENT_END, this),
            this.wrapper[s]("drag", this),
            this.wrapper[s]("dragend", this),
            this.wrapper[s]("flick", this),
            this.wrapper[s]("scrollend", this),
            this.options.scrollX && this.wrapper[s]("swiperight", this);
            var n = this.wrapper.querySelector(".mui-segmented-control");
            n && mui(n)[i ? "off" : "on"]("click", "a", t.preventDefault),
            this.wrapper[s]("scrollstart", this),
            this.wrapper[s]("refresh", this)
        },
        _handleIndicatorScrollend: function() {
            this.indicators.map(function(t) {
                t.fade()
            })
        },
        _handleIndicatorScrollstart: function() {
            this.indicators.map(function(t) {
                t.fade(1)
            })
        },
        _handleIndicatorRefresh: function() {
            this.indicators.map(function(t) {
                t.refresh()
            })
        },
        handleEvent: function(e) {
            if (this.stopped)
                return void this.resetPosition();
            switch (e.type) {
            case t.EVENT_START:
                this._start(e);
                break;
            case "drag":
                this.options.stopPropagation && e.stopPropagation(),
                this._drag(e);
                break;
            case "dragend":
            case "flick":
                this.options.stopPropagation && e.stopPropagation(),
                this._flick(e);
                break;
            case t.EVENT_CANCEL:
            case t.EVENT_END:
                this._end(e);
                break;
            case "webkitTransitionEnd":
                this.transitionTimer && this.transitionTimer.cancel(),
                this._transitionEnd(e);
                break;
            case "scrollstart":
                this._handleIndicatorScrollstart(e);
                break;
            case "scrollend":
                this._handleIndicatorScrollend(e),
                this._scrollend(e),
                e.stopPropagation();
                break;
            case "orientationchange":
            case "resize":
                this._resize();
                break;
            case "swiperight":
                e.stopPropagation();
                break;
            case "refresh":
                this._handleIndicatorRefresh(e)
            }
        },
        _start: function(e) {
            if (this.moved = this.needReset = !1,
            this._transitionTime(),
            this.isInTransition) {
                this.needReset = !0,
                this.isInTransition = !1;
                var i = t.parseTranslateMatrix(t.getStyles(this.scroller, "webkitTransform"));
                this.setTranslate(Math.round(i.x), Math.round(i.y)),
                t.trigger(this.scroller, "scrollend", this),
                e.preventDefault()
            }
            this.reLayout(),
            t.trigger(this.scroller, "beforescrollstart", this)
        },
        _getDirectionByAngle: function(t) {
            return t < -80 && t > -100 ? "up" : t >= 80 && t < 100 ? "down" : t >= 170 || t <= -170 ? "left" : t >= -35 && t <= 10 ? "right" : null
        },
        _drag: function(i) {
            var s = i.detail;
            if ((this.options.scrollY || "up" === s.direction || "down" === s.direction) && t.os.ios && parseFloat(t.os.version) >= 8) {
                var n = s.gesture.touches[0].clientY;
                if (n + 10 > e.innerHeight || n < 10)
                    return void this.resetPosition(this.options.bounceTime)
            }
            var o = isReturn = !1;
            this._getDirectionByAngle(s.angle);
            if ("left" === s.direction || "right" === s.direction ? this.options.scrollX ? (o = !0,
            this.moved || (t.gestures.session.lockDirection = !0,
            t.gestures.session.startDirection = s.direction)) : this.options.scrollY && !this.moved && (isReturn = !0) : "up" === s.direction || "down" === s.direction ? this.options.scrollY ? (o = !0,
            this.moved || (t.gestures.session.lockDirection = !0,
            t.gestures.session.startDirection = s.direction)) : this.options.scrollX && !this.moved && (isReturn = !0) : isReturn = !0,
            (this.moved || o) && (i.stopPropagation(),
            s.gesture && s.gesture.preventDefault()),
            !isReturn) {
                this.moved ? i.stopPropagation() : t.trigger(this.scroller, "scrollstart", this);
                var a = 0
                  , r = 0;
                this.moved ? (a = s.deltaX - t.gestures.session.prevTouch.deltaX,
                r = s.deltaY - t.gestures.session.prevTouch.deltaY) : (a = s.deltaX,
                r = s.deltaY);
                var l = Math.abs(s.deltaX)
                  , c = Math.abs(s.deltaY);
                l > c + this.options.directionLockThreshold ? r = 0 : c >= l + this.options.directionLockThreshold && (a = 0),
                a = this.hasHorizontalScroll ? a : 0,
                r = this.hasVerticalScroll ? r : 0;
                var u = this.x + a
                  , d = this.y + r;
                (u > 0 || u < this.maxScrollX) && (u = this.options.bounce ? this.x + a / 3 : u > 0 ? 0 : this.maxScrollX),
                (d > 0 || d < this.maxScrollY) && (d = this.options.bounce ? this.y + r / 3 : d > 0 ? 0 : this.maxScrollY),
                this.requestAnimationFrame || this._updateTranslate(),
                this.direction = s.deltaX > 0 ? "right" : "left",
                this.moved = !0,
                this.x = u,
                this.y = d,
                t.trigger(this.scroller, "scroll", this)
            }
        },
        _flick: function(e) {
            if (this.moved) {
                e.stopPropagation();
                var i = e.detail;
                if (this._clearRequestAnimationFrame(),
                "dragend" !== e.type || !i.flick) {
                    var s = Math.round(this.x)
                      , o = Math.round(this.y);
                    if (this.isInTransition = !1,
                    !this.resetPosition(this.options.bounceTime)) {
                        if (this.scrollTo(s, o),
                        "dragend" === e.type)
                            return void t.trigger(this.scroller, "scrollend", this);
                        var a = 0
                          , r = "";
                        if (this.options.momentum && i.flickTime < 300 && (momentumX = this.hasHorizontalScroll ? this._momentum(this.x, i.flickDistanceX, i.flickTime, this.maxScrollX, this.options.bounce ? this.wrapperWidth : 0, this.options.deceleration) : {
                            destination: s,
                            duration: 0
                        },
                        momentumY = this.hasVerticalScroll ? this._momentum(this.y, i.flickDistanceY, i.flickTime, this.maxScrollY, this.options.bounce ? this.wrapperHeight : 0, this.options.deceleration) : {
                            destination: o,
                            duration: 0
                        },
                        s = momentumX.destination,
                        o = momentumY.destination,
                        a = Math.max(momentumX.duration, momentumY.duration),
                        this.isInTransition = !0),
                        s != this.x || o != this.y)
                            return (s > 0 || s < this.maxScrollX || o > 0 || o < this.maxScrollY) && (r = n.quadratic),
                            void this.scrollTo(s, o, a, r);
                        t.trigger(this.scroller, "scrollend", this)
                    }
                }
            }
        },
        _end: function(e) {
            this.needReset = !1,
            (!this.moved && this.needReset || e.type === t.EVENT_CANCEL) && this.resetPosition()
        },
        _transitionEnd: function(e) {
            e.target == this.scroller && this.isInTransition && (this._transitionTime(),
            this.resetPosition(this.options.bounceTime) || (this.isInTransition = !1,
            t.trigger(this.scroller, "scrollend", this)))
        },
        _scrollend: function(e) {
            (0 === this.y && 0 === this.maxScrollY || Math.abs(this.y) > 0 && this.y <= this.maxScrollY) && t.trigger(this.scroller, "scrollbottom", this)
        },
        _resize: function() {
            var t = this;
            clearTimeout(t.resizeTimeout),
            t.resizeTimeout = setTimeout(function() {
                t.refresh()
            }, t.options.resizePolling)
        },
        _transitionTime: function(e) {
            if (e = e || 0,
            this.scrollerStyle.webkitTransitionDuration = e + "ms",
            this.parallaxElement && this.options.scrollY && (this.parallaxStyle.webkitTransitionDuration = e + "ms"),
            this.options.fixedBadAndorid && !e && t.os.isBadAndroid && (this.scrollerStyle.webkitTransitionDuration = "0.001s",
            this.parallaxElement && this.options.scrollY && (this.parallaxStyle.webkitTransitionDuration = "0.001s")),
            this.indicators)
                for (var i = this.indicators.length; i--; )
                    this.indicators[i].transitionTime(e);
            e && (this.transitionTimer && this.transitionTimer.cancel(),
            this.transitionTimer = t.later(function() {
                t.trigger(this.scroller, "webkitTransitionEnd")
            }, e + 100, this))
        },
        _transitionTimingFunction: function(t) {
            if (this.scrollerStyle.webkitTransitionTimingFunction = t,
            this.parallaxElement && this.options.scrollY && (this.parallaxStyle.webkitTransitionDuration = t),
            this.indicators)
                for (var e = this.indicators.length; e--; )
                    this.indicators[e].transitionTimingFunction(t)
        },
        _translate: function(t, e) {
            this.x = t,
            this.y = e
        },
        _clearRequestAnimationFrame: function() {
            this.requestAnimationFrame && (cancelAnimationFrame(this.requestAnimationFrame),
            this.requestAnimationFrame = null)
        },
        _updateTranslate: function() {
            var t = this;
            t.x === t.lastX && t.y === t.lastY || t.setTranslate(t.x, t.y),
            t.requestAnimationFrame = requestAnimationFrame(function() {
                t._updateTranslate()
            })
        },
        _createScrollBar: function(t) {
            var e = i.createElement("div")
              , s = i.createElement("div");
            return e.className = "mui-scrollbar " + t,
            s.className = "mui-scrollbar-indicator",
            e.appendChild(s),
            "mui-scrollbar-vertical" === t ? (this.scrollbarY = e,
            this.scrollbarIndicatorY = s) : "mui-scrollbar-horizontal" === t && (this.scrollbarX = e,
            this.scrollbarIndicatorX = s),
            this.wrapper.appendChild(e),
            e
        },
        _preventDefaultException: function(t, e) {
            for (var i in e)
                if (e[i].test(t[i]))
                    return !0;
            return !1
        },
        _reLayout: function() {
            if (this.hasHorizontalScroll || (this.maxScrollX = 0,
            this.scrollerWidth = this.wrapperWidth),
            this.hasVerticalScroll || (this.maxScrollY = 0,
            this.scrollerHeight = this.wrapperHeight),
            this.indicators.map(function(t) {
                t.refresh()
            }),
            this.options.snap && "string" == typeof this.options.snap) {
                var t = this.scroller.querySelectorAll(this.options.snap);
                this.itemLength = 0,
                this.snaps = [];
                for (var e = 0, i = t.length; e < i; e++) {
                    var s = t[e];
                    s.parentNode === this.scroller && (this.itemLength++,
                    this.snaps.push(s))
                }
                this._initSnap()
            }
        },
        _momentum: function(t, e, i, s, n, o) {
            var a, r, l = parseFloat(Math.abs(e) / i);
            return o = void 0 === o ? 6e-4 : o,
            a = t + l * l / (2 * o) * (e < 0 ? -1 : 1),
            r = l / o,
            a < s ? (a = n ? s - n / 2.5 * (l / 8) : s,
            e = Math.abs(a - t),
            r = e / l) : a > 0 && (a = n ? n / 2.5 * (l / 8) : 0,
            e = Math.abs(t) + a,
            r = e / l),
            {
                destination: Math.round(a),
                duration: r
            }
        },
        _getTranslateStr: function(t, e) {
            return this.options.hardwareAccelerated ? "translate3d(" + t + "px," + e + "px,0px) " + this.translateZ : "translate(" + t + "px," + e + "px) "
        },
        setStopped: function(t) {
            this.stopped = !!t
        },
        setTranslate: function(e, i) {
            if (this.x = e,
            this.y = i,
            this.scrollerStyle.webkitTransform = this._getTranslateStr(e, i),
            this.parallaxElement && this.options.scrollY) {
                var s = i * this.options.parallaxRatio
                  , n = 1 + s / ((this.parallaxHeight - s) / 2);
                n > 1 ? (this.parallaxImgStyle.opacity = 1 - s / 100 * this.options.parallaxRatio,
                this.parallaxStyle.webkitTransform = this._getTranslateStr(0, -s) + " scale(" + n + "," + n + ")") : (this.parallaxImgStyle.opacity = 1,
                this.parallaxStyle.webkitTransform = this._getTranslateStr(0, -1) + " scale(1,1)")
            }
            if (this.indicators)
                for (var o = this.indicators.length; o--; )
                    this.indicators[o].updatePosition();
            this.lastX = this.x,
            this.lastY = this.y,
            t.trigger(this.scroller, "scroll", this)
        },
        reLayout: function() {
            this.wrapper.offsetHeight;
            var e = parseFloat(t.getStyles(this.wrapper, "padding-left")) || 0
              , i = parseFloat(t.getStyles(this.wrapper, "padding-right")) || 0
              , s = parseFloat(t.getStyles(this.wrapper, "padding-top")) || 0
              , n = parseFloat(t.getStyles(this.wrapper, "padding-bottom")) || 0
              , o = this.wrapper.clientWidth
              , a = this.wrapper.clientHeight;
            this.scrollerWidth = this.scroller.offsetWidth,
            this.scrollerHeight = this.scroller.offsetHeight,
            this.wrapperWidth = o - e - i,
            this.wrapperHeight = a - s - n,
            this.maxScrollX = Math.min(this.wrapperWidth - this.scrollerWidth, 0),
            this.maxScrollY = Math.min(this.wrapperHeight - this.scrollerHeight, 0),
            this.hasHorizontalScroll = this.options.scrollX && this.maxScrollX < 0,
            this.hasVerticalScroll = this.options.scrollY && this.maxScrollY < 0,
            this._reLayout()
        },
        resetPosition: function(t) {
            var e = this.x
              , i = this.y;
            return t = t || 0,
            !this.hasHorizontalScroll || this.x > 0 ? e = 0 : this.x < this.maxScrollX && (e = this.maxScrollX),
            !this.hasVerticalScroll || this.y > 0 ? i = 0 : this.y < this.maxScrollY && (i = this.maxScrollY),
            (e != this.x || i != this.y) && (this.scrollTo(e, i, t, this.options.scrollEasing),
            !0)
        },
        _reInit: function() {
            for (var t = this.wrapper.querySelectorAll(".mui-scroll"), e = 0, i = t.length; e < i; e++)
                if (t[e].parentNode === this.wrapper) {
                    this.scroller = t[e];
                    break
                }
            this.scrollerStyle = this.scroller && this.scroller.style
        },
        refresh: function() {
            this._reInit(),
            this.reLayout(),
            t.trigger(this.scroller, "refresh", this),
            this.resetPosition()
        },
        scrollTo: function(t, e, i, s) {
            var s = s || n.circular;
            this.isInTransition = i > 0,
            this.isInTransition ? (this._clearRequestAnimationFrame(),
            this._transitionTimingFunction(s.style),
            this._transitionTime(i),
            this.setTranslate(t, e)) : this.setTranslate(t, e)
        },
        scrollToBottom: function(t, e) {
            t = t || this.options.scrollTime,
            this.scrollTo(0, this.maxScrollY, t, e)
        },
        gotoPage: function(t) {
            this._gotoPage(t)
        },
        destroy: function() {
            this._initEvent(!0),
            delete t.data[this.wrapper.getAttribute("data-scroll")],
            this.wrapper.setAttribute("data-scroll", "")
        }
    })
      , a = function(e, s) {
        this.wrapper = "string" == typeof s.el ? i.querySelector(s.el) : s.el,
        this.wrapperStyle = this.wrapper.style,
        this.indicator = this.wrapper.children[0],
        this.indicatorStyle = this.indicator.style,
        this.scroller = e,
        this.options = t.extend({
            listenX: !0,
            listenY: !0,
            fade: !1,
            speedRatioX: 0,
            speedRatioY: 0
        }, s),
        this.sizeRatioX = 1,
        this.sizeRatioY = 1,
        this.maxPosX = 0,
        this.maxPosY = 0,
        this.options.fade && (this.wrapperStyle.webkitTransform = this.scroller.translateZ,
        this.wrapperStyle.webkitTransitionDuration = this.options.fixedBadAndorid && t.os.isBadAndroid ? "0.001s" : "0ms",
        this.wrapperStyle.opacity = "0")
    };
    a.prototype = {
        handleEvent: function(t) {},
        transitionTime: function(e) {
            e = e || 0,
            this.indicatorStyle.webkitTransitionDuration = e + "ms",
            this.scroller.options.fixedBadAndorid && !e && t.os.isBadAndroid && (this.indicatorStyle.webkitTransitionDuration = "0.001s")
        },
        transitionTimingFunction: function(t) {
            this.indicatorStyle.webkitTransitionTimingFunction = t
        },
        refresh: function() {
            this.transitionTime(),
            this.options.listenX && !this.options.listenY ? this.indicatorStyle.display = this.scroller.hasHorizontalScroll ? "block" : "none" : this.options.listenY && !this.options.listenX ? this.indicatorStyle.display = this.scroller.hasVerticalScroll ? "block" : "none" : this.indicatorStyle.display = this.scroller.hasHorizontalScroll || this.scroller.hasVerticalScroll ? "block" : "none",
            this.wrapper.offsetHeight,
            this.options.listenX && (this.wrapperWidth = this.wrapper.clientWidth,
            this.indicatorWidth = Math.max(Math.round(this.wrapperWidth * this.wrapperWidth / (this.scroller.scrollerWidth || this.wrapperWidth || 1)), 8),
            this.indicatorStyle.width = this.indicatorWidth + "px",
            this.maxPosX = this.wrapperWidth - this.indicatorWidth,
            this.minBoundaryX = 0,
            this.maxBoundaryX = this.maxPosX,
            this.sizeRatioX = this.options.speedRatioX || this.scroller.maxScrollX && this.maxPosX / this.scroller.maxScrollX),
            this.options.listenY && (this.wrapperHeight = this.wrapper.clientHeight,
            this.indicatorHeight = Math.max(Math.round(this.wrapperHeight * this.wrapperHeight / (this.scroller.scrollerHeight || this.wrapperHeight || 1)), 8),
            this.indicatorStyle.height = this.indicatorHeight + "px",
            this.maxPosY = this.wrapperHeight - this.indicatorHeight,
            this.minBoundaryY = 0,
            this.maxBoundaryY = this.maxPosY,
            this.sizeRatioY = this.options.speedRatioY || this.scroller.maxScrollY && this.maxPosY / this.scroller.maxScrollY),
            this.updatePosition()
        },
        updatePosition: function() {
            var t = this.options.listenX && Math.round(this.sizeRatioX * this.scroller.x) || 0
              , e = this.options.listenY && Math.round(this.sizeRatioY * this.scroller.y) || 0;
            t < this.minBoundaryX ? (this.width = Math.max(this.indicatorWidth + t, 8),
            this.indicatorStyle.width = this.width + "px",
            t = this.minBoundaryX) : t > this.maxBoundaryX ? (this.width = Math.max(this.indicatorWidth - (t - this.maxPosX), 8),
            this.indicatorStyle.width = this.width + "px",
            t = this.maxPosX + this.indicatorWidth - this.width) : this.width != this.indicatorWidth && (this.width = this.indicatorWidth,
            this.indicatorStyle.width = this.width + "px"),
            e < this.minBoundaryY ? (this.height = Math.max(this.indicatorHeight + 3 * e, 8),
            this.indicatorStyle.height = this.height + "px",
            e = this.minBoundaryY) : e > this.maxBoundaryY ? (this.height = Math.max(this.indicatorHeight - 3 * (e - this.maxPosY), 8),
            this.indicatorStyle.height = this.height + "px",
            e = this.maxPosY + this.indicatorHeight - this.height) : this.height != this.indicatorHeight && (this.height = this.indicatorHeight,
            this.indicatorStyle.height = this.height + "px"),
            this.x = t,
            this.y = e,
            this.indicatorStyle.webkitTransform = this.scroller._getTranslateStr(t, e)
        },
        fade: function(t, e) {
            if (!e || this.visible) {
                clearTimeout(this.fadeTimeout),
                this.fadeTimeout = null;
                var i = t ? 250 : 500
                  , s = t ? 0 : 300;
                t = t ? "1" : "0",
                this.wrapperStyle.webkitTransitionDuration = i + "ms",
                this.fadeTimeout = setTimeout(function(t) {
                    this.wrapperStyle.opacity = t,
                    this.visible = +t
                }
                .bind(this, t), s)
            }
        }
    },
    t.Scroll = o,
    t.fn.scroll = function(e) {
        var i = [];
        return this.each(function() {
            var s = null
              , n = this
              , a = n.getAttribute("data-scroll");
            if (a)
                s = t.data[a];
            else {
                a = ++t.uuid;
                var r = t.extend({}, e);
                n.classList.contains("mui-segmented-control") && (r = t.extend(r, {
                    scrollY: !1,
                    scrollX: !0,
                    indicators: !1,
                    snap: ".mui-control-item"
                })),
                t.data[a] = s = new o(n,r),
                n.setAttribute("data-scroll", a)
            }
            i.push(s)
        }),
        1 === i.length ? i[0] : i
    }
}(mui, window, document),
function(t, e, i, s) {
    var n = t.Scroll.extend(t.extend({
        handleEvent: function(t) {
            this._super(t),
            "scrollbottom" === t.type && t.target === this.scroller && this._scrollbottom()
        },
        _scrollbottom: function() {
            this.pulldown || this.loading || (this.pulldown = !1,
            this._initPullupRefresh(),
            this.pullupLoading())
        },
        _start: function(t) {
            t.touches && t.touches.length && t.touches[0].clientX > 30 && t.target && !this._preventDefaultException(t.target, this.options.preventDefaultException) && t.preventDefault(),
            this.loading || (this.pulldown = this.pullPocket = this.pullCaption = this.pullLoading = !1),
            this._super(t)
        },
        _drag: function(t) {
            this._super(t),
            !this.pulldown && !this.loading && this.topPocket && "down" === t.detail.direction && this.y >= 0 && this._initPulldownRefresh(),
            this.pulldown && this._setCaption(this.y > this.options.down.height ? this.options.down.contentover : this.options.down.contentdown)
        },
        _reLayout: function() {
            this.hasVerticalScroll = !0,
            this._super()
        },
        resetPosition: function(t) {
            if (this.pulldown) {
                if (this.y >= this.options.down.height)
                    return this.pulldownLoading(void 0, t || 0),
                    !0;
                !this.loading && this.topPocket.classList.remove("mui-visibility")
            }
            return this._super(t)
        },
        pulldownLoading: function(t, e) {
            if (void 0 === t && (t = this.options.down.height),
            this.scrollTo(0, t, e, this.options.bounceEasing),
            !this.loading) {
                this._initPulldownRefresh(),
                this._setCaption(this.options.down.contentrefresh),
                this.loading = !0,
                this.indicators.map(function(t) {
                    t.fade(0)
                });
                var i = this.options.down.callback;
                i && i.call(this)
            }
        },
        endPulldownToRefresh: function() {
            var t = this;
            t.topPocket && t.loading && this.pulldown && (t.scrollTo(0, 0, t.options.bounceTime, t.options.bounceEasing),
            t.loading = !1,
            t._setCaption(t.options.down.contentdown, !0),
            setTimeout(function() {
                t.loading || t.topPocket.classList.remove("mui-visibility")
            }, 350))
        },
        pullupLoading: function(t, e, i) {
            e = e || 0,
            this.scrollTo(e, this.maxScrollY, i, this.options.bounceEasing),
            this.loading || (this._initPullupRefresh(),
            this._setCaption(this.options.up.contentrefresh),
            this.indicators.map(function(t) {
                t.fade(0)
            }),
            this.loading = !0,
            (t = t || this.options.up.callback) && t.call(this))
        },
        endPullupToRefresh: function(t) {
            var e = this;
            e.bottomPocket && (e.loading = !1,
            t ? (this.finished = !0,
            e._setCaption(e.options.up.contentnomore),
            e.wrapper.removeEventListener("scrollbottom", e)) : (e._setCaption(e.options.up.contentdown),
            e.loading || e.bottomPocket.classList.remove("mui-visibility")))
        },
        disablePullupToRefresh: function() {
            this._initPullupRefresh(),
            this.bottomPocket.className = "mui-pull-bottom-pocket mui-hidden",
            this.wrapper.removeEventListener("scrollbottom", this)
        },
        enablePullupToRefresh: function() {
            this._initPullupRefresh(),
            this.bottomPocket.classList.remove("mui-hidden"),
            this._setCaption(this.options.up.contentdown),
            this.wrapper.addEventListener("scrollbottom", this)
        },
        refresh: function(t) {
            t && this.finished && (this.enablePullupToRefresh(),
            this.finished = !1),
            this._super()
        }
    }, t.PullRefresh));
    t.fn.pullRefresh = function(e) {
        if (1 === this.length) {
            var i = this[0]
              , s = null;
            e = e || {};
            var o = i.getAttribute("data-pullrefresh");
            return o ? s = t.data[o] : (o = ++t.uuid,
            t.data[o] = s = new n(i,e),
            i.setAttribute("data-pullrefresh", o)),
            e.down && e.down.auto ? s.pulldownLoading(e.down.autoY) : e.up && e.up.auto && s.pullupLoading(),
            s
        }
    }
}(mui, window, document),
function(t, e) {
    var i = t.Slider = t.Scroll.extend({
        init: function(e, i) {
            this._super(e, t.extend(!0, {
                fingers: 1,
                interval: 0,
                scrollY: !1,
                scrollX: !0,
                indicators: !1,
                scrollTime: 1e3,
                startX: !1,
                slideTime: 0,
                snap: ".mui-slider-item"
            }, i)),
            this.options.startX
        },
        _init: function() {
            this._reInit(),
            this.scroller && (this.scrollerStyle = this.scroller.style,
            this.progressBar = this.wrapper.querySelector(".mui-slider-progress-bar"),
            this.progressBar && (this.progressBarWidth = this.progressBar.offsetWidth,
            this.progressBarStyle = this.progressBar.style),
            this._super(),
            this._initTimer())
        },
        _triggerSlide: function() {
            var e = this;
            e.isInTransition = !1;
            e.currentPage;
            e.slideNumber = e._fixedSlideNumber(),
            e.loop && (0 === e.slideNumber ? e.setTranslate(e.pages[1][0].x, 0) : e.slideNumber === e.itemLength - 3 && e.setTranslate(e.pages[e.itemLength - 2][0].x, 0)),
            e.lastSlideNumber != e.slideNumber && (e.lastSlideNumber = e.slideNumber,
            e.lastPage = e.currentPage,
            t.trigger(e.wrapper, "slide", {
                slideNumber: e.slideNumber
            })),
            e._initTimer()
        },
        _handleSlide: function(e) {
            var i = this;
            if (e.target === i.wrapper) {
                var s = e.detail;
                s.slideNumber = s.slideNumber || 0;
                for (var n = i.scroller.querySelectorAll(".mui-slider-item"), o = [], a = 0, r = n.length; a < r; a++) {
                    var l = n[a];
                    l.parentNode === i.scroller && o.push(l)
                }
                var c = s.slideNumber;
                if (i.loop && (c += 1),
                !i.wrapper.classList.contains("mui-segmented-control"))
                    for (var a = 0, r = o.length; a < r; a++) {
                        var l = o[a];
                        l.parentNode === i.scroller && (a === c ? l.classList.add("mui-active") : l.classList.remove("mui-active"))
                    }
                var u = i.wrapper.querySelector(".mui-slider-indicator");
                if (u) {
                    u.getAttribute("data-scroll") && t(u).scroll().gotoPage(s.slideNumber);
                    var d = u.querySelectorAll(".mui-indicator");
                    if (d.length > 0)
                        for (var a = 0, r = d.length; a < r; a++)
                            d[a].classList[a === s.slideNumber ? "add" : "remove"]("mui-active");
                    else {
                        var h = u.querySelector(".mui-number span");
                        if (h)
                            h.innerText = s.slideNumber + 1;
                        else
                            for (var p = u.querySelectorAll(".mui-control-item"), a = 0, r = p.length; a < r; a++)
                                p[a].classList[a === s.slideNumber ? "add" : "remove"]("mui-active")
                    }
                }
                e.stopPropagation()
            }
        },
        _handleTabShow: function(t) {
            var e = this;
            e.gotoItem(t.detail.tabNumber || 0, e.options.slideTime)
        },
        _handleIndicatorTap: function(t) {
            var e = this
              , i = t.target;
            (i.classList.contains("mui-action-previous") || i.classList.contains("mui-action-next")) && (e[i.classList.contains("mui-action-previous") ? "prevItem" : "nextItem"](),
            t.stopPropagation())
        },
        _initEvent: function(e) {
            var i = this;
            i._super(e);
            var s = e ? "removeEventListener" : "addEventListener";
            i.wrapper[s]("slide", this),
            i.wrapper[s](t.eventName("shown", "tab"), this)
        },
        handleEvent: function(e) {
            switch (this._super(e),
            e.type) {
            case "slide":
                this._handleSlide(e);
                break;
            case t.eventName("shown", "tab"):
                ~this.snaps.indexOf(e.target) && this._handleTabShow(e)
            }
        },
        _scrollend: function(t) {
            this._super(t),
            this._triggerSlide(t)
        },
        _drag: function(t) {
            this._super(t);
            var i = t.detail.direction;
            if ("left" === i || "right" === i) {
                var s = this.wrapper.getAttribute("data-slidershowTimer");
                s && e.clearTimeout(s),
                t.stopPropagation()
            }
        },
        _initTimer: function() {
            var t = this
              , i = t.wrapper
              , s = t.options.interval
              , n = i.getAttribute("data-slidershowTimer");
            n && e.clearTimeout(n),
            s && (n = e.setTimeout(function() {
                i && ((i.offsetWidth || i.offsetHeight) && t.nextItem(!0),
                t._initTimer())
            }, s),
            i.setAttribute("data-slidershowTimer", n))
        },
        _fixedSlideNumber: function(t) {
            t = t || this.currentPage;
            var e = t.pageX;
            return this.loop && (e = 0 === t.pageX ? this.itemLength - 3 : t.pageX === this.itemLength - 1 ? 0 : t.pageX - 1),
            e
        },
        _reLayout: function() {
            this.hasHorizontalScroll = !0,
            this.loop = this.scroller.classList.contains("mui-slider-loop"),
            this._super()
        },
        _getScroll: function() {
            var e = t.parseTranslateMatrix(t.getStyles(this.scroller, "webkitTransform"));
            return e ? e.x : 0
        },
        _transitionEnd: function(e) {
            e.target === this.scroller && this.isInTransition && (this._transitionTime(),
            this.isInTransition = !1,
            t.trigger(this.wrapper, "scrollend", this))
        },
        _flick: function(t) {
            if (this.moved) {
                var e = t.detail
                  , i = e.direction;
                this._clearRequestAnimationFrame(),
                this.isInTransition = !0,
                "flick" === t.type ? (e.deltaTime < 200 && (this.x = this._getPage(this.slideNumber + ("right" === i ? -1 : 1), !0).x),
                this.resetPosition(this.options.bounceTime)) : "dragend" !== t.type || e.flick || this.resetPosition(this.options.bounceTime),
                t.stopPropagation()
            }
        },
        _initSnap: function() {
            if (this.scrollerWidth = this.itemLength * this.scrollerWidth,
            this.maxScrollX = Math.min(this.wrapperWidth - this.scrollerWidth, 0),
            this._super(),
            this.currentPage.x)
                this.slideNumber = this._fixedSlideNumber(),
                this.lastSlideNumber = void 0 === this.lastSlideNumber ? this.slideNumber : this.lastSlideNumber;
            else {
                var t = this.pages[this.loop ? 1 : 0];
                if (!(t = t || this.pages[0]))
                    return;
                this.currentPage = t[0],
                this.slideNumber = 0,
                this.lastSlideNumber = void 0 === this.lastSlideNumber ? 0 : this.lastSlideNumber
            }
            this.options.startX = this.currentPage.x || 0
        },
        _getSnapX: function(t) {
            return Math.max(-t, this.maxScrollX)
        },
        _getPage: function(t, e) {
            return this.loop ? t > this.itemLength - (e ? 2 : 3) ? (t = 1,
            time = 0) : t < (e ? -1 : 0) ? (t = this.itemLength - 2,
            time = 0) : t += 1 : (e || (t > this.itemLength - 1 ? (t = 0,
            time = 0) : t < 0 && (t = this.itemLength - 1,
            time = 0)),
            t = Math.min(Math.max(0, t), this.itemLength - 1)),
            this.pages[t][0]
        },
        _gotoItem: function(e, i) {
            this.currentPage = this._getPage(e, !0),
            this.scrollTo(this.currentPage.x, 0, i, this.options.scrollEasing),
            0 === i && t.trigger(this.wrapper, "scrollend", this)
        },
        setTranslate: function(t, e) {
            this._super(t, e),
            this.progressBar && (this.progressBarStyle.webkitTransform = this._getTranslateStr(-t * (this.progressBarWidth / this.wrapperWidth), 0))
        },
        resetPosition: function(t) {
            return t = t || 0,
            this.x > 0 ? this.x = 0 : this.x < this.maxScrollX && (this.x = this.maxScrollX),
            this.currentPage = this._nearestSnap(this.x),
            this.scrollTo(this.currentPage.x, 0, t, this.options.scrollEasing),
            !0
        },
        gotoItem: function(t, e) {
            this._gotoItem(t, void 0 === e ? this.options.scrollTime : e)
        },
        nextItem: function() {
            this._gotoItem(this.slideNumber + 1, this.options.scrollTime)
        },
        prevItem: function() {
            this._gotoItem(this.slideNumber - 1, this.options.scrollTime)
        },
        getSlideNumber: function() {
            return this.slideNumber || 0
        },
        _reInit: function() {
            for (var t = this.wrapper.querySelectorAll(".mui-slider-group"), e = 0, i = t.length; e < i; e++)
                if (t[e].parentNode === this.wrapper) {
                    this.scroller = t[e];
                    break
                }
            this.scrollerStyle = this.scroller && this.scroller.style,
            this.progressBar && (this.progressBarWidth = this.progressBar.offsetWidth,
            this.progressBarStyle = this.progressBar.style)
        },
        refresh: function(e) {
            e ? (t.extend(this.options, e),
            this._super(),
            this._initTimer()) : this._super()
        },
        destroy: function() {
            this._initEvent(!0),
            delete t.data[this.wrapper.getAttribute("data-slider")],
            this.wrapper.setAttribute("data-slider", "")
        }
    });
    t.fn.slider = function(e) {
        var s = null;
        return this.each(function() {
            var n = this;
            if (this.classList.contains("mui-slider") || (n = this.querySelector(".mui-slider")),
            n && n.querySelector(".mui-slider-item")) {
                var o = n.getAttribute("data-slider");
                o ? (s = t.data[o]) && e && s.refresh(e) : (o = ++t.uuid,
                t.data[o] = s = new i(n,e),
                n.setAttribute("data-slider", o))
            }
        }),
        s
    }
    ,
    t.ready(function() {
        t(".mui-slider").slider(),
        t(".mui-scroll-wrapper.mui-slider-indicator.mui-segmented-control").scroll({
            scrollY: !1,
            scrollX: !0,
            indicators: !1,
            snap: ".mui-control-item"
        })
    })
}(mui, window),
function(t, e) {
    t.os.plus && t.os.android && t.plusReady(function() {
        if (!1 !== window.__NWin_Enable__) {
            var i = t.Class.extend({
                init: function(t, e) {
                    this.element = t,
                    this.options = e,
                    this.wrapper = this.scroller = t,
                    this._init(),
                    this._initPulldownRefreshEvent()
                },
                _init: function() {
                    var t = this;
                    window.addEventListener("dragup", t),
                    e.addEventListener("plusscrollbottom", t),
                    t.scrollInterval = window.setInterval(function() {
                        t.isScroll && !t.loading && window.pageYOffset + window.innerHeight + 10 >= e.documentElement.scrollHeight && (t.isScroll = !1,
                        t.bottomPocket && t.pullupLoading())
                    }, 100)
                },
                _initPulldownRefreshEvent: function() {
                    var e = this;
                    e.topPocket && e.options.webviewId && t.plusReady(function() {
                        var t = plus.webview.getWebviewById(e.options.webviewId);
                        if (t) {
                            e.options.webview = t;
                            var i = e.options.down
                              , s = i.height;
                            t.addEventListener("close", function() {
                                var t = e.options.webviewId && e.options.webviewId.replace(/\//g, "_");
                                e.element.removeAttribute("data-pullrefresh-plus-" + t)
                            }),
                            t.addEventListener("dragBounce", function(s) {
                                switch (e.pulldown ? e.pullPocket.classList.add("mui-block") : e._initPulldownRefresh(),
                                s.status) {
                                case "beforeChangeOffset":
                                    e._setCaption(i.contentdown);
                                    break;
                                case "afterChangeOffset":
                                    e._setCaption(i.contentover);
                                    break;
                                case "dragEndAfterChangeOffset":
                                    t.evalJS("mui&&mui.options.pullRefresh.down.callback()"),
                                    e._setCaption(i.contentrefresh)
                                }
                            }, !1),
                            t.setBounce({
                                position: {
                                    top: 2 * s + "px"
                                },
                                changeoffset: {
                                    top: s + "px"
                                }
                            })
                        }
                    })
                },
                handleEvent: function(t) {
                    var e = this;
                    e.stopped || (e.isScroll = !1,
                    "dragup" !== t.type && "plusscrollbottom" !== t.type || (e.isScroll = !0,
                    setTimeout(function() {
                        e.isScroll = !1
                    }, 1e3)))
                }
            }).extend(t.extend({
                setStopped: function(t) {
                    this.stopped = !!t;
                    var e = plus.webview.currentWebview();
                    if (this.stopped)
                        e.setStyle({
                            bounce: "none"
                        }),
                        e.setBounce({
                            position: {
                                top: "none"
                            }
                        });
                    else {
                        var i = this.options.down.height;
                        e.setStyle({
                            bounce: "vertical"
                        }),
                        e.setBounce({
                            position: {
                                top: 2 * i + "px"
                            },
                            changeoffset: {
                                top: i + "px"
                            }
                        })
                    }
                },
                pulldownLoading: function() {
                    t.plusReady(function() {
                        plus.webview.currentWebview().setBounce({
                            offset: {
                                top: this.options.down.height + "px"
                            }
                        })
                    }
                    .bind(this))
                },
                _pulldownLoading: function() {
                    var e = this;
                    t.plusReady(function() {
                        plus.webview.getWebviewById(e.options.webviewId).setBounce({
                            offset: {
                                top: e.options.down.height + "px"
                            }
                        })
                    })
                },
                endPulldownToRefresh: function() {
                    var t = plus.webview.currentWebview();
                    t.parent().evalJS("mui&&mui(document.querySelector('.mui-content')).pullRefresh('" + JSON.stringify({
                        webviewId: t.id
                    }) + "')._endPulldownToRefresh()")
                },
                _endPulldownToRefresh: function() {
                    var t = this;
                    t.topPocket && t.options.webview && (t.options.webview.endPullToRefresh(),
                    t.loading = !1,
                    t._setCaption(t.options.down.contentdown, !0),
                    setTimeout(function() {
                        t.loading || t.topPocket.classList.remove("mui-block")
                    }, 350))
                },
                pullupLoading: function(t) {
                    var e = this;
                    e.isLoading || (e.isLoading = !0,
                    !1 !== e.pulldown ? e._initPullupRefresh() : this.pullPocket.classList.add("mui-block"),
                    setTimeout(function() {
                        e.pullLoading.classList.add("mui-visibility"),
                        e.pullLoading.classList.remove("mui-hidden"),
                        e.pullCaption.innerHTML = "",
                        e.pullCaption.className = "mui-pull-caption mui-pull-caption-refresh",
                        e.pullCaption.innerHTML = e.options.up.contentrefresh,
                        (t = t || e.options.up.callback) && t.call(e)
                    }, 300))
                },
                endPullupToRefresh: function(t) {
                    var i = this;
                    i.pullLoading && (i.pullLoading.classList.remove("mui-visibility"),
                    i.pullLoading.classList.add("mui-hidden"),
                    i.isLoading = !1,
                    t ? (i.finished = !0,
                    i.pullCaption.className = "mui-pull-caption mui-pull-caption-nomore",
                    i.pullCaption.innerHTML = i.options.up.contentnomore,
                    e.removeEventListener("plusscrollbottom", i),
                    window.removeEventListener("dragup", i)) : (i.pullCaption.className = "mui-pull-caption mui-pull-caption-down",
                    i.pullCaption.innerHTML = i.options.up.contentdown))
                },
                disablePullupToRefresh: function() {
                    this._initPullupRefresh(),
                    this.bottomPocket.className = "mui-pull-bottom-pocket mui-hidden",
                    window.removeEventListener("dragup", this)
                },
                enablePullupToRefresh: function() {
                    this._initPullupRefresh(),
                    this.bottomPocket.classList.remove("mui-hidden"),
                    this.pullCaption.className = "mui-pull-caption mui-pull-caption-down",
                    this.pullCaption.innerHTML = this.options.up.contentdown,
                    e.addEventListener("plusscrollbottom", this),
                    window.addEventListener("dragup", this)
                },
                scrollTo: function(e, i, s) {
                    t.scrollTo(i, s)
                },
                scrollToBottom: function(i) {
                    t.scrollTo(e.documentElement.scrollHeight, i)
                },
                refresh: function(t) {
                    t && this.finished && (this.enablePullupToRefresh(),
                    this.finished = !1)
                }
            }, t.PullRefresh));
            t.fn.pullRefresh = function(s) {
                var n;
                0 === this.length ? (n = e.createElement("div"),
                n.className = "mui-content",
                e.body.appendChild(n)) : n = this[0];
                var o = s;
                s = s || {},
                "string" == typeof s && (s = t.parseJSON(s)),
                !s.webviewId && (s.webviewId = plus.webview.currentWebview().id || plus.webview.currentWebview().getURL());
                var a = null
                  , r = s.webviewId && s.webviewId.replace(/\//g, "_")
                  , l = n.getAttribute("data-pullrefresh-plus-" + r);
                return !(!l && void 0 === o) && (l ? a = t.data[l] : (l = ++t.uuid,
                n.setAttribute("data-pullrefresh-plus-" + r, l),
                e.body.classList.add("mui-plus-pullrefresh"),
                t.data[l] = a = new i(n,s)),
                s.down && s.down.auto ? a._pulldownLoading() : s.up && s.up.auto && a.pullupLoading(),
                a)
            }
        }
    })
}(mui, document),
function(t, e, i, s) {
    var n = t.Class.extend({
        init: function(e, s) {
            this.wrapper = this.element = e,
            this.scroller = this.wrapper.querySelector(".mui-inner-wrap"),
            this.classList = this.wrapper.classList,
            this.scroller && (this.options = t.extend(!0, {
                dragThresholdX: 10,
                scale: .8,
                opacity: .1,
                preventDefaultException: {
                    tagName: /^(INPUT|TEXTAREA|BUTTON|SELECT|VIDEO)$/
                }
            }, s),
            i.body.classList.add("mui-fullscreen"),
            this.refresh(),
            this.initEvent())
        },
        _preventDefaultException: function(t, e) {
            for (var i in e)
                if (e[i].test(t[i]))
                    return !0;
            return !1
        },
        refresh: function(t) {
            this.slideIn = this.classList.contains("mui-slide-in"),
            this.scalable = this.classList.contains("mui-scalable") && !this.slideIn,
            this.scroller = this.wrapper.querySelector(".mui-inner-wrap"),
            this.offCanvasLefts = this.wrapper.querySelectorAll(".mui-off-canvas-left"),
            this.offCanvasRights = this.wrapper.querySelectorAll(".mui-off-canvas-right"),
            t ? t.classList.contains("mui-off-canvas-left") ? this.offCanvasLeft = t : t.classList.contains("mui-off-canvas-right") && (this.offCanvasRight = t) : (this.offCanvasRight = this.wrapper.querySelector(".mui-off-canvas-right"),
            this.offCanvasLeft = this.wrapper.querySelector(".mui-off-canvas-left")),
            this.offCanvasRightWidth = this.offCanvasLeftWidth = 0,
            this.offCanvasLeftSlideIn = this.offCanvasRightSlideIn = !1,
            this.offCanvasRight && (this.offCanvasRightWidth = this.offCanvasRight.offsetWidth,
            this.offCanvasRightSlideIn = this.slideIn && this.offCanvasRight.parentNode === this.wrapper),
            this.offCanvasLeft && (this.offCanvasLeftWidth = this.offCanvasLeft.offsetWidth,
            this.offCanvasLeftSlideIn = this.slideIn && this.offCanvasLeft.parentNode === this.wrapper),
            this.backdrop = this.scroller.querySelector(".mui-off-canvas-backdrop"),
            this.options.dragThresholdX = this.options.dragThresholdX || 10,
            this.visible = !1,
            this.startX = null,
            this.lastX = null,
            this.offsetX = null,
            this.lastTranslateX = null
        },
        handleEvent: function(e) {
            switch (e.type) {
            case t.EVENT_START:
                e.target && !this._preventDefaultException(e.target, this.options.preventDefaultException) && e.preventDefault();
                break;
            case "webkitTransitionEnd":
                e.target === this.scroller && this._dispatchEvent();
                break;
            case "drag":
                var i = e.detail;
                this.startX ? this.lastX = i.center.x : (this.startX = i.center.x,
                this.lastX = this.startX),
                !this.isDragging && Math.abs(this.lastX - this.startX) > this.options.dragThresholdX && ("left" === i.direction || "right" === i.direction) && (this.slideIn ? (this.scroller = this.wrapper.querySelector(".mui-inner-wrap"),
                this.classList.contains("mui-active") ? this.offCanvasRight && this.offCanvasRight.classList.contains("mui-active") ? (this.offCanvas = this.offCanvasRight,
                this.offCanvasWidth = this.offCanvasRightWidth) : (this.offCanvas = this.offCanvasLeft,
                this.offCanvasWidth = this.offCanvasLeftWidth) : "left" === i.direction && this.offCanvasRight ? (this.offCanvas = this.offCanvasRight,
                this.offCanvasWidth = this.offCanvasRightWidth) : "right" === i.direction && this.offCanvasLeft ? (this.offCanvas = this.offCanvasLeft,
                this.offCanvasWidth = this.offCanvasLeftWidth) : this.scroller = null) : this.classList.contains("mui-active") ? "left" === i.direction ? (this.offCanvas = this.offCanvasLeft,
                this.offCanvasWidth = this.offCanvasLeftWidth) : (this.offCanvas = this.offCanvasRight,
                this.offCanvasWidth = this.offCanvasRightWidth) : "right" === i.direction ? (this.offCanvas = this.offCanvasLeft,
                this.offCanvasWidth = this.offCanvasLeftWidth) : (this.offCanvas = this.offCanvasRight,
                this.offCanvasWidth = this.offCanvasRightWidth),
                this.offCanvas && this.scroller && (this.startX = this.lastX,
                this.isDragging = !0,
                t.gestures.session.lockDirection = !0,
                t.gestures.session.startDirection = i.direction,
                this.offCanvas.classList.remove("mui-transitioning"),
                this.scroller.classList.remove("mui-transitioning"),
                this.offsetX = this.getTranslateX(),
                this._initOffCanvasVisible())),
                this.isDragging && (this.updateTranslate(this.offsetX + (this.lastX - this.startX)),
                i.gesture.preventDefault(),
                e.stopPropagation());
                break;
            case "dragend":
                if (this.isDragging) {
                    var i = e.detail
                      , s = i.direction;
                    this.isDragging = !1,
                    this.offCanvas.classList.add("mui-transitioning"),
                    this.scroller.classList.add("mui-transitioning");
                    var n = 0
                      , o = this.getTranslateX();
                    if (this.slideIn) {
                        if (n = o >= 0 ? this.offCanvasRightWidth && o / this.offCanvasRightWidth || 0 : this.offCanvasLeftWidth && o / this.offCanvasLeftWidth || 0,
                        "right" === s && n <= 0 && (n >= -.5 || i.swipe) ? this.openPercentage(100) : "right" === s && n > 0 && (n >= .5 || i.swipe) ? this.openPercentage(0) : "right" === s && n <= -.5 ? this.openPercentage(0) : "right" === s && n > 0 && n <= .5 ? this.openPercentage(-100) : "left" === s && n >= 0 && (n <= .5 || i.swipe) ? this.openPercentage(-100) : "left" === s && n < 0 && (n <= -.5 || i.swipe) ? this.openPercentage(0) : "left" === s && n >= .5 ? this.openPercentage(0) : "left" === s && n >= -.5 && n < 0 ? this.openPercentage(100) : this.openPercentage(0),
                        1 === n || -1 === n || 0 === n)
                            return void this._dispatchEvent()
                    } else {
                        if (0 === (n = o >= 0 ? this.offCanvasLeftWidth && o / this.offCanvasLeftWidth || 0 : this.offCanvasRightWidth && o / this.offCanvasRightWidth || 0))
                            return this.openPercentage(0),
                            void this._dispatchEvent();
                        "right" === s && n >= 0 && (n >= .5 || i.swipe) ? this.openPercentage(100) : "right" === s && n < 0 && (n > -.5 || i.swipe) ? this.openPercentage(0) : "right" === s && n > 0 && n < .5 ? this.openPercentage(0) : "right" === s && n < .5 ? this.openPercentage(-100) : "left" === s && n <= 0 && (n <= -.5 || i.swipe) ? this.openPercentage(-100) : "left" === s && n > 0 && (n <= .5 || i.swipe) ? this.openPercentage(0) : "left" === s && n < 0 && n >= -.5 ? this.openPercentage(0) : "left" === s && n > .5 ? this.openPercentage(100) : this.openPercentage(0),
                        1 !== n && -1 !== n || this._dispatchEvent()
                    }
                }
            }
        },
        _dispatchEvent: function() {
            this.classList.contains("mui-active") ? t.trigger(this.wrapper, "shown", this) : t.trigger(this.wrapper, "hidden", this)
        },
        _initOffCanvasVisible: function() {
            this.visible || (this.visible = !0,
            this.offCanvasLeft && (this.offCanvasLeft.style.visibility = "visible"),
            this.offCanvasRight && (this.offCanvasRight.style.visibility = "visible"))
        },
        initEvent: function() {
            var e = this;
            e.backdrop && e.backdrop.addEventListener("tap", function(t) {
                e.close(),
                t.detail.gesture.preventDefault()
            }),
            this.classList.contains("mui-draggable") && (this.wrapper.addEventListener(t.EVENT_START, this),
            this.wrapper.addEventListener("drag", this),
            this.wrapper.addEventListener("dragend", this)),
            this.wrapper.addEventListener("webkitTransitionEnd", this)
        },
        openPercentage: function(t) {
            var e = t / 100;
            this.slideIn ? (this.offCanvasLeft && t >= 0 ? (e = 0 === e ? -1 : 0,
            this.updateTranslate(this.offCanvasLeftWidth * e),
            this.offCanvasLeft.classList[0 !== t ? "add" : "remove"]("mui-active")) : this.offCanvasRight && t <= 0 && (e = 0 === e ? 1 : 0,
            this.updateTranslate(this.offCanvasRightWidth * e),
            this.offCanvasRight.classList[0 !== t ? "add" : "remove"]("mui-active")),
            this.classList[0 !== t ? "add" : "remove"]("mui-active")) : (this.offCanvasLeft && t >= 0 ? (this.updateTranslate(this.offCanvasLeftWidth * e),
            this.offCanvasLeft.classList[0 !== e ? "add" : "remove"]("mui-active")) : this.offCanvasRight && t <= 0 && (this.updateTranslate(this.offCanvasRightWidth * e),
            this.offCanvasRight.classList[0 !== e ? "add" : "remove"]("mui-active")),
            this.classList[0 !== e ? "add" : "remove"]("mui-active"))
        },
        updateTranslate: function(e) {
            if (e !== this.lastTranslateX) {
                if (this.slideIn) {
                    if (this.offCanvas.classList.contains("mui-off-canvas-right")) {
                        if (e < 0)
                            return void this.setTranslateX(0);
                        if (e > this.offCanvasRightWidth)
                            return void this.setTranslateX(this.offCanvasRightWidth)
                    } else {
                        if (e > 0)
                            return void this.setTranslateX(0);
                        if (e < -this.offCanvasLeftWidth)
                            return void this.setTranslateX(-this.offCanvasLeftWidth)
                    }
                    this.setTranslateX(e)
                } else {
                    if (!this.offCanvasLeft && e > 0 || !this.offCanvasRight && e < 0)
                        return void this.setTranslateX(0);
                    if (this.leftShowing && e > this.offCanvasLeftWidth)
                        return void this.setTranslateX(this.offCanvasLeftWidth);
                    if (this.rightShowing && e < -this.offCanvasRightWidth)
                        return void this.setTranslateX(-this.offCanvasRightWidth);
                    this.setTranslateX(e),
                    e >= 0 ? (this.leftShowing = !0,
                    this.rightShowing = !1,
                    e > 0 && (this.offCanvasLeft && t.each(this.offCanvasLefts, function(t, e) {
                        e === this.offCanvasLeft ? this.offCanvasLeft.style.zIndex = 0 : e.style.zIndex = -1
                    }
                    .bind(this)),
                    this.offCanvasRight && (this.offCanvasRight.style.zIndex = -1))) : (this.rightShowing = !0,
                    this.leftShowing = !1,
                    this.offCanvasRight && t.each(this.offCanvasRights, function(t, e) {
                        e === this.offCanvasRight ? e.style.zIndex = 0 : e.style.zIndex = -1
                    }
                    .bind(this)),
                    this.offCanvasLeft && (this.offCanvasLeft.style.zIndex = -1))
                }
                this.lastTranslateX = e
            }
        },
        setTranslateX: t.animationFrame(function(t) {
            if (this.scroller)
                if (this.scalable && this.offCanvas.parentNode === this.wrapper) {
                    var e = Math.abs(t) / this.offCanvasWidth
                      , i = 1 - (1 - this.options.scale) * e
                      , s = this.options.scale + (1 - this.options.scale) * e
                      , n = (this.options.opacity,
                    this.options.opacity + (1 - this.options.opacity) * e);
                    this.offCanvas.classList.contains("mui-off-canvas-left") ? (this.offCanvas.style.webkitTransformOrigin = "-100%",
                    this.scroller.style.webkitTransformOrigin = "left") : (this.offCanvas.style.webkitTransformOrigin = "200%",
                    this.scroller.style.webkitTransformOrigin = "right"),
                    this.offCanvas.style.opacity = n,
                    this.offCanvas.style.webkitTransform = "translate3d(0,0,0) scale(" + s + ")",
                    this.scroller.style.webkitTransform = "translate3d(" + t + "px,0,0) scale(" + i + ")"
                } else
                    this.slideIn ? this.offCanvas.style.webkitTransform = "translate3d(" + t + "px,0,0)" : this.scroller.style.webkitTransform = "translate3d(" + t + "px,0,0)"
        }),
        getTranslateX: function() {
            if (this.offCanvas) {
                var e = this.slideIn ? this.offCanvas : this.scroller
                  , i = t.parseTranslateMatrix(t.getStyles(e, "webkitTransform"));
                return i && i.x || 0
            }
            return 0
        },
        isShown: function(t) {
            var e = !1;
            if (this.slideIn)
                e = "left" === t ? this.classList.contains("mui-active") && this.wrapper.querySelector(".mui-off-canvas-left.mui-active") : "right" === t ? this.classList.contains("mui-active") && this.wrapper.querySelector(".mui-off-canvas-right.mui-active") : this.classList.contains("mui-active") && (this.wrapper.querySelector(".mui-off-canvas-left.mui-active") || this.wrapper.querySelector(".mui-off-canvas-right.mui-active"));
            else {
                var i = this.getTranslateX();
                e = "right" === t ? this.classList.contains("mui-active") && i < 0 : "left" === t ? this.classList.contains("mui-active") && i > 0 : this.classList.contains("mui-active") && 0 !== i
            }
            return e
        },
        close: function() {
            this._initOffCanvasVisible(),
            this.offCanvas = this.wrapper.querySelector(".mui-off-canvas-right.mui-active") || this.wrapper.querySelector(".mui-off-canvas-left.mui-active"),
            this.offCanvasWidth = this.offCanvas.offsetWidth,
            this.scroller && (this.offCanvas.offsetHeight,
            this.offCanvas.classList.add("mui-transitioning"),
            this.scroller.classList.add("mui-transitioning"),
            this.openPercentage(0))
        },
        show: function(t) {
            return this._initOffCanvasVisible(),
            !this.isShown(t) && (t || (t = this.wrapper.querySelector(".mui-off-canvas-right") ? "right" : "left"),
            "right" === t ? (this.offCanvas = this.offCanvasRight,
            this.offCanvasWidth = this.offCanvasRightWidth) : (this.offCanvas = this.offCanvasLeft,
            this.offCanvasWidth = this.offCanvasLeftWidth),
            this.scroller && (this.offCanvas.offsetHeight,
            this.offCanvas.classList.add("mui-transitioning"),
            this.scroller.classList.add("mui-transitioning"),
            this.openPercentage("left" === t ? 100 : -100)),
            !0)
        },
        toggle: function(t) {
            var e = t;
            t && t.classList && (e = t.classList.contains("mui-off-canvas-left") ? "left" : "right",
            this.refresh(t)),
            this.show(e) || this.close()
        }
    })
      , o = function(t) {
        if (parentNode = t.parentNode,
        parentNode) {
            if (parentNode.classList.contains("mui-off-canvas-wrap"))
                return parentNode;
            if (parentNode = parentNode.parentNode,
            parentNode.classList.contains("mui-off-canvas-wrap"))
                return parentNode
        }
    }
      , a = function(e, s) {
        if ("A" === s.tagName && s.hash) {
            var n = i.getElementById(s.hash.replace("#", ""));
            if (n) {
                var a = o(n);
                if (a)
                    return t.targets._container = a,
                    n
            }
        }
        return !1
    };
    t.registerTarget({
        name: "offcanvas",
        index: 60,
        handle: a,
        target: !1,
        isReset: !1,
        isContinue: !0
    }),
    e.addEventListener("tap", function(e) {
        if (t.targets.offcanvas)
            for (var s = e.target; s && s !== i; s = s.parentNode)
                if ("A" === s.tagName && s.hash && s.hash === "#" + t.targets.offcanvas.id) {
                    e.detail && e.detail.gesture && e.detail.gesture.preventDefault(),
                    t(t.targets._container).offCanvas().toggle(t.targets.offcanvas),
                    t.targets.offcanvas = t.targets._container = null;
                    break
                }
    }),
    t.fn.offCanvas = function(e) {
        var i = [];
        return this.each(function() {
            var s = null
              , a = this;
            a.classList.contains("mui-off-canvas-wrap") || (a = o(a));
            var r = a.getAttribute("data-offCanvas");
            r ? s = t.data[r] : (r = ++t.uuid,
            t.data[r] = s = new n(a,e),
            a.setAttribute("data-offCanvas", r)),
            "show" !== e && "close" !== e && "toggle" !== e || s.toggle(),
            i.push(s)
        }),
        1 === i.length ? i[0] : i
    }
    ,
    t.ready(function() {
        t(".mui-off-canvas-wrap").offCanvas()
    })
}(mui, window, document),
function(t, e) {
    var i = function(t, e) {
        var i = e.className || "";
        return "string" != typeof i && (i = ""),
        !(!i || !~i.indexOf("mui-action")) && (e.classList.contains("mui-action-back") && t.preventDefault(),
        e)
    };
    t.registerTarget({
        name: "action",
        index: 50,
        handle: i,
        target: !1,
        isContinue: !0
    })
}(mui),
function(t, e, i, s) {
    var n = function(t, e) {
        if ("A" === e.tagName && e.hash) {
            var s = i.getElementById(e.hash.replace("#", ""));
            if (s && s.classList.contains("mui-modal"))
                return s
        }
        return !1
    };
    t.registerTarget({
        name: "modal",
        index: 50,
        handle: n,
        target: !1,
        isReset: !1,
        isContinue: !0
    }),
    e.addEventListener("tap", function(e) {
        t.targets.modal && (e.detail.gesture.preventDefault(),
        t.targets.modal.classList.toggle("mui-active"))
    })
}(mui, window, document),
function(t, e, i, s) {
    var n = function(e, s) {
        if ("A" === s.tagName && s.hash) {
            if (t.targets._popover = i.getElementById(s.hash.replace("#", "")),
            t.targets._popover && t.targets._popover.classList.contains("mui-popover"))
                return s;
            t.targets._popover = null
        }
        return !1
    };
    t.registerTarget({
        name: "popover",
        index: 60,
        handle: n,
        target: !1,
        isReset: !1,
        isContinue: !0
    });
    var o, a = function(e) {
        this.removeEventListener("webkitTransitionEnd", a),
        this.addEventListener(t.EVENT_MOVE, t.preventDefault),
        t.trigger(this, "shown", this)
    }, r = function(e) {
        d(this, "none"),
        this.removeEventListener("webkitTransitionEnd", r),
        this.removeEventListener(t.EVENT_MOVE, t.preventDefault),
        t.trigger(this, "hidden", this)
    }, l = function() {
        var e = i.createElement("div");
        return e.classList.add("mui-backdrop"),
        e.addEventListener(t.EVENT_MOVE, t.preventDefault),
        e.addEventListener("tap", function(e) {
            var s = t.targets._popover;
            s && (s.addEventListener("webkitTransitionEnd", r),
            s.classList.remove("mui-active"),
            c(s),
            i.body.setAttribute("style", ""))
        }),
        e
    }(), c = function(e) {
        l.setAttribute("style", "opacity:0"),
        t.targets.popover = t.targets._popover = null,
        o = t.later(function() {
            !e.classList.contains("mui-active") && l.parentNode && l.parentNode === i.body && i.body.removeChild(l)
        }, 350)
    };
    e.addEventListener("tap", function(e) {
        if (t.targets.popover) {
            for (var s = !1, n = e.target; n && n !== i; n = n.parentNode)
                n === t.targets.popover && (s = !0);
            s && (e.detail.gesture.preventDefault(),
            u(t.targets._popover, t.targets.popover))
        }
    });
    var u = function(t, e, s) {
        if (!("show" === s && t.classList.contains("mui-active") || "hide" === s && !t.classList.contains("mui-active"))) {
            o && o.cancel(),
            t.removeEventListener("webkitTransitionEnd", a),
            t.removeEventListener("webkitTransitionEnd", r),
            l.classList.remove("mui-bar-backdrop"),
            l.classList.remove("mui-backdrop-action");
            var n = i.querySelector(".mui-popover.mui-active");
            if (n && (n.addEventListener("webkitTransitionEnd", r),
            n.classList.remove("mui-active"),
            t === n))
                return void c(n);
            var u = !1;
            (t.classList.contains("mui-bar-popover") || t.classList.contains("mui-popover-action")) && (t.classList.contains("mui-popover-action") ? (u = !0,
            l.classList.add("mui-backdrop-action")) : l.classList.add("mui-bar-backdrop")),
            d(t, "block"),
            t.offsetHeight,
            t.classList.add("mui-active"),
            l.setAttribute("style", ""),
            i.body.appendChild(l),
            h(t, e, u),
            l.classList.add("mui-active"),
            t.addEventListener("webkitTransitionEnd", a)
        }
    }
      , d = function(t, e, i, s) {
        var n = t.style;
        void 0 !== e && (n.display = e),
        void 0 !== i && (n.top = i + "px"),
        void 0 !== s && (n.left = s + "px")
    }
      , h = function(s, n, o) {
        if (s && n) {
            if (o)
                return void d(s, "block");
            var a = e.innerWidth
              , r = e.innerHeight
              , l = s.offsetWidth
              , c = s.offsetHeight
              , u = n.offsetWidth
              , h = n.offsetHeight
              , p = t.offset(n)
              , f = s.querySelector(".mui-popover-arrow");
            f || (f = i.createElement("div"),
            f.className = "mui-popover-arrow",
            s.appendChild(f));
            var m = f && f.offsetWidth / 2 || 0
              , g = 0
              , v = 0
              , b = 0
              , w = 0
              , y = s.classList.contains("mui-popover-action") ? 0 : 5
              , L = "top";
            c + m < p.top - e.pageYOffset ? g = p.top - c - m : c + m < r - (p.top - e.pageYOffset) - h ? (L = "bottom",
            g = p.top + h + m) : (L = "middle",
            g = Math.max((r - c) / 2 + e.pageYOffset, 0),
            v = Math.max((a - l) / 2 + e.pageXOffset, 0)),
            "top" === L || "bottom" === L ? (v = u / 2 + p.left - l / 2,
            b = v,
            v < y && (v = y),
            v + l > a && (v = a - l - y),
            f && ("top" === L ? f.classList.add("mui-bottom") : f.classList.remove("mui-bottom"),
            b -= v,
            w = l / 2 - m / 2 + b,
            w = Math.max(Math.min(w, l - 2 * m - 6), 6),
            f.setAttribute("style", "left:" + w + "px"))) : "middle" === L && f.setAttribute("style", "display:none"),
            d(s, "block", g, v)
        }
    };
    t.createMask = function(e) {
        var s = i.createElement("div");
        s.classList.add("mui-backdrop"),
        s.addEventListener(t.EVENT_MOVE, t.preventDefault),
        s.addEventListener("tap", function() {
            n.close()
        });
        var n = [s];
        return n._show = !1,
        n.show = function() {
            return n._show = !0,
            s.setAttribute("style", "opacity:1"),
            i.body.appendChild(s),
            n
        }
        ,
        n._remove = function() {
            return n._show && (n._show = !1,
            s.setAttribute("style", "opacity:0"),
            t.later(function() {
                var t = i.body;
                s.parentNode === t && t.removeChild(s)
            }, 350)),
            n
        }
        ,
        n.close = function() {
            e ? !1 !== e() && n._remove() : n._remove()
        }
        ,
        n
    }
    ,
    t.fn.popover = function() {
        var e = arguments;
        this.each(function() {
            t.targets._popover = this,
            "show" !== e[0] && "hide" !== e[0] && "toggle" !== e[0] || u(this, e[1], e[0])
        })
    }
}(mui, window, document),
function(t, e, i, s, n) {
    var o = function(t, e) {
        return !(!e.classList || !e.classList.contains("mui-control-item") && !e.classList.contains("mui-tab-item")) && (e.parentNode && e.parentNode.classList && e.parentNode.classList.contains("mui-segmented-control-vertical") || t.preventDefault(),
        e)
    };
    t.registerTarget({
        name: "tab",
        index: 80,
        handle: o,
        target: !1
    }),
    e.addEventListener("tap", function(e) {
        var s = t.targets.tab;
        if (s) {
            for (var n, o, a, r = s.parentNode; r && r !== i; r = r.parentNode) {
                if (r.classList.contains("mui-segmented-control")) {
                    n = r.querySelector(".mui-active.mui-control-item");
                    break
                }
                r.classList.contains("mui-bar-tab") && (n = r.querySelector(".mui-active.mui-tab-item"))
            }
            n && n.classList.remove("mui-active");
            var l = s === n;
            if (s && s.classList.add("mui-active"),
            s.hash && (a = i.getElementById(s.hash.replace("#", "")))) {
                if (!a.classList.contains("mui-control-content"))
                    return void s.classList[l ? "remove" : "add"]("mui-active");
                if (!l) {
                    var c = a.parentNode;
                    o = c.querySelectorAll(".mui-control-content.mui-active");
                    for (var u = 0; u < o.length; u++) {
                        var d = o[u];
                        d.parentNode === c && d.classList.remove("mui-active")
                    }
                    a.classList.add("mui-active");
                    for (var h = [], p = c.querySelectorAll(".mui-control-content"), u = 0; u < p.length; u++)
                        p[u].parentNode === c && h.push(p[u]);
                    t.trigger(a, t.eventName("shown", "tab"), {
                        tabNumber: Array.prototype.indexOf.call(h, a)
                    }),
                    e.detail && e.detail.gesture.preventDefault()
                }
            }
        }
    })
}(mui, window, document),
function(t, e, i) {
    var s = function(t, e) {
        return !(!e.classList || !e.classList.contains("mui-switch")) && e
    };
    t.registerTarget({
        name: "toggle",
        index: 100,
        handle: s,
        target: !1
    });
    var n = function(t) {
        this.element = t,
        this.classList = this.element.classList,
        this.handle = this.element.querySelector(".mui-switch-handle"),
        this.init(),
        this.initEvent()
    };
    n.prototype.init = function() {
        this.toggleWidth = this.element.offsetWidth,
        this.handleWidth = this.handle.offsetWidth,
        this.handleX = this.toggleWidth - this.handleWidth - 3
    }
    ,
    n.prototype.initEvent = function() {
        this.element.addEventListener(t.EVENT_START, this),
        this.element.addEventListener("drag", this),
        this.element.addEventListener("swiperight", this),
        this.element.addEventListener(t.EVENT_END, this),
        this.element.addEventListener(t.EVENT_CANCEL, this)
    }
    ,
    n.prototype.handleEvent = function(e) {
        if (!this.classList.contains("mui-disabled"))
            switch (e.type) {
            case t.EVENT_START:
                this.start(e);
                break;
            case "drag":
                this.drag(e);
                break;
            case "swiperight":
                this.swiperight();
                break;
            case t.EVENT_END:
            case t.EVENT_CANCEL:
                this.end(e)
            }
    }
    ,
    n.prototype.start = function(t) {
        this.handle.style.webkitTransitionDuration = this.element.style.webkitTransitionDuration = ".2s",
        this.classList.add("mui-dragging"),
        0 !== this.toggleWidth && 0 !== this.handleWidth || this.init()
    }
    ,
    n.prototype.drag = function(t) {
        var e = t.detail;
        this.isDragging || "left" !== e.direction && "right" !== e.direction || (this.isDragging = !0,
        this.lastChanged = void 0,
        this.initialState = this.classList.contains("mui-active")),
        this.isDragging && (this.setTranslateX(e.deltaX),
        t.stopPropagation(),
        e.gesture.preventDefault())
    }
    ,
    n.prototype.swiperight = function(t) {
        this.isDragging && t.stopPropagation()
    }
    ,
    n.prototype.end = function(e) {
        this.classList.remove("mui-dragging"),
        this.isDragging ? (this.isDragging = !1,
        e.stopPropagation(),
        t.trigger(this.element, "toggle", {
            isActive: this.classList.contains("mui-active")
        })) : this.toggle()
    }
    ,
    n.prototype.toggle = function(e) {
        var i = this.classList;
        this.handle.style.webkitTransitionDuration = this.element.style.webkitTransitionDuration = !1 === e ? "0s" : ".2s",
        i.contains("mui-active") ? (i.remove("mui-active"),
        this.handle.style.webkitTransform = "translate(0,0)") : (i.add("mui-active"),
        this.handle.style.webkitTransform = "translate(" + this.handleX + "px,0)"),
        t.trigger(this.element, "toggle", {
            isActive: this.classList.contains("mui-active")
        })
    }
    ,
    n.prototype.setTranslateX = t.animationFrame(function(t) {
        if (this.isDragging) {
            var e = !1;
            (this.initialState && -t > this.handleX / 2 || !this.initialState && t > this.handleX / 2) && (e = !0),
            this.lastChanged !== e && (e ? (this.handle.style.webkitTransform = "translate(" + (this.initialState ? 0 : this.handleX) + "px,0)",
            this.classList[this.initialState ? "remove" : "add"]("mui-active")) : (this.handle.style.webkitTransform = "translate(" + (this.initialState ? this.handleX : 0) + "px,0)",
            this.classList[this.initialState ? "add" : "remove"]("mui-active")),
            this.lastChanged = e)
        }
    }),
    t.fn.switch = function(e) {
        var i = [];
        return this.each(function() {
            var e = null
              , s = this.getAttribute("data-switch");
            s ? e = t.data[s] : (s = ++t.uuid,
            t.data[s] = new n(this),
            this.setAttribute("data-switch", s)),
            i.push(e)
        }),
        i.length > 1 ? i : i[0]
    }
    ,
    t.ready(function() {
        t(".mui-switch").switch()
    })
}(mui, window),
function(t, e, i) {
    function s(t, e) {
        var i = e ? "removeEventListener" : "addEventListener";
        t[i]("drag", h),
        t[i]("dragend", h),
        t[i]("swiperight", h),
        t[i]("swipeleft", h),
        t[i]("flick", h)
    }
    var n, o, a = isOpened = openedActions = progress = !1, r = sliderActionLeft = sliderActionRight = buttonsLeft = buttonsRight = sliderDirection = sliderRequestAnimationFrame = !1, l = translateX = lastTranslateX = sliderActionLeftWidth = sliderActionRightWidth = 0, c = function(t) {
        t ? o ? o.classList.add("mui-active") : n && n.classList.add("mui-active") : (l && l.cancel(),
        o ? o.classList.remove("mui-active") : n && n.classList.remove("mui-active"))
    }, u = function() {
        if (translateX !== lastTranslateX) {
            if (buttonsRight && buttonsRight.length > 0) {
                progress = translateX / sliderActionRightWidth,
                translateX < -sliderActionRightWidth && (translateX = -sliderActionRightWidth - Math.pow(-translateX - sliderActionRightWidth, .8));
                for (var t = 0, e = buttonsRight.length; t < e; t++) {
                    var i = buttonsRight[t];
                    void 0 === i._buttonOffset && (i._buttonOffset = i.offsetLeft),
                    buttonOffset = i._buttonOffset,
                    d(i, translateX - buttonOffset * (1 + Math.max(progress, -1)))
                }
            }
            if (buttonsLeft && buttonsLeft.length > 0) {
                progress = translateX / sliderActionLeftWidth,
                translateX > sliderActionLeftWidth && (translateX = sliderActionLeftWidth + Math.pow(translateX - sliderActionLeftWidth, .8));
                for (var t = 0, e = buttonsLeft.length; t < e; t++) {
                    var s = buttonsLeft[t];
                    void 0 === s._buttonOffset && (s._buttonOffset = sliderActionLeftWidth - s.offsetLeft - s.offsetWidth),
                    buttonOffset = s._buttonOffset,
                    buttonsLeft.length > 1 && (s.style.zIndex = buttonsLeft.length - t),
                    d(s, translateX + buttonOffset * (1 - Math.min(progress, 1)))
                }
            }
            d(r, translateX),
            lastTranslateX = translateX
        }
        sliderRequestAnimationFrame = requestAnimationFrame(function() {
            u()
        })
    }, d = function(t, e) {
        t && (t.style.webkitTransform = "translate(" + e + "px,0)")
    };
    e.addEventListener(t.EVENT_START, function(e) {
        n && c(!1),
        n = o = !1,
        a = isOpened = openedActions = !1;
        for (var r = e.target, u = !1; r && r !== i; r = r.parentNode)
            if (r.classList) {
                var d = r.classList;
                if (("INPUT" === r.tagName && "radio" !== r.type && "checkbox" !== r.type || "BUTTON" === r.tagName || d.contains("mui-switch") || d.contains("mui-btn") || d.contains("mui-disabled")) && (u = !0),
                d.contains("mui-collapse-content"))
                    break;
                if (d.contains("mui-table-view-cell")) {
                    n = r;
                    var h = n.parentNode.querySelector(".mui-selected");
                    if (!n.parentNode.classList.contains("mui-table-view-radio") && h && h !== n)
                        return t.swipeoutClose(h),
                        void (n = u = !1);
                    if (!n.parentNode.classList.contains("mui-grid-view")) {
                        var p = n.querySelector("a");
                        p && p.parentNode === n && (o = p)
                    }
                    var f = n.querySelector(".mui-slider-handle");
                    f && (s(n),
                    e.stopPropagation()),
                    u || (f ? (l && l.cancel(),
                    l = t.later(function() {
                        c(!0)
                    }, 100)) : c(!0));
                    break
                }
            }
    }),
    e.addEventListener(t.EVENT_MOVE, function(t) {
        c(!1)
    });
    var h = {
        handleEvent: function(t) {
            switch (t.type) {
            case "drag":
                this.drag(t);
                break;
            case "dragend":
                this.dragend(t);
                break;
            case "flick":
                this.flick(t);
                break;
            case "swiperight":
                this.swiperight(t);
                break;
            case "swipeleft":
                this.swipeleft(t)
            }
        },
        drag: function(t) {
            if (n) {
                a || (r = sliderActionLeft = sliderActionRight = buttonsLeft = buttonsRight = sliderDirection = sliderRequestAnimationFrame = !1,
                (r = n.querySelector(".mui-slider-handle")) && (sliderActionLeft = n.querySelector(".mui-slider-left"),
                sliderActionRight = n.querySelector(".mui-slider-right"),
                sliderActionLeft && (sliderActionLeftWidth = sliderActionLeft.offsetWidth,
                buttonsLeft = sliderActionLeft.querySelectorAll(".mui-btn")),
                sliderActionRight && (sliderActionRightWidth = sliderActionRight.offsetWidth,
                buttonsRight = sliderActionRight.querySelectorAll(".mui-btn")),
                n.classList.remove("mui-transitioning"),
                isOpened = n.classList.contains("mui-selected"),
                isOpened && (openedActions = n.querySelector(".mui-slider-left.mui-selected") ? "left" : "right")));
                var e = t.detail
                  , i = e.direction
                  , s = e.angle;
                if ("left" === i && (s > 150 || s < -150) ? (buttonsRight || buttonsLeft && isOpened) && (a = !0) : "right" === i && s > -30 && s < 30 && (buttonsLeft || buttonsRight && isOpened) && (a = !0),
                a) {
                    t.stopPropagation(),
                    t.detail.gesture.preventDefault();
                    var o = t.detail.deltaX;
                    if (isOpened && ("right" === openedActions ? o -= sliderActionRightWidth : o += sliderActionLeftWidth),
                    o > 0 && !buttonsLeft || o < 0 && !buttonsRight) {
                        if (!isOpened)
                            return;
                        o = 0
                    }
                    o < 0 ? sliderDirection = "toLeft" : o > 0 ? sliderDirection = "toRight" : sliderDirection || (sliderDirection = "toLeft"),
                    sliderRequestAnimationFrame || u(),
                    translateX = o
                }
            }
        },
        flick: function(t) {
            a && t.stopPropagation()
        },
        swipeleft: function(t) {
            a && t.stopPropagation()
        },
        swiperight: function(t) {
            a && t.stopPropagation()
        },
        dragend: function(e) {
            if (a) {
                e.stopPropagation(),
                sliderRequestAnimationFrame && (cancelAnimationFrame(sliderRequestAnimationFrame),
                sliderRequestAnimationFrame = null);
                var i = e.detail;
                a = !1;
                var s = "close"
                  , o = "toLeft" === sliderDirection ? sliderActionRightWidth : sliderActionLeftWidth;
                (i.swipe || Math.abs(translateX) > o / 2) && (isOpened ? "left" === i.direction && "right" === openedActions ? s = "open" : "right" === i.direction && "left" === openedActions && (s = "open") : s = "open"),
                n.classList.add("mui-transitioning");
                var l;
                if ("open" === s) {
                    var c = "toLeft" === sliderDirection ? -o : o;
                    if (d(r, c),
                    void 0 !== (l = "toLeft" === sliderDirection ? buttonsRight : buttonsLeft)) {
                        for (var u = null, h = 0; h < l.length; h++)
                            u = l[h],
                            d(u, c);
                        u.parentNode.classList.add("mui-selected"),
                        n.classList.add("mui-selected"),
                        isOpened || t.trigger(n, "toLeft" === sliderDirection ? "slideleft" : "slideright")
                    }
                } else
                    d(r, 0),
                    sliderActionLeft && sliderActionLeft.classList.remove("mui-selected"),
                    sliderActionRight && sliderActionRight.classList.remove("mui-selected"),
                    n.classList.remove("mui-selected");
                var p;
                if (buttonsLeft && buttonsLeft.length > 0 && buttonsLeft !== l)
                    for (var h = 0, f = buttonsLeft.length; h < f; h++) {
                        var m = buttonsLeft[h];
                        p = m._buttonOffset,
                        void 0 === p && (m._buttonOffset = sliderActionLeftWidth - m.offsetLeft - m.offsetWidth),
                        d(m, p)
                    }
                if (buttonsRight && buttonsRight.length > 0 && buttonsRight !== l)
                    for (var h = 0, f = buttonsRight.length; h < f; h++) {
                        var g = buttonsRight[h];
                        p = g._buttonOffset,
                        void 0 === p && (g._buttonOffset = g.offsetLeft),
                        d(g, -p)
                    }
            }
        }
    };
    t.swipeoutOpen = function(e, i) {
        if (e) {
            var s = e.classList;
            if (!s.contains("mui-selected")) {
                i || (i = e.querySelector(".mui-slider-right") ? "right" : "left");
                var n = e.querySelector(t.classSelector(".slider-" + i));
                if (n) {
                    n.classList.add("mui-selected"),
                    s.add("mui-selected"),
                    s.remove("mui-transitioning");
                    for (var o, a = n.querySelectorAll(".mui-btn"), r = n.offsetWidth, l = "right" === i ? -r : r, c = a.length, u = 0; u < c; u++)
                        o = a[u],
                        "right" === i ? d(o, -o.offsetLeft) : d(o, r - o.offsetWidth - o.offsetLeft);
                    s.add("mui-transitioning");
                    for (var u = 0; u < c; u++)
                        d(a[u], l);
                    d(e.querySelector(".mui-slider-handle"), l)
                }
            }
        }
    }
    ,
    t.swipeoutClose = function(e) {
        if (e) {
            var i = e.classList;
            if (i.contains("mui-selected")) {
                var s = e.querySelector(".mui-slider-right.mui-selected") ? "right" : "left"
                  , n = e.querySelector(t.classSelector(".slider-" + s));
                if (n) {
                    n.classList.remove("mui-selected"),
                    i.remove("mui-selected"),
                    i.add("mui-transitioning");
                    var o, a = n.querySelectorAll(".mui-btn"), r = n.offsetWidth, l = a.length;
                    d(e.querySelector(".mui-slider-handle"), 0);
                    for (var c = 0; c < l; c++)
                        o = a[c],
                        "right" === s ? d(o, -o.offsetLeft) : d(o, r - o.offsetWidth - o.offsetLeft)
                }
            }
        }
    }
    ,
    e.addEventListener(t.EVENT_END, function(t) {
        n && (c(!1),
        r && s(n, !0))
    }),
    e.addEventListener(t.EVENT_CANCEL, function(t) {
        n && (c(!1),
        r && s(n, !0))
    });
    var p = function(e) {
        var i = e.target && e.target.type || "";
        if ("radio" !== i && "checkbox" !== i) {
            var s = n.classList;
            if (s.contains("mui-radio")) {
                var o = n.querySelector("input[type=radio]");
                o && (o.disabled || o.readOnly || (o.checked = !o.checked,
                t.trigger(o, "change")))
            } else if (s.contains("mui-checkbox")) {
                var o = n.querySelector("input[type=checkbox]");
                o && (o.disabled || o.readOnly || (o.checked = !o.checked,
                t.trigger(o, "change")))
            }
        }
    };
    e.addEventListener(t.EVENT_CLICK, function(t) {
        n && n.classList.contains("mui-collapse") && t.preventDefault()
    }),
    e.addEventListener("doubletap", function(t) {
        n && p(t)
    });
    var f = /^(INPUT|TEXTAREA|BUTTON|SELECT)$/;
    e.addEventListener("tap", function(e) {
        if (n) {
            var i = !1
              , s = n.classList
              , o = n.parentNode;
            if (o && o.classList.contains("mui-table-view-radio")) {
                if (s.contains("mui-selected"))
                    return;
                var a = o.querySelector("li.mui-selected");
                return a && a.classList.remove("mui-selected"),
                s.add("mui-selected"),
                void t.trigger(n, "selected", {
                    el: n
                })
            }
            if (s.contains("mui-collapse") && !n.parentNode.classList.contains("mui-unfold")) {
                if (f.test(e.target.tagName) || e.detail.gesture.preventDefault(),
                !s.contains("mui-active")) {
                    var r = n.parentNode.querySelector(".mui-collapse.mui-active");
                    r && r.classList.remove("mui-active"),
                    i = !0
                }
                s.toggle("mui-active"),
                i && t.trigger(n, "expand")
            } else
                p(e)
        }
    })
}(mui, window, document),
function(t, e) {
    t.alert = function(i, s, n, o) {
        if (t.os.plus) {
            if (void 0 === i)
                return;
            "function" == typeof s ? (o = s,
            s = null,
            n = "确定") : "function" == typeof n && (o = n,
            n = null),
            t.plusReady(function() {
                plus.nativeUI.alert(i, o, s, n)
            })
        } else
            e.alert(i)
    }
}(mui, window),
function(t, e) {
    t.confirm = function(i, s, n, o) {
        if (t.os.plus) {
            if (void 0 === i)
                return;
            "function" == typeof s ? (o = s,
            s = null,
            n = null) : "function" == typeof n && (o = n,
            n = null),
            t.plusReady(function() {
                plus.nativeUI.confirm(i, o, s, n)
            })
        } else
            o(e.confirm(i) ? {
                index: 0
            } : {
                index: 1
            })
    }
}(mui, window),
function(t, e) {
    t.prompt = function(i, s, n, o, a) {
        if (t.os.plus) {
            if ("undefined" == typeof message)
                return;
            "function" == typeof s ? (a = s,
            s = null,
            n = null,
            o = null) : "function" == typeof n ? (a = n,
            n = null,
            o = null) : "function" == typeof o && (a = o,
            o = null),
            t.plusReady(function() {
                plus.nativeUI.prompt(i, a, n, s, o)
            })
        } else {
            var r = e.prompt(i);
            a(r ? {
                index: 0,
                value: r
            } : {
                index: 1,
                value: ""
            })
        }
    }
}(mui, window),
function(t, e) {
    t.toast = function(e, i) {
        var s = {
            long: 3500,
            short: 2e3
        };
        if (i = t.extend({
            duration: "short"
        }, i || {}),
        !t.os.plus || "div" === i.type) {
            "number" == typeof i.duration ? duration = i.duration > 0 ? i.duration : s.short : duration = s[i.duration],
            duration || (duration = s.short);
            var n = document.createElement("div");
            return n.classList.add("mui-toast-container"),
            n.innerHTML = '<div class="mui-toast-message">' + e + "</div>",
            n.addEventListener("webkitTransitionEnd", function() {
                n.classList.contains("mui-active") || (n.parentNode.removeChild(n),
                n = null)
            }),
            n.addEventListener("click", function() {
                n.parentNode.removeChild(n),
                n = null
            }),
            document.body.appendChild(n),
            n.offsetHeight,
            n.classList.add("mui-active"),
            setTimeout(function() {
                n && n.classList.remove("mui-active")
            }, duration),
            {
                isVisible: function() {
                    return !!n
                }
            }
        }
        t.plusReady(function() {
            plus.nativeUI.toast(e, {
                verticalAlign: "bottom",
                duration: i.duration
            })
        })
    }
}(mui, window),
function(t, e, i) {
    var s = "mui-popup-backdrop"
      , s = "mui-popup-backdrop"
      , n = []
      , o = function() {
        var e = i.createElement("div");
        return e.classList.add(s),
        e.addEventListener(t.EVENT_MOVE, t.preventDefault),
        e.addEventListener("webkitTransitionEnd", function() {
            this.classList.contains("mui-active") || e.parentNode && e.parentNode.removeChild(e)
        }),
        e
    }()
      , a = function(t) {
        return '<div class="mui-popup-input"><input type="text" autofocus placeholder="' + (t || "") + '"/></div>'
    }
      , r = function(t, e, i) {
        return '<div class="mui-popup-inner"><div class="mui-popup-title">' + e + '</div><div class="mui-popup-text">' + t.replace(/\r\n/g, "<br/>").replace(/\n/g, "<br/>") + "</div>" + (i || "") + "</div>"
    }
      , l = function(t) {
        for (var e = t.length, i = [], s = 0; s < e; s++)
            i.push('<span class="mui-popup-button' + (s === e - 1 ? " mui-popup-button-bold" : "") + '">' + t[s] + "</span>");
        return '<div class="mui-popup-buttons">' + i.join("") + "</div>"
    }
      , c = function(e, s) {
        var a = i.createElement("div");
        a.className = "mui-popup",
        a.innerHTML = e;
        var r = function() {
            a.parentNode && a.parentNode.removeChild(a),
            a = null
        };
        a.addEventListener(t.EVENT_MOVE, t.preventDefault),
        a.addEventListener("webkitTransitionEnd", function(t) {
            a && t.target === a && a.classList.contains("mui-popup-out") && r()
        }),
        a.style.display = "block",
        i.body.appendChild(a),
        a.offsetHeight,
        a.classList.add("mui-popup-in"),
        o.classList.contains("mui-active") || (o.style.display = "block",
        i.body.appendChild(o),
        o.offsetHeight,
        o.classList.add("mui-active"));
        var l = t.qsa(".mui-popup-button", a)
          , c = a.querySelector(".mui-popup-input input")
          , u = {
            element: a,
            close: function(t, e) {
                if (a) {
                    if (!1 === (s && s({
                        index: t || 0,
                        value: c && c.value || ""
                    })))
                        return;
                    !1 !== e ? (a.classList.remove("mui-popup-in"),
                    a.classList.add("mui-popup-out")) : r(),
                    n.pop(),
                    n.length ? n[n.length - 1].show(e) : o.classList.remove("mui-active")
                }
            }
        }
          , d = function(t) {
            u.close(l.indexOf(t.target))
        };
        return t(a).on("tap", ".mui-popup-button", d),
        n.length && n[n.length - 1].hide(),
        n.push({
            close: u.close,
            show: function(t) {
                a.style.display = "block",
                a.offsetHeight,
                a.classList.add("mui-popup-in")
            },
            hide: function() {
                a.style.display = "none",
                a.classList.remove("mui-popup-in")
            }
        }),
        u
    }
      , u = function(e, i, s, n, o) {
        if (void 0 !== e)
            return "function" == typeof i ? (n = i,
            o = s,
            i = null,
            s = null) : "function" == typeof s && (o = n,
            n = s,
            s = null),
            t.os.plus && "div" !== o ? plus.nativeUI.alert(e, n, i || "提示", s || "确定") : c(r(e, i || "提示") + l([s || "确定"]), n)
    }
      , d = function(e, i, s, n, o) {
        if (void 0 !== e)
            return "function" == typeof i ? (n = i,
            o = s,
            i = null,
            s = null) : "function" == typeof s && (o = n,
            n = s,
            s = null),
            t.os.plus && "div" !== o ? plus.nativeUI.confirm(e, n, i, s || ["取消", "确认"]) : c(r(e, i || "提示") + l(s || ["取消", "确认"]), n)
    }
      , h = function(e, i, s, n, o, u) {
        if (void 0 !== e)
            return "function" == typeof i ? (o = i,
            u = s,
            i = null,
            s = null,
            n = null) : "function" == typeof s ? (o = s,
            u = n,
            s = null,
            n = null) : "function" == typeof n && (u = o,
            o = n,
            n = null),
            t.os.plus && "div" !== u ? plus.nativeUI.prompt(e, o, s || "提示", i, n || ["取消", "确认"]) : c(r(e, s || "提示", a(i)) + l(n || ["取消", "确认"]), o)
    }
      , p = function() {
        return !!n.length && (n[n.length - 1].close(),
        !0)
    }
      , f = function() {
        for (; n.length; )
            n[n.length - 1].close()
    };
    t.closePopup = p,
    t.closePopups = f,
    t.alert = u,
    t.confirm = d,
    t.prompt = h
}(mui, window, document),
function(t, e) {
    var i = function(e) {
        if (e = t(e || "body"),
        0 !== e.length) {
            if (e = e[0],
            e.classList.contains("mui-progressbar"))
                return e;
            var i = e.querySelectorAll(".mui-progressbar");
            if (i)
                for (var s = 0, n = i.length; s < n; s++) {
                    var o = i[s];
                    if (o.parentNode === e)
                        return o
                }
        }
    }
      , s = function(i, s, n) {
        if ("number" == typeof i && (n = s,
        s = i,
        i = "body"),
        i = t(i || "body"),
        0 !== i.length) {
            i = i[0];
            var a;
            if (i.classList.contains("mui-progressbar"))
                a = i;
            else {
                var r = i.querySelectorAll(".mui-progressbar:not(.mui-progressbar-out)");
                if (r)
                    for (var l = 0, c = r.length; l < c; l++) {
                        var u = r[l];
                        if (u.parentNode === i) {
                            a = u;
                            break
                        }
                    }
                a ? a.classList.add("mui-progressbar-in") : (a = e.createElement("span"),
                a.className = "mui-progressbar mui-progressbar-in" + (void 0 !== s ? "" : " mui-progressbar-infinite") + (n ? " mui-progressbar-" + n : ""),
                void 0 !== s && (a.innerHTML = "<span></span>"),
                i.appendChild(a))
            }
            return s && o(i, s),
            a
        }
    }
      , n = function(t) {
        var e = i(t);
        if (e) {
            var s = e.classList;
            s.contains("mui-progressbar-in") && !s.contains("mui-progressbar-out") && (s.remove("mui-progressbar-in"),
            s.add("mui-progressbar-out"),
            e.addEventListener("webkitAnimationEnd", function() {
                e.parentNode && e.parentNode.removeChild(e),
                e = null
            }))
        }
    }
      , o = function(t, e, s) {
        "number" == typeof t && (s = e,
        e = t,
        t = !1);
        var n = i(t);
        if (n && !n.classList.contains("mui-progressbar-infinite")) {
            e && (e = Math.min(Math.max(e, 0), 100)),
            n.offsetHeight;
            var o = n.querySelector("span");
            if (o) {
                var a = o.style;
                a.webkitTransform = "translate3d(" + (-100 + e) + "%,0,0)",
                a.webkitTransitionDuration = void 0 !== s ? s + "ms" : ""
            }
            return n
        }
    };
    t.fn.progressbar = function(t) {
        var e = [];
        return t = t || {},
        this.each(function() {
            var i = this
              , a = i.mui_plugin_progressbar;
            a ? t && a.setOptions(t) : i.mui_plugin_progressbar = a = {
                options: t,
                setOptions: function(t) {
                    this.options = t
                },
                show: function() {
                    return s(i, this.options.progress, this.options.color)
                },
                setProgress: function(t) {
                    return o(i, t)
                },
                hide: function() {
                    return n(i)
                }
            },
            e.push(a)
        }),
        1 === e.length ? e[0] : e
    }
}(mui, document),
function(t, e, i) {
    var s = function(t) {
        for (; t && t !== i; t = t.parentNode)
            if (t.classList && t.classList.contains("mui-input-row"))
                return t;
        return null
    }
      , n = function(t, e) {
        this.element = t,
        this.options = e || {
            actions: "clear"
        },
        ~this.options.actions.indexOf("slider") ? (this.sliderActionClass = "mui-tooltip mui-hidden",
        this.sliderActionSelector = ".mui-tooltip") : (~this.options.actions.indexOf("clear") && (this.clearActionClass = "mui-icon mui-icon-clear mui-hidden",
        this.clearActionSelector = ".mui-icon-clear"),
        ~this.options.actions.indexOf("speech") && (this.speechActionClass = "mui-icon mui-icon-speech",
        this.speechActionSelector = ".mui-icon-speech"),
        ~this.options.actions.indexOf("search") && (this.searchActionClass = "mui-placeholder",
        this.searchActionSelector = ".mui-placeholder"),
        ~this.options.actions.indexOf("password") && (this.passwordActionClass = "mui-icon mui-icon-eye",
        this.passwordActionSelector = ".mui-icon-eye")),
        this.init()
    };
    n.prototype.init = function() {
        this.initAction(),
        this.initElementEvent()
    }
    ,
    n.prototype.initAction = function() {
        var e = this
          , i = e.element.parentNode;
        i && (e.sliderActionClass ? e.sliderAction = e.createAction(i, e.sliderActionClass, e.sliderActionSelector) : (e.searchActionClass && (e.searchAction = e.createAction(i, e.searchActionClass, e.searchActionSelector),
        e.searchAction.addEventListener("tap", function(i) {
            t.focus(e.element),
            i.stopPropagation()
        })),
        e.speechActionClass && (e.speechAction = e.createAction(i, e.speechActionClass, e.speechActionSelector),
        e.speechAction.addEventListener("click", t.stopPropagation),
        e.speechAction.addEventListener("tap", function(t) {
            e.speechActionClick(t)
        })),
        e.clearActionClass && (e.clearAction = e.createAction(i, e.clearActionClass, e.clearActionSelector),
        e.clearAction.addEventListener("tap", function(t) {
            e.clearActionClick(t)
        })),
        e.passwordActionClass && (e.passwordAction = e.createAction(i, e.passwordActionClass, e.passwordActionSelector),
        e.passwordAction.addEventListener("tap", function(t) {
            e.passwordActionClick(t)
        }))))
    }
    ,
    n.prototype.createAction = function(t, e, s) {
        var n = t.querySelector(s);
        if (!n) {
            var n = i.createElement("span");
            n.className = e,
            e === this.searchActionClass && (n.innerHTML = '<span class="mui-icon mui-icon-search"></span><span>' + this.element.getAttribute("placeholder") + "</span>",
            this.element.setAttribute("placeholder", ""),
            this.element.value.trim() && t.classList.add("mui-active")),
            t.insertBefore(n, this.element.nextSibling)
        }
        return n
    }
    ,
    n.prototype.initElementEvent = function() {
        var e = this.element;
        if (this.sliderActionClass) {
            var i = this.sliderAction
              , s = null
              , n = function() {
                i.classList.remove("mui-hidden");
                var t = e.offsetLeft
                  , n = e.offsetWidth - 28
                  , o = i.offsetWidth
                  , a = Math.abs(e.max - e.min)
                  , r = n / a * Math.abs(e.value - e.min);
                i.style.left = 14 + t + r - o / 2 + "px",
                i.innerText = e.value,
                s && clearTimeout(s),
                s = setTimeout(function() {
                    i.classList.add("mui-hidden")
                }, 1e3)
            };
            e.addEventListener("input", n),
            e.addEventListener("tap", n),
            e.addEventListener(t.EVENT_MOVE, function(t) {
                t.stopPropagation()
            })
        } else {
            if (this.clearActionClass) {
                var o = this.clearAction;
                if (!o)
                    return;
                t.each(["keyup", "change", "input", "focus", "cut", "paste"], function(t, i) {
                    !function(t) {
                        e.addEventListener(t, function() {
                            o.classList[e.value.trim() ? "remove" : "add"]("mui-hidden")
                        })
                    }(i)
                }),
                e.addEventListener("blur", function() {
                    o.classList.add("mui-hidden")
                })
            }
            this.searchActionClass && (e.addEventListener("focus", function() {
                e.parentNode.classList.add("mui-active")
            }),
            e.addEventListener("blur", function() {
                e.value.trim() || e.parentNode.classList.remove("mui-active")
            }))
        }
    }
    ,
    n.prototype.setPlaceholder = function(t) {
        if (this.searchActionClass) {
            var e = this.element.parentNode.querySelector(".mui-placeholder");
            e && (e.getElementsByTagName("span")[1].innerText = t)
        } else
            this.element.setAttribute("placeholder", t)
    }
    ,
    n.prototype.passwordActionClick = function(t) {
        "text" === this.element.type ? this.element.type = "password" : this.element.type = "text",
        this.passwordAction.classList.toggle("mui-active"),
        t.preventDefault()
    }
    ,
    n.prototype.clearActionClick = function(e) {
        var i = this;
        i.element.value = "",
        t.focus(i.element),
        i.clearAction.classList.add("mui-hidden"),
        e.preventDefault()
    }
    ,
    n.prototype.speechActionClick = function(s) {
        if (e.plus) {
            var n = this
              , o = n.element.value;
            n.element.value = "",
            i.body.classList.add("mui-focusin"),
            plus.speech.startRecognize({
                engine: "iFly"
            }, function(e) {
                n.element.value += e,
                t.focus(n.element),
                plus.speech.stopRecognize(),
                t.trigger(n.element, "recognized", {
                    value: n.element.value
                }),
                o !== n.element.value && (t.trigger(n.element, "change"),
                t.trigger(n.element, "input"))
            }, function(t) {
                i.body.classList.remove("mui-focusin")
            })
        } else
            alert("only for 5+");
        s.preventDefault()
    }
    ,
    t.fn.input = function(e) {
        var i = [];
        return this.each(function() {
            var e = null
              , o = []
              , a = s(this.parentNode);
            if ("range" === this.type && a.classList.contains("mui-input-range"))
                o.push("slider");
            else {
                var r = this.classList;
                r.contains("mui-input-clear") && o.push("clear"),
                t.os.android && t.os.stream || !r.contains("mui-input-speech") || o.push("speech"),
                r.contains("mui-input-password") && o.push("password"),
                "search" === this.type && a.classList.contains("mui-search") && o.push("search")
            }
            var l = this.getAttribute("data-input-" + o[0]);
            if (l)
                e = t.data[l];
            else {
                l = ++t.uuid,
                e = t.data[l] = new n(this,{
                    actions: o.join(",")
                });
                for (var c = 0, u = o.length; c < u; c++)
                    this.setAttribute("data-input-" + o[c], l)
            }
            i.push(e)
        }),
        1 === i.length ? i[0] : i
    }
    ,
    t.ready(function() {
        t(".mui-input-row input").input()
    })
}(mui, window, document),
function(t, e) {
    var i = /^rgba\((\d{1,3}),\s*(\d{1,3}),\s*(\d{1,3}),\s*(\d{1,3})\)$/
      , s = function(t) {
        var e = t.match(i);
        return e && 5 === e.length ? [e[1], e[2], e[3], e[4]] : []
    }
      , n = function(e, i) {
        this.element = e,
        this.options = t.extend({
            top: 0,
            offset: 150,
            duration: 16
        }, i || {}),
        this._style = this.element.style,
        this._bgColor = this._style.backgroundColor;
        var n = s(mui.getStyles(this.element, "backgroundColor"));
        if (!n.length)
            throw new Error("元素背景颜色必须为RGBA");
        this._R = n[0],
        this._G = n[1],
        this._B = n[2],
        this._A = n[3],
        this._bufferFn = t.buffer(this.handleScroll, this.options.duration, this),
        this.initEvent()
    };
    n.prototype.initEvent = function() {
        e.addEventListener("scroll", this._bufferFn),
        e.addEventListener(t.EVENT_MOVE, this._bufferFn)
    }
    ,
    n.prototype.handleScroll = function() {
        this._style.backgroundColor = "rgba(" + this._R + "," + this._G + "," + this._B + "," + (e.scrollY - this.options.top) / this.options.offset + ")"
    }
    ,
    n.prototype.destory = function() {
        e.removeEventListener("scroll", this._bufferFn),
        e.removeEventListener(t.EVENT_MOVE, this._bufferFn),
        this.element.style.backgroundColor = this._bgColor,
        this.element.mui_plugin_transparent = null
    }
    ,
    t.fn.transparent = function(t) {
        t = t || {};
        var e = [];
        return this.each(function() {
            var i = this.mui_plugin_transparent;
            if (!i) {
                var s = this.getAttribute("data-top")
                  , o = this.getAttribute("data-offset")
                  , a = this.getAttribute("data-duration");
                null !== s && void 0 === t.top && (t.top = s),
                null !== o && void 0 === t.offset && (t.offset = o),
                null !== a && void 0 === t.duration && (t.duration = a),
                i = this.mui_plugin_transparent = new n(this,t)
            }
            e.push(i)
        }),
        1 === e.length ? e[0] : e
    }
    ,
    t.ready(function() {
        t(".mui-bar-transparent").transparent()
    })
}(mui, window),
function(t) {
    var e = "ontouchstart"in document
      , i = e ? "tap" : "click"
      , s = t.Numbox = t.Class.extend({
        init: function(e, i) {
            var s = this;
            if (!e)
                throw "构造 numbox 时缺少容器元素";
            s.holder = e,
            i = i || {},
            i.step = parseInt(i.step || 1),
            s.options = i,
            s.input = t.qsa(".mui-input-numbox,.mui-numbox-input", s.holder)[0],
            s.plus = t.qsa(".mui-btn-numbox-plus,.mui-numbox-btn-plus", s.holder)[0],
            s.minus = t.qsa(".mui-btn-numbox-minus,.mui-numbox-btn-minus", s.holder)[0],
            s.checkValue(),
            s.initEvent()
        },
        initEvent: function() {
            var e = this;
            e.plus.addEventListener(i, function(i) {
                var s = parseInt(e.input.value) + e.options.step;
                e.input.value = s.toString(),
                t.trigger(e.input, "change", null)
            }),
            e.minus.addEventListener(i, function(i) {
                var s = parseInt(e.input.value) - e.options.step;
                e.input.value = s.toString(),
                t.trigger(e.input, "change", null)
            }),
            e.input.addEventListener("change", function(i) {
                e.checkValue();
                var s = parseInt(e.input.value);
                t.trigger(e.holder, "change", {
                    value: s
                })
            })
        },
        getValue: function() {
            var t = this;
            return parseInt(t.input.value)
        },
        checkValue: function() {
            var t = this
              , e = t.input.value;
            if (null == e || "" == e || isNaN(e))
                t.input.value = t.options.min || 0,
                t.minus.disabled = null != t.options.min;
            else {
                var e = parseInt(e);
                null != t.options.max && !isNaN(t.options.max) && e >= parseInt(t.options.max) ? (e = t.options.max,
                t.plus.disabled = !0) : t.plus.disabled = !1,
                null != t.options.min && !isNaN(t.options.min) && e <= parseInt(t.options.min) ? (e = t.options.min,
                t.minus.disabled = !0) : t.minus.disabled = !1,
                t.input.value = e
            }
        },
        setOption: function(t, e) {
            this.options[t] = e
        },
        setValue: function(t) {
            this.input.value = t,
            this.checkValue()
        }
    });
    t.fn.numbox = function(t) {
        return this.each(function(t, e) {
            if (!e.numbox)
                if (n)
                    e.numbox = new s(e,n);
                else {
                    var i = e.getAttribute("data-numbox-options")
                      , n = i ? JSON.parse(i) : {};
                    n.step = e.getAttribute("data-numbox-step") || n.step,
                    n.min = e.getAttribute("data-numbox-min") || n.min,
                    n.max = e.getAttribute("data-numbox-max") || n.max,
                    e.numbox = new s(e,n)
                }
        }),
        this[0] ? this[0].numbox : null
    }
    ,
    t.ready(function() {
        t(".mui-numbox").numbox()
    })
}(mui),
function(t, e, i) {
    var s = {
        loadingText: "Loading...",
        loadingIcon: "mui-spinner mui-spinner-white",
        loadingIconPosition: "left"
    }
      , n = function(e, i) {
        this.element = e,
        this.options = t.extend({}, s, i),
        this.options.loadingText || (this.options.loadingText = s.loadingText),
        null === this.options.loadingIcon && (this.options.loadingIcon = "mui-spinner",
        "rgb(255, 255, 255)" === t.getStyles(this.element, "color") && (this.options.loadingIcon += " mui-spinner-white")),
        this.isInput = "INPUT" === this.element.tagName,
        this.resetHTML = this.isInput ? this.element.value : this.element.innerHTML,
        this.state = ""
    };
    n.prototype.loading = function() {
        this.setState("loading")
    }
    ,
    n.prototype.reset = function() {
        this.setState("reset")
    }
    ,
    n.prototype.setState = function(t) {
        if (this.state === t)
            return !1;
        if (this.state = t,
        "reset" === t)
            this.element.disabled = !1,
            this.element.classList.remove("mui-disabled"),
            this.setHtml(this.resetHTML);
        else if ("loading" === t) {
            this.element.disabled = !0,
            this.element.classList.add("mui-disabled");
            var e = this.isInput ? this.options.loadingText : "<span>" + this.options.loadingText + "</span>";
            this.options.loadingIcon && !this.isInput && ("right" === this.options.loadingIconPosition ? e += '&nbsp;<span class="' + this.options.loadingIcon + '"></span>' : e = '<span class="' + this.options.loadingIcon + '"></span>&nbsp;' + e),
            this.setHtml(e)
        }
    }
    ,
    n.prototype.setHtml = function(t) {
        this.isInput ? this.element.value = t : this.element.innerHTML = t
    }
    ,
    t.fn.button = function(t) {
        var e = [];
        return this.each(function() {
            var i = this.mui_plugin_button;
            if (!i) {
                var s = this.getAttribute("data-loading-text")
                  , o = this.getAttribute("data-loading-icon")
                  , a = this.getAttribute("data-loading-icon-position");
                this.mui_plugin_button = i = new n(this,{
                    loadingText: s,
                    loadingIcon: o,
                    loadingIconPosition: a
                })
            }
            "loading" !== t && "reset" !== t || i.setState(t),
            e.push(i)
        }),
        1 === e.length ? e[0] : e
    }
}(mui, window, document);
var envConfig = {
    env: "prod",
    count: "https://ad.gogobids.com/",
    landing: "https://ad.gogobids.com/",
    imgHost: "https://qnimg.gogobids.com/",
    api: "https://gogobids.com/"
}
  , doc = document
  , win = window
  , wrap = doc.querySelector(".wrap")
  , api = {
    env: envConfig.env
}
  , ua = navigator.userAgent.toLowerCase()
  , _vid = _getingVid = 0
  , _agreement = !0
  , _p = {
    init: function() {
        _p.isInApp() || win._canPb || (_p.getWxOpenId(),
        _p.getConfig(),
        _p.getBidReturn(),
        _p.getCity(),
        _p.getUserInfo(),
        _p.doActivate(),
        _p.newGuide(),
        _p.getNotice(),
        _p.getFulladdr())
    },
    tabbar: function(t) {
        var e = ["index.html", "latestdeal.html", "category.html", "mycenter.html"]
          , i = ["icon-home", "icon-deal", "icon-category", "icon-user"]
          , s = ["首页", "最新成交", "分类", "我"]
          , n = ""
          , t = t || 0;
        mui.each(e, function(e, o) {
            n += e == t ? '<a href="' + o + '" class="active"><i class="' + i[e] + '"></i><span>' + s[e] + "</span></a>" : '<a href="' + o + '"><i class="' + i[e] + '"></i><span>' + s[e] + "</span></a>"
        }),
        doc.getElementById("tabbar").innerHTML = n
    },
    loading: function() {
        var t = doc.querySelector(".ui-loading");
        if (t)
            t.style.display = "block";
        else {
            var e = doc.createElement("div");
            e.className = "ui-loading",
            e.innerHTML = "<div><i></i><i></i><i></i></div>"
//          doc.body.appendChild(e)
        }
    },
    removeLoading: function() {
        var t = doc.querySelector(".ui-loading");
        t && (t.style.display = "none")
    },
    paEl: function(t, e) {
        for (var e = e, i = 0, s = !1, n = doc.querySelector("body"), o = wrap.querySelectorAll(t); e != n && e.parentNode != n; ) {
            for (var a = 0, r = o.length; a < r; a++)
                if (o[a] == e) {
                    i = 1;
                    break
                }
            if (i) {
                s = e;
                break
            }
            e = e.parentNode
        }
        return s
    },
    popupLock: function(t) {
        var e = doc.querySelector(".ui-popup-mask")
          , i = doc.querySelector(".ui-popup-lock");
        if (e)
            e.style.display = "block";
        else {
            var s = doc.createElement("div");
            s.className = "ui-popup-mask",
            wrap.appendChild(s)
        }
        if (i)
            i.querySelector(".info_text i").innerHTML = t,
            i.style.display = "block";
        else {
            var n = doc.createElement("div");
            n.className = "ui-popup-content ui-popup-lock",
            n.innerHTML = '<div class="ui-popup-title"><i class="icon-close-x J-close"></i></div><div class="info_text">只有锁定的<i>' + (t > 0 ? t + "个" : "") + '</i>用户才可以继续参与竞拍哦！</div><div class="lookmore"><a href="h_about_lock.html">为什么会锁定？&gt;</a</div>',
            wrap.appendChild(n)
        }
    },
    getConfig: function(t) {
        sessionStorage._getConfig || (api.url = "88",
        api.method = "get",
        mui.post((envConfig.host || "/") + "api.php", api, function(e) {
            "0000" == e.code ? (sessionStorage._getConfig = !0,
            sessionStorage.config = JSON.stringify(e.data),
            "function" == typeof t && t()) : sessionStorage.config = "{}"
        }, "json"))
    },
    getBidReturn: function() {
        sessionStorage._getBidReturn || (api.url = "89",
        api.method = "get",
        mui.post((envConfig.host || "/") + "api.php", api, function(t) {
            "0000" == t.code ? (sessionStorage._getBidReturn = !0,
            sessionStorage.bidReturn = t.data.value) : sessionStorage.bidReturn = 0
        }, "json"))
    },
    getCity: function() {
        sessionStorage._getCity || (api.url = "90",
        api.method = "get",
        mui.post((envConfig.host || "/") + "api.php", api, function(t) {
            "0000" == t.code ? (sessionStorage._getCity = !0,
            sessionStorage.city = JSON.stringify(t.data)) : sessionStorage.city = "{}"
        }, "json"))
    },
    getUserInfo: function() {
        !sessionStorage._getUserInfo && _user.id > 0 && (api.url = "43",
        api.method = "get",
        mui.post((envConfig.host || "/") + "api.php", api, function(t) {
            "0000" == t.code ? (sessionStorage._getUserInfo = !0,
            t.data.id = _user.id,
            t.data.token = _user.token,
            localStorage.userInfo = JSON.stringify(t.data)) : localStorage.removeItem("userInfo")
        }, "json"))
    },
    getDateTime: function(t, e) {
        var t = new Date(t)
          , i = t.getFullYear()
          , s = t.getMonth() + 1
          , n = t.getDate()
          , o = t.getHours()
          , a = t.getMinutes()
          , r = t.getSeconds();
        return s = s < 10 ? "0" + s : s,
        n = n < 10 ? "0" + n : n,
        o = o < 10 ? "0" + o : o,
        a = a < 10 ? "0" + a : a,
        r = r < 10 ? "0" + r : r,
        e ? i + "-" + s + "-" + n : i + "-" + s + "-" + n + " " + o + ":" + a + ":" + r
    },
    getTimeLine: function(t, e) {
        var i = Math.ceil(t / 1e3);
        e && i > e && (i = e);
        var s = parseInt(i / 3600)
          , n = parseInt((i - 3600 * s) / 60)
          , o = i - 3600 * s - 60 * n;
        return (s > 9 ? s : "0" + s) + ":" + (n > 9 ? n : "0" + n) + ":" + (o > 9 ? o : "0" + o)
    },
    getMyTime: function(t) {
        var e, i, s;
        if (t > 0) {
            var n = parseInt(t / 1e3);
            e = parseInt(n / 3600),
            i = parseInt((n - 3600 * e) / 60),
            s = n - 3600 * e - 60 * i,
            e = e > 9 ? e : "0" + e,
            i = i > 9 ? i : "0" + i,
            s = s > 9 ? s : "0" + s
        } else
            e = "00",
            i = "00",
            s = "00";
        return {
            h: e,
            m: i,
            s: s
        }
    },
    getPageUrl: function(t, e) {
        var i = "";
        if ("html" != t) {
            var s = "";
            mui.each(e, function(t, e) {
                s += 0 == t ? "?" : "&",
                s += e.key + "=" + e.value
            }),
            i = t + s
        } else
            mui.each(e, function(t, e) {
                "do_action" != e.key && e.value && (i = e.value)
            });
        return i
    },
    length: function(t) {
        var e = t.match(/[^\x00-\xff]/gi);
        return t.length + (null == e ? 0 : e.length)
    },
    queryStr: function(t, e) {
        var e = e || location.search
          , i = new Object;
        if (-1 != e.indexOf("?")) {
            var s = e.substr(1);
            strs = s.split("&");
            for (var n = 0; n < strs.length; n++) {
                var o = strs[n].split("=");
                i[o[0]] = decodeURIComponent(o[1])
            }
            return t ? i[t] : i
        }
        return null
    },
    trim: function(t) {
        return t.replace(/(^\s*)|(\s*$)/g, "")
    },
    goodsList: function(t, e, i) {
        var s = {
            html: "",
            arr: [],
            gid: []
        };
        return mui.each(t, function(t, n) {
            if (-1 == i.indexOf(n.id)) {
                var o = n.is_favorite ? '<a class="add_focus ed">已收藏</a>' : '<a class="add_focus">收藏</a>';
                s.arr.push(n.id),
                s.gid.push(n.product_id),
                s.html += '<div class="goods ui-mark-' + n.bid_step + '" id="period' + n.id + "_" + e + '" data-pid="' + n.id + '" data-gid="' + n.product_id + '">' + o + '<a class="cover"><img src="' + envConfig.imgHost + n.img_cover + '" /></a><div class="timeline"></div><div class="price_wrap"><div class="price_cur"></div></div><div class="price_tip hidelong"></div><a class="bid_btn"></a></div>'
            }
        }),
        s
    },
    getFocus: function(t, e) {
        (_user.id > 0 || sessionStorage.user_id > 0) && t && t.length > 0 && (api.url = "91",
        api.product_ids = t.join(),
        api.method = "get",
        mui.ajax((envConfig.host || "/") + "api.php", {
            data: api,
            dataType: "json",
            type: "post",
            timeout: 1e4,
            success: function(t) {
                "0000" == t.code && mui.each(t.data, function(t, i) {
                    if (1 == i.is_favorite) {
                        var s = e.querySelector('[data-gid="' + i.product_id + '"] .add_focus');
                        s.classList.add("ed"),
                        s.innerHTML = "已收藏"
                    }
                })
            },
            error: function() {
                mui.toast("网络异常，请重试")
            }
        }))
    },
    doActivate: function() {
        if (!localStorage._isActivate) {
            var t = _p.queryStr("extend_id") || sessionStorage.extend_id || 0
              , e = _p.queryStr("uuid")
              , i = {
                active_type: "wap",
                extend_id: t
            };
            e ? i.uuid = e : localStorage.uuid && (i.uuid = localStorage.uuid),
            localStorage.sessionid && (i.user_session_id = localStorage.sessionid),
            mui.ajax(envConfig.count + "addata/activate", {
                data: i,
                dataType: "json",
                type: "post",
                headers: {
                    mobileos: localStorage.mobileOs,
                    mobileosversion: localStorage.mobileosversion
                },
                success: function(t) {
                    "0000" == t.code && (localStorage._isActivate = !0)
                },
                error: function() {
                    setTimeout(function() {
                        _p.doActivate()
                    }, 1e3)
                }
            })
        }
    },
    newGuidePop: function(t) {
        var e = ""
          , i = document.createElement("div");
        if (i.className = "mod_guide",
        _user.id > 0) {
            e = '<div class="r_red b_buy"><a class="icon-close-x" id="J-close"></a><div class="ct_wrap"><div class="bag_seal"></div><div class="txt">恭喜您!</div><div class="tips"></div></div><a class="sub_btn line" href="mybalance.html?t=2">查看余额</a><a class="sub_btn">立即使用</a></div>';
            var s = doc.createElement("img");
            s.src = "./images/redbag.png",
            s.style.width = 0,
            s.style.height = 0,
            wrap.appendChild(s),
            setTimeout(function() {
                var e = doc.querySelector(".r_red.b_buy");
                e && (e.className = "b_buy open",
                localStorage.removeItem("newGuide"),
                localStorage._oldUser = !0,
                t.product.id > 0 ? (e.querySelector(".tips").innerHTML = "马上使用赠币竞拍 " + t.product.title + "吧",
                e.querySelectorAll(".sub_btn")[1].setAttribute("href", "detail.html?new=N&gid=" + t.product.id)) : (e.querySelector(".tips").innerHTML = "赠币可用于参与全场任意商品",
                e.querySelectorAll(".sub_btn")[1].setAttribute("href", "index.html")))
            }, 800)
        } else
            e = 0 == t.type ? '<div class="r_goods"><a class="icon-close-x" id="J-close"></a><div class="ct_wrap"><div class="text">恭喜您获得免费竞拍<br><b>' + t.product.title + '</b>的机会</div><img src="' + envConfig.imgHost + t.product.img_cover + '" /></div><a class="sub_btn" href="login.html#register">立即参与</a></div>' : '<div class="r_red"><a class="icon-close-x" id="J-close"></a><div class="ct_wrap"><div class="bag_seal"></div><div class="txt">新人福利<br><em>5</em>元</div></div><a class="sub_btn" href="login.html#register">立即领取</a></div>';
        i.innerHTML = e,
        doc.querySelector("body").appendChild(i),
        doc.getElementById("J-close").addEventListener("tap", function(t) {
            localStorage.removeItem("newGuide"),
            localStorage._oldUser = !0,
            doc.querySelector(".mod_guide").classList.add("hide")
        }, !1)
    },
    newGuide: function() {
        localStorage._oldUser || location.pathname.match(/login.html|detail.html|mycenter.html/i) || (localStorage.newGuide ? _p.newGuidePop(JSON.parse(localStorage.newGuide)) : _user.id || (api.url = "92",
        api.method = "get",
        mui.post((envConfig.host || "/") + "api.php", api, function(t) {
            "0000" == t.code && (localStorage.newGuide = JSON.stringify(t.data),
            _p.newGuidePop(t.data))
        }, "json")))
    },
    isInApp: function() {
        return !!localStorage._isInApp || navigator.userAgent.indexOf("gogobids") > -1 && (localStorage._isInApp = !0,
        !0)
    },
    showPage: function(t, e) {
        var i = doc.querySelector(t)
          , s = wrap.getAttribute("data-pageZ") || 9
          , n = wrap.getAttribute("data-page");
        i.classList.add("show"),
        i.style.zIndex = s,
        s++,
        wrap.setAttribute("data-pageZ", s),
        wrap.setAttribute("data-page", t),
        e ? (history.replaceState(null, "", location.pathname + location.search + t),
        wrap.setAttribute("data-page0", t)) : history.pushState(null, "", location.pathname + location.search + t),
        n && (doc.querySelector(n).classList.remove("show"),
        doc.querySelector(n).removeAttribute("style"))
    },
    pageBack: function() {
        var t = wrap.getAttribute("data-page")
          , e = wrap.getAttribute("data-page0");
        return !(!t || t == e) && (wrap.removeAttribute("data-page"),
        doc.querySelector(t).classList.remove("show"),
        doc.querySelector(t).removeAttribute("style"),
        history.pushState(null, "", location.pathname + location.search),
        !0)
    },
    getWxOpenId: function() {
        ua.match(/micromessenger/i) && !localStorage.wxOpenId && "prod" == envConfig.env && (_p.queryStr("code") ? (_p.loading(),
        api.method = "get",
        api.url = "getWxOpenId",
        api.code = _p.queryStr("code"),
        mui.post((envConfig.host || "/") + "api.php", api, function(t) {
            _p.removeLoading(),
            "0000" == t.code && (localStorage.wxOpenId = t.open_id)
        }, "json")) : location.replace("https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxdffb4c26433c7b95&redirect_uri=" + encodeURIComponent(location.href) + "&response_type=code&scope=snsapi_base&state=getOpenId"))
    },
    getNotice: function() {
        var t = doc.querySelector(".ui-navigation")
          , e = doc.querySelector(".mui-bar.mui-bar-nav")
          , i = (new Date).getTime();
        if (!location.pathname.match(/login.html|mycenter|article|share_add|share.html|address_receiv|edit_info|auction_notice|mybids|mybalance|\/h_/i) && (t || e)) {
            _user.id > 0 && (api.url = "96",
            api.method = "get",
            mui.post((envConfig.host || "/") + "api.php", api, function(t) {
                if ("0000" == t.code) {
                    if (t.data.count > 0) {
                        var e = '<i class="ui-badge">' + t.data.count + "</i>";
                        doc.getElementById("a" + i).innerHTML = e
                    }
                } else
                    "vid" == t.code && (_getingVid || _p.vid(_p.getNotice))
            }, "json"));
            var s = doc.createElement("a");
            s.className = "icon-notice",
            s.id = "a" + i,
            s.href = "mycenter.html#notice",
            t ? t.appendChild(s) : e && e.appendChild(s)
        }
    },
    getFulladdr: function() {
        var t = (new Date).getTime()
          , e = localStorage._getFulladdr ? localStorage._getFulladdr : 0;
        t - e > 864e5 && (_p.loading(),
        api.url = "93",
        api.method = "get",
        mui.post((envConfig.host || "/") + "api.php", api, function(e) {
            if (_p.removeLoading(),
            "0000" == e.code) {
                localStorage._getFulladdr = t;
                var i = JSON.stringify(e.data).replace(/code/g, "value").replace(/name/g, "text").replace(/cities|districts/g, "children");
                localStorage.fullAddr = i
            }
        }, "json"))
    },
    setPayType: function(t) {
        var e = JSON.parse(sessionStorage.config)
          , t = t || doc.getElementById("J-payType");
        if (e.switch_pay) {
            var i = cl = ""
              , s = 0;
            mui.each(e.switch_pay, function(t, e) {
                ua.match(/micromessenger/i) ? 4 != e.pay_channel && (5 == e.pay_channel && 0 == s ? (cl = "active",
                s = 1) : cl = "",
                i += '<li class="' + cl + '" data-channel="' + e.pay_channel + '" data-platform="' + e.pay_platform + '" data-limit="' + (e.limit || 0) + '" data-limit-high="' + (e.limit_high > 0 ? e.limit_high : 9999999) + '" data-ldesc="' + e.limit_desc + '" data-lhdesc="' + e.limit_high_desc + '"><img src="' + envConfig.imgHost + e.icon + '"/><h3>' + e.name + "<em></em></h3>" + e.remark + "</li>") : (4 == e.pay_channel && 0 == s ? (cl = "active",
                s = 1) : cl = "",
                i += '<li class="' + cl + '" data-channel="' + e.pay_channel + '" data-platform="' + e.pay_platform + '" data-limit="' + (e.limit || 0) + '" data-limit-high="' + (e.limit_high > 0 ? e.limit_high : 9999999) + '" data-ldesc="' + e.limit_desc + '" data-lhdesc="' + e.limit_high_desc + '"><img src="' + envConfig.imgHost + e.icon + '"/><h3>' + e.name + "<em></em></h3>" + e.remark + "</li>")
            }),
            t.innerHTML = i
        } else
            sessionStorage.removeItem("_getConfig"),
            _p.getConfig(_p.setPayType)
    },
    openApp: function() {
        if (sessionStorage.hideJumpApp = !0,
        ua.match(/android/i))
            doc.location = "gogobids://",
            setTimeout(function() {
                doc.location = "intent://scan/#Intent;scheme=gogobids;package=com.gogobids.flash;end"
            }, 150);
        else if (ua.match(/iphone|ipod|ipad/i))
            if (ua.match(/os [0-8]_\d[_\d]* like mac os/i)) {
                var t = doc.createElement("iframe");
                t.src = "gogobids://",
                t.style.display = "none",
                doc.body.appendChild(t),
                setTimeout(function() {
                    doc.body.removeChild(t)
                }, 3e3)
            } else
                doc.location = "gogobids://"
    },
    moreApp: function() {
        var t = doc.querySelector(".pop-app");
        if (t)
            t.style.display = "block";
        else {
            var e = doc.createElement("div");
            e.className = "pop-app",
            e.innerHTML = '<div>此功能需要访问客户端才能使用哦！<a class="pop-app-c">逛逛其它</a><a href="https://static.gogobids.com/other/deeplink.html">下载手机APP</a></div>',
            wrap.appendChild(e)
        }
    },
    vid: function(t, e) {
        _p.loading(),
        _getingVid = 1,
        api.method = api.url = "vid",
        mui.post((envConfig.host || "/") + "api.php", api, function(i) {
            if ("0000" == i.code) {
                _vid = 1,
                _getingVid = 0,
                _p.removeLoading(),
                api.vid = i.data;
                var s = (new Date).getTime()
                  , n = {
                    fTime: s + 27e5,
                    vid: i.data
                };
                localStorage._vid = JSON.stringify(n),
                win._pinit || (win._pinit = 1,
                _p.init()),
                !win._init && win.main && win.main.init && main.init(),
                "function" == typeof t && t.apply(this, e)
            }
        }, "json")
    },
    getVid: function() {
        var t = localStorage._vid ? JSON.parse(localStorage._vid) : {
            vid: 0
        }
          , e = (new Date).getTime()
          , i = t.fTime || 0;
        api.vid = t.vid,
        i > e ? (_vid = win._pinit = 1,
        _p.init()) : _p.vid()
    },
    getOs: function() {
        var t = localStorage;
        if (ua.match(/android/i))
            t.mobileOs = "android",
            t.mobileosversion = mui.os.version;
        else if (ua.match(/windows/i)) {
            t.mobileOs = "windows";
            var e = ua.indexOf("windows nt")
              , i = ua.substring(e + 11, ua.indexOf(";", e));
            switch (i) {
            case "5.1":
            case "5.2":
                i = "XP";
                break;
            case "6.0":
                i = "vista";
                break;
            case "6.1":
                i = "7";
                break;
            case "6.2":
                i = "8";
                break;
            case "6.3":
                i = "8.1";
                break;
            case "6.4":
            case "10.0":
                i = "10"
            }
            t.mobileosversion = i
        } else if (ua.match(/letvshop/i)) {
            t.mobileOs = "ios";
            var s = ua.split(";");
            t.mobileosversion = s[4]
        } else if (ua.match(/com.wandafilm.appname/i) || ua.match(/cmread_iphone_appstore/i)) {
            t.mobileOs = "ios";
            var e = ua.indexOf("ios")
              , i = ua.substring(e + 4, ua.indexOf(";", e));
            t.mobileosversion = i
        } else if (ua.match(/linux/i)) {
            t.mobileOs = "linux";
            var e = ua.indexOf("(")
              , i = ua.substring(e + 1, ua.indexOf(";", e));
            t.mobileosversion = i
        } else
            t.mobileOs = "ios",
            t.mobileosversion = mui.os.version;
        api.mobileos = t.mobileOs,
        api.mobileosversion = t.mobileosversion
    },
    serviceBtn: function(t, e) {
        if (t) {
            var i = (e ? "<h2>若对此订单有任何疑问，请联系我们！</h2>" : "") + '<h3>工作日9:30-18:30<br>星期六9:30-18:30</h3><a href="http://18610331379.udesk.cn/im_client/?web_plugin_id=31594&group_id=45871&cur_title=' + doc.title + "&src_url=&cur_url=" + location.href + "&pre_url=" + location.href + '" id="serviceOL">在线客服</a><a href="tel:4008300803" id="callService">拨打客服热线</a>';
            t.classList.add("service_mod"),
            t.innerHTML = i
        }
    },
    filter: function(t, e) {
        var i = []
          , s = e || "title";
        return "1" == _p.queryStr("is_check") ? (mui.each(t, function(t, e) {
            e[s].toLowerCase().match(/iphone|macbook|imac|ipad|iwatch/i) || i.push(e)
        }),
        i) : t
    },
    changeTag: function(t, e) {
        var e = e || this
          , i = e.getAttribute("data-href")
          , s = e.parentNode
          , n = s.getAttribute("data-tags")
          , o = s.querySelectorAll(".ui-tags-tit a")
          , a = n ? doc.querySelectorAll('.ui-tags-ct[data-tags="' + n + '"] li') : doc.querySelectorAll(".ui-tags-ct li");
        mui.each(o, function(t, e) {
            e.classList.remove("active")
        }),
        mui.each(a, function(t, e) {
            e.style.display = "none"
        }),
        e.classList.add("active"),
        doc.getElementById(i) && (doc.getElementById(i).style.display = "block")
    }
}
  , _user = localStorage.userInfo ? JSON.parse(localStorage.userInfo) : {};
Array.prototype.removeValue = function(t) {
    for (var e = 0; e < this.length; e++)
        if (this[e] == t) {
            this.splice(e, 1);
            break
        }
}
,
Array.prototype.contains = function(t) {
    for (i in this)
        if (this[i] == t)
            return !0;
    return !1
}
,
ua.match(/gogobids/i) && wrap.classList.add("app"),
_user.id > 0 && (api.userid = _user.id,
api.token = _user.token),
_p.getOs(),
_p.getVid(),
mui.ready(function() {
    mui(".ui-tags-tit").on("tap", "a", _p.changeTag),
    wrap.addEventListener("tap", function(t) {
        var e = t.target
          , i = e.classList;
        if (i.contains("J-close"))
            doc.querySelector(".ui-popup-mask").style.display = "none",
            mui(".ui-popup-content").each(function(t, e) {
                e.style.display = "none"
            });
        else if (i.contains("view-page")) {
            var s = e.getAttribute("data-href");
            "back" == s ? _p.pageBack() : _p.showPage(e.getAttribute("data-href"))
        } else if (i.contains("moreApp"))
            _p.isInApp() || (_p.openApp(),
            _p.moreApp());
        else if (i.contains("pop-app-c"))
            doc.querySelector(".pop-app").style.display = "none";
        else if (e.parentNode.classList.contains("view-page")) {
            e = e.parentNode;
            var s = e.getAttribute("data-href");
            "back" == s ? _p.pageBack() : _p.showPage(e.getAttribute("data-href"))
        } else
            e.getAttribute("href") && (location.href = e.href)
    }, !1);
    var t = mui.back;
    mui.back = function() {
        if (_p.pageBack())
            return !1;
        t()
    }
});
var _hmt = _hmt || [];
!function() {
    var t = document.createElement("script");
    ua.match(/micromessenger/i) ? t.src = "https://hm.baidu.com/hm.js?f4010a3162e39e730f95ec4149e4eb16" : "prod" != envConfig.env ? t.src = "https://hm.baidu.com/hm.js?a220157043ae97adf3b30d4197179db8" : ua.match(/gogobids/i) ? t.src = "https://hm.baidu.com/hm.js?03968c353e3108d3f4eccc2a91603fc2" : _p.queryStr("extend_id") || sessionStorage.extend_id ? (sessionStorage.extend_id = _p.queryStr("extend_id") || sessionStorage.extend_id,
    t.src = "https://hm.baidu.com/hm.js?1bd4c28fcc82f2135bbf030cfed1e5fb") : t.src = "https://hm.baidu.com/hm.js?cde840e5f94437dfa1178c5938ec4eb9";
    var e = document.getElementsByTagName("script")[0];
    e.parentNode.insertBefore(t, e)
}();
