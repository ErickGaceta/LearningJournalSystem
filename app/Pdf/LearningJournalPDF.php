<?php

namespace App\Pdf;

use TCPDF; // the real underlying TCPDF class

class LearningJournalPDF extends TCPDF
{
    protected string $headerImagePath = '';
    protected string $footerImagePath = '';

    public function setHeaderImagePath(string $path): void
    {
        $this->headerImagePath = $path;
    }

    public function setFooterImagePath(string $path): void
    {
        $this->footerImagePath = $path;
    }

    public function Header(): void
    {
        if ($this->headerImagePath && file_exists($this->headerImagePath)) {
            $this->Image(
                $this->headerImagePath,
                0, 0,    // x, y
                210,     // width — full A4
                0,       // height — auto
                'PNG',   // type
                '',      // link
                'T',     // align
                false,   // resize
                300,     // dpi
                '',      // palign
                false,   // ismask
                false,   // imgmask
                0        // border
            );
        }
    }

    public function Footer(): void
    {
        if ($this->footerImagePath && file_exists($this->footerImagePath)) {
            $this->Image(
                $this->footerImagePath,
                0,
                $this->getPageHeight() - 25, // tune this to your footer height in mm
                210,
                0,
                'PNG',
                '', 'T', false, 300, '', false, false, 0
            );
        }
    }
}