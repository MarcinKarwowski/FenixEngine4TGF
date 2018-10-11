<?php

namespace App\Service;

class ParseString extends \Phalcon\Mvc\User\Component
{
    public function htmltobbcode($text)
    {

        $find = array(
            '~<b>(.*?)\<\/b>~is',
            '~<i>(.*?)\<\/i>~is',
            '~<span style=\"text-decoration:underline;\">(.*?)<\/span>~is',
            '~<div style=\"float: left;\">(.*?)<\/div>~is',
            '~<div style=\"float: right;\">(.*?)<\/div>~is',
            '~<div style=\"text-align: center;\">(.*?)<\/div>~is',
            '~<pre>(.*?)<\/pre>~is',
            '~<span style=\"font-size:(.*?)px;\">(.*?)<\/span>~is',
            '~<span style=\"color:(.*?);\">(.*?)<\/span>~is',
            '~<a href=\"(.*?)\">(.*?)<\/a>~is',
            '~<img src=\"(.*?)\" alt=\"\" />~is'
        );

        $replace = array(
            '[b]$1[/b]',
            '[i]$1[/i]',
            '[u]$1[/u]',
            '[left]$1[/left]',
            '[right]$1[/right]',
            '[center]$1[/center]',
            '[quote]$1[/quote]',
            '[size=$1]$2[/size]',
            '[color=$1]$2[/color]',
            '[url=$1]$2[/url]',
            '[img]$1[/img]'
        );

        $text = preg_replace($find,$replace,$text);

        /*
         * Dodatki do sesji
         */
        $seek = '!<div class="bbcodetohtml">(.+?)<\/div>!si';
        if (preg_match_all($seek, $text, $arraytext))
        {
            foreach ($arraytext[1] as $i => $b)
                $text = preg_replace($seek, '*' . $arraytext[1][$i] . '*', $text, 1);
        }

        $text = strip_tags($text);

        /**
         * Return converted text
         */
        return $text;
    }
    public function bbcodetohtml($text)
    {
        /*
         * Phalcon escape (htmlspecialchars too)
         */
        //$this -> escaper->setHtmlQuoteType(ENT_QUOTES);
        //$text = $this -> escaper -> escapeHtml($text);

        /**
         * Delete HTML tags from text
         */
        $text = strip_tags($text);

        $seek = '{\*(.+?)\*}si';
        if (preg_match_all($seek, $text, $arraytext))
        {
            foreach ($arraytext[1] as $i => $b)
                $text = preg_replace($seek, '<div class="bbcodetohtml">' . $arraytext[1][$i] . '</div>', $text, 1);
        }

        /*
         * Parse bbcode
         */
        $find = array(
            '~\[b\](.*?)\[/b\]~is',
            '~\[i\](.*?)\[/i\]~is',
            '~\[u\](.*?)\[/u\]~is',
            '~\[left\](.*?)\[/left\]~is',
            '~\[right\](.*?)\[/right\]~is',
            '~\[center\](.*?)\[/center\]~is',
            '~\[quote\](.*?)\[/quote\]~is',
            '~\[size=(.*?)\](.*?)\[/size\]~is',
            '~\[color=(.*?)\](.*?)\[/color\]~is',
            '~\[url\]((?:ftp|https?)://.*?)\[/url\]~is',
            '~\[url=((?:ftp|https?)://.*?)\](.*?)\[/url\]~is',
            '~\[img\](https?://.*?\.(?:jpg|jpeg|gif|png|bmp))\[/img\]~is'
        );

        $replace = array(
            '<b>$1</b>',
            '<i>$1</i>',
            '<span style="text-decoration:underline;">$1</span>',
            '<div style="float: left;">$1</div>',
            '<div style="float: right;">$1</div>',
            '<div style="text-align: center;">$1</div>',
            '<pre>$1</'.'pre>',
            '<span style="font-size:$1px;">$2</span>',
            '<span style="color:$1;">$2</span>',
            '<a href="$1">$1</a>',
            '<a href="$1">$2</a>',
            '<img src="$1" alt="" />'
        );

        $text = preg_replace($find,$replace,$text);

        /**
         * Add <br />
         */
        $text = nl2br($text);

        return $text;
    }
}
