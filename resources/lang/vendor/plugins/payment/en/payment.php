<?php

use Botble\Payment\Enums\PaymentMethodEnum;
use Botble\Payment\Enums\PaymentStatusEnum;

return [
    'payments'                              => 'Payments',
    'checkout_success'                      => 'Checkout successfully!',
    'view_payment'                          => 'View payment #',
    'charge_id'                             => 'Charge ID',
    'amount'                                => 'Amount',
    'currency'                              => 'Currency',
    'user'                                  => 'User',
    'stripe'                                => 'Stripe',
    'paypal'                                => 'PayPal',
    'action'                                => 'Action',
    'payment_via_card'                      => 'Payment via card',
    'card_number'                           => 'Card number',
    'full_name'                             => 'Full name',
    'payment_via_paypal'                    => 'Payment via PayPal',
    'mm_yy'                                 => 'MM/YY',
    'cvc'                                   => 'CVC',
    'details'                               => 'Details',
    'payer_name'                            => 'Payer Name',
    'email'                                 => 'Email',
    'phone'                                 => 'Phone',
    'country'                               => 'Country',
    'shipping_address'                      => 'Shipping Address',
    'payment_details'                       => 'Payment Details',
    'card'                                  => 'Card',
    'address'                               => 'Address',
    'could_not_get_stripe_token'            => 'Could not get Stripe token to make a charge.',
    'payment_id'                            => 'Payment ID',
    'payment_methods'                       => 'Payment methods',
    'payment_methods_description'           => 'Setup payment methods for website',
    'paypal_description'                    => 'Customer can buy product and pay directly via PayPal',
    'use'                                   => 'Use',
    'stripe_description'                    => 'Customer can buy product and pay directly using Visa, Credit card via Stripe',
    'edit'                                  => 'Edit',
    'settings'                              => 'Settings',
    'activate'                              => 'Activate',
    'deactivate'                            => 'Deactivate',
    'update'                                => 'Update',
    'configuration_instruction'             => 'Configuration instruction for :name',
    'configuration_requirement'             => 'To use :name, you need',
    'service_registration'                  => 'Register with :name',
    'after_service_registration_msg'        => 'After registration at :name, you will have Client ID, Client Secret',
    'enter_client_id_and_secret'            => 'Enter Client ID, Secret into the box in right hand',
    'method_name'                           => 'Method name',
    'please_provide_information'            => 'Please provide information',
    'client_id'                             => 'Client ID',
    'client_secret'                         => 'Client Secret',
    'secret'                                => 'Secret',
    'pay_online_via'                        => 'Pay online via :name',
    'sandbox_mode'                          => 'Sandbox mode',
    'deactivate_payment_method'             => 'Deactivate payment method',
    'deactivate_payment_method_description' => 'Do you really want to deactivate this payment method?',
    'agree'                                 => 'Agree',
    'name'                                  => 'Payments',
    'create'                                => 'New payment',
    'go_back'                               => 'Go back',
    'information'                           => 'Information',
    'methods'                               => [
        PaymentMethodEnum::PAYPAL        => 'PayPal',
        PaymentMethodEnum::STRIPE        => 'Stripe',
        PaymentMethodEnum::COD           => 'Cash on delivery (COD)',
        PaymentMethodEnum::BANK_TRANSFER => 'Bank transfer',
    ],
    'statuses'                              => [
        PaymentStatusEnum::PENDING   => 'Pending',
        PaymentStatusEnum::COMPLETED => 'Completed',
        PaymentStatusEnum::REFUNDING => 'Refunding',
        PaymentStatusEnum::REFUNDED  => 'Refunded',
        PaymentStatusEnum::FRAUD     => 'Fraud',
    ],
    'payment_methods_instruction'           => 'Guide customers to pay directly. You can choose to pay by delivery or bank transfer',
    'payment_method_description'            => 'Payment guide - (Displayed on the notice of successful purchase and payment page)',
    'payment_via_cod'                       => 'Cash on delivery (COD)',
    'payment_via_bank_transfer'             => 'Bank transfer',
    'payment_pending'                       => 'Checkout successfully. Your payment is pending and will be checked by our staff.',
];
