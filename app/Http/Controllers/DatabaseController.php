<?php

namespace App\Http\Controllers;

use App\Models\CallLogInfo;

class DatabaseController extends Controller
{
    public function insertField(string $field, $value)
    {
        $allowedFields = ['name_given', 'email_given', 'company_given'];
    
        if (!in_array($field, $allowedFields)) {
            return; 
        }
    
        $lastRow = CallLogInfo::orderByDesc('id')->first();
    
        if ($lastRow) {
            $lastRow->$field = $value;
            $lastRow->save();
        }
    }
    
}
