<?php
namespace Admin\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\Url as UrlValidator;

class MainConfigureForm extends Form
{

    public function initialize($entity = null, $options = null)
    {
        // Game title
        $title = new Text('title');
        $title->setLabel($this -> translate['configuration-game_title']);
        $title->addValidators(array(
            new PresenceOf(array(
                'message' => $this -> translate['form-field_required']
            ))
        ));
        $this->add($title);

        // Game description
        $description = new Text('description');
        $description->setLabel($this -> translate['configuration-game_description']);
        $description->addValidators(array(
            new PresenceOf(array(
                'message' => $this -> translate['form-field_required']
            ))
        ));
        $this->add($description);

        // Custom code
        $custom = new TextArea('custom');
        $custom->setLabel($this -> translate['configuration-custom_code']);
        $this->add($custom);

        // counter template
        $description = new Text('starttime');
        $description->setLabel($this -> translate['configuration-game_timetostart']);
        $description->addValidators(array(
            new PresenceOf(array(
                'message' => $this -> translate['form-field_required']
            ))
        ));
        $this->add($description);

        // Game keywords
        $keywords = new Text('keywords');
        $keywords->setLabel($this -> translate['configuration-game_keywords']);
        $keywords->addValidators(array(
            new PresenceOf(array(
                'message' => $this -> translate['form-field_required']
            ))
        ));
        $this->add($keywords);

        // Game publicUrl
        $publicUrl = new Text('url');
        $publicUrl->setLabel($this -> translate['configuration-game_url']);
        $publicUrl->addValidators(array(
            new PresenceOf(array(
                'message' => $this -> translate['form-field_required']
            )),
            new UrlValidator(array(
                'message' => $this -> translate['form-field_url']
            ))
        ));
        $this->add($publicUrl);

        // register is off/on
        $registerOff = new Select("registeroff", array('true' => $this -> translate['yes'], 'false' => $this -> translate['no']), array(
            'using' => array('id', 'name'),
            'value' => ($this -> config -> game -> registerOff ? 'true' : 'false')
        ));
        $registerOff->setLabel($this -> translate['configuration-register_off']);
        $registerOff->addValidators(array(
            new PresenceOf(array(
                'message' => $this -> translate['form-field_required']
            ))
        ));
        $this->add($registerOff);

        // Game google analitycs
        $ga = new Text('ga');
        $ga->setLabel($this -> translate['configuration-google_analitycs']);
        $this->add($ga);

        // Email sender name
        $emailName = new Text('emailName');
        $emailName->setLabel($this -> translate['configuration-email_sender']);
        $emailName->addValidators(array(
            new PresenceOf(array(
                'message' => $this -> translate['form-field_required']
            ))
        ));
        $this->add($emailName);

        // Email data
        $email = new Text('email');
        $email->setLabel($this -> translate['configuration-email']);
        $email->addValidators(array(
            new PresenceOf(array(
                'message' => $this -> translate['form-field_required']
            )),
            new Email(array(
                'message' => $this -> translate['form-email_notvalid']
            ))
        ));
        $this->add($email);

        // email serwer type
        $emailServerType = new Select("emailServerType", array('sendmail' => 'Sendmail', 'smtp' => 'SMTP'), array(
            'using' => array('id', 'name'),
            'value' => $this -> config -> mail -> serverType
        ));
        $emailServerType->setLabel($this -> translate['configuration-servet_type']);
        $emailServerType->addValidators(array(
            new PresenceOf(array(
                'message' => $this -> translate['form-field_required']
            ))
        ));
        $this->add($emailServerType);

        // Email SMTP server
        $emailServer = new Text('emailServer');
        $emailServer->setLabel($this -> translate['configuration-smtp_server']);
        $this->add($emailServer);

        // Email SMTP server port
        $emailServerPort = new Text('emailServerPort');
        $emailServerPort->setLabel($this -> translate['configuration-smtp_port']);
        $this->add($emailServerPort);

        // Email SMTP server security
        $emailServerSecurity = new Select("emailServerSecurity", array('' => $this -> translate['none'], 'ssl' => 'ssl', 'tls' => 'tls'), array(
            'using' => array('id', 'name'),
            'value' => $this -> config -> mail -> smtp -> security
        ));
        $emailServerSecurity->setLabel($this -> translate['configuration-smtp_security']);
        $this->add($emailServerSecurity);

        // Email SMTP server user
        $emailServerUser = new Text('emailServerUser');
        $emailServerUser->setLabel($this -> translate['configuration-smtp_username']);
        $this->add($emailServerUser);

        // Email SMTP server password
        $emailServerPass = new Password('emailServerPass');
        $emailServerPass->setLabel($this -> translate['configuration-smtp_password']);
        $this->add($emailServerPass);

        // CSRF
        $csrf = new Hidden('csrf');

        $csrf->addValidator(new Identical(array(
            'value' => $this->security->getToken(),
            'message' => $this -> translate['form-token_csrf']
        )));

        $this->add($csrf);

        // Sign Up
        $this->add(new Submit($this -> translate['save'], array(
            'class' => 'btn btn-success'
        )));
    }

    /**
     * Show rendered form field html
     * @param $name
     */
    public function renderDecorated($name, $attributes = null)
    {
        $element  = $this->get($name);
        $element->setAttributes($attributes);
        $element->setAttribute('aria-describedby', 'form-'.$element->getName());

        // Get any generated messages for the current element
        $messages = $this->getMessagesFor(
            $element->getName()
        );

        echo '<div class="form-group '.(count($messages) > 0 ?  'has-error' : '').'"><div class="col-md-12"><div class="input-group">';
        echo '<span class="input-group-addon"><span class="glyphicon glyphicon-circle"></span>'.$element->getLabel().'</span>';
        if (isset($attributes['tooltip'])) echo '<div class="input-group-addon" data-toggle="tooltip" data-placement="right" title="'.$attributes['tooltip'].'"><i class="fa fa-question-circle"></i></div>';
        echo $element;
        echo '</div>';
        if (count($messages)) {
            echo '<span id="form-'.$element->getName().'" class="help-block">';
            foreach ($messages as $message) {
                echo $message.'<br />';
            }
            echo '</span>';
        }
        echo '</div></div>';
    }

    /**
     * Prints messages for a specific element
     */
    public function messages($name)
    {
        if ($this->hasMessagesFor($name)) {
            foreach ($this->getMessagesFor($name) as $message) {
                $this->flash->error($message);
            }
        }
    }
}
