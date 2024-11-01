jQuery(function($){

  // overlay/dialog
  function resizeOverlay(){
    $('.sns-overlay').css('height',$(window).height() + 'px');
    var shortcodeBox = $('.sns-slider-shortcode');
    var shortcodeBoxPos = shortcodeBox.position();
    $('.sns-shortcode-message').css({'position':'absolute','left':shortcodeBoxPos.left + 1 + 'px','top':shortcodeBoxPos.top + 1 + 'px','height':shortcodeBox.outerHeight() - 5 + 'px','width':shortcodeBox.outerWidth() - 2 + 'px'});
  }
  resizeOverlay();

  $(window).resize(function(){
    resizeOverlay();
  });

  $('.sns-dialog-close').click(function(){
    fadeOutDialog();
  });

  $('.sns-builder-wrapper').sortable({
    handle: '.sns-image-header',
    update: function(){ writeSaveableHTML(); }
  });

  function writeSaveableHTML() {
    $('#sns-admin-html').text( $('.sns-builder-wrapper').html().trim().replace('toggle-open','').replace('style="display: block;"','') );
    var outputHtml = '';
    $('.sns-slider-image').each(function(){
      if ($(this).find('.sns-image-url').val().length !== 0) {
        outputHtml += '<div class="sns-slider-image">';
        if ($(this).find('.sns-image-link').val().length !== 0){
          outputHtml += '<a href="' + $(this).find('.sns-image-link').val() + '"';
          if( $(this).find('.sns-link-new').prop('checked') ) {
            outputHtml += ' target="_blank" ';
          }
          outputHtml += '>';
        }
        outputHtml +=     '<img src="' + $(this).find('.sns-image-url').val() + '" alt="' + $(this).find('.sns-image-title').val() + '" />';
        if ($(this).find('.sns-overlay-pos').val() != 'hidden'){
          outputHtml += '<div class="sns-overlay sns-overlay-' + $(this).find('.sns-overlay-pos').val() + '">';
                        if ( $(this).find('.sns-image-title').val() !== ''){
            outputHtml += '<h4 class="sns-overlay-title">' +
                            $(this).find('.sns-image-title').val() +
                          '</h4>';
                        }
                        if ( $(this).find('.sns-image-desc').val() !== ''){
            outputHtml += '<p class="sns-overlay-desc">' +
                            $(this).find('.sns-image-desc').val() +
                          '</p>';
                        }
          outputHtml += '</div>';
        }
        outputHtml +=   '</a>' +
                      '</div>';
      }
    });
    $('#sns-output-html').text( outputHtml );
  }

  var snsUploadLink = $('.sns-upload-link').text();

  var snsSectionHtml = '<div class="sns-slider-image">' +
                          '<div class="sns-image-header">' +
                            '<div class="sns-image-tools">' +
                              '<span class="sns-image-edit dashicons dashicons-edit"></span>' +
                              '<span class="sns-image-clone dashicons dashicons-admin-page"></span>' +
                            '</div>' +
                            '<span class="sns-image-delete dashicons dashicons-no"></span>' +
                            '<h3 class="sns-block-title">New Image</h3>' +
                          '</div>' +
                          '<div class="sns-builder-subsection">' +
                            '<div class="sns-image-preview"></div>' +
                            '<label for="sns-image-title">Title</label>' +
                            '<br />' +
                            '<input type="text" class="sns-image-title" name="sns-image-title" />' +
                            '<br />' +
                            '<label for="sns-image-desc">Description</label>' +
                            '<br />' +
                            '<textarea type="text" class="sns-image-desc" name="sns-image-desc" />' +
                            '<br />' +
                            '<label for="sns-image-url">Image</label>' +
                            '<br />' +
                            '<input type="text" class="sns-image-url" name="sns-image-url" placeholder="Image URL" />' +
                            '<br />' +
                            '<a class="sns-image-upload button" href="' + snsUploadLink + '">Upload/Select</a>' +
                            '<br />' +
                            '<label for="sns-image-link">Link</label>' +
                            '<br />' +
                            '<input type="text" class="sns-image-link" name="sns-image-link" placeholder="e.g. https://www.google.com" />' +
                            '<br />' +
                            '<input type="checkbox" class="sns-link-new" name="sns-link-new" /> Open in a new tab' +
                            '<br />' +
                            '<label for="sns-overlay-pos">Overlay Position</label>' +
                            '<br />' +
                            '<select type="text" class="sns-overlay-pos" name="sns-overlay-pos">' +
                              '<option value="hidden" selected>Hidden</option>' +
                              '<option value="top">Top</option>' +
                              '<option value="middle">Middle</option>' +
                              '<option value="bottom">Bottom</option>' +
                            '</select>' +
                          '</div>' +
                        '</div>';

  // put the admin_html in the builder wrapper on page load
  $('.sns-builder-wrapper').html( $('#sns-admin-html').text() );

  // add slider image block button
  $('.sns-add-slider-image').click(function(){
    $('.sns-builder-wrapper').append( snsSectionHtml );
    $('.toggle-open').removeClass('toggle-open').children('.sns-builder-subsection').slideUp();
    $('.sns-slider-image').last().addClass('toggle-open').children('.sns-builder-subsection').slideDown();
    toggleTooltip();
    writeSaveableHTML();
  });

  // Delete row btn
  $('.sns-image-delete').live('click', '.dashicons', function(e){
    if (e.altKey) {
      $(this).parent().parent().remove();
      writeSaveableHTML();
      toggleTooltip();
    } else {
      $(this).parent().parent().addClass('sns-row-to-delete');
      fadeInDialog();
    }
    e.stopPropogation();
  });

  // Clone block button
  $('.sns-image-clone').live('click',function(e){
    var blockHtml = $(this).closest('.sns-slider-image')[0].outerHTML;
    $(this).closest('.sns-slider-image').after(blockHtml.replace('toggle-open','').replace('style="display: block;"',''));
    writeSaveableHTML();
    e.stopPropogation();
  });

  // function to fade in overlay/dialog
  function fadeInDialog(){
    $('.sns-overlay').fadeIn();
  }

  // function to fade out overlay/dialog
  function fadeOutDialog(){
    $('.sns-overlay').fadeOut();
    $('.sns-row-to-delete').removeClass('sns-row-to-delete');
    toggleTooltip();
  }

  // overlay/dialog accept
  $('.sns-dialog-accept').click(function(){
    $('.sns-row-to-delete').remove();
    fadeOutDialog();
  });

  // overlay/dialog deny
  $('.sns-dialog-deny').click(function(){
    fadeOutDialog();
  });

  // fadeout overlay/dialog on overlay Click
  $('.sns-overlay-bg').live('click',function(){
    fadeOutDialog();
  });

  // toggle tooltip function
  $('.sns-no-images-tip').hide();
  function toggleTooltip(){
    if ( $('.sns-slider-image').length === 0 ) {
      $('.sns-no-images-tip').fadeIn();
    } else {
      $('.sns-no-images-tip').fadeOut();
    }
  }
  toggleTooltip();

  // keep a var to indicate whether the cursor is over an icon (to fix opening accordion on icon click)
  var mouseOverIcon = 0;

  $('.sns-image-clone, .sns-image-handle, .sns-image-delete').mouseover(function(){
    mouseOverIcon = 1;
  });

  $('.sns-image-clone, .sns-image-handle, .sns-image-delete').mouseout(function(){
    mouseOverIcon = 0;
  });

  // Open/close (accordion) subsections
  $('.sns-image-header').live('click', function(){
    if (mouseOverIcon === 0){
      var imageBlock = $(this).closest('.sns-slider-image');
      var subSection = $(this).closest('.sns-image-header').siblings('.sns-builder-subsection');
      var openBlock = $('.toggle-open');
      var openSub = $('.toggle-open').children('.sns-builder-subsection');
      if (imageBlock.hasClass('toggle-open')) {
        imageBlock.removeClass('toggle-open');
        subSection.slideUp();
      } else {
        openSub.slideUp();
        openBlock.removeClass('toggle-open');
        imageBlock.addClass('toggle-open');
        subSection.slideDown();
      }
    }
  });

  // Update select option selected attrs (for saving dropdowns)
  $('.sns-slider-image select').live('change keyup',function(){
    var selected = $(this).val();
    var selectedIndex = $(this).children('option[value='+selected+']').index();
    $(this).children('option').removeAttr('selected').eq(selectedIndex).attr('selected','');
    writeSaveableHTML();
  });

  // update text input values (for saving)
  $('.sns-slider-image input[type="text"]').live('keyup change paste',function(){
    $(this).attr("value",$(this).val());
  });

  // update textarea values (for saving)
  $('textarea').live('keyup change paste',function(){
    $(this).text($(this).val());
    writeSaveableHTML();
  });

  // update checkbox values (for saving)
  $('.sns-slider-image input[type="checkbox"]').live('change click',function(){
    if( $(this).prop('checked') ){
      $(this).attr('checked','');
    } else {
      $(this).removeAttr('checked');
    }
  });

  // write saveables on any input change
  $('.sns-slider-image input').live('keyup change paste',function(){
    writeSaveableHTML();
  });

  // update block titles
  $('.sns-image-title').live('change paste keyup',function(){
    $(this).parent().siblings('.sns-image-header').children('.sns-block-title').text( $(this).val() );
  });

  // populate image previews on page Load
  $('.sns-image-preview').each(function(){
    $(this).css('background-image','url(' + $(this).siblings('.sns-image-url').val() + ')');
  });

  // media uploader
  var frame;
  var metabox;
  var imgContainer;

  $('.sns-image-upload').live('click',function(e){

    e.preventDefault();
    metabox = $(this).siblings('.sns-image-url');
    imgContainer = $(this).siblings('.sns-image-preview');

    if ( frame ) {
      frame.open();
      return;
    }

    frame = wp.media({
      title: 'Select or upload an image',
      multiple: false
    });

    // When an image is selected in the media frame...
    frame.on( 'select', function() {

      // Get media attachment details from the frame state
      var attachment = frame.state().get('selection').first().toJSON();

      // Send the attachment URL to our custom image input field.
      imgContainer.css( 'background-image', 'url('+attachment.url+')' );

      metabox.val(attachment.url);
      metabox.attr("value",attachment.url);
      writeSaveableHTML();

    });

    frame.open();

  });

  // update image preview if url is entered manually (not via uploader)
  $('.sns-image-url').live('change paste keyup',function(){
    $(this).siblings('.sns-image-preview').css( 'background-image', 'url('+$(this).val()+')' );
    writeSaveableHTML();
  });

  // update shortcode on options change
  $('#sns-side input, #sns-side select').live('change paste keyup',function(){

    var shortcode = '[swish-slider id="' + $('#sns-post-id').val() + '"';

    if ( $('#sns-style-select').val() !== 'default' ) {
      shortcode += ' style="' + $('#sns-style-select').val() + '" ';
    }

    if ( $('#sns-arrows-toggle').val() !== 'true' ) {
      shortcode += ' arrows="' + $('#sns-arrows-toggle').val() + '" ';
    }

    if ( $('#sns-dots-toggle').val() !== 'true' ) {
      shortcode += ' dots="' + $('#sns-dots-toggle').val() + '" ';
    }

    if ( $('#sns-overlays-toggle').val() !== 'true' ) {
      shortcode += ' overlays="' + $('#sns-overlays-toggle').val() + '" ';
    }

    if ( $('#sns-autoplay-toggle').val() !== 'true' ) {
      shortcode += ' autoplay="' + $('#sns-autoplay-toggle').val() + '" ';
    }

    if ( $('#sns-autoplay-speed').val() !== '3000' ) {
      shortcode += ' autoplayspeed="' + $('#sns-autoplay-speed').val() + '" ';
    }

    if ( $('#sns-loop-toggle').val() !== 'true' ) {
      shortcode += ' loop="' + $('#sns-loop-toggle').val() + '" ';
    }

    if ( $('#sns-slider-speed').val() !== '500' ) {
      shortcode += ' speed="' + $('#sns-slider-speed').val() + '" ';
    }

    if ( $('#sns-swipe-toggle').val() !== 'true' ) {
      shortcode += ' swipe="' + $('#sns-swipe-toggle').val() + '" ';
    }

    if ( $('#sns-num-slides').val() !== '1' ) {
      shortcode += ' slides="' + $('#sns-num-slides').val() + '" ';
    }

    if ( $('#sns-fade-toggle').val() !== 'false' ) {
      shortcode += ' fade="' + $('#sns-fade-toggle').val() + '" ';
    }

    if ( $('#sns-lazy-load').val() !== 'off' ) {
      shortcode += ' lazyload="' + $('#sns-lazy-load').val() + '" ';
    }

    shortcode += ']';

    shortcode = shortcode.replace(' ]',']');

    $('#sns-side .sns-slider-shortcode').val(shortcode).attr('value',shortcode);

  });

  // copy shortcode button
  $('.sns-copy-shortcode').click(function(){
    var temp = $('<input type="text">');
    $('body').append(temp);
    temp.val($('.sns-slider-shortcode').val()).select();
    document.execCommand("copy");
    temp.remove();
    $('.sns-shortcode-message').show().delay(1000).fadeOut('slow');
  });

});
