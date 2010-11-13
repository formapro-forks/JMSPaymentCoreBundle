<?php

namespace Bundle\PaymentBundle\Entity;

interface PaymentInterface
{
    const STATE_APPROVED = 1;
    const STATE_APPROVING = 2;
    const STATE_CANCELED = 3;
    const STATE_EXPIRED = 4;
    const STATE_FAILED = 5;
    const STATE_NEW = 6;
    
    function getApprovedAmount();
    function getApproveTransaction();
    function getApprovingAmount();
    function getDepositedAmount();
    function getDepositingAmount();
    function getDepositTransactions();
    function getExpirationDate();
    function getId();
    function getPaymentInstruction();
    function getReverseApprovalTransactions();
    function getReverseDepositTransactions();
    function getReversingApprovedAmount();
    function getReversingDepositedAmount();
    function getState();
    function getTargetAmount();
    function isAttentionRequired();
    function isExpired();
    function setApprovedAmount($amount);
    function setApprovingAmount($amount);
    function setAttentionRequired($boolean);
    function setDepositedAmount($amount);
    function setDepositingAmount($amount);
    function setExpirationDate(\Date $date);
    function setExpired($boolean);
    function setReversingApprovedAmount($amount);
    function setReversingDepositedAmount($amount);
}