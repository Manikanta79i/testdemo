<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Action Page Generator</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h2 {
            text-align: center;
        }

        form {
            margin-top: 20px;
        }

        button {
            padding: 10px;
            font-size: 16px;
            cursor: pointer;
        }

        textarea {
            width: 100%;
            height: 200px;
            margin-top: 10px;
        }

        #download-link {
            display: none;
            margin-top: 10px;
            color: blue;
            text-decoration: underline;
            cursor: pointer;
        }
    </style>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

</head>
<body>
    <div class="container">
        <h2 class="text-center">Code Generator</h2>

        <form id="form-generator" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="needs-validation" novalidate>
            <div class="form-group">
                <label for="form-name">Form Name:</label>
                <input type="text" id="form-name" name="form-name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="form-action">Form Action:</label>
                <input type="text" id="form-action" name="form-action" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="form-fields">Form Fields (comma-separated):</label>
                <input type="text" id="form-fields" name="form-fields" class="form-control" required>
            </div>

            <button type="button" onclick="generateCode()" class="btn btn-primary">Generate PHP Code</button>
        </form>

        <textarea id="generated-code" readonly class="form-control mt-3" rows="10" ></textarea>

        <a id="download-link" style="display: none" class="mt-3 btn btn-success">Download PHP Code</a>
    </div>

    <script>
       function generateCode() {
    const formName = document.getElementById('form-name').value;
    const formAction = document.getElementById('form-action').value;
    const formFields = document.getElementById('form-fields').value.split(',');

    // Generate PHP code
    const phpCode = `<?php\nif ($_SERVER["REQUEST_METHOD"] === "POST") {\n${generateFormProcessingCode(formFields)}\n}\n?>`;

    // Display generated code in textarea
    document.getElementById('generated-code').value = phpCode;

    // Display download link
    const downloadLink = document.getElementById('download-link');
    downloadLink.style.display = 'block';
    downloadLink.href = 'data:text/plain;charset=utf-8,' + encodeURIComponent(phpCode);
    downloadLink.download = `${formName}_action.php`;

    // Send form data via AJAX to PHP script for email sending
    sendFormDataToEmail(formFields);

    // No need to redirect in JavaScript
}

function generateFormProcessingCode(fields) {
    let code = '';
    for (const field of fields) {
        const fieldName = trimQuotes(field.trim());
        code += `$${fieldName} = $_POST['${fieldName}'];\n`;
    }
     code += '$headers = \'From: webmaster@example.com\'; // Replace with a valid sender email address\n\n';
    code += '// Send the email\n';
    code += 'if (mail($to, $subject, $messageBody, $headers)) {\n';
    code += '    echo \'Email sent successfully.\';\n';
    code += '} else {\n';
    code += '    echo \'Error sending email. Please try again later.\';\n';
    code += '}';
    return code;
}

function trimQuotes(str) {
    return str.replace(/^['"]|['"]$/g, '');
}

function sendFormDataToEmail(fields) {
    const xhr = new XMLHttpRequest();
    const url = 'email_handler.php'; // Replace with the actual path to your email handling PHP script

    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    const formData = fields.map(field => {
        const fieldName = trimQuotes(field.trim());
        return `${encodeURIComponent(fieldName)}=${encodeURIComponent(document.getElementById(fieldName).value)}`;
    }).join('&');

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                console.log('Email sent successfully.');
            } else {
                console.error('Error sending email. Please try again later.');
            }
        }
    };

    xhr.send(formData);
}


    </script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
