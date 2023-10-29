<select id="organizationId" data-label="Organization" required>
    <?php 
        if ($orgResult !== false && $orgResult->num_rows > 0) {
            echo "<option value='' selected disabled>Select</option>";
            while($org = $orgResult->fetch_assoc()) {
                echo "<option value='" . $org['id'] . "' " . 
                        ( 
                            (isset($_POST['organizationId'])) 
                            ? (($_POST['organizationId'] == $org['id']) ? 'selected' : '') 
                            : '' 
                        )  
                    . " >" . $org['name'] . "</option>";
            }
        } else {
            echo "<option value='null' selected disabled>No record</option>";

        }
    ?>
</select>