<?php

namespace Jov\Bundle\WhoChartedBundle\Model;

use Oro\Bundle\ChartBundle\Model\ChartViewBuilder;
use Oro\Bundle\ChartBundle\Model\Data\ArrayData;

class WhoChartedBuilder extends ChartViewBuilder
{
    /**
     * Get chart view vars
     *
     * @return array
     * @throws BadMethodCallException
     * @throws InvalidArgumentException
     */
    protected function getVars()
    {
        $options = $this->options;

        if (null === $options) {
            throw new BadMethodCallException('Can\'t build result when setOptions() was not called.');
        }

        $config = $this->getChartConfig();

        if (!isset($config['template'])) {
            throw new InvalidArgumentException(
                sprintf('Config of chart "%s" must have "template" key.', $this->options['name'])
            );
        }

        $options['settings'] = array_replace_recursive($config['default_settings'], $options['settings']);

        if (isset($options['settings']['colors'])) {
            $this->setChartColors($options['settings']['colors']);
        }

        return array(
            'options' => $options,
            'config' => $config
        );
    }

    /**
     * Build chart view
     *
     * @return ChartView
     */
    public function getView()
    {
        $vars = $this->getVars();
        $data = $this->getData($vars);

        return new ChartView($this->twig, $vars['config']['template'], $data, $vars);
    }

    protected function setChartColors( $colors )
    {
        $data = $this->data->toArray();

        if (isset($data['datasets'])) {
            foreach ($data['datasets'] as $dataset_key => &$dataset) {

                if (isset($colors[$dataset_key])) {
                    foreach ($colors[$dataset_key] as $color_key => $color) {
                        $dataset[$color_key] = $color;
                    }
                } else {
                    $chart_config = $this->getChartConfig();
                    $default_colors = $chart_config['default_settings']['colors'];

                    if (isset($default_colors[$dataset_key])) {
                        foreach ($default_colors[$dataset_key] as $color_key => $color) {
                            $dataset[$color_key] = $color;
                        }
                    } else {
                        foreach ($default_colors[0] as $color_key => $color) {
                            $dataset[$color_key] = $color;
                        }
                    }
                }
            }
        } else {
            foreach ($data as $data_key => &$data_value) {
                if (isset($colors[$data_key])) {
                    foreach ($colors[$data_key] as $color_key => $color) {
                        $data[$data_key][$color_key] = $color;
                    }
                } else {
                    $chart_config = $this->getChartConfig();
                    $default_colors = $chart_config['default_settings']['colors'];

                    if (isset($default_colors[$data_key])) {
                        foreach ($default_colors[$data_key] as $color_key => $color) {
                            $data[$data_key][$color_key] = $color;
                        }
                    } else {
                        foreach ($default_colors[0] as $color_key => $color) {
                            $data[$data_key][$color_key] = $color;
                        }
                    }

                }
            }
        }

        $this->setArrayData($data);
    }
}
