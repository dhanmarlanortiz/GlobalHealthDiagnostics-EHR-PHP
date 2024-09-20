<?php
$resultsAPE = getResultsAPE($id);
if (!empty($resultsAPE)) {
    foreach ($resultsAPE as $resAPE) {
        $examName = $resAPE['examName'];
        $fileName = $resAPE['fileName'];
        $medicalExaminationFK = $resAPE['medicalExaminationFK'];
        $src = base_url(false) . "/uploads/" . $fileName;

        $resultData = [
                        'filePath' => $src,
                        'examName' => $examName,
                        'organizationId' => $o,
                        'firstName' => $_POST['firstName'],
                        'middleName' => $_POST['middleName'],
                        'lastName' => $_POST['lastName']
                    ];
        $encodeResult = base64_encode(json_encode( $resultData ));
        echo '<li class="flex flex-col sm:flex-row justify-between gap-x-6 py-5">
                <div class="flex min-w-0 gap-x-4">
                    <div class="min-w-0 flex-auto">
                        <p class="text-sm font-semibold leading-6 text-gray-900">'. $examName . '</p>
                        <p class="mt-1 truncate text-xs leading-5 text-gray-500">' . $fileName . '</p>
                    </div>
                </div>
                <div class="shrink-0 mt-2 sm:mt-0 sm:flex sm:items-end">';
                    echo "<a href='" . base_url(false) . "/employeeViewResult-APE.php?pdf=$encodeResult' title='View' class='btn btn-sm bg-green-700 hover:bg-green-800'>
                            <svg xmlns='http://www.w3.org/2000/svg' fill='#fff' height='1em' viewBox='0 0 448 512'><path d='M32 32C14.3 32 0 46.3 0 64v96c0 17.7 14.3 32 32 32s32-14.3 32-32V96h64c17.7 0 32-14.3 32-32s-14.3-32-32-32H32zM64 352c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7 14.3 32 32 32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H64V352zM320 32c-17.7 0-32 14.3-32 32s14.3 32 32 32h64v64c0 17.7 14.3 32 32 32s32-14.3 32-32V64c0-17.7-14.3-32-32-32H320zM448 352c0-17.7-14.3-32-32-32s-32 14.3-32 32v64H320c-17.7 0-32 14.3-32 32s14.3 32 32 32h96c17.7 0 32-14.3 32-32V352z'/></svg>
                        </a>";
                    echo "<a href='{$src}' class='btn btn-sm ml-1 bg-sky-500 hover:bg-sky-600' title='Download' download>
                            <svg xmlns='http://www.w3.org/2000/svg' fill='#fff' height='1em' viewBox='0 0 512 512'><path d='M288 32c0-17.7-14.3-32-32-32s-32 14.3-32 32V274.7l-73.4-73.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l128 128c12.5 12.5 32.8 12.5 45.3 0l128-128c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L288 274.7V32zM64 352c-35.3 0-64 28.7-64 64v32c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V416c0-35.3-28.7-64-64-64H346.5l-45.3 45.3c-25 25-65.5 25-90.5 0L165.5 352H64zm368 56a24 24 0 1 1 0 48 24 24 0 1 1 0-48z'/></svg>
                        </a>";

                    if($role == 1 || $role == 3) {
                        echo "<a href='employeeDeleteResult-APE.php?fileName=$fileName&medicalExaminationFK=$medicalExaminationFK&APEFK=$id' title='delete' class='btn btn-sm ml-1 bg-red-500 hover:bg-red-600'>
                            <svg xmlns='http://www.w3.org/2000/svg' fill='#fff' height='1em' viewBox='0 0 448 512'><path d='M135.2 17.7C140.6 6.8 151.7 0 163.8 0H284.2c12.1 0 23.2 6.8 28.6 17.7L320 32h96c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 96 0 81.7 0 64S14.3 32 32 32h96l7.2-14.3zM32 128H416V448c0 35.3-28.7 64-64 64H96c-35.3 0-64-28.7-64-64V128zm96 64c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16z'/></svg>
                        </a>";
                    }
            echo '</div>';
        echo "</li>";
    }
}