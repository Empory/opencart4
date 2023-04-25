<?php

namespace Opencart\Catalog\Controller\Extension\Lets\Startup;

class Lets extends \Opencart\System\Engine\Controller
{
    public function index(): void
    {
        if ($this->config->get('theme_lets_status')) {
            $this->event->register('view/*/before', new \Opencart\System\Engine\Action('extension/lets/startup/lets|event'));
        }
    }

    public function event(string &$route, array &$args, mixed &$output): void
    {
        $override = [
            'common/header',
            'common/menu',
            'common/cart',
            'common/search',
            'common/home',
            'product/thumb',
            'product/product',
            'account/account_form',
            'account/address',
            'account/edit',
            'account/forgotton_reset',
            'account/forgotton',
            'account/newsletter',
            'account/password',
            'account/payment_method',
            'account/returns_form',
        ];

        if (in_array($route, $override)) {

            $args['inline_style'] = html_entity_decode('<style>
                :root {
                    --primary-color: #020201;
                    --secondary-color: #d2dff0;
                    --tertiary-color: #cc11dd;
                    // --danger-color: #ff3c20;
                    --danger-color: #020e24;
                    --warning-color: #00ccaa;
                    --dark-color: #232f3e;
                    // --dark-color: #0053bb;
                    --footer-bg: #19191a;
                }
            </style>', ENT_QUOTES, 'UTF-8');

            if (isset($_GET['product_id'])) {
                $args['scripts'] = '';
                $args["product_scripts"] = array();
                $args["product_styles"] = array();
                $args["product_scripts"][] = "catalog/view/javascript/jquery/datetimepicker/moment.min.js";
                $args["product_scripts"][] = "catalog/view/javascript/jquery/datetimepicker/moment-with-locales.min.js";
                $args["product_scripts"][] = "catalog/view/javascript/jquery/datetimepicker/daterangepicker.js";
                $args["product_scripts"][] = "catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js";
                $args["product_styles"][] = "catalog/view/javascript/jquery/datetimepicker/daterangepicker.css";
            } else {
                $args["product_scripts"] = array();
                $args["product_styles"] = array();
            }
            ### .product_scripts ###

            $route = 'extension/lets/' . $route;
        }
    }
}
