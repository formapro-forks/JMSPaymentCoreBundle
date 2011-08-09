<?php

namespace JMS\Payment\CoreBundle\Entity;

use JMS\Payment\CoreBundle\Model\PaymentInstructionInterface;
use Doctrine\Common\Collections\ArrayCollection;

/*
 * Copyright 2010 Johannes M. Schmitt <schmittjoh@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

class PaymentInstruction implements PaymentInstructionInterface
{
    protected $account;
    protected $amount;
    protected $approvedAmount;
    protected $approvingAmount;
    protected $createdAt;
    protected $creditedAmount;
    protected $creditingAmount;
    protected $credits;
    protected $currency;
    protected $depositedAmount;
    protected $depositingAmount;
    protected $extendedData;
    protected $extendedDataModified;
    protected $id;
    protected $payments;
    protected $paymentSystemName;
    protected $reversingApprovedAmount;
    protected $reversingCreditedAmount;
    protected $reversingDepositedAmount;
    protected $state;
    protected $updatedAt;

    public function __construct($amount, $currency, $paymentSystemName, ExtendedData $data)
    {
        $this->amount = $amount;
        $this->approvedAmount = 0.0;
        $this->approvingAmount = 0.0;
        $this->createdAt = new \DateTime;
        $this->creditedAmount = 0.0;
        $this->creditingAmount = 0.0;
        $this->credits = new ArrayCollection();
        $this->currency = $currency;
        $this->depositingAmount = 0.0;
        $this->depositedAmount = 0.0;
        $this->extendedData = clone $data;
        $this->extendedDataModified = false;
        $this->payments = new ArrayCollection();
        $this->paymentSystemName = $paymentSystemName;
        $this->reversingApprovedAmount = 0.0;
        $this->reversingCreditedAmount = 0.0;
        $this->reversingDepositedAmount = 0.0;
        $this->state = self::STATE_NEW;
    }

    /**
     * This method adds a Credit container to this PaymentInstruction.
     *
     * This method is called automatically from Credit::__construct().
     *
     * @param Credit $credit
     * @return void
     */
    public function addCredit(Credit $credit)
    {
        if ($credit->getPaymentInstruction() !== $this) {
            throw new \InvalidArgumentException('This credit container belongs to another instruction.');
        }

        $this->credits->add($credit);
    }

    /**
     * This method adds a Payment container to this PaymentInstruction.
     *
     * This method is called automatically from Payment::__construct().
     *
     * @param Payment $payment
     * @return void
     */
    public function addPayment(Payment $payment)
    {
        if ($payment->getPaymentInstruction() !== $this) {
            throw new \InvalidArgumentException('This payment container belongs to another instruction.');
        }

        $this->payments->add($payment);
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function getPaymentSystemName()
    {
        return $this->paymentSystemName;
    }

    public function getExtendedData()
    {
        // if extended data requested from the outside it means that it may be changed so we changed the object to point doctrine to save the column
        if (false == $this->extendedDataModified) {
            $this->extendedData = clone $this->extendedData;
            $this->extendedDataModified = true;
        }

        return $this->extendedData;
    }

    public function getState()
    {
        return $this->state;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getApprovingAmount()
    {
        return $this->approvingAmount;
    }

    public function getApprovedAmount()
    {
        return $this->approvedAmount;
    }

    public function getCreditedAmount()
    {
        return $this->creditedAmount;
    }

    public function getCreditingAmount()
    {
        return $this->creditingAmount;
    }

    public function getDepositedAmount()
    {
        return $this->depositedAmount;
    }

    public function getDepositingAmount()
    {
        return $this->depositingAmount;
    }

    public function getCredits()
    {
        return $this->credits;
    }

    public function getPayments()
    {
        return $this->payments;
    }

    public function getPendingTransaction()
    {
        foreach ($this->payments as $payment) {
            if (null !== $transaction = $payment->getPendingTransaction()) {
                return $transaction;
            }
        }

        foreach ($this->credits as $credit) {
            if (null !== $transaction = $credit->getPendingTransaction()) {
                return $transaction;
            }
        }

        return null;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getReversingApprovedAmount()
    {
        return $this->reversingApprovedAmount;
    }

    public function getReversingCreditedAmount()
    {
        return $this->reversingCreditedAmount;
    }

    public function getReversingDepositedAmount()
    {
        return $this->reversingDepositedAmount;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function hasPendingTransaction()
    {
        return null !== $this->getPendingTransaction();
    }

    public function onPrePersist()
    {
        if (null !== $this->id) {
            $this->updatedAt = new \Datetime;
        }
    }

    public function onPostSave()
    {
        $this->extendedDataModified = false;
    }

    public function setApprovingAmount($amount)
    {
        $this->approvingAmount = $amount;
    }

    public function setApprovedAmount($amount)
    {
        $this->approvedAmount = $amount;
    }

    public function setCreditedAmount($amount)
    {
        $this->creditedAmount = $amount;
    }

    public function setCreditingAmount($amount)
    {
        $this->creditingAmount = $amount;
    }

    public function setDepositedAmount($amount)
    {
        $this->depositedAmount = $amount;
    }

    public function setDepositingAmount($amount)
    {
        $this->depositingAmount = $amount;
    }

    public function setReversingApprovedAmount($amount)
    {
        $this->reversingApprovedAmount = $amount;
    }

    public function setReversingCreditedAmount($amount)
    {
        $this->reversingCreditedAmount = $amount;
    }

    public function setReversingDepositedAmount($amount)
    {
        $this->reversingDepositedAmount = $amount;
    }

    public function setState($state)
    {
        $this->state = $state;
    }
}