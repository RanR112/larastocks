<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Forecast Upload</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f0f2f5;">
    <div style="max-width: 600px; margin: 20px auto; background-color: #f8f9fa;">
        <!-- Header dengan gradient -->
        <div style="background: linear-gradient(135deg, #0061f2 0%, #6900f2 100%); padding: 40px 20px; text-align: center; border-radius: 8px 8px 0 0;">
            <h1 style="color: #ffffff; margin: 0; font-size: 24px; font-weight: 600;">
                Control Material System
            </h1>
        </div>

        <div style="background-color: #ffffff; padding: 30px; border-radius: 0 0 8px 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <div style="text-align: center; margin-bottom: 30px;">
                <div style="background-color: #e8eaf6; border-radius: 50%; width: 80px; height: 80px; margin: 0 auto; display: flex; align-items: center; justify-content: center;">
                    <span style="font-size: 40px;">📊</span>
                </div>
                
                <h2 style="color: #2d3748; margin: 20px 0; font-size: 22px; font-weight: 600;">
                    Notifikasi Upload Forecast Baru
                </h2>
            </div>
            
            <p style="margin-bottom: 20px; color: #4a5568; font-size: 16px;">Halo,</p>
            
            <p style="margin-bottom: 25px; color: #4a5568; font-size: 16px;">
                Forecast baru telah diupload untuk supplier <strong style="color: #2d3748; background-color: #e8eaf6; padding: 2px 8px; border-radius: 4px;">{{ $supplier->name }}</strong>
            </p>
            
            <div style="background-color: #f8fafc; padding: 20px; border-radius: 8px; border-left: 4px solid #0061f2; margin: 25px 0;">
                <h3 style="color: #2d3748; margin-top: 0; font-size: 18px; font-weight: 600;">Detail Informasi:</h3>
                <div style="margin-left: 10px;">
                    <p style="margin: 10px 0; color: #4a5568;">
                        <span style="display: inline-block; width: 24px; text-align: center;">📄</span>
                        <strong>Nama File:</strong> {{ $fileName }}
                    </p>
                    <p style="margin: 10px 0; color: #4a5568;">
                        <span style="display: inline-block; width: 24px; text-align: center;">👤</span>
                        <strong>Diupload Oleh:</strong> {{ $uploadedBy->name }}
                    </p>
                    <p style="margin: 10px 0; color: #4a5568;">
                        <span style="display: inline-block; width: 24px; text-align: center;">🕒</span>
                        <strong>Waktu Upload:</strong> {{ now()->format('d M Y H:i') }}
                    </p>
                </div>
            </div>
            
            <div style="background-color: #ebf8ff; padding: 15px; border-radius: 8px; margin: 25px 0; border: 1px dashed #0061f2;">
                <p style="margin: 0; color: #2d4a8c; text-align: center;">
                    <span style="font-size: 20px;">🔔</span><br>
                    <strong>Anda dapat melihat forecast ini dengan login ke sistem.</strong>
                </p>
            </div>
            
            <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #e2e8f0; text-align: center;">
                <p style="margin: 0; color: #4a5568;">Salam hormat,</p>
                <p style="margin: 5px 0; color: #2d3748; font-weight: 600;">{{ config('app.name') }}</p>
            </div>
        </div>

        <!-- Footer -->
        <div style="text-align: center; padding: 20px; color: #718096; font-size: 14px;">
            <p style="margin: 0;">Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
            <p style="margin: 5px 0;">© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html> 