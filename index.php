<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LinkSpotter Hub - Verified Gateway</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
        body { background: linear-gradient(180deg, #12091d 0%, #24103a 50%, #3d1446 100%); color: #f0eaf5; min-height: 100vh; display: flex; justify-content: center; padding: 40px 16px; }
        .container { width: 100%; max-width: 520px; display: flex; flex-direction: column; align-items: center; }
        header { text-align: center; margin-bottom: 24px; width: 100%; }
        .avatar { width: 88px; height: 88px; border-radius: 50%; object-fit: cover; border: 3px solid #ab47bc; margin-bottom: 12px; }
        .username { font-size: 1.5rem; font-weight: 800; color: #ffffff; margin-bottom: 6px; }
        .bio { font-size: 0.92rem; color: #cfc1e3; line-height: 1.45; margin-bottom: 14px; }
        .search-container { width: 100%; position: relative; margin-bottom: 20px; }
        .search-input { width: 100%; padding: 13px 18px 13px 44px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.22); background: rgba(255,255,255,0.07); color: #fff; outline: none; font-size: 0.95rem; }
        .search-icon { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #ba68c8; }
        .link-list { width: 100%; display: flex; flex-direction: column; gap: 12px; }
        .link-card { background: rgba(255, 255, 255, 0.06); border: 1px solid rgba(255, 255, 255, 0.12); color: #ffffff; text-decoration: none; display: flex; align-items: center; padding: 12px 16px; border-radius: 12px; transition: 0.15s ease; }
        .link-card:hover { background: rgba(255, 255, 255, 0.12); border-color: #ba68c8; transform: translateY(-2px); }
        .link-card img { width: 44px; height: 44px; border-radius: 8px; object-fit: cover; flex-shrink: 0; }
        .link-info { flex-grow: 1; padding: 0 14px; }
        .link-title { font-weight: 600; font-size: 0.95rem; display: block; }
        .link-sub { font-size: 0.75rem; color: #b39ddb; }
        .no-results { color: #aaa; text-align: center; font-size: 0.9rem; margin-top: 20px; display: none; }
    </style>
</head>
<body>

<div class="container">
    <header>
        <img src="https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=200" alt="Logo" class="avatar">
        <h1 class="username">LinkSpotter Hub</h1>
        <p class="bio">Curating verified file distributions and cloud mirror gateways.</p>
    </header>

    <main style="width:100%;">
        <div class="search-container">
            <i class="fa-solid fa-magnifying-glass search-icon"></i>
            <input type="search" id="searchInput" class="search-input" placeholder="Search verified mirrors...">
        </div>

        <nav class="link-list" id="linkContainer"></nav>
        <p id="noResultsMsg" class="no-results">No matching mirrors found.</p>
    </main>
</div>

<script>
    async function loadLinks() {
        const container = document.getElementById('linkContainer');
        
        // Fetch base links or load from localStorage if edited via static admin
        let links = JSON.parse(localStorage.getItem('static_links'));
        if (!links) {
            const res = await fetch('links.json');
            links = await res.json();
            localStorage.setItem('static_links', JSON.stringify(links));
        }

        container.innerHTML = links.slice().reverse().map(link => `
            <a href="redirect.html?id=${link.id}" class="link-card" data-title="${link.title.toLowerCase()}" target="_blank">
                <img src="${link.image_url}" alt="Icon" onerror="this.src='https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=100'">
                <div class="link-info">
                    <span class="link-title">${link.title}</span>
                    <span class="link-sub"><i class="fa-solid fa-cloud-arrow-down"></i> Direct Node • ${link.clicks || 0} requests</span>
                </div>
                <i class="fa-solid fa-chevron-right" style="color:#ba68c8;"></i>
            </a>
        `).join('');
    }

    document.getElementById('searchInput').addEventListener('input', function() {
        const q = this.value.trim().toLowerCase();
        const cards = document.querySelectorAll('.link-card');
        let count = 0;
        cards.forEach(card => {
            const match = card.dataset.title.includes(q);
            card.style.display = match ? 'flex' : 'none';
            if (match) count++;
        });
        document.getElementById('noResultsMsg').style.display = (count === 0) ? 'block' : 'none';
    });

    loadLinks();
</script>
</body>
</html>
