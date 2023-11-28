<dialog id="prompConfirmModal" class="modal">
    <div class="modal-box">
        <h3 class="prompt-header font-bold text-lg">Confirm</h3>
        <p class="py-4">Are you sure want to continue?</p>
        <div class="modal-action">
        <form method="dialog">
            <button class="prompt-button-no btn">No</button>
            <button class="prompt-button-yes btn">Yes</button>
        </form>
        </div>
    </div>
</dialog>

<script>
    var promptBgColor = '';
    var promptTextColor = '';
    var promptButtonText = '';
    var formSubmitReady = false;

    $("[type='submit']").click(function(){
        let nodeName = $(this).prop('nodeName').toLowerCase();
        
        $("[type='submit']").not(this).removeClass("clicked");
        $(this).addClass("clicked");

        promptBgColor = $(this).css('background-color');
        promptTextColor = $(this).css('color');
        promptButtonText = 
            nodeName == 'button'
                ? $(this).text()
                : nodeName == 'input'
                ? $(this).val()
                : 'Confirm';
    });

    $(".prompt-confirm").on("submit", function(e) {                
        let form = $(this);
        let modal = $("#prompConfirmModal");
        let buttonYes = modal.find(".prompt-button-yes");
        let headerText = modal.find(".prompt-header");
        let loaderText = $(".page-loader p");
        
        headerText.text(promptButtonText);
        loaderText.text("Processing");

        buttonYes.css({
            'background-color': promptBgColor,
            'color': promptTextColor,
        })

        headerText.css({
            'color': promptBgColor,
        })

        if(!formSubmitReady && !form.hasClass('form-error')) {
            prompConfirmModal.showModal();
            e.preventDefault();
        }

        $("#prompConfirmModal .prompt-button-yes").click(function(){
            formSubmitReady = true;
            $(".clicked[type='submit']").trigger("click");
            $("body").addClass("is-loading")
        });

        $("#prompConfirmModal .prompt-button-yes").hover(
            function() {
                $(this).css("background-color", darkenColor(promptBgColor, 10));
            },
            function() {
                $(this).css("background-color", promptBgColor);
            }
        );
    })
</script>