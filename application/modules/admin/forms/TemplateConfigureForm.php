<?php
namespace Admin\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\StringLength;

class TemplateConfigureForm extends Form
{

    public function initialize($entity = null, $options = null)
    {
        // Custom code
        $custom = new TextArea('custom');
        $custom->setLabel($this -> translate['configuration-custom_code']);
        $this->add($custom);

        // template
        $template = new Select("template", array('default' => $this -> translate['configuration-default_template'], 'fenix' => $this -> translate['configuration-fenix_template']), array(
            'using' => array('id', 'name'),
            'value' => $this -> config -> game -> template
        ));
        $template->setLabel($this -> translate['configuration-template']);
        $template->addValidators(array(
            new PresenceOf(array(
                'message' => $this -> translate['form-field_required']
            ))
        ));
        $this->add($template);

        $template_text_color = new Text("template_text_color");
        $template_text_color->setLabel($this -> translate['configuration-template_text_color']);
        $template_text_color->addValidators(array(
            new StringLength(
                [
                    'max' => 6,
                    'min' => 6,
                    'messageMaximum' => $this->translate['form-field_toolong'],
                    'messageMinimum' => $this->translate['form-field_tooschort'],
                    'allowEmpty' => true,
                ]
            ),
            new Regex(
                [
                    'message' => $this->translate['form-field_alphanum_required'],
                    'pattern' => '/[a-zA-Z0-9]+/',
                    'allowEmpty' => true,
                ]
            )
        ));
        $this->add($template_text_color);

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
