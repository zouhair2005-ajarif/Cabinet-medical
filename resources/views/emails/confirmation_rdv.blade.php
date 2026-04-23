<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: auto; background: white;
                     border-radius: 10px; overflow: hidden;
                     box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #2c3e50, #3498db);
                  color: white; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .header p { margin: 5px 0 0; opacity: 0.8; }
        .body { padding: 30px; }
        .info-box { background: #f8f9fa; border-left: 4px solid #3498db;
                    padding: 15px 20px; border-radius: 5px; margin: 20px 0; }
        .info-row { display: flex; margin-bottom: 10px; }
        .info-label { font-weight: bold; color: #2c3e50; width: 130px; flex-shrink: 0; }
        .info-value { color: #555; }
        .btn { display: inline-block; background: #27ae60; color: white;
               padding: 12px 30px; border-radius: 5px; text-decoration: none;
               font-weight: bold; margin-top: 20px; }
        .warning { background: #fff3cd; border: 1px solid #ffc107;
                   padding: 12px; border-radius: 5px; margin-top: 20px;
                   color: #856404; }
        .footer { background: #2c3e50; color: #adb5bd; text-align: center;
                  padding: 20px; font-size: 12px; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>🏥 Cabinet Médical</h1>
        <p>Confirmation de Rendez-vous</p>
    </div>
    <div class="body">
        <p>Bonjour <strong>{{ $rendezvous->patient->user->name }}</strong>,</p>
        <p>Votre demande de rendez-vous a bien été enregistrée. Voici les détails :</p>

        <div class="info-box">
            <div class="info-row">
                <span class="info-label">📅 Date :</span>
                <span class="info-value">
                    {{ \Carbon\Carbon::parse($rendezvous->date_heure)->format('l d/m/Y') }}
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
            <div class="info-row">
                <span class="info-label">🏥 Spécialité :</span>
                <span class="info-value">{{ $rendezvous->medecin->specialite }}</span>
            </div>
            @if($rendezvous->motif)
            <div class="info-row">
                <span class="info-label">📋 Motif :</span>
                <span class="info-value">{{ $rendezvous->motif }}</span>
            </div>
            @endif
            <div class="info-row">
                <span class="info-label">📌 Statut :</span>
                <span class="info-value">
                    <strong style="color:#e67e22;">En attente de confirmation</strong>
                </span>
            </div>
        </div>

        <div class="warning">
            ⚠️ Votre rendez-vous est <strong>en attente</strong>.
            Vous recevrez un email dès qu'il sera confirmé par le médecin.
        </div>

        <p style="margin-top:20px; color:#7f8c8d; font-size:13px;">
            En cas de problème, contactez-nous au <strong>05 24 XX XX XX</strong>.
        </p>
    </div>
    <div class="footer">
        © Cabinet Médical — 123 Avenue Mohammed V, Marrakech<br>
        Ce message est automatique, merci de ne pas répondre.
    </div>
</div>
</body>
</html>