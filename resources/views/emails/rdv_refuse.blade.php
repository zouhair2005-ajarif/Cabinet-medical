<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: auto; background: white;
                     border-radius: 10px; overflow: hidden;
                     box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #c0392b, #e74c3c);
                  color: white; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .body { padding: 30px; }
        .info-box { background: #fff5f5; border-left: 4px solid #e74c3c;
                    padding: 15px 20px; border-radius: 5px; margin: 20px 0; }
        .info-row { display: flex; margin-bottom: 10px; }
        .info-label { font-weight: bold; color: #2c3e50; width: 130px; flex-shrink: 0; }
        .action-box { background: #e8f4fd; border: 1px solid #3498db;
                      padding: 15px; border-radius: 5px; margin-top: 20px; }
        .footer { background: #2c3e50; color: #adb5bd; text-align: center;
                  padding: 20px; font-size: 12px; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>❌ Rendez-vous Non Disponible</h1>
        <p>Cabinet Médical</p>
    </div>
    <div class="body">
        <p>Bonjour <strong>{{ $rendezvous->patient->user->name }}</strong>,</p>
        <p>Nous sommes désolés, votre rendez-vous n'a pas pu être confirmé.</p>

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

        <div class="action-box">
            <strong>💡 Que faire ?</strong><br>
            Vous pouvez reprendre un nouveau rendez-vous en choisissant
            un autre créneau ou un autre médecin disponible.<br><br>
            <strong>📞 Contactez-nous :</strong> 05 24 XX XX XX
        </div>
    </div>
    <div class="footer">
        © Cabinet Médical — Ce message est automatique.
    </div>
</div>
</body>
</html>