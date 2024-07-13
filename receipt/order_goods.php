<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ORDER FORM</title>
    <link rel="stylesheet" href="styles.css">

<style>
/* Basic styling for the layout */
body {
    font-family: Arial, sans-serif;
    
    background-color: #f0f0f0;	
}

header {
    background-color: #333;
    color: #fff;
    text-align: center;
    padding: 1rem 0;
}


.vendor-section select {
    padding: 10px;
    font-size: 1rem;
    border: 1px solid #ccc;
    border-radius: 5px;
    width: 200px;
}
.item-section select {
    padding: 10px;
    font-size: 1rem;
    border: 1px solid #ccc;
    border-radius: 5px;
    width: 200px;
}

.date-selector {
  background-color: #fff;
  border: 1px solid #ccc;
  padding: 20px;
  
  
}
.shirt-form {
        max-width: 600px;
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        margin: 0 auto;
    }
    .shirt-option {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    .shirt-option label {
        font-weight: bold;
        margin-right: 10px;
    }
    .shirt-option select, .shirt-option input[type="number"] {
        flex: 1;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
    }
    .add-more, .delete-option {
        background-color: #4CAF50;
        color: white;
        padding: 8px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        margin-left: 10px;
    }
    .add-more:hover {
        background-color: #45a049;
    }label {
  font-weight: bold;
  display: block;
  margin-bottom: 10px;
}

input[type="date"] {
  padding: 8px;
  font-size: 16px;
 
  box-sizing: border-box;
  border: 1px solid #ccc;
  border-radius: 3px;
}

</style>
</head>
<body>
    <header>
        <h1>ORDER FORM</h1>
	
    </header>

    <main>
<br></br>
        <section class="vendor-section">
            <label for="vendor">Select a Vendor:</label>
            <select id="vendor" name="vendor">
                <option value="vendor1">Vendor 1</option>
                <option value="vendor2">Vendor 2</option>
                <option value="vendor3">Vendor 3</option>
                <option value="vendor4">Vendor 4</option>
            </select>
        </section>
    </main>
 <main>
        <section class="item-section">
            <label for="item">Select an item:</label>
            <select id="item" name="item">
                <option value="i1">i1</option>
                <option value="i2">i2</option>
                <option value="i3">i3</option>
                <option value="i4">i4</option>
            </select>
        </section>
    </main>
<div class="shirt-form">
    <div class="shirt-option">
        <label for="color">Color:</label>
        <select class="color" name="color">
            <option value="red">Red</option>
            <option value="blue">Blue</option>
            <option value="green">Green</option>
            <option value="black">Black</option>
            <!-- Add more color options as needed -->
        </select>

        <label for="size">Size:</label>
        <select class="size" name="size">
            <option value="small">Small</option>
            <option value="medium">Medium</option>
            <option value="large">Large</option>
            <option value="xl">Extra Large</option>
            <!-- Add more size options as needed -->
        </select>

        <label for="quantity">Quantity:</label>
        <input type="number" class="quantity" name="quantity" min="1" value="1">

        <div class="option-actions">
            <button class="delete-option">Delete</button>
        </div>
    </div>

    <button class="add-more">Add More</button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const formContainer = document.querySelector('.shirt-form');
    
    // Function to handle adding a new shirt option
    function addShirtOption() {
        const lastShirtOption = formContainer.lastElementChild.previousElementSibling;
        const newShirtOption = lastShirtOption.cloneNode(true);
        formContainer.insertBefore(newShirtOption, formContainer.lastElementChild);
        
        // Attach event listener to the new delete button
        const deleteButton = newShirtOption.querySelector('.delete-option');
        deleteButton.addEventListener('click', deleteShirtOption);
    }
    
    // Function to handle deleting a shirt option
    function deleteShirtOption(event) {
        const shirtOption = event.target.closest('.shirt-option');
        if (shirtOption && formContainer.childElementCount > 1) {
            formContainer.removeChild(shirtOption);
        }
    }
    
    // Initial event listener for the "Add More" button
    const addButton = document.querySelector('.add-more');
    addButton.addEventListener('click', addShirtOption);
    
    // Initial event listener for the first delete button
    const deleteButtons = document.querySelectorAll('.delete-option');
    deleteButtons.forEach(button => {
        button.addEventListener('click', deleteShirtOption);
    });
});
</script>

 <class="date-selector">
  <label for="date">Select a Date:</label>
  <input type="date" id="date" name="date">
<br></br>
<main>
        <section class="item-section">
            <label for="item">Means of transport:</label>
            <select id="item" name="item">
                <option value="By Road">By Road</option>
                <option value="By Air">By Air</option>
            </select>
        </section>
    </main>
<br></br>
            <button type="submit">Add</button>
        </form>
</main>
</body>
</html>
