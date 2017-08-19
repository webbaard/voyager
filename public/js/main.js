;(function($) {
    $(function(){
        var selector = "select.approver",
            template = $('.approver-item').first().clone();
        $(".add-Request").on("change",selector, function(){
            var allGood=true
            lastInputField=1
            $this = $(this);

            $this.closest("form").find(selector).each(function() {
                if ($(this).val() == "") {
                    allGood=false;
                    return false;
                }
                lastInputField++;
            });
            if (allGood) {
                var item = template.clone();
                item.find('label').attr('for', 'approver_'+lastInputField);
                item.find('select').attr('id', 'approver_'+lastInputField);
                $this.closest(".approver-wrap").append(item);
            }
        });
    });
})(jQuery);