
            

            

            <div
				class="absolute opacity-40 inset-x-0 bottom-0 top-[calc(100%-13rem)] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[calc(100%-30rem)]"
				aria-hidden="true">
				<div class="relative left-[calc(50%+3rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 bg-gradient-to-tr from-[#fbbf24] to-[#15803d] opacity-40 sm:left-[calc(50%+36rem)] sm:w-[72.1875rem]"
					style="clipPath: 'polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)'," >
                </div>
			</div>
		</div>
        <footer class="footer footer-center bottom-0 p-4 bg-white shadow-sm text-base-content">
                <aside>
                    <p>Copyright © 2023 - All right reserved by Global Health Diagnostics</p>
                </aside>
        </footer>




        <button class="btn" onclick="prompConfirmModal.showModal()">open modal</button>
        <dialog id="prompConfirmModal" class="modal">
            <div class="modal-box">
                <h3 class="prompt-header font-bold text-lg">Confirm</h3>
                <p class="py-4">Are you sure want to continue?</p>
                <div class="modal-action">
                <form method="dialog">
                    <!-- if there is a button in form, it will close the modal -->
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
            $("[type='submit']").click(function(){
                let nodeName = $(this).prop('nodeName').toLowerCase();
                
                promptBgColor = $(this).css('background-color');
                promptTextColor = $(this).css('color');
                promptButtonText = 
                    nodeName == 'button'
                        ? $(this).text()
                        : nodeName == 'input'
                        ? $(this).val()
                        : 'Confirm';
            });

            $("#prompConfirmModal .prompt-button-yes").hover(
                function() {
                    $(this).css("background-color", darkenColor(promptBgColor, 10));
                },
                function() {
                    $(this).css("background-color", promptBgColor);
                }
            );

            $(".prompt-confirm").on("submit", function(e) {                
                e.preventDefault();

                let modal = $("#prompConfirmModal");
                // let actionText = modal.find(".prompt-action-text");
                let buttonYes = modal.find(".prompt-button-yes");
                let headerText = modal.find(".prompt-header");
                
                headerText.text(promptButtonText);

                buttonYes.css({
                    'background-color': promptBgColor,
                    'color': promptTextColor,
                })

                headerText.css({
                    'color': promptBgColor,
                })

                prompConfirmModal.showModal();

            })

            function darkenColor(rgbColor, percent) {
                // Extract RGB values
                var rgbValues = rgbColor.match(/\d+/g);

                // Calculate darker color
                var r = Math.round(rgbValues[0] * (100 - percent) / 100);
                var g = Math.round(rgbValues[1] * (100 - percent) / 100);
                var b = Math.round(rgbValues[2] * (100 - percent) / 100);

                // Construct new RGB color
                var newColor = "rgb(" + r + ", " + g + ", " + b + ")";

                return newColor;
            }
        </script>

        <?php 
        $serverName = $_SERVER['SERVER_NAME']; // or $_SERVER['HTTP_HOST']
        if ($serverName === 'localhost' || $serverName === '127.0.0.1') {
            echo '<script src="'. base_url(false) .'/js/jquery.dataTables.min.js"></script>';
        } else {
            echo '<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>';
        }
        ?>
        
        <script>
            let dTable = new DataTable('table', {
                pageLength: 25
            }); 
        </script>
</body>
</html>