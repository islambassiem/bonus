<?php

require_once '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

function sendMail($email, $name, $empId, $amount) {
    $mail = new PHPMailer(true);
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_OFF;;                        
    $mail->isSMTP();                                            
    $mail->Host       = 'smtp.gmail.com';                     
    $mail->SMTPAuth   = true;                                   
    $mail->Username   = 'hr@inaya.edu.sa';                     
    $mail->Password   = 'pgik kbfl ocpw qjml';                               
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            
    $mail->Port       = 465;
    $mail->CharSet    = 'UTF-8';                                   

    //Recipients
    $mail->setFrom('hr@inaya.edu.sa', 'Annual Party 2026');
    $mail->addAddress($email, $name);                 
    $mail->addReplyTo('hr@inaya.edu.sa', 'Annual Party 2026');

    //Content
    $mail->isHTML(true);
    $mail->Subject = "Bonus for 2025";
    $mail->Body = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <title>Payment Notification</title>
    </head>
    <body style='margin:0;padding:0;background:#f3f4f6;font-family:Arial,sans-serif;'>

        <table width='100%' cellpadding='0' cellspacing='0' style='background:#f3f4f6;padding:40px 0;'>
            <tr>
                <td align='center'>

                    <table width='600' cellpadding='0' cellspacing='0'
                        style='background:#ffffff;border-radius:12px;overflow:hidden;'>

                        <!-- Header -->
                        <tr>
                            <td
                                style='background:#2563eb;
                                    padding:30px;
                                    text-align:center;
                                    color:#ffffff;
                                    font-size:28px;
                                    font-weight:bold;'>
                                Inaya Medical Colleges
                            </td>
                        </tr>

                        <!-- Content -->
                        <tr>
                            <td style='padding:40px;'>

                                <p style='font-size:16px;color:#111827;margin:0 0 20px;'>
                                    Dear {$name},
                                </p>

                                <p style='font-size:16px;color:#374151;line-height:1.7;margin:0 0 25px;'>
                                    Thank you for your hard work and dedication. We are pleased to share that you will receive the following annual bonus for the year 2025:
                                </p>

                                <!-- Amount Box -->
                                <table width='100%' cellpadding='0' cellspacing='0'
                                    style='background:#eff6ff;
                                        border:1px solid #bfdbfe;
                                        border-radius:10px;
                                        margin:30px auto;'>

                                    <tr>
                                        <td style='padding:30px;text-align:center;'>

                                            <div style='font-size:14px;color:#6b7280;margin-bottom:10px;'>
                                                Employee ID
                                            </div>

                                            <div style='font-size:22px;font-weight:bold;color:#111827;margin-bottom:25px;'>
                                                {$empId}
                                            </div>

                                            <div style='font-size:14px;color:#6b7280;margin-bottom:10px;'>
                                                Amount
                                            </div>

                                            <div style='font-size:42px;
                                                        font-weight:bold;
                                                        color:#2563eb;'>
                                                {$amount}
                                            </div>

                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <!-- Footer -->
                        <tr>
                            <td
                                style='background:#f9fafb;
                                    padding:20px;
                                    text-align:center;
                                    font-size:13px;
                                    color:#6b7280;'>

                                © " . date('Y') . " Inaya Medical Colleges. All rights reserved.

                            </td>
                        </tr>

                    </table>

                </td>
            </tr>
        </table>

    </body>
    </html>
    ";

    $mail->AltBody = "
    Payment Notification

    Employee ID: {$empId}
    Amount: {$amount}

    If you have questions, contact the finance department.
    ";

    $mail->send();
}
