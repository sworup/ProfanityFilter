<?php

namespace Sworup\ProfanityFilter;

class ProfanityFilter
{
    protected $swearWords = [];
    protected $blackList  = [];
    protected $replace    = [];

    public function __construct($swearWords, $blackList, $replace)
    {
        $this->swearWords = $swearWords;
        $this->blackList  = $blackList;
        $this->replace    = $replace;
    }

    public function clean($string, $censorChar = "*")
    {
        if (empty($string)) {
            return $this->createOutputObject();
        }

        $newstring = $this->checkForBadWordsAnywhere($string, $censorChar);
        $newstring = $this->checkForBlackListedWords($newstring, $censorChar);
                
        return $this->createOutputObject($string, $newstring);
    }

    private function checkForBadWordsAnywhere($string, $censorChar)
    {
        $replace   = $this->replace;
        $badwords  = $this->swearWords;
        $total_badwords = count($badwords);
        $replacement = array();
        for ($x = 0; $x < $total_badwords; $x++) {
            $replacement[$x] = str_repeat($censorChar, strlen($badwords[$x]));
            $badwords[$x] = '/'.str_ireplace(array_keys($replace), array_values($replace), $badwords[$x]).'/i';
        }
        return preg_replace($badwords, $replacement, $string);
    }

    private function checkForBlackListedWords($string, $censorChar)
    {
        $blackList = $this->blackList;
        $replace   = $this->replace;
        $replacement = array();
        $total_blacklist = count($blackList);
        for ($i = 0; $i < $total_blacklist; $i++) {
            $splitword = str_split($blackList[$i]);
            $first_time_through = true;
            $regex_ready_word = "";
            $replacement[$i] = str_repeat($censorChar, strlen($blackList[$i]));
            foreach ($splitword as $letter) {
                if ($first_time_through) {
                    $first_time_through = false;
                    $regex_ready_word .= str_ireplace(array_keys($replace), array_values($replace), $letter);
                } else {
                    $regex_ready_word .= "\W*" . str_ireplace(array_keys($replace), array_values($replace), $letter);
                }
            }
            
            $blackList[$i] = "#\b$regex_ready_word\b#i";
        }
        return preg_replace($blackList, $replacement, $string);
    }

    private function createOutputObject($string = '', $new_string = '')
    {
        return array(
            'old_string' => $string,
            'new_string' => $new_string,
            'clean'      => ($string === $new_string) ? true : false
            );
    }

    
}
