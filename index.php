<!DOCTYPE html>
<html>
<head>
  <title>TRADESMAN Storage App</title>
  <style>
body {
  font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
  font-size: 18px;
  color: #333333;
  background-color: #ECECEC;
}

h1 {
  text-align: center;
  margin-top: 20px;
  font-size: 36px;
  color: #333333;
}

ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
  text-align: center;
  margin-bottom: 20px;
}

li {
  display: inline-block;
  margin-right: 10px;
}

a {
  text-decoration: none;
  color: #555555;
  padding: 5px 10px;
  background-color: #DDDDDD;
  border-radius: 4px;
}

a:hover {
  background-color: #CCCCCC;
}

.section {
  margin: 0 auto;
  width: 90%;
  border: 1px solid #555555;
  padding: 10px;
  margin-bottom: 20px;
  background-color: #F7F7F7;
  box-shadow: 0 0 10px 0 #BBBBBB;
}

.section h2 {
  margin-top: 0;
  font-size: 24px;
  color: #555555;
}

.section .input-fields {
  display: none;
  margin-bottom: 10px;
}

.section .expand-button {
  margin-bottom: 10px;
  color: #F7F7F7;
  background-color: #555555;
  border: none;
  padding: 5px 10px;
  border-radius: 4px;
  cursor: pointer;
}

.section .expand-button:hover {
  background-color: #888888;
}

table {
  max-width: 100%;
  margin: 0 auto;
}

table td,
table th {
  border: 1px solid #555555;
  padding: 8px;
  text-align: left;
}

table th {
  background-color: #ECECEC;
}

.hire-button {
  padding: 5px 10px;
  background-color: #FF5500;
  color: #F7F7F7;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.hire-button:hover {
  background-color: #FF7755;
}


  </style>
