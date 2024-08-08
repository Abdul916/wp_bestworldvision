<?php
/**
 * @copyright © TMS-Plugins. All rights reserved.
 * @licence   See LICENCE.md for license details.
 */

namespace AmeliaBooking\Infrastructure\Services\Payment;

use AmeliaBooking\Domain\Services\Payment\AbstractPaymentService;
use AmeliaBooking\Domain\Services\Payment\PaymentServiceInterface;
use AmeliaBooking\Domain\ValueObjects\Number\Float\Price;
use AmeliaBooking\Domain\ValueObjects\String\Token;
use AmeliaStripe\Exception\ApiErrorException;
use AmeliaStripe\PaymentIntent;
use AmeliaStripe\PaymentMethod;
use AmeliaStripe\Stripe;
use AmeliaStripe\StripeClient;
use AmeliaStripe\Account;
use AmeliaStripe\AccountLink;
use AmeliaStripe\Transfer;
use Exception;

/**
 * Class StripeService
 */
class StripeService extends AbstractPaymentService implements PaymentServiceInterface
{
    /**
     * @param array $data
     * @param array $transfers
     *
     * @return mixed
     * @throws \Exception
     */
    public function execute($data, &$transfers)
    {
        $stripeSettings = $this->settingsService->getSetting('payments', 'stripe');

        Stripe::setApiKey(
            $stripeSettings['testMode'] === true ? $stripeSettings['testSecretKey'] : $stripeSettings['liveSecretKey']
        );

        $stripeConnectSettings = $stripeSettings['connect'];

        $intent = null;

        if ($data['paymentMethodId']) {
            $paymentMethodId = $data['paymentMethodId'];

            if ($stripeConnectSettings['enabled'] &&
                $stripeConnectSettings['amount'] &&
                sizeof($transfers['accounts']) === 1 &&
                $stripeConnectSettings['method'] === 'direct'
            ) {
                $paymentMethod = PaymentMethod::create(
                    [
                        'payment_method' => $data['paymentMethodId'],
                    ],
                    [
                        'stripe_account' => array_keys($transfers['accounts'])[0],
                    ]
                );

                $paymentMethodId = $paymentMethod->id;
            }

            $stripeData = [
                'payment_method'       => $paymentMethodId,
                'amount'               => $data['amount'],
                'currency'             => $this->settingsService->getCategorySettings('payments')['currency'],
                'confirmation_method'  => 'manual',
                'confirm'              => true,
                'payment_method_types' => ['card'],
            ];

            if ($stripeSettings['returnUrl']) {
                $stripeData['return_url'] = $stripeSettings['returnUrl'];
            }

            if ($stripeConnectSettings['enabled'] &&
                $stripeConnectSettings['amount'] &&
                $stripeConnectSettings['method'] === 'transfer' &&
                sizeof($transfers['accounts']) > 0
            ) {
                $token = new Token();

                $stripeData['transfer_group'] = $token->getValue();
            }

            $additionalStripeData = [];

            if ($stripeConnectSettings['enabled'] &&
                $stripeConnectSettings['amount'] &&
                sizeof($transfers['accounts']) === 1 &&
                $stripeConnectSettings['method'] === 'direct'
            ) {
                $platformFee = 0;

                foreach ($transfers['accounts'] as $payments) {
                    foreach ($payments as $payment) {
                        $platformFee += $payment['amount'];
                    }
                }

                $stripeData['application_fee_amount'] = $platformFee;

                $additionalStripeData = ['stripe_account' => array_keys($transfers['accounts'])[0]];
            }

            if ($stripeSettings['manualCapture']) {
                $stripeData['capture_method'] = 'manual';
            }

            if ($data['metaData']) {
                $stripeData['metadata'] = $data['metaData'];
            }

            if ($data['description']) {
                $stripeData['description'] = $data['description'];
            }

            $stripeData = apply_filters(
                'amelia_before_stripe_payment',
                $stripeData
            );

            $intent = PaymentIntent::create($stripeData, $additionalStripeData);


            if ($stripeConnectSettings['enabled'] &&
                $stripeConnectSettings['amount'] &&
                $stripeConnectSettings['method'] === 'transfer'
            ) {
                foreach ($transfers['accounts'] as $accountId => $payments) {
                    foreach ($payments as $paymentId => $payment) {
                        try {
                            $transfer = Transfer::create(
                                [
                                    'amount'         => $payment['amount'],
                                    'currency'       => $stripeData['currency'],
                                    'destination'    => $accountId,
                                    'transfer_group' => $stripeData['transfer_group'],
                                ]
                            );

                            $transfers['accounts'][$accountId][$paymentId]['transferId'] = $transfer->id;
                        } catch (Exception $e) {
                            unset($transfers['accounts'][$accountId][$paymentId]);
                        }
                    }
                }
            }
        }


        if ($data['paymentIntentId']) {
            $intent = PaymentIntent::retrieve(
                $data['paymentIntentId']
            );

            $intent->confirm();
        }

        if ($intent && ($intent->status === 'requires_action' || $intent->status === 'requires_source_action') && $intent->next_action->type === 'use_stripe_sdk') {
            return  [
                'requiresAction'            => true,
                'paymentIntentClientSecret' => $intent->client_secret,
                'paymentIntentId'           => $intent->getLastResponse()->json['id']
            ];
        } else if ($intent && ($intent->status === 'succeeded' || ($stripeSettings['manualCapture'] && $intent->status === 'requires_capture'))) {
            return  [
                'paymentSuccessful' => true,
                'paymentIntentId'   => $intent->getLastResponse()->json['id']
            ];
        }

        return  [
            'paymentSuccessful' => false
        ];
    }

