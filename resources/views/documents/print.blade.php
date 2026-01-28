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
            font-family: Bookman Old Style, serif;
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
                <td style="width: 80px;">
                    <img width="60" src="{{ public_path('favicon.png') }}" alt="DOST Logo">
                </td>
                <td>
                    <div>Republic of the Philippines</div>
                    <div class=""><strong>DEPARTMENT OF SCIENCE AND TECHNOLOGY</strong></div>
                    <div>Cordillera Administrative Region</div>
                </td>
            </tr>
        </table>
        <div style="position: absolute; right: 0; top: 0; text-align: left; border:#000 1px solid; padding: 1px;">
            <div><strong>FM-FAS-HR F13</strong></div>
            <div>Revision 0</div>
            <div>05-30-20</div>
        </div>
        <div style="margin-top: 10px; font-size: 14px;">
            My Learning Journal
        </div>
    </div>


    <div class="details-container">
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 6px;">
            <tr>
                <td style="width: 150px;">Name of Employee</td>
                <td style="width: 10px;">:</td>
                <td>{{ $document->last_name }}, {{ $document->first_name }} {{ $document->middle_name }}</td>
            </tr>
            <tr>
                <td style="width: 150px;">Title of L&D Program</td>
                <td style="width: 10px;">:</td>
                <td>{{ $document->title }}</td>
            </tr>
            <tr>
                <td style="width: 150px;">No. of L&D Hours</td>
                <td style="width: 10px;">:</td>
                <td>{{ $document->hours }}</td>
            </tr>
            <tr>
                <td style="width: 150px;">Date/Venue</td>
                <td style="width: 10px;">:</td>
                <td>{{ $document->date->format('F d, Y') }}/ {{ $document->venue }}</td>
            </tr>
            <tr>
                <td style="width: 150px;">Registration Fee</td>
                <td style="width: 10px;">:</td>
                <td>{{ $document->registration_fee }}</td>
            </tr>
            <tr>
                <td style="width: 150px;">Travel Expenses</td>
                <td style="width: 10px;">:</td>
                <td>{{ $document->travel_expenses }}</td>
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

</body>

</html>