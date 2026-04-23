<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: auto; background: white;
                     border-radius: 10px; overflow: hidden;
                     box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #f39c12, #e67e22);
                  color: white; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .body { padding: 30px; }
        .info-box { background: #fffbf0; border-left: 4px solid #f39c12;
                    padding: 15px 20px; border-radius: 5px; margin: 20px 0; }
        .info-row { display: flex; margin-bottom: 10px; }
        .info-label { font-weight: bold; color: #2c3e50; width: 130px; flex-shrink: 0; }
        .reminder { background: #ffeeba; border: 1px solid #f39c12;
                    padding: 12px; border-radius: 5px; margin-top: 20px; color: #856404; }
        .footer { background: #2c3e50; color: #adb5bd; text-align: center;
                  padding: 20px; font-size: 12px; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>⏰ Rappel — RDV Demain !</h1>
        <p>Cabinet Médical</p>
    </div>
    <div class="body">
        <p>Bonjour <strong>{{ $rendezvous->patient->user->name }}</strong>,</p>
        <p>
            Ceci est un rappel automatique. Vous avez un rendez-vous
            <strong>demain</strong> :
        </p>

        <div class="info-box">
            <div class="info-row">
                <span class="info-label">📅 Date :</span>
                <span class="info-value">
                    <strong>
                        {{ \Carbon\Carbon::parse($rendezvous->date_heure)->format('d/m/Y') }}
                    </strong>
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">🕐 Heure :</span>
                <span class="info-value">
                    <strong>
                        {{ \Carbon\Carbon::parse($rendezvous->date_heure)->format('H:i') }}
                    </strong>
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
        </div>

        <div class="reminder">
            <strong>📍 Adresse :</strong> 123 Avenue Mohammed V, Marrakech<br>
            <strong>⏰ Arrivez 10 minutes avant votre RDV.</strong><br>
            <strong>📞 Annulation :</strong> 05 24 XX XX XX
        </div>
    </div>
    <div class="footer">
        © Cabinet Médical — Rappel automatique.
    </div>
</div>
</body>
</html>