<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learning Journal Reminder</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f6f8; font-family: Georgia, 'Times New Roman', serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f6f8; padding: 40px 16px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="max-width: 600px; width: 100%; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.08);">

                    {{-- Header --}}
                    <tr>
                        <td style="background-color: #47c4d3; padding: 32px 40px; text-align: center;">
                            <p style="margin: 0 0 4px 0; font-family: Arial, sans-serif; font-size: 11px; letter-spacing: 3px; text-transform: uppercase; color: #000;">
                                Department of Science and Technology
                            </p>
                            <h1 style="margin: 0; font-family: Georgia, serif; font-size: 22px; font-weight: normal; color: #ffffff; letter-spacing: 0.5px;">
                                Cordillera Administrative Region
                            </h1>
                            <div style="margin: 16px auto 0; width: 40px; height: 2px; background-color: #f0a500;"></div>
                        </td>
                    </tr>

                    {{-- Accent Bar --}}
                    <tr>
                        <td style="background-color: #f0a500; height: 4px; font-size: 0; line-height: 0;">&nbsp;</td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding: 40px 40px 32px;">

                            <p style="margin: 0 0 8px; font-family: Arial, sans-serif; font-size: 13px; color: #888888; text-transform: uppercase; letter-spacing: 1.5px;">
                                Learning Journal Reminder
                            </p>
                            <h2 style="margin: 0 0 24px; font-family: Georgia, serif; font-size: 26px; font-weight: normal; color: #47c4d3; line-height: 1.3;">
                                Hello, {{ $body }}.
                            </h2>

                            <p style="margin: 0 0 24px; font-family: Arial, sans-serif; font-size: 15px; color: #444444; line-height: 1.7;">
                                Your training has been completed and you are now required to submit your
                                <strong style="color: #47c4d3;">Learning Journal</strong> through the system.
                                Please complete your submission at your earliest convenience.
                            </p>

                            {{-- Training Details Card --}}
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; margin-bottom: 28px;">
                                <tr>
                                    <td style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0;">
                                        <p style="margin: 0; font-family: Arial, sans-serif; font-size: 11px; text-transform: uppercase; letter-spacing: 1.5px; color: #888888;">Training Program</p>
                                        <p style="margin: 4px 0 0; font-family: Georgia, serif; font-size: 16px; color: #47c4d3; font-weight: bold;">{{ $module->title }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 0;">
                                        <table width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="padding: 14px 20px; border-right: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0; width: 50%;">
                                                    <p style="margin: 0; font-family: Arial, sans-serif; font-size: 11px; text-transform: uppercase; letter-spacing: 1.5px; color: #888888;">Venue</p>
                                                    <p style="margin: 4px 0 0; font-family: Arial, sans-serif; font-size: 14px; color: #333333;">{{ $module->venue }}</p>
                                                </td>
                                                <td style="padding: 14px 20px; border-bottom: 1px solid #e2e8f0; width: 50%;">
                                                    <p style="margin: 0; font-family: Arial, sans-serif; font-size: 11px; text-transform: uppercase; letter-spacing: 1.5px; color: #888888;">Training Hours</p>
                                                    <p style="margin: 4px 0 0; font-family: Arial, sans-serif; font-size: 14px; color: #333333;">{{ $module->hours }} hrs</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="padding: 14px 20px;">
                                                    <p style="margin: 0; font-family: Arial, sans-serif; font-size: 11px; text-transform: uppercase; letter-spacing: 1.5px; color: #888888;">Duration</p>
                                                    <p style="margin: 4px 0 0; font-family: Arial, sans-serif; font-size: 14px; color: #333333;">
                                                        {{ $module->datestart->format('F d, Y') }} &ndash; {{ $module->dateend->format('F d, Y') }}
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 0 0 28px; font-family: Arial, sans-serif; font-size: 15px; color: #444444; line-height: 1.7;">
                                Please log in to the <strong style="color: #47c4d3;">Learning Journal System</strong> to complete and submit your report.
                            </p>

                            {{-- CTA Button --}}
                            <table cellpadding="0" cellspacing="0" style="margin-bottom: 8px;">
                                <tr>
                                    <td style="background-color: #47c4d3; border-radius: 4px;">
                                        <a href="#" style="display: inline-block; padding: 12px 28px; font-family: Arial, sans-serif; font-size: 14px; font-weight: bold; color: #ffffff; text-decoration: none; letter-spacing: 0.5px;">
                                            Submit Learning Journal →
                                        </a>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 24px 40px; text-align: center;">
                            <p style="margin: 0 0 4px; font-family: Arial, sans-serif; font-size: 12px; color: #47c4d3; font-weight: bold;">
                                DOST CAR Learning Journal System
                            </p>
                            <p style="margin: 0; font-family: Arial, sans-serif; font-size: 11px; color: #aaaaaa;">
                                This is an automated reminder. Please do not reply to this email.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>