    /**
     * @param array $data
     *
     * @return array
     * @throws \AmeliaStripe\Exception\ApiErrorException
     */
    public function getPaymentLink($data)
    {
        $stripeSettings = $this->settingsService->getSetting('payments', 'stripe');

        $stripe = new StripeClient(
            $stripeSettings['testMode'] === true ? $stripeSettings['testSecretKey'] : $stripeSettings['liveSecretKey']
        );

        $additionalStripeData = [];

        if (!empty($data['transfer']) && $stripeSettings['connect']['method'] === 'direct') {
            $additionalStripeData = ['stripe_account' => $data['transfer']['accountId']];
        }

        $price = $stripe->prices->create(
            [
                'unit_amount'  => $data['amount'],
                'currency'     => $data['currency'],
                'product_data' => ['name' => $data['description']],
            ],
            $additionalStripeData
        );

        if ($price) {
            $paymentLinkData = [
                'line_items' => [
                    [
                        'price' => $price['id'],
                        'quantity' => 1,
                    ],
                ],
                'after_completion' => [
                    'type' => 'redirect',
                    'redirect' => [
                        'url' => $data['returnUrl'] . '&session_id={CHECKOUT_SESSION_ID}'
                    ]
                ],
//                'invoice_creation' => ['enabled' => true],
            ];

            if (!empty($data['metaData'])) {
                $paymentLinkData['metadata'] = $data['metaData'];
            }

            if (!empty($data['transfer'])) {
                $method = '';

                if ($stripeSettings['connect']['method'] === 'direct') {
                    $paymentLinkData['application_fee_amount'] = $data['amount'] - $data['transfer']['amount'];

                    $method = 'direct';
                } elseif ($stripeSettings['connect']['method'] === 'transfer') {
                    $paymentLinkData['transfer_data'] = ['destination' => $data['transfer']['accountId']];

                    $paymentLinkData['transfer_data']['amount'] = $data['transfer']['amount'];

                    $method = 'destination';
                }

                $paymentLinkData['after_completion']['redirect']['url'] .=
                    '&accountId=' . $data['transfer']['accountId'] . '&method=' . $method;
            }

            $response = $stripe->paymentLinks->create($paymentLinkData, $additionalStripeData);

            return $response && $response['url'] ?
                ['link' => $response['url'], 'status' => 200] :
                ['message' => $response['message'], 'status' => $response['status']];
        }

        return ['message' => $price['message'], 'status' => $price['status']];
    }

    /**
     * @param array $data
     *
     * @return array
     * @throws \Exception
     */
    public function refund($data)
    {
        $stripeSettings = $this->settingsService->getSetting('payments', 'stripe');

        $secretKey = $stripeSettings['testMode'] === true ? $stripeSettings['testSecretKey'] : $stripeSettings['liveSecretKey'];

        $stripe = new StripeClient($secretKey);

        $props = [
            'payment_intent' => $data['id'],
        ];

        if (!empty($data['amount'])) {
            $props['amount'] = $this->currencyService->getAmountInFractionalUnit(new Price($data['amount']));
        }

        $additionalProps = [];

        if (!empty($data['transfers']) && $data['transfers']['method'] === 'destination') {
            $props['refund_application_fee'] = true;

            $props['reverse_transfer'] = true;
        }

        if (!empty($data['transfers']) && $data['transfers']['method'] === 'direct') {
            $props['refund_application_fee'] = true;

            $additionalProps = ['stripe_account' => array_keys($data['transfers']['accounts'])[0]];
        }

        $response = $stripe->refunds->create($props, $additionalProps);

        if (!empty($data['transfers']) && $data['transfers']['method'] === 'transfer') {
            foreach ($data['transfers']['accounts'] as $transfers) {
                foreach ($transfers as $transferId => $amount) {
                    $stripe->transfers->createReversal($transferId, ['amount' => $amount]);
                }
            }
        }

        return ['error' => $response->getLastResponse()->code !== 200];
    }

