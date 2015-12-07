<?php

namespace Ekyna\Bundle\PaymentBundle\Controller\Admin;

use Ekyna\Bundle\AdminBundle\Controller\Resource\SortableTrait;
use Ekyna\Bundle\AdminBundle\Controller\Resource\TinymceTrait;
use Ekyna\Bundle\AdminBundle\Controller\Resource\ToggleableTrait;
use Ekyna\Bundle\AdminBundle\Controller\ResourceController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class MethodController
 * @package Ekyna\Bundle\PaymentBundle\Controller\Admin
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class MethodController extends ResourceController
{
    use ToggleableTrait,
        TinymceTrait,
        SortableTrait;

    /**
     * {@inheritdoc}
     */
    public function newAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            throw new NotFoundHttpException('Method creation through XMLHttpRequest is not yet implemented.');
        }

        $this->isGranted('CREATE');

        $context = $this->loadContext($request);

        $resource = $this->createNew($context);
        $resourceName = $this->config->getResourceName();
        $context->addResource($resourceName, $resource);


        $flow = $this->get('ekyna_payment.method_create.form_flow');
        $flow->bind($resource);

        $form = $flow->createForm();
        if ($flow->isValid($form)) {
            $flow->saveCurrentStepData($form);

            if ($flow->nextStep()) {
                // form for the next step
                $form = $flow->createForm();
            } else {
                // TODO use ResourceManager
                $event = $this->getOperator()->create($resource);

                $event->toFlashes($this->getFlashBag());

                if (!$event->hasErrors()) {
                    return $this->redirect($this->generateUrl(
                        $this->config->getRoute('show'),
                        $context->getIdentifiers(true)
                    ));
                }
            }
        }

        $this->appendBreadcrumb(sprintf('%s-new', $resourceName), 'ekyna_core.button.create');

        return $this->render(
            $this->config->getTemplate('new.html'),
            $context->getTemplateVars(array(
                'flow' => $flow,
                'form' => $form->createView()
            ))
        );
    }
}
