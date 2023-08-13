/* eslint-disable */
/* Do not edit directly, refer to ui/ folder. */
define(function() { return /******/ (function() { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ 7418:
/***/ (function(module) {

"use strict";
/*
object-assign
(c) Sindre Sorhus
@license MIT
*/


/* eslint-disable no-unused-vars */
var getOwnPropertySymbols = Object.getOwnPropertySymbols;
var hasOwnProperty = Object.prototype.hasOwnProperty;
var propIsEnumerable = Object.prototype.propertyIsEnumerable;

function toObject(val) {
	if (val === null || val === undefined) {
		throw new TypeError('Object.assign cannot be called with null or undefined');
	}

	return Object(val);
}

function shouldUseNative() {
	try {
		if (!Object.assign) {
			return false;
		}

		// Detect buggy property enumeration order in older V8 versions.

		// https://bugs.chromium.org/p/v8/issues/detail?id=4118
		var test1 = new String('abc');  // eslint-disable-line no-new-wrappers
		test1[5] = 'de';
		if (Object.getOwnPropertyNames(test1)[0] === '5') {
			return false;
		}

		// https://bugs.chromium.org/p/v8/issues/detail?id=3056
		var test2 = {};
		for (var i = 0; i < 10; i++) {
			test2['_' + String.fromCharCode(i)] = i;
		}
		var order2 = Object.getOwnPropertyNames(test2).map(function (n) {
			return test2[n];
		});
		if (order2.join('') !== '0123456789') {
			return false;
		}

		// https://bugs.chromium.org/p/v8/issues/detail?id=3056
		var test3 = {};
		'abcdefghijklmnopqrst'.split('').forEach(function (letter) {
			test3[letter] = letter;
		});
		if (Object.keys(Object.assign({}, test3)).join('') !==
				'abcdefghijklmnopqrst') {
			return false;
		}

		return true;
	} catch (err) {
		// We don't expect any of the above to throw, but better to be safe.
		return false;
	}
}

module.exports = shouldUseNative() ? Object.assign : function (target, source) {
	var from;
	var to = toObject(target);
	var symbols;

	for (var s = 1; s < arguments.length; s++) {
		from = Object(arguments[s]);

		for (var key in from) {
			if (hasOwnProperty.call(from, key)) {
				to[key] = from[key];
			}
		}

		if (getOwnPropertySymbols) {
			symbols = getOwnPropertySymbols(from);
			for (var i = 0; i < symbols.length; i++) {
				if (propIsEnumerable.call(from, symbols[i])) {
					to[symbols[i]] = from[symbols[i]];
				}
			}
		}
	}

	return to;
};


/***/ }),

/***/ 4448:
/***/ (function(__unused_webpack_module, exports, __webpack_require__) {

"use strict";
/** @license React v17.0.2
 * react-dom.production.min.js
 *
 * Copyright (c) Facebook, Inc. and its affiliates.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */
/*
 Modernizr 3.0.0pre (Custom Build) | MIT
*/
var aa=__webpack_require__(7294),m=__webpack_require__(7418),r=__webpack_require__(3840);function y(a){for(var b="https://reactjs.org/docs/error-decoder.html?invariant="+a,c=1;c<arguments.length;c++)b+="&args[]="+encodeURIComponent(arguments[c]);return"Minified React error #"+a+"; visit "+b+" for the full message or use the non-minified dev environment for full errors and additional helpful warnings."}if(!aa)throw Error(y(227));var ba=new Set,ca={};function da(a,b){ea(a,b);ea(a+"Capture",b)}
function ea(a,b){ca[a]=b;for(a=0;a<b.length;a++)ba.add(b[a])}
var fa=!("undefined"===typeof window||"undefined"===typeof window.document||"undefined"===typeof window.document.createElement),ha=/^[:A-Z_a-z\u00C0-\u00D6\u00D8-\u00F6\u00F8-\u02FF\u0370-\u037D\u037F-\u1FFF\u200C-\u200D\u2070-\u218F\u2C00-\u2FEF\u3001-\uD7FF\uF900-\uFDCF\uFDF0-\uFFFD][:A-Z_a-z\u00C0-\u00D6\u00D8-\u00F6\u00F8-\u02FF\u0370-\u037D\u037F-\u1FFF\u200C-\u200D\u2070-\u218F\u2C00-\u2FEF\u3001-\uD7FF\uF900-\uFDCF\uFDF0-\uFFFD\-.0-9\u00B7\u0300-\u036F\u203F-\u2040]*$/,ia=Object.prototype.hasOwnProperty,
ja={},ka={};function la(a){if(ia.call(ka,a))return!0;if(ia.call(ja,a))return!1;if(ha.test(a))return ka[a]=!0;ja[a]=!0;return!1}function ma(a,b,c,d){if(null!==c&&0===c.type)return!1;switch(typeof b){case "function":case "symbol":return!0;case "boolean":if(d)return!1;if(null!==c)return!c.acceptsBooleans;a=a.toLowerCase().slice(0,5);return"data-"!==a&&"aria-"!==a;default:return!1}}
function na(a,b,c,d){if(null===b||"undefined"===typeof b||ma(a,b,c,d))return!0;if(d)return!1;if(null!==c)switch(c.type){case 3:return!b;case 4:return!1===b;case 5:return isNaN(b);case 6:return isNaN(b)||1>b}return!1}function B(a,b,c,d,e,f,g){this.acceptsBooleans=2===b||3===b||4===b;this.attributeName=d;this.attributeNamespace=e;this.mustUseProperty=c;this.propertyName=a;this.type=b;this.sanitizeURL=f;this.removeEmptyString=g}var D={};
"children dangerouslySetInnerHTML defaultValue defaultChecked innerHTML suppressContentEditableWarning suppressHydrationWarning style".split(" ").forEach(function(a){D[a]=new B(a,0,!1,a,null,!1,!1)});[["acceptCharset","accept-charset"],["className","class"],["htmlFor","for"],["httpEquiv","http-equiv"]].forEach(function(a){var b=a[0];D[b]=new B(b,1,!1,a[1],null,!1,!1)});["contentEditable","draggable","spellCheck","value"].forEach(function(a){D[a]=new B(a,2,!1,a.toLowerCase(),null,!1,!1)});
["autoReverse","externalResourcesRequired","focusable","preserveAlpha"].forEach(function(a){D[a]=new B(a,2,!1,a,null,!1,!1)});"allowFullScreen async autoFocus autoPlay controls default defer disabled disablePictureInPicture disableRemotePlayback formNoValidate hidden loop noModule noValidate open playsInline readOnly required reversed scoped seamless itemScope".split(" ").forEach(function(a){D[a]=new B(a,3,!1,a.toLowerCase(),null,!1,!1)});
["checked","multiple","muted","selected"].forEach(function(a){D[a]=new B(a,3,!0,a,null,!1,!1)});["capture","download"].forEach(function(a){D[a]=new B(a,4,!1,a,null,!1,!1)});["cols","rows","size","span"].forEach(function(a){D[a]=new B(a,6,!1,a,null,!1,!1)});["rowSpan","start"].forEach(function(a){D[a]=new B(a,5,!1,a.toLowerCase(),null,!1,!1)});var oa=/[\-:]([a-z])/g;function pa(a){return a[1].toUpperCase()}
"accent-height alignment-baseline arabic-form baseline-shift cap-height clip-path clip-rule color-interpolation color-interpolation-filters color-profile color-rendering dominant-baseline enable-background fill-opacity fill-rule flood-color flood-opacity font-family font-size font-size-adjust font-stretch font-style font-variant font-weight glyph-name glyph-orientation-horizontal glyph-orientation-vertical horiz-adv-x horiz-origin-x image-rendering letter-spacing lighting-color marker-end marker-mid marker-start overline-position overline-thickness paint-order panose-1 pointer-events rendering-intent shape-rendering stop-color stop-opacity strikethrough-position strikethrough-thickness stroke-dasharray stroke-dashoffset stroke-linecap stroke-linejoin stroke-miterlimit stroke-opacity stroke-width text-anchor text-decoration text-rendering underline-position underline-thickness unicode-bidi unicode-range units-per-em v-alphabetic v-hanging v-ideographic v-mathematical vector-effect vert-adv-y vert-origin-x vert-origin-y word-spacing writing-mode xmlns:xlink x-height".split(" ").forEach(function(a){var b=a.replace(oa,
pa);D[b]=new B(b,1,!1,a,null,!1,!1)});"xlink:actuate xlink:arcrole xlink:role xlink:show xlink:title xlink:type".split(" ").forEach(function(a){var b=a.replace(oa,pa);D[b]=new B(b,1,!1,a,"http://www.w3.org/1999/xlink",!1,!1)});["xml:base","xml:lang","xml:space"].forEach(function(a){var b=a.replace(oa,pa);D[b]=new B(b,1,!1,a,"http://www.w3.org/XML/1998/namespace",!1,!1)});["tabIndex","crossOrigin"].forEach(function(a){D[a]=new B(a,1,!1,a.toLowerCase(),null,!1,!1)});
D.xlinkHref=new B("xlinkHref",1,!1,"xlink:href","http://www.w3.org/1999/xlink",!0,!1);["src","href","action","formAction"].forEach(function(a){D[a]=new B(a,1,!1,a.toLowerCase(),null,!0,!0)});
function qa(a,b,c,d){var e=D.hasOwnProperty(b)?D[b]:null;var f=null!==e?0===e.type:d?!1:!(2<b.length)||"o"!==b[0]&&"O"!==b[0]||"n"!==b[1]&&"N"!==b[1]?!1:!0;f||(na(b,c,e,d)&&(c=null),d||null===e?la(b)&&(null===c?a.removeAttribute(b):a.setAttribute(b,""+c)):e.mustUseProperty?a[e.propertyName]=null===c?3===e.type?!1:"":c:(b=e.attributeName,d=e.attributeNamespace,null===c?a.removeAttribute(b):(e=e.type,c=3===e||4===e&&!0===c?"":""+c,d?a.setAttributeNS(d,b,c):a.setAttribute(b,c))))}
var ra=aa.__SECRET_INTERNALS_DO_NOT_USE_OR_YOU_WILL_BE_FIRED,sa=60103,ta=60106,ua=60107,wa=60108,xa=60114,ya=60109,za=60110,Aa=60112,Ba=60113,Ca=60120,Da=60115,Ea=60116,Fa=60121,Ga=60128,Ha=60129,Ia=60130,Ja=60131;
if("function"===typeof Symbol&&Symbol.for){var E=Symbol.for;sa=E("react.element");ta=E("react.portal");ua=E("react.fragment");wa=E("react.strict_mode");xa=E("react.profiler");ya=E("react.provider");za=E("react.context");Aa=E("react.forward_ref");Ba=E("react.suspense");Ca=E("react.suspense_list");Da=E("react.memo");Ea=E("react.lazy");Fa=E("react.block");E("react.scope");Ga=E("react.opaque.id");Ha=E("react.debug_trace_mode");Ia=E("react.offscreen");Ja=E("react.legacy_hidden")}
var Ka="function"===typeof Symbol&&Symbol.iterator;function La(a){if(null===a||"object"!==typeof a)return null;a=Ka&&a[Ka]||a["@@iterator"];return"function"===typeof a?a:null}var Ma;function Na(a){if(void 0===Ma)try{throw Error();}catch(c){var b=c.stack.trim().match(/\n( *(at )?)/);Ma=b&&b[1]||""}return"\n"+Ma+a}var Oa=!1;
function Pa(a,b){if(!a||Oa)return"";Oa=!0;var c=Error.prepareStackTrace;Error.prepareStackTrace=void 0;try{if(b)if(b=function(){throw Error();},Object.defineProperty(b.prototype,"props",{set:function(){throw Error();}}),"object"===typeof Reflect&&Reflect.construct){try{Reflect.construct(b,[])}catch(k){var d=k}Reflect.construct(a,[],b)}else{try{b.call()}catch(k){d=k}a.call(b.prototype)}else{try{throw Error();}catch(k){d=k}a()}}catch(k){if(k&&d&&"string"===typeof k.stack){for(var e=k.stack.split("\n"),
f=d.stack.split("\n"),g=e.length-1,h=f.length-1;1<=g&&0<=h&&e[g]!==f[h];)h--;for(;1<=g&&0<=h;g--,h--)if(e[g]!==f[h]){if(1!==g||1!==h){do if(g--,h--,0>h||e[g]!==f[h])return"\n"+e[g].replace(" at new "," at ");while(1<=g&&0<=h)}break}}}finally{Oa=!1,Error.prepareStackTrace=c}return(a=a?a.displayName||a.name:"")?Na(a):""}
function Qa(a){switch(a.tag){case 5:return Na(a.type);case 16:return Na("Lazy");case 13:return Na("Suspense");case 19:return Na("SuspenseList");case 0:case 2:case 15:return a=Pa(a.type,!1),a;case 11:return a=Pa(a.type.render,!1),a;case 22:return a=Pa(a.type._render,!1),a;case 1:return a=Pa(a.type,!0),a;default:return""}}
function Ra(a){if(null==a)return null;if("function"===typeof a)return a.displayName||a.name||null;if("string"===typeof a)return a;switch(a){case ua:return"Fragment";case ta:return"Portal";case xa:return"Profiler";case wa:return"StrictMode";case Ba:return"Suspense";case Ca:return"SuspenseList"}if("object"===typeof a)switch(a.$$typeof){case za:return(a.displayName||"Context")+".Consumer";case ya:return(a._context.displayName||"Context")+".Provider";case Aa:var b=a.render;b=b.displayName||b.name||"";
return a.displayName||(""!==b?"ForwardRef("+b+")":"ForwardRef");case Da:return Ra(a.type);case Fa:return Ra(a._render);case Ea:b=a._payload;a=a._init;try{return Ra(a(b))}catch(c){}}return null}function Sa(a){switch(typeof a){case "boolean":case "number":case "object":case "string":case "undefined":return a;default:return""}}function Ta(a){var b=a.type;return(a=a.nodeName)&&"input"===a.toLowerCase()&&("checkbox"===b||"radio"===b)}
function Ua(a){var b=Ta(a)?"checked":"value",c=Object.getOwnPropertyDescriptor(a.constructor.prototype,b),d=""+a[b];if(!a.hasOwnProperty(b)&&"undefined"!==typeof c&&"function"===typeof c.get&&"function"===typeof c.set){var e=c.get,f=c.set;Object.defineProperty(a,b,{configurable:!0,get:function(){return e.call(this)},set:function(a){d=""+a;f.call(this,a)}});Object.defineProperty(a,b,{enumerable:c.enumerable});return{getValue:function(){return d},setValue:function(a){d=""+a},stopTracking:function(){a._valueTracker=
null;delete a[b]}}}}function Va(a){a._valueTracker||(a._valueTracker=Ua(a))}function Wa(a){if(!a)return!1;var b=a._valueTracker;if(!b)return!0;var c=b.getValue();var d="";a&&(d=Ta(a)?a.checked?"true":"false":a.value);a=d;return a!==c?(b.setValue(a),!0):!1}function Xa(a){a=a||("undefined"!==typeof document?document:void 0);if("undefined"===typeof a)return null;try{return a.activeElement||a.body}catch(b){return a.body}}
function Ya(a,b){var c=b.checked;return m({},b,{defaultChecked:void 0,defaultValue:void 0,value:void 0,checked:null!=c?c:a._wrapperState.initialChecked})}function Za(a,b){var c=null==b.defaultValue?"":b.defaultValue,d=null!=b.checked?b.checked:b.defaultChecked;c=Sa(null!=b.value?b.value:c);a._wrapperState={initialChecked:d,initialValue:c,controlled:"checkbox"===b.type||"radio"===b.type?null!=b.checked:null!=b.value}}function $a(a,b){b=b.checked;null!=b&&qa(a,"checked",b,!1)}
function ab(a,b){$a(a,b);var c=Sa(b.value),d=b.type;if(null!=c)if("number"===d){if(0===c&&""===a.value||a.value!=c)a.value=""+c}else a.value!==""+c&&(a.value=""+c);else if("submit"===d||"reset"===d){a.removeAttribute("value");return}b.hasOwnProperty("value")?bb(a,b.type,c):b.hasOwnProperty("defaultValue")&&bb(a,b.type,Sa(b.defaultValue));null==b.checked&&null!=b.defaultChecked&&(a.defaultChecked=!!b.defaultChecked)}
function cb(a,b,c){if(b.hasOwnProperty("value")||b.hasOwnProperty("defaultValue")){var d=b.type;if(!("submit"!==d&&"reset"!==d||void 0!==b.value&&null!==b.value))return;b=""+a._wrapperState.initialValue;c||b===a.value||(a.value=b);a.defaultValue=b}c=a.name;""!==c&&(a.name="");a.defaultChecked=!!a._wrapperState.initialChecked;""!==c&&(a.name=c)}
function bb(a,b,c){if("number"!==b||Xa(a.ownerDocument)!==a)null==c?a.defaultValue=""+a._wrapperState.initialValue:a.defaultValue!==""+c&&(a.defaultValue=""+c)}function db(a){var b="";aa.Children.forEach(a,function(a){null!=a&&(b+=a)});return b}function eb(a,b){a=m({children:void 0},b);if(b=db(b.children))a.children=b;return a}
function fb(a,b,c,d){a=a.options;if(b){b={};for(var e=0;e<c.length;e++)b["$"+c[e]]=!0;for(c=0;c<a.length;c++)e=b.hasOwnProperty("$"+a[c].value),a[c].selected!==e&&(a[c].selected=e),e&&d&&(a[c].defaultSelected=!0)}else{c=""+Sa(c);b=null;for(e=0;e<a.length;e++){if(a[e].value===c){a[e].selected=!0;d&&(a[e].defaultSelected=!0);return}null!==b||a[e].disabled||(b=a[e])}null!==b&&(b.selected=!0)}}
function gb(a,b){if(null!=b.dangerouslySetInnerHTML)throw Error(y(91));return m({},b,{value:void 0,defaultValue:void 0,children:""+a._wrapperState.initialValue})}function hb(a,b){var c=b.value;if(null==c){c=b.children;b=b.defaultValue;if(null!=c){if(null!=b)throw Error(y(92));if(Array.isArray(c)){if(!(1>=c.length))throw Error(y(93));c=c[0]}b=c}null==b&&(b="");c=b}a._wrapperState={initialValue:Sa(c)}}
function ib(a,b){var c=Sa(b.value),d=Sa(b.defaultValue);null!=c&&(c=""+c,c!==a.value&&(a.value=c),null==b.defaultValue&&a.defaultValue!==c&&(a.defaultValue=c));null!=d&&(a.defaultValue=""+d)}function jb(a){var b=a.textContent;b===a._wrapperState.initialValue&&""!==b&&null!==b&&(a.value=b)}var kb={html:"http://www.w3.org/1999/xhtml",mathml:"http://www.w3.org/1998/Math/MathML",svg:"http://www.w3.org/2000/svg"};
function lb(a){switch(a){case "svg":return"http://www.w3.org/2000/svg";case "math":return"http://www.w3.org/1998/Math/MathML";default:return"http://www.w3.org/1999/xhtml"}}function mb(a,b){return null==a||"http://www.w3.org/1999/xhtml"===a?lb(b):"http://www.w3.org/2000/svg"===a&&"foreignObject"===b?"http://www.w3.org/1999/xhtml":a}
var nb,ob=function(a){return"undefined"!==typeof MSApp&&MSApp.execUnsafeLocalFunction?function(b,c,d,e){MSApp.execUnsafeLocalFunction(function(){return a(b,c,d,e)})}:a}(function(a,b){if(a.namespaceURI!==kb.svg||"innerHTML"in a)a.innerHTML=b;else{nb=nb||document.createElement("div");nb.innerHTML="<svg>"+b.valueOf().toString()+"</svg>";for(b=nb.firstChild;a.firstChild;)a.removeChild(a.firstChild);for(;b.firstChild;)a.appendChild(b.firstChild)}});
function pb(a,b){if(b){var c=a.firstChild;if(c&&c===a.lastChild&&3===c.nodeType){c.nodeValue=b;return}}a.textContent=b}
var qb={animationIterationCount:!0,borderImageOutset:!0,borderImageSlice:!0,borderImageWidth:!0,boxFlex:!0,boxFlexGroup:!0,boxOrdinalGroup:!0,columnCount:!0,columns:!0,flex:!0,flexGrow:!0,flexPositive:!0,flexShrink:!0,flexNegative:!0,flexOrder:!0,gridArea:!0,gridRow:!0,gridRowEnd:!0,gridRowSpan:!0,gridRowStart:!0,gridColumn:!0,gridColumnEnd:!0,gridColumnSpan:!0,gridColumnStart:!0,fontWeight:!0,lineClamp:!0,lineHeight:!0,opacity:!0,order:!0,orphans:!0,tabSize:!0,widows:!0,zIndex:!0,zoom:!0,fillOpacity:!0,
floodOpacity:!0,stopOpacity:!0,strokeDasharray:!0,strokeDashoffset:!0,strokeMiterlimit:!0,strokeOpacity:!0,strokeWidth:!0},rb=["Webkit","ms","Moz","O"];Object.keys(qb).forEach(function(a){rb.forEach(function(b){b=b+a.charAt(0).toUpperCase()+a.substring(1);qb[b]=qb[a]})});function sb(a,b,c){return null==b||"boolean"===typeof b||""===b?"":c||"number"!==typeof b||0===b||qb.hasOwnProperty(a)&&qb[a]?(""+b).trim():b+"px"}
function tb(a,b){a=a.style;for(var c in b)if(b.hasOwnProperty(c)){var d=0===c.indexOf("--"),e=sb(c,b[c],d);"float"===c&&(c="cssFloat");d?a.setProperty(c,e):a[c]=e}}var ub=m({menuitem:!0},{area:!0,base:!0,br:!0,col:!0,embed:!0,hr:!0,img:!0,input:!0,keygen:!0,link:!0,meta:!0,param:!0,source:!0,track:!0,wbr:!0});
function vb(a,b){if(b){if(ub[a]&&(null!=b.children||null!=b.dangerouslySetInnerHTML))throw Error(y(137,a));if(null!=b.dangerouslySetInnerHTML){if(null!=b.children)throw Error(y(60));if(!("object"===typeof b.dangerouslySetInnerHTML&&"__html"in b.dangerouslySetInnerHTML))throw Error(y(61));}if(null!=b.style&&"object"!==typeof b.style)throw Error(y(62));}}
function wb(a,b){if(-1===a.indexOf("-"))return"string"===typeof b.is;switch(a){case "annotation-xml":case "color-profile":case "font-face":case "font-face-src":case "font-face-uri":case "font-face-format":case "font-face-name":case "missing-glyph":return!1;default:return!0}}function xb(a){a=a.target||a.srcElement||window;a.correspondingUseElement&&(a=a.correspondingUseElement);return 3===a.nodeType?a.parentNode:a}var yb=null,zb=null,Ab=null;
function Bb(a){if(a=Cb(a)){if("function"!==typeof yb)throw Error(y(280));var b=a.stateNode;b&&(b=Db(b),yb(a.stateNode,a.type,b))}}function Eb(a){zb?Ab?Ab.push(a):Ab=[a]:zb=a}function Fb(){if(zb){var a=zb,b=Ab;Ab=zb=null;Bb(a);if(b)for(a=0;a<b.length;a++)Bb(b[a])}}function Gb(a,b){return a(b)}function Hb(a,b,c,d,e){return a(b,c,d,e)}function Ib(){}var Jb=Gb,Kb=!1,Lb=!1;function Mb(){if(null!==zb||null!==Ab)Ib(),Fb()}
function Nb(a,b,c){if(Lb)return a(b,c);Lb=!0;try{return Jb(a,b,c)}finally{Lb=!1,Mb()}}
function Ob(a,b){var c=a.stateNode;if(null===c)return null;var d=Db(c);if(null===d)return null;c=d[b];a:switch(b){case "onClick":case "onClickCapture":case "onDoubleClick":case "onDoubleClickCapture":case "onMouseDown":case "onMouseDownCapture":case "onMouseMove":case "onMouseMoveCapture":case "onMouseUp":case "onMouseUpCapture":case "onMouseEnter":(d=!d.disabled)||(a=a.type,d=!("button"===a||"input"===a||"select"===a||"textarea"===a));a=!d;break a;default:a=!1}if(a)return null;if(c&&"function"!==
typeof c)throw Error(y(231,b,typeof c));return c}var Pb=!1;if(fa)try{var Qb={};Object.defineProperty(Qb,"passive",{get:function(){Pb=!0}});window.addEventListener("test",Qb,Qb);window.removeEventListener("test",Qb,Qb)}catch(a){Pb=!1}function Rb(a,b,c,d,e,f,g,h,k){var l=Array.prototype.slice.call(arguments,3);try{b.apply(c,l)}catch(n){this.onError(n)}}var Sb=!1,Tb=null,Ub=!1,Vb=null,Wb={onError:function(a){Sb=!0;Tb=a}};function Xb(a,b,c,d,e,f,g,h,k){Sb=!1;Tb=null;Rb.apply(Wb,arguments)}
function Yb(a,b,c,d,e,f,g,h,k){Xb.apply(this,arguments);if(Sb){if(Sb){var l=Tb;Sb=!1;Tb=null}else throw Error(y(198));Ub||(Ub=!0,Vb=l)}}function Zb(a){var b=a,c=a;if(a.alternate)for(;b.return;)b=b.return;else{a=b;do b=a,0!==(b.flags&1026)&&(c=b.return),a=b.return;while(a)}return 3===b.tag?c:null}function $b(a){if(13===a.tag){var b=a.memoizedState;null===b&&(a=a.alternate,null!==a&&(b=a.memoizedState));if(null!==b)return b.dehydrated}return null}function ac(a){if(Zb(a)!==a)throw Error(y(188));}
function bc(a){var b=a.alternate;if(!b){b=Zb(a);if(null===b)throw Error(y(188));return b!==a?null:a}for(var c=a,d=b;;){var e=c.return;if(null===e)break;var f=e.alternate;if(null===f){d=e.return;if(null!==d){c=d;continue}break}if(e.child===f.child){for(f=e.child;f;){if(f===c)return ac(e),a;if(f===d)return ac(e),b;f=f.sibling}throw Error(y(188));}if(c.return!==d.return)c=e,d=f;else{for(var g=!1,h=e.child;h;){if(h===c){g=!0;c=e;d=f;break}if(h===d){g=!0;d=e;c=f;break}h=h.sibling}if(!g){for(h=f.child;h;){if(h===
c){g=!0;c=f;d=e;break}if(h===d){g=!0;d=f;c=e;break}h=h.sibling}if(!g)throw Error(y(189));}}if(c.alternate!==d)throw Error(y(190));}if(3!==c.tag)throw Error(y(188));return c.stateNode.current===c?a:b}function cc(a){a=bc(a);if(!a)return null;for(var b=a;;){if(5===b.tag||6===b.tag)return b;if(b.child)b.child.return=b,b=b.child;else{if(b===a)break;for(;!b.sibling;){if(!b.return||b.return===a)return null;b=b.return}b.sibling.return=b.return;b=b.sibling}}return null}
function dc(a,b){for(var c=a.alternate;null!==b;){if(b===a||b===c)return!0;b=b.return}return!1}var ec,fc,gc,hc,ic=!1,jc=[],kc=null,lc=null,mc=null,nc=new Map,oc=new Map,pc=[],qc="mousedown mouseup touchcancel touchend touchstart auxclick dblclick pointercancel pointerdown pointerup dragend dragstart drop compositionend compositionstart keydown keypress keyup input textInput copy cut paste click change contextmenu reset submit".split(" ");
function rc(a,b,c,d,e){return{blockedOn:a,domEventName:b,eventSystemFlags:c|16,nativeEvent:e,targetContainers:[d]}}function sc(a,b){switch(a){case "focusin":case "focusout":kc=null;break;case "dragenter":case "dragleave":lc=null;break;case "mouseover":case "mouseout":mc=null;break;case "pointerover":case "pointerout":nc.delete(b.pointerId);break;case "gotpointercapture":case "lostpointercapture":oc.delete(b.pointerId)}}
function tc(a,b,c,d,e,f){if(null===a||a.nativeEvent!==f)return a=rc(b,c,d,e,f),null!==b&&(b=Cb(b),null!==b&&fc(b)),a;a.eventSystemFlags|=d;b=a.targetContainers;null!==e&&-1===b.indexOf(e)&&b.push(e);return a}
function uc(a,b,c,d,e){switch(b){case "focusin":return kc=tc(kc,a,b,c,d,e),!0;case "dragenter":return lc=tc(lc,a,b,c,d,e),!0;case "mouseover":return mc=tc(mc,a,b,c,d,e),!0;case "pointerover":var f=e.pointerId;nc.set(f,tc(nc.get(f)||null,a,b,c,d,e));return!0;case "gotpointercapture":return f=e.pointerId,oc.set(f,tc(oc.get(f)||null,a,b,c,d,e)),!0}return!1}
function vc(a){var b=wc(a.target);if(null!==b){var c=Zb(b);if(null!==c)if(b=c.tag,13===b){if(b=$b(c),null!==b){a.blockedOn=b;hc(a.lanePriority,function(){r.unstable_runWithPriority(a.priority,function(){gc(c)})});return}}else if(3===b&&c.stateNode.hydrate){a.blockedOn=3===c.tag?c.stateNode.containerInfo:null;return}}a.blockedOn=null}
function xc(a){if(null!==a.blockedOn)return!1;for(var b=a.targetContainers;0<b.length;){var c=yc(a.domEventName,a.eventSystemFlags,b[0],a.nativeEvent);if(null!==c)return b=Cb(c),null!==b&&fc(b),a.blockedOn=c,!1;b.shift()}return!0}function zc(a,b,c){xc(a)&&c.delete(b)}
function Ac(){for(ic=!1;0<jc.length;){var a=jc[0];if(null!==a.blockedOn){a=Cb(a.blockedOn);null!==a&&ec(a);break}for(var b=a.targetContainers;0<b.length;){var c=yc(a.domEventName,a.eventSystemFlags,b[0],a.nativeEvent);if(null!==c){a.blockedOn=c;break}b.shift()}null===a.blockedOn&&jc.shift()}null!==kc&&xc(kc)&&(kc=null);null!==lc&&xc(lc)&&(lc=null);null!==mc&&xc(mc)&&(mc=null);nc.forEach(zc);oc.forEach(zc)}
function Bc(a,b){a.blockedOn===b&&(a.blockedOn=null,ic||(ic=!0,r.unstable_scheduleCallback(r.unstable_NormalPriority,Ac)))}
function Cc(a){function b(b){return Bc(b,a)}if(0<jc.length){Bc(jc[0],a);for(var c=1;c<jc.length;c++){var d=jc[c];d.blockedOn===a&&(d.blockedOn=null)}}null!==kc&&Bc(kc,a);null!==lc&&Bc(lc,a);null!==mc&&Bc(mc,a);nc.forEach(b);oc.forEach(b);for(c=0;c<pc.length;c++)d=pc[c],d.blockedOn===a&&(d.blockedOn=null);for(;0<pc.length&&(c=pc[0],null===c.blockedOn);)vc(c),null===c.blockedOn&&pc.shift()}
function Dc(a,b){var c={};c[a.toLowerCase()]=b.toLowerCase();c["Webkit"+a]="webkit"+b;c["Moz"+a]="moz"+b;return c}var Ec={animationend:Dc("Animation","AnimationEnd"),animationiteration:Dc("Animation","AnimationIteration"),animationstart:Dc("Animation","AnimationStart"),transitionend:Dc("Transition","TransitionEnd")},Fc={},Gc={};
fa&&(Gc=document.createElement("div").style,"AnimationEvent"in window||(delete Ec.animationend.animation,delete Ec.animationiteration.animation,delete Ec.animationstart.animation),"TransitionEvent"in window||delete Ec.transitionend.transition);function Hc(a){if(Fc[a])return Fc[a];if(!Ec[a])return a;var b=Ec[a],c;for(c in b)if(b.hasOwnProperty(c)&&c in Gc)return Fc[a]=b[c];return a}
var Ic=Hc("animationend"),Jc=Hc("animationiteration"),Kc=Hc("animationstart"),Lc=Hc("transitionend"),Mc=new Map,Nc=new Map,Oc=["abort","abort",Ic,"animationEnd",Jc,"animationIteration",Kc,"animationStart","canplay","canPlay","canplaythrough","canPlayThrough","durationchange","durationChange","emptied","emptied","encrypted","encrypted","ended","ended","error","error","gotpointercapture","gotPointerCapture","load","load","loadeddata","loadedData","loadedmetadata","loadedMetadata","loadstart","loadStart",
"lostpointercapture","lostPointerCapture","playing","playing","progress","progress","seeking","seeking","stalled","stalled","suspend","suspend","timeupdate","timeUpdate",Lc,"transitionEnd","waiting","waiting"];function Pc(a,b){for(var c=0;c<a.length;c+=2){var d=a[c],e=a[c+1];e="on"+(e[0].toUpperCase()+e.slice(1));Nc.set(d,b);Mc.set(d,e);da(e,[d])}}var Qc=r.unstable_now;Qc();var F=8;
function Rc(a){if(0!==(1&a))return F=15,1;if(0!==(2&a))return F=14,2;if(0!==(4&a))return F=13,4;var b=24&a;if(0!==b)return F=12,b;if(0!==(a&32))return F=11,32;b=192&a;if(0!==b)return F=10,b;if(0!==(a&256))return F=9,256;b=3584&a;if(0!==b)return F=8,b;if(0!==(a&4096))return F=7,4096;b=4186112&a;if(0!==b)return F=6,b;b=62914560&a;if(0!==b)return F=5,b;if(a&67108864)return F=4,67108864;if(0!==(a&134217728))return F=3,134217728;b=805306368&a;if(0!==b)return F=2,b;if(0!==(1073741824&a))return F=1,1073741824;
F=8;return a}function Sc(a){switch(a){case 99:return 15;case 98:return 10;case 97:case 96:return 8;case 95:return 2;default:return 0}}function Tc(a){switch(a){case 15:case 14:return 99;case 13:case 12:case 11:case 10:return 98;case 9:case 8:case 7:case 6:case 4:case 5:return 97;case 3:case 2:case 1:return 95;case 0:return 90;default:throw Error(y(358,a));}}
function Uc(a,b){var c=a.pendingLanes;if(0===c)return F=0;var d=0,e=0,f=a.expiredLanes,g=a.suspendedLanes,h=a.pingedLanes;if(0!==f)d=f,e=F=15;else if(f=c&134217727,0!==f){var k=f&~g;0!==k?(d=Rc(k),e=F):(h&=f,0!==h&&(d=Rc(h),e=F))}else f=c&~g,0!==f?(d=Rc(f),e=F):0!==h&&(d=Rc(h),e=F);if(0===d)return 0;d=31-Vc(d);d=c&((0>d?0:1<<d)<<1)-1;if(0!==b&&b!==d&&0===(b&g)){Rc(b);if(e<=F)return b;F=e}b=a.entangledLanes;if(0!==b)for(a=a.entanglements,b&=d;0<b;)c=31-Vc(b),e=1<<c,d|=a[c],b&=~e;return d}
function Wc(a){a=a.pendingLanes&-1073741825;return 0!==a?a:a&1073741824?1073741824:0}function Xc(a,b){switch(a){case 15:return 1;case 14:return 2;case 12:return a=Yc(24&~b),0===a?Xc(10,b):a;case 10:return a=Yc(192&~b),0===a?Xc(8,b):a;case 8:return a=Yc(3584&~b),0===a&&(a=Yc(4186112&~b),0===a&&(a=512)),a;case 2:return b=Yc(805306368&~b),0===b&&(b=268435456),b}throw Error(y(358,a));}function Yc(a){return a&-a}function Zc(a){for(var b=[],c=0;31>c;c++)b.push(a);return b}
function $c(a,b,c){a.pendingLanes|=b;var d=b-1;a.suspendedLanes&=d;a.pingedLanes&=d;a=a.eventTimes;b=31-Vc(b);a[b]=c}var Vc=Math.clz32?Math.clz32:ad,bd=Math.log,cd=Math.LN2;function ad(a){return 0===a?32:31-(bd(a)/cd|0)|0}var dd=r.unstable_UserBlockingPriority,ed=r.unstable_runWithPriority,fd=!0;function gd(a,b,c,d){Kb||Ib();var e=hd,f=Kb;Kb=!0;try{Hb(e,a,b,c,d)}finally{(Kb=f)||Mb()}}function id(a,b,c,d){ed(dd,hd.bind(null,a,b,c,d))}
function hd(a,b,c,d){if(fd){var e;if((e=0===(b&4))&&0<jc.length&&-1<qc.indexOf(a))a=rc(null,a,b,c,d),jc.push(a);else{var f=yc(a,b,c,d);if(null===f)e&&sc(a,d);else{if(e){if(-1<qc.indexOf(a)){a=rc(f,a,b,c,d);jc.push(a);return}if(uc(f,a,b,c,d))return;sc(a,d)}jd(a,b,d,null,c)}}}}
function yc(a,b,c,d){var e=xb(d);e=wc(e);if(null!==e){var f=Zb(e);if(null===f)e=null;else{var g=f.tag;if(13===g){e=$b(f);if(null!==e)return e;e=null}else if(3===g){if(f.stateNode.hydrate)return 3===f.tag?f.stateNode.containerInfo:null;e=null}else f!==e&&(e=null)}}jd(a,b,d,e,c);return null}var kd=null,ld=null,md=null;
function nd(){if(md)return md;var a,b=ld,c=b.length,d,e="value"in kd?kd.value:kd.textContent,f=e.length;for(a=0;a<c&&b[a]===e[a];a++);var g=c-a;for(d=1;d<=g&&b[c-d]===e[f-d];d++);return md=e.slice(a,1<d?1-d:void 0)}function od(a){var b=a.keyCode;"charCode"in a?(a=a.charCode,0===a&&13===b&&(a=13)):a=b;10===a&&(a=13);return 32<=a||13===a?a:0}function pd(){return!0}function qd(){return!1}
function rd(a){function b(b,d,e,f,g){this._reactName=b;this._targetInst=e;this.type=d;this.nativeEvent=f;this.target=g;this.currentTarget=null;for(var c in a)a.hasOwnProperty(c)&&(b=a[c],this[c]=b?b(f):f[c]);this.isDefaultPrevented=(null!=f.defaultPrevented?f.defaultPrevented:!1===f.returnValue)?pd:qd;this.isPropagationStopped=qd;return this}m(b.prototype,{preventDefault:function(){this.defaultPrevented=!0;var a=this.nativeEvent;a&&(a.preventDefault?a.preventDefault():"unknown"!==typeof a.returnValue&&
(a.returnValue=!1),this.isDefaultPrevented=pd)},stopPropagation:function(){var a=this.nativeEvent;a&&(a.stopPropagation?a.stopPropagation():"unknown"!==typeof a.cancelBubble&&(a.cancelBubble=!0),this.isPropagationStopped=pd)},persist:function(){},isPersistent:pd});return b}
var sd={eventPhase:0,bubbles:0,cancelable:0,timeStamp:function(a){return a.timeStamp||Date.now()},defaultPrevented:0,isTrusted:0},td=rd(sd),ud=m({},sd,{view:0,detail:0}),vd=rd(ud),wd,xd,yd,Ad=m({},ud,{screenX:0,screenY:0,clientX:0,clientY:0,pageX:0,pageY:0,ctrlKey:0,shiftKey:0,altKey:0,metaKey:0,getModifierState:zd,button:0,buttons:0,relatedTarget:function(a){return void 0===a.relatedTarget?a.fromElement===a.srcElement?a.toElement:a.fromElement:a.relatedTarget},movementX:function(a){if("movementX"in
a)return a.movementX;a!==yd&&(yd&&"mousemove"===a.type?(wd=a.screenX-yd.screenX,xd=a.screenY-yd.screenY):xd=wd=0,yd=a);return wd},movementY:function(a){return"movementY"in a?a.movementY:xd}}),Bd=rd(Ad),Cd=m({},Ad,{dataTransfer:0}),Dd=rd(Cd),Ed=m({},ud,{relatedTarget:0}),Fd=rd(Ed),Gd=m({},sd,{animationName:0,elapsedTime:0,pseudoElement:0}),Hd=rd(Gd),Id=m({},sd,{clipboardData:function(a){return"clipboardData"in a?a.clipboardData:window.clipboardData}}),Jd=rd(Id),Kd=m({},sd,{data:0}),Ld=rd(Kd),Md={Esc:"Escape",
Spacebar:" ",Left:"ArrowLeft",Up:"ArrowUp",Right:"ArrowRight",Down:"ArrowDown",Del:"Delete",Win:"OS",Menu:"ContextMenu",Apps:"ContextMenu",Scroll:"ScrollLock",MozPrintableKey:"Unidentified"},Nd={8:"Backspace",9:"Tab",12:"Clear",13:"Enter",16:"Shift",17:"Control",18:"Alt",19:"Pause",20:"CapsLock",27:"Escape",32:" ",33:"PageUp",34:"PageDown",35:"End",36:"Home",37:"ArrowLeft",38:"ArrowUp",39:"ArrowRight",40:"ArrowDown",45:"Insert",46:"Delete",112:"F1",113:"F2",114:"F3",115:"F4",116:"F5",117:"F6",118:"F7",
119:"F8",120:"F9",121:"F10",122:"F11",123:"F12",144:"NumLock",145:"ScrollLock",224:"Meta"},Od={Alt:"altKey",Control:"ctrlKey",Meta:"metaKey",Shift:"shiftKey"};function Pd(a){var b=this.nativeEvent;return b.getModifierState?b.getModifierState(a):(a=Od[a])?!!b[a]:!1}function zd(){return Pd}
var Qd=m({},ud,{key:function(a){if(a.key){var b=Md[a.key]||a.key;if("Unidentified"!==b)return b}return"keypress"===a.type?(a=od(a),13===a?"Enter":String.fromCharCode(a)):"keydown"===a.type||"keyup"===a.type?Nd[a.keyCode]||"Unidentified":""},code:0,location:0,ctrlKey:0,shiftKey:0,altKey:0,metaKey:0,repeat:0,locale:0,getModifierState:zd,charCode:function(a){return"keypress"===a.type?od(a):0},keyCode:function(a){return"keydown"===a.type||"keyup"===a.type?a.keyCode:0},which:function(a){return"keypress"===
a.type?od(a):"keydown"===a.type||"keyup"===a.type?a.keyCode:0}}),Rd=rd(Qd),Sd=m({},Ad,{pointerId:0,width:0,height:0,pressure:0,tangentialPressure:0,tiltX:0,tiltY:0,twist:0,pointerType:0,isPrimary:0}),Td=rd(Sd),Ud=m({},ud,{touches:0,targetTouches:0,changedTouches:0,altKey:0,metaKey:0,ctrlKey:0,shiftKey:0,getModifierState:zd}),Vd=rd(Ud),Wd=m({},sd,{propertyName:0,elapsedTime:0,pseudoElement:0}),Xd=rd(Wd),Yd=m({},Ad,{deltaX:function(a){return"deltaX"in a?a.deltaX:"wheelDeltaX"in a?-a.wheelDeltaX:0},
deltaY:function(a){return"deltaY"in a?a.deltaY:"wheelDeltaY"in a?-a.wheelDeltaY:"wheelDelta"in a?-a.wheelDelta:0},deltaZ:0,deltaMode:0}),Zd=rd(Yd),$d=[9,13,27,32],ae=fa&&"CompositionEvent"in window,be=null;fa&&"documentMode"in document&&(be=document.documentMode);var ce=fa&&"TextEvent"in window&&!be,de=fa&&(!ae||be&&8<be&&11>=be),ee=String.fromCharCode(32),fe=!1;
function ge(a,b){switch(a){case "keyup":return-1!==$d.indexOf(b.keyCode);case "keydown":return 229!==b.keyCode;case "keypress":case "mousedown":case "focusout":return!0;default:return!1}}function he(a){a=a.detail;return"object"===typeof a&&"data"in a?a.data:null}var ie=!1;function je(a,b){switch(a){case "compositionend":return he(b);case "keypress":if(32!==b.which)return null;fe=!0;return ee;case "textInput":return a=b.data,a===ee&&fe?null:a;default:return null}}
function ke(a,b){if(ie)return"compositionend"===a||!ae&&ge(a,b)?(a=nd(),md=ld=kd=null,ie=!1,a):null;switch(a){case "paste":return null;case "keypress":if(!(b.ctrlKey||b.altKey||b.metaKey)||b.ctrlKey&&b.altKey){if(b.char&&1<b.char.length)return b.char;if(b.which)return String.fromCharCode(b.which)}return null;case "compositionend":return de&&"ko"!==b.locale?null:b.data;default:return null}}
var le={color:!0,date:!0,datetime:!0,"datetime-local":!0,email:!0,month:!0,number:!0,password:!0,range:!0,search:!0,tel:!0,text:!0,time:!0,url:!0,week:!0};function me(a){var b=a&&a.nodeName&&a.nodeName.toLowerCase();return"input"===b?!!le[a.type]:"textarea"===b?!0:!1}function ne(a,b,c,d){Eb(d);b=oe(b,"onChange");0<b.length&&(c=new td("onChange","change",null,c,d),a.push({event:c,listeners:b}))}var pe=null,qe=null;function re(a){se(a,0)}function te(a){var b=ue(a);if(Wa(b))return a}
function ve(a,b){if("change"===a)return b}var we=!1;if(fa){var xe;if(fa){var ye="oninput"in document;if(!ye){var ze=document.createElement("div");ze.setAttribute("oninput","return;");ye="function"===typeof ze.oninput}xe=ye}else xe=!1;we=xe&&(!document.documentMode||9<document.documentMode)}function Ae(){pe&&(pe.detachEvent("onpropertychange",Be),qe=pe=null)}function Be(a){if("value"===a.propertyName&&te(qe)){var b=[];ne(b,qe,a,xb(a));a=re;if(Kb)a(b);else{Kb=!0;try{Gb(a,b)}finally{Kb=!1,Mb()}}}}
function Ce(a,b,c){"focusin"===a?(Ae(),pe=b,qe=c,pe.attachEvent("onpropertychange",Be)):"focusout"===a&&Ae()}function De(a){if("selectionchange"===a||"keyup"===a||"keydown"===a)return te(qe)}function Ee(a,b){if("click"===a)return te(b)}function Fe(a,b){if("input"===a||"change"===a)return te(b)}function Ge(a,b){return a===b&&(0!==a||1/a===1/b)||a!==a&&b!==b}var He="function"===typeof Object.is?Object.is:Ge,Ie=Object.prototype.hasOwnProperty;
function Je(a,b){if(He(a,b))return!0;if("object"!==typeof a||null===a||"object"!==typeof b||null===b)return!1;var c=Object.keys(a),d=Object.keys(b);if(c.length!==d.length)return!1;for(d=0;d<c.length;d++)if(!Ie.call(b,c[d])||!He(a[c[d]],b[c[d]]))return!1;return!0}function Ke(a){for(;a&&a.firstChild;)a=a.firstChild;return a}
function Le(a,b){var c=Ke(a);a=0;for(var d;c;){if(3===c.nodeType){d=a+c.textContent.length;if(a<=b&&d>=b)return{node:c,offset:b-a};a=d}a:{for(;c;){if(c.nextSibling){c=c.nextSibling;break a}c=c.parentNode}c=void 0}c=Ke(c)}}function Me(a,b){return a&&b?a===b?!0:a&&3===a.nodeType?!1:b&&3===b.nodeType?Me(a,b.parentNode):"contains"in a?a.contains(b):a.compareDocumentPosition?!!(a.compareDocumentPosition(b)&16):!1:!1}
function Ne(){for(var a=window,b=Xa();b instanceof a.HTMLIFrameElement;){try{var c="string"===typeof b.contentWindow.location.href}catch(d){c=!1}if(c)a=b.contentWindow;else break;b=Xa(a.document)}return b}function Oe(a){var b=a&&a.nodeName&&a.nodeName.toLowerCase();return b&&("input"===b&&("text"===a.type||"search"===a.type||"tel"===a.type||"url"===a.type||"password"===a.type)||"textarea"===b||"true"===a.contentEditable)}
var Pe=fa&&"documentMode"in document&&11>=document.documentMode,Qe=null,Re=null,Se=null,Te=!1;
function Ue(a,b,c){var d=c.window===c?c.document:9===c.nodeType?c:c.ownerDocument;Te||null==Qe||Qe!==Xa(d)||(d=Qe,"selectionStart"in d&&Oe(d)?d={start:d.selectionStart,end:d.selectionEnd}:(d=(d.ownerDocument&&d.ownerDocument.defaultView||window).getSelection(),d={anchorNode:d.anchorNode,anchorOffset:d.anchorOffset,focusNode:d.focusNode,focusOffset:d.focusOffset}),Se&&Je(Se,d)||(Se=d,d=oe(Re,"onSelect"),0<d.length&&(b=new td("onSelect","select",null,b,c),a.push({event:b,listeners:d}),b.target=Qe)))}
Pc("cancel cancel click click close close contextmenu contextMenu copy copy cut cut auxclick auxClick dblclick doubleClick dragend dragEnd dragstart dragStart drop drop focusin focus focusout blur input input invalid invalid keydown keyDown keypress keyPress keyup keyUp mousedown mouseDown mouseup mouseUp paste paste pause pause play play pointercancel pointerCancel pointerdown pointerDown pointerup pointerUp ratechange rateChange reset reset seeked seeked submit submit touchcancel touchCancel touchend touchEnd touchstart touchStart volumechange volumeChange".split(" "),
0);Pc("drag drag dragenter dragEnter dragexit dragExit dragleave dragLeave dragover dragOver mousemove mouseMove mouseout mouseOut mouseover mouseOver pointermove pointerMove pointerout pointerOut pointerover pointerOver scroll scroll toggle toggle touchmove touchMove wheel wheel".split(" "),1);Pc(Oc,2);for(var Ve="change selectionchange textInput compositionstart compositionend compositionupdate".split(" "),We=0;We<Ve.length;We++)Nc.set(Ve[We],0);ea("onMouseEnter",["mouseout","mouseover"]);
ea("onMouseLeave",["mouseout","mouseover"]);ea("onPointerEnter",["pointerout","pointerover"]);ea("onPointerLeave",["pointerout","pointerover"]);da("onChange","change click focusin focusout input keydown keyup selectionchange".split(" "));da("onSelect","focusout contextmenu dragend focusin keydown keyup mousedown mouseup selectionchange".split(" "));da("onBeforeInput",["compositionend","keypress","textInput","paste"]);da("onCompositionEnd","compositionend focusout keydown keypress keyup mousedown".split(" "));
da("onCompositionStart","compositionstart focusout keydown keypress keyup mousedown".split(" "));da("onCompositionUpdate","compositionupdate focusout keydown keypress keyup mousedown".split(" "));var Xe="abort canplay canplaythrough durationchange emptied encrypted ended error loadeddata loadedmetadata loadstart pause play playing progress ratechange seeked seeking stalled suspend timeupdate volumechange waiting".split(" "),Ye=new Set("cancel close invalid load scroll toggle".split(" ").concat(Xe));
function Ze(a,b,c){var d=a.type||"unknown-event";a.currentTarget=c;Yb(d,b,void 0,a);a.currentTarget=null}
function se(a,b){b=0!==(b&4);for(var c=0;c<a.length;c++){var d=a[c],e=d.event;d=d.listeners;a:{var f=void 0;if(b)for(var g=d.length-1;0<=g;g--){var h=d[g],k=h.instance,l=h.currentTarget;h=h.listener;if(k!==f&&e.isPropagationStopped())break a;Ze(e,h,l);f=k}else for(g=0;g<d.length;g++){h=d[g];k=h.instance;l=h.currentTarget;h=h.listener;if(k!==f&&e.isPropagationStopped())break a;Ze(e,h,l);f=k}}}if(Ub)throw a=Vb,Ub=!1,Vb=null,a;}
function G(a,b){var c=$e(b),d=a+"__bubble";c.has(d)||(af(b,a,2,!1),c.add(d))}var bf="_reactListening"+Math.random().toString(36).slice(2);function cf(a){a[bf]||(a[bf]=!0,ba.forEach(function(b){Ye.has(b)||df(b,!1,a,null);df(b,!0,a,null)}))}
function df(a,b,c,d){var e=4<arguments.length&&void 0!==arguments[4]?arguments[4]:0,f=c;"selectionchange"===a&&9!==c.nodeType&&(f=c.ownerDocument);if(null!==d&&!b&&Ye.has(a)){if("scroll"!==a)return;e|=2;f=d}var g=$e(f),h=a+"__"+(b?"capture":"bubble");g.has(h)||(b&&(e|=4),af(f,a,e,b),g.add(h))}
function af(a,b,c,d){var e=Nc.get(b);switch(void 0===e?2:e){case 0:e=gd;break;case 1:e=id;break;default:e=hd}c=e.bind(null,b,c,a);e=void 0;!Pb||"touchstart"!==b&&"touchmove"!==b&&"wheel"!==b||(e=!0);d?void 0!==e?a.addEventListener(b,c,{capture:!0,passive:e}):a.addEventListener(b,c,!0):void 0!==e?a.addEventListener(b,c,{passive:e}):a.addEventListener(b,c,!1)}
function jd(a,b,c,d,e){var f=d;if(0===(b&1)&&0===(b&2)&&null!==d)a:for(;;){if(null===d)return;var g=d.tag;if(3===g||4===g){var h=d.stateNode.containerInfo;if(h===e||8===h.nodeType&&h.parentNode===e)break;if(4===g)for(g=d.return;null!==g;){var k=g.tag;if(3===k||4===k)if(k=g.stateNode.containerInfo,k===e||8===k.nodeType&&k.parentNode===e)return;g=g.return}for(;null!==h;){g=wc(h);if(null===g)return;k=g.tag;if(5===k||6===k){d=f=g;continue a}h=h.parentNode}}d=d.return}Nb(function(){var d=f,e=xb(c),g=[];
a:{var h=Mc.get(a);if(void 0!==h){var k=td,x=a;switch(a){case "keypress":if(0===od(c))break a;case "keydown":case "keyup":k=Rd;break;case "focusin":x="focus";k=Fd;break;case "focusout":x="blur";k=Fd;break;case "beforeblur":case "afterblur":k=Fd;break;case "click":if(2===c.button)break a;case "auxclick":case "dblclick":case "mousedown":case "mousemove":case "mouseup":case "mouseout":case "mouseover":case "contextmenu":k=Bd;break;case "drag":case "dragend":case "dragenter":case "dragexit":case "dragleave":case "dragover":case "dragstart":case "drop":k=
Dd;break;case "touchcancel":case "touchend":case "touchmove":case "touchstart":k=Vd;break;case Ic:case Jc:case Kc:k=Hd;break;case Lc:k=Xd;break;case "scroll":k=vd;break;case "wheel":k=Zd;break;case "copy":case "cut":case "paste":k=Jd;break;case "gotpointercapture":case "lostpointercapture":case "pointercancel":case "pointerdown":case "pointermove":case "pointerout":case "pointerover":case "pointerup":k=Td}var w=0!==(b&4),z=!w&&"scroll"===a,u=w?null!==h?h+"Capture":null:h;w=[];for(var t=d,q;null!==
t;){q=t;var v=q.stateNode;5===q.tag&&null!==v&&(q=v,null!==u&&(v=Ob(t,u),null!=v&&w.push(ef(t,v,q))));if(z)break;t=t.return}0<w.length&&(h=new k(h,x,null,c,e),g.push({event:h,listeners:w}))}}if(0===(b&7)){a:{h="mouseover"===a||"pointerover"===a;k="mouseout"===a||"pointerout"===a;if(h&&0===(b&16)&&(x=c.relatedTarget||c.fromElement)&&(wc(x)||x[ff]))break a;if(k||h){h=e.window===e?e:(h=e.ownerDocument)?h.defaultView||h.parentWindow:window;if(k){if(x=c.relatedTarget||c.toElement,k=d,x=x?wc(x):null,null!==
x&&(z=Zb(x),x!==z||5!==x.tag&&6!==x.tag))x=null}else k=null,x=d;if(k!==x){w=Bd;v="onMouseLeave";u="onMouseEnter";t="mouse";if("pointerout"===a||"pointerover"===a)w=Td,v="onPointerLeave",u="onPointerEnter",t="pointer";z=null==k?h:ue(k);q=null==x?h:ue(x);h=new w(v,t+"leave",k,c,e);h.target=z;h.relatedTarget=q;v=null;wc(e)===d&&(w=new w(u,t+"enter",x,c,e),w.target=q,w.relatedTarget=z,v=w);z=v;if(k&&x)b:{w=k;u=x;t=0;for(q=w;q;q=gf(q))t++;q=0;for(v=u;v;v=gf(v))q++;for(;0<t-q;)w=gf(w),t--;for(;0<q-t;)u=
gf(u),q--;for(;t--;){if(w===u||null!==u&&w===u.alternate)break b;w=gf(w);u=gf(u)}w=null}else w=null;null!==k&&hf(g,h,k,w,!1);null!==x&&null!==z&&hf(g,z,x,w,!0)}}}a:{h=d?ue(d):window;k=h.nodeName&&h.nodeName.toLowerCase();if("select"===k||"input"===k&&"file"===h.type)var J=ve;else if(me(h))if(we)J=Fe;else{J=De;var K=Ce}else(k=h.nodeName)&&"input"===k.toLowerCase()&&("checkbox"===h.type||"radio"===h.type)&&(J=Ee);if(J&&(J=J(a,d))){ne(g,J,c,e);break a}K&&K(a,h,d);"focusout"===a&&(K=h._wrapperState)&&
K.controlled&&"number"===h.type&&bb(h,"number",h.value)}K=d?ue(d):window;switch(a){case "focusin":if(me(K)||"true"===K.contentEditable)Qe=K,Re=d,Se=null;break;case "focusout":Se=Re=Qe=null;break;case "mousedown":Te=!0;break;case "contextmenu":case "mouseup":case "dragend":Te=!1;Ue(g,c,e);break;case "selectionchange":if(Pe)break;case "keydown":case "keyup":Ue(g,c,e)}var Q;if(ae)b:{switch(a){case "compositionstart":var L="onCompositionStart";break b;case "compositionend":L="onCompositionEnd";break b;
case "compositionupdate":L="onCompositionUpdate";break b}L=void 0}else ie?ge(a,c)&&(L="onCompositionEnd"):"keydown"===a&&229===c.keyCode&&(L="onCompositionStart");L&&(de&&"ko"!==c.locale&&(ie||"onCompositionStart"!==L?"onCompositionEnd"===L&&ie&&(Q=nd()):(kd=e,ld="value"in kd?kd.value:kd.textContent,ie=!0)),K=oe(d,L),0<K.length&&(L=new Ld(L,a,null,c,e),g.push({event:L,listeners:K}),Q?L.data=Q:(Q=he(c),null!==Q&&(L.data=Q))));if(Q=ce?je(a,c):ke(a,c))d=oe(d,"onBeforeInput"),0<d.length&&(e=new Ld("onBeforeInput",
"beforeinput",null,c,e),g.push({event:e,listeners:d}),e.data=Q)}se(g,b)})}function ef(a,b,c){return{instance:a,listener:b,currentTarget:c}}function oe(a,b){for(var c=b+"Capture",d=[];null!==a;){var e=a,f=e.stateNode;5===e.tag&&null!==f&&(e=f,f=Ob(a,c),null!=f&&d.unshift(ef(a,f,e)),f=Ob(a,b),null!=f&&d.push(ef(a,f,e)));a=a.return}return d}function gf(a){if(null===a)return null;do a=a.return;while(a&&5!==a.tag);return a?a:null}
function hf(a,b,c,d,e){for(var f=b._reactName,g=[];null!==c&&c!==d;){var h=c,k=h.alternate,l=h.stateNode;if(null!==k&&k===d)break;5===h.tag&&null!==l&&(h=l,e?(k=Ob(c,f),null!=k&&g.unshift(ef(c,k,h))):e||(k=Ob(c,f),null!=k&&g.push(ef(c,k,h))));c=c.return}0!==g.length&&a.push({event:b,listeners:g})}function jf(){}var kf=null,lf=null;function mf(a,b){switch(a){case "button":case "input":case "select":case "textarea":return!!b.autoFocus}return!1}
function nf(a,b){return"textarea"===a||"option"===a||"noscript"===a||"string"===typeof b.children||"number"===typeof b.children||"object"===typeof b.dangerouslySetInnerHTML&&null!==b.dangerouslySetInnerHTML&&null!=b.dangerouslySetInnerHTML.__html}var of="function"===typeof setTimeout?setTimeout:void 0,pf="function"===typeof clearTimeout?clearTimeout:void 0;function qf(a){1===a.nodeType?a.textContent="":9===a.nodeType&&(a=a.body,null!=a&&(a.textContent=""))}
function rf(a){for(;null!=a;a=a.nextSibling){var b=a.nodeType;if(1===b||3===b)break}return a}function sf(a){a=a.previousSibling;for(var b=0;a;){if(8===a.nodeType){var c=a.data;if("$"===c||"$!"===c||"$?"===c){if(0===b)return a;b--}else"/$"===c&&b++}a=a.previousSibling}return null}var tf=0;function uf(a){return{$$typeof:Ga,toString:a,valueOf:a}}var vf=Math.random().toString(36).slice(2),wf="__reactFiber$"+vf,xf="__reactProps$"+vf,ff="__reactContainer$"+vf,yf="__reactEvents$"+vf;
function wc(a){var b=a[wf];if(b)return b;for(var c=a.parentNode;c;){if(b=c[ff]||c[wf]){c=b.alternate;if(null!==b.child||null!==c&&null!==c.child)for(a=sf(a);null!==a;){if(c=a[wf])return c;a=sf(a)}return b}a=c;c=a.parentNode}return null}function Cb(a){a=a[wf]||a[ff];return!a||5!==a.tag&&6!==a.tag&&13!==a.tag&&3!==a.tag?null:a}function ue(a){if(5===a.tag||6===a.tag)return a.stateNode;throw Error(y(33));}function Db(a){return a[xf]||null}
function $e(a){var b=a[yf];void 0===b&&(b=a[yf]=new Set);return b}var zf=[],Af=-1;function Bf(a){return{current:a}}function H(a){0>Af||(a.current=zf[Af],zf[Af]=null,Af--)}function I(a,b){Af++;zf[Af]=a.current;a.current=b}var Cf={},M=Bf(Cf),N=Bf(!1),Df=Cf;
function Ef(a,b){var c=a.type.contextTypes;if(!c)return Cf;var d=a.stateNode;if(d&&d.__reactInternalMemoizedUnmaskedChildContext===b)return d.__reactInternalMemoizedMaskedChildContext;var e={},f;for(f in c)e[f]=b[f];d&&(a=a.stateNode,a.__reactInternalMemoizedUnmaskedChildContext=b,a.__reactInternalMemoizedMaskedChildContext=e);return e}function Ff(a){a=a.childContextTypes;return null!==a&&void 0!==a}function Gf(){H(N);H(M)}function Hf(a,b,c){if(M.current!==Cf)throw Error(y(168));I(M,b);I(N,c)}
function If(a,b,c){var d=a.stateNode;a=b.childContextTypes;if("function"!==typeof d.getChildContext)return c;d=d.getChildContext();for(var e in d)if(!(e in a))throw Error(y(108,Ra(b)||"Unknown",e));return m({},c,d)}function Jf(a){a=(a=a.stateNode)&&a.__reactInternalMemoizedMergedChildContext||Cf;Df=M.current;I(M,a);I(N,N.current);return!0}function Kf(a,b,c){var d=a.stateNode;if(!d)throw Error(y(169));c?(a=If(a,b,Df),d.__reactInternalMemoizedMergedChildContext=a,H(N),H(M),I(M,a)):H(N);I(N,c)}
var Lf=null,Mf=null,Nf=r.unstable_runWithPriority,Of=r.unstable_scheduleCallback,Pf=r.unstable_cancelCallback,Qf=r.unstable_shouldYield,Rf=r.unstable_requestPaint,Sf=r.unstable_now,Tf=r.unstable_getCurrentPriorityLevel,Uf=r.unstable_ImmediatePriority,Vf=r.unstable_UserBlockingPriority,Wf=r.unstable_NormalPriority,Xf=r.unstable_LowPriority,Yf=r.unstable_IdlePriority,Zf={},$f=void 0!==Rf?Rf:function(){},ag=null,bg=null,cg=!1,dg=Sf(),O=1E4>dg?Sf:function(){return Sf()-dg};
function eg(){switch(Tf()){case Uf:return 99;case Vf:return 98;case Wf:return 97;case Xf:return 96;case Yf:return 95;default:throw Error(y(332));}}function fg(a){switch(a){case 99:return Uf;case 98:return Vf;case 97:return Wf;case 96:return Xf;case 95:return Yf;default:throw Error(y(332));}}function gg(a,b){a=fg(a);return Nf(a,b)}function hg(a,b,c){a=fg(a);return Of(a,b,c)}function ig(){if(null!==bg){var a=bg;bg=null;Pf(a)}jg()}
function jg(){if(!cg&&null!==ag){cg=!0;var a=0;try{var b=ag;gg(99,function(){for(;a<b.length;a++){var c=b[a];do c=c(!0);while(null!==c)}});ag=null}catch(c){throw null!==ag&&(ag=ag.slice(a+1)),Of(Uf,ig),c;}finally{cg=!1}}}var kg=ra.ReactCurrentBatchConfig;function lg(a,b){if(a&&a.defaultProps){b=m({},b);a=a.defaultProps;for(var c in a)void 0===b[c]&&(b[c]=a[c]);return b}return b}var mg=Bf(null),ng=null,og=null,pg=null;function qg(){pg=og=ng=null}
function rg(a){var b=mg.current;H(mg);a.type._context._currentValue=b}function sg(a,b){for(;null!==a;){var c=a.alternate;if((a.childLanes&b)===b)if(null===c||(c.childLanes&b)===b)break;else c.childLanes|=b;else a.childLanes|=b,null!==c&&(c.childLanes|=b);a=a.return}}function tg(a,b){ng=a;pg=og=null;a=a.dependencies;null!==a&&null!==a.firstContext&&(0!==(a.lanes&b)&&(ug=!0),a.firstContext=null)}
function vg(a,b){if(pg!==a&&!1!==b&&0!==b){if("number"!==typeof b||1073741823===b)pg=a,b=1073741823;b={context:a,observedBits:b,next:null};if(null===og){if(null===ng)throw Error(y(308));og=b;ng.dependencies={lanes:0,firstContext:b,responders:null}}else og=og.next=b}return a._currentValue}var wg=!1;function xg(a){a.updateQueue={baseState:a.memoizedState,firstBaseUpdate:null,lastBaseUpdate:null,shared:{pending:null},effects:null}}
function yg(a,b){a=a.updateQueue;b.updateQueue===a&&(b.updateQueue={baseState:a.baseState,firstBaseUpdate:a.firstBaseUpdate,lastBaseUpdate:a.lastBaseUpdate,shared:a.shared,effects:a.effects})}function zg(a,b){return{eventTime:a,lane:b,tag:0,payload:null,callback:null,next:null}}function Ag(a,b){a=a.updateQueue;if(null!==a){a=a.shared;var c=a.pending;null===c?b.next=b:(b.next=c.next,c.next=b);a.pending=b}}
function Bg(a,b){var c=a.updateQueue,d=a.alternate;if(null!==d&&(d=d.updateQueue,c===d)){var e=null,f=null;c=c.firstBaseUpdate;if(null!==c){do{var g={eventTime:c.eventTime,lane:c.lane,tag:c.tag,payload:c.payload,callback:c.callback,next:null};null===f?e=f=g:f=f.next=g;c=c.next}while(null!==c);null===f?e=f=b:f=f.next=b}else e=f=b;c={baseState:d.baseState,firstBaseUpdate:e,lastBaseUpdate:f,shared:d.shared,effects:d.effects};a.updateQueue=c;return}a=c.lastBaseUpdate;null===a?c.firstBaseUpdate=b:a.next=
b;c.lastBaseUpdate=b}
function Cg(a,b,c,d){var e=a.updateQueue;wg=!1;var f=e.firstBaseUpdate,g=e.lastBaseUpdate,h=e.shared.pending;if(null!==h){e.shared.pending=null;var k=h,l=k.next;k.next=null;null===g?f=l:g.next=l;g=k;var n=a.alternate;if(null!==n){n=n.updateQueue;var A=n.lastBaseUpdate;A!==g&&(null===A?n.firstBaseUpdate=l:A.next=l,n.lastBaseUpdate=k)}}if(null!==f){A=e.baseState;g=0;n=l=k=null;do{h=f.lane;var p=f.eventTime;if((d&h)===h){null!==n&&(n=n.next={eventTime:p,lane:0,tag:f.tag,payload:f.payload,callback:f.callback,
next:null});a:{var C=a,x=f;h=b;p=c;switch(x.tag){case 1:C=x.payload;if("function"===typeof C){A=C.call(p,A,h);break a}A=C;break a;case 3:C.flags=C.flags&-4097|64;case 0:C=x.payload;h="function"===typeof C?C.call(p,A,h):C;if(null===h||void 0===h)break a;A=m({},A,h);break a;case 2:wg=!0}}null!==f.callback&&(a.flags|=32,h=e.effects,null===h?e.effects=[f]:h.push(f))}else p={eventTime:p,lane:h,tag:f.tag,payload:f.payload,callback:f.callback,next:null},null===n?(l=n=p,k=A):n=n.next=p,g|=h;f=f.next;if(null===
f)if(h=e.shared.pending,null===h)break;else f=h.next,h.next=null,e.lastBaseUpdate=h,e.shared.pending=null}while(1);null===n&&(k=A);e.baseState=k;e.firstBaseUpdate=l;e.lastBaseUpdate=n;Dg|=g;a.lanes=g;a.memoizedState=A}}function Eg(a,b,c){a=b.effects;b.effects=null;if(null!==a)for(b=0;b<a.length;b++){var d=a[b],e=d.callback;if(null!==e){d.callback=null;d=c;if("function"!==typeof e)throw Error(y(191,e));e.call(d)}}}var Fg=(new aa.Component).refs;
function Gg(a,b,c,d){b=a.memoizedState;c=c(d,b);c=null===c||void 0===c?b:m({},b,c);a.memoizedState=c;0===a.lanes&&(a.updateQueue.baseState=c)}
var Kg={isMounted:function(a){return(a=a._reactInternals)?Zb(a)===a:!1},enqueueSetState:function(a,b,c){a=a._reactInternals;var d=Hg(),e=Ig(a),f=zg(d,e);f.payload=b;void 0!==c&&null!==c&&(f.callback=c);Ag(a,f);Jg(a,e,d)},enqueueReplaceState:function(a,b,c){a=a._reactInternals;var d=Hg(),e=Ig(a),f=zg(d,e);f.tag=1;f.payload=b;void 0!==c&&null!==c&&(f.callback=c);Ag(a,f);Jg(a,e,d)},enqueueForceUpdate:function(a,b){a=a._reactInternals;var c=Hg(),d=Ig(a),e=zg(c,d);e.tag=2;void 0!==b&&null!==b&&(e.callback=
b);Ag(a,e);Jg(a,d,c)}};function Lg(a,b,c,d,e,f,g){a=a.stateNode;return"function"===typeof a.shouldComponentUpdate?a.shouldComponentUpdate(d,f,g):b.prototype&&b.prototype.isPureReactComponent?!Je(c,d)||!Je(e,f):!0}
function Mg(a,b,c){var d=!1,e=Cf;var f=b.contextType;"object"===typeof f&&null!==f?f=vg(f):(e=Ff(b)?Df:M.current,d=b.contextTypes,f=(d=null!==d&&void 0!==d)?Ef(a,e):Cf);b=new b(c,f);a.memoizedState=null!==b.state&&void 0!==b.state?b.state:null;b.updater=Kg;a.stateNode=b;b._reactInternals=a;d&&(a=a.stateNode,a.__reactInternalMemoizedUnmaskedChildContext=e,a.__reactInternalMemoizedMaskedChildContext=f);return b}
function Ng(a,b,c,d){a=b.state;"function"===typeof b.componentWillReceiveProps&&b.componentWillReceiveProps(c,d);"function"===typeof b.UNSAFE_componentWillReceiveProps&&b.UNSAFE_componentWillReceiveProps(c,d);b.state!==a&&Kg.enqueueReplaceState(b,b.state,null)}
function Og(a,b,c,d){var e=a.stateNode;e.props=c;e.state=a.memoizedState;e.refs=Fg;xg(a);var f=b.contextType;"object"===typeof f&&null!==f?e.context=vg(f):(f=Ff(b)?Df:M.current,e.context=Ef(a,f));Cg(a,c,e,d);e.state=a.memoizedState;f=b.getDerivedStateFromProps;"function"===typeof f&&(Gg(a,b,f,c),e.state=a.memoizedState);"function"===typeof b.getDerivedStateFromProps||"function"===typeof e.getSnapshotBeforeUpdate||"function"!==typeof e.UNSAFE_componentWillMount&&"function"!==typeof e.componentWillMount||
(b=e.state,"function"===typeof e.componentWillMount&&e.componentWillMount(),"function"===typeof e.UNSAFE_componentWillMount&&e.UNSAFE_componentWillMount(),b!==e.state&&Kg.enqueueReplaceState(e,e.state,null),Cg(a,c,e,d),e.state=a.memoizedState);"function"===typeof e.componentDidMount&&(a.flags|=4)}var Pg=Array.isArray;
function Qg(a,b,c){a=c.ref;if(null!==a&&"function"!==typeof a&&"object"!==typeof a){if(c._owner){c=c._owner;if(c){if(1!==c.tag)throw Error(y(309));var d=c.stateNode}if(!d)throw Error(y(147,a));var e=""+a;if(null!==b&&null!==b.ref&&"function"===typeof b.ref&&b.ref._stringRef===e)return b.ref;b=function(a){var b=d.refs;b===Fg&&(b=d.refs={});null===a?delete b[e]:b[e]=a};b._stringRef=e;return b}if("string"!==typeof a)throw Error(y(284));if(!c._owner)throw Error(y(290,a));}return a}
function Rg(a,b){if("textarea"!==a.type)throw Error(y(31,"[object Object]"===Object.prototype.toString.call(b)?"object with keys {"+Object.keys(b).join(", ")+"}":b));}
function Sg(a){function b(b,c){if(a){var d=b.lastEffect;null!==d?(d.nextEffect=c,b.lastEffect=c):b.firstEffect=b.lastEffect=c;c.nextEffect=null;c.flags=8}}function c(c,d){if(!a)return null;for(;null!==d;)b(c,d),d=d.sibling;return null}function d(a,b){for(a=new Map;null!==b;)null!==b.key?a.set(b.key,b):a.set(b.index,b),b=b.sibling;return a}function e(a,b){a=Tg(a,b);a.index=0;a.sibling=null;return a}function f(b,c,d){b.index=d;if(!a)return c;d=b.alternate;if(null!==d)return d=d.index,d<c?(b.flags=2,
c):d;b.flags=2;return c}function g(b){a&&null===b.alternate&&(b.flags=2);return b}function h(a,b,c,d){if(null===b||6!==b.tag)return b=Ug(c,a.mode,d),b.return=a,b;b=e(b,c);b.return=a;return b}function k(a,b,c,d){if(null!==b&&b.elementType===c.type)return d=e(b,c.props),d.ref=Qg(a,b,c),d.return=a,d;d=Vg(c.type,c.key,c.props,null,a.mode,d);d.ref=Qg(a,b,c);d.return=a;return d}function l(a,b,c,d){if(null===b||4!==b.tag||b.stateNode.containerInfo!==c.containerInfo||b.stateNode.implementation!==c.implementation)return b=
Wg(c,a.mode,d),b.return=a,b;b=e(b,c.children||[]);b.return=a;return b}function n(a,b,c,d,f){if(null===b||7!==b.tag)return b=Xg(c,a.mode,d,f),b.return=a,b;b=e(b,c);b.return=a;return b}function A(a,b,c){if("string"===typeof b||"number"===typeof b)return b=Ug(""+b,a.mode,c),b.return=a,b;if("object"===typeof b&&null!==b){switch(b.$$typeof){case sa:return c=Vg(b.type,b.key,b.props,null,a.mode,c),c.ref=Qg(a,null,b),c.return=a,c;case ta:return b=Wg(b,a.mode,c),b.return=a,b}if(Pg(b)||La(b))return b=Xg(b,
a.mode,c,null),b.return=a,b;Rg(a,b)}return null}function p(a,b,c,d){var e=null!==b?b.key:null;if("string"===typeof c||"number"===typeof c)return null!==e?null:h(a,b,""+c,d);if("object"===typeof c&&null!==c){switch(c.$$typeof){case sa:return c.key===e?c.type===ua?n(a,b,c.props.children,d,e):k(a,b,c,d):null;case ta:return c.key===e?l(a,b,c,d):null}if(Pg(c)||La(c))return null!==e?null:n(a,b,c,d,null);Rg(a,c)}return null}function C(a,b,c,d,e){if("string"===typeof d||"number"===typeof d)return a=a.get(c)||
null,h(b,a,""+d,e);if("object"===typeof d&&null!==d){switch(d.$$typeof){case sa:return a=a.get(null===d.key?c:d.key)||null,d.type===ua?n(b,a,d.props.children,e,d.key):k(b,a,d,e);case ta:return a=a.get(null===d.key?c:d.key)||null,l(b,a,d,e)}if(Pg(d)||La(d))return a=a.get(c)||null,n(b,a,d,e,null);Rg(b,d)}return null}function x(e,g,h,k){for(var l=null,t=null,u=g,z=g=0,q=null;null!==u&&z<h.length;z++){u.index>z?(q=u,u=null):q=u.sibling;var n=p(e,u,h[z],k);if(null===n){null===u&&(u=q);break}a&&u&&null===
n.alternate&&b(e,u);g=f(n,g,z);null===t?l=n:t.sibling=n;t=n;u=q}if(z===h.length)return c(e,u),l;if(null===u){for(;z<h.length;z++)u=A(e,h[z],k),null!==u&&(g=f(u,g,z),null===t?l=u:t.sibling=u,t=u);return l}for(u=d(e,u);z<h.length;z++)q=C(u,e,z,h[z],k),null!==q&&(a&&null!==q.alternate&&u.delete(null===q.key?z:q.key),g=f(q,g,z),null===t?l=q:t.sibling=q,t=q);a&&u.forEach(function(a){return b(e,a)});return l}function w(e,g,h,k){var l=La(h);if("function"!==typeof l)throw Error(y(150));h=l.call(h);if(null==
h)throw Error(y(151));for(var t=l=null,u=g,z=g=0,q=null,n=h.next();null!==u&&!n.done;z++,n=h.next()){u.index>z?(q=u,u=null):q=u.sibling;var w=p(e,u,n.value,k);if(null===w){null===u&&(u=q);break}a&&u&&null===w.alternate&&b(e,u);g=f(w,g,z);null===t?l=w:t.sibling=w;t=w;u=q}if(n.done)return c(e,u),l;if(null===u){for(;!n.done;z++,n=h.next())n=A(e,n.value,k),null!==n&&(g=f(n,g,z),null===t?l=n:t.sibling=n,t=n);return l}for(u=d(e,u);!n.done;z++,n=h.next())n=C(u,e,z,n.value,k),null!==n&&(a&&null!==n.alternate&&
u.delete(null===n.key?z:n.key),g=f(n,g,z),null===t?l=n:t.sibling=n,t=n);a&&u.forEach(function(a){return b(e,a)});return l}return function(a,d,f,h){var k="object"===typeof f&&null!==f&&f.type===ua&&null===f.key;k&&(f=f.props.children);var l="object"===typeof f&&null!==f;if(l)switch(f.$$typeof){case sa:a:{l=f.key;for(k=d;null!==k;){if(k.key===l){switch(k.tag){case 7:if(f.type===ua){c(a,k.sibling);d=e(k,f.props.children);d.return=a;a=d;break a}break;default:if(k.elementType===f.type){c(a,k.sibling);
d=e(k,f.props);d.ref=Qg(a,k,f);d.return=a;a=d;break a}}c(a,k);break}else b(a,k);k=k.sibling}f.type===ua?(d=Xg(f.props.children,a.mode,h,f.key),d.return=a,a=d):(h=Vg(f.type,f.key,f.props,null,a.mode,h),h.ref=Qg(a,d,f),h.return=a,a=h)}return g(a);case ta:a:{for(k=f.key;null!==d;){if(d.key===k)if(4===d.tag&&d.stateNode.containerInfo===f.containerInfo&&d.stateNode.implementation===f.implementation){c(a,d.sibling);d=e(d,f.children||[]);d.return=a;a=d;break a}else{c(a,d);break}else b(a,d);d=d.sibling}d=
Wg(f,a.mode,h);d.return=a;a=d}return g(a)}if("string"===typeof f||"number"===typeof f)return f=""+f,null!==d&&6===d.tag?(c(a,d.sibling),d=e(d,f),d.return=a,a=d):(c(a,d),d=Ug(f,a.mode,h),d.return=a,a=d),g(a);if(Pg(f))return x(a,d,f,h);if(La(f))return w(a,d,f,h);l&&Rg(a,f);if("undefined"===typeof f&&!k)switch(a.tag){case 1:case 22:case 0:case 11:case 15:throw Error(y(152,Ra(a.type)||"Component"));}return c(a,d)}}var Yg=Sg(!0),Zg=Sg(!1),$g={},ah=Bf($g),bh=Bf($g),ch=Bf($g);
function dh(a){if(a===$g)throw Error(y(174));return a}function eh(a,b){I(ch,b);I(bh,a);I(ah,$g);a=b.nodeType;switch(a){case 9:case 11:b=(b=b.documentElement)?b.namespaceURI:mb(null,"");break;default:a=8===a?b.parentNode:b,b=a.namespaceURI||null,a=a.tagName,b=mb(b,a)}H(ah);I(ah,b)}function fh(){H(ah);H(bh);H(ch)}function gh(a){dh(ch.current);var b=dh(ah.current);var c=mb(b,a.type);b!==c&&(I(bh,a),I(ah,c))}function hh(a){bh.current===a&&(H(ah),H(bh))}var P=Bf(0);
function ih(a){for(var b=a;null!==b;){if(13===b.tag){var c=b.memoizedState;if(null!==c&&(c=c.dehydrated,null===c||"$?"===c.data||"$!"===c.data))return b}else if(19===b.tag&&void 0!==b.memoizedProps.revealOrder){if(0!==(b.flags&64))return b}else if(null!==b.child){b.child.return=b;b=b.child;continue}if(b===a)break;for(;null===b.sibling;){if(null===b.return||b.return===a)return null;b=b.return}b.sibling.return=b.return;b=b.sibling}return null}var jh=null,kh=null,lh=!1;
function mh(a,b){var c=nh(5,null,null,0);c.elementType="DELETED";c.type="DELETED";c.stateNode=b;c.return=a;c.flags=8;null!==a.lastEffect?(a.lastEffect.nextEffect=c,a.lastEffect=c):a.firstEffect=a.lastEffect=c}function oh(a,b){switch(a.tag){case 5:var c=a.type;b=1!==b.nodeType||c.toLowerCase()!==b.nodeName.toLowerCase()?null:b;return null!==b?(a.stateNode=b,!0):!1;case 6:return b=""===a.pendingProps||3!==b.nodeType?null:b,null!==b?(a.stateNode=b,!0):!1;case 13:return!1;default:return!1}}
function ph(a){if(lh){var b=kh;if(b){var c=b;if(!oh(a,b)){b=rf(c.nextSibling);if(!b||!oh(a,b)){a.flags=a.flags&-1025|2;lh=!1;jh=a;return}mh(jh,c)}jh=a;kh=rf(b.firstChild)}else a.flags=a.flags&-1025|2,lh=!1,jh=a}}function qh(a){for(a=a.return;null!==a&&5!==a.tag&&3!==a.tag&&13!==a.tag;)a=a.return;jh=a}
function rh(a){if(a!==jh)return!1;if(!lh)return qh(a),lh=!0,!1;var b=a.type;if(5!==a.tag||"head"!==b&&"body"!==b&&!nf(b,a.memoizedProps))for(b=kh;b;)mh(a,b),b=rf(b.nextSibling);qh(a);if(13===a.tag){a=a.memoizedState;a=null!==a?a.dehydrated:null;if(!a)throw Error(y(317));a:{a=a.nextSibling;for(b=0;a;){if(8===a.nodeType){var c=a.data;if("/$"===c){if(0===b){kh=rf(a.nextSibling);break a}b--}else"$"!==c&&"$!"!==c&&"$?"!==c||b++}a=a.nextSibling}kh=null}}else kh=jh?rf(a.stateNode.nextSibling):null;return!0}
function sh(){kh=jh=null;lh=!1}var th=[];function uh(){for(var a=0;a<th.length;a++)th[a]._workInProgressVersionPrimary=null;th.length=0}var vh=ra.ReactCurrentDispatcher,wh=ra.ReactCurrentBatchConfig,xh=0,R=null,S=null,T=null,yh=!1,zh=!1;function Ah(){throw Error(y(321));}function Bh(a,b){if(null===b)return!1;for(var c=0;c<b.length&&c<a.length;c++)if(!He(a[c],b[c]))return!1;return!0}
function Ch(a,b,c,d,e,f){xh=f;R=b;b.memoizedState=null;b.updateQueue=null;b.lanes=0;vh.current=null===a||null===a.memoizedState?Dh:Eh;a=c(d,e);if(zh){f=0;do{zh=!1;if(!(25>f))throw Error(y(301));f+=1;T=S=null;b.updateQueue=null;vh.current=Fh;a=c(d,e)}while(zh)}vh.current=Gh;b=null!==S&&null!==S.next;xh=0;T=S=R=null;yh=!1;if(b)throw Error(y(300));return a}function Hh(){var a={memoizedState:null,baseState:null,baseQueue:null,queue:null,next:null};null===T?R.memoizedState=T=a:T=T.next=a;return T}
function Ih(){if(null===S){var a=R.alternate;a=null!==a?a.memoizedState:null}else a=S.next;var b=null===T?R.memoizedState:T.next;if(null!==b)T=b,S=a;else{if(null===a)throw Error(y(310));S=a;a={memoizedState:S.memoizedState,baseState:S.baseState,baseQueue:S.baseQueue,queue:S.queue,next:null};null===T?R.memoizedState=T=a:T=T.next=a}return T}function Jh(a,b){return"function"===typeof b?b(a):b}
function Kh(a){var b=Ih(),c=b.queue;if(null===c)throw Error(y(311));c.lastRenderedReducer=a;var d=S,e=d.baseQueue,f=c.pending;if(null!==f){if(null!==e){var g=e.next;e.next=f.next;f.next=g}d.baseQueue=e=f;c.pending=null}if(null!==e){e=e.next;d=d.baseState;var h=g=f=null,k=e;do{var l=k.lane;if((xh&l)===l)null!==h&&(h=h.next={lane:0,action:k.action,eagerReducer:k.eagerReducer,eagerState:k.eagerState,next:null}),d=k.eagerReducer===a?k.eagerState:a(d,k.action);else{var n={lane:l,action:k.action,eagerReducer:k.eagerReducer,
eagerState:k.eagerState,next:null};null===h?(g=h=n,f=d):h=h.next=n;R.lanes|=l;Dg|=l}k=k.next}while(null!==k&&k!==e);null===h?f=d:h.next=g;He(d,b.memoizedState)||(ug=!0);b.memoizedState=d;b.baseState=f;b.baseQueue=h;c.lastRenderedState=d}return[b.memoizedState,c.dispatch]}
function Lh(a){var b=Ih(),c=b.queue;if(null===c)throw Error(y(311));c.lastRenderedReducer=a;var d=c.dispatch,e=c.pending,f=b.memoizedState;if(null!==e){c.pending=null;var g=e=e.next;do f=a(f,g.action),g=g.next;while(g!==e);He(f,b.memoizedState)||(ug=!0);b.memoizedState=f;null===b.baseQueue&&(b.baseState=f);c.lastRenderedState=f}return[f,d]}
function Mh(a,b,c){var d=b._getVersion;d=d(b._source);var e=b._workInProgressVersionPrimary;if(null!==e)a=e===d;else if(a=a.mutableReadLanes,a=(xh&a)===a)b._workInProgressVersionPrimary=d,th.push(b);if(a)return c(b._source);th.push(b);throw Error(y(350));}
function Nh(a,b,c,d){var e=U;if(null===e)throw Error(y(349));var f=b._getVersion,g=f(b._source),h=vh.current,k=h.useState(function(){return Mh(e,b,c)}),l=k[1],n=k[0];k=T;var A=a.memoizedState,p=A.refs,C=p.getSnapshot,x=A.source;A=A.subscribe;var w=R;a.memoizedState={refs:p,source:b,subscribe:d};h.useEffect(function(){p.getSnapshot=c;p.setSnapshot=l;var a=f(b._source);if(!He(g,a)){a=c(b._source);He(n,a)||(l(a),a=Ig(w),e.mutableReadLanes|=a&e.pendingLanes);a=e.mutableReadLanes;e.entangledLanes|=a;for(var d=
e.entanglements,h=a;0<h;){var k=31-Vc(h),v=1<<k;d[k]|=a;h&=~v}}},[c,b,d]);h.useEffect(function(){return d(b._source,function(){var a=p.getSnapshot,c=p.setSnapshot;try{c(a(b._source));var d=Ig(w);e.mutableReadLanes|=d&e.pendingLanes}catch(q){c(function(){throw q;})}})},[b,d]);He(C,c)&&He(x,b)&&He(A,d)||(a={pending:null,dispatch:null,lastRenderedReducer:Jh,lastRenderedState:n},a.dispatch=l=Oh.bind(null,R,a),k.queue=a,k.baseQueue=null,n=Mh(e,b,c),k.memoizedState=k.baseState=n);return n}
function Ph(a,b,c){var d=Ih();return Nh(d,a,b,c)}function Qh(a){var b=Hh();"function"===typeof a&&(a=a());b.memoizedState=b.baseState=a;a=b.queue={pending:null,dispatch:null,lastRenderedReducer:Jh,lastRenderedState:a};a=a.dispatch=Oh.bind(null,R,a);return[b.memoizedState,a]}
function Rh(a,b,c,d){a={tag:a,create:b,destroy:c,deps:d,next:null};b=R.updateQueue;null===b?(b={lastEffect:null},R.updateQueue=b,b.lastEffect=a.next=a):(c=b.lastEffect,null===c?b.lastEffect=a.next=a:(d=c.next,c.next=a,a.next=d,b.lastEffect=a));return a}function Sh(a){var b=Hh();a={current:a};return b.memoizedState=a}function Th(){return Ih().memoizedState}function Uh(a,b,c,d){var e=Hh();R.flags|=a;e.memoizedState=Rh(1|b,c,void 0,void 0===d?null:d)}
function Vh(a,b,c,d){var e=Ih();d=void 0===d?null:d;var f=void 0;if(null!==S){var g=S.memoizedState;f=g.destroy;if(null!==d&&Bh(d,g.deps)){Rh(b,c,f,d);return}}R.flags|=a;e.memoizedState=Rh(1|b,c,f,d)}function Wh(a,b){return Uh(516,4,a,b)}function Xh(a,b){return Vh(516,4,a,b)}function Yh(a,b){return Vh(4,2,a,b)}function Zh(a,b){if("function"===typeof b)return a=a(),b(a),function(){b(null)};if(null!==b&&void 0!==b)return a=a(),b.current=a,function(){b.current=null}}
function $h(a,b,c){c=null!==c&&void 0!==c?c.concat([a]):null;return Vh(4,2,Zh.bind(null,b,a),c)}function ai(){}function bi(a,b){var c=Ih();b=void 0===b?null:b;var d=c.memoizedState;if(null!==d&&null!==b&&Bh(b,d[1]))return d[0];c.memoizedState=[a,b];return a}function ci(a,b){var c=Ih();b=void 0===b?null:b;var d=c.memoizedState;if(null!==d&&null!==b&&Bh(b,d[1]))return d[0];a=a();c.memoizedState=[a,b];return a}
function di(a,b){var c=eg();gg(98>c?98:c,function(){a(!0)});gg(97<c?97:c,function(){var c=wh.transition;wh.transition=1;try{a(!1),b()}finally{wh.transition=c}})}
function Oh(a,b,c){var d=Hg(),e=Ig(a),f={lane:e,action:c,eagerReducer:null,eagerState:null,next:null},g=b.pending;null===g?f.next=f:(f.next=g.next,g.next=f);b.pending=f;g=a.alternate;if(a===R||null!==g&&g===R)zh=yh=!0;else{if(0===a.lanes&&(null===g||0===g.lanes)&&(g=b.lastRenderedReducer,null!==g))try{var h=b.lastRenderedState,k=g(h,c);f.eagerReducer=g;f.eagerState=k;if(He(k,h))return}catch(l){}finally{}Jg(a,e,d)}}
var Gh={readContext:vg,useCallback:Ah,useContext:Ah,useEffect:Ah,useImperativeHandle:Ah,useLayoutEffect:Ah,useMemo:Ah,useReducer:Ah,useRef:Ah,useState:Ah,useDebugValue:Ah,useDeferredValue:Ah,useTransition:Ah,useMutableSource:Ah,useOpaqueIdentifier:Ah,unstable_isNewReconciler:!1},Dh={readContext:vg,useCallback:function(a,b){Hh().memoizedState=[a,void 0===b?null:b];return a},useContext:vg,useEffect:Wh,useImperativeHandle:function(a,b,c){c=null!==c&&void 0!==c?c.concat([a]):null;return Uh(4,2,Zh.bind(null,
b,a),c)},useLayoutEffect:function(a,b){return Uh(4,2,a,b)},useMemo:function(a,b){var c=Hh();b=void 0===b?null:b;a=a();c.memoizedState=[a,b];return a},useReducer:function(a,b,c){var d=Hh();b=void 0!==c?c(b):b;d.memoizedState=d.baseState=b;a=d.queue={pending:null,dispatch:null,lastRenderedReducer:a,lastRenderedState:b};a=a.dispatch=Oh.bind(null,R,a);return[d.memoizedState,a]},useRef:Sh,useState:Qh,useDebugValue:ai,useDeferredValue:function(a){var b=Qh(a),c=b[0],d=b[1];Wh(function(){var b=wh.transition;
wh.transition=1;try{d(a)}finally{wh.transition=b}},[a]);return c},useTransition:function(){var a=Qh(!1),b=a[0];a=di.bind(null,a[1]);Sh(a);return[a,b]},useMutableSource:function(a,b,c){var d=Hh();d.memoizedState={refs:{getSnapshot:b,setSnapshot:null},source:a,subscribe:c};return Nh(d,a,b,c)},useOpaqueIdentifier:function(){if(lh){var a=!1,b=uf(function(){a||(a=!0,c("r:"+(tf++).toString(36)));throw Error(y(355));}),c=Qh(b)[1];0===(R.mode&2)&&(R.flags|=516,Rh(5,function(){c("r:"+(tf++).toString(36))},
void 0,null));return b}b="r:"+(tf++).toString(36);Qh(b);return b},unstable_isNewReconciler:!1},Eh={readContext:vg,useCallback:bi,useContext:vg,useEffect:Xh,useImperativeHandle:$h,useLayoutEffect:Yh,useMemo:ci,useReducer:Kh,useRef:Th,useState:function(){return Kh(Jh)},useDebugValue:ai,useDeferredValue:function(a){var b=Kh(Jh),c=b[0],d=b[1];Xh(function(){var b=wh.transition;wh.transition=1;try{d(a)}finally{wh.transition=b}},[a]);return c},useTransition:function(){var a=Kh(Jh)[0];return[Th().current,
a]},useMutableSource:Ph,useOpaqueIdentifier:function(){return Kh(Jh)[0]},unstable_isNewReconciler:!1},Fh={readContext:vg,useCallback:bi,useContext:vg,useEffect:Xh,useImperativeHandle:$h,useLayoutEffect:Yh,useMemo:ci,useReducer:Lh,useRef:Th,useState:function(){return Lh(Jh)},useDebugValue:ai,useDeferredValue:function(a){var b=Lh(Jh),c=b[0],d=b[1];Xh(function(){var b=wh.transition;wh.transition=1;try{d(a)}finally{wh.transition=b}},[a]);return c},useTransition:function(){var a=Lh(Jh)[0];return[Th().current,
a]},useMutableSource:Ph,useOpaqueIdentifier:function(){return Lh(Jh)[0]},unstable_isNewReconciler:!1},ei=ra.ReactCurrentOwner,ug=!1;function fi(a,b,c,d){b.child=null===a?Zg(b,null,c,d):Yg(b,a.child,c,d)}function gi(a,b,c,d,e){c=c.render;var f=b.ref;tg(b,e);d=Ch(a,b,c,d,f,e);if(null!==a&&!ug)return b.updateQueue=a.updateQueue,b.flags&=-517,a.lanes&=~e,hi(a,b,e);b.flags|=1;fi(a,b,d,e);return b.child}
function ii(a,b,c,d,e,f){if(null===a){var g=c.type;if("function"===typeof g&&!ji(g)&&void 0===g.defaultProps&&null===c.compare&&void 0===c.defaultProps)return b.tag=15,b.type=g,ki(a,b,g,d,e,f);a=Vg(c.type,null,d,b,b.mode,f);a.ref=b.ref;a.return=b;return b.child=a}g=a.child;if(0===(e&f)&&(e=g.memoizedProps,c=c.compare,c=null!==c?c:Je,c(e,d)&&a.ref===b.ref))return hi(a,b,f);b.flags|=1;a=Tg(g,d);a.ref=b.ref;a.return=b;return b.child=a}
function ki(a,b,c,d,e,f){if(null!==a&&Je(a.memoizedProps,d)&&a.ref===b.ref)if(ug=!1,0!==(f&e))0!==(a.flags&16384)&&(ug=!0);else return b.lanes=a.lanes,hi(a,b,f);return li(a,b,c,d,f)}
function mi(a,b,c){var d=b.pendingProps,e=d.children,f=null!==a?a.memoizedState:null;if("hidden"===d.mode||"unstable-defer-without-hiding"===d.mode)if(0===(b.mode&4))b.memoizedState={baseLanes:0},ni(b,c);else if(0!==(c&1073741824))b.memoizedState={baseLanes:0},ni(b,null!==f?f.baseLanes:c);else return a=null!==f?f.baseLanes|c:c,b.lanes=b.childLanes=1073741824,b.memoizedState={baseLanes:a},ni(b,a),null;else null!==f?(d=f.baseLanes|c,b.memoizedState=null):d=c,ni(b,d);fi(a,b,e,c);return b.child}
function oi(a,b){var c=b.ref;if(null===a&&null!==c||null!==a&&a.ref!==c)b.flags|=128}function li(a,b,c,d,e){var f=Ff(c)?Df:M.current;f=Ef(b,f);tg(b,e);c=Ch(a,b,c,d,f,e);if(null!==a&&!ug)return b.updateQueue=a.updateQueue,b.flags&=-517,a.lanes&=~e,hi(a,b,e);b.flags|=1;fi(a,b,c,e);return b.child}
function pi(a,b,c,d,e){if(Ff(c)){var f=!0;Jf(b)}else f=!1;tg(b,e);if(null===b.stateNode)null!==a&&(a.alternate=null,b.alternate=null,b.flags|=2),Mg(b,c,d),Og(b,c,d,e),d=!0;else if(null===a){var g=b.stateNode,h=b.memoizedProps;g.props=h;var k=g.context,l=c.contextType;"object"===typeof l&&null!==l?l=vg(l):(l=Ff(c)?Df:M.current,l=Ef(b,l));var n=c.getDerivedStateFromProps,A="function"===typeof n||"function"===typeof g.getSnapshotBeforeUpdate;A||"function"!==typeof g.UNSAFE_componentWillReceiveProps&&
"function"!==typeof g.componentWillReceiveProps||(h!==d||k!==l)&&Ng(b,g,d,l);wg=!1;var p=b.memoizedState;g.state=p;Cg(b,d,g,e);k=b.memoizedState;h!==d||p!==k||N.current||wg?("function"===typeof n&&(Gg(b,c,n,d),k=b.memoizedState),(h=wg||Lg(b,c,h,d,p,k,l))?(A||"function"!==typeof g.UNSAFE_componentWillMount&&"function"!==typeof g.componentWillMount||("function"===typeof g.componentWillMount&&g.componentWillMount(),"function"===typeof g.UNSAFE_componentWillMount&&g.UNSAFE_componentWillMount()),"function"===
typeof g.componentDidMount&&(b.flags|=4)):("function"===typeof g.componentDidMount&&(b.flags|=4),b.memoizedProps=d,b.memoizedState=k),g.props=d,g.state=k,g.context=l,d=h):("function"===typeof g.componentDidMount&&(b.flags|=4),d=!1)}else{g=b.stateNode;yg(a,b);h=b.memoizedProps;l=b.type===b.elementType?h:lg(b.type,h);g.props=l;A=b.pendingProps;p=g.context;k=c.contextType;"object"===typeof k&&null!==k?k=vg(k):(k=Ff(c)?Df:M.current,k=Ef(b,k));var C=c.getDerivedStateFromProps;(n="function"===typeof C||
"function"===typeof g.getSnapshotBeforeUpdate)||"function"!==typeof g.UNSAFE_componentWillReceiveProps&&"function"!==typeof g.componentWillReceiveProps||(h!==A||p!==k)&&Ng(b,g,d,k);wg=!1;p=b.memoizedState;g.state=p;Cg(b,d,g,e);var x=b.memoizedState;h!==A||p!==x||N.current||wg?("function"===typeof C&&(Gg(b,c,C,d),x=b.memoizedState),(l=wg||Lg(b,c,l,d,p,x,k))?(n||"function"!==typeof g.UNSAFE_componentWillUpdate&&"function"!==typeof g.componentWillUpdate||("function"===typeof g.componentWillUpdate&&g.componentWillUpdate(d,
x,k),"function"===typeof g.UNSAFE_componentWillUpdate&&g.UNSAFE_componentWillUpdate(d,x,k)),"function"===typeof g.componentDidUpdate&&(b.flags|=4),"function"===typeof g.getSnapshotBeforeUpdate&&(b.flags|=256)):("function"!==typeof g.componentDidUpdate||h===a.memoizedProps&&p===a.memoizedState||(b.flags|=4),"function"!==typeof g.getSnapshotBeforeUpdate||h===a.memoizedProps&&p===a.memoizedState||(b.flags|=256),b.memoizedProps=d,b.memoizedState=x),g.props=d,g.state=x,g.context=k,d=l):("function"!==typeof g.componentDidUpdate||
h===a.memoizedProps&&p===a.memoizedState||(b.flags|=4),"function"!==typeof g.getSnapshotBeforeUpdate||h===a.memoizedProps&&p===a.memoizedState||(b.flags|=256),d=!1)}return qi(a,b,c,d,f,e)}
function qi(a,b,c,d,e,f){oi(a,b);var g=0!==(b.flags&64);if(!d&&!g)return e&&Kf(b,c,!1),hi(a,b,f);d=b.stateNode;ei.current=b;var h=g&&"function"!==typeof c.getDerivedStateFromError?null:d.render();b.flags|=1;null!==a&&g?(b.child=Yg(b,a.child,null,f),b.child=Yg(b,null,h,f)):fi(a,b,h,f);b.memoizedState=d.state;e&&Kf(b,c,!0);return b.child}function ri(a){var b=a.stateNode;b.pendingContext?Hf(a,b.pendingContext,b.pendingContext!==b.context):b.context&&Hf(a,b.context,!1);eh(a,b.containerInfo)}
var si={dehydrated:null,retryLane:0};
function ti(a,b,c){var d=b.pendingProps,e=P.current,f=!1,g;(g=0!==(b.flags&64))||(g=null!==a&&null===a.memoizedState?!1:0!==(e&2));g?(f=!0,b.flags&=-65):null!==a&&null===a.memoizedState||void 0===d.fallback||!0===d.unstable_avoidThisFallback||(e|=1);I(P,e&1);if(null===a){void 0!==d.fallback&&ph(b);a=d.children;e=d.fallback;if(f)return a=ui(b,a,e,c),b.child.memoizedState={baseLanes:c},b.memoizedState=si,a;if("number"===typeof d.unstable_expectedLoadTime)return a=ui(b,a,e,c),b.child.memoizedState={baseLanes:c},
b.memoizedState=si,b.lanes=33554432,a;c=vi({mode:"visible",children:a},b.mode,c,null);c.return=b;return b.child=c}if(null!==a.memoizedState){if(f)return d=wi(a,b,d.children,d.fallback,c),f=b.child,e=a.child.memoizedState,f.memoizedState=null===e?{baseLanes:c}:{baseLanes:e.baseLanes|c},f.childLanes=a.childLanes&~c,b.memoizedState=si,d;c=xi(a,b,d.children,c);b.memoizedState=null;return c}if(f)return d=wi(a,b,d.children,d.fallback,c),f=b.child,e=a.child.memoizedState,f.memoizedState=null===e?{baseLanes:c}:
{baseLanes:e.baseLanes|c},f.childLanes=a.childLanes&~c,b.memoizedState=si,d;c=xi(a,b,d.children,c);b.memoizedState=null;return c}function ui(a,b,c,d){var e=a.mode,f=a.child;b={mode:"hidden",children:b};0===(e&2)&&null!==f?(f.childLanes=0,f.pendingProps=b):f=vi(b,e,0,null);c=Xg(c,e,d,null);f.return=a;c.return=a;f.sibling=c;a.child=f;return c}
function xi(a,b,c,d){var e=a.child;a=e.sibling;c=Tg(e,{mode:"visible",children:c});0===(b.mode&2)&&(c.lanes=d);c.return=b;c.sibling=null;null!==a&&(a.nextEffect=null,a.flags=8,b.firstEffect=b.lastEffect=a);return b.child=c}
function wi(a,b,c,d,e){var f=b.mode,g=a.child;a=g.sibling;var h={mode:"hidden",children:c};0===(f&2)&&b.child!==g?(c=b.child,c.childLanes=0,c.pendingProps=h,g=c.lastEffect,null!==g?(b.firstEffect=c.firstEffect,b.lastEffect=g,g.nextEffect=null):b.firstEffect=b.lastEffect=null):c=Tg(g,h);null!==a?d=Tg(a,d):(d=Xg(d,f,e,null),d.flags|=2);d.return=b;c.return=b;c.sibling=d;b.child=c;return d}function yi(a,b){a.lanes|=b;var c=a.alternate;null!==c&&(c.lanes|=b);sg(a.return,b)}
function zi(a,b,c,d,e,f){var g=a.memoizedState;null===g?a.memoizedState={isBackwards:b,rendering:null,renderingStartTime:0,last:d,tail:c,tailMode:e,lastEffect:f}:(g.isBackwards=b,g.rendering=null,g.renderingStartTime=0,g.last=d,g.tail=c,g.tailMode=e,g.lastEffect=f)}
function Ai(a,b,c){var d=b.pendingProps,e=d.revealOrder,f=d.tail;fi(a,b,d.children,c);d=P.current;if(0!==(d&2))d=d&1|2,b.flags|=64;else{if(null!==a&&0!==(a.flags&64))a:for(a=b.child;null!==a;){if(13===a.tag)null!==a.memoizedState&&yi(a,c);else if(19===a.tag)yi(a,c);else if(null!==a.child){a.child.return=a;a=a.child;continue}if(a===b)break a;for(;null===a.sibling;){if(null===a.return||a.return===b)break a;a=a.return}a.sibling.return=a.return;a=a.sibling}d&=1}I(P,d);if(0===(b.mode&2))b.memoizedState=
null;else switch(e){case "forwards":c=b.child;for(e=null;null!==c;)a=c.alternate,null!==a&&null===ih(a)&&(e=c),c=c.sibling;c=e;null===c?(e=b.child,b.child=null):(e=c.sibling,c.sibling=null);zi(b,!1,e,c,f,b.lastEffect);break;case "backwards":c=null;e=b.child;for(b.child=null;null!==e;){a=e.alternate;if(null!==a&&null===ih(a)){b.child=e;break}a=e.sibling;e.sibling=c;c=e;e=a}zi(b,!0,c,null,f,b.lastEffect);break;case "together":zi(b,!1,null,null,void 0,b.lastEffect);break;default:b.memoizedState=null}return b.child}
function hi(a,b,c){null!==a&&(b.dependencies=a.dependencies);Dg|=b.lanes;if(0!==(c&b.childLanes)){if(null!==a&&b.child!==a.child)throw Error(y(153));if(null!==b.child){a=b.child;c=Tg(a,a.pendingProps);b.child=c;for(c.return=b;null!==a.sibling;)a=a.sibling,c=c.sibling=Tg(a,a.pendingProps),c.return=b;c.sibling=null}return b.child}return null}var Bi,Ci,Di,Ei;
Bi=function(a,b){for(var c=b.child;null!==c;){if(5===c.tag||6===c.tag)a.appendChild(c.stateNode);else if(4!==c.tag&&null!==c.child){c.child.return=c;c=c.child;continue}if(c===b)break;for(;null===c.sibling;){if(null===c.return||c.return===b)return;c=c.return}c.sibling.return=c.return;c=c.sibling}};Ci=function(){};
Di=function(a,b,c,d){var e=a.memoizedProps;if(e!==d){a=b.stateNode;dh(ah.current);var f=null;switch(c){case "input":e=Ya(a,e);d=Ya(a,d);f=[];break;case "option":e=eb(a,e);d=eb(a,d);f=[];break;case "select":e=m({},e,{value:void 0});d=m({},d,{value:void 0});f=[];break;case "textarea":e=gb(a,e);d=gb(a,d);f=[];break;default:"function"!==typeof e.onClick&&"function"===typeof d.onClick&&(a.onclick=jf)}vb(c,d);var g;c=null;for(l in e)if(!d.hasOwnProperty(l)&&e.hasOwnProperty(l)&&null!=e[l])if("style"===
l){var h=e[l];for(g in h)h.hasOwnProperty(g)&&(c||(c={}),c[g]="")}else"dangerouslySetInnerHTML"!==l&&"children"!==l&&"suppressContentEditableWarning"!==l&&"suppressHydrationWarning"!==l&&"autoFocus"!==l&&(ca.hasOwnProperty(l)?f||(f=[]):(f=f||[]).push(l,null));for(l in d){var k=d[l];h=null!=e?e[l]:void 0;if(d.hasOwnProperty(l)&&k!==h&&(null!=k||null!=h))if("style"===l)if(h){for(g in h)!h.hasOwnProperty(g)||k&&k.hasOwnProperty(g)||(c||(c={}),c[g]="");for(g in k)k.hasOwnProperty(g)&&h[g]!==k[g]&&(c||
(c={}),c[g]=k[g])}else c||(f||(f=[]),f.push(l,c)),c=k;else"dangerouslySetInnerHTML"===l?(k=k?k.__html:void 0,h=h?h.__html:void 0,null!=k&&h!==k&&(f=f||[]).push(l,k)):"children"===l?"string"!==typeof k&&"number"!==typeof k||(f=f||[]).push(l,""+k):"suppressContentEditableWarning"!==l&&"suppressHydrationWarning"!==l&&(ca.hasOwnProperty(l)?(null!=k&&"onScroll"===l&&G("scroll",a),f||h===k||(f=[])):"object"===typeof k&&null!==k&&k.$$typeof===Ga?k.toString():(f=f||[]).push(l,k))}c&&(f=f||[]).push("style",
c);var l=f;if(b.updateQueue=l)b.flags|=4}};Ei=function(a,b,c,d){c!==d&&(b.flags|=4)};function Fi(a,b){if(!lh)switch(a.tailMode){case "hidden":b=a.tail;for(var c=null;null!==b;)null!==b.alternate&&(c=b),b=b.sibling;null===c?a.tail=null:c.sibling=null;break;case "collapsed":c=a.tail;for(var d=null;null!==c;)null!==c.alternate&&(d=c),c=c.sibling;null===d?b||null===a.tail?a.tail=null:a.tail.sibling=null:d.sibling=null}}
function Gi(a,b,c){var d=b.pendingProps;switch(b.tag){case 2:case 16:case 15:case 0:case 11:case 7:case 8:case 12:case 9:case 14:return null;case 1:return Ff(b.type)&&Gf(),null;case 3:fh();H(N);H(M);uh();d=b.stateNode;d.pendingContext&&(d.context=d.pendingContext,d.pendingContext=null);if(null===a||null===a.child)rh(b)?b.flags|=4:d.hydrate||(b.flags|=256);Ci(b);return null;case 5:hh(b);var e=dh(ch.current);c=b.type;if(null!==a&&null!=b.stateNode)Di(a,b,c,d,e),a.ref!==b.ref&&(b.flags|=128);else{if(!d){if(null===
b.stateNode)throw Error(y(166));return null}a=dh(ah.current);if(rh(b)){d=b.stateNode;c=b.type;var f=b.memoizedProps;d[wf]=b;d[xf]=f;switch(c){case "dialog":G("cancel",d);G("close",d);break;case "iframe":case "object":case "embed":G("load",d);break;case "video":case "audio":for(a=0;a<Xe.length;a++)G(Xe[a],d);break;case "source":G("error",d);break;case "img":case "image":case "link":G("error",d);G("load",d);break;case "details":G("toggle",d);break;case "input":Za(d,f);G("invalid",d);break;case "select":d._wrapperState=
{wasMultiple:!!f.multiple};G("invalid",d);break;case "textarea":hb(d,f),G("invalid",d)}vb(c,f);a=null;for(var g in f)f.hasOwnProperty(g)&&(e=f[g],"children"===g?"string"===typeof e?d.textContent!==e&&(a=["children",e]):"number"===typeof e&&d.textContent!==""+e&&(a=["children",""+e]):ca.hasOwnProperty(g)&&null!=e&&"onScroll"===g&&G("scroll",d));switch(c){case "input":Va(d);cb(d,f,!0);break;case "textarea":Va(d);jb(d);break;case "select":case "option":break;default:"function"===typeof f.onClick&&(d.onclick=
jf)}d=a;b.updateQueue=d;null!==d&&(b.flags|=4)}else{g=9===e.nodeType?e:e.ownerDocument;a===kb.html&&(a=lb(c));a===kb.html?"script"===c?(a=g.createElement("div"),a.innerHTML="<script>\x3c/script>",a=a.removeChild(a.firstChild)):"string"===typeof d.is?a=g.createElement(c,{is:d.is}):(a=g.createElement(c),"select"===c&&(g=a,d.multiple?g.multiple=!0:d.size&&(g.size=d.size))):a=g.createElementNS(a,c);a[wf]=b;a[xf]=d;Bi(a,b,!1,!1);b.stateNode=a;g=wb(c,d);switch(c){case "dialog":G("cancel",a);G("close",a);
e=d;break;case "iframe":case "object":case "embed":G("load",a);e=d;break;case "video":case "audio":for(e=0;e<Xe.length;e++)G(Xe[e],a);e=d;break;case "source":G("error",a);e=d;break;case "img":case "image":case "link":G("error",a);G("load",a);e=d;break;case "details":G("toggle",a);e=d;break;case "input":Za(a,d);e=Ya(a,d);G("invalid",a);break;case "option":e=eb(a,d);break;case "select":a._wrapperState={wasMultiple:!!d.multiple};e=m({},d,{value:void 0});G("invalid",a);break;case "textarea":hb(a,d);e=
gb(a,d);G("invalid",a);break;default:e=d}vb(c,e);var h=e;for(f in h)if(h.hasOwnProperty(f)){var k=h[f];"style"===f?tb(a,k):"dangerouslySetInnerHTML"===f?(k=k?k.__html:void 0,null!=k&&ob(a,k)):"children"===f?"string"===typeof k?("textarea"!==c||""!==k)&&pb(a,k):"number"===typeof k&&pb(a,""+k):"suppressContentEditableWarning"!==f&&"suppressHydrationWarning"!==f&&"autoFocus"!==f&&(ca.hasOwnProperty(f)?null!=k&&"onScroll"===f&&G("scroll",a):null!=k&&qa(a,f,k,g))}switch(c){case "input":Va(a);cb(a,d,!1);
break;case "textarea":Va(a);jb(a);break;case "option":null!=d.value&&a.setAttribute("value",""+Sa(d.value));break;case "select":a.multiple=!!d.multiple;f=d.value;null!=f?fb(a,!!d.multiple,f,!1):null!=d.defaultValue&&fb(a,!!d.multiple,d.defaultValue,!0);break;default:"function"===typeof e.onClick&&(a.onclick=jf)}mf(c,d)&&(b.flags|=4)}null!==b.ref&&(b.flags|=128)}return null;case 6:if(a&&null!=b.stateNode)Ei(a,b,a.memoizedProps,d);else{if("string"!==typeof d&&null===b.stateNode)throw Error(y(166));
c=dh(ch.current);dh(ah.current);rh(b)?(d=b.stateNode,c=b.memoizedProps,d[wf]=b,d.nodeValue!==c&&(b.flags|=4)):(d=(9===c.nodeType?c:c.ownerDocument).createTextNode(d),d[wf]=b,b.stateNode=d)}return null;case 13:H(P);d=b.memoizedState;if(0!==(b.flags&64))return b.lanes=c,b;d=null!==d;c=!1;null===a?void 0!==b.memoizedProps.fallback&&rh(b):c=null!==a.memoizedState;if(d&&!c&&0!==(b.mode&2))if(null===a&&!0!==b.memoizedProps.unstable_avoidThisFallback||0!==(P.current&1))0===V&&(V=3);else{if(0===V||3===V)V=
4;null===U||0===(Dg&134217727)&&0===(Hi&134217727)||Ii(U,W)}if(d||c)b.flags|=4;return null;case 4:return fh(),Ci(b),null===a&&cf(b.stateNode.containerInfo),null;case 10:return rg(b),null;case 17:return Ff(b.type)&&Gf(),null;case 19:H(P);d=b.memoizedState;if(null===d)return null;f=0!==(b.flags&64);g=d.rendering;if(null===g)if(f)Fi(d,!1);else{if(0!==V||null!==a&&0!==(a.flags&64))for(a=b.child;null!==a;){g=ih(a);if(null!==g){b.flags|=64;Fi(d,!1);f=g.updateQueue;null!==f&&(b.updateQueue=f,b.flags|=4);
null===d.lastEffect&&(b.firstEffect=null);b.lastEffect=d.lastEffect;d=c;for(c=b.child;null!==c;)f=c,a=d,f.flags&=2,f.nextEffect=null,f.firstEffect=null,f.lastEffect=null,g=f.alternate,null===g?(f.childLanes=0,f.lanes=a,f.child=null,f.memoizedProps=null,f.memoizedState=null,f.updateQueue=null,f.dependencies=null,f.stateNode=null):(f.childLanes=g.childLanes,f.lanes=g.lanes,f.child=g.child,f.memoizedProps=g.memoizedProps,f.memoizedState=g.memoizedState,f.updateQueue=g.updateQueue,f.type=g.type,a=g.dependencies,
f.dependencies=null===a?null:{lanes:a.lanes,firstContext:a.firstContext}),c=c.sibling;I(P,P.current&1|2);return b.child}a=a.sibling}null!==d.tail&&O()>Ji&&(b.flags|=64,f=!0,Fi(d,!1),b.lanes=33554432)}else{if(!f)if(a=ih(g),null!==a){if(b.flags|=64,f=!0,c=a.updateQueue,null!==c&&(b.updateQueue=c,b.flags|=4),Fi(d,!0),null===d.tail&&"hidden"===d.tailMode&&!g.alternate&&!lh)return b=b.lastEffect=d.lastEffect,null!==b&&(b.nextEffect=null),null}else 2*O()-d.renderingStartTime>Ji&&1073741824!==c&&(b.flags|=
64,f=!0,Fi(d,!1),b.lanes=33554432);d.isBackwards?(g.sibling=b.child,b.child=g):(c=d.last,null!==c?c.sibling=g:b.child=g,d.last=g)}return null!==d.tail?(c=d.tail,d.rendering=c,d.tail=c.sibling,d.lastEffect=b.lastEffect,d.renderingStartTime=O(),c.sibling=null,b=P.current,I(P,f?b&1|2:b&1),c):null;case 23:case 24:return Ki(),null!==a&&null!==a.memoizedState!==(null!==b.memoizedState)&&"unstable-defer-without-hiding"!==d.mode&&(b.flags|=4),null}throw Error(y(156,b.tag));}
function Li(a){switch(a.tag){case 1:Ff(a.type)&&Gf();var b=a.flags;return b&4096?(a.flags=b&-4097|64,a):null;case 3:fh();H(N);H(M);uh();b=a.flags;if(0!==(b&64))throw Error(y(285));a.flags=b&-4097|64;return a;case 5:return hh(a),null;case 13:return H(P),b=a.flags,b&4096?(a.flags=b&-4097|64,a):null;case 19:return H(P),null;case 4:return fh(),null;case 10:return rg(a),null;case 23:case 24:return Ki(),null;default:return null}}
function Mi(a,b){try{var c="",d=b;do c+=Qa(d),d=d.return;while(d);var e=c}catch(f){e="\nError generating stack: "+f.message+"\n"+f.stack}return{value:a,source:b,stack:e}}function Ni(a,b){try{console.error(b.value)}catch(c){setTimeout(function(){throw c;})}}var Oi="function"===typeof WeakMap?WeakMap:Map;function Pi(a,b,c){c=zg(-1,c);c.tag=3;c.payload={element:null};var d=b.value;c.callback=function(){Qi||(Qi=!0,Ri=d);Ni(a,b)};return c}
function Si(a,b,c){c=zg(-1,c);c.tag=3;var d=a.type.getDerivedStateFromError;if("function"===typeof d){var e=b.value;c.payload=function(){Ni(a,b);return d(e)}}var f=a.stateNode;null!==f&&"function"===typeof f.componentDidCatch&&(c.callback=function(){"function"!==typeof d&&(null===Ti?Ti=new Set([this]):Ti.add(this),Ni(a,b));var c=b.stack;this.componentDidCatch(b.value,{componentStack:null!==c?c:""})});return c}var Ui="function"===typeof WeakSet?WeakSet:Set;
function Vi(a){var b=a.ref;if(null!==b)if("function"===typeof b)try{b(null)}catch(c){Wi(a,c)}else b.current=null}function Xi(a,b){switch(b.tag){case 0:case 11:case 15:case 22:return;case 1:if(b.flags&256&&null!==a){var c=a.memoizedProps,d=a.memoizedState;a=b.stateNode;b=a.getSnapshotBeforeUpdate(b.elementType===b.type?c:lg(b.type,c),d);a.__reactInternalSnapshotBeforeUpdate=b}return;case 3:b.flags&256&&qf(b.stateNode.containerInfo);return;case 5:case 6:case 4:case 17:return}throw Error(y(163));}
function Yi(a,b,c){switch(c.tag){case 0:case 11:case 15:case 22:b=c.updateQueue;b=null!==b?b.lastEffect:null;if(null!==b){a=b=b.next;do{if(3===(a.tag&3)){var d=a.create;a.destroy=d()}a=a.next}while(a!==b)}b=c.updateQueue;b=null!==b?b.lastEffect:null;if(null!==b){a=b=b.next;do{var e=a;d=e.next;e=e.tag;0!==(e&4)&&0!==(e&1)&&(Zi(c,a),$i(c,a));a=d}while(a!==b)}return;case 1:a=c.stateNode;c.flags&4&&(null===b?a.componentDidMount():(d=c.elementType===c.type?b.memoizedProps:lg(c.type,b.memoizedProps),a.componentDidUpdate(d,
b.memoizedState,a.__reactInternalSnapshotBeforeUpdate)));b=c.updateQueue;null!==b&&Eg(c,b,a);return;case 3:b=c.updateQueue;if(null!==b){a=null;if(null!==c.child)switch(c.child.tag){case 5:a=c.child.stateNode;break;case 1:a=c.child.stateNode}Eg(c,b,a)}return;case 5:a=c.stateNode;null===b&&c.flags&4&&mf(c.type,c.memoizedProps)&&a.focus();return;case 6:return;case 4:return;case 12:return;case 13:null===c.memoizedState&&(c=c.alternate,null!==c&&(c=c.memoizedState,null!==c&&(c=c.dehydrated,null!==c&&Cc(c))));
return;case 19:case 17:case 20:case 21:case 23:case 24:return}throw Error(y(163));}
function aj(a,b){for(var c=a;;){if(5===c.tag){var d=c.stateNode;if(b)d=d.style,"function"===typeof d.setProperty?d.setProperty("display","none","important"):d.display="none";else{d=c.stateNode;var e=c.memoizedProps.style;e=void 0!==e&&null!==e&&e.hasOwnProperty("display")?e.display:null;d.style.display=sb("display",e)}}else if(6===c.tag)c.stateNode.nodeValue=b?"":c.memoizedProps;else if((23!==c.tag&&24!==c.tag||null===c.memoizedState||c===a)&&null!==c.child){c.child.return=c;c=c.child;continue}if(c===
a)break;for(;null===c.sibling;){if(null===c.return||c.return===a)return;c=c.return}c.sibling.return=c.return;c=c.sibling}}
function bj(a,b){if(Mf&&"function"===typeof Mf.onCommitFiberUnmount)try{Mf.onCommitFiberUnmount(Lf,b)}catch(f){}switch(b.tag){case 0:case 11:case 14:case 15:case 22:a=b.updateQueue;if(null!==a&&(a=a.lastEffect,null!==a)){var c=a=a.next;do{var d=c,e=d.destroy;d=d.tag;if(void 0!==e)if(0!==(d&4))Zi(b,c);else{d=b;try{e()}catch(f){Wi(d,f)}}c=c.next}while(c!==a)}break;case 1:Vi(b);a=b.stateNode;if("function"===typeof a.componentWillUnmount)try{a.props=b.memoizedProps,a.state=b.memoizedState,a.componentWillUnmount()}catch(f){Wi(b,
f)}break;case 5:Vi(b);break;case 4:cj(a,b)}}function dj(a){a.alternate=null;a.child=null;a.dependencies=null;a.firstEffect=null;a.lastEffect=null;a.memoizedProps=null;a.memoizedState=null;a.pendingProps=null;a.return=null;a.updateQueue=null}function ej(a){return 5===a.tag||3===a.tag||4===a.tag}
function fj(a){a:{for(var b=a.return;null!==b;){if(ej(b))break a;b=b.return}throw Error(y(160));}var c=b;b=c.stateNode;switch(c.tag){case 5:var d=!1;break;case 3:b=b.containerInfo;d=!0;break;case 4:b=b.containerInfo;d=!0;break;default:throw Error(y(161));}c.flags&16&&(pb(b,""),c.flags&=-17);a:b:for(c=a;;){for(;null===c.sibling;){if(null===c.return||ej(c.return)){c=null;break a}c=c.return}c.sibling.return=c.return;for(c=c.sibling;5!==c.tag&&6!==c.tag&&18!==c.tag;){if(c.flags&2)continue b;if(null===
c.child||4===c.tag)continue b;else c.child.return=c,c=c.child}if(!(c.flags&2)){c=c.stateNode;break a}}d?gj(a,c,b):hj(a,c,b)}
function gj(a,b,c){var d=a.tag,e=5===d||6===d;if(e)a=e?a.stateNode:a.stateNode.instance,b?8===c.nodeType?c.parentNode.insertBefore(a,b):c.insertBefore(a,b):(8===c.nodeType?(b=c.parentNode,b.insertBefore(a,c)):(b=c,b.appendChild(a)),c=c._reactRootContainer,null!==c&&void 0!==c||null!==b.onclick||(b.onclick=jf));else if(4!==d&&(a=a.child,null!==a))for(gj(a,b,c),a=a.sibling;null!==a;)gj(a,b,c),a=a.sibling}
function hj(a,b,c){var d=a.tag,e=5===d||6===d;if(e)a=e?a.stateNode:a.stateNode.instance,b?c.insertBefore(a,b):c.appendChild(a);else if(4!==d&&(a=a.child,null!==a))for(hj(a,b,c),a=a.sibling;null!==a;)hj(a,b,c),a=a.sibling}
function cj(a,b){for(var c=b,d=!1,e,f;;){if(!d){d=c.return;a:for(;;){if(null===d)throw Error(y(160));e=d.stateNode;switch(d.tag){case 5:f=!1;break a;case 3:e=e.containerInfo;f=!0;break a;case 4:e=e.containerInfo;f=!0;break a}d=d.return}d=!0}if(5===c.tag||6===c.tag){a:for(var g=a,h=c,k=h;;)if(bj(g,k),null!==k.child&&4!==k.tag)k.child.return=k,k=k.child;else{if(k===h)break a;for(;null===k.sibling;){if(null===k.return||k.return===h)break a;k=k.return}k.sibling.return=k.return;k=k.sibling}f?(g=e,h=c.stateNode,
8===g.nodeType?g.parentNode.removeChild(h):g.removeChild(h)):e.removeChild(c.stateNode)}else if(4===c.tag){if(null!==c.child){e=c.stateNode.containerInfo;f=!0;c.child.return=c;c=c.child;continue}}else if(bj(a,c),null!==c.child){c.child.return=c;c=c.child;continue}if(c===b)break;for(;null===c.sibling;){if(null===c.return||c.return===b)return;c=c.return;4===c.tag&&(d=!1)}c.sibling.return=c.return;c=c.sibling}}
function ij(a,b){switch(b.tag){case 0:case 11:case 14:case 15:case 22:var c=b.updateQueue;c=null!==c?c.lastEffect:null;if(null!==c){var d=c=c.next;do 3===(d.tag&3)&&(a=d.destroy,d.destroy=void 0,void 0!==a&&a()),d=d.next;while(d!==c)}return;case 1:return;case 5:c=b.stateNode;if(null!=c){d=b.memoizedProps;var e=null!==a?a.memoizedProps:d;a=b.type;var f=b.updateQueue;b.updateQueue=null;if(null!==f){c[xf]=d;"input"===a&&"radio"===d.type&&null!=d.name&&$a(c,d);wb(a,e);b=wb(a,d);for(e=0;e<f.length;e+=
2){var g=f[e],h=f[e+1];"style"===g?tb(c,h):"dangerouslySetInnerHTML"===g?ob(c,h):"children"===g?pb(c,h):qa(c,g,h,b)}switch(a){case "input":ab(c,d);break;case "textarea":ib(c,d);break;case "select":a=c._wrapperState.wasMultiple,c._wrapperState.wasMultiple=!!d.multiple,f=d.value,null!=f?fb(c,!!d.multiple,f,!1):a!==!!d.multiple&&(null!=d.defaultValue?fb(c,!!d.multiple,d.defaultValue,!0):fb(c,!!d.multiple,d.multiple?[]:"",!1))}}}return;case 6:if(null===b.stateNode)throw Error(y(162));b.stateNode.nodeValue=
b.memoizedProps;return;case 3:c=b.stateNode;c.hydrate&&(c.hydrate=!1,Cc(c.containerInfo));return;case 12:return;case 13:null!==b.memoizedState&&(jj=O(),aj(b.child,!0));kj(b);return;case 19:kj(b);return;case 17:return;case 23:case 24:aj(b,null!==b.memoizedState);return}throw Error(y(163));}function kj(a){var b=a.updateQueue;if(null!==b){a.updateQueue=null;var c=a.stateNode;null===c&&(c=a.stateNode=new Ui);b.forEach(function(b){var d=lj.bind(null,a,b);c.has(b)||(c.add(b),b.then(d,d))})}}
function mj(a,b){return null!==a&&(a=a.memoizedState,null===a||null!==a.dehydrated)?(b=b.memoizedState,null!==b&&null===b.dehydrated):!1}var nj=Math.ceil,oj=ra.ReactCurrentDispatcher,pj=ra.ReactCurrentOwner,X=0,U=null,Y=null,W=0,qj=0,rj=Bf(0),V=0,sj=null,tj=0,Dg=0,Hi=0,uj=0,vj=null,jj=0,Ji=Infinity;function wj(){Ji=O()+500}var Z=null,Qi=!1,Ri=null,Ti=null,xj=!1,yj=null,zj=90,Aj=[],Bj=[],Cj=null,Dj=0,Ej=null,Fj=-1,Gj=0,Hj=0,Ij=null,Jj=!1;function Hg(){return 0!==(X&48)?O():-1!==Fj?Fj:Fj=O()}
function Ig(a){a=a.mode;if(0===(a&2))return 1;if(0===(a&4))return 99===eg()?1:2;0===Gj&&(Gj=tj);if(0!==kg.transition){0!==Hj&&(Hj=null!==vj?vj.pendingLanes:0);a=Gj;var b=4186112&~Hj;b&=-b;0===b&&(a=4186112&~a,b=a&-a,0===b&&(b=8192));return b}a=eg();0!==(X&4)&&98===a?a=Xc(12,Gj):(a=Sc(a),a=Xc(a,Gj));return a}
function Jg(a,b,c){if(50<Dj)throw Dj=0,Ej=null,Error(y(185));a=Kj(a,b);if(null===a)return null;$c(a,b,c);a===U&&(Hi|=b,4===V&&Ii(a,W));var d=eg();1===b?0!==(X&8)&&0===(X&48)?Lj(a):(Mj(a,c),0===X&&(wj(),ig())):(0===(X&4)||98!==d&&99!==d||(null===Cj?Cj=new Set([a]):Cj.add(a)),Mj(a,c));vj=a}function Kj(a,b){a.lanes|=b;var c=a.alternate;null!==c&&(c.lanes|=b);c=a;for(a=a.return;null!==a;)a.childLanes|=b,c=a.alternate,null!==c&&(c.childLanes|=b),c=a,a=a.return;return 3===c.tag?c.stateNode:null}
function Mj(a,b){for(var c=a.callbackNode,d=a.suspendedLanes,e=a.pingedLanes,f=a.expirationTimes,g=a.pendingLanes;0<g;){var h=31-Vc(g),k=1<<h,l=f[h];if(-1===l){if(0===(k&d)||0!==(k&e)){l=b;Rc(k);var n=F;f[h]=10<=n?l+250:6<=n?l+5E3:-1}}else l<=b&&(a.expiredLanes|=k);g&=~k}d=Uc(a,a===U?W:0);b=F;if(0===d)null!==c&&(c!==Zf&&Pf(c),a.callbackNode=null,a.callbackPriority=0);else{if(null!==c){if(a.callbackPriority===b)return;c!==Zf&&Pf(c)}15===b?(c=Lj.bind(null,a),null===ag?(ag=[c],bg=Of(Uf,jg)):ag.push(c),
c=Zf):14===b?c=hg(99,Lj.bind(null,a)):(c=Tc(b),c=hg(c,Nj.bind(null,a)));a.callbackPriority=b;a.callbackNode=c}}
function Nj(a){Fj=-1;Hj=Gj=0;if(0!==(X&48))throw Error(y(327));var b=a.callbackNode;if(Oj()&&a.callbackNode!==b)return null;var c=Uc(a,a===U?W:0);if(0===c)return null;var d=c;var e=X;X|=16;var f=Pj();if(U!==a||W!==d)wj(),Qj(a,d);do try{Rj();break}catch(h){Sj(a,h)}while(1);qg();oj.current=f;X=e;null!==Y?d=0:(U=null,W=0,d=V);if(0!==(tj&Hi))Qj(a,0);else if(0!==d){2===d&&(X|=64,a.hydrate&&(a.hydrate=!1,qf(a.containerInfo)),c=Wc(a),0!==c&&(d=Tj(a,c)));if(1===d)throw b=sj,Qj(a,0),Ii(a,c),Mj(a,O()),b;a.finishedWork=
a.current.alternate;a.finishedLanes=c;switch(d){case 0:case 1:throw Error(y(345));case 2:Uj(a);break;case 3:Ii(a,c);if((c&62914560)===c&&(d=jj+500-O(),10<d)){if(0!==Uc(a,0))break;e=a.suspendedLanes;if((e&c)!==c){Hg();a.pingedLanes|=a.suspendedLanes&e;break}a.timeoutHandle=of(Uj.bind(null,a),d);break}Uj(a);break;case 4:Ii(a,c);if((c&4186112)===c)break;d=a.eventTimes;for(e=-1;0<c;){var g=31-Vc(c);f=1<<g;g=d[g];g>e&&(e=g);c&=~f}c=e;c=O()-c;c=(120>c?120:480>c?480:1080>c?1080:1920>c?1920:3E3>c?3E3:4320>
c?4320:1960*nj(c/1960))-c;if(10<c){a.timeoutHandle=of(Uj.bind(null,a),c);break}Uj(a);break;case 5:Uj(a);break;default:throw Error(y(329));}}Mj(a,O());return a.callbackNode===b?Nj.bind(null,a):null}function Ii(a,b){b&=~uj;b&=~Hi;a.suspendedLanes|=b;a.pingedLanes&=~b;for(a=a.expirationTimes;0<b;){var c=31-Vc(b),d=1<<c;a[c]=-1;b&=~d}}
function Lj(a){if(0!==(X&48))throw Error(y(327));Oj();if(a===U&&0!==(a.expiredLanes&W)){var b=W;var c=Tj(a,b);0!==(tj&Hi)&&(b=Uc(a,b),c=Tj(a,b))}else b=Uc(a,0),c=Tj(a,b);0!==a.tag&&2===c&&(X|=64,a.hydrate&&(a.hydrate=!1,qf(a.containerInfo)),b=Wc(a),0!==b&&(c=Tj(a,b)));if(1===c)throw c=sj,Qj(a,0),Ii(a,b),Mj(a,O()),c;a.finishedWork=a.current.alternate;a.finishedLanes=b;Uj(a);Mj(a,O());return null}
function Vj(){if(null!==Cj){var a=Cj;Cj=null;a.forEach(function(a){a.expiredLanes|=24&a.pendingLanes;Mj(a,O())})}ig()}function Wj(a,b){var c=X;X|=1;try{return a(b)}finally{X=c,0===X&&(wj(),ig())}}function Xj(a,b){var c=X;X&=-2;X|=8;try{return a(b)}finally{X=c,0===X&&(wj(),ig())}}function ni(a,b){I(rj,qj);qj|=b;tj|=b}function Ki(){qj=rj.current;H(rj)}
function Qj(a,b){a.finishedWork=null;a.finishedLanes=0;var c=a.timeoutHandle;-1!==c&&(a.timeoutHandle=-1,pf(c));if(null!==Y)for(c=Y.return;null!==c;){var d=c;switch(d.tag){case 1:d=d.type.childContextTypes;null!==d&&void 0!==d&&Gf();break;case 3:fh();H(N);H(M);uh();break;case 5:hh(d);break;case 4:fh();break;case 13:H(P);break;case 19:H(P);break;case 10:rg(d);break;case 23:case 24:Ki()}c=c.return}U=a;Y=Tg(a.current,null);W=qj=tj=b;V=0;sj=null;uj=Hi=Dg=0}
function Sj(a,b){do{var c=Y;try{qg();vh.current=Gh;if(yh){for(var d=R.memoizedState;null!==d;){var e=d.queue;null!==e&&(e.pending=null);d=d.next}yh=!1}xh=0;T=S=R=null;zh=!1;pj.current=null;if(null===c||null===c.return){V=1;sj=b;Y=null;break}a:{var f=a,g=c.return,h=c,k=b;b=W;h.flags|=2048;h.firstEffect=h.lastEffect=null;if(null!==k&&"object"===typeof k&&"function"===typeof k.then){var l=k;if(0===(h.mode&2)){var n=h.alternate;n?(h.updateQueue=n.updateQueue,h.memoizedState=n.memoizedState,h.lanes=n.lanes):
(h.updateQueue=null,h.memoizedState=null)}var A=0!==(P.current&1),p=g;do{var C;if(C=13===p.tag){var x=p.memoizedState;if(null!==x)C=null!==x.dehydrated?!0:!1;else{var w=p.memoizedProps;C=void 0===w.fallback?!1:!0!==w.unstable_avoidThisFallback?!0:A?!1:!0}}if(C){var z=p.updateQueue;if(null===z){var u=new Set;u.add(l);p.updateQueue=u}else z.add(l);if(0===(p.mode&2)){p.flags|=64;h.flags|=16384;h.flags&=-2981;if(1===h.tag)if(null===h.alternate)h.tag=17;else{var t=zg(-1,1);t.tag=2;Ag(h,t)}h.lanes|=1;break a}k=
void 0;h=b;var q=f.pingCache;null===q?(q=f.pingCache=new Oi,k=new Set,q.set(l,k)):(k=q.get(l),void 0===k&&(k=new Set,q.set(l,k)));if(!k.has(h)){k.add(h);var v=Yj.bind(null,f,l,h);l.then(v,v)}p.flags|=4096;p.lanes=b;break a}p=p.return}while(null!==p);k=Error((Ra(h.type)||"A React component")+" suspended while rendering, but no fallback UI was specified.\n\nAdd a <Suspense fallback=...> component higher in the tree to provide a loading indicator or placeholder to display.")}5!==V&&(V=2);k=Mi(k,h);p=
g;do{switch(p.tag){case 3:f=k;p.flags|=4096;b&=-b;p.lanes|=b;var J=Pi(p,f,b);Bg(p,J);break a;case 1:f=k;var K=p.type,Q=p.stateNode;if(0===(p.flags&64)&&("function"===typeof K.getDerivedStateFromError||null!==Q&&"function"===typeof Q.componentDidCatch&&(null===Ti||!Ti.has(Q)))){p.flags|=4096;b&=-b;p.lanes|=b;var L=Si(p,f,b);Bg(p,L);break a}}p=p.return}while(null!==p)}Zj(c)}catch(va){b=va;Y===c&&null!==c&&(Y=c=c.return);continue}break}while(1)}
function Pj(){var a=oj.current;oj.current=Gh;return null===a?Gh:a}function Tj(a,b){var c=X;X|=16;var d=Pj();U===a&&W===b||Qj(a,b);do try{ak();break}catch(e){Sj(a,e)}while(1);qg();X=c;oj.current=d;if(null!==Y)throw Error(y(261));U=null;W=0;return V}function ak(){for(;null!==Y;)bk(Y)}function Rj(){for(;null!==Y&&!Qf();)bk(Y)}function bk(a){var b=ck(a.alternate,a,qj);a.memoizedProps=a.pendingProps;null===b?Zj(a):Y=b;pj.current=null}
function Zj(a){var b=a;do{var c=b.alternate;a=b.return;if(0===(b.flags&2048)){c=Gi(c,b,qj);if(null!==c){Y=c;return}c=b;if(24!==c.tag&&23!==c.tag||null===c.memoizedState||0!==(qj&1073741824)||0===(c.mode&4)){for(var d=0,e=c.child;null!==e;)d|=e.lanes|e.childLanes,e=e.sibling;c.childLanes=d}null!==a&&0===(a.flags&2048)&&(null===a.firstEffect&&(a.firstEffect=b.firstEffect),null!==b.lastEffect&&(null!==a.lastEffect&&(a.lastEffect.nextEffect=b.firstEffect),a.lastEffect=b.lastEffect),1<b.flags&&(null!==
a.lastEffect?a.lastEffect.nextEffect=b:a.firstEffect=b,a.lastEffect=b))}else{c=Li(b);if(null!==c){c.flags&=2047;Y=c;return}null!==a&&(a.firstEffect=a.lastEffect=null,a.flags|=2048)}b=b.sibling;if(null!==b){Y=b;return}Y=b=a}while(null!==b);0===V&&(V=5)}function Uj(a){var b=eg();gg(99,dk.bind(null,a,b));return null}
function dk(a,b){do Oj();while(null!==yj);if(0!==(X&48))throw Error(y(327));var c=a.finishedWork;if(null===c)return null;a.finishedWork=null;a.finishedLanes=0;if(c===a.current)throw Error(y(177));a.callbackNode=null;var d=c.lanes|c.childLanes,e=d,f=a.pendingLanes&~e;a.pendingLanes=e;a.suspendedLanes=0;a.pingedLanes=0;a.expiredLanes&=e;a.mutableReadLanes&=e;a.entangledLanes&=e;e=a.entanglements;for(var g=a.eventTimes,h=a.expirationTimes;0<f;){var k=31-Vc(f),l=1<<k;e[k]=0;g[k]=-1;h[k]=-1;f&=~l}null!==
Cj&&0===(d&24)&&Cj.has(a)&&Cj.delete(a);a===U&&(Y=U=null,W=0);1<c.flags?null!==c.lastEffect?(c.lastEffect.nextEffect=c,d=c.firstEffect):d=c:d=c.firstEffect;if(null!==d){e=X;X|=32;pj.current=null;kf=fd;g=Ne();if(Oe(g)){if("selectionStart"in g)h={start:g.selectionStart,end:g.selectionEnd};else a:if(h=(h=g.ownerDocument)&&h.defaultView||window,(l=h.getSelection&&h.getSelection())&&0!==l.rangeCount){h=l.anchorNode;f=l.anchorOffset;k=l.focusNode;l=l.focusOffset;try{h.nodeType,k.nodeType}catch(va){h=null;
break a}var n=0,A=-1,p=-1,C=0,x=0,w=g,z=null;b:for(;;){for(var u;;){w!==h||0!==f&&3!==w.nodeType||(A=n+f);w!==k||0!==l&&3!==w.nodeType||(p=n+l);3===w.nodeType&&(n+=w.nodeValue.length);if(null===(u=w.firstChild))break;z=w;w=u}for(;;){if(w===g)break b;z===h&&++C===f&&(A=n);z===k&&++x===l&&(p=n);if(null!==(u=w.nextSibling))break;w=z;z=w.parentNode}w=u}h=-1===A||-1===p?null:{start:A,end:p}}else h=null;h=h||{start:0,end:0}}else h=null;lf={focusedElem:g,selectionRange:h};fd=!1;Ij=null;Jj=!1;Z=d;do try{ek()}catch(va){if(null===
Z)throw Error(y(330));Wi(Z,va);Z=Z.nextEffect}while(null!==Z);Ij=null;Z=d;do try{for(g=a;null!==Z;){var t=Z.flags;t&16&&pb(Z.stateNode,"");if(t&128){var q=Z.alternate;if(null!==q){var v=q.ref;null!==v&&("function"===typeof v?v(null):v.current=null)}}switch(t&1038){case 2:fj(Z);Z.flags&=-3;break;case 6:fj(Z);Z.flags&=-3;ij(Z.alternate,Z);break;case 1024:Z.flags&=-1025;break;case 1028:Z.flags&=-1025;ij(Z.alternate,Z);break;case 4:ij(Z.alternate,Z);break;case 8:h=Z;cj(g,h);var J=h.alternate;dj(h);null!==
J&&dj(J)}Z=Z.nextEffect}}catch(va){if(null===Z)throw Error(y(330));Wi(Z,va);Z=Z.nextEffect}while(null!==Z);v=lf;q=Ne();t=v.focusedElem;g=v.selectionRange;if(q!==t&&t&&t.ownerDocument&&Me(t.ownerDocument.documentElement,t)){null!==g&&Oe(t)&&(q=g.start,v=g.end,void 0===v&&(v=q),"selectionStart"in t?(t.selectionStart=q,t.selectionEnd=Math.min(v,t.value.length)):(v=(q=t.ownerDocument||document)&&q.defaultView||window,v.getSelection&&(v=v.getSelection(),h=t.textContent.length,J=Math.min(g.start,h),g=void 0===
g.end?J:Math.min(g.end,h),!v.extend&&J>g&&(h=g,g=J,J=h),h=Le(t,J),f=Le(t,g),h&&f&&(1!==v.rangeCount||v.anchorNode!==h.node||v.anchorOffset!==h.offset||v.focusNode!==f.node||v.focusOffset!==f.offset)&&(q=q.createRange(),q.setStart(h.node,h.offset),v.removeAllRanges(),J>g?(v.addRange(q),v.extend(f.node,f.offset)):(q.setEnd(f.node,f.offset),v.addRange(q))))));q=[];for(v=t;v=v.parentNode;)1===v.nodeType&&q.push({element:v,left:v.scrollLeft,top:v.scrollTop});"function"===typeof t.focus&&t.focus();for(t=
0;t<q.length;t++)v=q[t],v.element.scrollLeft=v.left,v.element.scrollTop=v.top}fd=!!kf;lf=kf=null;a.current=c;Z=d;do try{for(t=a;null!==Z;){var K=Z.flags;K&36&&Yi(t,Z.alternate,Z);if(K&128){q=void 0;var Q=Z.ref;if(null!==Q){var L=Z.stateNode;switch(Z.tag){case 5:q=L;break;default:q=L}"function"===typeof Q?Q(q):Q.current=q}}Z=Z.nextEffect}}catch(va){if(null===Z)throw Error(y(330));Wi(Z,va);Z=Z.nextEffect}while(null!==Z);Z=null;$f();X=e}else a.current=c;if(xj)xj=!1,yj=a,zj=b;else for(Z=d;null!==Z;)b=
Z.nextEffect,Z.nextEffect=null,Z.flags&8&&(K=Z,K.sibling=null,K.stateNode=null),Z=b;d=a.pendingLanes;0===d&&(Ti=null);1===d?a===Ej?Dj++:(Dj=0,Ej=a):Dj=0;c=c.stateNode;if(Mf&&"function"===typeof Mf.onCommitFiberRoot)try{Mf.onCommitFiberRoot(Lf,c,void 0,64===(c.current.flags&64))}catch(va){}Mj(a,O());if(Qi)throw Qi=!1,a=Ri,Ri=null,a;if(0!==(X&8))return null;ig();return null}
function ek(){for(;null!==Z;){var a=Z.alternate;Jj||null===Ij||(0!==(Z.flags&8)?dc(Z,Ij)&&(Jj=!0):13===Z.tag&&mj(a,Z)&&dc(Z,Ij)&&(Jj=!0));var b=Z.flags;0!==(b&256)&&Xi(a,Z);0===(b&512)||xj||(xj=!0,hg(97,function(){Oj();return null}));Z=Z.nextEffect}}function Oj(){if(90!==zj){var a=97<zj?97:zj;zj=90;return gg(a,fk)}return!1}function $i(a,b){Aj.push(b,a);xj||(xj=!0,hg(97,function(){Oj();return null}))}function Zi(a,b){Bj.push(b,a);xj||(xj=!0,hg(97,function(){Oj();return null}))}
function fk(){if(null===yj)return!1;var a=yj;yj=null;if(0!==(X&48))throw Error(y(331));var b=X;X|=32;var c=Bj;Bj=[];for(var d=0;d<c.length;d+=2){var e=c[d],f=c[d+1],g=e.destroy;e.destroy=void 0;if("function"===typeof g)try{g()}catch(k){if(null===f)throw Error(y(330));Wi(f,k)}}c=Aj;Aj=[];for(d=0;d<c.length;d+=2){e=c[d];f=c[d+1];try{var h=e.create;e.destroy=h()}catch(k){if(null===f)throw Error(y(330));Wi(f,k)}}for(h=a.current.firstEffect;null!==h;)a=h.nextEffect,h.nextEffect=null,h.flags&8&&(h.sibling=
null,h.stateNode=null),h=a;X=b;ig();return!0}function gk(a,b,c){b=Mi(c,b);b=Pi(a,b,1);Ag(a,b);b=Hg();a=Kj(a,1);null!==a&&($c(a,1,b),Mj(a,b))}
function Wi(a,b){if(3===a.tag)gk(a,a,b);else for(var c=a.return;null!==c;){if(3===c.tag){gk(c,a,b);break}else if(1===c.tag){var d=c.stateNode;if("function"===typeof c.type.getDerivedStateFromError||"function"===typeof d.componentDidCatch&&(null===Ti||!Ti.has(d))){a=Mi(b,a);var e=Si(c,a,1);Ag(c,e);e=Hg();c=Kj(c,1);if(null!==c)$c(c,1,e),Mj(c,e);else if("function"===typeof d.componentDidCatch&&(null===Ti||!Ti.has(d)))try{d.componentDidCatch(b,a)}catch(f){}break}}c=c.return}}
function Yj(a,b,c){var d=a.pingCache;null!==d&&d.delete(b);b=Hg();a.pingedLanes|=a.suspendedLanes&c;U===a&&(W&c)===c&&(4===V||3===V&&(W&62914560)===W&&500>O()-jj?Qj(a,0):uj|=c);Mj(a,b)}function lj(a,b){var c=a.stateNode;null!==c&&c.delete(b);b=0;0===b&&(b=a.mode,0===(b&2)?b=1:0===(b&4)?b=99===eg()?1:2:(0===Gj&&(Gj=tj),b=Yc(62914560&~Gj),0===b&&(b=4194304)));c=Hg();a=Kj(a,b);null!==a&&($c(a,b,c),Mj(a,c))}var ck;
ck=function(a,b,c){var d=b.lanes;if(null!==a)if(a.memoizedProps!==b.pendingProps||N.current)ug=!0;else if(0!==(c&d))ug=0!==(a.flags&16384)?!0:!1;else{ug=!1;switch(b.tag){case 3:ri(b);sh();break;case 5:gh(b);break;case 1:Ff(b.type)&&Jf(b);break;case 4:eh(b,b.stateNode.containerInfo);break;case 10:d=b.memoizedProps.value;var e=b.type._context;I(mg,e._currentValue);e._currentValue=d;break;case 13:if(null!==b.memoizedState){if(0!==(c&b.child.childLanes))return ti(a,b,c);I(P,P.current&1);b=hi(a,b,c);return null!==
b?b.sibling:null}I(P,P.current&1);break;case 19:d=0!==(c&b.childLanes);if(0!==(a.flags&64)){if(d)return Ai(a,b,c);b.flags|=64}e=b.memoizedState;null!==e&&(e.rendering=null,e.tail=null,e.lastEffect=null);I(P,P.current);if(d)break;else return null;case 23:case 24:return b.lanes=0,mi(a,b,c)}return hi(a,b,c)}else ug=!1;b.lanes=0;switch(b.tag){case 2:d=b.type;null!==a&&(a.alternate=null,b.alternate=null,b.flags|=2);a=b.pendingProps;e=Ef(b,M.current);tg(b,c);e=Ch(null,b,d,a,e,c);b.flags|=1;if("object"===
typeof e&&null!==e&&"function"===typeof e.render&&void 0===e.$$typeof){b.tag=1;b.memoizedState=null;b.updateQueue=null;if(Ff(d)){var f=!0;Jf(b)}else f=!1;b.memoizedState=null!==e.state&&void 0!==e.state?e.state:null;xg(b);var g=d.getDerivedStateFromProps;"function"===typeof g&&Gg(b,d,g,a);e.updater=Kg;b.stateNode=e;e._reactInternals=b;Og(b,d,a,c);b=qi(null,b,d,!0,f,c)}else b.tag=0,fi(null,b,e,c),b=b.child;return b;case 16:e=b.elementType;a:{null!==a&&(a.alternate=null,b.alternate=null,b.flags|=2);
a=b.pendingProps;f=e._init;e=f(e._payload);b.type=e;f=b.tag=hk(e);a=lg(e,a);switch(f){case 0:b=li(null,b,e,a,c);break a;case 1:b=pi(null,b,e,a,c);break a;case 11:b=gi(null,b,e,a,c);break a;case 14:b=ii(null,b,e,lg(e.type,a),d,c);break a}throw Error(y(306,e,""));}return b;case 0:return d=b.type,e=b.pendingProps,e=b.elementType===d?e:lg(d,e),li(a,b,d,e,c);case 1:return d=b.type,e=b.pendingProps,e=b.elementType===d?e:lg(d,e),pi(a,b,d,e,c);case 3:ri(b);d=b.updateQueue;if(null===a||null===d)throw Error(y(282));
d=b.pendingProps;e=b.memoizedState;e=null!==e?e.element:null;yg(a,b);Cg(b,d,null,c);d=b.memoizedState.element;if(d===e)sh(),b=hi(a,b,c);else{e=b.stateNode;if(f=e.hydrate)kh=rf(b.stateNode.containerInfo.firstChild),jh=b,f=lh=!0;if(f){a=e.mutableSourceEagerHydrationData;if(null!=a)for(e=0;e<a.length;e+=2)f=a[e],f._workInProgressVersionPrimary=a[e+1],th.push(f);c=Zg(b,null,d,c);for(b.child=c;c;)c.flags=c.flags&-3|1024,c=c.sibling}else fi(a,b,d,c),sh();b=b.child}return b;case 5:return gh(b),null===a&&
ph(b),d=b.type,e=b.pendingProps,f=null!==a?a.memoizedProps:null,g=e.children,nf(d,e)?g=null:null!==f&&nf(d,f)&&(b.flags|=16),oi(a,b),fi(a,b,g,c),b.child;case 6:return null===a&&ph(b),null;case 13:return ti(a,b,c);case 4:return eh(b,b.stateNode.containerInfo),d=b.pendingProps,null===a?b.child=Yg(b,null,d,c):fi(a,b,d,c),b.child;case 11:return d=b.type,e=b.pendingProps,e=b.elementType===d?e:lg(d,e),gi(a,b,d,e,c);case 7:return fi(a,b,b.pendingProps,c),b.child;case 8:return fi(a,b,b.pendingProps.children,
c),b.child;case 12:return fi(a,b,b.pendingProps.children,c),b.child;case 10:a:{d=b.type._context;e=b.pendingProps;g=b.memoizedProps;f=e.value;var h=b.type._context;I(mg,h._currentValue);h._currentValue=f;if(null!==g)if(h=g.value,f=He(h,f)?0:("function"===typeof d._calculateChangedBits?d._calculateChangedBits(h,f):1073741823)|0,0===f){if(g.children===e.children&&!N.current){b=hi(a,b,c);break a}}else for(h=b.child,null!==h&&(h.return=b);null!==h;){var k=h.dependencies;if(null!==k){g=h.child;for(var l=
k.firstContext;null!==l;){if(l.context===d&&0!==(l.observedBits&f)){1===h.tag&&(l=zg(-1,c&-c),l.tag=2,Ag(h,l));h.lanes|=c;l=h.alternate;null!==l&&(l.lanes|=c);sg(h.return,c);k.lanes|=c;break}l=l.next}}else g=10===h.tag?h.type===b.type?null:h.child:h.child;if(null!==g)g.return=h;else for(g=h;null!==g;){if(g===b){g=null;break}h=g.sibling;if(null!==h){h.return=g.return;g=h;break}g=g.return}h=g}fi(a,b,e.children,c);b=b.child}return b;case 9:return e=b.type,f=b.pendingProps,d=f.children,tg(b,c),e=vg(e,
f.unstable_observedBits),d=d(e),b.flags|=1,fi(a,b,d,c),b.child;case 14:return e=b.type,f=lg(e,b.pendingProps),f=lg(e.type,f),ii(a,b,e,f,d,c);case 15:return ki(a,b,b.type,b.pendingProps,d,c);case 17:return d=b.type,e=b.pendingProps,e=b.elementType===d?e:lg(d,e),null!==a&&(a.alternate=null,b.alternate=null,b.flags|=2),b.tag=1,Ff(d)?(a=!0,Jf(b)):a=!1,tg(b,c),Mg(b,d,e),Og(b,d,e,c),qi(null,b,d,!0,a,c);case 19:return Ai(a,b,c);case 23:return mi(a,b,c);case 24:return mi(a,b,c)}throw Error(y(156,b.tag));
};function ik(a,b,c,d){this.tag=a;this.key=c;this.sibling=this.child=this.return=this.stateNode=this.type=this.elementType=null;this.index=0;this.ref=null;this.pendingProps=b;this.dependencies=this.memoizedState=this.updateQueue=this.memoizedProps=null;this.mode=d;this.flags=0;this.lastEffect=this.firstEffect=this.nextEffect=null;this.childLanes=this.lanes=0;this.alternate=null}function nh(a,b,c,d){return new ik(a,b,c,d)}function ji(a){a=a.prototype;return!(!a||!a.isReactComponent)}
function hk(a){if("function"===typeof a)return ji(a)?1:0;if(void 0!==a&&null!==a){a=a.$$typeof;if(a===Aa)return 11;if(a===Da)return 14}return 2}
function Tg(a,b){var c=a.alternate;null===c?(c=nh(a.tag,b,a.key,a.mode),c.elementType=a.elementType,c.type=a.type,c.stateNode=a.stateNode,c.alternate=a,a.alternate=c):(c.pendingProps=b,c.type=a.type,c.flags=0,c.nextEffect=null,c.firstEffect=null,c.lastEffect=null);c.childLanes=a.childLanes;c.lanes=a.lanes;c.child=a.child;c.memoizedProps=a.memoizedProps;c.memoizedState=a.memoizedState;c.updateQueue=a.updateQueue;b=a.dependencies;c.dependencies=null===b?null:{lanes:b.lanes,firstContext:b.firstContext};
c.sibling=a.sibling;c.index=a.index;c.ref=a.ref;return c}
function Vg(a,b,c,d,e,f){var g=2;d=a;if("function"===typeof a)ji(a)&&(g=1);else if("string"===typeof a)g=5;else a:switch(a){case ua:return Xg(c.children,e,f,b);case Ha:g=8;e|=16;break;case wa:g=8;e|=1;break;case xa:return a=nh(12,c,b,e|8),a.elementType=xa,a.type=xa,a.lanes=f,a;case Ba:return a=nh(13,c,b,e),a.type=Ba,a.elementType=Ba,a.lanes=f,a;case Ca:return a=nh(19,c,b,e),a.elementType=Ca,a.lanes=f,a;case Ia:return vi(c,e,f,b);case Ja:return a=nh(24,c,b,e),a.elementType=Ja,a.lanes=f,a;default:if("object"===
typeof a&&null!==a)switch(a.$$typeof){case ya:g=10;break a;case za:g=9;break a;case Aa:g=11;break a;case Da:g=14;break a;case Ea:g=16;d=null;break a;case Fa:g=22;break a}throw Error(y(130,null==a?a:typeof a,""));}b=nh(g,c,b,e);b.elementType=a;b.type=d;b.lanes=f;return b}function Xg(a,b,c,d){a=nh(7,a,d,b);a.lanes=c;return a}function vi(a,b,c,d){a=nh(23,a,d,b);a.elementType=Ia;a.lanes=c;return a}function Ug(a,b,c){a=nh(6,a,null,b);a.lanes=c;return a}
function Wg(a,b,c){b=nh(4,null!==a.children?a.children:[],a.key,b);b.lanes=c;b.stateNode={containerInfo:a.containerInfo,pendingChildren:null,implementation:a.implementation};return b}
function jk(a,b,c){this.tag=b;this.containerInfo=a;this.finishedWork=this.pingCache=this.current=this.pendingChildren=null;this.timeoutHandle=-1;this.pendingContext=this.context=null;this.hydrate=c;this.callbackNode=null;this.callbackPriority=0;this.eventTimes=Zc(0);this.expirationTimes=Zc(-1);this.entangledLanes=this.finishedLanes=this.mutableReadLanes=this.expiredLanes=this.pingedLanes=this.suspendedLanes=this.pendingLanes=0;this.entanglements=Zc(0);this.mutableSourceEagerHydrationData=null}
function kk(a,b,c){var d=3<arguments.length&&void 0!==arguments[3]?arguments[3]:null;return{$$typeof:ta,key:null==d?null:""+d,children:a,containerInfo:b,implementation:c}}
function lk(a,b,c,d){var e=b.current,f=Hg(),g=Ig(e);a:if(c){c=c._reactInternals;b:{if(Zb(c)!==c||1!==c.tag)throw Error(y(170));var h=c;do{switch(h.tag){case 3:h=h.stateNode.context;break b;case 1:if(Ff(h.type)){h=h.stateNode.__reactInternalMemoizedMergedChildContext;break b}}h=h.return}while(null!==h);throw Error(y(171));}if(1===c.tag){var k=c.type;if(Ff(k)){c=If(c,k,h);break a}}c=h}else c=Cf;null===b.context?b.context=c:b.pendingContext=c;b=zg(f,g);b.payload={element:a};d=void 0===d?null:d;null!==
d&&(b.callback=d);Ag(e,b);Jg(e,g,f);return g}function mk(a){a=a.current;if(!a.child)return null;switch(a.child.tag){case 5:return a.child.stateNode;default:return a.child.stateNode}}function nk(a,b){a=a.memoizedState;if(null!==a&&null!==a.dehydrated){var c=a.retryLane;a.retryLane=0!==c&&c<b?c:b}}function ok(a,b){nk(a,b);(a=a.alternate)&&nk(a,b)}function pk(){return null}
function qk(a,b,c){var d=null!=c&&null!=c.hydrationOptions&&c.hydrationOptions.mutableSources||null;c=new jk(a,b,null!=c&&!0===c.hydrate);b=nh(3,null,null,2===b?7:1===b?3:0);c.current=b;b.stateNode=c;xg(b);a[ff]=c.current;cf(8===a.nodeType?a.parentNode:a);if(d)for(a=0;a<d.length;a++){b=d[a];var e=b._getVersion;e=e(b._source);null==c.mutableSourceEagerHydrationData?c.mutableSourceEagerHydrationData=[b,e]:c.mutableSourceEagerHydrationData.push(b,e)}this._internalRoot=c}
qk.prototype.render=function(a){lk(a,this._internalRoot,null,null)};qk.prototype.unmount=function(){var a=this._internalRoot,b=a.containerInfo;lk(null,a,null,function(){b[ff]=null})};function rk(a){return!(!a||1!==a.nodeType&&9!==a.nodeType&&11!==a.nodeType&&(8!==a.nodeType||" react-mount-point-unstable "!==a.nodeValue))}
function sk(a,b){b||(b=a?9===a.nodeType?a.documentElement:a.firstChild:null,b=!(!b||1!==b.nodeType||!b.hasAttribute("data-reactroot")));if(!b)for(var c;c=a.lastChild;)a.removeChild(c);return new qk(a,0,b?{hydrate:!0}:void 0)}
function tk(a,b,c,d,e){var f=c._reactRootContainer;if(f){var g=f._internalRoot;if("function"===typeof e){var h=e;e=function(){var a=mk(g);h.call(a)}}lk(b,g,a,e)}else{f=c._reactRootContainer=sk(c,d);g=f._internalRoot;if("function"===typeof e){var k=e;e=function(){var a=mk(g);k.call(a)}}Xj(function(){lk(b,g,a,e)})}return mk(g)}ec=function(a){if(13===a.tag){var b=Hg();Jg(a,4,b);ok(a,4)}};fc=function(a){if(13===a.tag){var b=Hg();Jg(a,67108864,b);ok(a,67108864)}};
gc=function(a){if(13===a.tag){var b=Hg(),c=Ig(a);Jg(a,c,b);ok(a,c)}};hc=function(a,b){return b()};
yb=function(a,b,c){switch(b){case "input":ab(a,c);b=c.name;if("radio"===c.type&&null!=b){for(c=a;c.parentNode;)c=c.parentNode;c=c.querySelectorAll("input[name="+JSON.stringify(""+b)+'][type="radio"]');for(b=0;b<c.length;b++){var d=c[b];if(d!==a&&d.form===a.form){var e=Db(d);if(!e)throw Error(y(90));Wa(d);ab(d,e)}}}break;case "textarea":ib(a,c);break;case "select":b=c.value,null!=b&&fb(a,!!c.multiple,b,!1)}};Gb=Wj;
Hb=function(a,b,c,d,e){var f=X;X|=4;try{return gg(98,a.bind(null,b,c,d,e))}finally{X=f,0===X&&(wj(),ig())}};Ib=function(){0===(X&49)&&(Vj(),Oj())};Jb=function(a,b){var c=X;X|=2;try{return a(b)}finally{X=c,0===X&&(wj(),ig())}};function uk(a,b){var c=2<arguments.length&&void 0!==arguments[2]?arguments[2]:null;if(!rk(b))throw Error(y(200));return kk(a,b,null,c)}var vk={Events:[Cb,ue,Db,Eb,Fb,Oj,{current:!1}]},wk={findFiberByHostInstance:wc,bundleType:0,version:"17.0.2",rendererPackageName:"react-dom"};
var xk={bundleType:wk.bundleType,version:wk.version,rendererPackageName:wk.rendererPackageName,rendererConfig:wk.rendererConfig,overrideHookState:null,overrideHookStateDeletePath:null,overrideHookStateRenamePath:null,overrideProps:null,overridePropsDeletePath:null,overridePropsRenamePath:null,setSuspenseHandler:null,scheduleUpdate:null,currentDispatcherRef:ra.ReactCurrentDispatcher,findHostInstanceByFiber:function(a){a=cc(a);return null===a?null:a.stateNode},findFiberByHostInstance:wk.findFiberByHostInstance||
pk,findHostInstancesForRefresh:null,scheduleRefresh:null,scheduleRoot:null,setRefreshHandler:null,getCurrentFiber:null};if("undefined"!==typeof __REACT_DEVTOOLS_GLOBAL_HOOK__){var yk=__REACT_DEVTOOLS_GLOBAL_HOOK__;if(!yk.isDisabled&&yk.supportsFiber)try{Lf=yk.inject(xk),Mf=yk}catch(a){}}exports.__SECRET_INTERNALS_DO_NOT_USE_OR_YOU_WILL_BE_FIRED=vk;exports.createPortal=uk;
exports.findDOMNode=function(a){if(null==a)return null;if(1===a.nodeType)return a;var b=a._reactInternals;if(void 0===b){if("function"===typeof a.render)throw Error(y(188));throw Error(y(268,Object.keys(a)));}a=cc(b);a=null===a?null:a.stateNode;return a};exports.flushSync=function(a,b){var c=X;if(0!==(c&48))return a(b);X|=1;try{if(a)return gg(99,a.bind(null,b))}finally{X=c,ig()}};exports.hydrate=function(a,b,c){if(!rk(b))throw Error(y(200));return tk(null,a,b,!0,c)};
exports.render=function(a,b,c){if(!rk(b))throw Error(y(200));return tk(null,a,b,!1,c)};exports.unmountComponentAtNode=function(a){if(!rk(a))throw Error(y(40));return a._reactRootContainer?(Xj(function(){tk(null,null,a,!1,function(){a._reactRootContainer=null;a[ff]=null})}),!0):!1};exports.unstable_batchedUpdates=Wj;exports.unstable_createPortal=function(a,b){return uk(a,b,2<arguments.length&&void 0!==arguments[2]?arguments[2]:null)};
exports.unstable_renderSubtreeIntoContainer=function(a,b,c,d){if(!rk(c))throw Error(y(200));if(null==a||void 0===a._reactInternals)throw Error(y(38));return tk(a,b,c,!1,d)};exports.version="17.0.2";


/***/ }),

/***/ 3935:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

"use strict";


function checkDCE() {
  /* global __REACT_DEVTOOLS_GLOBAL_HOOK__ */
  if (
    typeof __REACT_DEVTOOLS_GLOBAL_HOOK__ === 'undefined' ||
    typeof __REACT_DEVTOOLS_GLOBAL_HOOK__.checkDCE !== 'function'
  ) {
    return;
  }
  if (false) {}
  try {
    // Verify that the code above has been dead code eliminated (DCE'd).
    __REACT_DEVTOOLS_GLOBAL_HOOK__.checkDCE(checkDCE);
  } catch (err) {
    // DevTools shouldn't crash React, no matter what.
    // We should still report in case we break this code.
    console.error(err);
  }
}

if (true) {
  // DCE check should happen before ReactDOM bundle executes so that
  // DevTools can report bad minification during injection.
  checkDCE();
  module.exports = __webpack_require__(4448);
} else {}


/***/ }),

/***/ 9852:
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "j": function() { return /* binding */ focusManager; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_esm_inheritsLoose__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(1721);
/* harmony import */ var _subscribable__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(2943);
/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(2288);




var FocusManager = /*#__PURE__*/function (_Subscribable) {
  (0,_babel_runtime_helpers_esm_inheritsLoose__WEBPACK_IMPORTED_MODULE_0__/* .default */ .Z)(FocusManager, _Subscribable);

  function FocusManager() {
    return _Subscribable.apply(this, arguments) || this;
  }

  var _proto = FocusManager.prototype;

  _proto.onSubscribe = function onSubscribe() {
    if (!this.removeEventListener) {
      this.setDefaultEventListener();
    }
  };

  _proto.setEventListener = function setEventListener(setup) {
    var _this = this;

    if (this.removeEventListener) {
      this.removeEventListener();
    }

    this.removeEventListener = setup(function (focused) {
      if (typeof focused === 'boolean') {
        _this.setFocused(focused);
      } else {
        _this.onFocus();
      }
    });
  };

  _proto.setFocused = function setFocused(focused) {
    this.focused = focused;

    if (focused) {
      this.onFocus();
    }
  };

  _proto.onFocus = function onFocus() {
    this.listeners.forEach(function (listener) {
      listener();
    });
  };

  _proto.isFocused = function isFocused() {
    if (typeof this.focused === 'boolean') {
      return this.focused;
    } // document global can be unavailable in react native


    if (typeof document === 'undefined') {
      return true;
    }

    return [undefined, 'visible', 'prerender'].includes(document.visibilityState);
  };

  _proto.setDefaultEventListener = function setDefaultEventListener() {
    var _window;

    if (!_utils__WEBPACK_IMPORTED_MODULE_1__/* .isServer */ .sk && ((_window = window) == null ? void 0 : _window.addEventListener)) {
      this.setEventListener(function (onFocus) {
        var listener = function listener() {
          return onFocus();
        }; // Listen to visibillitychange and focus


        window.addEventListener('visibilitychange', listener, false);
        window.addEventListener('focus', listener, false);
        return function () {
          // Be sure to unsubscribe if a new handler is set
          window.removeEventListener('visibilitychange', listener);
          window.removeEventListener('focus', listener);
        };
      });
    }
  };

  return FocusManager;
}(_subscribable__WEBPACK_IMPORTED_MODULE_2__/* .Subscribable */ .l);

var focusManager = new FocusManager();

/***/ }),

/***/ 6747:
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "CancelledError": function() { return /* reexport safe */ _retryer__WEBPACK_IMPORTED_MODULE_0__.p8; },
/* harmony export */   "QueryCache": function() { return /* reexport safe */ _queryCache__WEBPACK_IMPORTED_MODULE_1__.t; },
/* harmony export */   "QueryClient": function() { return /* reexport safe */ _queryClient__WEBPACK_IMPORTED_MODULE_2__.S; },
/* harmony export */   "QueryObserver": function() { return /* reexport safe */ _queryObserver__WEBPACK_IMPORTED_MODULE_3__.z; },
/* harmony export */   "QueriesObserver": function() { return /* reexport safe */ _queriesObserver__WEBPACK_IMPORTED_MODULE_4__.y; },
/* harmony export */   "InfiniteQueryObserver": function() { return /* reexport safe */ _infiniteQueryObserver__WEBPACK_IMPORTED_MODULE_5__.c; },
/* harmony export */   "MutationCache": function() { return /* reexport safe */ _mutationCache__WEBPACK_IMPORTED_MODULE_6__.L; },
/* harmony export */   "MutationObserver": function() { return /* reexport safe */ _mutationObserver__WEBPACK_IMPORTED_MODULE_7__.X; },
/* harmony export */   "setLogger": function() { return /* reexport safe */ _logger__WEBPACK_IMPORTED_MODULE_8__.E; },
/* harmony export */   "notifyManager": function() { return /* reexport safe */ _notifyManager__WEBPACK_IMPORTED_MODULE_9__.V; },
/* harmony export */   "focusManager": function() { return /* reexport safe */ _focusManager__WEBPACK_IMPORTED_MODULE_10__.j; },
/* harmony export */   "onlineManager": function() { return /* reexport safe */ _onlineManager__WEBPACK_IMPORTED_MODULE_11__.N; },
/* harmony export */   "hashQueryKey": function() { return /* reexport safe */ _utils__WEBPACK_IMPORTED_MODULE_12__.yF; },
/* harmony export */   "isError": function() { return /* reexport safe */ _utils__WEBPACK_IMPORTED_MODULE_12__.VZ; },
/* harmony export */   "isCancelledError": function() { return /* reexport safe */ _retryer__WEBPACK_IMPORTED_MODULE_0__.DV; }
/* harmony export */ });
/* harmony import */ var _retryer__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(1216);
/* harmony import */ var _queryCache__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(2122);
/* harmony import */ var _queryClient__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(8328);
/* harmony import */ var _queryObserver__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(4254);
/* harmony import */ var _queriesObserver__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(4119);
/* harmony import */ var _infiniteQueryObserver__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(8186);
/* harmony import */ var _mutationCache__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(8133);
/* harmony import */ var _mutationObserver__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(36);
/* harmony import */ var _logger__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(1909);
/* harmony import */ var _notifyManager__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(101);
/* harmony import */ var _focusManager__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(9852);
/* harmony import */ var _onlineManager__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(68);
/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(2288);
/* harmony import */ var _types__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(6755);
/* harmony import */ var _types__WEBPACK_IMPORTED_MODULE_13___default = /*#__PURE__*/__webpack_require__.n(_types__WEBPACK_IMPORTED_MODULE_13__);
/* harmony reexport (unknown) */ var __WEBPACK_REEXPORT_OBJECT__ = {};
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _types__WEBPACK_IMPORTED_MODULE_13__) if(["default","CancelledError","QueryCache","QueryClient","QueryObserver","QueriesObserver","InfiniteQueryObserver","MutationCache","MutationObserver","setLogger","notifyManager","focusManager","onlineManager","hashQueryKey","isError","isCancelledError"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) __WEBPACK_REEXPORT_OBJECT__[__WEBPACK_IMPORT_KEY__] = function(key) { return _types__WEBPACK_IMPORTED_MODULE_13__[key]; }.bind(0, __WEBPACK_IMPORT_KEY__)
/* harmony reexport (unknown) */ __webpack_require__.d(__webpack_exports__, __WEBPACK_REEXPORT_OBJECT__);













 // Types



/***/ }),

/***/ 6997:
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Gm": function() { return /* binding */ infiniteQueryBehavior; },
/* harmony export */   "Qy": function() { return /* binding */ hasNextPage; },
/* harmony export */   "ZF": function() { return /* binding */ hasPreviousPage; }
/* harmony export */ });
/* unused harmony exports getNextPageParam, getPreviousPageParam */
/* harmony import */ var _retryer__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(1216);

function infiniteQueryBehavior() {
  return {
    onFetch: function onFetch(context) {
      context.fetchFn = function () {
        var _context$fetchOptions, _context$fetchOptions2, _context$fetchOptions3, _context$fetchOptions4, _context$state$data, _context$state$data2;

        var refetchPage = (_context$fetchOptions = context.fetchOptions) == null ? void 0 : (_context$fetchOptions2 = _context$fetchOptions.meta) == null ? void 0 : _context$fetchOptions2.refetchPage;
        var fetchMore = (_context$fetchOptions3 = context.fetchOptions) == null ? void 0 : (_context$fetchOptions4 = _context$fetchOptions3.meta) == null ? void 0 : _context$fetchOptions4.fetchMore;
        var pageParam = fetchMore == null ? void 0 : fetchMore.pageParam;
        var isFetchingNextPage = (fetchMore == null ? void 0 : fetchMore.direction) === 'forward';
        var isFetchingPreviousPage = (fetchMore == null ? void 0 : fetchMore.direction) === 'backward';
        var oldPages = ((_context$state$data = context.state.data) == null ? void 0 : _context$state$data.pages) || [];
        var oldPageParams = ((_context$state$data2 = context.state.data) == null ? void 0 : _context$state$data2.pageParams) || [];
        var newPageParams = oldPageParams;
        var cancelled = false; // Get query function

        var queryFn = context.options.queryFn || function () {
          return Promise.reject('Missing queryFn');
        };

        var buildNewPages = function buildNewPages(pages, param, page, previous) {
          newPageParams = previous ? [param].concat(newPageParams) : [].concat(newPageParams, [param]);
          return previous ? [page].concat(pages) : [].concat(pages, [page]);
        }; // Create function to fetch a page


        var fetchPage = function fetchPage(pages, manual, param, previous) {
          if (cancelled) {
            return Promise.reject('Cancelled');
          }

          if (typeof param === 'undefined' && !manual && pages.length) {
            return Promise.resolve(pages);
          }

          var queryFnContext = {
            queryKey: context.queryKey,
            pageParam: param
          };
          var queryFnResult = queryFn(queryFnContext);
          var promise = Promise.resolve(queryFnResult).then(function (page) {
            return buildNewPages(pages, param, page, previous);
          });

          if ((0,_retryer__WEBPACK_IMPORTED_MODULE_0__/* .isCancelable */ .LE)(queryFnResult)) {
            var promiseAsAny = promise;
            promiseAsAny.cancel = queryFnResult.cancel;
          }

          return promise;
        };

        var promise; // Fetch first page?

        if (!oldPages.length) {
          promise = fetchPage([]);
        } // Fetch next page?
        else if (isFetchingNextPage) {
            var manual = typeof pageParam !== 'undefined';
            var param = manual ? pageParam : getNextPageParam(context.options, oldPages);
            promise = fetchPage(oldPages, manual, param);
          } // Fetch previous page?
          else if (isFetchingPreviousPage) {
              var _manual = typeof pageParam !== 'undefined';

              var _param = _manual ? pageParam : getPreviousPageParam(context.options, oldPages);

              promise = fetchPage(oldPages, _manual, _param, true);
            } // Refetch pages
            else {
                (function () {
                  newPageParams = [];
                  var manual = typeof context.options.getNextPageParam === 'undefined';
                  var shouldFetchFirstPage = refetchPage && oldPages[0] ? refetchPage(oldPages[0], 0, oldPages) : true; // Fetch first page

                  promise = shouldFetchFirstPage ? fetchPage([], manual, oldPageParams[0]) : Promise.resolve(buildNewPages([], oldPageParams[0], oldPages[0])); // Fetch remaining pages

                  var _loop = function _loop(i) {
                    promise = promise.then(function (pages) {
                      var shouldFetchNextPage = refetchPage && oldPages[i] ? refetchPage(oldPages[i], i, oldPages) : true;

                      if (shouldFetchNextPage) {
                        var _param2 = manual ? oldPageParams[i] : getNextPageParam(context.options, pages);

                        return fetchPage(pages, manual, _param2);
                      }

                      return Promise.resolve(buildNewPages(pages, oldPageParams[i], oldPages[i]));
                    });
                  };

                  for (var i = 1; i < oldPages.length; i++) {
                    _loop(i);
                  }
                })();
              }

        var finalPromise = promise.then(function (pages) {
          return {
            pages: pages,
            pageParams: newPageParams
          };
        });
        var finalPromiseAsAny = finalPromise;

        finalPromiseAsAny.cancel = function () {
          cancelled = true;

          if ((0,_retryer__WEBPACK_IMPORTED_MODULE_0__/* .isCancelable */ .LE)(promise)) {
            promise.cancel();
          }
        };

        return finalPromise;
      };
    }
  };
}
function getNextPageParam(options, pages) {
  return options.getNextPageParam == null ? void 0 : options.getNextPageParam(pages[pages.length - 1], pages);
}
function getPreviousPageParam(options, pages) {
  return options.getPreviousPageParam == null ? void 0 : options.getPreviousPageParam(pages[0], pages);
}
/**
 * Checks if there is a next page.
 * Returns `undefined` if it cannot be determined.
 */

function hasNextPage(options, pages) {
  if (options.getNextPageParam && Array.isArray(pages)) {
    var nextPageParam = getNextPageParam(options, pages);
    return typeof nextPageParam !== 'undefined' && nextPageParam !== null && nextPageParam !== false;
  }
}
/**
 * Checks if there is a previous page.
 * Returns `undefined` if it cannot be determined.
 */

function hasPreviousPage(options, pages) {
  if (options.getPreviousPageParam && Array.isArray(pages)) {
    var previousPageParam = getPreviousPageParam(options, pages);
    return typeof previousPageParam !== 'undefined' && previousPageParam !== null && previousPageParam !== false;
  }
}

/***/ }),

/***/ 8186:
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "c": function() { return /* binding */ InfiniteQueryObserver; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(7462);
/* harmony import */ var _babel_runtime_helpers_esm_inheritsLoose__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(1721);
/* harmony import */ var _queryObserver__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(4254);
/* harmony import */ var _infiniteQueryBehavior__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(6997);




var InfiniteQueryObserver = /*#__PURE__*/function (_QueryObserver) {
  (0,_babel_runtime_helpers_esm_inheritsLoose__WEBPACK_IMPORTED_MODULE_0__/* .default */ .Z)(InfiniteQueryObserver, _QueryObserver);

  // Type override
  // Type override
  // Type override
  // eslint-disable-next-line @typescript-eslint/no-useless-constructor
  function InfiniteQueryObserver(client, options) {
    return _QueryObserver.call(this, client, options) || this;
  }

  var _proto = InfiniteQueryObserver.prototype;

  _proto.bindMethods = function bindMethods() {
    _QueryObserver.prototype.bindMethods.call(this);

    this.fetchNextPage = this.fetchNextPage.bind(this);
    this.fetchPreviousPage = this.fetchPreviousPage.bind(this);
  };

  _proto.setOptions = function setOptions(options) {
    _QueryObserver.prototype.setOptions.call(this, (0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_1__/* .default */ .Z)({}, options, {
      behavior: (0,_infiniteQueryBehavior__WEBPACK_IMPORTED_MODULE_2__/* .infiniteQueryBehavior */ .Gm)()
    }));
  };

  _proto.getOptimisticResult = function getOptimisticResult(options) {
    options.behavior = (0,_infiniteQueryBehavior__WEBPACK_IMPORTED_MODULE_2__/* .infiniteQueryBehavior */ .Gm)();
    return _QueryObserver.prototype.getOptimisticResult.call(this, options);
  };

  _proto.fetchNextPage = function fetchNextPage(options) {
    return this.fetch({
      cancelRefetch: true,
      throwOnError: options == null ? void 0 : options.throwOnError,
      meta: {
        fetchMore: {
          direction: 'forward',
          pageParam: options == null ? void 0 : options.pageParam
        }
      }
    });
  };

  _proto.fetchPreviousPage = function fetchPreviousPage(options) {
    return this.fetch({
      cancelRefetch: true,
      throwOnError: options == null ? void 0 : options.throwOnError,
      meta: {
        fetchMore: {
          direction: 'backward',
          pageParam: options == null ? void 0 : options.pageParam
        }
      }
    });
  };

  _proto.createResult = function createResult(query, options) {
    var _state$data, _state$data2, _state$fetchMeta, _state$fetchMeta$fetc, _state$fetchMeta2, _state$fetchMeta2$fet;

    var state = query.state;

    var result = _QueryObserver.prototype.createResult.call(this, query, options);

    return (0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_1__/* .default */ .Z)({}, result, {
      fetchNextPage: this.fetchNextPage,
      fetchPreviousPage: this.fetchPreviousPage,
      hasNextPage: (0,_infiniteQueryBehavior__WEBPACK_IMPORTED_MODULE_2__/* .hasNextPage */ .Qy)(options, (_state$data = state.data) == null ? void 0 : _state$data.pages),
      hasPreviousPage: (0,_infiniteQueryBehavior__WEBPACK_IMPORTED_MODULE_2__/* .hasPreviousPage */ .ZF)(options, (_state$data2 = state.data) == null ? void 0 : _state$data2.pages),
      isFetchingNextPage: state.isFetching && ((_state$fetchMeta = state.fetchMeta) == null ? void 0 : (_state$fetchMeta$fetc = _state$fetchMeta.fetchMore) == null ? void 0 : _state$fetchMeta$fetc.direction) === 'forward',
      isFetchingPreviousPage: state.isFetching && ((_state$fetchMeta2 = state.fetchMeta) == null ? void 0 : (_state$fetchMeta2$fet = _state$fetchMeta2.fetchMore) == null ? void 0 : _state$fetchMeta2$fet.direction) === 'backward'
    });
  };

  return InfiniteQueryObserver;
}(_queryObserver__WEBPACK_IMPORTED_MODULE_3__/* .QueryObserver */ .z);

/***/ }),

/***/ 1909:
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "j": function() { return /* binding */ getLogger; },
/* harmony export */   "E": function() { return /* binding */ setLogger; }
/* harmony export */ });
/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(2288);
 // TYPES

// FUNCTIONS
var logger = console || {
  error: _utils__WEBPACK_IMPORTED_MODULE_0__/* .noop */ .ZT,
  warn: _utils__WEBPACK_IMPORTED_MODULE_0__/* .noop */ .ZT,
  log: _utils__WEBPACK_IMPORTED_MODULE_0__/* .noop */ .ZT
};
function getLogger() {
  return logger;
}
function setLogger(newLogger) {
  logger = newLogger;
}

/***/ }),

/***/ 1262:
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "m": function() { return /* binding */ Mutation; },
/* harmony export */   "R": function() { return /* binding */ getDefaultState; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(7462);
/* harmony import */ var _logger__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(1909);
/* harmony import */ var _notifyManager__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(101);
/* harmony import */ var _retryer__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(1216);
/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(2288);




 // TYPES

// CLASS
var Mutation = /*#__PURE__*/function () {
  function Mutation(config) {
    this.options = (0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__/* .default */ .Z)({}, config.defaultOptions, config.options);
    this.mutationId = config.mutationId;
    this.mutationCache = config.mutationCache;
    this.observers = [];
    this.state = config.state || getDefaultState();
  }

  var _proto = Mutation.prototype;

  _proto.setState = function setState(state) {
    this.dispatch({
      type: 'setState',
      state: state
    });
  };

  _proto.addObserver = function addObserver(observer) {
    if (this.observers.indexOf(observer) === -1) {
      this.observers.push(observer);
    }
  };

  _proto.removeObserver = function removeObserver(observer) {
    this.observers = this.observers.filter(function (x) {
      return x !== observer;
    });
  };

  _proto.cancel = function cancel() {
    if (this.retryer) {
      this.retryer.cancel();
      return this.retryer.promise.then(_utils__WEBPACK_IMPORTED_MODULE_1__/* .noop */ .ZT).catch(_utils__WEBPACK_IMPORTED_MODULE_1__/* .noop */ .ZT);
    }

    return Promise.resolve();
  };

  _proto.continue = function _continue() {
    if (this.retryer) {
      this.retryer.continue();
      return this.retryer.promise;
    }

    return this.execute();
  };

  _proto.execute = function execute() {
    var _this = this;

    var data;
    var restored = this.state.status === 'loading';
    var promise = Promise.resolve();

    if (!restored) {
      this.dispatch({
        type: 'loading',
        variables: this.options.variables
      });
      promise = promise.then(function () {
        return _this.options.onMutate == null ? void 0 : _this.options.onMutate(_this.state.variables);
      }).then(function (context) {
        if (context !== _this.state.context) {
          _this.dispatch({
            type: 'loading',
            context: context,
            variables: _this.state.variables
          });
        }
      });
    }

    return promise.then(function () {
      return _this.executeMutation();
    }).then(function (result) {
      data = result; // Notify cache callback

      _this.mutationCache.config.onSuccess == null ? void 0 : _this.mutationCache.config.onSuccess(data, _this.state.variables, _this.state.context, _this);
    }).then(function () {
      return _this.options.onSuccess == null ? void 0 : _this.options.onSuccess(data, _this.state.variables, _this.state.context);
    }).then(function () {
      return _this.options.onSettled == null ? void 0 : _this.options.onSettled(data, null, _this.state.variables, _this.state.context);
    }).then(function () {
      _this.dispatch({
        type: 'success',
        data: data
      });

      return data;
    }).catch(function (error) {
      // Notify cache callback
      _this.mutationCache.config.onError == null ? void 0 : _this.mutationCache.config.onError(error, _this.state.variables, _this.state.context, _this); // Log error

      (0,_logger__WEBPACK_IMPORTED_MODULE_2__/* .getLogger */ .j)().error(error);
      return Promise.resolve().then(function () {
        return _this.options.onError == null ? void 0 : _this.options.onError(error, _this.state.variables, _this.state.context);
      }).then(function () {
        return _this.options.onSettled == null ? void 0 : _this.options.onSettled(undefined, error, _this.state.variables, _this.state.context);
      }).then(function () {
        _this.dispatch({
          type: 'error',
          error: error
        });

        throw error;
      });
    });
  };

  _proto.executeMutation = function executeMutation() {
    var _this2 = this,
        _this$options$retry;

    this.retryer = new _retryer__WEBPACK_IMPORTED_MODULE_3__/* .Retryer */ .m4({
      fn: function fn() {
        if (!_this2.options.mutationFn) {
          return Promise.reject('No mutationFn found');
        }

        return _this2.options.mutationFn(_this2.state.variables);
      },
      onFail: function onFail() {
        _this2.dispatch({
          type: 'failed'
        });
      },
      onPause: function onPause() {
        _this2.dispatch({
          type: 'pause'
        });
      },
      onContinue: function onContinue() {
        _this2.dispatch({
          type: 'continue'
        });
      },
      retry: (_this$options$retry = this.options.retry) != null ? _this$options$retry : 0,
      retryDelay: this.options.retryDelay
    });
    return this.retryer.promise;
  };

  _proto.dispatch = function dispatch(action) {
    var _this3 = this;

    this.state = reducer(this.state, action);
    _notifyManager__WEBPACK_IMPORTED_MODULE_4__/* .notifyManager.batch */ .V.batch(function () {
      _this3.observers.forEach(function (observer) {
        observer.onMutationUpdate(action);
      });

      _this3.mutationCache.notify(_this3);
    });
  };

  return Mutation;
}();
function getDefaultState() {
  return {
    context: undefined,
    data: undefined,
    error: null,
    failureCount: 0,
    isPaused: false,
    status: 'idle',
    variables: undefined
  };
}

function reducer(state, action) {
  switch (action.type) {
    case 'failed':
      return (0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__/* .default */ .Z)({}, state, {
        failureCount: state.failureCount + 1
      });

    case 'pause':
      return (0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__/* .default */ .Z)({}, state, {
        isPaused: true
      });

    case 'continue':
      return (0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__/* .default */ .Z)({}, state, {
        isPaused: false
      });

    case 'loading':
      return (0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__/* .default */ .Z)({}, state, {
        context: action.context,
        data: undefined,
        error: null,
        isPaused: false,
        status: 'loading',
        variables: action.variables
      });

    case 'success':
      return (0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__/* .default */ .Z)({}, state, {
        data: action.data,
        error: null,
        status: 'success',
        isPaused: false
      });

    case 'error':
      return (0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__/* .default */ .Z)({}, state, {
        data: undefined,
        error: action.error,
        failureCount: state.failureCount + 1,
        isPaused: false,
        status: 'error'
      });

    case 'setState':
      return (0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__/* .default */ .Z)({}, state, action.state);

    default:
      return state;
  }
}

/***/ }),

/***/ 8133:
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "L": function() { return /* binding */ MutationCache; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_esm_inheritsLoose__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(1721);
/* harmony import */ var _notifyManager__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(101);
/* harmony import */ var _mutation__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(1262);
/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(2288);
/* harmony import */ var _subscribable__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(2943);




 // TYPES

// CLASS
var MutationCache = /*#__PURE__*/function (_Subscribable) {
  (0,_babel_runtime_helpers_esm_inheritsLoose__WEBPACK_IMPORTED_MODULE_0__/* .default */ .Z)(MutationCache, _Subscribable);

  function MutationCache(config) {
    var _this;

    _this = _Subscribable.call(this) || this;
    _this.config = config || {};
    _this.mutations = [];
    _this.mutationId = 0;
    return _this;
  }

  var _proto = MutationCache.prototype;

  _proto.build = function build(client, options, state) {
    var mutation = new _mutation__WEBPACK_IMPORTED_MODULE_1__/* .Mutation */ .m({
      mutationCache: this,
      mutationId: ++this.mutationId,
      options: client.defaultMutationOptions(options),
      state: state,
      defaultOptions: options.mutationKey ? client.getMutationDefaults(options.mutationKey) : undefined
    });
    this.add(mutation);
    return mutation;
  };

  _proto.add = function add(mutation) {
    this.mutations.push(mutation);
    this.notify(mutation);
  };

  _proto.remove = function remove(mutation) {
    this.mutations = this.mutations.filter(function (x) {
      return x !== mutation;
    });
    mutation.cancel();
    this.notify(mutation);
  };

  _proto.clear = function clear() {
    var _this2 = this;

    _notifyManager__WEBPACK_IMPORTED_MODULE_2__/* .notifyManager.batch */ .V.batch(function () {
      _this2.mutations.forEach(function (mutation) {
        _this2.remove(mutation);
      });
    });
  };

  _proto.getAll = function getAll() {
    return this.mutations;
  };

  _proto.find = function find(filters) {
    if (typeof filters.exact === 'undefined') {
      filters.exact = true;
    }

    return this.mutations.find(function (mutation) {
      return (0,_utils__WEBPACK_IMPORTED_MODULE_3__/* .matchMutation */ .X7)(filters, mutation);
    });
  };

  _proto.findAll = function findAll(filters) {
    return this.mutations.filter(function (mutation) {
      return (0,_utils__WEBPACK_IMPORTED_MODULE_3__/* .matchMutation */ .X7)(filters, mutation);
    });
  };

  _proto.notify = function notify(mutation) {
    var _this3 = this;

    _notifyManager__WEBPACK_IMPORTED_MODULE_2__/* .notifyManager.batch */ .V.batch(function () {
      _this3.listeners.forEach(function (listener) {
        listener(mutation);
      });
    });
  };

  _proto.onFocus = function onFocus() {
    this.resumePausedMutations();
  };

  _proto.onOnline = function onOnline() {
    this.resumePausedMutations();
  };

  _proto.resumePausedMutations = function resumePausedMutations() {
    var pausedMutations = this.mutations.filter(function (x) {
      return x.state.isPaused;
    });
    return _notifyManager__WEBPACK_IMPORTED_MODULE_2__/* .notifyManager.batch */ .V.batch(function () {
      return pausedMutations.reduce(function (promise, mutation) {
        return promise.then(function () {
          return mutation.continue().catch(_utils__WEBPACK_IMPORTED_MODULE_3__/* .noop */ .ZT);
        });
      }, Promise.resolve());
    });
  };

  return MutationCache;
}(_subscribable__WEBPACK_IMPORTED_MODULE_4__/* .Subscribable */ .l);

/***/ }),

/***/ 36:
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "X": function() { return /* binding */ MutationObserver; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(7462);
/* harmony import */ var _babel_runtime_helpers_esm_inheritsLoose__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(1721);
/* harmony import */ var _mutation__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(1262);
/* harmony import */ var _notifyManager__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(101);
/* harmony import */ var _subscribable__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(2943);





// CLASS
var MutationObserver = /*#__PURE__*/function (_Subscribable) {
  (0,_babel_runtime_helpers_esm_inheritsLoose__WEBPACK_IMPORTED_MODULE_0__/* .default */ .Z)(MutationObserver, _Subscribable);

  function MutationObserver(client, options) {
    var _this;

    _this = _Subscribable.call(this) || this;
    _this.client = client;

    _this.setOptions(options);

    _this.bindMethods();

    _this.updateResult();

    return _this;
  }

  var _proto = MutationObserver.prototype;

  _proto.bindMethods = function bindMethods() {
    this.mutate = this.mutate.bind(this);
    this.reset = this.reset.bind(this);
  };

  _proto.setOptions = function setOptions(options) {
    this.options = this.client.defaultMutationOptions(options);
  };

  _proto.onUnsubscribe = function onUnsubscribe() {
    if (!this.listeners.length) {
      var _this$currentMutation;

      (_this$currentMutation = this.currentMutation) == null ? void 0 : _this$currentMutation.removeObserver(this);
    }
  };

  _proto.onMutationUpdate = function onMutationUpdate(action) {
    this.updateResult(); // Determine which callbacks to trigger

    var notifyOptions = {
      listeners: true
    };

    if (action.type === 'success') {
      notifyOptions.onSuccess = true;
    } else if (action.type === 'error') {
      notifyOptions.onError = true;
    }

    this.notify(notifyOptions);
  };

  _proto.getCurrentResult = function getCurrentResult() {
    return this.currentResult;
  };

  _proto.reset = function reset() {
    this.currentMutation = undefined;
    this.updateResult();
    this.notify({
      listeners: true
    });
  };

  _proto.mutate = function mutate(variables, options) {
    this.mutateOptions = options;

    if (this.currentMutation) {
      this.currentMutation.removeObserver(this);
    }

    this.currentMutation = this.client.getMutationCache().build(this.client, (0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_1__/* .default */ .Z)({}, this.options, {
      variables: typeof variables !== 'undefined' ? variables : this.options.variables
    }));
    this.currentMutation.addObserver(this);
    return this.currentMutation.execute();
  };

  _proto.updateResult = function updateResult() {
    var state = this.currentMutation ? this.currentMutation.state : (0,_mutation__WEBPACK_IMPORTED_MODULE_2__/* .getDefaultState */ .R)();
    this.currentResult = (0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_1__/* .default */ .Z)({}, state, {
      isLoading: state.status === 'loading',
      isSuccess: state.status === 'success',
      isError: state.status === 'error',
      isIdle: state.status === 'idle',
      mutate: this.mutate,
      reset: this.reset
    });
  };

  _proto.notify = function notify(options) {
    var _this2 = this;

    _notifyManager__WEBPACK_IMPORTED_MODULE_3__/* .notifyManager.batch */ .V.batch(function () {
      // First trigger the mutate callbacks
      if (_this2.mutateOptions) {
        if (options.onSuccess) {
          _this2.mutateOptions.onSuccess == null ? void 0 : _this2.mutateOptions.onSuccess(_this2.currentResult.data, _this2.currentResult.variables, _this2.currentResult.context);
          _this2.mutateOptions.onSettled == null ? void 0 : _this2.mutateOptions.onSettled(_this2.currentResult.data, null, _this2.currentResult.variables, _this2.currentResult.context);
        } else if (options.onError) {
          _this2.mutateOptions.onError == null ? void 0 : _this2.mutateOptions.onError(_this2.currentResult.error, _this2.currentResult.variables, _this2.currentResult.context);
          _this2.mutateOptions.onSettled == null ? void 0 : _this2.mutateOptions.onSettled(undefined, _this2.currentResult.error, _this2.currentResult.variables, _this2.currentResult.context);
        }
      } // Then trigger the listeners


      if (options.listeners) {
        _this2.listeners.forEach(function (listener) {
          listener(_this2.currentResult);
        });
      }
    });
  };

  return MutationObserver;
}(_subscribable__WEBPACK_IMPORTED_MODULE_4__/* .Subscribable */ .l);

/***/ }),

/***/ 101:
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "V": function() { return /* binding */ notifyManager; }
/* harmony export */ });
/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(2288);
 // TYPES

// CLASS
var NotifyManager = /*#__PURE__*/function () {
  function NotifyManager() {
    this.queue = [];
    this.transactions = 0;

    this.notifyFn = function (callback) {
      callback();
    };

    this.batchNotifyFn = function (callback) {
      callback();
    };
  }

  var _proto = NotifyManager.prototype;

  _proto.batch = function batch(callback) {
    this.transactions++;
    var result = callback();
    this.transactions--;

    if (!this.transactions) {
      this.flush();
    }

    return result;
  };

  _proto.schedule = function schedule(callback) {
    var _this = this;

    if (this.transactions) {
      this.queue.push(callback);
    } else {
      (0,_utils__WEBPACK_IMPORTED_MODULE_0__/* .scheduleMicrotask */ .A4)(function () {
        _this.notifyFn(callback);
      });
    }
  }
  /**
   * All calls to the wrapped function will be batched.
   */
  ;

  _proto.batchCalls = function batchCalls(callback) {
    var _this2 = this;

    return function () {
      for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
        args[_key] = arguments[_key];
      }

      _this2.schedule(function () {
        callback.apply(void 0, args);
      });
    };
  };

  _proto.flush = function flush() {
    var _this3 = this;

    var queue = this.queue;
    this.queue = [];

    if (queue.length) {
      (0,_utils__WEBPACK_IMPORTED_MODULE_0__/* .scheduleMicrotask */ .A4)(function () {
        _this3.batchNotifyFn(function () {
          queue.forEach(function (callback) {
            _this3.notifyFn(callback);
          });
        });
      });
    }
  }
  /**
   * Use this method to set a custom notify function.
   * This can be used to for example wrap notifications with `React.act` while running tests.
   */
  ;

  _proto.setNotifyFunction = function setNotifyFunction(fn) {
    this.notifyFn = fn;
  }
  /**
   * Use this method to set a custom function to batch notifications together into a single tick.
   * By default React Query will use the batch function provided by ReactDOM or React Native.
   */
  ;

  _proto.setBatchNotifyFunction = function setBatchNotifyFunction(fn) {
    this.batchNotifyFn = fn;
  };

  return NotifyManager;
}(); // SINGLETON


var notifyManager = new NotifyManager();

/***/ }),

/***/ 68:
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "N": function() { return /* binding */ onlineManager; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_esm_inheritsLoose__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(1721);
/* harmony import */ var _subscribable__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(2943);
/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(2288);




var OnlineManager = /*#__PURE__*/function (_Subscribable) {
  (0,_babel_runtime_helpers_esm_inheritsLoose__WEBPACK_IMPORTED_MODULE_0__/* .default */ .Z)(OnlineManager, _Subscribable);

  function OnlineManager() {
    return _Subscribable.apply(this, arguments) || this;
  }

  var _proto = OnlineManager.prototype;

  _proto.onSubscribe = function onSubscribe() {
    if (!this.removeEventListener) {
      this.setDefaultEventListener();
    }
  };

  _proto.setEventListener = function setEventListener(setup) {
    var _this = this;

    if (this.removeEventListener) {
      this.removeEventListener();
    }

    this.removeEventListener = setup(function (online) {
      if (typeof online === 'boolean') {
        _this.setOnline(online);
      } else {
        _this.onOnline();
      }
    });
  };

  _proto.setOnline = function setOnline(online) {
    this.online = online;

    if (online) {
      this.onOnline();
    }
  };

  _proto.onOnline = function onOnline() {
    this.listeners.forEach(function (listener) {
      listener();
    });
  };

  _proto.isOnline = function isOnline() {
    if (typeof this.online === 'boolean') {
      return this.online;
    }

    if (typeof navigator === 'undefined' || typeof navigator.onLine === 'undefined') {
      return true;
    }

    return navigator.onLine;
  };

  _proto.setDefaultEventListener = function setDefaultEventListener() {
    var _window;

    if (!_utils__WEBPACK_IMPORTED_MODULE_1__/* .isServer */ .sk && ((_window = window) == null ? void 0 : _window.addEventListener)) {
      this.setEventListener(function (onOnline) {
        var listener = function listener() {
          return onOnline();
        }; // Listen to online


        window.addEventListener('online', listener, false);
        window.addEventListener('offline', listener, false);
        return function () {
          // Be sure to unsubscribe if a new handler is set
          window.removeEventListener('online', listener);
          window.removeEventListener('offline', listener);
        };
      });
    }
  };

  return OnlineManager;
}(_subscribable__WEBPACK_IMPORTED_MODULE_2__/* .Subscribable */ .l);

var onlineManager = new OnlineManager();

/***/ }),

/***/ 4119:
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "y": function() { return /* binding */ QueriesObserver; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_esm_inheritsLoose__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(1721);
/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(2288);
/* harmony import */ var _notifyManager__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(101);
/* harmony import */ var _queryObserver__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(4254);
/* harmony import */ var _subscribable__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(2943);





var QueriesObserver = /*#__PURE__*/function (_Subscribable) {
  (0,_babel_runtime_helpers_esm_inheritsLoose__WEBPACK_IMPORTED_MODULE_0__/* .default */ .Z)(QueriesObserver, _Subscribable);

  function QueriesObserver(client, queries) {
    var _this;

    _this = _Subscribable.call(this) || this;
    _this.client = client;
    _this.queries = [];
    _this.result = [];
    _this.observers = [];
    _this.observersMap = {};

    if (queries) {
      _this.setQueries(queries);
    }

    return _this;
  }

  var _proto = QueriesObserver.prototype;

  _proto.onSubscribe = function onSubscribe() {
    var _this2 = this;

    if (this.listeners.length === 1) {
      this.observers.forEach(function (observer) {
        observer.subscribe(function (result) {
          _this2.onUpdate(observer, result);
        });
      });
    }
  };

  _proto.onUnsubscribe = function onUnsubscribe() {
    if (!this.listeners.length) {
      this.destroy();
    }
  };

  _proto.destroy = function destroy() {
    this.listeners = [];
    this.observers.forEach(function (observer) {
      observer.destroy();
    });
  };

  _proto.setQueries = function setQueries(queries, notifyOptions) {
    this.queries = queries;
    this.updateObservers(notifyOptions);
  };

  _proto.getCurrentResult = function getCurrentResult() {
    return this.result;
  };

  _proto.getOptimisticResult = function getOptimisticResult(queries) {
    var _this3 = this;

    return queries.map(function (options, index) {
      var defaultedOptions = _this3.client.defaultQueryObserverOptions(options);

      return _this3.getObserver(defaultedOptions, index).getOptimisticResult(defaultedOptions);
    });
  };

  _proto.getObserver = function getObserver(options, index) {
    var _currentObserver;

    var defaultedOptions = this.client.defaultQueryObserverOptions(options);
    var currentObserver = this.observersMap[defaultedOptions.queryHash];

    if (!currentObserver && defaultedOptions.keepPreviousData) {
      currentObserver = this.observers[index];
    }

    return (_currentObserver = currentObserver) != null ? _currentObserver : new _queryObserver__WEBPACK_IMPORTED_MODULE_1__/* .QueryObserver */ .z(this.client, defaultedOptions);
  };

  _proto.updateObservers = function updateObservers(notifyOptions) {
    var _this4 = this;

    _notifyManager__WEBPACK_IMPORTED_MODULE_2__/* .notifyManager.batch */ .V.batch(function () {
      var hasIndexChange = false;
      var prevObservers = _this4.observers;
      var prevObserversMap = _this4.observersMap;
      var newResult = [];
      var newObservers = [];
      var newObserversMap = {};

      _this4.queries.forEach(function (options, i) {
        var defaultedOptions = _this4.client.defaultQueryObserverOptions(options);

        var queryHash = defaultedOptions.queryHash;

        var observer = _this4.getObserver(defaultedOptions, i);

        if (prevObserversMap[queryHash] || defaultedOptions.keepPreviousData) {
          observer.setOptions(defaultedOptions, notifyOptions);
        }

        if (observer !== prevObservers[i]) {
          hasIndexChange = true;
        }

        newObservers.push(observer);
        newResult.push(observer.getCurrentResult());
        newObserversMap[queryHash] = observer;
      });

      if (prevObservers.length === newObservers.length && !hasIndexChange) {
        return;
      }

      _this4.observers = newObservers;
      _this4.observersMap = newObserversMap;
      _this4.result = newResult;

      if (!_this4.hasListeners()) {
        return;
      }

      (0,_utils__WEBPACK_IMPORTED_MODULE_3__/* .difference */ .e5)(prevObservers, newObservers).forEach(function (observer) {
        observer.destroy();
      });
      (0,_utils__WEBPACK_IMPORTED_MODULE_3__/* .difference */ .e5)(newObservers, prevObservers).forEach(function (observer) {
        observer.subscribe(function (result) {
          _this4.onUpdate(observer, result);
        });
      });

      _this4.notify();
    });
  };

  _proto.onUpdate = function onUpdate(observer, result) {
    var index = this.observers.indexOf(observer);

    if (index !== -1) {
      this.result = (0,_utils__WEBPACK_IMPORTED_MODULE_3__/* .replaceAt */ .Rc)(this.result, index, result);
      this.notify();
    }
  };

  _proto.notify = function notify() {
    var _this5 = this;

    _notifyManager__WEBPACK_IMPORTED_MODULE_2__/* .notifyManager.batch */ .V.batch(function () {
      _this5.listeners.forEach(function (listener) {
        listener(_this5.result);
      });
    });
  };

  return QueriesObserver;
}(_subscribable__WEBPACK_IMPORTED_MODULE_4__/* .Subscribable */ .l);

/***/ }),

/***/ 2122:
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";

// EXPORTS
__webpack_require__.d(__webpack_exports__, {
  "t": function() { return /* binding */ QueryCache; }
});

// EXTERNAL MODULE: ./node_modules/@babel/runtime/helpers/esm/inheritsLoose.js + 1 modules
var inheritsLoose = __webpack_require__(1721);
// EXTERNAL MODULE: ./node_modules/react-query/es/core/utils.js
var utils = __webpack_require__(2288);
// EXTERNAL MODULE: ./node_modules/@babel/runtime/helpers/esm/extends.js
var esm_extends = __webpack_require__(7462);
// EXTERNAL MODULE: ./node_modules/react-query/es/core/notifyManager.js
var notifyManager = __webpack_require__(101);
// EXTERNAL MODULE: ./node_modules/react-query/es/core/logger.js
var logger = __webpack_require__(1909);
// EXTERNAL MODULE: ./node_modules/react-query/es/core/retryer.js
var retryer = __webpack_require__(1216);
;// CONCATENATED MODULE: ./node_modules/react-query/es/core/query.js




 // TYPES

// CLASS
var Query = /*#__PURE__*/function () {
  function Query(config) {
    this.defaultOptions = config.defaultOptions;
    this.setOptions(config.options);
    this.observers = [];
    this.cache = config.cache;
    this.queryKey = config.queryKey;
    this.queryHash = config.queryHash;
    this.initialState = config.state || this.getDefaultState(this.options);
    this.state = this.initialState;
    this.scheduleGc();
  }

  var _proto = Query.prototype;

  _proto.setOptions = function setOptions(options) {
    var _this$options$cacheTi;

    this.options = (0,esm_extends/* default */.Z)({}, this.defaultOptions, options); // Default to 5 minutes if not cache time is set

    this.cacheTime = Math.max(this.cacheTime || 0, (_this$options$cacheTi = this.options.cacheTime) != null ? _this$options$cacheTi : 5 * 60 * 1000);
  };

  _proto.setDefaultOptions = function setDefaultOptions(options) {
    this.defaultOptions = options;
  };

  _proto.scheduleGc = function scheduleGc() {
    var _this = this;

    this.clearGcTimeout();

    if ((0,utils/* isValidTimeout */.PN)(this.cacheTime)) {
      this.gcTimeout = setTimeout(function () {
        _this.optionalRemove();
      }, this.cacheTime);
    }
  };

  _proto.clearGcTimeout = function clearGcTimeout() {
    clearTimeout(this.gcTimeout);
    this.gcTimeout = undefined;
  };

  _proto.optionalRemove = function optionalRemove() {
    if (!this.observers.length && !this.state.isFetching) {
      this.cache.remove(this);
    }
  };

  _proto.setData = function setData(updater, options) {
    var _this$options$isDataE, _this$options;

    var prevData = this.state.data; // Get the new data

    var data = (0,utils/* functionalUpdate */.SE)(updater, prevData); // Use prev data if an isDataEqual function is defined and returns `true`

    if ((_this$options$isDataE = (_this$options = this.options).isDataEqual) == null ? void 0 : _this$options$isDataE.call(_this$options, prevData, data)) {
      data = prevData;
    } else if (this.options.structuralSharing !== false) {
      // Structurally share data between prev and new data if needed
      data = (0,utils/* replaceEqualDeep */.Q$)(prevData, data);
    } // Set data and mark it as cached


    this.dispatch({
      data: data,
      type: 'success',
      dataUpdatedAt: options == null ? void 0 : options.updatedAt
    });
    return data;
  };

  _proto.setState = function setState(state, setStateOptions) {
    this.dispatch({
      type: 'setState',
      state: state,
      setStateOptions: setStateOptions
    });
  };

  _proto.cancel = function cancel(options) {
    var _this$retryer;

    var promise = this.promise;
    (_this$retryer = this.retryer) == null ? void 0 : _this$retryer.cancel(options);
    return promise ? promise.then(utils/* noop */.ZT).catch(utils/* noop */.ZT) : Promise.resolve();
  };

  _proto.destroy = function destroy() {
    this.clearGcTimeout();
    this.cancel({
      silent: true
    });
  };

  _proto.reset = function reset() {
    this.destroy();
    this.setState(this.initialState);
  };

  _proto.isActive = function isActive() {
    return this.observers.some(function (observer) {
      return observer.options.enabled !== false;
    });
  };

  _proto.isFetching = function isFetching() {
    return this.state.isFetching;
  };

  _proto.isStale = function isStale() {
    return this.state.isInvalidated || !this.state.dataUpdatedAt || this.observers.some(function (observer) {
      return observer.getCurrentResult().isStale;
    });
  };

  _proto.isStaleByTime = function isStaleByTime(staleTime) {
    if (staleTime === void 0) {
      staleTime = 0;
    }

    return this.state.isInvalidated || !this.state.dataUpdatedAt || !(0,utils/* timeUntilStale */.Kp)(this.state.dataUpdatedAt, staleTime);
  };

  _proto.onFocus = function onFocus() {
    var _this$retryer2;

    var observer = this.observers.find(function (x) {
      return x.shouldFetchOnWindowFocus();
    });

    if (observer) {
      observer.refetch();
    } // Continue fetch if currently paused


    (_this$retryer2 = this.retryer) == null ? void 0 : _this$retryer2.continue();
  };

  _proto.onOnline = function onOnline() {
    var _this$retryer3;

    var observer = this.observers.find(function (x) {
      return x.shouldFetchOnReconnect();
    });

    if (observer) {
      observer.refetch();
    } // Continue fetch if currently paused


    (_this$retryer3 = this.retryer) == null ? void 0 : _this$retryer3.continue();
  };

  _proto.addObserver = function addObserver(observer) {
    if (this.observers.indexOf(observer) === -1) {
      this.observers.push(observer); // Stop the query from being garbage collected

      this.clearGcTimeout();
      this.cache.notify({
        type: 'observerAdded',
        query: this,
        observer: observer
      });
    }
  };

  _proto.removeObserver = function removeObserver(observer) {
    if (this.observers.indexOf(observer) !== -1) {
      this.observers = this.observers.filter(function (x) {
        return x !== observer;
      });

      if (!this.observers.length) {
        // If the transport layer does not support cancellation
        // we'll let the query continue so the result can be cached
        if (this.retryer) {
          if (this.retryer.isTransportCancelable) {
            this.retryer.cancel({
              revert: true
            });
          } else {
            this.retryer.cancelRetry();
          }
        }

        if (this.cacheTime) {
          this.scheduleGc();
        } else {
          this.cache.remove(this);
        }
      }

      this.cache.notify({
        type: 'observerRemoved',
        query: this,
        observer: observer
      });
    }
  };

  _proto.getObserversCount = function getObserversCount() {
    return this.observers.length;
  };

  _proto.invalidate = function invalidate() {
    if (!this.state.isInvalidated) {
      this.dispatch({
        type: 'invalidate'
      });
    }
  };

  _proto.fetch = function fetch(options, fetchOptions) {
    var _this2 = this,
        _this$options$behavio,
        _context$fetchOptions;

    if (this.state.isFetching) {
      if (this.state.dataUpdatedAt && (fetchOptions == null ? void 0 : fetchOptions.cancelRefetch)) {
        // Silently cancel current fetch if the user wants to cancel refetches
        this.cancel({
          silent: true
        });
      } else if (this.promise) {
        // Return current promise if we are already fetching
        return this.promise;
      }
    } // Update config if passed, otherwise the config from the last execution is used


    if (options) {
      this.setOptions(options);
    } // Use the options from the first observer with a query function if no function is found.
    // This can happen when the query is hydrated or created with setQueryData.


    if (!this.options.queryFn) {
      var observer = this.observers.find(function (x) {
        return x.options.queryFn;
      });

      if (observer) {
        this.setOptions(observer.options);
      }
    }

    var queryKey = (0,utils/* ensureQueryKeyArray */.mc)(this.queryKey); // Create query function context

    var queryFnContext = {
      queryKey: queryKey,
      pageParam: undefined
    }; // Create fetch function

    var fetchFn = function fetchFn() {
      return _this2.options.queryFn ? _this2.options.queryFn(queryFnContext) : Promise.reject('Missing queryFn');
    }; // Trigger behavior hook


    var context = {
      fetchOptions: fetchOptions,
      options: this.options,
      queryKey: queryKey,
      state: this.state,
      fetchFn: fetchFn
    };

    if ((_this$options$behavio = this.options.behavior) == null ? void 0 : _this$options$behavio.onFetch) {
      var _this$options$behavio2;

      (_this$options$behavio2 = this.options.behavior) == null ? void 0 : _this$options$behavio2.onFetch(context);
    } // Store state in case the current fetch needs to be reverted


    this.revertState = this.state; // Set to fetching state if not already in it

    if (!this.state.isFetching || this.state.fetchMeta !== ((_context$fetchOptions = context.fetchOptions) == null ? void 0 : _context$fetchOptions.meta)) {
      var _context$fetchOptions2;

      this.dispatch({
        type: 'fetch',
        meta: (_context$fetchOptions2 = context.fetchOptions) == null ? void 0 : _context$fetchOptions2.meta
      });
    } // Try to fetch the data


    this.retryer = new retryer/* Retryer */.m4({
      fn: context.fetchFn,
      onSuccess: function onSuccess(data) {
        _this2.setData(data); // Notify cache callback


        _this2.cache.config.onSuccess == null ? void 0 : _this2.cache.config.onSuccess(data, _this2); // Remove query after fetching if cache time is 0

        if (_this2.cacheTime === 0) {
          _this2.optionalRemove();
        }
      },
      onError: function onError(error) {
        // Optimistically update state if needed
        if (!((0,retryer/* isCancelledError */.DV)(error) && error.silent)) {
          _this2.dispatch({
            type: 'error',
            error: error
          });
        }

        if (!(0,retryer/* isCancelledError */.DV)(error)) {
          // Notify cache callback
          _this2.cache.config.onError == null ? void 0 : _this2.cache.config.onError(error, _this2); // Log error

          (0,logger/* getLogger */.j)().error(error);
        } // Remove query after fetching if cache time is 0


        if (_this2.cacheTime === 0) {
          _this2.optionalRemove();
        }
      },
      onFail: function onFail() {
        _this2.dispatch({
          type: 'failed'
        });
      },
      onPause: function onPause() {
        _this2.dispatch({
          type: 'pause'
        });
      },
      onContinue: function onContinue() {
        _this2.dispatch({
          type: 'continue'
        });
      },
      retry: context.options.retry,
      retryDelay: context.options.retryDelay
    });
    this.promise = this.retryer.promise;
    return this.promise;
  };

  _proto.dispatch = function dispatch(action) {
    var _this3 = this;

    this.state = this.reducer(this.state, action);
    notifyManager/* notifyManager.batch */.V.batch(function () {
      _this3.observers.forEach(function (observer) {
        observer.onQueryUpdate(action);
      });

      _this3.cache.notify({
        query: _this3,
        type: 'queryUpdated',
        action: action
      });
    });
  };

  _proto.getDefaultState = function getDefaultState(options) {
    var data = typeof options.initialData === 'function' ? options.initialData() : options.initialData;
    var hasInitialData = typeof options.initialData !== 'undefined';
    var initialDataUpdatedAt = hasInitialData ? typeof options.initialDataUpdatedAt === 'function' ? options.initialDataUpdatedAt() : options.initialDataUpdatedAt : 0;
    var hasData = typeof data !== 'undefined';
    return {
      data: data,
      dataUpdateCount: 0,
      dataUpdatedAt: hasData ? initialDataUpdatedAt != null ? initialDataUpdatedAt : Date.now() : 0,
      error: null,
      errorUpdateCount: 0,
      errorUpdatedAt: 0,
      fetchFailureCount: 0,
      fetchMeta: null,
      isFetching: false,
      isInvalidated: false,
      isPaused: false,
      status: hasData ? 'success' : 'idle'
    };
  };

  _proto.reducer = function reducer(state, action) {
    var _action$meta, _action$dataUpdatedAt;

    switch (action.type) {
      case 'failed':
        return (0,esm_extends/* default */.Z)({}, state, {
          fetchFailureCount: state.fetchFailureCount + 1
        });

      case 'pause':
        return (0,esm_extends/* default */.Z)({}, state, {
          isPaused: true
        });

      case 'continue':
        return (0,esm_extends/* default */.Z)({}, state, {
          isPaused: false
        });

      case 'fetch':
        return (0,esm_extends/* default */.Z)({}, state, {
          fetchFailureCount: 0,
          fetchMeta: (_action$meta = action.meta) != null ? _action$meta : null,
          isFetching: true,
          isPaused: false,
          status: !state.dataUpdatedAt ? 'loading' : state.status
        });

      case 'success':
        return (0,esm_extends/* default */.Z)({}, state, {
          data: action.data,
          dataUpdateCount: state.dataUpdateCount + 1,
          dataUpdatedAt: (_action$dataUpdatedAt = action.dataUpdatedAt) != null ? _action$dataUpdatedAt : Date.now(),
          error: null,
          fetchFailureCount: 0,
          isFetching: false,
          isInvalidated: false,
          isPaused: false,
          status: 'success'
        });

      case 'error':
        var error = action.error;

        if ((0,retryer/* isCancelledError */.DV)(error) && error.revert && this.revertState) {
          return (0,esm_extends/* default */.Z)({}, this.revertState);
        }

        return (0,esm_extends/* default */.Z)({}, state, {
          error: error,
          errorUpdateCount: state.errorUpdateCount + 1,
          errorUpdatedAt: Date.now(),
          fetchFailureCount: state.fetchFailureCount + 1,
          isFetching: false,
          isPaused: false,
          status: 'error'
        });

      case 'invalidate':
        return (0,esm_extends/* default */.Z)({}, state, {
          isInvalidated: true
        });

      case 'setState':
        return (0,esm_extends/* default */.Z)({}, state, action.state);

      default:
        return state;
    }
  };

  return Query;
}();
// EXTERNAL MODULE: ./node_modules/react-query/es/core/subscribable.js
var subscribable = __webpack_require__(2943);
;// CONCATENATED MODULE: ./node_modules/react-query/es/core/queryCache.js





// CLASS
var QueryCache = /*#__PURE__*/function (_Subscribable) {
  (0,inheritsLoose/* default */.Z)(QueryCache, _Subscribable);

  function QueryCache(config) {
    var _this;

    _this = _Subscribable.call(this) || this;
    _this.config = config || {};
    _this.queries = [];
    _this.queriesMap = {};
    return _this;
  }

  var _proto = QueryCache.prototype;

  _proto.build = function build(client, options, state) {
    var _options$queryHash;

    var queryKey = options.queryKey;
    var queryHash = (_options$queryHash = options.queryHash) != null ? _options$queryHash : (0,utils/* hashQueryKeyByOptions */.Rm)(queryKey, options);
    var query = this.get(queryHash);

    if (!query) {
      query = new Query({
        cache: this,
        queryKey: queryKey,
        queryHash: queryHash,
        options: client.defaultQueryOptions(options),
        state: state,
        defaultOptions: client.getQueryDefaults(queryKey)
      });
      this.add(query);
    }

    return query;
  };

  _proto.add = function add(query) {
    if (!this.queriesMap[query.queryHash]) {
      this.queriesMap[query.queryHash] = query;
      this.queries.push(query);
      this.notify({
        type: 'queryAdded',
        query: query
      });
    }
  };

  _proto.remove = function remove(query) {
    var queryInMap = this.queriesMap[query.queryHash];

    if (queryInMap) {
      query.destroy();
      this.queries = this.queries.filter(function (x) {
        return x !== query;
      });

      if (queryInMap === query) {
        delete this.queriesMap[query.queryHash];
      }

      this.notify({
        type: 'queryRemoved',
        query: query
      });
    }
  };

  _proto.clear = function clear() {
    var _this2 = this;

    notifyManager/* notifyManager.batch */.V.batch(function () {
      _this2.queries.forEach(function (query) {
        _this2.remove(query);
      });
    });
  };

  _proto.get = function get(queryHash) {
    return this.queriesMap[queryHash];
  };

  _proto.getAll = function getAll() {
    return this.queries;
  };

  _proto.find = function find(arg1, arg2) {
    var _parseFilterArgs = (0,utils/* parseFilterArgs */.I6)(arg1, arg2),
        filters = _parseFilterArgs[0];

    if (typeof filters.exact === 'undefined') {
      filters.exact = true;
    }

    return this.queries.find(function (query) {
      return (0,utils/* matchQuery */._x)(filters, query);
    });
  };

  _proto.findAll = function findAll(arg1, arg2) {
    var _parseFilterArgs2 = (0,utils/* parseFilterArgs */.I6)(arg1, arg2),
        filters = _parseFilterArgs2[0];

    return filters ? this.queries.filter(function (query) {
      return (0,utils/* matchQuery */._x)(filters, query);
    }) : this.queries;
  };

  _proto.notify = function notify(event) {
    var _this3 = this;

    notifyManager/* notifyManager.batch */.V.batch(function () {
      _this3.listeners.forEach(function (listener) {
        listener(event);
      });
    });
  };

  _proto.onFocus = function onFocus() {
    var _this4 = this;

    notifyManager/* notifyManager.batch */.V.batch(function () {
      _this4.queries.forEach(function (query) {
        query.onFocus();
      });
    });
  };

  _proto.onOnline = function onOnline() {
    var _this5 = this;

    notifyManager/* notifyManager.batch */.V.batch(function () {
      _this5.queries.forEach(function (query) {
        query.onOnline();
      });
    });
  };

  return QueryCache;
}(subscribable/* Subscribable */.l);

/***/ }),

/***/ 8328:
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "S": function() { return /* binding */ QueryClient; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(7462);
/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(2288);
/* harmony import */ var _queryCache__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(2122);
/* harmony import */ var _mutationCache__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(8133);
/* harmony import */ var _focusManager__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(9852);
/* harmony import */ var _onlineManager__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(68);
/* harmony import */ var _notifyManager__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(101);
/* harmony import */ var _infiniteQueryBehavior__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(6997);







 // TYPES

// CLASS
var QueryClient = /*#__PURE__*/function () {
  function QueryClient(config) {
    if (config === void 0) {
      config = {};
    }

    this.queryCache = config.queryCache || new _queryCache__WEBPACK_IMPORTED_MODULE_0__/* .QueryCache */ .t();
    this.mutationCache = config.mutationCache || new _mutationCache__WEBPACK_IMPORTED_MODULE_1__/* .MutationCache */ .L();
    this.defaultOptions = config.defaultOptions || {};
    this.queryDefaults = [];
    this.mutationDefaults = [];
  }

  var _proto = QueryClient.prototype;

  _proto.mount = function mount() {
    var _this = this;

    this.unsubscribeFocus = _focusManager__WEBPACK_IMPORTED_MODULE_2__/* .focusManager.subscribe */ .j.subscribe(function () {
      if (_focusManager__WEBPACK_IMPORTED_MODULE_2__/* .focusManager.isFocused */ .j.isFocused() && _onlineManager__WEBPACK_IMPORTED_MODULE_3__/* .onlineManager.isOnline */ .N.isOnline()) {
        _this.mutationCache.onFocus();

        _this.queryCache.onFocus();
      }
    });
    this.unsubscribeOnline = _onlineManager__WEBPACK_IMPORTED_MODULE_3__/* .onlineManager.subscribe */ .N.subscribe(function () {
      if (_focusManager__WEBPACK_IMPORTED_MODULE_2__/* .focusManager.isFocused */ .j.isFocused() && _onlineManager__WEBPACK_IMPORTED_MODULE_3__/* .onlineManager.isOnline */ .N.isOnline()) {
        _this.mutationCache.onOnline();

        _this.queryCache.onOnline();
      }
    });
  };

  _proto.unmount = function unmount() {
    var _this$unsubscribeFocu, _this$unsubscribeOnli;

    (_this$unsubscribeFocu = this.unsubscribeFocus) == null ? void 0 : _this$unsubscribeFocu.call(this);
    (_this$unsubscribeOnli = this.unsubscribeOnline) == null ? void 0 : _this$unsubscribeOnli.call(this);
  };

  _proto.isFetching = function isFetching(arg1, arg2) {
    var _parseFilterArgs = (0,_utils__WEBPACK_IMPORTED_MODULE_4__/* .parseFilterArgs */ .I6)(arg1, arg2),
        filters = _parseFilterArgs[0];

    filters.fetching = true;
    return this.queryCache.findAll(filters).length;
  };

  _proto.isMutating = function isMutating(filters) {
    return this.mutationCache.findAll((0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_5__/* .default */ .Z)({}, filters, {
      fetching: true
    })).length;
  };

  _proto.getQueryData = function getQueryData(queryKey, filters) {
    var _this$queryCache$find;

    return (_this$queryCache$find = this.queryCache.find(queryKey, filters)) == null ? void 0 : _this$queryCache$find.state.data;
  };

  _proto.getQueriesData = function getQueriesData(queryKeyOrFilters) {
    return this.getQueryCache().findAll(queryKeyOrFilters).map(function (_ref) {
      var queryKey = _ref.queryKey,
          state = _ref.state;
      var data = state.data;
      return [queryKey, data];
    });
  };

  _proto.setQueryData = function setQueryData(queryKey, updater, options) {
    var parsedOptions = (0,_utils__WEBPACK_IMPORTED_MODULE_4__/* .parseQueryArgs */ ._v)(queryKey);
    var defaultedOptions = this.defaultQueryOptions(parsedOptions);
    return this.queryCache.build(this, defaultedOptions).setData(updater, options);
  };

  _proto.setQueriesData = function setQueriesData(queryKeyOrFilters, updater, options) {
    var _this2 = this;

    return _notifyManager__WEBPACK_IMPORTED_MODULE_6__/* .notifyManager.batch */ .V.batch(function () {
      return _this2.getQueryCache().findAll(queryKeyOrFilters).map(function (_ref2) {
        var queryKey = _ref2.queryKey;
        return [queryKey, _this2.setQueryData(queryKey, updater, options)];
      });
    });
  };

  _proto.getQueryState = function getQueryState(queryKey, filters) {
    var _this$queryCache$find2;

    return (_this$queryCache$find2 = this.queryCache.find(queryKey, filters)) == null ? void 0 : _this$queryCache$find2.state;
  };

  _proto.removeQueries = function removeQueries(arg1, arg2) {
    var _parseFilterArgs2 = (0,_utils__WEBPACK_IMPORTED_MODULE_4__/* .parseFilterArgs */ .I6)(arg1, arg2),
        filters = _parseFilterArgs2[0];

    var queryCache = this.queryCache;
    _notifyManager__WEBPACK_IMPORTED_MODULE_6__/* .notifyManager.batch */ .V.batch(function () {
      queryCache.findAll(filters).forEach(function (query) {
        queryCache.remove(query);
      });
    });
  };

  _proto.resetQueries = function resetQueries(arg1, arg2, arg3) {
    var _this3 = this;

    var _parseFilterArgs3 = (0,_utils__WEBPACK_IMPORTED_MODULE_4__/* .parseFilterArgs */ .I6)(arg1, arg2, arg3),
        filters = _parseFilterArgs3[0],
        options = _parseFilterArgs3[1];

    var queryCache = this.queryCache;

    var refetchFilters = (0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_5__/* .default */ .Z)({}, filters, {
      active: true
    });

    return _notifyManager__WEBPACK_IMPORTED_MODULE_6__/* .notifyManager.batch */ .V.batch(function () {
      queryCache.findAll(filters).forEach(function (query) {
        query.reset();
      });
      return _this3.refetchQueries(refetchFilters, options);
    });
  };

  _proto.cancelQueries = function cancelQueries(arg1, arg2, arg3) {
    var _this4 = this;

    var _parseFilterArgs4 = (0,_utils__WEBPACK_IMPORTED_MODULE_4__/* .parseFilterArgs */ .I6)(arg1, arg2, arg3),
        filters = _parseFilterArgs4[0],
        _parseFilterArgs4$ = _parseFilterArgs4[1],
        cancelOptions = _parseFilterArgs4$ === void 0 ? {} : _parseFilterArgs4$;

    if (typeof cancelOptions.revert === 'undefined') {
      cancelOptions.revert = true;
    }

    var promises = _notifyManager__WEBPACK_IMPORTED_MODULE_6__/* .notifyManager.batch */ .V.batch(function () {
      return _this4.queryCache.findAll(filters).map(function (query) {
        return query.cancel(cancelOptions);
      });
    });
    return Promise.all(promises).then(_utils__WEBPACK_IMPORTED_MODULE_4__/* .noop */ .ZT).catch(_utils__WEBPACK_IMPORTED_MODULE_4__/* .noop */ .ZT);
  };

  _proto.invalidateQueries = function invalidateQueries(arg1, arg2, arg3) {
    var _ref3,
        _filters$refetchActiv,
        _filters$refetchInact,
        _this5 = this;

    var _parseFilterArgs5 = (0,_utils__WEBPACK_IMPORTED_MODULE_4__/* .parseFilterArgs */ .I6)(arg1, arg2, arg3),
        filters = _parseFilterArgs5[0],
        options = _parseFilterArgs5[1];

    var refetchFilters = (0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_5__/* .default */ .Z)({}, filters, {
      // if filters.refetchActive is not provided and filters.active is explicitly false,
      // e.g. invalidateQueries({ active: false }), we don't want to refetch active queries
      active: (_ref3 = (_filters$refetchActiv = filters.refetchActive) != null ? _filters$refetchActiv : filters.active) != null ? _ref3 : true,
      inactive: (_filters$refetchInact = filters.refetchInactive) != null ? _filters$refetchInact : false
    });

    return _notifyManager__WEBPACK_IMPORTED_MODULE_6__/* .notifyManager.batch */ .V.batch(function () {
      _this5.queryCache.findAll(filters).forEach(function (query) {
        query.invalidate();
      });

      return _this5.refetchQueries(refetchFilters, options);
    });
  };

  _proto.refetchQueries = function refetchQueries(arg1, arg2, arg3) {
    var _this6 = this;

    var _parseFilterArgs6 = (0,_utils__WEBPACK_IMPORTED_MODULE_4__/* .parseFilterArgs */ .I6)(arg1, arg2, arg3),
        filters = _parseFilterArgs6[0],
        options = _parseFilterArgs6[1];

    var promises = _notifyManager__WEBPACK_IMPORTED_MODULE_6__/* .notifyManager.batch */ .V.batch(function () {
      return _this6.queryCache.findAll(filters).map(function (query) {
        return query.fetch(undefined, {
          meta: {
            refetchPage: filters == null ? void 0 : filters.refetchPage
          }
        });
      });
    });
    var promise = Promise.all(promises).then(_utils__WEBPACK_IMPORTED_MODULE_4__/* .noop */ .ZT);

    if (!(options == null ? void 0 : options.throwOnError)) {
      promise = promise.catch(_utils__WEBPACK_IMPORTED_MODULE_4__/* .noop */ .ZT);
    }

    return promise;
  };

  _proto.fetchQuery = function fetchQuery(arg1, arg2, arg3) {
    var parsedOptions = (0,_utils__WEBPACK_IMPORTED_MODULE_4__/* .parseQueryArgs */ ._v)(arg1, arg2, arg3);
    var defaultedOptions = this.defaultQueryOptions(parsedOptions); // https://github.com/tannerlinsley/react-query/issues/652

    if (typeof defaultedOptions.retry === 'undefined') {
      defaultedOptions.retry = false;
    }

    var query = this.queryCache.build(this, defaultedOptions);
    return query.isStaleByTime(defaultedOptions.staleTime) ? query.fetch(defaultedOptions) : Promise.resolve(query.state.data);
  };

  _proto.prefetchQuery = function prefetchQuery(arg1, arg2, arg3) {
    return this.fetchQuery(arg1, arg2, arg3).then(_utils__WEBPACK_IMPORTED_MODULE_4__/* .noop */ .ZT).catch(_utils__WEBPACK_IMPORTED_MODULE_4__/* .noop */ .ZT);
  };

  _proto.fetchInfiniteQuery = function fetchInfiniteQuery(arg1, arg2, arg3) {
    var parsedOptions = (0,_utils__WEBPACK_IMPORTED_MODULE_4__/* .parseQueryArgs */ ._v)(arg1, arg2, arg3);
    parsedOptions.behavior = (0,_infiniteQueryBehavior__WEBPACK_IMPORTED_MODULE_7__/* .infiniteQueryBehavior */ .Gm)();
    return this.fetchQuery(parsedOptions);
  };

  _proto.prefetchInfiniteQuery = function prefetchInfiniteQuery(arg1, arg2, arg3) {
    return this.fetchInfiniteQuery(arg1, arg2, arg3).then(_utils__WEBPACK_IMPORTED_MODULE_4__/* .noop */ .ZT).catch(_utils__WEBPACK_IMPORTED_MODULE_4__/* .noop */ .ZT);
  };

  _proto.cancelMutations = function cancelMutations() {
    var _this7 = this;

    var promises = _notifyManager__WEBPACK_IMPORTED_MODULE_6__/* .notifyManager.batch */ .V.batch(function () {
      return _this7.mutationCache.getAll().map(function (mutation) {
        return mutation.cancel();
      });
    });
    return Promise.all(promises).then(_utils__WEBPACK_IMPORTED_MODULE_4__/* .noop */ .ZT).catch(_utils__WEBPACK_IMPORTED_MODULE_4__/* .noop */ .ZT);
  };

  _proto.resumePausedMutations = function resumePausedMutations() {
    return this.getMutationCache().resumePausedMutations();
  };

  _proto.executeMutation = function executeMutation(options) {
    return this.mutationCache.build(this, options).execute();
  };

  _proto.getQueryCache = function getQueryCache() {
    return this.queryCache;
  };

  _proto.getMutationCache = function getMutationCache() {
    return this.mutationCache;
  };

  _proto.getDefaultOptions = function getDefaultOptions() {
    return this.defaultOptions;
  };

  _proto.setDefaultOptions = function setDefaultOptions(options) {
    this.defaultOptions = options;
  };

  _proto.setQueryDefaults = function setQueryDefaults(queryKey, options) {
    var result = this.queryDefaults.find(function (x) {
      return (0,_utils__WEBPACK_IMPORTED_MODULE_4__/* .hashQueryKey */ .yF)(queryKey) === (0,_utils__WEBPACK_IMPORTED_MODULE_4__/* .hashQueryKey */ .yF)(x.queryKey);
    });

    if (result) {
      result.defaultOptions = options;
    } else {
      this.queryDefaults.push({
        queryKey: queryKey,
        defaultOptions: options
      });
    }
  };

  _proto.getQueryDefaults = function getQueryDefaults(queryKey) {
    var _this$queryDefaults$f;

    return queryKey ? (_this$queryDefaults$f = this.queryDefaults.find(function (x) {
      return (0,_utils__WEBPACK_IMPORTED_MODULE_4__/* .partialMatchKey */ .to)(queryKey, x.queryKey);
    })) == null ? void 0 : _this$queryDefaults$f.defaultOptions : undefined;
  };

  _proto.setMutationDefaults = function setMutationDefaults(mutationKey, options) {
    var result = this.mutationDefaults.find(function (x) {
      return (0,_utils__WEBPACK_IMPORTED_MODULE_4__/* .hashQueryKey */ .yF)(mutationKey) === (0,_utils__WEBPACK_IMPORTED_MODULE_4__/* .hashQueryKey */ .yF)(x.mutationKey);
    });

    if (result) {
      result.defaultOptions = options;
    } else {
      this.mutationDefaults.push({
        mutationKey: mutationKey,
        defaultOptions: options
      });
    }
  };

  _proto.getMutationDefaults = function getMutationDefaults(mutationKey) {
    var _this$mutationDefault;

    return mutationKey ? (_this$mutationDefault = this.mutationDefaults.find(function (x) {
      return (0,_utils__WEBPACK_IMPORTED_MODULE_4__/* .partialMatchKey */ .to)(mutationKey, x.mutationKey);
    })) == null ? void 0 : _this$mutationDefault.defaultOptions : undefined;
  };

  _proto.defaultQueryOptions = function defaultQueryOptions(options) {
    if (options == null ? void 0 : options._defaulted) {
      return options;
    }

    var defaultedOptions = (0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_5__/* .default */ .Z)({}, this.defaultOptions.queries, this.getQueryDefaults(options == null ? void 0 : options.queryKey), options, {
      _defaulted: true
    });

    if (!defaultedOptions.queryHash && defaultedOptions.queryKey) {
      defaultedOptions.queryHash = (0,_utils__WEBPACK_IMPORTED_MODULE_4__/* .hashQueryKeyByOptions */ .Rm)(defaultedOptions.queryKey, defaultedOptions);
    }

    return defaultedOptions;
  };

  _proto.defaultQueryObserverOptions = function defaultQueryObserverOptions(options) {
    return this.defaultQueryOptions(options);
  };

  _proto.defaultMutationOptions = function defaultMutationOptions(options) {
    if (options == null ? void 0 : options._defaulted) {
      return options;
    }

    return (0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_5__/* .default */ .Z)({}, this.defaultOptions.mutations, this.getMutationDefaults(options == null ? void 0 : options.mutationKey), options, {
      _defaulted: true
    });
  };

  _proto.clear = function clear() {
    this.queryCache.clear();
    this.mutationCache.clear();
  };

  return QueryClient;
}();

/***/ }),

/***/ 4254:
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "z": function() { return /* binding */ QueryObserver; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(7462);
/* harmony import */ var _babel_runtime_helpers_esm_inheritsLoose__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(1721);
/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(2288);
/* harmony import */ var _notifyManager__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(101);
/* harmony import */ var _focusManager__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(9852);
/* harmony import */ var _subscribable__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(2943);
/* harmony import */ var _logger__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(1909);
/* harmony import */ var _retryer__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(1216);








var QueryObserver = /*#__PURE__*/function (_Subscribable) {
  (0,_babel_runtime_helpers_esm_inheritsLoose__WEBPACK_IMPORTED_MODULE_0__/* .default */ .Z)(QueryObserver, _Subscribable);

  function QueryObserver(client, options) {
    var _this;

    _this = _Subscribable.call(this) || this;
    _this.client = client;
    _this.options = options;
    _this.trackedProps = [];
    _this.previousSelectError = null;

    _this.bindMethods();

    _this.setOptions(options);

    return _this;
  }

  var _proto = QueryObserver.prototype;

  _proto.bindMethods = function bindMethods() {
    this.remove = this.remove.bind(this);
    this.refetch = this.refetch.bind(this);
  };

  _proto.onSubscribe = function onSubscribe() {
    if (this.listeners.length === 1) {
      this.currentQuery.addObserver(this);

      if (shouldFetchOnMount(this.currentQuery, this.options)) {
        this.executeFetch();
      }

      this.updateTimers();
    }
  };

  _proto.onUnsubscribe = function onUnsubscribe() {
    if (!this.listeners.length) {
      this.destroy();
    }
  };

  _proto.shouldFetchOnReconnect = function shouldFetchOnReconnect() {
    return _shouldFetchOnReconnect(this.currentQuery, this.options);
  };

  _proto.shouldFetchOnWindowFocus = function shouldFetchOnWindowFocus() {
    return _shouldFetchOnWindowFocus(this.currentQuery, this.options);
  };

  _proto.destroy = function destroy() {
    this.listeners = [];
    this.clearTimers();
    this.currentQuery.removeObserver(this);
  };

  _proto.setOptions = function setOptions(options, notifyOptions) {
    var prevOptions = this.options;
    var prevQuery = this.currentQuery;
    this.options = this.client.defaultQueryObserverOptions(options);

    if (typeof this.options.enabled !== 'undefined' && typeof this.options.enabled !== 'boolean') {
      throw new Error('Expected enabled to be a boolean');
    } // Keep previous query key if the user does not supply one


    if (!this.options.queryKey) {
      this.options.queryKey = prevOptions.queryKey;
    }

    this.updateQuery();
    var mounted = this.hasListeners(); // Fetch if there are subscribers

    if (mounted && shouldFetchOptionally(this.currentQuery, prevQuery, this.options, prevOptions)) {
      this.executeFetch();
    } // Update result


    this.updateResult(notifyOptions); // Update stale interval if needed

    if (mounted && (this.currentQuery !== prevQuery || this.options.enabled !== prevOptions.enabled || this.options.staleTime !== prevOptions.staleTime)) {
      this.updateStaleTimeout();
    } // Update refetch interval if needed


    if (mounted && (this.currentQuery !== prevQuery || this.options.enabled !== prevOptions.enabled || this.options.refetchInterval !== prevOptions.refetchInterval)) {
      this.updateRefetchInterval();
    }
  };

  _proto.getOptimisticResult = function getOptimisticResult(options) {
    var defaultedOptions = this.client.defaultQueryObserverOptions(options);
    var query = this.client.getQueryCache().build(this.client, defaultedOptions);
    return this.createResult(query, defaultedOptions);
  };

  _proto.getCurrentResult = function getCurrentResult() {
    return this.currentResult;
  };

  _proto.trackResult = function trackResult(result) {
    var _this2 = this;

    var trackedResult = {};
    Object.keys(result).forEach(function (key) {
      Object.defineProperty(trackedResult, key, {
        configurable: false,
        enumerable: true,
        get: function get() {
          var typedKey = key;

          if (!_this2.trackedProps.includes(typedKey)) {
            _this2.trackedProps.push(typedKey);
          }

          return result[typedKey];
        }
      });
    });
    return trackedResult;
  };

  _proto.getNextResult = function getNextResult(options) {
    var _this3 = this;

    return new Promise(function (resolve, reject) {
      var unsubscribe = _this3.subscribe(function (result) {
        if (!result.isFetching) {
          unsubscribe();

          if (result.isError && (options == null ? void 0 : options.throwOnError)) {
            reject(result.error);
          } else {
            resolve(result);
          }
        }
      });
    });
  };

  _proto.getCurrentQuery = function getCurrentQuery() {
    return this.currentQuery;
  };

  _proto.remove = function remove() {
    this.client.getQueryCache().remove(this.currentQuery);
  };

  _proto.refetch = function refetch(options) {
    return this.fetch((0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_1__/* .default */ .Z)({}, options, {
      meta: {
        refetchPage: options == null ? void 0 : options.refetchPage
      }
    }));
  };

  _proto.fetchOptimistic = function fetchOptimistic(options) {
    var _this4 = this;

    var defaultedOptions = this.client.defaultQueryObserverOptions(options);
    var query = this.client.getQueryCache().build(this.client, defaultedOptions);
    return query.fetch().then(function () {
      return _this4.createResult(query, defaultedOptions);
    });
  };

  _proto.fetch = function fetch(fetchOptions) {
    var _this5 = this;

    return this.executeFetch(fetchOptions).then(function () {
      _this5.updateResult();

      return _this5.currentResult;
    });
  };

  _proto.executeFetch = function executeFetch(fetchOptions) {
    // Make sure we reference the latest query as the current one might have been removed
    this.updateQuery(); // Fetch

    var promise = this.currentQuery.fetch(this.options, fetchOptions);

    if (!(fetchOptions == null ? void 0 : fetchOptions.throwOnError)) {
      promise = promise.catch(_utils__WEBPACK_IMPORTED_MODULE_2__/* .noop */ .ZT);
    }

    return promise;
  };

  _proto.updateStaleTimeout = function updateStaleTimeout() {
    var _this6 = this;

    this.clearStaleTimeout();

    if (_utils__WEBPACK_IMPORTED_MODULE_2__/* .isServer */ .sk || this.currentResult.isStale || !(0,_utils__WEBPACK_IMPORTED_MODULE_2__/* .isValidTimeout */ .PN)(this.options.staleTime)) {
      return;
    }

    var time = (0,_utils__WEBPACK_IMPORTED_MODULE_2__/* .timeUntilStale */ .Kp)(this.currentResult.dataUpdatedAt, this.options.staleTime); // The timeout is sometimes triggered 1 ms before the stale time expiration.
    // To mitigate this issue we always add 1 ms to the timeout.

    var timeout = time + 1;
    this.staleTimeoutId = setTimeout(function () {
      if (!_this6.currentResult.isStale) {
        _this6.updateResult();
      }
    }, timeout);
  };

  _proto.updateRefetchInterval = function updateRefetchInterval() {
    var _this7 = this;

    this.clearRefetchInterval();

    if (_utils__WEBPACK_IMPORTED_MODULE_2__/* .isServer */ .sk || this.options.enabled === false || !(0,_utils__WEBPACK_IMPORTED_MODULE_2__/* .isValidTimeout */ .PN)(this.options.refetchInterval)) {
      return;
    }

    this.refetchIntervalId = setInterval(function () {
      if (_this7.options.refetchIntervalInBackground || _focusManager__WEBPACK_IMPORTED_MODULE_3__/* .focusManager.isFocused */ .j.isFocused()) {
        _this7.executeFetch();
      }
    }, this.options.refetchInterval);
  };

  _proto.updateTimers = function updateTimers() {
    this.updateStaleTimeout();
    this.updateRefetchInterval();
  };

  _proto.clearTimers = function clearTimers() {
    this.clearStaleTimeout();
    this.clearRefetchInterval();
  };

  _proto.clearStaleTimeout = function clearStaleTimeout() {
    clearTimeout(this.staleTimeoutId);
    this.staleTimeoutId = undefined;
  };

  _proto.clearRefetchInterval = function clearRefetchInterval() {
    clearInterval(this.refetchIntervalId);
    this.refetchIntervalId = undefined;
  };

  _proto.createResult = function createResult(query, options) {
    var prevQuery = this.currentQuery;
    var prevOptions = this.options;
    var prevResult = this.currentResult;
    var prevResultState = this.currentResultState;
    var prevResultOptions = this.currentResultOptions;
    var queryChange = query !== prevQuery;
    var queryInitialState = queryChange ? query.state : this.currentQueryInitialState;
    var prevQueryResult = queryChange ? this.currentResult : this.previousQueryResult;
    var state = query.state;
    var dataUpdatedAt = state.dataUpdatedAt,
        error = state.error,
        errorUpdatedAt = state.errorUpdatedAt,
        isFetching = state.isFetching,
        status = state.status;
    var isPreviousData = false;
    var isPlaceholderData = false;
    var data; // Optimistically set result in fetching state if needed

    if (options.optimisticResults) {
      var mounted = this.hasListeners();
      var fetchOnMount = !mounted && shouldFetchOnMount(query, options);
      var fetchOptionally = mounted && shouldFetchOptionally(query, prevQuery, options, prevOptions);

      if (fetchOnMount || fetchOptionally) {
        isFetching = true;

        if (!dataUpdatedAt) {
          status = 'loading';
        }
      }
    } // Keep previous data if needed


    if (options.keepPreviousData && !state.dataUpdateCount && (prevQueryResult == null ? void 0 : prevQueryResult.isSuccess) && status !== 'error') {
      data = prevQueryResult.data;
      dataUpdatedAt = prevQueryResult.dataUpdatedAt;
      status = prevQueryResult.status;
      isPreviousData = true;
    } // Select data if needed
    else if (options.select && typeof state.data !== 'undefined') {
        // Memoize select result
        if (prevResult && state.data === (prevResultState == null ? void 0 : prevResultState.data) && options.select === (prevResultOptions == null ? void 0 : prevResultOptions.select) && !this.previousSelectError) {
          data = prevResult.data;
        } else {
          try {
            data = options.select(state.data);

            if (options.structuralSharing !== false) {
              data = (0,_utils__WEBPACK_IMPORTED_MODULE_2__/* .replaceEqualDeep */ .Q$)(prevResult == null ? void 0 : prevResult.data, data);
            }

            this.previousSelectError = null;
          } catch (selectError) {
            (0,_logger__WEBPACK_IMPORTED_MODULE_4__/* .getLogger */ .j)().error(selectError);
            error = selectError;
            this.previousSelectError = selectError;
            errorUpdatedAt = Date.now();
            status = 'error';
          }
        }
      } // Use query data
      else {
          data = state.data;
        } // Show placeholder data if needed


    if (typeof options.placeholderData !== 'undefined' && typeof data === 'undefined' && (status === 'loading' || status === 'idle')) {
      var placeholderData; // Memoize placeholder data

      if ((prevResult == null ? void 0 : prevResult.isPlaceholderData) && options.placeholderData === (prevResultOptions == null ? void 0 : prevResultOptions.placeholderData)) {
        placeholderData = prevResult.data;
      } else {
        placeholderData = typeof options.placeholderData === 'function' ? options.placeholderData() : options.placeholderData;

        if (options.select && typeof placeholderData !== 'undefined') {
          try {
            placeholderData = options.select(placeholderData);

            if (options.structuralSharing !== false) {
              placeholderData = (0,_utils__WEBPACK_IMPORTED_MODULE_2__/* .replaceEqualDeep */ .Q$)(prevResult == null ? void 0 : prevResult.data, placeholderData);
            }

            this.previousSelectError = null;
          } catch (selectError) {
            (0,_logger__WEBPACK_IMPORTED_MODULE_4__/* .getLogger */ .j)().error(selectError);
            error = selectError;
            this.previousSelectError = selectError;
            errorUpdatedAt = Date.now();
            status = 'error';
          }
        }
      }

      if (typeof placeholderData !== 'undefined') {
        status = 'success';
        data = placeholderData;
        isPlaceholderData = true;
      }
    }

    var result = {
      status: status,
      isLoading: status === 'loading',
      isSuccess: status === 'success',
      isError: status === 'error',
      isIdle: status === 'idle',
      data: data,
      dataUpdatedAt: dataUpdatedAt,
      error: error,
      errorUpdatedAt: errorUpdatedAt,
      failureCount: state.fetchFailureCount,
      isFetched: state.dataUpdateCount > 0 || state.errorUpdateCount > 0,
      isFetchedAfterMount: state.dataUpdateCount > queryInitialState.dataUpdateCount || state.errorUpdateCount > queryInitialState.errorUpdateCount,
      isFetching: isFetching,
      isLoadingError: status === 'error' && state.dataUpdatedAt === 0,
      isPlaceholderData: isPlaceholderData,
      isPreviousData: isPreviousData,
      isRefetchError: status === 'error' && state.dataUpdatedAt !== 0,
      isStale: isStale(query, options),
      refetch: this.refetch,
      remove: this.remove
    };
    return result;
  };

  _proto.shouldNotifyListeners = function shouldNotifyListeners(result, prevResult) {
    if (!prevResult) {
      return true;
    }

    if (result === prevResult) {
      return false;
    }

    var _this$options = this.options,
        notifyOnChangeProps = _this$options.notifyOnChangeProps,
        notifyOnChangePropsExclusions = _this$options.notifyOnChangePropsExclusions;

    if (!notifyOnChangeProps && !notifyOnChangePropsExclusions) {
      return true;
    }

    if (notifyOnChangeProps === 'tracked' && !this.trackedProps.length) {
      return true;
    }

    var includedProps = notifyOnChangeProps === 'tracked' ? this.trackedProps : notifyOnChangeProps;
    return Object.keys(result).some(function (key) {
      var typedKey = key;
      var changed = result[typedKey] !== prevResult[typedKey];
      var isIncluded = includedProps == null ? void 0 : includedProps.some(function (x) {
        return x === key;
      });
      var isExcluded = notifyOnChangePropsExclusions == null ? void 0 : notifyOnChangePropsExclusions.some(function (x) {
        return x === key;
      });
      return changed && !isExcluded && (!includedProps || isIncluded);
    });
  };

  _proto.updateResult = function updateResult(notifyOptions) {
    var prevResult = this.currentResult;
    this.currentResult = this.createResult(this.currentQuery, this.options);
    this.currentResultState = this.currentQuery.state;
    this.currentResultOptions = this.options; // Only notify if something has changed

    if ((0,_utils__WEBPACK_IMPORTED_MODULE_2__/* .shallowEqualObjects */ .VS)(this.currentResult, prevResult)) {
      return;
    } // Determine which callbacks to trigger


    var defaultNotifyOptions = {
      cache: true
    };

    if ((notifyOptions == null ? void 0 : notifyOptions.listeners) !== false && this.shouldNotifyListeners(this.currentResult, prevResult)) {
      defaultNotifyOptions.listeners = true;
    }

    this.notify((0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_1__/* .default */ .Z)({}, defaultNotifyOptions, notifyOptions));
  };

  _proto.updateQuery = function updateQuery() {
    var query = this.client.getQueryCache().build(this.client, this.options);

    if (query === this.currentQuery) {
      return;
    }

    var prevQuery = this.currentQuery;
    this.currentQuery = query;
    this.currentQueryInitialState = query.state;
    this.previousQueryResult = this.currentResult;

    if (this.hasListeners()) {
      prevQuery == null ? void 0 : prevQuery.removeObserver(this);
      query.addObserver(this);
    }
  };

  _proto.onQueryUpdate = function onQueryUpdate(action) {
    var notifyOptions = {};

    if (action.type === 'success') {
      notifyOptions.onSuccess = true;
    } else if (action.type === 'error' && !(0,_retryer__WEBPACK_IMPORTED_MODULE_5__/* .isCancelledError */ .DV)(action.error)) {
      notifyOptions.onError = true;
    }

    this.updateResult(notifyOptions);

    if (this.hasListeners()) {
      this.updateTimers();
    }
  };

  _proto.notify = function notify(notifyOptions) {
    var _this8 = this;

    _notifyManager__WEBPACK_IMPORTED_MODULE_6__/* .notifyManager.batch */ .V.batch(function () {
      // First trigger the configuration callbacks
      if (notifyOptions.onSuccess) {
        _this8.options.onSuccess == null ? void 0 : _this8.options.onSuccess(_this8.currentResult.data);
        _this8.options.onSettled == null ? void 0 : _this8.options.onSettled(_this8.currentResult.data, null);
      } else if (notifyOptions.onError) {
        _this8.options.onError == null ? void 0 : _this8.options.onError(_this8.currentResult.error);
        _this8.options.onSettled == null ? void 0 : _this8.options.onSettled(undefined, _this8.currentResult.error);
      } // Then trigger the listeners


      if (notifyOptions.listeners) {
        _this8.listeners.forEach(function (listener) {
          listener(_this8.currentResult);
        });
      } // Then the cache listeners


      if (notifyOptions.cache) {
        _this8.client.getQueryCache().notify({
          query: _this8.currentQuery,
          type: 'observerResultsUpdated'
        });
      }
    });
  };

  return QueryObserver;
}(_subscribable__WEBPACK_IMPORTED_MODULE_7__/* .Subscribable */ .l);

function shouldLoadOnMount(query, options) {
  return options.enabled !== false && !query.state.dataUpdatedAt && !(query.state.status === 'error' && options.retryOnMount === false);
}

function shouldRefetchOnMount(query, options) {
  return options.enabled !== false && query.state.dataUpdatedAt > 0 && (options.refetchOnMount === 'always' || options.refetchOnMount !== false && isStale(query, options));
}

function shouldFetchOnMount(query, options) {
  return shouldLoadOnMount(query, options) || shouldRefetchOnMount(query, options);
}

function _shouldFetchOnReconnect(query, options) {
  return options.enabled !== false && (options.refetchOnReconnect === 'always' || options.refetchOnReconnect !== false && isStale(query, options));
}

function _shouldFetchOnWindowFocus(query, options) {
  return options.enabled !== false && (options.refetchOnWindowFocus === 'always' || options.refetchOnWindowFocus !== false && isStale(query, options));
}

function shouldFetchOptionally(query, prevQuery, options, prevOptions) {
  return options.enabled !== false && (query !== prevQuery || prevOptions.enabled === false) && (query.state.status !== 'error' || prevOptions.enabled === false) && isStale(query, options);
}

function isStale(query, options) {
  return query.isStaleByTime(options.staleTime);
}

/***/ }),

/***/ 1216:
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "LE": function() { return /* binding */ isCancelable; },
/* harmony export */   "p8": function() { return /* binding */ CancelledError; },
/* harmony export */   "DV": function() { return /* binding */ isCancelledError; },
/* harmony export */   "m4": function() { return /* binding */ Retryer; }
/* harmony export */ });
/* harmony import */ var _focusManager__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(9852);
/* harmony import */ var _onlineManager__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(68);
/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(2288);


 // TYPES

function defaultRetryDelay(failureCount) {
  return Math.min(1000 * Math.pow(2, failureCount), 30000);
}

function isCancelable(value) {
  return typeof (value == null ? void 0 : value.cancel) === 'function';
}
var CancelledError = function CancelledError(options) {
  this.revert = options == null ? void 0 : options.revert;
  this.silent = options == null ? void 0 : options.silent;
};
function isCancelledError(value) {
  return value instanceof CancelledError;
} // CLASS

var Retryer = function Retryer(config) {
  var _this = this;

  var cancelRetry = false;
  var cancelFn;
  var continueFn;
  var promiseResolve;
  var promiseReject;

  this.cancel = function (cancelOptions) {
    return cancelFn == null ? void 0 : cancelFn(cancelOptions);
  };

  this.cancelRetry = function () {
    cancelRetry = true;
  };

  this.continue = function () {
    return continueFn == null ? void 0 : continueFn();
  };

  this.failureCount = 0;
  this.isPaused = false;
  this.isResolved = false;
  this.isTransportCancelable = false;
  this.promise = new Promise(function (outerResolve, outerReject) {
    promiseResolve = outerResolve;
    promiseReject = outerReject;
  });

  var resolve = function resolve(value) {
    if (!_this.isResolved) {
      _this.isResolved = true;
      config.onSuccess == null ? void 0 : config.onSuccess(value);
      continueFn == null ? void 0 : continueFn();
      promiseResolve(value);
    }
  };

  var reject = function reject(value) {
    if (!_this.isResolved) {
      _this.isResolved = true;
      config.onError == null ? void 0 : config.onError(value);
      continueFn == null ? void 0 : continueFn();
      promiseReject(value);
    }
  };

  var pause = function pause() {
    return new Promise(function (continueResolve) {
      continueFn = continueResolve;
      _this.isPaused = true;
      config.onPause == null ? void 0 : config.onPause();
    }).then(function () {
      continueFn = undefined;
      _this.isPaused = false;
      config.onContinue == null ? void 0 : config.onContinue();
    });
  }; // Create loop function


  var run = function run() {
    // Do nothing if already resolved
    if (_this.isResolved) {
      return;
    }

    var promiseOrValue; // Execute query

    try {
      promiseOrValue = config.fn();
    } catch (error) {
      promiseOrValue = Promise.reject(error);
    } // Create callback to cancel this fetch


    cancelFn = function cancelFn(cancelOptions) {
      if (!_this.isResolved) {
        reject(new CancelledError(cancelOptions)); // Cancel transport if supported

        if (isCancelable(promiseOrValue)) {
          try {
            promiseOrValue.cancel();
          } catch (_unused) {}
        }
      }
    }; // Check if the transport layer support cancellation


    _this.isTransportCancelable = isCancelable(promiseOrValue);
    Promise.resolve(promiseOrValue).then(resolve).catch(function (error) {
      var _config$retry, _config$retryDelay;

      // Stop if the fetch is already resolved
      if (_this.isResolved) {
        return;
      } // Do we need to retry the request?


      var retry = (_config$retry = config.retry) != null ? _config$retry : 3;
      var retryDelay = (_config$retryDelay = config.retryDelay) != null ? _config$retryDelay : defaultRetryDelay;
      var delay = typeof retryDelay === 'function' ? retryDelay(_this.failureCount, error) : retryDelay;
      var shouldRetry = retry === true || typeof retry === 'number' && _this.failureCount < retry || typeof retry === 'function' && retry(_this.failureCount, error);

      if (cancelRetry || !shouldRetry) {
        // We are done if the query does not need to be retried
        reject(error);
        return;
      }

      _this.failureCount++; // Notify on fail

      config.onFail == null ? void 0 : config.onFail(_this.failureCount, error); // Delay

      (0,_utils__WEBPACK_IMPORTED_MODULE_0__/* .sleep */ .Gh)(delay) // Pause if the document is not visible or when the device is offline
      .then(function () {
        if (!_focusManager__WEBPACK_IMPORTED_MODULE_1__/* .focusManager.isFocused */ .j.isFocused() || !_onlineManager__WEBPACK_IMPORTED_MODULE_2__/* .onlineManager.isOnline */ .N.isOnline()) {
          return pause();
        }
      }).then(function () {
        if (cancelRetry) {
          reject(error);
        } else {
          run();
        }
      });
    });
  }; // Start loop


  run();
};

/***/ }),

/***/ 2943:
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "l": function() { return /* binding */ Subscribable; }
/* harmony export */ });
var Subscribable = /*#__PURE__*/function () {
  function Subscribable() {
    this.listeners = [];
  }

  var _proto = Subscribable.prototype;

  _proto.subscribe = function subscribe(listener) {
    var _this = this;

    var callback = listener || function () {
      return undefined;
    };

    this.listeners.push(callback);
    this.onSubscribe();
    return function () {
      _this.listeners = _this.listeners.filter(function (x) {
        return x !== callback;
      });

      _this.onUnsubscribe();
    };
  };

  _proto.hasListeners = function hasListeners() {
    return this.listeners.length > 0;
  };

  _proto.onSubscribe = function onSubscribe() {// Do nothing
  };

  _proto.onUnsubscribe = function onUnsubscribe() {// Do nothing
  };

  return Subscribable;
}();

/***/ }),

/***/ 6755:
/***/ (function() {



/***/ }),

/***/ 2288:
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "sk": function() { return /* binding */ isServer; },
/* harmony export */   "ZT": function() { return /* binding */ noop; },
/* harmony export */   "SE": function() { return /* binding */ functionalUpdate; },
/* harmony export */   "PN": function() { return /* binding */ isValidTimeout; },
/* harmony export */   "mc": function() { return /* binding */ ensureQueryKeyArray; },
/* harmony export */   "e5": function() { return /* binding */ difference; },
/* harmony export */   "Rc": function() { return /* binding */ replaceAt; },
/* harmony export */   "Kp": function() { return /* binding */ timeUntilStale; },
/* harmony export */   "_v": function() { return /* binding */ parseQueryArgs; },
/* harmony export */   "lV": function() { return /* binding */ parseMutationArgs; },
/* harmony export */   "I6": function() { return /* binding */ parseFilterArgs; },
/* harmony export */   "cb": function() { return /* binding */ parseMutationFilterArgs; },
/* harmony export */   "_x": function() { return /* binding */ matchQuery; },
/* harmony export */   "X7": function() { return /* binding */ matchMutation; },
/* harmony export */   "Rm": function() { return /* binding */ hashQueryKeyByOptions; },
/* harmony export */   "yF": function() { return /* binding */ hashQueryKey; },
/* harmony export */   "to": function() { return /* binding */ partialMatchKey; },
/* harmony export */   "Q$": function() { return /* binding */ replaceEqualDeep; },
/* harmony export */   "VS": function() { return /* binding */ shallowEqualObjects; },
/* harmony export */   "VZ": function() { return /* binding */ isError; },
/* harmony export */   "Gh": function() { return /* binding */ sleep; },
/* harmony export */   "A4": function() { return /* binding */ scheduleMicrotask; }
/* harmony export */ });
/* unused harmony exports mapQueryStatusFilter, stableValueHash, partialDeepEqual, isPlainObject, isQueryKey */
/* harmony import */ var _babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(7462);

// TYPES
// UTILS
var isServer = typeof window === 'undefined';
function noop() {
  return undefined;
}
function functionalUpdate(updater, input) {
  return typeof updater === 'function' ? updater(input) : updater;
}
function isValidTimeout(value) {
  return typeof value === 'number' && value >= 0 && value !== Infinity;
}
function ensureQueryKeyArray(value) {
  return Array.isArray(value) ? value : [value];
}
function difference(array1, array2) {
  return array1.filter(function (x) {
    return array2.indexOf(x) === -1;
  });
}
function replaceAt(array, index, value) {
  var copy = array.slice(0);
  copy[index] = value;
  return copy;
}
function timeUntilStale(updatedAt, staleTime) {
  return Math.max(updatedAt + (staleTime || 0) - Date.now(), 0);
}
function parseQueryArgs(arg1, arg2, arg3) {
  if (!isQueryKey(arg1)) {
    return arg1;
  }

  if (typeof arg2 === 'function') {
    return (0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__/* .default */ .Z)({}, arg3, {
      queryKey: arg1,
      queryFn: arg2
    });
  }

  return (0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__/* .default */ .Z)({}, arg2, {
    queryKey: arg1
  });
}
function parseMutationArgs(arg1, arg2, arg3) {
  if (isQueryKey(arg1)) {
    if (typeof arg2 === 'function') {
      return (0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__/* .default */ .Z)({}, arg3, {
        mutationKey: arg1,
        mutationFn: arg2
      });
    }

    return (0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__/* .default */ .Z)({}, arg2, {
      mutationKey: arg1
    });
  }

  if (typeof arg1 === 'function') {
    return (0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__/* .default */ .Z)({}, arg2, {
      mutationFn: arg1
    });
  }

  return (0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__/* .default */ .Z)({}, arg1);
}
function parseFilterArgs(arg1, arg2, arg3) {
  return isQueryKey(arg1) ? [(0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__/* .default */ .Z)({}, arg2, {
    queryKey: arg1
  }), arg3] : [arg1 || {}, arg2];
}
function parseMutationFilterArgs(arg1, arg2) {
  return isQueryKey(arg1) ? (0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__/* .default */ .Z)({}, arg2, {
    mutationKey: arg1
  }) : arg1;
}
function mapQueryStatusFilter(active, inactive) {
  if (active === true && inactive === true || active == null && inactive == null) {
    return 'all';
  } else if (active === false && inactive === false) {
    return 'none';
  } else {
    // At this point, active|inactive can only be true|false or false|true
    // so, when only one value is provided, the missing one has to be the negated value
    var isActive = active != null ? active : !inactive;
    return isActive ? 'active' : 'inactive';
  }
}
function matchQuery(filters, query) {
  var active = filters.active,
      exact = filters.exact,
      fetching = filters.fetching,
      inactive = filters.inactive,
      predicate = filters.predicate,
      queryKey = filters.queryKey,
      stale = filters.stale;

  if (isQueryKey(queryKey)) {
    if (exact) {
      if (query.queryHash !== hashQueryKeyByOptions(queryKey, query.options)) {
        return false;
      }
    } else if (!partialMatchKey(query.queryKey, queryKey)) {
      return false;
    }
  }

  var queryStatusFilter = mapQueryStatusFilter(active, inactive);

  if (queryStatusFilter === 'none') {
    return false;
  } else if (queryStatusFilter !== 'all') {
    var isActive = query.isActive();

    if (queryStatusFilter === 'active' && !isActive) {
      return false;
    }

    if (queryStatusFilter === 'inactive' && isActive) {
      return false;
    }
  }

  if (typeof stale === 'boolean' && query.isStale() !== stale) {
    return false;
  }

  if (typeof fetching === 'boolean' && query.isFetching() !== fetching) {
    return false;
  }

  if (predicate && !predicate(query)) {
    return false;
  }

  return true;
}
function matchMutation(filters, mutation) {
  var exact = filters.exact,
      fetching = filters.fetching,
      predicate = filters.predicate,
      mutationKey = filters.mutationKey;

  if (isQueryKey(mutationKey)) {
    if (!mutation.options.mutationKey) {
      return false;
    }

    if (exact) {
      if (hashQueryKey(mutation.options.mutationKey) !== hashQueryKey(mutationKey)) {
        return false;
      }
    } else if (!partialMatchKey(mutation.options.mutationKey, mutationKey)) {
      return false;
    }
  }

  if (typeof fetching === 'boolean' && mutation.state.status === 'loading' !== fetching) {
    return false;
  }

  if (predicate && !predicate(mutation)) {
    return false;
  }

  return true;
}
function hashQueryKeyByOptions(queryKey, options) {
  var hashFn = (options == null ? void 0 : options.queryKeyHashFn) || hashQueryKey;
  return hashFn(queryKey);
}
/**
 * Default query keys hash function.
 */

function hashQueryKey(queryKey) {
  var asArray = ensureQueryKeyArray(queryKey);
  return stableValueHash(asArray);
}
/**
 * Hashes the value into a stable hash.
 */

function stableValueHash(value) {
  return JSON.stringify(value, function (_, val) {
    return isPlainObject(val) ? Object.keys(val).sort().reduce(function (result, key) {
      result[key] = val[key];
      return result;
    }, {}) : val;
  });
}
/**
 * Checks if key `b` partially matches with key `a`.
 */

function partialMatchKey(a, b) {
  return partialDeepEqual(ensureQueryKeyArray(a), ensureQueryKeyArray(b));
}
/**
 * Checks if `b` partially matches with `a`.
 */

function partialDeepEqual(a, b) {
  if (a === b) {
    return true;
  }

  if (typeof a !== typeof b) {
    return false;
  }

  if (a && b && typeof a === 'object' && typeof b === 'object') {
    return !Object.keys(b).some(function (key) {
      return !partialDeepEqual(a[key], b[key]);
    });
  }

  return false;
}
/**
 * This function returns `a` if `b` is deeply equal.
 * If not, it will replace any deeply equal children of `b` with those of `a`.
 * This can be used for structural sharing between JSON values for example.
 */

function replaceEqualDeep(a, b) {
  if (a === b) {
    return a;
  }

  var array = Array.isArray(a) && Array.isArray(b);

  if (array || isPlainObject(a) && isPlainObject(b)) {
    var aSize = array ? a.length : Object.keys(a).length;
    var bItems = array ? b : Object.keys(b);
    var bSize = bItems.length;
    var copy = array ? [] : {};
    var equalItems = 0;

    for (var i = 0; i < bSize; i++) {
      var key = array ? i : bItems[i];
      copy[key] = replaceEqualDeep(a[key], b[key]);

      if (copy[key] === a[key]) {
        equalItems++;
      }
    }

    return aSize === bSize && equalItems === aSize ? a : copy;
  }

  return b;
}
/**
 * Shallow compare objects. Only works with objects that always have the same properties.
 */

function shallowEqualObjects(a, b) {
  if (a && !b || b && !a) {
    return false;
  }

  for (var key in a) {
    if (a[key] !== b[key]) {
      return false;
    }
  }

  return true;
} // Copied from: https://github.com/jonschlinkert/is-plain-object

function isPlainObject(o) {
  if (!hasObjectPrototype(o)) {
    return false;
  } // If has modified constructor


  var ctor = o.constructor;

  if (typeof ctor === 'undefined') {
    return true;
  } // If has modified prototype


  var prot = ctor.prototype;

  if (!hasObjectPrototype(prot)) {
    return false;
  } // If constructor does not have an Object-specific method


  if (!prot.hasOwnProperty('isPrototypeOf')) {
    return false;
  } // Most likely a plain Object


  return true;
}

function hasObjectPrototype(o) {
  return Object.prototype.toString.call(o) === '[object Object]';
}

function isQueryKey(value) {
  return typeof value === 'string' || Array.isArray(value);
}
function isError(value) {
  return value instanceof Error;
}
function sleep(timeout) {
  return new Promise(function (resolve) {
    setTimeout(resolve, timeout);
  });
}
/**
 * Schedules a microtask.
 * This can be useful to schedule state updates after rendering.
 */

function scheduleMicrotask(callback) {
  Promise.resolve().then(callback).catch(function (error) {
    return setTimeout(function () {
      throw error;
    });
  });
}

/***/ }),

/***/ 8767:
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _core__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(6747);
/* harmony reexport (unknown) */ var __WEBPACK_REEXPORT_OBJECT__ = {};
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _core__WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== "default") __WEBPACK_REEXPORT_OBJECT__[__WEBPACK_IMPORT_KEY__] = function(key) { return _core__WEBPACK_IMPORTED_MODULE_0__[key]; }.bind(0, __WEBPACK_IMPORT_KEY__)
/* harmony reexport (unknown) */ __webpack_require__.d(__webpack_exports__, __WEBPACK_REEXPORT_OBJECT__);
/* harmony import */ var _react__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(458);
/* harmony reexport (unknown) */ var __WEBPACK_REEXPORT_OBJECT__ = {};
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _react__WEBPACK_IMPORTED_MODULE_1__) if(["default","CancelledError","QueryCache","QueryClient","QueryObserver","QueriesObserver","InfiniteQueryObserver","MutationCache","MutationObserver","setLogger","notifyManager","focusManager","onlineManager","hashQueryKey","isError","isCancelledError"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) __WEBPACK_REEXPORT_OBJECT__[__WEBPACK_IMPORT_KEY__] = function(key) { return _react__WEBPACK_IMPORTED_MODULE_1__[key]; }.bind(0, __WEBPACK_IMPORT_KEY__)
/* harmony reexport (unknown) */ __webpack_require__.d(__webpack_exports__, __WEBPACK_REEXPORT_OBJECT__);



/***/ }),

/***/ 4921:
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "N": function() { return /* binding */ useQueryClient; },
/* harmony export */   "a": function() { return /* binding */ QueryClientProvider; }
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(7294);

var defaultContext = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0__.createContext(undefined);
var QueryClientSharingContext = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0__.createContext(false); // if contextSharing is on, we share the first and at least one
// instance of the context across the window
// to ensure that if React Query is used across
// different bundles or microfrontends they will
// all use the same **instance** of context, regardless
// of module scoping.

function getQueryClientContext(contextSharing) {
  if (contextSharing && typeof window !== 'undefined') {
    if (!window.ReactQueryClientContext) {
      window.ReactQueryClientContext = defaultContext;
    }

    return window.ReactQueryClientContext;
  }

  return defaultContext;
}

var useQueryClient = function useQueryClient() {
  var queryClient = react__WEBPACK_IMPORTED_MODULE_0__.useContext(getQueryClientContext(react__WEBPACK_IMPORTED_MODULE_0__.useContext(QueryClientSharingContext)));

  if (!queryClient) {
    throw new Error('No QueryClient set, use QueryClientProvider to set one');
  }

  return queryClient;
};
var QueryClientProvider = function QueryClientProvider(_ref) {
  var client = _ref.client,
      _ref$contextSharing = _ref.contextSharing,
      contextSharing = _ref$contextSharing === void 0 ? false : _ref$contextSharing,
      children = _ref.children;
  react__WEBPACK_IMPORTED_MODULE_0__.useEffect(function () {
    client.mount();
    return function () {
      client.unmount();
    };
  }, [client]);
  var Context = getQueryClientContext(contextSharing);
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0__.createElement(QueryClientSharingContext.Provider, {
    value: contextSharing
  }, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0__.createElement(Context.Provider, {
    value: client
  }, children));
};

/***/ }),

/***/ 2362:
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "_": function() { return /* binding */ useQueryErrorResetBoundary; },
/* harmony export */   "k": function() { return /* binding */ QueryErrorResetBoundary; }
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(7294);
 // CONTEXT

function createValue() {
  var _isReset = false;
  return {
    clearReset: function clearReset() {
      _isReset = false;
    },
    reset: function reset() {
      _isReset = true;
    },
    isReset: function isReset() {
      return _isReset;
    }
  };
}

var QueryErrorResetBoundaryContext = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0__.createContext(createValue()); // HOOK

var useQueryErrorResetBoundary = function useQueryErrorResetBoundary() {
  return react__WEBPACK_IMPORTED_MODULE_0__.useContext(QueryErrorResetBoundaryContext);
}; // COMPONENT

var QueryErrorResetBoundary = function QueryErrorResetBoundary(_ref) {
  var children = _ref.children;
  var value = react__WEBPACK_IMPORTED_MODULE_0__.useMemo(function () {
    return createValue();
  }, []);
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0__.createElement(QueryErrorResetBoundaryContext.Provider, {
    value: value
  }, typeof children === 'function' ? children(value) : children);
};

/***/ }),

/***/ 458:
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "QueryClientProvider": function() { return /* reexport safe */ _QueryClientProvider__WEBPACK_IMPORTED_MODULE_2__.a; },
/* harmony export */   "useQueryClient": function() { return /* reexport safe */ _QueryClientProvider__WEBPACK_IMPORTED_MODULE_2__.N; },
/* harmony export */   "QueryErrorResetBoundary": function() { return /* reexport safe */ _QueryErrorResetBoundary__WEBPACK_IMPORTED_MODULE_3__.k; },
/* harmony export */   "useQueryErrorResetBoundary": function() { return /* reexport safe */ _QueryErrorResetBoundary__WEBPACK_IMPORTED_MODULE_3__._; },
/* harmony export */   "useIsFetching": function() { return /* reexport safe */ _useIsFetching__WEBPACK_IMPORTED_MODULE_4__.y; },
/* harmony export */   "useIsMutating": function() { return /* reexport safe */ _useIsMutating__WEBPACK_IMPORTED_MODULE_5__.B; },
/* harmony export */   "useMutation": function() { return /* reexport safe */ _useMutation__WEBPACK_IMPORTED_MODULE_6__.D; },
/* harmony export */   "useQuery": function() { return /* reexport safe */ _useQuery__WEBPACK_IMPORTED_MODULE_7__.a; },
/* harmony export */   "useQueries": function() { return /* reexport safe */ _useQueries__WEBPACK_IMPORTED_MODULE_8__.h; },
/* harmony export */   "useInfiniteQuery": function() { return /* reexport safe */ _useInfiniteQuery__WEBPACK_IMPORTED_MODULE_9__.N; }
/* harmony export */ });
/* harmony import */ var _setBatchUpdatesFn__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(1930);
/* harmony import */ var _setLogger__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(6490);
/* harmony import */ var _QueryClientProvider__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(4921);
/* harmony import */ var _QueryErrorResetBoundary__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(2362);
/* harmony import */ var _useIsFetching__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(934);
/* harmony import */ var _useIsMutating__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(1607);
/* harmony import */ var _useMutation__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(4493);
/* harmony import */ var _useQuery__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(9693);
/* harmony import */ var _useQueries__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(8522);
/* harmony import */ var _useInfiniteQuery__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(5275);
/* harmony import */ var _types__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(1251);
/* harmony import */ var _types__WEBPACK_IMPORTED_MODULE_10___default = /*#__PURE__*/__webpack_require__.n(_types__WEBPACK_IMPORTED_MODULE_10__);
/* harmony reexport (unknown) */ var __WEBPACK_REEXPORT_OBJECT__ = {};
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _types__WEBPACK_IMPORTED_MODULE_10__) if(["default","QueryClientProvider","useQueryClient","QueryErrorResetBoundary","useQueryErrorResetBoundary","useIsFetching","useIsMutating","useMutation","useQuery","useQueries","useInfiniteQuery"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) __WEBPACK_REEXPORT_OBJECT__[__WEBPACK_IMPORT_KEY__] = function(key) { return _types__WEBPACK_IMPORTED_MODULE_10__[key]; }.bind(0, __WEBPACK_IMPORT_KEY__)
/* harmony reexport (unknown) */ __webpack_require__.d(__webpack_exports__, __WEBPACK_REEXPORT_OBJECT__);
// Side effects









 // Types



/***/ }),

/***/ 1930:
/***/ (function(__unused_webpack_module, __unused_webpack___webpack_exports__, __webpack_require__) {

"use strict";

// EXTERNAL MODULE: ./node_modules/react-query/es/core/notifyManager.js
var notifyManager = __webpack_require__(101);
// EXTERNAL MODULE: ./node_modules/react-dom/index.js
var react_dom = __webpack_require__(3935);
;// CONCATENATED MODULE: ./node_modules/react-query/es/react/reactBatchedUpdates.js

var unstable_batchedUpdates = react_dom.unstable_batchedUpdates;
;// CONCATENATED MODULE: ./node_modules/react-query/es/react/setBatchUpdatesFn.js


notifyManager/* notifyManager.setBatchNotifyFunction */.V.setBatchNotifyFunction(unstable_batchedUpdates);

/***/ }),

/***/ 6490:
/***/ (function(__unused_webpack_module, __unused_webpack___webpack_exports__, __webpack_require__) {

"use strict";

// EXTERNAL MODULE: ./node_modules/react-query/es/core/logger.js
var logger = __webpack_require__(1909);
;// CONCATENATED MODULE: ./node_modules/react-query/es/react/logger.js
var logger_logger = console;
;// CONCATENATED MODULE: ./node_modules/react-query/es/react/setLogger.js



if (logger_logger) {
  (0,logger/* setLogger */.E)(logger_logger);
}

/***/ }),

/***/ 1251:
/***/ (function() {



/***/ }),

/***/ 5976:
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "r": function() { return /* binding */ useBaseQuery; }
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(7294);
/* harmony import */ var _core_notifyManager__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(101);
/* harmony import */ var _QueryErrorResetBoundary__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(2362);
/* harmony import */ var _QueryClientProvider__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(4921);




function useBaseQuery(options, Observer) {
  var mountedRef = react__WEBPACK_IMPORTED_MODULE_0__.useRef(false);

  var _React$useState = react__WEBPACK_IMPORTED_MODULE_0__.useState(0),
      forceUpdate = _React$useState[1];

  var queryClient = (0,_QueryClientProvider__WEBPACK_IMPORTED_MODULE_1__/* .useQueryClient */ .N)();
  var errorResetBoundary = (0,_QueryErrorResetBoundary__WEBPACK_IMPORTED_MODULE_2__/* .useQueryErrorResetBoundary */ ._)();
  var defaultedOptions = queryClient.defaultQueryObserverOptions(options); // Make sure results are optimistically set in fetching state before subscribing or updating options

  defaultedOptions.optimisticResults = true; // Include callbacks in batch renders

  if (defaultedOptions.onError) {
    defaultedOptions.onError = _core_notifyManager__WEBPACK_IMPORTED_MODULE_3__/* .notifyManager.batchCalls */ .V.batchCalls(defaultedOptions.onError);
  }

  if (defaultedOptions.onSuccess) {
    defaultedOptions.onSuccess = _core_notifyManager__WEBPACK_IMPORTED_MODULE_3__/* .notifyManager.batchCalls */ .V.batchCalls(defaultedOptions.onSuccess);
  }

  if (defaultedOptions.onSettled) {
    defaultedOptions.onSettled = _core_notifyManager__WEBPACK_IMPORTED_MODULE_3__/* .notifyManager.batchCalls */ .V.batchCalls(defaultedOptions.onSettled);
  }

  if (defaultedOptions.suspense) {
    // Always set stale time when using suspense to prevent
    // fetching again when directly mounting after suspending
    if (typeof defaultedOptions.staleTime !== 'number') {
      defaultedOptions.staleTime = 1000;
    }
  }

  if (defaultedOptions.suspense || defaultedOptions.useErrorBoundary) {
    // Prevent retrying failed query if the error boundary has not been reset yet
    if (!errorResetBoundary.isReset()) {
      defaultedOptions.retryOnMount = false;
    }
  }

  var _React$useState2 = react__WEBPACK_IMPORTED_MODULE_0__.useState(function () {
    return new Observer(queryClient, defaultedOptions);
  }),
      observer = _React$useState2[0];

  var result = observer.getOptimisticResult(defaultedOptions);
  react__WEBPACK_IMPORTED_MODULE_0__.useEffect(function () {
    mountedRef.current = true;
    errorResetBoundary.clearReset();
    var unsubscribe = observer.subscribe(_core_notifyManager__WEBPACK_IMPORTED_MODULE_3__/* .notifyManager.batchCalls */ .V.batchCalls(function () {
      if (mountedRef.current) {
        forceUpdate(function (x) {
          return x + 1;
        });
      }
    })); // Update result to make sure we did not miss any query updates
    // between creating the observer and subscribing to it.

    observer.updateResult();
    return function () {
      mountedRef.current = false;
      unsubscribe();
    };
  }, [errorResetBoundary, observer]);
  react__WEBPACK_IMPORTED_MODULE_0__.useEffect(function () {
    // Do not notify on updates because of changes in the options because
    // these changes should already be reflected in the optimistic result.
    observer.setOptions(defaultedOptions, {
      listeners: false
    });
  }, [defaultedOptions, observer]); // Handle suspense

  if (defaultedOptions.suspense && result.isLoading) {
    throw observer.fetchOptimistic(defaultedOptions).then(function (_ref) {
      var data = _ref.data;
      defaultedOptions.onSuccess == null ? void 0 : defaultedOptions.onSuccess(data);
      defaultedOptions.onSettled == null ? void 0 : defaultedOptions.onSettled(data, null);
    }).catch(function (error) {
      errorResetBoundary.clearReset();
      defaultedOptions.onError == null ? void 0 : defaultedOptions.onError(error);
      defaultedOptions.onSettled == null ? void 0 : defaultedOptions.onSettled(undefined, error);
    });
  } // Handle error boundary


  if ((defaultedOptions.suspense || defaultedOptions.useErrorBoundary) && result.isError && !result.isFetching) {
    throw result.error;
  } // Handle result property usage tracking


  if (defaultedOptions.notifyOnChangeProps === 'tracked') {
    result = observer.trackResult(result);
  }

  return result;
}

/***/ }),

/***/ 5275:
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "N": function() { return /* binding */ useInfiniteQuery; }
/* harmony export */ });
/* harmony import */ var _core_infiniteQueryObserver__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(8186);
/* harmony import */ var _core_utils__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(2288);
/* harmony import */ var _useBaseQuery__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(5976);


 // HOOK

function useInfiniteQuery(arg1, arg2, arg3) {
  var options = (0,_core_utils__WEBPACK_IMPORTED_MODULE_0__/* .parseQueryArgs */ ._v)(arg1, arg2, arg3);
  return (0,_useBaseQuery__WEBPACK_IMPORTED_MODULE_1__/* .useBaseQuery */ .r)(options, _core_infiniteQueryObserver__WEBPACK_IMPORTED_MODULE_2__/* .InfiniteQueryObserver */ .c);
}

/***/ }),

/***/ 934:
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "y": function() { return /* binding */ useIsFetching; }
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(7294);
/* harmony import */ var _core_notifyManager__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(101);
/* harmony import */ var _core_utils__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(2288);
/* harmony import */ var _QueryClientProvider__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(4921);




function useIsFetching(arg1, arg2) {
  var mountedRef = react__WEBPACK_IMPORTED_MODULE_0__.useRef(false);
  var queryClient = (0,_QueryClientProvider__WEBPACK_IMPORTED_MODULE_1__/* .useQueryClient */ .N)();

  var _parseFilterArgs = (0,_core_utils__WEBPACK_IMPORTED_MODULE_2__/* .parseFilterArgs */ .I6)(arg1, arg2),
      filters = _parseFilterArgs[0];

  var _React$useState = react__WEBPACK_IMPORTED_MODULE_0__.useState(queryClient.isFetching(filters)),
      isFetching = _React$useState[0],
      setIsFetching = _React$useState[1];

  var filtersRef = react__WEBPACK_IMPORTED_MODULE_0__.useRef(filters);
  filtersRef.current = filters;
  var isFetchingRef = react__WEBPACK_IMPORTED_MODULE_0__.useRef(isFetching);
  isFetchingRef.current = isFetching;
  react__WEBPACK_IMPORTED_MODULE_0__.useEffect(function () {
    mountedRef.current = true;
    var unsubscribe = queryClient.getQueryCache().subscribe(_core_notifyManager__WEBPACK_IMPORTED_MODULE_3__/* .notifyManager.batchCalls */ .V.batchCalls(function () {
      if (mountedRef.current) {
        var newIsFetching = queryClient.isFetching(filtersRef.current);

        if (isFetchingRef.current !== newIsFetching) {
          setIsFetching(newIsFetching);
        }
      }
    }));
    return function () {
      mountedRef.current = false;
      unsubscribe();
    };
  }, [queryClient]);
  return isFetching;
}

/***/ }),

/***/ 1607:
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "B": function() { return /* binding */ useIsMutating; }
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(7294);
/* harmony import */ var _core_notifyManager__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(101);
/* harmony import */ var _core_utils__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(2288);
/* harmony import */ var _QueryClientProvider__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(4921);




function useIsMutating(arg1, arg2) {
  var mountedRef = react__WEBPACK_IMPORTED_MODULE_0__.useRef(false);
  var filters = (0,_core_utils__WEBPACK_IMPORTED_MODULE_1__/* .parseMutationFilterArgs */ .cb)(arg1, arg2);
  var queryClient = (0,_QueryClientProvider__WEBPACK_IMPORTED_MODULE_2__/* .useQueryClient */ .N)();

  var _React$useState = react__WEBPACK_IMPORTED_MODULE_0__.useState(queryClient.isMutating(filters)),
      isMutating = _React$useState[0],
      setIsMutating = _React$useState[1];

  var filtersRef = react__WEBPACK_IMPORTED_MODULE_0__.useRef(filters);
  filtersRef.current = filters;
  var isMutatingRef = react__WEBPACK_IMPORTED_MODULE_0__.useRef(isMutating);
  isMutatingRef.current = isMutating;
  react__WEBPACK_IMPORTED_MODULE_0__.useEffect(function () {
    mountedRef.current = true;
    var unsubscribe = queryClient.getMutationCache().subscribe(_core_notifyManager__WEBPACK_IMPORTED_MODULE_3__/* .notifyManager.batchCalls */ .V.batchCalls(function () {
      if (mountedRef.current) {
        var newIsMutating = queryClient.isMutating(filtersRef.current);

        if (isMutatingRef.current !== newIsMutating) {
          setIsMutating(newIsMutating);
        }
      }
    }));
    return function () {
      mountedRef.current = false;
      unsubscribe();
    };
  }, [queryClient]);
  return isMutating;
}

/***/ }),

/***/ 4493:
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "D": function() { return /* binding */ useMutation; }
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(7462);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(7294);
/* harmony import */ var _core_notifyManager__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(101);
/* harmony import */ var _core_utils__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(2288);
/* harmony import */ var _core_mutationObserver__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(36);
/* harmony import */ var _QueryClientProvider__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(4921);






function useMutation(arg1, arg2, arg3) {
  var mountedRef = react__WEBPACK_IMPORTED_MODULE_0__.useRef(false);

  var _React$useState = react__WEBPACK_IMPORTED_MODULE_0__.useState(0),
      forceUpdate = _React$useState[1];

  var options = (0,_core_utils__WEBPACK_IMPORTED_MODULE_1__/* .parseMutationArgs */ .lV)(arg1, arg2, arg3);
  var queryClient = (0,_QueryClientProvider__WEBPACK_IMPORTED_MODULE_2__/* .useQueryClient */ .N)();
  var obsRef = react__WEBPACK_IMPORTED_MODULE_0__.useRef();

  if (!obsRef.current) {
    obsRef.current = new _core_mutationObserver__WEBPACK_IMPORTED_MODULE_3__/* .MutationObserver */ .X(queryClient, options);
  } else {
    obsRef.current.setOptions(options);
  }

  var currentResult = obsRef.current.getCurrentResult();
  react__WEBPACK_IMPORTED_MODULE_0__.useEffect(function () {
    mountedRef.current = true;
    var unsubscribe = obsRef.current.subscribe(_core_notifyManager__WEBPACK_IMPORTED_MODULE_4__/* .notifyManager.batchCalls */ .V.batchCalls(function () {
      if (mountedRef.current) {
        forceUpdate(function (x) {
          return x + 1;
        });
      }
    }));
    return function () {
      mountedRef.current = false;
      unsubscribe();
    };
  }, []);
  var mutate = react__WEBPACK_IMPORTED_MODULE_0__.useCallback(function (variables, mutateOptions) {
    obsRef.current.mutate(variables, mutateOptions).catch(_core_utils__WEBPACK_IMPORTED_MODULE_1__/* .noop */ .ZT);
  }, []);

  if (currentResult.error && obsRef.current.options.useErrorBoundary) {
    throw currentResult.error;
  }

  return (0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_5__/* .default */ .Z)({}, currentResult, {
    mutate: mutate,
    mutateAsync: currentResult.mutate
  });
}

/***/ }),

/***/ 8522:
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "h": function() { return /* binding */ useQueries; }
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(7294);
/* harmony import */ var _core_notifyManager__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(101);
/* harmony import */ var _core_queriesObserver__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(4119);
/* harmony import */ var _QueryClientProvider__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(4921);




function useQueries(queries) {
  var mountedRef = react__WEBPACK_IMPORTED_MODULE_0__.useRef(false);

  var _React$useState = react__WEBPACK_IMPORTED_MODULE_0__.useState(0),
      forceUpdate = _React$useState[1];

  var queryClient = (0,_QueryClientProvider__WEBPACK_IMPORTED_MODULE_1__/* .useQueryClient */ .N)();
  var defaultedQueries = queries.map(function (options) {
    var defaultedOptions = queryClient.defaultQueryObserverOptions(options); // Make sure the results are already in fetching state before subscribing or updating options

    defaultedOptions.optimisticResults = true;
    return defaultedOptions;
  });

  var _React$useState2 = react__WEBPACK_IMPORTED_MODULE_0__.useState(function () {
    return new _core_queriesObserver__WEBPACK_IMPORTED_MODULE_2__/* .QueriesObserver */ .y(queryClient, defaultedQueries);
  }),
      observer = _React$useState2[0];

  var result = observer.getOptimisticResult(defaultedQueries);
  react__WEBPACK_IMPORTED_MODULE_0__.useEffect(function () {
    mountedRef.current = true;
    var unsubscribe = observer.subscribe(_core_notifyManager__WEBPACK_IMPORTED_MODULE_3__/* .notifyManager.batchCalls */ .V.batchCalls(function () {
      if (mountedRef.current) {
        forceUpdate(function (x) {
          return x + 1;
        });
      }
    }));
    return function () {
      mountedRef.current = false;
      unsubscribe();
    };
  }, [observer]);
  react__WEBPACK_IMPORTED_MODULE_0__.useEffect(function () {
    // Do not notify on updates because of changes in the options because
    // these changes should already be reflected in the optimistic result.
    observer.setQueries(defaultedQueries, {
      listeners: false
    });
  }, [defaultedQueries, observer]);
  return result;
}

/***/ }),

/***/ 9693:
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "a": function() { return /* binding */ useQuery; }
/* harmony export */ });
/* harmony import */ var _core__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(4254);
/* harmony import */ var _core_utils__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(2288);
/* harmony import */ var _useBaseQuery__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(5976);


 // HOOK

function useQuery(arg1, arg2, arg3) {
  var parsedOptions = (0,_core_utils__WEBPACK_IMPORTED_MODULE_0__/* .parseQueryArgs */ ._v)(arg1, arg2, arg3);
  return (0,_useBaseQuery__WEBPACK_IMPORTED_MODULE_1__/* .useBaseQuery */ .r)(parsedOptions, _core__WEBPACK_IMPORTED_MODULE_2__/* .QueryObserver */ .z);
}

/***/ }),

/***/ 2408:
/***/ (function(__unused_webpack_module, exports, __webpack_require__) {

"use strict";
/** @license React v17.0.2
 * react.production.min.js
 *
 * Copyright (c) Facebook, Inc. and its affiliates.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */
var l=__webpack_require__(7418),n=60103,p=60106;exports.Fragment=60107;exports.StrictMode=60108;exports.Profiler=60114;var q=60109,r=60110,t=60112;exports.Suspense=60113;var u=60115,v=60116;
if("function"===typeof Symbol&&Symbol.for){var w=Symbol.for;n=w("react.element");p=w("react.portal");exports.Fragment=w("react.fragment");exports.StrictMode=w("react.strict_mode");exports.Profiler=w("react.profiler");q=w("react.provider");r=w("react.context");t=w("react.forward_ref");exports.Suspense=w("react.suspense");u=w("react.memo");v=w("react.lazy")}var x="function"===typeof Symbol&&Symbol.iterator;
function y(a){if(null===a||"object"!==typeof a)return null;a=x&&a[x]||a["@@iterator"];return"function"===typeof a?a:null}function z(a){for(var b="https://reactjs.org/docs/error-decoder.html?invariant="+a,c=1;c<arguments.length;c++)b+="&args[]="+encodeURIComponent(arguments[c]);return"Minified React error #"+a+"; visit "+b+" for the full message or use the non-minified dev environment for full errors and additional helpful warnings."}
var A={isMounted:function(){return!1},enqueueForceUpdate:function(){},enqueueReplaceState:function(){},enqueueSetState:function(){}},B={};function C(a,b,c){this.props=a;this.context=b;this.refs=B;this.updater=c||A}C.prototype.isReactComponent={};C.prototype.setState=function(a,b){if("object"!==typeof a&&"function"!==typeof a&&null!=a)throw Error(z(85));this.updater.enqueueSetState(this,a,b,"setState")};C.prototype.forceUpdate=function(a){this.updater.enqueueForceUpdate(this,a,"forceUpdate")};
function D(){}D.prototype=C.prototype;function E(a,b,c){this.props=a;this.context=b;this.refs=B;this.updater=c||A}var F=E.prototype=new D;F.constructor=E;l(F,C.prototype);F.isPureReactComponent=!0;var G={current:null},H=Object.prototype.hasOwnProperty,I={key:!0,ref:!0,__self:!0,__source:!0};
function J(a,b,c){var e,d={},k=null,h=null;if(null!=b)for(e in void 0!==b.ref&&(h=b.ref),void 0!==b.key&&(k=""+b.key),b)H.call(b,e)&&!I.hasOwnProperty(e)&&(d[e]=b[e]);var g=arguments.length-2;if(1===g)d.children=c;else if(1<g){for(var f=Array(g),m=0;m<g;m++)f[m]=arguments[m+2];d.children=f}if(a&&a.defaultProps)for(e in g=a.defaultProps,g)void 0===d[e]&&(d[e]=g[e]);return{$$typeof:n,type:a,key:k,ref:h,props:d,_owner:G.current}}
function K(a,b){return{$$typeof:n,type:a.type,key:b,ref:a.ref,props:a.props,_owner:a._owner}}function L(a){return"object"===typeof a&&null!==a&&a.$$typeof===n}function escape(a){var b={"=":"=0",":":"=2"};return"$"+a.replace(/[=:]/g,function(a){return b[a]})}var M=/\/+/g;function N(a,b){return"object"===typeof a&&null!==a&&null!=a.key?escape(""+a.key):b.toString(36)}
function O(a,b,c,e,d){var k=typeof a;if("undefined"===k||"boolean"===k)a=null;var h=!1;if(null===a)h=!0;else switch(k){case "string":case "number":h=!0;break;case "object":switch(a.$$typeof){case n:case p:h=!0}}if(h)return h=a,d=d(h),a=""===e?"."+N(h,0):e,Array.isArray(d)?(c="",null!=a&&(c=a.replace(M,"$&/")+"/"),O(d,b,c,"",function(a){return a})):null!=d&&(L(d)&&(d=K(d,c+(!d.key||h&&h.key===d.key?"":(""+d.key).replace(M,"$&/")+"/")+a)),b.push(d)),1;h=0;e=""===e?".":e+":";if(Array.isArray(a))for(var g=
0;g<a.length;g++){k=a[g];var f=e+N(k,g);h+=O(k,b,c,f,d)}else if(f=y(a),"function"===typeof f)for(a=f.call(a),g=0;!(k=a.next()).done;)k=k.value,f=e+N(k,g++),h+=O(k,b,c,f,d);else if("object"===k)throw b=""+a,Error(z(31,"[object Object]"===b?"object with keys {"+Object.keys(a).join(", ")+"}":b));return h}function P(a,b,c){if(null==a)return a;var e=[],d=0;O(a,e,"","",function(a){return b.call(c,a,d++)});return e}
function Q(a){if(-1===a._status){var b=a._result;b=b();a._status=0;a._result=b;b.then(function(b){0===a._status&&(b=b.default,a._status=1,a._result=b)},function(b){0===a._status&&(a._status=2,a._result=b)})}if(1===a._status)return a._result;throw a._result;}var R={current:null};function S(){var a=R.current;if(null===a)throw Error(z(321));return a}var T={ReactCurrentDispatcher:R,ReactCurrentBatchConfig:{transition:0},ReactCurrentOwner:G,IsSomeRendererActing:{current:!1},assign:l};
exports.Children={map:P,forEach:function(a,b,c){P(a,function(){b.apply(this,arguments)},c)},count:function(a){var b=0;P(a,function(){b++});return b},toArray:function(a){return P(a,function(a){return a})||[]},only:function(a){if(!L(a))throw Error(z(143));return a}};exports.Component=C;exports.PureComponent=E;exports.__SECRET_INTERNALS_DO_NOT_USE_OR_YOU_WILL_BE_FIRED=T;
exports.cloneElement=function(a,b,c){if(null===a||void 0===a)throw Error(z(267,a));var e=l({},a.props),d=a.key,k=a.ref,h=a._owner;if(null!=b){void 0!==b.ref&&(k=b.ref,h=G.current);void 0!==b.key&&(d=""+b.key);if(a.type&&a.type.defaultProps)var g=a.type.defaultProps;for(f in b)H.call(b,f)&&!I.hasOwnProperty(f)&&(e[f]=void 0===b[f]&&void 0!==g?g[f]:b[f])}var f=arguments.length-2;if(1===f)e.children=c;else if(1<f){g=Array(f);for(var m=0;m<f;m++)g[m]=arguments[m+2];e.children=g}return{$$typeof:n,type:a.type,
key:d,ref:k,props:e,_owner:h}};exports.createContext=function(a,b){void 0===b&&(b=null);a={$$typeof:r,_calculateChangedBits:b,_currentValue:a,_currentValue2:a,_threadCount:0,Provider:null,Consumer:null};a.Provider={$$typeof:q,_context:a};return a.Consumer=a};exports.createElement=J;exports.createFactory=function(a){var b=J.bind(null,a);b.type=a;return b};exports.createRef=function(){return{current:null}};exports.forwardRef=function(a){return{$$typeof:t,render:a}};exports.isValidElement=L;
exports.lazy=function(a){return{$$typeof:v,_payload:{_status:-1,_result:a},_init:Q}};exports.memo=function(a,b){return{$$typeof:u,type:a,compare:void 0===b?null:b}};exports.useCallback=function(a,b){return S().useCallback(a,b)};exports.useContext=function(a,b){return S().useContext(a,b)};exports.useDebugValue=function(){};exports.useEffect=function(a,b){return S().useEffect(a,b)};exports.useImperativeHandle=function(a,b,c){return S().useImperativeHandle(a,b,c)};
exports.useLayoutEffect=function(a,b){return S().useLayoutEffect(a,b)};exports.useMemo=function(a,b){return S().useMemo(a,b)};exports.useReducer=function(a,b,c){return S().useReducer(a,b,c)};exports.useRef=function(a){return S().useRef(a)};exports.useState=function(a){return S().useState(a)};exports.version="17.0.2";


/***/ }),

/***/ 7294:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

"use strict";


if (true) {
  module.exports = __webpack_require__(2408);
} else {}


/***/ }),

/***/ 53:
/***/ (function(__unused_webpack_module, exports) {

"use strict";
/** @license React v0.20.2
 * scheduler.production.min.js
 *
 * Copyright (c) Facebook, Inc. and its affiliates.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */
var f,g,h,k;if("object"===typeof performance&&"function"===typeof performance.now){var l=performance;exports.unstable_now=function(){return l.now()}}else{var p=Date,q=p.now();exports.unstable_now=function(){return p.now()-q}}
if("undefined"===typeof window||"function"!==typeof MessageChannel){var t=null,u=null,w=function(){if(null!==t)try{var a=exports.unstable_now();t(!0,a);t=null}catch(b){throw setTimeout(w,0),b;}};f=function(a){null!==t?setTimeout(f,0,a):(t=a,setTimeout(w,0))};g=function(a,b){u=setTimeout(a,b)};h=function(){clearTimeout(u)};exports.unstable_shouldYield=function(){return!1};k=exports.unstable_forceFrameRate=function(){}}else{var x=window.setTimeout,y=window.clearTimeout;if("undefined"!==typeof console){var z=
window.cancelAnimationFrame;"function"!==typeof window.requestAnimationFrame&&console.error("This browser doesn't support requestAnimationFrame. Make sure that you load a polyfill in older browsers. https://reactjs.org/link/react-polyfills");"function"!==typeof z&&console.error("This browser doesn't support cancelAnimationFrame. Make sure that you load a polyfill in older browsers. https://reactjs.org/link/react-polyfills")}var A=!1,B=null,C=-1,D=5,E=0;exports.unstable_shouldYield=function(){return exports.unstable_now()>=
E};k=function(){};exports.unstable_forceFrameRate=function(a){0>a||125<a?console.error("forceFrameRate takes a positive int between 0 and 125, forcing frame rates higher than 125 fps is not supported"):D=0<a?Math.floor(1E3/a):5};var F=new MessageChannel,G=F.port2;F.port1.onmessage=function(){if(null!==B){var a=exports.unstable_now();E=a+D;try{B(!0,a)?G.postMessage(null):(A=!1,B=null)}catch(b){throw G.postMessage(null),b;}}else A=!1};f=function(a){B=a;A||(A=!0,G.postMessage(null))};g=function(a,b){C=
x(function(){a(exports.unstable_now())},b)};h=function(){y(C);C=-1}}function H(a,b){var c=a.length;a.push(b);a:for(;;){var d=c-1>>>1,e=a[d];if(void 0!==e&&0<I(e,b))a[d]=b,a[c]=e,c=d;else break a}}function J(a){a=a[0];return void 0===a?null:a}
function K(a){var b=a[0];if(void 0!==b){var c=a.pop();if(c!==b){a[0]=c;a:for(var d=0,e=a.length;d<e;){var m=2*(d+1)-1,n=a[m],v=m+1,r=a[v];if(void 0!==n&&0>I(n,c))void 0!==r&&0>I(r,n)?(a[d]=r,a[v]=c,d=v):(a[d]=n,a[m]=c,d=m);else if(void 0!==r&&0>I(r,c))a[d]=r,a[v]=c,d=v;else break a}}return b}return null}function I(a,b){var c=a.sortIndex-b.sortIndex;return 0!==c?c:a.id-b.id}var L=[],M=[],N=1,O=null,P=3,Q=!1,R=!1,S=!1;
function T(a){for(var b=J(M);null!==b;){if(null===b.callback)K(M);else if(b.startTime<=a)K(M),b.sortIndex=b.expirationTime,H(L,b);else break;b=J(M)}}function U(a){S=!1;T(a);if(!R)if(null!==J(L))R=!0,f(V);else{var b=J(M);null!==b&&g(U,b.startTime-a)}}
function V(a,b){R=!1;S&&(S=!1,h());Q=!0;var c=P;try{T(b);for(O=J(L);null!==O&&(!(O.expirationTime>b)||a&&!exports.unstable_shouldYield());){var d=O.callback;if("function"===typeof d){O.callback=null;P=O.priorityLevel;var e=d(O.expirationTime<=b);b=exports.unstable_now();"function"===typeof e?O.callback=e:O===J(L)&&K(L);T(b)}else K(L);O=J(L)}if(null!==O)var m=!0;else{var n=J(M);null!==n&&g(U,n.startTime-b);m=!1}return m}finally{O=null,P=c,Q=!1}}var W=k;exports.unstable_IdlePriority=5;
exports.unstable_ImmediatePriority=1;exports.unstable_LowPriority=4;exports.unstable_NormalPriority=3;exports.unstable_Profiling=null;exports.unstable_UserBlockingPriority=2;exports.unstable_cancelCallback=function(a){a.callback=null};exports.unstable_continueExecution=function(){R||Q||(R=!0,f(V))};exports.unstable_getCurrentPriorityLevel=function(){return P};exports.unstable_getFirstCallbackNode=function(){return J(L)};
exports.unstable_next=function(a){switch(P){case 1:case 2:case 3:var b=3;break;default:b=P}var c=P;P=b;try{return a()}finally{P=c}};exports.unstable_pauseExecution=function(){};exports.unstable_requestPaint=W;exports.unstable_runWithPriority=function(a,b){switch(a){case 1:case 2:case 3:case 4:case 5:break;default:a=3}var c=P;P=a;try{return b()}finally{P=c}};
exports.unstable_scheduleCallback=function(a,b,c){var d=exports.unstable_now();"object"===typeof c&&null!==c?(c=c.delay,c="number"===typeof c&&0<c?d+c:d):c=d;switch(a){case 1:var e=-1;break;case 2:e=250;break;case 5:e=1073741823;break;case 4:e=1E4;break;default:e=5E3}e=c+e;a={id:N++,callback:b,priorityLevel:a,startTime:c,expirationTime:e,sortIndex:-1};c>d?(a.sortIndex=c,H(M,a),null===J(L)&&a===J(M)&&(S?h():S=!0,g(U,c-d))):(a.sortIndex=e,H(L,a),R||Q||(R=!0,f(V)));return a};
exports.unstable_wrapCallback=function(a){var b=P;return function(){var c=P;P=b;try{return a.apply(this,arguments)}finally{P=c}}};


/***/ }),

/***/ 3840:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

"use strict";


if (true) {
  module.exports = __webpack_require__(53);
} else {}


/***/ }),

/***/ 4634:
/***/ (function(__unused_webpack_module, exports, __webpack_require__) {

"use strict";

var __createBinding = (this && this.__createBinding) || (Object.create ? (function(o, m, k, k2) {
    if (k2 === undefined) k2 = k;
    Object.defineProperty(o, k2, { enumerable: true, get: function() { return m[k]; } });
}) : (function(o, m, k, k2) {
    if (k2 === undefined) k2 = k;
    o[k2] = m[k];
}));
var __setModuleDefault = (this && this.__setModuleDefault) || (Object.create ? (function(o, v) {
    Object.defineProperty(o, "default", { enumerable: true, value: v });
}) : function(o, v) {
    o["default"] = v;
});
var __importStar = (this && this.__importStar) || function (mod) {
    if (mod && mod.__esModule) return mod;
    var result = {};
    if (mod != null) for (var k in mod) if (k !== "default" && Object.prototype.hasOwnProperty.call(mod, k)) __createBinding(result, mod, k);
    __setModuleDefault(result, mod);
    return result;
};
Object.defineProperty(exports, "__esModule", ({ value: true }));
exports.AddonRequired = exports.IfAddonActivatedOrPromoEnabled = void 0;
var react_1 = __importStar(__webpack_require__(7294));
var contexts_1 = __webpack_require__(2471);
var hooks_1 = __webpack_require__(7909);
var IfAddonActivatedOrPromoEnabled = function (_a) {
    var children = _a.children;
    var _b = (0, react_1.useContext)(contexts_1.AddonContext), activated = _b.activated, enablepromo = _b.enablepromo;
    if (!activated && !enablepromo) {
        return null;
    }
    return react_1.default.createElement(react_1.default.Fragment, null, children);
};
exports.IfAddonActivatedOrPromoEnabled = IfAddonActivatedOrPromoEnabled;
var AddonRequired = function () {
    var promourl = (0, react_1.useContext)(contexts_1.AddonContext).promourl;
    var getStr = (0, hooks_1.useStrings)(["xpplusrequired", "unlockfeaturewithxpplus"]);
    var handleClick = function (e) { return e.preventDefault(); };
    return (react_1.default.createElement("a", { href: "#", role: "button", onClick: handleClick, "data-toggle": "popover", "data-placement": "top", "data-container": "body", "data-content": getStr("unlockfeaturewithxpplus", promourl), "data-html": "true", className: "xp-py-1 xp-px-1.5 xp-normal-case xp-text-2xs xp-inline-block xp-bg-black xp-text-white xp-rounded xp-no-underline" }, getStr("xpplusrequired")));
};
exports.AddonRequired = AddonRequired;


/***/ }),

/***/ 2224:
/***/ (function(__unused_webpack_module, exports, __webpack_require__) {

"use strict";

var __assign = (this && this.__assign) || function () {
    __assign = Object.assign || function(t) {
        for (var s, i = 1, n = arguments.length; i < n; i++) {
            s = arguments[i];
            for (var p in s) if (Object.prototype.hasOwnProperty.call(s, p))
                t[p] = s[p];
        }
        return t;
    };
    return __assign.apply(this, arguments);
};
var __createBinding = (this && this.__createBinding) || (Object.create ? (function(o, m, k, k2) {
    if (k2 === undefined) k2 = k;
    Object.defineProperty(o, k2, { enumerable: true, get: function() { return m[k]; } });
}) : (function(o, m, k, k2) {
    if (k2 === undefined) k2 = k;
    o[k2] = m[k];
}));
var __setModuleDefault = (this && this.__setModuleDefault) || (Object.create ? (function(o, v) {
    Object.defineProperty(o, "default", { enumerable: true, value: v });
}) : function(o, v) {
    o["default"] = v;
});
var __importStar = (this && this.__importStar) || function (mod) {
    if (mod && mod.__esModule) return mod;
    var result = {};
    if (mod != null) for (var k in mod) if (k !== "default" && Object.prototype.hasOwnProperty.call(mod, k)) __createBinding(result, mod, k);
    __setModuleDefault(result, mod);
    return result;
};
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", ({ value: true }));
exports.BulkEditPointsModal = exports.getDefaultBulkEditPointsState = void 0;
var react_1 = __importStar(__webpack_require__(7294));
var Modal_1 = __webpack_require__(1274);
var hooks_1 = __webpack_require__(7909);
var Str_1 = __importDefault(__webpack_require__(430));
var NumberInput_1 = __webpack_require__(4959);
var RadioGroup_1 = __webpack_require__(3828);
var constants_1 = __webpack_require__(9482);
function calculationMethodReducer(state, action) {
    switch (action.type) {
        case "setMethod":
            return __assign(__assign({}, state), { method: action.payload });
        case "setBase":
            return __assign(__assign({}, state), { base: Math.max(1, action.payload) });
        case "setIncrement":
            return __assign(__assign({}, state), { incr: Math.max(0, action.payload) });
        case "setCoef":
            return __assign(__assign({}, state), { coef: Math.min(5, Math.max(1, action.payload)) });
    }
    return state;
}
function getDefaultBulkEditPointsState(props) {
    return {
        method: props.method || "relative",
        base: Math.max(1, props.base || 120),
        incr: Math.max(0, props.incr || 40),
        coef: Math.min(5, Math.max(props.coef || 1.3)),
    };
}
exports.getDefaultBulkEditPointsState = getDefaultBulkEditPointsState;
var BulkEditPoints = function (_a) {
    var method = _a.method, base = _a.base, incr = _a.incr, coef = _a.coef, onBaseChange = _a.onBaseChange, onCoefChange = _a.onCoefChange, onIncrementChange = _a.onIncrementChange, onMethodChange = _a.onMethodChange;
    var getStr = (0, hooks_1.useStrings)([
        "basepoints",
        "basepointslineardesc",
        "basepointsrelativedesc",
        "difficulty",
        "difficultyflat",
        "difficultyflatdesc",
        "difficultylinear",
        "difficultylineardesc",
        "difficultylinearincrdesc",
        "difficultypointincrease",
        "difficultyrelative",
        "difficultyrelativedesc",
        "difficultyrelativeincrdesc",
        "documentation",
        "pointincrement",
        "pointincrementdesc",
        "pointsperlevel",
        "recommended",
    ], "block_xp");
    return (react_1.default.createElement("div", { className: "xp-space-y-4" },
        react_1.default.createElement("div", null,
            react_1.default.createElement("div", { className: "xp-mb-2 xp-flex xp-items-start xp-flex-wrap" },
                react_1.default.createElement("div", { className: "xp-grow xp-font-bold" }, getStr("difficulty")),
                react_1.default.createElement("div", { className: "xp-shrink-0" },
                    react_1.default.createElement("a", { href: constants_1.HELP_URL_LEVELS, target: "_blank", rel: "noopener" }, getStr("documentation")))),
            react_1.default.createElement(RadioGroup_1.RadioGroup, { onChange: onMethodChange, value: method, items: [
                    { value: "flat", label: getStr("difficultyflat"), desc: getStr("difficultyflatdesc") },
                    {
                        value: "linear",
                        label: getStr("difficultylinear"),
                        desc: getStr("difficultylineardesc"),
                    },
                    {
                        value: "relative",
                        label: (react_1.default.createElement(react_1.default.Fragment, null,
                            getStr("difficultyrelative"),
                            react_1.default.createElement("div", { className: "badge badge-info xp-ml-2" }, getStr("recommended")))),
                        desc: getStr("difficultyrelativedesc"),
                    },
                ] })),
        react_1.default.createElement("div", null,
            react_1.default.createElement("p", { className: "xp-font-bold xp-mb-2" },
                react_1.default.createElement(Str_1.default, { id: "settings", component: "core" })),
            method === "flat" ? (react_1.default.createElement(react_1.default.Fragment, null,
                react_1.default.createElement("div", { className: "" },
                    react_1.default.createElement("label", { htmlFor: "xp-calc-bp", className: "xp-m-0" },
                        react_1.default.createElement(Str_1.default, { id: "pointsperlevel" })),
                    react_1.default.createElement("div", null,
                        react_1.default.createElement(NumberInput_1.NumberInputWithButtons, { value: base, onChange: onBaseChange, min: 1, step: 10, inputProps: { id: "xp-calc-bp", className: "xp-w-24" } }))))) : null,
            method === "linear" ? (react_1.default.createElement(react_1.default.Fragment, null,
                react_1.default.createElement("div", { className: "xp-space-y-2" },
                    react_1.default.createElement("div", { className: "" },
                        react_1.default.createElement("label", { htmlFor: "xp-calc-bp", className: "xp-m-0" },
                            react_1.default.createElement(Str_1.default, { id: "basepoints" })),
                        react_1.default.createElement("div", null,
                            react_1.default.createElement(NumberInput_1.NumberInputWithButtons, { value: base, onChange: onBaseChange, min: 1, step: 10, inputProps: { id: "xp-calc-bp", className: "xp-w-24" } })),
                        react_1.default.createElement("p", { className: "xp-text-gray-500 xp-m-0 xp-mt-1" }, getStr("basepointslineardesc"))),
                    react_1.default.createElement("div", { className: "" },
                        react_1.default.createElement("label", { htmlFor: "xp-calc-pi", className: "xp-m-0" }, getStr("difficultypointincrease")),
                        react_1.default.createElement("div", null,
                            react_1.default.createElement(NumberInput_1.NumberInputWithButtons, { value: incr, onChange: onIncrementChange, min: 0, inputProps: { id: "xp-calc-pi", className: "xp-w-24" } })),
                        react_1.default.createElement("p", { className: "xp-text-gray-500 xp-m-0 xp-mt-1" }, getStr("difficultylinearincrdesc")))))) : null,
            method === "relative" ? (react_1.default.createElement(react_1.default.Fragment, null,
                react_1.default.createElement("div", { className: "xp-space-y-2" },
                    react_1.default.createElement("div", { className: "" },
                        react_1.default.createElement("label", { htmlFor: "xp-calc-bp", className: "xp-m-0" },
                            react_1.default.createElement(Str_1.default, { id: "basepoints" })),
                        react_1.default.createElement("div", null,
                            react_1.default.createElement(NumberInput_1.NumberInputWithButtons, { value: base, onChange: onBaseChange, min: 1, step: 10, inputProps: { id: "xp-calc-bp", className: "xp-w-24" } })),
                        react_1.default.createElement("p", { className: "xp-text-gray-500 xp-m-0 xp-mt-1" }, getStr("basepointsrelativedesc"))),
                    react_1.default.createElement("div", { className: "" },
                        react_1.default.createElement("label", { htmlFor: "xp-calc-pi", className: "xp-m-0" }, getStr("difficultypointincrease")),
                        react_1.default.createElement("div", null,
                            react_1.default.createElement(NumberInput_1.NumberInputWithButtons, { value: Math.floor(coef * 100 - 100), onChange: function (p) { return onCoefChange(1 + p / 100); }, min: 0, max: 400, inputProps: { id: "xp-calc-pi", className: "xp-w-24", maxLength: 3 }, suffix: "%" })),
                        react_1.default.createElement("p", { className: "xp-text-gray-500 xp-m-0 xp-mt-1" }, getStr("difficultyrelativeincrdesc")))))) : null)));
};
var BulkEditPointsModal = function (props) {
    var _a = (0, react_1.useReducer)(calculationMethodReducer, props, getDefaultBulkEditPointsState), state = _a[0], dispatch = _a[1];
    var getStr = (0, hooks_1.useStrings)(["quickeditpoints", "apply"], "block_xp");
    var setMethod = function (p) { return dispatch({ type: "setMethod", payload: p }); };
    var setIncrement = function (p) { return dispatch({ type: "setIncrement", payload: p }); };
    var setBase = function (p) { return dispatch({ type: "setBase", payload: p }); };
    var setCoef = function (p) { return dispatch({ type: "setCoef", payload: p }); };
    var handleClose = function () {
        dispatch({ type: "reset", payload: getDefaultBulkEditPointsState(props) });
        props.onClose && props.onClose();
    };
    var handleSave = function () {
        props.onSave && props.onSave(state);
    };
    return (react_1.default.createElement(Modal_1.SaveCancelModal, { show: props.show, onClose: handleClose, onSave: handleSave, title: getStr("quickeditpoints"), saveButtonText: getStr("apply") },
        react_1.default.createElement(BulkEditPoints, { coef: state.coef, base: state.base, incr: state.incr, method: state.method, onBaseChange: setBase, onCoefChange: setCoef, onIncrementChange: setIncrement, onMethodChange: setMethod })));
};
exports.BulkEditPointsModal = BulkEditPointsModal;


/***/ }),

/***/ 6549:
/***/ (function(__unused_webpack_module, exports, __webpack_require__) {

"use strict";

var __assign = (this && this.__assign) || function () {
    __assign = Object.assign || function(t) {
        for (var s, i = 1, n = arguments.length; i < n; i++) {
            s = arguments[i];
            for (var p in s) if (Object.prototype.hasOwnProperty.call(s, p))
                t[p] = s[p];
        }
        return t;
    };
    return __assign.apply(this, arguments);
};
var __rest = (this && this.__rest) || function (s, e) {
    var t = {};
    for (var p in s) if (Object.prototype.hasOwnProperty.call(s, p) && e.indexOf(p) < 0)
        t[p] = s[p];
    if (s != null && typeof Object.getOwnPropertySymbols === "function")
        for (var i = 0, p = Object.getOwnPropertySymbols(s); i < p.length; i++) {
            if (e.indexOf(p[i]) < 0 && Object.prototype.propertyIsEnumerable.call(s, p[i]))
                t[p[i]] = s[p[i]];
        }
    return t;
};
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", ({ value: true }));
exports.AnchorButton = exports.SaveButton = exports.Button = void 0;
var react_1 = __importDefault(__webpack_require__(7294));
var hooks_1 = __webpack_require__(7909);
var Pix_1 = __importDefault(__webpack_require__(9803));
var Spinner_1 = __importDefault(__webpack_require__(5811));
var Str_1 = __importDefault(__webpack_require__(430));
var utils_1 = __webpack_require__(6926);
var Button = function (_a) {
    var onClick = _a.onClick, disabled = _a.disabled, children = _a.children, primary = _a.primary, className = _a.className, _b = _a.type, type = _b === void 0 ? "button" : _b;
    var classes = (0, utils_1.classNames)("btn", primary ? "btn-primary" : "btn-default btn-secondary", className);
    return (react_1.default.createElement("button", { className: classes, onClick: onClick, disabled: disabled, type: type }, children));
};
exports.Button = Button;
var SaveButton = function (_a) {
    var onClick = _a.onClick, disabled = _a.disabled, label = _a.label, _b = _a.mutation, mutation = _b === void 0 ? {} : _b, _c = _a.statePosition, statePosition = _c === void 0 ? "after" : _c;
    var getStr = (0, hooks_1.useStrings)(["changessaved", "error"], "core");
    var isLoading = mutation.isLoading, isSuccess = mutation.isSuccess, isError = mutation.isError;
    var isStateBefore = statePosition === "before";
    var state = (react_1.default.createElement("div", { className: "xp-w-8 xp-flex " + (isStateBefore ? "xp-mr-4 xp-justify-end" : "xp-ml-4"), "aria-live": "assertive" },
        isLoading ? react_1.default.createElement(Spinner_1.default, null) : null,
        isSuccess ? react_1.default.createElement(Pix_1.default, { id: "i/valid", component: "core", alt: getStr("changessaved") }) : null,
        isError ? react_1.default.createElement(Pix_1.default, { id: "i/invalid", component: "core", alt: getStr("error") }) : null));
    return (react_1.default.createElement("div", { className: "xp-flex xp-items-center" },
        isStateBefore ? state : null,
        react_1.default.createElement("div", { className: "" },
            react_1.default.createElement(exports.Button, { primary: true, onClick: onClick, disabled: disabled || isLoading }, label || react_1.default.createElement(Str_1.default, { id: "savechanges", component: "core" }))),
        !isStateBefore ? state : null));
};
exports.SaveButton = SaveButton;
var AnchorButton = function (_a) {
    var children = _a.children, onClick = _a.onClick, className = _a.className, props = __rest(_a, ["children", "onClick", "className"]);
    var anchorButtonProps = (0, hooks_1.useAnchorButtonProps)(onClick);
    return (react_1.default.createElement("a", __assign({ className: (0, utils_1.classNames)("xp-text-inherit xp-no-underline", className) }, props, anchorButtonProps), children));
};
exports.AnchorButton = AnchorButton;


/***/ }),

/***/ 1707:
/***/ (function(__unused_webpack_module, exports, __webpack_require__) {

"use strict";

var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", ({ value: true }));
var react_1 = __importDefault(__webpack_require__(7294));
var react_animate_height_1 = __importDefault(__webpack_require__(5085));
function Expandable(_a) {
    var expanded = _a.expanded, children = _a.children, id = _a.id;
    return (react_1.default.createElement(react_animate_height_1.default, { id: id, height: expanded ? "auto" : 0, applyInlineTransitions: false, animationStateClasses: {
            animating: "xp-transition-height xp-duration-500",
            static: "xp-transition-height xp-duration-500",
            animatingUp: "",
            animatingDown: "",
            animatingToHeightZero: "",
            animatingToHeightAuto: "",
            animatingToHeightSpecific: "",
            staticHeightZero: "",
            staticHeightAuto: "",
            staticHeightSpecific: "",
        } }, children));
}
exports.default = Expandable;


/***/ }),

/***/ 3521:
/***/ (function(__unused_webpack_module, exports, __webpack_require__) {

"use strict";

var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", ({ value: true }));
exports.PaperAirplaneIconSolid = exports.LanguageIcon = exports.CheckBadgeIconSolid = exports.Bars3BottomLeftIcon = void 0;
var react_1 = __importDefault(__webpack_require__(7294));
var Bars3BottomLeftIcon = function (_a) {
    var className = _a.className;
    return (react_1.default.createElement("svg", { xmlns: "http://www.w3.org/2000/svg", viewBox: "0 0 24 24", fill: "currentColor", className: className },
        react_1.default.createElement("path", { fillRule: "evenodd", d: "M3 6.75A.75.75 0 013.75 6h16.5a.75.75 0 010 1.5H3.75A.75.75 0 013 6.75zM3 12a.75.75 0 01.75-.75h16.5a.75.75 0 010 1.5H3.75A.75.75 0 013 12zm0 5.25a.75.75 0 01.75-.75H12a.75.75 0 010 1.5H3.75a.75.75 0 01-.75-.75z", clipRule: "evenodd" })));
};
exports.Bars3BottomLeftIcon = Bars3BottomLeftIcon;
var CheckBadgeIconSolid = function (_a) {
    var className = _a.className;
    return (react_1.default.createElement("svg", { xmlns: "http://www.w3.org/2000/svg", viewBox: "0 0 24 24", fill: "currentColor", className: className },
        react_1.default.createElement("path", { fillRule: "evenodd", d: "M8.603 3.799A4.49 4.49 0 0112 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 013.498 1.307 4.491 4.491 0 011.307 3.497A4.49 4.49 0 0121.75 12a4.49 4.49 0 01-1.549 3.397 4.491 4.491 0 01-1.307 3.497 4.491 4.491 0 01-3.497 1.307A4.49 4.49 0 0112 21.75a4.49 4.49 0 01-3.397-1.549 4.49 4.49 0 01-3.498-1.306 4.491 4.491 0 01-1.307-3.498A4.49 4.49 0 012.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 011.307-3.497 4.49 4.49 0 013.497-1.307zm7.007 6.387a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z", clipRule: "evenodd" })));
};
exports.CheckBadgeIconSolid = CheckBadgeIconSolid;
var LanguageIcon = function (_a) {
    var className = _a.className;
    return (react_1.default.createElement("svg", { xmlns: "http://www.w3.org/2000/svg", viewBox: "0 0 24 24", fill: "currentColor", className: className },
        react_1.default.createElement("path", { fillRule: "evenodd", d: "M9 2.25a.75.75 0 01.75.75v1.506a49.38 49.38 0 015.343.371.75.75 0 11-.186 1.489c-.66-.083-1.323-.151-1.99-.206a18.67 18.67 0 01-2.969 6.323c.317.384.65.753.998 1.107a.75.75 0 11-1.07 1.052A18.902 18.902 0 019 13.687a18.823 18.823 0 01-5.656 4.482.75.75 0 11-.688-1.333 17.323 17.323 0 005.396-4.353A18.72 18.72 0 015.89 8.598a.75.75 0 011.388-.568A17.21 17.21 0 009 11.224a17.17 17.17 0 002.391-5.165 48.038 48.038 0 00-8.298.307.75.75 0 01-.186-1.489 49.159 49.159 0 015.343-.371V3A.75.75 0 019 2.25zM15.75 9a.75.75 0 01.68.433l5.25 11.25a.75.75 0 01-1.36.634l-1.198-2.567h-6.744l-1.198 2.567a.75.75 0 01-1.36-.634l5.25-11.25A.75.75 0 0115.75 9zm-2.672 8.25h5.344l-2.672-5.726-2.672 5.726z", clipRule: "evenodd" })));
};
exports.LanguageIcon = LanguageIcon;
var PaperAirplaneIconSolid = function (_a) {
    var className = _a.className;
    return (react_1.default.createElement("svg", { xmlns: "http://www.w3.org/2000/svg", viewBox: "0 0 24 24", fill: "currentColor", className: className },
        react_1.default.createElement("path", { d: "M3.478 2.405a.75.75 0 00-.926.94l2.432 7.905H13.5a.75.75 0 010 1.5H4.984l-2.432 7.905a.75.75 0 00.926.94 60.519 60.519 0 0018.445-8.986.75.75 0 000-1.218A60.517 60.517 0 003.478 2.405z" })));
};
exports.PaperAirplaneIconSolid = PaperAirplaneIconSolid;


/***/ }),

/***/ 6935:
/***/ (function(__unused_webpack_module, exports, __webpack_require__) {

"use strict";

var __assign = (this && this.__assign) || function () {
    __assign = Object.assign || function(t) {
        for (var s, i = 1, n = arguments.length; i < n; i++) {
            s = arguments[i];
            for (var p in s) if (Object.prototype.hasOwnProperty.call(s, p))
                t[p] = s[p];
        }
        return t;
    };
    return __assign.apply(this, arguments);
};
var __rest = (this && this.__rest) || function (s, e) {
    var t = {};
    for (var p in s) if (Object.prototype.hasOwnProperty.call(s, p) && e.indexOf(p) < 0)
        t[p] = s[p];
    if (s != null && typeof Object.getOwnPropertySymbols === "function")
        for (var i = 0, p = Object.getOwnPropertySymbols(s); i < p.length; i++) {
            if (e.indexOf(p[i]) < 0 && Object.prototype.propertyIsEnumerable.call(s, p[i]))
                t[p[i]] = s[p[i]];
        }
    return t;
};
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", ({ value: true }));
exports.Textarea = exports.Select = void 0;
var react_1 = __importDefault(__webpack_require__(7294));
var Input = function (_a) {
    var _b = _a.className, className = _b === void 0 ? '' : _b, props = __rest(_a, ["className"]);
    /** Apply those classes for normalised styling across themes and versions. */
    return react_1.default.createElement("input", __assign({}, props, { className: "xp-m-0 form-control " + className }));
};
var Select = function (_a) {
    var _b = _a.className, className = _b === void 0 ? '' : _b, props = __rest(_a, ["className"]);
    /** Apply those classes for normalised styling across themes and versions. */
    return react_1.default.createElement("select", __assign({}, props, { className: "xp-m-0 xp-max-w-auto form-control " + className }));
};
exports.Select = Select;
var Textarea = function (_a) {
    var _b = _a.className, className = _b === void 0 ? '' : _b, props = __rest(_a, ["className"]);
    /** Apply those classes for normalised styling across themes and versions. */
    return react_1.default.createElement("textarea", __assign({}, props, { className: "xp-m-0 form-control " + className }));
};
exports.Textarea = Textarea;
exports.default = Input;


/***/ }),

/***/ 633:
/***/ (function(__unused_webpack_module, exports, __webpack_require__) {

"use strict";

var __createBinding = (this && this.__createBinding) || (Object.create ? (function(o, m, k, k2) {
    if (k2 === undefined) k2 = k;
    Object.defineProperty(o, k2, { enumerable: true, get: function() { return m[k]; } });
}) : (function(o, m, k, k2) {
    if (k2 === undefined) k2 = k;
    o[k2] = m[k];
}));
var __setModuleDefault = (this && this.__setModuleDefault) || (Object.create ? (function(o, v) {
    Object.defineProperty(o, "default", { enumerable: true, value: v });
}) : function(o, v) {
    o["default"] = v;
});
var __importStar = (this && this.__importStar) || function (mod) {
    if (mod && mod.__esModule) return mod;
    var result = {};
    if (mod != null) for (var k in mod) if (k !== "default" && Object.prototype.hasOwnProperty.call(mod, k)) __createBinding(result, mod, k);
    __setModuleDefault(result, mod);
    return result;
};
Object.defineProperty(exports, "__esModule", ({ value: true }));
var react_1 = __importStar(__webpack_require__(7294));
var hooks_1 = __webpack_require__(7909);
var Level = (0, react_1.forwardRef)(function (_a, ref) {
    var level = _a.level, small = _a.small, medium = _a.medium;
    var label = (0, hooks_1.useString)("levelx", "block_xp", level.level);
    var classes = "block_xp-level level-" + level.level + (small ? " small" : medium ? " medium" : "");
    if (level.badgeurl) {
        return (react_1.default.createElement("div", { className: classes + " level-badge", "aria-label": label, ref: ref },
            react_1.default.createElement("img", { src: level.badgeurl, alt: label })));
    }
    return (react_1.default.createElement("div", { className: classes, "aria-label": label, ref: ref }, level.level));
});
exports.default = Level;


/***/ }),

/***/ 1274:
/***/ (function(__unused_webpack_module, exports, __webpack_require__) {

"use strict";

var __createBinding = (this && this.__createBinding) || (Object.create ? (function(o, m, k, k2) {
    if (k2 === undefined) k2 = k;
    Object.defineProperty(o, k2, { enumerable: true, get: function() { return m[k]; } });
}) : (function(o, m, k, k2) {
    if (k2 === undefined) k2 = k;
    o[k2] = m[k];
}));
var __setModuleDefault = (this && this.__setModuleDefault) || (Object.create ? (function(o, v) {
    Object.defineProperty(o, "default", { enumerable: true, value: v });
}) : function(o, v) {
    o["default"] = v;
});
var __importStar = (this && this.__importStar) || function (mod) {
    if (mod && mod.__esModule) return mod;
    var result = {};
    if (mod != null) for (var k in mod) if (k !== "default" && Object.prototype.hasOwnProperty.call(mod, k)) __createBinding(result, mod, k);
    __setModuleDefault(result, mod);
    return result;
};
Object.defineProperty(exports, "__esModule", ({ value: true }));
exports.SaveCancelModal = void 0;
var react_1 = __webpack_require__(7294);
var ReactDOM = __importStar(__webpack_require__(3935));
var moodle_1 = __webpack_require__(3422);
var SaveCancelModal = function (_a) {
    var children = _a.children, onClose = _a.onClose, onSave = _a.onSave, show = _a.show, title = _a.title, saveButtonText = _a.saveButtonText;
    var modalPromise = (0, react_1.useRef)();
    var modalRef = (0, react_1.useRef)();
    var _b = (0, react_1.useState)(false), ready = _b[0], setReady = _b[1];
    var setSaveButtonText = function (text) {
        if (!modalRef.current || !text)
            return;
        var saveBtn = modalRef.current.getFooter()[0].querySelector('[data-action="save"]');
        if (!saveBtn)
            return;
        saveBtn.textContent = saveButtonText;
    };
    // Create the modal object.
    (0, react_1.useEffect)(function () {
        var cancelled = false;
        if (modalRef.current)
            return;
        if (!modalPromise.current) {
            var ModalFactory = (0, moodle_1.getModule)("core/modal_factory");
            modalPromise.current = ModalFactory.create({
                type: ModalFactory.types.SAVE_CANCEL,
                title: title,
                body: "<div class='block_xp'></div>",
            });
        }
        modalPromise.current.then(function (modal) {
            if (cancelled)
                return;
            modalRef.current = modal;
            if (saveButtonText) {
                setSaveButtonText(saveButtonText);
            }
            setReady(true); // State update to force re-render.
            if (show) {
                modal.show();
            }
        });
        return function () {
            cancelled = true;
        };
    });
    // Attach event listeners.
    (0, react_1.useEffect)(function () {
        var modal = modalRef.current;
        if (!modal)
            return;
        var ModalEvents = (0, moodle_1.getModule)("core/modal_events");
        var root = modal.getRoot();
        var handleSave = function () {
            onSave && onSave();
        };
        var handleClose = function () {
            onClose && onClose();
        };
        root.on(ModalEvents.save, handleSave);
        root.on(ModalEvents.hidden, handleClose);
        return function () {
            root.off(ModalEvents.save, handleSave);
            root.off(ModalEvents.hidden, handleClose);
        };
    });
    // Update visibility.
    (0, react_1.useEffect)(function () {
        if (!modalRef.current)
            return;
        if (show) {
            modalRef.current.show();
        }
        else {
            modalRef.current.hide();
        }
    }, [show, modalRef.current]);
    // Update title.
    (0, react_1.useEffect)(function () {
        if (!modalRef.current || !title)
            return;
        modalRef.current.setTitle(title);
    }, [title, modalRef.current]);
    // Update save button text.
    (0, react_1.useEffect)(function () {
        setSaveButtonText(saveButtonText);
    }, [saveButtonText, modalRef.current]);
    return modalRef.current ? ReactDOM.createPortal(children, modalRef.current.getBody()[0].querySelector(".block_xp")) : null;
};
exports.SaveCancelModal = SaveCancelModal;


/***/ }),

/***/ 4959:
/***/ (function(__unused_webpack_module, exports, __webpack_require__) {

"use strict";

var __assign = (this && this.__assign) || function () {
    __assign = Object.assign || function(t) {
        for (var s, i = 1, n = arguments.length; i < n; i++) {
            s = arguments[i];
            for (var p in s) if (Object.prototype.hasOwnProperty.call(s, p))
                t[p] = s[p];
        }
        return t;
    };
    return __assign.apply(this, arguments);
};
var __rest = (this && this.__rest) || function (s, e) {
    var t = {};
    for (var p in s) if (Object.prototype.hasOwnProperty.call(s, p) && e.indexOf(p) < 0)
        t[p] = s[p];
    if (s != null && typeof Object.getOwnPropertySymbols === "function")
        for (var i = 0, p = Object.getOwnPropertySymbols(s); i < p.length; i++) {
            if (e.indexOf(p[i]) < 0 && Object.prototype.propertyIsEnumerable.call(s, p[i]))
                t[p[i]] = s[p[i]];
        }
    return t;
};
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", ({ value: true }));
exports.NumberInputWithButtons = exports.PlainNumberInput = exports.NumInput = void 0;
var react_1 = __importDefault(__webpack_require__(7294));
var hooks_1 = __webpack_require__(7909);
var utils_1 = __webpack_require__(6926);
var Input_1 = __importDefault(__webpack_require__(6935));
var NumInput = function (_a) {
    var className = _a.className, value = _a.value, onChange = _a.onChange, props = __rest(_a, ["className", "value", "onChange"]);
    var inputProps = (0, hooks_1.useNumericInputProps)(value, onChange);
    return react_1.default.createElement(Input_1.default, __assign({ type: "text" }, inputProps, { className: className }, props));
};
exports.NumInput = NumInput;
var PlainNumberInput = function (_a) {
    var value = _a.value, onChange = _a.onChange, props = __rest(_a, ["value", "onChange"]);
    var inputProps = (0, hooks_1.useNumericInputProps)(value, onChange);
    return react_1.default.createElement("input", __assign({ type: "text" }, inputProps, props));
};
exports.PlainNumberInput = PlainNumberInput;
var NumberInputWithButtons = function (_a) {
    var onChange = _a.onChange, value = _a.value, min = _a.min, max = _a.max, suffix = _a.suffix, _b = _a.step, step = _b === void 0 ? 1 : _b, inputProps = _a.inputProps;
    var hasMin = typeof min !== "undefined";
    var hasMax = typeof max !== "undefined";
    var minDisabled = hasMin && min >= value;
    var maxDisabled = hasMax && max <= value;
    var minusProps = (0, hooks_1.useAnchorButtonProps)(function () {
        if (minDisabled)
            return;
        handleChange(value - step);
    });
    var plusProps = (0, hooks_1.useAnchorButtonProps)(function () {
        if (maxDisabled)
            return;
        handleChange(value + step);
    });
    var handleChange = function (n) {
        var final = n;
        if (hasMin) {
            final = Math.max(min, final);
        }
        if (hasMax) {
            final = Math.min(max, final);
        }
        onChange(final);
    };
    var _c = inputProps !== null && inputProps !== void 0 ? inputProps : {}, inputClassName = _c.className, remainingInputProps = __rest(_c, ["className"]);
    var allInputProps = __assign({ className: (0, utils_1.classNames)("xp-h-auto xp-border-0 xp-text-center xp-rounded-none focus:xp-z-10", suffix ? "xp-pr-6" : null, inputClassName || "xp-w-16") }, remainingInputProps);
    return (react_1.default.createElement("div", { className: "xp-inline-flex xp-rounded xp-border xp-border-solid xp-border-gray-300" },
        react_1.default.createElement("a", __assign({}, minusProps, { className: (0, utils_1.classNames)("xp-flex-0 xp-border-0 xp-border-gray-300 xp-border-solid xp-border-r xp-rounded-l xp-py-0.5 xp-px-1 xp-flex xp-items-center xp-justify-center", "focus:xp-z-10", minDisabled ? "xp-bg-gray-100 xp-cursor-pointer xp-text-gray-500" : "xp-bg-white xp-text-inherit") }),
            react_1.default.createElement("svg", { xmlns: "http://www.w3.org/2000/svg", viewBox: "0 0 20 20", fill: "currentColor", className: "xp-w-5 xp-h-5" },
                react_1.default.createElement("path", { fillRule: "evenodd", d: "M4 10a.75.75 0 01.75-.75h10.5a.75.75 0 010 1.5H4.75A.75.75 0 014 10z", clipRule: "evenodd" }))),
        react_1.default.createElement("div", { className: "xp-flex-1 xp-relative" },
            react_1.default.createElement(exports.NumInput, __assign({ onChange: handleChange, value: value }, allInputProps)),
            suffix ? (react_1.default.createElement("div", { className: "xp-pointer-events-none xp-absolute xp-inset-y-0 xp-right-0 xp-flex xp-items-center xp-pr-2" },
                react_1.default.createElement("span", { className: "xp-text-gray-500" }, suffix))) : null),
        react_1.default.createElement("a", __assign({}, plusProps, { className: (0, utils_1.classNames)("xp-flex-0 xp-border-0 xp-border-gray-300 xp-border-solid xp-border-l xp-rounded-r xp-py-0.5 xp-px-1 xp-flex xp-items-center xp-justify-center", "focus:xp-z-10", maxDisabled ? "xp-bg-gray-100 xp-cursor-pointer xp-text-gray-500" : "xp-bg-white xp-text-inherit") }),
            react_1.default.createElement("svg", { xmlns: "http://www.w3.org/2000/svg", viewBox: "0 0 20 20", fill: "currentColor", className: "xp-w-5 xp-h-5" },
                react_1.default.createElement("path", { d: "M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" })))));
};
exports.NumberInputWithButtons = NumberInputWithButtons;


/***/ }),

/***/ 9803:
/***/ (function(__unused_webpack_module, exports, __webpack_require__) {

"use strict";

var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", ({ value: true }));
var react_1 = __importDefault(__webpack_require__(7294));
var moodle_1 = __webpack_require__(3422);
var Pix = function (_a) {
    var id = _a.id, _b = _a.component, component = _b === void 0 ? 'block_xp' : _b, className = _a.className, _c = _a.alt, alt = _c === void 0 ? '' : _c;
    return react_1.default.createElement("img", { src: (0, moodle_1.imageUrl)(id, component), alt: alt, className: className });
};
exports.default = Pix;


/***/ }),

/***/ 3828:
/***/ (function(__unused_webpack_module, exports, __webpack_require__) {

"use strict";

var __createBinding = (this && this.__createBinding) || (Object.create ? (function(o, m, k, k2) {
    if (k2 === undefined) k2 = k;
    Object.defineProperty(o, k2, { enumerable: true, get: function() { return m[k]; } });
}) : (function(o, m, k, k2) {
    if (k2 === undefined) k2 = k;
    o[k2] = m[k];
}));
var __setModuleDefault = (this && this.__setModuleDefault) || (Object.create ? (function(o, v) {
    Object.defineProperty(o, "default", { enumerable: true, value: v });
}) : function(o, v) {
    o["default"] = v;
});
var __importStar = (this && this.__importStar) || function (mod) {
    if (mod && mod.__esModule) return mod;
    var result = {};
    if (mod != null) for (var k in mod) if (k !== "default" && Object.prototype.hasOwnProperty.call(mod, k)) __createBinding(result, mod, k);
    __setModuleDefault(result, mod);
    return result;
};
Object.defineProperty(exports, "__esModule", ({ value: true }));
exports.RadioGroup = void 0;
var react_1 = __importStar(__webpack_require__(7294));
var RadioGroup = function (_a) {
    var items = _a.items, value = _a.value, onChange = _a.onChange;
    var uniqid = (0, react_1.useState)(function () { return Math.random().toString(12).slice(2); })[0];
    return (react_1.default.createElement("div", { className: "xp-space-y-2" }, items.map(function (item, idx) { return (react_1.default.createElement("label", { className: "xp-relative xp-flex xp-items-start xp-cursor-pointer xp-m-0 xp-font-normal", key: item.value },
        react_1.default.createElement("div", { className: "xp-h-6 xp-flex xp-items-center" },
            react_1.default.createElement("input", { type: "radio", "aria-describedby": "xp-radiogroup-" + uniqid + "-" + idx, checked: value === item.value, onChange: function () { return onChange(item.value); } })),
        react_1.default.createElement("div", { className: "xp-ml-3" },
            react_1.default.createElement("div", { className: "xp-font-medium" }, item.label),
            item.desc ? (react_1.default.createElement("p", { id: "xp-radiogroup-" + uniqid + "-" + idx, className: "xp-text-gray-500 xp-m-0" }, item.desc)) : null))); })));
};
exports.RadioGroup = RadioGroup;


/***/ }),

/***/ 5811:
/***/ (function(__unused_webpack_module, exports, __webpack_require__) {

"use strict";

var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", ({ value: true }));
var react_1 = __importDefault(__webpack_require__(7294));
var hooks_1 = __webpack_require__(7909);
var Pix_1 = __importDefault(__webpack_require__(9803));
var Spinner = function (_a) {
    var className = _a.className;
    var alt = (0, hooks_1.useString)('loadinghelp', 'core');
    return react_1.default.createElement(Pix_1.default, { id: "y/loading", component: "core", className: className, alt: alt });
};
exports.default = Spinner;


/***/ }),

/***/ 430:
/***/ (function(__unused_webpack_module, exports, __webpack_require__) {

"use strict";

var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", ({ value: true }));
var react_1 = __importDefault(__webpack_require__(7294));
var hooks_1 = __webpack_require__(7909);
var Str = function (_a) {
    var id = _a.id, _b = _a.component, component = _b === void 0 ? "block_xp" : _b, a = _a.a;
    var str = (0, hooks_1.useString)(id, component, a);
    return react_1.default.createElement(react_1.default.Fragment, null, str || "​");
};
exports.default = Str;


/***/ }),

/***/ 7992:
/***/ (function(__unused_webpack_module, exports, __webpack_require__) {

"use strict";

var __createBinding = (this && this.__createBinding) || (Object.create ? (function(o, m, k, k2) {
    if (k2 === undefined) k2 = k;
    Object.defineProperty(o, k2, { enumerable: true, get: function() { return m[k]; } });
}) : (function(o, m, k, k2) {
    if (k2 === undefined) k2 = k;
    o[k2] = m[k];
}));
var __setModuleDefault = (this && this.__setModuleDefault) || (Object.create ? (function(o, v) {
    Object.defineProperty(o, "default", { enumerable: true, value: v });
}) : function(o, v) {
    o["default"] = v;
});
var __importStar = (this && this.__importStar) || function (mod) {
    if (mod && mod.__esModule) return mod;
    var result = {};
    if (mod != null) for (var k in mod) if (k !== "default" && Object.prototype.hasOwnProperty.call(mod, k)) __createBinding(result, mod, k);
    __setModuleDefault(result, mod);
    return result;
};
Object.defineProperty(exports, "__esModule", ({ value: true }));
exports.Tooltip = void 0;
var react_1 = __importStar(__webpack_require__(7294));
var moodle_1 = __webpack_require__(3422);
var Tooltip = function (_a) {
    var children = _a.children, content = _a.content;
    var ref = react_1.default.useRef(null);
    (0, react_1.useEffect)(function () {
        var $ = (0, moodle_1.getModule)("jquery");
        if (!$ || !ref.current || !$(ref.current).tooltip) {
            return;
        }
        ref.current.setAttribute("data-toggle", "popover");
        ref.current.setAttribute("data-container", "body");
        ref.current.setAttribute("title", content);
        $(ref.current).tooltip("enable");
        return function () {
            $(ref.current).tooltip("dispose");
        };
    }, [content]);
    return (0, react_1.cloneElement)(children, { ref: ref });
};
exports.Tooltip = Tooltip;


/***/ }),

/***/ 5935:
/***/ (function(__unused_webpack_module, exports, __webpack_require__) {

"use strict";

var __assign = (this && this.__assign) || function () {
    __assign = Object.assign || function(t) {
        for (var s, i = 1, n = arguments.length; i < n; i++) {
            s = arguments[i];
            for (var p in s) if (Object.prototype.hasOwnProperty.call(s, p))
                t[p] = s[p];
        }
        return t;
    };
    return __assign.apply(this, arguments);
};
var __createBinding = (this && this.__createBinding) || (Object.create ? (function(o, m, k, k2) {
    if (k2 === undefined) k2 = k;
    Object.defineProperty(o, k2, { enumerable: true, get: function() { return m[k]; } });
}) : (function(o, m, k, k2) {
    if (k2 === undefined) k2 = k;
    o[k2] = m[k];
}));
var __setModuleDefault = (this && this.__setModuleDefault) || (Object.create ? (function(o, v) {
    Object.defineProperty(o, "default", { enumerable: true, value: v });
}) : function(o, v) {
    o["default"] = v;
});
var __importStar = (this && this.__importStar) || function (mod) {
    if (mod && mod.__esModule) return mod;
    var result = {};
    if (mod != null) for (var k in mod) if (k !== "default" && Object.prototype.hasOwnProperty.call(mod, k)) __createBinding(result, mod, k);
    __setModuleDefault(result, mod);
    return result;
};
var __rest = (this && this.__rest) || function (s, e) {
    var t = {};
    for (var p in s) if (Object.prototype.hasOwnProperty.call(s, p) && e.indexOf(p) < 0)
        t[p] = s[p];
    if (s != null && typeof Object.getOwnPropertySymbols === "function")
        for (var i = 0, p = Object.getOwnPropertySymbols(s); i < p.length; i++) {
            if (e.indexOf(p[i]) < 0 && Object.prototype.propertyIsEnumerable.call(s, p[i]))
                t[p[i]] = s[p[i]];
        }
    return t;
};
var __spreadArray = (this && this.__spreadArray) || function (to, from, pack) {
    if (pack || arguments.length === 2) for (var i = 0, l = from.length, ar; i < l; i++) {
        if (ar || !(i in from)) {
            if (!ar) ar = Array.prototype.slice.call(from, 0, i);
            ar[i] = from[i];
        }
    }
    return to.concat(ar || Array.prototype.slice.call(from));
};
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", ({ value: true }));
exports.startApp = exports.dependencies = exports.App = void 0;
var react_1 = __webpack_require__(5594);
var react_2 = __importStar(__webpack_require__(7294));
var react_dom_1 = __importDefault(__webpack_require__(3935));
var react_query_1 = __webpack_require__(8767);
var Addon_1 = __webpack_require__(4634);
var BulkEditPoints_1 = __webpack_require__(2224);
var Button_1 = __webpack_require__(6549);
var Expandable_1 = __importDefault(__webpack_require__(1707));
var Icons_1 = __webpack_require__(3521);
var Input_1 = __importStar(__webpack_require__(6935));
var Level_1 = __importDefault(__webpack_require__(633));
var NumberInput_1 = __webpack_require__(4959);
var Str_1 = __importDefault(__webpack_require__(430));
var Tooltip_1 = __webpack_require__(7992);
var constants_1 = __webpack_require__(9482);
var contexts_1 = __webpack_require__(2471);
var hooks_1 = __webpack_require__(7909);
var levels_1 = __webpack_require__(8839);
var moodle_1 = __webpack_require__(3422);
var utils_1 = __webpack_require__(6926);
var BADGE_TYPE;
(function (BADGE_TYPE) {
    BADGE_TYPE[BADGE_TYPE["Site"] = 1] = "Site";
    BADGE_TYPE[BADGE_TYPE["Course"] = 2] = "Course";
})(BADGE_TYPE || (BADGE_TYPE = {}));
var optionsStates = [
    {
        id: "name",
        Icon: Icons_1.LanguageIcon,
        yes: "hasname",
        no: "hasnoname",
        checker: function (level) { return level.name && level.name.trim().length > 0; },
    },
    {
        id: "description",
        Icon: Icons_1.Bars3BottomLeftIcon,
        yes: "hasdescription",
        no: "hasnodescription",
        checker: function (level) { return level.description && level.description.trim().length > 0; },
    },
    {
        id: "popupmessage",
        Icon: Icons_1.PaperAirplaneIconSolid,
        yes: "haspopupmessage",
        no: "hasnopopupmessage",
        checker: function (level) { return level.popupmessage && level.popupmessage.trim().length > 0; },
    },
    {
        id: "badgeaward",
        Icon: Icons_1.CheckBadgeIconSolid,
        yes: "hasbadgeaward",
        no: "hasnobadgeaward",
        checker: function (level) { return Boolean(level.badgeawardid); },
    },
];
var optionsStatesStringIds = optionsStates.map(function (o) { return o.yes; }).concat(optionsStates.map(function (o) { return o.no; }));
var getInitialState = function (_a) {
    var levelsInfo = _a.levelsInfo;
    return {
        algo: __assign(__assign({}, levelsInfo.algo), { method: levelsInfo.algo.method || "relative", incr: levelsInfo.algo.incr || 30 }),
        levels: levelsInfo.levels.map(function (level) { return (__assign({}, level)); }),
        nblevels: levelsInfo.levels.length,
        pendingSave: false,
    };
};
var markPendingSave = function (state) {
    return __assign(__assign({}, state), { pendingSave: true });
};
var updateLevelPoints = function (state) {
    return __assign(__assign({}, state), { levels: state.levels.reduce(function (carry, level, i) {
            return carry.concat([
                __assign(__assign({}, level), { xprequired: Math.max(level.xprequired, (0, levels_1.getMinimumPointsForLevel)(carry.concat([level]), level)) }),
            ]);
        }, []) });
};
var reducer = function (state, _a) {
    var action = _a[0], payload = _a[1];
    var nextLevel;
    switch (action) {
        case "bulkEdit":
            return markPendingSave(__assign(__assign({}, state), { algo: payload, levels: state.levels.map(function (level) { return (__assign(__assign({}, level), { xprequired: (0, levels_1.computeRequiredPointsWithMethod)(level.level, payload) })); }) }));
        case "levelDescChange":
            return markPendingSave(__assign(__assign({}, state), { levels: state.levels.map(function (level) {
                    if (level !== payload.level) {
                        return level;
                    }
                    return __assign(__assign({}, level), { description: (0, utils_1.stripTags)(payload.desc) || null });
                }) }));
        case "levelNameChange":
            return markPendingSave(__assign(__assign({}, state), { levels: state.levels.map(function (level) {
                    if (level !== payload.level) {
                        return level;
                    }
                    return __assign(__assign({}, level), { name: (0, utils_1.stripTags)(payload.name) || null });
                }) }));
        case "levelBadgeAwardIdChange":
            return markPendingSave(__assign(__assign({}, state), { levels: state.levels.map(function (level) {
                    if (level !== payload.level) {
                        return level;
                    }
                    return __assign(__assign({}, level), { badgeawardid: payload.badgeawardid || null });
                }) }));
        case "levelPopupMessageChange":
            return markPendingSave(__assign(__assign({}, state), { levels: state.levels.map(function (level) {
                    if (level !== payload.level) {
                        return level;
                    }
                    return __assign(__assign({}, level), { popupmessage: payload.popupmessage || null });
                }) }));
        case "levelPointsChange":
            nextLevel = (0, levels_1.getNextLevel)(state.levels, payload.level, state.nblevels);
            if (isNaN(payload.points) || payload.points <= 2 || payload.points >= Infinity) {
                return state;
            }
            else if (payload.points <= (0, levels_1.getPreviousLevel)(state.levels, payload.level).xprequired) {
                return state;
            }
            return markPendingSave(updateLevelPoints(__assign(__assign({}, state), { levels: state.levels.map(function (level) {
                    if (level !== payload.level) {
                        return level;
                    }
                    return __assign(__assign({}, level), { xprequired: payload.points });
                }) })));
        case "nbLevelsChange":
            if (typeof (payload === null || payload === void 0 ? void 0 : payload.n) === "undefined" || isNaN(payload.n) || payload.n < 2 || payload.n > 99) {
                return state;
            }
            return markPendingSave(__assign(__assign({}, state), { nblevels: payload.n, levels: state.levels.concat(Array.from({ length: Math.max(0, payload.n - state.levels.length) }).map(function (_, i) {
                    var l = state.levels.length + i + 1;
                    return {
                        level: l,
                        name: null,
                        description: null,
                        badgeurl: ((payload === null || payload === void 0 ? void 0 : payload.defaultBadgeUrls) || {})[l] || null,
                        xprequired: (0, levels_1.computeRequiredPointsWithMethod)(l, state.algo),
                    };
                })) }));
        case "markSaved":
            return __assign(__assign({}, state), { pendingSave: false });
    }
    return state;
};
var OptionField = function (_a) {
    var label = _a.label, children = _a.children, note = _a.note, xpPlusRequired = _a.xpPlusRequired;
    return (react_2.default.createElement("div", null,
        react_2.default.createElement("label", { className: "xp-m-0 xp-block xp-font-normal" },
            react_2.default.createElement("div", { className: "xp-flex" },
                react_2.default.createElement("div", { className: "xp-grow xp-uppercase xp-text-xs" }, label),
                react_2.default.createElement("div", null, xpPlusRequired ? react_2.default.createElement(Addon_1.AddonRequired, null) : null)),
            react_2.default.createElement("div", { className: "xp-mt-1" }, children)),
        note ? react_2.default.createElement("div", { className: "xp-text-gray-500 xp-mt-1" }, note) : null));
};
var App = function (_a) {
    var courseId = _a.courseId, levelsInfo = _a.levelsInfo, resetToDefaultsUrl = _a.resetToDefaultsUrl, defaultBadgeUrls = _a.defaultBadgeUrls, _b = _a.badges, badges = _b === void 0 ? [] : _b;
    var hasXpPlus = (0, hooks_1.useAddonActivated)();
    var _c = (0, react_2.useReducer)(reducer, { levelsInfo: levelsInfo }, getInitialState), state = _c[0], dispatch = _c[1];
    var levels = state.levels.slice(0, state.nblevels);
    var _d = react_2.default.useState([]), expanded = _d[0], setExpanded = _d[1];
    var _e = react_2.default.useState(false), bulkEdit = _e[0], setBulkEdit = _e[1];
    var getStr = (0, hooks_1.useStrings)(optionsStatesStringIds.concat(["levelssaved", "unknownbadgea", "levelx"]));
    var getBadgeStr = (0, hooks_1.useStrings)(["coursebadges", "sitebadges"], "core_badges");
    var getCoreStr = (0, hooks_1.useStrings)(["other", "none"], "core");
    (0, hooks_1.useUnloadCheck)(state.pendingSave);
    // Prepare the save mutation.
    var mutation = (0, react_query_1.useMutation)(function () {
        // An falsy course ID means admin config.
        var method = courseId ? "block_xp_set_levels_info" : "block_xp_set_default_levels_info";
        return (0, moodle_1.getModule)("core/ajax").call([
            {
                methodname: method,
                args: {
                    courseid: courseId ? courseId : undefined,
                    levels: levels.map(function (level) {
                        var levelnum = level.level, xprequired = level.xprequired, metadata = __rest(level, ["level", "xprequired"]);
                        return {
                            level: levelnum,
                            xprequired: xprequired,
                            metadata: Object.entries(metadata).reduce(function (carry, _a) {
                                var name = _a[0], value = _a[1];
                                return carry.concat([{ name: name, value: value }]);
                            }, []),
                        };
                    }),
                    algo: state.algo,
                },
            },
        ])[0];
    });
    // Reset mutation after success.
    (0, react_2.useEffect)(function () {
        if (!mutation.isSuccess) {
            return;
        }
        var t = setTimeout(function () {
            mutation.reset();
        }, 2500);
        return function () { return clearTimeout(t); };
    });
    var siteBadges = (0, react_2.useMemo)(function () { return badges.filter(function (b) { return b.type === BADGE_TYPE.Site; }).sort(function (a, b) { return a.name.localeCompare(b.name); }); }, [badges]);
    var courseBadges = (0, react_2.useMemo)(function () { return badges.filter(function (b) { return b.type === BADGE_TYPE.Course; }).sort(function (a, b) { return a.name.localeCompare(b.name); }); }, [badges]);
    var allExpanded = expanded.length === levels.length;
    var handleCollapseExpandAll = function () {
        setExpanded(allExpanded ? [] : levels.map(function (l) { return l.level; }));
    };
    var handleSave = function () {
        mutation.mutate(undefined, {
            onSuccess: function () {
                var Toast = (0, moodle_1.getModule)("core/toast");
                Toast && Toast.add(getStr("levelssaved"));
                dispatch(["markSaved", true]);
            },
        });
    };
    var handleBulkEdit = function (state) {
        dispatch(["bulkEdit", state]);
    };
    var handleNumLevelsChange = function (n) {
        dispatch(["nbLevelsChange", { n: n, defaultBadgeUrls: defaultBadgeUrls }]);
    };
    var handleLevelDescChange = function (level, desc) {
        if (level.description === desc)
            return;
        dispatch(["levelDescChange", { level: level, desc: desc }]);
    };
    var handleLevelNameChange = function (level, name) {
        if (level.name === name)
            return;
        dispatch(["levelNameChange", { level: level, name: name }]);
    };
    var handleXpChange = function (level, xp) {
        if (level.xprequired === xp)
            return;
        dispatch(["levelPointsChange", { level: level, points: xp }]);
    };
    return (react_2.default.createElement("div", { className: "xp-flex xp-flex-col" },
        react_2.default.createElement("div", { className: "xp-mb-4 xp-flex xp-items-end xp-justify-end xp-flex-wrap xp-gap-4" },
            react_2.default.createElement("div", { className: "xp-flex xp-flex-1 xp-gap-4 xp-items-end xp-flex-wrap" },
                react_2.default.createElement("div", { className: "" },
                    react_2.default.createElement("label", { htmlFor: "label-x", className: "xp-block xp-m-0" },
                        react_2.default.createElement(Str_1.default, { id: "numberoflevels" })),
                    react_2.default.createElement(NumberInput_1.NumberInputWithButtons, { value: state.nblevels, onChange: handleNumLevelsChange, min: 2, max: 99, inputProps: { id: "label-x", maxLength: 2 } })),
                react_2.default.createElement("div", { className: "" },
                    react_2.default.createElement(Button_1.Button, { onClick: function () { return setBulkEdit(true); } },
                        react_2.default.createElement(Str_1.default, { id: "quickeditpoints" })),
                    react_2.default.createElement(BulkEditPoints_1.BulkEditPointsModal, { show: bulkEdit, onClose: function () { return setBulkEdit(false); }, onSave: handleBulkEdit, method: state.algo.method, coef: state.algo.coef, base: state.algo.base, incr: state.algo.incr }))),
            react_2.default.createElement("div", { className: "xp-flex xp-gap-1" },
                react_2.default.createElement(Button_1.SaveButton, { statePosition: "before", onClick: handleSave, mutation: mutation, disabled: !state.pendingSave || mutation.isLoading }),
                react_2.default.createElement(react_1.Menu, { as: "div", className: "xp-relative xp-inline-block xp-text-left" },
                    react_2.default.createElement("div", null,
                        react_2.default.createElement(react_1.Menu.Button, { className: "xp-bg-transparent xp-border-0 xp-p-2 xp-flex xp-items-center xp-rounded-full hover:xp-bg-gray-100" },
                            react_2.default.createElement("span", { className: "sr-only" },
                                react_2.default.createElement(Str_1.default, { id: "options", component: "core" })),
                            react_2.default.createElement("svg", { xmlns: "http://www.w3.org/2000/svg", viewBox: "0 0 20 20", fill: "currentColor", className: "xp-w-5 xp-h-5", "aria-hidden": "true" },
                                react_2.default.createElement("path", { d: "M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM10 8.5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM11.5 15.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z" })))),
                    react_2.default.createElement(react_1.Menu.Items, { className: "xp-absolute xp-right-0 xp-z-10 xp-mt-2 xp-w-56 xp-origin-top-right xp-rounded-md xp-bg-white xp-border xp-border-solid xp-border-gray-300 xp-shadow-sm xp-divide-y xp-divide-gray-100" },
                        react_2.default.createElement("div", { className: "xp-py-1" },
                            react_2.default.createElement(react_1.Menu.Item, null, function (_a) {
                                var active = _a.active, close = _a.close;
                                return (react_2.default.createElement("a", { href: "#", role: "button", onClick: function (e) {
                                        e.preventDefault();
                                        handleCollapseExpandAll();
                                        close();
                                    }, className: (0, utils_1.classNames)(active ? "xp-bg-gray-100" : null, "xp-text-inherit xp-block xp-px-6 xp-py-1 xp-no-underline") }, allExpanded ? react_2.default.createElement(Str_1.default, { id: "collapseall", component: "core" }) : react_2.default.createElement(Str_1.default, { id: "expandall", component: "core" })));
                            }),
                            react_2.default.createElement(react_1.Menu.Item, null, function (_a) {
                                var active = _a.active, close = _a.close;
                                return (react_2.default.createElement("a", { href: constants_1.HELP_URL_LEVELS, target: "_blank", rel: "noopener", className: (0, utils_1.classNames)(active ? "xp-bg-gray-100" : null, "xp-text-inherit xp-block xp-px-6 xp-py-1 xp-no-underline") },
                                    react_2.default.createElement(Str_1.default, { id: "documentation" })));
                            })),
                        resetToDefaultsUrl ? (react_2.default.createElement("div", { className: "xp-py-1" },
                            react_2.default.createElement(react_1.Menu.Item, null, function (_a) {
                                var active = _a.active, close = _a.close;
                                return (react_2.default.createElement("a", { href: resetToDefaultsUrl, className: (0, utils_1.classNames)(active ? "xp-bg-gray-100" : null, "xp-text-red-600 xp-block xp-px-6 xp-py-1 xp-no-underline") },
                                    react_2.default.createElement(Str_1.default, { id: "resetlevelstodefaults" })));
                            }))) : null)))),
        react_2.default.createElement("div", { className: "xp-flex xp-flex-col xp-flex-1 xp-gap-4" }, Array.from({ length: state.nblevels }).map(function (_, idx) {
            var _a, _b;
            var level = levels[idx] || { level: idx + 1, xprequired: 0 };
            var prevLevel = levels[idx - 1];
            var nextLevel = levels[idx + 1];
            var pointsInLevel = nextLevel ? nextLevel.xprequired - level.xprequired : 0;
            var isExpanded = expanded.includes(level.level);
            var optionStates = level.level <= 1
                ? optionsStates.filter(function (o) { return ["name", "description", courseId ? null : "badgeawardid"].includes(o.id); })
                : optionsStates;
            optionStates = optionStates.concat(Array.from({ length: Math.max(0, optionsStates.length - optionStates.length) }).map(function (_) { return null; }));
            var isBadgeValueMissing = ((_a = levelsInfo.levels[idx]) === null || _a === void 0 ? void 0 : _a.badgeawardid) && !badges.find(function (b) { return b.id === levelsInfo.levels[idx].badgeawardid; });
            var handleBadgeAwardIdChange = function (e) {
                dispatch(["levelBadgeAwardIdChange", { level: level, badgeawardid: parseInt(e.target.value, 10) || null }]);
            };
            var handlePopupMessageChange = function (e) {
                dispatch(["levelPopupMessageChange", { level: level, popupmessage: e.target.value }]);
            };
            return (react_2.default.createElement(react_2.default.Fragment, { key: "l" + level.level },
                react_2.default.createElement("div", { className: "xp-relative xp-min-h-28 xp-rounded-lg xp-border xp-border-solid xp-border-gray-200 xp-p-3 xp-overflow-hidden" },
                    react_2.default.createElement("div", { className: "xp-absolute xp--top-4 xp--left-8 xp-text-[10rem] xp-text-gray-50 xp-leading-none xp-pointer-events-none" }, level.level),
                    react_2.default.createElement("div", { className: "xp-flex xp-items-center xp-flex-grow xp-gap-4 sm:xp-gap-8 xp-flex-col sm:xp-flex-row xp-relative" },
                        react_2.default.createElement("div", { className: "xp-flex-0" },
                            react_2.default.createElement(Tooltip_1.Tooltip, { content: getStr("levelx", level.level) },
                                react_2.default.createElement(Level_1.default, { level: level }))),
                        react_2.default.createElement("div", { className: "xp-shrink-0 xp-basis-auto sm:xp-basis-52 sm:xp--mt-3.5" },
                            react_2.default.createElement("div", { className: "xp-grid xp-grid-cols-2" },
                                react_2.default.createElement("label", { className: "xp-m-0 xp-flex xp-items-end xp-text-xs xp-font-normal xp-uppercase", htmlFor: "xp-level-" + level.level + "-start" },
                                    react_2.default.createElement(Str_1.default, { id: "levelpointsstart" })),
                                react_2.default.createElement("label", { className: "xp-m-0 xp-flex xp-items-end xp-text-xs xp-font-normal xp-uppercase", htmlFor: "xp-level-" + level.level + "-length" },
                                    react_2.default.createElement(Str_1.default, { id: "levelpointslength" }))),
                            react_2.default.createElement("div", { className: "xp-grid xp-grid-cols-2 xp-border xp-border-solid xp-border-gray-300 xp-rounded" },
                                react_2.default.createElement("div", null,
                                    react_2.default.createElement(NumberInput_1.NumInput, { value: level.xprequired, onChange: function (xp) { return handleXpChange(level, xp); }, disabled: level.level <= 1, className: "xp-min-w-[4ch] xp-w-full xp-rounded-none xp-rounded-l xp-border-0 xp-relative focus:xp-z-10", id: "xp-level-" + level.level + "-start" })),
                                react_2.default.createElement("div", { className: "" },
                                    react_2.default.createElement("div", { className: "xp-flex-1 xp-relative" },
                                        react_2.default.createElement("div", { className: "xp-pointer-events-none xp-absolute xp-inset-y-0 xp-left-0 xp-flex xp-items-center xp-pl-2 xp-z-20" },
                                            react_2.default.createElement("span", { className: "xp-text-gray-500" }, "+")),
                                        react_2.default.createElement(NumberInput_1.NumInput, { value: pointsInLevel, onChange: function (xp) { return handleXpChange(nextLevel, level.xprequired + xp); }, disabled: pointsInLevel <= 0, className: "xp-min-w-[4ch] xp-w-full xp-border-0 xp-rounded-none xp-border-l xp-border-gray-300 xp-rounded-r xp-pl-6 xp-relative focus:xp-z-10", id: "xp-level-" + level.level + "-length" }))))),
                        react_2.default.createElement("div", { className: "xp-flex xp-grow xp-items-center xp-justify-center  xp-gap-4" }, optionStates.map(function (o, idx) {
                            if (!o) {
                                return react_2.default.createElement("div", { key: idx, className: "xp-w-6 xp-h-6 xp-hidden sm:xp-block" });
                            }
                            var state = o.checker(level);
                            var label = getStr(state ? o.yes : o.no);
                            return (react_2.default.createElement(Tooltip_1.Tooltip, { content: label, key: idx },
                                react_2.default.createElement("div", { className: (0, utils_1.classNames)("xp-w-6 xp-h-6", !state ? "xp-text-gray-300" : null) },
                                    react_2.default.createElement("span", { className: "xp-sr-only" }, label),
                                    react_2.default.createElement(o.Icon, { className: "xp-w-full xp-h-full" }))));
                        })),
                        react_2.default.createElement("div", { className: "xp-flex-0 sm:xp--mr-3" },
                            react_2.default.createElement(Button_1.AnchorButton, { "aria-expanded": isExpanded, "aria-controls": "xp-level-" + level.level + "-options", onClick: function () {
                                    setExpanded(isExpanded ? expanded.filter(function (e) { return e != level.level; }) : __spreadArray([level.level], expanded, true));
                                }, className: "xp-p-2 xp-inline-block sm:xp-mr-1" },
                                react_2.default.createElement("span", { className: "xp-sr-only" }, isExpanded ? react_2.default.createElement(Str_1.default, { id: "collapse", component: "core" }) : react_2.default.createElement(Str_1.default, { id: "expand", component: "core" })),
                                react_2.default.createElement("svg", { xmlns: "http://www.w3.org/2000/svg", fill: "none", viewBox: "0 0 24 24", strokeWidth: 1.5, stroke: "currentColor", className: (0, utils_1.classNames)("xp-w-6 xp-h-6 xp-transition-transform xp-duration-300", isExpanded ? "xp-rotate-90" : null) },
                                    react_2.default.createElement("path", { strokeLinecap: "round", strokeLinejoin: "round", d: "M8.25 4.5l7.5 7.5-7.5 7.5" }))))),
                    react_2.default.createElement(Expandable_1.default, { expanded: isExpanded, id: "xp-level-" + level.level + "-options" },
                        react_2.default.createElement("div", { className: (0, utils_1.classNames)("sm:xp-ml-[100px] sm:xp-pl-8 xp-space-y-4") },
                            react_2.default.createElement(OptionField, { label: react_2.default.createElement(Str_1.default, { id: "name" }) },
                                react_2.default.createElement(Input_1.default, { className: "xp-min-w-48 x-w-full sm:xp-w-1/2 xp-max-w-full", onBlur: function (e) { return handleLevelNameChange(level, e.target.value); }, defaultValue: level.name || "", maxLength: 40, type: "text" })),
                            react_2.default.createElement(OptionField, { label: react_2.default.createElement(Str_1.default, { id: "description" }), note: react_2.default.createElement(Str_1.default, { id: "leveldescriptiondesc" }) },
                                react_2.default.createElement(Input_1.Textarea, { className: "xp-w-full", onBlur: function (e) { return handleLevelDescChange(level, e.target.value); }, defaultValue: level.description || "", maxLength: 280, rows: 2 })),
                            react_2.default.createElement(Addon_1.IfAddonActivatedOrPromoEnabled, null, level.level > 1 ? (react_2.default.createElement(react_2.default.Fragment, null,
                                react_2.default.createElement(OptionField, { label: react_2.default.createElement(Str_1.default, { id: "popupnotificationmessage" }), note: react_2.default.createElement(Str_1.default, { id: "popupnotificationmessagedesc" }), xpPlusRequired: !hasXpPlus },
                                    react_2.default.createElement(Input_1.Textarea, { className: "xp-w-full", onBlur: handlePopupMessageChange, defaultValue: level.popupmessage || "", maxLength: 280, rows: 2, disabled: !hasXpPlus })),
                                react_2.default.createElement(OptionField, { label: react_2.default.createElement(Str_1.default, { id: "badgeaward" }), note: react_2.default.createElement(Str_1.default, { id: "badgeawarddesc" }), xpPlusRequired: !hasXpPlus }, courseId ? (react_2.default.createElement(Input_1.Select, { disabled: !hasXpPlus, className: "xp-max-w-full xp-w-auto", value: (_b = level.badgeawardid) !== null && _b !== void 0 ? _b : "", onChange: handleBadgeAwardIdChange },
                                    react_2.default.createElement("option", null, getCoreStr("none")),
                                    courseBadges.length ? (react_2.default.createElement("optgroup", { label: getBadgeStr("coursebadges") }, courseBadges.map(function (b) { return (react_2.default.createElement("option", { value: b.id, key: b.id }, b.name)); }))) : null,
                                    siteBadges.length ? (react_2.default.createElement("optgroup", { label: getBadgeStr("sitebadges") }, siteBadges.map(function (b) { return (react_2.default.createElement("option", { value: b.id, key: b.id }, b.name)); }))) : null,
                                    isBadgeValueMissing ? (react_2.default.createElement("optgroup", { label: getCoreStr("other") },
                                        react_2.default.createElement("option", { value: level.badgeawardid || "" }, getStr("unknownbadgea", level.badgeawardid)))) : null)) : (react_2.default.createElement("div", { className: "alert alert-info xp-m-0" },
                                    react_2.default.createElement(Str_1.default, { id: "cannotbesetindefaults" })))))) : (react_2.default.createElement("div", null,
                                react_2.default.createElement("div", { className: "xp-text-sm xp-text-gray-500 xp-italic" },
                                    react_2.default.createElement(Str_1.default, { id: "levelupoptionsunavailableforlevelone" }))))))))));
        })),
        react_2.default.createElement("div", { className: "xp-flex xp-flex-1 xp-gap-4 xp-items-start xp-flex-wrap xp-mt-4" },
            react_2.default.createElement("div", { className: "xp-grow" },
                react_2.default.createElement(Button_1.Button, { onClick: function () { return handleNumLevelsChange(state.nblevels + 1); } },
                    react_2.default.createElement(Str_1.default, { id: "addlevel" }))),
            react_2.default.createElement("div", { className: "" },
                react_2.default.createElement(Button_1.SaveButton, { statePosition: "before", onClick: handleSave, mutation: mutation, disabled: !state.pendingSave || mutation.isLoading })))));
};
exports.App = App;
var queryClient = new react_query_1.QueryClient({
    defaultOptions: {
        mutations: {
            onError: function (err) { return (0, moodle_1.getModule)("core/notification").exception(err); },
        },
    },
});
function startApp(node, props) {
    react_dom_1.default.render(react_2.default.createElement(contexts_1.AddonContext.Provider, { value: (0, contexts_1.makeAddonContextValueFromAppProps)(props) },
        react_2.default.createElement(react_query_1.QueryClientProvider, { client: queryClient },
            react_2.default.createElement(exports.App, __assign({}, props)))), node);
}
exports.startApp = startApp;
var dependencies = (0, moodle_1.makeDependenciesDefinition)([
    "core/str",
    "core/ajax",
    "core/modal",
    "core/modal_events",
    "core/modal_factory",
    "core/notification",
    "?core/toast",
    "jquery",
]);
exports.dependencies = dependencies;


/***/ }),

/***/ 9482:
/***/ (function(__unused_webpack_module, exports) {

"use strict";

Object.defineProperty(exports, "__esModule", ({ value: true }));
exports.HELP_URL_LEVELS = void 0;
exports.HELP_URL_LEVELS = "https://docs.levelup.plus/xp/docs/levels";


/***/ }),

/***/ 2471:
/***/ (function(__unused_webpack_module, exports, __webpack_require__) {

"use strict";

var __assign = (this && this.__assign) || function () {
    __assign = Object.assign || function(t) {
        for (var s, i = 1, n = arguments.length; i < n; i++) {
            s = arguments[i];
            for (var p in s) if (Object.prototype.hasOwnProperty.call(s, p))
                t[p] = s[p];
        }
        return t;
    };
    return __assign.apply(this, arguments);
};
Object.defineProperty(exports, "__esModule", ({ value: true }));
exports.AddonContext = exports.makeAddonContextValueFromAppProps = void 0;
var react_1 = __webpack_require__(7294);
var makeAddonContextValueFromAppProps = function (props) {
    var _a;
    return __assign({ activated: false, enablepromo: true, promourl: "https://levelup.plus/xp/" }, ((_a = props === null || props === void 0 ? void 0 : props.addon) !== null && _a !== void 0 ? _a : {}));
};
exports.makeAddonContextValueFromAppProps = makeAddonContextValueFromAppProps;
exports.AddonContext = (0, react_1.createContext)({
    activated: false,
    enablepromo: true,
    promourl: "https://levelup.plus/xp/", // Local promo page where possible.
});


/***/ }),

/***/ 7909:
/***/ (function(__unused_webpack_module, exports, __webpack_require__) {

"use strict";

var __assign = (this && this.__assign) || function () {
    __assign = Object.assign || function(t) {
        for (var s, i = 1, n = arguments.length; i < n; i++) {
            s = arguments[i];
            for (var p in s) if (Object.prototype.hasOwnProperty.call(s, p))
                t[p] = s[p];
        }
        return t;
    };
    return __assign.apply(this, arguments);
};
var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
var __generator = (this && this.__generator) || function (thisArg, body) {
    var _ = { label: 0, sent: function() { if (t[0] & 1) throw t[1]; return t[1]; }, trys: [], ops: [] }, f, y, t, g;
    return g = { next: verb(0), "throw": verb(1), "return": verb(2) }, typeof Symbol === "function" && (g[Symbol.iterator] = function() { return this; }), g;
    function verb(n) { return function (v) { return step([n, v]); }; }
    function step(op) {
        if (f) throw new TypeError("Generator is already executing.");
        while (_) try {
            if (f = 1, y && (t = op[0] & 2 ? y["return"] : op[0] ? y["throw"] || ((t = y["return"]) && t.call(y), 0) : y.next) && !(t = t.call(y, op[1])).done) return t;
            if (y = 0, t) op = [op[0] & 2, t.value];
            switch (op[0]) {
                case 0: case 1: t = op; break;
                case 4: _.label++; return { value: op[1], done: false };
                case 5: _.label++; y = op[1]; op = [0]; continue;
                case 7: op = _.ops.pop(); _.trys.pop(); continue;
                default:
                    if (!(t = _.trys, t = t.length > 0 && t[t.length - 1]) && (op[0] === 6 || op[0] === 2)) { _ = 0; continue; }
                    if (op[0] === 3 && (!t || (op[1] > t[0] && op[1] < t[3]))) { _.label = op[1]; break; }
                    if (op[0] === 6 && _.label < t[1]) { _.label = t[1]; t = op; break; }
                    if (t && _.label < t[2]) { _.label = t[2]; _.ops.push(op); break; }
                    if (t[2]) _.ops.pop();
                    _.trys.pop(); continue;
            }
            op = body.call(thisArg, _);
        } catch (e) { op = [6, e]; y = 0; } finally { f = t = 0; }
        if (op[0] & 5) throw op[1]; return { value: op[0] ? op[1] : void 0, done: true };
    }
};
Object.defineProperty(exports, "__esModule", ({ value: true }));
exports.useStrings = exports.useString = exports.useUnloadCheck = exports.useRoleButtonListeners = exports.useNumericInputProps = exports.useAnchorButtonProps = exports.useAddonActivated = void 0;
var react_1 = __webpack_require__(7294);
var moodle_1 = __webpack_require__(3422);
var contexts_1 = __webpack_require__(2471);
var useAddonActivated = function () {
    return (0, react_1.useContext)(contexts_1.AddonContext).activated;
};
exports.useAddonActivated = useAddonActivated;
var useAnchorButtonProps = function (onClick) {
    var listeners = (0, exports.useRoleButtonListeners)(onClick);
    return __assign({ href: "#", role: "button" }, listeners);
};
exports.useAnchorButtonProps = useAnchorButtonProps;
var useNumericInputProps = function (value, onChange) {
    var valueAsString = value.toString();
    var _a = (0, react_1.useState)(valueAsString), externalValue = _a[0], setExternalValue = _a[1];
    var _b = (0, react_1.useState)(externalValue), internalValue = _b[0], setInternalValue = _b[1];
    (0, react_1.useEffect)(function () {
        if (valueAsString !== externalValue) {
            setExternalValue(valueAsString);
            setInternalValue(valueAsString);
        }
    });
    var handleBlur = function (e) {
        var v = parseInt(internalValue, 10) || 0;
        setExternalValue(v.toString());
        onChange(v);
    };
    var handleChange = function (e) {
        setInternalValue(e.target.value.replace(/[^0-9]/, ""));
    };
    return {
        value: internalValue,
        onChange: handleChange,
        onBlur: handleBlur,
    };
};
exports.useNumericInputProps = useNumericInputProps;
var useRoleButtonListeners = function (onClick) {
    var handleClick = function (e) {
        e.preventDefault();
        onClick();
    };
    var handleKeyDown = function (e) {
        if (e.key !== " " && e.key !== "Enter") {
            return;
        }
        e.preventDefault();
        onClick();
    };
    return {
        onClick: handleClick,
        onKeyDown: handleKeyDown,
    };
};
exports.useRoleButtonListeners = useRoleButtonListeners;
var useUnloadCheck = function (isDirty) {
    var str = (0, exports.useString)("changesmadereallygoaway", "core");
    (0, react_1.useEffect)(function () {
        var fn = function (e) {
            if (!isDirty || (0, moodle_1.isBehatRunning)()) {
                return;
            }
            e.preventDefault();
            e.returnValue = str;
            return str;
        };
        window.addEventListener("beforeunload", fn);
        return function () {
            window.removeEventListener("beforeunload", fn);
        };
    });
};
exports.useUnloadCheck = useUnloadCheck;
var useString = function (id, component, a) {
    if (component === void 0) { component = "block_xp"; }
    var wasKnownAtMount = (0, react_1.useMemo)(function () { return (0, moodle_1.hasString)(id, component); }, [id, component]);
    var _a = (0, react_1.useState)(false), isLoaded = _a[0], setLoaded = _a[1];
    // When the string changes, remove the promise.
    (0, react_1.useEffect)(function () {
        setLoaded(false);
    }, [id, component]);
    // Load the string when it is unknown.
    (0, react_1.useEffect)(function () {
        if (wasKnownAtMount || isLoaded) {
            return;
        }
        var cancelled = false;
        (function () { return __awaiter(void 0, void 0, void 0, function () {
            var err_1;
            return __generator(this, function (_a) {
                switch (_a.label) {
                    case 0:
                        _a.trys.push([0, 2, , 3]);
                        return [4 /*yield*/, (0, moodle_1.loadString)(id, component)];
                    case 1:
                        _a.sent();
                        if (!cancelled) {
                            setLoaded(true);
                        }
                        return [3 /*break*/, 3];
                    case 2:
                        err_1 = _a.sent();
                        return [3 /*break*/, 3];
                    case 3: return [2 /*return*/];
                }
            });
        }); })();
        return function () {
            cancelled = true;
        };
    });
    return (0, moodle_1.hasString)(id, component) ? (0, moodle_1.getString)(id, component, a) : "​";
};
exports.useString = useString;
var useStrings = function (ids, component) {
    if (component === void 0) { component = "block_xp"; }
    var idsForKey = ids.join(",");
    var allKnownAtMount = (0, react_1.useMemo)(function () { return ids.every(function (id) { return (0, moodle_1.hasString)(id, component); }); }, [idsForKey, component]);
    var _a = (0, react_1.useState)(false), isLoaded = _a[0], setLoaded = _a[1];
    // When the string changes, remove the promise.
    (0, react_1.useEffect)(function () {
        setLoaded(false);
    }, [idsForKey, component]);
    // Load the string when it is unknown.
    (0, react_1.useEffect)(function () {
        if (allKnownAtMount || isLoaded) {
            return;
        }
        var cancelled = false;
        (function () { return __awaiter(void 0, void 0, void 0, function () {
            var err_2;
            return __generator(this, function (_a) {
                switch (_a.label) {
                    case 0:
                        _a.trys.push([0, 2, , 3]);
                        return [4 /*yield*/, (0, moodle_1.loadStrings)(ids, component)];
                    case 1:
                        _a.sent();
                        if (!cancelled) {
                            setLoaded(true);
                        }
                        return [3 /*break*/, 3];
                    case 2:
                        err_2 = _a.sent();
                        return [3 /*break*/, 3];
                    case 3: return [2 /*return*/];
                }
            });
        }); })();
        return function () {
            cancelled = true;
        };
    });
    return function (id, a) { return ((0, moodle_1.hasString)(id, component) ? (0, moodle_1.getString)(id, component, a) : "​"); };
};
exports.useStrings = useStrings;


/***/ }),

/***/ 8839:
/***/ (function(__unused_webpack_module, exports) {

"use strict";

Object.defineProperty(exports, "__esModule", ({ value: true }));
exports.getPreviousLevel = exports.getNextLevel = exports.getMinimumPointsAtLevel = exports.getMinimumPointsForLevel = exports.getLevel = exports.computeRequiredPointsWithMethod = exports.computeRequiredPoints = void 0;
function computeRequiredPoints(level, base, coef) {
    if (level <= 1) {
        return 0;
    }
    else if (level == 2) {
        return base;
    }
    if (coef <= 1) {
        return base * (level - 1);
    }
    return Math.round(base * ((1 - Math.pow(coef, level - 1)) / (1 - coef)));
}
exports.computeRequiredPoints = computeRequiredPoints;
function computeRequiredPointsWithMethod(level, method) {
    if (level <= 1) {
        return 0;
    }
    else if (level === 2) {
        return method.base;
    }
    if (method.method === "relative") {
        // Refer to the original method that was algorithmic.
        return computeRequiredPoints(level, method.base, method.coef);
    }
    else if (method.method === "linear") {
        // Each level is worth the base + increment (starting at level 3);
        // Level 1: 0; level 2: 100; Level 3: 210 (100 + (100 + 10)); Level 4: 330 (100 + (100 + 10) + (100 + 10 + 10));
        return (method.base * (level - 1) +
            Array.from({ length: level }).reduce(function (carry, _, idx) { return carry + Math.max(0, idx - 1) * method.incr; }, 0));
    }
    // Flat method.
    return (level - 1) * method.base;
}
exports.computeRequiredPointsWithMethod = computeRequiredPointsWithMethod;
var getLevel = function (levels, level) {
    return levels[Math.max(0, level - 1)];
};
exports.getLevel = getLevel;
var getMinimumPointsForLevel = function (levels, level) {
    if (level.level === 1 || !levels.length) {
        return 0;
    }
    return (0, exports.getPreviousLevel)(levels, level).xprequired + 1;
};
exports.getMinimumPointsForLevel = getMinimumPointsForLevel;
var getMinimumPointsAtLevel = function (levels, level) {
    var l = (0, exports.getLevel)(levels, level - 1);
    return l ? l.xprequired + 1 : 0;
};
exports.getMinimumPointsAtLevel = getMinimumPointsAtLevel;
var getNextLevel = function (levels, level, highest) {
    if (highest === void 0) { highest = 9999; }
    var index = Math.min(highest, Math.max(levels.indexOf(level) + 1, 0));
    return levels[index];
};
exports.getNextLevel = getNextLevel;
var getPreviousLevel = function (levels, level) {
    return levels[Math.max(levels.indexOf(level) - 1, 0)];
};
exports.getPreviousLevel = getPreviousLevel;


/***/ }),

/***/ 3422:
/***/ (function(__unused_webpack_module, exports, __webpack_require__) {

"use strict";

var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
var __generator = (this && this.__generator) || function (thisArg, body) {
    var _ = { label: 0, sent: function() { if (t[0] & 1) throw t[1]; return t[1]; }, trys: [], ops: [] }, f, y, t, g;
    return g = { next: verb(0), "throw": verb(1), "return": verb(2) }, typeof Symbol === "function" && (g[Symbol.iterator] = function() { return this; }), g;
    function verb(n) { return function (v) { return step([n, v]); }; }
    function step(op) {
        if (f) throw new TypeError("Generator is already executing.");
        while (_) try {
            if (f = 1, y && (t = op[0] & 2 ? y["return"] : op[0] ? y["throw"] || ((t = y["return"]) && t.call(y), 0) : y.next) && !(t = t.call(y, op[1])).done) return t;
            if (y = 0, t) op = [op[0] & 2, t.value];
            switch (op[0]) {
                case 0: case 1: t = op; break;
                case 4: _.label++; return { value: op[1], done: false };
                case 5: _.label++; y = op[1]; op = [0]; continue;
                case 7: op = _.ops.pop(); _.trys.pop(); continue;
                default:
                    if (!(t = _.trys, t = t.length > 0 && t[t.length - 1]) && (op[0] === 6 || op[0] === 2)) { _ = 0; continue; }
                    if (op[0] === 3 && (!t || (op[1] > t[0] && op[1] < t[3]))) { _.label = op[1]; break; }
                    if (op[0] === 6 && _.label < t[1]) { _.label = t[1]; t = op; break; }
                    if (t && _.label < t[2]) { _.label = t[2]; _.ops.push(op); break; }
                    if (t[2]) _.ops.pop();
                    _.trys.pop(); continue;
            }
            op = body.call(thisArg, _);
        } catch (e) { op = [6, e]; y = 0; } finally { f = t = 0; }
        if (op[0] & 5) throw op[1]; return { value: op[0] ? op[1] : void 0, done: true };
    }
};
Object.defineProperty(exports, "__esModule", ({ value: true }));
exports.setModule = exports.makeDependenciesDefinition = exports.loadStrings = exports.loadString = exports.isBehatRunning = exports.imageUrl = exports.getModule = exports.hasString = exports.getUrl = exports.getString = void 0;
var utils_1 = __webpack_require__(6926);
var M = window.M;
var modules = {};
function getString(id, component, a) {
    return M.util.get_string(id, component, a);
}
exports.getString = getString;
function getUrl(uri) {
    if (uri[0] != "/") {
        uri = "/" + uri;
    }
    return M.cfg.wwwroot + uri;
}
exports.getUrl = getUrl;
function hasString(id, component) {
    return typeof M.str[component] !== "undefined" && typeof M.str[component][id] !== "undefined";
}
exports.hasString = hasString;
function getModule(name) {
    return modules[name];
}
exports.getModule = getModule;
function imageUrl(name, component) {
    return M.util.image_url(name, component);
}
exports.imageUrl = imageUrl;
function isBehatRunning() {
    return M.cfg.behatsiterunning;
}
exports.isBehatRunning = isBehatRunning;
var loadStringCache = (0, utils_1.fifoCache)(64);
function loadString(id, component) {
    return __awaiter(this, void 0, void 0, function () {
        var cacheKey, promise;
        return __generator(this, function (_a) {
            switch (_a.label) {
                case 0:
                    cacheKey = id + "/" + component;
                    promise = loadStringCache.get(cacheKey);
                    if (!promise) {
                        promise = getModule("core/str").get_string(id, component);
                        loadStringCache.set(cacheKey, promise);
                    }
                    return [4 /*yield*/, promise];
                case 1: return [2 /*return*/, _a.sent()];
            }
        });
    });
}
exports.loadString = loadString;
function loadStrings(ids, component) {
    return __awaiter(this, void 0, void 0, function () {
        var cacheKey, promise;
        return __generator(this, function (_a) {
            switch (_a.label) {
                case 0:
                    cacheKey = ids.join(",") + "/" + component;
                    promise = loadStringCache.get(cacheKey);
                    if (!promise) {
                        promise = getModule("core/str").get_strings(ids.map(function (id) { return ({ key: id, component: component }); }));
                        loadStringCache.set(cacheKey, promise);
                    }
                    return [4 /*yield*/, promise];
                case 1: return [2 /*return*/, _a.sent()];
            }
        });
    });
}
exports.loadStrings = loadStrings;
var makeDependenciesDefinition = function (names) {
    var optional = [];
    var list = names.map(function (name) {
        var isOptional = name.charAt(0) === "?";
        var module = isOptional ? name.substring(1) : name;
        if (isOptional) {
            optional.push(module);
        }
        return module;
    });
    return {
        list: list,
        optional: optional,
        loader: function (mods) {
            mods.forEach(function (mod, i) {
                setModule(list[i], mod);
            });
        },
    };
};
exports.makeDependenciesDefinition = makeDependenciesDefinition;
function setModule(name, mod) {
    modules[name] = mod;
}
exports.setModule = setModule;


/***/ }),

/***/ 6926:
/***/ (function(__unused_webpack_module, exports) {

"use strict";

Object.defineProperty(exports, "__esModule", ({ value: true }));
exports.stripTags = exports.fifoCache = exports.classNames = void 0;
var classNames = function () {
    var args = [];
    for (var _i = 0; _i < arguments.length; _i++) {
        args[_i] = arguments[_i];
    }
    return args.filter(Boolean).join(' ');
};
exports.classNames = classNames;
var fifoCache = function (maxItems) {
    if (maxItems === void 0) { maxItems = 128; }
    var items = {};
    var keys = [];
    var purge = function () {
        if (keys.length > maxItems) {
            var idx = Math.max(0, keys.length - maxItems);
            keys.slice(0, idx).forEach(function (key) {
                delete items[key];
            });
            keys = keys.slice(idx);
        }
    };
    return {
        set: function (key, value) {
            items[key] = value;
            keys.push(key);
            purge();
        },
        get: function (key) {
            return items[key];
        },
    };
};
exports.fifoCache = fifoCache;
var stripTags = function (html) {
    var tmp = document.createElement('div');
    tmp.innerHTML = html;
    return tmp.textContent || tmp.innerText || '';
};
exports.stripTags = stripTags;


/***/ }),

/***/ 5006:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

"use strict";
var zo=Object.create;var ct=Object.defineProperty;var Zo=Object.getOwnPropertyDescriptor;var er=Object.getOwnPropertyNames;var tr=Object.getPrototypeOf,nr=Object.prototype.hasOwnProperty;var or=(e,t,n)=>t in e?ct(e,t,{enumerable:!0,configurable:!0,writable:!0,value:n}):e[t]=n;var rr=(e,t)=>{for(var n in t)ct(e,n,{get:t[n],enumerable:!0})},Un=(e,t,n,o)=>{if(t&&typeof t=="object"||typeof t=="function")for(let l of er(t))!nr.call(e,l)&&l!==n&&ct(e,l,{get:()=>t[l],enumerable:!(o=Zo(t,l))||o.enumerable});return e};var oe=(e,t,n)=>(n=e!=null?zo(tr(e)):{},Un(t||!e||!e.__esModule?ct(n,"default",{value:e,enumerable:!0}):n,e)),ar=e=>Un(ct({},"__esModule",{value:!0}),e);var Ct=(e,t,n)=>(or(e,typeof t!="symbol"?t+"":t,n),n);var Xi={};rr(Xi,{Combobox:()=>_r,Dialog:()=>Ma,Disclosure:()=>Ka,FocusTrap:()=>Xe,Listbox:()=>pl,Menu:()=>Cl,Popover:()=>$l,Portal:()=>it,RadioGroup:()=>ai,Switch:()=>ci,Tab:()=>Ii,Transition:()=>Ki});module.exports=ar(Xi);var B=oe(__webpack_require__(7294),1);var Vn=__webpack_require__(7294);var At=__webpack_require__(7294);var Qt=class{constructor(){Ct(this,"current",this.detect());Ct(this,"handoffState","pending");Ct(this,"currentId",0)}set(t){this.current!==t&&(this.handoffState="pending",this.currentId=0,this.current=t)}reset(){this.set(this.detect())}nextId(){return++this.currentId}get isServer(){return this.current==="server"}get isClient(){return this.current==="client"}detect(){return typeof window=="undefined"||typeof document=="undefined"?"server":"client"}handoff(){this.handoffState==="pending"&&(this.handoffState="complete")}get isHandoffComplete(){return this.handoffState==="complete"}},Pe=new Qt;var w=(e,t)=>{Pe.isServer?(0,At.useEffect)(e,t):(0,At.useLayoutEffect)(e,t)};var Bn=__webpack_require__(7294);function z(e){let t=(0,Bn.useRef)(e);return w(()=>{t.current=e},[e]),t}function Be(e,t){let[n,o]=(0,Vn.useState)(e),l=z(e);return w(()=>o(l.current),[l,o,...t]),n}var Lt=__webpack_require__(7294);function we(e){typeof queueMicrotask=="function"?queueMicrotask(e):Promise.resolve().then(e).catch(t=>setTimeout(()=>{throw t}))}function ie(){let e=[],t={addEventListener(n,o,l,r){return n.addEventListener(o,l,r),t.add(()=>n.removeEventListener(o,l,r))},requestAnimationFrame(...n){let o=requestAnimationFrame(...n);return t.add(()=>cancelAnimationFrame(o))},nextFrame(...n){return t.requestAnimationFrame(()=>t.requestAnimationFrame(...n))},setTimeout(...n){let o=setTimeout(...n);return t.add(()=>clearTimeout(o))},microTask(...n){let o={current:!0};return we(()=>{o.current&&n[0]()}),t.add(()=>{o.current=!1})},style(n,o,l){let r=n.style.getPropertyValue(o);return Object.assign(n.style,{[o]:l}),this.add(()=>{Object.assign(n.style,{[o]:r})})},group(n){let o=ie();return n(o),this.add(()=>o.dispose())},add(n){return e.push(n),()=>{let o=e.indexOf(n);if(o>=0)for(let l of e.splice(o,1))l()}},dispose(){for(let n of e.splice(0))n()}};return t}function se(){let[e]=(0,Lt.useState)(ie);return(0,Lt.useEffect)(()=>()=>e.dispose(),[e]),e}var jn=oe(__webpack_require__(7294),1);var T=function(t){let n=z(t);return jn.default.useCallback((...o)=>n.current(...o),[n])};var zt=oe(__webpack_require__(7294),1);var ft=__webpack_require__(7294);function Ce(){let[e,t]=(0,ft.useState)(Pe.isHandoffComplete);return e&&Pe.isHandoffComplete===!1&&t(!1),(0,ft.useEffect)(()=>{e!==!0&&t(!0)},[e]),(0,ft.useEffect)(()=>Pe.handoff(),[]),e}var Wn,j=(Wn=zt.default.useId)!=null?Wn:function(){let t=Ce(),[n,o]=zt.default.useState(t?()=>Pe.nextId():null);return w(()=>{n===null&&o(Pe.nextId())},[n]),n!=null?""+n:void 0};var mt=__webpack_require__(7294);function M(e,t,...n){if(e in t){let l=t[e];return typeof l=="function"?l(...n):l}let o=new Error(`Tried to handle "${e}" but there is no handler defined. Only defined handlers are: ${Object.keys(t).map(l=>`"${l}"`).join(", ")}.`);throw Error.captureStackTrace&&Error.captureStackTrace(o,M),o}function me(e){return Pe.isServer?null:e instanceof Node?e.ownerDocument:e!=null&&e.hasOwnProperty("current")&&e.current instanceof Node?e.current.ownerDocument:document}var Zt=["[contentEditable=true]","[tabindex]","a[href]","area[href]","button:not([disabled])","iframe","input:not([disabled])","select:not([disabled])","textarea:not([disabled])"].map(e=>`${e}:not([tabindex='-1'])`).join(",");function Ye(e=document.body){return e==null?[]:Array.from(e.querySelectorAll(Zt)).sort((t,n)=>Math.sign((t.tabIndex||Number.MAX_SAFE_INTEGER)-(n.tabIndex||Number.MAX_SAFE_INTEGER)))}function _e(e,t=0){var n;return e===((n=me(e))==null?void 0:n.body)?!1:M(t,{[0](){return e.matches(Zt)},[1](){let o=e;for(;o!==null;){if(o.matches(Zt))return!0;o=o.parentElement}return!1}})}function en(e){let t=me(e);ie().nextFrame(()=>{t&&!_e(t.activeElement,0)&&He(e)})}typeof window!="undefined"&&typeof document!="undefined"&&(document.addEventListener("keydown",e=>{e.metaKey||e.altKey||e.ctrlKey||(document.documentElement.dataset.headlessuiFocusVisible="")},!0),document.addEventListener("click",e=>{e.detail===1?delete document.documentElement.dataset.headlessuiFocusVisible:e.detail===0&&(document.documentElement.dataset.headlessuiFocusVisible="")},!0));function He(e){e==null||e.focus({preventScroll:!0})}var lr=["textarea","input"].join(",");function ir(e){var t,n;return(n=(t=e==null?void 0:e.matches)==null?void 0:t.call(e,lr))!=null?n:!1}function ye(e,t=n=>n){return e.slice().sort((n,o)=>{let l=t(n),r=t(o);if(l===null||r===null)return 0;let a=l.compareDocumentPosition(r);return a&Node.DOCUMENT_POSITION_FOLLOWING?-1:a&Node.DOCUMENT_POSITION_PRECEDING?1:0})}function Kn(e,t){return le(Ye(),t,{relativeTo:e})}function le(e,t,{sorted:n=!0,relativeTo:o=null,skipElements:l=[]}={}){let r=Array.isArray(e)?e.length>0?e[0].ownerDocument:document:e.ownerDocument,a=Array.isArray(e)?n?ye(e):e:Ye(e);l.length>0&&a.length>1&&(a=a.filter(g=>!l.includes(g))),o=o!=null?o:r.activeElement;let i=(()=>{if(t&5)return 1;if(t&10)return-1;throw new Error("Missing Focus.First, Focus.Previous, Focus.Next or Focus.Last")})(),s=(()=>{if(t&1)return 0;if(t&2)return Math.max(0,a.indexOf(o))-1;if(t&4)return Math.max(0,a.indexOf(o))+1;if(t&8)return a.length-1;throw new Error("Missing Focus.First, Focus.Previous, Focus.Next or Focus.Last")})(),u=t&32?{preventScroll:!0}:{},m=0,p=a.length,y;do{if(m>=p||m+p<=0)return 0;let g=s+m;if(t&16)g=(g+p)%p;else{if(g<0)return 3;if(g>=p)return 1}y=a[g],y==null||y.focus(u),m+=i}while(y!==r.activeElement);return t&6&&ir(y)&&y.select(),2}var Xn=__webpack_require__(7294);function Tt(e,t,n){let o=z(t);(0,Xn.useEffect)(()=>{function l(r){o.current(r)}return document.addEventListener(e,l,n),()=>document.removeEventListener(e,l,n)},[e,n])}var $n=__webpack_require__(7294);function ht(e,t,n){let o=z(t);(0,$n.useEffect)(()=>{function l(r){o.current(r)}return window.addEventListener(e,l,n),()=>window.removeEventListener(e,l,n)},[e,n])}function De(e,t,n=!0){let o=(0,mt.useRef)(!1);(0,mt.useEffect)(()=>{requestAnimationFrame(()=>{o.current=n})},[n]);function l(a,i){if(!o.current||a.defaultPrevented)return;let s=i(a);if(s===null||!s.getRootNode().contains(s)||!s.isConnected)return;let u=function m(p){return typeof p=="function"?m(p()):Array.isArray(p)||p instanceof Set?p:[p]}(e);for(let m of u){if(m===null)continue;let p=m instanceof HTMLElement?m:m.current;if(p!=null&&p.contains(s)||a.composed&&a.composedPath().includes(p))return}return!_e(s,1)&&s.tabIndex!==-1&&a.preventDefault(),t(a,s)}let r=(0,mt.useRef)(null);Tt("pointerdown",a=>{var i,s;o.current&&(r.current=((s=(i=a.composedPath)==null?void 0:i.call(a))==null?void 0:s[0])||a.target)},!0),Tt("mousedown",a=>{var i,s;o.current&&(r.current=((s=(i=a.composedPath)==null?void 0:i.call(a))==null?void 0:s[0])||a.target)},!0),Tt("click",a=>{r.current&&(l(a,()=>r.current),r.current=null)},!0),Tt("touchend",a=>l(a,()=>a.target instanceof HTMLElement?a.target:null),!0),ht("blur",a=>l(a,()=>window.document.activeElement instanceof HTMLIFrameElement?window.document.activeElement:null),!0)}var qn=__webpack_require__(7294);function Jn(e){var n;if(e.type)return e.type;let t=(n=e.as)!=null?n:"button";if(typeof t=="string"&&t.toLowerCase()==="button")return"button"}function xe(e,t){let[n,o]=(0,qn.useState)(()=>Jn(e));return w(()=>{o(Jn(e))},[e.type,e.as]),w(()=>{n||t.current&&t.current instanceof HTMLButtonElement&&!t.current.hasAttribute("type")&&o("button")},[n,t]),n}var Dt=__webpack_require__(7294);var Yn=Symbol();function Qe(e,t=!0){return Object.assign(e,{[Yn]:t})}function I(...e){let t=(0,Dt.useRef)(e);(0,Dt.useEffect)(()=>{t.current=e},[e]);let n=T(o=>{for(let l of t.current)l!=null&&(typeof l=="function"?l(o):l.current=o)});return e.every(o=>o==null||(o==null?void 0:o[Yn]))?void 0:n}var bt=__webpack_require__(7294);function ze({container:e,accept:t,walk:n,enabled:o=!0}){let l=(0,bt.useRef)(t),r=(0,bt.useRef)(n);(0,bt.useEffect)(()=>{l.current=t,r.current=n},[t,n]),w(()=>{if(!e||!o)return;let a=me(e);if(!a)return;let i=l.current,s=r.current,u=Object.assign(p=>i(p),{acceptNode:i}),m=a.createTreeWalker(e,NodeFilter.SHOW_ELEMENT,u,!1);for(;m.nextNode();)s(m.currentNode)},[e,o,l,r])}function sr(e){throw new Error("Unexpected object: "+e)}function Ze(e,t){let n=t.resolveItems();if(n.length<=0)return null;let o=t.resolveActiveIndex(),l=o!=null?o:-1,r=(()=>{switch(e.focus){case 0:return n.findIndex(a=>!t.resolveDisabled(a));case 1:{let a=n.slice().reverse().findIndex((i,s,u)=>l!==-1&&u.length-s-1>=l?!1:!t.resolveDisabled(i));return a===-1?a:n.length-1-a}case 2:return n.findIndex((a,i)=>i<=l?!1:!t.resolveDisabled(a));case 3:{let a=n.slice().reverse().findIndex(i=>!t.resolveDisabled(i));return a===-1?a:n.length-1-a}case 4:return n.findIndex(a=>t.resolveId(a)===e.id);case 5:return null;default:sr(e)}})();return r===-1?o:r}var Re=__webpack_require__(7294);function yt(...e){return e.filter(Boolean).join(" ")}function L({ourProps:e,theirProps:t,slot:n,defaultTag:o,features:l,visible:r=!0,name:a}){let i=Qn(t,e);if(r)return Mt(i,n,o,a);let s=l!=null?l:0;if(s&2){let{static:u=!1,...m}=i;if(u)return Mt(m,n,o,a)}if(s&1){let{unmount:u=!0,...m}=i;return M(u?0:1,{[0](){return null},[1](){return Mt({...m,hidden:!0,style:{display:"none"}},n,o,a)}})}return Mt(i,n,o,a)}function Mt(e,t={},n,o){let{as:l=n,children:r,refName:a="ref",...i}=nn(e,["unmount","static"]),s=e.ref!==void 0?{[a]:e.ref}:{},u=typeof r=="function"?r(t):r;"className"in i&&i.className&&typeof i.className=="function"&&(i.className=i.className(t));let m={};if(t){let p=!1,y=[];for(let[g,d]of Object.entries(t))typeof d=="boolean"&&(p=!0),d===!0&&y.push(g);p&&(m["data-headlessui-state"]=y.join(" "))}if(l===Re.Fragment&&Object.keys(Me(i)).length>0){if(!(0,Re.isValidElement)(u)||Array.isArray(u)&&u.length>1)throw new Error(['Passing props on "Fragment"!',"",`The current component <${o} /> is rendering a "Fragment".`,"However we need to passthrough the following props:",Object.keys(i).map(d=>`  - ${d}`).join(`
`),"","You can apply a few solutions:",['Add an `as="..."` prop, to ensure that we render an actual element instead of a "Fragment".',"Render a single element as the child so that we can forward the props onto that element."].map(d=>`  - ${d}`).join(`
`)].join(`
`));let p=u.props,y=typeof(p==null?void 0:p.className)=="function"?(...d)=>yt(p==null?void 0:p.className(...d),i.className):yt(p==null?void 0:p.className,i.className),g=y?{className:y}:{};return(0,Re.cloneElement)(u,Object.assign({},Qn(u.props,Me(nn(i,["ref"]))),m,s,ur(u.ref,s.ref),g))}return(0,Re.createElement)(l,Object.assign({},nn(i,["ref"]),l!==Re.Fragment&&s,l!==Re.Fragment&&m),u)}function ur(...e){return{ref:e.every(t=>t==null)?void 0:t=>{for(let n of e)n!=null&&(typeof n=="function"?n(t):n.current=t)}}}function Qn(...e){var o;if(e.length===0)return{};if(e.length===1)return e[0];let t={},n={};for(let l of e)for(let r in l)r.startsWith("on")&&typeof l[r]=="function"?((o=n[r])!=null||(n[r]=[]),n[r].push(l[r])):t[r]=l[r];if(t.disabled||t["aria-disabled"])return Object.assign(t,Object.fromEntries(Object.keys(n).map(l=>[l,void 0])));for(let l in n)Object.assign(t,{[l](r,...a){let i=n[l];for(let s of i){if((r instanceof Event||(r==null?void 0:r.nativeEvent)instanceof Event)&&r.defaultPrevented)return;s(r,...a)}}});return t}function h(e){var t;return Object.assign((0,Re.forwardRef)(e),{displayName:(t=e.displayName)!=null?t:e.name})}function Me(e){let t=Object.assign({},e);for(let n in t)t[n]===void 0&&delete t[n];return t}function nn(e,t=[]){let n=Object.assign({},e);for(let o of t)o in n&&delete n[o];return n}function de(e){let t=e.parentElement,n=null;for(;t&&!(t instanceof HTMLFieldSetElement);)t instanceof HTMLLegendElement&&(n=t),t=t.parentElement;let o=(t==null?void 0:t.getAttribute("disabled"))==="";return o&&pr(n)?!1:o}function pr(e){if(!e)return!1;let t=e.previousElementSibling;for(;t!==null;){if(t instanceof HTMLLegendElement)return!1;t=t.previousElementSibling}return!0}function Ve(e={},t=null,n=[]){for(let[o,l]of Object.entries(e))Zn(n,zn(t,o),l);return n}function zn(e,t){return e?e+"["+t+"]":t}function Zn(e,t,n){if(Array.isArray(n))for(let[o,l]of n.entries())Zn(e,zn(t,o.toString()),l);else n instanceof Date?e.push([t,n.toISOString()]):typeof n=="boolean"?e.push([t,n?"1":"0"]):typeof n=="string"?e.push([t,n]):typeof n=="number"?e.push([t,`${n}`]):n==null?e.push([t,""]):Ve(n,t,e)}function Ft(e){var n;let t=(n=e==null?void 0:e.form)!=null?n:e.closest("form");if(t){for(let o of t.elements)if(o.tagName==="INPUT"&&o.type==="submit"||o.tagName==="BUTTON"&&o.type==="submit"||o.nodeName==="INPUT"&&o.type==="image"){o.click();return}t.requestSubmit()}}var dr="div";function cr(e,t){let{features:n=1,...o}=e,l={ref:t,"aria-hidden":(n&2)===2?!0:void 0,style:{position:"fixed",top:1,left:1,width:1,height:0,padding:0,margin:-1,overflow:"hidden",clip:"rect(0, 0, 0, 0)",whiteSpace:"nowrap",borderWidth:"0",...(n&4)===4&&(n&2)!==2&&{display:"none"}}};return L({ourProps:l,theirProps:o,slot:{},defaultTag:dr,name:"Hidden"})}var pe=h(cr);var et=oe(__webpack_require__(7294),1),on=(0,et.createContext)(null);on.displayName="OpenClosedContext";function be(){return(0,et.useContext)(on)}function Se({value:e,children:t}){return et.default.createElement(on.Provider,{value:e},t)}var tt=__webpack_require__(7294);function ke(e,t,n){let[o,l]=(0,tt.useState)(n),r=e!==void 0,a=(0,tt.useRef)(r),i=(0,tt.useRef)(!1),s=(0,tt.useRef)(!1);return r&&!a.current&&!i.current?(i.current=!0,a.current=r,console.error("A component is changing from uncontrolled to controlled. This may be caused by the value changing from undefined to a defined value, which should not happen.")):!r&&a.current&&!s.current&&(s.current=!0,a.current=r,console.error("A component is changing from controlled to uncontrolled. This may be caused by the value changing from a defined value to undefined, which should not happen.")),[r?e:o,T(u=>(r||l(u),t==null?void 0:t(u)))]}var It=__webpack_require__(7294);function je(e,t){let n=(0,It.useRef)([]),o=T(e);(0,It.useEffect)(()=>{let l=[...n.current];for(let[r,a]of t.entries())if(n.current[r]!==a){let i=o(t,l);return n.current=t,i}},[o,...t])}var to=__webpack_require__(7294);function eo(e){return[e.screenX,e.screenY]}function nt(){let e=(0,to.useRef)([-1,-1]);return{wasMoved(t){let n=eo(t);return e.current[0]===n[0]&&e.current[1]===n[1]?!1:(e.current=n,!0)},update(t){e.current=eo(t)}}}function rn(){return/iPhone/gi.test(window.navigator.platform)||/Mac/gi.test(window.navigator.platform)&&window.navigator.maxTouchPoints>0}function fr(){return/Android/gi.test(window.navigator.userAgent)}function no(){return rn()||fr()}function an(e,t=n=>n){let n=e.activeOptionIndex!==null?e.options[e.activeOptionIndex]:null,o=ye(t(e.options.slice()),r=>r.dataRef.current.domRef.current),l=n?o.indexOf(n):null;return l===-1&&(l=null),{options:o,activeOptionIndex:l}}var Tr={[1](e){var t;return(t=e.dataRef.current)!=null&&t.disabled||e.comboboxState===1?e:{...e,activeOptionIndex:null,comboboxState:1}},[0](e){var n;if((n=e.dataRef.current)!=null&&n.disabled||e.comboboxState===0)return e;let t=e.activeOptionIndex;if(e.dataRef.current){let{isSelected:o}=e.dataRef.current,l=e.options.findIndex(r=>o(r.dataRef.current.value));l!==-1&&(t=l)}return{...e,comboboxState:0,activeOptionIndex:t}},[2](e,t){var l,r,a,i;if((l=e.dataRef.current)!=null&&l.disabled||(r=e.dataRef.current)!=null&&r.optionsRef.current&&!((a=e.dataRef.current)!=null&&a.optionsPropsRef.current.static)&&e.comboboxState===1)return e;let n=an(e);if(n.activeOptionIndex===null){let s=n.options.findIndex(u=>!u.dataRef.current.disabled);s!==-1&&(n.activeOptionIndex=s)}let o=Ze(t,{resolveItems:()=>n.options,resolveActiveIndex:()=>n.activeOptionIndex,resolveId:s=>s.id,resolveDisabled:s=>s.dataRef.current.disabled});return{...e,...n,activeOptionIndex:o,activationTrigger:(i=t.trigger)!=null?i:1}},[3]:(e,t)=>{var r,a;let n={id:t.id,dataRef:t.dataRef},o=an(e,i=>[...i,n]);e.activeOptionIndex===null&&(r=e.dataRef.current)!=null&&r.isSelected(t.dataRef.current.value)&&(o.activeOptionIndex=o.options.indexOf(n));let l={...e,...o,activationTrigger:1};return(a=e.dataRef.current)!=null&&a.__demoMode&&e.dataRef.current.value===void 0&&(l.activeOptionIndex=0),l},[4]:(e,t)=>{let n=an(e,o=>{let l=o.findIndex(r=>r.id===t.id);return l!==-1&&o.splice(l,1),o});return{...e,...n,activationTrigger:1}},[5]:(e,t)=>({...e,labelId:t.id})},ln=(0,B.createContext)(null);ln.displayName="ComboboxActionsContext";function gt(e){let t=(0,B.useContext)(ln);if(t===null){let n=new Error(`<${e} /> is missing a parent <Combobox /> component.`);throw Error.captureStackTrace&&Error.captureStackTrace(n,gt),n}return t}var sn=(0,B.createContext)(null);sn.displayName="ComboboxDataContext";function rt(e){let t=(0,B.useContext)(sn);if(t===null){let n=new Error(`<${e} /> is missing a parent <Combobox /> component.`);throw Error.captureStackTrace&&Error.captureStackTrace(n,rt),n}return t}function mr(e,t){return M(t.type,Tr,e,t)}var br=B.Fragment;function yr(e,t){let{value:n,defaultValue:o,onChange:l,form:r,name:a,by:i=(S,F)=>S===F,disabled:s=!1,__demoMode:u=!1,nullable:m=!1,multiple:p=!1,...y}=e,[g=p?[]:void 0,d]=ke(n,l,o),[f,b]=(0,B.useReducer)(mr,{dataRef:(0,B.createRef)(),comboboxState:u?0:1,options:[],activeOptionIndex:null,activationTrigger:1,labelId:null}),c=(0,B.useRef)(!1),E=(0,B.useRef)({static:!1,hold:!1}),D=(0,B.useRef)(null),A=(0,B.useRef)(null),U=(0,B.useRef)(null),_=(0,B.useRef)(null),x=T(typeof i=="string"?(S,F)=>{let q=i;return(S==null?void 0:S[q])===(F==null?void 0:F[q])}:i),P=(0,B.useCallback)(S=>M(O.mode,{[1]:()=>g.some(F=>x(F,S)),[0]:()=>x(g,S)}),[g]),O=(0,B.useMemo)(()=>({...f,optionsPropsRef:E,labelRef:D,inputRef:A,buttonRef:U,optionsRef:_,value:g,defaultValue:o,disabled:s,mode:p?1:0,get activeOptionIndex(){if(c.current&&f.activeOptionIndex===null&&f.options.length>0){let S=f.options.findIndex(F=>!F.dataRef.current.disabled);if(S!==-1)return S}return f.activeOptionIndex},compare:x,isSelected:P,nullable:m,__demoMode:u}),[g,o,s,p,m,u,f]),v=(0,B.useRef)(O.activeOptionIndex!==null?O.options[O.activeOptionIndex]:null);(0,B.useEffect)(()=>{let S=O.activeOptionIndex!==null?O.options[O.activeOptionIndex]:null;v.current!==S&&(v.current=S)}),w(()=>{f.dataRef.current=O},[O]),De([O.buttonRef,O.inputRef,O.optionsRef],()=>Q.closeCombobox(),O.comboboxState===0);let R=(0,B.useMemo)(()=>({open:O.comboboxState===0,disabled:s,activeIndex:O.activeOptionIndex,activeOption:O.activeOptionIndex===null?null:O.options[O.activeOptionIndex].dataRef.current.value,value:g}),[O,s,g]),C=T(S=>{let F=O.options.find(q=>q.id===S);F&&H(F.dataRef.current.value)}),N=T(()=>{if(O.activeOptionIndex!==null){let{dataRef:S,id:F}=O.options[O.activeOptionIndex];H(S.current.value),Q.goToOption(4,F)}}),ee=T(()=>{b({type:0}),c.current=!0}),K=T(()=>{b({type:1}),c.current=!1}),G=T((S,F,q)=>(c.current=!1,S===4?b({type:2,focus:4,id:F,trigger:q}):b({type:2,focus:S,trigger:q}))),X=T((S,F)=>(b({type:3,id:S,dataRef:F}),()=>{var q;((q=v.current)==null?void 0:q.id)===S&&(c.current=!0),b({type:4,id:S})})),ce=T(S=>(b({type:5,id:S}),()=>b({type:5,id:null}))),H=T(S=>M(O.mode,{[0](){return d==null?void 0:d(S)},[1](){let F=O.value.slice(),q=F.findIndex(Ee=>x(Ee,S));return q===-1?F.push(S):F.splice(q,1),d==null?void 0:d(F)}})),Q=(0,B.useMemo)(()=>({onChange:H,registerOption:X,registerLabel:ce,goToOption:G,closeCombobox:K,openCombobox:ee,selectActiveOption:N,selectOption:C}),[]),J=t===null?{}:{ref:t},te=(0,B.useRef)(null),fe=se();return(0,B.useEffect)(()=>{te.current&&o!==void 0&&fe.addEventListener(te.current,"reset",()=>{H(o)})},[te,H]),B.default.createElement(ln.Provider,{value:Q},B.default.createElement(sn.Provider,{value:O},B.default.createElement(Se,{value:M(O.comboboxState,{[0]:1,[1]:2})},a!=null&&g!=null&&Ve({[a]:g}).map(([S,F],q)=>B.default.createElement(pe,{features:4,ref:q===0?Ee=>{var Ue;te.current=(Ue=Ee==null?void 0:Ee.closest("form"))!=null?Ue:null}:void 0,...Me({key:S,as:"input",type:"hidden",hidden:!0,readOnly:!0,form:r,name:S,value:F})})),L({ourProps:J,theirProps:y,slot:R,defaultTag:br,name:"Combobox"}))))}var gr="input";function Er(e,t){var x,P,O,v;let n=j(),{id:o=`headlessui-combobox-input-${n}`,onChange:l,displayValue:r,type:a="text",...i}=e,s=rt("Combobox.Input"),u=gt("Combobox.Input"),m=I(s.inputRef,t),p=(0,B.useRef)(!1),y=se(),g=function(){var R;return typeof r=="function"&&s.value!==void 0?(R=r(s.value))!=null?R:"":typeof s.value=="string"?s.value:""}();je(([R,C],[N,ee])=>{if(p.current)return;let K=s.inputRef.current;K&&((ee===0&&C===1||R!==N)&&(K.value=R),requestAnimationFrame(()=>{if(p.current||!K)return;let{selectionStart:G,selectionEnd:X}=K;Math.abs((X!=null?X:0)-(G!=null?G:0))===0&&G===0&&K.setSelectionRange(K.value.length,K.value.length)}))},[g,s.comboboxState]),je(([R],[C])=>{if(R===0&&C===1){if(p.current)return;let N=s.inputRef.current;if(!N)return;let ee=N.value,{selectionStart:K,selectionEnd:G,selectionDirection:X}=N;N.value="",N.value=ee,X!==null?N.setSelectionRange(K,G,X):N.setSelectionRange(K,G)}},[s.comboboxState]);let d=(0,B.useRef)(!1),f=T(()=>{d.current=!0}),b=T(()=>{y.nextFrame(()=>{d.current=!1})}),c=T(R=>{switch(p.current=!0,R.key){case"Backspace":case"Delete":if(s.mode!==0||!s.nullable)return;let C=R.currentTarget;y.requestAnimationFrame(()=>{C.value===""&&(u.onChange(null),s.optionsRef.current&&(s.optionsRef.current.scrollTop=0),u.goToOption(5))});break;case"Enter":if(p.current=!1,s.comboboxState!==0||d.current)return;if(R.preventDefault(),R.stopPropagation(),s.activeOptionIndex===null){u.closeCombobox();return}u.selectActiveOption(),s.mode===0&&u.closeCombobox();break;case"ArrowDown":return p.current=!1,R.preventDefault(),R.stopPropagation(),M(s.comboboxState,{[0]:()=>{u.goToOption(2)},[1]:()=>{u.openCombobox()}});case"ArrowUp":return p.current=!1,R.preventDefault(),R.stopPropagation(),M(s.comboboxState,{[0]:()=>{u.goToOption(1)},[1]:()=>{u.openCombobox(),y.nextFrame(()=>{s.value||u.goToOption(3)})}});case"Home":if(R.shiftKey)break;return p.current=!1,R.preventDefault(),R.stopPropagation(),u.goToOption(0);case"PageUp":return p.current=!1,R.preventDefault(),R.stopPropagation(),u.goToOption(0);case"End":if(R.shiftKey)break;return p.current=!1,R.preventDefault(),R.stopPropagation(),u.goToOption(3);case"PageDown":return p.current=!1,R.preventDefault(),R.stopPropagation(),u.goToOption(3);case"Escape":return p.current=!1,s.comboboxState!==0?void 0:(R.preventDefault(),s.optionsRef.current&&!s.optionsPropsRef.current.static&&R.stopPropagation(),u.closeCombobox());case"Tab":if(p.current=!1,s.comboboxState!==0)return;s.mode===0&&u.selectActiveOption(),u.closeCombobox();break}}),E=T(R=>{l==null||l(R),u.openCombobox()}),D=T(()=>{p.current=!1}),A=Be(()=>{if(s.labelId)return[s.labelId].join(" ")},[s.labelId]),U=(0,B.useMemo)(()=>({open:s.comboboxState===0,disabled:s.disabled}),[s]),_={ref:m,id:o,role:"combobox",type:a,"aria-controls":(x=s.optionsRef.current)==null?void 0:x.id,"aria-expanded":s.comboboxState===0,"aria-activedescendant":s.activeOptionIndex===null||(P=s.options[s.activeOptionIndex])==null?void 0:P.id,"aria-labelledby":A,"aria-autocomplete":"list",defaultValue:(v=(O=e.defaultValue)!=null?O:s.defaultValue!==void 0?r==null?void 0:r(s.defaultValue):null)!=null?v:s.defaultValue,disabled:s.disabled,onCompositionStart:f,onCompositionEnd:b,onKeyDown:c,onChange:E,onBlur:D};return L({ourProps:_,theirProps:i,slot:U,defaultTag:gr,name:"Combobox.Input"})}var Pr="button";function xr(e,t){var d;let n=rt("Combobox.Button"),o=gt("Combobox.Button"),l=I(n.buttonRef,t),r=j(),{id:a=`headlessui-combobox-button-${r}`,...i}=e,s=se(),u=T(f=>{switch(f.key){case"ArrowDown":return f.preventDefault(),f.stopPropagation(),n.comboboxState===1&&o.openCombobox(),s.nextFrame(()=>{var b;return(b=n.inputRef.current)==null?void 0:b.focus({preventScroll:!0})});case"ArrowUp":return f.preventDefault(),f.stopPropagation(),n.comboboxState===1&&(o.openCombobox(),s.nextFrame(()=>{n.value||o.goToOption(3)})),s.nextFrame(()=>{var b;return(b=n.inputRef.current)==null?void 0:b.focus({preventScroll:!0})});case"Escape":return n.comboboxState!==0?void 0:(f.preventDefault(),n.optionsRef.current&&!n.optionsPropsRef.current.static&&f.stopPropagation(),o.closeCombobox(),s.nextFrame(()=>{var b;return(b=n.inputRef.current)==null?void 0:b.focus({preventScroll:!0})}));default:return}}),m=T(f=>{if(de(f.currentTarget))return f.preventDefault();n.comboboxState===0?o.closeCombobox():(f.preventDefault(),o.openCombobox()),s.nextFrame(()=>{var b;return(b=n.inputRef.current)==null?void 0:b.focus({preventScroll:!0})})}),p=Be(()=>{if(n.labelId)return[n.labelId,a].join(" ")},[n.labelId,a]),y=(0,B.useMemo)(()=>({open:n.comboboxState===0,disabled:n.disabled,value:n.value}),[n]),g={ref:l,id:a,type:xe(e,n.buttonRef),tabIndex:-1,"aria-haspopup":"listbox","aria-controls":(d=n.optionsRef.current)==null?void 0:d.id,"aria-expanded":n.comboboxState===0,"aria-labelledby":p,disabled:n.disabled,onClick:m,onKeyDown:u};return L({ourProps:g,theirProps:i,slot:y,defaultTag:Pr,name:"Combobox.Button"})}var vr="label";function Rr(e,t){let n=j(),{id:o=`headlessui-combobox-label-${n}`,...l}=e,r=rt("Combobox.Label"),a=gt("Combobox.Label"),i=I(r.labelRef,t);w(()=>a.registerLabel(o),[o]);let s=T(()=>{var p;return(p=r.inputRef.current)==null?void 0:p.focus({preventScroll:!0})}),u=(0,B.useMemo)(()=>({open:r.comboboxState===0,disabled:r.disabled}),[r]);return L({ourProps:{ref:i,id:o,onClick:s},theirProps:l,slot:u,defaultTag:vr,name:"Combobox.Label"})}var Sr="ul",Or=3;function Cr(e,t){let n=j(),{id:o=`headlessui-combobox-options-${n}`,hold:l=!1,...r}=e,a=rt("Combobox.Options"),i=I(a.optionsRef,t),s=be(),u=(()=>s!==null?(s&1)===1:a.comboboxState===0)();w(()=>{var g;a.optionsPropsRef.current.static=(g=e.static)!=null?g:!1},[a.optionsPropsRef,e.static]),w(()=>{a.optionsPropsRef.current.hold=l},[a.optionsPropsRef,l]),ze({container:a.optionsRef.current,enabled:a.comboboxState===0,accept(g){return g.getAttribute("role")==="option"?NodeFilter.FILTER_REJECT:g.hasAttribute("role")?NodeFilter.FILTER_SKIP:NodeFilter.FILTER_ACCEPT},walk(g){g.setAttribute("role","none")}});let m=Be(()=>{var g,d;return(d=a.labelId)!=null?d:(g=a.buttonRef.current)==null?void 0:g.id},[a.labelId,a.buttonRef.current]),p=(0,B.useMemo)(()=>({open:a.comboboxState===0}),[a]),y={"aria-labelledby":m,role:"listbox","aria-multiselectable":a.mode===1?!0:void 0,id:o,ref:i};return L({ourProps:y,theirProps:r,slot:p,defaultTag:Sr,features:Or,visible:u,name:"Combobox.Options"})}var Ar="li";function Lr(e,t){var P,O;let n=j(),{id:o=`headlessui-combobox-option-${n}`,disabled:l=!1,value:r,...a}=e,i=rt("Combobox.Option"),s=gt("Combobox.Option"),u=i.activeOptionIndex!==null?i.options[i.activeOptionIndex].id===o:!1,m=i.isSelected(r),p=(0,B.useRef)(null),y=z({disabled:l,value:r,domRef:p,textValue:(O=(P=p.current)==null?void 0:P.textContent)==null?void 0:O.toLowerCase()}),g=I(t,p),d=T(()=>s.selectOption(o));w(()=>s.registerOption(o,y),[y,o]);let f=(0,B.useRef)(!i.__demoMode);w(()=>{if(!i.__demoMode)return;let v=ie();return v.requestAnimationFrame(()=>{f.current=!0}),v.dispose},[]),w(()=>{if(i.comboboxState!==0||!u||!f.current||i.activationTrigger===0)return;let v=ie();return v.requestAnimationFrame(()=>{var R,C;(C=(R=p.current)==null?void 0:R.scrollIntoView)==null||C.call(R,{block:"nearest"})}),v.dispose},[p,u,i.comboboxState,i.activationTrigger,i.activeOptionIndex]);let b=T(v=>{if(l)return v.preventDefault();d(),i.mode===0&&s.closeCombobox(),no()||requestAnimationFrame(()=>{var R;return(R=i.inputRef.current)==null?void 0:R.focus()})}),c=T(()=>{if(l)return s.goToOption(5);s.goToOption(4,o)}),E=nt(),D=T(v=>E.update(v)),A=T(v=>{E.wasMoved(v)&&(l||u||s.goToOption(4,o,0))}),U=T(v=>{E.wasMoved(v)&&(l||u&&(i.optionsPropsRef.current.hold||s.goToOption(5)))}),_=(0,B.useMemo)(()=>({active:u,selected:m,disabled:l}),[u,m,l]);return L({ourProps:{id:o,ref:g,role:"option",tabIndex:l===!0?void 0:-1,"aria-disabled":l===!0?!0:void 0,"aria-selected":m,disabled:void 0,onClick:b,onFocus:c,onPointerEnter:D,onMouseEnter:D,onPointerMove:A,onMouseMove:A,onPointerLeave:U,onMouseLeave:U},theirProps:a,slot:_,defaultTag:Ar,name:"Combobox.Option"})}var hr=h(yr),Dr=h(xr),Mr=h(Er),Fr=h(Rr),Ir=h(Cr),wr=h(Lr),_r=Object.assign(hr,{Input:Mr,Button:Dr,Label:Fr,Options:Ir,Option:wr});var W=oe(__webpack_require__(7294),1);var Fe=oe(__webpack_require__(7294),1);var ro=__webpack_require__(7294);function Et(){let e=(0,ro.useRef)(0);return ht("keydown",t=>{t.key==="Tab"&&(e.current=t.shiftKey?1:0)},!0),e}var ao=__webpack_require__(7294);function Ae(){let e=(0,ao.useRef)(!1);return w(()=>(e.current=!0,()=>{e.current=!1}),[]),e}var lo=__webpack_require__(7294);function ge(...e){return(0,lo.useMemo)(()=>me(...e),[...e])}var io=__webpack_require__(7294);function at(e,t,n,o){let l=z(n);(0,io.useEffect)(()=>{e=e!=null?e:window;function r(a){l.current(a)}return e.addEventListener(t,r,o),()=>e.removeEventListener(t,r,o)},[e,t,o])}function so(e){function t(){document.readyState!=="loading"&&(e(),document.removeEventListener("DOMContentLoaded",t))}typeof window!="undefined"&&typeof document!="undefined"&&(document.addEventListener("DOMContentLoaded",t),t())}var wt=__webpack_require__(7294);function _t(e){let t=T(e),n=(0,wt.useRef)(!1);(0,wt.useEffect)(()=>(n.current=!1,()=>{n.current=!0,we(()=>{n.current&&t()})}),[t])}function uo(e){if(!e)return new Set;if(typeof e=="function")return new Set(e());let t=new Set;for(let n of e.current)n.current instanceof HTMLElement&&t.add(n.current);return t}var kr="div",po=(a=>(a[a.None=1]="None",a[a.InitialFocus=2]="InitialFocus",a[a.TabLock=4]="TabLock",a[a.FocusLock=8]="FocusLock",a[a.RestoreFocus=16]="RestoreFocus",a[a.All=30]="All",a))(po||{});function Nr(e,t){let n=(0,Fe.useRef)(null),o=I(n,t),{initialFocus:l,containers:r,features:a=30,...i}=e;Ce()||(a=1);let s=ge(n);Br({ownerDocument:s},Boolean(a&16));let u=Vr({ownerDocument:s,container:n,initialFocus:l},Boolean(a&2));jr({ownerDocument:s,container:n,containers:r,previousActiveElement:u},Boolean(a&8));let m=Et(),p=T(f=>{let b=n.current;if(!b)return;(E=>E())(()=>{M(m.current,{[0]:()=>{le(b,1,{skipElements:[f.relatedTarget]})},[1]:()=>{le(b,8,{skipElements:[f.relatedTarget]})}})})}),y=se(),g=(0,Fe.useRef)(!1),d={ref:o,onKeyDown(f){f.key=="Tab"&&(g.current=!0,y.requestAnimationFrame(()=>{g.current=!1}))},onBlur(f){let b=uo(r);n.current instanceof HTMLElement&&b.add(n.current);let c=f.relatedTarget;c instanceof HTMLElement&&c.dataset.headlessuiFocusGuard!=="true"&&(co(b,c)||(g.current?le(n.current,M(m.current,{[0]:()=>4,[1]:()=>2})|16,{relativeTo:f.target}):f.target instanceof HTMLElement&&He(f.target)))}};return Fe.default.createElement(Fe.default.Fragment,null,Boolean(a&4)&&Fe.default.createElement(pe,{as:"button",type:"button","data-headlessui-focus-guard":!0,onFocus:p,features:2}),L({ourProps:d,theirProps:i,defaultTag:kr,name:"FocusTrap"}),Boolean(a&4)&&Fe.default.createElement(pe,{as:"button",type:"button","data-headlessui-focus-guard":!0,onFocus:p,features:2}))}var Gr=h(Nr),Xe=Object.assign(Gr,{features:po}),Ne=[];so(()=>{function e(t){t.target instanceof HTMLElement&&t.target!==document.body&&Ne[0]!==t.target&&(Ne.unshift(t.target),Ne=Ne.filter(n=>n!=null&&n.isConnected),Ne.splice(10))}window.addEventListener("click",e,{capture:!0}),window.addEventListener("mousedown",e,{capture:!0}),window.addEventListener("focus",e,{capture:!0}),document.body.addEventListener("click",e,{capture:!0}),document.body.addEventListener("mousedown",e,{capture:!0}),document.body.addEventListener("focus",e,{capture:!0})});function Ur(e=!0){let t=(0,Fe.useRef)(Ne.slice());return je(([n],[o])=>{o===!0&&n===!1&&we(()=>{t.current.splice(0)}),o===!1&&n===!0&&(t.current=Ne.slice())},[e,Ne,t]),T(()=>{var n;return(n=t.current.find(o=>o!=null&&o.isConnected))!=null?n:null})}function Br({ownerDocument:e},t){let n=Ur(t);je(()=>{t||(e==null?void 0:e.activeElement)===(e==null?void 0:e.body)&&He(n())},[t]),_t(()=>{t&&He(n())})}function Vr({ownerDocument:e,container:t,initialFocus:n},o){let l=(0,Fe.useRef)(null),r=Ae();return je(()=>{if(!o)return;let a=t.current;a&&we(()=>{if(!r.current)return;let i=e==null?void 0:e.activeElement;if(n!=null&&n.current){if((n==null?void 0:n.current)===i){l.current=i;return}}else if(a.contains(i)){l.current=i;return}n!=null&&n.current?He(n.current):le(a,1)===0&&console.warn("There are no focusable elements inside the <FocusTrap />"),l.current=e==null?void 0:e.activeElement})},[o]),l}function jr({ownerDocument:e,container:t,containers:n,previousActiveElement:o},l){let r=Ae();at(e==null?void 0:e.defaultView,"focus",a=>{if(!l||!r.current)return;let i=uo(n);t.current instanceof HTMLElement&&i.add(t.current);let s=o.current;if(!s)return;let u=a.target;u&&u instanceof HTMLElement?co(i,u)?(o.current=u,He(u)):(a.preventDefault(),a.stopPropagation(),He(s)):He(o.current)},!0)}function co(e,t){for(let n of e)if(n.contains(t))return!0;return!1}var re=oe(__webpack_require__(7294),1),mo=__webpack_require__(3935);var lt=oe(__webpack_require__(7294),1),fo=(0,lt.createContext)(!1);function To(){return(0,lt.useContext)(fo)}function kt(e){return lt.default.createElement(fo.Provider,{value:e.force},e.children)}function Wr(e){let t=To(),n=(0,re.useContext)(bo),o=ge(e),[l,r]=(0,re.useState)(()=>{if(!t&&n!==null||Pe.isServer)return null;let a=o==null?void 0:o.getElementById("headlessui-portal-root");if(a)return a;if(o===null)return null;let i=o.createElement("div");return i.setAttribute("id","headlessui-portal-root"),o.body.appendChild(i)});return(0,re.useEffect)(()=>{l!==null&&(o!=null&&o.body.contains(l)||o==null||o.body.appendChild(l))},[l,o]),(0,re.useEffect)(()=>{t||n!==null&&r(n.current)},[n,r,t]),l}var Kr=re.Fragment;function Xr(e,t){let n=e,o=(0,re.useRef)(null),l=I(Qe(p=>{o.current=p}),t),r=ge(o),a=Wr(o),[i]=(0,re.useState)(()=>{var p;return Pe.isServer?null:(p=r==null?void 0:r.createElement("div"))!=null?p:null}),s=(0,re.useContext)(pn),u=Ce();return w(()=>{!a||!i||a.contains(i)||(i.setAttribute("data-headlessui-portal",""),a.appendChild(i))},[a,i]),w(()=>{if(i&&s)return s.register(i)},[s,i]),_t(()=>{var p;!a||!i||(i instanceof Node&&a.contains(i)&&a.removeChild(i),a.childNodes.length<=0&&((p=a.parentElement)==null||p.removeChild(a)))}),u?!a||!i?null:(0,mo.createPortal)(L({ourProps:{ref:l},theirProps:n,defaultTag:Kr,name:"Portal"}),i):null}var $r=re.Fragment,bo=(0,re.createContext)(null);function Jr(e,t){let{target:n,...o}=e,r={ref:I(t)};return re.default.createElement(bo.Provider,{value:n},L({ourProps:r,theirProps:o,defaultTag:$r,name:"Popover.Group"}))}var pn=(0,re.createContext)(null);function Nt(){let e=(0,re.useContext)(pn),t=(0,re.useRef)([]),n=T(r=>(t.current.push(r),e&&e.register(r),()=>o(r))),o=T(r=>{let a=t.current.indexOf(r);a!==-1&&t.current.splice(a,1),e&&e.unregister(r)}),l=(0,re.useMemo)(()=>({register:n,unregister:o,portals:t}),[n,o,t]);return[t,(0,re.useMemo)(()=>function({children:a}){return re.default.createElement(pn.Provider,{value:l},a)},[l])]}var qr=h(Xr),Yr=h(Jr),it=Object.assign(qr,{Group:Yr});var Le=oe(__webpack_require__(7294),1);var yo=(0,Le.createContext)(null);function go(){let e=(0,Le.useContext)(yo);if(e===null){let t=new Error("You used a <Description /> component, but it is not inside a relevant parent.");throw Error.captureStackTrace&&Error.captureStackTrace(t,go),t}return e}function $e(){let[e,t]=(0,Le.useState)([]);return[e.length>0?e.join(" "):void 0,(0,Le.useMemo)(()=>function(o){let l=T(a=>(t(i=>[...i,a]),()=>t(i=>{let s=i.slice(),u=s.indexOf(a);return u!==-1&&s.splice(u,1),s}))),r=(0,Le.useMemo)(()=>({register:l,slot:o.slot,name:o.name,props:o.props}),[l,o.slot,o.name,o.props]);return Le.default.createElement(yo.Provider,{value:r},o.children)},[t])]}var Qr="p";function zr(e,t){let n=j(),{id:o=`headlessui-description-${n}`,...l}=e,r=go(),a=I(t);w(()=>r.register(o),[o,r.register]);let i={ref:a,...r.props,id:o};return L({ourProps:i,theirProps:l,slot:r.slot||{},defaultTag:Qr,name:r.name||"Description"})}var Zr=h(zr),st=Object.assign(Zr,{});var ut=oe(__webpack_require__(7294),1);var dn=(0,ut.createContext)(()=>{});dn.displayName="StackContext";function ea(){return(0,ut.useContext)(dn)}function Eo({children:e,onUpdate:t,type:n,element:o,enabled:l}){let r=ea(),a=T((...i)=>{t==null||t(...i),r(...i)});return w(()=>{let i=l===void 0||l===!0;return i&&a(0,n,o),()=>{i&&a(1,n,o)}},[a,n,o,l]),ut.default.createElement(dn.Provider,{value:a},e)}var vo=oe(__webpack_require__(7294),1);var ta=oe(__webpack_require__(7294),1);function na(e,t){return e===t&&(e!==0||1/e===1/t)||e!==e&&t!==t}var oa=typeof Object.is=="function"?Object.is:na,{useState:ra,useEffect:aa,useLayoutEffect:la,useDebugValue:ia}=ta;function Po(e,t,n){let o=t(),[{inst:l},r]=ra({inst:{value:o,getSnapshot:t}});return la(()=>{l.value=o,l.getSnapshot=t,cn(l)&&r({inst:l})},[e,o,t]),aa(()=>(cn(l)&&r({inst:l}),e(()=>{cn(l)&&r({inst:l})})),[e]),ia(o),o}function cn(e){let t=e.getSnapshot,n=e.value;try{let o=t();return!oa(n,o)}catch{return!0}}function xo(e,t,n){return t()}var sa=typeof window!="undefined"&&typeof window.document!="undefined"&&typeof window.document.createElement!="undefined",ua=!sa,pa=ua?xo:Po,Ro="useSyncExternalStore"in vo?(e=>e.useSyncExternalStore)(vo):pa;function So(e){return Ro(e.subscribe,e.getSnapshot,e.getSnapshot)}function Oo(e,t){let n=e(),o=new Set;return{getSnapshot(){return n},subscribe(l){return o.add(l),()=>o.delete(l)},dispatch(l,...r){let a=t[l].call(n,...r);a&&(n=a,o.forEach(i=>i()))}}}function Co(){let e;return{before({doc:t}){var l;let n=t.documentElement;e=((l=t.defaultView)!=null?l:window).innerWidth-n.clientWidth},after({doc:t,d:n}){let o=t.documentElement,l=o.clientWidth-o.offsetWidth,r=e-l;n.style(o,"paddingRight",`${r}px`)}}}function Ao(){if(!rn())return{};let e;return{before(){e=window.pageYOffset},after({doc:t,d:n,meta:o}){function l(a){return o.containers.flatMap(i=>i()).some(i=>i.contains(a))}n.style(t.body,"marginTop",`-${e}px`),window.scrollTo(0,0);let r=null;n.addEventListener(t,"click",a=>{if(a.target instanceof HTMLElement)try{let i=a.target.closest("a");if(!i)return;let{hash:s}=new URL(i.href),u=t.querySelector(s);u&&!l(u)&&(r=u)}catch{}},!0),n.addEventListener(t,"touchmove",a=>{a.target instanceof HTMLElement&&!l(a.target)&&a.preventDefault()},{passive:!1}),n.add(()=>{window.scrollTo(0,window.pageYOffset+e),r&&r.isConnected&&(r.scrollIntoView({block:"nearest"}),r=null)})}}}function Lo(){return{before({doc:e,d:t}){t.style(e.documentElement,"overflow","hidden")}}}function da(e){let t={};for(let n of e)Object.assign(t,n(t));return t}var Ge=Oo(()=>new Map,{PUSH(e,t){var o;let n=(o=this.get(e))!=null?o:{doc:e,count:0,d:ie(),meta:new Set};return n.count++,n.meta.add(t),this.set(e,n),this},POP(e,t){let n=this.get(e);return n&&(n.count--,n.meta.delete(t)),this},SCROLL_PREVENT({doc:e,d:t,meta:n}){let o={doc:e,d:t,meta:da(n)},l=[Ao(),Co(),Lo()];l.forEach(({before:r})=>r==null?void 0:r(o)),l.forEach(({after:r})=>r==null?void 0:r(o))},SCROLL_ALLOW({d:e}){e.dispose()},TEARDOWN({doc:e}){this.delete(e)}});Ge.subscribe(()=>{let e=Ge.getSnapshot(),t=new Map;for(let[n]of e)t.set(n,n.documentElement.style.overflow);for(let n of e.values()){let o=t.get(n.doc)==="hidden",l=n.count!==0;(l&&!o||!l&&o)&&Ge.dispatch(n.count>0?"SCROLL_PREVENT":"SCROLL_ALLOW",n),n.count===0&&Ge.dispatch("TEARDOWN",n)}});function ho(e,t,n){let o=So(Ge),l=e?o.get(e):void 0,r=l?l.count>0:!1;return w(()=>{if(!(!e||!t))return Ge.dispatch("PUSH",e,n),()=>Ge.dispatch("POP",e,n)},[t,e]),r}var fn=new Map,Pt=new Map;function Tn(e,t=!0){w(()=>{var r;if(!t)return;let n=typeof e=="function"?e():e.current;if(!n)return;function o(){var s;if(!n)return;let a=(s=Pt.get(n))!=null?s:1;if(a===1?Pt.delete(n):Pt.set(n,a-1),a!==1)return;let i=fn.get(n);i&&(i["aria-hidden"]===null?n.removeAttribute("aria-hidden"):n.setAttribute("aria-hidden",i["aria-hidden"]),n.inert=i.inert,fn.delete(n))}let l=(r=Pt.get(n))!=null?r:0;return Pt.set(n,l+1),l!==0||(fn.set(n,{"aria-hidden":n.getAttribute("aria-hidden"),inert:n.inert}),n.setAttribute("aria-hidden","true"),n.inert=!0),o},[e,t])}var pt=oe(__webpack_require__(7294),1);function Gt({defaultContainers:e=[],portals:t}={}){let n=(0,pt.useRef)(null),o=ge(n),l=T(()=>{var a;let r=[];for(let i of e)i!==null&&(i instanceof HTMLElement?r.push(i):"current"in i&&i.current instanceof HTMLElement&&r.push(i.current));if(t!=null&&t.current)for(let i of t.current)r.push(i);for(let i of(a=o==null?void 0:o.querySelectorAll("html > *, body > *"))!=null?a:[])i!==document.body&&i!==document.head&&i instanceof HTMLElement&&i.id!=="headlessui-portal-root"&&(i.contains(n.current)||r.some(s=>i.contains(s))||r.push(i));return r});return{resolveContainers:l,contains:T(r=>l().some(a=>a.contains(r))),mainTreeNodeRef:n,MainTreeNode:(0,pt.useMemo)(()=>function(){return pt.default.createElement(pe,{features:4,ref:n})},[n])}}var ca={[0](e,t){return e.titleId===t.id?e:{...e,titleId:t.id}}},Ut=(0,W.createContext)(null);Ut.displayName="DialogContext";function xt(e){let t=(0,W.useContext)(Ut);if(t===null){let n=new Error(`<${e} /> is missing a parent <Dialog /> component.`);throw Error.captureStackTrace&&Error.captureStackTrace(n,xt),n}return t}function fa(e,t,n=()=>[document.body]){ho(e,t,o=>{var l;return{containers:[...(l=o.containers)!=null?l:[],n]}})}function Ta(e,t){return M(t.type,ca,e,t)}var ma="div",ba=3;function ya(e,t){var Gn;let n=j(),{id:o=`headlessui-dialog-${n}`,open:l,onClose:r,initialFocus:a,__demoMode:i=!1,...s}=e,[u,m]=(0,W.useState)(0),p=be();l===void 0&&p!==null&&(l=(p&1)===1);let y=(0,W.useRef)(null),g=I(y,t),d=ge(y),f=e.hasOwnProperty("open")||p!==null,b=e.hasOwnProperty("onClose");if(!f&&!b)throw new Error("You have to provide an `open` and an `onClose` prop to the `Dialog` component.");if(!f)throw new Error("You provided an `onClose` prop to the `Dialog`, but forgot an `open` prop.");if(!b)throw new Error("You provided an `open` prop to the `Dialog`, but forgot an `onClose` prop.");if(typeof l!="boolean")throw new Error(`You provided an \`open\` prop to the \`Dialog\`, but the value is not a boolean. Received: ${l}`);if(typeof r!="function")throw new Error(`You provided an \`onClose\` prop to the \`Dialog\`, but the value is not a function. Received: ${r}`);let c=l?0:1,[E,D]=(0,W.useReducer)(Ta,{titleId:null,descriptionId:null,panelRef:(0,W.createRef)()}),A=T(()=>r(!1)),U=T(Te=>D({type:0,id:Te})),x=Ce()?i?!1:c===0:!1,P=u>1,O=(0,W.useContext)(Ut)!==null,[v,R]=Nt(),{resolveContainers:C,mainTreeNodeRef:N,MainTreeNode:ee}=Gt({portals:v,defaultContainers:[(Gn=E.panelRef.current)!=null?Gn:y.current]}),K=P?"parent":"leaf",G=p!==null?(p&4)===4:!1,X=(()=>O||G?!1:x)(),ce=(0,W.useCallback)(()=>{var Te,Ie;return(Ie=Array.from((Te=d==null?void 0:d.querySelectorAll("body > *"))!=null?Te:[]).find(ve=>ve.id==="headlessui-portal-root"?!1:ve.contains(N.current)&&ve instanceof HTMLElement))!=null?Ie:null},[N]);Tn(ce,X);let H=(()=>P?!0:x)(),Q=(0,W.useCallback)(()=>{var Te,Ie;return(Ie=Array.from((Te=d==null?void 0:d.querySelectorAll("[data-headlessui-portal]"))!=null?Te:[]).find(ve=>ve.contains(N.current)&&ve instanceof HTMLElement))!=null?Ie:null},[N]);Tn(Q,H);let J=(()=>!(!x||P))();De(C,A,J);let te=(()=>!(P||c!==0))();at(d==null?void 0:d.defaultView,"keydown",Te=>{te&&(Te.defaultPrevented||Te.key==="Escape"&&(Te.preventDefault(),Te.stopPropagation(),A()))});let fe=(()=>!(G||c!==0||O))();fa(d,fe,C),(0,W.useEffect)(()=>{if(c!==0||!y.current)return;let Te=new ResizeObserver(Ie=>{for(let ve of Ie){let Ot=ve.target.getBoundingClientRect();Ot.x===0&&Ot.y===0&&Ot.width===0&&Ot.height===0&&A()}});return Te.observe(y.current),()=>Te.disconnect()},[c,y,A]);let[S,F]=$e(),q=(0,W.useMemo)(()=>[{dialogState:c,close:A,setTitleId:U},E],[c,E,A,U]),Ee=(0,W.useMemo)(()=>({open:c===0}),[c]),Ue={ref:g,id:o,role:"dialog","aria-modal":c===0?!0:void 0,"aria-labelledby":E.titleId,"aria-describedby":S};return W.default.createElement(Eo,{type:"Dialog",enabled:c===0,element:y,onUpdate:T((Te,Ie)=>{Ie==="Dialog"&&M(Te,{[0]:()=>m(ve=>ve+1),[1]:()=>m(ve=>ve-1)})})},W.default.createElement(kt,{force:!0},W.default.createElement(it,null,W.default.createElement(Ut.Provider,{value:q},W.default.createElement(it.Group,{target:y},W.default.createElement(kt,{force:!1},W.default.createElement(F,{slot:Ee,name:"Dialog.Description"},W.default.createElement(Xe,{initialFocus:a,containers:C,features:x?M(K,{parent:Xe.features.RestoreFocus,leaf:Xe.features.All&~Xe.features.FocusLock}):Xe.features.None},W.default.createElement(R,null,L({ourProps:Ue,theirProps:s,slot:Ee,defaultTag:ma,features:ba,visible:c===0,name:"Dialog"}))))))))),W.default.createElement(ee,null))}var ga="div";function Ea(e,t){let n=j(),{id:o=`headlessui-dialog-overlay-${n}`,...l}=e,[{dialogState:r,close:a}]=xt("Dialog.Overlay"),i=I(t),s=T(p=>{if(p.target===p.currentTarget){if(de(p.currentTarget))return p.preventDefault();p.preventDefault(),p.stopPropagation(),a()}}),u=(0,W.useMemo)(()=>({open:r===0}),[r]);return L({ourProps:{ref:i,id:o,"aria-hidden":!0,onClick:s},theirProps:l,slot:u,defaultTag:ga,name:"Dialog.Overlay"})}var Pa="div";function xa(e,t){let n=j(),{id:o=`headlessui-dialog-backdrop-${n}`,...l}=e,[{dialogState:r},a]=xt("Dialog.Backdrop"),i=I(t);(0,W.useEffect)(()=>{if(a.panelRef.current===null)throw new Error("A <Dialog.Backdrop /> component is being used, but a <Dialog.Panel /> component is missing.")},[a.panelRef]);let s=(0,W.useMemo)(()=>({open:r===0}),[r]);return W.default.createElement(kt,{force:!0},W.default.createElement(it,null,L({ourProps:{ref:i,id:o,"aria-hidden":!0},theirProps:l,slot:s,defaultTag:Pa,name:"Dialog.Backdrop"})))}var va="div";function Ra(e,t){let n=j(),{id:o=`headlessui-dialog-panel-${n}`,...l}=e,[{dialogState:r},a]=xt("Dialog.Panel"),i=I(t,a.panelRef),s=(0,W.useMemo)(()=>({open:r===0}),[r]),u=T(p=>{p.stopPropagation()});return L({ourProps:{ref:i,id:o,onClick:u},theirProps:l,slot:s,defaultTag:va,name:"Dialog.Panel"})}var Sa="h2";function Oa(e,t){let n=j(),{id:o=`headlessui-dialog-title-${n}`,...l}=e,[{dialogState:r,setTitleId:a}]=xt("Dialog.Title"),i=I(t);(0,W.useEffect)(()=>(a(o),()=>a(null)),[o,a]);let s=(0,W.useMemo)(()=>({open:r===0}),[r]);return L({ourProps:{ref:i,id:o},theirProps:l,slot:s,defaultTag:Sa,name:"Dialog.Title"})}var Ca=h(ya),Aa=h(xa),La=h(Ra),ha=h(Ea),Da=h(Oa),Ma=Object.assign(Ca,{Backdrop:Aa,Panel:La,Overlay:ha,Title:Da,Description:st});var Y=oe(__webpack_require__(7294),1);var Mo=oe(__webpack_require__(7294),1),Do,Fo=(Do=Mo.default.startTransition)!=null?Do:function(t){t()};var Fa={[0]:e=>({...e,disclosureState:M(e.disclosureState,{[0]:1,[1]:0})}),[1]:e=>e.disclosureState===1?e:{...e,disclosureState:1},[4](e){return e.linkedPanel===!0?e:{...e,linkedPanel:!0}},[5](e){return e.linkedPanel===!1?e:{...e,linkedPanel:!1}},[2](e,t){return e.buttonId===t.buttonId?e:{...e,buttonId:t.buttonId}},[3](e,t){return e.panelId===t.panelId?e:{...e,panelId:t.panelId}}},mn=(0,Y.createContext)(null);mn.displayName="DisclosureContext";function bn(e){let t=(0,Y.useContext)(mn);if(t===null){let n=new Error(`<${e} /> is missing a parent <Disclosure /> component.`);throw Error.captureStackTrace&&Error.captureStackTrace(n,bn),n}return t}var yn=(0,Y.createContext)(null);yn.displayName="DisclosureAPIContext";function Io(e){let t=(0,Y.useContext)(yn);if(t===null){let n=new Error(`<${e} /> is missing a parent <Disclosure /> component.`);throw Error.captureStackTrace&&Error.captureStackTrace(n,Io),n}return t}var gn=(0,Y.createContext)(null);gn.displayName="DisclosurePanelContext";function Ia(){return(0,Y.useContext)(gn)}function wa(e,t){return M(t.type,Fa,e,t)}var _a=Y.Fragment;function Ha(e,t){let{defaultOpen:n=!1,...o}=e,l=(0,Y.useRef)(null),r=I(t,Qe(b=>{l.current=b},e.as===void 0||e.as===Y.Fragment)),a=(0,Y.useRef)(null),i=(0,Y.useRef)(null),s=(0,Y.useReducer)(wa,{disclosureState:n?0:1,linkedPanel:!1,buttonRef:i,panelRef:a,buttonId:null,panelId:null}),[{disclosureState:u,buttonId:m},p]=s,y=T(b=>{p({type:1});let c=me(l);if(!c||!m)return;let E=(()=>b?b instanceof HTMLElement?b:b.current instanceof HTMLElement?b.current:c.getElementById(m):c.getElementById(m))();E==null||E.focus()}),g=(0,Y.useMemo)(()=>({close:y}),[y]),d=(0,Y.useMemo)(()=>({open:u===0,close:y}),[u,y]),f={ref:r};return Y.default.createElement(mn.Provider,{value:s},Y.default.createElement(yn.Provider,{value:g},Y.default.createElement(Se,{value:M(u,{[0]:1,[1]:2})},L({ourProps:f,theirProps:o,slot:d,defaultTag:_a,name:"Disclosure"}))))}var ka="button";function Na(e,t){let n=j(),{id:o=`headlessui-disclosure-button-${n}`,...l}=e,[r,a]=bn("Disclosure.Button"),i=Ia(),s=i===null?!1:i===r.panelId,u=(0,Y.useRef)(null),m=I(u,t,s?null:r.buttonRef);(0,Y.useEffect)(()=>{if(!s)return a({type:2,buttonId:o}),()=>{a({type:2,buttonId:null})}},[o,a,s]);let p=T(c=>{var E;if(s){if(r.disclosureState===1)return;switch(c.key){case" ":case"Enter":c.preventDefault(),c.stopPropagation(),a({type:0}),(E=r.buttonRef.current)==null||E.focus();break}}else switch(c.key){case" ":case"Enter":c.preventDefault(),c.stopPropagation(),a({type:0});break}}),y=T(c=>{switch(c.key){case" ":c.preventDefault();break}}),g=T(c=>{var E;de(c.currentTarget)||e.disabled||(s?(a({type:0}),(E=r.buttonRef.current)==null||E.focus()):a({type:0}))}),d=(0,Y.useMemo)(()=>({open:r.disclosureState===0}),[r]),f=xe(e,u),b=s?{ref:m,type:f,onKeyDown:p,onClick:g}:{ref:m,id:o,type:f,"aria-expanded":r.disclosureState===0,"aria-controls":r.linkedPanel?r.panelId:void 0,onKeyDown:p,onKeyUp:y,onClick:g};return L({ourProps:b,theirProps:l,slot:d,defaultTag:ka,name:"Disclosure.Button"})}var Ga="div",Ua=3;function Ba(e,t){let n=j(),{id:o=`headlessui-disclosure-panel-${n}`,...l}=e,[r,a]=bn("Disclosure.Panel"),{close:i}=Io("Disclosure.Panel"),s=I(t,r.panelRef,g=>{Fo(()=>a({type:g?4:5}))});(0,Y.useEffect)(()=>(a({type:3,panelId:o}),()=>{a({type:3,panelId:null})}),[o,a]);let u=be(),m=(()=>u!==null?(u&1)===1:r.disclosureState===0)(),p=(0,Y.useMemo)(()=>({open:r.disclosureState===0,close:i}),[r,i]),y={ref:s,id:o};return Y.default.createElement(gn.Provider,{value:r.panelId},L({ourProps:y,theirProps:l,slot:p,defaultTag:Ga,features:Ua,visible:m,name:"Disclosure.Panel"}))}var Va=h(Ha),ja=h(Na),Wa=h(Ba),Ka=Object.assign(Va,{Button:ja,Panel:Wa});var $=oe(__webpack_require__(7294),1);var En=__webpack_require__(7294);var wo=/([\u2700-\u27BF]|[\uE000-\uF8FF]|\uD83C[\uDC00-\uDFFF]|\uD83D[\uDC00-\uDFFF]|[\u2011-\u26FF]|\uD83E[\uDD10-\uDDFF])/g;function _o(e){var r,a;let t=(r=e.innerText)!=null?r:"",n=e.cloneNode(!0);if(!(n instanceof HTMLElement))return t;let o=!1;for(let i of n.querySelectorAll('[hidden],[aria-hidden],[role="img"]'))i.remove(),o=!0;let l=o?(a=n.innerText)!=null?a:"":t;return wo.test(l)&&(l=l.replace(wo,"")),l}function Ho(e){let t=e.getAttribute("aria-label");if(typeof t=="string")return t.trim();let n=e.getAttribute("aria-labelledby");if(n){let o=n.split(" ").map(l=>{let r=document.getElementById(l);if(r){let a=r.getAttribute("aria-label");return typeof a=="string"?a.trim():_o(r).trim()}return null}).filter(Boolean);if(o.length>0)return o.join(", ")}return _o(e).trim()}function Bt(e){let t=(0,En.useRef)(""),n=(0,En.useRef)("");return T(()=>{let o=e.current;if(!o)return"";let l=o.innerText;if(t.current===l)return n.current;let r=Ho(o).trim().toLowerCase();return t.current=l,n.current=r,r})}function Pn(e,t=n=>n){let n=e.activeOptionIndex!==null?e.options[e.activeOptionIndex]:null,o=ye(t(e.options.slice()),r=>r.dataRef.current.domRef.current),l=n?o.indexOf(n):null;return l===-1&&(l=null),{options:o,activeOptionIndex:l}}var Xa={[1](e){return e.dataRef.current.disabled||e.listboxState===1?e:{...e,activeOptionIndex:null,listboxState:1}},[0](e){if(e.dataRef.current.disabled||e.listboxState===0)return e;let t=e.activeOptionIndex,{isSelected:n}=e.dataRef.current,o=e.options.findIndex(l=>n(l.dataRef.current.value));return o!==-1&&(t=o),{...e,listboxState:0,activeOptionIndex:t}},[2](e,t){var l;if(e.dataRef.current.disabled||e.listboxState===1)return e;let n=Pn(e),o=Ze(t,{resolveItems:()=>n.options,resolveActiveIndex:()=>n.activeOptionIndex,resolveId:r=>r.id,resolveDisabled:r=>r.dataRef.current.disabled});return{...e,...n,searchQuery:"",activeOptionIndex:o,activationTrigger:(l=t.trigger)!=null?l:1}},[3]:(e,t)=>{if(e.dataRef.current.disabled||e.listboxState===1)return e;let o=e.searchQuery!==""?0:1,l=e.searchQuery+t.value.toLowerCase(),a=(e.activeOptionIndex!==null?e.options.slice(e.activeOptionIndex+o).concat(e.options.slice(0,e.activeOptionIndex+o)):e.options).find(s=>{var u;return!s.dataRef.current.disabled&&((u=s.dataRef.current.textValue)==null?void 0:u.startsWith(l))}),i=a?e.options.indexOf(a):-1;return i===-1||i===e.activeOptionIndex?{...e,searchQuery:l}:{...e,searchQuery:l,activeOptionIndex:i,activationTrigger:1}},[4](e){return e.dataRef.current.disabled||e.listboxState===1||e.searchQuery===""?e:{...e,searchQuery:""}},[5]:(e,t)=>{let n={id:t.id,dataRef:t.dataRef},o=Pn(e,l=>[...l,n]);return e.activeOptionIndex===null&&e.dataRef.current.isSelected(t.dataRef.current.value)&&(o.activeOptionIndex=o.options.indexOf(n)),{...e,...o}},[6]:(e,t)=>{let n=Pn(e,o=>{let l=o.findIndex(r=>r.id===t.id);return l!==-1&&o.splice(l,1),o});return{...e,...n,activationTrigger:1}},[7]:(e,t)=>({...e,labelId:t.id})},xn=(0,$.createContext)(null);xn.displayName="ListboxActionsContext";function vt(e){let t=(0,$.useContext)(xn);if(t===null){let n=new Error(`<${e} /> is missing a parent <Listbox /> component.`);throw Error.captureStackTrace&&Error.captureStackTrace(n,vt),n}return t}var vn=(0,$.createContext)(null);vn.displayName="ListboxDataContext";function Rt(e){let t=(0,$.useContext)(vn);if(t===null){let n=new Error(`<${e} /> is missing a parent <Listbox /> component.`);throw Error.captureStackTrace&&Error.captureStackTrace(n,Rt),n}return t}function $a(e,t){return M(t.type,Xa,e,t)}var Ja=$.Fragment;function qa(e,t){let{value:n,defaultValue:o,form:l,name:r,onChange:a,by:i=(S,F)=>S===F,disabled:s=!1,horizontal:u=!1,multiple:m=!1,...p}=e,y=u?"horizontal":"vertical",g=I(t),[d=m?[]:void 0,f]=ke(n,a,o),[b,c]=(0,$.useReducer)($a,{dataRef:(0,$.createRef)(),listboxState:1,options:[],searchQuery:"",labelId:null,activeOptionIndex:null,activationTrigger:1}),E=(0,$.useRef)({static:!1,hold:!1}),D=(0,$.useRef)(null),A=(0,$.useRef)(null),U=(0,$.useRef)(null),_=T(typeof i=="string"?(S,F)=>{let q=i;return(S==null?void 0:S[q])===(F==null?void 0:F[q])}:i),x=(0,$.useCallback)(S=>M(P.mode,{[1]:()=>d.some(F=>_(F,S)),[0]:()=>_(d,S)}),[d]),P=(0,$.useMemo)(()=>({...b,value:d,disabled:s,mode:m?1:0,orientation:y,compare:_,isSelected:x,optionsPropsRef:E,labelRef:D,buttonRef:A,optionsRef:U}),[d,s,m,b]);w(()=>{b.dataRef.current=P},[P]),De([P.buttonRef,P.optionsRef],(S,F)=>{var q;c({type:1}),_e(F,1)||(S.preventDefault(),(q=P.buttonRef.current)==null||q.focus())},P.listboxState===0);let O=(0,$.useMemo)(()=>({open:P.listboxState===0,disabled:s,value:d}),[P,s,d]),v=T(S=>{let F=P.options.find(q=>q.id===S);F&&X(F.dataRef.current.value)}),R=T(()=>{if(P.activeOptionIndex!==null){let{dataRef:S,id:F}=P.options[P.activeOptionIndex];X(S.current.value),c({type:2,focus:4,id:F})}}),C=T(()=>c({type:0})),N=T(()=>c({type:1})),ee=T((S,F,q)=>S===4?c({type:2,focus:4,id:F,trigger:q}):c({type:2,focus:S,trigger:q})),K=T((S,F)=>(c({type:5,id:S,dataRef:F}),()=>c({type:6,id:S}))),G=T(S=>(c({type:7,id:S}),()=>c({type:7,id:null}))),X=T(S=>M(P.mode,{[0](){return f==null?void 0:f(S)},[1](){let F=P.value.slice(),q=F.findIndex(Ee=>_(Ee,S));return q===-1?F.push(S):F.splice(q,1),f==null?void 0:f(F)}})),ce=T(S=>c({type:3,value:S})),H=T(()=>c({type:4})),Q=(0,$.useMemo)(()=>({onChange:X,registerOption:K,registerLabel:G,goToOption:ee,closeListbox:N,openListbox:C,selectActiveOption:R,selectOption:v,search:ce,clearSearch:H}),[]),J={ref:g},te=(0,$.useRef)(null),fe=se();return(0,$.useEffect)(()=>{te.current&&o!==void 0&&fe.addEventListener(te.current,"reset",()=>{X(o)})},[te,X]),$.default.createElement(xn.Provider,{value:Q},$.default.createElement(vn.Provider,{value:P},$.default.createElement(Se,{value:M(P.listboxState,{[0]:1,[1]:2})},r!=null&&d!=null&&Ve({[r]:d}).map(([S,F],q)=>$.default.createElement(pe,{features:4,ref:q===0?Ee=>{var Ue;te.current=(Ue=Ee==null?void 0:Ee.closest("form"))!=null?Ue:null}:void 0,...Me({key:S,as:"input",type:"hidden",hidden:!0,readOnly:!0,form:l,name:S,value:F})})),L({ourProps:J,theirProps:p,slot:O,defaultTag:Ja,name:"Listbox"}))))}var Ya="button";function Qa(e,t){var f;let n=j(),{id:o=`headlessui-listbox-button-${n}`,...l}=e,r=Rt("Listbox.Button"),a=vt("Listbox.Button"),i=I(r.buttonRef,t),s=se(),u=T(b=>{switch(b.key){case" ":case"Enter":case"ArrowDown":b.preventDefault(),a.openListbox(),s.nextFrame(()=>{r.value||a.goToOption(0)});break;case"ArrowUp":b.preventDefault(),a.openListbox(),s.nextFrame(()=>{r.value||a.goToOption(3)});break}}),m=T(b=>{switch(b.key){case" ":b.preventDefault();break}}),p=T(b=>{if(de(b.currentTarget))return b.preventDefault();r.listboxState===0?(a.closeListbox(),s.nextFrame(()=>{var c;return(c=r.buttonRef.current)==null?void 0:c.focus({preventScroll:!0})})):(b.preventDefault(),a.openListbox())}),y=Be(()=>{if(r.labelId)return[r.labelId,o].join(" ")},[r.labelId,o]),g=(0,$.useMemo)(()=>({open:r.listboxState===0,disabled:r.disabled,value:r.value}),[r]),d={ref:i,id:o,type:xe(e,r.buttonRef),"aria-haspopup":"listbox","aria-controls":(f=r.optionsRef.current)==null?void 0:f.id,"aria-expanded":r.listboxState===0,"aria-labelledby":y,disabled:r.disabled,onKeyDown:u,onKeyUp:m,onClick:p};return L({ourProps:d,theirProps:l,slot:g,defaultTag:Ya,name:"Listbox.Button"})}var za="label";function Za(e,t){let n=j(),{id:o=`headlessui-listbox-label-${n}`,...l}=e,r=Rt("Listbox.Label"),a=vt("Listbox.Label"),i=I(r.labelRef,t);w(()=>a.registerLabel(o),[o]);let s=T(()=>{var p;return(p=r.buttonRef.current)==null?void 0:p.focus({preventScroll:!0})}),u=(0,$.useMemo)(()=>({open:r.listboxState===0,disabled:r.disabled}),[r]);return L({ourProps:{ref:i,id:o,onClick:s},theirProps:l,slot:u,defaultTag:za,name:"Listbox.Label"})}var el="ul",tl=3;function nl(e,t){var b;let n=j(),{id:o=`headlessui-listbox-options-${n}`,...l}=e,r=Rt("Listbox.Options"),a=vt("Listbox.Options"),i=I(r.optionsRef,t),s=se(),u=se(),m=be(),p=(()=>m!==null?(m&1)===1:r.listboxState===0)();(0,$.useEffect)(()=>{var E;let c=r.optionsRef.current;c&&r.listboxState===0&&c!==((E=me(c))==null?void 0:E.activeElement)&&c.focus({preventScroll:!0})},[r.listboxState,r.optionsRef]);let y=T(c=>{switch(u.dispose(),c.key){case" ":if(r.searchQuery!=="")return c.preventDefault(),c.stopPropagation(),a.search(c.key);case"Enter":if(c.preventDefault(),c.stopPropagation(),r.activeOptionIndex!==null){let{dataRef:E}=r.options[r.activeOptionIndex];a.onChange(E.current.value)}r.mode===0&&(a.closeListbox(),ie().nextFrame(()=>{var E;return(E=r.buttonRef.current)==null?void 0:E.focus({preventScroll:!0})}));break;case M(r.orientation,{vertical:"ArrowDown",horizontal:"ArrowRight"}):return c.preventDefault(),c.stopPropagation(),a.goToOption(2);case M(r.orientation,{vertical:"ArrowUp",horizontal:"ArrowLeft"}):return c.preventDefault(),c.stopPropagation(),a.goToOption(1);case"Home":case"PageUp":return c.preventDefault(),c.stopPropagation(),a.goToOption(0);case"End":case"PageDown":return c.preventDefault(),c.stopPropagation(),a.goToOption(3);case"Escape":return c.preventDefault(),c.stopPropagation(),a.closeListbox(),s.nextFrame(()=>{var E;return(E=r.buttonRef.current)==null?void 0:E.focus({preventScroll:!0})});case"Tab":c.preventDefault(),c.stopPropagation();break;default:c.key.length===1&&(a.search(c.key),u.setTimeout(()=>a.clearSearch(),350));break}}),g=Be(()=>{var c,E,D;return(D=(c=r.labelRef.current)==null?void 0:c.id)!=null?D:(E=r.buttonRef.current)==null?void 0:E.id},[r.labelRef.current,r.buttonRef.current]),d=(0,$.useMemo)(()=>({open:r.listboxState===0}),[r]),f={"aria-activedescendant":r.activeOptionIndex===null||(b=r.options[r.activeOptionIndex])==null?void 0:b.id,"aria-multiselectable":r.mode===1?!0:void 0,"aria-labelledby":g,"aria-orientation":r.orientation,id:o,onKeyDown:y,role:"listbox",tabIndex:0,ref:i};return L({ourProps:f,theirProps:l,slot:d,defaultTag:el,features:tl,visible:p,name:"Listbox.Options"})}var ol="li";function rl(e,t){let n=j(),{id:o=`headlessui-listbox-option-${n}`,disabled:l=!1,value:r,...a}=e,i=Rt("Listbox.Option"),s=vt("Listbox.Option"),u=i.activeOptionIndex!==null?i.options[i.activeOptionIndex].id===o:!1,m=i.isSelected(r),p=(0,$.useRef)(null),y=Bt(p),g=z({disabled:l,value:r,domRef:p,get textValue(){return y()}}),d=I(t,p);w(()=>{if(i.listboxState!==0||!u||i.activationTrigger===0)return;let x=ie();return x.requestAnimationFrame(()=>{var P,O;(O=(P=p.current)==null?void 0:P.scrollIntoView)==null||O.call(P,{block:"nearest"})}),x.dispose},[p,u,i.listboxState,i.activationTrigger,i.activeOptionIndex]),w(()=>s.registerOption(o,g),[g,o]);let f=T(x=>{if(l)return x.preventDefault();s.onChange(r),i.mode===0&&(s.closeListbox(),ie().nextFrame(()=>{var P;return(P=i.buttonRef.current)==null?void 0:P.focus({preventScroll:!0})}))}),b=T(()=>{if(l)return s.goToOption(5);s.goToOption(4,o)}),c=nt(),E=T(x=>c.update(x)),D=T(x=>{c.wasMoved(x)&&(l||u||s.goToOption(4,o,0))}),A=T(x=>{c.wasMoved(x)&&(l||u&&s.goToOption(5))}),U=(0,$.useMemo)(()=>({active:u,selected:m,disabled:l}),[u,m,l]);return L({ourProps:{id:o,ref:d,role:"option",tabIndex:l===!0?void 0:-1,"aria-disabled":l===!0?!0:void 0,"aria-selected":m,disabled:void 0,onClick:f,onFocus:b,onPointerEnter:E,onMouseEnter:E,onPointerMove:D,onMouseMove:D,onPointerLeave:A,onMouseLeave:A},theirProps:a,slot:U,defaultTag:ol,name:"Listbox.Option"})}var al=h(qa),ll=h(Qa),il=h(Za),sl=h(nl),ul=h(rl),pl=Object.assign(al,{Button:ll,Label:il,Options:sl,Option:ul});var ae=oe(__webpack_require__(7294),1);function Rn(e,t=n=>n){let n=e.activeItemIndex!==null?e.items[e.activeItemIndex]:null,o=ye(t(e.items.slice()),r=>r.dataRef.current.domRef.current),l=n?o.indexOf(n):null;return l===-1&&(l=null),{items:o,activeItemIndex:l}}var dl={[1](e){return e.menuState===1?e:{...e,activeItemIndex:null,menuState:1}},[0](e){return e.menuState===0?e:{...e,__demoMode:!1,menuState:0}},[2]:(e,t)=>{var l;let n=Rn(e),o=Ze(t,{resolveItems:()=>n.items,resolveActiveIndex:()=>n.activeItemIndex,resolveId:r=>r.id,resolveDisabled:r=>r.dataRef.current.disabled});return{...e,...n,searchQuery:"",activeItemIndex:o,activationTrigger:(l=t.trigger)!=null?l:1}},[3]:(e,t)=>{let o=e.searchQuery!==""?0:1,l=e.searchQuery+t.value.toLowerCase(),a=(e.activeItemIndex!==null?e.items.slice(e.activeItemIndex+o).concat(e.items.slice(0,e.activeItemIndex+o)):e.items).find(s=>{var u;return((u=s.dataRef.current.textValue)==null?void 0:u.startsWith(l))&&!s.dataRef.current.disabled}),i=a?e.items.indexOf(a):-1;return i===-1||i===e.activeItemIndex?{...e,searchQuery:l}:{...e,searchQuery:l,activeItemIndex:i,activationTrigger:1}},[4](e){return e.searchQuery===""?e:{...e,searchQuery:"",searchActiveItemIndex:null}},[5]:(e,t)=>{let n=Rn(e,o=>[...o,{id:t.id,dataRef:t.dataRef}]);return{...e,...n}},[6]:(e,t)=>{let n=Rn(e,o=>{let l=o.findIndex(r=>r.id===t.id);return l!==-1&&o.splice(l,1),o});return{...e,...n,activationTrigger:1}}},Sn=(0,ae.createContext)(null);Sn.displayName="MenuContext";function Vt(e){let t=(0,ae.useContext)(Sn);if(t===null){let n=new Error(`<${e} /> is missing a parent <Menu /> component.`);throw Error.captureStackTrace&&Error.captureStackTrace(n,Vt),n}return t}function cl(e,t){return M(t.type,dl,e,t)}var fl=ae.Fragment;function Tl(e,t){let{__demoMode:n=!1,...o}=e,l=(0,ae.useReducer)(cl,{__demoMode:n,menuState:n?0:1,buttonRef:(0,ae.createRef)(),itemsRef:(0,ae.createRef)(),items:[],searchQuery:"",activeItemIndex:null,activationTrigger:1}),[{menuState:r,itemsRef:a,buttonRef:i},s]=l,u=I(t);De([i,a],(g,d)=>{var f;s({type:1}),_e(d,1)||(g.preventDefault(),(f=i.current)==null||f.focus())},r===0);let m=T(()=>{s({type:1})}),p=(0,ae.useMemo)(()=>({open:r===0,close:m}),[r,m]),y={ref:u};return ae.default.createElement(Sn.Provider,{value:l},ae.default.createElement(Se,{value:M(r,{[0]:1,[1]:2})},L({ourProps:y,theirProps:o,slot:p,defaultTag:fl,name:"Menu"})))}var ml="button";function bl(e,t){var d;let n=j(),{id:o=`headlessui-menu-button-${n}`,...l}=e,[r,a]=Vt("Menu.Button"),i=I(r.buttonRef,t),s=se(),u=T(f=>{switch(f.key){case" ":case"Enter":case"ArrowDown":f.preventDefault(),f.stopPropagation(),a({type:0}),s.nextFrame(()=>a({type:2,focus:0}));break;case"ArrowUp":f.preventDefault(),f.stopPropagation(),a({type:0}),s.nextFrame(()=>a({type:2,focus:3}));break}}),m=T(f=>{switch(f.key){case" ":f.preventDefault();break}}),p=T(f=>{if(de(f.currentTarget))return f.preventDefault();e.disabled||(r.menuState===0?(a({type:1}),s.nextFrame(()=>{var b;return(b=r.buttonRef.current)==null?void 0:b.focus({preventScroll:!0})})):(f.preventDefault(),a({type:0})))}),y=(0,ae.useMemo)(()=>({open:r.menuState===0}),[r]),g={ref:i,id:o,type:xe(e,r.buttonRef),"aria-haspopup":"menu","aria-controls":(d=r.itemsRef.current)==null?void 0:d.id,"aria-expanded":r.menuState===0,onKeyDown:u,onKeyUp:m,onClick:p};return L({ourProps:g,theirProps:l,slot:y,defaultTag:ml,name:"Menu.Button"})}var yl="div",gl=3;function El(e,t){var b,c;let n=j(),{id:o=`headlessui-menu-items-${n}`,...l}=e,[r,a]=Vt("Menu.Items"),i=I(r.itemsRef,t),s=ge(r.itemsRef),u=se(),m=be(),p=(()=>m!==null?(m&1)===1:r.menuState===0)();(0,ae.useEffect)(()=>{let E=r.itemsRef.current;E&&r.menuState===0&&E!==(s==null?void 0:s.activeElement)&&E.focus({preventScroll:!0})},[r.menuState,r.itemsRef,s]),ze({container:r.itemsRef.current,enabled:r.menuState===0,accept(E){return E.getAttribute("role")==="menuitem"?NodeFilter.FILTER_REJECT:E.hasAttribute("role")?NodeFilter.FILTER_SKIP:NodeFilter.FILTER_ACCEPT},walk(E){E.setAttribute("role","none")}});let y=T(E=>{var D,A;switch(u.dispose(),E.key){case" ":if(r.searchQuery!=="")return E.preventDefault(),E.stopPropagation(),a({type:3,value:E.key});case"Enter":if(E.preventDefault(),E.stopPropagation(),a({type:1}),r.activeItemIndex!==null){let{dataRef:U}=r.items[r.activeItemIndex];(A=(D=U.current)==null?void 0:D.domRef.current)==null||A.click()}en(r.buttonRef.current);break;case"ArrowDown":return E.preventDefault(),E.stopPropagation(),a({type:2,focus:2});case"ArrowUp":return E.preventDefault(),E.stopPropagation(),a({type:2,focus:1});case"Home":case"PageUp":return E.preventDefault(),E.stopPropagation(),a({type:2,focus:0});case"End":case"PageDown":return E.preventDefault(),E.stopPropagation(),a({type:2,focus:3});case"Escape":E.preventDefault(),E.stopPropagation(),a({type:1}),ie().nextFrame(()=>{var U;return(U=r.buttonRef.current)==null?void 0:U.focus({preventScroll:!0})});break;case"Tab":E.preventDefault(),E.stopPropagation(),a({type:1}),ie().nextFrame(()=>{Kn(r.buttonRef.current,E.shiftKey?2:4)});break;default:E.key.length===1&&(a({type:3,value:E.key}),u.setTimeout(()=>a({type:4}),350));break}}),g=T(E=>{switch(E.key){case" ":E.preventDefault();break}}),d=(0,ae.useMemo)(()=>({open:r.menuState===0}),[r]),f={"aria-activedescendant":r.activeItemIndex===null||(b=r.items[r.activeItemIndex])==null?void 0:b.id,"aria-labelledby":(c=r.buttonRef.current)==null?void 0:c.id,id:o,onKeyDown:y,onKeyUp:g,role:"menu",tabIndex:0,ref:i};return L({ourProps:f,theirProps:l,slot:d,defaultTag:yl,features:gl,visible:p,name:"Menu.Items"})}var Pl=ae.Fragment;function xl(e,t){let n=j(),{id:o=`headlessui-menu-item-${n}`,disabled:l=!1,...r}=e,[a,i]=Vt("Menu.Item"),s=a.activeItemIndex!==null?a.items[a.activeItemIndex].id===o:!1,u=(0,ae.useRef)(null),m=I(t,u);w(()=>{if(a.__demoMode||a.menuState!==0||!s||a.activationTrigger===0)return;let _=ie();return _.requestAnimationFrame(()=>{var x,P;(P=(x=u.current)==null?void 0:x.scrollIntoView)==null||P.call(x,{block:"nearest"})}),_.dispose},[a.__demoMode,u,s,a.menuState,a.activationTrigger,a.activeItemIndex]);let p=Bt(u),y=(0,ae.useRef)({disabled:l,domRef:u,get textValue(){return p()}});w(()=>{y.current.disabled=l},[y,l]),w(()=>(i({type:5,id:o,dataRef:y}),()=>i({type:6,id:o})),[y,o]);let g=T(()=>{i({type:1})}),d=T(_=>{if(l)return _.preventDefault();i({type:1}),en(a.buttonRef.current)}),f=T(()=>{if(l)return i({type:2,focus:5});i({type:2,focus:4,id:o})}),b=nt(),c=T(_=>b.update(_)),E=T(_=>{b.wasMoved(_)&&(l||s||i({type:2,focus:4,id:o,trigger:0}))}),D=T(_=>{b.wasMoved(_)&&(l||s&&i({type:2,focus:5}))}),A=(0,ae.useMemo)(()=>({active:s,disabled:l,close:g}),[s,l,g]);return L({ourProps:{id:o,ref:m,role:"menuitem",tabIndex:l===!0?void 0:-1,"aria-disabled":l===!0?!0:void 0,disabled:void 0,onClick:d,onFocus:f,onPointerEnter:c,onMouseEnter:c,onPointerMove:E,onMouseMove:E,onPointerLeave:D,onMouseLeave:D},theirProps:r,slot:A,defaultTag:Pl,name:"Menu.Item"})}var vl=h(Tl),Rl=h(bl),Sl=h(El),Ol=h(xl),Cl=Object.assign(vl,{Button:Rl,Items:Sl,Item:Ol});var k=oe(__webpack_require__(7294),1);var Al={[0]:e=>{let t={...e,popoverState:M(e.popoverState,{[0]:1,[1]:0})};return t.popoverState===0&&(t.__demoMode=!1),t},[1](e){return e.popoverState===1?e:{...e,popoverState:1}},[2](e,t){return e.button===t.button?e:{...e,button:t.button}},[3](e,t){return e.buttonId===t.buttonId?e:{...e,buttonId:t.buttonId}},[4](e,t){return e.panel===t.panel?e:{...e,panel:t.panel}},[5](e,t){return e.panelId===t.panelId?e:{...e,panelId:t.panelId}}},On=(0,k.createContext)(null);On.displayName="PopoverContext";function jt(e){let t=(0,k.useContext)(On);if(t===null){let n=new Error(`<${e} /> is missing a parent <Popover /> component.`);throw Error.captureStackTrace&&Error.captureStackTrace(n,jt),n}return t}var Cn=(0,k.createContext)(null);Cn.displayName="PopoverAPIContext";function An(e){let t=(0,k.useContext)(Cn);if(t===null){let n=new Error(`<${e} /> is missing a parent <Popover /> component.`);throw Error.captureStackTrace&&Error.captureStackTrace(n,An),n}return t}var Ln=(0,k.createContext)(null);Ln.displayName="PopoverGroupContext";function ko(){return(0,k.useContext)(Ln)}var Wt=(0,k.createContext)(null);Wt.displayName="PopoverPanelContext";function Ll(){return(0,k.useContext)(Wt)}function hl(e,t){return M(t.type,Al,e,t)}var Dl="div";function Ml(e,t){var K;let{__demoMode:n=!1,...o}=e,l=(0,k.useRef)(null),r=I(t,Qe(G=>{l.current=G})),a=(0,k.useRef)([]),i=(0,k.useReducer)(hl,{__demoMode:n,popoverState:n?0:1,buttons:a,button:null,buttonId:null,panel:null,panelId:null,beforePanelSentinel:(0,k.createRef)(),afterPanelSentinel:(0,k.createRef)()}),[{popoverState:s,button:u,buttonId:m,panel:p,panelId:y,beforePanelSentinel:g,afterPanelSentinel:d},f]=i,b=ge((K=l.current)!=null?K:u),c=(0,k.useMemo)(()=>{if(!u||!p)return!1;for(let te of document.querySelectorAll("body > *"))if(Number(te==null?void 0:te.contains(u))^Number(te==null?void 0:te.contains(p)))return!0;let G=Ye(),X=G.indexOf(u),ce=(X+G.length-1)%G.length,H=(X+1)%G.length,Q=G[ce],J=G[H];return!p.contains(Q)&&!p.contains(J)},[u,p]),E=z(m),D=z(y),A=(0,k.useMemo)(()=>({buttonId:E,panelId:D,close:()=>f({type:1})}),[E,D,f]),U=ko(),_=U==null?void 0:U.registerPopover,x=T(()=>{var G;return(G=U==null?void 0:U.isFocusWithinPopoverGroup())!=null?G:(b==null?void 0:b.activeElement)&&((u==null?void 0:u.contains(b.activeElement))||(p==null?void 0:p.contains(b.activeElement)))});(0,k.useEffect)(()=>_==null?void 0:_(A),[_,A]);let[P,O]=Nt(),v=Gt({portals:P,defaultContainers:[u,p]});at(b==null?void 0:b.defaultView,"focus",G=>{var X,ce,H,Q;G.target!==window&&G.target instanceof HTMLElement&&s===0&&(x()||u&&p&&(v.contains(G.target)||(ce=(X=g.current)==null?void 0:X.contains)!=null&&ce.call(X,G.target)||(Q=(H=d.current)==null?void 0:H.contains)!=null&&Q.call(H,G.target)||f({type:1})))},!0),De(v.resolveContainers,(G,X)=>{f({type:1}),_e(X,1)||(G.preventDefault(),u==null||u.focus())},s===0);let R=T(G=>{f({type:1});let X=(()=>G?G instanceof HTMLElement?G:"current"in G&&G.current instanceof HTMLElement?G.current:u:u)();X==null||X.focus()}),C=(0,k.useMemo)(()=>({close:R,isPortalled:c}),[R,c]),N=(0,k.useMemo)(()=>({open:s===0,close:R}),[s,R]),ee={ref:r};return k.default.createElement(Wt.Provider,{value:null},k.default.createElement(On.Provider,{value:i},k.default.createElement(Cn.Provider,{value:C},k.default.createElement(Se,{value:M(s,{[0]:1,[1]:2})},k.default.createElement(O,null,L({ourProps:ee,theirProps:o,slot:N,defaultTag:Dl,name:"Popover"}),k.default.createElement(v.MainTreeNode,null))))))}var Fl="button";function Il(e,t){let n=j(),{id:o=`headlessui-popover-button-${n}`,...l}=e,[r,a]=jt("Popover.Button"),{isPortalled:i}=An("Popover.Button"),s=(0,k.useRef)(null),u=`headlessui-focus-sentinel-${j()}`,m=ko(),p=m==null?void 0:m.closeOthers,g=Ll()!==null;(0,k.useEffect)(()=>{if(!g)return a({type:3,buttonId:o}),()=>{a({type:3,buttonId:null})}},[g,o,a]);let[d]=(0,k.useState)(()=>Symbol()),f=I(s,t,g?null:C=>{if(C)r.buttons.current.push(d);else{let N=r.buttons.current.indexOf(d);N!==-1&&r.buttons.current.splice(N,1)}r.buttons.current.length>1&&console.warn("You are already using a <Popover.Button /> but only 1 <Popover.Button /> is supported."),C&&a({type:2,button:C})}),b=I(s,t),c=ge(s),E=T(C=>{var N,ee,K;if(g){if(r.popoverState===1)return;switch(C.key){case" ":case"Enter":C.preventDefault(),(ee=(N=C.target).click)==null||ee.call(N),a({type:1}),(K=r.button)==null||K.focus();break}}else switch(C.key){case" ":case"Enter":C.preventDefault(),C.stopPropagation(),r.popoverState===1&&(p==null||p(r.buttonId)),a({type:0});break;case"Escape":if(r.popoverState!==0)return p==null?void 0:p(r.buttonId);if(!s.current||c!=null&&c.activeElement&&!s.current.contains(c.activeElement))return;C.preventDefault(),C.stopPropagation(),a({type:1});break}}),D=T(C=>{g||C.key===" "&&C.preventDefault()}),A=T(C=>{var N,ee;de(C.currentTarget)||e.disabled||(g?(a({type:1}),(N=r.button)==null||N.focus()):(C.preventDefault(),C.stopPropagation(),r.popoverState===1&&(p==null||p(r.buttonId)),a({type:0}),(ee=r.button)==null||ee.focus()))}),U=T(C=>{C.preventDefault(),C.stopPropagation()}),_=r.popoverState===0,x=(0,k.useMemo)(()=>({open:_}),[_]),P=xe(e,s),O=g?{ref:b,type:P,onKeyDown:E,onClick:A}:{ref:f,id:r.buttonId,type:P,"aria-expanded":r.popoverState===0,"aria-controls":r.panel?r.panelId:void 0,onKeyDown:E,onKeyUp:D,onClick:A,onMouseDown:U},v=Et(),R=T(()=>{let C=r.panel;if(!C)return;function N(){M(v.current,{[0]:()=>le(C,1),[1]:()=>le(C,8)})===0&&le(Ye().filter(K=>K.dataset.headlessuiFocusGuard!=="true"),M(v.current,{[0]:4,[1]:2}),{relativeTo:r.button})}N()});return k.default.createElement(k.default.Fragment,null,L({ourProps:O,theirProps:l,slot:x,defaultTag:Fl,name:"Popover.Button"}),_&&!g&&i&&k.default.createElement(pe,{id:u,features:2,"data-headlessui-focus-guard":!0,as:"button",type:"button",onFocus:R}))}var wl="div",_l=3;function Hl(e,t){let n=j(),{id:o=`headlessui-popover-overlay-${n}`,...l}=e,[{popoverState:r},a]=jt("Popover.Overlay"),i=I(t),s=be(),u=(()=>s!==null?(s&1)===1:r===0)(),m=T(g=>{if(de(g.currentTarget))return g.preventDefault();a({type:1})}),p=(0,k.useMemo)(()=>({open:r===0}),[r]);return L({ourProps:{ref:i,id:o,"aria-hidden":!0,onClick:m},theirProps:l,slot:p,defaultTag:wl,features:_l,visible:u,name:"Popover.Overlay"})}var kl="div",Nl=3;function Gl(e,t){let n=j(),{id:o=`headlessui-popover-panel-${n}`,focus:l=!1,...r}=e,[a,i]=jt("Popover.Panel"),{close:s,isPortalled:u}=An("Popover.Panel"),m=`headlessui-focus-sentinel-before-${j()}`,p=`headlessui-focus-sentinel-after-${j()}`,y=(0,k.useRef)(null),g=I(y,t,x=>{i({type:4,panel:x})}),d=ge(y);w(()=>(i({type:5,panelId:o}),()=>{i({type:5,panelId:null})}),[o,i]);let f=be(),b=(()=>f!==null?(f&1)===1:a.popoverState===0)(),c=T(x=>{var P;switch(x.key){case"Escape":if(a.popoverState!==0||!y.current||d!=null&&d.activeElement&&!y.current.contains(d.activeElement))return;x.preventDefault(),x.stopPropagation(),i({type:1}),(P=a.button)==null||P.focus();break}});(0,k.useEffect)(()=>{var x;e.static||a.popoverState===1&&((x=e.unmount)==null||x)&&i({type:4,panel:null})},[a.popoverState,e.unmount,e.static,i]),(0,k.useEffect)(()=>{if(a.__demoMode||!l||a.popoverState!==0||!y.current)return;let x=d==null?void 0:d.activeElement;y.current.contains(x)||le(y.current,1)},[a.__demoMode,l,y,a.popoverState]);let E=(0,k.useMemo)(()=>({open:a.popoverState===0,close:s}),[a,s]),D={ref:g,id:o,onKeyDown:c,onBlur:l&&a.popoverState===0?x=>{var O,v,R,C,N;let P=x.relatedTarget;P&&y.current&&((O=y.current)!=null&&O.contains(P)||(i({type:1}),((R=(v=a.beforePanelSentinel.current)==null?void 0:v.contains)!=null&&R.call(v,P)||(N=(C=a.afterPanelSentinel.current)==null?void 0:C.contains)!=null&&N.call(C,P))&&P.focus({preventScroll:!0})))}:void 0,tabIndex:-1},A=Et(),U=T(()=>{let x=y.current;if(!x)return;function P(){M(A.current,{[0]:()=>{var v;le(x,1)===0&&((v=a.afterPanelSentinel.current)==null||v.focus())},[1]:()=>{var O;(O=a.button)==null||O.focus({preventScroll:!0})}})}P()}),_=T(()=>{let x=y.current;if(!x)return;function P(){M(A.current,{[0]:()=>{var ee;if(!a.button)return;let O=Ye(),v=O.indexOf(a.button),R=O.slice(0,v+1),N=[...O.slice(v+1),...R];for(let K of N.slice())if(K.dataset.headlessuiFocusGuard==="true"||(ee=a.panel)!=null&&ee.contains(K)){let G=N.indexOf(K);G!==-1&&N.splice(G,1)}le(N,1,{sorted:!1})},[1]:()=>{var v;le(x,2)===0&&((v=a.button)==null||v.focus())}})}P()});return k.default.createElement(Wt.Provider,{value:o},b&&u&&k.default.createElement(pe,{id:m,ref:a.beforePanelSentinel,features:2,"data-headlessui-focus-guard":!0,as:"button",type:"button",onFocus:U}),L({ourProps:D,theirProps:r,slot:E,defaultTag:kl,features:Nl,visible:b,name:"Popover.Panel"}),b&&u&&k.default.createElement(pe,{id:p,ref:a.afterPanelSentinel,features:2,"data-headlessui-focus-guard":!0,as:"button",type:"button",onFocus:_}))}var Ul="div";function Bl(e,t){let n=(0,k.useRef)(null),o=I(n,t),[l,r]=(0,k.useState)([]),a=T(d=>{r(f=>{let b=f.indexOf(d);if(b!==-1){let c=f.slice();return c.splice(b,1),c}return f})}),i=T(d=>(r(f=>[...f,d]),()=>a(d))),s=T(()=>{var b;let d=me(n);if(!d)return!1;let f=d.activeElement;return(b=n.current)!=null&&b.contains(f)?!0:l.some(c=>{var E,D;return((E=d.getElementById(c.buttonId.current))==null?void 0:E.contains(f))||((D=d.getElementById(c.panelId.current))==null?void 0:D.contains(f))})}),u=T(d=>{for(let f of l)f.buttonId.current!==d&&f.close()}),m=(0,k.useMemo)(()=>({registerPopover:i,unregisterPopover:a,isFocusWithinPopoverGroup:s,closeOthers:u}),[i,a,s,u]),p=(0,k.useMemo)(()=>({}),[]),y=e,g={ref:o};return k.default.createElement(Ln.Provider,{value:m},L({ourProps:g,theirProps:y,slot:p,defaultTag:Ul,name:"Popover.Group"}))}var Vl=h(Ml),jl=h(Il),Wl=h(Hl),Kl=h(Gl),Xl=h(Bl),$l=Object.assign(Vl,{Button:jl,Overlay:Wl,Panel:Kl,Group:Xl});var Z=oe(__webpack_require__(7294),1);var Je=__webpack_require__(7294);function Kt(e=0){let[t,n]=(0,Je.useState)(e),o=Ae(),l=(0,Je.useCallback)(s=>{o.current&&n(u=>u|s)},[t,o]),r=(0,Je.useCallback)(s=>Boolean(t&s),[t]),a=(0,Je.useCallback)(s=>{o.current&&n(u=>u&~s)},[n,o]),i=(0,Je.useCallback)(s=>{o.current&&n(u=>u^s)},[n]);return{flags:t,addFlag:l,hasFlag:r,removeFlag:a,toggleFlag:i}}var he=oe(__webpack_require__(7294),1);var No=(0,he.createContext)(null);function Go(){let e=(0,he.useContext)(No);if(e===null){let t=new Error("You used a <Label /> component, but it is not inside a relevant parent.");throw Error.captureStackTrace&&Error.captureStackTrace(t,Go),t}return e}function St(){let[e,t]=(0,he.useState)([]);return[e.length>0?e.join(" "):void 0,(0,he.useMemo)(()=>function(o){let l=T(a=>(t(i=>[...i,a]),()=>t(i=>{let s=i.slice(),u=s.indexOf(a);return u!==-1&&s.splice(u,1),s}))),r=(0,he.useMemo)(()=>({register:l,slot:o.slot,name:o.name,props:o.props}),[l,o.slot,o.name,o.props]);return he.default.createElement(No.Provider,{value:r},o.children)},[t])]}var Jl="label";function ql(e,t){let n=j(),{id:o=`headlessui-label-${n}`,passive:l=!1,...r}=e,a=Go(),i=I(t);w(()=>a.register(o),[o,a.register]);let s={ref:i,...a.props,id:o};return l&&("onClick"in s&&(delete s.htmlFor,delete s.onClick),"onClick"in r&&delete r.onClick),L({ourProps:s,theirProps:r,slot:a.slot||{},defaultTag:Jl,name:a.name||"Label"})}var Yl=h(ql),Xt=Object.assign(Yl,{});var Ql={[0](e,t){let n=[...e.options,{id:t.id,element:t.element,propsRef:t.propsRef}];return{...e,options:ye(n,o=>o.element.current)}},[1](e,t){let n=e.options.slice(),o=e.options.findIndex(l=>l.id===t.id);return o===-1?e:(n.splice(o,1),{...e,options:n})}},hn=(0,Z.createContext)(null);hn.displayName="RadioGroupDataContext";function Uo(e){let t=(0,Z.useContext)(hn);if(t===null){let n=new Error(`<${e} /> is missing a parent <RadioGroup /> component.`);throw Error.captureStackTrace&&Error.captureStackTrace(n,Uo),n}return t}var Dn=(0,Z.createContext)(null);Dn.displayName="RadioGroupActionsContext";function Bo(e){let t=(0,Z.useContext)(Dn);if(t===null){let n=new Error(`<${e} /> is missing a parent <RadioGroup /> component.`);throw Error.captureStackTrace&&Error.captureStackTrace(n,Bo),n}return t}function zl(e,t){return M(t.type,Ql,e,t)}var Zl="div";function ei(e,t){let n=j(),{id:o=`headlessui-radiogroup-${n}`,value:l,defaultValue:r,form:a,name:i,onChange:s,by:u=(H,Q)=>H===Q,disabled:m=!1,...p}=e,y=T(typeof u=="string"?(H,Q)=>{let J=u;return(H==null?void 0:H[J])===(Q==null?void 0:Q[J])}:u),[g,d]=(0,Z.useReducer)(zl,{options:[]}),f=g.options,[b,c]=St(),[E,D]=$e(),A=(0,Z.useRef)(null),U=I(A,t),[_,x]=ke(l,s,r),P=(0,Z.useMemo)(()=>f.find(H=>!H.propsRef.current.disabled),[f]),O=(0,Z.useMemo)(()=>f.some(H=>y(H.propsRef.current.value,_)),[f,_]),v=T(H=>{var J;if(m||y(H,_))return!1;let Q=(J=f.find(te=>y(te.propsRef.current.value,H)))==null?void 0:J.propsRef.current;return Q!=null&&Q.disabled?!1:(x==null||x(H),!0)});ze({container:A.current,accept(H){return H.getAttribute("role")==="radio"?NodeFilter.FILTER_REJECT:H.hasAttribute("role")?NodeFilter.FILTER_SKIP:NodeFilter.FILTER_ACCEPT},walk(H){H.setAttribute("role","none")}});let R=T(H=>{let Q=A.current;if(!Q)return;let J=me(Q),te=f.filter(fe=>fe.propsRef.current.disabled===!1).map(fe=>fe.element.current);switch(H.key){case"Enter":Ft(H.currentTarget);break;case"ArrowLeft":case"ArrowUp":if(H.preventDefault(),H.stopPropagation(),le(te,18)===2){let S=f.find(F=>F.element.current===(J==null?void 0:J.activeElement));S&&v(S.propsRef.current.value)}break;case"ArrowRight":case"ArrowDown":if(H.preventDefault(),H.stopPropagation(),le(te,20)===2){let S=f.find(F=>F.element.current===(J==null?void 0:J.activeElement));S&&v(S.propsRef.current.value)}break;case" ":{H.preventDefault(),H.stopPropagation();let fe=f.find(S=>S.element.current===(J==null?void 0:J.activeElement));fe&&v(fe.propsRef.current.value)}break}}),C=T(H=>(d({type:0,...H}),()=>d({type:1,id:H.id}))),N=(0,Z.useMemo)(()=>({value:_,firstOption:P,containsCheckedOption:O,disabled:m,compare:y,...g}),[_,P,O,m,y,g]),ee=(0,Z.useMemo)(()=>({registerOption:C,change:v}),[C,v]),K={ref:U,id:o,role:"radiogroup","aria-labelledby":b,"aria-describedby":E,onKeyDown:R},G=(0,Z.useMemo)(()=>({value:_}),[_]),X=(0,Z.useRef)(null),ce=se();return(0,Z.useEffect)(()=>{X.current&&r!==void 0&&ce.addEventListener(X.current,"reset",()=>{v(r)})},[X,v]),Z.default.createElement(D,{name:"RadioGroup.Description"},Z.default.createElement(c,{name:"RadioGroup.Label"},Z.default.createElement(Dn.Provider,{value:ee},Z.default.createElement(hn.Provider,{value:N},i!=null&&_!=null&&Ve({[i]:_}).map(([H,Q],J)=>Z.default.createElement(pe,{features:4,ref:J===0?te=>{var fe;X.current=(fe=te==null?void 0:te.closest("form"))!=null?fe:null}:void 0,...Me({key:H,as:"input",type:"radio",checked:Q!=null,hidden:!0,readOnly:!0,form:a,name:H,value:Q})})),L({ourProps:K,theirProps:p,slot:G,defaultTag:Zl,name:"RadioGroup"})))))}var ti="div";function ni(e,t){var R;let n=j(),{id:o=`headlessui-radiogroup-option-${n}`,value:l,disabled:r=!1,...a}=e,i=(0,Z.useRef)(null),s=I(i,t),[u,m]=St(),[p,y]=$e(),{addFlag:g,removeFlag:d,hasFlag:f}=Kt(1),b=z({value:l,disabled:r}),c=Uo("RadioGroup.Option"),E=Bo("RadioGroup.Option");w(()=>E.registerOption({id:o,element:i,propsRef:b}),[o,E,i,e]);let D=T(C=>{var N;if(de(C.currentTarget))return C.preventDefault();E.change(l)&&(g(2),(N=i.current)==null||N.focus())}),A=T(C=>{if(de(C.currentTarget))return C.preventDefault();g(2)}),U=T(()=>d(2)),_=((R=c.firstOption)==null?void 0:R.id)===o,x=c.disabled||r,P=c.compare(c.value,l),O={ref:s,id:o,role:"radio","aria-checked":P?"true":"false","aria-labelledby":u,"aria-describedby":p,"aria-disabled":x?!0:void 0,tabIndex:(()=>x?-1:P||!c.containsCheckedOption&&_?0:-1)(),onClick:x?void 0:D,onFocus:x?void 0:A,onBlur:x?void 0:U},v=(0,Z.useMemo)(()=>({checked:P,disabled:x,active:f(2)}),[P,x,f]);return Z.default.createElement(y,{name:"RadioGroup.Description"},Z.default.createElement(m,{name:"RadioGroup.Label"},L({ourProps:O,theirProps:a,slot:v,defaultTag:ti,name:"RadioGroup.Option"})))}var oi=h(ei),ri=h(ni),ai=Object.assign(oi,{Option:ri,Label:Xt,Description:st});var ue=oe(__webpack_require__(7294),1);var Mn=(0,ue.createContext)(null);Mn.displayName="GroupContext";var li=ue.Fragment;function ii(e){var m;let[t,n]=(0,ue.useState)(null),[o,l]=St(),[r,a]=$e(),i=(0,ue.useMemo)(()=>({switch:t,setSwitch:n,labelledby:o,describedby:r}),[t,n,o,r]),s={},u=e;return ue.default.createElement(a,{name:"Switch.Description"},ue.default.createElement(l,{name:"Switch.Label",props:{htmlFor:(m=i.switch)==null?void 0:m.id,onClick(p){t&&(p.currentTarget.tagName==="LABEL"&&p.preventDefault(),t.click(),t.focus({preventScroll:!0}))}}},ue.default.createElement(Mn.Provider,{value:i},L({ourProps:s,theirProps:u,defaultTag:li,name:"Switch.Group"}))))}var si="button";function ui(e,t){let n=j(),{id:o=`headlessui-switch-${n}`,checked:l,defaultChecked:r=!1,onChange:a,name:i,value:s,form:u,...m}=e,p=(0,ue.useContext)(Mn),y=(0,ue.useRef)(null),g=I(y,t,p===null?null:p.setSwitch),[d,f]=ke(l,a,r),b=T(()=>f==null?void 0:f(!d)),c=T(x=>{if(de(x.currentTarget))return x.preventDefault();x.preventDefault(),b()}),E=T(x=>{x.key===" "?(x.preventDefault(),b()):x.key==="Enter"&&Ft(x.currentTarget)}),D=T(x=>x.preventDefault()),A=(0,ue.useMemo)(()=>({checked:d}),[d]),U={id:o,ref:g,role:"switch",type:xe(e,y),tabIndex:0,"aria-checked":d,"aria-labelledby":p==null?void 0:p.labelledby,"aria-describedby":p==null?void 0:p.describedby,onClick:c,onKeyUp:E,onKeyPress:D},_=se();return(0,ue.useEffect)(()=>{var P;let x=(P=y.current)==null?void 0:P.closest("form");x&&r!==void 0&&_.addEventListener(x,"reset",()=>{f(r)})},[y,f]),ue.default.createElement(ue.default.Fragment,null,i!=null&&d&&ue.default.createElement(pe,{features:4,...Me({as:"input",type:"checkbox",hidden:!0,readOnly:!0,form:u,checked:d,name:i,value:s})}),L({ourProps:U,theirProps:m,slot:A,defaultTag:si,name:"Switch"}))}var pi=h(ui),di=ii,ci=Object.assign(pi,{Group:di,Label:Xt,Description:st});var ne=oe(__webpack_require__(7294),1);var $t=oe(__webpack_require__(7294),1);function Vo({onFocus:e}){let[t,n]=(0,$t.useState)(!0),o=Ae();return t?$t.default.createElement(pe,{as:"button",type:"button",features:2,onFocus:l=>{l.preventDefault();let r,a=50;function i(){if(a--<=0){r&&cancelAnimationFrame(r);return}if(e()){if(cancelAnimationFrame(r),!o.current)return;n(!1);return}r=requestAnimationFrame(i)}r=requestAnimationFrame(i)}}):null}var Oe=oe(__webpack_require__(7294),1),jo=Oe.createContext(null);function fi(){return{groups:new Map,get(e,t){var a;let n=this.groups.get(e);n||(n=new Map,this.groups.set(e,n));let o=(a=n.get(t))!=null?a:0;n.set(t,o+1);let l=Array.from(n.keys()).indexOf(t);function r(){let i=n.get(t);i>1?n.set(t,i-1):n.delete(t)}return[l,r]}}}function Wo({children:e}){let t=Oe.useRef(fi());return Oe.createElement(jo.Provider,{value:t},e)}function Fn(e){let t=Oe.useContext(jo);if(!t)throw new Error("You must wrap your component in a <StableCollection>");let n=Ti(),[o,l]=t.current.get(e,n);return Oe.useEffect(()=>l,[]),o}function Ti(){var o,l,r;let e=(r=(l=(o=Oe.__SECRET_INTERNALS_DO_NOT_USE_OR_YOU_WILL_BE_FIRED)==null?void 0:o.ReactCurrentOwner)==null?void 0:l.current)!=null?r:null;if(!e)return Symbol();let t=[],n=e;for(;n;)t.push(n.index),n=n.return;return"$."+t.join(".")}var mi={[0](e,t){var m;let n=ye(e.tabs,p=>p.current),o=ye(e.panels,p=>p.current),l=n.filter(p=>{var y;return!((y=p.current)!=null&&y.hasAttribute("disabled"))}),r={...e,tabs:n,panels:o};if(t.index<0||t.index>n.length-1){let p=M(Math.sign(t.index-e.selectedIndex),{[-1]:()=>1,[0]:()=>M(Math.sign(t.index),{[-1]:()=>0,[0]:()=>0,[1]:()=>1}),[1]:()=>0});return l.length===0?r:{...r,selectedIndex:M(p,{[0]:()=>n.indexOf(l[0]),[1]:()=>n.indexOf(l[l.length-1])})}}let a=n.slice(0,t.index),s=[...n.slice(t.index),...a].find(p=>l.includes(p));if(!s)return r;let u=(m=n.indexOf(s))!=null?m:e.selectedIndex;return u===-1&&(u=e.selectedIndex),{...r,selectedIndex:u}},[1](e,t){var r;if(e.tabs.includes(t.tab))return e;let n=e.tabs[e.selectedIndex],o=ye([...e.tabs,t.tab],a=>a.current),l=(r=o.indexOf(n))!=null?r:e.selectedIndex;return l===-1&&(l=e.selectedIndex),{...e,tabs:o,selectedIndex:l}},[2](e,t){return{...e,tabs:e.tabs.filter(n=>n!==t.tab)}},[3](e,t){return e.panels.includes(t.panel)?e:{...e,panels:ye([...e.panels,t.panel],n=>n.current)}},[4](e,t){return{...e,panels:e.panels.filter(n=>n!==t.panel)}}},In=(0,ne.createContext)(null);In.displayName="TabsDataContext";function dt(e){let t=(0,ne.useContext)(In);if(t===null){let n=new Error(`<${e} /> is missing a parent <Tab.Group /> component.`);throw Error.captureStackTrace&&Error.captureStackTrace(n,dt),n}return t}var wn=(0,ne.createContext)(null);wn.displayName="TabsActionsContext";function _n(e){let t=(0,ne.useContext)(wn);if(t===null){let n=new Error(`<${e} /> is missing a parent <Tab.Group /> component.`);throw Error.captureStackTrace&&Error.captureStackTrace(n,_n),n}return t}function bi(e,t){return M(t.type,mi,e,t)}var yi=ne.Fragment;function gi(e,t){let{defaultIndex:n=0,vertical:o=!1,manual:l=!1,onChange:r,selectedIndex:a=null,...i}=e,s=o?"vertical":"horizontal",u=l?"manual":"auto",m=a!==null,p=I(t),[y,g]=(0,ne.useReducer)(bi,{selectedIndex:a!=null?a:n,tabs:[],panels:[]}),d=(0,ne.useMemo)(()=>({selectedIndex:y.selectedIndex}),[y.selectedIndex]),f=z(r||(()=>{})),b=z(y.tabs),c=(0,ne.useMemo)(()=>({orientation:s,activation:u,...y}),[s,u,y]),E=T(P=>(g({type:1,tab:P}),()=>g({type:2,tab:P}))),D=T(P=>(g({type:3,panel:P}),()=>g({type:4,panel:P}))),A=T(P=>{U.current!==P&&f.current(P),m||g({type:0,index:P})}),U=z(m?e.selectedIndex:y.selectedIndex),_=(0,ne.useMemo)(()=>({registerTab:E,registerPanel:D,change:A}),[]);w(()=>{g({type:0,index:a!=null?a:n})},[a]),w(()=>{if(U.current===void 0||y.tabs.length<=0)return;let P=ye(y.tabs,v=>v.current);P.some((v,R)=>y.tabs[R]!==v)&&A(P.indexOf(y.tabs[U.current]))});let x={ref:p};return ne.default.createElement(Wo,null,ne.default.createElement(wn.Provider,{value:_},ne.default.createElement(In.Provider,{value:c},c.tabs.length<=0&&ne.default.createElement(Vo,{onFocus:()=>{var P,O;for(let v of b.current)if(((P=v.current)==null?void 0:P.tabIndex)===0)return(O=v.current)==null||O.focus(),!0;return!1}}),L({ourProps:x,theirProps:i,slot:d,defaultTag:yi,name:"Tabs"}))))}var Ei="div";function Pi(e,t){let{orientation:n,selectedIndex:o}=dt("Tab.List"),l=I(t);return L({ourProps:{ref:l,role:"tablist","aria-orientation":n},theirProps:e,slot:{selectedIndex:o},defaultTag:Ei,name:"Tabs.List"})}var xi="button";function vi(e,t){var P,O;let n=j(),{id:o=`headlessui-tabs-tab-${n}`,...l}=e,{orientation:r,activation:a,selectedIndex:i,tabs:s,panels:u}=dt("Tab"),m=_n("Tab"),p=dt("Tab"),y=(0,ne.useRef)(null),g=I(y,t);w(()=>m.registerTab(y),[m,y]);let d=Fn("tabs"),f=s.indexOf(y);f===-1&&(f=d);let b=f===i,c=T(v=>{var C;let R=v();if(R===2&&a==="auto"){let N=(C=me(y))==null?void 0:C.activeElement,ee=p.tabs.findIndex(K=>K.current===N);ee!==-1&&m.change(ee)}return R}),E=T(v=>{let R=s.map(N=>N.current).filter(Boolean);if(v.key===" "||v.key==="Enter"){v.preventDefault(),v.stopPropagation(),m.change(f);return}switch(v.key){case"Home":case"PageUp":return v.preventDefault(),v.stopPropagation(),c(()=>le(R,1));case"End":case"PageDown":return v.preventDefault(),v.stopPropagation(),c(()=>le(R,8))}if(c(()=>M(r,{vertical(){return v.key==="ArrowUp"?le(R,18):v.key==="ArrowDown"?le(R,20):0},horizontal(){return v.key==="ArrowLeft"?le(R,18):v.key==="ArrowRight"?le(R,20):0}}))===2)return v.preventDefault()}),D=(0,ne.useRef)(!1),A=T(()=>{var v;D.current||(D.current=!0,(v=y.current)==null||v.focus(),m.change(f),we(()=>{D.current=!1}))}),U=T(v=>{v.preventDefault()}),_=(0,ne.useMemo)(()=>({selected:b}),[b]),x={ref:g,onKeyDown:E,onMouseDown:U,onClick:A,id:o,role:"tab",type:xe(e,y),"aria-controls":(O=(P=u[f])==null?void 0:P.current)==null?void 0:O.id,"aria-selected":b,tabIndex:b?0:-1};return L({ourProps:x,theirProps:l,slot:_,defaultTag:xi,name:"Tabs.Tab"})}var Ri="div";function Si(e,t){let{selectedIndex:n}=dt("Tab.Panels"),o=I(t),l=(0,ne.useMemo)(()=>({selectedIndex:n}),[n]);return L({ourProps:{ref:o},theirProps:e,slot:l,defaultTag:Ri,name:"Tabs.Panels"})}var Oi="div",Ci=3;function Ai(e,t){var c,E,D,A;let n=j(),{id:o=`headlessui-tabs-panel-${n}`,tabIndex:l=0,...r}=e,{selectedIndex:a,tabs:i,panels:s}=dt("Tab.Panel"),u=_n("Tab.Panel"),m=(0,ne.useRef)(null),p=I(m,t);w(()=>u.registerPanel(m),[u,m]);let y=Fn("panels"),g=s.indexOf(m);g===-1&&(g=y);let d=g===a,f=(0,ne.useMemo)(()=>({selected:d}),[d]),b={ref:p,id:o,role:"tabpanel","aria-labelledby":(E=(c=i[g])==null?void 0:c.current)==null?void 0:E.id,tabIndex:d?l:-1};return!d&&((D=r.unmount)==null||D)&&!((A=r.static)!=null&&A)?ne.default.createElement(pe,{as:"span",...b}):L({ourProps:b,theirProps:r,slot:f,defaultTag:Oi,features:Ci,visible:d,name:"Tabs.Panel"})}var Li=h(vi),hi=h(gi),Di=h(Pi),Mi=h(Si),Fi=h(Ai),Ii=Object.assign(Li,{Group:hi,List:Di,Panels:Mi,Panel:Fi});var V=oe(__webpack_require__(7294),1);function Ko(e){let t={called:!1};return(...n)=>{if(!t.called)return t.called=!0,e(...n)}}function Hn(e,...t){e&&t.length>0&&e.classList.add(...t)}function kn(e,...t){e&&t.length>0&&e.classList.remove(...t)}function wi(e,t){let n=ie();if(!e)return n.dispose;let{transitionDuration:o,transitionDelay:l}=getComputedStyle(e),[r,a]=[o,l].map(s=>{let[u=0]=s.split(",").filter(Boolean).map(m=>m.includes("ms")?parseFloat(m):parseFloat(m)*1e3).sort((m,p)=>p-m);return u}),i=r+a;if(i!==0){n.group(u=>{u.setTimeout(()=>{t(),u.dispose()},i),u.addEventListener(e,"transitionrun",m=>{m.target===m.currentTarget&&u.dispose()})});let s=n.addEventListener(e,"transitionend",u=>{u.target===u.currentTarget&&(t(),s())})}else t();return n.add(()=>t()),n.dispose}function Xo(e,t,n,o){let l=n?"enter":"leave",r=ie(),a=o!==void 0?Ko(o):()=>{};l==="enter"&&(e.removeAttribute("hidden"),e.style.display="");let i=M(l,{enter:()=>t.enter,leave:()=>t.leave}),s=M(l,{enter:()=>t.enterTo,leave:()=>t.leaveTo}),u=M(l,{enter:()=>t.enterFrom,leave:()=>t.leaveFrom});return kn(e,...t.enter,...t.enterTo,...t.enterFrom,...t.leave,...t.leaveFrom,...t.leaveTo,...t.entered),Hn(e,...i,...u),r.nextFrame(()=>{kn(e,...u),Hn(e,...s),wi(e,()=>(kn(e,...i),Hn(e,...t.entered),a()))}),r.dispose}function $o({container:e,direction:t,classes:n,onStart:o,onStop:l}){let r=Ae(),a=se(),i=z(t);w(()=>{let s=ie();a.add(s.dispose);let u=e.current;if(u&&i.current!=="idle"&&r.current)return s.dispose(),o.current(i.current),s.add(Xo(u,n.current,i.current==="enter",()=>{s.dispose(),l.current(i.current)})),s.dispose},[t])}function qe(e=""){return e.split(" ").filter(t=>t.trim().length>1)}var Jt=(0,V.createContext)(null);Jt.displayName="TransitionContext";function _i(){let e=(0,V.useContext)(Jt);if(e===null)throw new Error("A <Transition.Child /> is used but it is missing a parent <Transition /> or <Transition.Root />.");return e}function Hi(){let e=(0,V.useContext)(qt);if(e===null)throw new Error("A <Transition.Child /> is used but it is missing a parent <Transition /> or <Transition.Root />.");return e}var qt=(0,V.createContext)(null);qt.displayName="NestingContext";function Yt(e){return"children"in e?Yt(e.children):e.current.filter(({el:t})=>t.current!==null).filter(({state:t})=>t==="visible").length>0}function qo(e,t){let n=z(e),o=(0,V.useRef)([]),l=Ae(),r=se(),a=T((g,d=1)=>{let f=o.current.findIndex(({el:b})=>b===g);f!==-1&&(M(d,{[0](){o.current.splice(f,1)},[1](){o.current[f].state="hidden"}}),r.microTask(()=>{var b;!Yt(o)&&l.current&&((b=n.current)==null||b.call(n))}))}),i=T(g=>{let d=o.current.find(({el:f})=>f===g);return d?d.state!=="visible"&&(d.state="visible"):o.current.push({el:g,state:"visible"}),()=>a(g,0)}),s=(0,V.useRef)([]),u=(0,V.useRef)(Promise.resolve()),m=(0,V.useRef)({enter:[],leave:[],idle:[]}),p=T((g,d,f)=>{s.current.splice(0),t&&(t.chains.current[d]=t.chains.current[d].filter(([b])=>b!==g)),t==null||t.chains.current[d].push([g,new Promise(b=>{s.current.push(b)})]),t==null||t.chains.current[d].push([g,new Promise(b=>{Promise.all(m.current[d].map(([c,E])=>E)).then(()=>b())})]),d==="enter"?u.current=u.current.then(()=>t==null?void 0:t.wait.current).then(()=>f(d)):f(d)}),y=T((g,d,f)=>{Promise.all(m.current[d].splice(0).map(([b,c])=>c)).then(()=>{var b;(b=s.current.shift())==null||b()}).then(()=>f(d))});return(0,V.useMemo)(()=>({children:o,register:i,unregister:a,onStart:p,onStop:y,wait:u,chains:m}),[i,a,o,p,y,m,u])}function ki(){}var Ni=["beforeEnter","afterEnter","beforeLeave","afterLeave"];function Jo(e){var n;let t={};for(let o of Ni)t[o]=(n=e[o])!=null?n:ki;return t}function Gi(e){let t=(0,V.useRef)(Jo(e));return(0,V.useEffect)(()=>{t.current=Jo(e)},[e]),t}var Ui="div",Yo=1;function Bi(e,t){let{beforeEnter:n,afterEnter:o,beforeLeave:l,afterLeave:r,enter:a,enterFrom:i,enterTo:s,entered:u,leave:m,leaveFrom:p,leaveTo:y,...g}=e,d=(0,V.useRef)(null),f=I(d,t),b=g.unmount?0:1,{show:c,appear:E,initial:D}=_i(),[A,U]=(0,V.useState)(c?"visible":"hidden"),_=Hi(),{register:x,unregister:P}=_,O=(0,V.useRef)(null);(0,V.useEffect)(()=>x(d),[x,d]),(0,V.useEffect)(()=>{if(b===1&&d.current){if(c&&A!=="visible"){U("visible");return}return M(A,{["hidden"]:()=>P(d),["visible"]:()=>x(d)})}},[A,d,x,P,c,b]);let v=z({enter:qe(a),enterFrom:qe(i),enterTo:qe(s),entered:qe(u),leave:qe(m),leaveFrom:qe(p),leaveTo:qe(y)}),R=Gi({beforeEnter:n,afterEnter:o,beforeLeave:l,afterLeave:r}),C=Ce();(0,V.useEffect)(()=>{if(C&&A==="visible"&&d.current===null)throw new Error("Did you forget to passthrough the `ref` to the actual DOM node?")},[d,A,C]);let N=D&&!E,ee=(()=>!C||N||O.current===c?"idle":c?"enter":"leave")(),K=Kt(0),G=T(J=>M(J,{enter:()=>{K.addFlag(8),R.current.beforeEnter()},leave:()=>{K.addFlag(4),R.current.beforeLeave()},idle:()=>{}})),X=T(J=>M(J,{enter:()=>{K.removeFlag(8),R.current.afterEnter()},leave:()=>{K.removeFlag(4),R.current.afterLeave()},idle:()=>{}})),ce=qo(()=>{U("hidden"),P(d)},_);$o({container:d,classes:v,direction:ee,onStart:z(J=>{ce.onStart(d,J,G)}),onStop:z(J=>{ce.onStop(d,J,X),J==="leave"&&!Yt(ce)&&(U("hidden"),P(d))})}),(0,V.useEffect)(()=>{N&&(b===1?O.current=null:O.current=c)},[c,N,A]);let H=g,Q={ref:f};return E&&c&&D&&(H={...H,className:yt(g.className,...v.current.enter,...v.current.enterFrom)}),V.default.createElement(qt.Provider,{value:ce},V.default.createElement(Se,{value:M(A,{["visible"]:1,["hidden"]:2})|K.flags},L({ourProps:Q,theirProps:H,defaultTag:Ui,features:Yo,visible:A==="visible",name:"Transition.Child"})))}function Vi(e,t){let{show:n,appear:o=!1,unmount:l,...r}=e,a=(0,V.useRef)(null),i=I(a,t);Ce();let s=be();if(n===void 0&&s!==null&&(n=(s&1)===1),![!0,!1].includes(n))throw new Error("A <Transition /> is used but it is missing a `show={true | false}` prop.");let[u,m]=(0,V.useState)(n?"visible":"hidden"),p=qo(()=>{m("hidden")}),[y,g]=(0,V.useState)(!0),d=(0,V.useRef)([n]);w(()=>{y!==!1&&d.current[d.current.length-1]!==n&&(d.current.push(n),g(!1))},[d,n]);let f=(0,V.useMemo)(()=>({show:n,appear:o,initial:y}),[n,o,y]);(0,V.useEffect)(()=>{if(n)m("visible");else if(!Yt(p))m("hidden");else{let D=a.current;if(!D)return;let A=D.getBoundingClientRect();A.x===0&&A.y===0&&A.width===0&&A.height===0&&m("hidden")}},[n,p]);let b={unmount:l},c=T(()=>{var D;y&&g(!1),(D=e.beforeEnter)==null||D.call(e)}),E=T(()=>{var D;y&&g(!1),(D=e.beforeLeave)==null||D.call(e)});return V.default.createElement(qt.Provider,{value:p},V.default.createElement(Jt.Provider,{value:f},L({ourProps:{...b,as:V.Fragment,children:V.default.createElement(Qo,{ref:i,...b,...r,beforeEnter:c,beforeLeave:E})},theirProps:{},defaultTag:V.Fragment,features:Yo,visible:u==="visible",name:"Transition"})))}function ji(e,t){let n=(0,V.useContext)(Jt)!==null,o=be()!==null;return V.default.createElement(V.default.Fragment,null,!n&&o?V.default.createElement(Nn,{ref:t,...e}):V.default.createElement(Qo,{ref:t,...e}))}var Nn=h(Vi),Qo=h(Bi),Wi=h(ji),Ki=Object.assign(Nn,{Child:Wi,Root:Nn});


/***/ }),

/***/ 5594:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

"use strict";


if (true) {
  module.exports = __webpack_require__(5006)
} else {}


/***/ }),

/***/ 5085:
/***/ (function(__unused_webpack_module, exports, __webpack_require__) {

"use strict";

var __createBinding = (this && this.__createBinding) || (Object.create ? (function(o, m, k, k2) {
    if (k2 === undefined) k2 = k;
    var desc = Object.getOwnPropertyDescriptor(m, k);
    if (!desc || ("get" in desc ? !m.__esModule : desc.writable || desc.configurable)) {
      desc = { enumerable: true, get: function() { return m[k]; } };
    }
    Object.defineProperty(o, k2, desc);
}) : (function(o, m, k, k2) {
    if (k2 === undefined) k2 = k;
    o[k2] = m[k];
}));
var __setModuleDefault = (this && this.__setModuleDefault) || (Object.create ? (function(o, v) {
    Object.defineProperty(o, "default", { enumerable: true, value: v });
}) : function(o, v) {
    o["default"] = v;
});
var __importStar = (this && this.__importStar) || function (mod) {
    if (mod && mod.__esModule) return mod;
    var result = {};
    if (mod != null) for (var k in mod) if (k !== "default" && Object.prototype.hasOwnProperty.call(mod, k)) __createBinding(result, mod, k);
    __setModuleDefault(result, mod);
    return result;
};
Object.defineProperty(exports, "__esModule", ({ value: true }));
const react_1 = __importStar(__webpack_require__(7294));
// ------------------ Helpers
function isNumber(n) {
    const number = parseFloat(n);
    return !isNaN(number) && isFinite(number);
}
function isPercentage(height) {
    // Percentage height
    return (typeof height === 'string' &&
        height[height.length - 1] === '%' &&
        isNumber(height.substring(0, height.length - 1)));
}
function hideContent(element, height) {
    // Check for element?.style is added cause this would fail in tests (react-test-renderer)
    // Read more here: https://github.com/Stanko/react-animate-height/issues/17
    if (height === 0 && (element === null || element === void 0 ? void 0 : element.style)) {
        element.style.display = 'none';
    }
}
function showContent(element, height) {
    // Check for element?.style is added cause this would fail in tests (react-test-renderer)
    // Read more here: https://github.com/Stanko/react-animate-height/issues/17
    if (height === 0 && (element === null || element === void 0 ? void 0 : element.style)) {
        element.style.display = '';
    }
}
const ANIMATION_STATE_CLASSES = {
    animating: 'rah-animating',
    animatingUp: 'rah-animating--up',
    animatingDown: 'rah-animating--down',
    animatingToHeightZero: 'rah-animating--to-height-zero',
    animatingToHeightAuto: 'rah-animating--to-height-auto',
    animatingToHeightSpecific: 'rah-animating--to-height-specific',
    static: 'rah-static',
    staticHeightZero: 'rah-static--height-zero',
    staticHeightAuto: 'rah-static--height-auto',
    staticHeightSpecific: 'rah-static--height-specific',
};
function getStaticStateClasses(animationStateClasses, height) {
    return [
        animationStateClasses.static,
        height === 0 && animationStateClasses.staticHeightZero,
        typeof height === 'number' && height > 0
            ? animationStateClasses.staticHeightSpecific
            : null,
        height === 'auto' && animationStateClasses.staticHeightAuto,
    ]
        .filter((v) => v)
        .join(' ');
}
// ------------------ Component
const propsToOmitFromDiv = [
    'animateOpacity',
    'animationStateClasses',
    'applyInlineTransitions',
    'children',
    'className',
    'contentClassName',
    'contentRef',
    'delay',
    'duration',
    'easing',
    'height',
    'onHeightAnimationEnd',
    'onHeightAnimationStart',
    'style',
];
const AnimateHeight = react_1.default.forwardRef((componentProps, ref) => {
    // const AnimateHeight = forwardRef((componentProps: AnimateHeightProps, ref) => {
    // const AnimateHeight: React.FC<AnimateHeightProps> = (componentProps) => {
    const { animateOpacity = false, animationStateClasses = {}, applyInlineTransitions = true, children, className = '', contentClassName, delay: userDelay = 0, duration: userDuration = 500, easing = 'ease', height, onHeightAnimationEnd, onHeightAnimationStart, style, contentRef, } = componentProps;
    const divProps = Object.assign({}, componentProps);
    propsToOmitFromDiv.forEach((propKey) => {
        delete divProps[propKey];
    });
    // ------------------ Initialization
    const prevHeight = (0, react_1.useRef)(height);
    const contentElement = (0, react_1.useRef)(null);
    const animationClassesTimeoutID = (0, react_1.useRef)();
    const timeoutID = (0, react_1.useRef)();
    const stateClasses = (0, react_1.useRef)(Object.assign(Object.assign({}, ANIMATION_STATE_CLASSES), animationStateClasses));
    const isBrowser = typeof window !== 'undefined';
    const prefersReducedMotion = (0, react_1.useRef)(isBrowser && window.matchMedia
        ? window.matchMedia('(prefers-reduced-motion)').matches
        : false);
    const delay = prefersReducedMotion.current ? 0 : userDelay;
    const duration = prefersReducedMotion.current ? 0 : userDuration;
    let initHeight = height;
    let initOverflow = 'visible';
    if (typeof height === 'number') {
        // Reset negative height to 0
        initHeight = height < 0 ? 0 : height;
        initOverflow = 'hidden';
    }
    else if (isPercentage(initHeight)) {
        // If value is string "0%" make sure we convert it to number 0
        initHeight = height === '0%' ? 0 : height;
        initOverflow = 'hidden';
    }
    const [currentHeight, setCurrentHeight] = (0, react_1.useState)(initHeight);
    const [overflow, setOverflow] = (0, react_1.useState)(initOverflow);
    const [useTransitions, setUseTransitions] = (0, react_1.useState)(false);
    const [animationStateClassNames, setAnimationStateClassNames] = (0, react_1.useState)(getStaticStateClasses(stateClasses.current, height));
    // ------------------ Did mount
    (0, react_1.useEffect)(() => {
        // Hide content if height is 0 (to prevent tabbing into it)
        hideContent(contentElement.current, currentHeight);
        // This should be explicitly run only on mount
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, []);
    // ------------------ Height update
    (0, react_1.useEffect)(() => {
        if (height !== prevHeight.current && contentElement.current) {
            showContent(contentElement.current, prevHeight.current);
            // Cache content height
            contentElement.current.style.overflow = 'hidden';
            const contentHeight = contentElement.current.offsetHeight;
            contentElement.current.style.overflow = '';
            // set total animation time
            const totalDuration = duration + delay;
            let newHeight;
            let timeoutHeight;
            let timeoutOverflow = 'hidden';
            let timeoutUseTransitions;
            const isCurrentHeightAuto = prevHeight.current === 'auto';
            if (typeof height === 'number') {
                // Reset negative height to 0
                newHeight = height < 0 ? 0 : height;
                timeoutHeight = newHeight;
            }
            else if (isPercentage(height)) {
                // If value is string "0%" make sure we convert it to number 0
                newHeight = height === '0%' ? 0 : height;
                timeoutHeight = newHeight;
            }
            else {
                // If not, animate to content height
                // and then reset to auto
                newHeight = contentHeight; // TODO solve contentHeight = 0
                timeoutHeight = 'auto';
                timeoutOverflow = undefined;
            }
            if (isCurrentHeightAuto) {
                // This is the height to be animated to
                timeoutHeight = newHeight;
                // If previous height was 'auto'
                // set starting height explicitly to be able to use transition
                newHeight = contentHeight;
            }
            // Animation classes
            const newAnimationStateClassNames = [
                stateClasses.current.animating,
                (prevHeight.current === 'auto' || height < prevHeight.current) &&
                    stateClasses.current.animatingUp,
                (height === 'auto' || height > prevHeight.current) &&
                    stateClasses.current.animatingDown,
                timeoutHeight === 0 && stateClasses.current.animatingToHeightZero,
                timeoutHeight === 'auto' &&
                    stateClasses.current.animatingToHeightAuto,
                typeof timeoutHeight === 'number' && timeoutHeight > 0
                    ? stateClasses.current.animatingToHeightSpecific
                    : null,
            ]
                .filter((v) => v)
                .join(' ');
            // Animation classes to be put after animation is complete
            const timeoutAnimationStateClasses = getStaticStateClasses(stateClasses.current, timeoutHeight);
            // Set starting height and animating classes
            // When animating from 'auto' we first need to set fixed height
            // that change should be animated
            setCurrentHeight(newHeight);
            setOverflow('hidden');
            setUseTransitions(!isCurrentHeightAuto);
            setAnimationStateClassNames(newAnimationStateClassNames);
            // Clear timeouts
            clearTimeout(timeoutID.current);
            clearTimeout(animationClassesTimeoutID.current);
            if (isCurrentHeightAuto) {
                // When animating from 'auto' we use a short timeout to start animation
                // after setting fixed height above
                timeoutUseTransitions = true;
                // Short timeout to allow rendering of the initial animation state first
                timeoutID.current = setTimeout(() => {
                    setCurrentHeight(timeoutHeight);
                    setOverflow(timeoutOverflow);
                    setUseTransitions(timeoutUseTransitions);
                    // ANIMATION STARTS, run a callback if it exists
                    onHeightAnimationStart === null || onHeightAnimationStart === void 0 ? void 0 : onHeightAnimationStart(timeoutHeight);
                }, 50);
                // Set static classes and remove transitions when animation ends
                animationClassesTimeoutID.current = setTimeout(() => {
                    setUseTransitions(false);
                    setAnimationStateClassNames(timeoutAnimationStateClasses);
                    // ANIMATION ENDS
                    // Hide content if height is 0 (to prevent tabbing into it)
                    hideContent(contentElement.current, timeoutHeight);
                    // Run a callback if it exists
                    onHeightAnimationEnd === null || onHeightAnimationEnd === void 0 ? void 0 : onHeightAnimationEnd(timeoutHeight);
                }, totalDuration);
            }
            else {
                // ANIMATION STARTS, run a callback if it exists
                onHeightAnimationStart === null || onHeightAnimationStart === void 0 ? void 0 : onHeightAnimationStart(newHeight);
                // Set end height, classes and remove transitions when animation is complete
                timeoutID.current = setTimeout(() => {
                    setCurrentHeight(timeoutHeight);
                    setOverflow(timeoutOverflow);
                    setUseTransitions(false);
                    setAnimationStateClassNames(timeoutAnimationStateClasses);
                    // ANIMATION ENDS
                    // If height is auto, don't hide the content
                    // (case when element is empty, therefore height is 0)
                    if (height !== 'auto') {
                        // Hide content if height is 0 (to prevent tabbing into it)
                        hideContent(contentElement.current, newHeight); // TODO solve newHeight = 0
                    }
                    // Run a callback if it exists
                    onHeightAnimationEnd === null || onHeightAnimationEnd === void 0 ? void 0 : onHeightAnimationEnd(newHeight);
                }, totalDuration);
            }
        }
        prevHeight.current = height;
        return () => {
            clearTimeout(timeoutID.current);
            clearTimeout(animationClassesTimeoutID.current);
        };
        // This should be explicitly run only on height change
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [height]);
    // ------------------ Render
    const componentStyle = Object.assign(Object.assign({}, style), { height: currentHeight, overflow: overflow || (style === null || style === void 0 ? void 0 : style.overflow) });
    if (useTransitions && applyInlineTransitions) {
        componentStyle.transition = `height ${duration}ms ${easing} ${delay}ms`;
        // Include transition passed through styles
        if (style === null || style === void 0 ? void 0 : style.transition) {
            componentStyle.transition = `${style.transition}, ${componentStyle.transition}`;
        }
        // Add webkit vendor prefix still used by opera, blackberry...
        componentStyle.WebkitTransition = componentStyle.transition;
    }
    const contentStyle = {};
    if (animateOpacity) {
        contentStyle.transition = `opacity ${duration}ms ${easing} ${delay}ms`;
        // Add webkit vendor prefix still used by opera, blackberry...
        contentStyle.WebkitTransition = contentStyle.transition;
        if (currentHeight === 0) {
            contentStyle.opacity = 0;
        }
    }
    // Check if user passed aria-hidden prop
    const hasAriaHiddenProp = typeof divProps['aria-hidden'] !== 'undefined';
    const ariaHidden = hasAriaHiddenProp
        ? divProps['aria-hidden']
        : height === 0;
    return (react_1.default.createElement("div", Object.assign({}, divProps, { "aria-hidden": ariaHidden, className: `${animationStateClassNames} ${className}`, style: componentStyle, ref: ref }),
        react_1.default.createElement("div", { className: contentClassName, style: contentStyle, ref: (el) => {
                contentElement.current = el;
                if (contentRef) {
                    contentRef.current = el;
                }
            } }, children)));
});
exports.default = AnimateHeight;


/***/ }),

/***/ 7462:
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Z": function() { return /* binding */ _extends; }
/* harmony export */ });
function _extends() {
  _extends = Object.assign || function (target) {
    for (var i = 1; i < arguments.length; i++) {
      var source = arguments[i];

      for (var key in source) {
        if (Object.prototype.hasOwnProperty.call(source, key)) {
          target[key] = source[key];
        }
      }
    }

    return target;
  };

  return _extends.apply(this, arguments);
}

/***/ }),

/***/ 1721:
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";

// EXPORTS
__webpack_require__.d(__webpack_exports__, {
  "Z": function() { return /* binding */ _inheritsLoose; }
});

;// CONCATENATED MODULE: ./node_modules/@babel/runtime/helpers/esm/setPrototypeOf.js
function _setPrototypeOf(o, p) {
  _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) {
    o.__proto__ = p;
    return o;
  };

  return _setPrototypeOf(o, p);
}
;// CONCATENATED MODULE: ./node_modules/@babel/runtime/helpers/esm/inheritsLoose.js

function _inheritsLoose(subClass, superClass) {
  subClass.prototype = Object.create(superClass.prototype);
  subClass.prototype.constructor = subClass;
  _setPrototypeOf(subClass, superClass);
}

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	!function() {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = function(module) {
/******/ 			var getter = module && module.__esModule ?
/******/ 				function() { return module['default']; } :
/******/ 				function() { return module; };
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	!function() {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = function(exports, definition) {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	!function() {
/******/ 		__webpack_require__.o = function(obj, prop) { return Object.prototype.hasOwnProperty.call(obj, prop); }
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	!function() {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = function(exports) {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	}();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module is referenced by other modules so it can't be inlined
/******/ 	var __webpack_exports__ = __webpack_require__(5935);
/******/ 	
/******/ 	return __webpack_exports__;
/******/ })()
;
});;