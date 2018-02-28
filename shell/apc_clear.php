<?php

class Apc
{
    public function clear() {
        if (!function_exists('apc_clear_cache')) {
            echo 'error: apc is not installed.';
            return false;
        }
        
        if (apc_clear_cache('opcode')) {
            echo 'success: apc opcode cache cleared.';
            return true;
        } else {
            echo 'success: apc opcode cache not cleared.';
            return false;
        }
    }
}

$objApc = new Apc();
$objApc->clear();