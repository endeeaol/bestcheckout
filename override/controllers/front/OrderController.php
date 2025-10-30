<?php
use PrestaShop\PrestaShop\Core\Checkout\CheckoutProcess;
use PrestaShop\PrestaShop\Core\Domain\Checkout\Exception\CheckoutException;

class OrderController extends OrderControllerCore
{
    public function initContent()
    {
        parent::initContent();

        $requestedStep = Tools::getValue('step');
        $validSteps = ['personal-information', 'address', 'delivery', 'payment', 'confirmation'];

        if (!in_array($requestedStep, $validSteps)) {
            $requestedStep = $this->getActiveStepIdentifier();
        }

        try {
            $this->setActiveStepByIdentifier($requestedStep);
        } catch (CheckoutException $e) {}

        $this->setTemplate('checkout/checkout');
    }

    protected function setActiveStepByIdentifier(string $identifier)
    {
        $process = $this->checkoutProcess;
        foreach ($process->getSteps() as $step) {
            $stepIdentifier = method_exists($step, 'getIdentifier')
                ? $step->getIdentifier()
                : (property_exists($step, 'identifier') ? $step->identifier : null);

            if ($stepIdentifier === $identifier) {
                $process->setCurrentStep($step);
                break;
            }
        }
    }

    protected function getActiveStepIdentifier()
    {
        $currentStep = $this->checkoutProcess->getCurrentStep();

        if (is_object($currentStep)) {
            if (method_exists($currentStep, 'getIdentifier')) {
                return $currentStep->getIdentifier();
            }
            if (property_exists($currentStep, 'identifier')) {
                return $currentStep->identifier;
            }
        }

        return 'personal-information';
    }

    public function postProcess()
    {
        parent::postProcess();

        if (Tools::isSubmit('submitAddress')) {
            Tools::redirect('index.php?controller=order&step=delivery');
        }

        if (Tools::isSubmit('submitDeliveryOption')) {
            Tools::redirect('index.php?controller=order&step=payment');
        }

        if (Tools::isSubmit('confirmOrder')) {
            Tools::redirect('index.php?controller=order&step=confirmation');
        }
    }
}
