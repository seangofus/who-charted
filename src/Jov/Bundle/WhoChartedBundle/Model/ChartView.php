<?php

namespace Jov\Bundle\WhoChartedBundle\Model;

use Oro\Bundle\ChartBundle\Model\ChartView as OroChartView;

class ChartView extends OroChartView
{

    /**
     * Render chart
     *
     * @return string
     */
    public function render()
    {
        $context = $this->vars;
        $context['data'] = $this->data->toArray();

        if (isset($context['options']['settings']['colors'])) {
            unset($context['options']['settings']['colors']);
        }

        return $this->twig->render($this->template, $context);
    }

}
