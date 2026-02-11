<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        h1, h2 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #000;
            padding: 4px 6px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .section-title {
            margin-top: 20px;
            font-weight: bold;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <h1>{{ $moduleTitle }}</h1>
    <h2>{{ $employeeName }}</h2>
    <p>Date: {{ now()->format('M d, Y') }}</p>

    {{-- Assignments Table --}}
    @if($assignments->count())
        <div class="section-title">Assignments</div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Module ID</th>
                </tr>
            </thead>
            <tbody>
                @foreach($assignments as $index => $assignment)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $assignment->training_module }}</td>
                        <td>{{ ucfirst($assignment->status) }}</td>
                        <td>{{ $assignment->module_id }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- Documents Table --}}
    @if($documents->count())
        <div class="section-title">Documents</div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Document Name</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($documents as $index => $doc)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $doc->title ?? 'N/A' }}</td>
                        <td>{{ $doc->created_at->format('M d, Y') }}</td>
                        <td>{{ $doc->updated_at->format('M d, Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</body>
</html>
