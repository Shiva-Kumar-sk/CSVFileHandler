<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Csv File Upload</title>
</head>

<body>

    <?php

    function isValidDate($date)
    {
        if (preg_match("/^(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/(19|20)\d\d$/", $date)) {
        } else {
            return "Invalid date format Please use the mm/dd/yyyy format";
        }
    }

    $showDownloadLink = false;

    if (isset($_POST['submit'])) {
        if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {

            $uploadDir = 'uploads/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $uploadFile = $uploadDir . basename($_FILES['file']['name']);

            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {


                $assoc_array = [];
                if (($handle = fopen($uploadFile, "r")) !== false) {                 // open for reading
                    if (($data = fgetcsv($handle, 1000, ",")) !== false) {         // extract header data
                        $keys = $data;                                             // save as keys
                    }
                    while (($data = fgetcsv($handle, 1000, ",")) !== false) {      // loop remaining rows of data
                        $assoc_array[] = array_combine($keys, $data);              // push associative subarrays
                    }
                    fclose($handle);                                               // close when done
                }
                // echo "<pre>";
                // var_export($assoc_array);                                      // print to screen
                // echo "</pre>";





                foreach ($assoc_array as $index => $record) {
                    $productionDateErr = '';
                    $VisitDateErr = '';
                    $agencyNameErr = '';
                    $employeeIDErr = '';
                    $employeeNameErr = '';




                    if (!empty($record['ProductionDate'])) {
                        $notValidDate = isValidDate($record['ProductionDate']);
                        if ($notValidDate) {
                            $productionDateErr = "error on row number " . $index . " " . isValidDate($record['ProductionDate']) . " for Production Date <br>";
                        }
                    } else {
                        $productionDateErr = 'error on row number ' . $index . " ProductionDate  is required" . "<br>";
                    }


                    if (!empty($record['VisitDate'])) {
                        $notValidDate = isValidDate($record['VisitDate']);
                        if ($notValidDate) {
                            $VisitDateErr = "error on row number " . $index . " " . isValidDate($record['VisitDate']) . " for Visit Date <br>";
                        }
                    } else {
                        $VisitDateErr = 'error on row number ' . $index . " VisitDate  is required" . "<br>";
                    }




                    if (!empty($record['AgencyName'])) {
                    } else {
                        $agencyNameErr = 'error on row number ' . $index . " AgencyName  is required" . "<br>";
                    }


                    if (!empty($record['EmployeeID'])) {
                    } else {
                        $employeeIDErr = 'error on row number ' . $index . " EmployeeID  is required" . "<br>";
                    }

                    if (!empty($record['EmployeeName'])) {
                    } else {
                        $employeeNameErr = 'error on row number ' . $index . " EmployeeName  is required" . "<br>";
                    }




                    $errors = $productionDateErr . $VisitDateErr . $agencyNameErr . $employeeIDErr . $employeeNameErr;

                    // Write to a new file
                    file_put_contents("errors.txt", $errors);

                    // Append to an existing file
                    file_put_contents("errors.txt", $errors . PHP_EOL, FILE_APPEND);
                    $showDownloadLink = true;

                    // methods will be here for database work
                }
            } else {
                echo "File upload failed.";
            }
        } else {
            echo "No file uploaded or upload error.";
        }
    }


    ?>








    <div class="container">
        <div class="main bg-body-secondary p-5 mt-5">

            <?php if ($showDownloadLink) : ?>
                <a href="errors.txt" download class="btn btn-info">Download Errors</a>
            <?php endif; ?>
            <h1 class="mt-4 d-flex justify-content-md-center">Upload</h1>
            <form action="" class="mt-3" method="post" enctype="multipart/form-data">

                <div class="form-group">
                    <label for="image">Csv File:</label>
                    <input type="file" class="form-control" id="file" name="file" accept=".csv, text/csv">
                    <span class=" text-danger"></span>
                </div>

                <div class="text-center mt-2">
                    <button type="submit" name='submit' class="btn btn-success">Upload</button>

                </div>
            </form>
        </div>
    </div>




</body>

</html>