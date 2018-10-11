<?php

namespace App\Service;


class MyFlash extends \Phalcon\Flash\Session implements \Phalcon\FlashInterface
{
    /**
     * Adds error message to stack
     * @param ParseString $text
     * @return Flash
     */
    public function error($message)
    {
        $message .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
        parent::error($message);
    }

    /**
     * Adds notice message to stack
     * @param ParseString $text
     * @return Flash
     */
    public function notice($message)
    {
        $message .= ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
        parent::notice($message);
    }

    public function success($message)
    {
        $message .= ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
        parent::success($message);
    }
}
