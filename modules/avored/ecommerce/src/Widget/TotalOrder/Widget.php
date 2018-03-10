<?php

namespace AvoRed\Ecommerce\Widget\TotalOrder;

use AvoRed\Ecommerce\Models\Database\Order;
use AvoRed\Framework\Widget\Contracts\Widget as WidgetContract;

class Widget implements WidgetContract
{

    /**
     *
     * Widget View Path
     *
     * @var string $view
     */

    protected $view = "avored-ecommerce::widget.total-order";


    /**
     *
     * Widget Label
     *
     * @var string $view
     */

    protected $label = 'Total Order';


    /**
     *
     * Widget Type
     *
     * @var string $view
     */

    protected $type = "dashboard";

    /**
     *
     * Widget unique identifier
     *
     * @var string $identifier
     */
    protected $identifier = "total-order";


    public function view()
    {
        return $this->view;
    }

    /*
     * Widget unique identifier
     *
     */
    public function identifier()
    {

        return $this->identifier;
    }

    /*
    * Widget unique identifier
    *
    */
    public function label()
    {

        return $this->label;
    }

    /*
    * Widget unique identifier
    *
    */
    public function type()
    {

        return $this->type;
    }

    /**
     * View Required Parameters
     *
     * @return array
     */
    public function with()
    {

        $totalUser = Order::all()->count();
        return ['totalOrder' => $totalUser];
    }

}