<!-- <!DOCTYPE html>
<html><head><title>Admit Card</title><style> @page { size: A4 portrait; margin:10mm; } </style></head>
<body>
    
    <div style="border:2px solid #000; padding:20px; text-align:center">
        <h2><?= esc($content_data['data']['student']['school_name'] ?? 'School Name') ?> - Admit Card</h2>
        <img src="<?= base_url($content_data['data']['student']['profile_image']) ?>" width="120"><br>
        <h3><?= esc(ucwords($content_data['data']['student']['first_name'].' '.$content_data['data']['student']['last_name'])) ?> (<?= $content_data['data']['student']['roll_no'] ?>)</h3>
        <p>Class: <?= $content_data['data']['student']['class_name'] ?> | Exam: ________________</p>
        <button onclick="window.print()">🖨️ Print</button>
    </div>
</body></html> -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(145deg, #e0eafc 0%, #cfdef3 100%);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px 20px;
        }

        /* premium card container with modern depth */
        .admit-card {
            max-width: 880px;
            width: 100%;
            background: #ffffff;
            border-radius: 48px;
            box-shadow: 0 25px 45px -12px rgba(0, 0, 0, 0.35), 0 4px 12px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            transition: transform 0.2s ease;
            position: relative;
        }

        /* decorative gradient header stripe */
        .card-header-stripe {
            height: 12px;
            background: linear-gradient(90deg, #1E3C72, #2A5298, #6A11CB, #2575FC);
            width: 100%;
        }

        /* main content padding */
        .card-inner {
            padding: 2rem 2.5rem 2.2rem 2.5rem;
        }

        /* header area with school name and logo badge */
        .school-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            border-bottom: 2px dashed #e2e8f0;
            padding-bottom: 1.2rem;
            margin-bottom: 1.6rem;
        }

        .title-section h2 {
            font-size: 1.9rem;
            font-weight: 800;
            background: linear-gradient(135deg, #1E2A5E, #2B3B7A);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            letter-spacing: -0.3px;
            margin-bottom: 0.25rem;
        }

        .title-section p {
            color: #4a5568;
            font-weight: 500;
            font-size: 0.85rem;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .badge-icon {
            background: #f0f4fe;
            padding: 10px 20px;
            border-radius: 60px;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
            color: #1e3a8a;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }

        .badge-icon i {
            font-size: 1.3rem;
        }

        /* student profile row: modern split layout */
        .student-profile-row {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            margin-bottom: 30px;
            background: #F9FAFE;
            border-radius: 36px;
            padding: 1.5rem;
            transition: all 0.2s;
        }

        .profile-image-area {
            flex: 0 0 130px;
            text-align: center;
        }

        .profile-image-frame {
            background: white;
            padding: 6px;
            border-radius: 32px;
            box-shadow: 0 15px 25px -10px rgba(0, 0, 0, 0.15);
            border: 2px solid rgba(37, 117, 252, 0.3);
            transition: 0.2s;
        }

        .student-img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 28px;
            display: block;
            background: #f1f5f9;
        }

        .student-details {
            flex: 2;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: space-between;
        }

        .info-group {
            min-width: 180px;
        }

        .info-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 1px;
            color: #5b6e8c;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .info-value {
            font-size: 1.3rem;
            font-weight: 800;
            color: #0a2540;
            line-height: 1.3;
        }

        .roll-badge {
            background: #eef2ff;
            padding: 4px 12px;
            border-radius: 40px;
            font-size: 0.9rem;
            font-weight: 600;
            display: inline-block;
        }

        /* exam details grid */
        .exam-details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 20px;
            background: #ffffff;
            border-radius: 28px;
            padding: 1.2rem 0.8rem;
            margin: 10px 0 20px 0;
            border: 1px solid #eef2ff;
        }

        .detail-card {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 8px 12px;
            background: #F8FAFF;
            border-radius: 60px;
            transition: all 0.2s;
        }

        .detail-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(145deg, #ffffff, #eef2fc);
            border-radius: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2c5f9a;
            font-size: 1.3rem;
            box-shadow: 0 4px 8px rgba(0,0,0,0.02);
        }

        .detail-text h4 {
            font-size: 0.7rem;
            font-weight: 600;
            color: #5f7f9e;
            letter-spacing: 0.5px;
        }

        .detail-text p {
            font-size: 1rem;
            font-weight: 800;
            color: #0b2b42;
        }

        /* instructions & signature section */
        .info-footer {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin: 30px 0 20px 0;
            border-top: 2px dotted #e2edff;
            padding-top: 24px;
            gap: 20px;
        }

        .instruction-box {
            flex: 2;
        }

        .instruction-box h4 {
            font-size: 0.8rem;
            font-weight: 800;
            margin-bottom: 12px;
            color: #1e2f4b;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .instruction-list {
            list-style: none;
        }

        .instruction-list li {
            font-size: 0.75rem;
            margin-bottom: 8px;
            display: flex;
            align-items: baseline;
            gap: 8px;
            color: #2c3e50;
            font-weight: 500;
        }

        .instruction-list li i {
            font-size: 0.7rem;
            color: #3b82f6;
            width: 18px;
        }

        .signature-area {
            flex: 1;
            text-align: right;
            border-left: 1px solid #e2e8f0;
            padding-left: 20px;
        }

        .signature-line {
            margin-top: 20px;
            border-top: 2px dashed #94a3b8;
            width: 160px;
            display: inline-block;
            padding-top: 8px;
            font-size: 0.7rem;
            font-weight: 600;
            color: #475569;
        }

        /* print button container */
        .action-buttons {
            display: flex;
            justify-content: flex-end;
            margin-top: 10px;
            gap: 18px;
            align-items: center;
        }

        .btn-print {
            background: linear-gradient(95deg, #1E3C72, #2b4c8a);
            border: none;
            padding: 12px 28px;
            border-radius: 50px;
            color: white;
            font-weight: 700;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: 0.2s;
            box-shadow: 0 6px 14px rgba(0, 35, 80, 0.2);
            font-family: 'Inter', sans-serif;
        }

        .btn-print:hover {
            background: linear-gradient(95deg, #112a4f, #1f3e6e);
            transform: scale(0.97);
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        }

        .btn-print i {
            font-size: 1rem;
        }

        /* watermark effect (subtle) */
        .admit-card::before {
            content: "✦ ADMIT CARD ✦";
            position: absolute;
            bottom: 20px;
            right: 25px;
            font-size: 60px;
            font-weight: 800;
            color: rgba(165, 180, 210, 0.08);
            pointer-events: none;
            font-family: 'Inter', sans-serif;
            z-index: 0;
            letter-spacing: 4px;
            transform: rotate(-5deg);
        }

        /* print styles - A4 perfect */
        @media print {
            body {
                background: white;
                padding: 0;
                margin: 0;
            }
            .admit-card {
                box-shadow: none;
                border-radius: 0;
                max-width: 100%;
                margin: 0;
                page-break-after: avoid;
                break-inside: avoid;
            }
            .btn-print {
                display: none;
            }
            .action-buttons {
                display: none;
            }
            .card-header-stripe {
                background: #000 !important;
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
            .badge-icon, .detail-card, .profile-image-frame, .student-profile-row {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
            .student-img {
                border: 1px solid #ccc;
            }
            .info-value, .detail-text p {
                color: black;
            }
            @page {
                size: A4 portrait;
                margin: 1.2cm;
            }
            .admit-card::before {
                opacity: 0.2;
            }
        }

        /* responsive */
        @media (max-width: 680px) {
            .card-inner {
                padding: 1.2rem;
            }
            .student-profile-row {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
            .student-details {
                justify-content: center;
                text-align: center;
            }
            .info-group {
                text-align: center;
            }
            .signature-area {
                text-align: center;
                border-left: none;
                border-top: 1px solid #e2e8f0;
                padding-top: 20px;
            }
            .signature-line {
                width: 100%;
            }
        }
    </style>



<div class="admit-card">
    <div class="card-header-stripe"></div>
    <div class="card-inner">
        <!-- Header: School & digital badge -->
        <div class="school-header">
            <div class="title-section">
                <h2>🎓 ADMIT CARD • EXAM SESSION</h2>
                <p>authorized examination permit • verified digital document</p>
            </div>
            <div class="badge-icon">
                <i class="fas fa-qrcode"></i> 
                <span>ID: <?= esc($content_data['data']['student']['roll_no'] ?? 'STU-2025') ?></span>
            </div>
        </div>

        <!-- Student Profile Row: elegant -->
        <div class="student-profile-row">
            <div class="profile-image-area">
                <div class="profile-image-frame">
                    <?php 
                        $imgSrc = base_url($content_data['data']['student']['profile_image'] ?? '');
                        // Fallback in case image is missing
                        if(empty($content_data['data']['student']['profile_image'])) {
                            $imgSrc = 'https://ui-avatars.com/api/?background=1E3C72&color=fff&rounded=true&size=120&bold=true&name='.urlencode(($content_data['data']['student']['first_name'] ?? 'S').' '.($content_data['data']['student']['last_name'] ?? 'T'));
                        }
                    ?>
                    <img class="student-img" src="<?= $imgSrc ?>" alt="Student portrait" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?background=2A5298&color=fff&rounded=true&size=120&bold=true&name=ST'">
                </div>
            </div>
            <div class="student-details">
                <div class="info-group">
                    <div class="info-label"><i class="fas fa-user-graduate"></i> Full Name</div>
                    <div class="info-value"><?= esc(ucwords(($content_data['data']['student']['first_name'] ?? 'Alex') . ' ' . ($content_data['data']['student']['last_name'] ?? 'Morgan'))) ?></div>
                </div>
                <div class="info-group">
                    <div class="info-label"><i class="fas fa-id-card"></i> Roll Number</div>
                    <div class="info-value"><span class="roll-badge"><?= esc($content_data['data']['student']['roll_no'] ?? '24CS101') ?></span></div>
                </div>
                <div class="info-group">
                    <div class="info-label"><i class="fas fa-layer-group"></i> Class / Section</div>
                    <div class="info-value"><?= esc($content_data['data']['student']['class_name'] ?? 'Grade XII - Science') ?></div>
                </div>
            </div>
        </div>

        <!-- Exam details: dynamic and modern -->
        <div class="exam-details-grid">
            <div class="detail-card">
                <div class="detail-icon"><i class="fas fa-calendar-alt"></i></div>
                <div class="detail-text">
                    <h4>EXAM DATE</h4>
                    <p><?= esc($content_data['data']['exam_date'] ?? '15 - 25 March 2026') ?></p>
                </div>
            </div>
            <div class="detail-card">
                <div class="detail-icon"><i class="fas fa-clock"></i></div>
                <div class="detail-text">
                    <h4>REPORTING TIME</h4>
                    <p><?= esc($content_data['data']['reporting_time'] ?? '08:30 AM (Sharp)') ?></p>
                </div>
            </div>
            <div class="detail-card">
                <div class="detail-icon"><i class="fas fa-map-marker-alt"></i></div>
                <div class="detail-text">
                    <h4>EXAM CENTER</h4>
                    <p><?= esc($content_data['data']['center'] ?? 'Main Auditorium Hall - Block A') ?></p>
                </div>
            </div>
            <div class="detail-card">
                <div class="detail-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                <div class="detail-text">
                    <h4>EXAMINATION</h4>
                    <p><?= esc($content_data['data']['exam_name'] ?? 'Annual Final Assessment 2026') ?></p>
                </div>
            </div>
        </div>

        <!-- Additional info: instructions & signature & dynamic note -->
        <div class="info-footer">
            <div class="instruction-box">
                <h4><i class="fas fa-clipboard-list"></i> IMPORTANT INSTRUCTIONS</h4>
                <ul class="instruction-list">
                    <li><i class="fas fa-check-circle"></i> Admit card is mandatory for entry — no exceptions.</li>
                    <li><i class="fas fa-check-circle"></i> Reach the center 30 minutes before the exam.</li>
                    <li><i class="fas fa-check-circle"></i> Carry a valid school ID along with this card.</li>
                    <li><i class="fas fa-check-circle"></i> Electronic gadgets are strictly prohibited.</li>
                    <li><i class="fas fa-check-circle"></i> Use black/blue pen only</li>
                </ul>
            </div>
            <div class="signature-area">
                <div><i class="fas fa-stamp"></i> <strong>Authorized Signatory</strong></div>
                <div class="signature-line">
                    <?= esc($content_data['data']['signatory'] ?? 'Dr. Arjun Mehta (Controller of Exams)') ?>
                </div>
                <div style="margin-top: 15px; font-size: 0.7rem; color:#5b6e8c;">
                    <i class="fas fa-fingerprint"></i> Digitally Verified
                </div>
            </div>
        </div>

        <!-- Dynamic remarks if needed -->
        <?php if(!empty($content_data['data']['remarks'])): ?>
        <div style="background: #F1F5F9; border-radius: 20px; padding: 10px 18px; margin-top: 12px; font-size: 0.75rem; display: flex; align-items: center; gap: 12px;">
            <i class="fas fa-ribbon" style="color:#2c5f9a;"></i>
            <span><strong>Note:</strong> <?= esc($content_data['data']['remarks']) ?></span>
        </div>
        <?php endif; ?>

        <!-- Action Buttons: print & elegance -->
        <div class="action-buttons">
            <button class="btn-print" onclick="window.print();">
                <i class="fas fa-print"></i> PRINT ADMIT CARD
            </button>
            <div style="font-size: 0.7rem; color: #4b6a8b;">
                <i class="fas fa-shield-alt"></i> keep digital copy
            </div>
        </div>
    </div>
</div>

<!-- optional micro script to handle dynamic data fallback seamlessly -->
<script>
    // In case any dynamic fields are missing, console safe, but the design stays impressive
    (function() {
        // ensure print margins & smoothness
        console.log("✨ Premium Admit Card — ready for unique experience");
        // allow any custom dynamic style if needed
        const setImageFallback = () => {
            let imgs = document.querySelectorAll('.student-img');
            imgs.forEach(img => {
                img.addEventListener('error', function() {
                    if(!this.src.includes('ui-avatars')) {
                        let name = document.querySelector('.info-value')?.innerText?.trim() || 'Student';
                        let fallback = `https://ui-avatars.com/api/?background=1E3C72&color=fff&rounded=true&size=120&bold=true&name=${encodeURIComponent(name.split(' ')[0])}`;
                        this.src = fallback;
                    }
                });
            });
        };
        setImageFallback();
    })();
</script>

