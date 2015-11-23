<?php

namespace Ekyna\Bundle\PaymentBundle\Install;

use Ekyna\Bundle\MediaBundle\Entity\Folder;
use Ekyna\Bundle\InstallBundle\Install\OrderedInstallerInterface;
use Ekyna\Bundle\MediaBundle\Model\MediaTypes;
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
     * @throws \Exception
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

        if (class_exists('Ekyna\Bundle\PayumSipsBundle\EkynaPayumSipsBundle')) {
            $methods['Carte bancaire'] = array(
                'atos_sips',
                'credit-card.png',
                '<p>Réglez avec votre carte bancaire.</p>',
                false
            );
        }

        foreach ($methods as $name => $options) {
            $output->write(sprintf(
                '- <comment>%s</comment> %s ',
                $name,
                str_pad('.', 44 - mb_strlen($name), '.', STR_PAD_LEFT)
            ));

            // TODO check that factory method exists

            if (null !== $method = $methodRepository->findOneBy(array('gatewayName' => $name))) {
                $output->writeln('already exists.');
                continue;
            }

            $source = $imageDir.'/'.$options[1];
            if (!file_exists($source)) {
                throw new \Exception(sprintf('File "%s" does not exists.', $source));
            }
            $target = sys_get_temp_dir() . '/' . $options[1];
            if (!copy($source, $target)) {
                throw new \Exception(sprintf('Failed to copy "%s" into "%s".', $source, $target));
            }

            /** @var \Ekyna\Bundle\MediaBundle\Model\MediaInterface $image */
            $image = $mediaRepository->createNew();
            $image
                ->setFile(new File($target))
                ->setFolder($folder)
                ->setTitle($name)
                ->setType(MediaTypes::IMAGE)
            ;

            /** @var \Ekyna\Bundle\PaymentBundle\Entity\Method $method */
            $method = $methodRepository->createNew();
            $method
                ->setGatewayName($name)
                ->setFactoryName($options[0])
                ->setMedia($image)
                ->setDescription($options[2])
                ->setEnabled($options[3])
                ->setAvailable(true)
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
