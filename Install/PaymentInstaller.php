<?php

namespace Ekyna\Bundle\PaymentBundle\Install;

use Ekyna\Bundle\MediaBundle\Entity\Folder;
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
     * Creates the payment images folder.
     *
     * @return Folder
     */
    private function createImageFolder()
    {
        $em = $this->container->get('ekyna_payment.method.manager');
        $folderRepository = $this->container->get('ekyna_media.folder.repository');

        if (null === $rootFolder = $folderRepository->findRoot()) {
            throw new \RuntimeException('Can\'t find root folder. Please run MediaBundle installer first.');
        }

        $name = 'Payment method';

        $paymentFolder = $folderRepository->findOneBy(array(
            'name' => $name,
            'parent' => $rootFolder,
        ));
        if (null !== $paymentFolder) {
            return $paymentFolder;
        }

        $paymentFolder = new Folder();
        $paymentFolder
            ->setName($name)
            ->setParent($rootFolder)
        ;

        $em->persist($paymentFolder);
        $em->flush();

        return $paymentFolder;
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
        $methodRepository = $this->container->get('ekyna_payment.method.repository');
        $mediaRepository = $this->container->get('ekyna_media.media.repository');

        $folder = $this->createImageFolder();
        $imageDir = realpath(__DIR__.'/../Resources/asset/img');

        $methods = array(
            'Chèque'   => array(
                'offline',
                'cheque.png',
                '<p>Veuillez adresser votre chèque à l\'ordre de ...</p>',
                true
            ),
            'Virement' => array(
                'offline',
                'virement.png',
                '<p>Veuillez adresser votre virement à l\'ordre de ...</p>',
                true
            ),
            'Paypal'   => array(
                'paypal_express_checkout_nvp',
                'paypal.png',
                '<p>Réglez avec votre compte paypal, ou votre carte bancaire.</p>',
                false
            ),
        );

        foreach ($methods as $name => $options) {
            $output->write(sprintf(
                '- <comment>%s</comment> %s ',
                $name,
                str_pad('.', 44 - mb_strlen($name), '.', STR_PAD_LEFT)
            ));

            // TODO check that factory method exists

            if (null !== $method = $methodRepository->findOneBy(array('paymentName' => $name))) {
                $output->writeln('already exists.');
                continue;
            }

            /** @var \Ekyna\Bundle\MediaBundle\Model\MediaInterface $image */
            $image = $mediaRepository->createNew();
            $image
                ->setFile(new File($imageDir.'/'.$options[1]))
                ->setFolder($folder)
                ->setTitle($name)
            ;

            /** @var \Ekyna\Bundle\PaymentBundle\Entity\Method $method */
            $method = $methodRepository->createNew();
            $method
                ->setPaymentName($name)
                ->setFactoryName($options[0])
                ->setMedia($image)
                ->setDescription($options[2])
                ->setEnabled($options[3])
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
