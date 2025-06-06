<?php
/************************************************************************
 *
 * Copyright 2024 Adobe
 * All Rights Reserved.
 *
 * NOTICE: All information contained herein is, and remains
 * the property of Adobe and its suppliers, if any. The intellectual
 * and technical concepts contained herein are proprietary to Adobe
 * and its suppliers and are protected by all applicable intellectual
 * property laws, including trade secret and copyright laws.
 * Dissemination of this information or reproduction of this material
 * is strictly forbidden unless prior written permission is obtained
 * from Adobe.
 * ************************************************************************
 */
declare(strict_types=1);

namespace Magento\PaymentServicesPaypal\Controller\SmartButtons;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Session\Generic as PaypalSession;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Api\Data\CartInterfaceFactory;

class SetQuoteAsInactive implements HttpPostActionInterface
{
    /**
     * @var ResultFactory
     */
    private $resultFactory;

    /**
     * @var CartRepositoryInterface
     */
    private $quoteRepository;

    /**
     * @var PaypalSession
     */
    private PaypalSession $paypalSession;

    /**
     * @var CheckoutSession
     */
    private CheckoutSession $checkoutSession;

    /**
     * @param ResultFactory $resultFactory
     * @param CartRepositoryInterface $quoteRepository
     * @param PaypalSession $paypalSession
     * @param CheckoutSession $checkoutSession
     */
    public function __construct(
        ResultFactory           $resultFactory,
        CartRepositoryInterface $quoteRepository,
        PaypalSession    $paypalSession,
        CheckoutSession  $checkoutSession
    ) {
        $this->resultFactory = $resultFactory;
        $this->quoteRepository = $quoteRepository;
        $this->paypalSession = $paypalSession;
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * Set the quote as inactive
     *
     * @return ResultInterface
     */
    public function execute() : ResultInterface
    {
        $result = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        try {
            $quote = $this->getQuote();

            if ($quote->getIsActive()) {
                $quote->setIsActive(false);
                $this->quoteRepository->save($quote);
            }

            $result->setHttpResponseCode(200);
            return $result;
        } catch (\Exception $e) {
            $result->setHttpResponseCode(500)
                ->setData(['error' => "Error when setting the quote as inactive"]);

            return $result;
        }
    }

    /**
     * Get quote method
     *
     * @return CartInterface
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    private function getQuote() : CartInterface
    {
        if ($this->paypalSession->getQuoteId()) {
            return $this->quoteRepository->get($this->paypalSession->getQuoteId());
        }

        return $this->checkoutSession->getQuote();
    }
}
