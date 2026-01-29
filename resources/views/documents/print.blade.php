<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <style>
        @page {
            size: A4;
            margin: 20mm;
        }

        body {
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            font-size: 12px;
            color: #000;
        }

        h1 {
            font-size: 16px;
            margin-bottom: 10px;
        }

        .section {
            margin-bottom: 12px;
        }

        .label {
            font-weight: bold;
        }

        .details-container {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
            width: 100vw;
        }
    </style>
</head>

<body>

    <div style="align-items:center; width: 100%; text-align: center; margin-bottom: 20px; font-size: 12px;">
        <table style="margin: 0 auto; border-collapse: collapse; width: auto;">
            <tr>
                <td style="width: 60px;">
                    <img width="40" src="{{ public_path('favicon.png') }}" alt="DOST Logo">
                </td>
                <td style="width: 600px;">
                    <div>Republic of the Philippines</div>
                    <div style="font-size: 15px;"><strong>DEPARTMENT OF SCIENCE AND TECHNOLOGY</strong></div>
                    <div style="font-size: 13px;">CORDILLERA ADMINISTRATIVE REGION</div>
                </td>
            </tr>
        </table>
        <div style="position: absolute; right: 0; top: 0; text-align: left; border:#000 1px solid; padding: 1px;">
            <div><strong>FM-FAS-HR F13</strong></div>
            <div>Revision 1</div>
            <div>02-06-2026</div>
            <div>Page 2 of 2</div>
        </div>
        <div style="margin-top: 10px; font-size: 14px;">
            My Learning Journal
        </div>
    </div>


    <div class="details-container">
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 6px;">
            <tr>
                <td style="width: 135px;">Name of Employee</td>
                <td style="width: 10px;">:</td>
                <td>{{ $document->user->last_name }}, {{ $document->user->first_name }} {{ $document->user->middle_name }}</td>
            </tr>
            <tr>
                <td style="width: 135px;"><strong><i>Department/ Unit/ Office</i></strong></td>
                <td style="width: 10px;">:</td>
                <td>{{ $document->user->divisionUnit->division_units }}</td>
            </tr>
            <tr>
                <td style="width: 135px;"><strong><i>Position</i></strong></td>
                <td style="width: 10px;">:</td>
                <td>{{ $document->user->position->positions }}</td>
            </tr>
            <tr>
                <td style="width: 135px;">Title of L&D Program</td>
                <td style="width: 10px;">:</td>
                <td>{{ $document->title }}</td>
            </tr>
            <tr>
                <td style="width: 135px;"><strong><i>Date Started</i></strong></td>
                <td style="width: 10px;">:</td>
                <td>{{ $document->datestart->format('F d, Y') }}</td>
            </tr>
            <tr>
                <td style="width: 135px;"><strong><i>Date Ended</i></strong></td>
                <td style="width: 10px;">:</td>
                <td>{{ $document->dateend->format('F d, Y') }}</td>
            </tr>
            <tr>
                <td style="width: 135px;">Venue</td>
                <td style="width: 10px;">:</td>
                <td>{{ $document->venue }}</td>
            </tr>
            <tr>
                <td style="width: 135px;">No. of L&D Hours</td>
                <td style="width: 10px;">:</td>
                <td>{{ $document->hours }}</td>
            </tr>
            <tr>
                <td style="width: 135px;"><strong><i>Conducted/ sponsored by</i></strong></td>
                <td style="width: 10px;">:</td>
                <td>{{ $document->conductedby }}</td>
            </tr>
            <tr>
                <td style="width: 135px;">Registration Fee</td>
                <td style="width: 10px;">:</td>
                <td>Php {{ $document->registration_fee }}.00</td>
            </tr>
            <tr>
                <td style="width: 135px;">Travel Expenses</td>
                <td style="width: 10px;">:</td>
                <td>Php {{ $document->travel_expenses }}.00</td>
            </tr>
        </table>
    </div>


    <div class="section">
        <strong>A. I learned the following from the L&D program I attended...</strong><br>
        <strong>(Knowledge, skills, attitude, information.) Please indicate the topic/topics</strong><br>
        <span style="text-align: justify; display: block;">{{ $document->topics }}</span>
    </div>

    <div class="section">
        <strong>B. I gained the following insights and discoveries...</strong><br>
        <strong>(Understanding, perception, awareness)</strong><br>
        <span style="text-align: justify; display: block;">{{ $document->insights }}</span>
    </div>

    <div class="section">
        <strong>C. I will apply the new learnings in my current function by doing the following...</strong><br>
        <span style="text-align: justify; display: block;">{{ $document->application }}</span>
    </div>

    <div class="section">
        <strong>D. I was challenged most on...</strong><br>
        <span style="text-align: justify; display: block;">{{ $document->challenges }}</span>
    </div>

    <div class="section">
        <strong>E. I appreciated the...</strong><br>
        <strong>(Feedback: for management and services of HRD.)</strong><br>
        <span style="text-align: justify; display: block;">{{ $document->appreciation }}</span>
    </div>

    <table style="margin-top: 20px; width: 100%;">
        <tbody>
            <tr>
                <td style="width: 480px;"></td>
                <td>_______________________</td>
            </tr>
            <tr>
                <td style="width: 480px;"></td>
                <td style="padding-left: 50px; padding-top: 10px;"><strong>Signature</strong></td>
            </tr>
        </tbody>
    </table>

    <table style="margin: 0 auto; margin-top: 30px;">
        <tr>
            <td style="font-size: 10px;">All document information printed from the Quality Management Information System(QMIS) is deemed "UNCONTROLLED"</td>
        </tr>
    </table>

</body>

</html>