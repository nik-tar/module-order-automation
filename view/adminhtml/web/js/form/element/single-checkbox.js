define([
  'Magento_Ui/js/form/element/single-checkbox',
  'ko',
  'jquery',
  'underscore'
], function(Abstract, ko, $, _) {
  'use strict';

  return Abstract.extend({
    defaults: {
      /**
       * That's good to have, because we can use it in imports and
       * hide some fields if this checkbox is unchecked
       */
      invertedChecked: true
    },

    initObservable: function () {
      this._super();

      this.invertedChecked = ko.computed(function () {
        return !this.checked();
      }.bind(this));

      return this;
    },

    getReverseValueMap: function (value) {
      var bool = false;

      _.some(this.valueMap, function(iValue, iBool) {
        // default function works only with boolean types
        // but sometimes checkbox value can be also integer
        if (iValue === value ||
          $.isNumeric(iValue) && parseInt(iValue, 10) === value
        ) {
          bool = iBool === 'true';

          return true;
        }
      });

      return bool;
    }
  });
});
