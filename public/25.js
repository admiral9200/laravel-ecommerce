(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[25],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./packages/framework/resources/components/catalog/property/PropertySave.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./packages/framework/resources/components/catalog/property/PropertySave.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var lodash_isNil__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! lodash/isNil */ "./node_modules/lodash/isNil.js");
/* harmony import */ var lodash_isNil__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(lodash_isNil__WEBPACK_IMPORTED_MODULE_0__);

var id = 0;
/* harmony default export */ __webpack_exports__["default"] = ({
  props: ["property", "baseUrl"],
  data: function data() {
    return {
      propertyForm: this.$form.createForm(this),
      dropdownOptions: [],
      processDropdownOptionStatus: false
    };
  },
  methods: {
    handleSubmit: function handleSubmit() {
      this.propertyForm.validateFields(function (err, values) {
        if (err) {
          e.preventDefault();
        }
      });
    },
    fieldTypeChange: function fieldTypeChange(val) {
      this.field_type = val;

      if (val === "SELECT" || val === "RADIO") {
        this.dropdownOptions.push(this.randomString());
      } else {
        this.dropdownOptions = [];
      }
    },
    cancelProperty: function cancelProperty() {
      window.location = this.baseUrl + "/property";
    },
    randomString: function randomString() {
      var random_string = "";
      var random_ascii;

      for (var i = 0; i < 6; i++) {
        random_ascii = Math.floor(Math.random() * 25 + 97);
        random_string += String.fromCharCode(random_ascii);
      }

      return random_string;
    },
    getInitDropdownValue: function getInitDropdownValue(index) {
      if (lodash_isNil__WEBPACK_IMPORTED_MODULE_0___default()(this.property.dropdown_options[index]["display_text"])) {
        return '';
      }

      return this.property.dropdown_options[index]["display_text"];
    },
    dropdownOptionChange: function dropdownOptionChange(index) {
      if (index == this.dropdownOptions.length - 1) {
        this.dropdownOptions.push(this.randomString());
      } else {
        this.dropdownOptions.splice(index, 1);
      }
    },
    dropdown_options: function dropdown_options(index) {
      return "dropdown_option[" + index + "]";
    }
  },
  mounted: function mounted() {
    var _this = this;

    if (!lodash_isNil__WEBPACK_IMPORTED_MODULE_0___default()(this.property)) {
      this.is_visible_frontend = this.property.is_visible_frontend;
      this.use_for_all_products = this.property.use_for_all_products;
      this.data_type = this.property.data_type;
      this.field_type = this.property.field_type;

      if (!lodash_isNil__WEBPACK_IMPORTED_MODULE_0___default()(this.property.dropdown_options) && this.property.dropdown_options.length > 0) {
        this.property.dropdown_options.forEach(function (element) {
          _this.dropdownOptions.push(element.id);
        });
      }

      this.processDropdownOptionStatus = true;
    }
  }
});

/***/ }),

/***/ "./packages/framework/resources/components/catalog/property/PropertySave.vue":
/*!***********************************************************************************!*\
  !*** ./packages/framework/resources/components/catalog/property/PropertySave.vue ***!
  \***********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _PropertySave_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./PropertySave.vue?vue&type=script&lang=js& */ "./packages/framework/resources/components/catalog/property/PropertySave.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");
var render, staticRenderFns




/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_1__["default"])(
  _PropertySave_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"],
  render,
  staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "packages/framework/resources/components/catalog/property/PropertySave.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./packages/framework/resources/components/catalog/property/PropertySave.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************!*\
  !*** ./packages/framework/resources/components/catalog/property/PropertySave.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_PropertySave_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./PropertySave.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./packages/framework/resources/components/catalog/property/PropertySave.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_PropertySave_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ })

}]);