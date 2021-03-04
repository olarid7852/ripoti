(function ($) {
  function toggleIcon($this, $id) {
    $this.on('click', function() {
        let field = $(this).siblings().find($id);
        if (field.attr("type") == "password") {
            field.attr("type", "text");
            $this.removeClass('fa-eye');
            $this.addClass('fa-eye-slash');
        } else {
            field.attr("type", "password");
            $this.addClass('fa-eye');
            $this.removeClass('fa-eye-slash');
        }
      
    })
  }

  toggleIcon($(".toggle-password"), "#input-pwd")
  toggleIcon($(".toggle-confirm-password"), "#confirm-pwd")  

})(jQuery);