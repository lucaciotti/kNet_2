<?php
namespace knet\Helpers\FiltersUtils;

use Carbon\Carbon;
use Illuminate\Support\Str;

class ArrayFilter
{
    private bool $enabled = false;
    private $value = [];
    public function set(array $value)
    {
        $this->value = $value;
        if (!empty($this->value)) $this->enabled = true;
    }
    public function get()
    {
        return $this->value;
    }
    public function toString()
    {
        return '[' . implode(', ', $this->value) . ']';
    }
    public function isEnabled()
    {
        return $this->enabled;
    }
    public function disable()
    {
        return $this->enabled = false;
    }
    public function enable()
    {
        return $this->enabled = true;
    }
}