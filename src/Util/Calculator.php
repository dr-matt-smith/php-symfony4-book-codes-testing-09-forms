<?php

namespace App\Util;


class Calculator
{
    public function add($n1, $n2)
    {
        return $n1 + $n2;
    }

    public function subtract($n1, $n2)
    {
        return $n1 - $n2;
    }

    public function divide($n, $divisor)
    {
        if(empty($divisor)){
            throw new \InvalidArgumentException("Divisor must be a number");
        }

        return $n / $divisor;
    }

    public function process($n1, $n2, $process)
    {
        switch($process){
            case 'subtract':
                return $this->subtract($n1, $n2);
                break;
            case 'divide':
                return $this->divide($n1, $n2);
                break;
            case 'add':
            default:
                return $this->add($n1, $n2);
        }
    }
}

