<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Mail Driver
    |--------------------------------------------------------------------------
    |
    | Laravel supports both SMTP and PHP's "mail" function as drivers for the
    | sending of e-mail. You may specify which one you're using throughout
    | your application here. By default, Laravel is setup for SMTP mail.
    |
    | Supported: "smtp", "sendmail", "mailgun", "ses",
    |            "postmark", "log", "array"
    |
    */

    'driver' => env('MAIL_DRIVER', 'smtp'),

    /*
    |--------------------------------------------------------------------------
    | SMTP Host Address
    |--------------------------------------------------------------------------
    |
    | Here you may provide the host address of the SMTP server used by your
    | applications. A default option is provided that is compatible with
    | the Mailgun mail service which will provide reliable deliveries.
    |
    */

    'host' => env('MAIL_HOST', 'smtp.mailgun.org'),

    /*
    |--------------------------------------------------------------------------
    | SMTP Host Port
    |--------------------------------------------------------------------------
    |
    | This is the SMTP port used by your application to deliver e-mails to
    | users of the application. Like the host we have set this value to
    | stay compatible with the Mailgun e-mail application by default.
    |
    */

    'port' => env('MAIL_PORT', 587),

    /*
    |--------------------------------------------------------------------------
    | Global "From" Address
    |--------------------------------------------------------------------------
    |
    | You may wish for all e-mails sent by your application to be sent from
    | the same address. Here, you may specify a name and address that is
    | used globally for all e-mails that are sent by your application.
    |
    */

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
        'name' => env('MAIL_FROM_NAME', 'Example'),
    ],

    /*
    |--------------------------------------------------------------------------
    | E-Mail Encryption Protocol
    |--------------------------------------------------------------------------
    |
    | Here you may specify the encryption protocol that should be used when
    | the application send e-mail messages. A sensible default using the
    | transport layer security protocol should provide great security.
    |
    */

    'encryption' => env('MAIL_ENCRYPTION', 'ssl'),

    /*
    |--------------------------------------------------------------------------
    | SMTP Server Username
    |--------------------------------------------------------------------------
    |
    | If your SMTP server requires a username for authentication, you should
    | set it here. This will get used to authenticate with your server on
    | connection. You may also set the "password" value below this one.
    |
    */

    'username' => env('MAIL_USERNAME'),

    'password' => env('MAIL_PASSWORD'),

    /*
    |--------------------------------------------------------------------------
    | Sendmail System Path
    |--------------------------------------------------------------------------
    |
    | When using the "sendmail" driver to send e-mails, we will need to know
    | the path to where Sendmail lives on this server. A default path has
    | been provided here, which will work well on most of your systems.
    |
    */

    'sendmail' => '/usr/sbin/sendmail -bs',

    /*
    |--------------------------------------------------------------------------
    | Markdown Mail Settings
    |--------------------------------------------------------------------------
    |
    | If you are using Markdown based email rendering, you may configure your
    | theme and component paths here, allowing you to customize the design
    | of the emails. Or, you may simply stick with the Laravel defaults!
    |
    */

    'markdown' => [
        'theme' => 'default',
        'paths' => [
            resource_path('views/vendor/mail'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Log Channel
    |--------------------------------------------------------------------------
    |
    | If you are using the "log" driver, you may specify the logging channel
    | if you prefer to keep mail messages separate from other log entries
    | for simpler reading. Otherwise, the default channel will be used.
    |
    */

    'log_channel' => env('MAIL_LOG_CHANNEL'),

    // Email templete types
    'email_events' => [
        'Account_Verification' => 'Account Verification (Register)',
        'Contact_Us' => 'Contact Us Email',
        'Course_Certificate_Available' => 'Course Certificate Available',
        'Enrollment_Payment_Invoice' => 'Enrollment Payment Invoice',
        'Enrollment_Payment_Success' => 'Enrollment Payment Success',
        'Enrollment_Payment_Failure' => 'Enrollment Payment Failure',
        'Forgot_Password' => 'Forgot Password (Reset Password)',
        'New_Assignment_Setup_By_Instructor' => 'New Assignment Created',
        'New_Discussion_Topic_Reply_Received' => 'New Discussion Topic Reply Received',
        'New_Message_Received' => 'New Message Received',
        'New_Quiz_Created' => 'New Quiz Created',
        'New_Test_Setup_By_Instructor' => 'New Test Created',
        'Pre_Enroll_Message_From_Instructor' => 'Pre Enroll Message From Instructor',
        'Schedule_Of_Live_Lesson_Changed' => 'Schedule Of Live Lesson Changed',
        'Test_Results_Posted_By_Instructor' => 'Test Results Posted By Instructor',
        'Welcome_Email_After_Verification' => 'Welcome Email After Verification',
        'Kyc_Approved' => 'KYC Apporved Email',
        'Kyc_Rejected' => 'KYC Rejected Email',
        'Withdraw_Request' => 'Withdraw Request',
        'Course_Approval_By_Admin' => 'Course Apporval By Admin',
        'Demo_Confirm_By_Instructor' => 'Demo Confirmed By Instructor',
        'Demo_Request_By_Student' => 'Demo Requested By Student',
        'Verify_Code' => 'Email Verify Code'
    ]
];
