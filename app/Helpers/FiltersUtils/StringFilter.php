<?php
namespace knet\Helpers\FiltersUtils;

use Carbon\Carbon;
use Illuminate\Support\Str;

class StringFilter
{
    private bool $enabled = false;
    private string $op = '';
    private $value = null;
    public function set($op, $value)
    {
        if (in_array($op, ['eql', 'stw', 'cnt', 'notEql'])) {
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
                case 'stw':
                    return 'LIKE';
                    break;
                case 'cnt':
                    return 'LIKE';
                    break;
                case 'notEql':
                    return '!=';
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
            switch ($this->op) {
                case 'eql':
                    return $this->value;
                    break;
                case 'stw':
                    return $this->value.'%';
                    break;
                case 'cnt':
                    return '%'. $this->value . '%';;
                    break;
                case 'notEql':
                    return $this->value;
                    break;
                default:
                    return $this->value;
                    break;
            }
        }
        return null;
    }
    public function toString()
    {
        if ($this->isEnabled()) {
            switch ($this->op) {
                case 'eql':
                    return 'è uguale a ' . $this->value;
                    break;
                case 'stw':
                    return 'inizia con ' . $this->value;
                    break;
                case 'cnt':
                    return 'contiene ' . $this->value;
                    break;
                case 'notEql':
                    return 'diverso da ' . $this->value;
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
