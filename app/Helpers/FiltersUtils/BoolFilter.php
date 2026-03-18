<?php
namespace knet\Helpers\FiltersUtils;

use Carbon\Carbon;
use Illuminate\Support\Str;

class BoolFilter
{
    private bool $enabled = false;
    private bool $value = false;
    public function set($value)
    {
        if (in_array(Str::lower($value), ['vero', 'true', 'sì', 'si', 'yes', 1, '1'])) {
            $this->value = true;
        } else {
            $this->value = false;
        }
        $this->enabled = true;
    }
    public function get()
    {
        return $this->value;
    }
    public function toString()
    {
        return ($this->value) ? 'Vero' : 'Falso';
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