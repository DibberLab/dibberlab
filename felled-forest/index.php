<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Felled Forest | Heirloom Wood & Provisions</title>
    <style>
        /* --- RESET & VARIABLES --- */
        :root {
            --bg-dark: #0f0f0f;
            --bg-panel: #181818;
            --bg-card: #222222;
            --accent: #cd7f32; /* Bronze/Burnt Orange */
            --text-main: #e6e6e6;
            --text-muted: #999999;
            --border: #333333;
            
            --font-display: 'Georgia', 'Times New Roman', serif;
            --font-body: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            background-color: var(--bg-dark);
            color: var(--text-main);
            font-family: var(--font-body);
            line-height: 1.7;
            overflow-x: hidden;
        }

        a { text-decoration: none; color: inherit; transition: 0.3s; }
        ul { list-style: none; }
        img { max-width: 100%; display: block; }

        /* --- TYPOGRAPHY --- */
        h1, h2, h3, h4 { font-family: var(--font-display); font-weight: 700; letter-spacing: 0.5px; color: #fff; }
        h1 { font-size: 3.5rem; line-height: 1.1; }
        h2 { font-size: 2.5rem; margin-bottom: 1.5rem; }
        h3 { font-size: 1.5rem; margin-bottom: 1rem; }
        
        .accent-text { color: var(--accent); }
        .uppercase { text-transform: uppercase; letter-spacing: 3px; font-size: 0.8rem; font-weight: 700; color: var(--accent); display: block; margin-bottom: 10px; }
        .serif-italic { font-family: var(--font-display); font-style: italic; color: var(--text-muted); }

        /* --- LAYOUT UTILS --- */
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; }
        .grid-3 { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; }
        .section-pad { padding: 100px 0; }
        .text-center { text-align: center; }

        /* --- ANIMATIONS --- */
        .fade-in { opacity: 0; transform: translateY(20px); transition: opacity 0.8s ease, transform 0.8s ease; }
        .fade-in.visible { opacity: 1; transform: translateY(0); }

        /* --- NAVIGATION --- */
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
            background-color: rgba(15, 15, 15, 0.95);
            backdrop-filter: blur(10px);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid var(--border);
        }

        .logo { font-size: 1.4rem; font-family: var(--font-display); font-weight: bold; letter-spacing: 1px; }
        .nav-links { display: flex; gap: 40px; }
        .nav-links a { font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); }
        .nav-links a:hover { color: var(--accent); }
        
        .cta-btn {
            background-color: var(--accent);
            color: #000;
            padding: 12px 30px;
            font-weight: bold;
            font-size: 0.9rem;
            letter-spacing: 1px;
            border: 1px solid var(--accent);
        }
        .cta-btn:hover { background-color: transparent; color: var(--accent); }

        /* --- HERO --- */
        header.hero {
            height: 90vh;
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.8)), url('https://images.unsplash.com/photo-1502082553048-f009c37129b9?q=80&w=2000&auto=format&fit=crop'); 
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            position: relative;
        }

        .hero-content { max-width: 800px; padding: 0 20px; }
        .hero p { font-size: 1.3rem; color: #ccc; margin: 20px 0 40px 0; }
        .scroll-indicator { position: absolute; bottom: 30px; left: 50%; transform: translateX(-50%); animation: bounce 2s infinite; opacity: 0.6; }

        @keyframes bounce { 0%, 20%, 50%, 80%, 100% {transform: translateX(-50%) translateY(0);} 40% {transform: translateX(-50%) translateY(-10px);} 60% {transform: translateX(-50%) translateY(-5px);} }

        /* --- MANIFESTO --- */
        .manifesto { background: var(--bg-panel); border-bottom: 1px solid var(--border); }
        .manifesto p { font-size: 1.5rem; font-family: var(--font-display); color: var(--text-muted); line-height: 1.6; max-width: 900px; margin: 0 auto; }
        .manifesto strong { color: var(--text-main); }

        /* --- PRODUCT SHOWCASE: JERKY --- */
        .product-split { display: flex; flex-wrap: wrap; }
        .product-img { flex: 1; min-width: 400px; min-height: 600px; background-size: cover; background-position: center; }
        .product-info { flex: 1; min-width: 400px; padding: 80px; display: flex; flex-direction: column; justify-content: center; background: var(--bg-dark); }
        
        #jerky-img { background-image: url('https://images.unsplash.com/photo-1627483863765-a65c2b5757d4?q=80&w=1200&auto=format&fit=crop'); } /* Steak/Meat */
        
        /* Stats Bars */
        .stat-row { margin-bottom: 15px; }
        .stat-label { display: flex; justify-content: space-between; font-size: 0.8rem; text-transform: uppercase; margin-bottom: 5px; color: var(--text-muted); }
        .stat-bar-bg { width: 100%; height: 4px; background: #333; }
        .stat-bar-fill { height: 100%; background: var(--accent); }

        /* --- PRODUCT SHOWCASE: WOOD --- */
        #wood-img { background-image: url('https://images.unsplash.com/photo-1610555356070-d0efb6505f81?q=80&w=1200&auto=format&fit=crop'); }
        .wood-features li { padding: 15px 0; border-bottom: 1px solid var(--border); color: var(--text-muted); display: flex; align-items: center; }
        .wood-features li::before { content: "DW"; font-family: monospace; color: var(--accent); margin-right: 15px; border: 1px solid var(--accent); padding: 2px 5px; font-size: 0.7rem; }

        /* --- TESTIMONIALS --- */
        .reviews { background-color: var(--bg-panel); }
        .review-card { background: var(--bg-card); padding: 40px; border-left: 3px solid var(--accent); }
        .review-text { font-family: var(--font-display); font-size: 1.2rem; font-style: italic; margin-bottom: 20px; }
        .reviewer { font-size: 0.9rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; }

        /* --- NEWSLETTER --- */
        .newsletter { background: linear-gradient(rgba(0,0,0,0.8), rgba(0,0,0,0.8)), url('https://images.unsplash.com/photo-1542838132-92c53300491e?q=80&w=1200'); background-size: cover; background-position: center; padding: 120px 20px; }
        
        form { margin-top: 30px; display: flex; gap: 0; max-width: 500px; margin-left: auto; margin-right: auto; }
        input[type="email"] { flex: 1; padding: 15px; background: rgba(0,0,0,0.6); border: 1px solid #555; color: #fff; outline: none; }
        input[type="email"]:focus { border-color: var(--accent); }
        form button { padding: 15px 40px; background: var(--accent); border: none; color: #000; font-weight: bold; cursor: pointer; transition: 0.3s; }
        form button:hover { background: #fff; }

        /* --- FOOTER --- */
        footer { padding: 60px 20px; border-top: 1px solid #333; font-size: 0.9rem; color: var(--text-muted); }
        .footer-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 40px; max-width: 1200px; margin: 0 auto; }
        .footer-col h4 { margin-bottom: 20px; color: #fff; }
        .footer-col ul li { margin-bottom: 10px; }
        .footer-col a:hover { color: var(--accent); text-decoration: underline; }

        @media (max-width: 900px) {
            .hero h1 { font-size: 2.5rem; }
            .grid-2 { grid-template-columns: 1fr; }
            .product-split { flex-direction: column; }
            .product-img { min-height: 400px; }
            .product-info { padding: 40px 20px; }
            .nav-links { display: none; }
        }
    </style>
</head>
<body>

    <nav>
        <div class="logo">FELLED FOREST</div>
        <div class="nav-links">
            <a href="#manifesto">Philosophy</a>
            <a href="#provisions">Provisions</a>
            <a href="#wood">The Shop</a>
        </div>
        <a href="#subscribe" class="cta-btn">The List</a>
    </nav>

    <header class="hero">
        <div class="hero-content fade-in">
            <span class="uppercase">Small Batch / Heavy Grain</span>
            <h1>CRAFTED FROM<br><span class="accent-text">THE ROUGH CUT</span></h1>
            <p>Industrial furniture built to last generations. Beef jerky cured to survive the workday. </p>
            <a href="#provisions" class="cta-btn">EXPLORE GOODS</a>
        </div>
        <div class="scroll-indicator">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 13l5 5 5-5M7 6l5 5 5-5"/></svg>
        </div>
    </header>

    <section id="manifesto" class="section-pad manifesto text-center">
        <div class="container fade-in">
            <span class="uppercase">Our Method</span>
            <p>"We don't believe in fillers. Whether it’s particle board in a table or corn syrup in meat, <strong>cheap inputs lead to weak products.</strong> We treat a slab of walnut and a cut of beef with the same respect: Kiln dried, hand-finished, and made for the long haul."</p>
        </div>
    </section>

    <div id="provisions" class="product-split">
        <div class="product-info fade-in">
            <span class="uppercase">The Provisions</span>
            <h2>OBSIDIAN CURED JERKY</h2>
            <p style="margin-bottom: 30px; color: var(--text-muted);">
                Store-bought jerky is candy. Ours is food. We skipped the pink curing salt and high-fructose syrup for a recipe that dates back to the iron range.
                Marinated for 24 hours in soy, whisky, and crushed black pepper, then smoked until it snaps.
            </p>

            <div class="flavor-stats">
                <div class="stat-row">
                    <div class="stat-label"><span>Smoke Intensity</span><span>High</span></div>
                    <div class="stat-bar-bg"><div class="stat-bar-fill" style="width: 85%;"></div></div>
                </div>
                <div class="stat-row">
                    <div class="stat-label"><span>Savory / Umami</span><span>Very High</span></div>
                    <div class="stat-bar-bg"><div class="stat-bar-fill" style="width: 95%;"></div></div>
                </div>
                <div class="stat-row">
                    <div class="stat-label"><span>Black Pepper Heat</span><span>Medium</span></div>
                    <div class="stat-bar-bg"><div class="stat-bar-fill" style="width: 60%;"></div></div>
                </div>
                <div class="stat-row">
                    <div class="stat-label"><span>Sweetness</span><span>Low</span></div>
                    <div class="stat-bar-bg"><div class="stat-bar-fill" style="width: 20%;"></div></div>
                </div>
            </div>

            <div style="margin-top: 40px;">
                <a href="#" class="cta-btn">SHOP BATCH #042</a>
                <span style="margin-left: 20px; font-size: 0.8rem; color: #666;">*Limited Supply</span>
            </div>
        </div>
        <div class="product-img" id="jerky-img"></div>
    </div>

    <div id="wood" class="product-split">
        <div class="product-img" id="wood-img"></div>
        <div class="product-info fade-in">
            <span class="uppercase">The Shop</span>
            <h2>KILN DRIED GOODS</h2>
            <p style="margin-bottom: 30px; color: var(--text-muted);">
                We fell domestic hardwoods or salvage trees damaged in storms. No veneers. No epoxy rivers. Just solid joinery and finishes that let the grain speak.
            </p>
            
            <ul class="wood-features">
                <li><strong>Sourcing:</strong> Fallen Walnut, White Oak, Ash</li>
                <li><strong>Milling:</strong> Rough sawn and air-dried for 12 months</li>
                <li><strong>Finishing:</strong> Hand-rubbed polymerized linseed oil</li>
                <li><strong>Hardware:</strong> Blackened cold-rolled steel</li>
            </ul>

            <div style="margin-top: 40px;">
                <a href="#" class="cta-btn">VIEW COMMISSION LIST</a>
            </div>
        </div>
    </div>

    <section class="section-pad reviews">
        <div class="container">
            <div class="text-center fade-in" style="margin-bottom: 60px;">
                <span class="uppercase">From the Field</span>
                <h2>CLIENT NOTES</h2>
            </div>
            
            <div class="grid-3">
                <div class="review-card fade-in">
                    <div class="review-text">"Finally, jerky that actually tastes like steak. The pepper hit is perfect. I keep a bag in the truck and one in the shop."</div>
                    <div class="reviewer">— D. Miller, Carpenter</div>
                </div>
                <div class="review-card fade-in">
                    <div class="review-text">"The dining table Felled Forest built is a tank. It’s the centerpiece of our home. You can feel the quality in the weight of it."</div>
                    <div class="reviewer">— Sarah J., Architect</div>
                </div>
                <div class="review-card fade-in">
                    <div class="review-text">"Ordered the 'Shop Rations' pack. The flavor depth is insane compared to gas station stuff. Highly recommend."</div>
                    <div class="reviewer">— Mark T., Welder</div>
                </div>
            </div>
        </div>
    </section>

    <section id="subscribe" class="newsletter text-center">
        <div class="container fade-in">
            <h2>CLAIM YOUR BATCH</h2>
            <p style="color: #ccc; max-width: 600px; margin: 20px auto;">
                We are a two-person operation. Furniture commissions open quarterly. Jerky batches drop monthly. Get on the list to eat.
            </p>
            <form>
                <input type="email" placeholder="Enter email address" required>
                <button type="submit">JOIN</button>
            </form>
        </div>
    </section>

    <footer>
        <div class="footer-grid">
            <div class="footer-col">
                <h4>FELLED FOREST</h4>
                <p>Grain & Patience.</p>
                <p>Est. 2024</p>
            </div>
            <div class="footer-col">
                <h4>Shop</h4>
                <ul>
                    <li><a href="#">Provisions</a></li>
                    <li><a href="#">Furniture</a></li>
                    <li><a href="#">Apparel</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Support</h4>
                <ul>
                    <li><a href="#">Shipping Policy</a></li>
                    <li><a href="#">Ingredients & Allergens</a></li>
                    <li><a href="#">Commission FAQ</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Contact</h4>
                <ul>
                    <li><a href="#">shop@felledforest.com</a></li>
                    <li><a href="#">Instagram</a></li>
                </ul>
            </div>
        </div>
        <div class="text-center" style="margin-top: 60px; font-size: 0.8rem; opacity: 0.5;">
            &copy; 2024 Felled Forest Co. All rights reserved.
        </div>
    </footer>

    <script>
        // Intersection Observer for Fade-in Animation
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target); // Only animate once
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-in').forEach(element => {
            observer.observe(element);
        });

        // Smooth Scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>

</body>
</html>