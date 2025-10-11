<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class BestCheckout extends Module
{
    public function __construct()
    {
        $this->name = 'bestcheckout';
        $this->tab = 'checkout';
        $this->version = '2.1.0';
        $this->author = 'ENBL';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = ['min' => '8.2.0', 'max' => '9.0.0'];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Best Checkout');
        $this->description = $this->l('Ulepszony proces składania zamówienia z poziomą nawigacją kroków i dodatkami do podsumowania koszyka.');
    }

    public function install()
    {
        return parent::install()
            && $this->registerHook('displayCheckoutSummary')
            && $this->registerHook('displayCartSummaryAddon')
            && $this->registerHook('actionFrontControllerSetMedia');
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    public function hookDisplayCheckoutSummary($params)
    {
        $process = $this->context->controller->getCheckoutProcess();

        if ($process && method_exists($process, 'getSteps')) {
            $steps = [];

            foreach ($process->getSteps() as $step) {
                $steps[] = [
                    'title' => method_exists($step, 'getTitle') ? $step->getTitle() : 'Krok',
                    'is_current' => method_exists($step, 'isCurrent') ? $step->isCurrent() : false,
                    'is_complete' => method_exists($step, 'isCompleted') ? $step->isCompleted() : null,
                    'is_reachable' => method_exists($step, 'isReachable') ? $step->isReachable() : false,
                ];
            }

            $this->context->smarty->assign([
                'bestcheckout_steps' => $steps,
            ]);

            file_put_contents(_PS_ROOT_DIR_ . '/log_bestcheckout.txt', '[STEPS ASSIGNED IN HOOK] ' . count($steps) . " steps\n", FILE_APPEND);

            return $this->display(__FILE__, 'views/templates/front/_partials/navigation.tpl');
        }

        file_put_contents(_PS_ROOT_DIR_ . '/log_bestcheckout.txt', '[CHECKOUT PROCESS NOT AVAILABLE IN HOOK]' . "\n", FILE_APPEND);
        return '';
    }

    public function hookDisplayCartSummaryAddon($params)
    {
        $cart = $this->context->cart;

        $this->context->smarty->assign([
            'bestcheckout_cart_total' => $cart->getOrderTotal(),
            'bestcheckout_cart_products' => $cart->getProducts(),
        ]);

        file_put_contents(_PS_ROOT_DIR_ . '/log_bestcheckout.txt', '[CART SUMMARY ADDON RENDERED]' . "\n", FILE_APPEND);

        return $this->display(__FILE__, 'views/templates/front/_partials/cart-addon.tpl');
    }

    public function hookActionFrontControllerSetMedia()
    {
        if ($this->context->controller instanceof OrderController) {
            $this->context->controller->registerStylesheet(
                'module-bestcheckout-style',
                'modules/' . $this->name . '/views/css/bestcheckout.css',
                ['media' => 'all', 'priority' => 150]
            );
        }
    }

    public function hookActionFrontControllerInitAfter($params)
    {
        if ($this->context->controller instanceof OrderController) {
            $absolute_path = _PS_MODULE_DIR_ . $this->name . '/views/templates/front/theme-overrides/checkout/_partials/cart-summary.tpl';

            if (is_readable($absolute_path)) {
                file_put_contents(_PS_ROOT_DIR_ . '/log_bestcheckout.txt', '[OVERRIDE FOUND] ' . $absolute_path . "\n", FILE_APPEND);
            } else {
                file_put_contents(_PS_ROOT_DIR_ . '/log_bestcheckout.txt', '[OVERRIDE MISSING] ' . $absolute_path . "\n", FILE_APPEND);
            }

            file_put_contents(_PS_ROOT_DIR_ . '/log_bestcheckout.txt', '[INIT AFTER: ORDER CONTROLLER DETECTED]' . "\n", FILE_APPEND);
        } else {
            file_put_contents(_PS_ROOT_DIR_ . '/log_bestcheckout.txt', '[INIT AFTER: NOT ORDER CONTROLLER]' . "\n", FILE_APPEND);
        }
    }
}
