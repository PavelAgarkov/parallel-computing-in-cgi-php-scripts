<?php

namespace jobs;

use src\routine\php_routine\RoutineInterface;
use src\routine\php_routine\RoutineTrait;

class Async_1 implements RoutineInterface
{
    use RoutineTrait;

    public function __construct()
    {
        $a = 1;
    }

    public function beforeExecute(): self
    {
        $a = 2;

        return $this;
    }

    public function execute(): self
    {
        $Job = $this->Routine->getJob();
        $Job->synchroniseRead();
        $read = $Job->getOutput();

        $id = posix_getpid();
        $fp = fopen("t{$id}.txt", "w");
        $str = implode(',', $read);
        fwrite($fp, " {$str} \r\n");
        fclose($fp);

        $Job->runSingleAsyncJob();

        return $this;
    }

    public function afterExecute(): self
    {
        $a = 4;

        return $this;
    }

    public function logic(): array
    {
        $array = [10];
        foreach (range(0, 9) as $key => $value) {
            $array[] = $value;
        }
        return $array;
    }
}