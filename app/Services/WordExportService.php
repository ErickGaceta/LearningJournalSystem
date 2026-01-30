<?php

namespace App\Services;

use App\Models\Document;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\Jc;
use Illuminate\Support\Facades\Log;
use ZipArchive;

class WordExportService
{
    /**
     * Generate a Word document from a Document model
     * 
     * @param Document $document
     * @return string Path to the generated file
     * @throws \Exception
     */
    public function generateDocument(Document $document): string
    {
        // Create new document
        $phpWord = new PhpWord();
        $phpWord->setDefaultFontName('Arial');
        $phpWord->setDefaultFontSize(12);

        // Add section with margins
        $section = $phpWord->addSection([
            'marginLeft' => 1440,    // 1 inch in twips
            'marginRight' => 1440,
            'marginTop' => 1440,
            'marginBottom' => 1440,
        ]);
        $this->addHeader($section);
        $this->addFooter($section);

        // Content LAST
        $this->addContent($section, $document);

        // Save and verify
        return $this->saveAndVerify($phpWord);
    }

    /**
     * Add document header
     */
    private function addHeader($section): void
    {
        $header = $section->addHeader();

        $header->addText(
            'Republic of the Philippines',
            ['size' => 12],
            ['alignment' => Jc::CENTER]
        );

        $header->addText(
            'DEPARTMENT OF SCIENCE AND TECHNOLOGY',
            ['size' => 15, 'bold' => true],
            ['alignment' => Jc::CENTER]
        );

        $header->addText(
            'CORDILLERA ADMINISTRATIVE REGION',
            ['size' => 13],
            ['alignment' => Jc::CENTER]
        );

        $header->addText(''); // Spacer

        $header->addText(
            'My Learning Journal',
            ['size' => 14, 'bold' => true],
            ['alignment' => Jc::CENTER]
        );
    }

    /**
     * Add document footer
     */
    private function addFooter($section): void
    {
        $footer = $section->addFooter();

        $footer->addPreserveText(
            'Page {PAGE} of {NUMPAGES}',
            ['size' => 10],
            ['alignment' => Jc::CENTER]
        );
    }


    /**
     * Add main document content
     */
    private function addContent($section, Document $document): void
    {
        $section->addText('');

        // Employee Information Section
        $section->addText('EMPLOYEE INFORMATION', ['bold' => true, 'size' => 13]);
        $section->addText('');

        // Employee details
        $section->addText('Name of Employee: ' . $this->cleanText($document->fullname ?: 'N/A'));
        $section->addText('Title of L&D Program: ' . $this->cleanText($document->title ?: 'N/A'));

        // Dates
        $dateStart = $this->formatDate($document->datestart);
        $section->addText('Date Started: ' . $dateStart);

        $dateEnd = $this->formatDate($document->dateend);
        $section->addText('Date Ended: ' . $dateEnd);

        // Other details
        $section->addText('Venue: ' . $this->cleanText($document->venue ?: 'N/A'));
        $section->addText('No. of L&D Hours: ' . ($document->hours ?: '0'));
        $section->addText('Conducted by: ' . $this->cleanText($document->conductedby ?: 'N/A'));
        $section->addText('Registration Fee: Php ' . number_format($document->registration_fee ?? 0, 2));
        $section->addText('Travel Expenses: Php ' . number_format($document->travel_expenses ?? 0, 2));

        $section->addText('');
        $section->addText('');

        // Learning sections
        $this->addLearningSection($section, 'A', 'I learned the following from the L&D program I attended...', $document->topics);
        $this->addLearningSection($section, 'B', 'I gained the following insights and discoveries...', $document->insights);
        $this->addLearningSection($section, 'C', 'I will apply the new learnings in my current function by doing the following...', $document->application);
        $this->addLearningSection($section, 'D', 'I was challenged most on...', $document->challenges);
        $this->addLearningSection($section, 'E', 'I appreciated the...', $document->appreciation);

        // Signature
        $section->addText('');
        $section->addText('');
        $section->addText('________________________', [], ['alignment' => Jc::RIGHT]);
        $section->addText('Signature', ['bold' => true, 'size' => 11], ['alignment' => Jc::RIGHT]);
    }

    /**
     * Add a learning section
     */
    private function addLearningSection($section, string $letter, string $title, ?string $content): void
    {
        $section->addText("{$letter}. {$title}", ['bold' => true, 'size' => 12]);

        $cleanedContent = $this->cleanText($content);

        if ($cleanedContent !== '') {
            foreach (preg_split("/\R/u", $cleanedContent) as $line) {
                $section->addText(trim($line));
            }
        }

        $section->addText('');
    }

    /**
     * Clean text to prevent XML errors
     */
    private function cleanText(?string $text): string
    {
        if (empty($text)) {
            return '';
        }

        $text = trim($text);

        // Remove control characters except newlines and tabs
        $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $text);

        // Ensure valid UTF-8
        if (!mb_check_encoding($text, 'UTF-8')) {
            $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
        }

        return $text;
    }

    /**
     * Format date safely
     */
    private function formatDate($date): string
    {
        if (empty($date)) {
            return 'N/A';
        }

        try {
            return $date->format('F d, Y');
        } catch (\Exception $e) {
            Log::warning('Date format error: ' . $e->getMessage());
            return 'N/A';
        }
    }

    /**
     * Save document and verify it's valid
     */
    private function saveAndVerify(PhpWord $phpWord): string
    {
        $fileName = 'Learning_Journal_' . date('Y-m-d_His') . '.docx';
        $tempDir = storage_path('app/temp');
        $tempFile = $tempDir . '/' . $fileName;

        // Create directory if needed
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        // Save the document
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempFile);

        // Verify the file was created
        if (!file_exists($tempFile)) {
            throw new \Exception('File was not created at: ' . $tempFile);
        }

        // Verify it's a valid ZIP (DOCX is a ZIP file)
        $zip = new ZipArchive();
        $zipResult = $zip->open($tempFile, ZipArchive::CHECKCONS);
        if ($zipResult !== true) {
            throw new \Exception('Generated DOCX is not a valid ZIP archive. Error code: ' . $zipResult);
        }
        $zip->close();

        Log::info('Word document created successfully: ' . $fileName);

        return $tempFile;
    }
}
