define(function(require) {
    'use strict';

    var DoughnutChartComponent,
        Chart = require('Chart'),
        BaseChartComponent = require('jovwhocharted/js/app/components/base-chart-component'),
        mediator = require('oroui/js/mediator'),
        _ = require('underscore');

    DoughnutChartComponent = BaseChartComponent.extend({
        /**
         *
         * @overrides
         * @param {Object} options
         */
        initialize: function(options) {
            DoughnutChartComponent.__super__.initialize.call(this, options);
        },

        setChartSize: function() {
            var isChanged = false;
            var $container = this.$container;
            var $chart = this.$chart;
            var $widgetContent = $container.parent();
            var chartWidth = $widgetContent.width();

            var width = chartWidth;
            var height = Math.min(Math.round(chartWidth * 0.4), 350);

            this.$chartCanvas.attr('width', width);
            this.$chartCanvas.attr('height', height);
            isChanged = true;

            return isChanged;
        },

        draw: function() {
            var data = this.data;
            var settings = this.options.settings;

            var ctx = this.$chartCanvas.get(0).getContext("2d");

            if(this.hasOwnProperty('whoChartedChart')) {
                if(this.whoChartedChart.id in Chart.instances) {
                    this.whoChartedChart.destroy();
                    this.whoChartedChart = new Chart(ctx).Doughnut(data, settings);
                }
            } else {
                this.whoChartedChart = new Chart(ctx).Doughnut(data, settings);
            }

            if(typeof this.legend !== 'undefined' && this.legendCreated === null) {
                DoughnutChartComponent.__super__.createLegend.call(this, this.whoChartedChart);
            }
            mediator.trigger('who-charted-drawn');
        }
    });

    return DoughnutChartComponent;
});
