<?php

namespace Tests\Reversi\Spec;

use Reversi\Model\Board;
use Reversi\Spec\ReversiBoardSizeSpecification;

class ReversiBoardSizeSpecificationTest extends \PHPUnit_Framework_TestCase
{

  public function testIsSatisfiedByShouldReturnFalseWithAnInvalidBoard()
  {

    $spec = new ReversiBoardSizeSpecification();
    $this->assertEquals($spec->isSatisfiedBy(4, 4), true);
    $this->assertEquals($spec->isSatisfiedBy(4, 3), false);

  }

}
