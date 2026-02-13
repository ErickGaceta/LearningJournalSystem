<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 11px;
        color: #000;
    }

    .form-title {
        font-size: 12px;
        text-align: center;
        line-height: 3;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        border: 1px solid #000;
        padding: 5px 7px;
        vertical-align: top;
    }

    .label-cell {
        background-color: #f2f2f2;
        font-weight: bold;
        width: 35%;
    }

    .section-header {
        background-color: #d9d9d9;
        font-weight: bold;
        font-size: 11px;
        padding: 5px 7px;
        border: 1px solid #000;
    }

    .question-label {
        font-weight: bold;
        font-size: 10px;
        margin-bottom: 3px;
    }

    .answer-cell {
        min-height: 50px;
    }

    .spacer {
        height: 10px;
    }

    .signature-table td {
        border: none;
        padding: 2px 7px;
    }

    .underline {
        border-bottom: 1px solid #000;
        display: inline-block;
        min-width: 200px;
    }

    .text {
        margin: 0;
        padding: 0;
        line-height: 0.6;
        display: block;
    }
    .head-body {
        margin: 0;
        padding: 0;
        line-height: 0.6;
        display: block;
    }
</style>

<div class="form-title">My Learning Journal</div>

<div class="text">Name of Employee <strong><i>(Surname, Given Name Middle Initial)</i></strong>: {{ $document->user->last_name . ', ' . $document->user->first_name . ' ' . ($document->user->middle_name ? strtoupper(substr($document->user->middle_name, 0, 1)) . '.' : '') }}</div>
<div class="text"><strong><i>Department / Unit / Office:</i></strong> {{ $document->user->divisionUnit->division_units }}</div>
<div class="text"><strong><i>Position:</i></strong> {{ $document->user->position->positions  }}</div>
<div class="text">Title of L&D Program Attended: {{ $document->module->title }}</div>
<div class="text"><strong><i>Date started:</i></strong> {{ $document->module->datestart }}</div>
<div class="text"><strong><i>Date ended:</i></strong> {{ $document->module->dateend }}</div>
<div class="text">Venue: {{ $document->module->venue }}</div>
<div class="text">No. of L&D Hours: {{ $document->module->hours }}</div>
<div class="text"><strong><i>Conducted/sponsored by:</i></strong> {{ $document->module->conductedby }}</div>
<div class="text">Registration Fee: ₱ {{ $document->module->registration_fee }}.00</div>
<div class="text">Travel Expenses: ₱ {{ $document->travel_expenses }}.00</div>

<div class="spacer"></div>

<div class="head-body"><strong>A. I learned the following from the L&D program I attended…</strong></div>
<div class="head-body"><strong>(knowledge, skills, attitude, information) Please indicate topic/topics.</strong></div>
<div class="head-body" style="line-height: 1;">{{ $document->topics }}</div>

<div class="head-body"><strong>B. I gained the following insights and discoveries…</strong></div>
<div class="head-body"><strong>(understanding, perception, awareness)</strong></div>
<div class="head-body" style="line-height: 1;">{{ $document->insights }}</div>

<div class="head-body"><strong>C. I will apply the new learnings in my current function by doing the following…</strong></div>
<div class="head-body" style="line-height: 1;">{{ $document->application }}</div>

<div class="head-body"><strong>D. I was challenged most on…</strong></div>
<div class="head-body" style="line-height: 1;">{{ $document->challenges }}</div>

<div class="head-body"><strong>E. I appreciated the…</strong></div>
<div class="head-body"><strong>(Feedback: for management and services of HRD)</strong></div>
<div class="head-body" style="line-height: 1;">{{ $document->appreciation }}</div>

<div class="spacer"></div>

<div class="signature" style="line-height: 1; margin-right: 0;">
    <img src="{{ public_path('documents/signature.png') }}" alt="">
</div>