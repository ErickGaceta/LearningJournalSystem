<?php

namespace App\Services;

use App\Models\Document;
use ZipArchive;

class WordXmlTemplateService
{
    public function generate(Document $doc): string
    {
        $template = storage_path('app/templates/learning_journal_template.docx');

        if (!file_exists($template)) {
            throw new \Exception("Template file does not exist at $template");
        }

        $output = storage_path('app/temp/Learning_Journal_' . time() . '.docx');
        copy($template, $output);

        $zip = new ZipArchive;
        if ($zip->open($output) !== true) {
            throw new \Exception("Could not open temporary docx file for writing.");
        }

        $xml = $zip->getFromName('word/document.xml');

        $user = $doc->user;

        $registrationFee = preg_replace('/[^0-9.]/', '', $doc->registration_fee);
        $travelFee       = preg_replace('/[^0-9.]/', '', $doc->travel_expenses);

        $replacements = [
            '{{LAST_NAME}}'       => $user->last_name ?? '',
            '{{FIRST_NAME}}'      => $user->first_name ?? '',
            '{{MIDDLE_NAME}}'     => $user->middle_name ?? '',
            '{{DIVISION_UNIT}}'   => $user->divisionUnit->division_units ?? '',
            '{{POSITION}}'        => $user->position->positions ?? '',
            '{{TITLE}}'           => $doc->title,
            '{{DATESTART}}'       => optional($doc->datestart)->format('F d, Y'),
            '{{DATEEND}}'         => optional($doc->dateend)->format('F d, Y'),
            '{{VENUE}}'           => $doc->venue,
            '{{HOURS}}'           => $doc->hours,
            '{{CONDUCTEDBY}}'     => $doc->conductedby,
            '{{REGISTRATION_FEE}}' => number_format((float)$registrationFee, 2),
            '{{TRAVEL_FEE}}'      => number_format((float)$travelFee, 2),
            '{{TOPICS}}'          => $doc->topics,
            '{{GAINED}}'          => $doc->insights,
            '{{APPLY}}'           => $doc->application,
            '{{CHALLENGE}}'       => $doc->challenges,
            '{{FEEDBACK}}'        => $doc->appreciation,
        ];

        $xml = $this->replace($xml, $replacements);

        $zip->addFromString('word/document.xml', $xml);
        $zip->close();

        return $output;
    }

    private function replace(string $xml, array $vars): string
    {
        foreach ($vars as $key => $value) {
            $xml = str_replace($key, htmlspecialchars($value ?? '', ENT_XML1), $xml);
        }
        return $xml;
    }
}
