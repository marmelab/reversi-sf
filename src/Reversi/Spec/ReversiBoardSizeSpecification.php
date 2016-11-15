<?php

namespace Reversi\Spec;

use Reversi\Model\Board;

class ReversiBoardSizeSpecification
{

  public function isSatisfiedBy($cols, $rows)
  {
      return $rows%2 === 0 && $cols%2 === 0 && $rows >= 4 && $cols >= 4;
  }

}
