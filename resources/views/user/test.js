<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Searchable Select with List</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 20px;
    }

    .dropdown {
      position: relative;
      width: 300px;
    }

    #searchInput {
      width: 100%;
      padding: 10px;
      box-sizing: border-box;
    }

    .dropdown-list {
      position: absolute;
      top: 100%;
      left: 0;
      width: 100%;
      max-height: 200px;
      overflow-y: auto;
      border: 1px solid #ccc;
      border-top: none;
      background: white;
      z-index: 100;
      display: none;
    }

    .dropdown-list div {
      padding: 10px;
      cursor: pointer;
    }

    .dropdown-list div:hover {
      background-color: #f0f0f0;
    }

    #selectedItems {
      margin-top: 20px;
    }

    .selected-item {
      background-color: #e0e0ff;
      padding: 5px 10px;
      margin: 5px 0;
      border-radius: 4px;
      display: inline-block;
    }
  </style>
</head>
<body>

  <h2>Search and Select Items</h2>

  <div class="dropdown">
    <input type="text" id="searchInput" placeholder="Search items...">
    <div class="dropdown-list" id="dropdownList"></div>
  </div>

  <div id="selectedItems">
    <h3>Selected Items:</h3>
  </div>

  <script>
    const items = [
      "Apple", "Banana", "Orange", "Mango", "Pineapple",
      "Strawberry", "Grapes", "Peach", "Watermelon", "Blueberry"
    ];

    const searchInput = document.getElementById('searchInput');
    const dropdownList = document.getElementById('dropdownList');
    const selectedItems = document.getElementById('selectedItems');

    let selected = [];

    searchInput.addEventListener('input', () => {
      const keyword = searchInput.value.toLowerCase();
      dropdownList.innerHTML = '';

      if (keyword.trim() === '') {
        dropdownList.style.display = 'none';
        return;
      }

      const filteredItems = items.filter(item =>
        item.toLowerCase().includes(keyword) && !selected.includes(item)
      );

      if (filteredItems.length === 0) {
        dropdownList.style.display = 'none';
        return;
      }

      filteredItems.forEach(item => {
        const option = document.createElement('div');
        option.textContent = item;
        option.addEventListener('click', () => {
          selected.push(item);
          addToList(item);
          searchInput.value = '';
          dropdownList.style.display = 'none';
        });
        dropdownList.appendChild(option);
      });

      dropdownList.style.display = 'block';
    });

    function addToList(item) {
      const tag = document.createElement('span');
      tag.className = 'selected-item';
      tag.textContent = item;
      selectedItems.appendChild(tag);
    }

    // Hide dropdown when clicking outside
    document.addEventListener('click', function(event) {
      if (!event.target.closest('.dropdown')) {
        dropdownList.style.display = 'none';
      }
    });
  </script>

</body>
</html>
