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
        font-size: 13px;
        font-weight: bold;
        text-align: center;
        margin-bottom: 4px;
    }
    .form-subtitle {
        font-size: 10px;
        text-align: center;
        margin-bottom: 12px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
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
</style>

<div class="form-title">LEARNING JOURNAL</div>
<div class="form-subtitle">Department of Science and Technology - Cordillera Administrative Region</div>

<table>
    <tr>
        <td class="label-cell">Name of Employee</td>
        <td colspan="3">{{ $document->fullname }}</td>
    </tr>
    <tr>
        <td class="label-cell">Position</td>
        <td>{{ $user->position->positions ?? 'N/A' }}</td>
        <td class="label-cell">Division/Unit/Office</td>
        <td>{{ $user->divisionUnit->division_units ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td class="label-cell">Title of L&D Program</td>
        <td colspan="3">{{ $module?->title ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td class="label-cell">Venue</td>
        <td colspan="3">{{ $module?->venue ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td class="label-cell">Conducted/Sponsored By</td>
        <td colspan="3">{{ $module?->conductedby ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td class="label-cell">Inclusive Dates</td>
        <td>
            {{ $module?->datestart?->format('M d, Y') ?? 'N/A' }}
            –
            {{ $module?->dateend?->format('M d, Y') ?? 'N/A' }}
        </td>
        <td class="label-cell">Number of Hours</td>
        <td>{{ $module?->hours ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td class="label-cell">Registration Fee</td>
        <td>{{ $module?->registration_fee ?? 'N/A' }}</td>
        <td class="label-cell">Travel Expenses</td>
        <td>{{ $document->travel_expenses ?? 'N/A' }}</td>
    </tr>
</table>

<div class="spacer"></div>

<table>
    <tr>
        <td class="section-header">LEARNING JOURNAL ENTRIES</td>
    </tr>
    <tr>
        <td>
            <div class="question-label">A. I learned the following from the L&D program I attended...</div>
            <div class="answer-cell">{{ $document->topics }}</div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="question-label">B. I gained the following insights and discoveries...</div>
            <div class="answer-cell">{{ $document->insights }}</div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="question-label">C. I will apply the new learnings in my current function by doing the following...</div>
            <div class="answer-cell">{{ $document->application }}</div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="question-label">D. I was challenged most on...</div>
            <div class="answer-cell">{{ $document->challenges }}</div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="question-label">E. I appreciated the... (Feedback for management and services of HRD)</div>
            <div class="answer-cell">{{ $document->appreciation }}</div>
        </td>
    </tr>
</table>

<div class="spacer"></div>

<table class="signature-table">
    <tr>
        <td style="width:50%;">
            Submitted by:<br><br>
            <span class="underline">{{ $document->fullname }}</span><br>
            <span style="font-size:10px;">Signature over Printed Name / Date</span>
        </td>
        <td style="width:50%;">
            Noted by:<br><br>
            <span class="underline">&nbsp;</span><br>
            <span style="font-size:10px;">Supervisor / Date</span>
        </td>
    </tr>
</table>

<div class="spacer"></div>
<div style="font-size:9px; text-align:right;">Date Printed: {{ $date }}</div>