<?php

namespace spec\Sworup\ProfanityFilter;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ProfanityFilterSpec extends ObjectBehavior
{
    function let()
    {
        $swearWords = ['fuck'];
        $blackList  = ['ass'];
        $replace =    [];
        $this->beConstructedWith($swearWords, $blackList, $replace);
    }
    function it_is_initializable()
    {
        $this->shouldHaveType('Sworup\ProfanityFilter\ProfanityFilter');
    }


    function it_checks_an_empty_string()
    {
        $string = '';
        $this->clean($string)->shouldHaveKeyWithValue('old_string', '');
        $this->clean($string)->shouldHaveKeyWithValue('new_string', '');
        $this->clean($string)->shouldHaveKeyWithValue('clean', true);

    }

    function it_checks_for_a_bad_word()
    {
        $string = 'Ass clown asshole fucktard';
        $this->clean($string)->shouldBeArray();
        $this->clean($string)->shouldHaveKeyWithValue('new_string', "*** clown asshole ****tard");
        $this->clean($string)->shouldHaveKeyWithValue('clean', false);
    }

    function it_checks_for_black_listed_word()
    {
        $string = 'Ass clown asshole';
        $this->clean($string)->shouldBeArray();
        $this->clean($string)->shouldHaveKeyWithValue('new_string', "*** clown asshole");
        $this->clean($string)->shouldHaveKeyWithValue('clean', false);
    }
}
