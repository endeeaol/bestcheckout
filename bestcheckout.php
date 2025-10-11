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
        $this->version = '2.1.1'; // Podbijam wersję
        $this->author = 'ENBL & Moduły Prestashop 8.2';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = ['min' => '8.2.0', 'max' => '9.0.0'];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Best Checkout');
        $this->description = $this->l('Dostarcza zmienną z aktualnym krokiem zamówienia do szablonu Smarty.');
    }

    public function install()
    {
        return parent::install()
            && $this->registerHook('actionFrontControllerSetVariables')
            && $this->registerHook('actionFrontControllerSetMedia');
    }

    public function hookActionFrontControllerSetVariables(array &$params)
    {
        if ($this->context->controller->php_self === 'order') {
            
            $checkoutProcess = $this->context->controller->getCheckoutProcess();

            if (is_object($checkoutProcess)) {
                
                // === TUTAJ JEST KLUCZOWA POPRAWKA ===
                // 1. Pobieramy obiekt aktualnego kroku
                $currentStep = $checkoutProcess->getCurrentStep();

                // 2. Dopiero z obiektu kroku pobieramy jego identyfikator
                if (is_object($currentStep)) {
                    $currentStepIdentifier = $currentStep->getIdentifier();
                    $this->context->smarty->assign('current_step_identifier', $currentStepIdentifier);
                }
            }
        }
    }

    public function hookActionFrontControllerSetMedia()
    {
        if ($this->context->controller->php_self === 'order') {
            $this->context->controller->registerStylesheet(
                'module-bestcheckout-style',
                'modules/' . $this->name . '/views/css/bestcheckout.css',
                ['media' => 'all', 'priority' => 200]
            );
        }
    }
}