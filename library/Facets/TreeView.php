<?php
namespace App\Facets;

use Phalcon\Mvc\User\Component;

/**
 * App\Service\Mail
 * Sends e-mails based on pre-defined templates
 */
class TreeView extends Component
{

    public $retArr = array();

    public function __construct($arr)
    {
        $this -> recursive($this -> categoriesToTree($arr));
    }

    public function recursive($arr, $indent = 0)
    {
        $arrRecursive = array();
        foreach ($arr as $k => $v)
        {
            if (is_array($v))
            {
                $this -> recursive($v, ($indent + 1));
            }
            elseif ($k != '')
            {
                $arrRecursive[$k] = $v; // Zapisuję wartość

                $keys = array_keys($arr);
                if ($k == $keys[sizeof($keys) - 2])
                {
                    $arrRecursive['deep'] = $this -> nodeMark($indent);
                    $this -> retArr[$arrRecursive['id']] = $arrRecursive;
                }
            }
        }
    }

    public function nodeMark($ident)
    {
        $ident = (int) $ident;
        if ($ident <= 1) return '';

        return str_repeat('-', ($ident - 1));
    }

    public function categoriesToTree(&$categories) {

        $map = array(
            0 => array('subcategories' => array())
        );

        foreach ($categories as &$category) {
            $category['subcategories'] = array();
            $map[$category['id']] = &$category;
        }

        foreach ($categories as &$category) {
            $map[$category['parent_id']]['subcategories'][] = &$category;
        }

        return $map[0]['subcategories'];
    }
}
