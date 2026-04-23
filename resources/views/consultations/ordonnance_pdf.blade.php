<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 13px;
            color: #2c3e50;
            margin: 0;
            padding: 20px;
        }
        .header-table { width: 100%; margin-bottom: 20px; }
        .cabinet-name { font-size: 20px; font-weight: bold; color: #2980b9; }
        .cabinet-info { font-size: 11px; color: #7f8c8d; margin-top: 5px; }
        .titre {
            text-align: center; font-size: 16px; font-weight: bold;
            text-transform: uppercase; letter-spacing: 2px;
            border: 2px solid #2980b9; padding: 8px;
            margin-bottom: 20px; color: #2c3e50;
        }
        .info-box {
            background: #ecf0f1; padding: 12px;
            margin-bottom: 20px; font-size: 12px;
        }
        .info-box table { width: 100%; }
        .info-box td { padding: 4px 8px; }
        .label { font-weight: bold; color: #2980b9; width: 140px; }
        .section-title {
            font-size: 13px; font-weight: bold; color: #2980b9;
            border-bottom: 1px solid #bdc3c7;
            padding-bottom: 4px; margin: 15px 0 8px 0;
        }
        .medicament {
            border-left: 4px solid #2980b9;
            padding: 8px 12px; margin-bottom: 8px;
            background: #f8f9fa;
        }
        .instructions {
            padding: 10px; border: 1px solid #ecf0f1;
            background: #fefefe; white-space: pre-line;
        }
        .footer-signature { margin-top: 60px; }
        .sig-box {
            border-top: 2px solid #2c3e50; padding-top: 8px;
            width: 200px; text-align: center;
            float: right;
        }
        .footer {
            position: fixed; bottom: 0; left: 0; right: 0;
            text-align: center; font-size: 10px; color: #95a5a6;
            border-top: 1px solid #ecf0f1; padding: 5px;
        }
    </style>
</head>
<body>

    <!-- En-tête -->
    <table class="header-table">
        <tr>
            <td>
                <div class="cabinet-name">🏥 Cabinet Médical</div>
                <div class="cabinet-info">
                    123 Avenue Mohammed V, Marrakech<br>
                    📞 05 24 XX XX XX | ✉ cabinet@medical.ma
                </div>
            </td>
            <td style="text-align:right; font-size:12px; color:#7f8c8d;">
                <strong>N° :</strong>
                ORD-{{ str_pad($ordonnance->id, 4, '0', STR_PAD_LEFT) }}<br>
                <strong>Date :</strong>
                {{ \Carbon\Carbon::parse($ordonnance->date)->format('d/m/Y') }}
            </td>
        </tr>
    </table>

    <hr style="border-color:#2980b9; border-width:2px; margin-bottom:15px;">

    <!-- Titre -->
    <div class="titre">Ordonnance Médicale</div>

    <!-- Infos -->
    <div class="info-box">
        <table>
            <tr>
                <td class="label">Médecin :</td>
                <td>
                    <strong>
                        {{ $ordonnance->consultation->rendezvous->medecin->user->name }}
                    </strong>
                </td>
                <td class="label">Patient :</td>
                <td>
                    <strong>
                        {{ $ordonnance->consultation->rendezvous->patient->user->name }}
                    </strong>
                </td>
            </tr>
            <tr>
                <td class="label">Spécialité :</td>
                <td>
                    {{ $ordonnance->consultation->rendezvous->medecin->specialite }}
                </td>
                <td class="label">Âge :</td>
                <td>
                    @php
                        $dob = $ordonnance->consultation->rendezvous
                               ->patient->date_naissance;
                    @endphp
                    {{ $dob
                        ? \Carbon\Carbon::parse($dob)->age . ' ans'
                        : 'Non renseigné' }}
                </td>
            </tr>
            <tr>
                <td class="label">Date consultation :</td>
                <td>
                    {{ \Carbon\Carbon::parse($ordonnance->consultation->date)
                        ->format('d/m/Y') }}
                </td>
                <td class="label">Téléphone :</td>
                <td>
                    {{ $ordonnance->consultation->rendezvous->patient->telephone ?? '—' }}
                </td>
            </tr>
        </table>
    </div>

    <!-- Médicaments -->
    <div class="section-title">💊 Médicaments prescrits</div>
    @foreach(explode("\n", $ordonnance->medicaments) as $ligne)
        @if(trim($ligne))
            <div class="medicament">{{ trim($ligne) }}</div>
        @endif
    @endforeach

    <!-- Instructions -->
    @if($ordonnance->instructions)
        <div class="section-title">📋 Instructions</div>
        <div class="instructions">{{ $ordonnance->instructions }}</div>
    @endif

    <!-- Signature -->
    <div class="footer-signature">
        <div class="sig-box">
            <br><br>
            <strong>
                {{ $ordonnance->consultation->rendezvous->medecin->user->name }}
            </strong><br>
            <small>
                {{ $ordonnance->consultation->rendezvous->medecin->specialite }}
            </small>
        </div>
        <div style="clear:both;"></div>
    </div>

    <!-- Pied de page -->
    <div class="footer">
        Cabinet Médical — 123 Avenue Mohammed V, Marrakech
        | Généré le {{ now()->format('d/m/Y à H:i') }}
        | Document valable 3 mois
    </div>

</body>
</html>