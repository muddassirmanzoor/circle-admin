<?php

namespace App\Helpers;

use Illuminate\Validation\Rule;

Class ProfessionValidationHelper {
    /*
      |--------------------------------------------------------------------------
      | ProfessionValidationHelper that contains all the Profession Validation methods for APIs
      |--------------------------------------------------------------------------
      |
      | This Helper controls all the methods that use Profession processes
      |
     */

    public static function englishMessages() {
        return [
            'name.required' => 'Profession text is required',
        ];
    }


    public static function saveProfessionRules() {
        $validate['rules'] = [
            'name' => [
                'required',
                Rule::unique('professions', 'name')->where('is_archive', 0),
            ]];
        $validate['message_en'] = self::englishMessages();
        return $validate;
    }

    public static function updateProfessionRules() {
        $validate['rules'] = [
            'name' => 'required|unique:professions,name'
        ];
        $validate['message_en'] = self::englishMessages();
        return $validate;
    }
}

?>
