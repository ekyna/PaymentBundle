<?php

namespace Ekyna\Bundle\PaymentBundle\Twig;

use Ekyna\Bundle\PaymentBundle\Model\MethodInterface;
use Ekyna\Bundle\PaymentBundle\Model\PaymentStates;
use Ekyna\Bundle\PaymentBundle\Model\PaymentTransitions;
use Ekyna\Component\Sale\Payment\PaymentInterface;
use Ekyna\Component\Sale\Payment\PaymentTransitions as Transitions;
use SM\Factory\FactoryInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class PaymentExtension
 * @package Ekyna\Bundle\PaymentBundle\Twig
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class PaymentExtension extends \Twig_Extension
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var UrlGeneratorInterface
     */
    protected $urlGenerator;

    /**
     * @var FactoryInterface
     */
    protected $factory;


    /**
     * Constructor.
     *
     * @param TranslatorInterface   $translator
     * @param UrlGeneratorInterface $urlGenerator
     * @param FactoryInterface      $factory
     */
    public function __construct(
        TranslatorInterface $translator,
        UrlGeneratorInterface $urlGenerator,
        FactoryInterface $factory
    ) {
        $this->translator = $translator;
        $this->urlGenerator = $urlGenerator;
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('payment_state_label', array($this, 'getPaymentStateLabel'), array('is_safe' => array('html'))),
            new \Twig_SimpleFilter('payment_state_badge', array($this, 'getPaymentStateBadge'), array('is_safe' => array('html'))),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('render_payment_actions', array($this, 'renderPaymentActions'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('render_method_config',   array($this, 'renderMethodConfig'),   array('is_safe' => array('html'))),
        );
    }

    /**
     * Returns the payment state label.
     *
     * @param string|PaymentInterface $stateOrPayment
     * @return string
     */
    public function getPaymentStateLabel($stateOrPayment)
    {
        $state = $stateOrPayment instanceof PaymentInterface ? $stateOrPayment->getState() : $stateOrPayment;

        return $this->translator->trans(PaymentStates::getLabel($state));
    }

    /**
     * Returns the payment state badge.
     *
     * @param string|PaymentInterface $stateOrPayment
     * @return string
     */
    public function getPaymentStateBadge($stateOrPayment)
    {
        $state = $stateOrPayment instanceof PaymentInterface ? $stateOrPayment->getState() : $stateOrPayment;

        return sprintf(
            '<span class="label label-%s">%s</span>',
            PaymentStates::getTheme($state),
            $this->getPaymentStateLabel($state)
        );
    }

    /**
     * Renders the payment actions buttons.
     *
     * @param PaymentInterface $payment
     * @param string $route
     * @param array  $routeParameters
     * @return string
     */
    public function renderPaymentActions(PaymentInterface $payment, $route, array $routeParameters)
    {
        if ($payment->getMethod()->getFactoryName() !== 'offline') {
            return '';
        }

        $sm = $this->factory->get($payment);

        $buttons = [];
        $model = '<a href="%s" class="btn btn-%s btn-xs" onclick="confirm(\'Souhaitez-vous réellement %s le payment ?\');">%s</a>';

        foreach (Transitions::getManualTransitions() as $transition) {
            if ($sm->can($transition)) {
                $label = $this->translator->trans(PaymentTransitions::getLabel($transition));
                $path = $this->urlGenerator->generate($route, array_merge(
                    $routeParameters, array('transition' => $transition)
                ));
                $theme = PaymentTransitions::getTheme($transition);
                $buttons[] = sprintf($model, $path, $theme, strtolower($label), $label);
            }
        }

        return implode('', $buttons);
    }

    /**
     * Renders the method config.
     *
     * @param MethodInterface $method
     * @return string
     */
    public function renderMethodConfig(MethodInterface $method)
    {
        $output = '<dl class="dl-horizontal">';

        foreach ($method->getConfig() as $key => $value) {
            if (is_array($value)) {
                continue;
            }

            $output .= sprintf('<dt>%s</dt><dd>%s</dd>', $key, $value);
        }

        $output .= '</dl>';

        return $output;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_payment';
    }
}
