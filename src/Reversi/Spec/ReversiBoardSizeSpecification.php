<?php

namespace Reversi\Spec;

class ReversiBoardSizeSpecification
{
    public function isSatisfiedBy($cols, $rows)
    {
        return $rows % 2 === 0 && $cols % 2 === 0 && $rows >= 4 && $cols >= 4;
    }
}
