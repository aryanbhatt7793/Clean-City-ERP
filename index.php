<?php include "includes/navbar.php"; ?>
<link rel="stylesheet" href="assets/css/style.css">

<style>

/* Global */
body{
    margin:0;
    font-family:'Segoe UI',sans-serif;
    background:#f8fafc;
}

/* HERO SECTION */
.hero{
    background: linear-gradient(135deg,#0f172a,#1e3a8a);
    color:white;
    padding:120px 20px;
    text-align:center;
}

.hero h1{
    font-size:48px;
    margin-bottom:20px;
    font-weight:700;
}

.hero p{
    font-size:20px;
    opacity:0.9;
    margin-bottom:35px;
}

.hero-btn{
    padding:14px 30px;
    background:#3b82f6;
    border:none;
    border-radius:10px;
    color:white;
    font-weight:600;
    text-decoration:none;
    font-size:16px;
    transition:0.3s;
}

.hero-btn:hover{
    background:#2563eb;
}

/* SECTIONS */
.section{
    padding:80px 10%;
}

.section h2{
    font-size:32px;
    margin-bottom:40px;
    text-align:center;
}

/* GRID */
.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:30px;
}

/* CARD */
.card{
    background:white;
    padding:30px;
    border-radius:15px;
    box-shadow:0 15px 35px rgba(0,0,0,0.05);
    transition:0.3s;
}

.card:hover{
    transform:translateY(-8px);
}

.card h3{
    margin-bottom:15px;
}

/* HOW IT WORKS STEPS */
.steps{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
    gap:30px;
    text-align:center;
}

.step-number{
    font-size:28px;
    font-weight:bold;
    color:#2563eb;
    margin-bottom:10px;
}

/* CTA SECTION */
.cta{
    background:linear-gradient(135deg,#2563eb,#1e40af);
    color:white;
    text-align:center;
    padding:80px 20px;
}

.cta h2{
    margin-bottom:20px;
}

.cta a{
    background:white;
    color:#2563eb;
    padding:14px 30px;
    border-radius:10px;
    text-decoration:none;
    font-weight:600;
}

.cta a:hover{
    background:#e5e7eb;
}

/* FOOTER */
.footer{
    background:#0f172a;
    color:white;
    text-align:center;
    padding:20px;
    font-size:14px;
}

</style>


<!-- HERO -->
<div class="hero">
    <h1>♻ Transforming Waste into Opportunity</h1>
    <p>Data-Driven Civic Platform Connecting Citizens, Recyclers & Innovators</p>
    <a href="register.php" class="hero-btn">Get Started Today</a>
</div>


<!-- WHO WE ARE -->
<div class="section">
    <h2>Who We Are</h2>
    <div class="grid">
        <div class="card">
            <h3>🌍 Civic Tech Innovation</h3>
            <p>We combine technology, sustainability, and community engagement to solve urban waste challenges.</p>
        </div>
        <div class="card">
            <h3>🤖 Smart Reporting System</h3>
            <p>Advanced analytics help identify high-risk dumping zones and optimize waste collection routes for greater efficiency and impact.</p>
        </div>
        <div class="card">
            <h3>💰 Incentive Ecosystem</h3>
            <p>Citizens earn points for verified reports and redeem them in a sustainable marketplace.</p>
        </div>
    </div>
</div>


<!-- PROBLEMS -->
<div class="section" style="background:#f1f5f9;">
    <h2>Problems We Solve</h2>
    <div class="grid">
        <div class="card">
            <h3>Illegal Dumping</h3>
            <p>Unreported garbage sites harm public health and the environment.</p>
        </div>
        <div class="card">
            <h3>Recycling Gaps</h3>
            <p>Waste fails to reach recyclers due to inefficient coordination.</p>
        </div>
        <div class="card">
            <h3>Lack of Incentives</h3>
            <p>Citizens have no motivation to report environmental hazards.</p>
        </div>
    </div>
</div>


<!-- HOW IT WORKS -->
<div class="section">
    <h2>How It Works</h2>
    <div class="steps">
        <div>
            <div class="step-number">1</div>
            <p>Citizens report waste in their area.</p>
        </div>
        <div>
            <div class="step-number">2</div>
            <p>Admin verifies & assigns priority level.</p>
        </div>
        <div>
            <div class="step-number">3</div>
            <p>Bidders compete to collect waste.</p>
        </div>
        <div>
            <div class="step-number">4</div>
            <p>Users earn points & redeem eco-products.</p>
        </div>
    </div>
</div>


<!-- ROLES -->
<div class="section" style="background:#f1f5f9;">
    <h2>Platform Roles</h2>
    <div class="grid">
        <div class="card"><h3>User</h3><p>Reports waste & earns rewards.</p></div>
        <div class="card"><h3>Seller</h3><p>Offers recycled eco-products.</p></div>
        <div class="card"><h3>Bidder</h3><p>Bids for waste collection projects.</p></div>
        <div class="card"><h3>Admin</h3><p>Manages and monitors the ecosystem.</p></div>
    </div>
</div>


<!-- CALL TO ACTION -->
<div class="cta">
    <h2>Join the CleanCity Movement</h2>
    <p>Be part of a sustainable, tech-powered future.</p>
    <br>
    <a href="register.php">Create Your Account</a>
</div>


<!-- FOOTER -->
<div class="footer">
    © <?php echo date("Y"); ?> CleanCity | Sustainable Future Initiative
</div>