<?php

namespace Ekyna\Bundle\PaymentBundle\Install;

use Ekyna\Bundle\CmsBundle\Entity\Image;
use Ekyna\Bundle\InstallBundle\Install\OrderedInstallerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class PaymentInstaller
 * @package Ekyna\Bundle\PaymentBundle\Install
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class PaymentInstaller implements OrderedInstallerInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Sets the container.
     *
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function install(Command $command, InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>[Payment] Creating default methods:</info>');
        $this->createPaymentMethods($output);
        $output->writeln('');
    }

    /**
     * Creates default payment methods entities.
     *
     * @param OutputInterface $output
     */
    private function createPaymentMethods(OutputInterface $output)
    {
        $em = $this->container->get('ekyna_payment.method.manager');
        //$registry = $this->container->get('payum');
        $repository = $this->container->get('ekyna_payment.method.repository');
        $imageDir = realpath(__DIR__.'/../Resources/asset/img');

        $methods = array(
            'Chèque'   => array('offline', 'cheque.png', '<p>Veuillez adresser votre chèque à l\'ordre de ...</p>'),
            'Virement' => array('offline', 'virement.png', '<p>Veuillez adresser votre virement à l\'ordre de ...</p>'),
            'Paypal'   => array('paypal_express_checkout_nvp', 'paypal.png', '<p>Réglez avec votre compte paypal, ou votre carte bancaire.</p>'),
        );

        foreach ($methods as $name => $options) {
            $output->write(sprintf(
                '- <comment>%s</comment> %s ',
                $name,
                str_pad('.', 44 - mb_strlen($name), '.', STR_PAD_LEFT)
            ));

            // TODO check that factiory method exists

            if (null !== $method = $repository->findOneBy(array('paymentName' => $name))) {
                $output->writeln('already exists.');
                continue;
            }

            $image = new Image();
            $image
                ->setFile(new File($imageDir.'/'.$options[1]))
                ->setAlt($name)
            ;

            /** @var \Ekyna\Bundle\PaymentBundle\Entity\Method $method */
            $method = $repository->createNew();
            $method
                ->setPaymentName($name)
                ->setFactoryName($options[0])
                ->setImage($image)
                ->setDescription($options[2])
            ;

            $em->persist($method);

            $output->writeln('created.');
        }
        $em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 0;
    }
}
