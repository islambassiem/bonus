<?php
require_once '../vendor/autoload.php';
require_once __DIR__ . '/mail.php';

session_start();

$message = $_SESSION['message'] ?? '';
$rowCount = $_SESSION['rowCount'] ?? 0;
unset($_SESSION['message'], $_SESSION['rowCount']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['csv_file'])) {
        $message = "No file uploaded.";
    } else {
        $file = $_FILES['csv_file'];
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $message = "Upload failed.";
        } else {
            $inputHandle = fopen($file['tmp_name'], 'r');
            if ($inputHandle === false) {
                $message = "Unable to open uploaded file.";
            } else {
                $outputHandle = fopen('processed.csv', 'w');

                if ($outputHandle === false) {
                    $message = "Unable to create output file.";
                } else {
                    fgetcsv($inputHandle);
                    fputcsv($outputHandle, ['empid', 'name', 'email', 'amount']);
                    while (($row = fgetcsv($inputHandle)) !== false) {
                        fputcsv($outputHandle, $row);
                        sendMail($row[2], $row[1], $row[0], $row[3]);
                        $rowCount++;
                    }
                    fclose($outputHandle);
                    $message = "Successfully processed {$rowCount} emails.";
                }
                fclose($inputHandle);
            }
        }
    }
    $_SESSION['message'] = $message;
    $_SESSION['rowCount'] = $rowCount;
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bonus Email</title>

    <!-- TailwindCSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-6">

    <div class="bg-white shadow-xl rounded-2xl p-8 w-full max-w-xl">

        <h1 class="text-3xl font-bold mb-6 text-gray-800 text-center">
            CSV file email sender
        </h1>

        <?php if(file_exists('processed.csv')): ?>
            <div class="mb-6 p-4 rounded-lg bg-green-100 text-green-800">
                <p class="text-lg font-semibold">
                    Successfully sent <span class="font-bold"><?= $rowCount ?></span> emails.
                </p>
            </div>
        <?php endif; ?>

        <?php if(!file_exists('processed.csv')): ?>
            <!-- Upload Form -->
            <form
                method="POST"
                enctype="multipart/form-data"
                class="space-y-6"
                id="uploadForm"
            >

                <div>
                    <input
                        id="inputFile"
                        type="file"
                        name="csv_file"
                        accept=".csv"
                        required
                        class="block w-full text-sm text-gray-700
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-lg file:border-0
                            file:text-sm file:font-semibold
                            file:bg-blue-600 file:text-white
                            hover:file:bg-blue-700
                            cursor-pointer"
                    >
                </div>

                <button
                    type="submit"
                    id="submitBtn"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition flex items-center justify-center gap-2"
                >
                    <span id="btnText">Send</span>

                    <svg
                        id="spinner"
                        class="hidden animate-spin h-5 w-5 text-white"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <circle
                            class="opacity-25"
                            cx="12"
                            cy="12"
                            r="10"
                            stroke="currentColor"
                            stroke-width="4"
                        ></circle>

                        <path
                            class="opacity-75"
                            fill="currentColor"
                            d="M4 12a8 8 0 018-8v8H4z"
                        ></path>
                    </svg>

                </button>

            </form>

        <?php endif; ?>

    </div>
    <script>
        const form = document.getElementById('uploadForm');
        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');
        const spinner = document.getElementById('spinner');
        const inputFile = document.getElementById('inputFile');

        form.addEventListener('submit', () => {
            submitBtn.disabled = true;
            submitBtn.classList.remove('hover:bg-blue-700');
            submitBtn.classList.add('bg-blue-400', 'cursor-not-allowed');
            btnText.textContent = `Please do not close the window or refresh the page...`;
            spinner.classList.remove('hidden');
            inputFile.classList.add('hidden');
        });
    </script>
</body>
</html>