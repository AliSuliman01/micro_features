<?php


namespace AliSuliman\MicroFeatures\Jobs;


abstract class Job
{
    abstract public function handle();

    public function getProps(): array
    {
        return get_object_vars($this);
    }
    public function setProps($props): array
    {
        foreach ($props as $key => $value)
            $this->$key = $value;
    }
}
