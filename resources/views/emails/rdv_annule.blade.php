<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: auto; background: white;
                     border-radius: 10px; overflow: hidden;
                     box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #636e72, #2d3436);
                  color: white; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .body { padding: 30px; }
        .info-box { background: #f8f9fa; border-left: 4px solid #636e72;
                    padding: 15px 20px; border-radius: 5px; margin: 20px 0; }
        .info-row { display: flex; margin-bottom: 10px; }
        .info-label { font-weight: bold; color: #2c3e50; width: 130px; flex-shrink: 0; }
        .comment-box { background: #fff3cd; border: 1px solid #ffc107;
                       padding: 15px; border-radius: 5px; margin-top: 15px; }
        .action-box { background: #e8f4fd; border: 1px solid #3498db;
                      padding: 15px; border-radius: 5px; margin-top: 15px; }
        .footer { background: #2c3e50; color: #adb5bd; text-align: center;
                  padding: 20px; font-size: 12px; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>❌ Rendez-vous Annulé</h1>
        <p>Cabinet Médical</p>
    </div>
    <div class="body">
        <p>Bonjour <strong>{{ $rendezvous->patient->user->name }}</strong>,</p>
        <p>Votre rendez-vous a été <strong>annulé</strong>.</p>

        <div class="info-box">
            <div class="info-row">
                <span class="info-label">📅 Date :</span>
                <span class="info-value">
                    {{ \Carbon\Carbon::parse($rendezvous->date_heure)->format('d/m/Y') }}
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">🕐 Heure :</span>
                <span class="info-value">
                    {{ \Carbon\Carbon::parse($rendezvous->date_heure)->format('H:i') }}
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">👨‍⚕️ Médecin :</span>
                <span class="info-value">{{ $rendezvous->medecin->user->name }}</span>
            </div>
        </div>

        @if($commentaire)
        <div class="comment-box">
            <strong>💬 Motif de l'annulation :</strong><br>
            {{ $commentaire }}
        </div>
        @endif

        <div class="action-box">
            <strong>💡 Que faire ?</strong><br>
            Vous pouvez prendre un nouveau rendez-vous en ligne
            ou contacter le cabinet au <strong>05 24 XX XX XX</strong>.
        </div>
    </div>
    <div class="footer">© Cabinet Médical — Ce message est automatique.</div>
</div>
</body>
</html>