<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Smalot\PdfParser\Parser;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportFiles extends Component
{
    use WithFileUploads;

    // ── File inputs ──────────────────────────────────────────────
    public $cnasnusFile;
    public $anrfFile;

    // ── Optional description fields ──────────────────────────────
    public $cnasnusDescription = '';
    public $anrfDescription    = '';

    // ── Flash messages (inline, no full-page reload) ─────────────
    public $successMessage = '';
    public $errorMessage   = '';

    // ── Validation rules ─────────────────────────────────────────
    protected function rules(): array
    {
        return [
            'cnasnusFile'        => 'nullable|file|mimes:xml,html,htm,pdf|max:102400',
            'anrfFile'           => 'nullable|file|mimes:xml,html,htm,pdf|max:102400',
            'cnasnusDescription' => 'nullable|string|max:255',
            'anrfDescription'    => 'nullable|string|max:255',
        ];
    }

    protected $messages = [
        'cnasnusFile.mimes' => 'Le fichier CNASNUS doit être au format XML, HTML ou PDF.',
        'cnasnusFile.max'   => 'Le fichier CNASNUS ne doit pas dépasser 100 Mo.',
        'anrfFile.mimes'    => 'Le fichier ANRF doit être au format XML, HTML ou PDF.',
        'anrfFile.max'      => 'Le fichier ANRF ne doit pas dépasser 100 Mo.',
    ];

    // ─────────────────────────────────────────────────────────────
    // Public actions (called from the view via wire:click)
    // ─────────────────────────────────────────────────────────────

    public function importCnasnus()
    {
        $this->clearMessages();

        $this->validate([
            'cnasnusFile' => 'required|file|mimes:xml,html,htm,pdf|max:102400',
        ], [
            'cnasnusFile.required' => 'Veuillez sélectionner un fichier CNASNUS.',
            'cnasnusFile.mimes'    => 'Le fichier CNASNUS doit être au format XML, HTML ou PDF.',
            'cnasnusFile.max'      => 'Le fichier CNASNUS ne doit pas dépasser 100 Mo.',
        ]);

        $file      = $this->cnasnusFile;
        $mime      = $file->getMimeType();
        $extension = strtolower($file->getClientOriginalExtension());

        if (str_contains($mime, 'html') || in_array($extension, ['html', 'htm'])) {
            return $this->processHtml($file, 'cnasnus');
        } elseif (str_contains($mime, 'xml') || $extension === 'xml') {
            return $this->processXml($file, 'cnasnus');
        } elseif (str_contains($mime, 'pdf') || $extension === 'pdf') {
            return $this->processPdf($file, 'cnasnus');
        }

        $this->errorMessage = 'Format de fichier non supporté ❌';
    }

    public function importAnrf()
    {
        $this->clearMessages();

        $this->validate([
            'anrfFile' => 'required|file|mimes:xml,html,htm,pdf,xlsx,xls|max:102400',
        ], [
            'anrfFile.required' => 'Veuillez sélectionner un fichier ANRF.',
            'anrfFile.mimes'    => 'Le fichier ANRF doit être au format XML, HTML, PDF ou Excel (XLSX, XLS).',
            'anrfFile.max'      => 'Le fichier ANRF ne doit pas dépasser 100 Mo.',
        ]);

        $file      = $this->anrfFile;
        $mime      = $file->getMimeType();
        $extension = strtolower($file->getClientOriginalExtension());

        // Check extension first for more reliable detection
        if (in_array($extension, ['xlsx', 'xls']) || str_contains($mime, 'spreadsheet') || str_contains($mime, 'excel')) {
            return $this->processExel($file, 'anrf');
        } elseif (in_array($extension, ['html', 'htm']) || str_contains($mime, 'html')) {
            return $this->processHtml($file, 'anrf');
        } elseif ($extension === 'xml' || str_contains($mime, 'xml')) {
            return $this->processXml($file, 'anrf');
        } elseif ($extension === 'pdf' || str_contains($mime, 'pdf')) {
            return $this->processPdf($file, 'anrf');
        }

        $this->errorMessage = 'Format de fichier non supporté ❌';
    }

    // ─────────────────────────────────────────────────────────────
    // Private processors
    // ─────────────────────────────────────────────────────────────

    private function processPdf($file, string $type): void
    {
        if ($type !== 'cnasnus') {
            $this->errorMessage = 'Import PDF non disponible pour ANRF ❌';
            return;
        }

        // ── Extract and normalize text from PDF ──────────────────────────────
        $parser = new Parser();
        $text   = preg_replace('/\s+/', ' ', $parser->parseFile($file->getRealPath())->getText());

        // ── Split on known CNASNUS entry identifiers (HTi.006, IQi.001 …) ────
        // Each entry always starts with [2-3 uppercase letters + "i." + digits] then " Nom:"
        $blocks = preg_split('/(?=[A-Z]{2,3}i\.\d+\s+Nom:)/u', $text, -1, PREG_SPLIT_NO_EMPTY);

        $inserted = 0;

        // ── Helpers ───────────────────────────────────────────────────────────
        // n.d. / blank / dash → null
        $nd = fn(?string $v): ?string =>
            ($v !== null && trim($v) !== '' && !in_array(strtolower(trim($v)), ['n.d.', '-', '—']))
                ? trim($v)
                : null;

        // Extract value between two known sequential field labels (no \s+ in lookahead
        // so that empty fields like "Lieu de naissance:" still match correctly)
        $get = function(string $from, string ...$nexts) use (&$block, $nd): ?string {
            $fromPat  = preg_quote($from, '/');
            $nextAlts = implode('|', array_map(fn($n) => preg_quote($n, '/'), $nexts));
            preg_match("/\b{$fromPat}\s*(.*?)(?={$nextAlts}|\$)/isu", $block, $m);
            return $nd($m[1] ?? null);
        };

        foreach ($blocks as $block) {
            $block = trim($block);
            if (!str_contains($block, 'Nom:')) continue;

            // ── DataID ────────────────────────────────────────────────────────
            preg_match('/^([A-Z]{2,3}i\.\d+)/u', $block, $idMatch);
            $dataID = $idMatch[1] ?? null;

            // ── Names (1: FIRST 2: SECOND 3: THIRD 4: FOURTH) ────────────────
            preg_match(
                '/Nom:\s*1:\s*(.*?)\s+2:\s*(.*?)(?:\s+3:\s*(.*?))?(?:\s+4:\s*(.*?))?'
                . '(?=\s+Nom \(alphabet|\s+Titre:|\s+D[ée]signation:|$)/iu',
                $block, $nom
            );
            $firstName  = $nd($nom[1] ?? null);
            $secondName = $nd($nom[2] ?? null);
            $thirdName  = $nd($nom[3] ?? null);
            $fourthName = $nd($nom[4] ?? null);

            // ── Original script (Arabic, Chinese …) ──────────────────────────
            preg_match("/Nom \(alphabet d'origine\):\s*(.*?)(?=\s+Titre:|\s+D[ée]signation:|$)/iu", $block, $orig);
            $originalName = $nd($orig[1] ?? null)
                ?? trim(implode(' ', array_filter([$firstName, $secondName, $thirdName, $fourthName])));

            // ── All remaining fields using the $get() helper ──────────────────
            // Field order in CNASNUS PDF is fixed — use next field name as boundary.
            $dateOfBirth = $get('Date de naissance:',         'Lieu de naissance:',              'Pseudonyme fiable:');
            $city        = $get('Lieu de naissance:',          'Pseudonyme fiable:',              'Nationalité:', 'Nationalite:');
            $aliasName   = $get('Pseudonyme fiable:',          'Pseudonyme peu fiable:',          'Nationalité:', 'Nationalite:');
            // skip "Pseudonyme peu fiable" (not stored)
            $nationality = $get('Nationalité:',                'Numéro de passeport:',            'Numéro national');
            if (!$nationality) {
                $nationality = $get('Nationalite:',            'Numéro de passeport:',            'Numéro national');
            }
            $country     = $nationality ? trim(explode(' b)', $nationality)[0]) : null;

            $passportRaw = $get('Numéro de passeport:',       "Numéro national d'identification:");
            if (!$passportRaw) {
                $passportRaw = $get('Numero de passeport:',   "Numéro national d'identification:");
            }
            $idNumRaw    = $get("Numéro national d'identification:", 'Adresse:');

            // Extract only the bare number — strip description text after the number
            // e.g. "D00000898, délivré le 11 avril 2013 (valable…)" → "D00000898"
            $cleanDocNum = function(?string $raw): ?string {
                if (!$raw) return null;
                // Stop at: comma, opening parenthesis, or descriptive French/English keywords
                $num = preg_replace(
                    '/[\(,].*$/u',
                    '',
                    preg_replace('/\s+(d[ée]livr[ée]|[ée]mis|valable|expirant|issued|valid|expires?|passport\s+n[o°]?).*$/iu', '', $raw)
                );
                return trim($num) ?: null;
            };

            if ($passportRaw) {
                $typeOfDocument = 'Passeport';
                $documentNumber = $cleanDocNum($passportRaw);
            } elseif ($idNumRaw) {
                $typeOfDocument = 'Identification nationale';
                $documentNumber = $cleanDocNum($idNumRaw);
            } else {
                $typeOfDocument = null;
                $documentNumber = null;
            }

            $adresse   = $get('Adresse:',          "Date d'inscription:");
            $dateAjout = $get("Date d'inscription:", 'Renseignements divers:');
            $comment1  = $get('Renseignements divers:'); // last field — matches to end of block

            // ── DB insert ─────────────────────────────────────────────────────
            DB::table('c_n_a_s_n_u_s')->insert([
                'dataID'         => $dataID,
                'firstName'      => $firstName,
                'secondName'     => $secondName,
                'thirdName'      => $thirdName,
                'fourthName'     => $fourthName,
                'originalName'   => $originalName,
                'comment1'       => $comment1,
                'nationality'    => $nationality,
                'aliasName'      => $aliasName,
                'typeOfDocument' => $typeOfDocument,
                'documentNumber' => $documentNumber,
                'adresse'        => $adresse,
                'city'           => $city,
                'country'        => $country,
                'dateOfBirth'    => $this->translateFrenchDate($dateOfBirth),
                'dateAjout'      => $this->translateFrenchDate($dateAjout) ?? now()->toDateTimeString(),
            ]);

            $inserted++;
        }

        // ── Save original PDF to storage ──────────────────────────────────────
        $storedName = strtoupper($type) . '_PDF_' . now()->format('Ymd_His') . '_' . $file->getClientOriginalName();
        $file->storeAs('imports/' . $type . '/pdf', $storedName, 'local');

        $this->successMessage = "{$inserted} entrée(s) importée(s) depuis le PDF ✅ - {$storedName}";
        $this->cnasnusFile = null;
    }
 

    /**
     * Convert "n.d." / empty strings to null.
     */
    private function nd(?string $value): ?string
    {
        if ($value === null) return null;
        $value = trim($value);
        return ($value === '' || strtolower($value) === 'n.d.' || $value === '—') ? null : $value;
    }

    private function processHtml($file, string $type): void
    {
        if ($type !== 'cnasnus') {
            $this->errorMessage = 'Import HTML non disponible pour ANRF ❌';
            return;
        }

        $html = file_get_contents($file->getRealPath());
        libxml_use_internal_errors(true);

        $dom = new \DOMDocument();
        $dom->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new \DOMXPath($dom);
        $rows  = $xpath->query('//tr[contains(@class,"rowtext")]');

        // ── Helper: n.d. / blank / dash → null ──────────────────────────────
        $nd = fn (?string $v): ?string =>
            ($v !== null && trim($v) !== '' && !in_array(strtolower(trim($v)), ['n.d.', '-', '—', 'n.d']))
                ? trim($v)
                : null;

        // ── Helper: extract text between two label boundaries ────────────────
        $get = function (string $text, string $from, string ...$nexts) use ($nd): ?string {
            $fromPat  = preg_quote($from, '/');
            $nextAlts = implode('|', array_map(fn ($n) => preg_quote($n, '/'), $nexts));
            preg_match("/\b{$fromPat}\s*(.*?)(?={$nextAlts}|\$)/isu", $text, $m);
            // Strip sub-labels like "a)" "b)" and trailing whitespace
            $val = preg_replace('/\b[a-z]\)\s*/iu', ' ', $m[1] ?? '');
            return $nd(preg_replace('/\s+/', ' ', $val));
        };

        $inserted = 0;

        foreach ($rows as $row) {
            // Collapse all whitespace and non-breaking spaces to single spaces
            $text = preg_replace('/[\xc2\xa0\s]+/u', ' ', $row->textContent);
            $text = trim($text);

            // Normalize all Unicode apostrophe variants → straight apostrophe
            // so boundary labels like "Date d'inscription:" match reliably
            $text = str_replace(["\u{2019}", "\u{2018}", "\u{02BC}", "\u{0060}"], "'", $text);

            if (!str_contains($text, 'Nom:')) continue;

            // ── DataID  (CDi.014 | QDi.001 | HTi.006 …) ────────────────────
            preg_match('/\b([A-Z]{2,3}i\.\d+)/u', $text, $idMatch);
            $dataID = $nd($idMatch[1] ?? null);

            // ── Names 1: … 2: … 3: … 4: … ──────────────────────────────────
            // Stop before Titre:, Désignation:, OR "Nom (alphabet d'origine):"
            // We use \s* in the lookahead because sometimes labels follow "n.d." directly without a space.
            preg_match(
                '/Nom:\s*1:\s*(.*?)\s+2:\s*(.*?)(?:\s+3:\s*(.*?))?(?:\s+4:\s*(.*?))?'
                . "(?=\\s*(?:Titre:|D[ée]signation:|Nom\\s*\\(alphabet)|$)/iu",
                $text, $nom
            );
            $firstName  = $nd($nom[1] ?? null);
            $secondName = $nd($nom[2] ?? null);
            $thirdName  = $nd($nom[3] ?? null);
            $fourthName = $nd($nom[4] ?? null);

            $originalName = trim(implode(' ', array_filter(
                [$firstName, $secondName, $thirdName, $fourthName]
            ))) ?: null;

            // ── Alias (Pseudonyme fiable) ────────────────────────────────────
            $aliasRaw = $get($text,
                'Pseudonyme fiable:',
                'Pseudonyme peu fiable:', 'Nationalité:', 'Nationalite:'
            );
            $aliasName = $aliasRaw ? mb_substr($aliasRaw, 0, 1000) : null;

            // ── Nationality / country ────────────────────────────────────────
            $nationality = $get($text,
                'Nationalité:',
                'Numéro de passeport:', 'Numero de passeport:', 'Numéro national'
            );
            if (!$nationality) {
                $nationality = $get($text,
                    'Nationalite:',
                    'Numéro de passeport:', 'Numéro national'
                );
            }
            $country = $nationality;

            // ── Date of birth (take first date, strip "a)" "b)" markers) ────
            $dobRaw = $get($text, 'Date de naissance:', 'Lieu de naissance:', 'Pseudonyme fiable:');
            // Keep only the first occurrence before any second date marker
            $dateOfBirth = $dobRaw ? trim(preg_split('/\bvers\b|\bou\b/iu', $dobRaw)[0]) : null;
            $dateOfBirth = $nd($dateOfBirth);

            // ── Place of birth ───────────────────────────────────────────────
            $cityRaw = $get($text,
                'Lieu de naissance:',
                'Pseudonyme fiable:', 'Nationalité:', 'Nationalite:'
            );
            // Take the part before the first comma as city, rest as country if available
            $city = $cityRaw ? trim(explode(',', $cityRaw)[0]) : null;

            // ── Passport ─────────────────────────────────────────────────────
            $passportRaw = $get($text,
                'Numéro de passeport:',
                "Numéro national d'identification:", "Numero national"
            );
            $idNumRaw = $get($text,
                "Numéro national d'identification:",
                'Adresse:'
            );

            $cleanDocNum = function (?string $raw): ?string {
                if (!$raw) return null;
                $num = preg_replace('/[,(].*$/u', '', $raw);
                $num = preg_replace('/\s+(d[ée]livr[ée]|[ée]mis|valable|expirant|issued|valid|expires?).*/iu', '', $num);
                return trim($num) ?: null;
            };

            if ($passportRaw && strtolower(trim($passportRaw)) !== 'n.d.') {
                $typeOfDocument = 'Passeport';
                $documentNumber = $cleanDocNum($passportRaw);
            } elseif ($idNumRaw) {
                $typeOfDocument = "Identification nationale";
                $documentNumber = $cleanDocNum($idNumRaw);
            } else {
                $typeOfDocument = null;
                $documentNumber = null;
            }

            // ── Address ──────────────────────────────────────────────────────
            // Truncate to 500 chars to avoid column overflow
            $adresseRaw = $get($text, 'Adresse:', "Date d'inscription:");
            $adresse = $adresseRaw ? mb_substr($adresseRaw, 0, 500) : null;

            // ── Date inscription ─────────────────────────────────────────────
            $dateAjoutRaw = $get($text, "Date d'inscription:", 'Renseignements divers:');
            // Strip "(modifications …)" suffix
            $dateAjout = $dateAjoutRaw
                ? trim(preg_split('/\(/', $dateAjoutRaw)[0])
                : null;
            $dateAjout = $nd($dateAjout) ?? now()->toDateTimeString();

            // ── Remarks ──────────────────────────────────────────────────────
            $comment1Raw = $get($text, 'Renseignements divers:');
            $comment1 = $comment1Raw ? mb_substr($comment1Raw, 0, 1000) : null;

            // ── Insert ───────────────────────────────────────────────────────
            DB::table('c_n_a_s_n_u_s')->insert([
                'dataID'         => $dataID,
                'firstName'      => $firstName,
                'secondName'     => $secondName,
                'thirdName'      => $thirdName,
                'fourthName'     => $fourthName,
                'originalName'   => $originalName,
                'aliasName'      => $aliasName,
                'nationality'    => $nationality,
                'country'        => $country,
                'city'           => $city,
                'dateOfBirth'    => $this->translateFrenchDate($dateOfBirth),
                'typeOfDocument' => $typeOfDocument,
                'documentNumber' => $documentNumber,
                'adresse'        => $adresse,
                'dateAjout'      => $this->translateFrenchDate($dateAjout),
                'comment1'       => $comment1,
            ]);

            $inserted++;
        }

        // ── Save original file ───────────────────────────────────────────────
        $storedName = strtoupper($type) . '_HTML_' . now()->format('Ymd_His') . '_' . $file->getClientOriginalName();
        $file->storeAs('imports/' . $type . '/html', $storedName, 'local');

        $this->successMessage = "{$inserted} entrée(s) importée(s) depuis le HTML ✅ - {$storedName}";
        $this->cnasnusFile = null;
    }

    private function processXml($file, string $type): void
    {
        if ($type !== 'cnasnus' && $type !== 'anrf') {
            $this->errorMessage = 'Import XML non disponible pour ce type ❌';
            return;
        }

        // If it's ANRF XML, we might need a different parser later, 
        // but for now let's at least not crash with the wrong message.
        if ($type === 'anrf') {
             // If the user has an ANRF XML format, it would go here.
        }

        $xml      = simplexml_load_file($file->getRealPath());
        $inserted = 0;

        // n.d. / blank → null
        $nd = fn(string $v): ?string => ($t = trim($v)) !== '' ? $t : null;

        foreach ($xml->INDIVIDUALS->INDIVIDUAL as $ind) {

            // ── Scalar fields ─────────────────────────────────────────────────
            $dataID       = $nd((string) ($ind->DATAID               ?? ''));
            $firstName    = $nd((string) ($ind->FIRST_NAME           ?? ''));
            $secondName   = $nd((string) ($ind->SECOND_NAME          ?? ''));
            $thirdName    = $nd((string) ($ind->THIRD_NAME           ?? ''));
            $fourthName   = $nd((string) ($ind->FOURTH_NAME          ?? ''));
            $nameOriginal = $nd((string) ($ind->NAME_ORIGINAL_SCRIPT ?? ''));
            $comment1     = $nd((string) ($ind->COMMENTS1            ?? ''));
            $nationality  = $nd((string) ($ind->NATIONALITY->VALUE   ?? ''));

            // Fallback originalName from name parts
            $originalName = $nameOriginal
                ?? trim(implode(' ', array_filter([$firstName, $secondName, $thirdName, $fourthName])));

            // ── INDIVIDUAL_ALIAS → aliasName (first "Good" quality, else first any) ──
            $aliasName = null;
            if (isset($ind->INDIVIDUAL_ALIAS)) {
                foreach ($ind->INDIVIDUAL_ALIAS as $alias) {
                    $quality = strtolower(trim((string) ($alias->QUALITY ?? '')));
                    $name    = $nd((string) ($alias->ALIAS_NAME ?? ''));
                    if (!$name) continue;
                    // Always pick first Good quality; keep first-found as fallback
                    if ($quality === 'good') { $aliasName = $name; break; }
                    $aliasName ??= $name;
                }
            }

            // ── INDIVIDUAL_DATE_OF_BIRTH → dateOfBirth ────────────────────────
            $dateOfBirth = null;
            if (isset($ind->INDIVIDUAL_DATE_OF_BIRTH)) {
                $dob = $ind->INDIVIDUAL_DATE_OF_BIRTH[0];
                $dateOfBirth = $nd((string) ($dob->DATE ?? ''))  // full date e.g. 1951-06-19
                    ?? $nd((string) ($dob->YEAR ?? ''));          // year only e.g. 1965
            }

            // ── INDIVIDUAL_PLACE_OF_BIRTH → city / country ────────────────────
            $city    = null;
            $country = null;
            if (isset($ind->INDIVIDUAL_PLACE_OF_BIRTH)) {
                $pob     = $ind->INDIVIDUAL_PLACE_OF_BIRTH[0];
                $city    = $nd((string) ($pob->CITY    ?? ''));
                $country = $nd((string) ($pob->COUNTRY ?? ''));
            }
            // Fallback country from nationality
            $country ??= $nationality;

            // ── INDIVIDUAL_ADDRESS → adresse ──────────────────────────────────
            $adresseParts = [];
            if (isset($ind->INDIVIDUAL_ADDRESS)) {
                foreach ($ind->INDIVIDUAL_ADDRESS as $addr) {
                    $parts = array_filter([
                        $nd((string) ($addr->STREET  ?? '')),
                        $nd((string) ($addr->CITY    ?? '')),
                        $nd((string) ($addr->COUNTRY ?? '')),
                        $nd((string) ($addr->NOTE    ?? '')),
                    ]);
                    if ($parts) $adresseParts[] = implode(', ', $parts);
                }
            }
            $adresse = $adresseParts ? implode(' | ', $adresseParts) : null;

            // ── INDIVIDUAL_DOCUMENT → typeOfDocument / documentNumber ─────────
            $typeOfDocument = null;
            $documentNumber = null;
            if (isset($ind->INDIVIDUAL_DOCUMENT)) {
                foreach ($ind->INDIVIDUAL_DOCUMENT as $doc) {
                    $num = $nd((string) ($doc->NUMBER ?? ''));
                    if (!$num) continue;
                    $typeOfDocument = $nd((string) ($doc->TYPE_OF_DOCUMENT ?? '')) ?? 'Document';
                    $documentNumber = $num;
                    break; // take first valid document
                }
            }

            // ── Insert ────────────────────────────────────────────────────────
            DB::table('c_n_a_s_n_u_s')->insert([
                'dataID'         => $dataID,
                'firstName'      => $firstName,
                'secondName'     => $secondName,
                'thirdName'      => $thirdName,
                'fourthName'     => $fourthName,
                'originalName'   => $originalName,
                'comment1'       => $comment1,
                'nationality'    => $nationality,
                'aliasName'      => $aliasName,
                'typeOfDocument' => $typeOfDocument,
                'documentNumber' => $documentNumber,
                'adresse'        => $adresse,
                'city'           => $city,
                'country'        => $country,
                'dateOfBirth'    => $this->translateFrenchDate($dateOfBirth),
                'dateAjout'      => $this->translateFrenchDate((string)now()) ?? now(),
            ]);

            $inserted++;
        }

        // ── Save original file ────────────────────────────────────────────────
        $storedName = strtoupper($type) . '_XML_' . now()->format('Ymd_His') . '_' . $file->getClientOriginalName();
        $file->storeAs('imports/' . $type . '/xml', $storedName, 'local');

        $this->successMessage = "{$inserted} entrée(s) importée(s) depuis le XML ✅ - {$storedName}";
        $this->cnasnusFile = null;
    }

    // ─────────────────────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────────────────────

    private function processExel($file, string $type): void
    {
        try {
            $spreadsheet = IOFactory::load($file->getRealPath());
            $insertedAnrf = 0;
            $insertedAnrfPP = 0;

            // --- 1. Process Personnes Physiques (ANRF_PP) ---
            // Assuming it's the first sheet OR we search for a sheet with a specific name
            $sheetPP = $spreadsheet->getSheet(0); // Adjust index if needed
            $rowsPP = $sheetPP->toArray();
            
            // Skip header (Assuming Row 1 is header)
            foreach (array_slice($rowsPP, 1) as $row) {
                if (empty(array_filter($row))) continue;

                // Map columns: adjust indexes based on actual Excel structure
                // Example: 0:Nom, 1:Prenom, 2:DateNaissance, 3:Profession, 4:Nationalite, 5:Identifiant
                DB::table('a_n_r_f__p_p_s')->updateOrInsert(
                    ['identifiant'    => $this->nd($row[5])],
                    [
                        'nom'            => $this->nd($row[0]),
                        'prenom'         => $this->nd($row[1]),
                        'date_naissance' => $this->translateFrenchDate($row[2]),
                        'profession'     => $this->nd($row[3]),
                        'nationalite'    => $this->nd($row[4]),
                        'updated_at'     => now(),
                    ]
                );
                $insertedAnrfPP++;
            }

            // --- 2. Process Personnes Morales (ANRF) ---
            // Assuming it's the second sheet
            if ($spreadsheet->getSheetCount() > 1) {
                $sheetMoral = $spreadsheet->getSheet(1);
                $rowsMoral = $sheetMoral->toArray();
                
                foreach (array_slice($rowsMoral, 1) as $row) {
                    if (empty(array_filter($row))) continue;

                    // Map columns: 0:Nom, 1:Identifiant, 2:Pays, 3:Activite
                    DB::table('a_n_r_f_s')->updateOrInsert(
                        ['identifiant' => $this->nd($row[1])],
                        [
                            'nom'        => $this->nd($row[0]),
                            'pays'       => $this->nd($row[2]),
                            'activite'   => $this->nd($row[3]),
                            'dateAjout'  => now(),
                        ]
                    );
                    $insertedAnrf++;
                }
            }

            // --- Save file ---
            $storedName = 'ANRF_EXCEL_' . now()->format('Ymd_His') . '_' . $file->getClientOriginalName();
            $file->storeAs('imports/anrf/excel', $storedName, 'local');

            $this->successMessage = "Importation réussie ✅ : {$insertedAnrfPP} Personnes Physiques et {$insertedAnrf} Personnes Morales importées.";
            $this->anrfFile = null;

        } catch (\Exception $e) {
            $this->errorMessage = "Erreur lors de l'importation Excel : " . $e->getMessage();
        }
    }

    private function translateFrenchDate(?string $date): ?string
    {
        if (!$date) return null;

        $frenchMonths = [
            'janvier', 'février', 'mars', 'avril', 'mai', 'juin',
            'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre',
            'janv.', 'févr.', 'avr.', 'sept.', 'oct.', 'nov.', 'déc.'
        ];

        $englishMonths = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December',
            'Jan', 'Feb', 'Apr', 'Sep', 'Oct', 'Nov', 'Dec'
        ];

        $normalizedDate = str_ireplace($frenchMonths, $englishMonths, $date);

        // Remove common French prepositions/words found in dates
        $normalizedDate = preg_replace('/\b(le|en|de|vers|ou)\b/iu', '', $normalizedDate);
        
        // Final trim and cleanup
        $normalizedDate = trim(preg_replace('/\s+/', ' ', $normalizedDate));

        try {
            // Attempt to parse to Y-m-d format for database consistency
            return \Carbon\Carbon::parse($normalizedDate)->format('Y-m-d');
        } catch (\Exception $e) {
            // If parsing fails, return the normalized string and let the DB or View handle it
            return $normalizedDate;
        }
    }

    private function clearMessages(): void
    {
        $this->successMessage = '';
        $this->errorMessage   = '';
    }

    public function render()
    {
        return view('livewire.import-files');
    }
}