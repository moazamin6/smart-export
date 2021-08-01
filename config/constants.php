<?php

// Orderstalker Status Constants
define('STATUS_PENDING', 'Pending');
define('STATUS_INFO_NOT_RECEIVED', 'Info Not Received');
define('STATUS_TRANSIT', 'Transit');
define('STATUS_PICKUP', 'Out For Delivery');
define('STATUS_DELIVERED', 'Delivered');
define('STATUS_UNDELIVERED', 'Failed Attempts');
define('STATUS_EXCEPTION', 'Exception');
define('STATUS_EXPIRED', 'Expired');

// Trackingmore Status constants
define('TM_PENDING', 'pending');
define('TM_NOT_FOUND', 'notfound');
define('TM_TRANSIT', 'transit');
define('TM_PICKUP', 'pickup');
define('TM_DELIVERED', 'delivered');
define('TM_UNDELIVERED', 'undelivered');
define('TM_EXCEPTION', 'exception');
define('TM_EXPIRED', 'expired');

// Status classes constants
define('STYLE_PENDING', 'order-pending');
define('STYLE_INFO_RECEIVED', 'order-info-not-received');
define('STYLE_TRANSIT', 'order-transit');
define('STYLE_OUT_FOR_DELIVERY', 'order-out-for-delivery');
define('STYLE_DELIVERED', 'order-delivered');
define('STYLE_FAILED_ATTEMPTS', 'order-failed-attempts');
define('STYLE_EXCEPTION', 'order-exception');
define('STYLE_EXPIRED', 'order-expired');

define('TRIGGER_STATUS_ACTIVE', 'Active');
define('TRIGGER_STATUS_INACTIVE', 'Deactivated');

// Config type constants
define('CONFIG_TYPE_SLACK_WEBHOOK', 'slack_webhook_url');
define('CONFIG_TYPE_SLACK_CHANNEL_NAME', 'slack_channel_name');
define('CONFIG_TYPE_CARD_ATTACHED', 'credit-card-attached');
define('CONFIG_TYPE_STEP_BY_STEP_DASHBOARD', 'step-by-step-dashboard');
define('CONFIG_TYPE_TRIAL_ACCEPTED', 'trial-accepted');


define('IS_STORE_CONFIG_COMPLETE', 'is-store-config-completed');

define('INITIAL_ORDER_BACK_DAYS', '-10 day');

// Cron task output base path
define('CRON_OUTPUT_BASE_PATH', '/var/www/public_html/latest.orderstalker.com/storage/cron/');


define('APP_INSTALL_PAYMENT_DESCRIPTION', '$1 is charged for payment verification.');

// Amount to be charged in dollars
define('BASIC_CHARGE_AMOUNT', 29.95);
// Additional Charge amount
define('CAPPED_AMOUNT', 150);


define('APP_DOMAIN', env('APP_DOMAIN'));
define('APP_ADMIN_DOMAIN', env('APP_ADMIN_DOMAIN'));


define('APPLICATION_TRIAL_DAYS', 14);

define('PAYMENT_TYPE_RECURRING_APPLICATION_CHARGE', 'RecurringApplicationCharge');
define('PAYMENT_TYPE_USAGE_CHARGE', 'UsageCharge');


const HEADER_A = 'Consignee Name';
const HEADER_B = 'Consignee Address';
const HEADER_C = 'Consignee Mobile';
const HEADER_D = 'Consignee Email';
const HEADER_E = 'Destination City';
const HEADER_F = 'Pieces';
const HEADER_G = 'Weight';
const HEADER_H = 'COD Amount';
const HEADER_I = 'Order Reference';
const HEADER_J = 'Special Handling';
const HEADER_K = 'Service Type';
const HEADER_L = 'Product Details';
const HEADER_M = 'Remarks';
const HEADER_N = 'Insurance/Declared Value';
