<?php
/**
 * @author Uhon Liu http://phalconcmf.com <futustar@qq.com>
 */

namespace Core;

use Phalcon\Di;
use Swift_Mailer;
use Swift_Message;
use Swift_Attachment;
use Phalcon\Mvc\View;
use Swift_SmtpTransport;
use Swift_SendmailTransport;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;

require_once APP_PATH . '/libraries/SwiftMailer/swift_required.php';

class Email
{
    /**
     * @var Swift_Mailer
     */
    public $mailer;

    /**
     * A Phalcon\Config\Adapter\Php
     *
     * @var mixed
     */
    private $config;

    /**
     * @var Swift_Message
     */
    private $message;

    /**
     * @var string Email body
     */
    public $body = '';

    /**
     * Get Email Instance
     *
     * @param mixed $config A Phalcon\Config\Adapter\Php
     * @return Email
     */
    public static function getInstance($config = null)
    {
        return new self($config);
    }

    /**
     * Instance construct
     *
     * @param array $config
     */
    public function __construct($config)
    {
        $this->_initConfig($config);
    }

    /**
     * Init view (Phalcon Volt Template)
     *
     * @return View
     */
    private function _initView()
    {
        $view = new View();

        $view->setDI(Di::getDefault());

        $view->registerEngines([
            '.volt' => function($view, $di) {

                $volt = new VoltEngine($view, $di);

                $volt->setOptions([
                    'compiledPath' => function($templatePath) {
                        $templatePath = strstr($templatePath, '/app');
                        $dirName = dirname($templatePath);
                        if(!is_dir(ROOT_PATH . '/var/cache/volt' . $dirName)) {
                            mkdir(ROOT_PATH . '/var/cache/volt' . $dirName, 0755, true);
                        }
                        return ROOT_PATH . '/var/cache/volt' . $dirName . '/' . basename($templatePath, '.volt') . '.php';
                    },
                    'compiledSeparator' => '_',
                    'compileAlways' => true,
                    'stat' => false
                ]);

                $compiler = $volt->getCompiler();
                $compiler->addFunction('__', '__');
                return $volt;
            }
        ]);
        return $view;
    }

    /**
     * Init config
     *
     * @param $config
     */
    private function _initConfig($config)
    {
        if($config) {
            $this->config = $config;
        } else {
            $this->config = Factory::getConfig();
        }
        $this->message = Swift_Message::newInstance();
        if($this->config->mail->mailType == 'smtp') {
            $this->message->setFrom($this->config->mail->smtpUser, $this->config->mail->mailName);
            $transporter = Swift_SmtpTransport::newInstance($this->config->mail->smtpHost, $this->config->mail->smtpPort, $this->config->mail->smtpSecure)
                ->setUsername($this->config->mail->smtpUser)
                ->setPassword($this->config->mail->smtpPass);
            $this->mailer = Swift_Mailer::newInstance($transporter);
        } else {
            $this->message->setFrom($this->config->mail->mailFrom, $this->config->mail->mailName);
            $transporter = Swift_SendmailTransport::newInstance($this->config->mail->sendMail . ' -bs');
            $this->mailer = Swift_Mailer::newInstance($transporter);
        }
    }

    /**
     * Send Email
     *
     * @return int
     */
    public function send()
    {
        return $this->mailer->send($this->message);
    }

    /**
     * Set the subject of this message
     *
     * @param string $subject
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->message->setSubject($subject);
        return $this;
    }

    /**
     * Set email body
     *
     * @param string $body
     * @param string $contentType
     * @param string $charset
     * @return $this
     */
    public function setBody($body, $contentType = null, $charset = null)
    {
        $this->message->setBody($body, $contentType, $charset);
        return $this;
    }

    /**
     * Add to
     *
     * @param string $address
     * @param string $name Default null
     * @return $this
     */
    public function addTo($address, $name = null)
    {
        $this->message->addTo($address, $name);
        return $this;
    }

    /**
     * Add cc
     *
     * @param string $address
     * @param string $name Default null
     * @return $this
     */
    public function addCc($address, $name = null)
    {
        $this->message->addCc($address, $name);
        return $this;
    }

    /**
     * Add reply to
     *
     * @param string $address
     * @param string $name Default null
     * @return $this
     */
    public function addReplyTo($address, $name = null)
    {
        $this->message->addReplyTo($address, $name);
        return $this;
    }

    /**
     * Add from
     *
     * @param string $address
     * @param string $name Default null
     * @return $this
     */
    public function addFrom($address, $name = null)
    {
        $this->message->addFrom($address, $name);
        return $this;
    }

    /**
     * Add Bcc
     *
     * @param string $address
     * @param string $name Default null
     * @return $this
     */
    public function addBcc($address, $name = null)
    {
        $this->message->addBcc($address, $name);
        return $this;
    }


    /**
     * Set the character set
     *
     * @param string $charset
     * @return $this
     */
    public function setCharset($charset)
    {
        $this->message->setCharset($charset);
        return $this;
    }

    /**
     * Set the Content-type
     *
     * @param string $type
     * @return $this
     */
    public function setContentType($type)
    {
        $this->message->setContentType($type);
        return $this;
    }

    /**
     * Attach a file
     *
     * @param string $path Path of attachment file
     * @return $this
     */
    public function attach($path)
    {
        $this->message->attach(Swift_Attachment::fromPath($path));
        return $this;
    }

    /**
     * Set volt template for email
     *
     * @param string $module
     * @param string $template
     * @param array $data
     * @param string $moduleLocation
     * @param string $contentType
     * @param string $charset
     * @return $this
     */
    public function setTemplate($module, $template, $data = [], $moduleLocation = 'frontend', $contentType = 'text/html', $charset = 'utf-8')
    {
        $view = $this->_initView();

        // Init base variable
        $view->setVar('_baseUri', BASE_URI);
        $view->setVar('_siteName', $this->config->website->siteName);

        $view->setVar('data', $data);
        $view->start();
        $overrideFolder = APP_PATH . '/templates/' . $moduleLocation . DS . $this->config->frontendTemplate->defaultTemplate . '/languages/email-templates/' . $this->config->website->language . DS;
        $overrideFile = $overrideFolder . $module . DS . $template . '.volt';
        if(file_exists($overrideFile)) {
            $view->setViewsDir($overrideFolder);
            $view->render($module, $template)->getContent();
        } else {
            $view->setViewsDir(APP_PATH . '/' . $moduleLocation . DS . $module . '/languages/email-templates/');
            $view->render($this->config->website->language, $template)->getContent();
        }
        $view->finish();
        $this->message->setBody($view->getContent(), $contentType, $charset);
        return $this;
    }
}