<?php

namespace App\EventListener;

use App\Entity\Invoice;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

class InvoiceListener
{
    /**
     * On pre persist entity invoice
     *
     * @param LifecycleEventArgs $args
     * @throws \Exception
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        /** @var $entity Invoice */
        $entity = $args->getEntity();

        $this->setCreatedAt($entity);
        $this->setDueDate($entity);
        $this->setReference($entity);
    }

    /**
     * On pre update entity invoice
     *
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        /** @var $entity Invoice */
        $entity = $args->getEntity();

        $this->setUpdatedAt($entity);
    }

    /**
     * @param $invoice Invoice
     */
    private function setCreatedAt($invoice)
    {
        if (!$invoice instanceof Invoice) {
            return;
        }

        $invoice->setCreatedAt(new \DateTime('now'));
    }

    /**
     * @param $invoice Invoice
     */
    private function setUpdatedAt($invoice)
    {
        if (!$invoice instanceof Invoice) {
            return;
        }

        $invoice->setUpdatedAt(new \DateTime('now'));
    }

    /**
     * @param $invoice Invoice
     * @throws \Exception
     */
    private function setDueDate($invoice)
    {
        if (!$invoice instanceof Invoice) {
            return;
        }

        $date = new \DateTime($invoice->getInvoiceDate()->format('Y-m-d H:i:s'));
        $date->add(new \DateInterval('P30D'));

        $invoice->setDueDate($date);
    }

    /**
     * @param $invoice Invoice
     * @throws \Exception
     */
    private function setReference($invoice)
    {
        if (!$invoice instanceof Invoice) {
            return;
        }

        $ref = $invoice->getClientAcronym().date('Y').date('m').date('d').$invoice->getId();

        $invoice->setReference($ref);
    }
}