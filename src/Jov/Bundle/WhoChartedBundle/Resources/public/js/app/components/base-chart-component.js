define(function(require) {
    'use strict';

    var BaseChartComponent,
        _ = require('underscore'),
        $ = require('jquery'),
        chartTemplate = require('text!jovwhocharted/js/templates/base-chart-template.html'),
        BaseComponent = require('oroui/js/app/components/base/component');

    /**
     * @class orochart.app.components.BaseChartComponent
     * @extends oroui.app.components.base.Component
     * @exports orochart/app/components/base-chart-component
     */
    BaseChartComponent = BaseComponent.extend({
        template: _.template(chartTemplate),

        /**
         *
         * @constructor
         * @param {Object} options
         */
        initialize: function(options) {
            var updateHandler;
            this.data = options.data;
            this.options = options.options;
            this.config = options.config;

            if(this.options.settings.legend) {
                this.legend = this.options.settings.legend;
                this.legendCreated = null;
            }

            this.$el = $(options._sourceElement);
            this.$chart = null;

            this.renderBaseLayout();

            updateHandler = _.bind(this.update, this);
            this.$chart.bind('update.' + this.cid, updateHandler);
            // updates the chart on resize once per frame (1000/25)
            $(window).bind('resize.' + this.cid, _.throttle(updateHandler, 40, {leading: false}));
            $(window).bind('responsive-reflow.' + this.cid, updateHandler);

            _.defer(updateHandler);
        },

        /**
         * Dispose all event handlers
         *
         * @overrides
         */
        dispose: function() {
            this.$chart.unbind('.' + this.cid);
            $(window).unbind('.' + this.cid);
            delete this.$el;
            delete this.$chart;
            delete this.$container;
            BaseChartComponent.__super__.dispose.call(this);
        },

        renderBaseLayout: function() {
            this.$el.html(this.template());
            this.$chart = this.$el.find('.chart-content');
            this.$container = this.$el.find('.chart-container');
            this.$chartCanvas = this.$chart.find('.who-charted-chart');
        },

        /**
         * Update chart size and redraw
         */
        update: function() {
            var isChanged = this.setChartSize();
            if(isChanged) {
                this.draw();
                //HANDLE LEGEND UPDATE
            }
        },

        /**
         * Set size of chart
         *
         * @returns {boolean}
         */
        setChartSize: function() {
            var $chart = this.$chart;
            var $widgetContent = $chart.parents('.chart-container').parent();
            var chartWidth = Math.round($widgetContent.width() * 0.9);

            if (chartWidth > 0 && chartWidth !== $chart.width()) {
                $chart.width(chartWidth);
                $chart.height(Math.min(Math.round(chartWidth * 0.4), 350));
                return true;
            }
            return false;
        },

        /**
         * Draw comonent
         */
        draw: function() {
            this.$el.html('component renders here');
        },

        /*
         * Generate Chart Legend
         */
        createLegend: function(chart) {
            if(typeof this.legend !== 'undefined') {
                var legendMarkup = chart.generateLegend();
                this.$container.after(legendMarkup);
                this.legendCreated = true;
            }
        }
    });

    return BaseChartComponent;
});
