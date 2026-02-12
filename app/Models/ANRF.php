<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ANRF extends Model
{
        use HasFactory;

    protected $fillable = [
        'nom',
        'identifiant',
        'pays',
        'dateAjout',
        'activite',
    ];
    public static function normalizeName(?string $text): ?string
{
    if (!$text) return null;

    // Lowercase
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

    // Latin normalization (Mohamed variations)
    $latinSearch = ['ph', 'th', 'kh', 'gh', 'sh', 'ch'];
    $latinReplace = ['f', 't', 'k', 'g', 's', 'k'];

    $text = str_replace($latinSearch, $latinReplace, $text);

    // Remove vowels (mohamed → mhmd)
    $text = preg_replace('/[aeiouy]/', '', $text);

    // Remove spaces & special chars
    $text = preg_replace('/[^a-z0-9ء-ي]/u', '', $text);

    return $text;
}

}
