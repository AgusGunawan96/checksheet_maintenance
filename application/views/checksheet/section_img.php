 <div class="form-group">
     <label for="image">Foto</label>
     <div class="img-fluid">
         <img src="<?= $detail['img_item']; ?>" alt="image" class="img-thumbnail">
     </div>
     <div class="row">
         <div class="col">
             <input type="file" id="img_item" class="form-control form-control-sm">
         </div>
         <input type="hidden" name="id_detail" value="<?= $detail['id']; ?>">
         <input type="hidden" name="img_item">

         <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
         <div class="col-3">
             <button type="button" class="btn btn-info pull-right btn-sm btn_upload_img_item"><i class='bx bxs-cloud-upload'></i></button>
         </div>
     </div>
 </div>

 <script>
     //create onchange event for input file img_item and change to base64 and append base64 to input hidden img_item
     $(document).on('change', '#img_item', function(e) {
         e.preventDefault();
         e.stopImmediatePropagation();
         var file = e.target.files[0];
         var reader = new FileReader();
         reader.onload = function(e) {
             var base64 = e.target.result;
             console.log(base64);
             $('input[name="img_item"]').val(base64);
         }
         reader.readAsDataURL(file);
     })
     $(document).on('click', '.btn_upload_img_item', function(e) {
         e.preventDefault();
         e.stopImmediatePropagation();
         // console.log($('#form_img_item').serializeArray());
         $.ajax({
             method: "post",
             url: "<?= site_url('checksheet/upload_img_item') ?>",
             dataType: 'json',
             data: {
                 img_item: $('input[name="img_item"]').val(),
                 id_detail: $('input[name="id_detail"]').val(),
                 _csrf: '<?= $this->security->get_csrf_hash(); ?>'
             },
             success: function(data) {
                 console.log(data);
             }
         })
     })
 </script>