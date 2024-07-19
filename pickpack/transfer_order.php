<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 0;
        }

        .container {
            background-color: #ffffff;
            padding: 20px;
            border: 2px solid #000;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 600px;
        }

        h1 {
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="email"],
        input[type="number"] {
            width: calc(25% - 12px); /* Adjusted width to fit nicely */
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button {
            padding: 10px;
            margin-top: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #45a049;
        }

        .article {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            position: relative; /* Ensure position context for absolute button */
        }

        .article input {
            width: calc(25% - 12px); /* Adjusted width to match input fields */
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .delete-btn {
            background-color: #f44336;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 8px;
            cursor: pointer;
            width: 50px; /* Fixed width for delete button */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Order Form</h1>
        <form id="orderForm" action="process_order.php" method="POST">
            <div id="articles-container">
                <!-- Initial article set (cannot be removed) -->
                <div class="article">
                    <input type="text" placeholder="Article" required>
                    <input type="text" placeholder="Size" required>
                    <input type="number" placeholder="Quantity" required>
                    <!-- No delete button for the first article -->
                </div>
            </div>
            <button type="button" onclick="addMoreArticles()">Add More</button>

            <label for="customerName">Customer Name:</label>
            <input type="text" id="customerName" name="customerName" required>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>

            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <button type="submit">Submit Order</button>
        </form>
    </div>

    <!-- JavaScript to handle file upload, form population, and dynamic row deletion -->
    <script>
        function uploadExcel() {
            let fileInput = document.getElementById('excelFile');
            let file = fileInput.files[0];
            let formData = new FormData();
            formData.append('excelFile', file);

            fetch('process_excel.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('customerName').value = data.customerName;
                document.getElementById('address').value = data.address;
                document.getElementById('phone').value = data.phone;
                document.getElementById('email').value = data.email;

                let articlesContainer = document.getElementById('articles-container');
                articlesContainer.innerHTML = ''; // Clear previous entries

                data.articles.forEach((article, index) => {
                    let div = createArticleDiv(article, index === 0);
                    articlesContainer.appendChild(div);
                });
            })
            .catch(error => console.error('Error:', error));
        }

        function addMoreArticles() {
            let articlesContainer = document.getElementById('articles-container');
            let div = createArticleDiv({ article: '', size: '', quantity: '' }, false);
            articlesContainer.appendChild(div);
        }

        function createArticleDiv(article, isFirst) {
            let div = document.createElement('div');
            div.classList.add('article');
            div.innerHTML = `
                <input type="text" placeholder="Article" value="${article.article}" required>
                <input type="text" placeholder="Size" value="${article.size}" required>
                <input type="number" placeholder="Quantity" value="${article.quantity}" required>
            `;
            if (!isFirst) {
                div.innerHTML += `<button type="button" class="delete-btn" onclick="deleteArticle(this)">Delete</button>`;
            }
            return div;
        }

        function deleteArticle(button) {
            let articleDiv = button.parentElement;
            articleDiv.remove();
        }
    </script>
</body>
</html>
