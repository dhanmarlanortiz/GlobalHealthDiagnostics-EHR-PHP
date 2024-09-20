<?php
echo '<li class="flex flex-col sm:flex-row justify-between gap-x-6 py-5">
<div class="flex min-w-0 gap-x-4">
    <div class="min-w-0 flex-auto">
        <p class="text-sm font-semibold leading-6 text-gray-900">Clinical Chemistry</p>
        <p class="text-xs leading-5 text-gray-500 flex gap-x-4">';
            if(($clinicalChemistry)) {
                echo '<span class="flex gap-x-1 items-center">';
                echo    '<svg xmlns="http://www.w3.org/2000/svg" fill="rgb(107, 114, 128)" height="12" width="12" viewBox="0 0 448 512"><path d="M96 32V64H48C21.5 64 0 85.5 0 112v48H448V112c0-26.5-21.5-48-48-48H352V32c0-17.7-14.3-32-32-32s-32 14.3-32 32V64H160V32c0-17.7-14.3-32-32-32S96 14.3 96 32zM448 192H0V464c0 26.5 21.5 48 48 48H400c26.5 0 48-21.5 48-48V192z"/></svg>';
                echo    date("M d, Y", strtotime($clinicalChemistry['clinicchem_date']));
                echo '</span>';
            } else {
                echo 'Not Available';
            }
echo            '</p>';
echo '      </div>
</div>
<div class="shrink-0 mt-2 sm:mt-0 sm:flex sm:items-end">';
    if(($clinicalChemistry)) {
        $clinicalChemistryData = [
            'href' => '/reports/clinical-chemistry.php?id=' . $id,
            'organizationId' => $o,
            'firstName' => $_POST['firstName'],
            'middleName' => $_POST['middleName'],
            'lastName' => $_POST['lastName']
        ];
        $encodeClinicalChemistry = base64_encode(json_encode( $clinicalChemistryData ));

        echo "<a href='" . base_url(false) . "/employeeViewClinicalChemistry-APE.php?q=$encodeClinicalChemistry' title='View' class='btn btn-sm bg-green-700 hover:bg-green-800'>
                <svg xmlns='http://www.w3.org/2000/svg' fill='#fff' height='1em' viewBox='0 0 448 512'><path d='M32 32C14.3 32 0 46.3 0 64v96c0 17.7 14.3 32 32 32s32-14.3 32-32V96h64c17.7 0 32-14.3 32-32s-14.3-32-32-32H32zM64 352c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7 14.3 32 32 32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H64V352zM320 32c-17.7 0-32 14.3-32 32s14.3 32 32 32h64v64c0 17.7 14.3 32 32 32s32-14.3 32-32V64c0-17.7-14.3-32-32-32H320zM448 352c0-17.7-14.3-32-32-32s-32 14.3-32 32v64H320c-17.7 0-32 14.3-32 32s14.3 32 32 32h96c17.7 0 32-14.3 32-32V352z'/></svg>
            </a>";
        echo "<a href='" . base_url(false) . "/reports/clinical-chemistry.php?id=" . $id . "' title='Download' class='btn btn-sm ml-1 bg-sky-400 hover:bg-sky-500' download>
                <svg xmlns='http://www.w3.org/2000/svg' fill='#fff' height='1em' viewBox='0 0 512 512'><path d='M288 32c0-17.7-14.3-32-32-32s-32 14.3-32 32V274.7l-73.4-73.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l128 128c12.5 12.5 32.8 12.5 45.3 0l128-128c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L288 274.7V32zM64 352c-35.3 0-64 28.7-64 64v32c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V416c0-35.3-28.7-64-64-64H346.5l-45.3 45.3c-25 25-65.5 25-90.5 0L165.5 352H64zm368 56a24 24 0 1 1 0 48 24 24 0 1 1 0-48z'/></svg>
            </a>";

        if($role == 1 || $role == 3) {
            echo "<button type='button' onClick='clinicalChemistryModal.showModal()' title='Edit' class='btn btn-sm ml-1 bg-amber-500 hover:bg-amber-600 text-white'>
                <svg xmlns='http://www.w3.org/2000/svg' fill='#fff' height='1em' viewBox='0 0 512 512'><path d='M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152V424c0 48.6 39.4 88 88 88H360c48.6 0 88-39.4 88-88V312c0-13.3-10.7-24-24-24s-24 10.7-24 24V424c0 22.1-17.9 40-40 40H88c-22.1 0-40-17.9-40-40V152c0-22.1 17.9-40 40-40H200c13.3 0 24-10.7 24-24s-10.7-24-24-24H88z'/></svg>
                </button>";
            echo "<button type='submit' name='deleteClinicalChemistry' title='delete' class='btn btn-sm ml-1 bg-red-500 hover:bg-red-600 text-white'>
                <span class='sr-only'>Delete Clinical Chemistry</span>
                <svg xmlns='http://www.w3.org/2000/svg' fill='#fff' height='1em' viewBox='0 0 448 512'><path d='M135.2 17.7C140.6 6.8 151.7 0 163.8 0H284.2c12.1 0 23.2 6.8 28.6 17.7L320 32h96c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 96 0 81.7 0 64S14.3 32 32 32h96l7.2-14.3zM32 128H416V448c0 35.3-28.7 64-64 64H96c-35.3 0-64-28.7-64-64V128zm96 64c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16z'/></svg>
            </button>";
        }
    } else {
        if($role == 1 || $role == 3) {
            echo "<button type='button' onClick='clinicalChemistryModal.showModal()' title='Create' class='btn btn-sm bg-green-700 hover:bg-green-800'>
                    <svg xmlns='http://www.w3.org/2000/svg' fill='#fff' height='14' width='12' viewBox='0 0 448 512'><path d='M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z'/></svg>
                </button>";
        }
    }
echo   '</div>';
echo "</li>";