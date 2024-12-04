<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Center</title>
    <link href="https://fonts.googleapis.com/css2?family=Gotu&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Gotu', sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
            color: #000;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h1 {
            font-size: 2.5em;
            text-align: center;
            color: white;
            background: linear-gradient(to right, #0072ce, #fbb917, #000, #34a853, #ea4335);
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            width: 100%;
        }

        #carousel-container {
            width: 90%;
            overflow: hidden;
            position: relative;
        }

        #news-container {
            display: flex;
            gap: 20px;
            transition: transform 0.8s ease-in-out;
        }

        .article {
            width: 300px; /* Adjusted for sufficient width */
            height: 400px;
            flex-shrink: 0;
            padding: 10px;
            border-radius: 15px;
            background-color: #fff;
            border: 1px solid #ddd;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            justify-content: flex-start; /* Ensure title, image, and description fit well */
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;
        }

        .article:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            background-color: var(--random-color, #fff);
        }

        .article h2 {
            font-size: 1.2em;
            margin: 10px 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap; /* Prevent long titles from breaking layout */
        }

        .article img {
            width: 100%;
            height: 180px; /* Ensure images fit visually */
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .article p {
            font-size: 0.9em;
            margin-bottom: 10px;
            line-height: 1.4; /* Ensure description is easy to read */
            max-height: 60px; /* Limit description height */
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .read-more-btn {
            display: inline-block;
            padding: 5px 10px;
            background-color: #0072ce;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .read-more-btn:hover {
            background-color: #0056a3;
            transform: scale(1.05);
        }

        /* Scrollbar styling */
        #news-container::-webkit-scrollbar {
            display: none; /* Hide scrollbar */
        }
    </style>
</head>
<body>
    <h1>Top News</h1>
    <div id="carousel-container">
        <div id="news-container"></div>
    </div>
    <script>
        const newsApiKey = '4d70f184aa07465bbfd1ad9912e95e78'; // Replace with your News API key
        const newsApiUrl = `https://newsapi.org/v2/everything?q=olympics&apiKey=4d70f184aa07465bbfd1ad9912e95e78`;

        // Define random Olympic colors
        const olympicColors = ['#0072ce', '#fbb917', '#000', '#34a853', '#ea4335'];

        let currentIndex = 0;

        async function fetchNews() {
            try {
                const response = await fetch(newsApiUrl);
                const data = await response.json();
                displayNews(data.articles);
                startCarousel();
            } catch (error) {
                console.error(`Error fetching news: ${error.message}`);
                document.getElementById('news-container').innerText = "Failed to load news.";
            }
        }

        function displayNews(articles) {
            const container = document.getElementById('news-container');
            articles.forEach(article => {
                const articleElement = document.createElement('div');
                articleElement.className = 'article';
                articleElement.style.setProperty('--random-color', getRandomColor());
                articleElement.innerHTML = `
                    <h2>${article.title}</h2>
                    <img src="${article.urlToImage || 'https://via.placeholder.com/300'}" alt="${article.title}">
                    <p>${article.description || "No description available."}</p>
                    <a href="${article.url}" target="_blank" class="read-more-btn">Read more</a>
                `;
                container.appendChild(articleElement);
            });
        }

        function getRandomColor() {
            return olympicColors[Math.floor(Math.random() * olympicColors.length)];
        }

        function startCarousel() {
            const container = document.getElementById('news-container');
            const articles = document.querySelectorAll('.article');
            const totalArticles = articles.length;
            const visibleArticles = Math.floor(container.offsetWidth / 320); // Approx box width + gap

            setInterval(() => {
                currentIndex = (currentIndex + visibleArticles) % totalArticles;
                const offset = -currentIndex * 320; // Approx width + gap
                container.style.transform = `translateX(${offset}px)`;
            }, 6000);
        }

        // Fetch news on page load
        fetchNews();
    </script>
</body>
</html>
