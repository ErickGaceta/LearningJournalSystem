{{--
    hr/monitoring/certificate.blade.php
    Renders as a standalone page (for the preview iframe)
    and as the PDF template (loaded by Dompdf / Snappy).
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Certificate of Participation</title>
    <style>
        /* ── Reset ── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Georgia', 'Times New Roman', serif;
            background: #f5f0e8;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 2rem;
        }

        /* ── Certificate card ── */
        .cert {
            position: relative;
            width: 100%;
            max-width: 900px;
            background: #fff;
            border: 2px solid #b8975a;
            padding: 60px 70px;
            text-align: center;
            box-shadow: 0 8px 40px rgba(0,0,0,.15);
        }

        /* Double-rule border inset */
        .cert::before {
            content: '';
            position: absolute;
            inset: 10px;
            border: 1px solid #d4b87a;
            pointer-events: none;
        }

        /* ── Header ── */
        .org-name {
            font-size: 11px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #6b5c3e;
            margin-bottom: 6px;
        }

        .cert-title {
            font-size: 36px;
            font-weight: 700;
            color: #2c3e50;
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .cert-subtitle {
            font-size: 13px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #7f8c8d;
            margin-bottom: 36px;
        }

        /* ── Divider ── */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 0 auto 32px;
            width: 60%;
        }
        .divider span { flex: 1; height: 1px; background: #c9a84c; }
        .divider-diamond {
            width: 8px; height: 8px;
            background: #c9a84c;
            transform: rotate(45deg);
            flex-shrink: 0;
        }

        /* ── Body text ── */
        .presented-to {
            font-size: 13px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #95a5a6;
            margin-bottom: 14px;
        }

        .employee-name {
            font-size: 42px;
            font-weight: 700;
            font-style: italic;
            color: #1a252f;
            margin-bottom: 6px;
            line-height: 1.1;
        }

        .employee-meta {
            font-size: 13px;
            color: #7f8c8d;
            margin-bottom: 28px;
        }

        .body-copy {
            font-size: 14px;
            color: #555;
            line-height: 1.8;
            max-width: 640px;
            margin: 0 auto 28px;
        }

        .training-title {
            font-size: 20px;
            font-weight: 700;
            color: #2c3e50;
            font-style: italic;
        }

        /* ── Details grid ── */
        .details {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin: 0 auto 40px;
            flex-wrap: wrap;
        }

        .detail-item {
            text-align: center;
        }

        .detail-label {
            font-size: 10px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #95a5a6;
            margin-bottom: 3px;
        }

        .detail-value {
            font-size: 13px;
            font-weight: 600;
            color: #2c3e50;
        }

        /* ── Signature row ── */
        .signatures {
            display: flex;
            justify-content: space-around;
            margin-top: 50px;
            gap: 30px;
        }

        .sig-block {
            text-align: center;
            min-width: 160px;
        }

        .sig-line {
            border-top: 1px solid #2c3e50;
            width: 180px;
            margin: 0 auto 6px;
        }

        .sig-name {
            font-size: 13px;
            font-weight: 700;
            color: #2c3e50;
        }

        .sig-role {
            font-size: 11px;
            color: #95a5a6;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* ── Footer ── */
        .cert-footer {
            position: absolute;
            bottom: 28px;
            left: 0; right: 0;
            text-align: center;
            font-size: 10px;
            color: #bdc3c7;
            letter-spacing: 1px;
        }

        /* ── Print / PDF overrides ── */
        @media print {
            body { background: #fff; padding: 0; }
            .cert { box-shadow: none; }
        }
    </style>
</head>
<body>

<div class="cert">

    {{-- Org name / header --}}
    <p class="org-name">Human Resources Department</p>
    <h1 class="cert-title">Certificate</h1>
    <p class="cert-subtitle">of Participation</p>

    <div class="divider">
        <span></span>
        <div class="divider-diamond"></div>
        <span></span>
    </div>

    {{-- Recipient --}}
    <p class="presented-to">This is to certify that</p>

    <p class="employee-name">{{ $employeeName }}</p>

    @if($employeePosition || $employeeDivision)
        <p class="employee-meta">
            {{ collect([$employeePosition, $employeeDivision])->filter()->implode(' &mdash; ') }}
        </p>
    @endif

    {{-- Body --}}
    <p class="body-copy">
        has successfully participated in the training program entitled
        <br>
        <span class="training-title">&ldquo;{{ $trainingTitle }}&rdquo;</span>
    </p>

    {{-- Details grid --}}
    <div class="details">

        @if($conductor)
        <div class="detail-item">
            <p class="detail-label">Conducted by</p>
            <p class="detail-value">{{ $conductor }}</p>
        </div>
        @endif

        @if($venue)
        <div class="detail-item">
            <p class="detail-label">Venue</p>
            <p class="detail-value">{{ $venue }}</p>
        </div>
        @endif

        <div class="detail-item">
            <p class="detail-label">Date</p>
            <p class="detail-value">
                @if($dateFrom && $dateTo && $dateFrom !== $dateTo)
                    {{ $dateFrom }} &ndash; {{ $dateTo }}
                @elseif($dateFrom)
                    {{ $dateFrom }}
                @else
                    &mdash;
                @endif
            </p>
        </div>

        @if($hours)
        <div class="detail-item">
            <p class="detail-label">Training Hours</p>
            <p class="detail-value">{{ $hours }} hrs</p>
        </div>
        @endif

        @if($units)
        <div class="detail-item">
            <p class="detail-label">Units</p>
            <p class="detail-value">{{ $units }}</p>
        </div>
        @endif

    </div>

    {{-- Signatures --}}
    <div class="signatures">
        <div class="sig-block">
            <div class="sig-line"></div>
            <p class="sig-name">{{ $conductor ?? '&nbsp;' }}</p>
            <p class="sig-role">Trainer / Conductor</p>
        </div>
        <div class="sig-block">
            <div class="sig-line"></div>
            <p class="sig-name">&nbsp;</p>
            <p class="sig-role">HR Officer</p>
        </div>
    </div>

    <p class="cert-footer">
        Training ID #{{ $trainingId }} &bull; Generated {{ now()->format('F j, Y') }}
    </p>

</div>

</body>
</html>
