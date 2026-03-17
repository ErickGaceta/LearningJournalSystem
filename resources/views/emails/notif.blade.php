<!DOCTYPE html>
<html>
<body style="font-family: sans-serif; padding: 24px; color: #333;">

    <h2>Hello, {{ $body }}!</h2>

    <p>Your training has been completed and you may now submit your Learning Journal.</p>

    <table style="border-collapse: collapse; width: 100%; margin: 16px 0;">
        <tr>
            <td style="padding: 8px; font-weight: bold;">Training:</td>
            <td style="padding: 8px;">{{ $module->title }}</td>
        </tr>
        <tr>
            <td style="padding: 8px; font-weight: bold;">Venue:</td>
            <td style="padding: 8px;">{{ $module->venue }}</td>
        </tr>
        <tr>
            <td style="padding: 8px; font-weight: bold;">Duration:</td>
            <td style="padding: 8px;">
                {{ $module->datestart->format('M d, Y') }} – {{ $module->dateend->format('M d, Y') }}
            </td>
        </tr>
        <tr>
            <td style="padding: 8px; font-weight: bold;">Hours:</td>
            <td style="padding: 8px;">{{ $module->hours }} hrs</td>
        </tr>
    </table>

    <p>Please log in to the Learning Journal System to submit your report.</p>

    <p style="color: #888; font-size: 12px; margin-top: 32px;">
        DOST CAR Learning Journal System
    </p>

</body>
</html>