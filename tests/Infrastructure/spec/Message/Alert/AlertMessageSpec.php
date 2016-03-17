<?php

namespace spec\Infrastructure\Message\Alert;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AlertMessageSpec extends ObjectBehavior
{
    function let(\Maknz\Slack\Client $client)
    {
        $this->beConstructedWith($client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Infrastructure\Message\Alert\AlertMessage');
    }
}
