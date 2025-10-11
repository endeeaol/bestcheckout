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
        $this->author = 'ENBL & Moduły Prestashop 8.2';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = ['min' => '8.2.0', 'max' => '9.0.0'];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Best Checkout');
        $this->description = $this->l('Dostarcza zmienną z aktualnym krokiem zamówienia do szablonu Smarty.');
    }

    /**
     * Instalacja modułu - rejestrujemy DWA kluczowe hooki.
     */
    public function install()
    {
        return parent::install()
            && $this->registerHook('actionFrontControllerSetVariables')
            && $this->registerHook('actionFrontControllerSetMedia');
    }

    /**
     * To jest serce naszego modułu.
     * Uruchamia się tuż przed renderowaniem szablonu i wstrzykuje naszą zmienną.
     */
    public function hookActionFrontControllerSetVariables(array &$params)
    {
        // Sprawdzamy, czy jesteśmy na stronie zamówienia
        if ($this->context->controller->php_self === 'order') {
            
            $checkoutProcess = $this->context->controller->getCheckoutProcess();

            if (Validate::isLoadedObject($checkoutProcess)) {
                $currentStepIdentifier = $checkoutProcess->getSelectedStepIdentifier();
                
                // Przypisujemy kluczową zmienną do Smarty
                $this->context->smarty->assign('current_step_identifier', $currentStepIdentifier);
            }
        }
    }

    /**
     * Ten hook dołącza nasz plik CSS do strony zamówienia.
     */
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