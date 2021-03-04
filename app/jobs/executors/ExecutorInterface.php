<?php

namespace app\jobs\executors;

interface ExecutorInterface
{
    public static function trigger($executorData = []);
}