    /**
     * @param string $sessionId
     * @param string $method
     * @param string $accountId
     *
     * @return string
     */
    public function getPaymentIntent($sessionId, $method, $accountId)
    {
        $stripeSettings = $this->settingsService->getSetting('payments', 'stripe');

        $secretKey = $stripeSettings['testMode'] === true ? $stripeSettings['testSecretKey'] : $stripeSettings['liveSecretKey'];

        $stripe = new StripeClient($secretKey);

        $additionalStripeData = [];

        if ($method === 'direct' && $accountId) {
            $additionalStripeData = ['stripe_account' => $accountId];
        }

        $response =  $stripe->checkout->sessions->retrieve($sessionId, [], $additionalStripeData);

        return $response->getLastResponse()->code === 200 ? $response['payment_intent'] : null;
    }

    /**
     * @param string $id
     * @param array|null $transfers
     *
     * @return mixed
     * @throws \Exception
     */
    public function getTransactionAmount($id, $transfers)
    {
        $stripeSettings = $this->settingsService->getSetting('payments', 'stripe');

        $secretKey = $stripeSettings['testMode'] === true ? $stripeSettings['testSecretKey'] : $stripeSettings['liveSecretKey'];

        $stripe = new StripeClient($secretKey);

        $response = $stripe->paymentIntents->retrieve(
            $id,
            [],
            !empty($transfers['method']) && !empty($transfers['accounts']) && $transfers['method'] === 'direct' ?
                ['stripe_account' => array_keys($transfers['accounts'])[0]] : []
        );

        return $response->getLastResponse()->code === 200 ? $response->toArray()['amount']/100 : null;
    }

    /**
     * @param string $providerEmail
     * @param string $providerStripeConnectId
     * @param string $returnUrl
     * @param string $accountType
     *
     * @return array
     * @throws ApiErrorException
     */
    public function onBoardProvider($providerEmail, $providerStripeConnectId, $returnUrl, $accountType)
    {
        $stripeSettings = $this->settingsService->getSetting('payments', 'stripe');

        Stripe::setApiKey(
            $stripeSettings['testMode'] === true ? $stripeSettings['testSecretKey'] : $stripeSettings['liveSecretKey']
        );

        if (!$providerStripeConnectId) {
            $accountData = [
                'type'                   => $accountType,
                'requested_capabilities' => $accountType === 'express' ? ['card_payments', 'transfers'] : [],
            ];

            if ($providerEmail && $accountType === 'express') {
                $accountData['email'] = $providerEmail;
            }

            $account = Account::create($accountData);

            $providerStripeConnectId = $account->id;
        }

        $accountLinks = AccountLink::create(
            [
                'account'     => $providerStripeConnectId,
                'refresh_url' => $returnUrl,
                'return_url'  => $returnUrl,
                'type'        => 'account_onboarding',
            ]
        );

        return [
            'id'  => $providerStripeConnectId,
            'url' => $accountLinks->url,
        ];
    }

    /**
     * @param string $stripeId
     *
     * @return Account
     * @throws Exception
     */
    public function getAccount($stripeId)
    {
        $stripeSettings = $this->settingsService->getSetting('payments', 'stripe');

        Stripe::setApiKey(
            $stripeSettings['testMode'] === true ? $stripeSettings['testSecretKey'] : $stripeSettings['liveSecretKey']
        );

        return Account::retrieve($stripeId);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getAccounts()
    {
        $stripeSettings = $this->settingsService->getSetting('payments', 'stripe');

        Stripe::setApiKey(
            $stripeSettings['testMode'] === true ? $stripeSettings['testSecretKey'] : $stripeSettings['liveSecretKey']
        );

        $accounts = Account::all(['limit' => 100]);

        $result = [];

        foreach ($accounts->toArray()['data'] as $account) {
            $result[] = [
                'id'    => $account['id'],
                'email' => $account['email'],
                'type'  => $account['type'],
            ];
        }

        return $result;
    }


    /**
     * @throws ApiErrorException
     */
    public function getExpressAccountLink($id)
    {
        $stripeSettings = $this->settingsService->getSetting('payments', 'stripe');

        $secretKey = $stripeSettings['testMode'] === true ? $stripeSettings['testSecretKey'] : $stripeSettings['liveSecretKey'];

        $stripe = new StripeClient($secretKey);

        $response = $stripe->accounts->createLoginLink($id, []);

        return $response->getLastResponse()->code === 200 ? $response->toArray()['url'] : null;
    }
}
