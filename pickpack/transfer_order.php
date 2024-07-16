<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Form</title>
</head>
<body>
    <h1>Order Form</h1>
    <form id="orderForm" action="process_order.php" method="POST">
        <div id="articles-container">
            <!-- Initial article set (can be removed if not needed) -->
            <div class="article">
                <input type="text" placeholder="Article" required>
                <input type="text" placeholder="Size" required>
                <input type="number" placeholder="Quantity" required>
            </div>
        </div>

        <label for="customerName">Customer Name:</label>
        <input type="text" id="customerName" name="customerName" required><br><br>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" required><br><br>

        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <!-- Input for uploading Excel file -->
        <label for="excelFile">Upload Excel Sheet:</label>
        <input type="file" id="excelFile" name="excelFile" accept=".xls,.xlsx"><br><br>

        <button type="button" onclick="uploadExcel()">Upload & Populate</button>
        <button type="submit">Submit Order</button>
    </form>

    <!-- JavaScript to handle file upload and form population -->
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

                data.articles.forEach(article => {
                    let div = document.createElement('div');
                    div.classList.add('article');
                    div.innerHTML = `
                        <input type="text" placeholder="Article" value="${article.article}" required>
                        <input type="text" placeholder="Size" value="${article.size}" required>
                        <input type="number" placeholder="Quantity" value="${article.quantity}" required>
                    `;
                    articlesContainer.appendChild(div);
                });
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>
