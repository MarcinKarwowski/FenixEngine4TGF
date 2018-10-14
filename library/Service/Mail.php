<?php
namespace App\Service;

use Phalcon\Mvc\User\Component;
use Swift_Message as Message;
use Swift_SmtpTransport as Smtp;
use Swift_SendmailTransport as Sendmail;
use Phalcon\Mvc\View;

/**
 * App\Service\Mail
 * Sends e-mails based on pre-defined templates
 */
class Mail extends Component
{

    protected $transport = '/usr/sbin/sendmail -bs';

    /**
     * Applies a template to be used in the e-mail
     *
     * @param ParseString $name
     * @param array $params
     */
    public function getTemplate($name, $params)
    {
        $parameters = array_merge(array(
            'publicUrl' => $this->config->game->publicUrl
        ), $params);

        return $this->view->getRender('emailTemplates', $name, $parameters, function ($view) {
            $view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        });

        return $view->getContent();
    }

    /**
     * Sends e-mails
     *
     * @param array $to
     * @param ParseString $subject
     * @param ParseString $name
     * @param array $params
     */
    public function send($to, $subject, $name, $params)
    {
        // Settings
        $mailSettings = $this->config->mail;

        $template = $this->getTemplate($name, $params);

        // Create the message
        $message = Message::newInstance()
            ->setSubject($subject)
            ->setTo($to)
            ->setFrom(array(
                $mailSettings->fromEmail => $mailSettings->fromName
            ))
            ->setBody($template, 'text/html');

            if ($mailSettings->smtp->server != '' && $mailSettings -> serverType == 'smtp') {
                $this->transport = Smtp::newInstance(
                    $mailSettings->smtp->server,
                    $mailSettings->smtp->port,
                    $mailSettings->smtp->security
                )
                    ->setUsername($mailSettings->smtp->username)
                    ->setPassword($mailSettings->smtp->password);
            } else {
                // Sendmail
                $this->transport = new Sendmail('/usr/sbin/sendmail -bs');
            }

            // Create the Mailer using your created Transport
            $mailer = \Swift_Mailer::newInstance($this->transport);
            return $mailer->send($message, $failures);
    }
}