</head>
<body onload="loadTradesmanData()">
  <h1>Hire Tradesmen</h1>
  <ul>
    <li><a href="#source-tradesman">Source Tradesman</a></li>
  </ul>
  <div id="source-tradesman" class="section">
    <h2>Source Tradesman</h2>
    <button type="button" class="expand-button" onclick="toggleSection('source-tradesman')">Expand</button>
    <div class="input-fields">
      <input type="text" id="tradesmanName" placeholder="Name of the tradesman">
      <input type="text" id="tradesmanServices" placeholder="Services offered by the tradesman">
      <input type="text" id="tradesmanPaymentAmount" placeholder="Payment amount for the tradesman">
      <input type="text" id="tradesmanEmail" placeholder="Email of the tradesman">
      <input type="password" id="answer" placeholder="Answer to the question">
      <button type="button" onclick="trackSourceTradesmanInputs()">Track</button>
    </div>
    <table id="source-tradesman-table">
      <thead>
        <tr>
          <th>Name</th>
          <th style="width: 60%;">Services</th>
          <th>Payment Amount</th>
          <th>Email</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>

  <script>
    function toggleSection(sectionId) {
      var section = document.getElementById(sectionId);
      var inputFields = section.querySelector('.input-fields');
      var expandButton = section.querySelector('.expand-button');

      if (inputFields.style.display === 'none') {
        inputFields.style.display = 'block';
        expandButton.textContent = 'Collapse';
      } else {
        inputFields.style.display = 'none';
        expandButton.textContent = 'Expand';
      }
    }

    function loadTradesmanData() {
      var xhr = new XMLHttpRequest();
      xhr.open('GET', 'get_tradesman.php', true);
      xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
          var tradesmanData = JSON.parse(xhr.responseText);
          populateTradesmanTable(tradesmanData);
        }
      };
      xhr.send();
    }

    function populateTradesmanTable(tradesmanData) {
      var table = document.getElementById('source-tradesman-table').getElementsByTagName('tbody')[0];

      for (var i = 0; i < tradesmanData.length; i++) {
        var tradesman = tradesmanData[i];
        addTradesmanRow(tradesman.name, tradesman.services, tradesman.paymentAmount, tradesman.email);
      }
    }

    function addTradesmanRow(name, services, paymentAmount, email) {
      var table = document.getElementById('source-tradesman-table').getElementsByTagName('tbody')[0];

      var newRow = table.insertRow();
      var nameCell = newRow.insertCell(0);
      var servicesCell = newRow.insertCell(1);
      var paymentAmountCell = newRow.insertCell(2);
      var emailCell = newRow.insertCell(3);
      var actionCell = newRow.insertCell(4);

      nameCell.innerHTML = name;
      servicesCell.innerHTML = services;
      paymentAmountCell.innerHTML = paymentAmount;
      emailCell.innerHTML = '********';
      actionCell.innerHTML = '<button class="hire-button" onclick="hireTradesman(\'' + email + '\')">Hire Now</button>';
    }

    function trackSourceTradesmanInputs() {
      var tradesmanName = document.getElementById('tradesmanName').value;
      var tradesmanServices = document.getElementById('tradesmanServices').value;
      var tradesmanPaymentAmount = document.getElementById('tradesmanPaymentAmount').value;
      var tradesmanEmail = document.getElementById('tradesmanEmail').value;
      var answer = document.getElementById('answer').value;

      var table = document.getElementById('source-tradesman-table').getElementsByTagName('tbody')[0];

      var existingTradesman = findExistingTradesman(tradesmanName);

          if (existingTradesman) {
        updateTradesmanRow(existingTradesman, tradesmanServices, tradesmanPaymentAmount);
      } else {
        addTradesmanRow(tradesmanName, tradesmanServices, tradesmanPaymentAmount, tradesmanEmail);
      }

      document.getElementById('tradesmanName').value = '';
      document.getElementById('tradesmanServices').value = '';
      document.getElementById('tradesmanPaymentAmount').value = '';
      document.getElementById('tradesmanEmail').value = '';
      document.getElementById('answer').value = '';

      var tradesmanData = {
        name: tradesmanName,
        services: tradesmanServices,
        paymentAmount: tradesmanPaymentAmount,
        email: tradesmanEmail
      };
      saveTradesmanToDatabase(tradesmanData, answer);
    }

    function findExistingTradesman(name) {
      var tableRows = document.getElementById('source-tradesman-table').getElementsByTagName('tbody')[0].getElementsByTagName('tr');

      for (var i = 0; i < tableRows.length; i++) {
        var row = tableRows[i];
        var rowName = row.cells[0].innerHTML;

        if (rowName === name) {
          return row;
        }
      }

      return null;
    }

    function updateTradesmanRow(row, services, paymentAmount) {
      row.cells[1].innerHTML = services;
      row.cells[2].innerHTML = paymentAmount;
    }

    function saveTradesmanToDatabase(tradesmanData, answer) {
      if (answer !== '4') {
        tradesmanData.email = '*******';
      }

      var xhr = new XMLHttpRequest();
      xhr.open('POST', 'save_tradesman.php', true);
      xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
      xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
          console.log(xhr.responseText);
        }
      };

      var params = 'name=' + encodeURIComponent(tradesmanData.name) +
                   '&services=' + encodeURIComponent(tradesmanData.services) +
                   '&paymentAmount=' + encodeURIComponent(tradesmanData.paymentAmount) +
                   '&email=' + encodeURIComponent(tradesmanData.email);

      xhr.send(params);
    }



  function hireTradesman() {
    // Get the user input
    var answer = parseInt(prompt("What is 2 + 2?"));

    // Validate the answer
    if (answer !== 4) {
      alert("Incorrect answer. Please try again.");
      return;
    }

    // Retrieve the necessary data
    var email = document.getElementById('email').value;
    var amount = document.getElementById('amount').value;
    var services = document.getElementById('services').value;
    var tradesmanId = document.getElementById('tradesmanId').value;

    // Create an AJAX request
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'get_tradesman_email.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    // Handle the AJAX response
    xhr.onreadystatechange = function() {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status === 200) {
          var response = JSON.parse(xhr.responseText);

          // Check if the email was retrieved successfully
          if (response.success) {
            // Assign the retrieved email to the 'email' variable
            email = response.email;

            // Create a form dynamically
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = 'send_email.php';

            // Create hidden input fields for the data
            var emailInput = document.createElement('input');
            emailInput.type = 'hidden';
            emailInput.name = 'email';
            emailInput.value = email;

            var amountInput = document.createElement('input');
            amountInput.type = 'hidden';
            amountInput.name = 'amount';
            amountInput.value = amount;

            var servicesInput = document.createElement('input');
            servicesInput.type = 'hidden';
            servicesInput.name = 'services';
            servicesInput.value = services;

            var tradesmanIdInput = document.createElement('input');
            tradesmanIdInput.type = 'hidden';
            tradesmanIdInput.name = 'tradesmanId';
            tradesmanIdInput.value = tradesmanId;

            // Append the input fields to the form
            form.appendChild(emailInput);
            form.appendChild(amountInput);
            form.appendChild(servicesInput);
            form.appendChild(tradesmanIdInput);

            // Append the form to the document body and submit it
            document.body.appendChild(form);
            form.submit();
          } else {
            alert('Failed to retrieve the tradesman email.');
          }
        } else {
          alert('An error occurred while making the AJAX request.');
        }
      }
    };

    // Send the AJAX request with the tradesmanId
    xhr.send('tradesmanId=' + tradesmanId);
  }



  </script>
</body>
</html>

