<?php
// Base API URLs
$searchUrl = 'https://youtube.googleapis.com/youtube/v3/search';

// Your API key
$apiKey = 'AIzaSyDVclYPmZ3_dUrjADAlPfy-o_SkFEu9zX8';
$channelId = 'UCTl3QQTvqHFjurroKxexy2Q'; // Olympics Channel ID
$maxResults = 12; // Fetch 12 videos

// Fetch videos from the channel (exclude Shorts and Playlists)
$searchParams = http_build_query([
    'part' => 'snippet',
    'channelId' => $channelId,
    'maxResults' => $maxResults,
    'order' => 'date', // Get the latest videos
    'type' => 'video', // Only standard videos, exclude Shorts/Playlists
    'key' => $apiKey
]);

$searchResponse = file_get_contents($searchUrl . '?' . $searchParams);
$searchData = json_decode($searchResponse, true);

// Check if videos are available
$videos = $searchData['items'] ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Olympics Videos</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f9f9f9;
        margin: 0;
        padding: 0;
    }

    .video-events {
        width: 90%;
        max-width: 1400px; /* Increased max width for larger slots */
        margin: 30px auto;
        text-align: center;
    }

    .video-events h2 {
        font-size: 2em;
        margin-bottom: 20px;
    }

    .video-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr); /* 3 videos per row */
        gap: 50px; /* Spacing between boxes */
        justify-content: center;
    }

    @media (max-width: 1024px) {
        .video-container {
            grid-template-columns: repeat(2, 1fr); /* 2 videos per row on medium screens */
        }
    }

    @media (max-width: 768px) {
        .video-container {
            grid-template-columns: 1fr; /* 1 video per row on small screens */
        }
    }

    .video-slot {
        background-color: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s, box-shadow 0.3s, background-color 0.3s;
        cursor: pointer;
    }

    .video-slot:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        background-color: var(--random-color);
    }

    .video-slot iframe {
        width: 100%;
        height: 280px; /* Increased height for a larger display */
    }

    .video-slot p {
        margin: 10px;
        font-size: 1.2em; /* Increased font size */
        color: #333;
        font-weight: bold;
    }

    </style>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const slots = document.querySelectorAll('.video-slot');
            const colors = ['#0081C8', '#FCB131', '#000000', '#00A651', '#EE334E']; // Olympic colors

            slots.forEach(slot => {
                slot.addEventListener('mouseover', () => {
                    const randomColor = colors[Math.floor(Math.random() * colors.length)];
                    slot.style.setProperty('--random-color', randomColor);
                });

                slot.addEventListener('click', () => {
                    const iframe = slot.querySelector('iframe');
                    if (iframe) {
                        window.open(iframe.src, '_blank'); // Open video in a new tab
                    }
                });
            });
        });
    </script>
</head>
<body>
    <div class="video-events">
        <div class="video-container">
            <?php foreach ($videos as $video): ?>
                <?php
                $videoId = $video['id']['videoId'];
                $videoTitle = htmlspecialchars($video['snippet']['title']);
                ?>
                <div class="video-slot">
                    <iframe src="https://www.youtube.com/embed/<?= $videoId ?>?start=2"
                            title="<?= $videoTitle ?>"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen></iframe>
                    <p><?= $videoTitle ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<!-- Code injected by live-server -->
<script>
	// <![CDATA[  <-- For SVG support
	if ('WebSocket' in window) {
		(function () {
			function refreshCSS() {
				var sheets = [].slice.call(document.getElementsByTagName("link"));
				var head = document.getElementsByTagName("head")[0];
				for (var i = 0; i < sheets.length; ++i) {
					var elem = sheets[i];
					var parent = elem.parentElement || head;
					parent.removeChild(elem);
					var rel = elem.rel;
					if (elem.href && typeof rel != "string" || rel.length == 0 || rel.toLowerCase() == "stylesheet") {
						var url = elem.href.replace(/(&|\?)_cacheOverride=\d+/, '');
						elem.href = url + (url.indexOf('?') >= 0 ? '&' : '?') + '_cacheOverride=' + (new Date().valueOf());
					}
					parent.appendChild(elem);
				}
			}
			var protocol = window.location.protocol === 'http:' ? 'ws://' : 'wss://';
			var address = protocol + window.location.host + window.location.pathname + '/ws';
			var socket = new WebSocket(address);
			socket.onmessage = function (msg) {
				if (msg.data == 'reload') window.location.reload();
				else if (msg.data == 'refreshcss') refreshCSS();
			};
			if (sessionStorage && !sessionStorage.getItem('IsThisFirstTime_Log_From_LiveServer')) {
				console.log('Live reload enabled.');
				sessionStorage.setItem('IsThisFirstTime_Log_From_LiveServer', true);
			}
		})();
	}
	else {
		console.error('Upgrade your browser. This Browser is NOT supported WebSocket for Live-Reloading.');
	}
	// ]]>
</script>
</body>
</html>
