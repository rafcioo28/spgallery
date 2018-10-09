jQuery(document).ready(($) => {
  var mediaUploader;
  $('#spgallery_upload_button').click((e) => {
    e.preventDefault();
      if (mediaUploader) {
      mediaUploader.open();
      return;
    }
    mediaUploader = wp.media({
      title: 'Wybierz zdjęcia',
      button: {
      text: 'Wybór wieli z wciśniętym klawiszem CTRL'
    }, multiple: true });
    mediaUploader.on('select', () => {
      var innerHTML = '';
      var mediaIDs = [];
      var attachment = mediaUploader.state().get('selection').toJSON();
      attachment.forEach((mediaFile) => {
        innerHTML += '<img src="' + mediaFile.sizes.thumbnail.url  + '">';
        mediaIDs.push(mediaFile.id);
        //console.log(innerHTML);
      });
      console.log(mediaIDs.join());
      $("#spgallery_media_ids").val(mediaIDs.join());
      $("#sgallery-thumb").html(innerHTML);
      
      //console.log(JSON.stringify(attachment, null, 4));
      //$('#spgallery_media').val(attachment.url);
    });
    mediaUploader.open();
  });
  
  $("#sgallery-thumb img").click((e) =>{
    e.preventDefault();
    var mediaID = e.target.id.substring(6);
    var mediaIDs = $("#spgallery_media_ids").val().split(",");
    mediaIDs = mediaIDs.filter((val) => {
      return val != mediaID;
    });
    $("#spgallery_media_ids").val(mediaIDs.join());
    $(e.target).remove();
    
  });
  
});