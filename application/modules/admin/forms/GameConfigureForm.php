<?php
namespace Admin\Forms;

use Phalcon\Forms\Form;
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
use App\Validators\CreatemapValidator;

class GameConfigureForm extends Form
{

    public function initialize($entity = null, $options = null)
    {
        $charactersAmount = new Text('charactersAmount');
        $charactersAmount->setLabel($this -> translate['panel-characters_amount']);
        $charactersAmount->addValidators(array(
            new PresenceOf(array(
                'message' => $this -> translate['form-field_required']
            )),
            new StringLength(array(
                'max' => 1,
                'min' => 1,
                'messageMaximum' => $this -> translate['form-field_toolong'],
                'messageMinimum' => $this -> translate['form-field_tooschort']
            )),
            new Regex(array(
                'message' => $this -> translate['form-field_integer_required'],
                'pattern' => '/[0-9]+/'
            ))
        ));
        $this->add($charactersAmount);

        // register is off/on
        $levelOff = new Select("leveloff", array(1 => $this -> translate['yes'], 0 => $this -> translate['no']), array(
            'using' => array('id', 'name'),
            'value' => ($this -> config -> game -> params -> levelOff ? 1 : 0)
        ));
        $levelOff->setLabel($this -> translate['configuration-game_keywords']);
        $levelOff->addValidators(array(
            new PresenceOf(array(
                'message' => $this -> translate['form-field_required']
            ))
        ));
        $this->add($levelOff);

        $levelCap = new Text('levelCap');
        $levelCap->setLabel($this -> translate['panel-level_cap']);
        $levelCap->addValidators(array(
            new PresenceOf(array(
                'message' => $this -> translate['form-field_required']
            )),
            new StringLength(array(
                'max' => 100,
                'min' => 1,
                'messageMaximum' => $this -> translate['form-field_toolong'],
                'messageMinimum' => $this -> translate['form-field_tooschort']
            )),
            new Regex(array(
                'message' => $this -> translate['form-field_integer_required'],
                'pattern' => '/[0-9]+/'
            ))
        ));
        $this->add($levelCap);

        $eraDate = new Text('eraDate');
        $eraDate->setLabel($this -> translate['panel-level_cap']);
        $eraDate->addValidators(array(
            new PresenceOf(array(
                'message' => $this -> translate['form-field_required']
            )),
            new StringLength(array(
                'max' => 6,
                'min' => 1,
                'messageMaximum' => $this -> translate['form-field_toolong'],
                'messageMinimum' => $this -> translate['form-field_tooschort']
            )),
            new Regex(array(
                'message' => $this -> translate['form-field_integer_required'],
                'pattern' => '/[0-9]+/'
            ))
        ));
        $this->add($eraDate);

        // map module on/off
        $mapOn = new Select("mapOn", array(1 => $this -> translate['yes'], 0 => $this -> translate['no']), array(
            'using' => array('id', 'name'),
            'value' => ($this -> config -> game -> params -> mapOn ? 1 : 0)
        ));
        $mapOn->setLabel($this -> translate['configuration-game_keywords']);
        $mapOn->addValidators(array(
            new PresenceOf(array(
                'message' => $this -> translate['form-field_required']
            )),
            new CreatemapValidator(array(
                'message' => $this -> translate['map-validator_not_pass'],
                'mapdir' => PUBLIC_PATH.$this -> config -> url -> staticBaseUri.'static/map/'
            ))
        ));
        $this->add($mapOn);

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
