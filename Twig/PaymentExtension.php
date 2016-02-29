<?php

namespace Ekyna\Bundle\PaymentBundle\Twig;

use Ekyna\Bundle\PaymentBundle\Model\PaymentStates;
use Ekyna\Bundle\PaymentBundle\Model\PaymentTransitions;
use Ekyna\Component\Sale\Payment\MethodInterface;
use Ekyna\Component\Sale\Payment\PaymentInterface;
use Ekyna\Component\Sale\Payment\PaymentTransitions as Transitions;
use SM\Factory\FactoryInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class PaymentExtension
 * @package Ekyna\Bundle\PaymentBundle\Twig
 * @author  Étienne Dauvergne <contact@ekyna.com>
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
     * @param TranslatorInterface $translator
     * @param UrlGeneratorInterface $urlGenerator
     * @param FactoryInterface $factory
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
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('get_payment_state', array(
                $this,
                'getPaymentState',
            ), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('render_payment_state', array(
                $this,
                'renderPaymentState',
            ), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('render_payment_actions', array(
                $this,
                'renderPaymentActions',
            ), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('render_method_config', array(
                $this,
                'renderMethodConfig',
            ), array('is_safe' => array('html'))),
        );
    }

    /**
     * Renders the translated payment state.
     *
     * @param string $state
     *
     * @return string
     */
    public function getPaymentState($state)
    {
        return $this->translator->trans(PaymentStates::getLabel($state));
    }

    /**
     * Renders the payment state label.
     *
     * @param PaymentInterface $payment
     *
     * @return string
     */
    public function renderPaymentState(PaymentInterface $payment)
    {
        $state = $payment->getState();
        return sprintf(
            '<span class="label label-%s">%s</span>',
            PaymentStates::getTheme($state),
            $this->getPaymentState($state)
        );
    }

    /**
     * Renders the payment actions buttons.
     *
     * @param PaymentInterface $payment
     * @param string           $route
     * @param array            $routeParameters
     *
     * @return string
     */
    public function renderPaymentActions(PaymentInterface $payment, $route, array $routeParameters)
    {
        $buttons = [];
        $sm = $this->factory->get($payment);

        if ($payment->getMethod()->getFactoryName() !== 'offline') {
            if ($sm->can(Transitions::TRANSITION_CANCEL)) {
                $date = null !== $payment->getUpdatedAt() ? $payment->getUpdatedAt() : $payment->getCreatedAt();
                if ($date < new \DateTime('-1 day')) {
                    $buttons[] = $this->buildPaymentActionButton(Transitions::TRANSITION_CANCEL, $route, $routeParameters);
                }
            }
        } else {
            foreach (Transitions::getManualTransitions() as $transition) {
                if ($sm->can($transition)) {
                    $buttons[] = $this->buildPaymentActionButton($transition, $route, $routeParameters);
                }
            }
        }

        return implode('', $buttons);
    }

    /**
     * Builds the payment action button.
     *
     * @param string $transition
     * @param string $route
     * @param array  $routeParameters
     *
     * @return string
     */
    private function buildPaymentActionButton($transition, $route, array $routeParameters)
    {
        $label = $this->translator->trans(PaymentTransitions::getLabel($transition));
        $path = $this->urlGenerator->generate($route, array_merge(
            $routeParameters, array('transition' => $transition))
        );
        $theme = PaymentTransitions::getTheme($transition);
        $model = '<a href="%s" class="btn btn-%s btn-xs" onclick="confirm(\'Souhaitez-vous réellement %s le payment ?\');">%s</a>';
        return sprintf($model, $path, $theme, strtolower($label), $label);
    }

    /**
     * Renders the method config.
     *
     * @param MethodInterface $method
     *
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

        $output .= '</dl>&nbsp;';

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
