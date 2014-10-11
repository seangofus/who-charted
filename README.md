# Jovial Who Charted Bundle

The Who Charted bundle extends the functionality of the Oro Chart bundle in order to use the Chart.js library. Out-of-the-box the Oro BAP Chart bundle uses the flotr2 library for charts. This bundle's goal is to add the Chart.js library as another option.

## Requirements

The Who Charted bundle is dependent on the Oro BAP, and as such has all the same requirements as the Oro BAP. Those requirements can be [viewed here](http://www.orocrm.com/system-requirements).

* PHP 5.4.9 or above
* Symfony 2.3 or above
* Oro BAP 1.4 or above

## Installation instructions

If your composer is up-to-date run the following command and proceed to party:

```bash
php /usr/local/bin/composer.phar require "jovial/who-charted"
```

Otherwise do it the old fashioned way and add the following line to your `composer.json` file:

```javascript
{
    "require": {
        // Other requirements
        "jovial/who-charted": "dev-master"
    }
}
```

Now you are ready to update composer.

```bash
php /usr/local/bin/composer.phar update
```
___

After composer finishes updating you need to clear your cache and install the assets.

```bash
php app/console cache:clear
```
```bash
php app/console assets:install
```

## Getting Started

Using Who Charted is not a whole lot different from using the native Oro Chart service. You will need to setup all the proper controllers, routes, and configurations to add a widget to the dashboard. Most of that is out of the scope of this document but can be [found here](https://github.com/orocrm/platform/tree/master/src/Oro/Bundle/DashboardBundle).

First gather your data.

```php
<?php
$data = $this->getDoctrine()
            ->getRepository('YourBundle:YourEntity')
            ->getYourRepoMethod($this->get('oro_security.acl_helper'));
```
The data must be in a certain format before it gets set on the widget attribute. For bar charts, line charts, and radar charts the data must look like this:

```php
<?php
array(
    'labels' => array("Label 1", "Label 2", "Label 3"),
    "datasets" => array(
        array('data' => array(100, 200, 300))
    )
);
```

For polar charts, pie charts, and doughnut charts the data must look like this:

```php
<?php
array(
    array('label' => 'Label 1', 'value' => 100),
    array('label' => 'Label 2', 'value' => 200),
    array('label' => 'Label 3', 'value' => 300)
);
```

Next you need to create a widget attribute.

```php
<?php
$widgetAttr = $this->get('oro_dashboard.widget_attributes')->getWidgetAttributesForTwig($widget);
```

Then you just call the Who Charted service and set the data and options and get the view.

```php
<?php
$widgetAttr['chartView'] = $this->get('who.charted')
            ->setArrayData($data)
            ->setOptions(
                array(
                    'name' => 'bar_chart',
                    'settings' => array(
                        'colors' => [['fillColor' => 'rgba(222,38,76,.6)', 'strokeColor' => 'rgba(188,13,53,.6)', 'highlightFill' => 'rgba(246,177,195,.6)', 'highlightStroke' => 'rgba(240,120,140,.6)']],
                        'barValueSpacing' => 50,
                        'animation' => true
                    )
                )
            )
            ->getView();

        return $widgetAttr;
```
Inside the `setOptions()` method you need to pass it the name of the chart you want to use. Currently there are 6 types that are available: bar_chart, line_chart, radar_chart, polar_chart, pie_chart, and doughnut_chart. Next you can override any of the chart's default settings using the optional `settings` array.

Inside the `settings` array you can override any of the chart type default values using the Chart.js keys and acceptable values. As an example of what defaults can be overridden, take a look at the Chart.js documentation for the bar chart ... http://www.chartjs.org/docs/#bar-chart-chart-options.

The `settings` array has one special sub-array that lets you specify colors for your chart. If no colors are specified then default colors will be used.

## Putting it together
#### src/YourNamespace/Bundle/YourBundle/Controller/Dashboard/DashboardController.php

Below is an example dashboard controller.

```php
<?php

namespace YourNamespace\Bundle\YourBundle\Controller\Dashboard;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Translation\TranslatorInterface;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Oro\Bundle\WorkflowBundle\Model\WorkflowManager;
use OroCRM\Bundle\SalesBundle\Entity\Repository\SalesFunnelRepository;

class DashboardController extends Controller
{
    /**
     * @Route(
     *      "/your_route/chart/{widget}",
     *      name="your_route_name",
     *      requirements={"widget"="[\w-]+"}
     * )
     * @Template("YourNamespaceYourBundle:Dashboard:exampleChart.html.twig")
     */
    public function exampleChartAction($widget)
    {
        $data = $this->getDoctrine()
            ->getRepository('JovTestBundle:TestEntity')
            ->getOpportunitiesByStatus($this->get('oro_security.acl_helper'));

        $widgetAttr = $this->get('oro_dashboard.widget_attributes')->getWidgetAttributesForTwig($widget);

        $widgetAttr['chartView'] = $this->get('who.charted')
            ->setArrayData($data)
            ->setOptions(
                array(
                    'name' => 'bar_chart',
                    'settings' => array(
                        'colors' => [
                            [
                                'fillColor' => 'rgba(222,38,76,.6)',
                                'strokeColor' => 'rgba(188,13,53,.6)',
                                'highlightFill' => 'rgba(246,177,195,.6)',
                                'highlightStroke' => 'rgba(240,120,140,.6)'
                            ]
                        ],
                        'barValueSpacing' => 50,
                        'animation' => true
                    )
                )
            )
            ->getView();

        return $widgetAttr;
    }
}
```
#### src/YourNamespace/Bundle/YourBundle/Resources/views/Dashboard/exampleChart.html.twig

Below is an example view file.

```twig
{% extends 'OroDashboardBundle:Dashboard:chartWidget.html.twig' %}

{% block content %}
    {{ chartView.render()|raw }}
{% endblock %}

```
## Credits
[Sean Gofus](http://www.seangofus.com) | [@seangofus](http://www.twitter.com/seangofus)
