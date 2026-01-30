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

        // CRITICAL FIX: Remove formatting between placeholder characters
        // This merges {{REGISTRATION_FEE}} even if it's split across multiple runs
        $xml = $this->cleanPlaceholders($xml);

        $user = $doc->user;

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
            '{{REGISTRATION_FEE}}' => $doc->registration_fee,
            '{{TRAVEL_FEE}}'      => $doc->travel_expenses,
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

    /**
     * Clean XML to merge fragmented placeholders
     * Word often splits {{PLACEHOLDER}} across multiple <w:t> tags
     */
    private function cleanPlaceholders(string $xml): string
    {
        // Pattern: {{TEXT</w:t></w:r><w:r><w:t>MORE}}
        // Replace with: {{TEXTMORE}}
        // This removes any XML tags between {{ and }}
        
        // Repeat the process multiple times to catch multi-level splits
        for ($i = 0; $i < 10; $i++) {
            $before = $xml;
            
            // Remove XML tags between {{ and }}
            $xml = preg_replace_callback(
                '/(\{\{[^}]*?)<\/w:t>.*?<w:t[^>]*>([^}]*?\}\})/s',
                function($matches) {
                    return $matches[1] . $matches[2];
                },
                $xml
            );
            
            // If nothing changed, we're done
            if ($before === $xml) break;
        }
        
        return $xml;
    }

    /**
     * Replace placeholders with values
     */
    private function replace(string $xml, array $vars): string
    {
        foreach ($vars as $key => $value) {
            // Escape special XML characters
            $escapedValue = htmlspecialchars($value ?? '', ENT_XML1 | ENT_QUOTES, 'UTF-8');
            $xml = str_replace($key, $escapedValue, $xml);
        }
        return $xml;
    }
}