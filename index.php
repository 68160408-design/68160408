<?php
require_once 'config/db.php';

// Student profile (starter table)
$profile = null;
$sql = "SELECT * FROM profile LIMIT 1";
$result = mysqli_query($conn, $sql);
if ($result && mysqli_num_rows($result) > 0) {
    $profile = mysqli_fetch_assoc($result);
}

// Education (starter table)
$educations = [];
$sql = "SELECT * FROM education ORDER BY start_year DESC";
$result = mysqli_query($conn, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $educations[] = $row;
    }
}

$skills = [];
if ($profile) {
    $profile_id = intval($profile['id']);
    $sql = "SELECT * FROM skill WHERE profile_id = $profile_id";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $skills[] = $row;
        }
    }
}

$trainings = [];
if ($profile) {
    $profile_id = intval($profile['id']);
    $sql = "SELECT * FROM training WHERE profile_id = $profile_id ORDER BY training_year DESC";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $trainings[] = $row;
        }
    }
}

$full_name = $profile ? htmlspecialchars($profile['full_name']) : 'Your Name';
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $profile ? htmlspecialchars($profile['full_name']) : 'My Resume'; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700;900&family=Chakra+Petch:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* ====== โทน: เงาสีม่วง + สายฟ้า + ประกายไฟ ====== */
        :root {
            --void:     #07050d;
            --shadow:   #160c28;
            --violet:   #a66bff;
            --bolt:     #e9d8ff;
            --ember:    #ffb86b;
            --mist:     #b8acd4;
            --text:     #e8dffa;
            --display:  'Cinzel', 'Chakra Petch', serif;
            --body:     'Chakra Petch', 'Cinzel', sans-serif;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: var(--body);
            font-size: 16px;
            line-height: 1.65;
            color: var(--text);
            background:
                radial-gradient(ellipse at 50% -10%, #2a1650 0%, transparent 55%),
                linear-gradient(180deg, var(--shadow) 0%, var(--void) 70%);
            background-attachment: fixed;
            overflow-x: hidden;
        }

        /* หมอกควันลอยช้า ๆ */
        body::before, body::after {
            content: "";
            position: fixed;
            width: 90vmax; height: 90vmax;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
            filter: blur(60px);
        }
        body::before {
            left: -40vmax; bottom: -55vmax;
            background: radial-gradient(circle, rgba(120,90,190,.35), transparent 65%);
            animation: fog-a 38s ease-in-out infinite alternate;
        }
        body::after {
            right: -45vmax; bottom: -60vmax;
            background: radial-gradient(circle, rgba(70,40,130,.40), transparent 65%);
            animation: fog-b 46s ease-in-out infinite alternate;
        }
        @keyframes fog-a { to { transform: translate(18vmax, -6vmax); } }
        @keyframes fog-b { to { transform: translate(-16vmax, -9vmax); } }

        #embers { position: fixed; inset: 0; width: 100%; height: 100%; pointer-events: none; z-index: 1; }

        /* แฟลชสายฟ้าตอนเปิดหน้า */
        #flash {
            position: fixed; inset: 0; z-index: 99; pointer-events: none;
            background: radial-gradient(circle at 50% 25%, #f1e6ff, #a66bff 60%);
            opacity: 0;
            animation: flash 1.2s ease-out .15s 1;
        }
        @keyframes flash {
            0%   { opacity: 0; }
            6%   { opacity: .85; }
            14%  { opacity: .05; }
            20%  { opacity: .5; }
            40%  { opacity: 0; }
            100% { opacity: 0; }
        }

        .container {
            position: relative;
            z-index: 2;
            max-width: 880px;
            margin: 0 auto;
            padding: 48px 20px 32px;
        }

        /* ====== การ์ด ====== */
        .card {
            position: relative;
            margin-bottom: 28px;
            padding: 28px 30px;
            background: linear-gradient(160deg, rgba(40,22,72,.72), rgba(10,6,20,.82));
            border: 1px solid rgba(166,107,255,.32);
            border-radius: 4px;
            box-shadow: 0 10px 40px rgba(0,0,0,.6), inset 0 0 40px rgba(120,60,220,.06);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            color: var(--text);
            transition: border-color .25s ease, box-shadow .25s ease;
        }
        .card:hover {
            border-color: rgba(214,180,255,.7);
            box-shadow: 0 10px 40px rgba(0,0,0,.6), 0 0 28px rgba(166,107,255,.35), inset 0 0 40px rgba(120,60,220,.10);
        }

        /* ====== HERO ====== */
        .hero {
            display: flex;
            align-items: center;
            gap: 32px;
            padding: 40px 36px;
            overflow: hidden;
        }
        .hero > div { position: relative; z-index: 1; }

        .bolt {
            position: absolute; inset: 0; width: 100%; height: 100%;
            z-index: 0; pointer-events: none;
        }
        .bolt path {
            fill: none;
            stroke: var(--bolt);
            stroke-width: 2.2;
            stroke-linejoin: round;
            stroke-dasharray: 1500;
            stroke-dashoffset: 1500;
            filter: drop-shadow(0 0 6px #b57bff) drop-shadow(0 0 16px #8a4dff);
            animation: strike .55s ease-out .25s forwards, bolt-rest 2.2s ease-out .85s forwards;
        }
        @keyframes strike    { to { stroke-dashoffset: 0; } }
        @keyframes bolt-rest { to { opacity: .16; } }

        .avatar {
            position: relative; z-index: 1;
            flex: 0 0 auto;
            width: 190px; height: 190px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid var(--bolt);
            box-shadow:
                0 0 0 6px rgba(20,8,40,.9),
                0 0 24px 4px rgba(166,107,255,.85),
                0 0 70px 14px rgba(110,50,220,.55);
            animation: aura 4.5s ease-in-out infinite;
        }
        @keyframes aura {
            0%,100% { box-shadow: 0 0 0 6px rgba(20,8,40,.9), 0 0 24px 4px rgba(166,107,255,.80), 0 0 70px 14px rgba(110,50,220,.50); }
            50%     { box-shadow: 0 0 0 6px rgba(20,8,40,.9), 0 0 34px 8px rgba(190,140,255,.95), 0 0 100px 26px rgba(120,60,240,.65); }
        }

        h1 {
            position: relative;
            margin: 0 0 6px;
            font-family: var(--display);
            font-weight: 900;
            font-size: clamp(2.2rem, 6vw, 3.6rem);
            letter-spacing: .06em;
            line-height: 1.1;
            color: #fff;
            text-shadow: 0 0 8px rgba(190,140,255,.9), 0 0 28px rgba(140,80,255,.75), 0 0 60px rgba(110,50,220,.6);
        }
        /* กลิตช์ครั้งเดียวตอนโหลด */
        h1::before, h1::after {
            content: attr(data-text);
            position: absolute; left: 0; top: 0;
            width: 100%;
            opacity: 0;
            pointer-events: none;
        }
        h1::before { color: #7d4dff; animation: glitch-a .7s steps(2,end) .5s 1; }
        h1::after  { color: #ff9ad8; animation: glitch-b .7s steps(2,end) .5s 1; }
        @keyframes glitch-a {
            0%   { opacity: .9; transform: translate(-4px, 1px); clip-path: inset(10% 0 55% 0); }
            35%  { opacity: .9; transform: translate(3px, -1px); clip-path: inset(55% 0 10% 0); }
            70%  { opacity: .9; transform: translate(-2px, 0);   clip-path: inset(30% 0 35% 0); }
            100% { opacity: 0;  transform: none; }
        }
        @keyframes glitch-b {
            0%   { opacity: .8; transform: translate(4px, -1px); clip-path: inset(60% 0 8% 0); }
            40%  { opacity: .8; transform: translate(-3px, 1px); clip-path: inset(8% 0 62% 0); }
            75%  { opacity: .8; transform: translate(2px, 0);    clip-path: inset(40% 0 30% 0); }
            100% { opacity: 0;  transform: none; }
        }

        .headline {
            margin: 0 0 14px;
            font-size: 1.1rem;
            font-weight: 500;
            letter-spacing: .08em;
            color: #cfb4ff;
            text-shadow: 0 0 12px rgba(166,107,255,.6);
        }
        .hero p { margin: 2px 0; color: var(--mist); }

        /* ====== หัวข้อ + เส้นสายฟ้า ====== */
        h2 {
            position: relative;
            margin: 0 0 22px;
            padding-bottom: 14px;
            font-family: var(--display);
            font-weight: 700;
            font-size: 1.55rem;
            letter-spacing: .1em;
            color: #fff;
            border-bottom: none !important;
            background: none !important;
            text-shadow: 0 0 14px rgba(166,107,255,.7);
        }
        h2::after {
            content: "";
            position: absolute; left: 0; bottom: 0;
            width: 100%; height: 8px;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 400 8' preserveAspectRatio='none'%3E%3Cpolyline points='0,4 70,4 78,1 86,7 97,2 108,6 120,4 400,4' fill='none' stroke='%23c9a0ff' stroke-width='1.4' vector-effect='non-scaling-stroke'/%3E%3C/svg%3E") left center / 100% 100% no-repeat;
            filter: drop-shadow(0 0 4px #a66bff);
            -webkit-mask-image: linear-gradient(90deg, #000 30%, transparent 100%);
                    mask-image: linear-gradient(90deg, #000 30%, transparent 100%);
        }
        h3 {
            margin: 0 0 4px;
            font-family: var(--body);
            font-weight: 600;
            font-size: 1.12rem;
            color: #f6efff;
        }

        /* ====== รายการ (Education / Training) ====== */
        .item {
            position: relative;
            margin-bottom: 14px;
            padding: 14px 18px;
            border: none !important;
            border-left: 3px solid var(--violet) !important;
            border-radius: 0;
            background: linear-gradient(90deg, rgba(120,70,210,.20), rgba(10,6,20,0) 85%);
            box-shadow: -8px 0 18px -10px rgba(166,107,255,.9);
            transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
        }
        .item:last-child { margin-bottom: 0; }
        .item:hover {
            transform: translateX(6px);
            background: linear-gradient(90deg, rgba(150,95,255,.34), rgba(10,6,20,0) 90%);
            box-shadow: -10px 0 24px -8px rgba(200,160,255,1);
        }
        .item p { margin: 2px 0; color: var(--mist); }

        /* ====== สกิล ====== */
        .skills { display: flex; flex-wrap: wrap; gap: 14px; }
        .skill-badge {
            position: relative;
            flex: 1 1 260px;
            padding: 14px 18px 14px 22px;
            background: linear-gradient(135deg, rgba(100,50,200,.30), rgba(10,6,20,.55));
            border: 1px solid rgba(190,150,255,.45);
            border-radius: 2px;
            box-shadow: 0 0 14px rgba(120,60,230,.28), inset 0 0 18px rgba(120,60,230,.10);
            transition: box-shadow .2s ease, border-color .2s ease, transform .2s ease;
        }
        .skill-badge::before {          /* เหลี่ยมมุมแบบเงาคม */
            content: "";
            position: absolute; left: -1px; top: -1px;
            width: 14px; height: 14px;
            border-top: 2px solid var(--bolt);
            border-left: 2px solid var(--bolt);
            filter: drop-shadow(0 0 4px #a66bff);
        }
        .skill-badge::after {
            content: "";
            position: absolute; right: -1px; bottom: -1px;
            width: 14px; height: 14px;
            border-bottom: 2px solid var(--bolt);
            border-right: 2px solid var(--bolt);
            filter: drop-shadow(0 0 4px #a66bff);
        }
        .skill-badge:hover {
            transform: translateY(-3px);
            border-color: var(--bolt);
            box-shadow: 0 0 26px rgba(166,107,255,.7), inset 0 0 22px rgba(140,80,255,.2);
        }
        .skill-badge strong {
            display: block;
            font-weight: 600;
            font-size: 1.04rem;
            color: #fff;
            text-shadow: 0 0 10px rgba(166,107,255,.6);
        }
        .skill-badge span {
            display: block;
            margin-top: 4px;
            font-size: .9rem;
            color: #d8c3ff !important;
            opacity: 1 !important;
        }

        footer {
            padding: 18px 0 8px;
            text-align: center;
            font-size: .9rem;
            letter-spacing: .04em;
            color: #8f82b0;
        }

        /* ====== มือถือ ====== */
        @media (max-width: 640px) {
            .hero { flex-direction: column; text-align: center; padding: 32px 20px; }
            .avatar { width: 150px; height: 150px; }
            .card { padding: 22px 18px; }
        }

        /* ลดการเคลื่อนไหวตามการตั้งค่าของผู้ใช้ */
        @media (prefers-reduced-motion: reduce) {
            body::before, body::after, .avatar, #flash, h1::before, h1::after { animation: none !important; }
            .bolt path { animation: none !important; stroke-dashoffset: 0; opacity: .16; }
            #embers { display: none; }
            .item, .skill-badge { transition: none; }
        }
    </style>
</head>
<body>
<div id="flash" aria-hidden="true"></div>
<canvas id="embers" aria-hidden="true"></canvas>

<div class="container">
    <header class="hero card">
        <svg class="bolt" viewBox="0 0 880 300" preserveAspectRatio="none" aria-hidden="true">
            <path d="M-10,20 L180,70 L150,105 L360,150 L330,185 L560,215 L540,245 L890,290"/>
        </svg>
        <img class="avatar" src="assets/images/<?php echo $profile ? htmlspecialchars($profile['photo']) : 'student.jpg'; ?>" alt="Profile Photo">
        <div>
            <h1 data-text="<?php echo $full_name; ?>"><?php echo $full_name; ?></h1>
            <p class="headline"><?php echo $profile ? htmlspecialchars($profile['headline']) : 'Student Resume Website'; ?></p>
            <?php if ($profile): ?>
                <p>📧 <?php echo htmlspecialchars($profile['email']); ?></p>
                <p>📱 <?php echo htmlspecialchars($profile['phone']); ?></p>
                <p>📍 <?php echo htmlspecialchars($profile['address']); ?></p>
            <?php endif; ?>
        </div>
    </header>

    <section class="card">
        <h2>About Me</h2>
        <p><?php echo $profile ? nl2br(htmlspecialchars($profile['about_me'])) : 'Add your profile data in phpMyAdmin.'; ?></p>
    </section>

    <section class="card">
        <h2>Education</h2>
        <?php if (count($educations) > 0): ?>
            <?php foreach ($educations as $edu): ?>
                <div class="item">
                    <h3><?php echo htmlspecialchars($edu['degree']); ?></h3>
                    <p><?php echo htmlspecialchars($edu['institution']); ?></p>
                    <p><?php echo htmlspecialchars($edu['start_year']); ?> - <?php echo htmlspecialchars($edu['end_year']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No education data found.</p>
        <?php endif; ?>
    </section>

    <section class="card">
        <h2>Skills</h2>
        <div class="skills">
            <?php foreach ($skills as $skill): ?>
                <div class="skill-badge">
                    <strong><?php echo htmlspecialchars($skill['skill_name']); ?></strong>
                    <span><?php echo htmlspecialchars($skill['skill_level']); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="card">
        <h2>Training / Certificates</h2>
        <?php foreach ($trainings as $training): ?>
            <div class="item">
                <h3><?php echo htmlspecialchars($training['course_name']); ?></h3>
                <p><?php echo htmlspecialchars($training['organization']); ?>, <?php echo htmlspecialchars($training['training_year']); ?></p>
            </div>
        <?php endforeach; ?>
    </section>

    <footer>
        Database Lab: Personal Resume Website with PHP + MySQL
    </footer>
</div>

<script>
/* ประกายไฟลอยขึ้น (ส้ม + ม่วง) */
(function () {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    var c = document.getElementById('embers');
    var ctx = c.getContext('2d');
    var W, H, dpr = Math.min(window.devicePixelRatio || 1, 2), parts = [];

    function resize() {
        W = c.width  = window.innerWidth  * dpr;
        H = c.height = window.innerHeight * dpr;
    }
    function spawn(initial) {
        var warm = Math.random() < 0.55;
        return {
            x: Math.random() * W,
            y: initial ? Math.random() * H : H + 10,
            r: (Math.random() * 1.6 + 0.6) * dpr,
            vy: (Math.random() * 0.35 + 0.12) * dpr,
            sway: Math.random() * Math.PI * 2,
            rgb: warm ? '255,184,107' : '200,160,255',
            a: Math.random() * 0.6 + 0.25
        };
    }
    resize();
    window.addEventListener('resize', resize);
    for (var i = 0; i < 55; i++) parts.push(spawn(true));

    function tick(t) {
        ctx.clearRect(0, 0, W, H);
        for (var i = 0; i < parts.length; i++) {
            var p = parts[i];
            p.y -= p.vy;
            p.x += Math.sin(t / 1800 + p.sway) * 0.25 * dpr;
            if (p.y < -10) parts[i] = spawn(false);
            var tw = 0.6 + 0.4 * Math.sin(t / 500 + p.sway * 3);
            ctx.beginPath();
            ctx.fillStyle = 'rgba(' + p.rgb + ',' + (p.a * tw).toFixed(3) + ')';
            ctx.shadowColor = 'rgba(' + p.rgb + ',0.9)';
            ctx.shadowBlur = 8 * dpr;
            ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
            ctx.fill();
        }
        requestAnimationFrame(tick);
    }
    requestAnimationFrame(tick);
})();
</script>
</body>
</html>
<?php mysqli_close($conn); ?>