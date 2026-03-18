<?php
namespace knet\Helpers\FiltersUtils;

use Carbon\Carbon;
use Illuminate\Support\Str;

class DateFilter
{
    private bool $enabled = false;
    private string $op = '';
    private $date1 = null;
    private $date2 = null;
    public function set($op, $date1, $date2 = null)
    {
        if (in_array($op, ['between', 'after', 'before', 'exact'])) {
            $this->op = $op;
        }
        try {
            if (gettype($date1)=='string') {
                $this->date1 = Carbon::createFromFormat('d/m/Y', $date1);
            }
            if ($date1 instanceof Carbon) {
                $this->date1 = $date1;
            }
            if (gettype($date2)=='string') {
                $this->date2 = Carbon::createFromFormat('d/m/Y', $date2);
            }
            if ($date2 instanceof Carbon) {
                $this->date2 = $date2;
            }
        } catch (\Throwable $th) {
            report($th);
        }
        if (!empty($this->op) && !empty($this->date1)) {
            if ($this->op == 'between' && !empty($this->date2)) {
                $this->enabled = true;
            } else {
                $this->enabled = true;
            }
        }
    }
    public function getOp()
    {
        if ($this->isEnabled()) {
            switch ($this->op) {
                case 'between':
                    return 'between';
                    break;
                case 'after':
                    return '>=';
                    break;
                case 'before':
                    return '<=';
                    break;
                case 'exact':
                    return '=';
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
            if ($this->op == 'between') {
                return [$this->date1, $this->date2];
            } else {
                return $this->date1;
            }
        }
        return null;
    }
    public function toString()
    {
        if ($this->isEnabled()) {
            switch ($this->op) {
                case 'between':
                    return '[' . $this->date1->format('d/m/Y') . ' <=> ' . $this->date2->format('d/m/Y') . ']';
                    break;
                case 'after':
                    return '>=' . $this->date1->format('d/m/Y');
                    break;
                case 'before':
                    return '<=' . $this->date1->format('d/m/Y');
                    break;
                case 'exact':
                    return '=' . $this->date1->format('d/m/Y');
                    break;
                default:
                    return $this->date1->format('d/m/Y');
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