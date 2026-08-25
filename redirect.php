<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connecting...</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
        body { background: linear-gradient(180deg, #12091d 0%, #24103a 50%, #3d1446 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .card { background: #ffffff; width: 100%; max-width: 440px; padding: 32px 24px; border-radius: 16px; text-align: center; }
        .timer-circle { width: 75px; height: 75px; border-radius: 50%; background: #f3e5f5; color: #7b1fa2; font-size: 1.8rem; font-weight: 800; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; border: 3px solid #ba68c8; }
        .timer-circle.finished { background: #e8f5e9; color: #2e7d32; border-color: #81c784; }
        h2 { color: #2e0854; font-size: 1.3rem; margin-bottom: 6px; }
        p { color: #666; font-size: 0.88rem; margin-bottom: 18px; }
        .btn { display: flex; align-items: center; justify-content: center; gap: 10px; width: 100%; padding: 14px; font-size: 1rem; font-weight: 700; border-radius: 10px; text-decoration: none; }
        .btn-locked { background: #e0e0e0; color: #888; pointer-events: none; }
        .btn-unlocked { background: #7b1fa2; color: #fff; cursor: pointer; }
    </style>
</head>
<body>

<div class="card">
    <div class="timer-circle" id="timer">8</div>
    <h2 id="statusTitle">Connecting to Gateway</h2>
    <p id="statusDesc">Decrypting link destination...</p>

    <a id="getLinkBtn" href="#" class="btn btn-locked">
        <i class="fa-solid fa-lock"></i>
        <span id="btnText">Please wait 8s...</span>
    </a>
</div>

<script>
    const params = new URLSearchParams(window.location.search);
    const id = parseInt(params.get('id'), 10);
    
    async function initRedirect() {
        let links = JSON.parse(localStorage.getItem('static_links'));
        if (!links) {
            const res = await fetch('links.json');
            links = await res.json();
        }

        const target = links.find(l => l.id === id);
        if (!target) {
            window.location.href = 'index.html';
            return;
        }

        // Increment click count locally
        target.clicks = (target.clicks || 0) + 1;
        localStorage.setItem('static_links', JSON.stringify(links));

        document.getElementById('statusDesc').textContent = `Decrypting link for: ${target.title}`;

        let timeLeft = 8;
        const timerElem = document.getElementById('timer');
        const getLinkBtn = document.getElementById('getLinkBtn');
        const btnText = document.getElementById('btnText');
        const statusTitle = document.getElementById('statusTitle');

        const countdown = setInterval(() => {
            timeLeft--;
            timerElem.textContent = timeLeft;
            btnText.textContent = `Please wait ${timeLeft}s...`;

            if (timeLeft <= 0) {
                clearInterval(countdown);
                timerElem.innerHTML = '<i class="fa-solid fa-check"></i>';
                timerElem.classList.add('finished');
                statusTitle.textContent = 'Destination Unlocked';
                getLinkBtn.className = 'btn btn-unlocked';
                getLinkBtn.href = target.url;
                getLinkBtn.innerHTML = '<i class="fa-solid fa-arrow-up-right-from-square"></i> Continue to Resource';
            }
        }, 1000);
    }

    initRedirect();
</script>
</body>
</html>

