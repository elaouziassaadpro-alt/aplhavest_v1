<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class CNASNU extends Model
{
        use HasFactory;
    protected $fillable = [
        'dataID',
        'firstName',
        'secondName',
        'thirdName',
        'fourthName',
        'originalName',
        'comment1',
        'nationality',
        'aliasName',
        'typeOfDocument',
        'documentNumber',
        'adresse',
        'city',
        'country',
        'dateOfBirth',
        'dateAjout',
    ];
    public static function normalizeName(?string $text): ?string
    {
        if (!$text) return null;

        $text = mb_strtolower($text);

        // Arabic normalization
        $arabicSearch = [
            'أ','إ','آ','ٱ',
            'ة','ى',
            'ؤ','ئ',
            'َ','ً','ُ','ٌ','ِ','ٍ','ْ','ّ'
        ];

        $arabicReplace = [
            'ا','ا','ا','ا',
            'ه','ي',
            'و','ي',
            '','','','','','','',''
        ];

        $text = str_replace($arabicSearch, $arabicReplace, $text);

        // Latin phonetic normalization
        $text = str_replace(['ph','th','kh','gh','sh','ch'], ['f','t','k','g','s','k'], $text);

        // Remove vowels (mohamed → mhmd)
        $text = preg_replace('/[aeiouy]/', '', $text);

        // Remove spaces & symbols
        $text = preg_replace('/[^a-z0-9ء-ي]/u', '', $text);

        return $text;
    }
}
