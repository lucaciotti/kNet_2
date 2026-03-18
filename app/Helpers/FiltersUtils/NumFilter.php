<?php
namespace knet\Helpers\FiltersUtils;

use Carbon\Carbon;
use Illuminate\Support\Str;

class NumFilter
{
    private bool $enabled = false;
    private string $op = '';
    private $value = null;
    public function set($op, $value)
    {
        if (in_array($op, ['eql', 'min', 'plus'])) {
            $this->op = $op;
        }
        $this->value = $value;
        if (!empty($this->op) && $this->value!==null) {
            $this->enabled = true;
        }
    }
    public function getOp()
    {
        if ($this->isEnabled()) {
            switch ($this->op) {
                case 'eql':
                    return '=';
                    break;
                case 'min':
                    return '<';
                    break;
                case 'plus':
                    return '>';
                    break;
                default:
                    return '';
                    break;
            }
        }
        return null;
    }
    public function get()
    {
        if ($this->isEnabled()) {
            return $this->value;
        }
        return null;
    }
    public function toString()
    {
        if ($this->isEnabled()) {
            switch ($this->op) {
                case 'eql':
                    return '== ' . $this->value;
                    break;
                case 'min':
                    return '< ' . $this->value;
                    break;
                case 'plus':
                    return '> ' . $this->value;
                    break;
                default:
                    return $this->value;
                    break;
            }
        }
        return '';
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